<?php

namespace App\Http\Livewire\Misentradas;

use App\Exports\DescargarInformeExport;
use Exception;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\AppUser;
use App\Models\Setting;
use Livewire\Component;
use App\Models\OrderChild;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Models\DigitalOrdenCompra;
use App\Models\OrderChildsDigital;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ApiController;
use App\Http\Traits\DetalleLivewireTrait;
use App\Models\DigitalOrdenCompraDetalle;

class DetalleLivewire extends Component
{
    use DetalleLivewireTrait;
    use WithPagination;
    protected $listeners = ['ventarapidapalco1', 'abrirventas2', 'retornarnota', 'detalle'];
    protected $paginationTheme = 'bootstrap';
    public $readytoload = false;
    public $evento_id;
    public $search = '', $search_estado = '', $organizar = 1, $filtrar_por = 0, $agrupar_palcos = false, $cantidad_palcos = 0, $cantidad_asientos = 0;
    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'search_estado' => ['except' => '', 'as' => 'zona'],
        'page' => ['except' => 1, 'as' => 'p'],
        'organizar' => ['except' => '', 'as' => 'org'],
        'filtrar_por' => ['except' => 0, 'as' => 'vendidos'],
    ];
    public $entradas_array = [];
    public $organizar_por = 1;
    public $entradas_seleccionadas = [], $entradas_seleccionadas_endosado = [];
    public $search_telefono, $encontrado = false, $cliente, $estado_venta = 1, $abonado = 0, $total = 0, $metodo_de_pago = 1, $nota_venta;
    public $nombre_cliente, $apellido_cliente, $telefono, $prefijo_telefono = '+57', $phone,  $cedula_cliente;
    public $search_telefono_endosado, $endosado_id, $endosado_identificador, $encontrado_endosado = false, $cliente_endosado;
    public $enviado = false;
    public $digital_id;
    public $total_endosadas = 0, $total_sin_endosar = 0, $porcentaje_venta, $dias_restantes,  $estado_evento;
    public $seleccionar_todos = false, $estadisticas = array();
    public $detalle_id;

    public function mount($id){
        $this->evento_id = $id;
    }
    
    public function render()
    {
        $this->estadisticas();
        return view('livewire.misentradas.detalle-livewire');
    }

    public function loadDatos(){
        $this->readytoload = true;
        $hoy = Carbon::now();
        $final = Carbon::parse(Event::findorfail($this->evento_id)->end_time);
        $this->dias_restantes = $hoy->diffInDays($final);
        $this->calcularendosados();
        if($this->search_estado != ''){
            $z = $this->Zonas->where('id', $this->search_estado)->first();
            if($z->forma_generar == 2){
                $this->agrupar_palcos = true;
                $this->emit('cambiarpalco',  $z);
            }
        }else{
            $this->reset('agrupar_palcos');
        }
    }    
    
    public function ventarapidapalco1($data) {
        $this->cliente = $data['cliente'];
        $this->entradas_seleccionadas = $data['array'];
        $this->enviado = true;
        $this->dispatchBrowserEvent('verenviadas');
        $this->reset('nota_venta');
    }
    
    public function detalle($id){
        $this->detalle_id = $id;
        $r = DigitalOrdenCompraDetalle::where('digital_id', $id)->first();
        if(!empty($r)){
            $this->reset(['cliente', 'entradas_seleccionadas']);
            $orden = DigitalOrdenCompra::where('id',$r->digital_orden_compra_id)->first();
                if(!empty($orden)){
                     $this->enviado = true;
                    $this->cliente = $orden->cliente;
                    $detalle = DigitalOrdenCompraDetalle::where('digital_orden_compra_id', $orden->id)->get();
                    foreach ($detalle as $ent) {
                        $digital = $ent->digital;
                        $nombre = $digital->zona->name;
                        $digial['entrada'] = $nombre;                       
                        $this->entradas_seleccionadas[] = $digital;
                    }
                    $this->dispatchBrowserEvent('verventaa');
                }else{
                    $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, contacta al administrador')]);
                }
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('El detalle de la venta no se ha encontrado en la base de datos, contacta al administrador')]);
        }
    }

    public function endosar($id){
        $this->emit('endosarentrada', $id);
    }

    public function abrirventarapida1($id){
        $this->dispatchBrowserEvent('abrirventa33');
        $this->digital_id = $id;
        $this->reset('nota_venta');
    }

    public function ventarapida(){
        $this->validate([
            'nota_venta' => 'required|min:1|max:550'
        ]);
        DB::beginTransaction();
        try {
          
            $id = $this->digital_id;
            $this->reset(['entradas_seleccionadas', 'cliente']);
            $r = $this->Entradas->find($id);
            $ent = OrderChild::findorfail($r->order_child_id);
            $ent->customer_id = Auth::user()->id;
            $ent->vendedor_id = Auth::user()->id;
            $ent->update();
            $r->endosado = 1;
            $r->update();
            
            $this->entradas_seleccionadas[] = $r;

            $this->cliente = AppUser::where([
                ['phone', 'LIKE',  Auth::user()->phone]
            ])->orWhere([
                 ['email', 'LIKE',  Auth::user()->email]
            ])->first();

            if(empty($this->cliente)){
                $cl = new AppUser();
                    $cl->name = 'Cliente ' . Auth::user()->first_name;
                    $cl->last_name = Auth::user()->last_name;
                    $cl->email = Auth::user()->email;
                    $cl->password = Hash::make(Auth::user()->phone);
                    $cl->image = Auth::user()->image;
                    $cl->phone = Auth::user()->phone;
                    $cl->cedula = '';
                    $cl->provider = 'LOCAL';
                    $cl->status = 1;
                    $cl->borrado = 0;
                $cl->save();
                $this->cliente = $cl;
            }

            $ordencompra = new DigitalOrdenCompra();
                $ordencompra->identificador = Str::upper(Str::random(7));
                $ordencompra->evento_id = $this->evento_id;
                $ordencompra->vendedor_id = Auth::user()->id;
                $ordencompra->cliente_id = $this->cliente->id;
                $ordencompra->cantidad_entradas = 1;
                $ordencompra->metodo_pago = 1;
                $ordencompra->abonado = 0;
                $ordencompra->total = 0;
                $ordencompra->estado_venta = 1;
                $ordencompra->nota = $this->nota_venta;
            $ordencompra->save();

            $detalle = new DigitalOrdenCompraDetalle();
                $detalle->digital_orden_compra_id = $ordencompra->id;
                $detalle->order_child_id = $ent->id;
                $detalle->endosado_id = null;
                $detalle->digital_id = $id;
            $detalle->save();
            DB::commit();
            $this->resetExcept(['evento_id', 'readytoload', 'search', 'search_estado', 'entradas_seleccionadas', 'cliente', 'total_sin_endosar', 'total_endosadas', 
                'porcentaje_venta', 'dias_restantes', 'estado_evento']);
            $this->enviado = true;
            $this->enviarcompartir($id);
            $this->estadisticas();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    

    public function asignarentrada2(){
        $this->phone = $this->prefijo_telefono . $this->telefono;
        $this->validate([
            'nombre_cliente' => 'required|min:2|max:120',
            'apellido_cliente' => 'required|min:2|max:120',
            'correo_cliente' => 'required|email|unique:app_user,email',
            'prefijo_telefono' => 'required',
            'telefono' => 'required|integer',
            'phone' => 'required|phone:CO,AUTO|unique:app_user,phone',
            'contraseña_cliente' => 'required|min:3|max:120',
            'notificar_nuevo' => 'required',
            'cedula_cliente' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            $cliente = new AppUser();
                $cliente->name = $this->nombre_cliente;
                $cliente->last_name = $this->apellido_cliente;
                $cliente->email = $this->correo_cliente;
                $cliente->password = Hash::make($this->contraseña_cliente);
                $cliente->phone = $this->phone;
                $cliente->cedula = $this->cedula_cliente;
                $cliente->provider = 'LOCAL';
                $cliente->status = 1;
                $cliente->borrado = 0;
                $cliente->image = 'defaultuser.png';
            $cliente->save();
            DB::commit();
            if($this->notificar_nuevo == true){
                $telefono = $this->phone;
                $mensaje =  urlencode( 'Bienvenido a ' .Str::upper($this->Setting) . ': ') . '%0d%0a' . 
                urlencode('Nombre: ' . Str::title( $this->nombre_cliente . ' ' . $this->apellido_cliente )) . '%0d%0a' .
                urlencode('Telefono de acceso: ') . urlencode($this->phone) . '%0d%0a' .
                urlencode('Contraseña: ') . urlencode($this->contraseña_cliente)  . '%0d%0a' ;
                $this->enviarsms2($telefono, $mensaje);
            }
            $this->dispatchBrowserEvent('storecliente');
            
            if(!in_array($this->endosado_id, $this->entradas_seleccionadas_endosado)){
                $this->entradas_seleccionadas_endosado[$this->endosado_id] = array(
                    'entrada_digital_id' => $this->endosado_id,
                    'cliente_id' => $cliente->id,
                    'cliente_name' => $cliente->name . ' ' . $cliente->last_name
                );
            }else{
                $key = array_search($this->endosado_id,array_column($this->entradas_seleccionadas_endosado, 'entrada_digital_id'));
                $r = array(
                    'entrada_digital_id' => $this->endosado_id,
                    'cliente_id' => $cliente->id,
                    'cliente_name' => $cliente->name . ' ' . $cliente->last_name
                );
                array_replace($this->entradas_seleccionadas_endosado[$key], $r);
            }
            $this->limpiarcliente();
            $this->reset(['search_telefono_endosado', 'endosado_id', 'encontrado_endosado', 'cliente_endosado', 'endosado_identificador']);
            $this->dispatchBrowserEvent('regresarcliente1');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function abrirventas(){
        
        if (count($this->entradas_array) > 0) {
            $this->dispatchBrowserEvent('abrirmodalventa');
            $this->cargarentradas();
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('Debe seleccionar al menos 1 entrada')]);
        }
    }

    public function abrirventas2($array){
        $this->entradas_array = $array;
        if (count($this->entradas_array) > 0) {
            $this->dispatchBrowserEvent('abrirmodalventa');
            $this->cargarentradas();
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('Debe seleccionar al menos 1 entrada')]);
        }
    }

   

    public function veruploads($id){
        $this->reset(['entradas_array', 'entradas_seleccionadas', 'abonado', 'total']);
        $this->dispatchBrowserEvent('abrirmodalventa');
        $this->entradas_array[] = $id;
        $this->entradas_seleccionadas = OrderChildsDigital::whereIn('id', $this->entradas_array)->get();        
    }

    public function descargar($id){
        $this->reset('digital_id');
        $this->digital_id = $id;
        if($this->Digital->permiso_descargar == 1){
            $this->dispatchBrowserEvent('verenlace');
            $key = base64_encode('@kf#'.$this->Digital->id);
            $url = route('ver.archivo', $key);
            $this->Digital->url_1 = $url;
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('No tiene permiso para ver el enlace')]);
        }
    }

    public function enviarentradas(){
       
        if ($this->estado_venta != null ) {            
            DB::beginTransaction();
            try {
                if ($this->encontrado == false ) {
                    $this->phone = $this->prefijo_telefono . $this->telefono;
                   
                    $this->validate([
                        'nombre_cliente' => 'required|max:120|min:2',
                        'prefijo_telefono' => 'required',
                        'telefono' => 'required',
                        'phone' => 'required|phone:COL,AUTO|unique:app_user,phone',
                        'cedula_cliente' => 'required|integer|unique:app_user,cedula'
                    ]);
                    $cliente = new AppUser();
                        $cliente->name = $this->nombre_cliente;
                        $cliente->phone = $this->phone;
                        $cliente->cedula = $this->cedula_cliente;
                        $cliente->password = Hash::make($this->cedula_cliente);
                        $cliente->provider = "LOCAL";
                        $cliente->status = 1;
                        $cliente->borrado = 0;
                    $cliente->save();
    
                  $this->cliente = $cliente;
                }
                $ordencompra = new DigitalOrdenCompra();
                    $ordencompra->identificador = Str::upper(Str::random(7));
                    $ordencompra->evento_id = $this->evento_id;
                    $ordencompra->vendedor_id = Auth::user()->id;
                    $ordencompra->cliente_id = $this->cliente->id;
                    $ordencompra->cantidad_entradas = count($this->entradas_seleccionadas);
                    $ordencompra->metodo_pago = $this->metodo_de_pago;
                    $ordencompra->abonado = $this->abonado;
                    $ordencompra->total = $this->total;
                    $ordencompra->estado_venta = $this->estado_venta;
                $ordencompra->save();
                foreach ($this->entradas_seleccionadas as $es) {
                    $ent = OrderChild::findorfail($es->order_child_id);
                    $ent->customer_id = $this->cliente->id;
                    $ent->vendedor_id = Auth::user()->id;
                    if (array_key_exists($es['id'],$this->entradas_seleccionadas_endosado)){
                        $ent->endosado_id = $this->entradas_seleccionadas_endosado[$es['id']]['cliente_id'];
                    }
                    $ent->update();
                    $es->endosado = 1;
                    $es->update();
                    $key = base64_encode('@kf#'.$es->id);
                    $es->url_1 = route('ver.archivo', $key);
                    $detalle = new DigitalOrdenCompraDetalle();
                        $detalle->digital_orden_compra_id = $ordencompra->id;
                        $detalle->order_child_id =$es->order_child_id;
                        if (array_key_exists($es['id'],$this->entradas_seleccionadas_endosado)){
                            $detalle->endosado_id = $this->entradas_seleccionadas_endosado[$es['id']]['cliente_id'];
                        }else{
                            $detalle->endosado_id = null;
                        }
                        $detalle->digital_id = $es['id'];
                    $detalle->save();
                }
                
                DB::commit();
                $this->resetExcept(['evento_id', 'readytoload', 'search', 'search_estado', 'entradas_seleccionadas', 'cliente', 'total_sin_endosar', 'total_endosadas', 
                    'porcentaje_venta', 'dias_restantes', 'estado_evento']);
                $this->enviado = true;
                $this->dispatchBrowserEvent('verenviadas');
                $this->estadisticas();
            } catch (Exception $e) {
                DB::rollBack();
                $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
            }
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('Debe seleccionar el estado de la venta')]);
        }
    }

    public function seleccionartodos(){        
        if($this->seleccionar_todos == false){
            $e = OrderChildsDigital::where([
                ['evento_id', $this->evento_id], ['identificador', 'LIKE', '%'.$this->search.'%'], ['zona_id', 'LIKE', '%'. $this->search_estado], ['endosado', $this->filtrar_por]
            ])->get(); 
    
            $this->reset('entradas_array');
            foreach ($e as $i) {
               $this->entradas_array[$i->id] = $i->id;
            }
            $this->seleccionar_todos = true;
        }else{
            $this->seleccionar_todos = false;
            $this->reset('entradas_array');
        }
    }

    public function quitar($id){
        
        foreach (array_keys($this->entradas_array, $id) as $key) 
        {
            unset($this->entradas_array[$key]);
        }
        $this->cargarentradas();
        $this->reset(['seleccionar_todos']);
    }

    public function limpiar(){
        $this->reset(['search', 'search_estado', 'organizar']);
        $this->calcularendosados();
        $this->resetpage();
    }

    private function calcularendosados(){
        $this->reset(['total_endosadas', 'total_sin_endosar']);
        $e = OrderChildsDigital::where([
            ['evento_id', $this->evento_id]
        ])->get();
        foreach ($e as $td) {
            if($td->endosado != 0){
                $this->total_endosadas = $this->total_endosadas + 1;
            }else{
                $this->total_sin_endosar = $this->total_sin_endosar +1;
            }
        }

        $this->calcularestadisticas(count($e), $this->total_endosadas);
    }

    private function calcularestadisticas($total, $endosadas){
        if($total > 0){
            $this->porcentaje_venta = round( ($endosadas / $total) * 100 );
        }else{
            $this->porcentaje_venta = 0;
        }
      
        if($this->dias_restantes > 15){
            if ($this->porcentaje_venta > 75) {
                $this->estado_evento = 1;
            }elseif($this->porcentaje_venta > 50 && $this->porcentaje_venta < 75){
                $this->estado_evento = 2;
            }elseif($this->estado_evento < 50){
                $this->estado_evento = 3;
            }
        }elseif($this->dias_restantes < 15) {
            if ($this->porcentaje_venta > 75) {
                $this->estado_evento = 1;
            }elseif($this->porcentaje_venta > 50 && $this->porcentaje_venta < 75){
                $this->estado_evento = 2;
            }elseif($this->estado_evento < 50){
                $this->estado_evento = 4;
            }
        }
    }    

    private function cargarentradas(){
       
        $this->entradas_seleccionadas = OrderChildsDigital::whereIn('id', $this->entradas_array)->get();
        $this->reset(['abonado', 'total']);
        foreach ($this->entradas_seleccionadas as $rr) {
            $rr->endosado = false;
        }
    }   

    public function enviarsms2($telefono, $mensaje){
        $data = array(
            'telefono' => $telefono,
            'mensaje' => $mensaje,
        );
        $data = (new ApiController)->sendsms2($data);
      
    }

    public function getEventoProperty(){
        return Event::findorfail($this->evento_id)->name;
    }

    public function getDigitalProperty(){
        return OrderChildsDigital::findorfail($this->digital_id);
    }

    public function getEntradasProperty(){
        return OrderChildsDigital::where([
            ['evento_id', $this->evento_id], ['identificador', 'LIKE', '%'.$this->search.'%'], ['zona_id', 'LIKE', '%'. $this->search_estado], ['endosado', $this->filtrar_por]
        ])->paginate(18);
    }
    
    public function getZonasProperty(){
        return Ticket::where([
            ['event_id', $this->evento_id], ['is_deleted', 0], ['tipo', 1], ['categoria', 2], ['status', 1], ['is_deleted', 0]
        ])->get();
    }

    public function getSettingProperty(){
        return Setting::findorfail(1)->app_name;
    } 

    public function enviarcompartir($id){
        $this->dispatchBrowserEvent('cerrarshow1');
        $ent = OrderChildsDigital::findorfail($id);
        $ent->cliente = !empty($this->cliente) ? $this->cliente : $ent->entrada->cliente;
        $ent->evento = $this->Evento;
        $ent->entrada = $ent->zona['name'];
        $this->emit('mostrarCompartir', $ent);
    }

    public function ventarapida2(){
        $this->validate([
            'nota_venta' => 'required|min:1|max:550'
        ]);
       $this->emit('ventarapidapalco', $this->nota_venta);
    }
   
     
}
