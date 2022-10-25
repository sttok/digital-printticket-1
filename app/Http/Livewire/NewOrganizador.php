<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NewOrganizador extends Component
{
    public  $nombre_organizador, $apellido_organizador, $email, $phone, $password;


    public function render()
    {
        return view('livewire.eventos.new-organizador');
    }

    public function crearorganizador()
    {
        $this->validate([
            'nombre_organizador' => 'required|max:120',
            'apellido_organizador' => 'required|max:120',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6'
        ]);

        DB::beginTransaction();
        try { 
            $data = [
                'first_name' => $this->nombre_organizador,
                'last_name' => $this->apellido_organizador,
                'email' => $this->email,
                'phone' => $this->phone,
            ];
    
            $data['password'] =  Hash::make($this->password);
            $data['borrado'] = '0';
            $user = User::create($data);
            $user->assignRole('organization');
    
            $value = 'El organizador ha sido creado con exito';
            $this->resetInput();
            $this->dispatchBrowserEvent('success', ['mensaje' => $value]);
            $this->emit('actualizarusuarios');
            $this->emit('actualizarusuarios2');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }        
    }

    private function resetInput()
    {
        $this->reset(['nombre_organizador', 'apellido_organizador', 'email', 'phone']);
    }
}
