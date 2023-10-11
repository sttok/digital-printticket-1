<div class="row">
    <style>
        .folder1 {
            color: rgba(225, 235, 245, .87);
        }

        .folder1:hover {
            color: rgba(255, 255, 255, 0.87);
        }

        .card.folder:hover {
            transform: scale(1.04);
            transition: all .3s ease-in-out;
        }

        .list-unstyled .li-folder:hover {
            border-radius: 10px;
            margin-bottom: 5px;
            margin-top: 5px
        }

        .imagen-new {
            background-size: cover;
            position: fixed;
            height: 100%;
            margin-left: -10px;
            border-bottom-left-radius: 10px;
            border-top-left-radius: 10px;
        }
    </style>
    <div class="col-sm-12 col-md-12">
        <div class="row">
            <div class="col-12">
                <div class="mb-3 col-12 col-md-4 row">
                    <div class="col-12 col-md-6 mb-2">
                        <button class="btn btn-{{ $status == 1 ? 'success' : 'secondary' }} btn-block"
                            wire:click="changeEstado('1')" wire:loading.attr="disabled">

                            <h4 class="m-b-md m-t-md">{{ __('Eventos disponibles') }}</h4>

                        </button>
                    </div>
                    <div class="col-12 col-md-6 mb-2">
                        <button class="btn btn-{{ $status == 0 ? 'success' : 'secondary' }} btn-block"
                            wire:click="changeEstado('0')" wire:loading.attr="disabled">

                            <h4 class="m-b-md m-t-md">{{ __('Historial eventos') }}</h4>

                        </button>
                    </div>
                </div>
            </div>


            @forelse ($this->Folders as $event)
                @desktop
                    <div class="col-md-4 col-12">
                        <div class="card folder {{ $event['estado'] != 1 ? 'folder-disabled' : '' }}">
                            @if ($event['estado'] == 1)
                                <div class="card-body" style="cursor:pointer"
                                    wire:click="abrirentradas('{{ $event['id'] }}')"
                                    wire:key="card-evento-{{ $event['id'] }}">
                                    <a class="folder1" href="#">
                                    @else
                                        <div class="card-body" wire:key="card-evento-{{ $event['id'] }}">
                            @endif
                            <div class="folder-icon">
                                @if ($event['estado'] == 1)
                                    <img src="{{ asset(route('inicio.frontend') . '/images/upload/' . $event['imagen']) }}"
                                        alt="img-evento">
                                @else
                                    <img src="{{ asset(route('inicio.frontend') . '/images/upload/' . $event['imagen']) }}"
                                        alt="img-evento">
                                @endif
                            </div>
                            <div class="col-md-8 col-6 folder-info text-center justify-content-center">
                                @if ($event['estado'] == 1)
                                    <a href="#">
                                        <h4 class="text" style="color: #091763">{{ $event['nombre'] }}</h4>
                                    </a>
                                @else
                                    <a href="#" style="cursor: default">
                                        <h4 class="text" style="color: #091763">{{ $event['nombre'] }}</h4>
                                    </a>
                                @endif
                                <h6 style="color: #091763">
                                    {{ Str::ucfirst(Carbon\Carbon::create($event['fecha'])->isoFormat('LLLL')) }}</h6>
                                <h3 style="color: #00C587">{{ __('Digitales') }}</h3>
                                <span style="color: #091763">{{ number_format($event['contador'], 0, ',', '.') }}
                                    {{ __('entradas') }} /
                                    {{ number_format($event['total'], 0, ',', '.') . ' entradas' }}</span>
                            </div>
                            @if ($event['estado'] == 1)
                                </a>
                            @endif
                        </div>
                    </div>
            </div>
        @elsedesktop
            <div class="col-12">
                <div class="card folder {{ $event['estado'] != 1 ? 'folder-disabled' : '' }}">
                    @if ($event['estado'] == 1)
                        <div class="card-body row" style="cursor:pointer; padding:0px"
                            wire:click="abrirentradas('{{ $event['id'] }}')" wire:key="card-evento-{{ $event['id'] }}">
                            <a class="folder1 col-6" href="#"
                                style="overflow: hidden;
                                overflow-y: auto;
                                perspective: 3px;">
                            @else
                                <div class="card-body row" wire:key="card-evento-{{ $event['id'] }}"
                                    style="overflow: hidden;  padding:0px; overflow-y: auto; perspective: 3px;">
                    @endif

                    @if ($event['estado'] == 1)
                        <img class="imagen-new"
                            src="{{ asset(route('inicio.frontend') . '/images/upload/' . $event['imagen']) }}"
                            alt="img-evento" style="">
                    @else
                        <div class="col-6">
                            <img class="imagen-new col-6 "
                                src="{{ asset(route('inicio.frontend') . '/images/upload/' . $event['imagen']) }}"
                                alt="img-evento" style="">
                        </div>
                    @endif

                    <div class="col-6 folder-info text-center justify-content-center"
                        style="margin-left: 0px; padding: 30px 20px">
                        @if ($event['estado'] == 1)
                            <a href="#">
                                <h4 class="text" style="color: #091763">{{ $event['nombre'] }}</h4>
                            </a>
                        @else
                            <a href="#" style="cursor: default">
                                <h4 class="text" style="color: #091763">{{ $event['nombre'] }}</h4>
                            </a>
                        @endif
                        <h6 style="color: #091763">
                            {{ Str::ucfirst(Carbon\Carbon::create($event['fecha'])->isoFormat('LLLL')) }}</h6>
                        <h3 style="color: #00C587">{{ __('Digitales') }}</h3>
                        <span style="color: #091763">{{ number_format($event['contador'], 0, ',', '.') }}
                            {{ __('entradas') }} / {{ number_format($event['total'], 0, ',', '.') . ' entradas' }}</span>
                    </div>
                    @if ($event['estado'] == 1)
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @enddesktop
@empty
    <div class="col-12 mb-3 text-center justify-content-center">
        <h4>¡{{ __('No tiene eventos disponibles') }}!</h4>
    </div>
    @endforelse
</div>
</div>
</div>
