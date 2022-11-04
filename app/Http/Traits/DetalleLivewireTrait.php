<?php
namespace App\Http\Traits;

use Exception;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\AppUser;
use Illuminate\Support\Str;
use App\Models\DigitalOrdenCompra;
use App\Models\OrderChildsDigital;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DescargarInformeExport;
use App\Models\DigitalOrdenCompraDetalle;

trait DetalleLivewireTrait {
    
    private function estadisticas()
    {
        $year = Carbon::now()->format('Y');
        for ($i = 1; $i <= 12; $i++) {
            switch ($i) {
                case 1:
                    $nombre = 'Ene';
                    break;
                case 2:
                    $nombre = 'Feb';
                    break;
                case 3:
                    $nombre = 'Mar';
                    break;
                case 4:
                    $nombre = 'Abr';
                    break;
                case 5:
                    $nombre = 'May';
                    break;
                case 6:
                    $nombre = 'Jun';
                    break;
                case 7:
                    $nombre = 'Jul';
                    break;
                case 8:
                    $nombre = 'Ago';
                    break;
                case 9:
                    $nombre = 'Sep';
                    break;
                case 10:
                    $nombre = 'Oct';
                    break;
                case 11:
                    $nombre = 'Nov';
                    break;
                case 12:
                    $nombre = 'Dic';
                    break;
            }
            $apartadas = DigitalOrdenCompra::where('evento_id', $this->evento_id)->whereYear('created_at', $year)->whereMonth('created_at', $i)->where('estado_venta', 1)->sum('cantidad_entradas');
            $abonadas = DigitalOrdenCompra::where('evento_id', $this->evento_id)->whereYear('created_at', $year)->whereMonth('created_at', $i)->where('estado_venta', 2)->sum('cantidad_entradas');
            $total = DigitalOrdenCompra::where('evento_id', $this->evento_id)->whereYear('created_at', $year)->whereMonth('created_at', $i)->where('estado_venta', 3)->sum('cantidad_entradas');

            $array[$i - 1] = array(
                'Nombre' => $nombre,
                'Apartadas' => (int)$apartadas,
                'Abonadas' => (int)$abonadas,
                'Total' => (int)$total
            );
        }

        $this->estadisticas = $array;
    }

    public function buscarcliente(){
        $this->validate([
            'search_telefono' => 'required'
        ]);

        $cl = AppUser::where([
            ['phone', 'LIKE', '%' . $this->search_telefono]
        ])->orWhere([
             ['cedula', 'LIKE',  $this->search_telefono]
        ])->first();
        
        if($cl != ''){
            $this->encontrado = true;
            $this->cliente = $cl->makeVisible(['name', 'last_name', 'phone', 'email']);
        }else{
            $this->reset(['encontrado', 'cliente']);
            $this->dispatchBrowserEvent('clientenoencontrado');
        }
    }

    public function buscarcliente2(){
        $this->reset(['encontrado_endosado', 'cliente_endosado', 'encontrado']);
        $this->validate([
            'search_telefono_endosado' => 'required'
        ]);
        $cl = AppUser::where([
            ['phone', 'LIKE', '%' . $this->search_telefono_endosado]
        ])->orWhere([
              ['cedula', 'LIKE',  $this->search_telefono_endosado]
        ])->first();
        
        if($cl != ''){
            $this->encontrado_endosado = true;
            $this->cliente_endosado = $cl->makeVisible(['name', 'last_name', 'phone', 'email']);
        }else{
            $this->reset(['encontrado_endosado', 'cliente_endosado', 'encontrado']);
            $this->dispatchBrowserEvent('clientenoencontrado');
        }
    }

    public function cerrarshow(){
        $this->dispatchBrowserEvent('cerrarshow1');
        $this->resetExcept(['evento_id', 'readytoload', 'search', 'search_estado', 'cliente', 'total_sin_endosar', 'total_endosadas', 
                'porcentaje_venta', 'dias_restantes', 'estado_evento', 'filtrar_por', 'organizar', 'agrupar_palcos', 'enviado']);
    }

    public function createcliente(){
        $this->dispatchBrowserEvent('createcliente2');
    }

    public function regresarcliente(){
        $this->dispatchBrowserEvent('regresarcliente1');
    }
    
    public function generarpass(){
        $this->contraseña_cliente = Str::random(8);
    }

    public function storecliente(){
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
            $this->search_telefono = $this->phone;
            $this->buscarcliente();
            $this->limpiarcliente();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function limpiarcliente(){
        $this->reset([
            'nombre_cliente', 'apellido_cliente', 'correo_cliente', 'imagen', 'telefono', 'prefijo_telefono', 'phone', 'contraseña_cliente', 'notificar_nuevo'
        ]);
        $this->calcularendosados();
    }
    
    public function UpdatedFiltrarPor(){
        $this->resetpage();
        $this->calcularendosados();
        $this->reset('entradas_array');
    }

    public function updatedSearch(){
        $this->resetPage();
        $this->reset('seleccionar_todos');
    }

    public function updatedSearchEstado(){
        $this->resetPage();
        $this->reset(['seleccionar_todos', 'entradas_array']);
       
        if($this->search_estado != ''){
            $z = $this->Zonas->where('id', $this->search_estado)->first();
            if($z->forma_generar == 2){
                $this->agrupar_palcos = true;
                $this->emit('cambiarpalco',  $z);
            }else{
                $this->reset('agrupar_palcos');
            }
        }else{
            $this->reset('agrupar_palcos');
        }
    }

    public function descargarinforme(){
        $data = array();
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('Superadmin') || Auth::user()->hasRole('organization')) {
            $orden_compra = DigitalOrdenCompra::with(['cliente:id,name,last_name'])->where('evento_id', $this->evento_id)->get();          
            foreach ($orden_compra as $orden) {
               $detalles = DigitalOrdenCompraDetalle::with(['entrada'])->where('digital_orden_compra_id', $orden->id)->get();
               foreach ($detalles as $detalle) {
                    $data[] = array(
                        'orden_compra_identificador' => $orden->identificador,
                        'nombre_entrada' => $detalle->entrada->evento->name,
                        'Identificador' => $detalle->entrada->identificador,
                        'Consecutivo' => $detalle->entrada->consecutivo,
                        'Palco' => $detalle->entrada->mesas != null ? $detalle->entrada->mesas : 'No',
                        'Asiento' => $detalle->entrada->asiento != null ? $detalle->entrada->asiento : 'No',
                        'estado' => $detalle->entrada->status,
                        'comprador' => $orden->cliente->name . ' ' . $orden->cliente->last_name,
                        'endosado' => $detalle->endosado_id != null ? $detalle->endosado->name . ' ' . $detalle->endosado->last_name : 'No',
                        'fecha_vendido' => Carbon::create($orden->created_at)->locale('es')->isoFormat('LLLL'),
                    );
               }
            }
        }elseif (Auth::user()->hasRole('punto venta')) {
            $orden_compra = DigitalOrdenCompra::where([
                ['evento_id', $this->evento_id], ['vendedor_id', Auth::user()->id]
                ])->with(['cliente:id,name,last_name'])->get();
            foreach ($orden_compra as $orden) {
                 $detalles = DigitalOrdenCompraDetalle::where('digital_orden_compra_id', $orden->id)->with(['entrada'])->get();
               foreach ($detalles as $detalle) {
                    $data[] = array(
                        'orden_compra_identificador' => $orden->identificador,
                        'nombre_entrada' => $detalle->entrada->evento->name,
                        'Identificador' => $detalle->entrada->identificador,
                        'Consecutivo' => $detalle->entrada->consecutivo,
                        'Palco' => $detalle->entrada->mesas != null ? $detalle->entrada->mesas : 'No',
                        'Asiento' => $detalle->entrada->asiento != null ? $detalle->entrada->asiento : 'No',
                        'estado' => $detalle->entrada->status,
                        'comprador' => $orden->cliente->name . ' ' . $orden->cliente->last_name,
                        'endosado' => $detalle->endosado_id != null ? $detalle->endosado->name . ' ' . $detalle->endosado->last_name : 'No',
                        'fecha_vendido' => Carbon::create($orden->created_at)->locale('es')->isoFormat('LLLL'),
                    );
               }
            }
        }
        return Excel::download(new DescargarInformeExport($data), 'informe-'. Carbon::now()->isoFormat('D-M-YY') . '-' . rand(1,999) .'.xlsx');
    }

    public function compartirventawhatsapp(){      
        $r = DigitalOrdenCompraDetalle::where('digital_id', $this->detalle_id)->first();
        if(!empty($r)){
            $orden = DigitalOrdenCompra::with('cliente')->where('id',$r->digital_orden_compra_id)->first();
            if(!empty($orden)){
                $telefono_cliente = $orden->cliente->phone;
                $entradas_vendidas = DigitalOrdenCompraDetalle::where('digital_orden_compra_id', $orden->id)->get();
                
                $evento = Event::find($orden->evento_id)->name;
                $texto =  urlencode('*¡Gracias por comprar con '. $this->Setting .'!*') .  '%0d%0a' . 
                        urlencode('*Evento*: ' . $evento) .  '%0d%0a' . 
                        urlencode('*Identificador venta*: ' . $orden->identificador).  '%0d%0a'.
                        urlencode('*Cantidad entradas*: x' . $orden->cantidad_entradas).  '%0d%0a'.
                        urlencode('*Entradas*: ').  '%0d%0a';

                foreach ($entradas_vendidas as $entrada) {
                    $ent = OrderChildsDigital::with(['zona:id,name,forma_generar', 'entrada:id,identificador,mesas,asiento'])->where('id',$entrada->digital_id)->first();
                    $key = base64_encode('@kf#'.$entrada->digital_id);
                    $url = route('ver.archivo', $key);
                    $text = urlencode('- ' .  $ent->zona->name). '%0d%0a';
                    $text .=  urlencode('  » _Identificador_: ' . $ent->entrada->identificador) . '%0d%0a';
                    if($ent->zona->forma_generar == 2){
                        $text .=  urlencode('  » _Palco_ #' .  $ent->entrada->mesas) . '%0d%0a';
                        $text .=  urlencode('  » _Asiento_ #' .  $ent->entrada->asiento) . '%0d%0a';
                    }
                    $text .=  urlencode('  » _Descargar tu entrada_: ' . $url) . '%0d%0a';
                    $texto .= $text;
                }              
                $url = 'https://api.whatsapp.com/send?phone='. urlencode( $telefono_cliente ) . '&text='. $texto;
                $this->dispatchBrowserEvent('compartirwhatsapp', ['url' => $url]);
            }else{
                $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, contacta al administrador')]);
            }
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, contacta al administrador')]);
        }
    }

    public function compartirsms(){
        $r = DigitalOrdenCompraDetalle::where('digital_id', $this->detalle_id)->first();
        if(!empty($r)){
            $orden = DigitalOrdenCompra::with('cliente')->where('id',$r->digital_orden_compra_id)->first();
            if(!empty($orden)){
                $telefono = $orden->cliente->phone;
                $entradas_vendidas = DigitalOrdenCompraDetalle::where('digital_orden_compra_id', $orden->id)->get();
                
                $evento = Event::find($orden->evento_id)->name;
                $texto =  urlencode('¡Gracias por comprar con '. $this->Setting .'!') .  '%0d%0a' . 
                        urlencode('Evento: ' . $evento) .  '%0d%0a' . 
                        urlencode('Identificador venta: ' . $orden->identificador).  '%0d%0a'.
                        urlencode('Cantidad entradas: x' . $orden->cantidad_entradas).  '%0d%0a'.
                        urlencode('Entradas: ').  '%0d%0a';

                foreach ($entradas_vendidas as $entrada) {
                    $ent = OrderChildsDigital::with(['zona:id,name,forma_generar', 'entrada:id,identificador,mesas,asiento'])->where('id',$entrada->digital_id)->first();
                    $key = base64_encode('@kf#'.$entrada->digital_id);
                    $url = route('ver.archivo', $key);
                    $text = urlencode('- ' .  $ent->zona->name). '%0d%0a';
                    $text .=  urlencode('  » Identificador: ' . $ent->entrada->identificador) . '%0d%0a';
                    if($ent->zona->forma_generar == 2){
                        $text .=  urlencode('  » Palco #' .  $ent->entrada->mesas) . '%0d%0a';
                        $text .=  urlencode('  » Asiento #' .  $ent->entrada->asiento) . '%0d%0a';
                    }
                    $text .=  urlencode('  » Descargar tu entrada: ' . $url) . '%0d%0a';
                    $texto .= $text;
                }
                
                $this->enviarsms2($telefono, $texto);
            }else{
                $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, contacta al administrador')]);
            }
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, contacta al administrador')]);
        }
    }
}