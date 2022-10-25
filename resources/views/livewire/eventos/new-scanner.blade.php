<div>
    <div class="modal fade" id="addscanner" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir nuevo Scanner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label  >Organizador</label>
                    <select  class="form-control @error('organizador')? is-invalid @enderror " wire:model.defer="organizador" >
                        @if (Auth::user()->hasRole('admin'))
                            <option  value="" selected="selected" hidden>{{__('Choose Organization')}}</option>
                            @foreach ($users as $item)
                                <option value="{{$item->id}}" {{$item->id==old('user_id')?'Selected':''}}>{{$item->first_name.' '.$item->last_name}}</option>
                            @endforeach
                        @elseif(Auth::user()->hasRole('organization'))
                            <option value="{{ Auth::user()->id }}">{{ Str::title( Auth::user()->first_name.' '.Auth::user()->last_name) }}</option>
                        @endif
                    </select>
                    @error('organizador')
                        <div class="invalid-feedback">{{$message}}</div>
                    @endif
                    <div class="row mt-3">
                        <div class="col-md-6 col-12 mb-2">
                            <label >{{__('First Name')}}</label>
                            <input type="text"  placeholder="Nombre Scanner" value="{{old('nombre_scanner')}}" wire:model.defer="nombre_scanner" class="form-control @error('nombre_scanner')? is-invalid @enderror">
                            @error('nombre_scanner')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                        <div class="col-md-6 col-12 mb-2">
                            <label >{{__('Last Name')}}</label>
                            <input type="text" placeholder="Apellido Scanner" value="{{old('apellido_scanner')}}" wire:model.defer="apellido_scanner" class="form-control @error('apellido_scanner')? is-invalid @enderror">
                            @error('apellido_scanner')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 row my-3">
                        <div class="col-md-5 col-12 mb-2">
                            <button class="btn btn-primary" type="button" wire:click="genrardatosScanner()" >¿Desa generar datos aleatorios?</button>
                        </div>
                        <div class="col-md-6 col-12 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck1" value="true" wire:model.defer="notificacion">
                                <label class="custom-control-label" for="customCheck1">¿Notificar al organizador?</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
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
                            <input type="text" name="password" placeholder="Contraseña" wire:model.defer="password" class="form-control @error('password')? is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{$message}}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" wire:click="crearscanner()" class="btn btn-primary">Guardar Cambios</button>                
                </div>
            </div>
        </div>
    </div>  
</div>
