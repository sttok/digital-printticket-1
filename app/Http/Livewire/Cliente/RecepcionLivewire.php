<?php

namespace App\Http\Livewire\Cliente;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\AppUser;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\DigitalOrdenCompra;

class RecepcionLivewire extends Component
{
    public $codigo;
    public $cedula;
    public $errors;
    protected $queryString = [
        'errors' => ['except' => '', 'as' => 'errors'],
    ];
    

    public function mount($id){
        $this->codigo = $id;
    }

    public function render()
    {
        return view('livewire.cliente.recepcion-livewire');
    }

    public function loadDatos(){
        if ($this->errors != '') {
            $this->dispatchBrowserEvent('errores', ['error' => __($this->errors)]);
            $this->reset('errors');
        }
    }

    public function validar(){
        $this->validate([
            'cedula' => 'required|min:5|max:15'
        ]);

        $id = base64_decode($this->codigo);
        $id = Str::after($id, '@kf#');
        $orden_compra = DigitalOrdenCompra::where('identificador', $id)->first();

        if($orden_compra){  
            $cliente = AppUser::find($orden_compra->cliente_id);
            if($cliente){
                if($cliente->cedula == $this->cedula){
                    $key = env('APP_KEY');
                    $payload = [
                        "iss"=> route('inicio.frontend'),
                        "aud"=> route('inicio.frontend'),
                        "iat"=> Carbon::now()->timestamp,
                        "exp"=> Carbon::now()->addHours(2)->timestamp,
                        "data" => [
                            'cliente' => $cliente->id,
                            'orden_compra' => $orden_compra->id,
                            'exp'=> Carbon::now()->addHours(2),
                        ]
                    ];
                    $jwt = JWT::encode($payload, $key, 'HS256');

                    return redirect()->route('ordencompra.cliente', ['base64' => urlencode($this->codigo), 'token' => urlencode($jwt)]);
                }else{
                    $this->dispatchBrowserEvent('errores', ['error' => __('Esta orden de compra no esta asignada a esta cedula, verifica los datos y intentalo nuevamente')]);
                }
            }else{
                $this->dispatchBrowserEvent('errores', ['error' => __('No se ha encontrado el cliente, contacta al administrador')]);
            }
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('La orden de compra no existe, verifica los datos y intentalo nuevamente')]);
        }
    }
}
