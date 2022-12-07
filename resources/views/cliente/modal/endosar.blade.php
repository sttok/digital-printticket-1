<div>
    <div class="modal fade" id="endosar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-share-alt"></i> {{ __('Endosar entrada') }}</h5>
                    <button type="button" class="btn-close" aria-label="Close" wire:click="limpiar()"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6 col-12 mb-3">
                            <span>{{ __('Cedula') }}</span>
                            <input type="number" class="form-control @error('cedula_endosado') is-invalid @enderror" wire:model.lazy="cedula_endosado" wire:target="endosado" wire:loading.attr="disabled">                           
                            @error('cedula_endosado')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror 
                        </div>

                        <div class="col-md-6 col-12 mb-3 row">
                            <div class="col-12">
                                <span>{{ __('Número de telefono') }}</span>
                            </div>
                            <div class="col-md-5 col-12 mb-3">
                                <select class="form-select @error('prefijo') is-invalid @enderror" wire:model.defer="prefijo" wire:target="endosado" wire:loading.attr="disabled">
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
                                <input type="tel" class="form-control @error('telefono') is-invalid @enderror @error('phone') is-invalid @enderror" 
                                wire:model.defer="telefono" wire:target="endosado" wire:loading.attr="disabled" wire:keydown.enter="endosado">
                                @error('telefono')
                                    <div class="invalid-feedback ">{{ $message }}  </div>
                                @enderror
                                @error('phone')
                                    <div class="invalid-feedback ">{{ $message }}  </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12 mb-3">
                            <span>{{ __('Nombre') }}</span>
                            <input type="text" class="form-control @error('nombre_endosado') is-invalid @enderror" wire:model.defer="nombre_endosado" wire:target="endosado" wire:loading.attr="disabled">                           
                            @error('nombre_endosado')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror 
                        </div>

                        <div class="col-md-6 col-12 mb-3">
                            <span>{{ __('Apellido') }}</span>
                            <input type="text" class="form-control @error('apellido_endosado') is-invalid @enderror" wire:model.defer="apellido_endosado" wire:target="endosado" wire:loading.attr="disabled">                           
                            @error('apellido_endosado')
                                <div class="invalid-feedback ">{{ $message }}  </div>
                            @enderror 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" wire:target="endosado" wire:loading.attr="disabled" wire:click="limpiar()">{{ __('Cancelar') }}</button>
                    <button type="button" class="btn btn-success"   wire:target="endosado" wire:loading.attr="disabled" wire:click="endosado()">{{ __('Endosar') }}</button>
                </div>
            </div>
        </div>
    </div>   
</div>
