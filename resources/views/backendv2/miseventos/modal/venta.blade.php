<div class="modal fade" id="ventas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >{{ __('Venta de entradas') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-12 mb-3 {{ $encontrado ? 'was-validated' : '' }} row" >
                            <span>{{ __('Busqueda cliente') }}</span>
                            <input type="tel" class="form-control mb-1 @error('search_telefono') is-invalid @enderror" placeholder="{{ __('No Celular o cedula') }}" wire:model.defer="search_telefono" wire:keydown.enter="buscarcliente" wire:target="enviarentradas" wire:loading.attr="disabled">
                            <button class="btn btn-primary btn-block my-2" wire:click="buscarcliente" wire:target="enviarentradas" wire:loading.attr="disabled" ><i class="fas fa-search"></i></button>
                            @error('search_telefono')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror
                        
                        
                    </div>
                    <div class="col-md-6 col-12 mb-3">
                        @if ($encontrado)
                            <div class="form-group row mb-3 mt-1">
                                <div class="col-sm-3 col-form-label " style="padding-left: 0px"></div>
                                <div class="col-sm-9">
                                    <span class="badge bg-success" >{{ __('Cliente encontrado') }}</span>
                                </div>
                            </div>
                           
                            <div class="form-group row mb-3 mt-1">
                                <label for="abonado" class="col-sm-3 col-form-label" style="padding-left: 0px; text-align:right">{{__('Nombre')}}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $cliente->name . ' ' . $cliente->last_name }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-3 mt-1">
                                <label for="abonado" class="col-sm-3 col-form-label" style="padding-left: 0px; text-align:right">{{__('Telefono')}}</label>
                                <div class="col-sm-9">
                                    <input type="tel" class="form-control" value="{{ $cliente->phone }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="abonado" class="col-sm-3 col-form-label" style="padding-left: 0px; text-align:right">{{__('Cedula')}}</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" value="{{ $cliente->cedula }}" readonly>
                                </div>
                            </div>
                            
                        @else
                            <div class="form-group row mb-3 mt-1">
                                <div class="col-sm-3 col-form-label " style="padding-left: 0px"></div>
                                <div class="col-sm-9">
                                    <span class="badge bg-secondary text-right" >{{ __('Cliente no encontrado') }}</span>
                                </div>
                            </div>
                            
                            <div class="form-group row mb-3 mt-1">
                                <label for="nombre_cliente" class="col-sm-3 col-form-label" style="padding-left: 0px; text-align:right">{{__('Nombre')}}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('nombre_cliente') is-invalid @enderror" wire:model.defer="nombre_cliente" >
                                    @error('nombre_cliente')
                                        <div class="invalid-feedback ">{{ $message }}  </div>
                                    @enderror
                                </div>
                                
                            </div>
                            <div class="form-group row mb-3 mt-1">
                                <label for="abonado" class="col-sm-3 col-form-label " style="padding-left: 0px; text-align:right">{{__('Telefono')}}</label>
                                <div class="col-sm-3">
                                    <select class="form-control form-select @error('prefijo_telefono') is-invalid @enderror" wire:model.defer="prefijo_telefono">
                                        <option value="+57" selected>+57 COL</option>
                                        <option value="+1">+1 EEUU</option>
                                        <option value="+56">+56 CL</option>
                                        <option value="+593">+593 ECU</option>
                                        <option value="+52">+52 MEX</option>
                                        <option value="+34">+34 ESP</option>
                                        <option value="+507">+507 PAN</option>
                                    </select>
                                    @error('prefijo_telefono')
                                        <div class="invalid-feedback ">{{ $message }}  </div>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <input type="tel" class="form-control @error('telefono') is-invalid @enderror @error('phone') is-invalid @enderror" wire:model.defer="telefono">
                                    @error('telefono')
                                        <div class="invalid-feedback ">{{ $message }}  </div>
                                    @enderror
                                    @error('phone')
                                        <div class="invalid-feedback ">{{ $message }}  </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="abonado" class="col-sm-3 col-form-label" style="padding-left: 0px; text-align:right">{{__('Cedula')}}</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control @error('cedula_cliente') is-invalid @enderror" wire:model.defer="cedula_cliente">
                                    @error('cedula_cliente')
                                        <div class="invalid-feedback ">{{ $message }}  </div>
                                    @enderror
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-6 col-12 mb-3 row">
                        <div class="col-md-6 col-6 mb-3 mt-1">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="metodosdepagoss" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('Metodo de pago') }}</button>
                                <div class="dropdown-menu" aria-labelledby="metodosdepagoss" style="box-shadow: 0 0 1.25rem rgba(31, 45, 61, 0.29);">
                                    <button type="button" class="dropdown-item" wire:click="$set('metodo_de_pago', '1')" style="border-bottom: solid 1px #00000014;"> <img class="img-fluid p-2" src="{{ asset('images/logo-nequi.png') }}" style="max-width:100px"> </button>
                                    <button type="button" class="dropdown-item"  wire:click="$set('metodo_de_pago', '2')" style="border-bottom: solid 1px #00000014;"> <img class="img-fluid p-2" src="{{ asset('images/logo-bancolombia.png') }}"style="max-width:100px"> </button>
                                    <button type="button" class="dropdown-item" wire:click="$set('metodo_de_pago', '3')"><span class="badge bg-success w-100" style="font-size:24px" ><i class="far fa-money-bill-alt" style="max-width:150px"></i> Efectivo</span></button>
                                </div>
                            </div>
                            @if($metodo_de_pago == 1)
                                <img class="img-fluid my-2 p-2" src="{{ asset('images/logo-nequi.png') }}" style="max-width:150px" >
                            @elseif($metodo_de_pago == 2)
                                <img class="img-fluid my-2 p-2" src="{{ asset('images/logo-bancolombia.png') }}"style="max-width:150px" >
                            @elseif($metodo_de_pago == 3)
                                <div class="my-2 p-2"><span class="badge bg-success w-100" style="font-size:24px" ><i class="far fa-money-bill-alt" style="max-width:150px"></i> Efectivo</span> </div>
                            @endif
                        </div>
                        <div class="col-md-6 col-6 mb-3 mt-1">
                            <div class="form-group row mb-3">
                                <label for="abonado" class="col-sm-3 col-form-label" style="padding-left: 0px">{{__('Abonado')}}</label>
                                <div class="col-sm-9">
                                  <input type="number" class="form-control" wire:model.lazy="abonado">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="total" class="col-sm-3 col-form-label">{{ __('Total') }}</label>
                                <div class="col-sm-9">
                                  <input type="number" class="form-control" wire:model.lazy="total">
                                </div>
                            </div>
                        </div>
                    </div>                    
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    @if ($agrupar_palcos == false)
                                        <th scope="col">#</th>
                                    @endif
                                    <th scope="col">{{ __('Identificador') }}</th>
                                    <th scope="col">{{ __('Entrada') }}</th>
                                    <th scope="col">{{ __('Endosado') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($entradas_seleccionadas as $ent)
                                    <tr>
                                        @if ($agrupar_palcos == false)
                                            <td><button class="btn btn-outline-danger" wire:click="quitar('{{ $ent['id'] }}')"><i class="fas fa-times"></i></button></td>
                                        @endif
                                        <th scope="row">#{{ $ent['identificador'] }}</th>
                                        <td>{{ $ent->zona->name }}</td>
                                        <td>
                                            @if (array_key_exists($ent['id'],$entradas_seleccionadas_endosado))
                                                <span class="badge bg-success">{{ $entradas_seleccionadas_endosado[$ent['id']]['cliente_name'] }}</span>
                                            @else
                                                {{ __('No') }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $agrupar_palcos == false ? 4 : 3 }}" class="text-center justify-content-center" >
                                            ¡{{ __('No hay entradas seleccionadas') }}!
                                        </td>
                                    </tr> 
                                @endforelse
                               
                            </tbody>
                        </table>
                    </div>
                   
                    <div wire:loading wire:target="enviarentradas">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                {{ __('Cargando...') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-auto" style="margin-right: 15px">
                    <div class="mb-3 form-check" wire:key="form-c-1">
                        <input type="radio" class="form-check-input" value="1" id="exampleCheck1"  wire:model="estado_venta" wire:key="form-ci-1">
                        <label class="form-check-label" for="exampleCheck1">{{ __('Separado') }}</label>
                    </div>
                </div>
                <div class="col-auto" style="margin-right: 15px">
                    <div class="mb-3 form-check" wire:key="form-c-2">
                        <input type="radio" class="form-check-input" value="2" id="exampleCheck2"  wire:model="estado_venta" wire:key="form-ci-2">
                        <label class="form-check-label" for="exampleCheck2">{{ __('Abonado') }}</label>
                    </div>
                </div>
                <div class="col-auto" style="margin-right: 15px">
                    <div class="mb-3 form-check" wire:key="form-c-2">
                        <input type="radio" class="form-check-input" value="3" id="exampleCheck3" wire:model="estado_venta" wire:key="form-ci-3">
                        <label class="form-check-label" for="exampleCheck3">{{ __('Pago total') }}</label>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary"  wire:target="enviarentradas" wire:loading.attr="disabled" data-bs-dismiss="modal">{{ __('Cerrar') }}</button>
                <button type="button" class="btn btn-primary"  wire:target="enviarentradas" wire:loading.attr="disabled" wire:click="enviarentradas()">{{ __('Vender') }}</button>
            </div>
        </div>
    </div>
</div>