<?php

namespace App\Http\Livewire\Cliente;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\AppUser;
use App\Models\Setting;
use Livewire\Component;
use App\Models\OrderChild;
use Illuminate\Support\Str;
use App\Models\DigitalOrdenCompra;
use App\Models\OrderChildsDigital;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use App\Models\DigitalOrdenCompraDetalle;

class DetalleLivewire extends Component
{
    protected $listeners = ['endosado2', ''];
    public $token;
    public $decodificado;
    public $entrada_id, $metodo_compartir = 0;
    public $readytoLoad = false;
    public $prefijo = '+57', $telefono, $phone, $nombre_endosado, $apellido_endosado, $cedula_endosado;

    public function mount($token){
        $this->token = $token;
        $this->decodificar();
    }

    public function render()
    {
        return view('livewire.cliente.detalle-livewire');
    }

    public function loadDatos(){
        $this->readytoLoad = true;
        //dd($this->decodificado);
    }

    public function updatedCedulaEndosado(){
        if($this->cedula_endosado != ''){
            $endosado = AppUser::where('cedula', $this->cedula_endosado)->first();
            if($endosado){
                $this->dispatchBrowserEvent('cedulaEncontrada', ['nombre' => $endosado->name. ' ' . $endosado->last_name]);
            }
        }
       
    }

    public function endosar($id){
        $this->limpiar();
        $this->entrada_id = $this->Ordencompradetalle->firstwhere('id',$id)->digital_id;
        $entrada =  $this->Ordencompradetalle->firstWhere('id', $id);
        if($entrada && $entrada->entrada->customer_id != 0 || $entrada->entrada->customer_id != null){
            $this->dispatchBrowserEvent('openEndosar');
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => 'La entrada ya se encuentra endosada']);
        }
    }

    public function endosado(){
        $this->phone = $this->prefijo . $this->telefono;
        $this->validate([
            'nombre_endosado' => 'required|max:120',
            'apellido_endosado' => 'required|max:120',
            'cedula_endosado' => 'required|max:10|unique:app_user,cedula',
            'prefijo' => 'required',
            'telefono' => 'required',
            'phone' => 'required|phone:CO,AUTO|unique:app_user,phone'
        ]);
        DB::beginTransaction();
        try {
            $cliente = AppUser::where('id',  $this->decodificado['cliente'])->first();
            if($cliente->id == $this->Ordencompra->cliente_id){
                 
                $endosado = AppUser::where('cedula', $this->cedula_endosado)->first();
                if(empty($endosado)){
                    $endosado = new AppUser();
                        $endosado->name = $this->nombre_endosado;
                        $endosado->last_name = $this->apellido_endosado;
                        $endosado->cedula = $this->cedula_endosado;
                        $endosado->password = Hash::make($this->cedula_endosado);
                        $endosado->phone = $this->phone;
                        $endosado->image = 'defaultuser.png';
                        $endosado->status = 1;
                        $endosado->borrado = 0;
                    $endosado->save();
                }
                $orderchild = OrderChild::where('id', $this->Entrada->order_child_id)->first();
                    $orderchild->endosado_id = $endosado->id;
                $orderchild->update();
                $detalle = $this->Ordencompradetalle->firstwhere('order_child_id', $this->Entrada->order_child_id);
                    $detalle->endosado_id = $endosado->id;
                $detalle->update();
                $order_child_digital = OrderChildsDigital::where('order_child_id', $orderchild->id)->first();
                if ($order_child_digital) {
                   $order_child_digital->endosado = 1;
                   $order_child_digital->update();
                }
                $this->dispatchBrowserEvent('endosadoSuccess');
                $this->limpiar();
                $this->compartir($detalle->id);
                DB::commit();
            }else{
                $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, contacta al administrador')]);
            }            
        }catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function endosado2(){
        //dd('hola');
        DB::beginTransaction();
        try {
            $cliente = AppUser::where('id',  $this->decodificado['cliente'])->first();
            if($cliente->id == $this->Ordencompra->cliente_id){
                $endosado = AppUser::where('cedula', $this->cedula_endosado)->first();
                if($endosado){
                    $orderchild = OrderChild::where('id', $this->Entrada->order_child_id)->first();
                        $orderchild->endosado_id = $endosado->id;
                    $orderchild->update();
                    $detalle = $this->Ordencompradetalle->firstwhere('order_child_id', $this->Entrada->order_child_id);
                        $detalle->endosado_id = $endosado->id;
                    $detalle->update();
                    $order_child_digital = OrderChildsDigital::where('order_child_id', $orderchild->id)->first();
                    if ($order_child_digital) {
                        $order_child_digital->endosado = 1;
                        $order_child_digital->update();
                    }
                    $this->dispatchBrowserEvent('endosadoSuccess');
                    $this->limpiar();
                    $this->compartir($detalle->id);
                    DB::commit();
                }else{
                    DB::rollBack();
                    $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, contacta al administrador')]);
                }
            }else{
                DB::rollBack();
                $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, contacta al administrador')]);
            }
        }catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function limpiar(){
        $this->reset(['entrada_id', 'metodo_compartir', 'prefijo', 'telefono', 'phone', 'nombre_endosado', 'apellido_endosado', 'cedula_endosado']);
        $this->dispatchBrowserEvent('cerrarModals');
    }

    public function compartir($id){
        $this->entrada_id = $this->Ordencompradetalle->firstwhere('id',$id)->digital_id;
          
        if($this->Entrada){
            $this->reset('metodo_compartir');
            $this->dispatchBrowserEvent('openCompartir');
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => 'Ha ocurrido un error, la entrada no coincide en nuestra base de datos']);
        }
    }

    public function seleccionarcompartir($num){
        if ($num == 1 || $num == 2) {
            $this->metodo_compartir = $num;
            $this->dispatchBrowserEvent('openCompartirPhone');
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => 'Ha ocurrido un error, contacta al administrador']);
        }
       
    }

    public function enviar(){
        $this->phone = $this->prefijo . $this->telefono;
        $this->validate([
            'prefijo' => 'required',
            'telefono' => 'required|max:120',
            'phone' => 'required|phone:CO,AUTO'
        ]);
        if($this->metodo_compartir == 1){
            $this->enviarWhatsapp();
        }elseif($this->metodo_compartir == 2){
            $this->enviarsms();
        }
    }

    private function enviarWhatsapp(){
        $key = base64_encode('@kf#'.$this->entrada_id);
        $ruta = route('descargar.entrada', $key);
        $url = urlencode('*¡Gracias por comprar con '. $this->Setting .'!*') .  '%0d%0a' . 
            urlencode('*Evento:* ' . $this->Ordencompra->evento->name) .  '%0d%0a' . 
            urlencode('*Entrada:* ' . $this->Entrada->zona->name . ' #'.$this->Entrada->entrada->identificador) .  '%0d%0a' . 
            urlencode('*Descarga tu entrada:* ' . '_'.$ruta.'_');
        $url = "https://api.whatsapp.com/send?phone=". urlencode( $this->phone )."&". "text=".$url;
        $this->dispatchBrowserEvent('descargar', ['url' => $url]);
        $this->dispatchBrowserEvent('mensajeEnviado');
        $this->limpiar();
    }

    private function enviarsms(){
        $key = base64_encode('@kf#'.$this->entrada_id);
        $ruta = route('descargar.entrada', $key);
        $mensaje = urlencode('¡Gracias por comprar con '. $this->Setting .'!') .  '%0d%0a' . 
            urlencode('Evento: ' . $this->Ordencompra->evento->name) .  '%0d%0a' . 
            urlencode('Entrada: ' . $this->Entrada->zona->name . ' #'.$this->Entrada->entrada->identificador) .  '%0d%0a' . 
            urlencode('Descarga tu entrada: ') . $ruta;
        $data = array(
            'telefono' => $this->phone,
            'mensaje' => $mensaje,
        );
        $data = (new ApiController)->sendsms3($data);
        $this->dispatchBrowserEvent('mensajeEnviado');
        $this->limpiar();
    }

    public function descargar($id){
        $entrada = $this->Ordencompradetalle->find($id);
        $entrada = OrderChildsDigital::where('id', $entrada->digital_id)->first();
        if($entrada->provider == "local"){
            $r = 'storage/ticket-digital/';
            $url = Str::after($entrada->url, $r) ;
            if($entrada->descargas == null){
                $entrada->descargas = 1;
            }else{
                $entrada->descargas++;
            }
            $entrada->update();
            return Storage::disk('custom')->download('ticket-digital/'.$url);
        }elseif($entrada->provider == 'drive'){
            $filename = Storage::disk("google")->url($entrada['url']);
            if($entrada->descargas == null){
                $entrada->descargas = 1;
            }else{
                $entrada->descargas++;
            }
            $entrada->update();
            $this->dispatchBrowserEvent('descargar', ['url' => $filename]);
            //return redirect()->to($filename);
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => 'Ha ocurrido un error, la entrada no coincide en nuestra base de datos']);
        }
    }

    private function decodificar(){
        $key = env('APP_KEY');
        $this->decodificado = JWT::decode($this->token, new Key($key, 'HS256'));
        $this->decodificado = (array)$this->decodificado->data;
    }

    public function getClienteProperty(){
        return AppUser::where('id', $this->decodificado['cliente'])->first();
    }

    public function getOrdencompraProperty(){
        return DigitalOrdenCompra::with('evento:id,name')->where('id', $this->decodificado['orden_compra'])->first();
    }

    public function getOrdencompradetalleProperty(){
        return DigitalOrdenCompraDetalle::with(['entrada:id,identificador,salto,consecutivo,status,mesas,asiento,customer_id,ticket_id','endosado:id,name,last_name'])->where('digital_orden_compra_id', $this->Ordencompra->id)->paginate(16);
    }

    public function getEntradaProperty(){
        return OrderChildsDigital::where('id', $this->entrada_id)->first();
    }

    public function getSettingProperty(){
        return Setting::findorfail(1)->app_name;
    } 
}
