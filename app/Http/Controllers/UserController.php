<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('backendv2.usuarios.index');
    }

    public function login()
    {
        if (!Auth::check()) {
            $config = Setting::find(1);
            $data = array(
                'titulo' => $config->app_name,
                'logo' => $config->logo,
                'icono' => $config->favicon,
                'fondo' => $config->fondo_digital
            );
            return view('backendv2.auth.login', compact('data'));
        } else {
            return redirect()->back();
        }
    }

    public function adminLogin(Request $request)
    {
        if (!Auth::check()) {

            $request['phone'] = $request['prefijo'] . $request['telefono'];
            $request->validate([
                'phone' => 'bail|required|phone:CO,AUTO',
                'password' => 'bail|required',
                'prefijo' => 'required'
            ]);
            $userdata = array(
                'phone' => $request->phone,
                'password' => $request->password,
                'status' => '1',
                'borrado' => '0'
            );
            $remember = $request->get('remember');
            if (Auth::attempt($userdata, $remember)) {
                $request->session()->regenerate();

                if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('Superadmin')) {
                    return redirect()->route('index.eventos');
                } elseif (Auth::user()->hasRole('organization') || Auth::user()->hasRole('punto venta')) {

                    return redirect()->route('mis.eventos');
                } else {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('iniciar');
                }
            } else {
                return Redirect::back()->with('error', __('Usuario o contraseña invalido'));
            }
        } else {
            if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('Superadmin')) {
                return redirect()->route('index.eventos');
            } elseif (Auth::user()->hasRole('organization') || Auth::user()->hasRole('punto venta')) {
                return redirect()->route('mis.eventos');
            } else {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('iniciar');
            }
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('iniciar');
        } else {
            return redirect()->route('iniciar');
        }
    }
}
