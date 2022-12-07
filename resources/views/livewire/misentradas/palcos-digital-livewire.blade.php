<div style="padding: 0px" >
    <div class="card">
        <div class="card-header">
            <nav class="nav nav-pills nav-justified">
                <a class="nav-item nav-link nav-link-1 {{ $filtrar_por == 0 ? 'active' : 'desactive' }}" href="#entrads" wire:click="$set('filtrar_por', 0)">{{ __('Palcos sin vender') . ' ( ' .  number_format($total_sin_ventas, 0 ,',','.') . ' )' }}</a>
                <a class="nav-item nav-link nav-link-2 {{ $filtrar_por == 1 ? 'active' : 'desactive' }}" href="#entrads" wire:click="$set('filtrar_por', 1)">{{ __('Palcos vendidos') . ' ( ' .  number_format($total_ventas, 0 ,',','.') . ' )' }}</a>
            </nav>
        </div>
        
        <div class="card-body row" id="entrads">
            <div wire:loading.delay.long>
                <div class="col-12 my-2 text-center justify-content-center row">
                    <div class="spinner-grow my-2" role="status" >
                    </div>
                </div>
            </div>
            @forelse ($data['orden_palcos'] as $orden)
                @if ($orden['vendido'] == $filtrar_por)
                    <div class="col-md-2 col-4 mb-3">
                        <div class="card card-file-manager zoom " >
                            <div class="d-flex">
                                <div class="col-md-12 col-12 mb-2">
                                    <div class="dropdown card-dropdown dropstart" style="float: right;" wire:key="palco-{{$loop->iteration}}">
                                        <button class="btn btn-primary dropstart dropdown-toggle float-right" type="button" id="dropdownMenuButton2" wire:key="btn-palco-{{ $orden['palco'] }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <div style="display: inline;">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </div>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2" style="" wire:key="drop-palco-{{ $orden['palco'] }}">
                                            <li>
                                                @if ($filtrar_por == 0)
                                                    <button class="dropdown-item" type="button" wire:click="venderpalco('{{ $orden['palco'] }}')" wire:key="btn-sell-{{ $orden['palco'] }}">
                                                        <i class="fas fa-tag"></i> {{ __('Venta') }}
                                                    </button>
                                                    <button class="dropdown-item" type="button" wire:click="abrirventarapida2('{{ $orden['palco'] }}')" wire:key="btn-sellfast-{{ $orden['palco'] }}">
                                                        <i class="fas fa-shipping-fast"></i> {{ __('Venta rapida') }}
                                                    </button>
                                                @else
                                                    <button class="dropdown-item" type="button" wire:click="detallepalco('{{ $orden['palco'] }}')" wire:key="btn-details-{{ $orden['palco'] }}">
                                                        <i class="fas fa-receipt"></i> {{ __('Detalle') }}
                                                    </button>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-file-header" style="background: transparent;">
                                @if ($filtrar_por == 0)
                                    <i class="fas fa-clipboard-list"></i>
                                @else
                                    <i class="fas fa-clipboard-check"></i>
                                @endif
                                
                            </div>
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2">{{ __('Palco') . ' ' . $orden['palco'] }}</h6>
                                <p class="card-text ">{{ __('Asientos: ') . $orden['asientos'] }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12 mb-3 text-center justify-content-center">
                    <h4>{{ __('No hay palcos disponibles') }}</h4>
                </div>
            @endforelse
        </div>
    </div>
</div>

