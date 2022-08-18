<div wire:init="loadDatos" >
    <div class="row">
        <div class="col-md-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-cog"></i> &nbsp; {{ __('General') }}</h5>
                    <p class="card-description">{{ __('Configuración general, como el título, el logotipo del sitio, etc') }}.</p>
                    <div class="accordion accordion-flush" id="configgeneral">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne" wire:ignore.self>
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#configuracion-general" aria-expanded="false" aria-controls="configuracion-general">
                                {{ __('Abrir') }}
                                </button>
                            </h2>
                            <div id="configuracion-general" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#configgeneral" wire:ignore.self>
                                <div class="accordion-body row">
                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Nombre de la app') }}</span>
                                        <input type="text" class="form-control @error('nombre_app') is-invalid @enderror" wire:model.defer="nombre_app" wire:target="storegeneral" wire:loading.attr="disabled">
                                        @error('nombre_app')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>
                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Descripcion de la web') }}</span>
                                        <textarea class="form-control @error('descripcion_web') is-invalid @enderror" wire:model.defer="descripcion_web" wire:target="storegeneral" wire:loading.attr="disabled"></textarea>
                                        @error('descripcion_web')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>
                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Correo de la app') }}</span>
                                        <input type="text" class="form-control @error('correo_app') is-invalid @enderror" wire:model.defer="correo_app" wire:target="storegeneral" wire:loading.attr="disabled">
                                        @error('correo_app')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>

                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Logo') }}</span> 
                                        <input type="file" accept="image/*" class="form-control @error('logo') is-invalid @enderror" wire:model="logo" wire:target="storegeneral" wire:loading.attr="disabled">
                                        @error('logo')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror

                                        @if ($logo_actual)
                                            <div class="col-12 mb-3 mt-2 row text-center justify-content-center">
                                                <span>{{__('logo actual')}}</span>
                                                <img class="img-fluid my-2" src="{{ asset(route('inicio.frontend') .'/storage/public/'.$logo_actual) }}" style="max-width: 150px; border-radius:1rem">
                                            </div>
                                        @endif

                                        <div class="col-12 mb-3 mt-2 row text-center justify-content-center" wire:loading wire:target="logo">
                                            <div class="spinner-grow" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>

                                        @if ($logo)
                                            <div class="col-12 mb-3 row mt-2 text-center justify-content-center">
                                                <span>{{__('Logo previa')}}</span> 
                                                <img class="img-fluid my-2" src="{{ $logo->temporaryUrl() }}" style="max-width: 150px; border-radius:1rem">
                                            </div>
                                        @endif
                                    </div>

                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Logo oscuro') }}</span> 
                                        <input type="file" accept="image/*" class="form-control @error('logo_oscuro') is-invalid @enderror" wire:model="logo_oscuro" wire:target="storegeneral" wire:loading.attr="disabled">
                                        @error('logo_oscuro')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror

                                        @if ($logo_oscuro_actual)
                                            <div class="col-12 mb-3 mt-2 row text-center justify-content-center">
                                                <span> {{__('logo oscuro actual')}}</span>
                                                <img class="img-fluid my-2" src="{{ asset(route('inicio.frontend') .'/storage/public/'.$logo_oscuro_actual) }}" style="max-width: 150px; border-radius:1rem">
                                            </div>
                                        @endif

                                        <div class="col-12 mb-3 mt-2 row text-center justify-content-center" wire:loading wire:target="logo_oscuro">
                                            <div class="spinner-grow" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>

                                        @if ($logo_oscuro)
                                            <div class="col-12 mb-3 mt-2 row text-center justify-content-center">
                                                <span>{{__('Logo oscuro previa')}}</span> 
                                                <img class="img-fluid my-2" src="{{ $logo_oscuro->temporaryUrl() }}" style="max-width: 150px; border-radius:1rem">
                                            </div>
                                        @endif
                                    </div>

                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Icono') }}</span> 
                                        <input type="file" accept="image/*" class="form-control @error('icono') is-invalid @enderror" wire:model="icono" wire:target="storegeneral" wire:loading.attr="disabled">
                                        @error('icono')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                        @if ($icono_actual)
                                            <div class="col-12 mb-3 mt-2 row text-center justify-content-center">
                                                <span>{{__('Icono actual')}}</span>
                                                <img class="img-fluid my-2" src="{{ asset(route('inicio.frontend') .'/storage/public/'.$icono_actual) }}" style="max-width: 150px; border-radius:1rem">
                                            </div>
                                        @endif

                                        <div class="col-12 mb-3 mt-2 row text-center justify-content-center" wire:loading wire:target="icono">
                                            <div class="spinner-grow" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>

                                        @if ($icono)
                                            <div class="col-12 mb-3 mt-2 row text-center justify-content-center">
                                                <span>{{__('Icono previa')}}</span>
                                                <img class="img-fluid my-2" src="{{ $icono->temporaryUrl() }}" style="max-width: 150px; border-radius:1rem">
                                            </div>
                                        @endif
                                    </div>


                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary btn-block" wire:target="storegeneral" wire:loading.attr="disabled" wire:click="storegeneral" > <i class="fas fa-save"></i> {{ __('Guardar') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-user-check"></i> &nbsp; {{ __('Verificacion') }}</h5>
                    <p class="card-description">{{ __('Habilitar la verificación y verificar al usuario por Email o por teléfono') }}.</p>
                    <div class="accordion accordion-flush" id="verificacion2">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingthree" wire:ignore.self>
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#verificacion" aria-expanded="false" aria-controls="verificacion">
                                {{ __('Abrir') }}
                                </button>
                            </h2>
                            <div id="verificacion" class="accordion-collapse collapse" aria-labelledby="flush-headingthree" data-bs-parent="#verificacion2" wire:ignore.self>
                                <div class="accordion-body row">
                                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                                        <span>¿{{ __('Desea verificar al usuario') }}?</span>
                                        <div class="col-auto mb-4 mt-3">
                                            <div class="custom-check">
                                                <input class="form-check-input" type="checkbox" value="true" id="verificarusuario" wire:model.defer="verificar_usuario" {{ $verificar_usuario == true ? 'checked' : '' }}>
                                                <label class="form-check-label" for="verificarusuario">
                                                   ¿{{ __('Si') }}?
                                            </div>
                                        </div>
                                        @error('facebook')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                                        <span>¿{{ __('De que manera desea verificar') }}?</span> 
                                        <div class="col-12 mt-3">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" wire:click="verifica('telefono')"  {{ $verificar_telefono == true ? 'checked' : '' }}>
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                  {{ __('Verficar por telefono') }}
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" wire:click="verifica('email')" {{ $verificar_email == true ? 'checked' : '' }}>
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                 {{__('Verificar por correo')}}
                                                </label>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary btn-block" wire:target="storeverificacion" wire:loading.attr="disabled" wire:click="storeverificacion" > <i class="fas fa-save"></i> {{ __('Guardar') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-share-alt"></i> &nbsp; {{ __('Redes sociales') }}</h5>
                    <p class="card-description">{{ __('Configuración de las redes sociales para mostrar') }}.</p>
                    <div class="accordion accordion-flush" id="redes1sociales">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingtwo" wire:ignore.self>
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#redes-sociales" aria-expanded="false" aria-controls="redes-sociales">
                                {{ __('Abrir') }}
                                </button>
                            </h2>
                            <div id="redes-sociales" class="accordion-collapse collapse" aria-labelledby="flush-headingtwo" data-bs-parent="#redes1sociales" wire:ignore.self>
                                <div class="accordion-body row">
                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Facebook') }}</span>
                                        <input type="text" class="form-control @error('facebook') is-invalid @enderror" wire:model.defer="facebook" wire:target="storeredes" wire:loading.attr="disabled">
                                        @error('facebook')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror

                                        <div class="col-auto mb-4 mt-3">
                                            <div class="custom-check">
                                                <input class="form-check-input" type="checkbox" value="true" id="habilitar-facebook" wire:model="habilitar_facebook">
                                                <label class="form-check-label" for="habilitar-facebook">
                                                   ¿{{ __('Habilitar facebook') }}?
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Instagram') }}</span>
                                        <input type="text" class="form-control @error('instagram') is-invalid @enderror" wire:model.defer="instagram" wire:target="storeredes" wire:loading.attr="disabled">
                                        @error('instagram')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror

                                        <div class="col-auto mb-4 mt-3">
                                            <div class="custom-check">
                                                <input class="form-check-input" type="checkbox" value="true" id="habilitar-instagram" wire:model="habilitar_instagram">
                                                <label class="form-check-label" for="habilitar-instagram">
                                                   ¿{{ __('Habilitar instagram') }}?
                                            </div>
                                        </div>
                                    </div>

                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Tik tok') }}</span>
                                        <input type="text" class="form-control @error('titktok') is-invalid @enderror" wire:model.defer="titktok" wire:target="storeredes" wire:loading.attr="disabled">
                                        @error('titktok')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror

                                        <div class="col-auto mb-4 mt-3">
                                            <div class="custom-check">
                                                <input class="form-check-input" type="checkbox" value="true" id="habilitar-tiktok" wire:model="habilitar_tiktok">
                                                <label class="form-check-label" for="habilitar-tiktok">
                                                   ¿{{ __('Habilitar tik tok') }}?
                                            </div>
                                        </div>
                                    </div>

                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Youtube') }}</span>
                                        <input type="text" class="form-control @error('youtube') is-invalid @enderror" wire:model.defer="youtube" wire:target="storeredes" wire:loading.attr="disabled">
                                        @error('youtube')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror

                                        <div class="col-auto mb-4 mt-3">
                                            <div class="custom-check">
                                                <input class="form-check-input" type="checkbox" value="true" id="habilitar-youtube" wire:model="habilitar_youtube">
                                                <label class="form-check-label" for="habilitar-youtube">
                                                   ¿{{ __('Habilitar youtube') }}?
                                            </div>
                                        </div>
                                    </div>

                                    


                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary btn-block" wire:target="storeredes" wire:loading.attr="disabled" wire:click="storeredes" > <i class="fas fa-save"></i> {{ __('Guardar') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-comment-dots"></i> &nbsp; {{ __('Notificación por Sms') }}</h5>
                    <p class="card-description">{{ __('Ajustes de configuración de SMS de la pasarela SMS LabsMobile') }}.</p>
                    <div class="accordion accordion-flush" id="configuracion1sms">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingfour" wire:ignore.self>
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#configuracionsms" aria-expanded="false" aria-controls="configuracionsms">
                                {{ __('Abrir') }}
                                </button>
                            </h2>
                            <div id="configuracionsms" class="accordion-collapse collapse" aria-labelledby="flush-headingfour" data-bs-parent="#configuracion1sms" wire:ignore.self>
                                <div class="accordion-body row">
                                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Habilitar notificación por SMS') }}</span>
                                        <div class="col-auto mb-4 mt-3">
                                            <div class="custom-check">
                                                <input class="form-check-input" type="checkbox" value="true" id="habilitar-SMS" wire:model="habilitar_sms">
                                                <label class="form-check-label" for="habilitar-SMS">
                                                   ¿{{ __('Habilitar envio sms') }}?
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Contador de sms') }}</span>
                                        <h3 class="mt-4">{{ $contador_sms }}</h3>
                                    </div>

                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Cuenta labsmobile') }}</span>
                                        <input type="text" class="form-control @error('cuenta_labsmobile') is-invalid @enderror" wire:model.defer="cuenta_labsmobile" wire:target="storesms" wire:loading.attr="disabled">
                                        @error('cuenta_labsmobile')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>
                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Token labsmobile') }}</span>
                                        <input type="text" class="form-control @error('token_labsmobile') is-invalid @enderror" wire:model.defer="token_labsmobile" wire:target="storesms" wire:loading.attr="disabled">
                                        @error('token_labsmobile')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary btn-block" wire:target="storesms" wire:loading.attr="disabled" wire:click="storesms" > <i class="fas fa-save"></i> {{ __('Guardar') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-bell"></i> &nbsp; {{ __('Push notificacion') }}</h5>
                    <p class="card-description">{{ __('Ajustes de configuración de OneSignal y configuración de notificaciones push de la aplicación.') }}.</p>
                    <div class="accordion accordion-flush" id="configuracion1notificacion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingfive" wire:ignore.self>
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#configuracionpush" aria-expanded="false" aria-controls="configuracionpush">
                                {{ __('Abrir') }}
                                </button>
                            </h2>
                            <div id="configuracionpush" class="accordion-collapse collapse" aria-labelledby="flush-headingfive" data-bs-parent="#configuracion1notificacion" wire:ignore.self>
                                <div class="accordion-body row">
                                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Habilitar notificación push') }}</span>
                                        <div class="col-auto mb-4 mt-3">
                                            <div class="custom-check">
                                                <input class="form-check-input" type="checkbox" value="true" id="habilitar-push" wire:model="habilitar_notificaciones">
                                                <label class="form-check-label" for="habilitar-push">
                                                   ¿{{ __('Habilitar notificaciones') }}?
                                            </div>
                                        </div>
                                    </div>

                                   <div class="col-12 mb-3">
                                        <h5>{{ __('Configuración de OneSignal para la aplicación de usuario:') }}</h5>
                                        <hr>
                                   </div>

                                 
                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Onesignal App Id') }}</span>
                                        <input type="text" class="form-control @error('app_id_user') is-invalid @enderror" wire:model.defer="app_id_user" wire:target="storenotificacionpush" wire:loading.attr="disabled">
                                        @error('app_id_user')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>
                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Onesignal Project Number') }}</span>
                                        <input type="text" class="form-control @error('project_number_user') is-invalid @enderror" wire:model.defer="project_number_user" wire:target="storenotificacionpush" wire:loading.attr="disabled">
                                        @error('project_number_user')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>
                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Onesignal Api key') }}</span>
                                        <input type="text" class="form-control @error('api_key_user') is-invalid @enderror" wire:model.defer="api_key_user" wire:target="storenotificacionpush" wire:loading.attr="disabled">
                                        @error('api_key_user')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>
                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Onesignal Auth Key') }}</span>
                                        <input type="text" class="form-control @error('auth_key_user') is-invalid @enderror" wire:model.defer="auth_key_user" wire:target="storenotificacionpush" wire:loading.attr="disabled">
                                        @error('auth_key_user')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>


                                    <div class="col-12 mb-3 mt-5">
                                        <h5>{{ __('Configuración de OneSignal para la aplicación del organizador:') }}</h5>
                                        <hr>
                                   </div>

                                   <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                    <span>{{ __('Onesignal App Id') }}</span>
                                    <input type="text" class="form-control @error('app_id_organizador') is-invalid @enderror" wire:model.defer="app_id_organizador" wire:target="storenotificacionpush" wire:loading.attr="disabled">
                                    @error('app_id_organizador')
                                        <div class="invalid-feedback ">{{ $message }}  </div>
                                    @enderror
                                </div>
                                <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                    <span>{{ __('Onesignal Project Number') }}</span>
                                    <input type="text" class="form-control @error('project_number_organizador') is-invalid @enderror" wire:model.defer="project_number_organizador" wire:target="storenotificacionpush" wire:loading.attr="disabled">
                                    @error('project_number_organizador')
                                        <div class="invalid-feedback ">{{ $message }}  </div>
                                    @enderror
                                </div>
                                <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                    <span>{{ __('Onesignal Api key') }}</span>
                                    <input type="text" class="form-control @error('api_key_organizador') is-invalid @enderror" wire:model.defer="api_key_organizador" wire:target="storenotificacionpush" wire:loading.attr="disabled">
                                    @error('api_key_organizador')
                                        <div class="invalid-feedback ">{{ $message }}  </div>
                                    @enderror
                                </div>
                                <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                    <span>{{ __('Onesignal Auth Key') }}</span>
                                    <input type="text" class="form-control @error('auth_key_organizador') is-invalid @enderror" wire:model.defer="auth_key_organizador" wire:target="storenotificacionpush" wire:loading.attr="disabled">
                                    @error('auth_key_organizador')
                                        <div class="invalid-feedback ">{{ $message }}  </div>
                                    @enderror
                                </div>


                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary btn-block" wire:target="storenotificacionpush" wire:loading.attr="disabled" wire:click="storenotificacionpush" > <i class="fas fa-save"></i> {{ __('Guardar') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-comment-dots"></i> &nbsp; {{ __('Configuracion adicional') }}</h5>
                    <p class="card-description">{{ __('Configuración adicionales, moneda, app version, etc.') }}.</p>
                    <div class="accordion accordion-flush" id="configuracion1adicional">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingsix" wire:ignore.self>
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#configuracioadicional" aria-expanded="false" aria-controls="configuracioadicional">
                                {{ __('Abrir') }}
                                </button>
                            </h2>
                            <div id="configuracioadicional" class="accordion-collapse collapse" aria-labelledby="flush-headingsix" data-bs-parent="#configuracion1adicional" wire:ignore.self>
                                <div class="accordion-body row">
                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Tipo de moneda') }}</span>
                                        <select class="form-control @error('divisa') is-invalid @enderror" wire:model.defer="divisa" wire:target="storeadicional" wire:loading.attr="disabled">
                                            @foreach ($this->Monedas as $moneda)
                                                <option value="{{ $moneda->code }}"> {{ $moneda->currency }} ({{ $moneda->symbol . ' - ' . $moneda->code }}) </option>
                                            @endforeach
                                        </select>
                                        @error('divisa')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>

                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('App version') }}</span>
                                        <input type="text" class="form-control @error('app_version') is-invalid @enderror" wire:model.defer="app_version" wire:target="storeadicional" wire:loading.attr="disabled">
                                        @error('app_version')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>
                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Copyright contenido') }}</span>
                                        <input type="text" class="form-control @error('copyright_contenido') is-invalid @enderror" wire:model.defer="copyright_contenido" wire:target="storeadicional" wire:loading.attr="disabled">
                                        @error('copyright_contenido')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>

                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Color principal') }}</span>
                                        <input type="text" class="form-control @error('color_principal') is-invalid @enderror" wire:model.defer="color_principal" wire:target="storeadicional" wire:loading.attr="disabled">
                                        @error('color_principal')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>

                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Color secundario') }}</span>
                                        <input type="text" class="form-control @error('color_secundario') is-invalid @enderror" wire:model.defer="color_secundario" wire:target="storeadicional" wire:loading.attr="disabled">
                                        @error('color_secundario')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>
                                    

                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary btn-block" wire:target="storeadicional" wire:loading.attr="disabled" wire:click="storeadicional" > <i class="fas fa-save"></i> {{ __('Guardar') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-tasks"></i> &nbsp; {{ __('Configuracion adicional') }}</h5>
                    <p class="card-description">{{ __('Configuración adicionales, moneda, app version, etc.') }}.</p>
                    <div class="accordion accordion-flush" id="configuracion1adicional">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingsix" wire:ignore.self>
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#configuracioadicional" aria-expanded="false" aria-controls="configuracioadicional">
                                {{ __('Abrir') }}
                                </button>
                            </h2>
                            <div id="configuracioadicional" class="accordion-collapse collapse" aria-labelledby="flush-headingsix" data-bs-parent="#configuracion1adicional" wire:ignore.self>
                                <div class="accordion-body row">
                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Tipo de moneda') }}</span>
                                        <select class="form-control @error('divisa') is-invalid @enderror" wire:model.defer="divisa" wire:target="storeadicional" wire:loading.attr="disabled">
                                            @foreach ($this->Monedas as $moneda)
                                                <option value="{{ $moneda->code }}"> {{ $moneda->currency }} ({{ $moneda->symbol . ' - ' . $moneda->code }}) </option>
                                            @endforeach
                                        </select>
                                        @error('divisa')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>

                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('App version') }}</span>
                                        <input type="text" class="form-control @error('app_version') is-invalid @enderror" wire:model.defer="app_version" wire:target="storeadicional" wire:loading.attr="disabled">
                                        @error('app_version')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>
                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Copyright contenido') }}</span>
                                        <input type="text" class="form-control @error('copyright_contenido') is-invalid @enderror" wire:model.defer="copyright_contenido" wire:target="storeadicional" wire:loading.attr="disabled">
                                        @error('copyright_contenido')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>

                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Color principal') }}</span>
                                        <input type="text" class="form-control @error('color_principal') is-invalid @enderror" wire:model.defer="color_principal" wire:target="storeadicional" wire:loading.attr="disabled">
                                        @error('color_principal')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>

                                    <div class=" col-lg-6 col-md-12 col-12 mb-3">
                                        <span>{{ __('Color secundario') }}</span>
                                        <input type="text" class="form-control @error('color_secundario') is-invalid @enderror" wire:model.defer="color_secundario" wire:target="storeadicional" wire:loading.attr="disabled">
                                        @error('color_secundario')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>
                                    

                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary btn-block" wire:target="storeadicional" wire:loading.attr="disabled" wire:click="storeadicional" > <i class="fas fa-save"></i> {{ __('Guardar') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('errores', event => {
            Swal.fire(
                '¡Error!',
                event.detail.error,
                'error'
            )
        })
        window.addEventListener('guardado', event => {
            Swal.fire({            
                icon: 'success',
                title: '¡Exito!',
                text: 'La configuracion ha sido guardada con exito',
                showConfirmButton: false,
                timer: 1500
            })
        })
    </script>
</div>
