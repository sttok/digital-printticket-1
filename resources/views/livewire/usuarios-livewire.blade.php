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
                    <h5 class="card-title">{{ __('Todos los usuarios') }} 
                        <button data-bs-toggle="modal" data-bs-target="#crearusuario" class="btn btn-primary ml-2" type="button" wire:click="limpiar()"><i class="fas fa-plus"></i></button> </h5>
                    <div class="row col-12 mb-4">
                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar cliente') }}</span>
                            <input type="search" class="form-control @error('search') is-invalid @enderror" placeholder="{{ __('Buscar cliente por nombre') }}" wire:model="search">
                            @error('search')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror
                        </div>
                        
                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar estado') }}</span>
                           <select class="form-control @error('search_estado') is-invalid @enderror" name="" id="" style="color: #fefefe" wire:model="search_estado">
                                <option value="" selected>{{ __('Seleccione una opción') }}</option>
                               <option value="1">{{ __('Activo') }}</option>
                               <option value="2">{{ __('Desactivado') }}</option>
                           </select>
                           @error('search_estado')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <span>{{ __('Buscar rol') }}</span>
                           <select class="form-control @error('search_rol') is-invalid @enderror" name="" id="" style="color: #fefefe" wire:model="search_rol">
                                <option value="" selected>{{ __('Seleccione una opción') }}</option>
                                @foreach ($this->Roles as $rol)
                                    <option value="{{ $rol }}">{{ $rol }}</option>
                                @endforeach                              
                              
                           </select>
                           @error('search_rol')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror
                        </div>
                       
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Nombre') }}</th>
                                    <th scope="col">{{ __('Correo') }}</th>
                                    <th scope="col">{{ __('Telefono') }}</th>
                                    <th scope="col">{{ __('Roles') }}</th>
                                    <th scope="col">{{ __('Estado') }}</th>
                                    <th scope="col">{{ __('Acción') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($this->Usuarios as $usuario)
                                    <tr>
                                        <th scope="row">#{{ $usuario->id }}</th>
                                        <td> 
                                            <img  class="b-lazy"
                                            src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
                                            data-src="{{ asset(route('inicio.frontend') .'/storage/perfil/'.$usuario->image) }}" alt="imagen-evento" style="max-width: 50px; border-radius:50%">
                                           
                                            {{ $usuario->first_name . ' ' . $usuario->last_name}}
                                        </td>
                                        <td>{{ $usuario->email }}</td>
                                        <td>{{ $usuario->phone}}</td>
                                        <td>
                                            @php
                                                $organizador = false;
                                            @endphp
                                            @foreach ($usuario->getRoleNames() as $rol)
                                                <span class="badge bg-info">{{ $rol }}</span>
                                                
                                                @if ($rol == 'organization')
                                                    @php
                                                        $organizador = true;
                                                    @endphp
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @if ($usuario->status == 1)
                                                <span class="badge bg-success">{{ __('Activo') }}</span>
                                            @elseif($usuario->status == 2)
                                                <span class="badge bg-secondary">{{ __('Desactivado') }}</span>
                                            
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown dropstart">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                                                    @if ($organizador == true)
                                                        <li><button class="dropdown-item" wire:click="organizadorr({{ $usuario->id }})"> <i class="fas fa-user-cog"></i> {{ __('Administrar usuario') }}</button></li>
                                                    @endif
                                                    <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editarusuario" wire:click="edit({{ $usuario->id }})"> <i class="fas fa-edit"></i> {{ __('Editar') }}</button></li>
                                                    <li><button class="dropdown-item"> <i class="fas fa-search-plus"></i> {{ __('Ver') }}</button></li>
                                                    <li><button class="dropdown-item" wire:click="estado({{ $usuario->id }})"> <i class="fas fa-eye{{ $usuario->status == 1 ? '-slash' : '' }} "></i> {{ $usuario->status == 1 ? __('Desactivar') : __('Activar') }}</button></li>
                                                    <li><button class="dropdown-item" wire:click="borrar({{ $usuario->id }})"> <i class="fas fa-trash-alt"></i> {{ __('Borrar') }}</button></li>
                                                    
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center justify-content-center" >
                                            ¡{{ __('No hay clientes disponibles') }}!
                                        </td>
                                    </tr> 
                                @endforelse
                               
                            </tbody>
                        </table>
                    </div>
                    @if (count($this->Usuarios) > 0)
                        <div class="row text-center justify-content-center mt-2" style="max-width: 99%">
                            @desktop
                                 {{ $this->Usuarios->links() }}
                            @elsedesktop
                                 {{ $this->Usuarios->onEachSide(1)->links() }}
                            @enddesktop
                           
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if ($readytoload)   
       @include('backendv2.usuarios.modal.create')
       @include('backendv2.usuarios.modal.edit')
       @include('backendv2.usuarios.modal.organizador')
       
        <script>
            window.addEventListener('cargarimagen', event => {
               ;(function() {
                    // Initialize
                    var bLazy = new Blazy();
                    
                })();
            });
        </script>
    @endif

    @if ($updatemode)
        
    @endif

    <script>
        window.addEventListener('errores', event => {
            Swal.fire(
                '¡Error!',
                event.detail.error,
                'error'
            )
        })
        window.addEventListener('storeusuario', event => {
            $('#crearusuario').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El usuario ha sido creado con exito',
                showConfirmButton: false,
                timer: 1500
            })
        })
        
        window.addEventListener('updateusuario', event => {
            $('#editarusuario').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El usuario ha sido actualizado con exito',
                showConfirmButton: false,
                timer: 1500
            })
        })
        window.addEventListener('borrar', event => {           
            Swal.fire({
                icon: 'question',
                title: "¿Esta seguro?",
                text: "Esta acción no se podra regresar",
                showCancelButton: true,
            }).then((result) => {
                if (result.value) {
                    window.livewire.emit('borrado')
                    let timerInterval
                    Swal.fire({
                        icon :'success',
                        title: '¡Procesando! ',
                        text: 'Espere un momento, en breve estara disponible',
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    })
                }
            });
        });
        window.addEventListener('estadocambiado', event => {
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El usuario ha cambiado su estado con exito',
                showConfirmButton: false,
                timer: 1500
            })
        })
        window.addEventListener('abrirorganizador', event => {
            $('#organizador').modal('show');
        })
        window.addEventListener('quitarorganizador', event => {
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'El usuario ha sido quitado del organizador con exito',
                showConfirmButton: false,
                timer: 1500
            })
        })
        window.addEventListener('edit2', event => {
            $('#organizador').modal('hide');
            $('#editarusuario').modal('show');
            
        })
    </script>
</div>
