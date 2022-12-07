<div>
    <div class="modal fade" id="compartirphone" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-share-alt"></i> {{ __('Compartir') }}</h5>
                    <button type="button" class="btn-close" aria-label="Close" wire:click="limpiar()"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 col-12 mb-3">
                            <span>{{ __('Número de telefono') }}</span>
                            <select class="form-select @error('prefijo') is-invalid @enderror" wire:model.defer="prefijo" wire:target="enviar" wire:loading.attr="disabled">
                                <option value="+57" selected>+57 COL</option>
                                <option value="+1">+1 EEUU</option>
                                <option value="+56">+56 CL</option>
                                <option value="+593">+593 ECU</option>
                                <option value="+52">+52 MEX</option>
                                <option value="+34">+34 ESP</option>
                                <option value="+507">+507 PAN</option>
                            </select>
                            @error('prefijo')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror 
                        </div>

                        <div class="col-md-6 col-12 mb-3">
                            <br>
                            <input type="tel" class="form-control @error('telefono') is-invalid @enderror @error('phone') is-invalid @enderror" 
                            wire:model.defer="telefono" wire:target="enviar" wire:loading.attr="disabled" wire:keydown.enter="enviar">
                            @error('telefono')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror
                            @error('phone')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" wire:target="enviar" wire:loading.attr="disabled" wire:click="limpiar()">{{ __('Cancelar') }}</button>
                    <button type="button" class="btn btn-success"   wire:target="enviar" wire:loading.attr="disabled" wire:click="enviar()">{{ __('Enviar') }}</button>
                </div>
            </div>
        </div>
    </div>   
</div>
