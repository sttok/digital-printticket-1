<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\OrderChildsDigital;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\ContadorVisitaGeneral;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect()->route('index.eventos');
    }

    public function changeLanguage($lang)
    {
        App::setLocale($lang);
        session()->put('locale', $lang);
        return redirect()->back();
    }

    public function logingoogleredirectToProvider()
    {
        
        if(Auth::guest()){
            $parameters = ['access_type' => 'offline'];
            return Socialite::driver('google')
                ->scopes([
                    'https://www.googleapis.com/auth/userinfo.profile', 
                ])
                ->with($parameters)
                ->redirect();
        }else{
            return redirect()->route('inicio');
        }  
       
    }

    public function loginhandleProviderCallback(Request $request)
    {
        if(Auth::guest()){
            $userLogin = Socialite::driver('google')->user();        
            $usuario = User::where([
                ['email', $userLogin->email], ['borrado', 0], ['status', 1]
            ])->first();
         
            if ($usuario) {
                if(Auth::login($usuario)){
                    if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('Superadmin')) {
                        $usuario->device_token = $userLogin->refreshToken;
                        $usuario->update();
                        $request->session()->regenerate();
                        return redirect()->route('index.eventos');
                    }else{
                        Auth::logout();
                        return redirect()->route('iniciar')->with('error_msg', __('Solo la persona autorizada puede iniciar sesion.'));
                    }
                  
                }else{
                    return  redirect()->route('inicio')->withErrors([
                        'error_msg' => 'Usuario no registrado',
                    ]);
                }            
            } else {              
                return  redirect()->route('iniciar')->withErrors([
                    'error_msg' => 'Usuario no registrado',
                ]);
            }
        }else{
            return  redirect()->route('inicio')->withErrors([
                'error_msg' => 'Usuario no registrado',
            ]);
        }
        
        
    }

    public function indexeventos(){
        abort_if(Gate::denies('event_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('backendv2.eventos.index');
    }

    public function showevento($id){
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('Superadmin') || Auth::user()->hasRole('organization')) {
             return view('backendv2.eventos.show', compact('id'));
        }else{
            abort(403);
        }
       
    }

    public function miseventos(){
        //abort_if(Gate::denies('asignar_ticket'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('Superadmin') || Auth::user()->hasRole('organization')) {
             return view('backendv2.miseventos.index');
        }else{
            abort(403);
        }
       
    }

    public function miseventosshow($id){
        abort_if(Gate::denies('asignar_ticket'), Response::HTTP_FORBIDDEN, '403 Forbidden');
         return view('backendv2.miseventos.detalle', compact('id'));
    }

    public function verarchivo($base64){
        $id = base64_decode($base64);
        $id = Str::after($id, '@kf#');
        $entrada = OrderChildsDigital::findorfail($id);
        
        if($entrada->permiso_descargar == 1){
            if($entrada->provider == "local"){
                    $r = 'storage/ticket-digital/';
                    $url = Str::after($entrada->url, $r) ;
                    if($entrada->descargas == null){
                        $entrada->descargas = 1;
                    }else{
                        $entrada->descargas++;
                    }
                    $entrada->update();
                    return Storage::disk('custom')->download('ticket-digital/'.$url);
        
            }elseif($entrada->provider == 'drive'){
                    $filename = Storage::disk("google")->url($entrada['url']);
                    if($entrada->descargas == null){
                        $entrada->descargas = 1;
                    }else{
                        $entrada->descargas++;
                    }
                    $entrada->update();
                    return redirect()->to($filename);
                    //$dir = Storage::disk("google")->get($entrada['url']);
                   /* return $dir->size();
                    $recursive = false;
                    $contents = collect(Storage::disk("google")->ListContents($dir, $recursive));
                    $file = $contents
                            ->where('type', '=', 'file')
                            //->where('path', '=', $entrada['url'])
                            /*->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
                            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
                            ->first();
                
                    return $file;
                    $rawData = Storage::disk("google")->get($entrada['url']);
                    return response($rawData, 200)
                        ->header('ContentType', Storage::disk("google")->mimetype($entrada['url']))
                        ->header('Content-Disposition', "attachment; filename=$filename");*/
            }
        }else{
            abort(403);
        }
    }
}
