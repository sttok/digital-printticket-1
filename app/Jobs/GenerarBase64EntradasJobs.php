<?php

namespace App\Jobs;

use App\Models\Notification;
use Exception;
use App\Models\OrderChild;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class GenerarBase64EntradasJobs implements ShouldQueue
{
    public $tries = 5;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $id_entrada;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id_entrada)
    {
       $this->id_entrada = $id_entrada;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $entradas = OrderChild::where('ticket_id', $this->id_entrada)->get();
        
            foreach ($entradas as $ent) {
                $ent->ticket_hash3 = base64_encode($ent->ticket_number);
                $ent->ticket_hash4 = base64_encode($ent->ticket_number2);
                $ent->update();
            }
            DB::commit();
            $en = Ticket::where('id', $this->id_entrada)->first();
            $noti = new Notification;
            $noti->evento_id = $en->event_id;
            $noti->entrada_id = $this->id_entrada;
            $noti->title = "Encriptacion base 64";
            $noti->message = "La entrada " . $en->ticket_number . " ya fue generada la encriptacion base64";
            $noti->estado = 0;
            $noti->save();
        }catch (Exception $e) {
            DB::rollBack();
            $en = Ticket::where('id', $this->id_entrada)->first();
            $noti = new Notification;
            $noti->evento_id = $en->event_id;
            $noti->entrada_id = $this->id_entrada;
            $noti->title = "Ha ocurrido un error en Encriptacion base 64";
            $noti->message =  $e->getMessage();
            $noti->estado = 5;
            $noti->save();
           
        }
       
    }
}
