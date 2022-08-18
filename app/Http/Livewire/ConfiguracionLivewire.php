<?php

namespace App\Http\Livewire;

use App\Models\Currency;
use App\Models\RedesSociales;
use Exception;
use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class ConfiguracionLivewire extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['borrado'];
    public $nombre_app, $descripcion_web, $correo_app, $logo, $logo_actual, $logo_oscuro, $logo_oscuro_actual, $icono, $icono_actual;
    public $habilitar_facebook = false, $facebook, $habilitar_instagram = false, $instagram, $habilitar_tiktok = false, $tiktok, $habilitar_youtube = false, $youtube;
    public $verificar_usuario = false, $verificar_telefono = false, $verificar_email = false;
    public $habilitar_sms = false, $cuenta_labsmobile, $token_labsmobile, $contador_sms = 0;
    public $habilitar_notificaciones = false, $app_id_user, $project_number_user, $api_key_user, $auth_key_user, $app_id_organizador, $project_number_organizador, $api_key_organizador, $auth_key_organizador;
    public $divisa, $app_version, $copyright_contenido, $color_principal, $color_secundario;
    public $readytoload = false;

    public function render()
    {
        return view('livewire.configuracion-livewire');
    }

    public function loadDatos(){

        $this->readytoload = true;
        $this->nombre_app = $this->Setting->app_name;
        $this->descripcion_web = $this->Setting->descripcion;
        $this->correo_app = $this->Setting->email;
        $this->logo_actual = $this->Setting->logo;
        $this->logo_oscuro_actual = $this->Setting->logo_dark;
        $this->icono_actual = $this->Setting->favicon;

        $this->facebook = $this->Redes->facebook;
        if($this->Redes->estado_facebook == 1){
            $this->habilitar_facebook = true;
        }
        $this->instagram = $this->Redes->instagram;
        if($this->Redes->estado_instagram == 1){
            $this->habilitar_instagram = true;
        }
        $this->tiktok = $this->Redes->tiktok;
        if($this->Redes->estado_tiktok == 1){
            $this->habilitar_tiktok = true;
        }
        $this->youtube = $this->Redes->youtube;
        if($this->Redes->estado_youtube == 1){
            $this->habilitar_youtube = true;
        }
        if($this->Setting->user_verify == 1){
            $this->verificar_usuario = true;
        }

        if($this->Setting->verify_by == 'phone'){
            $this->verificar_telefono = true;
            $this->verificar_email = false;
        }else{
            $this->verificar_telefono = false;
            $this->verificar_email = true;
        }
        if($this->Setting->sms_notification == 1){
            $this->habilitar_sms = true;
        }
        $this->cuenta_labsmobile = $this->Setting->labsmobile_account;
        $this->token_labsmobile = $this->Setting->labsmobile_token;
        $this->contador_sms = $this->Setting->contador_sms;
        if($this->Setting->push_notification == 1){
            $this->habilitar_notificaciones = true; 
        }
        $this->app_id_organizador = $this->Setting->or_onesignal_app_id; 
        $this->project_number_organizador = $this->Setting->or_onesignal_project_number; 
        $this->api_key_organizador = $this->Setting->or_onesignal_api_key; 
        $this->auth_key_organizador = $this->Setting->or_onesignal_auth_key;

        $this->app_id_user = $this->Setting->onesignal_app_id; 
        $this->project_number_user = $this->Setting->onesignal_project_number; 
        $this->api_key_user = $this->Setting->onesignal_api_key; 
        $this->auth_key_user = $this->Setting->onesignal_auth_key;

        $this->divisa = $this->Setting->currency;
        $this->app_version = $this->Setting->app_version;
        $this->copyright_contenido = $this->Setting->footer_copyright;
        $this->color_principal = $this->Setting->primary_color;
        $this->color_secundario = $this->Setting->secondary_color;        

    }

    public function storegeneral(){
        $this->validate([
            'nombre_app' => 'required|min:2|max:120',
            'descripcion_web' => 'required|max:160',
            'correo_app' => 'required|min:2|max:120|email',
            'logo' => 'nullable|image|max:5500|dimensions:width=214,height=60',
            'logo_oscuro' => 'nullable|image|max:5500|dimensions:width=214,height=60',
            'icono' => 'nullable|image|max:5500|dimensions:width=512,height=512'
        ]);

        DB::beginTransaction();
        try {
            $this->Setting->app_name = $this->nombre_app;
            $this->Setting->descripcion = $this->descripcion_web;
            $this->Setting->email = $this->correo_app;
            if ($this->logo) {
                $imgname2 = Str::slug( Str::limit('logo', 6, '') ) . '-'. Str::random(4);
                $imageName2 = $imgname2 . '.' . $this->logo->extension();
                $this->logo->storeAs('public', $imageName2, 'custom');
                $this->Setting->logo = $imageName2;
            }
            if ($this->logo_oscuro) {
                $imgname2 = Str::slug( Str::limit('logo_oscuro', 6, '') ) . '-'. Str::random(4);
                $imageName2 = $imgname2 . '.' . $this->logo_oscuro->extension();
                $this->logo_oscuro->storeAs('public', $imageName2, 'custom');
                $this->Setting->logo_dark = $imageName2;
            }
            if ($this->icono) {
                $imgname2 = Str::slug( Str::limit('icono', 6, '') ) . '-'. Str::random(4);
                $imageName2 = $imgname2 . '.' . $this->icono->extension();
                $this->icono->storeAs('public', $imageName2, 'custom');
                $this->Setting->favicon = $imageName2;
            }
            $this->Setting->update();
            DB::commit();    
            $this->dispatchBrowserEvent('guardado');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function storeredes(){
        $this->validate([
            'habilitar_facebook' => 'required',
            'facebook' => 'nullable|url',
            'habilitar_instagram' => 'required',
            'instagram' => 'nullable|url',
            'habilitar_tiktok' => 'required',
            'tiktok' => 'nullable|url',
            'habilitar_youtube' => 'required',
            'youtube' => 'nullable|url'
        ]);

        DB::beginTransaction();
        try {
            $this->Redes->facebook = $this->facebook;
            $this->Redes->instagram = $this->instagram;
            $this->Redes->tiktok = $this->tiktok;
            $this->Redes->youtube = $this->youtube;

            if($this->habilitar_facebook == true){
                $this->Redes->estado_facebook = 1;
            }else{
                $this->Redes->estado_facebook = 0;
            }

            if($this->habilitar_instagram == true){
                $this->Redes->estado_instagram = 1;
            }else{
                $this->Redes->estado_instagram = 0;
            }

            if($this->habilitar_tiktok == true){
                $this->Redes->estado_tiktok = 1;
            }else{
                $this->Redes->estado_tiktok = 0;
            }

            if($this->habilitar_youtube == true){
                $this->Redes->estado_youtube = 1;
            }else{
                $this->Redes->estado_youtube = 0;
            }
            $this->Redes->update();
            DB::commit();    
            $this->dispatchBrowserEvent('guardado');
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function storeverificacion(){
        $this->validate([
            'verificar_usuario' => 'required',
            'verificar_telefono' => 'required',
            'verificar_email' => 'required'
        ]);

        
        DB::beginTransaction();
        try {
            if($this->verificar_usuario == true){
                $this->Setting->user_verify = 1;
            }else{
                $this->Setting->user_verify = 0;
            }

            if($this->verificar_email == true && $this->verificar_telefono == false){
                $this->Setting->verify_by = 'email';
            }elseif($this->verificar_email == false && $this->verificar_telefono == true){
                $this->Setting->verify_by = 'phone';
            }
            $this->Setting->update();
            DB::commit();    
            $this->dispatchBrowserEvent('guardado');
        }catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function storesms(){
        $this->validate([
            'habilitar_sms' => 'required',
            'cuenta_labsmobile' => 'required|min:2|max:120',
            'token_labsmobile' => 'required|min:2|max:220',
        ]);

        DB::beginTransaction();
        try {
            if($this->habilitar_sms == true){
                $this->Setting->sms_notification = 1;
            }else{
                $this->Setting->sms_notification = 0;
            }
            $this->Setting->labsmobile_account = $this->cuenta_labsmobile;
            $this->Setting->labsmobile_token = $this->token_labsmobile;
            $this->Setting->update();
            DB::commit();    
            $this->dispatchBrowserEvent('guardado');
        }catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function storenotificacionpush(){
        $this->validate([
            'habilitar_notificaciones' => 'required',
            'app_id_user' => 'nullable|string|max:220',
            'project_number_user' => 'nullable|string|max:220',
            'api_key_user' => 'nullable|string|max:220',
            'auth_key_user' => 'nullable|string|max:220',
            'app_id_organizador' => 'nullable|string|max:220',
            'project_number_organizador' => 'nullable|string|max:220',
            'api_key_organizador' => 'nullable|string|max:220',
            'auth_key_organizador' => 'nullable|string|max:220'
        ]);

        DB::beginTransaction();
        try {
            if($this->habilitar_notificaciones == true){
                $this->Setting->push_notification = 1;
            }else{
                $this->Setting->push_notification = 0;
            }
            $this->Setting->or_onesignal_app_id =  $this->app_id_organizador; 
            $this->Setting->or_onesignal_project_number = $this->project_number_organizador; 
            $this->Setting->or_onesignal_api_key = $this->api_key_organizador; 
            $this->Setting->or_onesignal_auth_key = $this->auth_key_organizador;
            $this->Setting->onesignal_app_id = $this->app_id_user; 
            $this->Setting->onesignal_project_number = $this->project_number_user;
            $this->api_key_user = $this->Setting->onesignal_api_key; 
            $this->auth_key_user = $this->Setting->onesignal_auth_key;
            $this->Setting->update();
            DB::commit();    
            $this->dispatchBrowserEvent('guardado');
        }catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function storeadicional(){
        $this->validate([
            'divisa' => 'required',
            'app_version' => 'requried|max:120',
            'copyright_contenido' => 'required|max:120',
            'color_principal' => 'nullable',
            'color_secundario' => 'nullable'
        ]);

        DB::beginTransaction();
        try {
            $this->Setting->currency = $this->divisa;
            $this->Setting->app_version = $this->app_version;
            $this->Setting->footer_copyright = $this->copyright_contenido;
            $this->Setting->primary_color = $this->color_principal;
            $this->Setting->secondary_color = $this->color_secundario;
            $this->Setting->update();
            DB::commit();    
            $this->dispatchBrowserEvent('guardado');
        }catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function verifica($id){
       
        if($id == 'telefono'){
            $this->verificar_telefono = true;
            $this->verificar_email = false;
        }elseif($id == 'email'){
            
            $this->verificar_telefono = false;
            $this->verificar_email = true;
        }
    }

    public function getSettingProperty(){
        return Setting::findorfail(1);
    }

    public function getRedesProperty(){
        return RedesSociales::findorfail(1);
    }

    public function getMonedasProperty(){
        return Currency::all();
    }
}
