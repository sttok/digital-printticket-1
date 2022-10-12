<?php

namespace App\Jobs;

use Exception;
use App\Models\OrderChild;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use App\Models\OrderChildsDigital;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class GuardarEntradasJobs implements ShouldQueue
{
    public $tries = 5;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
       $this->data = $data;
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
            $identificador = Str::before($this->data['name'], '-');
            $ent = OrderChild::where([
                ['ticket_id', $this->data['entrada_id']], ['identificador', (int)$identificador]
            ])->first();
            if(!empty($ent)){
                $r = OrderChildsDigital::where([
                    ['evento_id', $this->data['evento_id']], ['zona_id', $this->data['entrada_id']], ['identificador', $identificador]
                ])->first();
                if(empty($r)){
                    $r = new OrderChildsDigital();
                    $r->admin_id = 1;
                    $r->evento_id = $this->data['evento_id'];
                    $r->zona_id =  $this->data['entrada_id'];
                    $r->identificador = $identificador;
                    $r->order_child_id = $ent->id;
                    $r->url = $this->data['path'];
                    $r->provider = 'drive';
                    $r->descargas = 0;
                    $r->permiso_descargar = 0;
                    $r->save();
                }else{
                    $r->url = $this->data['path'];
                    $r->update();
                }
            }else{
                $noti = new Notification;
                $noti->evento_id = $this->data['evento_id'];
                $noti->entrada_id = $this->data['entrada_id'];
                $noti->title = "Error registro de entradas digital " . $this->data['name'];
                $noti->message =  "Ha ocurrido un error en el registro de entrada digital data: ". json_encode($this->data) ;
                $noti->estado = 5;
                $noti->save();
            }
            DB::commit();
        }catch (Exception $e) {
            DB::rollBack();
            $noti = new Notification;
            $noti->evento_id = $this->data['evento_id'];
            $noti->entrada_id = $this->data['entrada_id'];
            $noti->title = "Error registro de entradas digital " . $this->data['name'];
            $noti->message =  $e->getMessage();
            $noti->estado = 5;
            $noti->save();
        }
    }
}
