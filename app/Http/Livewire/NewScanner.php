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

class NewScanner extends Component
{
    public $users, $organizador, $nombre_scanner, $apellido_scanner, $email, $phone, $password;
    public $notificacion = false;
    protected $listeners = ['actualizarusuarios2' ];
    public function mount($users)
    {
        $this->users = $users;
    }

    public function render()
    {
        return view('livewire.eventos.new-scanner');
    }

    public function crearscanner()
    {      
        $this->validate([
            'organizador' => 'required',
            'nombre_scanner' => 'required',
            'apellido_scanner' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6',
        ], [
            'nombre_scanner.required' => 'El nombre del scanner es requerido',
            'apellido_scanner.required' => 'El apellido del scanner es requerido',
            'phone.required' => 'El telefono es requerido',
            'phone.unique' => 'El telefono ya existe en la base de datos'
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'first_name' => $this->nombre_scanner,
                'last_name' => $this->apellido_scanner,
                'email' => $this->email,
                'phone' => $this->phone,
            ];
            $data['org_id'] = $this->organizador;
            $data['password'] =  Hash::make($this->password);
            $data['borrado'] = '0';
            $user = User::create($data);
            $user->assignRole('scanner');
            $value = 'El Scanner ha sido creado con exito';
            $this->dispatchBrowserEvent('success', ['mensaje' => $value]);
            $this->emit('actualizardatos');
    
            if ($this->notificacion == true) {
                if ($this->setting->sms_notification) {
                    $org = User::findorfail($this->organizador);
                    $data = array(
                        'telefono' => $org->phone,
                        'mensaje' => urlencode(Str::upper($this->setting->app_name) . ': ') . '%0d%0a' . urlencode('¡Su scanner ha sido creado con exito!') . '%0d%0a' .
                        urlencode('Telefono de acceso: ') . urlencode($this->phone) . '%0d%0a' .
                        urlencode('Contraseña: ') . urlencode($this->password) . '%0d%0a' 
                    );
                    $data = (new ApiController)->sendsms($data);    
                }
                $this->notificacion == false;
                $this->setting->contador_sms++;
                $this->setting->update();
            }
            DB::commit();
            $this->resetInput();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
        
    }
    private function resetInput()
    {
        $this->organizador = null;
        $this->nombre_scanner = null;
        $this->apellido_scanner = null;
        $this->email = null;
        $this->phone = null;
        $this->password = null;
    }

    public function actualizarusuarios2()
    {
        $this->users = User::role('organization')->orderBy('first_name', 'ASC')->get();
    }

    public function genrardatosScanner()
    {
        $this->phone = '+57' . rand(390, 396) . rand(100000, 9999999);
        $this->password = Str::random(10);

        if ($this->nombre_scanner && $this->apellido_scanner == '') {
            $this->email = Str::slug(Str::words($this->nombre_scanner, 2, '...')) . Str::lower(Str::random(5)) . '@example.com';
        } elseif ($this->nombre_scanner == '' && $this->apellido_scanner) {
            $this->email = Str::slug(Str::words($this->apellido_scanner, 2, '...')) . Str::lower(Str::random(5)) . '@example.com';
        } elseif ($this->nombre_scanner && $this->apellido_scanner) {
            $this->email = Str::slug(Str::words($this->apellido_scanner, 2, '...')) . Str::lower(Str::random(5)) . Str::slug(Str::words($this->nombre_scanner, 2, '...')) . '@example.com';
        } else {
            $this->email = Str::random(8) . '@example.com';
        }
    }

    public function getSettingProperty()
    {
        return Setting::findorfail(1);
    }
}
