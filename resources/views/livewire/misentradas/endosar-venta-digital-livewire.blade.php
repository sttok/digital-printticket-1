<div>
    <div class="modal fade" id="endosarventa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ __('Endosar') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="limpiar()"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3 {{ $encontrado ? 'was-validated' : '' }}">
                            <span>{{ __('La busqueda es solo clientes del empresario') }}</span>
                            <input type="tel" class="form-control mb-1 @error('search_telefono') is-invalid @enderror" placeholder="{{ __('No Celular o cedula') }}" wire:model.defer="search_telefono" wire:keydown.enter="buscarcliente" wire:target="enviarentradas" wire:loading.attr="disabled">
                            <button class="btn btn-primary btn-block my-2" wire:click="buscarcliente" wire:target="enviarentradas" wire:loading.attr="disabled" ><i class="fas fa-search"></i></button>
                            @error('search_telefono')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror
                        </div>
                        @if($search_telefono != '' && $encontrado == true)
                            <div class="col-12 mb-3">
                                <span>{{ __('Cliente encontrado') }}</span>
                                <h6 class="mt-1">
                                    <span class="badge bg-success" style="font-size: 13px;">{{ $cliente->name . ' ' . $cliente->last_name . ' - ' . $cliente->email }}</span>
                                </h6>
                            </div>
                        @endif
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:target="store" wire:loading.attr="disabled" data-bs-dismiss="modal" wire:click="limpiar()">{{ __('Cerrar') }}</button>
                    <button type="button" class="btn btn-success" wire:target="store" wire:loading.attr="disabled" data-bs-dismiss="modal" wire:click="endosar()" {{ $encontrado == false ? 'disable' : '' }} >{{ __('Endosar') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('abrirendosar', event => {
            $('#endosarventa').modal('show');
        })
        window.addEventListener('cerrarendosar', event => {
            $('#endosarventa').modal('hide');
            Swal.fire({
                icon: 'success',
                title: '¡Exito!',
                text: 'Ha sido endosado con exito',
                showConfirmButton: false,
                timer: 1500
            })
        })
    </script>
</div>
