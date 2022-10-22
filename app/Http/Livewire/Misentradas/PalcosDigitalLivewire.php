<?php

namespace App\Http\Livewire\Misentradas;

use Exception;
use App\Models\AppUser;
use Livewire\Component;
use App\Models\OrderChild;
use Illuminate\Support\Str;
use App\Models\DigitalOrdenCompra;
use App\Models\OrderChildsDigital;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\DigitalOrdenCompraDetalle;

class PalcosDigitalLivewire extends Component
{
    protected $listeners = ['cambiarpalco', 'ventarapidapalco'];
    public $palco, $cantidad_palcos = 0, $cantidad_asientos = 0;

    public $palco_id, $evento_id, $nota;

    public function mount($evento_id){
        $this->evento_id = $evento_id;
    }

    public function render()
    {
        return view('livewire.misentradas.palcos-digital-livewire');
    }

    public function cambiarpalco($zona){
        $this->palco = collect($zona);
        $this->cantidad_palcos = $this->palco['palcos'];
        $this->cantidad_asientos = $this->palco['puestos'];
    }

    public function abrirventarapida2($id){
        $this->dispatchBrowserEvent('abrirventa2');
        $this->palco_id = $id;
        $this->reset('nota');
    }

    public function ventarapidapalco($nota){
        $this->nota = $nota;
        $id = $this->palco_id;
        $ent = OrderChild::where([
            ['ticket_id', $this->palco['id']], ['mesas', $id]
        ])->get();

        $this->dispatchBrowserEvent('procesandoventa');
        
        DB::beginTransaction();
        try {
            $cliente = AppUser::where([
                ['phone', 'LIKE',  Auth::user()->phone]
            ])->orWhere([
                 ['email', 'LIKE',  Auth::user()->email]
            ])->first();

            if(empty($cliente)){
                $cl = new AppUser();
                    $cl->name = 'Cliente ' . Auth::user()->first_name;
                    $cl->last_name = Auth::user()->last_name;
                    $cl->email = Auth::user()->email;
                    $cl->password = Hash::make(Auth::user()->phone);
                    $cl->image = Auth::user()->image;
                    $cl->phone = Auth::user()->phone;
                    $cl->cedula = '';
                    $cl->provider = 'LOCAL';
                    $cl->status = 1;
                    $cl->borrado = 0;
                $cl->save();
                $cliente = $cl;
            }

            $ordencompra = new DigitalOrdenCompra();
                $ordencompra->identificador = Str::upper(Str::random(7));
                $ordencompra->evento_id = $this->evento_id;
                $ordencompra->vendedor_id = Auth::user()->id;
                $ordencompra->cliente_id = $cliente->id;
                $ordencompra->cantidad_entradas = $this->cantidad_asientos;
                $ordencompra->metodo_pago = 1;
                $ordencompra->abonado = 0;
                $ordencompra->total = 0;
                $ordencompra->estado_venta = 1;
                $ordencompra->nota = $this->nota;
            $ordencompra->save();
            $array = array();
            foreach ($ent as $e) {
                $r = OrderChildsDigital::where('order_child_id', $e->id)->first()->makeHidden(['updated_at', 'created_at']);
                $array[] =  $r;
                $e->customer_id = Auth::user()->id;
                $e->vendedor_id = Auth::user()->id;
                $e->update();
                $r->endosado = 1;
                $r->zona;
                $r->entrada;
                $r->update();

                $detalle = new DigitalOrdenCompraDetalle();
                    $detalle->digital_orden_compra_id = $ordencompra->id;
                    $detalle->order_child_id = $e->id;
                    $detalle->endosado_id = null;
                    $detalle->digital_id = $r->id;
                $detalle->save();
            }
            $data = array(
                'array' => $array,
                'cliente' => $cliente
            );
            $this->dispatchBrowserEvent('cerrarmodalventarapida');
            $this->reset(['palco_id', 'nota']);
            $this->emit('ventarapidapalco1', $data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
        }
    }

    public function venderpalco($id){
        $this->palco_id = $id;
        $id = $this->palco_id;
        $ent = OrderChild::where([
            ['ticket_id', $this->palco['id']], ['mesas', $id]
        ])->get()->pluck('id');

        $array = array();
        foreach ($ent as $e) {
            $r = OrderChildsDigital::where('order_child_id', $e)->first();
            $array[] =  $r->id;
        }
        $this->emit('abrirventas2',$array);
    }

}
