<div>
    <div class="modal fade" id="endosarventa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ __('Endosar') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="limpiar()"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3 {{ $encontrado ? 'was-validated' : '' }}">
                            <span>{{ __('La busqueda es solo clientes del empresario') }}</span>
                            <input type="tel" class="form-control mb-1 @error('search_telefono') is-invalid @enderror" placeholder="{{ __('No Celular o cedula') }}" wire:model.defer="search_telefono" wire:keydown.enter="buscarcliente" wire:target="enviarentradas" wire:loading.attr="disabled">
                            <button class="btn btn-primary btn-block my-2" wire:click="buscarcliente" wire:target="enviarentradas" wire:loading.attr="disabled" ><i class="fas fa-search"></i></button>
                            @error('search_telefono')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror
                        </div>
                        @if($search_telefono != '' && $encontrado == true)
                            <div class="col-12 mb-3">
                                <span>{{ __('Cliente encontrado') }}</span>
                                <h6 class="mt-1">
                                    <span class="badge bg-success" style="font-size: 13px;">{{ $cliente->name . ' ' . $cliente->last_name . ' - ' . $cliente->email }}</span>
                                </h6>
                            </div>
                        @endif
                        <hr>
                        <div class="col-12 mb-2">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                        <button class="accordion-button collapsed btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                            {{ __('Crear nuevo cliente') }}
                                        </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample" style="">
                                        <div class="accordion-body row">
                                            <div class="col-md-6 col-12 mb-2">
                                                <span>{{ __('Nombres') }}</span>
                                                <input type="text" class="form-control @error('nombre_cliente') is-invalid @enderror" placeholder="{{ __('Nombre del cliente') }}" wire:model.defer="nombre_cliente" wire:target="storecliente" wire:loading.attr="disabled">
                                                @error('nombre_cliente')
                                                    <div class="invalid-feedback ">{{ $message }}  </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 col-12 mb-2">
                                                <span>{{ __('Apellidos') }}</span>
                                                <input type="text" class="form-control @error('apellido_cliente') is-invalid @enderror" placeholder="{{ __('Apellido del cliente') }}" wire:model.defer="apellido_cliente" wire:target="storecliente" wire:loading.attr="disabled">
                                                @error('apellido_cliente')
                                                    <div class="invalid-feedback ">{{ $message }}  </div>
                                                @enderror
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-12 mb-2">
                                                <span>{{ __('Telefono') }}</span>
                                                <div class="d-flex">
                                                    <div class="col-md-4 col-6 mb-2">
                                                        <select class="form-control @error('prefijo_telefono') is-invalid @enderror" wire:model.defer="prefijo_telefono" wire:target="storecliente" wire:loading.attr="disabled">
                                                            <option value="+57">+57 COL</option>
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
                                                    <div class="col-md-8 col-6 mb-2">
                                                        <input type="tel" class="form-control @error('telefono') is-invalid @enderror @error('phone') is-invalid @enderror" placeholder="3101234567" wire:model.defer="telefono" wire:target="storecliente" wire:loading.attr="disabled">
                                                        @error('telefono')
                                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                                        @enderror
                                                        @error('phone')
                                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-12 mb-2">
                                                <span>{{ __('Correo') }}</span>
                                                <input type="email" class="form-control @error('correo_cliente') is-invalid @enderror" wire:model.defer="correo_cliente" wire:target="storecliente" wire:loading.attr="disabled">
                                                @error('correo_cliente')
                                                    <div class="invalid-feedback ">{{ $message }}  </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-11 col-11 mb-2">
                                                <span>{{ __('Contraseña') }}</span>
                                                <input type="text" class="form-control @error('contraseña_cliente') is-invalid @enderror" wire:model.defer="contraseña_cliente" wire:target="storecliente" wire:loading.attr="disabled">
                                                @error('contraseña_cliente')
                                                    <div class="invalid-feedback ">{{ $message }}  </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-1 col-1 mb-2" style="padding-left: 0px">
                                                <br>
                                                <button type="button" class="btn btn-primary" wire:click="generarpass()" ><i class="fas fa-dice"></i></button>
                                            </div>
                                            <div class="col-md-6 col-12 mb-2">
                                                <span>{{ __('Cedula') }}</span>
                                                <input type="text" class="form-control @error('cedula_cliente') is-invalid @enderror" wire:model.defer="cedula_cliente" wire:target="storecliente" wire:loading.attr="disabled">
                                                @error('cedula_cliente')
                                                    <div class="invalid-feedback ">{{ $message }}  </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 col-12 mb-2">
                                                <span>¿{{ __('Notificar cliente') }}?</span>
                                                <select class="form-control @error('notificar_nuevo') is-invalid @enderror" wire:model.defer="notificar_nuevo" wire:target="storecliente" wire:loading.attr="disabled">
                                                    <option value="false">No</option>
                                                    <option value="true">Si</option>
                                                </select> 
                                                @error('notificar_nuevo')
                                                    <div class="invalid-feedback ">{{ $message }}  </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:target="store" wire:loading.attr="disabled" data-bs-dismiss="modal" wire:click="limpiar()">{{ __('Cerrar') }}</button>
                    <button type="button" class="btn btn-success" wire:target="store" wire:loading.attr="disabled" data-bs-dismiss="modal" wire:click="endosar()" {{ $encontrado == false ? 'disable' : '' }} >{{ __('Endosar') }}</button>
                    <button type="button" class="btn btn-info" wire:target="store" wire:loading.attr="disabled" data-bs-dismiss="modal" wire:click="storeclienteendosar()"  >{{ __('Crear y endosar') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('abrirendosar', event => {
            $('#endosarventa').modal('show');
        })
        window.addEventListener('cerrarendosar', event => {
            $('#endosarventa').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'Ha sido endosado con exito',
                showConfirmButton: false,
                timer: 1500
            })
        })
    </script>
</div>
