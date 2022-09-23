@extends('layouts.app')
@section('content')
    <style>
        span.name {

            display: block;
            text-align: center;
            margin-top: 16px;
            margin-bottom: -16px;
            font-weight: 400;
            font-size: 18px;

        }

        small {
            display: block;
        }

        h2 {
            font-size: 22px;
            margin-bottom: 22px;
        }

        .paso {
            padding: 20px;
            border: 1px solid #e0e0e0;
        }

        .img-fluid {
            width: 100% !important;
            height: auto;
        }

        .drop-zone {
            width: 100%;
            height: 200px;
            padding: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-family: "Quicksand", sans-serif;
            font-weight: 500;
            font-size: 20px;
            cursor: pointer;
            color: #cccccc;
            border: 4px dashed #009578;
            border-radius: 10px;
        }

        .drop-zone--over {
            border-style: solid;
        }

        .drop-zone__input {
            display: none;
        }

        .drop-zone__thumb {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
            background-color: #cccccc;
            background-size: contain;
            position: relative;
            background-repeat: no-repeat;
            background-position: center;
        }

        .drop-zone__thumb::after {
            content: attr(data-label);
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 5px 0;
            color: #ffffff;
            background: rgba(0, 0, 0, 0.75);
            font-size: 14px;
            text-align: center;
        }

    </style>

    <section class="shipping_resume my-4">
        <!--Datos para el select de la oficina -->
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-error">{{ $error }}</div>
            @endforeach
        @endif

        <div class="container bg-light jumbotron">
            <form method="post" action="{{ route('picking.store') }}" enctype="multipart/form-data">
                @csrf
                <section class="paso activeView">
                    <h2>Datos de tu cliente (El Destinatario)</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre:</label>
                                <input type="text" class="form-control" value="" placeholder="nombre" required
                                    name="nombre">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Celular:</label>
                                <input type="text" class="form-control" value="" placeholder="celular" required
                                    name="celular">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Correo:</label>
                                <input type="mail" class="form-control" value="" placeholder="correo" required
                                    name="correo">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contenido del paquete:</label>
                                <input type="text" class="form-control" value="" required placeholder="contenido" required
                                    name="contenido">
                            </div>
                        </div>
                    </div>
                </section>
                <section class="paso d-none">
                    <h2>Ingresa las dimenciones del paquete</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <p>NOTA 1. Tu cliente tiene hasta el final del dia hábil para recoger su paquete, de lo
                                    contrario se le hará un cargo por cada dia de retraso. </p>
                                <p>NOTA 2. Si el tipo de paquete no coincide con el recepcionado en paqueteria pagaras la
                                    diferencia en efectivo. Por favor ingresa los datos correctamente . </p>

                                <label>Elige el tamaño del paquete</label><br>
                                <div class="row">
                                    <label class="col- col-md-3">
                                        <input type="radio" name="paquete" value="1" checked>
                                        <img class="img-fluid=" src="{{ asset('assets/images/envelope.png') }}">
                                        <span class="name">Carta o sobre</span>
                                        <h4 class="text-center d-block font-weight-bold my-3">MXN $10 </h4>
                                    </label>

                                    <label class="col- col-md-3">
                                        <input type="radio" name="paquete" value="2">
                                        <img class="img-fluid=" src="{{ asset('assets/images/box_20.png') }}">
                                        <span class="name">Paquete de hasta 20cm x 20cm x 20cm</span>
                                        <h4 class="text-center d-block font-weight-bold my-3">MXN $10 </h4>

                                    </label>
                                    <label class="col- col-md-3">
                                        <input type="radio" name="paquete" value="3">
                                        <img class="img-fluid=" src="{{ asset('assets/images/box_30.png') }}">
                                        <span class="name">Paquete de hasta 30cm x 30cm x 30cm</span>
                                        <h4 class="text-center d-block font-weight-bold my-3">MXN $13 </h4>
                                    </label>
                                    <label class="col- col-md-3">
                                        <input type="radio" name="paquete" value="4">
                                        <img class="img-fluid=" src="{{ asset('assets/images/box_30.png') }}">
                                        <span class="name">Paquete de hasta 60cm x 60cm x 60cm</span>
                                        <h4 class="text-center d-block font-weight-bold my-3">MXN $20 </h4>
                                    </label>
                                </div>
                            </div>
                            <p class="alert alert-warning text-center">No se aceptan paquetes mas grandes</p>

                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <small>Max: 3mb</small>
                                <label>Foto del paquete:</label>
                                <div class="drop-zone">
                                    <span class="drop-zone__prompt">Agrega aqui tu foto <small>(jpg, png y
                                            gif)</small></span>
                                    <input type="file" accept="image/x-png,image/gif,image/jpeg" required name="foto"
                                        class="drop-zone__input">
                                </div>
                            </div>

                        </div>
                </section>
                <section class="paso d-none">
                    <h2>Confirmación</h2>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Ingresa tu cupón</label>
                                <input type="text" name="cupon" class="form-control">
                            </div>
                            <button type="button" id="applyCoupon" class="btn btn-sm btn-primary mb-3">Aplicar
                                cupón</button>
                            <div id="cupon-response"></div>
                            <p>1. Da clic en Realizar pedido</p>
                            <p>2. El costo se descontara de tu saldo, corrobora que tengas suficiente saldo</p>
                            <p>3. Una vez realizado el pedido imprime tu recibo y entregalo junto con tu pedido en la
                                sucursal mas cercana</p>

                            <button type="submit" onClick="this.form.submit(); this.disabled=true; this.innerHtml='Espere'; " class="mt-5 btn btn-block btn-primary bo boton_naranja">Realizar
                                pedido</button>
                        </div>
                    </div>
                </section>
                @if ($errors->any())
                    <h4 class="alert alert-error">{{ $errors->first() }}</h4>
                @endif
                <div class="d-flex flex-row-reverse">
                    <div class="py-5 text-right">
                        <button type="button" id="back" class="btn btn-sm btn-secondary">Regresar</button>
                        <button type="button" id="next" class="btn btn-sm btn-primary boton_naranja">Siguiente</button>
                    </div>
                </div>

            </form>
        </div>
    </section>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"
        integrity="sha512-VQQXLthlZQO00P+uEu4mJ4G4OAgqTtKG1hri56kQY1DtdLeIqhKUp9W/lllDDu3uN3SnUNawpW7lBda8+dSi7w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '#applyCoupon', function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                if ($(this).hasClass('disabled')) {
                e.preventDefault();
                    return;
                }
                $(this).addClass('disabled');
                cupon = $('input[name=cupon]')[0].value.toUpperCase();
                dimensiones = $('input[name=paquete]:checked')[0].value;
                jQuery.ajax({
                    type: 'POST',
                    url: "{{ route('cupon.create') }}",
                    data: {
                        cupon: cupon,
                        price: dimensiones,
                        for: 'picking'

                    },

                    success: function(data, success) {
                        $(this).removeClass('disabled')
                        if (data['response'] == true) {
                            $('#cupon-response').html(
                                `<div class="alert alert-info">El cupon es válido: Precio con descuento $${data['precio']} </div>`
                            );
                        } else {
                            $('#cupon-response').html(
                                `<div class="alert alert-danger">El cupon no es válido`);
                        }

                    }
                });
            });
            
            var views = $('.paso');
            views.map((index, view)=> {
                console.log(index);
                $(view).data( "index", index );
            });
            $(document).on('click', '#next', function(){
                let currentViewIndex = $('.activeView').data('index');
                let newViewIndex = currentViewIndex + 1; 
                if(newViewIndex < views.length){
                    console.log($(views)[0]);
                    $(views[currentViewIndex]).removeClass('activeView');                    
                    $(views[currentViewIndex]).addClass('d-none');
                    $(views[currentViewIndex+1]).addClass('activeView');
                    $(views[currentViewIndex+1]).removeClass('d-none');

                }
            });
            $(document).on('click', '#back', function(){
                let currentViewIndex = $('.activeView').data('index');
                let newViewIndex = currentViewIndex - 1; 
                if(newViewIndex >= 0){
                    console.log($(views)[0]);
                    $(views[currentViewIndex]).removeClass('activeView');                    
                    $(views[currentViewIndex]).addClass('d-none');
                    $(views[newViewIndex]).addClass('activeView');
                    $(views[newViewIndex]).removeClass('d-none');

                }
            });

            document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
                const dropZoneElement = inputElement.closest(".drop-zone");

                dropZoneElement.addEventListener("click", (e) => {
                    inputElement.click();
                });

                inputElement.addEventListener("change", (e) => {
                    if (inputElement.files.length) {
                        updateThumbnail(dropZoneElement, inputElement.files[0]);
                    }
                });

                dropZoneElement.addEventListener("dragover", (e) => {
                    e.preventDefault();
                    dropZoneElement.classList.add("drop-zone--over");
                });

                ["dragleave", "dragend"].forEach((type) => {
                    dropZoneElement.addEventListener(type, (e) => {
                        dropZoneElement.classList.remove("drop-zone--over");
                    });
                });

                dropZoneElement.addEventListener("drop", (e) => {
                    e.preventDefault();

                    if (e.dataTransfer.files.length) {
                        inputElement.files = e.dataTransfer.files;
                        updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
                    }

                    dropZoneElement.classList.remove("drop-zone--over");
                });
            });

            /**
             * Updates the thumbnail on a drop zone element.
             *
             * @param {HTMLElement} dropZoneElement
             * @param {File} file
             */
            function updateThumbnail(dropZoneElement, file) {
                let thumbnailElement = dropZoneElement.querySelector(".drop-zone__thumb");

                // First time - remove the prompt
                if (dropZoneElement.querySelector(".drop-zone__prompt")) {
                    dropZoneElement.querySelector(".drop-zone__prompt").remove();
                }

                // First time - there is no thumbnail element, so lets create it
                if (!thumbnailElement) {
                    thumbnailElement = document.createElement("div");
                    thumbnailElement.classList.add("drop-zone__thumb");
                    dropZoneElement.appendChild(thumbnailElement);
                }

                thumbnailElement.dataset.label = file.name;

                // Show thumbnail for image files
                if (file.type.startsWith("image/")) {
                    const reader = new FileReader();

                    reader.readAsDataURL(file);
                    reader.onload = () => {
                        thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
                    };
                } else {
                    thumbnailElement.style.backgroundImage = null;
                }
            }
        });

    </script>

@endpush
