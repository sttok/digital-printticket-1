<div class="modal fade" id="crearcliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >{{ __('Crear nuevo cliente') }}</h5>
                <button type="button" class="btn-close" wire:click="regresarcliente()" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
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
                    <div class="col-lg-6 col-md-12 col-12 mb-2">
                        <span>{{ __('Telefono') }}</span>
                        <div class="row">
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
                    <div class="col-lg-6 col-md-12 col-12 mb-2">
                        <span>{{ __('Correo') }}</span>
                        <input type="email" class="form-control @error('correo_cliente') is-invalid @enderror" wire:model.defer="correo_cliente" wire:target="storecliente" wire:loading.attr="disabled">
                        @error('correo_cliente')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                    <div class="col-md-5 col-12 mb-2">
                        <span>{{ __('Contraseña') }}</span>
                        <input type="text" class="form-control @error('contraseña_cliente') is-invalid @enderror" wire:model.defer="contraseña_cliente" wire:target="storecliente" wire:loading.attr="disabled">
                        @error('contraseña_cliente')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                    <div class="col-md-1 col-12 mb-2" style="padding-left: 0px">
                        <br>
                        <button type="button" class="btn btn-primary" wire:click="generarpass()" ><i class="fas fa-dice"></i></button>
                    </div>
                    <div class="col-md-3 col-12 mb-2">
                        <span>{{ __('Cedula') }}</span>
                        <input type="text" class="form-control @error('cedula_cliente') is-invalid @enderror" wire:model.defer="cedula_cliente" wire:target="storecliente" wire:loading.attr="disabled">
                        @error('cedula_cliente')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                    <div class="col-md-3 col-12 mb-2">
                        <span>¿{{ __('Notificar cliente') }}?</span>
                        <select class="form-control @error('notificar_nuevo') is-invalid @enderror" wire:model.defer="notificar_nuevo" wire:target="storecliente" wire:loading.attr="disabled">
                            <option value="false">No</option>
                            <option value="true">Si</option>
                        </select> 
                        @error('notificar_nuevo')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                   
                    <div wire:loading wire:target="storecliente">
                        <div class="progress my-2">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                {{ __('Cargando...') }}
                            </div>
                        </div>                
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" wire:target="storecliente" wire:loading.attr="disabled" wire:click="regresarcliente()">{{ __('Cancelar') }}</button>
                <button type="button" class="btn btn-primary" wire:target="storecliente" wire:loading.attr="disabled" wire:click="storecliente()">{{ __('Guardar') }}</button>
            </div>
        </div>
    </div>
</div>