<div class="position-fixed bottom-0 start-50 translate-middle-x  z-index-9">
    <div class="toast mb-3" role="alert" aria-live="assertive" aria-atomic="true" id="toastinstall" data-bs-animation="true">
        <div class="toast-header">
          <img src="{{ asset('storage/public/icon-72x72.png') }}" class="m-r-sm" alt="Toast image" height="18" width="18">
          <strong class="me-auto">{{ __('Instalar app') }}</strong>
          <small class="text-muted">{{ __('Ahora') }}</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <div class="row">
                <div class="col">
                    {{ __('Haga clic en "Instalar" para instalar la aplicación PWA y experimentar como una aplicación independiente.') }}
                </div>
                <div class="col-auto align-self-center">
                    <button class="btn-primary btn btn-sm" id="addtohome">{{ __('Instalar') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>