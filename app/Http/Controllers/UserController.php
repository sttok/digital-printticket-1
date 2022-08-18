<?php

namespace App\Http\Controllers;

use DB;
use App;
use Rave;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\AppUser;
use App\Models\Setting;
use App\Models\ciudades;
use App\Models\EventScanner;
use App\Models\MetodoDePago;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\MetodoDePagoEntrada;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;
use Symfony\Component\Console\Input\Input;
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
                'icono' => $config->favicon
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
                'phone' => 'bail|required|phone:AUTO',
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
                if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('Superadmin')) {
                    return redirect()->route('index.eventos');
                } else {
                    return Redirect::back()->with('error_msg', __('Solo la persona autorizada puede iniciar sesion.'));
                }
            } else {
                return Redirect::back()->with('error_msg', __('Usuario o contraseña invalido'));
            }
        } else {
            return redirect()->back();
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('inicio');
        } else {
            abort(404);
        }
    }




















    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::all();
        $orgs = User::role('organization')->orderBy('id', 'DESC')->get();
        return view('admin.user.create', compact('roles', 'orgs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'first_name' => 'bail|required',
            'last_name' => 'bail|required',
            'email' => 'bail|required|email|unique:users',
            'phone' => 'bail|required|unique:users',
            'password' => 'bail|required|min:6',
        ]);
        $data = $request->all();
        $data['password'] =  Hash::make($request->password);
        $data['org_id'] = $request->organization;
        $data['borrado'] = '0';
        $user = User::create($data);
        $user->assignRole($request->input('roles', []));

        return redirect()->route('users.index')->withStatus(__('User is added successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
        // $user->load('roles');
        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::all();
        $user->load('roles');
        $orgs = User::role('organization')->orderBy('id', 'DESC')->get();

        return view('admin.user.edit', compact('roles', 'user', 'orgs'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        $request->validate([
            'first_name' => 'bail|required',
            'last_name' => 'bail|required',
            'phone' => 'bail|required|unique:users,phone,' . $user->id . ',id',
            'email' => 'bail|required|unique:users,email,' . $user->id . ',id',
        ]);
        $data = $request->all();
        if ($data['password']) {
            $data['password'] =  Hash::make($request->password);
        }

        $data['org_id'] = $request->organization;
        $user->update($data);
        $user->syncRoles($request->input('roles', []));

        return redirect()->route('users.index')->withStatus(__('User is updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        // $user->delete();
        // $user->syncRoles([]);

    }

    public function adminDashboard()
    {

        $master['organizations'] = User::role('organization')->count();
        $master['users'] = AppUser::count();

        $events = Event::where([['status', 1], ['is_deleted', 0]])->orderBy('id', 'DESC')->take(4)->get();
        $day = Carbon::parse(Carbon::now()->year . '-' . Carbon::now()->month . '-01')->daysInMonth;

        foreach ($events as $value) {
            $tickets = Ticket::where('event_id', $value->id)->sum('quantity');
            $sold_ticket = Order::where('event_id', $value->id)->sum('quantity');
            $value->avaliable = $tickets - $sold_ticket;
        }

        return view('admin.dashboard', compact('events',  'master'));
    }


    public function organizationDashboard()
    {
        $config = Setting::findorfail(1);
        $timezone = $config->timezone;
        $date = Carbon::now($timezone);
        $events = Event::where([
            ['status', 1], ['organizador_id', Auth::user()->id], ['is_deleted', 0],
            ['end_time', '>', $date->format('Y-m-d H:i:s')]


        ])->orderBy('id', 'DESC')->get();
        $master['contador_ventas'] = '0';
        $array = [];
        $msg = [];
        $text = [];
        $cantidad = [];

        foreach ($events as $item) {
            $entrada = [];
            $metodo = MetodoDePago::where('event_id', $item->id)->get();
            foreach ($metodo as $item2) {
                $master['contador_ventas'] =  $master['contador_ventas'] + $item2->cantidad_entradas;
                $mpe = MetodoDePagoEntrada::where('metodo_pago_id', $item2->id)->get();
                $mpe = $mpe->makeHidden(['updated_at', 'created_at', 'metodo_pago_id']);
                $item2->entradas = $mpe;

                foreach ($mpe as $item3) {
                    $key = array_search($item3['Detalle']->id, array_column($msg, 'Entrada_id'));
                    if ($key != false || $key === 0) {
                        $f = $msg[$key]['Cantidad'] + 1;
                        $msg[$key]['Cantidad'] = $f;
                    } else {
                        $m = array(
                            'Entrada_id' => $item3['Detalle']->id,
                            'Cantidad' => 1,
                            'Nombre_entrada' => $item3['Detalle']->name,

                        );
                        array_push($text, '"' . $item3['Detalle']->name . '"');

                        array_push($msg, $m);
                    }
                }
                array_push($array, $item2);
            }
        }
        foreach ($msg as $f) {
            array_push($cantidad, $f['Cantidad']);
        }
        $master['metodo_pago'] = $array;
        $master['chart'] = $msg;
        $master['texto'] = $text;
        $master['cantidad'] = $cantidad;


        // return implode(",", $text);

        return view('admin.org_dashboard', compact('events', 'master'));
    }

    public function viewProfile()
    {
        return view('admin.profile');
    }

    public function editProfile(Request $request)
    {
        User::find(Auth::user()->id)->update($request->all());
        return redirect('profile')->withStatus(__('Profile is updated successfully.'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'bail|required',
            'password' => 'bail|required|min:6',
            'confirm_password' => 'bail|required|same:password|min:6'
        ]);

        if (Hash::check($request->current_password, Auth::user()->password)) {
            User::find(Auth::user()->id)->update(['password' => Hash::make($request->password)]);
            return redirect('profile')->withStatus(__('Password is updated successfully.'));
        } else {
            return Redirect::back()->with('error_msg', 'Current Password is wrong!');
        }
    }

    public function makePayment($id)
    {
        $order = Order::with(['customer'])->find($id);
        return view('createPayment', compact('order'));
    }

    public function initialize(Request $request, $id)
    {
        Rave::initialize(route('callback', $id));
    }

    public function callback(Request $request, $id)
    {
        $payment_token = json_decode($request->resp)->tx->paymentId;
        $order = Order::find($id)->update(['payment_status' => 1, 'payment_token' => $payment_token]);
        return view('createPayment');
    }

    public function changeLanguage($lang)
    {

        App::setLocale($lang);
        session()->put('locale', $lang);
        if ($lang == "en") {
            $dir = "ltr";
        } else {
            $dir = "rtl";
        }

        session()->put('direction', $dir);
        return redirect()->back();
    }

    public function scanner()
    {
        if (Auth::user()->hasRole('admin')) {
            $scanners = User::role('scanner')->orderBy('id', 'DESC')->get();
        } else {
            $scanners = User::role('scanner')->where('org_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        }
        foreach ($scanners as $value) {
            $value->total_event = EventScanner::where('scanner_id', $value->id)->count();
        }
        return view('admin.scanner.index', compact('scanners'));
    }

    public function scannerCreate()
    {
        return view('admin.scanner.create');
    }

    public function addScanner(Request $request)
    {

        return $request;
        $request->validate([
            'first_name' => 'bail|required',
            'last_name' => 'bail|required',
            'email' => 'bail|required|email|unique:users',
            'phone' => 'bail|required',
            'password' => 'bail|required|min:6',
        ]);
        $data = $request->all();
        $data['org_id'] = $request->idorg;
        $data['password'] =  Hash::make($request->password);

        $user = User::create($data);
        $user->assignRole('scanner');
        return redirect('scanner')->withStatus(__('Scanner is added successfully.'));
    }



    public function blockScanner($id)
    {
        $user = User::find($id);
        $user->status = $user->status == "1" ? "0" : "1";
        $user->save();
        return redirect('scanner')->withStatus(__('User status changed successfully.'));
    }

    public function getScanner($id)
    {
        $data = User::role('scanner')->where('org_id', $id)->orderBy('first_name', 'DESC')->get();
        return response()->json(['data' => $data, 'success' => true], 200);
    }

    public function getPuntoVenta($id)
    {
        $data = User::role('punto venta')->where('org_id', $id)->orderBy('first_name', 'DESC')->get();
        return response()->json(['data' => $data, 'success' => true], 200);
    }

    public function getCiudad($id)
    {
        $data = ciudades::where('Paises_Codigo', $id)->orderBy('Ciudad', 'asc')->get();
        return response()->json(['data' => $data, 'success' => true], 200);
    }
}
