<div class="page-sidebar">
    <ul class="list-unstyled accordion-menu">
        <li class="sidebar-title">
            {{ __('Principal') }}
        </li>

        @can('event_edit')
            <li class="{{ (request()->is('eventos*')) ? 'active-page' : '' }}" >
                <a href="{{ route('index.eventos') }}" class=""><i data-feather="calendar"></i>{{ __('Admin eventos') }}</a>
            </li>
        @endcan

        @can('asignar_ticket')
            <li class="{{ (request()->is('mis-eventos*')) ? 'active-page' : '' }}" >
                <a href="{{ route('mis.eventos') }}" class=""><i data-feather="folder"></i>{{ __('Mis entradas') }}</a>
            </li>
        @endcan
        

    </ul>
</div>