<div class="modal fade" id="verventa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >{{ __('Ver ventra realizada') }}</h5>
                <button type="button" class="btn-close" wire:click="cerrarshow()" data-bs-dismiss="modal" aria-label="Close" ></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-auto mb 2">
                        <h5>{{ __('Nombre') }}</h5>
                        <h6>{{ $cliente['name'] . ' ' . $cliente['last_name'] }}</h6>
                    </div>
                    <div class="col-auto mb 2">
                        <h5>{{ __('Correo') }}</h5>
                        <h6>{{ $cliente['email']}}</h6>
                    </div>
                    <div class="col-auto mb 2">
                        <h5>{{ __('Telefono') }}</h5>
                        <h6>{{ $cliente['phone']}}</h6>
                    </div>
                    
                    <div class="col-auto mb 2">
                        <h5>{{ __('Cedula') }}</h5>
                        <h6>{{ $cliente['cedula']}}</h6>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('Identificador') }}</th>
                                    <th scope="col">{{ __('Entrada') }}</th>
                                    <th scope="col">{{ __('Endosado') }}</th>
                                    <th scope="col">{{ __('Accion') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($entradas_seleccionadas as $ent)
                                    <tr>
                                        <th scope="row">#{{ $ent['identificador'] }}</th>
                                        <td>{{ $ent['zona']['name'] }}</td>
                                        <td>
                                           @if($ent['entrada']['endosado_id'] != 0)
                                                <span class="badge bg-success">{{ $cliente->entrada->endosado->first_name . ' ' . $cliente->entrada->endosado->last_name}}</span>
                                            @else
                                               <span class="badge bg-secondary"> No endosado</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-primary" type="button" wire:click="enviarcompartir({{$ent['id']}})"> <i class="fas fa-share-square"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center justify-content-center" >
                                            ¡{{ __('No hay entradas seleccionadas') }}!
                                        </td>
                                    </tr> 
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                   
                    
                </div>
            </div>
            <div class="modal-footer">
                
                <button type="button" class="btn btn-secondary"  wire:loading.attr="disabled" wire:click="cerrarshow()" data-bs-dismiss="modal">{{ __('Cerrar') }}</button>
            </div>
        </div>
    </div>
</div>