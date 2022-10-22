<div class="row mb-2">
    @for ($i = 1; $i <= $cantidad_palcos; $i++)
        <div class="col-md-2 col-4 mb-3">
            <div class="card card-file-manager zoom " >
                <div class="d-flex">
                    <div class="col-md-12 col-12 mb-2">
                        <div class="dropdown card-dropdown dropstart" style="float: right;">
                            <button class="btn btn-primary dropstart dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <div style="display: inline;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </div>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                                <li>
                                    <button class="dropdown-item" type="button" wire:click="venderpalco('{{ $i }}')">
                                        <i class="fas fa-tag"></i> {{ __('Venta') }}
                                    </button>
                                    <button class="dropdown-item" type="button" wire:click="abrirventarapida2('{{ $i }}')" >
                                        <i class="fas fa-shipping-fast"></i> {{ __('Venta rapida') }}
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-file-header" style="background: transparent;">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">{{ __('Palco') . ' ' . $i }}</h6>
                    <p class="card-text ">{{ __('Asientos: ') . $cantidad_asientos }}</p>
                </div>
            </div>
        </div>
    @endfor

    <script>
        window.addEventListener('abrirventa2', event => {
            $('#ventarapidamodal').modal('show');
        })
        window.addEventListener('cerrarmodalventarapida', event => {
            $('#ventarapidamodal').modal('hide');
        })
        window.addEventListener('procesandoventa', event => {
            Swal.fire({
                icon: 'info',
                title: '¡Espere un momento!',
                text: 'La venta se esta procesando',
                showConfirmButton: false,
                timer: 1700
            })
        })
    </script>
</div>
