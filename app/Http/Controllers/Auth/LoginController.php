<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticate(Request $request)
    {
        $validator = $request->validate([
            'password' => 'required|string',
            'phone' => ['required', 'string'],
        ], [
            'password.required' => 'La contraseña es obligatoria',
            'password.string' => 'El telefono es obligratorio',
            'phone.required' => 'El campo del telefono no puede quedar vacio',
            'phone.regex' => 'El formato del telefono no es valido',
        ]);

        $telefono = $request['phone'];
        $password = $request['password'];
        $remember = $request['remember-me'];

        if (Auth::attempt(['telefono' => $telefono, 'password' => $password, 'borrado' => 0, 'activo' => 1], $remember)) {
            // Authentication passed...
            $request->session()->regenerate();
            return redirect()->intended('/');
        } else {
            return back()->withErrors([
                'telefono' => 'Los datos no concuerdan con nuestra base de datos',
            ]);
        }
    }
}
