<div>
    <div class="modal fade" id="estadisitica" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel"> {{ __('Estadisticas de tu evento') }}</h4>
                    <button type="button" class="btn-close" aria-label="Close" wire:click="cerrarModalEstadisticas()"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-12 mb-2 p-4">
                        <div class="text-center justify-content-center row" style="padding-top: 5px">
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <div class="card card-custom text-white bg-primary">
                                    <div class="card-header text-center justify-content-center" style="padding-bottom: 5px;">
                                        <h6>{{ __('Vendido ') }}</h6>
                                    </div>
                                    <div class="card-body text-center justify-content-center">
                                        <h2>{{ $porcentaje_venta.'%' }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <div class="card card-custom bg-info text-white">
                                    <div class="card-header text-center justify-content-center" style="padding-bottom: 5px;">
                                        <h6>{{ __('Dias restantes ') }}</h6>
                                    </div>
                                    <div class="card-body text-center justify-content-center">
                                        <h2>{{ $dias_restantes }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <div class="card card-custom bg-secondary" style="min-height: 153px">
                                    <div class="card-header text-center justify-content-center" style="padding-bottom: 5px;">
                                        <h6>{{ __('Estado') }}</h6>
                                    </div>
                                    <div class="card-body text-center justify-content-center" style="padding: 23px;">
                                        @if ($estado_evento == 1)
                                            <i class="far fa-laugh-beam" style="font-size: 55px"></i>
                                        @elseif($estado_evento == 2)
                                            <i class="far fa-smile-beam" style="font-size: 55px"></i>
                                        @elseif($estado_evento == 3)
                                            <i class="far fa-meh text" style="font-size: 55px"></i>
                                        @elseif($estado_evento == 4)
                                            <i class="far fa-frown" style="font-size: 55px"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-12 mb-3">                       
                        <div id="apex2" wire:ignore></div>
                    </div>
                </div>
               
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:target="store" wire:loading.attr="disabled" wire:click="cerrarModalEstadisticas()">{{ __('Cerrar') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
