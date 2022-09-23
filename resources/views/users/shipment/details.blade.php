@extends('layouts.app')
@section('content')
    <div class="container my-5">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger"">{{$error}}</div>
            @endforeach
        @endif
        <!--
        @php
        
            echo var_dump(session()->all());
        @endphp
        -->
        <div class="details my-2">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 ng-binding">
                            <p style="margin:0"><b>Recolección:</b></p>
                            <a class="ng-binding">
                                {{ $shipment->zipcode_from }} - {{ $shipment->municipio_from }}
                            </a>
                        </div>
                        <div class="col-xs-12 col-sm-4 ng-binding">
                            <p style="margin:0"><b>Entrega:</b></p>
                            <a ng-show="state_delivery" class="ng-binding">
                                {{ $shipment->zipcode_to }} - {{ $shipment->municipio_to }}
                            </a>
                        </div>
                        <div class="col-xs-12 col-sm-4 text-center">
                                <a href="{{ route('quote.index') }}" id="information_quote" name="information_quote"
                                    class="btn btn-primary boton_naranja information_quote">
                                        Crea otra cotización
                                </a>
                        </div>
                    </div>



                </div>



            </div>


        </div>

        <div class="info my-2">

            <form class="
                                was-validated 
                                ng-pristine 
                                ng-invalid 
                                ng-invalid-required 
                                ng-valid-email 
                                ng-valid-pattern 
                                ng-valid-maxlength 
                                ng-valid-minlength" method="post" id="costsForm" action="{{ route('checkout.index') }}"
                autocomplete="off">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12">
                                <div class="titledetails">
                                    <b>ORIGEN:</b>
                                    <button class="btn boton_naranja " id="saveAddressOrigin">Guardar domicilio de
                                        origen</button>
                                </div>
                                <br>
                                <div class="form-row my-2" id="addressesOriginContainer">
                                    <div class="form-group col-md-6">
                                        <label for="listSavedAddresses">Domicilios guardados:</label>

                                        <select class="form-control" id="listSavedAddressesOrigin">

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" placeholder="Nombre" required style="margin-bottom: 8px;"
                                                class="form-control ng-pristine ng-untouched ng-not-empty ng-valid ng-valid-required"
                                                id="name_from" name="name_from" value="">

                                            <label for="name_from">Nombre </label>

                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="email" placeholder="E-mail" required
                                                class="form-control ng-pristine ng-untouched ng-empty ng-valid-email ng-invalid ng-invalid-required ng-valid-pattern"
                                                id="email_from" name="email_from" value="">
                                            <label for="email_from">Email</label>

                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" maxlength="10" pattern="[0-9]{10}"
                                                placeholder="Telefono de Contacto 10 (Dígitos)" required
                                                class="form-control ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-required ng-valid-pattern ng-valid-maxlength"
                                                id="phone_from" value="" name="phone_from">
                                            <label for="phone_from">Telefono  </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" maxlength="5" pattern="[0-9]{5}"
                                                class="form-control ng-pristine ng-untouched ng-valid ng-valid-pattern ng-valid-maxlength ng-not-empty"
                                                placeholder="C.P." readonly value="{{ $shipment->zipcode_from }}"
                                                name="zipcode_from" id="zipcode_from">
                                            <label for="zipcode_from">Codigo postal </label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text"
                                                class="form-control ng-pristine ng-untouched ng-scope ng-not-empty ng-valid ng-valid-required"
                                                placeholder="Estado" value=" {{ $shipment->state_from }}"
                                                name="estado_from" id="estado_from" required readonly>

                                            <label for="estado_from">Estado </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text"
                                                class="form-control ng-pristine ng-untouched ng-scope ng-valid-maxlength ng-not-empty ng-valid ng-valid-required"
                                                placeholder="Ciudad" maxlength="30" value=" {{ $shipment->city_from }}"
                                                name="ciudad_from" id="ciudad_from" required readonly>

                                            <label for="ciudad_from">Ciudad </label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" placeholder="Calle" required=""
                                                class="form-control ng-pristine ng-untouched ng-not-empty ng-valid ng-valid-required"
                                                name="street_from" value="" id="street_from">

                                            <label for="street_from">Calle </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" placeholder="Municipio" required=""
                                                class="form-control ng-pristine ng-untouched ng-valid ng-empty"
                                                value="{{ $shipment->municipio_from }}" name="municipio_from"
                                                id="municipio_from">

                                            <label for="municipio_from">Municipio</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" placeholder="Numero exterior remitente" required=""
                                                class="form-control ng-pristine ng-untouched ng-not-empty ng-valid ng-valid-required"
                                                name="numero_from" value="" id="numero_from">

                                            <label for="numero_from">Numero exterior</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" placeholder="Interior"
                                                class="form-control ng-pristine ng-untouched ng-not-empty ng-valid ng-valid-required"
                                                name="numero_int_from" value="" id="numero_int_from">

                                            <label for="numero_int_from">Numero interior</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" class="form-control" placeholder="Colonia de origen"
                                                name="colonia_from" value="" id="colonia_from" required>

                                            <label for="colonia_from">Colonia</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="form-label-group">
                                            <input type="text" placeholder="Casa verde" maxlength="50"
                                                class="form-control ng-pristine ng-untouched ng-valid ng-empty ng-valid-maxlength"
                                                name="referencia_from" value="" id="referencia_from">
                                            <label for="referencia_from">Referencia remitente</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="card my-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="titledetails">
                                    <b>DESTINO:</b>
                                    <button class="btn boton_naranja" id="saveAddressDestiny">Guardar domicilio de
                                        destino</button>

                                </div>
                                <br>
                                <div class="form-row my-2" id="addressesDestinyContainer">
                                    <div class="form-group col-md-6">
                                        <label for="listSavedAddressesDestiny">Domicilios guardados:</label>

                                        <select class="form-control" id="listSavedAddressesDestiny">

                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" placeholder="Nombre" required=""
                                                class="form-control ng-pristine ng-untouched ng-not-empty ng-valid ng-valid-required"
                                                name="name_to" value="" id="name_to">
                                            <label for="name_to">Nombre </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="email" placeholder="E-mail" required=""
                                                class="form-control ng-pristine ng-untouched ng-empty ng-valid-email ng-invalid ng-invalid-required ng-valid-pattern"
                                                name="email_to" value="" id="email_to">

                                            <label for="email_to">Correo </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" maxlength="10" pattern="[0-9]{10}"
                                                placeholder="Telefono de Contacto 10 (Dígitos)" required=""
                                                class="form-control ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-required ng-valid-pattern ng-valid-maxlength"
                                                name="phone_to" value="" id="phone_to">

                                            <label for="phone_to">Telefono destinatario</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" maxlength="5" pattern="[0-9]{5}"
                                                class="form-control ng-pristine ng-untouched ng-valid ng-valid-pattern ng-valid-maxlength ng-not-empty"
                                                placeholder="C.P." readonly value=" {{ $shipment->zipcode_to }}"
                                                name="zipcode_to" id="zipcode_to">

                                            <label for="zipcode_to">Codio postal </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text"
                                                class="form-control ng-pristine ng-untouched ng-scope ng-not-empty ng-valid ng-valid-required"
                                                placeholder="Estado" required readonly value="{{ $shipment->state_to }}"
                                                name="estado_to" id="estado_to">

                                            <label for="estado_to">Estado </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text"
                                                class="form-control ng-pristine ng-untouched ng-scope ng-valid-maxlength ng-not-empty ng-valid ng-valid-required"
                                                placeholder="Ciudad" required readonly value="{{ $shipment->city_to }}"
                                                name="ciudad_to" id="ciudad_to">

                                            <label for="ciudad_to">Ciudad </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" placeholder="Municipio" required
                                                class="form-control ng-pristine ng-untouched ng-valid ng-empty"
                                                value="{{ $shipment->municipio_to }}" name="municipio_to"
                                                id="municipio_to">

                                            <label for="municipio_to">Municipio </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" placeholder="Calle" required=""
                                                class="form-control ng-pristine ng-untouched ng-not-empty ng-valid ng-valid-required"
                                                name="street_to" value="" id="street_to">


                                            <label for="street_to">Calle </label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" placeholder="Exterior" required=""
                                                class="form-control ng-pristine ng-untouched ng-not-empty ng-valid ng-valid-required"
                                                name="numero_to" value="" id="numero_to">


                                            <label for="numero_to">Numero exterior </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" placeholder="Interior"
                                                class="form-control ng-pristine ng-untouched ng-not-empty ng-valid ng-valid-required"
                                                name="numero_int_to" value="" id="numero_int_to">

                                            <label for="numero_int_to">Numero interior </label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-label-group">
                                            <input type="text" placeholder="Colonia de destino" class="form-control"
                                                name="colonia_to" value="" id="colonia_to" required>

                                            <label for="colonia_to">Colonia </label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="form-label-group">
                                            <input type="text" maxlength="50" placeholder="Casa Azul"
                                                class="form-control ng-pristine ng-untouched ng-valid ng-empty ng-valid-maxlength"
                                                name="referencia_to" value="" id="referencia_to">

                                            <label for="referencia_to">Referencia destinatario</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="form-label-group">
                                            <input type="text" maxlength="50" id="description_to"
                                                class="form-control ng-pristine ng-untouched ng-valid ng-empty ng-valid-maxlength"
                                                placeholder="Escribe aquí en contenido del paquete " minlength="10"
                                                value="{{ $shipment->description }}" name="description" />
                                            <label for="description_to">Descripcion destinatario</label>

                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="d-flex flex-column-reverse">
                                    <button type="submit"  onClick="this.form.submit(); this.disabled=true; this.innerHtml='Espere'; "  value="information_quote" name="information_quote"
                                        class="btn btn-success boton_naranja information_quote">
                                        Concluir Envío
                                    </button>
                                  </div>
                            </div>
                        </div>
                    </div>
            </form>

        </div>
    @endsection


    @push('scripts')
        <script>
            $(document).ready(function() {
                // In your Javascript (external .js resource or <script> tag)
                $('#listSavedAddressesOrigin').select2();
                $('#listSavedAddressesDestiny').select2();costsForm
                var form = document.getElementById("costsForm");
                form.noValidate = true; // turn off default validation

                form.onsubmit = function(e) {
                e.preventDefault(); // preventing default behaviour
                this.reportValidity(); // run native validation manually

                // runs default behaviour (submitting) in case of validation success
                if (this.checkValidity()) {
                    $('body').addClass('overlay');
                    $('.btn').addClass("d-none");
                    return form.submit();
                }

                //alert('invalid'); // your code goes here
                }
                $(document).on('click', ".information_quote", function() {

                    //$('body').addClass('overlay');
                    //$('.btn').addClass("d-none");
                    //$(this).submit();
                });
                const endpoint = "https://api-sepomex.hckdrk.me/query/info_cp/"
                const token = "?token=368640bf-c16d-4b7b-9622-dbba51f654c1";
                const zipcode_from = $("#zipcode_from").val();
                const zipcode_to = $("#zipcode_to").val();
                //getColoniasOrigen();
                //getColoniasDestino();        
                $("#saveAddressOrigin").on('click', function(e) {
                    e.preventDefault();
                    const name_from = $("#name_from").val();
                    const email_from = $("#email_from").val();
                    const phone_from = $("#phone_from").val();
                    const zipcode_from = $("#zipcode_from").val();
                    const estado_from = $("#estado_from").val();
                    const municipio_from = $("#municipio_from").val();
                    const street_from = $("#street_from").val();
                    const numero_from = $("#numero_from").val();
                    const numero_int_from = $("#numero_int_from").val();
                    const colonia_from = $("#colonia_from").val();
                    const referencia_from = $("#referencia_from").val();
                    $.ajax({
                        url: "{{ route('address.create.origin') }}",
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        type: "POST",
                        data: {
                            name_from: name_from,
                            email_from: email_from,
                            phone_from: phone_from,
                            zipcode_from: zipcode_from,
                            estado_from: estado_from,
                            municipio_from: municipio_from,
                            street_from: street_from,
                            numero_from: numero_from,
                            numero_int_from: numero_int_from,
                            colonia_from: colonia_from,
                            referencia_from: referencia_from,
                            ruta: 0, //origen,
                            '_token': $('meta[name="csrf-token"]').attr('content') 
                        },
                        success: function(response) {
                            Swal.fire(
                                'Domicilio de origen guardado correctamente',
                                'Continua con el proceso de envio de tu paquete',
                                'success'
                            )
                        }
                    })


                });

                $("#saveAddressDestiny").on('click', function(e) {
                    e.preventDefault();
                    const name_to = $("#name_to").val();
                    const email_to = $("#email_to").val();
                    const phone_to = $("#phone_to").val();
                    const zipcode_to = $("#zipcode_to").val();
                    const estado_to = $("#estado_to").val();
                    const municipio_to = $("#municipio_to").val();
                    const street_to = $("#street_to").val();
                    const numero_to = $("#numero_to").val();
                    const numero_int_to = $("#numero_int_to").val();
                    const colonia_to = $("#colonia_to").val();
                    const referencia_to = $("#referencia_to").val();
                    $.ajax({
                        url: "{{ route('address.create.destination') }}",
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        type: "POST",
                        data: {
                            name_to: name_to,
                            email_to: email_to,
                            phone_to: phone_to,
                            zipcode_to: zipcode_to,
                            estado_to: estado_to,
                            municipio_to: municipio_to,
                            street_to: street_to,
                            numero_to: numero_to,
                            numero_int_to: numero_int_to,
                            colonia_to: colonia_to,
                            referencia_to: referencia_to,
                            ruta: 1 //destino
                        },
                        success: function(response) {
                            Swal.fire(
                                'Domicilio de destino guardado correctamente',
                                'Continua con el proceso de envio de tu paquete',
                                'success'
                            )
                        }
                    })


                });

                $('#listSavedAddressesDestiny').on('change', function(e) {
                    const address = JSON.parse(decodeURIComponent($(this).find(':selected').data(
                        'address')));
                    console.log(address);
                    /*$("#zipcode_from").val(address.zipcode_from);
                    $("#estado_from").val(address.estado_from);
                    $("#municipio_from").val(address.municipio_from);
                    $("#street_from").val(address.street_from);
                    $("#numero_from").val(address.numero_from);
                    $("#numero_int_from").val(address.numero_int_from);
                    $("#colonia_from").val(address.colonia_from);
                    $("#referencia_from").val(address.referencia_from);
                    */
                    $("#name_to").val(address.name_to);
                    $("#email_to").val(address.email_to);
                    $("#phone_to").val(address.phone_to);
                    $("#zipcode_to").val(address.zipcode_to);
                    $("#estado_to").val(address.estado_to);
                    $("#municipio_to").val(address.municipio_to);
                    $("#street_to").val(address.street_to);
                    $("#numero_to").val(address.numero_to);
                    $("#numero_int_to").val(address.numero_int_to);
                    $("#colonia_to").val(address.colonia_to);
                    $("#referencia_to").val(address.referencia_to);
                });
                $('#listSavedAddressesOrigin').on('change', function(e) {
                    const address = JSON.parse(decodeURIComponent($(this).find(':selected').data(
                        'address')));
                    console.log(address);
                    $("#name_from").val(address.name_from);
                    $("#email_from").val(address.email_from);
                    $("#phone_from").val(address.phone_from);
                    $("#zipcode_from").val(address.zipcode_from);
                    $("#estado_from").val(address.estado_from);
                    $("#municipio_from").val(address.municipio_from);
                    $("#street_from").val(address.street_from);
                    $("#numero_from").val(address.numero_from);
                    $("#numero_int_from").val(address.numero_int_from);
                    $("#colonia_from").val(address.colonia_from);
                    $("#referencia_from").val(address.referencia_from);
                });


                function getColoniasOrigen() {
                    $.getJSON(endpoint + zipcode_from + '/' + token, function(data, textStatus) {


                        data.map(function(item) {
                            console.log(item);
                            $('#colonia_from').append(
                                `<option>${item.response.asentamiento}</option>`);
                        })

                    });

                }

                function getColoniasDestino() {
                    $.getJSON(endpoint + zipcode_to + '/' + token, function(data, textStatus) {
                        data.map(function(item) {

                            $('#colonia_to').append(
                                `<option>${item.response.asentamiento}</option>`);
                        });

                    });
                }

            });

        </script>
        <script>
            $(document).ready(function() {
                const endpoint = "https://api-sepomex.hckdrk.me/query/info_cp/"
                const token = "?token=368640bf-c16d-4b7b-9622-dbba51f654c1";
                const zipcode_from = $("#zipcode_from").val();
                const zipcode_to = $("#zipcode_to").val();
                //getColoniasOrigen();
                //getColoniasDestino();        
                const addresses = getSavedAddressess();

                $("#saveAddressDestiny").on('click', function(e) {
                    e.preventDefault();
                    const name_to = $("#name_to").val();
                    const email_to = $("#email_to").val();
                    const phone_to = $("#phone_to").val();
                    const zipcode_to = $("#zipcode_to").val();
                    const estado_to = $("#estado_to").val();
                    const municipio_to = $("#municipio_to").val();
                    const street_to = $("#street_to").val();
                    const numero_to = $("#numero_to").val();
                    const numero_int_to = $("#numero_int_to").val();
                    const colonia_to = $("#colonia_to").val();
                    const referencia_to = $("#referencia_to").val();
                    $.ajax({
                        url: "",
                        type: "POST",
                        data: {
                            name_to: name_to,
                            email_to: email_to,
                            phone_to: phone_to,
                            zipcode_to: zipcode_to,
                            estado_to: estado_to,
                            municipio_to: municipio_to,
                            street_to: street_to,
                            numero_to: numero_to,
                            numero_int_to: numero_int_to,
                            colonia_to: colonia_to,
                            referencia_to: referencia_to,
                            ruta: 1 //destino
                        },
                        success: function(response) {
                            Swal.fire(
                                'Domicilio de destino guardado correctamente',
                                'Continua con el proceso de envio de tu paquete',
                                'success'
                            )
                        }
                    })


                });

                $('#listSavedAddressesDestiny').on('change', function(e) {
                    const address = JSON.parse(decodeURIComponent($(this).find(':selected').data(
                        'address')));
                    console.log(address);
                    /*$("#zipcode_from").val(address.zipcode_from);
                    $("#estado_from").val(address.estado_from);
                    $("#municipio_from").val(address.municipio_from);
                    $("#street_from").val(address.street_from);
                    $("#numero_from").val(address.numero_from);
                    $("#numero_int_from").val(address.numero_int_from);
                    $("#colonia_from").val(address.colonia_from);
                    $("#referencia_from").val(address.referencia_from);
                    */
                    $("#name_to").val(address.name_to);
                    $("#email_to").val(address.email_to);
                    $("#phone_to").val(address.phone_to);
                    $("#zipcode_to").val(address.zipcode_to);
                    $("#estado_to").val(address.estado_to);
                    $("#municipio_to").val(address.municipio_to);
                    $("#street_to").val(address.street_to);
                    $("#numero_to").val(address.numero_to);
                    $("#numero_int_to").val(address.numero_int_to);
                    $("#colonia_to").val(address.colonia_to);
                    $("#referencia_to").val(address.referencia_to);
                });
                $('#listSavedAddressesOrigin').on('change', function(e) {
                    const address = JSON.parse(decodeURIComponent($(this).find(':selected').data(
                        'address')));
                    console.log(address);
                    $("#name_from").val(address.name_from);
                    $("#email_from").val(address.email_from);
                    $("#phone_from").val(address.phone_from);
                    $("#zipcode_from").val(address.zipcode_from);
                    $("#estado_from").val(address.estado_from);
                    $("#municipio_from").val(address.municipio_from);
                    $("#street_from").val(address.street_from);
                    $("#numero_from").val(address.numero_from);
                    $("#numero_int_from").val(address.numero_int_from);
                    $("#colonia_from").val(address.colonia_from);
                    $("#referencia_from").val(address.referencia_from);
                });

                function getSavedAddressess() {
                    $.ajax({
                        url: "{{ route('address.show') }}",
                        type: "GET",
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {

                            const jsonResponse = response;
                            if (jsonResponse.originAddresses.length === 0) {
                                $("#addressesOriginContainer").remove();
                            } else {
                                console.log(jsonResponse.originAddresses);
                                var $select = $('#listSavedAddressesOrigin');
                                $select.find('option').remove();
                                $select.append(`<option selected="true" disabled="disabled"></option>`);
                                $.each(jsonResponse.originAddresses, function(key, value) {
                                    const address_info = JSON.parse(value.address_info);
                                    //$.each(address_info, function(key, value){
                                    console.log(address_info.street_from);
                                    //});
                                    $select.append(
                                        `<option value="${key}"" data-address="${encodeURIComponent(value.address_info)}">${address_info.street_from},  ${address_info.estado_from} </option>`
                                    );
                                });
                            }

                            if (jsonResponse.destinyAddresses.length === 0) {
                                $("#addressesDestinyContainer").remove();
                            } else {


                                var $select = $('#listSavedAddressesDestiny');
                                $select.find('option').remove();
                                $select.append(`<option selected="true" disabled="disabled"></option>`);
                                $.each(jsonResponse.destinyAddresses, function(key, value) {
                                    const address_info = JSON.parse(value.address_info);
                                    //$.each(address_info, function(key, value){
                                    console.log(address_info.street_from);
                                    //});
                                    $select.append(
                                        `<option value="${key}"" data-address="${encodeURIComponent(value.address_info)}">${address_info.street_to},  ${address_info.estado_to} </option>`
                                    );
                                });

                            }

                        }
                    });
                }

                function getColoniasOrigen() {
                    $.getJSON(endpoint + zipcode_from + '/' + token, function(data, textStatus) {


                        data.map(function(item) {
                            console.log(item);
                            $('#colonia_from').append(
                                `<option>${item.response.asentamiento}</option>`);
                        })

                    });

                }

                function getColoniasDestino() {
                    $.getJSON(endpoint + zipcode_to + '/' + token, function(data, textStatus) {
                        data.map(function(item) {

                            $('#colonia_to').append(
                                `<option>${item.response.asentamiento}</option>`);
                        });

                    });
                }

            });

        </script>

    @endpush
