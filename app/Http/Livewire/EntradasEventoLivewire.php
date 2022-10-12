<?php

namespace App\Http\Livewire;

use Exception;
use Carbon\Carbon;
use Google\Client;
use App\Models\Event;
use Firebase\JWT\JWT;
use App\Models\Ticket;
use Livewire\Component;
use Google\Service\Drive;
use App\Models\OrderChild;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Jobs\GuardarEntradasJobs;
use App\Models\DireccionesEvento;
use App\Models\OrderChildsDigital;
use Illuminate\Support\Facades\DB;
use App\Models\DireccionesUsuarios;
use App\Exports\EntradasExcelExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DireccionesEventoPalcos;
use Illuminate\Support\Facades\Storage;

class EntradasEventoLivewire extends Component
{
    use WithPagination;
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';
    public $readytoload = false;
    public $evento, $evento_id, $entrada_id, $uploads = [];
    public $search, $search_estado, $search_tipo;
    public $updatemode = false;
    public $buscar_evento, $entradas_clonar = [], $entradas_descargar = [], $descargartodoexcel = false;
    public $errores = [], $exitos = [];
    public $zona_digital_id, $buscar_identificador;
    public $lugar_almacenamiento = 1, $permitir_descarga = false;

    public $url_carpeta, $entrada_carpeta, $archivos_subidos = [];

    public function mount($id){
        $this->evento_id = $id;
    }

    public function render()
    {
        return view('livewire.eventos.entradas-evento-livewire');
    }
    
    public function crearcarpetas($id){
        $this->entrada_id = $id;
        $this->dispatchBrowserEvent('abrircarpetas');
        $usuario = DireccionesUsuarios::where('usuario_id', $this->Evento->organizador_id)->first();

        if(empty($usuario)){
            $usuario = $this->createDirUser();
        }
        $entrada = DireccionesEvento::where([
           ['evento_id', $this->evento_id], ['entrada_id', $id]
        ])->first();

        if(empty($entrada)){
            $entrada = $this->createDirEvent($usuario);
        }

        $this->entrada_carpeta = $entrada;
        $this->url_carpeta = Storage::disk("google")->url($entrada['path']);
    }

    public function buscardocumentos(){
        $dir = '/'.$this->entrada_carpeta['path'];
        $recursive = false; // Get subdirectories also?
        $this->archivos_subidos = collect(Storage::disk('google')->listContents($dir, $recursive));
    }

    public function guardarentradas(){
        if(count($this->archivos_subidos) > 0 ){
            foreach($this->archivos_subidos as $as){
                $data = array(
                    'evento_id' => $this->evento_id,
                    'entrada_id' => $this->entrada_id,
                    'name' => $as['name'],
                    'path' => $as['path'],
                );
                GuardarEntradasJobs::dispatch($data)->onQueue('normal');
            }
            $this->dispatchBrowserEvent('archivossubidos');
            $this->reset(['entrada_carpeta', 'archivos_subidos', 'url_carpeta', 'entrada_id']);
        }else{
            $this->dispatchBrowserEvent('noarchivos');
        }
    }

    public function limpiarcarpetas(){
        $this->reset(['entrada_carpeta', 'archivos_subidos', 'url_carpeta', 'entrada_id']);
    }

    public function descargarentrada($id){

        $this->entrada_id = $id;
        $data = [];
        $temp = array(
            'id' => $id
        );
        array_push($data, $temp);
        return Excel::download(new EntradasExcelExport($data), Str::slug($this->entrada->name).'.xlsx');
    }

    public function descargarexcel(){

        try{
            if($this->descargartodoexcel = true){
                $this->reset('entradas_descargar');
                foreach($this->entradas as $ent){
                    $temporal = array(
                        'id' => $ent->id
                    );
                    array_push($this->entradas_descargar, $temporal);
                }
            }
            $r = Excel::download(new EntradasExcelExport($this->entradas_descargar), Str::slug($this->evento->name).'.xlsx');
            $this->reset([
                'entradas_descargar', 'descargartodoexcel'
            ]);
            $this->dispatchBrowserEvent('cerrardescargarexcel');
            return $r;
        } catch (Exception $e) {
           
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function loadDatos()
    {
        $this->readytoload = true;
    }

    public function limpiar(){
        $this->resetExcept([
            'evento', 'readytoload', 'evento_id', 'updatemode'
        ]);
    }

    public function updatedSearch()
    {
        $this->resetpage();
    }

    public function updatedSearchTipo()
    {
        $this->resetpage();
    }

    public function updatedSearchEstado()
    {
        $this->resetpage();
    }

    public function subirpdfs($id){
        $this->limpiar();
        $this->entrada_id = $id;
        if($this->Entrada != ''){
            $this->dispatchBrowserEvent('openmodalupload');
        }
    }

    public function updatedUploads()
    {
        $this->validate([
            'uploads.*' => 'file|max:5024', 
        ]);
        $this->reset(['errores', 'exitos']);
    }

    public function uploadentrada(){    

        $this->validate([
            'uploads.*' => 'file|max:5024', 
        ]);        
        $errores = [];
        $exitos = [];
        
        if($this->lugar_almacenamiento == 2){
            $url_organizador = DireccionesUsuarios::where('usuario_id', $this->Evento->organizador_id)->first();
            if(empty($url_organizador)){
                $url_organizador = $this->createDirUser();
            }
            $url_entrada = DireccionesEvento::where([
                ['direccion_usuario', $url_organizador['id']]
            ])->first();

            if(empty($url_entrada)){
                $url_entrada = $this->createDirEvent($url_organizador);
            }
        }

        foreach ($this->uploads as $i) {
            $nombre = $i->getClientOriginalName();
            $identificador = Str::before($nombre, '-');
            if( (int)$identificador){
                $ent = OrderChild::where([
                    ['ticket_id', $this->Entrada->id], ['identificador', (int)$identificador]
                ])->first();
               
                if(!empty($ent)){
                    DB::beginTransaction();
                    try{
                        $rr1 = OrderChildsDigital::where('order_child_id', $ent->id)->first();
                        if(empty($rr1)){
                            $r = new OrderChildsDigital();
                                $r->admin_id = Auth::user()->id;
                                $r->evento_id = $this->Evento->id;
                                $r->zona_id = $this->Entrada->id;
                                $r->order_child_id = $ent->id;
                                $r->identificador = $ent->identificador;
                                $r->permiso_descargar = $this->permitir_descarga == true ? 1 : 0;
                                if ($this->lugar_almacenamiento == 1) {
                                    $url = 'ticket-digital/'. Str::lower(Str::slug($this->Evento->name) . '/' . Str::slug($this->Entrada->name)). '/';
                                    if($this->Entrada->forma_generar == 2){
                                        if($ent->mesas > 0){
                                            $url .= 'palco-' . $ent->mesas . '/' ;
                                        }else{
                                            $url .= 'palco-adicional/';
                                        }
                                    }
                                    $i->storeAs($url, $nombre, 'custom');
                                    $r->url = route('inicio.frontend') . '/storage/' . $url . $nombre;
                                    $r->provider = 'local';

                                }elseif($this->lugar_almacenamiento == 2){
                                   
                                    $url =  $url_entrada['path'];
                                    /*if($this->Entrada->forma_generar == 2){
                                        $palcco = DireccionesEventoPalcos::where([
                                            ['direccion_evento_id', $url_entrada->id], ['palco', (int)$ent->mesas]
                                        ])->first();
                                        
                                        if(empty($palcco)){
                                            $palcco = $this->createDirPalco($url_entrada, (int)$ent->mesas);
                                              $url =  $palcco['path'];
                                        }
                                    }*/
                                  
                                    $i->storeAs($url, $nombre, 'google');
                                    Storage::disk("google")->putFileAs($url, $i, $nombre);
                                    $dir = '/'.$url_entrada['path'];
                                    $recursive = false; // Get subdirectories also?
                                    $file = collect(Storage::disk('google')->listContents($dir, $recursive))
                                        ->where('type', '=', 'file')
                                        ->where('filename', '=', pathinfo($nombre, PATHINFO_FILENAME))
                                        ->where('extension', '=', pathinfo($nombre, PATHINFO_EXTENSION))
                                        ->sortBy('timestamp')
                                        ->last();
                                    $r->url = $file['path'];
                                    $r->provider = 'drive';
                                }
                            $r->save();
                            DB::commit();
                            $exitos[] = array(
                                'id' => $nombre,
                                'msg' => __('Se ha subido correctamente')
                            );
                        }else{
                            $rr1->permiso_descargar = $this->permitir_descarga == true ? 1 : 0;
                            if ($this->lugar_almacenamiento == 1) {
                                $url = 'ticket-digital/'. Str::lower(Str::slug($this->Evento->name) . '/' . Str::slug($this->Entrada->name)). '/';
                                if($this->Entrada->forma_generar == 2){
                                    if($ent->mesas > 0){
                                        $url .= 'palco-' . $ent->mesas . '/' ;
                                    }else{
                                        $url .= 'palco-adicional/';
                                    }
                                }
                                $i->storeAs($url, $nombre, 'custom');
                                $rr1->url = route('inicio.frontend'). '/storage/' . $url .$nombre;
                                $rr1->provider = 'local';

                            }elseif($this->lugar_almacenamiento == 2){
                                $url_organizador = DireccionesUsuarios::where('usuario_id', $this->Evento->organizador_id)->first();
                                if(empty($url_organizador)){
                                    $url_organizador = $this->createDirUser();
                                }
                                $url_entrada = DireccionesEvento::where([
                                    ['direccion_usuario', $url_organizador['id']]
                                ])->first();

                                if(empty($url_entrada)){
                                    $url_entrada = $this->createDirEvent($url_organizador);
                                }
                                $url =  $url_entrada['path'];
                                if($this->Entrada->forma_generar == 2){
                                    $palcco = DireccionesEventoPalcos::where([
                                        ['direccion_evento_id', $url_entrada->id], ['palco', (int)$ent->mesas]
                                    ])->first();
                                    
                                    if(empty($palcco)){
                                        $palcco = $this->createDirPalco($url_entrada, (int)$ent->mesas);
                                          $url =  $palcco['path'];
                                    }
                                }
                                Storage::disk("google")->delete($rr1['url']);
                                Storage::disk("google")->putFileAs($url, $i, $nombre);
                                $dir = '/'.$url_entrada['path'];
                                $recursive = false; // Get subdirectories also?
                                $file = collect(Storage::disk('google')->listContents($dir, $recursive))
                                    ->where('type', '=', 'file')
                                    ->where('filename', '=', pathinfo($nombre, PATHINFO_FILENAME))
                                    ->where('extension', '=', pathinfo($nombre, PATHINFO_EXTENSION))
                                    ->sortBy('timestamp')
                                    ->last();
                                $rr1->url = $file['path'];
                                $rr1->provider = 'drive';
                            }
                            $rr1->update();
                            DB::commit();
                            $exitos[] = array(
                                'id' => $nombre,
                                'msg' => __('Se ha actualizado correctamente')
                            );
                        }
                    } catch (Exception $e) {
                        DB::rollBack();
                        $errores[] = array(
                            'id' => $nombre,
                            'msg' => $e->getMessage()
                        );
                    }
                }else{
                    $errores[] = array(
                        'id' => $nombre,
                        'msg' => __('El identificador no se ha encontrado para esta zona dentro de la base de datos')
                    );
                }
            }else{
                $errores[] = array(
                    'id' => $nombre,
                    'msg' => __('Ha ocurrido un error con el nombre del archivo, verifica y vuelvelo a intentar')
                );
            }
        }
        if (!empty($errores)) {
            $this->reset(['errores', 'exitos']);
            $this->errores = $errores;
            $this->exitos = $exitos;
            $this->dispatchBrowserEvent('storeuploaderror');
        }else{
            $this->dispatchBrowserEvent('storeupload');
            $this->limpiar();
        }      
        $this->reset('uploads');
    }
    
    private function createDirUser(){
        $nombre_folder = $this->Evento->organizador->first_name . ' ' . $this->Evento->organizador->last_name;
        Storage::disk('google')->makeDirectory($nombre_folder);
            // Find parent dir for reference
        $dir = '/';
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::disk('google')->listContents($dir, $recursive));

        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', $nombre_folder)
            ->first(); // There could be duplicate directory names!

        if (!$dir) {
            return 'Directory does not exist!';
        }

        $direc = new DireccionesUsuarios();
            $direc->usuario_id = $this->Evento->organizador_id;
            $direc->path = $dir['path'];
            $direc->drivers = 'google';
        $direc->save();

        return $direc;
    }

    private function createDirEvent($dirUser){
        
        $nombre_folder = $this->Evento->id . ' - ' .$this->Evento->name;
        Storage::disk('google')->makeDirectory($dirUser['path'].'/'.$nombre_folder);

        $dirEvent = '/'.$dirUser['path'];
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::disk('google')->listContents($dirEvent, $recursive));

        $dirEvent = $contents->where('type', '=', 'dir')
            ->where('filename', '=', $nombre_folder)
            ->first(); // There could be duplicate directory names!

        if (!$dirEvent) {
            return 'Directory does not exist!';
        }

        $nombre_folder = $this->Entrada->ticket_number . ' - ' . $this->Entrada->name;;
        Storage::disk('google')->makeDirectory($dirEvent['path'].'/'.$nombre_folder);

        $dirEntrada = '/'.$dirEvent['path'];
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::disk('google')->listContents($dirEntrada, $recursive));

        $dirEntrada = $contents->where('type', '=', 'dir')
            ->where('filename', '=', $nombre_folder)
            ->first(); // There could be duplicate directory names!

        if (!$dirEntrada) {
            return 'Directory does not exist!';
        }

       $direc = new DireccionesEvento();
            $direc->evento_id = $this->Evento->id;
            $direc->usuario_id = $this->Evento->organizador_id;
            $direc->direccion_usuario = $dirUser->id;
            $direc->entrada_id = $this->Entrada->id;
            $direc->path = $dirEntrada['path'];
       $direc->save();

       return $direc;
       
    }

    private function createDirPalco($data, $palco){
        $nombre_folder = 'Palco '. $palco;
        Storage::disk('google')->makeDirectory($data['path'].'/'.$nombre_folder);

        $dirEvent = '/'.$data['path'];
        $recursive = false; // Get subdirectories also?
        $contents = collect(Storage::disk('google')->listContents($dirEvent, $recursive));

        $dirEvent = $contents->where('type', '=', 'dir')
            ->where('filename', '=', $nombre_folder)
            ->first(); // There could be duplicate directory names!

        if (!$dirEvent) {
            return 'Directory does not exist!';
        }

       $direc = new DireccionesEventoPalcos();
            $direc->direccion_evento_id = $data->id;
            $direc->palco = $palco;
            $direc->path = $dirEvent['path'];
       $direc->save();

       return $direc;
    }

    private function obtenerarchivo($name_file){
        $filename = $name_file;
        
        $dir = '/';
        $recursive = false;
        $contents = collect(Storage::disk("google")->ListContents($dir, $recursive));
        
        $file = $contents
                ->where('type', '=', 'file')
                ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
                ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
                ->first();
                
        $rawData = Storage::disk("google")->get($file['path']);
        
        return response($rawData, 200)
            ->header('ContentType', $file['mimetype'])
            ->header('Content-Disposition', "attachment; filename='$filename'");
    }

    public function veruploads($id){
        $this->limpiar();
        $this->zona_digital_id = $id;
        if(count($this->Digitals) > 0){
            $this->entrada_id = $id;
            $this->dispatchBrowserEvent('opendigitals');
        }else{
            $this->dispatchBrowserEvent('emptydigitals');
        }

    }
    
    public function getEntradasProperty()
    {
        return Ticket::where([
            ['name', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_estado],
            ['tipo', 'LIKE', '%' . $this->search_tipo], ['event_id', $this->Evento->id], ['is_deleted', 0]
        ])->orWhere([
            ['ticket_number', 'LIKE', '%' . $this->search . '%'], ['status', 'LIKE', '%' . $this->search_estado],
            ['tipo', 'LIKE', '%' . $this->search_tipo], ['event_id', $this->Evento->id], ['is_deleted', 0]
        ])->paginate(12);
    }
    
    public function getEntradaProperty()
    {
        return Ticket::findorfail($this->entrada_id);
    }

    public function getEventoProperty(){
        return Event::findorfail($this->evento_id);
    }

    public function getDigitalsProperty(){
        return OrderChildsDigital::where([
            ['evento_id', $this->Evento->id], ['zona_id', $this->zona_digital_id], ['identificador', 'LIKE', '%'.$this->buscar_identificador.'%']
        ])->get();
    }

    private  function accestokens(){
        $data = file_get_contents("../printticket-v2-drive-3003-62db1c1d3cbd.json");
        $data = json_decode($data, true);
        $privateKey = $data['private_key'];
        $hoy = Carbon::now();
       
        $adition_headers = [
            "kid" => $data['private_key_id']
        ];

        $payload = [
            'iss' => $data['client_email'],
            'scope' => 'https://www.googleapis.com/auth/drive',
            "aud" => $data['token_uri'],
            'iat' =>  $hoy->timestamp,
            'exp' =>  $hoy->addHours(1)->timestamp
        ];

        $jwt = JWT::encode($payload, $privateKey, 'RS256', null ,$adition_headers);

        $pr = Http::post('https://oauth2.googleapis.com/token',[
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]);
        $token = $pr->json()['access_token'];
        return $token;
    }   

}
