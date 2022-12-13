<div wire:init="loadDatos">
    <div class="col-sm-12 col-md-12">
        <div class="row">
           
            <h4 class="m-b-md m-t-md text-white">  <a href="{{ route('mis.eventos') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> {{ __('Regresar') }}</a> {{ __('Venta Digital') }}</h4>

            <div class="text-center justify-content-center">
                <img class="img-fluid rounded-circle" src="{{ asset(route('inicio.frontend') . '/images/upload/'. $this->Evento->image) }}" 
                alt="img-responsive" style=" width:200px; height:auto" >
                <h1 class="m-b-md m-t-md text-white">{{ $this->Evento->name }}</h1>
            </div>

            <div class="col-md-9 col-12" wire:init="cargarDatos">
                <div class="card" style="min-height: 400px;">
                    <div class="card-body row">
                        @forelse ($this->Zonas as $zona)
                            <div class="col-md-4 col-12 border row my-3 p-3 rounded" style="margin-right:10px">
                                <div class="col-md-2 d-md-block  d-sm-none">
                                    <i class="fas fa-ticket-alt" style="font-size: 28px; color: #091763"></i>
                                </div>
                                <div class="col-md-5 col-6">
                                    <span class="font-weight-bold">{{ $zona->name }}</span><br>
                                    @if ($loaded)
                                        <small>{{ number_format($disponibles[$zona->id]['cantidad_restantes'], 0 ,',','.' ) }}  {{ $zona->forma_generar == 1 ? __('Disponibles') : __('Asientos') }} </small>
                                    @else
                                        <small>{{ __('Cargando') }}...</small>
                                    @endif
                                </div>
                                <div class="col-md-2 col-3">
                                    <button class="btn btn-secondary" type="button" wire:click="quitarEntrada('{{ $zona->id }}')" ><i class="fas fa-minus"></i></button>
                                </div>
                                <div class="col-md-1 d-md-block d-sm-none">
                                </div>
                                <div class="col-md-2 col-3">
                                    <button class="btn btn-secondary" type="button" wire:click="agregarEntrada('{{ $zona->id }}')" ><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        @empty
                            <h3 class="text-center justify-content-center" >{{ __('No hay entradas disponibles') }}</h3>
                        @endforelse
                    </div>
                </div>
            </div>

            @desktop
                <div class="col-md-3">
                    <div class="card" style="min-height: 400px;">
                        <div class="card-header">
                            <h5 class="text-center justify-content-center">{{ __('Entradas seleccionadas') }}</h5>
                        </div>
                        <div class="card-body card-body-shop" style="padding: 5px 25px">
                            <div class="table-responsive my-1">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('Entrada') }}</th>
                                            <th scope="col">{{ __('Cantidad') }}</th>
                                            <th scope="col">{{ __('Acción') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($entradas_seleccionadas as $en)
                                            <tr>
                                                <td> {{ $en['entrada_name'] }}</td>
                                                <td>{{ number_format($en['cantidad'], 0 ,',','.' )  }}</td>
                                                <td>
                                                    <button class="btn btn-danger" wire:click="borrarEntradaCarrito('{{ $en['entrada_id'] }}')"> <i class="fas fa-trash-alt"></i> </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">{{ __('No se ha seleccionado entrada') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-success btn-block" wire:click="confirmarVenta" {{ count($entradas_seleccionadas) == 0 ? 'disabled' : '' }}>{{ __('Procesar') }}</button>
                        </div>
                    </div>
                </div>
            @elsedesktop
                    <a class=" btn-flotante" href="#" id="shop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-shopping-cart"></i> {{ __('Ver carrito') . ' x' . number_format(array_sum(array_column($entradas_seleccionadas,'cantidad')), 0 ,',','.') }}
                    </a>
                    <div class="dropdown-menu  profile-drop-menu" aria-labelledby="shop" >
                        <div class="card dropdown-item" style="padding: 5px">
                            <div class="card-header">
                                <h5 class="text-center justify-content-center">{{ __('Entradas seleccionadas')  }}</h5>
                            </div>
                            <div class="card-body card-body-shop" style="padding: 5px 25px">
                                <div class="table-responsive my-1">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('Entrada') }}</th>
                                                <th scope="col">{{ __('Cantidad') }}</th>
                                                <th scope="col">{{ __('Acción') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($entradas_seleccionadas as $en)
                                                <tr>
                                                    <td> {{ $en['entrada_name'] }}</td>
                                                    <td>{{ number_format($en['cantidad'], 0 ,',','.' )  }}</td>
                                                    <td>
                                                        <button class="btn btn-danger" wire:click="borrarEntradaCarrito('{{ $en['entrada_id'] }}')"> <i class="fas fa-trash-alt"></i> </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">{{ __('No se ha seleccionado entrada') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-success btn-block" wire:click="confirmarVenta" {{ count($entradas_seleccionadas) == 0 ? 'disabled' : '' }}>{{ __('Procesar') }}</button>
                            </div>
                        </div>                        
                    </div>
               
            @enddesktop
            
        </div>
    </div>

    @includeWhen($readyToLoad, 'miseventos.modal.aggPalcos')
    @includeWhen($readyToLoad, 'miseventos.modal.buscarcliente')
    @includeWhen($enviado, 'backendv2.miseventos.modal.verventa') 

    @livewire('misentradas.compartir-venta-digital-livewire')

    <script>
        window.addEventListener('errores', event => {
            Swal.fire(
                '¡Error!',
                event.detail.error,
                'error'
            )
        });
        window.addEventListener('openAggPalco', event => {
            $('#aggPalco').modal('show');
        });

        window.addEventListener('cerrarmodals', event => {
            $('#aggPalco').modal('hide');
        });
        window.addEventListener('buscarCliente', event => {
            $('#buscarCliente').modal('show');
        });
        window.addEventListener('clientenoencontrado', event => {
            Swal.fire({
                icon: 'error',
                title: '¡No encontrado!',
                text: 'El cliente no se ha encontrado',
                showConfirmButton: false,
                timer: 1500
            })
        })
        window.addEventListener('verenviadas', event => {
            $('#buscarCliente').modal('hide');
            let timerInterval
            Swal.fire({
                icon :'success',
                title: '¡Exito! ',
                text: '¡La entrada se han vendidos correctamente!',
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                    }
                }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        $('#verventa').modal('show');
                    }
                })
        })
        window.addEventListener('cerrarshow1', event => {
            $('#buscarclient').modal('hide');
            $('#ventas').modal('hide');
            $('#verventa').modal('hide');
            $('#verenlace').modal('hide');
            $('#compartir').modal('hide');
        })
        window.addEventListener('compartirwhatsapp1', event => {
            var tab = window.open(event.detail.url);
            if(tab){
                tab.focus(); //ir a la pestaña
            }else{
                alert('Pestañas bloqueadas, activa las ventanas emergentes (Popups) ');
                return false;
            }
        })
    </script>
</div>
