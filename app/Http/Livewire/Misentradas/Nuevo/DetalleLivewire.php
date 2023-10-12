<?php

namespace App\Http\Livewire\Misentradas\Nuevo;

use Exception;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\AppUser;
use App\Models\Setting;
use Livewire\Component;
use App\Models\OrderChild;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Models\TicketPuntoVenta;
use App\Models\DigitalOrdenCompra;
use App\Models\OrderChildsDigital;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DescargarInformeExport;
use App\Http\Controllers\ApiController;
use App\Models\DigitalOrdenCompraAnulado;
use App\Models\DigitalOrdenCompraDetalle;
use App\Exports\DescargarReporteVentaExport;

class DetalleLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => '', 'as' => 's'],
        'page' => ['except' => 1, 'as' => 'p'],
    ];
    public $search, $estadisticas = array(), $estado_evento;
    public $evento_id;
    public $disponibles = [];
    public $loaded = false, $readyToLoad = false, $enviado = false;
    public $entradas_seleccionadas = [], $grupos_palcos = [], $seleccionados_palcos = [];
    public $entradas_palcos_seleccionados;
    public $palco_id;

    public $search_telefono, $encontrado = false, $cliente, $estado_venta = 1, $abonado = 0, $total = 0, $metodo_de_pago = 1, $nota_venta;
    public $nombre_cliente, $apellido_cliente, $telefono, $prefijo_telefono = '+57', $phone,  $cedula_cliente;

    public $clienteNombre, $clienteTelefono, $clienteCedula;

    public $venta_id, $venta_realizada = [], $estadoVenta = 0, $estadoAnulacion = 0;

    public $porcentaje_venta = 0, $dias_restantes = 0;

    public function mount($event_id)
    {
        $this->evento_id = $event_id;
    }

    public function render()
    {
        $this->estadisticas();
        return view('livewire.misentradas.nuevo.detalle-livewire');
    }

    public function descargarReporte($id)
    {
        $data = array();
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('Superadmin') || Auth::user()->hasRole('organization')) {
            $orden_compra = DigitalOrdenCompra::with(['cliente:id,name,last_name'])->where('id', $id)->get();
            foreach ($orden_compra as $orden) {
                $detalles = DigitalOrdenCompraDetalle::with(['entrada'])->where('digital_orden_compra_id', $orden->id)->get();
                foreach ($detalles as $detalle) {
                    $data[] = array(
                        'orden_compra_identificador' => $orden->identificador,
                        'nombre_entrada' => $detalle->entrada->evento->name,
                        'Identificador' => $detalle->entrada->identificador,
                        'Consecutivo' => $detalle->entrada->consecutivo,
                        'Palco' => $detalle->entrada->mesas != null ? $detalle->entrada->mesas : 'No',
                        'Asiento' => $detalle->entrada->asiento != null ? $detalle->entrada->asiento : 'No',
                        'estado' => $detalle->entrada->status,
                        'comprador' => $orden->cliente->name . ' ' . $orden->cliente->last_name,
                        'endosado' => $detalle->endosado_id != null ? $detalle->endosado->name . ' ' . $detalle->endosado->last_name : 'No',
                        'fecha_vendido' => Carbon::create($orden->created_at)->locale('es')->isoFormat('LLLL'),
                    );
                }
            }
        } elseif (Auth::user()->hasRole('punto venta')) {
            $orden_compra = DigitalOrdenCompra::where([
                ['id', $id], ['vendedor_id', Auth::user()->id]
            ])->with(['cliente:id,name,last_name'])->get();
            foreach ($orden_compra as $orden) {
                $detalles = DigitalOrdenCompraDetalle::where('digital_orden_compra_id', $orden->id)->with(['entrada'])->get();
                foreach ($detalles as $detalle) {
                    $data[] = array(
                        'orden_compra_identificador' => $orden->identificador,
                        'nombre_entrada' => $detalle->entrada->evento->name,
                        'Identificador' => $detalle->entrada->identificador,
                        'Consecutivo' => $detalle->entrada->consecutivo,
                        'Palco' => $detalle->entrada->mesas != null ? $detalle->entrada->mesas : 'No',
                        'Asiento' => $detalle->entrada->asiento != null ? $detalle->entrada->asiento : 'No',
                        'estado' => $detalle->entrada->status,
                        'comprador' => $orden->cliente->name . ' ' . $orden->cliente->last_name,
                        'endosado' => $detalle->endosado_id != null ? $detalle->endosado->name . ' ' . $detalle->endosado->last_name : 'No',
                        'fecha_vendido' => Carbon::create($orden->created_at)->locale('es')->isoFormat('LLLL'),
                    );
                }
            }
        }
        return Excel::download(new DescargarInformeExport($data), 'informe-' . Carbon::now()->isoFormat('D-M-YY') . '-' . rand(1, 999) . '.xlsx');
    }

    public function detalleReporte($id)
    {
        $historial = DigitalOrdenCompra::where('id', $id)->first();
        $detalles = DigitalOrdenCompraDetalle::where('digital_orden_compra_id', $id)->get();

        $this->reset(['venta_realizada', 'estadoVenta', 'estadoAnulacion']);
        foreach ($detalles as $detalle) {
            $this->venta_realizada[] = array(
                'name_entrada' => $detalle->entrada->evento->name,
                'identificador' => $detalle->entrada->identificador,
                'consecutivo' => $detalle->entrada->consecutivo,
                'palco' => $detalle->entrada->mesas,
                'asiento' => $detalle->entrada->asiento
            );
        }

        $this->estadoVenta = $historial->estado_venta;
        $this->estadoAnulacion = $historial->anulado;

        $this->cliente = AppUser::find($historial->cliente_id);
        $this->venta_id = $id;
        $this->enviado = true;
        $this->dispatchBrowserEvent('abrirDetalle');
    }

    private function calcularestadisticas($total, $endosadas)
    {
        if ($total > 0) {
            $this->porcentaje_venta = round(($endosadas / $total) * 100);
        } else {
            $this->porcentaje_venta = 0;
        }

        if ($this->dias_restantes > 15) {
            if ($this->porcentaje_venta > 75) {
                $this->estado_evento = 1;
            } elseif ($this->porcentaje_venta > 50 && $this->porcentaje_venta < 75) {
                $this->estado_evento = 2;
            } elseif ($this->estado_evento < 50) {
                $this->estado_evento = 3;
            }
        } elseif ($this->dias_restantes < 15) {
            if ($this->porcentaje_venta > 75) {
                $this->estado_evento = 1;
            } elseif ($this->porcentaje_venta > 50 && $this->porcentaje_venta < 75) {
                $this->estado_evento = 2;
            } elseif ($this->estado_evento < 50) {
                $this->estado_evento = 4;
            }
        }
    }

    private function estadisticas()
    {
        $year = Carbon::now()->format('Y');
        for ($i = 1; $i <= 12; $i++) {
            switch ($i) {
                case 1:
                    $nombre = 'Ene';
                    break;
                case 2:
                    $nombre = 'Feb';
                    break;
                case 3:
                    $nombre = 'Mar';
                    break;
                case 4:
                    $nombre = 'Abr';
                    break;
                case 5:
                    $nombre = 'May';
                    break;
                case 6:
                    $nombre = 'Jun';
                    break;
                case 7:
                    $nombre = 'Jul';
                    break;
                case 8:
                    $nombre = 'Ago';
                    break;
                case 9:
                    $nombre = 'Sep';
                    break;
                case 10:
                    $nombre = 'Oct';
                    break;
                case 11:
                    $nombre = 'Nov';
                    break;
                case 12:
                    $nombre = 'Dic';
                    break;
            }
            $apartadas = DigitalOrdenCompra::where('evento_id', $this->evento_id)->whereYear('created_at', $year)->whereMonth('created_at', $i)->where('estado_venta', 1)->sum('cantidad_entradas');
            $abonadas = DigitalOrdenCompra::where('evento_id', $this->evento_id)->whereYear('created_at', $year)->whereMonth('created_at', $i)->where('estado_venta', 2)->sum('cantidad_entradas');
            $total = DigitalOrdenCompra::where('evento_id', $this->evento_id)->whereYear('created_at', $year)->whereMonth('created_at', $i)->where('estado_venta', 3)->sum('cantidad_entradas');

            $array[$i - 1] = array(
                'Nombre' => $nombre,
                'Apartadas' => (int)$apartadas,
                'Abonadas' => (int)$abonadas,
                'Total' => (int)$total
            );
        }

        $this->estadisticas = $array;
    }

    public function loadDatos()
    {
        $this->readyToLoad = true;
    }

    public function cargarDatos()
    {
        foreach ($this->Zonas as $zona) {
            $data = DB::table('order_child')
                ->where([
                    ['ticket_id',  $zona->id], ['customer_id', 0]
                ])
                ->pluck('id')
                ->toArray();

            $restan = OrderChildsDigital::whereIn('order_child_id', $data)->count();

            $this->disponibles[$zona->id] = array(
                'ticket_id' => $zona->id,
                'cantidad_restantes' =>  $restan
            );
        }
        $this->loaded = true;
        $hoy = Carbon::now();
        $final = Carbon::parse($this->Evento->end_time);
        $this->dias_restantes = $hoy->diffInDays($final);
        $this->calcularestadisticas($this->Evento->people, 100);
    }

    public function confirmarVenta()
    {

        if (count($this->entradas_seleccionadas) > 0) {
            $this->dispatchBrowserEvent('buscarCliente');
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => __('Debe agregar al menos una entrada')]);
        }
    }

    public function validacionCLiente()
    {
        return AppUser::where('phone', $this->phone)
            ->orWhere('cedula', $this->cedula_cliente)
            ->exists();
    }

    public function procesarCompra()
    {
        if ($this->estado_venta != null) {
            DB::beginTransaction();
            try {
                $this->reset('venta_realizada');

                if ($this->encontrado == false) {
                    $this->phone = $this->prefijo_telefono . $this->telefono;
                    $validacionCliente = $this->validacionCLiente();
                    if ($validacionCliente) {
                        $this->dispatchBrowserEvent('errores', ['error' => __('Ya existe el usuario, realiza una busqueda con el telefono o cedula.')]);
                        return null;
                    }

                    $this->validate([
                        'nombre_cliente' => 'required|max:120|min:2',
                        'prefijo_telefono' => 'required',
                        'telefono' => 'required',
                        'phone' => 'required|phone:COL,AUTO|unique:app_user,phone',
                        'cedula_cliente' => 'required|integer|unique:app_user,cedula'
                    ], [
                        'phone.unique' => 'Ya existe un usuario con este numero de telefono',
                        'cedula_cliente.unique' => 'Ya existe un usuario con este numero de cedula',
                    ]);
                    $cliente = new AppUser();
                    $cliente->name = $this->nombre_cliente;
                    $cliente->phone = $this->phone;
                    $cliente->cedula = $this->cedula_cliente;
                    $cliente->password = Hash::make($this->cedula_cliente);
                    $cliente->provider = "LOCAL";
                    $cliente->image = 'defaultuser.png';
                    $cliente->status = 1;
                    $cliente->borrado = 0;
                    $cliente->save();

                    $this->cliente = $cliente;
                }
                $ordencompra = new DigitalOrdenCompra();
                $ordencompra->identificador = Str::upper(Str::random(7));
                $ordencompra->evento_id = $this->evento_id;
                $ordencompra->vendedor_id = Auth::user()->id;
                $ordencompra->cliente_id = $this->cliente->id;
                $ordencompra->cantidad_entradas = 0;
                $ordencompra->metodo_pago = $this->metodo_de_pago;
                $ordencompra->abonado = $this->abonado;
                $ordencompra->total = $this->total;
                $ordencompra->estado_venta = $this->estado_venta;
                $ordencompra->save();

                $contador = 0;
                $contador2 = 0;

                foreach ($this->entradas_seleccionadas as $es) {
                    $contador2 = $contador2 + $es['cantidad'];
                    $entrada = Ticket::findorfail($es['entrada_id']);

                    if ($entrada->forma_generar == 1) {
                        $childs = OrderChild::where([
                            ['ticket_id', $entrada->id], ['customer_id',  0], ['endosado_id', 0]
                        ])->take($es['cantidad'])->get();
                    } elseif ($entrada->forma_generar == 2) {
                        $childs = OrderChild::where([
                            ['ticket_id', $entrada->id], ['customer_id',  0], ['endosado_id', 0]
                        ])->whereIn('id', $this->entradas_palcos_seleccionados[$entrada->id])->get();
                    }

                    if (count($childs) == $es['cantidad']) {
                        $msg = [];
                        foreach ($childs as $item) {
                            $OrderChildsDigital = OrderChildsDigital::where('order_child_id', $item->id)->first();

                            if ($OrderChildsDigital != null) {
                                $item->customer_id = $this->cliente->id;
                                $item->vendedor_id = Auth::user()->id;
                                $item->update();
                                $detalle = new DigitalOrdenCompraDetalle();
                                $detalle->digital_orden_compra_id = $ordencompra->id;
                                $detalle->order_child_id = $item->id;
                                $detalle->endosado_id = null;
                                $detalle->digital_id = $OrderChildsDigital->id;
                                $detalle->save();

                                $this->venta_realizada[] = array(
                                    'name_entrada' => $entrada->name,
                                    'id' => $item->id,
                                    'identificador' => $item->identificador,
                                    'consecutivo' => $item->consecutivo,
                                    'palco' => $item->mesas,
                                    'asiento' => $item->asiento,
                                );
                                $contador++;
                            } else {
                                $error = __('Ha ocurrido un error, no se han encontrado la entrada digital disponibles para "' . $entrada['name'] . '" con el identificador ' . $item['identificador']);
                                $msg[] = $error;
                                $this->dispatchBrowserEvent('errores', ['error' => $error]);
                                DB::rollBack();
                                break;
                            }
                        }
                    } else {
                        DB::rollBack();
                        $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, no se han encontrado entradas disponibles para ' . $es['name'])]);
                    }
                }

                if ($contador2 == $contador) {
                    $ordencompra->cantidad_entradas = $contador;
                    $ordencompra->update();
                    $this->venta_id = $ordencompra->id;
                    DB::commit();
                    $this->resetExcept(['evento_id', 'readyToLoad', 'cliente', 'venta_id', 'venta_realizada', 'dias_restantes', 'Zonas']);
                    $this->enviado = true;
                    $this->dispatchBrowserEvent('verenviadas');
                    $this->cargarDatos();
                } else {
                    $this->dispatchBrowserEvent('errores', ['error' => implode(', ', $msg)]);
                    DB::rollBack();
                }
            } catch (Exception $e) {
                dd($e);
                DB::rollBack();
                $this->dispatchBrowserEvent('errores', ['error' => $e->getMessage()]);
            }
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => __('Debe seleccionar el estado de la venta')]);
        }
    }

    public function buscarcliente()
    {
        $this->validate([
            'search_telefono' => 'required|max:120'
        ], [
            'search_telefono.required' => 'El campo de busqueda no puede estar vacio',
            'search_telefono.max' => 'El campo de busqueda no puede tener mas de 120 caracteres'
        ]);

        $this->reset(['encontrado', 'cliente']);

        $cl = AppUser::where([
            ['phone', 'LIKE', '%' . $this->search_telefono]
        ])->orWhere([
            ['cedula', 'LIKE',  $this->search_telefono]
        ])->first();

        if ($cl != null) {
            $this->encontrado = true;
            $this->clienteNombre = $cl->name . ' ' . $cl->last_name;
            $this->clienteTelefono = $cl->phone;
            $this->clienteCedula = $cl->cedula;
            $this->cliente = $cl;
            $this->reset('search_telefono');
        } else {
            $this->dispatchBrowserEvent('clientenoencontrado');
        }
    }

    public function enviarcompartir($id)
    {
        $this->dispatchBrowserEvent('cerrarshow1');
        $ent = OrderChildsDigital::findorfail($id);
        $ent->cliente = !empty($this->cliente) ? $this->cliente : $ent->entrada->cliente;
        $ent->evento = $this->Evento;
        $ent->entrada = $ent->zona['name'];
        $this->emit('mostrarCompartir', $ent);
    }

    public function compartirventawhatsapp()
    {
        // $orden = DigitalOrdenCompra::with('cliente')->where('id',$this->venta_id)->first();
        // if(!empty($orden)){
        //     $telefono_cliente = $orden->cliente->phone;
        //     $entradas_vendidas = DigitalOrdenCompraDetalle::where('digital_orden_compra_id', $orden->id)->get();

        //     $evento = Event::find($orden->evento_id)->name;
        //     $texto =  urlencode('*¡Gracias por comprar con '. $this->Setting .'!*') .  '%0d%0a' . 
        //             urlencode('*Evento*: ' . $evento) .  '%0d%0a' . 
        //             urlencode('*Identificador venta*: ' . $orden->identificador).  '%0d%0a'.
        //             urlencode('*Cantidad entradas*: x' . $orden->cantidad_entradas).  '%0d%0a'.
        //             urlencode('*Entradas*: ').  '%0d%0a';

        //     foreach ($entradas_vendidas as $entrada) {
        //         $ent = OrderChildsDigital::with(['zona:id,name,forma_generar', 'entrada:id,identificador,mesas,asiento'])->where('id',$entrada->digital_id)->first();
        //         $key = base64_encode('@kf#'.$entrada->digital_id);
        //         $url = route('ver.archivo', $key);
        //         $text = urlencode('- ' .  $ent->zona->name). '%0d%0a';
        //         $text .=  urlencode('  » _Identificador_: ' . $ent->entrada->identificador) . '%0d%0a';
        //         if($ent->zona->forma_generar == 2){
        //             $text .=  urlencode('  » _Palco_ #' .  $ent->entrada->mesas) . '%0d%0a';
        //             $text .=  urlencode('  » _Asiento_ #' .  $ent->entrada->asiento) . '%0d%0a';
        //         }
        //         $text .=  urlencode('  » _Descargar tu entrada_: ' . $url) . '%0d%0a';
        //         $texto .= $text;
        //     }              
        //     $url = 'https://api.whatsapp.com/send?phone='. urlencode( $telefono_cliente ) . '&text='. $texto;
        //     $this->dispatchBrowserEvent('compartirwhatsapp1', ['url' => $url]);
        // }else{
        //     $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, contacta al administrador')]);
        // }        
    }

    public function compartirventawhatsapp2()
    {
        $orden = DigitalOrdenCompra::with(['cliente', 'evento'])->where('id', $this->venta_id)->first();
        $key = base64_encode('@kf#' . $orden->identificador);
        $url = route('ver.archivo', $key);

        $texto =  urlencode('*¡Gracias por comprar con ' . $this->Setting . '!*') .  '%0d%0a' .
            urlencode('*Nombre*: ' . $orden->cliente->name . ' ' .  $orden->cliente->last_name) .  '%0d%0a' .
            urlencode('*Evento*: ' . $orden->evento->name) .  '%0d%0a' .
            urlencode('*Identificador venta*: ' . $orden->identificador) .  '%0d%0a' .
            urlencode('*Cantidad entradas*: x' . $orden->cantidad_entradas) .  '%0d%0a' .
            urlencode('*Descargar aca* : _' . $url . '_') . '%0d%0a';

        $url = 'https://api.whatsapp.com/send?phone=' . urlencode($orden->cliente->phone) . '&text=' . $texto;
        $this->dispatchBrowserEvent('compartirwhatsapp1', ['url' => $url]);
    }

    public function compartirsms()
    {
        $orden = DigitalOrdenCompra::with(['cliente', 'evento'])->where('id', $this->venta_id)->first();
        $key = base64_encode('@kf#' . $orden->identificador);
        $url = route('ver.archivo', $key);
        if (!empty($orden)) {
            $telefono = $orden->cliente->phone;
            $texto =  urlencode('¡Gracias por comprar con ' . $this->Setting . '!') .  '%0d%0a' .
                urlencode('Nombre: ' . $orden->cliente->name . ' ' .  $orden->cliente->last_name) .  '%0d%0a' .
                urlencode('Evento: ' . $orden->evento->name) .  '%0d%0a' .
                urlencode('Identificador venta: ' . $orden->identificador) .  '%0d%0a' .
                urlencode('Cantidad entradas: x' . $orden->cantidad_entradas) .  '%0d%0a' .
                urlencode('Descargar aca : ' . $url) . '%0d%0a';

            $this->enviarsms2($telefono, $texto);

            $this->dispatchBrowserEvent('enviadosms');
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => __('Ha ocurrido un error, contacta al administrador')]);
        }
    }

    public function agregarCarritoPalco()
    {
        $entrada = $this->Zonas->find($this->palco_id);
        $contador = count($this->seleccionados_palcos);
        if ($this->disponibles[$this->palco_id]['cantidad_restantes'] > 0) {
            $this->entradas_seleccionadas[$this->palco_id] = array(
                'entrada_id' => $entrada->id,
                'entrada_name' =>  $entrada->name,
                'cantidad' => $contador
            );
            $this->entradas_palcos_seleccionados[$this->palco_id] = $this->seleccionados_palcos;
            $this->reset(['seleccionados_palcos', 'grupos_palcos', 'palco_id']);
            $this->dispatchBrowserEvent('cerrarmodals');
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => __('La entrada no cuenta con entradas disponibles')]);
        }
    }

    public function agregarEntrada($id)
    {
        $entrada = $this->Zonas->find($id);
        if ($entrada) {
            if ($entrada->forma_generar == 1) {
                if (array_key_exists($id, $this->entradas_seleccionadas)) {
                    if ($this->entradas_seleccionadas[$id]['cantidad'] <= $this->disponibles[$id]['cantidad_restantes']) {
                        $this->entradas_seleccionadas[$id]['cantidad']++;
                    } else {
                        $this->dispatchBrowserEvent('errores', ['error' => __('Ya ha alcanzado el maximo de entradas disponibles')]);
                    }
                } else {
                    if ($this->disponibles[$id]['cantidad_restantes'] > 0) {
                        $this->entradas_seleccionadas[$id] = array(
                            'entrada_id' => $entrada->id,
                            'entrada_name' =>  $entrada->name,
                            'cantidad' => 1
                        );
                    } else {
                        $this->dispatchBrowserEvent('errores', ['error' => __('La entrada no cuenta con entradas disponibles')]);
                    }
                }
            } elseif ($entrada->forma_generar == 2) {
                $this->palco_id = $id;
                $this->grupos_palcos = OrderChild::where([
                    ['ticket_id', $entrada->id], ['customer_id', 0]
                ])->get()
                    ->makeHidden(['updated_at', 'created_at', 'ticket_hash4', 'ticket_hash3', 'ticket_hash2', 'ticket_hash', 'ticket_number2', 'ticket_number', 'metodo_pago_status', 'metodo_pago'])
                    ->groupBy('mesas')->toBase();
                if (count($this->grupos_palcos) > 0) {
                    $this->dispatchBrowserEvent('openAggPalco');
                } else {
                    $this->dispatchBrowserEvent('errores', ['error' => __('No hay entradas disponible')]);
                }
            }
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => __('Entrada no disponible')]);
        }
    }

    public function quitarEntrada($id)
    {
        $entrada = $this->Zonas->find($id);
        if ($entrada) {
            if (array_key_exists($id, $this->entradas_seleccionadas)) {
                if ($this->entradas_seleccionadas[$id]['cantidad'] <= $this->disponibles[$id]['cantidad_restantes'] &&  $this->entradas_seleccionadas[$id]['cantidad'] > 1) {
                    $this->entradas_seleccionadas[$id]['cantidad']--;
                } else {
                    unset($this->entradas_seleccionadas[$id]);
                }
            }
        } else {
            $this->dispatchBrowserEvent('errores', ['error' => __('Entrada no disponible')]);
        }
    }

    public function limpiar()
    {
        $this->reset(['grupos_palcos']);
        // $this->resetExcept(['loaded', 'readyToLoad']);
        $this->dispatchBrowserEvent('cerrarmodals');
    }

    public function cerrarModalReporte()
    {
        $this->dispatchBrowserEvent('cerrarModalReporte');
        $this->resetPage();
    }

    public function cerrarModalEstadisticas()
    {
        $this->dispatchBrowserEvent('cerrarModalEstadisitica');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function borrarEntradaCarrito($id)
    {
        unset($this->entradas_seleccionadas[$id]);
    }

    public function seleccionarPalco($mesas)
    {
        foreach ($this->grupos_palcos[$mesas] as $m) {
            $this->seleccionados_palcos[] = $m['id'];
        }
    }

    public function cerrarshow()
    {
        $this->dispatchBrowserEvent('cerrarshow2');
    }

    public function getEventoProperty()
    {
        return Event::where('id', $this->evento_id)->first();
    }

    public function getZonasProperty()
    {
        if (Auth::user()->hasRole('punto venta')) {
            $data = DB::table('ticket_punto_ventas')
                ->where([
                    ['pventa_id',   Auth::user()->id], ['event_id', $this->evento_id]
                ])
                ->pluck('ticket_id')
                ->toArray();

            return Ticket::where([
                ['event_id', $this->evento_id], ['is_deleted', 0], ['tipo', 1], ['categoria', 2], ['status', 1], ['is_deleted', 0]
            ])->whereIn('id', $data)->select('id', 'event_id', 'name', 'tipo', 'categoria', 'ticket_per_order', 'palcos', 'puestos', 'forma_generar')->get();
        } else {
            return Ticket::where([
                ['event_id', $this->evento_id], ['is_deleted', 0], ['tipo', 1], ['categoria', 2], ['status', 1], ['is_deleted', 0]
            ])->select('id', 'event_id', 'name', 'tipo', 'categoria', 'ticket_per_order', 'palcos', 'puestos', 'forma_generar')->get();
        }
    }

    public function getSettingProperty()
    {
        return Setting::findorfail(1)->app_name;
    }

    public function getHistorialsProperty()
    {
        if ($this->readyToLoad) {
            if (Auth::user()->hasRole('punto venta')) {
                return DigitalOrdenCompra::where(function ($query) {
                    $query->where('identificador', 'LIKE', '%' . $this->search . '%')
                        ->where('evento_id', $this->evento_id)
                        ->orWhereHas('cliente', function ($query) {
                            $query->where('name', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('cedula', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('phone', 'LIKE', '%' . $this->search . '%');
                        });
                })
                    ->where('vendedor_id', Auth::user()->id)
                    ->join('app_user', 'digital_orden_compras.cliente_id', '=', 'app_user.id')
                    ->select('digital_orden_compras.*',  'app_user.id AS appuser_id')
                    ->orderBy('digital_orden_compras.id', 'DESC')
                    ->paginate(12);
            } else {
                return DigitalOrdenCompra::where(function ($query) {
                    $query->where('identificador', 'LIKE', '%' . $this->search . '%')
                        ->where('evento_id', $this->evento_id)
                        ->orWhereHas('cliente', function ($query) {
                            $query->where('name', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('cedula', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('phone', 'LIKE', '%' . $this->search . '%');
                        });
                })
                    ->join('app_user', 'digital_orden_compras.cliente_id', '=', 'app_user.id')
                    ->select('digital_orden_compras.*',  'app_user.id AS appuser_id')
                    ->orderBy('digital_orden_compras.id', 'DESC')
                    ->paginate(12);
            }
        } else {
            return [];
        }
    }

    private function enviarsms2($telefono, $mensaje)
    {
        $data = array(
            'telefono' => $telefono,
            'mensaje' => $mensaje,
        );
        $data = (new ApiController)->sendsms2($data);
    }


    public function cerrarVenta()
    {
        $this->reset([
            'encontrado', 'cliente', 'estado_venta', 'abonado', 'total', 'metodo_de_pago', 'search_telefono', 'clienteNombre', 'clienteTelefono', 'clienteCedula', 'nombre_cliente', 'prefijo_telefono',
            'telefono', 'cedula_cliente'
        ]);
        $this->dispatchBrowserEvent('cerrarshow1');
    }

    public function descargarReporteOrganizador()
    {
        //$this->dispatchBrowserEvent('cerrarModalReporte');
        $this->dispatchBrowserEvent('downloadReport');
    }

    public function solicitarAnulacion()
    {
        $historial = DigitalOrdenCompra::where('id', $this->venta_id)->first();

        $anulacion = new DigitalOrdenCompraAnulado();
        $anulacion->identificador = $historial->identificador;
        $anulacion->compra_id = $historial->id;
        $anulacion->organizador_id = Auth::user()->id;
        $anulacion->estado = 0;
        $anulacion->save();


        $historial->anulado = 1;
        $historial->update();

        $this->cerrarshow();

        $this->dispatchBrowserEvent('solicitadoAnulacion');
    }
}
