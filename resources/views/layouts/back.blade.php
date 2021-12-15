<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('css/back.css')}}">
    <title>@yield('title')</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/admin') }}">
                        {{ Auth::user()->affiliate?Auth::user()->affiliate->name:config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('admin/item-lists') }}">Listas</a></li>
                        <li class="dropdown" >
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sliders <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('admin/sliders/0') }}" data-toggle='modal-dinamic'>HOME</a></li>
                                <li><a href="{{ url('admin/sliders/1') }}" data-toggle='modal-dinamic'>TOURS</a></li>
                                <li><a href="{{ url('admin/sliders/2') }}" data-toggle='modal-dinamic'>TRANSFERS</a></li>
                                <li><a href="{{ url('admin/sliders/3') }}" data-toggle='modal-dinamic'>OFFERS</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ url('admin') }}">Reservaciones</a></li>
                        <li><a href="{{ url('admin/tours') }}">Tours</a></li>
                        <li><a href="{{ url('admin/destinations') }}">Destinos</a></li>
                        @if (Gate::allows('users'))
                            <li><a href="{{ url('admin/affiliates') }}">Afiliados</a></li>
                            <li><a href="{{ url('admin/users') }}">Usuarios</a></li>
                            <li><a href="{{ url('admin/transport-services') }}">Transportación</a></li>
                        @endif
                        <li><a href="{{ url('admin/settings') }}">Configuraciones</a></li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="container well">
        @yield('content')
    </div>
    <div id='warning-modal' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-body">
            <h5>¿Esta seguro que desea continuar?</h5>       
            <p class='extra-message'></p>       
        </div>
        <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
             <a href='#' class="btn btn-primary action-button" >Continuar</a>
        </div>                                        
    </div>
    <!-- js app -->
    <script src="{{asset('assets/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('assets/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/jquery-form/dist/jquery.form.min.js')}}"></script>
    <script src="{{asset('assets/bootstrap-table/dist/bootstrap-table.min.js')}}"></script>
    <script src="{{asset('assets/bootstrap-modal/js/bootstrap-modal.js')}}"></script>
    <script src="{{asset('assets/bootstrap-modal/js/bootstrap-modalmanager.js')}}"></script>
    <script src="{{asset('assets/trumbowyg/dist/trumbowyg.min.js')}}"></script>
    <script src="{{asset('assets/alertify-js/build/alertify.min.js')}}"></script>
    <script src="{{asset('js/tools.js')}}"></script>
    <script src="{{asset('js/back.js')}}"></script>
    @yield('script')
</body>
</html>