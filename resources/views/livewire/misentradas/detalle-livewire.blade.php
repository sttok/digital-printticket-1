<div class="row" wire:init="loadDatos">    
    <style>
        .form-check .form-check-input{
            margin-left: 0px !important;
        }
        .card-custom{
            height: 150px;
        }
        .card-header{
            border-bottom: none;                
        }
        .dropdown-menu.show{
            z-index: 999;
        }
        table .form-check-input{
            border: 1px solid rgba(0,0,0,.25) !important;
        }
        table .form-check{
            padding-left: 0px !important;
            padding-top: 0px !important;
        }
        .float-right{
            float: right !important;
        }
        .form-check-input[type="checkbox"]{
            width: 30px !important;
            height: 30px !important;
            border-radius: 8px !important;
        }
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
        .card-dropdown{
            padding-right: 10px;
            padding-top: 10px;
            position: unset !important;
        }
        .form-check{
            padding-left: 25px;
            padding-top: 10px;
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
                                    @foreach ($this->Zonas as $it)
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
                                    <option value="3">{{ __('Icono mediano') }}</option>
                                    <option value="2">{{ __('Listar') }}</option>
                                </select>
                                @error('organizar')
                                    <div class="invalid-feedback ">{{ $message }}  </div>
                                @enderror
                            </div>
    
                            <div class="col-md-6 col-12 mb-2">
                                <br>
                                <button type="button" class="btn btn-info btn-block" wire:click="limpiar()" >{{ __('Limpiar') }}</button>
                            </div>
                            <div class="col-12 mb-2 p-4">
                                <div class="text-center justify-content-center row" style="padding-top: 5px">
                                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                                        <div class="card card-custom text-white bg-primary">
                                            <div class="card-header text-center justify-content-center" style="padding-bottom: 5px;">
                                                <h6>{{ __('Vendido ') }}</h6>
                                            </div>
                                            <div class="card-body text-center justify-content-center">
                                                <h2>{{ $porcentaje_venta.'%' }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                                        <div class="card card-custom bg-info text-white">
                                            <div class="card-header text-center justify-content-center" style="padding-bottom: 5px;">
                                                <h6>{{ __('Dias restantes ') }}</h6>
                                            </div>
                                            <div class="card-body text-center justify-content-center">
                                                <h2>{{ $dias_restantes }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                                        <div class="card card-custom bg-secondary text-white" style="min-height: 153px">
                                            <div class="card-header text-center justify-content-center" style="padding-bottom: 5px;">
                                                <h6>{{ __('Estado') }}</h6>
                                            </div>
                                            <div class="card-body text-center justify-content-center" style="padding: 23px;">
                                                <h3>{{ $estado_evento }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-12 mb-3">
                            <h3>{{ __('Estadisticas de tu evento') }}</h3>
                            <div id="apex2" wire:ignore></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <nav class="nav nav-pills nav-justified">
                        <a class="nav-item nav-link nav-link-1 {{ $filtrar_por == 0 ? 'active' : 'desactive' }}" href="#entrads" wire:click="$set('filtrar_por', 0)">{{ __('Sin vender') . ' ( ' .  number_format($total_sin_endosar, 0 ,',','.') . ' )' }}</a>
                        <a class="nav-item nav-link nav-link-2 {{ $filtrar_por == 1 ? 'active' : 'desactive' }}" href="#entrads" wire:click="$set('filtrar_por', 1)">{{ __('Vendido') . ' ( ' .  number_format($total_endosadas, 0 ,',','.') . ' )' }}</a>
                    </nav>
                </div>
                
                <div class="card-body row" id="entrads">
                    <div wire:loading.delay.long wire:target="(['filtrar_por', 'organizar', 'search_estado', 'search'])">
                        <div class="col-12 my-2 text-center justify-content-center row">
                            <div class="spinner-grow my-2" role="status" >
                            </div>
                        </div>
                    </div>
                    
                    @if ($agrupar_palcos == false)
                        @if (count($this->Entradas) > 0)
                            <div class="row col-12">
                                <div class="col-md-6 col-12 mb-2 float-left">
                                    <div class="mb-4 form-check ml-1" >
                                        <input type="checkbox" class="form-check-input" style="margin-left: 0px !important; margin-top: 0px; margin-right: 10px;" wire:click.lazy="seleccionartodos()" {{ $seleccionar_todos == true ? 'checked' : '' }}>
                                        <label class="form-check-label" wire:click.lazy="seleccionartodos()">{{ __('Seleccionar todos') }}</label>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($organizar == 1 || $organizar == 3)
                            @forelse ($this->Entradas as $entrada)
                                
                                <div class="col-lg-{{ $organizar == 1 ? 3 : 2 }} col-md-{{ $organizar == 1 ? 6 : 3 }} col-{{ $organizar == 1 ? 12 : 6 }}" >
                                    <div class="card card-file-manager zoom" >
                                        <div class="d-flex">
                                            <div class="col-md-6 col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="{{ $entrada->id }}"  wire:model.lazy="entradas_array.{{$entrada->id}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6 mb-2">
                                                <div class="dropdown card-dropdown dropstart" style="float: right;">
                                                    <button class="btn btn-primary dropstart dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
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
                                                            @if ($entrada->endosado == 0)
                                                                <button class="dropdown-item" type="button" wire:click="veruploads('{{ $entrada->id }}')">
                                                                    <i class="fas fa-tag"></i> {{ __('Venta') }}
                                                                </button>
                                                                <button class="dropdown-item" type="button" wire:click="abrirventarapida1('{{ $entrada->id }}')" >
                                                                    <i class="fas fa-shipping-fast"></i> {{ __('Venta rapida') }}
                                                                </button>
                                                            @else
                                                                <button class="dropdown-item" type="button" wire:click="detalle('{{ $entrada->id }}')" >
                                                                    <i class="fas fa-receipt"></i> {{ __('Detalle') }}
                                                                </button>
                                                                <button class="dropdown-item" type="button" wire:click="enviarcompartir('{{ $entrada->id }}')" >
                                                                    <i class="fas fa-share-alt"></i> {{ __('Compartir') }}
                                                                </button>
                                                            @endif
                                                            <button class="dropdown-item" type="button" wire:click="endosar('{{ $entrada->id }}')">
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
                                            </div>
                                        </div>
                                        
                                        <div class="card-file-header " style="background: transparent;">
                                            <i class="fas fa-file-pdf" ></i>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 ">{{ $entrada->zona->name. ' - ' . $entrada->identificador }} 
                                                @if ($entrada->endosado > 0)
                                                    <span class="badge bg-success" style="margin-left: 5px"><i class="fas fa-user-tag"></i></span>
                                                @endif
                                            </h6>
                                            <p class="card-text">{{ $entrada->created_at->diffForHumans() }}</p>
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
                                            <th scope="col">{{ __('Fecha') }}</th>
                                            <th scope="col">{{ __('Acción') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($this->Entradas as $entrada)
                                            <tr>
                                                <th scope="row">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="{{ $entrada->id }}" id="flexCheckChecked" wire:model.lazy="entradas_array.{{$entrada->id}}">
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
                                                    @if ($entrada->endosado == 0)
                                                        <button class="btn btn-primary mb-2"  type="button" wire:click="veruploads('{{ $entrada->id }}')">
                                                            <i class="fas fa-tag"></i> {{ __('Vender') }}
                                                        </button>
                                                        <button class="btn btn-primary mb-2" type="button" wire:click="abrirventarapida1('{{ $entrada->id }}')" >
                                                            <i class="fas fa-shipping-fast"></i> {{ __('Venta rapida') }}
                                                        </button>
                                                    @else
                                                        <button class="btn btn-primary mb-2" type="button" wire:click="detalle('{{ $entrada->id }}')" >
                                                            <i class="fas fa-receipt"></i> {{ __('Detalle') }}
                                                        </button>
                                                        <button class="btn btn-primary mb-2" type="button" wire:click="enviarcompartir('{{ $entrada->id }}')" >
                                                            <i class="fas fa-share-alt"></i> {{ __('Compartir') }}
                                                        </button>
                                                    @endif
                                                    
                                                    <button class="btn btn-primary mb-2" type="button" wire:click="endosar('{{ $entrada->id }}')">
                                                        <i class="fas fa-user-check" ></i> {{ __('Endosar') }}
                                                    </button>
                                                    @if ($entrada->permiso_descargar == 1)
                                                        <button class="btn btn-success mb-2" type="button" wire:click="descargar('{{ $entrada->id }}')">
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
                    @else
                        @livewire('misentradas.palcos-digital-livewire', ['evento_id' => $evento_id])
                    @endif
                    
                   
                </div>
                @if (count($entradas_array) > 0)
                    <a href="#" class="btn-flotante" wire:click="abrirventas()"><i class="fas fa-check"></i> {{ count(array_filter($entradas_array, function($k) { return $k != null;} )) . ' Seleccionados' }} </a>
                @else
                    <a href="#" class="btn-flotante btn-secondary" wire:click="abrirventas()"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </div>
    </div>
    @if ($readytoload)       
        @include('backendv2.cliente.modal.create')
        @include('backendv2.miseventos.modal.buscaruser')
        @include('backendv2.miseventos.modal.ver')
        @if ($enviado == true)
            @include('backendv2.miseventos.modal.verventa')
        @else
            @include('backendv2.miseventos.modal.venta')
        @endif
        @include('backendv2.miseventos.modal.ventarapida')
        @livewire('misentradas.compartir-venta-digital-livewire')
        @livewire('misentradas.endosar-venta-digital-livewire')
    @endif   
    
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
            $('#verenlace').modal('hide');
            $('#compartir').modal('hide');
            $('#ventarapidamodal').modal('hide');
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
        window.addEventListener('verventaa', event => {
            $('#verventa').modal('show');
        })
        window.addEventListener('abrirventa33', event => {
            $('#ventarapidamodal').modal('show');
        })
    </script>
    <script>
        var options2 = {
                chart: {
                    height: 300,
                    type: 'area',
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                series: [{
                    name: 'Apartadas',
                    data: [{{$estadisticas['0']['Apartadas']}}, {{$estadisticas['1']['Apartadas']}}, {{$estadisticas['3']['Apartadas']}}, {{$estadisticas['4']['Apartadas']}}, {{$estadisticas['5']['Apartadas']}}, 
                        {{$estadisticas['6']['Apartadas']}}, {{$estadisticas['7']['Apartadas']}}, {{$estadisticas['8']['Apartadas']}}, {{$estadisticas['9']['Apartadas']}}, {{$estadisticas['10']['Apartadas']}}, {{$estadisticas['11']['Apartadas']}}]
                }, {
                    name: 'Abonadas',
                    data: [{{$estadisticas['0']['Abonadas']}}, {{$estadisticas['1']['Abonadas']}}, {{$estadisticas['3']['Abonadas']}}, {{$estadisticas['4']['Abonadas']}}, {{$estadisticas['5']['Abonadas']}}, 
                        {{$estadisticas['6']['Abonadas']}}, {{$estadisticas['7']['Abonadas']}}, {{$estadisticas['8']['Abonadas']}}, {{$estadisticas['9']['Abonadas']}}, {{$estadisticas['10']['Abonadas']}}, {{$estadisticas['11']['Abonadas']}}]
                }, {
                    name: 'Pagadas',
                    data: [{{$estadisticas['0']['Total']}}, {{$estadisticas['1']['Total']}}, {{$estadisticas['3']['Total']}}, {{$estadisticas['4']['Total']}}, {{$estadisticas['5']['Total']}}, 
                        {{$estadisticas['6']['Total']}}, {{$estadisticas['7']['Total']}}, {{$estadisticas['8']['Total']}}, {{$estadisticas['9']['Total']}}, {{$estadisticas['10']['Total']}}, {{$estadisticas['11']['Total']}}]
                }],        
                xaxis: {
                    categories: ["{{$estadisticas['0']['Nombre']}}", "{{$estadisticas['1']['Nombre']}}", "{{$estadisticas['3']['Nombre']}}", "{{$estadisticas['4']['Nombre']}}", "{{$estadisticas['5']['Nombre']}}", 
                        "{{$estadisticas['6']['Nombre']}}", "{{$estadisticas['7']['Nombre']}}", "{{$estadisticas['8']['Nombre']}}", "{{$estadisticas['9']['Nombre']}}", "{{$estadisticas['10']['Nombre']}}", "{{$estadisticas['11']['Nombre']}}"],
                    labels: {
                        style: {
                            colors: 'rgba(94, 96, 110, .5)'
                        }
                    }
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                },
                grid: {
                    borderColor: 'rgba(94, 96, 110, .5)',
                    strokeDashArray: 4
                }
            };
        $("#apex2").children().remove();
        var chart2 = new ApexCharts(
            document.querySelector("#apex2"),
            options2
        );    
        chart2.render();
    </script>
    
</div>

