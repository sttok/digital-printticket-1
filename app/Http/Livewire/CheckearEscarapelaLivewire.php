<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Livewire\Component;
use Illuminate\Support\Str;

class CheckearEscarapelaLivewire extends Component
{
    public $token;
    public $errors;
    public $decodificado, $verificado = false;
    protected $queryString = [
        'errors' => ['except' => '', 'as' => 'errors'],
    ];
    
     public function mount($token){
        $this->token = $token;
    }
    
    public function render()
    {
        return view('livewire.checkear-escarapela-livewire');
    }
    
    public function loadDatos(){
        if ($this->errors != '') {
            $this->dispatchBrowserEvent('errores', ['error' => __($this->errors)]);
            $this->reset('errors');
            $this->verificado = false;
        }else{
            $key = "T3NjYXJNYW4xNCNLZiMyNjU3NTQ5Nw==";
            $jwt = JWT::decode($this->token, new Key($key, 'HS256'));
            $this->decodificado = (array)$jwt->data;
            
            $this->dispatchBrowserEvent('Valido');
            $this->verificado = true;
        }
    }
}
