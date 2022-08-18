<div class="file-manager-menu">
    <span class="fmm-title">{{ __('Mis entradas') }}</span>
    <style>
      .active-folder{
          background: #04a1f6;
          border-radius: 10px;
          color: white;
      }
    </style>
      <ul class="list-unstyled">
          <li class="{{ (request()->is('mis-eventos')) ? 'active-folder' : '' }}">
              <a href="{{ route('mis.eventos') }}"><i class="fas fa-folder{{ (request()->is('mis-eventos')) ? '-open' : '' }}"></i>{{ __('Todos los eventos') }}</a>
          </li>
          @foreach ($this->Folders as $event)
              @if ($event['estado'] == 1)
                  <li class="{{ (request()->is('mis-eventos/entradas/'. $event['id'])) ? 'active-folder' : '' }} li-folder">
                      <a href="{{ route('mis.eventos.show', $event['id']) }}"><i class="fas fa-folder{{ (request()->is('mis-eventos/entradas/'. $event['id'])) ? '-open' : '' }}" style="font-size: 16px"></i>{{ $event['nombre'] }}</a>
                  </li>
              @endif
          @endforeach
          @foreach ($this->Folders as $event)
              @if ($event['estado'] != 1)
                  <li>
                      <a href="#"><i class="far fa-folder"></i>{{ $event['nombre'] }}</a>
                  </li>
              @endif
          @endforeach
      </ul>
</div>
