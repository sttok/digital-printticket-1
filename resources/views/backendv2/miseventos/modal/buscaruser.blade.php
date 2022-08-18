<div class="modal fade" id="buscarclient" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >{{ __('Buscar cliente para endosar'). ' - ' . $endosado_identificador }}</h5>
                <button type="button" class="btn-close" wire:click="regresarcliente" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-6 mb-2 {{ $encontrado_endosado ? 'was-validated' : '' }}">
                        <span>{{ __('Telefono cliente') }}</span>
                        <input type="tel" class="form-control mb-1 @error('search_telefono_endosado') is-invalid @enderror "  wire:model.defer="search_telefono_endosado" wire:keydown.enter="buscarcliente2" wire:target="asignarentrada" wire:loading.attr="disabled">
                        <button class="btn btn-primary btn-block my-2" wire:click="buscarcliente2" wire:target="asignarentrada" wire:loading.attr="disabled" ><i class="fas fa-search"></i></button>
                        @error('search_telefono_endosado')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                    @if ($search_telefono_endosado != '' && $encontrado_endosado == true)
                        <div class="col-md-6 col-6 mb-2 text-center justify-content-center row">
                            <div class="col-auto mb 2">
                                <h5>{{ __('Nombre') }}</h5>
                                <h6>{{ $cliente_endosado->name . ' ' . $cliente_endosado->last_name }}</h6>
                            </div>
                            <div class="col-auto mb 2">
                                <h5>{{ __('Correo') }}</h5>
                                <h6>{{ $cliente_endosado->email }}</h6>
                            </div>
                            <div class="col-auto mb 2">
                                <h5>{{ __('Telefono') }}</h5>
                                <h6>{{ $cliente_endosado->phone }}</h6>
                            </div>
                        </div>
                    @endif
                   
                    <div wire:loading wire:target="buscarcliente2,asignarentrada">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                {{ __('Cargando...') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  wire:target="asignarentrada" wire:loading.attr="disabled" wire:click="regresarcliente">{{ __('Cerrar') }}</button>
                <button type="button" class="btn btn-primary"  wire:target="asignarentrada" wire:loading.attr="disabled" wire:click="asignarentrada()">{{ __('Endosar') }}</button>
            </div>
        </div>
    </div>
</div>