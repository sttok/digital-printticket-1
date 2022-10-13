<?php

namespace App\Http\Livewire\Misentradas;

use Exception;
use App\Models\AppUser;
use App\Models\OrderChild;
use App\Models\OrderChildsDigital;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class EndosarVentaDigitalLivewire extends Component
{
    protected $listeners = ['endosarentrada'];
    public $encontrado = false, $search_telefono, $cliente;
    public $entrada_id;

    public function render()
    {
        return view('livewire.misentradas.endosar-venta-digital-livewire');
    }

    public function endosarentrada($id){
        $this->entrada_id = $id;
        $this->dispatchBrowserEvent('abrirendosar');
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

    public function endosar(){
        $this->validate([
            'encontrado' => 'required',
            'cliente' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $ent = OrderChildsDigital::findorfail($this->entrada_id);
            if(!empty($ent)){
                $order_child = OrderChild::findorfail($ent->order_child_id);
                $order_child->endosado_id = $this->cliente->id;
                $order_child->update();
                DB::commit();
                $this->dispatchBrowserEvent('cerrarendosar');
                $this->reset(['encontrado', 'search_telefono', 'cliente', 'entrada_id']);
            }else{
                $this->dispatchBrowserEvent('errores', ['error' => __('No se ha encontrado la entrada digital, contacta al administrador')]);
            }

        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }
    
    public function limpiar(){
        $this->dispatchBrowserEvent('abrirventa');
        $this->reset(['search_telefono', 'cliente', 'encontrado']);
    }
}
