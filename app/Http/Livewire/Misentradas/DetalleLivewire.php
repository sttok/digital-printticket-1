<?php

namespace App\Http\Livewire\Misentradas;

use Exception;
use ZipArchive;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\AppUser;
use App\Models\Setting;
use Livewire\Component;
use App\Models\OrderChild;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Models\OrderChildsDigital;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Query\Builder;
use App\Http\Controllers\ApiController;
use App\Models\DigitalOrdenCompra;
use Illuminate\Support\Facades\Storage;

class DetalleLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $readytoload = false;
    public $evento_id;
    public $search = '', $search_estado = '', $organizar = 1, $filtrar_por = 0;
    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'search_estado' => ['except' => '', 'as' => 'zona'],
        'page' => ['except' => 1, 'as' => 'p'],
        'organizar' => ['except' => '1', 'as' => 'org'],
        'filtrar_por' => ['except' => 0, 'as' => 'vendidos'],
    ];
    public $entradas_array = [];
    public $organizar_por = 1;
    public $entradas_seleccionadas = [];
    public $search_telefono, $encontrado = false, $cliente, $estado_venta = 1, $abonado = 0, $total = 0, $metodo_de_pago = 1;
    public $nombre_cliente, $apellido_cliente, $correo_cliente, $imagen, $telefono, $prefijo_telefono = '+57', $phone, $contraseña_cliente, $notificar_nuevo = false, $cedula_cliente;
    public $search_telefono_endosado, $endosado_id, $endosado_identificador, $encontrado_endosado = false, $cliente_endosado;
    public $enviado = false;
    public $entradas = [], $digital_id;
    public $total_endosadas = 0, $total_sin_endosar = 0, $porcentaje_venta = 0, $dias_restantes = 0, $estado_evento;
    public $seleccionar_todos = false;

    public function mount($id){
        $this->evento_id = $id;
    }
    
    public function render()
    {
        return view('livewire.misentradas.detalle-livewire');
    }

    public function loadDatos(){
        $this->readytoload = true;
        $this->organizar = '1';
        $this->entradas = Ticket::where([
            ['event_id', $this->evento_id], ['is_deleted', 0]
        ])->get();
        $hoy = Carbon::now();
        $final = Carbon::parse(Event::findorfail($this->evento_id)->end_time);
        $this->dias_restantes = $hoy->diffInDays($final);
        $this->calcularendosados();        
    }

    public function buscarendosar($id, $ident){
        $this->reset(['search_telefono_endosado', 'endosado_id', 'encontrado_endosado', 'cliente_endosado', 'endosado_identificador']);
        $this->endosado_id = $id;
        $this->endosado_identificador = $ident;
        $this->dispatchBrowserEvent('abrirbuscarendosar');
    }

    public function buscarcliente(){
        $this->validate([
            'search_telefono' => 'required'
        ]);

        $cl = AppUser::where([
            ['phone', 'LIKE',  $this->search_telefono]
        ])->orWhere([
             ['cedula', 'LIKE',  $this->search_telefono]
        ])->first();
        
        if($cl != ''){
            $this->encontrado = true;
            $this->cliente = $cl->makeVisible(['name', 'last_name', 'phone', 'email']);
        }else{
            $this->reset(['encontrado', 'cliente']);
            $this->dispatchBrowserEvent('clientenoencontrado');
        }
    }

    public function buscarcliente2(){
        $this->reset(['encontrado_endosado', 'cliente_endosado', 'encontrado']);
        $this->validate([
            'search_telefono_endosado' => 'required'
        ]);
        $cl = AppUser::where([
            ['phone', 'LIKE',  $this->search_telefono_endosado]
        ])->orWhere([
              ['cedula', 'LIKE',  $this->search_telefono_endosado]
        ])->first();
        
        if($cl != ''){
            $this->encontrado_endosado = true;
            $this->cliente_endosado = $cl->makeVisible(['name', 'last_name', 'phone', 'email']);
        }else{
            $this->reset(['encontrado_endosado', 'cliente_endosado', 'encontrado']);
            $this->dispatchBrowserEvent('clientenoencontrado');
        }
    }

    public function asignarentrada(){
       
        $this->validate([
            'cliente_endosado' => 'required',
            'endosado_id' => 'required',
            'encontrado_endosado' => 'required|accepted'
        ]);

        foreach ($this->entradas_seleccionadas as $rr) {
            if($rr->id == $this->endosado_id){
                $rr->endosado = true;
                $rr->cliente_id = $this->cliente_endosado->id;
                $rr->cliente_name = $this->cliente_endosado->name . ' ' . $this->cliente_endosado->last_name;
            }
        }

        // dd($this->entradas_seleccionadas);
        $this->reset(['search_telefono_endosado', 'endosado_id', 'encontrado_endosado', 'cliente_endosado', 'endosado_identificador']);
        $this->dispatchBrowserEvent('regresarcliente1');
    }

    public function abrirventas(){
        if (count($this->entradas_array) > 0) {
            $this->dispatchBrowserEvent('abrirmodalventa');
            $this->cargarentradas();
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('Debe seleccionar al menos 1 entrada')]);
        }
    }

    public function eliminarendosado($id){
        foreach ($this->entradas_seleccionadas as $rr) {
            if($rr->id == $id){
                $rr->endosado = false;
                $rr->cliente_id = '';
                $rr->cliente_name = '';
            }
        }
        $this->dispatchBrowserEvent('quitarendosado');
    }

    public function veruploads($id){
        $this->reset(['entradas_array', 'entradas_seleccionadas', 'abonado', 'total']);
        $this->dispatchBrowserEvent('abrirmodalventa');
        $this->entradas_array[] = $id;
        $this->entradas_seleccionadas = OrderChildsDigital::whereIn('id', $this->entradas_array)->get();        
    }

    public function descargar($id){
        $this->reset('digital_id');
        $this->digital_id = $id;
        if($this->Digital->permiso_descargar == 1){
            $this->dispatchBrowserEvent('verenlace');
            $key = base64_encode('@kf#'.$this->Digital->id);
            $url = route('ver.archivo', $key);
            $this->Digital->url_1 = $url;
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('No tiene permiso para ver el enlace')]);
        }
    }

    public function enviarentradas(){
        if ($this->estado_venta != null ) {
            if ($this->encontrado == true && $this->cliente != '') {
                DB::beginTransaction();
                $array = [];
                try {
                    foreach ($this->entradas_seleccionadas as $es) {
                        $ent = OrderChild::findorfail($es->order_child_id);
                        $ent->customer_id = $this->cliente->id;
                        $ent->vendedor_id = Auth::user()->id;
                        if($ent->endosado == true){
                            $ent->endosado_id = $es->cliente_id;
                        }
                        $ent->update();
                       
                        $es->endosado = 1;
                        $es->update();
                        $array[] = array(
                            'order_child_id' => $es->order_child_id,
                            'endosado_id' => $es->cliente_id != null ? $es->cliente_id : null,
                            'digital_id' => $es['id'],
                        );
                         $key = base64_encode('@kf#'.$es->id);
                        $es->url_1 = route('ver.archivo', $key);
                    }

                    $ordencompra = new DigitalOrdenCompra();
                        $ordencompra->identificador = Str::upper(Str::random(7));
                        $ordencompra->evento_id = $this->evento_id;
                        $ordencompra->vendedor_id = Auth::user()->id;
                        $ordencompra->cliente_id = $this->cliente->id;
                        $ordencompra->cantidad_entradas = count($this->entradas_seleccionadas);
                        $ordencompra->metodo_pago = $this->metodo_de_pago;
                        $ordencompra->abonado = $this->abonado;
                        $ordencompra->total = $this->total;
                        $ordencompra->array_entradas = json_encode($array);
                        $ordencompra->estado_venta = $this->estado_venta;
                    $ordencompra->save();
                    
                    DB::commit();
                    $this->resetExcept(['evento_id', 'readytoload', 'search', 'search_estado', 'entradas_seleccionadas', 'cliente','entradas']);
                    $this->enviado = true;
                    $this->dispatchBrowserEvent('verenviadas');
                } catch (Exception $e) {
                    DB::rollBack();
                    $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
                }
            }else{
                $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, debe seleccionar un cliente')]);
            }
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('Debe seleccionar el estado de la venta')]);
        }
    }

    public function seleccionartodos(){
        
        if($this->seleccionar_todos == false){
            $e = OrderChildsDigital::where([
                ['evento_id', $this->evento_id], ['identificador', 'LIKE', '%'.$this->search.'%'], ['zona_id', 'LIKE', '%'. $this->search_estado], ['endosado', $this->filtrar_por]
            ])->get(); 
    
            $this->reset('entradas_array');
            foreach ($e as $i) {
               $this->entradas_array[] = $i->id;
            }
            $this->seleccionar_todos = true;
        }else{
            $this->seleccionar_todos = false;
            $this->reset('entradas_array');
        }
    }

    public function quitar($id){
        
        foreach (array_keys($this->entradas_array, $id) as $key) 
        {
            unset($this->entradas_array[$key]);
        }
        $this->cargarentradas();
        $this->reset(['seleccionar_todos']);
    }

    public function cerrarshow(){
        $this->dispatchBrowserEvent('cerrarshow1');
        $this->resetExcept(['evento_id', 'readytoload', 'search', 'search_estado', 'entradas']);
    }

    public function createcliente(){
        $this->dispatchBrowserEvent('createcliente2');
    }

    public function regresarcliente(){
        $this->dispatchBrowserEvent('regresarcliente1');
    }
    
    public function generarpass(){
        $this->contraseña_cliente = Str::random(8);
    }

    public function storecliente(){
        $this->phone = $this->prefijo_telefono . $this->telefono;
        $this->validate([
            'nombre_cliente' => 'required|min:2|max:120',
            'apellido_cliente' => 'required|min:2|max:120',
            'correo_cliente' => 'nullable|email|unique:app_user,email',
            'prefijo_telefono' => 'required',
            'telefono' => 'required|integer',
            'phone' => 'required|phone:CO,AUTO|unique:app_user,phone',
            'contraseña_cliente' => 'required|min:3|max:120',
            'notificar_nuevo' => 'required',
            'cedula_cliente' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            $cliente = new AppUser();
                $cliente->name = $this->nombre_cliente;
                $cliente->last_name = $this->apellido_cliente;
                $cliente->email = $this->correo_cliente;
                $cliente->password = Hash::make($this->contraseña_cliente);
                $cliente->phone = $this->phone;
                $cliente->cedula = $this->cedula_cliente;
                $cliente->provider = 'LOCAL';
                $cliente->status = 1;
                $cliente->borrado = 0;
                $cliente->image = 'defaultuser.png';
            $cliente->save();
            DB::commit();
            if($this->notificar_nuevo == true){
                $telefono = $this->phone;
                $mensaje =  urlencode( 'Bienvenido a ' .Str::upper($this->Setting) . ': ') . '%0d%0a' . 
                urlencode('Nombre: ' . Str::title( $this->nombre_cliente . ' ' . $this->apellido_cliente )) . '%0d%0a' .
                urlencode('Telefono de acceso: ') . urlencode($this->phone) . '%0d%0a' .
                urlencode('Contraseña: ') . urlencode($this->contraseña_cliente)  . '%0d%0a' ;
                $this->enviarsms2($telefono, $mensaje);
            }
            $this->dispatchBrowserEvent('storecliente');
            $this->search_telefono = $this->phone;
            $this->buscarcliente();
            $this->limpiarcliente();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function limpiarcliente(){
        $this->reset([
            'nombre_cliente', 'apellido_cliente', 'correo_cliente', 'imagen', 'telefono', 'prefijo_telefono', 'phone', 'contraseña_cliente', 'notificar_nuevo'
        ]);
        $this->calcularendosados();
    }

    public function limpiar(){
        $this->reset(['search', 'search_estado', 'organizar']);
        $this->calcularendosados();
        $this->resetpage();
    }

    private function calcularendosados(){
        $this->reset(['total_endosadas', 'total_sin_endosar']);
        $e = OrderChildsDigital::where([
            ['evento_id', $this->evento_id]
        ])->get();
        foreach ($e as $td) {
            if($td->endosado != 0){
                $this->total_endosadas = $this->total_endosadas + 1;
            }else{
                $this->total_sin_endosar = $this->total_sin_endosar +1;
            }
        }

        $this->calcularestadisticas(count($e), $this->total_endosadas);
    }

    private function calcularestadisticas($total, $endosadas){
        $this->porcentaje_venta = round( ($endosadas / $total) * 100 );

        if($this->dias_restantes > 15){
            if ($this->porcentaje_venta > 75) {
                $this->estado_evento = __('Execelente');
            }elseif($this->porcentaje_venta > 50 && $this->porcentaje_venta < 75){
                $this->estado_evento = __('Muy bien');
            }elseif($this->estado_evento < 50){
                $this->estado_evento = __('Regular');
            }
        }elseif($this->dias_restantes < 15) {
            if ($this->porcentaje_venta > 75) {
                $this->estado_evento = __('Execelente');
            }elseif($this->porcentaje_venta > 50 && $this->porcentaje_venta < 75){
                $this->estado_evento = __('Regular');
            }elseif($this->estado_evento < 50){
                $this->estado_evento = __('Mal');
            }
        }
    }    

    public function cambiarfiltrar(){
        if ($this->filtrar_por == 0) {
            $this->filtrar_por = 1;
        }else{
            $this->filtrar_por = 0;
        }
    }

    public function UpdatedFiltrarPor(){
        $this->resetpage();
    }

    public function updatedSearch(){
        $this->resetPage();
        $this->reset('seleccionar_todos');
    }

    public function updatedSearchEstado(){
        $this->resetPage();
        $this->reset('seleccionar_todos');
    }

    private function cargarentradas(){
        $this->entradas_seleccionadas = OrderChildsDigital::whereIn('id', $this->entradas_array)->get();
        $this->reset(['abonado', 'total']);
        foreach ($this->entradas_seleccionadas as $rr) {
            $rr->endosado = false;
        }
    }

    public function getEntradasProperty(){
    
        return OrderChildsDigital::where([
            ['evento_id', $this->evento_id], ['identificador', 'LIKE', '%'.$this->search.'%'], ['zona_id', 'LIKE', '%'. $this->search_estado], ['endosado', $this->filtrar_por]
        ])->paginate(24); 
        
    }

    public function getSettingProperty(){
        return Setting::findorfail(1)->app_name;
    } 

    public function enviarsms2($telefono, $mensaje){
        $data = array(
            'telefono' => $telefono,
            'mensaje' => $mensaje,
        );
        $data = (new ApiController)->sendsms2($data);
      
    }

    public function getEventoProperty(){
        return Event::findorfail($this->evento_id)->name;
    }

    public function getDigitalProperty(){
        return OrderChildsDigital::findorfail($this->digital_id);
    }

    public function descargarmasivo(){
        if(count($this->entradas_array) > 0){
            $zipFileName = 'EntradasDigitales-' . Str::lower(Str::random(4)) . '.zip';
            $public_dir = str_replace('\\', '/', public_path());
            $zip = new ZipArchive;
            $this->cargarentradas();
            
            $c = count($this->entradas_seleccionadas);
            $c1 = 0;
            if ($zip->open($public_dir . '/zip/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
            
                foreach ($this->entradas_seleccionadas as $ent) {
                    if($ent->permiso_descargar == 1){
                        $url = Str::after($ent->url, 'ticket-digital/');
                        if($ent->provider == "local"){
                             $zip->addFile($public_dir . '/storage/ticket-digital/' . $url, $url);
                        }elseif($ent->provider == "drive"){
                            //$url_evento = $ent->;
                            //$url = Storage::disk("google")->url($ent['url']);
                            //dd(Storage::disk("google")->files($ent['url']));
                            //$zip->addFile(Storage::disk("google")->files($url));
                        }
                       
                        if($ent->descargas == null){
                            $ent->descargas = 1;
                        }else{
                            $ent->descargas++;
                        }
                        $ent->update(); 
                        $c1 = $c1 + 1;
                    }
                }
                $zip->close();
            }
            $headers = array('Content-Type' => 'application/octet-stream');
            $filetopath = $public_dir.'/zip/'.$zipFileName;
            if(file_exists($filetopath)){
                if($c1 > 0){
                    if($c1 < $c){
                        $this->dispatchBrowserEvent('errores', ['error' => __('Algunas entradas no se pudieron descargar ya que no cuentas con el permiso, contacta al administrador para más información')]);
                    }
                    return response()->download($filetopath,$zipFileName,$headers);
                    // $this->descargararchivo($filetopath,$zipFileName,$headers);
                    // unlink($public_dir . '/zip/' . $zipFileName);
                    
                }else{
                    $this->dispatchBrowserEvent('errores', ['error' => __('No hay nada que descargar o no tienes permiso para descargar las entradas, contacta al administrador para más información')]);
                    unlink($public_dir . '/zip/' . $zipFileName);
                }
            }else{
                $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, intentalo nuevamente')]);
            }
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('Debe seleccionar al menos 1 entrada')]);
        }
    }

    public function enviarcompartir($ent){
        $ent['cliente'] = $this->cliente;
        $this->emit('mostrarCompartir', $ent);
    }
   
}
