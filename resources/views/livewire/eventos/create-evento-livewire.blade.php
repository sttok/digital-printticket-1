<div wire:init="loadDatos">
    <style>
        .wrapper{
        display: inline-flex;
        background: #fff;
        height: 100px;
        width: 450px;
        align-items: center;
        justify-content: space-evenly;
        border-radius: 5px;
        padding: 20px 15px;
       
        }
        .wrapper .option{
        background: #fff;
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        margin: 0 10px;
        border-radius: 5px;
        cursor: pointer;
        padding: 0 10px;
        border: 2px solid lightgrey;
        transition: all 0.3s ease;
        }
        .wrapper .option .dot{
        height: 20px;
        width: 20px;
        background: #d9d9d9;
        border-radius: 50%;
        position: relative;
        }
        .wrapper .option .dot::before{
        position: absolute;
        content: "";
        top: 4px;
        left: 4px;
        width: 12px;
        height: 12px;
        background: var(--color_terciario);
        border-radius: 50%;
        opacity: 0;
        transform: scale(1.5);
        transition: all 0.3s ease;
        }
        input[type="radio"]{
        display: none;
        }
        #option-1:checked:checked ~ .option-1,
        #option-2:checked:checked ~ .option-2{
        border-color: var(--color_terciario);
        background: var(--color_terciario);
        }
        #option-1:checked:checked ~ .option-1 .dot,
        #option-2:checked:checked ~ .option-2 .dot{
        background: #fff;
        }
        #option-1:checked:checked ~ .option-1 .dot::before,
        #option-2:checked:checked ~ .option-2 .dot::before{
        opacity: 1;
        transform: scale(1);
        }
        .wrapper .option span{
        font-size: 14px;
        color: #808080;
        }
        #option-1:checked:checked ~ .option-1 span,
        #option-2:checked:checked ~ .option-2 span{
        color: #fff;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <h4 >{{ __('Imagenes del evento') }}</h4>
        </div>
        <div class="card-body row justify-content-center mb-3">
            <div class="col-md-4 col-12 mb-3">                    
                <label>{{ __('Imagen Evento') }} (1080 x 1080px)</label>
                <input type="file" class="form-control @error('imagen_evento') is-invalid @enderror" accept="image/*" wire:model="imagen_evento" wire:target="storeEvento" wire:loading.attr="disabled">
                @error('imagen_evento')
                    <div class="invalid-feedback block">{{$message}}</div>
                @endif
                
                <div wire:loading.inline wire:target="imagen_evento">
                    <div class="col-12 my-1 text-center justify-content-center row">
                        <div class="spinner-grow my-2" role="status" >
                        </div>
                    </div>
                </div>
                @if ($this->imagen_evento)
                    <div class="col-12 mb-3 mt-3 text-center justify-content-center row">
                        <span class="w-100 text-white">{{ __('Previa de imagen') }}</span>
                        <img class="img-fluid " src="{{ $imagen_evento->temporaryUrl() }}" style="max-width: 200px; border-radius:1rem">
                    </div>
                @endif
            </div>
            <div class="col-md-4 col-12 mb-3">
                <label>{{ __('Imagen Banner') }} 1920 x 400px ({{ __('opcional') }})</label>
                <input type="file" class="form-control @error('imagen_banner') is-invalid @enderror" accept="image/*" wire:model="imagen_banner" wire:target="storeEvento" wire:loading.attr="disabled">
                @error('imagen_banner')
                    <div class="invalid-feedback block">{{$message}}</div>
                @endif
                <div wire:loading.inline wire:target="imagen_banner">
                    <div class="col-12 my-1 text-center justify-content-center row">
                        <div class="spinner-grow my-2" role="status" >
                        </div>
                    </div>
                </div>
                @if ($this->imagen_banner)
                    <div class="col-12 mb-3 mt-3 text-center justify-content-center row">
                        <span class="w-100 text-white">{{ __('Previa de imagen') }}</span>
                        <img class="img-fluid " src="{{ $imagen_banner->temporaryUrl() }}" style="max-width: 200px; border-radius:1rem">
                    </div>
                @endif
            </div>
            <div class="col-md-4 col-12 mb-3">
                <label>{{ __('Imagen Mapa') }} 1080 x 1080px ({{ __('opcional') }})</label>
                <input type="file" class="form-control @error('imagen_mapa') is-invalid @enderror" accept="image/*" wire:model="imagen_mapa" wire:target="storeEvento" wire:loading.attr="disabled">
                @error('imagen_mapa')
                    <div class="invalid-feedback block">{{$message}}</div>
                @endif                   
                <div wire:loading.inline wire:target="imagen_mapa">
                    <div class="col-12 my-1 text-center justify-content-center row">
                        <div class="spinner-grow my-2" role="status" >
                        </div>
                    </div>
                </div>
                @if ($this->imagen_mapa)
                    <div class="col-12 mb-3 mt-3 text-center justify-content-center row">
                        <span class="w-100 text-white">{{ __('Previa de imagen') }}</span>
                        <img class="img-fluid " src="{{ $imagen_mapa->temporaryUrl() }}" style="max-width: 200px; border-radius:1rem">
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>{{ __('Datos adicionales') }}</h4>
        </div>
        <div class="card-body row mb-3">
            <div class="col-md-4 col-12 mb-3">
                <label>{{ __('Titulo del evento') }}</label>
                <input type="text" wire:model.defer="titulo" placeholder="{{ __('Titulo del evento') }}" class="form-control @error('titulo')? is-invalid @enderror" wire:target="storeEvento" wire:loading.attr="disabled" >
                @error('titulo')
                    <div class="invalid-feedback">{{$message}}</div>
                @endif
            </div>

            <div class="col-md-4 col-12 mb-3">
                <label>{{__('Category')}}</label>
                <select class="form-control @error('categoria_id')? is-invalid @enderror" wire:target="storeEvento" wire:loading.attr="disabled" wire:model.defer="categoria_id">
                    <option value="" selected="selcted" hidden>{{ __('Select Category') }}</option>   
                    @foreach ($this->Categorias as $item)
                        <option value="{{$item->id}}" >{{$item->name}}</option>
                    @endforeach
                </select>
                @error('categoria_id')
                    <div class="invalid-feedback block">{{$message}}</div>
                @endif
            </div>

            <div class="col-md-4 col-12 mb-3">
                <label>{{__('Maximum people will join in this event')}}</label>
                <input type="number" min="1" max="100000" placeholder="Máximo aforo de personas del evento" class="form-control @error('aforo')? is-invalid @enderror"  wire:target="storeEvento" wire:loading.attr="disabled" wire:model.defer="aforo" >
                @error('aforo')
                    <div class="invalid-feedback">{{$message}}</div>
                @endif
            </div>

            <div class="col-md-3 col-12 mb-3">
                <label>{{ __('Hora inicio del evento') }}</label>
                <input type="datetime-local" class="form-control  @error('hora_inicio_evento')? is-invalid @enderror" wire:target="storeEvento" wire:loading.attr="disabled" wire:model.defer="hora_inicio_evento">
                @error('hora_inicio_evento')
                    <div class="invalid-feedback">{{$message}}</div>
                @endif
            </div>

            <div class="col-md-3 col-12 mb-3">
                <label>{{ __('Hora final del evento') }}</label>
                <input type="datetime-local"  class="form-control date @error('hora_final_evento')? is-invalid @enderror" wire:target="storeEvento" wire:loading.attr="disabled" wire:model.defer="hora_final_evento">
                @error('hora_final_evento')
                    <div class="invalid-feedback">{{$message}}</div>
                @endif
            </div>

            <div class="col-md-3 col-12 mb-3">
                <label>{{__('Tags')}}</label>
                <input type="text" class="form-control  @error('etiquetas')? is-invalid @enderror" wire:target="storeEvento" wire:loading.attr="disabled" wire:model.defer="etiquetas">
                @error('etiquetas')
                    <div class="invalid-feedback">{{$message}}</div>
                @endif
            </div>
            <div class="col-md-3 col-12 mb-3">                
                <div class="form-check form-switch p-4">
                    <input class="form-check-input @error('privacidad_evento')? is-invalid @enderror" type="checkbox" id="flexSwitchCheckDefault" value="true" wire:model.lazy="privacidad_evento" 
                    wire:target="storeEvento" wire:loading.attr="disabled" style="width: 50px; height: 20px;">
                    <label class="form-check-label mx-2" for="flexSwitchCheckDefault">¿{{ __('Mostrar evento') }}? </label>
                </div>
                @error('privacidad_evento')
                    <div class="invalid-feedback">{{$message}}</div>
                @endif
                
            </div>
            <div class=" col-12 mb-3">
                <label>{{ __('Descripcion') }} </label>
                <textarea class="form-control @error('descripcion')? is-invalid @enderror" style="height: 80px !important;" wire:target="storeEvento" wire:loading.attr="disabled" wire:model.defer="descripcion"></textarea>
                @error('descripcion')
                    <div class="invalid-feedback block">{{$message}}</div>
                @endif
            </div>
            
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>{{ __('Datos organizador') }}</h4>
        </div>
        <div class="card-body row">
            <div class="col-lg-6 col-sm-12 mb-3">
                <label>{{ __('Organizadores') }}</label>
                <div class="row">
                    <div class="col-lg-10 col-sm-12"> 
                        <select class="form-control  @error('organizador_id')? is-invalid @enderror" wire:target="storeEvento" wire:loading.attr="disabled" wire:model="organizador_id" >
                            <option  value="" selected="true" hidden>Selecciona un organizador</option>
                            @foreach ($this->Organizadores as $organizador)
                                <option value="{{$organizador->id}}">{{$organizador->first_name.' '.$organizador->last_name}}</option>
                            @endforeach
                        </select>
                        @error('organizador_id')
                            <div class="invalid-feedback">{{$message}}</div>
                        @endif
                    </div>
                    <div class="col-lg-2 col-sm-12">
                        @can('user_create')
                            <button class="btn btn-success btn-block" type="button" wire:click="abrirorganizador">
                                <i class="fas fa-user-plus"></i>  
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
            @if ($organizador_id != '')
                <div class="row">
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label>{{ __('Escaners') }}</label>
                        <div class="row">
                            <div class="col-lg-12">
                                <div wire:loading.inline wire:target="organizador_id">
                                    <div class="col-12 my-2 text-center justify-content-center row">
                                        <div class="spinner-grow my-2" role="status" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row" wire:loading.remove wire:target="organizador_id">
                                    <div class="col-auto mb-4" >
                                        <button class="btn btn-info btn-block" type="button" wire:click="abrirscanner" style="height: 51px">
                                            <i class="fas fa-user-plus"></i>  Crear escaner
                                        </button>
                                    </div>
                                    @forelse ($this->Escaneres as $escaner)
                                        <div class="col-auto mb-4" >
                                            <div class="custom-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $escaner->id }}" id="escanner-{{ $escaner->id }}-{{ $loop->iteration }}" wire:model="scanners.{{ $loop->iteration }}">
                                                <label class="form-check-label " for="escanner-{{ $escaner->id }}-{{ $loop->iteration }}">
                                                    {{ $escaner->first_name . ' ' . $escaner->last_name }}
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center justify-content-center">
                                            <h6 >¡{{ __('No hay escaneres disponibles') }}!</h6>
                                        </div>
                                    @endforelse
                                </div>
                                @error('scanners')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label>{{ __('Puntos de ventas') }}</label>
                        <div class="row">
                            <div class="col-12 mb-3" >
                                <div wire:loading.inline wire:target="organizador_id">
                                    <div class="col-12 my-2 text-center justify-content-center row">
                                        <div class="spinner-grow my-2" role="status" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row" wire:loading.remove wire:target="organizador_id">
                                    <div class="col-auto mb-4" >
                                        <button class="btn btn-info btn-block" type="button" wire:click="abrirpuntoventa" style="height: 51px">
                                            <i class="fas fa-user-plus"></i>  Crear punto venta
                                        </button>
                                    </div>
                                    @forelse ($this->Puntoventas as $venta)
                                        <div class="col-auto mb-4" >
                                            <div class="custom-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $venta->id }}" id="venta-{{ $venta->id }}-{{ $loop->iteration }}" wire:model="puntos_ventas.{{ $loop->iteration }}">
                                                <label class="form-check-label" for="venta-{{ $venta->id }}-{{ $loop->iteration }}">
                                                    {{ $venta->first_name . ' ' . $venta->last_name }}
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center justify-content-center">
                                            <h6 >¡{{ __('No hay puntos de ventas disponibles') }}!</h6>
                                        </div>
                                    @endforelse
                                </div>
                                @error('puntos_ventas')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h4 >{{__('Location Detail')}}</h4>
        </div>
        <div class="card-body row">
            <div class="col-12 mb-3">
                <div class="wrapper">
                    <input type="radio" name="select" id="option-1" value="0" wire:model.lazy="tipo_evento">
                    <input type="radio" name="select" id="option-2" value="1" wire:model.lazy="tipo_evento">
                    <label for="option-1" class="option option-1">
                        <div class="dot"></div>
                        <span>{{__('Venue')}}</span>
                    </label>
                    <label for="option-2" class="option option-2">
                        <div class="dot"></div>
                        <span>{{__('Online Event')}}</span>
                    </label>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="location-detail" style="display: {{ $tipo_evento == 1 ? 'none' : 'block'}}" >
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label  for="pais">{{ __('Country') }}</label>
                            <select class="form-control @error('pais_id')? is-invalid @enderror" wire:model="pais_id" wire:target="storeEvento" wire:loading.attr="disabled">
                                <option value="" selected="selected" hiden >{{ __('Selecciona un pais') }}</option>
                                @foreach ($this->Paises as $pais)
                                    <option value="{{ $pais->Codigo }}">{{ $pais->Pais }}</option> 
                                @endforeach
                            </select>
                            @error('pais_id')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label  for="ciudad">{{ __('City') }}</label>
                            <select  class="form-control  @error('ciudad_id')? is-invalid @enderror" wire:model="ciudad_id" wire:target="storeEvento" wire:loading.attr="disabled" {{ count($this->Ciudades) == 0 ? 'Disabled' : '' }}>
                                <option value="" hidden selected="selected">Selecciona un pais primero</option>
                                    @foreach ($this->Ciudades as $item)
                                        <option value="{{ $item->id }}">{{ $item->Ciudad }}</option> 
                                    @endforeach
                            </select>
                            @error('ciudad_id')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-lg-6 mb-3">
                            <label  >{{__('Lugares')}}</label>
                            <div class="d-flex">
                                <div class="col-md-10" style="padding-left: 0px;">
                                    <select class="form-control" wire:model="localidad_id">
                                        <option value="">{{ __('Seleccione una opcion') }}</option>
                                        @forelse ($this->Localidades as $i)
                                            <option value="{{ $i->id }}">{{ $i->nombre }}</option>
                                        @empty
                                            <option value="">{{ __('No hay items disponibles') }}</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-success btn-block" wire:click="createlocalidad" ><i class="fas fa-folder-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label >{{__('Event Address')}}</label>
                            <input type="text" placeholder="Dirección del evento" class="form-control @error('localidad_direccion')? is-invalid @enderror" wire:model.defer="localidad_direccion" wire:target="storeEvento" wire:loading.attr="disabled">
                            @error('localidad_direccion')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label >Iframe Google Maps (Opcional) </label>
                            <textarea  class="form-control  @error('localidad_iframe')? is-invalid @enderror" wire:model.defer="localidad_iframe" >{{ $localidad_iframe }}</textarea>
                            @error('localidad_iframe')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="online-detail mt-3 mb-5" style="display: {{ $tipo_evento == 1 ? 'block' : 'none'}}">
                    <div class="row">
                        <div class="col-md-4 col-12 mb-3">
                            <label for="plataforma">{{ __('Platform') }}</label>
                            <select  class="form-control @error('plataforma_evento')? is-invalid @enderror" wire:model.defer="plataforma_evento" wire:target="storeEvento" wire:loading.attr="disabled">
                                <option value="Facebook">Facebook</option>
                                <option value="Twitch" >Twitch</option>
                                <option value="Youtube">Youtube</option>
                                <option value="Otro">Otro</option>
                            </select>
                            @error('plataforma_evento')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <label  for="url_evento">Url (opcional)</label>
                            <input type="text" class="form-control @error('url_evento')? is-invalid @enderror" wire:model.defer="url_evento" wire:target="storeEvento" wire:loading.attr="disabled">
                            @error('url_evento')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>  
    
    <div class="card">
        <div class="card-header">
            <h4>{{ __('Entradas') }}</h4>
        </div>
        <div class="card-body row">
            <div class="col-md-3 col-6 mb-2">
                <label for="">{{ __('Nombre entrada') }}</label>
                <input type="text" class="form-control @error('nombre_entrada') is-invalid @enderror" wire:model.defer="nombre_entrada" wire:target="storeEvento" wire:loading.attr="disabled" wire:keydown.enter="temporarlentrada">
                @error('nombre_entrada')
                    <div class="invalid-feedback block">{{$message}}</div>
                @endif
            </div>
            <div class="col-md-2 col-6 mb-2">
                <label for="">{{ __('Tipo') }}</label>
                <select class="form-control @error('tipo_entrada') is-invalid @enderror" wire:model="tipo_entrada" wire:target="storeEvento" wire:loading.attr="disabled">
                    <option value="1">General</option>
                    <option value="2">Palcos</option>
                </select>
                @error('tipo_entrada')
                    <div class="invalid-feedback block">{{$message}}</div>
                @endif
            </div>
            @if ($tipo_entrada == 1)
                <div class="col-md-2 col-6 mb-2">
                    <label for="">{{ __('Cantidad entrada') }}</label>
                    <input type="number" class="form-control @error('cantidad_entrada') is-invalid @enderror" wire:model.defer="cantidad_entrada" wire:target="storeEvento" wire:loading.attr="disabled">
                    @error('cantidad_entrada')
                        <div class="invalid-feedback block">{{$message}}</div>
                    @endif
                </div>
            @else
                <div class="col-md-2 col-6 mb-2">
                    <label for="">{{ __('Palcos') }}</label>
                    <input type="number" class="form-control @error('palcos') is-invalid @enderror" wire:model.defer="palcos" wire:target="storeEvento" min="1" max="99999" wire:loading.attr="disabled">
                    @error('palcos')
                        <div class="invalid-feedback block">{{$message}}</div>
                    @endif
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <label for="">{{ __('Puestos') }}</label>
                    <input type="number" class="form-control @error('puestos') is-invalid @enderror" wire:model.defer="puestos" wire:target="storeEvento" min="1" max="99999" wire:loading.attr="disabled">
                    @error('puestos')
                        <div class="invalid-feedback block">{{$message}}</div>
                    @endif
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <label for="">{{ __('Adicional') }}</label>
                    <input type="number" class="form-control @error('adicional') is-invalid @enderror" wire:model.defer="adicional" wire:target="storeEvento" wire:loading.attr="disabled">
                    @error('adicional')
                        <div class="invalid-feedback block">{{$message}}</div>
                    @endif
                </div>
            @endif
            
            <div class="col-md-1 col-6 mb-2">
                <br>
                <button class="btn btn-success" wire:click="temporarlentrada()" ><i class="fas fa-save"></i></button>
            </div>

            <div class="col-12 mb-2">
                <div class="table-responsive" >
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('Nombre') }}</th>
                                <th scope="col">{{ __('Cantidad') }}</th>
                                <th scope="col">{{ __('Tipo') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($entradas_array as $entrada)
                                <tr>
                                    <th scope="row"><button class="btn btn-outline-danger" type="button" wire:click="borrarentrada('{{ $entrada['id'] }}')" ><i class="fas fa-times"></i></button></th>
                                    <td>{{ $entrada['nombre_entrada'] }}</td>
                                    <td>
                                        @if ($entrada['tipo'] == 1)
                                            {{ number_format( $entrada['cantidad_entrada'], 0 ,',','.') }}
                                        @elseif($entrada['tipo'] == 2)
                                            {{ __('Palc: ') . number_format( $entrada['palcos'], 0 ,',','.') . ' / Puest: ' . number_format( $entrada['puestos'], 0 ,',','.') . ' / Adic: ' . number_format( $entrada['adicional'], 0 ,',','.') }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            @if ($entrada['tipo'] == 1)
                                                {{ __('General') }}
                                            @elseif($entrada['tipo'] == 2)
                                                {{ __('Palcos') }}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center justify-content-center" >
                                        ¡{{ __('No hay entradas disponibles') }}!
                                    </td>
                                </tr> 
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="col-12 mb-3">
                    <div wire:loading wire:target="storeEvento">
                        <div class="progress my-3 w-100">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                {{ __('Cargando...') }}
                            </div>
                        </div>                   
                    </div>
                </div>

                <button type="button" wire:click="storeEvento" class="btn btn-primary btn-block my-2">{{ __('Crear Evento') }}</button>
            </div>
        </div>
    </div>
    @if ($readyToLoad)
        @include('backendv2.eventos.modal.createlocalidad')
        @livewire('new-punto-venta',  ['users' => $this->Organizadores])
        @livewire('new-scanner', ['users' => $this->Organizadores])
        @livewire('new-organizador')
    @endif

    <script>
        window.addEventListener('errores', event => {
            Swal.fire(
                '¡Error!',
                event.detail.error,
                'error'
            )
        })  
        window.addEventListener('abrrirorganizador', event => {
            $('#addorganizador').modal('show');
        })  
        window.addEventListener('abrrirscanner', event => {
            $('#addscanner').modal('show');
        })  
        window.addEventListener('abrrirpuntoventa', event => {
            $('#addpuntoventa').modal('show');
        })  
        window.addEventListener('createlocalidadd', event => {
            $('#createlocalidad').modal('show');
        }) 
        window.addEventListener('storelocalidadd', event => {
            $('#createlocalidad').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El item ha sido creado con exito',
                showConfirmButton: false,
                timer: 1500
            })
        })
        window.addEventListener('successevento', event => {
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El evento ha sido creado con exito',
                showConfirmButton: false,
                timer: 1500
            })
        })
    </script>
    <script>
        window.addEventListener('success', event => {
            $('#addorganizador').modal('hide');
            $('#addpuntoventa').modal('hide');
            $('#addscanner').modal('hide');
            Swal.fire({            
                icon: 'success',
                title: '¡Exito!',
                text: event.detail.mensaje,
                showConfirmButton: false,
                timer: 1500
            })
        });
    </script>
   
</div>
