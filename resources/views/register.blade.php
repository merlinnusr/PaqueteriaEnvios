@extends('layouts.app')
@section('content')

    <div ui-view="" class="ng-scope">
        <div class="row ng-scope">
            <div class="col-md-12">
                <div class="card pp-auth-panel">
                    <div class="card-body">
                        <div class="Singin">
                            <div>
                                <div class="container-fluid">
                                    <div class="row no-gutter">
                                        <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
                                        <div class="col-md-8 col-lg-6">
                                            <div class="login d-flex align-items-center py-5">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-md-9 col-lg-8 mx-auto">
                                                            <h3 class="login-heading mb-4">Bienvenido!</h3>
                                                            <form method="post" action="{{ route('auth.create') }}">
                                                                @csrf
                                                                <div class="form-label-group">
                                                                    <input type="text" id="name" name="name" class="form-control"
                                                                        placeholder="Nombre completo" required autofocus>
                                                                    <label for="name">Nombre completo</label>
                                                                </div>
                                                                <div class="form-label-group">
                                                                    <input type="email" id="inputEmail" name="email" class="form-control"
                                                                        placeholder="Correo Electronico" required autofocus autocomplete="disabled"/>
                                                                    <label for="inputEmail">Correo electronico</label>
                                                                </div>

                                                                <div class="form-label-group">
                                                                    <input type="password" id="password"
                                                                        class="form-control" name="password" placeholder="Contraseña"
                                                                        required>
                                                                    <label for="password">Contraseña</label>
                                                                </div>
                                                                <div class="form-label-group">
                                                                    <input type="password" id="password_confirmation"
                                                                        class="form-control" name="password_confirmation" placeholder="Contraseña"
                                                                        required>
                                                                    <label for="password_confirmation">Contraseña confirmación</label>
                                                                </div>
                                                                <div class="refencia form-label-group">
                                                                    <input name="referencia" type="text" class="form-control" id="referencia" placeholder="Referencia">
                                                                    <label for="referencia">Referencia (Opcional)</label>
                                                                </div>
                                                                <button name="login"
                                                                    class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2"
                                                                    type="submit">Registrarte</button>
                                                                <div style="color: #D9534F;">
                                                                    @if ($errors->all())
                                                                        @foreach ($errors->all() as $error)
                                                                            <span class="text-danger">{{ $error }}</span>

                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                                <div class="text-center">
                                                                    <a class="small"
                                                                        href="{{ route('/') }}">Login</a>
                                                                </div>

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
