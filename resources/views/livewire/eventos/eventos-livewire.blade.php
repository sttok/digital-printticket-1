<div wire:init="loadDatos">
    <style>
        .skeleton {
            padding: 10px;
            max-width: 300px;
            width: 100%;
            /* background: #fff; */
            margin-bottom: 5px;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            /* box-shadow: 0 3px 4px 0 rgba(0, 0, 0, .14), 0 3px 3px -2px rgba(0, 0, 0, .2), 0 1px 8px 0 rgba(0, 0, 0, .12); */
        }

        .skeleton .square {
            height: 80px;
            border-radius: 5px;
            background: rgba(130, 130, 130, 0.2);
            background: -webkit-gradient(linear, left top, right top, color-stop(8%, rgba(130, 130, 130, 0.2)), color-stop(18%, rgba(130, 130, 130, 0.3)), color-stop(33%, rgba(130, 130, 130, 0.2)));
            background: linear-gradient(to right, rgba(130, 130, 130, 0.2) 8%, rgba(130, 130, 130, 0.3) 18%, rgba(130, 130, 130, 0.2) 33%);
            background-size: 800px 100px;
            animation: wave-squares 2s infinite ease-out;
        }

        .skeleton .line {
            height: 12px;
            margin-bottom: 6px;
            border-radius: 2px;
            background: rgba(130, 130, 130, 0.2);
            background: -webkit-gradient(linear, left top, right top, color-stop(8%, rgba(130, 130, 130, 0.2)), color-stop(18%, rgba(130, 130, 130, 0.3)), color-stop(33%, rgba(130, 130, 130, 0.2)));
            background: linear-gradient(to right, rgba(130, 130, 130, 0.2) 8%, rgba(130, 130, 130, 0.3) 18%, rgba(130, 130, 130, 0.2) 33%);
            background-size: 800px 100px;
            animation: wave-lines 2s infinite ease-out;
        }

        .skeleton-right {
            flex: 1;
        }

        .skeleton-left {
            flex: 2;
            padding-right: 15px;
        }

        .flex1 {
            flex: 1;
        }

        .flex2 {
            flex: 2;
        }

        .skeleton .line:last-child {
            margin-bottom: 0;
        }       

        .h15 {
            height: 15px !important;
        }

        .w33{
            width: 33% !important
        }        

        .w75 {
            width: 75% !important
        }

        .m10 {
            margin-bottom: 10px !important;
        }

        .circle {
            border-radius: 50% !important;
            height: 80px !important;
            width: 80px;
        }

        @keyframes wave-lines {
            0% {
                background-position: -468px 0;
            }

            100% {
                background-position: 468px 0;
            }
        }

        @keyframes wave-squares {
            0% {
                background-position: -468px 0;
            }

            100% {
                background-position: 468px 0;
            }
        }
        .hidden{
            display: none !important
        }
        @media only screen and (max-width: 600px) {
            .w33, .w75{
                width: 100% !important
            }
            .skeleton {
                padding: 0px;
                margin-bottom: 7px;
            }
        }
    </style>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Todos los eventos') }}</h5>
                    <div class="row col-12 mb-4">
                        <div class="col-lg-2 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar evento') }}</span>
                            <input type="search" class="form-control @error('search') is-invalid @enderror"
                                placeholder="{{ __('Buscar evento por nombre') }}" wire:model.lazy="search">
                            @error('search')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>
                        <div class="col-lg-2 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar desde') }}</span>
                            <input type="date" class="form-control @error('search_desde') is-invalid @enderror"
                                wire:model.lazy="search_desde">
                            @error('search_desde')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>
                        <div class="col-lg-2 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar hasta') }}</span>
                            <input type="date" class="form-control @error('search_hasta') is-invalid @enderror"
                                wire:model.lazy="search_hasta">
                            @error('search_hasta')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>
                        <div class="col-lg-2 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar estado') }}</span>
                            <select class="form-control @error('search_estado') is-invalid @enderror"
                                wire:model.lazy="search_estado">
                                <option value="" selected>{{ __('Seleccione una opción') }}</option>
                                <option value="1">{{ __('Disponible') }}</option>
                                <option value="2">{{ __('Finalizado') }}</option>
                                <option value="3">{{ __('Cancelado') }}</option>
                            </select>
                            @error('search_estado')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>
                        <div class="col-md-2 col-6 mb-2">
                            <br>
                            <div class="d-flex">
                                <div class="col-auto mx-2 p-1">
                                    <button type="button" class="btn btn-info"
                                        wire:click="limpiar()">{{ __('Limpiar') }}</button>
                                </div>
                                <div class="col-auto mx-2 p-1">
                                    <a href="{{ route('create.evento') }}"
                                        class="btn btn-success">{{ __('Crear evento') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Titulo') }}</th>
                                    <th scope="col">{{ __('Aforo') }}</th>
                                    <th scope="col">{{ __('Organizador') }}</th>
                                    <th scope="col">{{ __('Estado') }}</th>
                                    <th scope="col">{{ __('Acción') }}</th>
                                </tr>
                            </thead>

                            <tbody wire:loading.class="hidden">
                                @forelse ($this->Eventos as $evento)
                                    <tr>
                                        <th scope="row">#{{ $evento->id }}</th>
                                        <td>
                                            @if ($evento->event_destacado == 1)
                                                <i class="fas fa-star text-warning"></i>
                                            @endif
                                            {{ $evento->name }}
                                        </td>
                                        <td>{{ number_format($evento->people, 0, ',', '.') }}</td>
                                        <td>{{ $evento->organizador->first_name . ' ' . $evento->organizador->last_name }}
                                        </td>
                                        <td>
                                            @if ($evento->status == 1)
                                                <span class="badge bg-success">{{ __('Disponible') }}</span>
                                            @elseif($evento->status == 2)
                                                <span class="badge bg-secondary">{{ __('Finalizado') }}</span>
                                            @elseif($evento->status == 3)
                                                <span class="badge bg-secondary">{{ __('Cancelado') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-primary"
                                                href="{{ route('show.eventos', $evento->id) }}">{{ __('Endosar') }}</a>
                                            <button class="btn btn-warning" type="button"
                                                wire:click="reiniciarentradas('{{ $evento->id }}'')"><i
                                                    class="fas fa-redo"></i> &nbsp; {{ __('Descargar') }}</button>
                                        
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center justify-content-center">
                                            ¡{{ __('No hay eventos disponibles') }}!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tbody wire:loading.class.remove="hidden" class="hidden">
                                <tr>
                                    <th>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h10 w75"></div>
                                            </div>
                                        </div>
                                    </th>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h15 w75"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h15 w75 "></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h15 w75 "></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h15 w75 "></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left d-flex">
                                                <div class="line h15 m10 w33" style="margin-right: 15px"></div>
                                                <div class="line h15 m10 w33" style="margin-right: 15px"></div>
                                                <div class="line h15 m10 w33" style="margin-right: 15px"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h10 w75"></div>
                                            </div>
                                        </div>
                                    </th>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h15 w75"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h15 w75 "></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h15 w75 "></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h15 w75 "></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left d-flex">
                                                <div class="line h15 m10 w33" style="margin-right: 15px"></div>
                                                <div class="line h15 m10 w33" style="margin-right: 15px"></div>
                                                <div class="line h15 m10 w33" style="margin-right: 15px"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h10 w75"></div>
                                            </div>
                                        </div>
                                    </th>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h15 w75"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h15 w75 "></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h15 w75 "></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left">
                                                <div class="line h15 w75 "></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="skeleton">
                                            <div class="skeleton-left d-flex">
                                                <div class="line h15 m10 w33" style="margin-right: 15px"></div>
                                                <div class="line h15 m10 w33" style="margin-right: 15px"></div>
                                                <div class="line h15 m10 w33" style="margin-right: 15px"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @if (count($this->Eventos) > 0)
                        <div class="row text-center justify-content-center mt-2" style="max-width: 99%">
                            @desktop
                                {{ $this->Eventos->links() }}
                            @elsedesktop
                                {{ $this->Eventos->onEachSide(1)->links() }}
                            @enddesktop
                        </div>
                    @endif
                </div>
            </div>
           
        </div>
    </div>
    @if ($readytoload)
        @include('backendv2.eventos.modal.recordatos')
    @endif
    <script>
        window.addEventListener('errores', event => {
            Swal.fire(
                '¡Error!',
                event.detail.error,
                'error'
            )
        })
        window.addEventListener('reiniciar', event => {
            Swal.fire({
                title: '¿Esta seguro?',
                text: 'Se reiniciaran todas las ventas de entradas de este evento',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.isConfirmed) {
                    let timerInterval
                    window.livewire.emit('reiniciarentrada')
                    Swal.fire({
                        title: '¡Listo!',
                        html: 'Espere un momento..',
                        icon: 'info',
                        timer: 1000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    }).then((result) => {})
                }
            })
        });
        window.addEventListener('reiniciado', event => {
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'Proceso finalizado',
                showConfirmButton: false,
                timer: 1500
            })
        })
        window.addEventListener('abrridatos', event => {
            $('#recordardatos').modal('show');
        })
        window.addEventListener('cerrardatos', event => {
            $('#recordardatos').modal('hide');
        })
    </script>

    @section('js')
        <script>
            $('.page-link').click(function(event) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 700);
            });
        </script>
    @endsection
</div>
