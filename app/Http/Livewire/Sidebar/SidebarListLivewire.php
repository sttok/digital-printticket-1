<?php

namespace App\Http\Livewire\Sidebar;

use App\Models\Event;
use Livewire\Component;
use App\Models\EventPuntoVenta;
use App\Models\OrderChildsDigital;
use Illuminate\Support\Facades\Auth;

class SidebarListLivewire extends Component
{
    
    public function render()
    {
        return view('livewire.sidebar.sidebar-list-livewire');
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
                    'contador' => count($digital),
                    'total' => $event->people,
                    'estado' => 1,
                    'entradas' => $event->ticket_digital
                );
            }else{
                $evenn[] = array(
                    'id' => $event->id,
                    'nombre' => $event->name,
                    'contador' => count($digital),
                    'total' => $event->people,
                    'estado' => 0
                );
            }
        }
        return $evenn;
    }

   
}