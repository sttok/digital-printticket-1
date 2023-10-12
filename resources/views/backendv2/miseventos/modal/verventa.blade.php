<div class="modal fade" id="verventa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Venta realizada') }}</h5>
                <button type="button" class="btn-close" wire:click="cerrarshow()" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-auto mb 2">
                        <h5>{{ __('Nombre') }}</h5>
                        <h6>{{ $cliente['name'] . ' ' . $cliente['last_name'] }}</h6>
                    </div>

                    <div class="col-auto mb 2">
                        <h5>{{ __('Telefono') }}</h5>
                        <h6>{{ $cliente['phone'] }}</h6>
                    </div>

                    <div class="col-auto mb 2">
                        <h5>{{ __('Cedula') }}</h5>
                        <h6>{{ $cliente['cedula'] }}</h6>
                    </div>

                    @if ($estadoAnulacion > 0)
                        <div class="col-auto mb 2">
                            <h5>{{ __('Estado anulacion') }}</h5>
                            @if ($estadoAnulacion == 1)
                                <h6 class="badge bg-success">Espera de aprobacion</h6>
                            @elseif($estadoAnulacion == 2)
                                <h6 class="badge bg-danger">Anulado</h6>
                            @elseif($estadoAnulacion == 3)
                                <h6 class="badge bg-warning">No aprobado</h6>
                            @endif
                        </div>
                    @endif


                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('Entrada') }}</th>
                                    <th scope="col">{{ __('Identificador') }}</th>
                                    <th scope="col">{{ __('Consecutivo') }}</th>
                                    <th scope="col">{{ __('Palco') }}</th>
                                    <th scope="col">{{ __('Asiento') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($venta_realizada as $ent)
                                    <tr>
                                        <td> {{ $ent['name_entrada'] }} </td>
                                        <td>#{{ $ent['identificador'] }}</td>
                                        <td> {{ $ent['consecutivo'] }} </td>
                                        <td> {{ $ent['palco'] != '' ? $ent['palco'] : 'No' }} </td>
                                        <td> {{ $ent['asiento'] != '' ? $ent['asiento'] : 'No' }} </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center justify-content-center">
                                            ¡{{ __('No hay entradas seleccionadas') }}!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
            <div class="modal-footer">

                @if ($estadoVenta == 1)
                    <button type="button" class="btn btn-danger float-left" wire:loading.attr="disabled"
                        wire:click="solicitarAnulacion()">
                        <div wire:loading wire:target="solicitarAnulacion">
                            <div class="spinner-grow spinner-grow-sm" role="status">
                            </div>
                        </div>
                        <div wire:loading.remove wire:target="solicitarAnulacion" style="display: inline;">
                            <i class="fas fa-ban"></i> &nbsp; {{ __('Solicitar Anulación') }}
                        </div>
                    </button>
                @endif

                <button type="button" class="btn btn-success float-left" wire:loading.attr="disabled"
                    wire:click="compartirventawhatsapp2()">
                    <div wire:loading wire:target="compartirventawhatsapp2">
                        <div class="spinner-grow spinner-grow-sm" role="status">
                        </div>
                    </div>
                    <div wire:loading.remove wire:target="compartirventawhatsapp2" style="display: inline;">
                        <i class="fab fa-whatsapp"></i> &nbsp; {{ __('Compartir whatsapp') }}
                    </div>
                </button>

                <button type="button" class="btn btn-secondary float-left" wire:loading.attr="disabled"
                    wire:click="compartirsms()">
                    <div wire:loading wire:target="compartirsms">
                        <div class="spinner-grow spinner-grow-sm" role="status">
                        </div>
                    </div>
                    <div wire:loading.remove wire:target="compartirsms" style="display: inline;">
                        <i class="far fa-comment-dots"></i> &nbsp; {{ __('Compartir sms') }}
                    </div>
                </button>

                <button type="button" class="btn btn-secondary" wire:loading.attr="disabled"
                    wire:click="cerrarshow()">{{ __('Cerrar') }}</button>
            </div>
        </div>
    </div>
</div>
