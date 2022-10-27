<?php

namespace App\Http\Livewire\Misentradas;

use Exception;
use App\Models\AppUser;
use Livewire\Component;
use App\Models\OrderChild;
use Illuminate\Support\Str;
use App\Models\OrderChildsDigital;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\Models\DigitalOrdenCompraDetalle;

class EndosarVentaDigitalLivewire extends Component
{
    protected $listeners = ['endosarentrada'];
    public $encontrado = false, $search_telefono, $cliente;
    public $entrada_id;
    public $nombre_cliente, $apellido_cliente, $correo_cliente,  $telefono, $prefijo_telefono = '+57', $phone, $contraseña_cliente, $notificar_nuevo = false, $cedula_cliente;

    public function render()
    {
        return view('livewire.misentradas.endosar-venta-digital-livewire');
    }

    public function endosarentrada($id){
        $this->entrada_id = $id;
        $this->dispatchBrowserEvent('abrirendosar');
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

    public function storeclienteendosar(){
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
            'cedula_cliente' => 'required|integer',
            'entrada_id' => 'required'
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

            $ent = OrderChildsDigital::findorfail($this->entrada_id);
            if(!empty($ent)){
                $order_child = OrderChild::findorfail($ent->order_child_id);
                $order_child->endosado_id = $cliente->id;
                $order_child->update();
                $detalle = DigitalOrdenCompraDetalle::where('digital_id', $ent->id)->first();
                if($detalle){
                    $detalle->endosado_id = $cliente->id;
                    $detalle->update();
                }
            }

            DB::commit();
            $this->dispatchBrowserEvent('cerrarendosar');
            $this->resetExcept(['entrada_id']);
            if($this->notificar_nuevo == true){
                $telefono = $this->phone;
                $mensaje =  urlencode( 'Bienvenido a ' .Str::upper($this->Setting) . ': ') . '%0d%0a' . 
                urlencode('Nombre: ' . Str::title( $this->nombre_cliente . ' ' . $this->apellido_cliente )) . '%0d%0a' .
                urlencode('Telefono de acceso: ') . urlencode($this->phone) . '%0d%0a' .
                urlencode('Contraseña: ') . urlencode($this->contraseña_cliente)  . '%0d%0a' ;
                $this->enviarsms2($telefono, $mensaje);
            }
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function endosar(){
        $this->validate([
            'encontrado' => 'required',
            'cliente' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $ent = OrderChildsDigital::findorfail($this->entrada_id);
            if(!empty($ent)){
                $order_child = OrderChild::findorfail($ent->order_child_id);
                $order_child->endosado_id = $this->cliente->id;
                $order_child->update();
                $detalle = DigitalOrdenCompraDetalle::where('digital_id', $ent->id)->first();
                $detalle->endosado_id = $this->cliente->id;
                $detalle->update();
                DB::commit();
                $this->dispatchBrowserEvent('cerrarendosar');
                $this->reset(['encontrado', 'search_telefono', 'cliente', 'entrada_id']);
            }else{
                $this->dispatchBrowserEvent('errores', ['error' => __('No se ha encontrado la entrada digital, contacta al administrador')]);
            }

        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    private function enviarsms2($telefono, $mensaje){
        $data = array(
            'telefono' => $telefono,
            'mensaje' => $mensaje,
        );
        $data = (new ApiController)->sendsms2($data);      
    }
    
    public function limpiar(){
        $this->dispatchBrowserEvent('abrirventa');
        $this->reset(['search_telefono', 'cliente', 'encontrado']);
    }
}
