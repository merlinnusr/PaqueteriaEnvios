@extends('layouts.app')
@section('title', 'Cotizacion')

@section('content')
<section class="rates my-4">

    <div class="card">
        <div class="card-body">
            <h1 class="display-4">📦💸Cotiza el envio de tu paquete</h1>
            <form action="{{ route('rates.create') }}" id="quote" method="POST" class="row align-items-start">
                @csrf
                <div class="col-12 col-md-5">
                    <div class="form-label-group">

                        <input type="text" class="form-control" name="zipcode_from" id="zipcode_from" placeholder="CP Origen" value="44130">
                        <label for="zipcode_from">Codigo postal de origen</label>

                    </div>

                </div>

                <div class="col-12 col-md-5">
                    <div class="form-label-group">
                        <input type="text" class="form-control" name="zipcode_to" id="zipcode_to" placeholder="CP Destino" value="55000">
                        <label for="zipcode_to">Codigo postal de destino</label>


                    </div>

                </div>
                <div class="col-6 col-md-1">
                    <a class="btn blue-btn text-white btn-rounded" id="envelope_btn">
                        <i class="far fa-envelope"></i>
                        Sobre
                    </a>
                </div>
                <div class="col-6 col-md-1">
                    <a class="btn blue-btn text-white  btn-rounded" id="package_btn">
                        <i class="fas fa-archive"></i>
                        Paquete
                    </a>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-label-group">

                            <input type="text" class="form-control" name="description" id="description" placeholder="Descripción" value="documento">
                            <label for="description">Descripción de contenido del paquete</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <p class="text-muted">*Selecciona si tú envio es un sobre o paquete para poder continuar</p>
                </div>


                <div class="mb-3 row d-none" id="package_sizes">
                    <div class="col-md-2">
                        <div class="form-label-group">
                            <input type="text" value="24" class="form-control" name="width" id="width" placeholder="Ancho">
                            <label for="width">Ancho (CM)</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-label-group">
                            <input type="text" value="30" class="form-control" name="length" id="length" placeholder="Largo">
                            <label for="length">Largo (CM)</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-label-group">
                            <input type="text" value="1" class="form-control" name="height" id="height" placeholder="Alto">
                            <label for="height">Alto (CM)</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-label-group">
                            <input type="text" value="1" class="form-control" name="weight" id="weight" placeholder="Peso">
                            <label for="weight">Peso volumetrico </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-label-group">
                            <input type="text" value="1" class="form-control" name="weight_real" id="weight_real" placeholder="Peso">
                            <label for="weight_real">Peso real (KG)</label>
                        </div>
                    </div>
                </div>
                <div class="alert alert-primary" role="alert">
                    ⚖️ Para tomar las medidas de tu paquete ponderamos el peso volumetrico contra el peso real, siendo el mayor el que sea considerado como el peso de tú paquete
                </div>
                <div class="form-check d-flex flex-row-reverse">
                    <input type="checkbox" id="myCheck">

                    <label class="form-check-label" for="myCheck">
                        Confirmo que los datos de mi paquete son correctos
                    </label>
                </div>

                <div class="d-flex flex-row-reverse">
                    <button 
                        class="btn blue-btn text-white btn-rounded" 
                        name="cotizar" 
                        id="cotizar" 
                        type="submit"
                        onClick="this.form.submit(); this.disabled=true; this.innerHTML='Espere…'; " 

                        >Cotizar</button>

                  </div>

                @if ($errors->all())
                @foreach ($errors->all() as $error)
                <span class="text-danger">{{ $error }}</span>

                @endforeach
                @endif
            </form>


        </div>


    </div>


</section>


@endsection

@push('scripts')
<script>
    // A $( document ).ready() block.
    $(document).ready(function() {
        $("#quote").validate({
            rules: {
                zipcode_from: {
                    required: true,
                    maxlength: 5,
                    minlength: 5
                },
                zipcode_to: {
                    required: true,
                    maxlength: 5,
                    minlength: 5

                },
                description:{
                    required: true,
                    maxlength: 70,
                    minlength: 1
                },
                length: {
                    required: true,
                    min: 1,

                },
                width: {
                    required: true,
                    min: 1,

                },
                height: {
                    required: true,
                    min: 1,


                },
                weight: {
                    required: true,
                    min: 1,


                },
                weight_real: {
                    required: true,
                    min: 1,

                }
            },
            errorPlacement: function (error, element) {
                console.log(element);
                $(element).parent().after(error);
            }
        });
        document.getElementById("cotizar").hidden = true;

        $(document).on('click', '#myCheck', function() {
            var checkBox = document.getElementById("myCheck");
            if (checkBox.checked == true) {
                document.getElementById("cotizar").hidden = false;
            } else {
                document.getElementById("cotizar").hidden = true;
            }

        });

        $('#width').keyup(function() {
            calcularPeso()
        });
        $('#length').keyup(function() {
            calcularPeso()
        });
        $('#height').keyup(function() {
            calcularPeso();
        });

        $('#width').keydown(function() {
            calcularPeso()
        });
        $('#length').keydown(function() {
            calcularPeso()
        });
        $('#height').keydown(function() {
            calcularPeso();
        });

        $('#width').focusout(function() {
            calcularPeso()
        }).focusout();
        $('#length').focusout(function() {
            calcularPeso()
        });
        $('#height').focusout(function() {
            calcularPeso();
        });

        $('#weight').focusout(function() {
            var weight = ($('#width').val() * $('#length').val() * $('#height').val()) / 5000;
            if ($("#weight").val() < Math.ceil(weight)) {
                $("#weight").val(Math.ceil(weight));
                $("#weight").attr({
                    "min": Math.ceil(weight)
                });
            }
        });

        function calcularPeso() {
            if ($('#width').val()) {
                if ($('#length').val()) {
                    if ($('#height').val()) {
                        const weight = ($('#width').val() * $('#length').val() * $('#height').val()) / 5000;
                        console.log(weight);
                        $("#weight").val(Math.ceil(weight));
                        $("#weight").attr({
                            "min": Math.ceil(weight)
                        });
                    }
                }
            }
        }

        function sobre() {
            $('#form_paquete').hide();
            $('#description_text').text('Descripcion de contenido en el sobre');
            $('#btn_continuar').show();
            $('#descripcion_contenido').show();
            $('#btn_paquete').css('background', '#4c4c4c');
            $('#btn_sobre').css('background', '#007bff');
            $('#width').val('24');
            $("#width").prop('required', true);
            $('#length').val('30');
            $("#length").prop('required', true);
            $('#height').val('1');
            $("#height").prop('required', true);
            $('#weight').val('1');
            $("#weight").prop('required', true);
        }

        function paquete() {
            $('#form_paquete').show();
            $('#description_text').text('Descripcion de contenido en el paquete');
            $('#btn_continuar').show();
            $('#descripcion_contenido').show();
            $('#btn_paquete').css('background', '#007bff');
            $('#btn_sobre').css('background', '#4c4c4c');
        }

        $(document).on('click', '#envelope_btn', function(e) {
            e.preventDefault();
            if (!$("#package_sizes").hasClass('d-none')) {
                $("#package_sizes").addClass('d-none');
                reInitPackageSizes();
            }

            $("#rate_container").removeClass('d-none');
        });

        $(document).on('click', '#package_btn', function(e) {
            e.preventDefault();
            clearPackageSize();
            $("#package_sizes").removeClass('d-none');
            $("#rate_container").removeClass('d-none');
        });

        function reInitPackageSizes() {
            $("#width").val(24);
            $("#length").val(30);
            $("#height").val(1);
            $("#weight").val(1);
        }

        function clearPackageSize() {
            $("#width").val(0);
            $("#length").val(0);
            $("#height").val(0);
            $("#weight").val(0);
        }
    });
</script>
@endpush