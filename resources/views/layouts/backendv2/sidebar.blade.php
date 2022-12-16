<div class="page-sidebar">
    <ul class="list-unstyled accordion-menu">
        <li class="sidebar-title">
            {{ __('Principal') }}
        </li>
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('Superadmin'))
             <li class="{{ (request()->is('eventos*')) ? 'active-page' : '' }}" >
                <a href="{{ route('index.eventos') }}" class=""><i data-feather="calendar"></i>{{ __('Admin eventos') }}</a>
            </li>
        @endif

        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('Superadmin') || Auth::user()->hasRole('organization'))
            <li class="active-page open">
                <a href="#" class="active">
                    <i data-feather="folder"></i>{{ __('Mis entradas') }}<i class="fas fa-chevron-right dropdown-icon"></i>
                </a>
                <ul class="">                    
                    @livewire('misentradas.nuevo.reporte-livewire')
                </ul>
            </li>
        @endif
    </ul>
</div>