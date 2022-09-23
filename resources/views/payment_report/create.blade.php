@extends('layouts.app')
@section('content')

    <section class="shipping_resume my-4">
        <div class="col-md-12">
            <div class="card hover-shadow">
                <div class="card-body">
                    <div class="row">
                        <form action="{{ route('payment_report.store') }}" class='dropzone' method="POST"
                            id="paymentReportForm">
                            <div class="form-group">
                                <label for="folio">Folio</label>
                                <input type="text" class="form-control" name="folio" placeholder="Folio" required>
                            </div>
                            <div class="form-group">
                                <label for="amount">Monto</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="text" class="form-control" id="amount" name="amount"
                                        aria-label="Amount (to the nearest dollar)" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payment_place">Lugar de deposito</label>
                                <select class="form-control" id="paymentPlaces" name="paymentPlaces" required>
                                    @foreach ($paymentPlaces as $payment_place)
                                        <option name="{{ $payment_place->id }} " value="{{ $payment_place->id }}">
                                            {{ $payment_place->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row">

                                <div class="form-group col-md-9">
                                    <label for="paymentServiceCategories">Categoría de servicio</label>
                                    <select class="form-control" id="paymentServiceCategories"
                                        name="paymentServiceCategories" required>
                                        @foreach ($serviceCategories as $serviceCategory)
                                            <option name="{{ $serviceCategory->id }}"
                                                value="{{ $serviceCategory->id }}">
                                                {{ $serviceCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="paymentDate">Fecha de pago:</label>

                                    <input type="date" class="form-control" id="paymentDate" name="paymentDate" value=""
                                        required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <div class='content my-2'>
                                        <!-- Dropzone -->
                                        <label for="">Subir foto</label>

                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-row-reverse my-3">
                                <button type="submit" id="upload_evidence" class="btn btn-success">Reportar pago</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"
        integrity="sha512-VQQXLthlZQO00P+uEu4mJ4G4OAgqTtKG1hri56kQY1DtdLeIqhKUp9W/lllDDu3uN3SnUNawpW7lBda8+dSi7w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Dropzone.autoDiscover = false;
        $(document).ready(function() {
            // Add restrictions
            Dropzone.options.myDropzone = {
                init: function() {
                    this.on("complete", function(file) {
                        console.log('Done!');
                    });
                    this.on("addedfile", function(file) {
                        if (!confirm("Do you want to upload the file?")) {
                            this.removeFile(file);
                            return false;
                        }
                    });
                }
            };
            var myDropzone = new Dropzone(".dropzone", {
                maxFilesize: 8, // 3 mb
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                quality: 0.6,
                autoProcessQueue: false,
                // Convert ALL PNG images to JPEG
                convertSize: 0,
                parallelUploads: 10, // Number of files process at a time (default 2)
                dictDefaultMessage: "Arrastra tu imagen de recibo aqui, o has click aqui",

                success: function(file, response) {
                    console.log(response);
                    Swal.fire(
                        'El reporte fue guardado',
                        response.response,
                        'success',
                    )
                }

            });
            myDropzone.on("sending", function(file, xhr, formData) {
                // formData.append("amount", document.getElementById('amount'));
                // formData.append("folio", document.getElementById('folio'));
                // formData.append("paymentServiceCategories", document.getElementById('paymentServiceCategories'));
                // formData.append("paymentDate", document.getElementById('paymentDate'));
            });
            document.getElementById('upload_evidence').addEventListener('click', function() {

            });
            $("#paymentReportForm").validate({
                rules: {
                    paymentServiceCategories: {
                        required: true
                    },
                    amount: {
                        required: true,
                    },
                    paymentDate: {
                        required: true,
                    },
                },
                submitHandler: function(form, e) {
                    e.preventDefault();
                    myDropzone.processQueue();

                    // $.ajax({
                    //     url: form.action,
                    //     type: form.method,
                    //     data: $(form).serialize(),
                    //     success: function(resp) {
                    //         Swal.fire(
                    //             'El reporte fue guardado',
                    //             'Hemos avisado a dagpacket de tu reporte',
                    //             'success'
                    //         )
                    //     }
                    // });

                }
            });
        });
    </script>
@endpush
