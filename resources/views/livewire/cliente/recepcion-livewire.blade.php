<div wire:init="loadDatos">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-12 col-lg-4">
                <div class="card login-box-container">
                    <div class="card-body">
                        <div class="authent-logo mb-1">
                            <img src="{{ asset(route('inicio.frontend') .'/images/upload/'.\App\Models\Setting::find(1)->logo)}}" alt="">
                        </div>

                        <div class="authent-text mb-1">
                            <p>{{ __('Bienvenido a ') }} <b>{{ \App\Models\Setting::find(1)->app_name }}</b> </p>
                            <p>{{ __('Ingresa tu cedula para recibir las entradas') }}.</p>
                        </div>

                        <div class="my-1">
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control  @error('cedula') is-invalid @enderror" id="floatingInput" wire:model.defer="cedula"
                                      wire:keydown.enter="validar" wire:target="validar" wire:loading.attr="disabled">
                                    <label for="floatingInput">{{ __('Tu documento de identidad') }}</label>
                                    @error('cedula')
                                        <div class="invalid-feedback ">{{ $message }}  </div>
                                    @enderror
                                </div>
                                <div>
                                    <ul class="my-3">
                                        <li>{{ __('Debes ingresar el numero de cedula de la persona que realizo la compra') }}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary" type="button" wire:click="validar" wire:target="validar" wire:loading.attr="disabled">
                                    <div wire:loading.inline wire:target="validar">
                                        <div class="spinner-grow spinner-grow-sm" role="status" ></div>
                                    </div>
                                    <div wire:loading.remove  wire:target="validar">
                                        {{ __('Validar') }}
                                    </div>
                                   
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('errores', event => {
            let timerInterval
            Swal.fire({
                icon :'error',
                title: '¡Error! ',
                text: event.detail.error,
                timer: 3500,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            })
        })
    </script>
</div>
