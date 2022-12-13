<?php

namespace App\Http\Livewire\Misentradas\Nuevo;

use App\Models\Event;
use Livewire\Component;
use App\Models\EventPuntoVenta;
use App\Models\OrderChildsDigital;
use Illuminate\Support\Facades\Auth;

class TodosLivewire extends Component
{
    public function render()
    {
        return view('livewire.misentradas.nuevo.todos-livewire');
    }

    public function abrirentradas($id){
        return redirect()->route('mis.eventos.show', $id);
    }

    public function getFoldersProperty(){
        if (Auth::user()->hasRole('punto venta')) {
            $event_punto_ventas = EventPuntoVenta::where('punto_id', Auth::user()->id)->get();

            $idd = array();
            foreach ($event_punto_ventas as $epv) {
               $idd[] = $epv->event_id;
            }

            $eventos = Event::whereIn('id', $idd)
            ->where([ ['is_deleted', 0], ['status', ['1', '2']] ])->get();
        }else{
            $eventos = Event::where([
                ['organizador_id', Auth::user()->id], ['is_deleted', 0], ['status', ['1', '2']]
            ])->get();
        }
        

        $evenn = [];
        foreach ($eventos as $event) {
            $digital = OrderChildsDigital::where('evento_id', $event->id)->get();
            if(count($digital) > 0){
                $evenn[] = array(
                    'id' => $event->id,
                    'nombre' => $event->name,
                    'imagen' => $event->image,
                    'fecha' => $event->start_time,
                    'contador' => count($digital),
                    'total' => $event->ticket_digital->sum('quantity'),
                    'estado' => 1
                );
            }else{
                $evenn[] = array(
                    'id' => $event->id,
                    'nombre' => $event->name,
                    'imagen' => $event->image,
                    'fecha' => $event->start_time,
                    'contador' => count($digital),
                    'total' => $event->ticket_digital->sum('quantity'),
                    'estado' => 0
                );
            }
        }
        return collect($evenn)->sortByDesc('estado');
    }
}
