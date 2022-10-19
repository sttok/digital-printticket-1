<?php

namespace App\Http\Livewire;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\OrderChildsDigital;
use Illuminate\Support\Facades\Storage;

class DescargarArchivoDriveLivewire extends Component
{
    public $codigo;
    public $identificador;

    public function mount($codigo){
        $this->codigo = $codigo;
    }

    public function render()
    {
        return view('livewire.descargar-archivo-drive-livewire');
    }

    public function validarr(){
        $this->validate([
            'identificador' => 'required|integer' 
        ]);
        $id = base64_decode($this->codigo);
        $id = Str::after($id, '@kf#');
        $entrada = OrderChildsDigital::where('id', $id)->first();

        if(!empty($entrada)){
            if ($entrada->identificador == $this->identificador) {
                $filename = Storage::disk("google")->url($entrada['url']);
                if($entrada->descargas == null){
                    $entrada->descargas = 1;
                }else{
                    $entrada->descargas++;
                }
                $entrada->update();
                return redirect()->to($filename);
            }else{
                $this->dispatchBrowserEvent('errores', ['error' => 'El identificador no es correcto, vuelvelo a intentar']);
            }
        }else{
            $this->dispatchBrowserEvent('errores', ['error' => 'Ha ocurrido un error, la entrada no coincide en nuestra base de datos']);
        }
    }
}
