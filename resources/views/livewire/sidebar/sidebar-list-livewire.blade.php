<div class="file-manager-menu">
    <span class="fmm-title">{{ __('Mis entradas') }}</span>
    <style>
      .active-folder{
          background: #04a1f6;
          border-radius: 10px;
          color: white;
      }
      .ml-3{
          margin-left: 3rem;
      }
    </style>
      <ul class="list-unstyled">
          <li class="{{ (request()->is('mis-eventos')) ? 'active-folder' : '' }}">
              <a href="{{ route('mis.eventos') }}"><i class="fas fa-folder{{ (request()->is('mis-eventos')) ? '-open' : '' }}"></i>{{ __('Todos los eventos') }}</a>
          </li>
          @foreach ($this->Folders as $event)
              @if ($event['estado'] == 1)
                  <li class="{{ (request()->is('mis-eventos/entradas/'. $event['id'])) ? 'active-folder' : '' }} li-folder">
                      <a href="{{ route('mis.eventos.show', $event['id']) }}"><i class="fas fa-folder{{ (request()->is('mis-eventos/entradas/'. $event['id'])) ? '-open' : '' }}" style="font-size: 16px"></i>{{ $event['nombre'] }}  </a>
                  </li>
                  @if(count($event['entradas']))
                    <ul class="list-unstyled ml-3">
                        @foreach ($event['entradas'] as $ent)
                            <li>
                                <a href="{{ route('mis.eventos.show', ['id' => $event['id']]) }}?zona={{ $ent->id }}" ><i class="fas fa-ticket-alt" style="color: {{ $ent->color_localidad }}"></i> &nbsp; &nbsp; {{$ent->name}}</a> 
                            </li>
                        @endforeach
                    </ul>
                  @endif
                    
                        
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
