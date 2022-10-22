<div class="modal fade" id="ventarapidamodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >{{ __('Nota para venta rapida') }}</h5>
                <button type="button" class="btn-close" wire:click="cerrarshow" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-12 mb-2 ">
                        <span class="mb-2">{{ __('Ingresa una nota') }}</span>
                        <textarea class="form-control mb-1 @error('nota_venta') is-invalid @enderror" wire:model.lazy="nota_venta"  wire:target="ventarapida, ventarapida2" wire:loading.attr="disabled"></textarea>
                        @error('nota_venta')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                   
                    <div wire:loading wire:target="ventarapida, ventarapida2">
                        <div class="progress my-2">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                {{ __('Cargando...') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  wire:target="ventarapida, ventarapida2" wire:loading.attr="disabled" wire:click="cerrarshow">{{ __('Cancelar') }}</button>
                @if ($agrupar_palcos == false)
                    <button type="button" class="btn btn-primary"  wire:target="ventarapida" wire:loading.attr="disabled" wire:click="ventarapida()">{{ __('Guardar') }}</button>
                @else
                    <button type="button" class="btn btn-primary"  wire:target="ventarapida2" wire:loading.attr="disabled" wire:click="ventarapida2">{{ __('Guardar') }}</button>
                @endif
                
            </div>
        </div>
    </div>
</div>