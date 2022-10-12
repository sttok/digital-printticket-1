<?php

namespace App\Http\Livewire\Misentradas;

use Livewire\Component;
use App\Models\Setting;

class CompartirVentaDigitalLivewire extends Component
{
    protected $listeners = ['mostrarCompartir'];
    public $url_whatsapp, $telefono_cliente, $correo_cliente;
    public $configuracion;

    public function render()
    {
        return view('livewire.misentradas.compartir-venta-digital-livewire');
    }

    public function mostrarCompartir($data){
        $this->dispatchBrowserEvent('abrircompartir');
        $key = base64_encode('@kf#'.$data['id']);
        $data['url_1'] = route('ver.archivo', $key);
        $this->url_whatsapp = 'Hola, gracias por tu compra con ' . $this->Setting . ', tu url para descargar la entrada con el identificador #'. $data['identificador'] . ' es: ' . $data['url_1'];
        $this->telefono_cliente = $data['cliente']['phone'];
        $this->correo_cliente = $data['cliente']['email'];
    }
    
    public function limpiar(){
        
    }
    
    public function actualizar(){
        
    }
    
    public function getSettingProperty(){
        return Setting::findorfail(1)->app_name;
    } 

}
