<?php

namespace App\Http\Livewire;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Ticket;
use App\Models\Setting;
use Livewire\Component;
use App\Models\OrderChild;
use Illuminate\Support\Str;
use App\Models\EventScanner;
use Livewire\WithPagination;
use App\Models\EventPuntoVenta;
use Illuminate\Support\Facades\DB;
use App\Exports\EntradasExcelExport;


use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ApiController;

class EventosLivewire extends Component
{
    use WithPagination;
    // protected $listeners = ['reiniciarentrada', 'borrado', 'cancelado', 'recordar'];  
    protected $paginationTheme = 'bootstrap';
    public $readytoload = false;
    public $eventos = [];
    public $search = '', $search_estado = '', $search_desde = '', $search_hasta = '';
    // public $evento_id, $entradas_descargar = [], $descargartodoexcel = false;
    // public $numeros_notificar, $numeros = [], $recordar_organizador = false, $recordar_punto_venta = false, $recordar_escaner = false;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'buscar'],
        'search_desde' => ['except' => '', 'as' => 'desde'],
        'search_hasta' => ['except' => '', 'as' => 'hasta'],
        'search_estado' => ['except' => '', 'as' => 'estado'],
    ];
    

    public function render()
    {        
        if($this->readytoload){
            $this->dispatchBrowserEvent('cargarimagen');
        }
        return view('livewire.eventos.eventos-livewire');
    }

    public function loadDatos(){
        $this->readytoload = true;
        $this->dispatchBrowserEvent('cargarimagen');
    }

    public function recordardatos($id){
        $this->evento_id = $id;
        $this->reset([
            'numeros_notificar', 'recordar_organizador', 'recordar_punto_venta', 'recordar_escaner'
        ]);
    }

   

    public function limpiar(){
        $this->reset([
            'search', 'search_estado', 'search_desde', 'search_hasta'
        ]);
    }

    

    public function updatedSearch(){
        $this->resetPage();
    }

    public function updatedSearchEstado(){
        $this->resetPage();
    }

    public function updatedSearchDesde(){
        $this->resetPage();
    }

    public function updatedSearchHasta(){
        $this->resetPage();
    }

    public function getEventosProperty(){
        if($this->search_hasta != ''){
            $this->eventos = Event::where([
                ['name', 'LIKE', '%'.$this->search.'%'], ['is_deleted', 0], ['status', 'LIKE', '%'.$this->search_estado], ['start_time', '>=', $this->search_desde], ['end_time', '<=', $this->search_hasta]
             ])->orderBy('id', 'desc')->paginate(12);
        }else{
            $eventos = Event::where([
                ['name', 'LIKE', '%'.$this->search.'%'], ['is_deleted', 0], ['status', 'LIKE', '%'.$this->search_estado], ['start_time', '>=', $this->search_desde]
             ])->orderBy('id', 'desc')->paginate(12);
        }
       
        return $eventos;
    }

    public function getEventoProperty(){
        return Event::findorfail($this->evento_id);
    }

    public function getEntradasProperty(){
        return Ticket::where('event_id', $this->evento_id)->get();
    }

    public function getSettingProperty()
    {
        return Setting::findorfail(1);
    }
   
}
