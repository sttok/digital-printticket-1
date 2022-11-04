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
   
    public $numero_telefono, $array_telefono_notificar = [];
    public $recordarscanner = true, $recordarpuntoventa = true, $recordarorganizador = true;

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

    public function abrirdatos($id){
        $this->evento_id = $id;
        $this->dispatchBrowserEvent('abrridatos');
    }

    public function agregartelefonos(){
        $this->validate([
            'numero_telefono' => 'required|phone:CO,AUTO'
        ]);

        $key = array_search($this->numero_telefono, array_column($this->array_telefono_notificar, 'numero'));

        if(empty($key)){
            $this->array_telefono_notificar[] = array(
                'id' => Str::random(5),
                'numero' => $this->numero_telefono
            ); 
            $this->reset('numero_telefono');
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('El numero ya se encuentra agregado')]);
        }
       
    }

    public function borrartelefono($id){
        $key = array_search($id, array_column($this->array_telefono_notificar, 'id'));
        unset($this->array_telefono_notificar[$key]);
    }

    public function recordardatos(){
        $this->validate([
            'array_telefono_notificar' => 'required|array|min:1',
            'recordarscanner' => 'required|boolean',
            'recordarpuntoventa' => 'required|boolean',
            'recordarorganizador' => 'required|boolean'
        ]);

        if($this->recordarscanner == true || $this->recordarpuntoventa == true || $this->recordarorganizador == true && count($this->array_telefono_notificar) > 0){
            DB::beginTransaction();
            try {
                $evento = Event::find($this->evento_id);
                if ($this->recordarorganizador == true) {
                    $organizador = $evento->organizador;
                }

                if($this->recordarpuntoventa == true){
                    $puntos_ventas = $evento->eventpuntoventa;
                }

                if($this->recordarscanner == true){
                    $scanners = EventScanner::where('event_id', $evento->id)->get();
                }

                foreach ($this->array_telefono_notificar as $telefono) {
                    if ($this->recordarorganizador == true) {
                        $new_pass_organizador = Str::upper(Str::random(6));
                        $organizador->password = Hash::make($new_pass_organizador);
                        $organizador->update();
                        $data = array(
                            'telefono' => $telefono['numero'],
                            'mensaje' =>urlencode(Str::upper($this->setting->app_name) . ': ') . '%0d%0a' .
                            urlencode('Usuario: ') . urlencode($organizador->first_name . ' ' . $organizador->last_name) . '%0d%0a' .
                            urlencode('Telefono de acceso: ') . urlencode($organizador->phone) . '%0d%0a' .
                            urlencode('Contraseña: ') . urlencode($new_pass_organizador) . '%0d%0a' .
                            urlencode('¡Su contraseña como organizador ha sido reestablecida!') . '%0d%0a' 
                        );
                        $data = (new ApiController)->sendsms($data);
                    }

                    if($this->recordarpuntoventa == true){
                        foreach ($puntos_ventas as $punto) {
                            $user = User::findorfail($punto->punto_id);
                            $pass = Str::upper(Str::random(6));
                            $user->password = Hash::make($pass);
                            $user->update();
                            $data = array(
                                'telefono' => $telefono['numero'],
                                'mensaje' =>urlencode(Str::upper($this->setting->app_name) . ': ') . '%0d%0a' .
                                urlencode('Usuario: ') . urlencode($user->first_name . ' ' . $user->last_name) . '%0d%0a' .
                                urlencode('Telefono de acceso: ') . urlencode($user->phone) . '%0d%0a' .
                                urlencode('Contraseña: ') . urlencode($pass) . '%0d%0a' .
                                urlencode('¡Su contraseña como punto de venta ha sido reestablecida!') . '%0d%0a' 
                            );
                            $data = (new ApiController)->sendsms($data);
                        }
                    }

                    if($this->recordarscanner == true){
                        foreach ($scanners as $item) {
                            $user = User::findorfail($item->scanner_id);
                            $pass = Str::upper(Str::random(6));
                            $user->password = Hash::make($pass);
                            $user->update();
                            $data = array(
                                'telefono' => $telefono['numero'],
                                'mensaje' =>urlencode(Str::upper($this->setting->app_name) . ': ') . '%0d%0a' .
                                urlencode('Usuario: ') . urlencode($user->first_name . ' ' . $user->last_name) . '%0d%0a' .
                                urlencode('Telefono de acceso: ') . urlencode($user->phone) . '%0d%0a' .
                                urlencode('Contraseña: ') . urlencode($pass) . '%0d%0a' .
                                urlencode('¡Su contraseña como scanner ha sido reestablecida!') . '%0d%0a' 
                            );
                            $data = (new ApiController)->sendsms($data);
                        }
                    }
                }
                DB::commit();
                $this->reset(['array_telefono_notificar', 'recordarscanner', 'recordarpuntoventa', 'recordarorganizador']);
                $this->dispatchBrowserEvent('reiniciado');
                $this->dispatchBrowserEvent('cerrardatos');
            } catch (Exception $e) {
                DB::rollBack();
                $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
            }
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('Debe seleccionar una opcion o ingresar al menos un nuúmero de telefono')]);
        }
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
            return Event::with('organizador')->where(function ($query){
                $query->select('tipo')
                    ->from('tickets')
                    ->whereColumn('tickets.event_id', 'events.id')
                    ->where('tickets.categoria', 2)
                    ->limit(1);
            }, '1')->where([
                ['name', 'LIKE', '%'.$this->search.'%'], ['is_deleted', 0], ['status', 'LIKE', '%'.$this->search_estado], ['start_time', '>=', $this->search_desde], ['end_time', '<=', $this->search_hasta]
             ])->orderBy('id', 'desc')->paginate(12);
        }else{
            return Event::with('organizador')->where(function ($query){
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
