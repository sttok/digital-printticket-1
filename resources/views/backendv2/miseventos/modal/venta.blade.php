<div class="modal fade" id="ventas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >{{ __('Enviar entradas') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-6 mb-2 {{ $encontrado ? 'was-validated' : '' }}">
                        <span>{{ __('Telefono cliente') }}</span>
                        <input type="tel" class="form-control mb-1 @error('search_telefono') is-invalid @enderror" wire:model.defer="search_telefono" wire:keydown.enter="buscarcliente" wire:target="enviarentradas" wire:loading.attr="disabled">
                        <button class="btn btn-primary btn-block my-2" wire:click="buscarcliente" wire:target="enviarentradas" wire:loading.attr="disabled" ><i class="fas fa-search"></i></button>
                        @error('search_telefono')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                    @if ($search_telefono != '' && $encontrado == false)
                        <div class="col-md-6 col-6 mb-2 text-center justify-content-center">
                            <span>¿{{ __('Desea crear el cliente') }}?</span> <br>
                            <button class="btn btn-success btn-block mt-2" wire:click="createcliente()"><i class="fas fa-user-plus"></i></button>
                        </div>
                    @elseif($search_telefono != '' && $encontrado == true)
                        <div class="col-md-6 col-6 mb-2">
                            <span>{{ __('Datos del cliente') }}</span>
                            <h6 class="mt-1"><span class="badge bg-success" style="font-size: 13px;">{{ $cliente->name . ' ' . $cliente->last_name . ' - ' . $cliente->email }}</span></h6>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Identificador') }}</th>
                                    <th scope="col">{{ __('Entrada') }}</th>
                                    <th scope="col">{{ __('Endosado') }}</th>
                                    <th scope="col">{{ __('Acción') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($entradas_seleccionadas as $ent)
                                    <tr>
                                        <td><button class="btn btn-outline-danger" wire:click="quitar('{{ $ent->id }}')"><i class="fas fa-times"></i></button></td>
                                        <th scope="row">#{{ $ent->identificador }}</th>
                                        <td>{{ $ent->zona->name }}</td>
                                        <td>
                                            @if ($ent->endosado == false && $ent->entrada->endosado_id > 0)
                                                <span class="badge bg-success">{{ $ent->entrada->endosado->name . ' ' . $ent->entrada->endosado->last_name }}</span>  
                                            @elseif($ent->endosado == true)
                                                <span class="badge bg-success">{{ $ent->cliente_name }}</span>
                                            @else
                                                {{ __('No') }}
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-primary" wire:click="buscarendosar('{{ $ent->id }}', '{{ $ent->identificador }}')"><i class="fas fa-user-tag"></i> {{ __('Endosar') }}</button>
                                            @if ($ent->endosado == true)
                                                <button class="btn btn-danger" wire:click="eliminarendosado('{{ $ent->id }}')"><i class="fas fa-user-times"></i> {{ __('Eliminar endosado') }}</button>
                                            @endif
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
                <div class="col-auto" style="margin-right: 25px">
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1" style="margin-top: 0px;  margin-left: -40px !important;" wire:model="envio_correo">
                        <label class="form-check-label" for="exampleCheck1">¿{{ __('Enviar por correo') }}?</label>
                    </div>
                </div>
                <div class="col-auto" style="margin-right: 25px">
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck2" style="margin-top: 0px; margin-left: -40px !important;" wire:model="envio_sms">
                        <label class="form-check-label" for="exampleCheck2">¿{{ __('Enviar por sms') }}?</label>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary"  wire:target="enviarentradas" wire:loading.attr="disabled" data-bs-dismiss="modal">{{ __('Cerrar') }}</button>
                <button type="button" class="btn btn-primary"  wire:target="enviarentradas" wire:loading.attr="disabled" wire:click="enviarentradas()">{{ __('Enviar') }}</button>
            </div>
        </div>
    </div>
</div>