<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();
Route::get('/', [HomeController::class, 'index'])->name('inicio');
Route::get('/login', [UserController::class, 'login'])->name('iniciar');
Route::post('/login', [UserController::class, 'adminLogin'])->name('iniciar.post');
Route::get('/cambiar-lenguaje/{lang}', [HomeController::class, 'changeLanguage'])->name('cambiar-lenguaje');
Route::get('/login/google/redirect', [HomeController::class, 'logingoogleredirectToProvider'])->name('login.google');
Route::get('/login/google/callback', [HomeController::class, 'loginhandleProviderCallback'])->name('login.callback.google');

Route::get('ent/{base64}', [HomeController::class, 'verarchivo'])->name('ver.archivo');
Route::get('ent/{base64}/{token}', [HomeController::class, 'clienteordencompra'])->name('ordencompra.cliente');
Route::get('descargar/{base64}', [HomeController::class, 'descargarentrada'])->name('descargar.entrada');


////// PERSONALIZADO V2 ////////////////
Route::group(['middleware' => ['auth']], function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
   
    Route::get('/eventos', [HomeController::class, 'indexeventos'])->name('index.eventos');
    Route::prefix('eventos')->group(function () {
        Route::get('entradas/{id}', [HomeController::class, 'showevento'])->name('show.eventos');
        Route::get('crear', [HomeController::class, 'createevento'] )->name('create.evento');
    });

    Route::get('/mis-eventos', [HomeController::class, 'miseventos'])->name('mis.eventos');
    Route::get('/mis-eventos/entradas/{id}', [HomeController::class, 'miseventosshow'])->name('mis.eventos.show');   
});


//////////////////////////////////////////////////////////


Route::get('/offline', function () {
    return view('vendor.laravelpwa.offline');
});
Route::get('storage', function () {
    Artisan::call('storage:link');
});
Route::get('optimizar', function () {
    Artisan::call('optimize');
});
