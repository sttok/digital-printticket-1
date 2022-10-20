<?php

namespace App\Http\Livewire\Misentradas;

use App\Models\Setting;
use Livewire\Component;
use App\Http\Controllers\ApiController;

class CompartirVentaDigitalLivewire extends Component
{
    protected $listeners = ['mostrarCompartir'];
    public $url_whatsapp, $telefono_cliente, $correo_cliente;

    public function render()
    {
        return view('livewire.misentradas.compartir-venta-digital-livewire');
    }

    public function mostrarCompartir($data){
        $data = collect($data);
        $this->dispatchBrowserEvent('abrircompartir');
        $key = base64_encode('@kf#'.$data['id']);
        $data['url_1'] = route('ver.archivo', $key);
        $this->url_whatsapp = urlencode('¡Gracias por comprar con '. $this->Setting .'!') .  '%0d%0a' . 
            urlencode('Evento: ' . $data['evento']) .  '%0d%0a' . 
            urlencode('Entrada: ' . $data['zona']['name'] . ' #'.$data['identificador']) .  '%0d%0a' . 
            urlencode('Descarga tu entrada: ') . $data['url_1'];
        $this->telefono_cliente =  $data['cliente']['phone'];
        $this->correo_cliente =  $data['cliente']['email'];
    }

    public function enviarsms(){
        $data = array(
            'telefono' => $this->telefono_cliente,
            'mensaje' => $this->url_whatsapp,
        );
        $data = (new ApiController)->sendsms3($data);
        $this->dispatchBrowserEvent('enviadosms');
    }
    
    public function limpiar(){
        $this->dispatchBrowserEvent('abrirventa');
        $this->reset(['url_whatsapp', 'telefono_cliente', 'correo_cliente']);
    }    
    
    
    public function getSettingProperty(){
        return Setting::findorfail(1)->app_name;
    } 

}
