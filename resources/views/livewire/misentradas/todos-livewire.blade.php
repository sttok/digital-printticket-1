<div class="row">
    <style>
        .folder1{
            color: rgba(225, 235, 245, .87);
        }
        .folder1:hover{
            color: rgba(255, 255, 255, 0.87);
        }
        .card.folder:hover{
            background-color: #04a1f6;
        }
        .list-unstyled .li-folder:hover{
            background-color: #04a1f6;
            border-radius: 10px;
            margin-bottom: 5px;
            margin-top: 5px
        }
    </style>
    <div class="col-sm-12 col-md-2">
        @livewire('sidebar.sidebar-list-livewire')
    </div>
    <div class="col-sm-12 col-md-10">
        <div class="row">
            <h4 class="m-b-md m-t-md">{{ __('Carpetas') }}</h4>
            @forelse ($this->Folders as $event)
                <div class="col-md-4 col-6">
                    <div class="card folder">
                        @if ($event['estado'] == 1)
                            <div class="card-body" style="cursor:pointer" wire:click="abrirentradas('{{ $event['id'] }}')">
                            <a class="folder1" href="#" >
                        @else
                            <div class="card-body" >
                        @endif
                            <div class="folder-icon">
                                @if ($event['estado'] == 1)
                                    <i class="fas fa-folder"></i>
                                @else
                                    <i class="far fa-folder"></i>
                                @endif
                            </div>
                            <div class="folder-info">
                                @if ($event['estado'] == 1)
                                    <a href="#">{{ $event['nombre'] }}</a>
                                @else
                                    <a href="#" style="cursor: default">{{ $event['nombre'] }}</a>
                                @endif
                                <span>{{ number_format($event['contador'], 0 ,',','.') }} {{ __('archivos') }} / {{ number_format($event['total'], 0 ,',','.') . ' archivos'}}</span>
                            </div>
                            @if ($event['estado'] == 1)
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 mb-3 text-center justify-content-center">
                    <h4>¡{{ __('No tiene eventos disponibles') }}!</h4>
                </div>
            @endforelse
        </div>
    </div>
</div>
