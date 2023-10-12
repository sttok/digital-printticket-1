<div wire:init="loadDatos">
    <div class="col-sm-12 col-md-12">
        <div class="row">

            <h4 class="m-b-md m-t-md text-white"> <a href="{{ route('mis.eventos') }}" class="btn btn-secondary"
                    style="margin-right: 15px">
                    <i class="fas fa-arrow-left"></i>
                    @desktop
                        {{ __('Regresar') }}
                    @enddesktop
                </a> {{ __('Venta Digital') }}
            </h4>

            <div class="text-center justify-content-center">
                <img class="img-fluid rounded-circle zoom"
                    src="{{ asset(route('inicio.frontend') . '/images/upload/' . $this->Evento->image) }}"
                    alt="img-responsive" style=" width:200px; height:auto">
                <h1 class="m-b-md m-t-md text-white">{{ $this->Evento->name }}</h1>
            </div>

            <div class="col-md-9 col-12" wire:init="cargarDatos">
                <div class="card" style="min-height: 400px;">
                    <div class="card-body row">
                        @if ($readyToLoad)
                            @forelse ($this->Zonas as $zona)
                                <div class="col-lg-6 col-md-12 col-12 border row my-3 p-3 rounded"
                                    style="margin-right:10px; max-height: 135px;">
                                    <div class="col-md-2 d-md-block  d-sm-none">
                                        <i class="fas fa-ticket-alt" style="font-size: 28px; color: #091763"></i>
                                    </div>
                                    <div class="col-lg-5 col-md-8 col-6">
                                        <span class="font-weight-bold">{{ $zona->name }}</span><br>
                                        @if ($loaded)
                                            <small>{{ number_format($disponibles[$zona->id]['cantidad_restantes'], 0, ',', '.') }}
                                                {{ $zona->forma_generar == 1 ? __('Disponibles') : __('Asientos') }}
                                            </small>
                                        @else
                                            <small>{{ __('Cargando') }}...</small>
                                        @endif
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-3 text-center">
                                        <button class="btn btn-secondary btn-block" type="button"
                                            wire:click="quitarEntrada('{{ $zona->id }}')">
                                            <div wire:loading wire:target="quitarEntrada('{{ $zona->id }}')">
                                                <div class="spinner-grow spinner-grow-sm my-1" role="status">
                                                </div>
                                            </div>
                                            <i class="fas fa-minus" wire:loading.remove
                                                wire:target="quitarEntrada('{{ $zona->id }}')"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-1 d-md-block d-sm-none">
                                    </div>
                                    <div class="col-lg-2 col-md-5 col-3 text-center">
                                        <button class="btn btn-secondary btn-block" type="button"
                                            wire:click="agregarEntrada('{{ $zona->id }}')">
                                            <div wire:loading wire:target="agregarEntrada('{{ $zona->id }}')">
                                                <div class="spinner-grow spinner-grow-sm my-1" role="status">
                                                </div>
                                            </div>
                                            <i class="fas fa-plus" wire:loading.remove
                                                wire:target="agregarEntrada('{{ $zona->id }}')"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <h3 class="text-center justify-content-center">{{ __('No hay entradas disponibles') }}
                                </h3>
                            @endforelse
                        @else
                            <div class="border p-3 rounded col-md-6 col-12 my-2" style="max-height: 135px;">
                                <div class="background  ">
                                    <div class="left">
                                        <div class="image"></div>
                                    </div>
                                    <div class="right">
                                        <div class="bar"></div>
                                        <div class="mask thick"></div>
                                        <div class="bar"></div>
                                        <div class="mask thin"></div>
                                        <div class="bar medium"></div>
                                        <div class="mask thick"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
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
                                                <td class="font-weight-bold">
                                                    {{ number_format($en['cantidad'], 0, ',', '.') }}</td>
                                                <td>
                                                    <button class="btn btn-danger"
                                                        wire:click="borrarEntradaCarrito('{{ $en['entrada_id'] }}')"> <i
                                                            class="fas fa-trash-alt"></i> </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    {{ __('No se ha seleccionado entrada') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-success btn-block" wire:click="confirmarVenta"
                                    {{ count($entradas_seleccionadas) == 0 ? 'disabled' : '' }}>{{ __('Procesar') }}</button>
                            </div>
                        </div>
                    </div>
                @elsedesktop
                    <a class=" btn-flotante" href="#" id="shop" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-shopping-cart"></i>
                        {{ __('Ver carrito') . ' x' . number_format(array_sum(array_column($entradas_seleccionadas, 'cantidad')), 0, ',', '.') }}
                    </a>
                    <div class="dropdown-menu  profile-drop-menu" aria-labelledby="shop">
                        <div class="card dropdown-item" style="padding: 5px">
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
                                                    <td class="font-weight-bold">
                                                        {{ number_format($en['cantidad'], 0, ',', '.') }}</td>
                                                    <td>
                                                        <button class="btn btn-danger"
                                                            wire:click="borrarEntradaCarrito('{{ $en['entrada_id'] }}')">
                                                            <i class="fas fa-trash-alt"></i> </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">
                                                        {{ __('No se ha seleccionado entrada') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-success btn-block" wire:click="confirmarVenta"
                                    {{ count($entradas_seleccionadas) == 0 ? 'disabled' : '' }}>{{ __('Procesar') }}</button>
                            </div>
                        </div>
                    </div>
                @enddesktop

            </div>
        </div>

        @includeWhen($readyToLoad, 'miseventos.modal.aggPalcos')
        @includeWhen($readyToLoad, 'miseventos.modal.buscarcliente')
        @includeWhen($enviado, 'backendv2.miseventos.modal.verventa')

        @includeWhen($readyToLoad, 'miseventos.modal.reporte')
        @includeWhen($readyToLoad, 'miseventos.modal.estadisticas')

        @livewire('misentradas.compartir-venta-digital-livewire')

        <div>
            @desktop
                <a href="https://wa.me/573113190487?text%C2%A1Hola%20PrintTicket%21%20Me%20gustar%C3%ADa%20soporte%20t%C3%A9cnico%20en%20el%20servicio%20Digital"
                    target="_blank" class="btn-flotante btn-whatsapp">
                    Soporte tecnico
                    <i class="fab fa-whatsapp"></i>
                </a>
            @elsedesktop
                <a href="https://wa.me/573113190487?text%C2%A1Hola%20PrintTicket%21%20Me%20gustar%C3%ADa%20soporte%20t%C3%A9cnico%20en%20el%20servicio%20Digital"
                    target="_blank" class="btn-flotante btn-whatsapp"
                    style="bottom: 80px !important; right: -210px !important;">
                    <i class="fab fa-whatsapp" style="font-size:25px !important"></i>
                </a>
            @enddesktop

        </div>


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
                    icon: 'success',
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
            window.addEventListener('enviadosms', event => {
                let timerInterval
                Swal.fire({
                    icon: 'success',
                    title: '¡Exito! ',
                    text: '¡El mensaje ha sido enviado correctamente!',
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    }
                }).then((result) => {

                })
            })
            window.addEventListener('cerrarshow1', event => {
                $('#buscarCliente').modal('hide');
                $('#ventas').modal('hide');
                $('#verventa').modal('hide');
                $('#verenlace').modal('hide');
                $('#compartir').modal('hide');
            })
            window.addEventListener('compartirwhatsapp1', event => {
                var tab = window.open(event.detail.url);
                if (tab) {
                    tab.focus(); //ir a la pestaña
                } else {
                    alert('Pestañas bloqueadas, activa las ventanas emergentes (Popups) ');
                    return false;
                }
            })
        </script>

        <script>
            window.addEventListener('openReporte1', event => {
                $('body').removeClass("sidebar-hidden");
                $('#reporte').modal('show');
            });
            window.addEventListener('cerrarModalReporte', event => {
                $('#reporte').modal('hide');
            });
            window.addEventListener('abrirDetalle', event => {
                $('#reporte').modal('hide');
                $('#verventa').modal('show');
            });
            window.addEventListener('openEstadisticas1', event => {
                $('body').removeClass("sidebar-hidden");
                $('#estadisitica').modal('show');
            });
            window.addEventListener('cerrarModalEstadisitica', event => {
                $('#estadisitica').modal('hide');
            });
        </script>

    </div>
