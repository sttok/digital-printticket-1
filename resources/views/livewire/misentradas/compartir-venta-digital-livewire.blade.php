<div>
    <div class="modal fade" id="compartir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-share-alt"></i> {{ __('Compartir') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="limpiar()"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 col-12 mb-3">
                            <div class="card stat-widget" >
                                <a class="btn btn-success" href="https://api.whatsapp.com/send?phone={{ urlencode( $telefono_cliente )}}&text={{ $url_whatsapp }}" target="_blank" >
                                    <div class="card-body text-center justify-content-center" style="line-height: 35px; vertical-align: middle; height: 100%; display: inline;">
                                        <h3>{{ __('Whatsapp') }}<br>
                                            <i class="fab fa-whatsapp"></i>
                                        </h3>
                                    </div>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-md-4 col-12 mb-3">
                            <div class="card stat-widget" >
                                <a class="btn btn-secondary" href="mailto:{{$correo_cliente}}?Subject={{ urlencode('Url para descargar entrada') }}&body={{ $url_whatsapp }}" target="_blank" >
                                    <div class="card-body text-center justify-content-center" style="line-height: 35px; vertical-align: middle; height: 100%; display: inline;">
                                        <h3>{{ __('Correo') }}<br>
                                            <i class="far fa-envelope"></i>
                                        </h3>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4 col-12 mb-3">
                            <div class="card stat-widget" >
                                <a class="btn btn-primary" href="#" wire:click="enviarsms()" >
                                    <div class="card-body text-center justify-content-center" style="line-height: 35px; vertical-align: middle; height: 100%; display: inline;">
                                        <h3>{{ __('Enviar sms') }}<br>
                                            <i class="far fa-comment-dots"></i>
                                        </h3>
                                    </div>
                                </a>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:target="store" wire:loading.attr="disabled" data-bs-dismiss="modal" wire:click="limpiar()">{{ __('Regresar') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('abrircompartir', event => {
            $('#verventa').modal('hide');
            $('#compartir').modal('show');
        })
        window.addEventListener('abrirventa', event => {
            $('#compartir').modal('hide');
            $('#verventa').modal('show');
        })
        window.addEventListener('enviadosms', event => {
            Swal.fire({
                icon: 'success',
                title: '¡Enviado!',
                text: 'Mensaje enviado correctamente',
                showConfirmButton: false,
                timer: 1500
            })
        })
    </script>
</div>
