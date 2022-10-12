<div class="modal fade" id="showdigitals" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                @if (!empty($entrada_id))
                    <h5 class="modal-title" >{{ __('Archivos ya subidos') . ' - ' . $this->Entrada->name }}</h5>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-6 col-12 mb-2">
                    <span>{{ __('Buscar por identificador') }}</span>
                    <input type="number" class="form-control" wire:model="buscar_identificador">
                </div>
              
                <div class="row" style="max-height: 500px; overflow: auto;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Identificador') }}</th>
                                    <th scope="col">¿{{ __('Permiso descargar') }}?</th>
                                    <th scope="col">{{ __('Provider') }}</th>
                                    <th scope="col">{{ __('Url') }}</th>
                                    <th scope="col">{{ __('Acción') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($this->Digitals as $dig)
                                    <tr>
                                        <th scope="row">#{{ $dig->id }}</th>
                                        <td>#{{ $dig->identificador }}</td>
                                        <td>
                                            @if ($dig->descargas == 1)
                                                {{ __('Si') }}
                                            @else
                                                {{ __('No') }}
                                            @endif
                                        </td>
                                        <td>{{ $dig->provider }}</td>
                                        <td>
                                            @php
                                                $key = base64_encode('@kf#'.$dig->id);
                                            @endphp
                                           
                                            {{ route('ver.archivo', $key); }}
                                        </td>
                                        <td>
                                            @if($dig->provider == 'local')
                                                <a class="btn btn-info" target="_blank" href="{{ $dig->url }}">{{ __('Ver') }}</a>
                                            @else
                                                <a class="btn btn-info" target="_blank" href="{{ route('ver.archivo', $key); }}">{{ __('Ver') }}</a>
                                            @endif
                                            
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center justify-content-center" >
                                            ¡{{ __('No hay entradas disponibles') }}!
                                        </td>
                                    </tr> 
                                @endforelse
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  wire:target="uploadentrada" wire:loading.attr="disabled" data-bs-dismiss="modal">{{ __('Cerrar') }}</button>                
            </div>
        </div>
    </div>
</div>