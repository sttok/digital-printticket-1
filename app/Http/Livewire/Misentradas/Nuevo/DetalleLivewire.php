<?php

namespace App\Http\Livewire\Misentradas\Nuevo;

use Exception;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\AppUser;
use App\Models\Setting;
use Livewire\Component;
use App\Models\OrderChild;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use PhpParser\Node\Stmt\Foreach_;
use App\Models\DigitalOrdenCompra;
use App\Models\OrderChildsDigital;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\DigitalOrdenCompraDetalle;

class DetalleLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $evento_id;
    public $disponibles = [];
    public $loaded = false, $readyToLoad = false, $enviado = false;
    public $entradas_seleccionadas = [], $grupos_palcos = [], $seleccionados_palcos = [];
    public $entradas_palcos_seleccionados;
    public $palco_id;

    public $search_telefono, $encontrado = false, $cliente, $estado_venta = 1, $abonado = 0, $total = 0, $metodo_de_pago = 1, $nota_venta;
    public $nombre_cliente, $apellido_cliente, $telefono, $prefijo_telefono = '+57', $phone,  $cedula_cliente;

    public $venta_id, $venta_realizada = [];

    public function mount($event_id){
        $this->evento_id = $event_id;
    }

    public function render()
    {
        return view('livewire.misentradas.nuevo.detalle-livewire');
    }

    public function loadDatos(){
        $this->readyToLoad = true;
    }

    public function cargarDatos(){
        foreach ($this->Zonas as $zona) {
            $restan = DB::table('order_child')
            ->select(DB::raw('count(id) as restante'))
            ->where([
                ['ticket_id',  $zona->id], ['customer_id', 0]
            ])
            ->groupBy('ticket_id')
            ->first();

    
            $this->disponibles[$zona->id] = array(
                'ticket_id' => $zona->id,
                'cantidad_restantes' => json_decode(json_encode($restan->restante))
            );
        }
        $this->loaded = true;
    }

    public function confirmarVenta(){

        if (count($this->entradas_seleccionadas) > 0) {
            $this->dispatchBrowserEvent('buscarCliente');
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('Debe agregar al menos una entrada')]);
        }
    }

    public function procesarCompra(){
       
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
                    $ordencompra->cantidad_entradas = 0;
                    $ordencompra->metodo_pago = $this->metodo_de_pago;
                    $ordencompra->abonado = $this->abonado;
                    $ordencompra->total = $this->total;
                    $ordencompra->estado_venta = $this->estado_venta;
                $ordencompra->save();

                $contador = 0;
                foreach ($this->entradas_seleccionadas as $es) {
                    $entrada = Ticket::findorfail($es['entrada_id']);

                    if($entrada->forma_generar == 1){
                        $childs = OrderChild::where([
                            ['ticket_id', $entrada->id], ['customer_id',  0], ['endosado_id', 0]
                        ])->take($es['cantidad'])->get();

                    }elseif($entrada->forma_generar == 2){
                        $childs = OrderChild::where([
                            ['ticket_id', $entrada->id], ['customer_id',  0], ['endosado_id', 0]
                        ])->whereIn('id', $this->entradas_palcos_seleccionados[$entrada->id])->get();
                    }
                    if(count($childs) == $es['cantidad']){
                        $contador++;
                        foreach ($childs as $item) {
                            $item->customer_id = $this->cliente->id;
                            $item->vendedor_id = Auth::user()->id;
                            $item->update();
                            $detalle = new DigitalOrdenCompraDetalle();
                                $detalle->digital_orden_compra_id = $ordencompra->id;
                                $detalle->order_child_id = $item->id;
                                $detalle->endosado_id = null;
                                $detalle->digital_id = OrderChildsDigital::where('order_child_id', $item->id)->first()->id;
                            $detalle->save();

                            $this->venta_realizada[] = array(
                                'name_entrada' => $entrada->name,
                                'id' => $item->id,
                                'identificador' => $item->identificador,
                                'consecutivo' => $item->consecutivo,
                                'palco' => $item->mesas,
                                'asiento' => $item->asiento,
                            );
                        }
                    }else{
                        DB::rollBack();
                        $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, no se han encontrado entradas disponibles para ' . $es['name'])]);
                    }
                }
                $ordencompra->cantidad_entradas = $contador;
                $ordencompra->update();
                $this->venta_id = $ordencompra->id;
                DB::commit();
                $this->resetExcept(['evento_id', 'readytoload', 'cliente', 'venta_id', 'venta_realizada']);
                $this->enviado = true;
                $this->dispatchBrowserEvent('verenviadas');
            } catch (Exception $e) {
                DB::rollBack();
                $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
            }
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('Debe seleccionar el estado de la venta')]);
        }
    }

    public function buscarcliente(){
        $this->validate([
            'search_telefono' => 'nullable|max:120'
        ]);
     
        $cl = AppUser::where([
            ['phone', 'LIKE', '%' . $this->search_telefono]
        ])->orWhere([
             ['cedula', 'LIKE',  $this->search_telefono]
        ])->first();
       
        if($cl != ''){          
            $this->encontrado = true;
            $this->cliente = $cl->makeVisible(['name', 'last_name', 'phone', 'email']);
            $this->reset('search_telefono');
        }else{          
            $this->reset(['encontrado', 'cliente']);
            $this->dispatchBrowserEvent('clientenoencontrado');
        }
    }

    public function enviarcompartir($id){
        $this->dispatchBrowserEvent('cerrarshow1');
        $ent = OrderChildsDigital::findorfail($id);
        $ent->cliente = !empty($this->cliente) ? $this->cliente : $ent->entrada->cliente;
        $ent->evento = $this->Evento;
        $ent->entrada = $ent->zona['name'];
        $this->emit('mostrarCompartir', $ent);
    }

    public function compartirventawhatsapp(){       
        // $orden = DigitalOrdenCompra::with('cliente')->where('id',$this->venta_id)->first();
        // if(!empty($orden)){
        //     $telefono_cliente = $orden->cliente->phone;
        //     $entradas_vendidas = DigitalOrdenCompraDetalle::where('digital_orden_compra_id', $orden->id)->get();
            
        //     $evento = Event::find($orden->evento_id)->name;
        //     $texto =  urlencode('*¡Gracias por comprar con '. $this->Setting .'!*') .  '%0d%0a' . 
        //             urlencode('*Evento*: ' . $evento) .  '%0d%0a' . 
        //             urlencode('*Identificador venta*: ' . $orden->identificador).  '%0d%0a'.
        //             urlencode('*Cantidad entradas*: x' . $orden->cantidad_entradas).  '%0d%0a'.
        //             urlencode('*Entradas*: ').  '%0d%0a';

        //     foreach ($entradas_vendidas as $entrada) {
        //         $ent = OrderChildsDigital::with(['zona:id,name,forma_generar', 'entrada:id,identificador,mesas,asiento'])->where('id',$entrada->digital_id)->first();
        //         $key = base64_encode('@kf#'.$entrada->digital_id);
        //         $url = route('ver.archivo', $key);
        //         $text = urlencode('- ' .  $ent->zona->name). '%0d%0a';
        //         $text .=  urlencode('  » _Identificador_: ' . $ent->entrada->identificador) . '%0d%0a';
        //         if($ent->zona->forma_generar == 2){
        //             $text .=  urlencode('  » _Palco_ #' .  $ent->entrada->mesas) . '%0d%0a';
        //             $text .=  urlencode('  » _Asiento_ #' .  $ent->entrada->asiento) . '%0d%0a';
        //         }
        //         $text .=  urlencode('  » _Descargar tu entrada_: ' . $url) . '%0d%0a';
        //         $texto .= $text;
        //     }              
        //     $url = 'https://api.whatsapp.com/send?phone='. urlencode( $telefono_cliente ) . '&text='. $texto;
        //     $this->dispatchBrowserEvent('compartirwhatsapp1', ['url' => $url]);
        // }else{
        //     $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, contacta al administrador')]);
        // }        
    }

    public function compartirventawhatsapp2(){
        $orden = DigitalOrdenCompra::with(['cliente', 'evento'])->where('id',$this->venta_id)->first();
        $key = base64_encode('@kf#'.$orden->identificador);
        $url = route('ver.archivo', $key);

        $texto =  urlencode('*¡Gracias por comprar con '. $this->Setting .'!*') .  '%0d%0a' . 
        urlencode('*Evento*: ' . $orden->evento->name) .  '%0d%0a' . 
        urlencode('*Identificador venta*: ' . $orden->identificador).  '%0d%0a'.
        urlencode('*Cantidad entradas*: x' . $orden->cantidad_entradas).  '%0d%0a'.
        urlencode('*Descargar aca* : _'. $url . '_' ). '%0d%0a';

        $url = 'https://api.whatsapp.com/send?phone='. urlencode( $orden->cliente->phone ) . '&text='. $texto;
        $this->dispatchBrowserEvent('compartirwhatsapp1', ['url' => $url]);
        
    }
    
    public function compartirsms(){
        $orden = DigitalOrdenCompra::with(['cliente', 'evento'])->where('id',$this->venta_id)->first();
        $key = base64_encode('@kf#'.$orden->identificador);
        $url = route('ver.archivo', $key);
        if(!empty($orden)){
            $telefono = $orden->cliente->phone;
            $texto =  urlencode('¡Gracias por comprar con '. $this->Setting .'!') .  '%0d%0a' . 
            urlencode('Evento: ' . $orden->evento->name) .  '%0d%0a' . 
            urlencode('Identificador venta: ' . $orden->identificador).  '%0d%0a'.
            urlencode('Cantidad entradas: x' . $orden->cantidad_entradas).  '%0d%0a'.
            urlencode('Descargar aca : _'. $url . '_' ). '%0d%0a';
            
            $this->enviarsms2($telefono, $texto);
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, contacta al administrador')]);
        }
    }

    public function agregarCarritoPalco(){
        $entrada = $this->Zonas->find($this->palco_id);
        $contador = count($this->seleccionados_palcos);
        if($this->disponibles[$this->palco_id]['cantidad_restantes'] > 0){
            $this->entradas_seleccionadas[$this->palco_id] = array(
                'entrada_id' => $entrada->id,
                'entrada_name' =>  $entrada->name,
                'cantidad' => $contador
            );
            $this->entradas_palcos_seleccionados[$this->palco_id] = $this->seleccionados_palcos;
            $this->reset(['seleccionados_palcos', 'grupos_palcos', 'palco_id']);
            $this->dispatchBrowserEvent('cerrarmodals');
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('La entrada no cuenta con entradas disponibles')]);
        }
    }

    public function agregarEntrada($id){
        $entrada = $this->Zonas->find($id);
        if($entrada){
            if($entrada->forma_generar == 1){
                if(array_key_exists($id, $this->entradas_seleccionadas)){
                    if( $this->entradas_seleccionadas[$id]['cantidad'] <= $this->disponibles[$id]['cantidad_restantes'] ){
                        $this->entradas_seleccionadas[$id]['cantidad']++;
                    }else{
                        $this->dispatchBrowserEvent('errores', ['error' => __('Ya ha alcanzado el maximo de entradas disponibles')]);
                    }
                   
                }else{
                    if($this->disponibles[$id]['cantidad_restantes'] > 0){
                        $this->entradas_seleccionadas[$id] = array(
                            'entrada_id' => $entrada->id,
                            'entrada_name' =>  $entrada->name,
                            'cantidad' => 1
                        );
                    }else{
                        $this->dispatchBrowserEvent('errores', ['error' => __('La entrada no cuenta con entradas disponibles')]);
                    }
                }
            }elseif($entrada->forma_generar == 2){
                $this->palco_id = $id;
                $this->grupos_palcos = OrderChild::where([
                    ['ticket_id', $entrada->id], ['customer_id', 0]
                ])->get()
                ->makeHidden(['updated_at', 'created_at', 'ticket_hash4', 'ticket_hash3', 'ticket_hash2', 'ticket_hash', 'ticket_number2', 'ticket_number', 'metodo_pago_status', 'metodo_pago'])
                ->groupBy('mesas')->toBase();
                if(count($this->grupos_palcos) > 0){
                    $this->dispatchBrowserEvent('openAggPalco');
                }else{
                    $this->dispatchBrowserEvent('errores', ['error' => __('No hay entradas disponible')]);
                }
            }
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('Entrada no disponible')]);
        }
    }

    public function quitarEntrada($id){
        $entrada = $this->Zonas->find($id);
        if($entrada){
            if(array_key_exists($id, $this->entradas_seleccionadas)){
                if( $this->entradas_seleccionadas[$id]['cantidad'] <= $this->disponibles[$id]['cantidad_restantes'] &&  $this->entradas_seleccionadas[$id]['cantidad'] >1){
                    $this->entradas_seleccionadas[$id]['cantidad']--;
                }else{
                    unset( $this->entradas_seleccionadas[$id]);
                }
            }
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('Entrada no disponible')]);
        }
    }

    public function limpiar(){
        $this->reset(['grupos_palcos']);
        // $this->resetExcept(['loaded', 'readyToLoad']);
        $this->dispatchBrowserEvent('cerrarmodals');
    }

    public function borrarEntradaCarrito($id){
        unset( $this->entradas_seleccionadas[$id]);
    }

    public function seleccionarPalco($mesas){
        foreach ($this->grupos_palcos[$mesas] as $m) {
           $this->seleccionados_palcos[] = $m['id'];
        }     
    }

    public function cerrarshow(){
        $this->dispatchBrowserEvent('cerrarshow1');
        $this->resetExcept(['evento_id', 'readytoload', 'cliente', 'agrupar_palcos', 'enviado']);
    }

    public function getEventoProperty(){
        return Event::where('id', $this->evento_id)->first();
    }

    public function getZonasProperty(){
        return Ticket::where([
            ['event_id', $this->evento_id], ['is_deleted', 0], ['tipo', 1], ['categoria', 2], ['status', 1], ['is_deleted', 0]
        ])->get();
    }

    public function getSettingProperty(){
        return Setting::findorfail(1)->app_name;
    } 
}
