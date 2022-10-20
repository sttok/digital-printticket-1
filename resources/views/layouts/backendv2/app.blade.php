<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="robots" content="noindex, follow" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Panel de control para uso de printticket v2">
        <meta name="keywords" content="admin,dashboard">
        <meta name="author" content="Sttok Publicidad">

        <!-- Title -->
        <title>{{\App\Models\Setting::find(1)->app_name}} | {{ __('Digital') }}</title>

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
        
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
     
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        @livewireStyles
        @laravelPWA
        @yield('css')
    </head>
    <body class="sidebar-hidden" >   
        <div class='loader '>
            <div class='spinner-grow text-primary' role='status'>
            <span class='sr-only'>{{ __('Cargando') }}...</span>
            </div>
        </div>
        @include('layouts.backendv2.sidebarheader')
        @include('layouts.backendv2.sidebar')
        <div class="page-content">
            <div class="main-wrapper">
                @yield('contenido')
                @include('layouts.backendv2.toastinstall')
            </div>
        </div>    
        <!-- Javascripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://rawgit.com/sitepoint-editors/jsqrcode/master/src/qr_packed.js"></script>
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="{{ asset('backendv2/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="https://unpkg.com/feather-icons"></script>
        <script src="{{ asset('backendv2/plugins/perfectscroll/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('backendv2/js/main.min.js') }}"></script>
        <script src="{{ asset('backendv2/js/blazy.min.js') }}"></script>
        <script src="{{ asset('backendv2/js/custom.js') }}"></script>
        @livewireScripts
        @yield('js')
        @yield('jss')
        <script>
            ;(function() {
                // Initialize
                var bLazy = new Blazy();
            })();
        </script>

    </body>
</html>