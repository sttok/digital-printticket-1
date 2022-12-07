<div>
    <div class="authent-logo">
        <img src="{{ asset(route('inicio.frontend') .'/images/upload/'.App\Models\Setting::find(1)->logo) }}" alt="">
    </div>
    <div class="authent-text">       
        <p>{{ __('Ingresar tu numero de cedula') }}</p>
    </div>

    <div>
        <div class="mb-3">
            <div class="form-floating">
                <input type="number" class="form-control @error('identificador') is-invalid @enderror" id="identificador" placeholder="Ingrese cedula" wire:keydown.enter="validarr" wire:model.lazy="identificador"  wire:target="validarr" wire:loading.attr="disabled">
                <label for="identificador">Idenficador</label>
                @error('identificador')
                    <div class="invalid-feedback ">{{ $message }}  </div>
                @enderror
              </div>
        </div>
       
        <div class="d-grid">
            <button class="btn btn-primary" wire:click="validarr" wire:target="validarr" wire:loading.attr="disabled">{{ __('Buscar') }}</button>
        </div>
    </div>
    <div>
        <ul class="my-3">
            <li>{{ __('Debes ingresar el numero de cedula de la persona que realizo la compra') }}</li>
        </ul>
    </div>

    <script>
        window.addEventListener('errores', event => {
            let timerInterval
            Swal.fire({
                icon :'error',
                title: '¡Error! ',
                text: event.detail.error,
                timer: 3000,
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
