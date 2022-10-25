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
use App\Models\DigitalOrdenCompra;
use App\Models\DigitalOrdenCompraDetalle;
use App\Models\OrderChildsDigital;

class EventosLivewire extends Component
{
    use WithPagination;
    protected $listeners = ['reiniciarentrada'];
    protected $paginationTheme = 'bootstrap';
    public $readytoload = false;
    public $eventos = [];    public $search = '', $search_estado = '', $search_desde = '', $search_hasta = '';
   
    public $evento_id;

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

    public function reiniciarentradas($id){
        $this->evento_id = $id;
        $this->dispatchBrowserEvent('reiniciar');
    }

    public function reiniciarentrada(){
        DB::beginTransaction();
        try {
            $ordenes = DigitalOrdenCompra::where('evento_id', $this->evento_id)->get();
                foreach($ordenes as $orden){
                    $detalles = DigitalOrdenCompraDetalle::where('digital_orden_compra_id', $orden->id)->get();
                    foreach ($detalles as $detalle) {
                        $entrada = OrderChild::find($detalle->order_child_id);
                        if($entrada){
                            $entrada->customer_id = null;
                            $entrada->endosado_id = null;
                            $entrada->update();
                        }
                        $entrada_digital = OrderChildsDigital::find($detalle->digital_id);
                        if($entrada){
                            $entrada_digital->endosado = 0;
                            $entrada_digital->update();
                        }
                        $detalle->delete();
                    }
                    $orden->delete();
                }
            DB::commit();
            $this->dispatchBrowserEvent('reiniciado');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
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
            return Event::where(function ($query){
                $query->select('tipo')
                    ->from('tickets')
                    ->whereColumn('tickets.event_id', 'events.id')
                    ->where('tickets.categoria', 2)
                    ->limit(1);
            }, '1')->where([
                ['name', 'LIKE', '%'.$this->search.'%'], ['is_deleted', 0], ['status', 'LIKE', '%'.$this->search_estado], ['start_time', '>=', $this->search_desde], ['end_time', '<=', $this->search_hasta]
             ])->orderBy('id', 'desc')->paginate(12);
        }else{
            return Event::where(function ($query){
                $query->select('tipo')
                    ->from('tickets')
                    ->whereColumn('tickets.event_id', 'events.id')
                    ->where('tickets.categoria', 2)
                    ->limit(1);
            }, '1')->where([
                ['name', 'LIKE', '%'.$this->search.'%'], ['is_deleted', 0], ['status', 'LIKE', '%'.$this->search_estado], ['start_time', '>=', $this->search_desde]
            ])->orderBy('id', 'desc')->paginate(12);
        }
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
