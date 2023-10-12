<div>
    <div class="modal fade" id="reporte" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ __('Reporte') }}</h5>
                    <button type="button" class="btn-close" aria-label="Close"
                        wire:click="cerrarModalReporte()"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex">
                        <div
                            class="col-md-{{ Auth::user()->hasRole('organization') || Auth::user()->hasRole('Admin') ? 8 : 12 }} col-12">
                            <span>{{ __('Buscar por identificador de venta, nombre, apellido, telefono o cedula') }}</span>
                            <input type="search" class="form-control" wire:model="search">
                        </div>

                        @if (Auth::user()->hasRole('organization') || Auth::user()->hasRole('Admin'))
                            <div class="col-md-4 col-12 text-center justify-content-center">
                                <span>{{ __('Descargar reporte') }}</span><br>
                                <button type="button" class="btn btn-primary" wire:click="descargarReporteOrganizador">
                                    <i class="fas fa-cloud-download-alt"></i>
                                </button>
                            </div>
                        @endif
                    </div>


                    <table class="table table-hover">
                        <thead>
                            <tr>
                                @desktop
                                    <th scope="col">#</th>
                                @elsedesktop
                                    <th scope="col">{{ __('Accion') }}</th>
                                @enddesktop

                                <th scope="col">{{ __('Identificador') }}</th>
                                @if (Auth::user()->hasRole('organization') || Auth::user()->hasRole('Admin'))
                                    <th scope="col">{{ __('Vendedor') }}</th>
                                @endif
                                <th scope="col">{{ __('Comprador') }}</th>
                                <th scope="col">{{ __('Cantidad') }}</th>
                                <th scope="col">{{ __('Estado') }}</th>
                                @desktop
                                    <th scope="col">{{ __('Accion') }}</th>
                                @enddesktop

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($this->Historials as $historial)
                                <tr
                                    class=" {{ $historial->anulado == 1 ? 'bg-info text-white' : '' }} {{ $historial->anulado == 2 ? 'bg-danger text-white' : '' }}">
                                    @desktop
                                        <th scope="row">{{ $historial->id }}</th>
                                    @elsedesktop
                                        <td>

                                            <a class="dropdown dropleft btn btn-primary" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropleft" aria-labelledby="profileDropDown"
                                                style="left: auto !important;">
                                                <a class="dropdown-item" href="javascript: void(0)"
                                                    wire:click="detalleReporte({{ $historial->id }})"> <i
                                                        class="fas fa-search"></i> {{ __('Detalle') }}</a>
                                                <a class="dropdown-item" href="javascript: void(0)"
                                                    wire:click="descargarReporte({{ $historial->id }})"> <i
                                                        class="fas fa-cloud-download-alt"></i> {{ __('Descargar') }}</a>
                                            </div>
                                        </td>
                                    @enddesktop

                                    <td>{{ $historial->identificador }}</td>
                                    @if (Auth::user()->hasRole('organization') || Auth::user()->hasRole('Admin'))
                                        <td> {{ $historial->vendedor->first_name . ' ' . $historial->vendedor->last_name }}
                                        </td>
                                    @endif
                                    <td>{{ !empty($historial->cliente->name) ? $historial->cliente->name . ' ' . $historial->cliente->last_name : __('No') }}
                                    </td>
                                    <td>{{ number_format($historial->cantidad_entradas, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($historial->anulado == 1)
                                            <span class="badge bg-secondary">{{ __('Espera de anulacion') }}</span>
                                        @elseif ($historial->anulado == 2)
                                            <span class="badge bg-danger">{{ __('Anulado') }}</span>
                                        @else
                                            @if ($historial->estado_venta == 1)
                                                <span class="badge bg-secondary">{{ __('Separado') }}</span>
                                            @elseif($historial->estado_venta == 2)
                                                <span class="badge bg-secondary">{{ __('Abonado') }}</span>
                                            @elseif($historial->estado_venta == 3)
                                                <span class="badge bg-secondary">{{ __('Pago total') }}</span>
                                            @endif
                                        @endif

                                    </td>
                                    @desktop
                                        <td>
                                            <a class="dropdown dropleft btn btn-primary" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropleft" aria-labelledby="profileDropDown"
                                                style="left: auto !important;">
                                                <a class="dropdown-item" href="javascript: void(0)"
                                                    wire:click="detalleReporte({{ $historial->id }})"> <i
                                                        class="fas fa-search"></i> {{ __('Detalle') }}</a>
                                                <a class="dropdown-item" href="javascript: void(0)"
                                                    wire:click="descargarReporte({{ $historial->id }})"> <i
                                                        class="fas fa-cloud-download-alt"></i> {{ __('Descargar') }}</a>
                                            </div>
                                        </td>
                                    @enddesktop
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->hasRole('organization') || Auth::user()->hasRole('Admin') ? 7 : 6 }}"
                                        class="text-center justify-content-center">{{ __('No hay datos disponibles') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if (count($this->Historials) > 0)
                        <div class="col-12 text-center justify-content-center">
                            {{ $this->Historials->onEachSide(0)->links() }}
                        </div>
                    @endif


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:target="store" wire:loading.attr="disabled"
                        wire:click="cerrarModalReporte()">{{ __('Cerrar') }}</button>
                </div>
            </div>
        </div>
    </div>
    @if (Auth::user()->hasRole('organization') || Auth::user()->hasRole('Admin'))
        @livewire('sidebar.descargar-reporte-livewire', ['eventoId' => $evento_id])
    @endif
</div>
