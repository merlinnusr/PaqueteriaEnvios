<nav class="navbar navbar-expand-lg navbar-light " style="background-color: #01297C;">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('assets/images/logo1.png')}}" /> 
        </a>
        @auth
            <button class="navbar-toggler " type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="d-flex input-group w-auto ">
                <div class="collapse navbar-collapse text-white" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('home')}}">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" target="_blank" href="http://dagpacket.com.mx#ubicaciones">Ver sucursales</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('picking.index') }}">Entrega de paquete local</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('logs.index')}}">Movimientos</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="#">Saldo: {{get_balance()}}</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('payment_report.index')}}">Reportar pago</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('pos.index')}}">Pago de servicios</a>
                        </li>
                        <!-- Navbar dropdown -->
                        <li class="nav-item dropdown dropdown-menu-right"> 
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                                data-mdb-toggle="dropdown" aria-expanded="false">
                                Usuario
                            </a>
                            <!-- Dropdown menu -->
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown" style="left: -77px;">

                                <li>
                                    <a class="dropdown-item" href="{{ route('auth.logout') }}">Cerrar sesión</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

            </div>
        @endauth
    </div>
</nav>
