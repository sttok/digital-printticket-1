<div>
    <div class="modal fade" id="reporte" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ __('Reporte') }}</h5>
                    <button type="button" class="btn-close" aria-label="Close" wire:click="cerrarModalReporte()"></button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <span>{{ __('Buscar por identificador') }}</span>
                        <input type="search" class="form-control" wire:model="search">
                    </div>
                  
                    <table class="table table-hover">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Identificador') }}</th>
                            <th scope="col">{{ __('Comprador') }}</th>
                            <th scope="col">{{ __('Cantidad') }}</th>
                            <th scope="col">{{ __('Estado') }}</th>
                            <th scope="col">{{ __('Accion') }}</th>
                          </tr>
                        </thead>
                        <tbody>
                            @forelse ($this->Historials as $historial)
                                <tr>
                                    <th scope="row">{{ $historial->id }}</th>
                                    <td>{{ $historial->identificador }}</td>
                                    <td>{{ !empty($historial->cliente->name) ? $historial->cliente->name . ' ' . $historial->cliente->last_name : __('No') }}</td>
                                    <td>{{  number_format($historial->cantidad_entradas, 0 ,',','.' )  }}</td>
                                    <td>
                                        @if ($historial->estado_venta == 1)
                                            <span class="badge bg-secondary" >{{ __('Separado') }}</span>
                                        @elseif($historial->estado_venta == 2)
                                            <span class="badge bg-secondary" >{{ __('Abonado') }}</span>
                                        @elseif($historial->estado_venta == 3)
                                            <span class="badge bg-secondary" >{{ __('Pago total') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- <button class="btn btn-primary" wire:click="detalleReporte({{ $historial->id }})"><i class="fas fa-search"></i></button> --}}
                                        <a class="dropdown dropleft btn btn-primary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> 
                                            <i class="fas fa-ellipsis-v"></i> 
                                        </a>
                                        <div class="dropdown-menu dropleft" aria-labelledby="profileDropDown" style="left: auto !important;">
                                            <a class="dropdown-item" href="javascript: void(0)" wire:click="detalleReporte({{ $historial->id }})"> <i class="fas fa-search"></i> {{ __('Detalle') }}</a>
                                            <a class="dropdown-item" href="javascript: void(0)" wire:click="descargarReporte({{ $historial->id }})" > <i class="fas fa-cloud-download-alt"></i> {{ __('Descargar') }}</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                   <td colspan="6" class="text-center justify-content-center" >{{ __('No hay datos disponibles') }}</td> 
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="col-12 text-center justify-content-center row">
                        {{ $this->Historials->onEachSide(0)->links() }}
                    </div>
                </div>
               
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:target="store" wire:loading.attr="disabled" wire:click="cerrarModalReporte()">{{ __('Cerrar') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
