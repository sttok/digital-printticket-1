<div class="modal fade" id="recordardatos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">{{ __('Recordar datos') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                        <span>{{ __('Numero de telefono con prefijo del pais') }}</span>
                        <input type="tel" class="form-control mb-1 @error('numero_telefono') is-invalid @enderror" placeholder="+573101234567" wire:model.defer="numero_telefono" wire:keydown.enter="agregartelefonos" wire:target="enviarentradas" wire:loading.attr="disabled">
                        <button class="btn btn-success btn-block my-2" wire:click="agregartelefonos" wire:target="enviarentradas" wire:loading.attr="disabled" > {{ __('Agregar') }} </button>
                        @error('numero_telefono')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-md-12 col-12 mb-3 row" style="border: 1px solid #84848452;padding: 15px 5px; border-radius: 10px;">
                        @foreach ($array_telefono_notificar as $tel)
                            <div class="col-auto mb-1">
                                <span class="badge bg-success" >{{ $tel['numero'] }} <a href="#" wire:click="borrartelefono('{{ $tel['id'] }}')"><i class="fas fa-times-circle"></i></a> </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-12 mb-3 row">
                        <h6 class="mb-2" >{{ __('Seleccione las opciones a recordar') }}</h6>
                        <div class="col-md-4 col-12 mb-3">
                            <div class="custom-check">
                                <input class="form-check-input" type="checkbox" value="true" id="recordarorganizador" wire:model="recordarorganizador" wire:target="recordardatos" wire:loading.attr="disabled">
                                <label class="form-check-label" for="recordarorganizador">
                                    {{ __('Organizador') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <div class="custom-check">
                                <input class="form-check-input" type="checkbox" value="true" id="recordarscanner" wire:model.lazy="recordarscanner" wire:target="recordardatos" wire:loading.attr="disabled">
                                <label class="form-check-label" for="recordarscanner">
                                    {{ __('Scanner') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <div class="custom-check">
                                <input class="form-check-input" type="checkbox" value="true" id="recordarpuntoventa" wire:model.lazy="recordarpuntoventa" wire:target="recordardatos" wire:loading.attr="disabled">
                                <label class="form-check-label" for="recordarpuntoventa">
                                    {{ __('Punto de venta') }}
                                </label>
                            </div>
                        </div>
                        
                    </div>
                   
                    <div wire:loading wire:target="recordardatos,enviarentradas">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                {{ __('Cargando...') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  wire:target=" recordardatos" wire:loading.attr="disabled" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                <button type="button" class="btn btn-primary"  wire:target=" recordardatos" wire:loading.attr="disabled" wire:click="recordardatos()">{{ __('Enviar') }}</button>
            </div>
        </div>
    </div>
</div>