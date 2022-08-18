<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="administrador, printticket">
        <meta name="keywords" content="admin,ticket, evento">
        <meta name="author" content="sttok publicidad">
        <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        
        <!-- Title -->
        <title>{{ __('Sin conexion') }} | {{ __('Panel de control') }}</title>
        <link href="{{ asset(route('inicio.frontend') .'/storage/public/'.\App\Models\Setting::find(1)->favicon)}}" rel="icon" type="image/png">

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
        <link href="{{ asset('backendv2/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('backendv2/plugins/font-awesome/css/all.min.css') }}" rel="stylesheet">
        <link href="{{ asset('backendv2/plugins/perfectscroll/perfect-scrollbar.css') }}" rel="stylesheet">
      
        <!-- Theme Styles -->
        <link href="{{ asset('backendv2/css/main.css') }}" rel="stylesheet">
        <link href="{{ asset('backendv2/css/dark-theme.css') }}" rel="stylesheet">
        <link href="{{ asset('backendv2/css/custom.css') }}" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        @livewireStyles
        @laravelPWA
        @yield('css')
       
    </head>
    <body class="error-page err-404">
        <div class='loader'>
            <div class='spinner-grow text-primary' role='status'>
              <span class='sr-only'>Loading...</span>
            </div>
          </div>
        <div class="container">
            <div class="error-container">
                <div class="error-info">
                    <h1>¡{{ __('Sin conexion') }}!</h1>
                    <p>Ooopss... {{ __('Algo salió mal') }}<br>{{ __('Intente conectarse a internet y actualiza la página ') }} </p>
                </div>
                <div class="error-image"></div>
            </div>
        </div>
         
        
        <script src="{{ asset('backendv2/plugins/jquery/jquery-3.4.1.min.js') }}"></script>
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="{{ asset('backendv2/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="https://unpkg.com/feather-icons"></script>
        <script src="{{ asset('backendv2/plugins/perfectscroll/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('backendv2/js/main.min.js') }}"></script>
        <script src="{{ asset('backendv2/js/blazy.min.js') }}"></script>
        @livewireScripts
        @yield('js')
    </body>
</html>