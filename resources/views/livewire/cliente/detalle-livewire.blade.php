<div wire:init="loadDatos">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-12 col-lg-10">
                <div class="card login-box-container">
                    <div class="authent-logo p-3">
                        <img src="{{ asset(route('inicio.frontend') . '/images/upload/' . \App\Models\Setting::find(1)->logo) }}"
                            alt="">
                    </div>
                    <div class="card-body row">
                        <div class="col-12 mb-3 row">
                            <div class="col-md-3 col-12 mb-2">
                                <h5>{{ __('Cliente') }}</h5>
                                <span>{{ $this->Cliente->name . ' ' . $this->Cliente->last_name }}</span>
                            </div>
                            <div class="col-md-3 col-12 mb-2">
                                <h5>{{ __('Identificador') }}</h5> <span>#{{ $this->Ordencompra->identificador }}</span>
                            </div>
                            <div class="col-md-3 col-12 mb-2">
                                <h5>{{ __('Evento') }}</h5> <span>{{ $this->Ordencompra->evento->name }}</span>
                            </div>
                            <div class="col-md-3 col-12 mb-2">
                                <h5>{{ __('Cantidad entradas') }}</h5>
                                <span>x{{ number_format($this->Ordencompra->cantidad_entradas, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            @desktop
                                <div class="table-responsive my-2">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                @desktop
                                                    <th scope="col">#</th>
                                                @elsedesktop
                                                    <th scope="col">{{ __('Acción') }}</th>
                                                @enddesktop

                                                <th scope="col">{{ __('Entrada') }}</th>
                                                <th scope="col">{{ __('Identificador') }}</th>
                                                <th scope="col">{{ __('Palco') }}</th>
                                                <th scope="col">{{ __('Asiento') }}</th>
                                                <th scope="col">{{ __('Endosado') }}</th>
                                                @desktop
                                                    <th scope="col">{{ __('Acción') }}</th>
                                                @enddesktop

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($this->Ordencompradetalle as $detalle)
                                                <tr>
                                                    @desktop
                                                        <th scope="row">#{{ $loop->iteration }}</th>
                                                    @elsedesktop
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-block"
                                                                wire:click="compartir({{ $detalle->id }})"
                                                                wire:key="btn-compartir-{{ $detalle->id }}">
                                                                <div wire:loading.inline
                                                                    wire:target="compartir({{ $detalle->id }})">
                                                                    <div class="spinner-grow spinner-grow-sm" role="status">
                                                                    </div>
                                                                </div>
                                                                <div wire:loading.remove
                                                                    wire:target="compartir({{ $detalle->id }})">
                                                                    <i class="fas fa-share-alt"></i>
                                                                </div>

                                                            </button>
                                                            <button type="button" class="btn btn-success btn-block"
                                                                wire:click="descargar({{ $detalle->id }})"
                                                                wire:key="btn-descargar-{{ $detalle->id }}">
                                                                <div wire:loading.inline
                                                                    wire:target="descargar({{ $detalle->id }})">
                                                                    <div class="spinner-grow spinner-grow-sm" role="status">
                                                                    </div>
                                                                </div>
                                                                <div wire:loading.remove
                                                                    wire:target="descargar({{ $detalle->id }})">
                                                                    <i class="fas fa-cloud-download-alt"></i>
                                                                </div>
                                                            </button>

                                                        </td>
                                                    @enddesktop

                                                    <td>{{ $detalle->entrada->evento->name }}</td>
                                                    <td>{{ $detalle->entrada->identificador }}</td>
                                                    <td>{{ $detalle->entrada->mesas != '' ? $detalle->entrada->mesas : 'No' }}
                                                    </td>
                                                    <td>{{ $detalle->entrada->asiento != '' ? $detalle->entrada->asiento : 'No' }}
                                                    </td>
                                                    <td>
                                                        @if ($detalle->endosado_id != null)
                                                            <span
                                                                class="badge bg-success">{{ $detalle->endosado->name . ' ' . $detalle->endosado->last_name }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ __('No endosado') }}</span>
                                                        @endif
                                                    </td>
                                                    @desktop
                                                        <td>
                                                            <button type="button" class="btn btn-primary"
                                                                wire:click="compartir({{ $detalle->id }})"
                                                                wire:key="btn-compartir-{{ $detalle->id }}">
                                                                <div wire:loading.inline
                                                                    wire:target="compartir({{ $detalle->id }})">
                                                                    <div class="spinner-grow spinner-grow-sm" role="status">
                                                                    </div>
                                                                </div>
                                                                <div wire:loading.remove
                                                                    wire:target="compartir({{ $detalle->id }})">
                                                                    <i class="fas fa-share-alt"></i>
                                                                </div>

                                                            </button>
                                                            <button type="button" class="btn btn-success"
                                                                wire:click="descargar({{ $detalle->id }})"
                                                                wire:key="btn-descargar-{{ $detalle->id }}">
                                                                <div wire:loading.inline
                                                                    wire:target="descargar({{ $detalle->id }})">
                                                                    <div class="spinner-grow spinner-grow-sm" role="status">
                                                                    </div>
                                                                </div>
                                                                <div wire:loading.remove
                                                                    wire:target="descargar({{ $detalle->id }})">
                                                                    <i class="fas fa-cloud-download-alt"></i>
                                                                </div>
                                                            </button>

                                                        </td>
                                                    @enddesktop
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center justify-content-center">
                                                        ¡{{ __('No hay entradas disponibles') }}!
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            @elsedesktop
                                @forelse ($this->Ordencompradetalle as $detalle)
                                    <div class="card mb-3 bg-primary">
                                        <div class="card-body text-center justify-content-center ">
                                            <h3 class="text-white">{{ $detalle->entrada->evento->name }}</h3>
                                            <h4 class="text-white">{{ $detalle->entrada->identificador }}</h4>

                                            <button type="button" class="btn btn-success w-100 my-2"
                                                wire:click="descargar({{ $detalle->id }})"
                                                wire:key="btn-descargar-{{ $detalle->id }}">
                                                <div wire:loading.inline wire:target="descargar({{ $detalle->id }})">
                                                    <div class="spinner-grow spinner-grow-sm" role="status"></div>
                                                </div>
                                                <div wire:loading.remove wire:target="descargar({{ $detalle->id }})">
                                                    {{ __('Descargar') }}
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                            @enddesktop

                            @if (count($this->Ordencompradetalle) > 0)
                                <div class="row text-center justify-content-center mt-2" style="max-width: 99%">
                                    @desktop
                                        {{ $this->Ordencompradetalle->links() }}
                                    @elsedesktop
                                        {{ $this->Ordencompradetalle->onEachSide(1)->links() }}
                                    @enddesktop
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <small>*{{ __('Este token es solo valido por 2 horas') }}</small> <br>
                        <small wire:poll.100ms> {{ __('Tiempo restante: ') }} <b>
                                {{ gmdate('H:i:s', Carbon\Carbon::create($decodificado['exp'])->diffInSeconds(now())) }}
                            </b></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($readytoLoad)
        @include('cliente.modal.compartir')
        @include('cliente.modal.compartirphone')
    @endif
    <script>
        window.addEventListener('errores', event => {
            let timerInterval
            Swal.fire({
                icon: 'error',
                title: '¡Error! ',
                text: event.detail.error,
                timer: 3500,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            })
        })
        window.addEventListener('descargar', event => {
            var tab = window.open(event.detail.url);
            if (tab) {
                tab.focus(); //ir a la pestaña
            } else {
                alert('Pestañas bloqueadas, activa las ventanas emergentes (Popups) ');
                return false;
            }
        })
        window.addEventListener('cerrarModals', event => {
            $('#compartir').modal('hide');
            $('#compartirphone').modal('hide');
            $('#endosar').modal('hide');
        })
        window.addEventListener('openCompartir', event => {
            $('#compartirphone').modal('hide');
            $('#compartir').modal('show');
        })
        window.addEventListener('openCompartirPhone', event => {
            $('#compartir').modal('hide');
            $('#compartirphone').modal('show');
        })
        window.addEventListener('mensajeEnviado', event => {
            $('#compartir').modal('hide');
            $('#compartirphone').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El mensaje ha sido enviado correctamente',
                showConfirmButton: false,
                timer: 1700
            })
        })

        window.addEventListener('cedulaEncontrada', event => {
            Swal.fire({
                icon: 'question',
                title: '¡Cedula encontrada!',
                html: 'Cedula a nombre de "' + event.detail.nombre +
                    '"<br> ¿Desea seleccionar esta persona?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Si',
                denyButtonText: `No`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.livewire.emit('endosado2')
                    Swal.fire('¡Endosando!', 'Espere un momento...', 'success')
                    $('#endosar').modal('hide');
                } else if (result.isDenied) {
                    // Swal.fire('Changes are not saved', '', 'info')
                }
            })
        })
    </script>
</div>
