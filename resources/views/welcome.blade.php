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
                                                            <form method="post" action="{{ route('auth.login') }}">
                                                                @csrf
                                                                <div class="form-label-group">
                                                                    <input type="email" id="inputEmail" name="email" class="form-control"
                                                                        placeholder="Correo Electronico" required autofocus>
                                                                    <label for="inputEmail">Correo electronico</label>
                                                                </div>

                                                                <div class="form-label-group">
                                                                    <input type="password" id="inputPassword"
                                                                        class="form-control" name="password" placeholder="Contraseña"
                                                                        required>
                                                                    <label for="inputPassword">Contraseña</label>
                                                                </div>

                                                                <div class="custom-control custom-checkbox mb-3">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="customCheck1">
                                                                    <label class="custom-control-label"
                                                                        for="customCheck1">Remember password</label>
                                                                </div>
                                                                <button name="login"
                                                                    class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2"
                                                                    type="submit" onClick="this.form.submit(); this.disabled=true; this.innerHtml='Espere...'; "
                                                                    >Iniciar Sesión</button>
                                                                <div style="color: #D9534F;">
                                                                    @if ($errors->all())
                                                                        @foreach ($errors->all() as $error)
                                                                            <span class="text-danger">{{ $error }}</span>

                                                                        @endforeach
                                                                    @endif
                                                                    @if ($errors->has('discription'))
                                                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                                                    @endif
                                                                </div>
                                                                <div class="text-center">
                                                                    <a class="small"
                                                                        href="{{ route('auth.register') }}">Registrate</a>
                                                                </div>

                                                                <div class="text-center">
                                                                    <a class="small" href="#">Forgot password?</a>
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
