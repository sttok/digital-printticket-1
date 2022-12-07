<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, follow" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Administracion de mis entrada para uso de printticket v2">
        <meta name="keywords" content="admin,dashboard">
        <meta name="author" content="Sttok Publicidad">
        <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        
        <!-- Title -->
        <title>{{\App\Models\Setting::find(1)->app_name}} | {{ __('Administracion entradas') }}</title>
        <link href="{{ asset(route('inicio.frontend') .'/images/upload/'.\App\Models\Setting::find(1)->favicon)}}" rel="icon" type="image/png">

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('backendv2/plugins/font-awesome/css/all.min.css') }}" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        @livewireStyles        
        @yield('css')
    </head>
    <body class="{{ request()->routeIs('ver.archivo') ? 'login-page' : '' }}">
        <div class='loader'>
            <div class='spinner-grow text-primary' role='status'>
              <span class='sr-only'>{{ __('Cargando') }}...</span>
            </div>
        </div>
        <style>
            .section{
                background-color: #f5f5f5 !important;
            }
            .background-custom ::after{
                content: "";
                left: 0;
                top: 0;
                right: 0;
                bottom: 0;
                background: rgba(67,67,67, 0.01);
                position: absolute;
                z-index: 2;
            }
            .order-2{
                position: relative;
            }
            .order-2 .div-login-custom{
                position: absolute;
                top: 25%;
                left: 30%;
                height: 50%;
                margin: -15% 0 0 -25%;
            }
            .btn-primary:hover{
                background-color: #a8b2f7 !important;
                border-color: #a9b3fd !important;
                color: #fefefe !important;
                box-shadow: 0 7px 23px -8px #7d7d7d !important;
            }
            .px-0{
                padding-right: 0px !important;
                padding-left: 10px !important;
            }
            .form-control:hover{
                 box-shadow: 0 7px 23px -9px #7d7d7d !important;
            }
            .form-select:hover{
                 box-shadow: 0 7px 23px -9px #7d7d7d !important;
            }
        </style>
      
        <section class="section">
            @yield('contenido')
        </section>
    
        <!-- Javascripts -->
        <script src="{{ asset('backendv2/plugins/jquery/jquery-3.4.1.min.js') }}"></script>
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="{{ asset('backendv2/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="https://unpkg.com/feather-icons"></script>
        <script src="{{ asset('backendv2/plugins/perfectscroll/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('backendv2/js/main.min.js') }}"></script>
        @livewireScripts
    </body>
</html>
    
