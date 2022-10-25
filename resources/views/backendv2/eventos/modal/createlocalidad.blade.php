<div class="modal fade" id="createlocalidad" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¡{{ __('Crear localidad') }}!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="row">
                    <div class="col-md-6 col-12 mb-3">
                        <span>{{ __('Nombre localidad') }}</span>
                        <input type="text" class="form-control @error('nombre_localidad') is-invalid @enderror" wire:model.defer="nombre_localidad" wire:target="storeLocalidad" wire:loading.attr="disabled">
                        @error('nombre_localidad')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                    <div class="col-md-6 col-12 mb-3">
                        <span>{{ __('Direccion localidad') }}</span>
                        <input type="text" class="form-control @error('direccion_localidad') is-invalid @enderror" wire:model.defer="direccion_localidad" wire:target="storeLocalidad" wire:loading.attr="disabled">
                        @error('direccion_localidad')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <span>{{ __('Iframe localidad') }}</span>
                        <textarea class="form-control @error('iframe_localidad') is-invalid @enderror" wire:model.defer="iframe_localidad" wire:target="storeLocalidad" wire:loading.attr="disabled"></textarea>
                        @error('iframe_localidad')
                            <div class="invalid-feedback ">{{ $message }}  </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" wire:target="storeLocalidad" wire:loading.attr="disabled" data-bs-dismiss="modal" >{{ __('Cancelar') }}</button>
                <button type="button" class="btn btn-success" wire:target="storeLocalidad" wire:loading.attr="disabled" wire:click="storeLocalidad" >{{ __('Guardar') }}</button>
            </div>
        </div>
    </div>
</div>