<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\User;
use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;

class NewPuntoVenta extends Component
{
    public $users, $organizador, $nombre_pventa, $apellido_pventa, $email, $phone, $password;
    public $notificacion = false;
    protected $listeners = ['actualizarusuarios' => 'actualizarusuarios'];

    public function mount($users)
    {
        $this->users = $users;
    }

    public function render()
    {
        return view('livewire.eventos.new-punto-venta');
    }

    public function crearpuntodeventa()
    {
        $this->validate([
            'organizador' => 'required',
            'nombre_pventa' => 'required',
            'apellido_pventa' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6',
        ], [
            'nombre_pventa.required' => 'El nombre del scanner es requerido',
            'apellido_pventa.required' => 'El apellido del scanner es requerido',
            'phone.required' => 'El telefono es requerido',
            'phone.unique' => 'El telefono ya existe en la base de datos'
        ]);

        DB::beginTransaction();
        try {   
            $data = [
                'first_name' => $this->nombre_pventa,
                'last_name' => $this->apellido_pventa,
                'email' => $this->email,
                'phone' => $this->phone,
            ];
            $data['org_id'] = $this->organizador;
    
            $data['password'] =  Hash::make($this->password);
            $data['borrado'] = '0';
            $user = User::create($data);
            $user->assignRole('punto venta');
            $value = 'El punto de venta ha sido creado con exito';
            $this->dispatchBrowserEvent('success', ['mensaje' => $value]);
            $this->emit('actualizardatos');
    
            if ($this->notificacion == true) {
                if ($this->setting->sms_notification) {
                    $org = User::findorfail($this->organizador);
                    $data = array(
                        'telefono' => $org->phone,
                        'mensaje' =>urlencode(Str::upper($this->setting->app_name) . ': ') . '%0d%0a' . urlencode('¡Su punto de venta ha sido creado con exito!') . '%0d%0a' .
                        urlencode('Telefono de acceso: ') . urlencode($this->phone) . '%0d%0a' .
                        urlencode('Contraseña: ') . urlencode($this->password) . '%0d%0a' 
                    );
                    $data = (new ApiController)->sendsms($data);
                }
                $this->notificacion == false;
                $this->setting->contador_sms++;
                $this->setting->update();
            }    
            $this->resetInput();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }


    private function resetInput()
    {
        $this->organizador = null;
        $this->nombre_pventa = null;
        $this->apellido_pventa = null;
        $this->email = null;
        $this->phone = null;
        $this->password = null;
    }

    public function actualizarusuarios()
    {
        $this->users = User::role('organization')->orderBy('first_name', 'ASC')->get();
    }

    public function genrardatosPventa()
    {
        $this->phone = '+57' . rand(390, 396) . rand(100000, 9999999);
        $this->password = Str::random(10);

        if ($this->nombre_pventa && $this->apellido_pventa == '') {
            $this->email = Str::slug(Str::words($this->nombre_pventa, 2, '...')) . Str::lower(Str::random(5)) . '@example.com';
        } elseif ($this->nombre_pventa == '' && $this->apellido_pventa) {
            $this->email = Str::slug(Str::words($this->apellido_pventa, 2, '...')) . Str::lower(Str::random(5)) . '@example.com';
        } elseif ($this->nombre_pventa && $this->apellido_pventa) {
            $this->email = Str::slug(Str::words($this->apellido_pventa, 2, '...')) . Str::lower(Str::random(5)) . Str::slug(Str::words($this->nombre_pventa, 2, '...')) . '@example.com';
        } else {
            $this->email = Str::random(8) . '@example.com';
        }
    }

    public function getSettingProperty()
    {
        return Setting::findorfail(1);
    }
}
