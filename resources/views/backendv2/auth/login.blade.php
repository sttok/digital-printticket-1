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
        <title>{{\App\Models\Setting::find(1)->app_name}} | {{ __('Panel de control') }}</title>
        <link href="{{ asset(route('inicio.frontend') .'/storage/public/'.\App\Models\Setting::find(1)->favicon)}}" rel="icon" type="image/png">

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('backendv2/plugins/font-awesome/css/all.min.css') }}" rel="stylesheet">
    </head>
    <body class="login-page">
        <div class='loader'>
            <div class='spinner-grow text-primary' role='status'>
              <span class='sr-only'>{{ __('Cargando') }}...</span>
            </div>
          </div>
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-12 col-lg-4">
                    <div class="card login-box-container">
                        <div class="card-body">
                            <div class="authent-logo">
                                <img src="{{ asset(route('inicio.frontend') .'/storage/public/'.$data['logo']) }}" alt="">
                            </div>
                            <div class="authent-text">
                                <p>{{ __('Bienvenido a') . ' ' . $data['titulo'] }} </p>
                                <p>{{ __('Inicie sesión en su cuenta') }}.</p>
                            </div>

                            <form action="{{ route('iniciar.post') }}" method="POST">
                                @csrf
                                <div class="mb-3" style="display: flex">
                                    <div class="col-4" style="margin-right: 16px;">                                       
                                        <label class="visually-hidden" for="prefijo">{{ __('Prefijo') }}</label>
                                        <select class="form-select @error('prefijo') is-invalid @enderror" id="prefijo" name="prefijo" style="height: 51px" required>
                                            <option selected="">{{ __('Seleccione') }}...</option>
                                            <option value="+57">+57 COL</option>
                                            <option value="+1">+1 EEUU</option>
                                            <option value="+56">+56 CL</option>
                                            <option value="+593">+593 ECU</option>
                                            <option value="+52">+52 MEX</option>
                                            <option value="+34">+34 ESP</option>
                                            <option value="+507">+507 PAN</option>
                                        </select>   
                                        @error('prefijo')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror                                     
                                    </div>
                                    <div class="col-8" style="padding-right: 16px">
                                        <div class="form-floating">
                                            <input type="tel" class="form-control @error('telefono') is-invalid @enderror @error('phone') is-invalid @enderror" id="telefono" name="telefono" placeholder="3101234567" value="{{ old('telefono') }}" required>
                                            <label for="telefono">{{ __('Telefono') }}</label>
                                            @error('telefono')
                                                <div class="invalid-feedback ">{{ $message }}  </div>
                                            @enderror
                                            @error('phone')
                                                <div class="invalid-feedback ">{{ $message }}  </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ __('Tu contraseña') }}" value="{{ old('contraseña') }}" required >
                                        <label for="password">{{ __('Contraseña') }}</label>
                                        @error('password')
                                            <div class="invalid-feedback ">{{ $message }}  </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">{{ __('Recuedame') }}</label>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-info m-b-xs">{{ __('Iniciar Sesion') }}</button>

                                    <hr> 

                                    <div class="text-center justify-content-center" >
                                        <a href="{{ route('login.google') }}"> <img src="{{ asset('backendv2/images/iniciar-google.png') }}" alt="" style="max-width: 200px"> </a>
                                    </div>
                                    
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>          
        </div>         
        
        <!-- Javascripts -->
        <script src="{{ asset('backendv2/plugins/jquery/jquery-3.4.1.min.js') }}"></script>
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="{{ asset('backendv2/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="https://unpkg.com/feather-icons"></script>
        <script src="{{ asset('backendv2/plugins/perfectscroll/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('backendv2/js/main.min.js') }}"></script>
    </body>
</html>
    
