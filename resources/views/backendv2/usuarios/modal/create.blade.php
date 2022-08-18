<div class="modal fade" id="crearusuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Crear nuevo usuario') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-12 mb-2">
                        <span>{{ __('Nombres') }}</span>
                        <input type="text" class="form-control @error('nombre_usuario') is-invalid @enderror" placeholder="{{ __('Nombre del usuario') }}" wire:model.defer="nombre_usuario" wire:target="store" wire:loading.attr="disabled">
                        @error('nombre_usuario')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                    <div class="col-md-6 col-12 mb-2">
                        <span>{{ __('Apellidos') }}</span>
                        <input type="text" class="form-control @error('apellido_usuario') is-invalid @enderror" placeholder="{{ __('Apellido del usuario') }}" wire:model.defer="apellido_usuario" wire:target="store" wire:loading.attr="disabled">
                        @error('apellido_usuario')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-md-12 col-12 mb-2">
                        <span>{{ __('Telefono') }}</span>
                        <div class="row">
                            <div class="col-md-4 col-6 mb-2">
                                <select class="form-control @error('prefijo_telefono') is-invalid @enderror" wire:model.defer="prefijo_telefono" wire:target="store" wire:loading.attr="disabled">
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
                                <input type="tel" class="form-control @error('telefono') is-invalid @enderror @error('phone') is-invalid @enderror" placeholder="3101234567" wire:model.defer="telefono" wire:target="store" wire:loading.attr="disabled">
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
                        <input type="email" class="form-control @error('correo_usuario') is-invalid @enderror" wire:model.defer="correo_usuario" wire:target="store" wire:loading.attr="disabled">
                        @error('correo_usuario')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                    <div class="col-md-6 col-12 mb-2">
                        <span>{{ __('Contraseña') }}</span>
                        <input type="text" class="form-control @error('contraseña_usuario') is-invalid @enderror" wire:model.defer="contraseña_usuario" wire:target="store" wire:loading.attr="disabled">
                        @error('contraseña_usuario')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                    <div class="col-md-6 col-12 mb-2">
                        <span>¿{{ __('Desea notificar al usuario') }}?</span>
                        <div class="custom-check">
                            <input class="form-check-input" type="checkbox" value="true" 
                            id="notificar_nuevo" wire:model.defer="notificar_nuevo" wire:target="store" wire:loading.attr="disabled">
                            <label class="form-check-label" for="notificar_nuevo">
                                {{ __('Si, notificar') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <span>{{ __('Roles') }} </span>
                        <div class="row">
                            @foreach ($this->Roles as $rol)
                               
                                <div class="col-auto mb-2">
                                    <div class="custom-check">
                                        <input class="form-check-input" type="checkbox" value="true" 
                                        id="rol{{ $loop->iteration }}" wire:click="agregarrol('{{ $rol }}')" wire:target="store" wire:loading.attr="disabled" >
                                        <label class="form-check-label" for="rol{{ $loop->iteration }}">
                                            {{ $rol }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12 mb-2">
                        <span>{{ __('Imagen usuario') }} (1080 x 1080px)</span>
                        <input type="file" class="form-control @error('imagen') is-invalid @enderror" accept="image/*" wire:model="imagen" wire:target="store" wire:loading.attr="disabled">
                        @error('imagen')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                        
                        <div wire:loading.inline wire:target="imagen">
                            <div class="col-12 my-1 text-center justify-content-center row">
                                <div class="spinner-grow my-2" role="status" >
                                </div>
                            </div>
                        </div>

                        @if ($this->imagen)
                            <div class="col-12 mb-2 mt-3 text-center justify-content-center row">
                                <span>{{ __('Previa de imagen') }}</span>
                                <img class="img-fluid " src="{{ $imagen->temporaryUrl() }}" style="max-width: 300px; border-radius:1rem">
                            </div>
                        @endif
                    </div>
                    <div wire:loading wire:target="store">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                {{ __('Cargando...') }}
                            </div>
                        </div>                
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" wire:target="store" wire:loading.attr="disabled" data-bs-dismiss="modal" wire:click="limpiar()">{{ __('Cancelar') }}</button>
                <button type="button" class="btn btn-primary" wire:target="store" wire:loading.attr="disabled" wire:click="store()">{{ __('Guardar') }}</button>
            </div>
        </div>
    </div>
</div>