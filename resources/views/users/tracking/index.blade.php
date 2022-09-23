@extends('layouts.app')
@section('content')

    <div class="">
        <div class="container bg-light jumbotron my-5">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="widget p-3">
                        <div class="row" style="margin-bottom: 20px;">
                            <a class="btn btn-success col-2" href="{{ route('home') }}">Regresar</a>

                            <button 
                                data-id="{{ $shipment->purchase_number }}" 
                                 data-mdb-toggle="modal"
                                data-mdb-target="#cancelGuiaFormModal"
                                 id="cancelGuia"
                                class="btn btn-outline-danger col-2 offset-md-8">Cancelar guía</button>
                        </div>
                        @if (session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                            @endif <div class="row" style="margin-bottom: 20px;">
                                <center class="col-12">
                                    <h4 class="widget-title">Detalles de envío</h4>
                                </center>
                            </div>

                            <div class="summary-block">
                                <div class="summary-content">
                                    <div class="summary-price">
                                        <p class="summary-text">
                                            <strong style="font-size: 20px;">Origen: </strong>
                                            {{ $shipment->zipcode_from }} -
                                            {{ $shipment->street_from }} # {{ $shipment->numero_from }}
                                            {{ $shipment->numero_int_from == '' ? '' : ', Int. ' . $shipment->numero_int_from . ', ' }}
                                            {{ $shipment->ciudad_from }}, {{ $shipment->estado_from }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="summary-block">
                                <div class="summary-content">
                                    <div class="summary-price">
                                        <p class="summary-text">
                                            <strong style="font-size: 20px;">Destino: </strong>
                                            {{ $shipment->zipcode_to }} -
                                            {{ $shipment->street_to }} #{{ $shipment->numero_to }}
                                            {{ $shipment->numero_int_to == '' ? '' : ', Int. ' . $shipment->numero_int_to . ', ' }}
                                            {{ $shipment->ciudad_to }} , {{ $shipment->estado_to }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="summary-block">
                                <div class="summary-content">
                                    <div class="summary-price">
                                        <p class="summary-text">
                                            <strong style="font-size: 20px;">Datos del paquete: </strong>
                                            Alto: {{ $shipment->height }} cm / Largo: {{ $shipment->length }} cm /
                                            Ancho: {{ $shipment->width }} cm / Peso: {{ $shipment->weight }} kg
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="summary-block">
                                <div class="summary-content">
                                    <div class="summary-price">
                                        <p class="summary-text">
                                            <strong style="font-size: 20px;">Total: </strong>$
                                            {{ number_format(ceil($shipment->costo_extra * 1.16 + $shipment->amount), 2, '.', '') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @isset($shipment->tracking_label_url)
                                <div class="summary-block">
                                    <div class="summary-content">
                                        <div class="summary-price">
                                            <p class="summary-text">
                                                <strong style="font-size: 20px;">Guia: </strong>
                                                <a href="{{ $shipment->tracking_label_url }}" download>
                                                    <i class="fas fa-file-pdf" class="fa-2x" style="width: 50px;"></i>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endisset
                            @if ($shipment->rate_provider != 'Estafeta' || $shipment->rate_provider != 'Flecha Amarilla')
                                <div>
                                    <div>
                                        <div class="row" style="margin-bottom: 20px;">
                                            <center class="col-12">
                                                <h4 class="widget-title">Eventos</h4>
                                            </center>
                                        </div>
                                        <table class="table table-striped table-responsiv">
                                            <thead>
                                                <th>Fecha</th>
                                                <th>Detalle</th>
                                                <th>Localización</th>
                                            </thead>
                                            <tbody>
                                                @if ($trackingData != null  && isset($trackingData->events)) 
                                                    @foreach ($trackingData->events as $rows)
                                                        <tr class="">
                                                            <th>
                                                                <p style="padding-bottom:0px" class="ng-binding">
                                                                    {{ date_format(new DateTime($rows->date_time), 'Y/m/d H:i:s') }}
                                                                </p>
                                                            </th>
                                                            <th>
                                                                <p style="padding-bottom:0px" class="ng-binding">
                                                                    {{ $rows->description }}
                                                                </p>
                                                            </th>
                                                            <th>
                                                                <p style="padding-bottom:0px" class="ng-binding">
                                                                    {{ $rows->area }}
                                                                </p>
                                                            </th>
                                                        </tr>
                                                    @endforeach


                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if ($shipment->tracking_url)
                                <div>
                                    <div class="row" style="margin-bottom: 20px;">
                                        <center class="col-12">Enviado por</center>
                                        <center class="col-12">
                                            <img src='http://sandbox.mienvio.mx{{ $shipment->rate_provider_img }}'
                                                style="width: 300px;">
                                        </center>
                                        <center class="col-12">Número de guía: {{ $shipment->tracking_number }} </center>
                                        <center class="col-12"><a href="{{ $shipment->tracking_url }} ">URL de rastreo
                                                por
                                                paqueteria</a></center>
                                    </div>
                                </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="cancelGuiaFormModal" tabindex="-1" role="dialog" aria-labelledby="cancelGuiaFormLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelGuiaFormLabel">Cancelar guía</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('label.delete') }}" method="post" >
                        @csrf
                        <input type="hidden" class="form-control" id="packetId" name="packetId">
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Motivo de cancelación:</label>
                            <textarea class="form-control" id="reason" name="reason"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                              <input type="submit" onClick="this.form.submit(); this.disabled=true; this.innerHtml='Espere'; " class="btn btn-danger" id="btnCancelPacket" value="Confirmación de cancelación de guía">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // A $( document ).ready() block.
        $(document).ready(function() {

            $(document).on('click', '#btnCancelPacket', function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('label.delete')}}", 
                    type: 'POST',
                    data: {
                        packetId: $("#packetId").val(),
                        reason: $("#reason").val()
                    },
                    success: function(result){
                        
                        Swal.fire(
                        'Good job!',
                        result.response,
                        result.status
                        )
                  }});
                
            });

            $(document).on('show.bs.modal','#cancelGuiaFormModal', function (event) {
                const button = $(event.relatedTarget) // Button that triggered the modal
                const id = button.data('id') // Extract info from data-* attributes
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                //var modal = $(this)
                $("#packetId").val(id);
                //modal.find('#packetId').val(id);
                //modal.find('.modal-body input').val(recipient)
            });
        });

    </script>
@endpush
