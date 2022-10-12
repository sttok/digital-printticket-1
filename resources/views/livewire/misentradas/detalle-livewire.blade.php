<div class="row" wire:init="loadDatos">
    @if ($organizar == 1)
        <style>
            .form-check-input[type="checkbox"]{
                margin-left: -60px !important;
                width: 30px !important;
                height: 30px !important;   
                margin-top: -35px;
                border-radius: 8px !important;
            }
            
            .float-right{
                float: right !important;
            }
            .card-dropdown{
                padding-right: 10px;
                padding-top: 10px;
                position: unset !important;
            }
            
        </style>
    @else
        <style>
            .form-check-input[type="checkbox"]{
                width: 30px !important;
                height: 30px !important;
                  border-radius: 8px !important;
            }
            
        </style>
    @endif  
    <style>
        .nav-link-1{
            color: #fefefe !important;
            border-top-right-radius: 0px !important;
            border-bottom-right-radius: 0px !important;
        }
        .nav-link.desactive:hover{
            color: #fefefe !important;
            background: #052c88 !important;
        }
        .nav-link.desactive{
            background: #2b3b52 ;
        }

        .nav-link-2{
            color: #fefefe !important;
            border-top-left-radius: 0px !important;
            border-bottom-left-radius: 0px !important;
        }
        .card-header{
            background: transparent
        }
        .zoom {
          transition: transform .5s; /* Animation */
        }
        .zoom:hover {
          transform: scale(1.03); 
        }
    </style> 
    <div class="col-sm-12 col-md-3">
        @livewire('sidebar.sidebar-list-livewire')
    </div>
    <div class="col-sm-12 col-md-9">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Archivos') }}</h5>
                    <div class="row">
                        <div class="row col-lg-6 col-md-12 col-12 mb-3">
                            <div class="col-md-6 col-12 mb-2">
                                <span>{{ __('Buscar por identificador') }}</span>
                                <input type="search" class="form-control @error('search') is-invalid @enderror" placeholder="{{ __('Buscar por identificador') }}" wire:model="search">
                                @error('search')
                                    <div class="invalid-feedback ">{{ $message }}  </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 col-12 mb-2">
                                <span>{{ __('Buscar zona') }}</span>
                                <select class="form-control @error('search_estado') is-invalid @enderror"  wire:model="search_estado">
                                    <option value="">{{ __('Todos') }}</option>
                                    @foreach ($entradas as $it)
                                        <option value="{{ $it->id }}">{{ $it->name }}</option>
                                    @endforeach
                                </select>
                                @error('search_estado')
                                    <div class="invalid-feedback ">{{ $message }}  </div>
                                @enderror
                            </div>
    
                            <div class="col-md-6 col-12 mb-2">
                                <span>{{ __('Organizar') }}</span>
                                <select class="form-control @error('organizar') is-invalid @enderror"  wire:model="organizar">
                                    <option value="1">{{ __('Icono grande') }}</option>
                                    <option value="2">{{ __('Listar') }}</option>
                                    <option value="3">{{ __('Icono mediano') }}</option>
                                </select>
                                @error('organizar')
                                    <div class="invalid-feedback ">{{ $message }}  </div>
                                @enderror
                            </div>
    
                            <div class="col-md-6 col-12 mb-2">
                                <br>
                                <button type="button" class="btn btn-info btn-block" wire:click="limpiar()" >{{ __('Limpiar') }}</button>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-12 mb-3">
                            <div class="card stat-widget bg-info" >
                                <div class="card-header text-center justify-content-center" style="padding-bottom: 5px;">
                                    <h3>{{ __('Estadisticas de tu evento') }}</h3>
                                </div>
                                <div class="card-body text-center justify-content-center" style="padding-top: 5px">
                                    <div class="row p-2" style="border-top: 1px solid #fefefe6b; border-bottom: 1px solid #fefefe6b">
                                        <div class="col-md-6 col-12 mb-2">
                                            <h3 style="color: rgba(225,235,245,.87);"> {{ __('Vendido ') }} <br>
                                               {{ $porcentaje_venta.'%' }}
                                            </h3>
                                        </div>
                                        <div class="col-md-6 col-12 mb-2">
                                            <h3 style="color: rgba(225,235,245,.87);"> {{ __('Dias restantes') }} <br>
                                                {{ $dias_restantes }}
                                            </h3>
                                        </div>
                                        <h3 style="color: rgba(225,235,245,.87);"> {{ __('Estado') . ': '}} <b>{{ $estado_evento }}</b> </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <nav class="nav nav-pills nav-justified">
                        <a class="nav-item nav-link nav-link-1 {{ $filtrar_por == 0 ? 'active' : 'desactive' }}" href="#" wire:click="cambiarfiltrar()">{{ __('Sin vender') . ' ( ' .  number_format($total_sin_endosar, 0 ,',','.') . ' )' }}</a>
                        <a class="nav-item nav-link nav-link-2 {{ $filtrar_por == 1 ? 'active' : 'desactive' }}" href="#" wire:click="cambiarfiltrar()">{{ __('Vendido') . ' ( ' .  number_format($total_endosadas, 0 ,',','.') . ' )' }}</a>
                    </nav>
                </div>
                
                <div class="card-body row">
                    <div wire:loading.inline wire:target="organizar,cambiarfiltrar">
                        <div class="col-12 my-2 text-center justify-content-center row">
                            <div class="spinner-grow my-2" role="status" >
                            </div>
                        </div>
                    </div>
                    @if (count($this->Entradas) > 0)
                        <div class="row col-12">
                            <div class="col-md-6 col-12 mb-2 float-left">
                                <div class="mb-4 form-check ml-1" >
                                    <input type="checkbox" class="form-check-input" style="margin-left: 0px !important; margin-top: 0px; margin-right: 10px;" wire:click.lazy="seleccionartodos()" {{ $seleccionar_todos == true ? 'checked' : '' }}>
                                    <label class="form-check-label" wire:click.lazy="seleccionartodos()">{{ __('Seleccionar todos') }}</label>
                                </div>        
                            </div>
                            <div class="col-md-6 col-12 mb-2 ">
                                <button class="btn btn-{{ count($entradas_array) > 0 ? 'primary' : 'secondary' }} float-right" wire:click="descargarmasivo()" >
                                    <div wire:loading.inline wire:target="descargarmasivo">
                                        <div class="spinner-grow my-2" role="status" >
                                        </div>
                                    </div>
                                    <div wire:loading.remove wire:target="descargarmasivo">
                                        <i class="fas fa-download"></i> {{ __('Descargar reporte global') }}
                                    </div>
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    @if ($organizar == 1)
                        @forelse ($this->Entradas as $entrada)
                            @php
                                $acep = in_array($entrada->id, $this->entradas_array) !== false ? true : false;
                                
                            @endphp
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="card card-file-manager zoom {{ $acep == true ? 'bg-primary' : '' }}"  >
                                    <div class="dropdown card-dropdown">
                                        <button class="btn btn-primary dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            <div wire:loading.inline wire:target="descargarentrada({{ $entrada->id }})">
                                                <div class="spinner-grow spinner-grow-sm" role="status" >
                                                </div>
                                            </div>
                                            <div wire:loading.remove wire:target="descargarentrada({{ $entrada->id }})" style="display: inline;">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </div>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                                            <li>
                                                <button class="dropdown-item" type="button" wire:click="veruploads('{{ $entrada->id }}')">
                                                    <i class="fas fa-tag"></i> {{ __('Venta') }}
                                                </button>
                                                <button class="dropdown-item" type="button" >
                                                    <i class="fas fa-shipping-fast"></i> {{ __('Venta rapida') }}
                                                </button>
                                                <button class="dropdown-item" type="button">
                                                    <i class="fas fa-user-check" ></i> {{ __('Endosar') }}
                                                </button>
                                                @if ($entrada->permiso_descargar == 1)
                                                    <button class="dropdown-item" type="button" wire:click="descargar('{{ $entrada->id }}')">
                                                        <i class="fas fa-cloud-download-alt"></i> {{ __('Descargar') }}
                                                    </button>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-file-header {{ $acep == true ? 'text-white' : '' }}" style="background: transparent;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $entrada->id }}" id="flexCheckChecked" wire:model="entradas_array">
                                        </div>
                                        <i class="fas fa-file-pdf" ></i>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2 {{ $acep == true ? 'text-white' : 'text-muted' }} ">{{ $entrada->zona->name. ' - ' . $entrada->identificador }} 
                                            @if ($entrada->endosado > 0)
                                                <span class="badge bg-success" style="margin-left: 5px"><i class="fas fa-user-tag"></i></span>
                                            @endif
                                        </h6>
                                        <p class="card-text {{ $acep == true ? 'text-white' : '' }}">{{ $entrada->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 mb-2 text-center justify-content-center">
                                <h4>¡{{ __('No hay entradas disponibles') }}!</h4>
                            </div>
                        @endforelse
                    @elseif($organizar == 2)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{ __('Identificador') }}</th>
                                        <th scope="col">{{ __('Zona') }}</th>
                                        <th scope="col">{{ __('Archivos') }}</th>
                                        <th scope="col">{{ __('Acción') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($this->Entradas as $entrada)
                                        <tr>
                                            <th scope="row">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="{{ $entrada->id }}" id="flexCheckChecked" wire:model="entradas_array">
                                                </div>
                                            </th>
                                            <td> 
                                               #{{ $entrada->identificador }}  
                                                @if ($entrada->endosado > 0)
                                                    <span class="badge bg-success" style="margin-left: 5px"><i class="fas fa-user-tag"></i></span>
                                                @endif
                                            </td>
                                            <td>
                                               {{ $entrada->zona->name}}
                                            </td>
                                            <td>
                                               {{ $entrada->created_at->diffForHumans() }}
                                            </td>
                                            <td>
                                                <button class="btn btn-primary"  type="button" wire:click="veruploads('{{ $entrada->id }}')">
                                                    <i class="fas fa-tag"></i> {{ __('Vender') }}
                                                </button>
                                                @if ($entrada->permiso_descargar == 1)
                                                    <button class="btn btn-success" type="button" wire:click="descargar('{{ $entrada->id }}')">
                                                        <i class="fas fa-cloud-download-alt"></i> {{ __('Descargar') }}
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center justify-content-center" >
                                                ¡{{ __('No hay entradas disponibles') }}!
                                            </td>
                                        </tr> 
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                    @if (count($this->Entradas) > 0)
                        <div class="row text-center justify-content-center mt-2" style="max-width: 99%">
                            @desktop
                                {{ $this->Entradas->links() }}
                            @elsedesktop
                                {{ $this->Entradas->onEachSide(1)->links() }}
                            @enddesktop
                        </div>
                    @endif
                </div>
                @if (count($entradas_array) > 0)
                    <a href="#" class="btn-flotante" wire:click="abrirventas()"><i class="fas fa-check"></i> {{ count($entradas_array) . ' Seleccionados' }} </a>
                @else
                    <a href="#" class="btn-flotante btn-secondary" wire:click="abrirventas()"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </div>
    </div>
    @if ($readytoload)
        @include('backendv2.miseventos.modal.venta')
        @include('backendv2.cliente.modal.create')
        @include('backendv2.miseventos.modal.buscaruser')
        @include('backendv2.miseventos.modal.ver')
        @if ($enviado == true)
            @include('backendv2.miseventos.modal.verventa')
        @endif
       
    @endif
    @livewire('misentradas.compartir-venta-digital-livewire')
    <script>
        window.addEventListener('errores', event => {
            Swal.fire(
                '¡Error!',
                event.detail.error,
                'error'
            )
        })
        window.addEventListener('verenlace', event => {
            $('#buscarclient').modal('hide');
            $('#crearcliente').modal('hide');
            $('#ventas').modal('hide');
            $('#verventa').modal('hide');
            $('#verenlace').modal('show');
        })
        window.addEventListener('cerrarshow1', event => {
            $('#buscarclient').modal('hide');
            $('#crearcliente').modal('hide');
            $('#ventas').modal('hide');
            $('#verventa').modal('hide');
        })
        window.addEventListener('abrirmodalventa', event => {
            $('#buscarclient').modal('hide');
            $('#crearcliente').modal('hide');
            $('#verventa').modal('hide');
            $('#ventas').modal('show');
        })
        window.addEventListener('abrirbuscarendosar', event => {
            $('#ventas').modal('hide');
            $('#buscarclient').modal('show');
        })
        window.addEventListener('createcliente2', event => {
             $('#ventas').modal('hide');
             $('#crearcliente').modal('show');
        })
        window.addEventListener('regresarcliente1', event => {
            $('#buscarclient').modal('hide');
            $('#crearcliente').modal('hide');
            $('#ventas').modal('show');
        })
        window.addEventListener('storecliente', event => {
            $('#crearcliente').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El cliente ha sido creado con exito',
                showConfirmButton: false,
                timer: 1500
            })
            $('#ventas').modal('show');
        })
        window.addEventListener('clientenoencontrado', event => {
            Swal.fire({
                icon: 'error',
                title: '¡No encontrado!',
                text: 'El cliente no se ha encontrado',
                showConfirmButton: false,
                timer: 1500
            })
        })
        window.addEventListener('quitarendosado', event => {
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'La entrada ya no esta endosada',
                showConfirmButton: false,
                timer: 1500
            })
        })
        window.addEventListener('verenviadas', event => {
            $('#ventas').modal('hide');
            let timerInterval
            Swal.fire({
                icon :'success',
                title: '¡Exito! ',
                text: '¡La entrada se han enviado correctamente!',
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
    </script>
</div>

