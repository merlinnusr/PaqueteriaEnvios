@extends('layouts.app')
@section('content')
    <div class="container my-2">
        <div class="col-md-6">
            <a class="btn dagpacket_orange" href="{{ route('pos.index') }}">Volver</a>


        </div>
        <div class="col-md-6">

            <button class="btn btn-success my-2" onclick="generatePDF()">Imprimir recibo</button>

        </div>
        <div class="row">


            <div class="col-lg-6 mx-auto">
                <div class="card">
                    <img class="check_mark mx-auto" src="{{ asset('/assets/images/check.jpg') }}" alt="Card image cap"
                        style="
                            width: 145px;">
                    <div class="card-body invoice" id="invoice">
                        <!--
                                        Method Response
                                        {{ isset($MethodResponse) ? $MethodResponse : 'G' }} 
                                        {{ isset($ExecutionTime) ? $ExecutionTime : 'noagy' }}  
                                        {{ isset($jsonResponse) ? $jsonResponse : 'fsa' }}  
                                        -->
                        <div class="card-title">Estatus de pago:</div>
                        <div class="card-text">
                            <label class="font-weight-bold" for="transactionDateTime">Fecha de transacción</label>
                            <p id="transactionDateTime">{{ $data['ticket']['TransactionDateTime'] ?? '12/12/12' }}</p>
                            <!--Producto-->
                            <label class="font-weight-bold" for="productName">Producto</label>
                            <p id="productName">{{ $data['ticket']['ProductName'] ?? 'ERROR' }}</p>
                            <!--end Producto-->

                            <label class="font-weight-bold" for="pin">Cuenta</label>
                            <p class="pin"> {{ $data['ticket']['Pin'] ?? '11111' }} </p>
                            <!--Monto-->
                            <label class="font-weight-bold" for="amount">Monto</label>

                            <p class="amount">
                                {{ isset($data['ticket']['Amount']) ? number_format((float) $data['ticket']['Amount'], 2) : '10' }}
                            </p>

                            <!--end Monto-->

                            <label class="font-weight-bold" for="carrierControlNo">Numero de autorizacion</label>
                            <p id="carrierControlNo">
                                {{ !empty($data['ticket']['CarrierControlNo']) ? $data['ticket']['CarrierControlNo'] : 'N/A' }}
                            </p>

                            <!-- <label class="font-weight-bold" for="invoiceNo">Folio</label>
                                            <p class="invoiceNo">  </p> -->

                            <!--<label class="font-weight-bold" for="transactionId">ID de transacci車n</label>
                                            <p id="transactionId"></p>-->
                            <label class="font-weight-bold" for="responseMessage">Información</label>
                            <p id="responseMessage">
                                {{ $data['ticket']['ResponseMessage'] ?? '11' }}
                            </p>
                            <hr>
                            <p class="text-center lead my-5">
                                Monto:
                                {{ isset($data['ticket']['Amount']) ? number_format($data['ticket']['Amount'], 2) : '10' }}
                            </p>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')

    <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"
        integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js"
        integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        function generatePDF() {
            // Choose the element that our invoice is rendered in.
            const element = document.getElementById("invoice");
            // Choose the element and save the PDF for our user.
            let config = {


                jsPDF: {
                    format: 'b7',
                    orientation: 'portrait'
                }

            }
            html2pdf().set(config)
                .from(element)
                .save();
        }
    </script>
@endpush
