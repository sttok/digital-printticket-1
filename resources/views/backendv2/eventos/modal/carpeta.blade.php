<div class="modal fade" id="carpetas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                @if (!empty($entrada_id))
                    <h5 class="modal-title" >{{ __('Carpeta para') . ' - ' . $this->Entrada->name }}</h5>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:target="buscardocumentos" wire:loading.attr="disabled"></button>
            </div>
            <div class="modal-body row">
                <div class="col-md-8 col-12 mb-3">
                    <h5>{{ __('Url') }}</h5>
                    <a href="{{ $this->url_carpeta }}" target="_blank" rel="noopener noreferrer"><span>{{ $this->url_carpeta }}</span></a> 
                </div>
                <div class="col-md-4 col-12 mb-3">
                    <br>
                    <button class="btn btn-primary" wire:click="buscardocumentos"  wire:target="buscardocumentos" wire:loading.attr="disabled">
                        <div wire:loading.inline wire:target="buscardocumentos">
                            <div class="spinner-grow spinner-grow-sm" role="status" style="margin-top: -20px" >
                            </div>
                        </div>
                        <div wire:loading.remove wire:target="buscardocumentos" style="display: inline;">
                              <i class="fas fa-play"></i> {{ __('Buscar documentos') }}
                        </div>
                    </button>
                </div>
              
                <div class="row" style="max-height: 500px; overflow: auto;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            @if (!empty($this->Entrada))
                                @if ($this->Entrada->forma_generar == 1)
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">{{ __('Identificador') }}</th>
                                            <th scope="col">{{ __('Nombre') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($archivos_subidos as $ar)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ Str::before($ar['name'], '-'); }}</td>
                                                <td>{{ $ar['name']}}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center justify-content-center" >
                                                    ¡{{ __('No hay archivos disponibles') }}!
                                                </td>
                                            </tr> 
                                        @endforelse
                                    
                                    </tbody>
                                @else
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">{{ __('Palco') }}</th>
                                            <th scope="col">{{ __('Identificador') }}</th>
                                            <th scope="col">{{ __('Nombre') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($archivos_subidos as $ar)
                                            @foreach ($ar['items'] as $it)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $ar['name'] }}</td>
                                                    <td>{{ Str::before($it['name'], '-'); }}</td>
                                                    <td>{{ $it['name']}}</td>
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center justify-content-center" >
                                                    ¡{{ __('No hay archivos disponibles') }}!
                                                </td>
                                            </tr> 
                                        @endforelse
                                    
                                    </tbody>
                                @endif
                            @endif                            
                        </table>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:target="buscardocumentos" wire:loading.attr="disabled" data-bs-dismiss="modal" wire:click="limpiarcarpetas" >{{ __('Cerrar') }}</button>
                <button type="button" class="btn btn-success" wire:target="buscardocumentos" wire:loading.attr="disabled" data-bs-dismiss="modal" wire:click="guardarentradas" >{{ __('Guardar') }}</button>
            </div>
        </div>
    </div>
</div>