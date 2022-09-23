@extends('layouts.app')
@section('content')

    <!--Section: Block Content-->
    <section class="container my-5">

        <!--Grid row-->
        <div class="row">

            <!--Grid column-->
            <div class="col-lg-8">
                @if ($errors->any())
                    <div class="card mb-3">
                        <div class="card-body">
                            <p>Errores: </p>
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger">{{$error}}</div>
                                @endforeach
                            @endif                        
                        </div>

                    </div>
                @endif

                <!-- Card -->
                <div class="card mb-3">
                    <div class="card-body pt-4 wish-list">
                        <div class="d-flex flex-row-reverse">
                            <a class="btn btn-success" href="{{ route('quote.index') }}">
                                🛒 Agregar al otro envio al carrito
                            </a>
                        </div>
                        
                            
                        
                            @php
                            @endphp
                                                
                        
                        
                        <h5 class="mb-4">🛒 Carro de compras (<span>{{ count($shipments) }}</span> envios)</h5>
                        @foreach ($shipments as $index => $shipment)
                            <div class="row mb-4">
                                <div class="col-md-5 col-lg-3 col-xl-3">
                                    <div class="view zoom z-depth-1 rounded mb-3 mb-md-0">
                                        <img class="img-fluid w-100" src="{{ asset('assets/images/cajita.png') }}"
                                            alt="Sample">

                                    </div>
                                </div>
                                <div class="col-md-7 col-lg-9 col-xl-9">
                                    <div>
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h5>🚐 {{ $shipment['ciudad_from'] }} a {{ $shipment["ciudad_to"] }} </h5>
                                                <p class="mb-3 text-muted text-uppercase small">
                                                    Calle origen:
                                                    {{ $shipment['street_from'] }}
                                                    #{{ $shipment['numero_from'] }}
                                                    @if ($shipment['numero_int_from'])
                                                        INT. {{ $shipment["numero_int_from"] }}
                                                    @endif
                                                    {{ $shipment['colonia_from'] }}
                                                    {{ $shipment['city_from'] }}
                                                    {{ $shipment['state_from'] }}

                                                </p>
                                                <p class="mb-2 text-muted text-uppercase small">
                                                    Calle destino:
                                                    {{ $shipment['street_to']}}
                                                    #{{ $shipment['numero_to'] }},
                                                    @if ($shipment['numero_int_to'])
                                                        INT. {{ $shipment['numero_int_to'] }},
                                                    @endif
                                                    {{ $shipment['colonia_to'] }},
                                                    {{ $shipment['ciudad_to'] }},
                                                    {{ $shipment['estado_to'] }}


                                                </p>
                                                <p>Ancho: {{ $shipment['width'] }}, Largo: {{ $shipment['length'] }}, Alto {{ $shipment['height'] }}, Peso {{ $shipment['weight'] }}</p>
                                                <p class="mb-3 text-muted text-uppercase small">
                                                    {{ $shipment['service_level'] ?? '' }}</p>
                                            </div>
                                            <div>

                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <a href="{{ route('shipment.delete', ['index' => $index]) }}"
                                                    class="card-link-secondary small text-uppercase mr-3">
                                                    <i class="fas fa-trash-alt mr-1">
                                                    </i>
                                                    Borrar envio
                                                </a>
                                            </div>
                                            <p class="mb-0">
                                                <span>
                                                    <strong id="summary">
                                                        {{ number_local_format($shipment['amount']) }}
                                                    </strong>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="mb-4">
                        @endforeach
                    </div>
                </div>
                <!-- Card -->

                <!-- Card -->
                <div class="card mb-3">
                    <div class="card-body pt-4">

                        <h5 class="mb-4">Dia de llegada</h5>

                        <p class="mb-0"> {{ $shipment['duration_terms'] }}</p>
                    </div>
                </div>
                <!-- Card -->

                <!-- Card -->
                <div class="card mb-3">
                    <div class="card-body pt-4">

                        <h5 class="mb-4">Aceptamos</h5>

                        <img class="mr-2" width="45px"
                            src="https://mdbootstrap.com/wp-content/plugins/woocommerce-gateway-stripe/assets/images/visa.svg"
                            alt="Visa">
                        <img class="mr-2" width="45px"
                            src="https://mdbootstrap.com/wp-content/plugins/woocommerce-gateway-stripe/assets/images/amex.svg"
                            alt="American Express">
                        <img class="mr-2" width="45px"
                            src="https://mdbootstrap.com/wp-content/plugins/woocommerce-gateway-stripe/assets/images/mastercard.svg"
                            alt="Mastercard">
                    </div>
                </div>
                <!-- Card -->

            </div>
            <!--Grid column-->

            <!--Grid column-->
            <div class="col-lg-4">

                <!-- Card -->
                <div class="card mb-3">
                    <div class="card-body pt-4">

                        <h5 class="mb-3">
                            Costo por prducto
                        </h5>
                        @php
                            $total = 0;
                        @endphp
                        <ul class="list-group list-group-flush">
                            @foreach ($shipments as $index => $shipment)
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                    #{{ $index + 1 }}
                                    {{ $shipment['ciudad_from'] }} a {{ $shipment['ciudad_to'] }}
                                    <span>{{ number_local_format($shipment['amount']) }}</span>
                                </li>
                                @php
                                    $total += $shipment['amount']
                                @endphp
                            @endforeach
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                                <div>
                                    <strong>Total</strong>

                                </div>
                                <span><strong class="total" id="total">{{ number_local_format($total) }}</strong></span>
                            </li>
                        </ul>

                        <button type="button" class="btn boton_morado btn-block" data-mdb-toggle="modal"
                            data-mdb-target="#payModal">💴 Pagar con credito dagpacket</button>
                        <button type="button" data-mdb-toggle="modal" data-mdb-target="#cc_modal"
                            class="btn boton_naranja btn-block">💳 🔐 Pagar con tarjeta</button>

                    </div>
                </div>
                <!-- Card -->

                <!-- Card -->

                <!-- Card -->

            </div>
            <!--Grid column-->

        </div>
        <!-- Grid row -->
        <!-- Modal -->
        <div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Proceso de pago</h5>
                        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('pay.create') }}" method="POST">
                            @csrf
                            <div class=" col-md-12">
                                <div class="form-label-group">

                                    <input type="text" name="cupon" class="form-control bg-white cupon-1">
                                    <label for="cupon">Ingresa tu cupon</label>
                                </div>
                                <div class="form-label-group">

                                    <input type="number" name="telefono" class="form-control bg-white cupon-1-telefono">
                                    <label for="telefono">Ingresa el telefono del cliente</label>
                                </div>

                                <div class="d-flex flex-row-reverse">
                                    <button type="button"  data-id="cupon-1"
                                        class="btn btn-sm btn-primary my-3 btn_coupon">Aplicar cupon</button>

                                </div>
                                <div class="cupon-1 cupon-response"></div>

                            </div>
                            <h5>Para confirmar su compra escriba la siguiente informacion:</h5>
                            <!-- <div class="form-group">
                                                                       <label>E-mail</label>
                                                                       <input type="email" name="email_pago" id="email_pago" class="field full-welement" placeholder="Ingrese email" required="">
                                                                   </div> -->
                            <div class="form-group">
                                <div class="form-label-group">
                                    <input type="password" name="password_pago" id="password_pago" class="form-control"
                                        placeholder="Ingrese su Contraseña" required="">
                                    <label for="password_pago">Contraseña</label>
                                </div>


                            </div>
                            <div class="form-group">
                                <label>Mostrar contraseña</label>
                                <input class="" type="checkbox" onclick="showPassword()">

                            </div>
                            <input type="hidden" name="for" value="picking">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary boton_azul"
                                    data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success boton_naranja pagarWallet"
                                    id="pagarWallet">Pagar</button>

                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
        <!-- Modal -->

        <!--Modal-->

        <!-- Modal -->
        <div class="modal fade" id="cc_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pagar con tarjeta de credito</h5>
                        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Payment form -->
                        <div id="form_tarjeta">
                            <form method="POST" id="paymentFrm" action="{{ route('pay.create') }}">
                                @csrf
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-label-group">
                                            <input type="text" name="name" id="name" class="field full-welement"
                                                placeholder="Ingrese nombre" required>
                                            <label for="name">Nombre</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-label-group">
                                            <input type="email" name="email" id="email" class="field full-welement"
                                                placeholder="Ingrese email" required>
                                            <label>E-mail</label>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-md-12">
                                    <p class="lead">Detalles de tarjeta de credito</p>
                                    <hr>
                                    <div class="form-group">
                                        <label>Numero de Tarjeta</label>
                                        <div id="card_number" class="form-control field full-welement"></div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Fecha de expiracion</label>
                                            <div id="card_expiry" class="form-control field full-welement"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Codigo CVC</label>
                                            <div id="card_cvc" class="form-control  field full-welement"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class=" col-md-12">
                                    <p class="lead mt-2">Cupon</p>
                                    <hr>

                                    <label>Ingresa tu cupon</label>
                                    <input type="text" name="cupon" class="form-control bg-white cupon-2">
                                    <label>Ingresa el telefono del cliente</label>
                                    <input type="number" name="telefono" class="form-control bg-white cupon-2-telefono">
                                    <button type="button" onclick="aplicarCupon('cupon-2',true)"
                                        class="btn btn-sm btn-primary my-3">Aplicar cupon</button>
                                    <div class="cupon-2 cupon-response"></div>

                                </div>


                                <h5>Para confirmar su compra escriba la siguiente informacion:</h5>
                                <!-- <div class="form-group">
                                                                           <label>E-mail</label>
                                                                           <input type="email" name="email_pago" id="email_pago" class="field full-welement" placeholder="Ingrese email" required="">
                                                                       </div> -->
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <input type="password" name="password_pago" id="password_pago" class="form-control"
                                            placeholder="Ingrese su Contraseña" required="">
                                        <label for="password_pago">Contraseña</label>
                                    </div>


                                </div>
                                <div class="form-group">
                                    <label>Mostrar contraseña</label>
                                    <input class="" type="checkbox" onclick="showPassword()">

                                </div>
                                <?php if (!isset($errores)) { ?>

                                <div id="paymentResponse"></div>
                                <?php } ?>



                                <div class="col-md-12 col-lg-12">
                                    <button type="submit" class="btn btn-success boton_naranja col-md-3 col-lg-3"
                                        id="payBtn">Pagar</button>

                                </div>
                            </form>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!--Modal-->
    </section>
    <!--Section: Block Content-->
@endsection
<script src="https://js.stripe.com/v3/"></script>


@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', ".pagarWallet", function() {

                $('body').addClass('overlay');
                //$('.btn').addClass("d-none");
                //$('#pagarWallet').submit();
            });

            function showPassword() {
                var x = document.getElementById("password_pago");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }

            $(document).on('click', '.btn_coupon', function(){
                let cupon = $(this).data('id');
                console.log(cupon);
                cupon = $(`input.${cupon}`)[0].value.toUpperCase();
                telefono = $(`input.${cupon}-telefono`).val();

                precio = $('#total').html().split(' ')[1];
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({

                    type: 'POST',

                    url: "{{ route('cupon.create') }}",

                    data: {
                        cupon: cupon,
                        price: Number(precio),
                        telefono: telefono,
                        for: 'paqueteria'

                    },

                    success: function(data, success) {

                        console.log(data);
         
                        total = 0;
                        if (data['response'] == true) {

                            jQuery(`.cupon-response`).html(
                                `<div class="alert alert-info">El cupon es valido: Precio con descuento $${data.precio} </div>`
                            );
                        } else {
                            jQuery(`.cupon-response`).html(
                                `<div class="alert alert-danger">El cupon no es valido`);

                        }

                    }

                });
            });

            $('form').submit(function() {
                $('#pagarWallet').prop('disabled', true);
                $('#payBtn').prop('disabled', true);
                $(this).find("button[type='submit']").prop('disabled', true);
            });
            var stripe = Stripe("{{ config('stripe.STRIPE_PK') }}")
            var elements = stripe.elements();
            var style = {
                base: {
                    fontWeight: 400,
                    fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                    fontSize: '16px',
                    lineHeight: '1.4',
                    color: '#555',
                    backgroundColor: '#fff',
                    '::placeholder': {
                        color: '#888',
                    },
                },
                invalid: {
                    color: '#eb1c26',
                }
            };

            var cardElement = elements.create('cardNumber', {
                style: style
            });
            cardElement.mount('#card_number');

            var exp = elements.create('cardExpiry', {
                'style': style
            });
            exp.mount('#card_expiry');

            var cvc = elements.create('cardCvc', {
                'style': style
            });
            cvc.mount('#card_cvc');

            // Validate input of the card elements
            var resultContainer = document.getElementById('paymentResponse');
            cardElement.addEventListener('change', function(event) {
                if (event.error) {
                    resultContainer.innerHTML = '<p>' + event.error.message + '</p>';
                } else {
                    resultContainer.innerHTML = '';
                }
            });

            // Get payment form element
            var form = document.getElementById('paymentFrm');

            // Create a token when the form is submitted.
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                createToken();
            });

            // Create single-use token to charge the user
            function createToken() {
                stripe.createToken(cardElement).then(function(result) {
                    if (result.error) {
                        // Inform the user if there was an error
                        resultContainer.innerHTML = '<p>' + result.error.message + '</p>';
                    } else {
                        // Send the token to your server
                        stripeTokenHandler(result.token);
                    }
                });
            }

            // Callback to handle the response from stripe
            function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);
                // Submit the form
                form.submit();
            }
        });

    </script>
@endpush
