<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, follow" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Panel de control para uso de printticket v2">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="Sttok Publicidad">
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title>{{ \App\Models\Setting::find(1)->app_name }} | {{ __('Panel de control') }}</title>
    <link href="{{ asset(route('inicio.frontend') . '/images/upload/' . \App\Models\Setting::find(1)->favicon) }}"
        rel="icon" type="image/png">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @laravelPWA
</head>

<body class="login-page">
    <style>
        .section {
            background-color: #f5f5f5 !important;
        }

        .background-custom ::after {
            content: "";
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            background: rgba(67, 67, 67, 0.01);
            position: absolute;
            z-index: 2;
        }

        .order-2 {
            position: relative;
        }

        .order-2 .div-login-custom {
            position: absolute;
            top: 25%;
            left: 30%;
            height: 50%;
            margin: -15% 0 0 -25%;
        }

        .btn-primary:hover {
            background-color: #a8b2f7 !important;
            border-color: #a9b3fd !important;
            color: #fefefe !important;
            box-shadow: 0 7px 23px -8px #7d7d7d !important;
        }

        .px-0 {
            padding-right: 0px !important;
            padding-left: 10px !important;
        }

        .form-control:hover {
            box-shadow: 0 7px 23px -9px #7d7d7d !important;
        }

        .form-select:hover {
            box-shadow: 0 7px 23px -9px #7d7d7d !important;
        }
    </style>

    <div class='loader'>
        <div class='spinner-grow text-primary' role='status'>
            <span class='sr-only'>{{ __('Cargando') }}...</span>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-12 col-lg-4">
                <div class="authent-logo">
                    <img src="{{ asset(route('inicio.frontend') . '/images/upload/' . $data['logo']) }}" alt="">
                    <p class="text-white">Bienvenidos a <span
                            class="font-weight-bold">{{ \App\Models\Setting::find(1)->app_name }}</span></p>
                </div>
                <div class="card login-box-container">
                    <div class="card-body">
                        <form method="POST" action="{{ route('iniciar.post') }}" class="needs-validation "
                            novalidate="">
                            @csrf
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="d-block">
                                <label for="password" class="control-label">{{ __('Celular o Móvil') }}</label>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="col-md-4 col-4 ">
                                    <select class="form-select @error('prefijo') is-invalid @enderror" id="prefijo"
                                        name="prefijo" required value="{{ old('prefijo') }}">
                                        <option value="+57" selected>+57 COL</option>
                                        <option value="+1">+1 EEUU</option>
                                        <option value="+56">+56 CL</option>
                                        <option value="+593">+593 ECU</option>
                                        <option value="+52">+52 MEX</option>
                                        <option value="+34">+34 ESP</option>
                                        <option value="+507">+507 PAN</option>
                                    </select>
                                    @error('prefijo')
                                        <div class="invalid-feedback ">{{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="col-md-8 col-8">
                                    <input type="tel"
                                        class="form-control @error('telefono') is-invalid @enderror @error('phone') is-invalid @enderror"
                                        name="telefono" id="telefono" tabindex="1" required autofocus
                                        value="{{ old('telefono') }}">
                                    @error('telefono')
                                        <div class="invalid-feedback ">{{ $message }} </div>
                                    @enderror
                                    @error('phone')
                                        <div class="invalid-feedback ">{{ $message }} </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-block">
                                <label for="password" class="control-label">{{ __('Password') }}</label>
                            </div>
                            <div class=" mb-3">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    tabindex="2" required>
                                @error('password')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>


                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="remember" tabindex="3"
                                    id="remember" value="{{ old('remember') }}">
                                <label class="form-check-label" for="exampleCheck1">{{ __('Recuerdame') }}</label>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-info m-b-xs"> {{ __('Log in') }}</button>
                            </div>
                            <div class="d-block my-2">
                                <small>Copyright © {{ Carbon\Carbon::now()->isoFormat('Y') }}. Hecho con ❤️ de Sttok
                                    Publicidad.</small>
                            </div>
                            <div class="d-block my-2">
                                @include('alertas.alerta')
                                @include('alertas.session')
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- <section class="section" hidden>
        <div class="d-flex flex-wrap">
            <div class="col-lg-8 d-none d-md-none d-lg-block col-12 order-lg-1 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom background-custom"
                style="background-repeat: no-repeat;background-size: contain;background-image: url({{ asset(route('inicio.frontend') . '/images/upload/' . $data['fondo']) }});">
                <div class="absolute-bottom-left index-2">
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-12 order-lg-1 min-vh-100 order-2">
                <div class="p-5 justify-content-center div-login-custom">
                    <img src="{{ asset(route('inicio.frontend') . '/images/upload/' . $data['logo']) }}" alt="logo"
                        width="120" class="login-logo my-4 justify-content-center" style=" border-radius: 0.5rem">
                    <h4 class=" font-weight-normal mb-4">Bienvenidos a <span
                            class="font-weight-bold">{{ \App\Models\Setting::find(1)->app_name }}</span></h4>

                    <form method="POST" action="{{ route('iniciar.post') }}" class="needs-validation"
                        novalidate="">
                        @csrf
                        <div class="form-group mb-4 row">
                            <label class="" for="telefono">{{ __('Phone') }}</label>
                            <div class="col-md-4 col-4 px-0">
                                <select class="form-select @error('prefijo') is-invalid @enderror" id="prefijo"
                                    name="prefijo" required value="{{ old('prefijo') }}">
                                    <option value="+57" selected>+57 COL</option>
                                    <option value="+1">+1 EEUU</option>
                                    <option value="+56">+56 CL</option>
                                    <option value="+593">+593 ECU</option>
                                    <option value="+52">+52 MEX</option>
                                    <option value="+34">+34 ESP</option>
                                    <option value="+507">+507 PAN</option>
                                </select>
                                @error('prefijo')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>
                            <div class="col-md-8 col-8">
                                <input type="tel"
                                    class="form-control @error('telefono') is-invalid @enderror @error('phone') is-invalid @enderror"
                                    name="telefono" id="telefono" tabindex="1" required autofocus
                                    value="{{ old('telefono') }}">
                                @error('telefono')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                                @error('phone')
                                    <div class="invalid-feedback ">{{ $message }} </div>
                                @enderror
                            </div>

                        </div>

                        <div class="form-group mb-4">
                            <div class="d-block">
                                <label for="password" class="control-label">{{ __('Password') }}</label>
                            </div>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                tabindex="2" required>
                            @error('password')
                                <div class="invalid-feedback ">{{ $message }} </div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="remember" class="custom-control-input" tabindex="3"
                                    id="remember" value="{{ old('remember') }}">
                                <label class="custom-control-label" for="remember">{{ __('Remember me') }}</label>
                            </div>
                        </div>

                        <div class="form-group text-right mb-4">
                            <button type="submit" class="btn btn-primary btn-block" tabindex="4">
                                {{ __('Log in') }}
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-5 text-small">
                        Copyright &copy; 2022. Hecho con ❤️ de Sttok Publicidad.

                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Javascripts -->
    <script src="{{ asset('backendv2/plugins/jquery/jquery-3.4.1.min.js') }}"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="{{ asset('backendv2/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backendv2/plugins/perfectscroll/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('backendv2/js/main.min.js') }}"></script>
</body>

</html>
