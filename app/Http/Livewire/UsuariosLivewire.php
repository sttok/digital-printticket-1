<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\User;
use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;

class UsuariosLivewire extends Component
{

    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['borrado'];
    public $search = '', $search_estado = '', $search_rol = '';
    public $nombre_usuario, $apellido_usuario, $correo_usuario, $imagen, $imagen_actual, $telefono, $prefijo_telefono = '+57', $phone, $contraseña_usuario, $notificar_nuevo = false, $roles_usuario = [];
    public $usuario_id, $updatemode = false, $organizador_id;
    public $readytoload = false;
    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'search_estado' => ['except' => '', 'as' => 'estado'],
        'search_rol' => ['except' => '', 'as' => 'rol'],
        'page' => ['except' => 1, 'as' => 'p'],
    ];
    
    public function render()
    {        
        $this->dispatchBrowserEvent('cargarimagen');
        return view('livewire.usuarios-livewire');
    }

    public function store(){  
        $this->phone = $this->prefijo_telefono . $this->telefono;
        $this->validate([
            'nombre_usuario' => 'required|min:2|max:120',
            'apellido_usuario' => 'required|min:2|max:120',
            'correo_usuario' => 'nullable|email|unique:users,email',
            'imagen' => 'nullable|image|max:5048|dimensions:width=1080,height=1080',
            'prefijo_telefono' => 'required',
            'telefono' => 'required|integer',
            'phone' => 'required|phone:AUTO|unique:users,phone',
            'contraseña_usuario' => 'required|min:3|max:120',
            'notificar_nuevo' => 'required',
            'roles_usuario' => 'required|array|min:1'
        ]);

        DB::beginTransaction();
        try {
            $usuario = new User();
                $usuario->first_name = $this->nombre_usuario;
                $usuario->last_name = $this->apellido_usuario;
                $usuario->email = $this->correo_usuario;
                $usuario->password = Hash::make($this->contraseña_usuario);
                $usuario->phone = $this->phone;
                if ($this->imagen) {
                    $imgname2 = Str::slug( Str::limit($this->nombre_usuario, 6, '') ) . '-'. Str::random(4);
                    $imageName2 = $imgname2 . '.' . $this->imagen->extension();
                    $this->imagen->storeAs('perfil', $imageName2, 'custom');
                    $usuario->image = $imageName2;
                }else{
                    $usuario->image = 'defaultuser.png';
                }
                $usuario->status = 1;
                $usuario->borrado = 0;
            $usuario->save();
            $usuario->assignRole($this->roles_usuario);
            
            DB::commit();
            if($this->notificar_nuevo == true){
                $telefono = $this->phone;
                $mensaje =  urlencode( 'Bienvenido a ' .Str::upper($this->setting->app_name) . ': ') . '%0d%0a' . 
                urlencode('Nombre: ' . Str::title( $this->nombre_usuario . ' ' . $this->apellido_usuario )) . '%0d%0a' .
                urlencode('Telefono de acceso: ') . urlencode($this->phone) . '%0d%0a' .
                urlencode('Contraseña: ') . urlencode($this->contraseña_usuario)  . '%0d%0a' ;
                $this->enviarsms($telefono, $mensaje);
            }
            
            $this->dispatchBrowserEvent('storeusuario');
            $this->limpiar();
            $this->reset('roles_usuario');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function agregarrol($id){
        $r = array_search($id, array_column($this->roles_usuario, 'id'));
       
        if($r != false){            
            unset($this->roles_usuario[$r]);           
        }else{            
            $temporal = array(
                'id' => $id
            );
            array_push($this->roles_usuario, $temporal);
        }
    }


    public function edit($id){
        $this->usuario_id = $id;
        if($this->Usuario != ''){
            $this->updatemode = true;
            $this->reset('roles_usuario');
            $this->nombre_usuario = $this->Usuario->first_name;
            $this->apellido_usuario = $this->Usuario->last_name;
            $this->correo_usuario = $this->Usuario->email;
            $this->imagen_actual = $this->Usuario->image;
            try {
                $te = phone($this->Usuario->phone, $country = [], $format = null);
                $pais = $te->getCountry();
            } catch (Exception $e) {
                $pais = 'CO';
            }      
            if($pais == 'CO'){
                $this->telefono =   Str::remove('+57', $this->Usuario->phone) ;
                $this->prefijo_telefono = '+57';
            }elseif($pais == 'US'){
                $this->telefono =   Str::remove('+1', $this->Usuario->phone) ;
                $this->prefijo_telefono = '+1';
            }elseif($pais == 'CL'){
                $this->telefono =   Str::remove('+56', $this->Usuario->phone) ;
                $this->prefijo_telefono = '+56';
            }elseif($pais == 'ECU'){
                $this->telefono =   Str::remove('+593', $this->Usuario->phone) ;
                $this->prefijo_telefono = '+593';
            }elseif($pais == 'MX'){
                $this->telefono =   Str::remove('+52', $this->Usuario->phone) ;
                $this->prefijo_telefono = '+52';
            }elseif($pais == 'ESP'){
                $this->telefono =   Str::remove('+34', $this->Usuario->phone) ;
                $this->prefijo_telefono = '+34';
            }elseif($pais == 'PAN'){
                $this->telefono =   Str::remove('+507', $this->Usuario->phone) ;
                $this->prefijo_telefono = '+507';
            }
            foreach($this->Usuario->getRoleNames() as $r){
                $this->agregarrol($r);
            }
        }
    }

    public function edit2($id){
        $this->usuario_id = $id;
        if($this->Usuario != ''){
            $this->updatemode = true;
            $this->reset('roles_usuario');
            $this->nombre_usuario = $this->Usuario->first_name;
            $this->apellido_usuario = $this->Usuario->last_name;
            $this->correo_usuario = $this->Usuario->email;
            $this->imagen_actual = $this->Usuario->image;
            try {
                $te = phone($this->Usuario->phone, $country = [], $format = null);
                    $pais = $te->getCountry();
            } catch (Exception $e) {
                $pais = 'CO';
            }
           
            if($pais == 'CO'){
                $this->telefono =   Str::remove('+57', $this->Usuario->phone) ;
                $this->prefijo_telefono = '+57';
            }elseif($pais == 'US'){
                $this->telefono =   Str::remove('+1', $this->Usuario->phone) ;
                $this->prefijo_telefono = '+1';
            }elseif($pais == 'CL'){
                $this->telefono =   Str::remove('+56', $this->Usuario->phone) ;
                $this->prefijo_telefono = '+56';
            }elseif($pais == 'ECU'){
                $this->telefono =   Str::remove('+593', $this->Usuario->phone) ;
                $this->prefijo_telefono = '+593';
            }elseif($pais == 'MEX'){
                $this->telefono =   Str::remove('+52', $this->Usuario->phone) ;
                $this->prefijo_telefono = '+52';
            }elseif($pais == 'ESP'){
                $this->telefono =   Str::remove('+34', $this->Usuario->phone) ;
                $this->prefijo_telefono = '+34';
            }elseif($pais == 'PAN'){
                $this->telefono =   Str::remove('+507', $this->Usuario->phone) ;
                $this->prefijo_telefono = '+507';
            }
            foreach($this->Usuario->getRoleNames() as $r){
                $this->agregarrol($r);
            }
            $this->dispatchBrowserEvent('edit2');
        }
    }

    public function quitar($id){
        $this->usuario_id = $id;
        if($this->Usuario != ''){
            $this->Usuario->org_id = '';
            $this->Usuario->update();
            $this->dispatchBrowserEvent('quitarorganizador');
        }
    }

    public function actualizar(){
        $this->phone = $this->prefijo_telefono . $this->telefono;
        $this->validate([
            'nombre_usuario' => 'required|min:2|max:120',
            'apellido_usuario' => 'required|min:2|max:120',
            'correo_usuario' =>  ['nullable', 'string', 'email', Rule::unique('users', 'email')->ignore($this->usuario_id)],
            'imagen' => 'nullable|image|max:5048|dimensions:width=1080,height=1080',
            'prefijo_telefono' => 'required',
            'telefono' => 'required|integer',
            'phone' => ['required', 'string', 'phone:AUTO', Rule::unique('users')->ignore($this->usuario_id)] ,
            'contraseña_usuario' => 'nullable|min:3|max:120',
            'roles_usuario' => 'required|array|min:1'
        ]);

        DB::beginTransaction();
        try {            
            $this->Usuario->name = $this->nombre_usuario;
            $this->Usuario->last_name = $this->apellido_usuario;
            $this->Usuario->email = $this->correo_usuario;
            if($this->contraseña_usuario != ''){
                $this->Usuario->password = Hash::make($this->contraseña_usuario);
            }         
            $this->Usuario->phone = $this->phone;
            if ($this->imagen) {
                $imgname2 = Str::slug( Str::limit($this->nombre_usuario, 6, '') ) . '-'. Str::random(4);
                $imageName2 = $imgname2 . '.' . $this->imagen->extension();
                $this->imagen->storeAs('perfil', $imageName2, 'custom');
                $this->Usuario->image = $imageName2;
            }
            $this->Usuario->update();
            $this->Usuario->syncRoles($this->roles_usuario);
           

            DB::commit();
            $this->dispatchBrowserEvent('updateusuario');
            $this->limpiar();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function organizadorr($id){
        $this->organizador_id = $id;
        

        if($this->Organizadoruser->hasRole('organization')){
            $this->dispatchBrowserEvent('abrirorganizador');
        }
    }

    public function estado($id){
        $this->usuario_id = $id;
        if($this->Usuario != ''){
            if($this->Usuario->status == 1){
                $this->Usuario->Status =2;

            }else{
                $this->Usuario->status = 1;
            }
            $this->Usuario->update();
            $this->dispatchBrowserEvent('estadocambiado');
        }
    }

    public function borrar($id){
        $this->usuario_id = $id;
        $this->dispatchBrowserEvent('borrar');
    }

    public function borrado(){

        if($this->Usuario != ''){
            $this->Usuario->phone = base64_encode($this->Usuario->phone); 
            $this->Usuario->email = base64_encode($this->Usuario->email);
            $this->Usuario->status = 2;
            $this->Usuario->borrado = 1;
            $this->Usuario->update();
        }
    }

    public function enviarsms($telefono, $mensaje){
        $data = array(
            'telefono' => $telefono,
            'mensaje' => $mensaje,
        );
        $data = (new ApiController)->sendsms($data);
    }

    public function limpiar(){
        $this->reset([
            'nombre_usuario', 'apellido_usuario', 'correo_usuario', 'imagen', 'prefijo_telefono', 'telefono', 'phone',
            'contraseña_usuario', 'notificar_nuevo', 'roles_usuario'
        ]);
    }

    public function loadDatos(){
        $this->readytoload = true;
        $this->dispatchBrowserEvent('cargarimagen');
       
    }

    public function updatedSearch(){
        $this->resetpage();
    }

    public function updatedSearchEstado(){
        $this->resetpage();
    }

    public function updatedSearchRol(){
        $this->resetpage();
    }

    public function getUsuariosProperty(){

        if($this->search_rol != ''){
            $user = User::role($this->search_rol)
            ->where([
                ['first_name', 'LIKE', '%'.$this->search.'%'], ['status', 'LIKE', '%'.$this->search_estado], ['borrado', 0]
            ])->paginate(12);
        }else{
            $user = User::where([
                ['first_name', 'LIKE', '%'.$this->search.'%'], ['status', 'LIKE', '%'.$this->search_estado], ['borrado', 0]
            ])->orWhere([
                ['last_name', 'LIKE', '%'.$this->search.'%'], ['status', 'LIKE', '%'.$this->search_estado], ['borrado', 0]
            ])->orWhere([
                ['email', 'LIKE', '%'.$this->search.'%'], ['status', 'LIKE', '%'.$this->search_estado], ['borrado', 0]
            ])->orWhere([
                ['phone', 'LIKE', '%'.$this->search.'%'], ['status', 'LIKE', '%'.$this->search_estado], ['borrado', 0]
            ])
            ->paginate(12);
        }
        return $user;
    }

    public function getUsuarioProperty(){
        return User::findorfail($this->usuario_id);
    }

    public function getOrganizadoruserProperty(){
        return User::findorfail($this->organizador_id);
    }

    public function getUsuarioorganizadorProperty(){
        return User::where('org_id', $this->organizador_id)->get();
    }

    public function getSettingProperty()
    {
        return Setting::findorfail(1);
    } 

    public function getRolesProperty(){
       return Role::all()->pluck('name');
    }
}
