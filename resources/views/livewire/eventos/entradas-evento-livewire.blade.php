<div wire:init="loadDatos" >
    <style>
        .custom-check{
            padding: 15px;
            background: #2d3d54;
            border-radius: 1rem;
        }
    </style>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"> <a href="{{ route('index.eventos') }}" class="btn btn-primary btn-sm" ><i class="fas fa-arrow-left"></i></a> {{$this->Evento->name . '  -  ' . __('Entradas')  }}  </h5>
                    <div class="row col-12 mb-4">
                        <div class="col-lg-3 col-md-6 col-12 mb-2">
                            <span>{{ __('Buscar entrada') }}</span>
                            <input type="search" class="form-control @error('search') is-invalid @enderror" placeholder="{{ __('Buscar entrada') }}" wire:model="search">
                            @error('search')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror
                        </div>
                        <div class="col-lg-2 col-md-6 col-12 mb-2">
                            <span>{{ __('Buscar privacidad') }}</span>
                           <select class="form-control @error('search_estado') is-invalid @enderror" name="" id=""  wire:model="search_estado">
                                <option value="" selected>{{ __('Seleccione una opción') }}</option>
                               <option value="1">{{ __('Publico') }}</option>
                               <option value="2">{{ __('Privado') }}</option>
                           </select>
                           @error('search_estado')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror
                        </div>
                        <div class="col-lg-2 col-md-6 col-12 mb-2">
                            <span>{{ __('Buscar tipo') }}</span>
                           <select class="form-control @error('search_tipo') is-invalid @enderror" name="" id=""  wire:model="search_tipo">
                                <option value="" selected>{{ __('Seleccione una opción') }}</option>
                               <option value="1">{{ __('Ticket') }}</option>
                               <option value="2">{{ __('Manilla') }}</option>
                               <option value="3">{{ __('Escarapelas') }}</option>
                           </select>
                           @error('search_tipo')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror
                        </div>
                        
                        <div class="col-lg-2 col-md-3 col-12 mb-2">
                            <br>
                            <button class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#descargarentradas"> <i class="fas fa-download"></i> {{ __('Descargar') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" style="min-height: 250px">
                        <table class="table table-hover">
                            <thead>
                                <th scope="col">{{ __('No. Entrada') }}</th>
                                <th scope="col">{{ __('Color') }}</th>
                                <th scope="col">{{ __('Nombre') }}</th>
                                <th scope="col">{{ __('Cantidad') }}</th>
                                <th scope="col">{{ __('Precio') }}</th>
                                <th scope="col">{{ __('Tipo') }}</th>
                                <th scope="col">{{ __('Estado') }}</th>
                                <th scope="col">{{ __('Acción') }}</th>
                            </thead>
                            <tbody>
                                @forelse ($this->Entradas as $entrada)
                                    <tr>
                                        <th scope="row">#{{ $entrada->ticket_number }}</th>
                                        <td>
                                            <div style="background-color: {{ $entrada->color_localidad }}; height: 20px; border-radius: 10px;"></div>
                                        </td>
                                        <td>{{ $entrada->name }}</td>
                                        <td>{{ number_format( $entrada->quantity, 0 ,',','.') }}</td>
                                        <td>{{ number_format( $entrada->price, 0 ,',','.') }}</td>
                                        <td>
                                            @if ($entrada->tipo == 1)
                                                {{ __('Ticket') }}
                                            @elseif($entrada->tipo == 2)
                                                {{ __('Manilla') }}
                                            @elseif($entrada->tipo == 3)
                                                {{ __('Escarapela') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($entrada->status == 1)
                                                <span class="badge bg-success">{{ __('Disponible') }}</span>
                                            @elseif($entrada->status == 2)
                                                <span class="badge bg-secondary">{{ __('No disponbile') }}</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <div class="dropdown dropstart">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <div wire:loading.inline wire:target="descargarentrada, crearcarpetas">
                                                        <div class="spinner-grow spinner-grow-sm" role="status" style="margin-top: -10px" >
                                                        </div>
                                                    </div>
                                                    <div wire:loading.remove wire:target="descargarentrada, crearcarpetas" style="display: inline;">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </div>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                                                    <li>
                                                        <button class="dropdown-item" type="button" wire:click="crearcarpetas({{ $entrada->id }})">
                                                            <i class="fas fa-folder-plus"></i> {{ __('Crear carpetas drive') }}
                                                        </button>
                                                        <button class="dropdown-item" type="button" wire:click="subirpdfs({{ $entrada->id }})">
                                                            <i class="fas fa-file-upload"></i> {{ __('Subir entrada') }}
                                                        </button>
                                                        <button class="dropdown-item" type="button" wire:click="descargarentrada({{ $entrada->id }})">
                                                            <i class="fas fa-download"></i> {{ __('Descargar') }}
                                                        </button>
                                                        <button class="dropdown-item" type="button" wire:click="veruploads({{ $entrada->id }})">
                                                            <i class="fas fa-search"></i> {{ __('Ver archivos subidos') }}
                                                        </button>
                                                        
                                                    </li>
                                                    
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center justify-content-center" >
                                            ¡{{ __('No hay entradas disponibles') }}!
                                        </td>
                                    </tr> 
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if (count($this->Entradas) > 0)
                        <div class="row text-center justify-content-center mt-2" style="max-width: 99%">
                            {{ $this->Entradas->links() }}
                        </div>
                    @endif
                </div>
            </div>
           
        </div>
    </div>

    @if ($readytoload)
        @include('backendv2.eventos.modal.descargar')
        @include('backendv2.eventos.modal.subir')
        @include('backendv2.eventos.modal.show')
        @include('backendv2.eventos.modal.carpeta')
    @endif

    <script>        
        window.addEventListener('errores', event => {
            Swal.fire(
                '¡Error!',
                event.detail.error,
                'error'
            )
        })
        window.addEventListener('abrircarpetas', event => {
            $('#carpetas').modal('show');
        });
        window.addEventListener('opendigitals', event => {
            $('#showdigitals').modal('show');
        });
        window.addEventListener('openmodalupload', event => {
            $('#subirpdfs').modal('show');
        });
        window.addEventListener('cerrardescargarexcel', event => {
            $('#descargarentradas').modal('hide');
        });
        window.addEventListener('cargandoentrada', event => {
            let timerInterval
            Swal.fire({
                icon :'success',
                title: '¡Procesando! ',
                text: 'Las entradas se estan cargando, espere un momento...',
                timer: 1500,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            })
        });

        window.addEventListener('storeuploaderror', event => {
            Swal.fire({
                icon :'info',
                title: '¡Error! ',
                text: 'Subida con exito, pero ha ocurrido algunos errores, revisa y intentalo nuevamente',                
            })
        });
       
        window.addEventListener('storeupload', event => {
             $('#subirpdfs').modal('hide');
            let timerInterval
            Swal.fire({
                icon :'success',
                title: '¡Exito! ',
                text: 'Todas las entradas han subidas correctamentes',
                timer: 1500,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            })
        });

        window.addEventListener('archivossubidos', event => {
             $('#carpetas').modal('hide');
            let timerInterval
            Swal.fire({
                icon :'success',
                title: '¡Exito! ',
                text: 'Las entradas se han subido a la cola, en breves estara disponibles',
                timer: 1500,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            })
        });

        window.addEventListener('noarchivos', event => {
            let timerInterval
            Swal.fire({
                icon :'error',
                title: '¡Error! ',
                text: 'No se han encontrado archivos',
                timer: 1500,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            })
        });

        window.addEventListener('emptydigitals', event => {
            let timerInterval
            Swal.fire({
                icon :'info',
                title: '¡Vacio! ',
                text: 'Aun no se han subidos archivos para esta zona',
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            })
        });
        
    </script>
</div>


