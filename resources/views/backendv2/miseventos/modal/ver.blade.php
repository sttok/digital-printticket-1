<div class="modal fade" id="verenlace" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Ver enlace') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @if ($digital_id != '')
                        <div class="col-md-6 col-12 mb-3">
                            <div class="card">
                                <div class="card-body text-center justify-content-center" style="border: 2px dotted #fefefe6e; border-radius: 10px">
                                    <h5 class="card-title">{{ __('ENLACE') }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted" id="codigo">{{ $this->Digital->url_1 }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <div class="card stat-widget bg-primary" >
                                <a class="btn btn-primary" href="{{ $this->Digital->url_1 }}" target="_blank" >
                                    <div class="card-body text-center justify-content-center" style="line-height: 35px; vertical-align: middle; height: 100%; display: inline;">
                                        <h3 style="color: rgba(225,235,245,.87);" >{{ __('Descargar') }}<br>
                                            <i class="fas fa-cloud-download-alt"></i>
                                        </h3>
                                    </div>
                                </a>
                            </div>
                            
                        </div>
                    @endif
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" wire:target="store" wire:loading.attr="disabled" data-bs-dismiss="modal" wire:click="limpiar()">{{ __('Cancelar') }}</button>
            </div>
        </div>
    </div>
</div>