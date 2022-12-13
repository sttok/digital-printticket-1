<div>
    <div class="modal fade" id="aggPalco" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-chair"></i> {{ __('Selecciona un palco') }}</h5>
                    <button type="button" class="btn-close" aria-label="Close" wire:click="limpiar()"></button>
                </div>
                <div class="modal-body">
                    @if (count($this->grupos_palcos) > 0)
                        <div class="row">
                            @foreach ($this->grupos_palcos as $item)
                                <div class="col-12">
                                    <h4 id="list-item-{{  $item['0']['mesas'] }}">Palco {{ $item['0']['mesas'] }}</h4>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" wire:click="seleccionarPalco('{{ $item['0']['mesas'] }}')">
                                                            <label class="form-check-label" for="palco-{{ $item['0']['mesas'] }}">
                                                                Palco
                                                            </label>
                                                        </div>
                                                    </th>
                                                    <th scope="col">{{ __('Asiento') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item as $asiento)
                                                    <tr>
                                                        <th scope="row">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="{{ $asiento['id'] }}"  wire:model="seleccionados_palcos">
                                                                <label class="form-check-label" >
                                                                   Palco {{ $asiento['mesas'] }}
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <td>{{ $asiento['asiento'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <h4 class="text-center justify-content-center">¡{{ __('No hay palco disponible') }}!</h4>
                    @endif
                </div>
               
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:target="store" wire:loading.attr="disabled" wire:click="limpiar()">{{ __('Cerrar') }}</button>
                    <button type="button" class="btn btn-success" wire:click="agregarCarritoPalco">{{ __('Agregar') }}</button>
                </div>
            </div>
        </div>
    </div>   
</div>
