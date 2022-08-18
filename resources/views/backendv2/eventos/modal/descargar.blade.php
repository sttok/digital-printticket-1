<div class="modal fade" id="descargarentradas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">{{ __('Descargar entradas') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-12 mb-5">
                        <div class="custom-check">
                            <input class="form-check-input" type="checkbox" value="true" 
                            id="descargartodoexcel" wire:model="descargartodoexcel" wire:target="descargarexcel" wire:loading.attr="disabled">
                            <label class="form-check-label" for="descargartodoexcel">
                                ¿{{ __('Desea descargar todos') }}?
                            </label>
                        </div>
                    </div>
                    <div class="col-12 mb-3 row">
                        <h6 class="mb-2" >{{ __('Seleccione las entradas a descargar') }}</h6>
                        @foreach ($this->Entradas as $entrada2)
                            <div class="col-auto mb-4">
                                <div class="custom-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $entrada2->id }}" 
                                    id="entrada2-{{ $entrada2->id }}-{{ $loop->iteration }}" wire:click="aggentrada({{ $entrada2->id }})" wire:target="descargarexcel" wire:loading.attr="disabled">
                                    <label class="form-check-label" for="entrada2-{{ $entrada2->id }}-{{ $loop->iteration }}">
                                        {{ $entrada2->name  }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                   
                    <div wire:loading wire:target=" descargarexcel">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                {{ __('Cargando...') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  wire:target=" descargarexcel" wire:loading.attr="disabled" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                <button type="button" class="btn btn-primary"  wire:target=" descargarexcel" wire:loading.attr="disabled" wire:click="descargarexcel()">{{ __('Descargar') }}</button>
            </div>
        </div>
    </div>
</div>