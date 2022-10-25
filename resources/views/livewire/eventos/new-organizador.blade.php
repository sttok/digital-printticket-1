<div>
    <div class="modal fade" id="addorganizador" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear nuevo organizador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-12 mb-2">
                            <label  >{{__('First Name')}}</label>
                            <input type="text"  placeholder="Nombre organizador" value="{{old('nombre_organizador')}}" wire:model.defer="nombre_organizador" class="form-control @error('nombre_organizador')? is-invalid @enderror">
                            @error('nombre_organizador')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                        <div class="col-md-6 col-12 mb-2">
                            <label >{{__('Last Name')}}</label>
                            <input type="text" placeholder="Apellido organizador" value="{{old('apellido_organizador')}}" wire:model.defer="apellido_organizador" class="form-control @error('apellido_organizador')? is-invalid @enderror">
                            @error('apellido_organizador')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                        <div class="col-md-6 col-12 mb-2 ">
                            <label >{{__('Email')}}</label>
                            <input type="email" name="email" placeholder="Correo" value="{{old('email')}}" wire:model.defer="email" class="form-control @error('email')? is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                        <div class="col-md-6 col-12 mb-2">
                            <label >{{__('Phone')}}</label>
                            <input type="tel" placeholder="Telefono" value="{{old('phone')}}" wire:model.defer="phone" class="form-control @error('phone')? is-invalid @enderror">
                            @error('phone')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                        <div class="col-md-6 col-12 mb-2">
                            <label >{{__('Password')}}</label>
                            <input type="password" name="password" placeholder="Contraseña" wire:model.defer="password" class="form-control @error('password')? is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" wire:click="crearorganizador()" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
   
</div>
