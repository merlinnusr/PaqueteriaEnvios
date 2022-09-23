@extends('layouts.app')
@section('content')
    <style>
        i {
            color: #9ABC66;
            font-size: 100px;
            line-height: 200px;
            margin-left: -15px;
        }

    </style>
    <div class="col my-5 d-flex justify-content-center">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
                        <i class="checkmark">✓</i>
                    </div>
                    <div class="text-center">

                        <h1>Pago exitoso</h1>
                        <p>Nosotros ya recibimos tus pedido;<br /> en breve se enviaran tus pedidos!</p>

                    </div>
                    <div class="text-center mx-auto">

                        <a class="btn btn-primary" href="{{ route('home') }}">Ve tus pedidos</a>
                    </div>
                </div>

            </div>


        </div>

    </div>

@endsection
