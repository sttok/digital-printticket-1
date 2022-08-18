<div class='loader'>
    <div class='spinner-grow text-primary' role='status'>
    <span class='sr-only'>{{ __('Cargando') }}...</span>
    </div>
</div>
@include('layouts.backendv2.sidebarheader')
@include('layouts.backendv2.sidebar')
<div class="page-content">
    <div class="main-wrapper">
        @yield('contenido')
    </div>
</div>    