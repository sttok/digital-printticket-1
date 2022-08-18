<div class="modal fade" id="organizador" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">{{ __('Informacion organizador') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class=" col-md-6 col-12 mb-3">
                        <span>{{ __('Podras agregar usuarios a este organizador') }}</span>
                        
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-12 mb-5">
                          <div class="list-group" id="list-tab" role="tablist">
                            <a class="list-group-item list-group-item-action active" id="list-puntos-ventas" data-bs-toggle="list" href="#list-puntos-vventas" role="tab" aria-selected="true" wire:ignore.self>{{ __('Puntos de ventas') }}</a>
                            <a class="list-group-item list-group-item-action" id="list-escanners" data-bs-toggle="list" href="#list-escaneres" role="tab" aria-selected="false" wire:ignore.self>{{ __('Escaners') }}</a>
                          </div>
                        </div>
                        <div class="col-lg-8 col-12 mb-5">
                          <div class="tab-content mt-3" id="nav-tabContent">
                            <div class="tab-pane fade active show" id="list-puntos-vventas" role="tabpanel" aria-labelledby="list-puntos-ventas" wire:ignore.self>
                                <button type="button" class="btn btn-primary" ><i class="fas fa-user-plus"></i> {{ __('Agregar') }}</button>
                                <div class="table-responsive">
                                    <table class="table table-hover" style="min-height: 175px;">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ __('Nombre') }}</th>
                                                <th scope="col">{{ __('Telefono') }}</th>
                                                <th scope="col">{{ __('Acción') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($this->Usuarioorganizador as $usuarioorg)
                                                @if ($usuarioorg->hasRole('punto venta'))
                                                    <tr>
                                                        <th scope="row">#{{ $usuarioorg->id }}</th>
                                                        <td> 
                                                            {{ $usuarioorg->first_name . ' ' . $usuarioorg->last_name}}
                                                        </td>
                                                        <td>{{ $usuarioorg->phone}}</td>
                                                        <td>
                                                            <div class="dropdown dropstart">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                </button>
                                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                                                                    <li><button class="dropdown-item" wire:click="edit2({{ $usuarioorg->id }})"> <i class="fas fa-edit"></i> {{ __('Editar') }}</button></li>
                                                                   
                                                                    <li><button class="dropdown-item" wire:click="estado({{ $usuarioorg->id }})"> <i class="fas fa-eye{{ $usuarioorg->status == 1 ? '-slash' : '' }} "></i> {{ $usuarioorg->status == 1 ? __('Desactivar') : __('Activar') }}</button></li>
                                                                    <li><button class="dropdown-item" wire:click="quitar({{ $usuarioorg->id }})"> <i class="fas fa-trash-alt"></i> {{ __('Quitar') }}</button></li>
                                                                    
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center justify-content-center" >
                                                        ¡{{ __('No hay usuarios disponibles') }}!
                                                    </td>
                                                </tr> 
                                            @endforelse
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="list-escaneres" role="tabpanel" aria-labelledby="list-escanners" wire:ignore.self>
                                <button type="button" class="btn btn-primary" ><i class="fas fa-user-plus"></i> {{ __('Agregar') }}</button>
                                <div class="table-responsive">
                                    <table class="table table-hover" style="min-height: 175px;">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ __('Nombre') }}</th>
                                                <th scope="col">{{ __('Telefono') }}</th>
                                                <th scope="col">{{ __('Acción') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($this->Usuarioorganizador as $usuarioorg)
                                                @if ($usuarioorg->hasRole('scanner'))
                                                    <tr>
                                                        <th scope="row">#{{ $usuarioorg->id }}</th>
                                                        <td> 
                                                            {{ $usuarioorg->first_name . ' ' . $usuarioorg->last_name}}
                                                        </td>
                                                        <td>{{ $usuarioorg->phone}}</td>
                                                        <td>
                                                            <div class="dropdown dropstart">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                </button>
                                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">                                                                    
                                                                    <li><button class="dropdown-item" wire:click="edit2({{ $usuarioorg->id }})"> <i class="fas fa-edit"></i> {{ __('Editar') }}</button></li>                                                                    
                                                                    <li><button class="dropdown-item" wire:click="estado({{ $usuarioorg->id }})"> <i class="fas fa-eye{{ $usuarioorg->status == 1 ? '-slash' : '' }} "></i> {{ $usuarioorg->status == 1 ? __('Desactivar') : __('Activar') }}</button></li>
                                                                    <li><button class="dropdown-item" wire:click="quitar({{ $usuarioorg->id }})"> <i class="fas fa-trash-alt"></i> {{ __('Quitar') }}</button></li>
                                                                    
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                               
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center justify-content-center" >
                                                        ¡{{ __('No hay usuarios disponibles') }}!
                                                    </td>
                                                </tr> 
                                            @endforelse
                                           
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                          </div>
                        </div>
                      </div>
                   
                   
                    <div wire:loading wire:target="masentradas">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                {{ __('Cargando...') }}
                            </div>
                        </div>                
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  wire:target="masentradas" wire:loading.attr="disabled" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                <button type="button" class="btn btn-primary"  wire:target="masentradas" wire:loading.attr="disabled" wire:click="masentradas()">{{ __('Guardar') }}</button>
            </div>
        </div>
    </div>
</div>