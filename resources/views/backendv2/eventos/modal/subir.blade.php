<div class="modal fade" id="subirpdfs" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                @if (!empty($entrada_id))
                    <h5 class="modal-title" >{{ __('Subir entradas generadas') . ' - ' . $this->Entrada->name }}</h5>
                @endif
               
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-12 mb-5">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-5">
                                <span>{{ __('Seleccionar almacenamiento') }}</span>
                                <select class="form-control" wire:model.defer="lugar_almacenamiento">
                                    <option value="1">{{ __('Local') }}</option>
                                    <option value="2">{{ __('Drive') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-12 mb-5">
                                <span>¿{{ __('Permitir descarga') }}?</span>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" wire:model="permitir_descarga">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">{{ $permitir_descarga == true ? 'Si' : 'No' }}</label>
                                </div>
                            </div>
                            <div class="col-12 mb-2 mt-3 text-center justify-content-center">
                                <span>{{ __('Selecciona los archivos a subir') }}</span>
                                <input type="file" class="form-control @error('uploads') is-invalid @enderror" accept="image/*, .pdf" multiple wire:model="uploads"  wire:target="uploadentrada,uploads" wire:loading.attr="disabled">
                                @error('uploads')
                                    <div class="invalid-feedback ">{{ $message }}  </div>
                                @enderror
                            </div>
                        </div>
                        
                      
                        <div class="col-12 my-3" style="max-height: 320px; overflow:auto">
                            @if (!empty($errores))
                                @foreach ($errores as $it)
                                    <div class="alert alert-danger alert-dismissable my-1" style="border-radius: 1rem;">
                                        <strong style="color: #e0317a">¡Error!</strong>  {{$it['id'] . ' - ' . $it['msg'] }}.
                                    </div>  
                                @endforeach
                            @endif
                            @if (!empty($exitos))
                                @foreach ($exitos as $i)
                                    <div class="alert alert-success alert-dismissable my-1" style="border-radius: 1rem;">
                                        <strong style="color: #008c80">¡Exito!</strong>  {{$i['id'] . ' - ' . $i['msg'] }}.
                                    </div>  
                                @endforeach
                            @endif
                        </div>
                        
                    </div>
                    <div class="col-lg-6 col-md-12 col-12 mb-5">
                        <h6 class="mb-3">{{ __('Para que las entradas digitales puedan ser subidas de manera correcta debe seguir los siguientes pasos') }}</h6>
                        <ul class="mb-3">
                            <li class="mb-2">{{ __('Bajar la tabla excelde la zona con las entradas ya generadas en sistema previamente') }}</li>
                            <li class="mb-2">{{ __('Organizar los nombres de los archivos de con el numero de identificador de la zona seguido de un guio')}}</li>
                            <li class="mb-2">{{ __('Ejemplo: ') . '1-{zona}.pdf, 2-{zona}.pdf, 2-{zona}.pdf...' }}</li>
                            <li class="mb-2">{{ __('El archivo debe ser un formato de imagen o pdf')}}</li>
                            <li class="mb-2">{{ __('El archivo no puede pesar mas de 5mb')}}</li>
                            <li class="mb-2">{{ __('Si el archivo ya se encuentra subido, sera actualizado por el nuevo')}}</li>
                        </ul>
                        <button class="btn btn-success btn-block mt-3" type="button" wire:click="descargarentrada('{{ $entrada_id }}')" wire:target="uploadentrada,uploads" wire:loading.attr="disabled">
                            <div wire:loading.inline wire:target="descargarentrada('{{ $entrada_id }}')">
                                <div class="spinner-grow spinner-grow-sm" role="status" >
                                    
                                </div>
                            </div>
                            <div wire:loading.remove wire:target="descargarentrada('{{ $entrada_id }}')" style="display: inline;">
                                <i class="fas fa-download"></i> {{ __('Descargar excel') }}
                            </div>
                        </button>
                    </div>
                    
                   
                    <div wire:loading wire:target="uploadentrada,uploads">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                {{ __('Cargando...') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  wire:target="uploadentrada" wire:loading.attr="disabled" data-bs-dismiss="modal">{{ __('Cerrar') }}</button>
                <button type="button" class="btn btn-primary"  wire:target="uploadentrada" wire:loading.attr="disabled" wire:click="uploadentrada()">{{ __('Subir archivos') }}</button>
            </div>
        </div>
    </div>
</div>