<div class="page-container">
    <div class="page-header">
    <nav class="navbar navbar-expand-lg d-flex justify-content-between">
        <div class="" id="navbarNav">
            <ul class="navbar-nav" id="leftNav">
                <li class="nav-item">
                    <a class="nav-link" id="sidebar-toggle" href="#"><i data-feather="arrow-left"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('inicio.frontend')  }}">{{ __('Inicio') }}</a>
                </li>

                <li class="nav-item ml-2" style="padding: 5px 0;">
                    <button class="darkModeSwitch" id="switch">
                        <span><i class="fas fa-sun"></i></span>   
                        <span><i class="fas fa-moon"></i></span>
                    </button>
                </li>
                <li>
                    <a class="nav-link" href="#" >
                        <img class="img-fluid" src="{{ asset('images/googleplay.png') }}" style="width: 120px;margin-top: -10px;" >
                    </a>
                </li>
            </ul>
        </div>
        <div class="logo">
            @desktop
                <a class="navbar-brand nav-brand-claro" style="background: url({{ asset(route('inicio.frontend') .'/images/upload/'.\App\Models\Setting::find(1)->logo)}}) center center no-repeat" href="{{ route('index.eventos') }}"></a>
                <a class="navbar-brand nav-brand-oscuro" style="background: url({{ asset(route('inicio.frontend') .'/images/upload/'.\App\Models\Setting::find(1)->logo_oscuro)}}) center center no-repeat" href="{{ route('index.eventos') }}"></a>
            @elsedesktop
                <a class="navbar-brand" style="background: url({{ asset(route('inicio.frontend') .'/images/upload/'.\App\Models\Setting::find(1)->favicon)}}) center center no-repeat  " href="{{ route('index.eventos') }}"></a>
            @enddesktop
           
        </div>
        <div class="" id="headerNav">
            <ul class="navbar-nav">
               <li class="nav-item">
                    <a class="nav-link" href="#">{{ __('Modo de uso') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ __('Procesos de seguridad') }}</a>
                </li>
            <li class="nav-item dropdown">
                <a class="nav-link notifications-dropdown" href="#" id="notificationsDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false">1</a>
                <div class="dropdown-menu dropdown-menu-end notif-drop-menu" aria-labelledby="notificationsDropDown">
                    <h6 class="dropdown-header">{{ __('Notificaciones') }}</h6>
                    <a href="#">
                        <div class="header-notif">
                        <div class="notif-image">
                            <span class="notification-badge bg-info text-white">
                            <i class="fas fa-bullhorn"></i>
                            </span>
                        </div>
                        <div class="notif-text">
                            <p class="bold-notif-text">{{ __('Proximamente') }}</p>
                            <small>05-05-22</small>
                        </div>
                        </div>
                    </a>               
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link profile-dropdown" href="#" id="profileDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ route('inicio.frontend').('/storage/perfil/'.Auth::user()->image) }}" alt=""></a>
                <div class="dropdown-menu dropdown-menu-end profile-drop-menu" aria-labelledby="profileDropDown">
                    {{-- <a class="dropdown-item" href="{{ route('create.evento') }}"><i data-feather="plus"></i>{{ __('Crear evento') }}</a>
                    <a class="dropdown-item" href="{{ route('index.ubicaciones') }}"><i data-feather="map-pin"></i>{{ __('Ubicaciones') }}</a>
                    <a class="dropdown-item" href="#"><i data-feather="shopping-bag"></i>{{ __('Pedidos') }}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('index.estadisticas.web') }}"><i data-feather="trending-up"></i>{{ __('Estadisticas visita') }}</a>
                    <a class="dropdown-item" href="{{ route('index.configuracion') }}"><i data-feather="settings"></i>{{ __('Configuracion') }}</a> --}}
                    <a class="dropdown-item" href="{{ route('logout') }}"><i data-feather="log-out"></i>{{ __('Cerrar Sesion') }}</a>
                </div>
            </li>
            </ul>
        </div>
    </nav>
</div>