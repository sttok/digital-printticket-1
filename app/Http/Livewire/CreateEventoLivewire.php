<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\pais;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use Livewire\Component;
use App\Models\Category;
use App\Models\ciudades;
use App\Models\Location;
use App\Models\OrderChild;
use Illuminate\Support\Str;
use App\Models\EventScanner;
use Livewire\WithFileUploads;
use App\Models\EventPuntoVenta;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Jobs\GenerarBase64EntradasJobs;
use App\Jobs\GenerarEncriptacionEntradasJobs;

class CreateEventoLivewire extends Component
{
    use WithFileUploads;
    public $readyToLoad = false;
    protected $listeners = ['actualizardatos'];

    public $imagen_evento, $imagen_banner, $imagen_mapa;
    public $titulo, $categoria_id, $descripcion, $aforo, $hora_inicio_evento, $hora_final_evento, $etiquetas, $localidad_iframe, $localidad_direccion, $localidad_id;
    public $organizador_id, $scanners = [], $puntos_ventas = [];
    public $tipo_evento = 0, $pais_id = "CO", $ciudad_id = 10222;
    public $plataforma_evento, $url_evento;
    public $privacidad_evento = true;

    // public $titulo_evento, $descripcion_corta, $categoria_id, $maximo_aforo = 0, $tags, $estado_evento = 1;
    public $nombre_localidad, $direccion_localidad, $iframe_localidad;
    public $ent_direccion, $ent_iframe;
    public $ent_id;

    public $nombre_entrada, $tipo_entrada = 1, $cantidad_entrada = 0, $palcos = 1, $puestos = 1, $adicional = 0;
    public $entradas_array = [];

    public function render()
    {
        return view('livewire.eventos.create-evento-livewire');
    }
    public function loadDatos()
    {
        $this->readyToLoad  = true;
    }  

    public function abrirorganizador(){
        $this->dispatchBrowserEvent('abrrirorganizador');
    }

    public function abrirpuntoventa(){
        $this->dispatchBrowserEvent('abrrirpuntoventa');
    }

    public function abrirscanner(){
        $this->dispatchBrowserEvent('abrrirscanner');
    }

    public function actualizardatos(){
        $id = $this->organizador_id;
        $this->reset('organizador_id');
        $this->organizador_id = $id;
    }

    public function temporarlentrada(){
        $this->validate([
            'nombre_entrada' => 'required|max:120',
            'tipo_entrada' => ['required', 'integer', Rule::in([1, 2]) ],
            'cantidad_entrada' => 'required_if:tipo_entrada,1|integer|max:99999',
            'palcos' => 'required_if:tipo_entrada,2|integer|min:1|max:99999',
            'puestos' => 'required_if:tipo_entrada,2|integer|min:1|max:99999',
            'adicional' => 'required_if:tipo_entrada,2|integer|min:0|max:99999',
        ]);

        $this->entradas_array[] = array(
            'id' => Str::random(4),
            'nombre_entrada' => $this->nombre_entrada,
            'tipo' => $this->tipo_entrada,
            'cantidad_entrada' => $this->tipo_entrada == 1 ? $this->cantidad_entrada : ((int)$this->palcos * (int)$this->puestos) + (int)$this->adicional ,
            'palcos' => $this->tipo_entrada == 2 ? $this->palcos : 0,
            'puestos' => $this->tipo_entrada == 2 ? $this->puestos : 0,
            'adicional' => $this->tipo_entrada == 2 ? $this->adicional : 0,
        );
        
        $this->reset(['nombre_entrada', 'tipo_entrada', 'cantidad_entrada', 'palcos', 'puestos', 'adicional']);
    }

    public function borrarentrada($id){
        $key = array_search($id, array_column($this->entradas_array, 'id'));
        unset($this->entradas_array[$key]);
    }

    public function storeEvento(){
        $this->validate([
            'imagen_evento' => 'required|image|max:10048|dimensions:width=1080,height=1080',
            'imagen_banner' => 'nullable|image|max:10048|dimensions:width=1920,height=400',
            'imagen_mapa' => 'nullable|image|max:10048|dimensions:width=1080,height=1080',
            'titulo' => 'required|max:120',
            'descripcion' => 'nullable|max:550',
            'categoria_id' => 'required',
            'aforo' => 'required|max:9999999',
            'hora_inicio_evento' => 'required|date',
            'hora_final_evento' => 'required|date|after:hora_inicio_evento',
            'organizador_id' => 'required',
            'scanners' => 'required|array|min:1|max:20',
            'puntos_ventas' => 'nullable|array|max:20',
            'tipo_evento' => 'required|boolean',
            'pais_id' => 'bail|required_if:tipo_evento,0',
            'ciudad_id' => 'bail|required_if:tipo_evento,0',
            'localidad_direccion' => 'required_if:tipo_evento,0|max:250',
            'localidad_iframe' => 'nullable|max:1500',
            'plataforma_evento' => 'bail|required_if:tipo_evento,1',
            'url_evento' => 'nullable|url',
            'privacidad_evento' => 'required',
            'entradas_array' => 'required|array|min:1'
        ]);

        $can = collect($this->entradas_array)->sum('cantidad_entrada');
        if ($can <= $this->aforo) {
            DB::beginTransaction();
            try { 
                $evento = new Event();
                    $evento->name = $this->titulo;
                    $evento->description = $this->descripcion;
                    if($this->tipo_evento == 1){
                        $evento->type = "online";
                        $evento->plataforma = $this->plataforma_evento;
                        $evento->url_evento = $this->url_evento;
                    }elseif($this->tipo_evento == 0){
                        $evento->type = "offline";
                        if($this->localidad_id != ''){
                            $evento->localidad_id = $this->localidad_id;
                        }else{
                            $evento->address = $this->direccion_localidad;
                            $evento->google_iframe = $this->iframe_localidad;
                        }
                    }
                    $evento->user_id = Auth::user()->id;
                    $evento->organizador_id = $this->organizador_id;
                    $evento->ciudad = $this->ciudad_id;
                    $evento->pais = $this->pais_id;
                    $evento->category_id = $this->categoria_id;
                    $evento->start_time = $this->hora_inicio_evento;
                    $evento->end_time = $this->hora_final_evento;
                    if ($this->imagen_evento) {
                        $imgname2 = Str::slug( Str::limit($this->titulo, 6, '') ) . '-'. Str::random(4);
                        $imageName2 = $imgname2 . '.' . $this->imagen_evento->extension();
                        $this->imagen_evento->storeAs('/images/upload', $imageName2, 'customEventos');
                        $evento->image = $imageName2;
                    }
                    if ($this->imagen_banner) {
                        $imgname2 = Str::slug( Str::limit($this->titulo, 6, '') ) . '-banner-'. Str::random(4);
                        $imageName2 = $imgname2 . '.' . $this->imagen_banner->extension();
                        $this->imagen_banner->storeAs('/images/upload', $imageName2, 'customEventos');
                        $evento->imagen_banner = $imageName2;
                    }
                    if ($this->imagen_mapa) {
                        $imgname2 = Str::slug( Str::limit($this->titulo, 6, '') ) . '-mapa-'. Str::random(4);
                        $imageName2 = $imgname2 . '.' . $this->imagen_mapa->extension();
                        $this->imagen_mapa->storeAs('/images/upload', $imageName2, 'customEventos');
                        $evento->imagen_mapa = $imageName2;
                    }
                    $evento->people = $this->aforo;
                    $evento->security = $this->privacidad_evento;
                    $evento->tags = $this->etiquetas;
                    $evento->status = 1;
                    $evento->event_status = "Pending";
                    $evento->is_deleted = 0;
                $evento->save();
                if(count($this->scanners) > 0){
                    for ($i = 1; $i <= count($this->scanners); $i++) {
                        if(array_key_exists($i, $this->scanners)){
                            if($this->scanners[$i] != false){
                                $scanner = new EventScanner();
                                $scanner->event_id = $evento->id;
                                $scanner->scanner_id = $this->scanners[$i];
                                $scanner->save();
                            }
                        }
                    }
                }
                if(count($this->puntos_ventas) > 0){
                    for ($i = 1; $i <= count($this->puntos_ventas); $i++) {
                        if(array_key_exists($i,$this->puntos_ventas)){
                            if($this->puntos_ventas[$i] != false){
                                $puntodeventa = new EventPuntoVenta();
                                $puntodeventa->event_id = $evento->id;
                                $puntodeventa->punto_id = $this->puntos_ventas[$i];
                                $puntodeventa->save();
                                
                            }
                        }
                    }
                }
                $colores = array(
                    "#fefefe", 
                    "#f7ff00",
                    "#b600ff",
                    "#47ea00",
                    "#ffb600",
                    "#ff00d4",
                    "#00b9ff",
                    "#ff0000",
                    "#adadad",
                    "#FFD700",
                    "#ff0080",
                    "#9fd5d1"
                );
                foreach (collect($this->entradas_array) as $entrada) {
                    $ticket_number = chr(rand(65, 90)) . chr(rand(65, 90)) . '-' . rand(999, 99900);
                    $ent = new Ticket();
                        $ent->event_id = $evento->id;
                        $ent->user_id = Auth::user()->id;
                        $ent->ticket_number = $ticket_number;
                        $ent->name = $entrada['nombre_entrada'];
                        $ent->type = 'paid';
                        $ent->price = 1;
                        $ent->quantity = $entrada['cantidad_entrada'];
                        $ent->ticket_per_order = 1;
                        $ent->start_time = $this->hora_inicio_evento;
                        $ent->end_time = $this->hora_final_evento;
                        $ent->description = __('Entradas digitales');
                        $ent->color_localidad  = $colores[rand(0, 9)];
                        $ent->tipo = 1;
                        $ent->identificador = rand(1000, 99999);
                        if($entrada['tipo'] == 2){
                            $ent->palcos = $entrada['palcos'];
                            $ent->puestos = $entrada['puestos'];
                            $ent->puestos_adicional = $entrada['adicional'];
                        }
                        $ent->categoria = 2;
                        $ent->status = 1;
                        $ent->generadas = 1;
                        $ent->is_deleted = '0';
                        $ent->forma_generar = $entrada['tipo'];
                    $ent->save();
                        if ($entrada['tipo'] == 1) {
                            $ultimo_identificador = 0;
                            $ultimo_consecutivo = 0;
                            for ($i = 1; $i <= $entrada['cantidad_entrada']; $i++) {
                                $l1 = rand(2, 13);
                                if (empty($ultimo_identificador) && empty($ultimo_consecutivo)) {
                                    $identificador = $ent->identificador;
                                    $consecutivo = '1';
                                } else {
                                    $identificador = $ultimo_identificador;
                                    $consecutivo = $ultimo_consecutivo + '1';
                                }
                                $child = new OrderChild();
                                $child['consecutivo'] = $consecutivo;
                                $child['salto'] = $l1;
                                $child['vendedor_id'] = Auth::user()->id;
                                $child['endosado_id'] = '0';
                                $child['identificador'] = $identificador + $l1;
                                $cod = $ent->id . '-' . Str::random(10) . '#kf' . Str::random(5);
                                $cod2 = $ent->id . '-' . Str::random(10) . '#kf' . Str::random(5);
                                $child['ticket_number'] = $cod;
                                $child['ticket_number2'] = $cod2;
                                $child['ticket_id'] = $ent->id;
                                $child['order_id'] =  1;
                                $child['customer_id'] = '0';
                                $child['status'] = '0';
                                $child->save();
                                $ultimo_identificador = $child->identificador;
                                $ultimo_consecutivo = $child->consecutivo;
                            }
                        } elseif ($entrada['tipo'] == 2) {
                            $ultimo_identificador = 0;
                            $ultimo_consecutivo = 0;
                            for ($i = 1; $i <= $entrada['palcos']; $i++) {
                                for ($i2 = 1; $i2 <= $entrada['puestos']; $i2++) {
                                    $l1 = rand(2, 18);
                                    if (empty($ultimo_identificador) && empty($ultimo_consecutivo)) {
                                        $identificador = $ent->identificador;
                                        $consecutivo = '1';
                                    } else {
                                        $identificador = $ultimo_identificador;
                                        $consecutivo = $ultimo_consecutivo + '1';
                                    }
                                    $child = new Orderchild();
                                    $child['consecutivo'] = $consecutivo;
                                    $child['salto'] = $l1;
                                    $child['vendedor_id'] = Auth::user()->id;
                                    $child['endosado_id'] = '0';
                                    $child['mesas'] = $i;
                                    $child['asiento'] = $i2;
                                    $child['identificador'] = $identificador + $l1;
                                    $cod = $ent->id . '-' . Str::random(10) . '#kf' . Str::random(5);
                                    $cod2 = $ent->id . '-' . Str::random(10) . '#kf' . Str::random(5);
                                    $child['ticket_number'] = $cod;
                                    $child['ticket_number2'] = $cod2;
                                    $child['ticket_id'] = $ent->id;
                                    $child['order_id'] =  1;
                                    $child['customer_id'] = '0';
                                    $child['status'] = '0';
                                    $child->save();
                                    $ultimo_identificador = $child->identificador;
                                    $ultimo_consecutivo = $child->consecutivo;
                                }
                            }
                            if ($entrada['adicional'] >= 1) {
                                    $ultimo_identificador = 0;
                                    $ultimo_consecutivo = 0;
                                for ($i = 1; $i <= $entrada['adicional']; $i++) {
                                    $l1 = rand(2, 13);
                                    if (empty($ultimo_identificador) && empty($ultimo_consecutivo)) {
                                        $identificador = $ent->identificador;
                                        $consecutivo = '1';
                                    } else {
                                        $identificador = $ultimo_identificador;
                                        $consecutivo = $ultimo_consecutivo + '1';
                                    }

                                    $child = new OrderChild();
                                    $child['consecutivo'] = $consecutivo;
                                    $child['salto'] = $l1;
                                    $child['vendedor_id'] = Auth::user()->id;
                                    $child['endosado_id'] = '0';
                                    $child['mesas'] = 0;
                                    $child['asiento'] = $i;
                                    $child['identificador'] = $identificador + $l1;
                                    $cod = $ent->id . '-' . Str::random(10) . '#kf' . Str::random(5);
                                    $cod2 = $ent->id . '-' . Str::random(10) . '#kf' . Str::random(5);
                                    $child['ticket_number'] = $cod;
                                    $child['ticket_number2'] = $cod2;
                                    $child['ticket_id'] = $ent->id;
                                    $child['order_id'] =  1;
                                    $child['customer_id'] = '0';
                                    $child['status'] = '0';
                                    $child->save();
                                    $ultimo_identificador = $child->identificador;
                                    $ultimo_consecutivo = $child->consecutivo;
                                }
                            }
                        }
                   
                    GenerarBase64EntradasJobs::dispatch($ent->id)->onQueue('alto');
                    GenerarEncriptacionEntradasJobs::dispatch($ent->id)->onQueue('normal');
                }
                DB::commit();
                $this->dispatchBrowserEvent('successevento');
                $id = $evento->id;
                return redirect()->route('show.eventos', compact('id'));
            } catch (Exception $e) {
                DB::rollBack();
                $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
            }
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => __('La cantidad de entradas supera el aforo, revisa nuevamente')]);
        }

    }

    public function updatedOrganizadorId(){
        $this->reset(['scanners', 'puntos_ventas']);
    }

    public function storeLocalidad(){
        $this->validate([
            'nombre_localidad' => 'required|max:120|unique:location,nombre',
            'direccion_localidad' => 'required|max:550',
            'iframe_localidad' => 'required',
            'pais_id' => 'required',
            'ciudad_id' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $localidad = new Location();
            $localidad->nombre = $this->nombre_localidad;
            $localidad->direccion = $this->direccion_localidad;
            $localidad->google_iframe = $this->iframe_localidad;
            $localidad->pais_id = $this->pais_id;
            $localidad->ciudad_id = $this->ciudad_id;
            $localidad->estado = 1;
            $localidad->save();
            $this->dispatchBrowserEvent('storelocalidadd');
            DB::commit();
            $this->reset(['nombre_localidad', 'direccion_localidad', 'iframe_localidad']);
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function updatedLocalidadId(){
        
        if($this->localidad_id != null){
            $r = $this->Localidades->find($this->localidad_id);
            $this->localidad_direccion = $r->direccion;
            $this->localidad_iframe = (string) $r->google_iframe;
        }else{
            $this->reset(['localidad_direccion', 'localidad_iframe']);
        }
    }

    public function createlocalidad(){
        $this->reset(['nombre_localidad', 'direccion_localidad', 'iframe_localidad']);
        $this->dispatchBrowserEvent('createlocalidadd');
    }

    public function getLocalidadesProperty(){
        return Location::where([
           ['pais_id', 'LIKE', '%'.$this->pais_id.'%'], ['ciudad_id', 'LIKE', '%'.$this->ciudad_id.'%'], ['estado', 1]
        ])->get();
    }

    public function getCategoriasProperty(){
        return Category::where('status', 1)->orderBy('id', 'DESC')->get();
    }

    public function getPaisesProperty(){
        return pais::where('id', '>' , 0)->orderBy('Pais', 'ASC')->get();
    }

    public function getCiudadesProperty(){
        return ciudades::where('Paises_Codigo', $this->pais_id)->orderBy('Ciudad', 'ASC')->get();
    }

    public function getOrganizadoresProperty(){
        return User::role('organization')->orderBy('first_name', 'ASC')->get();
    }

    public function getEscaneresProperty(){
        return User::role('scanner')->where('org_id', $this->organizador_id)->orderBy('id', 'DESC')->get();
    }

    public function getPuntoventasProperty(){
        return User::role('punto venta')->where('org_id', $this->organizador_id)->orderBy('id', 'DESC')->get();
    }
}
