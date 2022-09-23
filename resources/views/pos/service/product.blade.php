@extends('layouts.app')
@section('content')

    <div class="container my-5">
        <button type="button" class="btn btn-warning d-block" id="goback">Volver</button>
        <div class="row my-3">
            <div class="col-md-6 mx-auto">
                <form action="" class="search-form">
                    <div class="form-group has-feedback">
                        <label for="search" class="sr-only">Search</label>
                        <input type="text" class="form-control" name="search" id="search" placeholder="Filtrar por nombre">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            @foreach ($products as $product)
                <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-3 my-2 product-list"
                    data-title="<?= $product->ProductName ?>">
                    <div class="card">
                        <div class="card-img-top" style="position: relative; height: 230px;">
                            <img src="{{ filter_var($product->ReferenceParameters->Reference1->URLImage, FILTER_VALIDATE_URL) ? $product->ReferenceParameters->Reference1->URLImage : 'https://servicios.dagpacket.org/assets/img/logo2.png' }}"
                                style="width: 100%;height: 12vw;object-fit: contain;" />

                        </div>

                        <div class="card-body">
                            <h5 class="card-title">{{ $product->ProductName }} </h5>
                            <p class="card-text"> {{ $product->ReferenceParameters->Reference1->ToolTip }} </p>
                            @if (isset($product->Amount) && !empty((string) $product->Amount))
                                <p class="price">Precio: {{ money_to_local_format((string) $product->Amount) }}
                                </p>
                            @endif
                            <p>Tipo de pago: {{ (string) $product->PaymentType }}</p>
                            <form action="{{ route('pos.service.item') }} " method="POST">

                                <input type="hidden" name="ProductId" value="{{ (string) $product->ProductId }}" />
                                <input type="hidden" name="LengthMin"
                                    value="{{ (string) $product->ReferenceParameters->Reference1->LengthMin }}" />
                                <input type="hidden" name="LengthMax"
                                    value="{{ (string) $product->ReferenceParameters->Reference1->LengthMax }}" />
                                <input type="hidden" name="Amount" value="{{ (string) $product->Amount }}" />
                                <input type="hidden" name="AmountMin" value="{{ (string) $product->AmountMin }}" />
                                <input type="hidden" name="AmountMax" value="{{ (string) $product->AmountMax }}" />
                                <input type="hidden" name="AmountMax" value="{{ (string) $product->AmountMax }}" />
                                <input type="hidden" name="PaymentType" value="{{ (string) $product->PaymentType }}" />
                                <input type="hidden" name="ProductName" value="{{ (string) $product->ProductName }}" />
                                <input type="hidden" name="ReferenceName"
                                    value="{{ (string) $product->ReferenceParameters->Reference1->ReferenceName }}" />
                                <input type="hidden" name="FieldType"
                                    value="{{ (string) $product->ReferenceParameters->Reference1->FieldType }}" />
                                <input type="hidden" name="ProductUFee" value="{{ (string) $product->ProductUFee }}" />

                                <button class="btn btn-primary">Pagar servicio con:
                                    {{ (string) $product->ReferenceParameters->Reference1->ReferenceName }}</button>
                            </form>
                            @if (!empty($product->ReferenceParameters->Reference2))
                                <form action="{{ route('pos.service.item') }} " method="POST">
                                    <input type="hidden" name="ProductId" value="{{ (string) $product->ProductId }}" />
                                    <input type="hidden" name="LengthMin"
                                        value="{{ (string) $product->ReferenceParameters->Reference2->LengthMin }}" />
                                    <input type="hidden" name="LengthMax"
                                        value="{{ (string) $product->ReferenceParameters->Reference2->LengthMax }}" />
                                    <input type="hidden" name="Amount" value="{{ (string) $product->Amount }}" />
                                    <input type="hidden" name="AmountMin" value="{{ (string) $product->AmountMin }}" />
                                    <input type="hidden" name="AmountMax" value="{{ (string) $product->AmountMax }}" />
                                    <input type="hidden" name="CarrierName"
                                        value="{{ (string) $product->CarrierName }}" />
                                    <input type="hidden" name="ProductName"
                                        value="{{ (string) $product->ProductName }}" />
                                    <input type="hidden" name="ReferenceName"
                                        value="{{ (string) $product->ReferenceParameters->Reference2->ReferenceName }}" />
                                    <input type="hidden" name="FieldType"
                                        value="{{ (string) $product->ReferenceParameters->Reference2->FieldType }}" />
                                    <input type="hidden" name="ProductUFee"
                                        value="{{ (string) $product->ProductUFee }}" />

                                    <button class="my-2 btn btn-primary">Pagar servicio con:
                                        {{ (string) $product->ReferenceParameters->Reference2->ReferenceName }} </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/underscore@1.13.1/underscore-umd-min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"
        integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js"
        integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            $("#goback").on('click', function() {

                window.history.back();
            })

            if (window.innerWidth < 768) {
                $('.btn').addClass('btn-sm');
            }

            // Medida por defecto (Sin ningún nombre de clase)
            else if (window.innerWidth < 900) {
                $('.btn').removeClass('btn-sm');
            }

            // Si el ancho del navegador es menor a 1200 px le asigno la clase 'btn-lg' 
            else if (window.innerWidth < 1200) {
                $('.btn').addClass('btn-lg');
            }
            $(document).on('keyup', filter)

            function filter() {
                // Declare variables
                var debounce_fun = _.debounce(function() {
                    console.log('1');
                    var input, filter, txtValue;
                    input = document.getElementById('search');
                    filter = input.value.toUpperCase();
                    //ul = document.getElementById("myUL");
                    var productList = $('.product-list');
                    productList.map((index, product) => {
                        let productName = product.dataset.title;
                        if (productName.toUpperCase().indexOf(filter) > -1) {
                            product.style.display = '';
                        } else {
                            product.style.display = 'none';
                        }
                    });
                }, 200);

                debounce_fun();

            }
        });
    </script>
@endpush
