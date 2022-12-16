<div class="page-container">
    <div class="page-header">
    <nav class="navbar navbar-expand-lg d-flex justify-content-between">
        <div class="" id="navbarNav">
            <ul class="navbar-nav" id="leftNav">
                @if (!request()->is('mis-eventos'))
                    <li class="nav-item">
                        <a class="nav-link" id="sidebar-toggle" href="#"><i data-feather="menu"></i></a>
                    </li>
                @endif
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('inicio.frontend')  }}">{{ __('Inicio') }}</a>
                </li>

                <li>
                    <a class="nav-link" href="https://play.google.com/store/apps/details?id=co.printticket.readerticket&hl=es_CO&gl=US" >
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
                    <a class="nav-link" href="{{ asset(route('inicio.frontend') .'/storage/public/'.\App\Models\Setting::find(1)->modo_uso_digital)}}" target="_blank" rel="noopener noreferrer">
                        @desktop
                            {{ __('Modo de uso') }}
                        @elsedesktop
                            <i class="fas fa-hands-helping text-primary" style="font-size: 22px"></i>
                        @enddesktop
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ asset(route('inicio.frontend') .'/storage/public/'.\App\Models\Setting::find(1)->modo_uso_digital)}}" target="_blank" rel="noopener noreferrer">
                        @desktop
                            {{ __('Procesos de seguridad') }}
                        @elsedesktop
                            <i class="fas fa-id-card-alt text-primary" style="font-size: 22px"></i>
                        @enddesktop
                    </a>
                </li>
            
                
                <li class="nav-item dropdown">
                    <a class="nav-link profile-dropdown" href="#" id="profileDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ route('inicio.frontend').('/storage/perfil/'.Auth::user()->image) }}" alt=""></a>
                    <div class="dropdown-menu dropdown-menu-end profile-drop-menu" aria-labelledby="profileDropDown">
                        <a class="dropdown-item" href="javascript: void(0)">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"><i data-feather="log-out"></i>{{ __('Cerrar Sesion') }}</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>