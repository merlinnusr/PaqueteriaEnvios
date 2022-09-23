@extends('layouts.app')
@section('content')
    <div class="container my-5" id="app">
        <button class="btn btn-success" onclick="goBack()">Volver</button>
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Proveedor: {{ $products['CarrierName'] }}</div>
                        <p>Producto: {{ $products['ProductName'] }} </p>
                        <p>Comision: {{ number_format($products['ProductUFee'], 2) }}</p>
                        <p>Forma de pago: {{ $products['PaymentType'] }}</p>
                        <form action="{{ route('pos.service.buy') }}" id="buyService" method="POST">
                            <div class="form-group">
                                <label for="productIdentification"> Introduzca su: <?= $products['ReferenceName'] ?>
                                </label>
                                <input type="text" id="productIdentification" name="productIdentification"
                                    maxlength="{{ $products['LengthMax'] }}" {{ !empty($pattern) ? $pattern : '' }}
                                    class="form-control" placeholder="{{ $products['ReferenceName'] }}" required>
                            </div>
                            <div class="form-group">
                                <label for="productIdentificationConfirmation">Confirmacion de
                                    {{ $products['ReferenceName'] }} </label>
                                <input type="text" id="productIdentificationConfirmation"
                                    name="productIdentificationConfirmation" maxlength="{{ $products['LengthMax'] }}"
                                    {{ !empty($pattern) ? $pattern : '' }} class="form-control"
                                    placeholder="{{ $products['ReferenceName'] }}  confirmación" required>
                            </div>


                            @if ($products['Amount'] == 0)
                                <div class="form-group">
                                    <label for="amountToPay">Monto a pagar</label>
                                    <input type="number" id="amountToPay" name="amountToPay"
                                        min="{{ $products['AmountMin'] }}" step="any"
                                        max=" {{ $products['AmountMax'] }}" {{ !empty($pattern) ? $pattern : '' }}
                                        class="form-control" placeholder="Monto" required>
                                </div>

                            @endif
                            <input type="hidden" name="ProductName" value="{{ $products['ProductName'] }}">
                            <input type="hidden" name="ProductUFee" value="{{ $products['ProductUFee'] }}">
                            <input type="hidden" name="amount" value="{{ $products['Amount'] }}">
                            <input type="hidden" name="ProductId" value="{{ $products['ProductId'] }}">
                            <button type="submit" class="btn btn-primary pay my-2">Pagar servicio</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"
        integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js"
        integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/localization/messages_es.min.js"></script>
    <script>
        $("#buyService").validate({
            rules: {
                productIdentification: {
                    required: true,
                    minlength: {{ $products['LengthMin'] }},
                    maxlength: {{ $products['LengthMax'] }}
                },
                productIdentificationConfirmation: {
                    required: true,
                    minlength: {{ $products['LengthMin'] }},
                    maxlength: {{ $products['LengthMax'] }},
                    equalTo: "#productIdentification"

                },
                amountToPay: {
                    required: {{ $products['Amount'] == 0 ? 'true' : 'false' }},
                    min: {{ $products['AmountMin'] }},
                    max: {{ $products['AmountMax'] }}
                }
            },

            submitHandler: function(form) { // <- pass 'form' argument in
                //$(".pay").attr("disabled", true);
                $('body').addClass('overlay');

                form.submit(); // <- use 'form' argument here.
            }
        });
    </script>

@endpush
