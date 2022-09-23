@extends('layouts.app')
@section('content')
    <div id="modals"></div>
    <section class="shipping_resume my-4">
        <div class="col-md-12">
            <div class="card hover-shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="mb-1 h1 ">
                                Bienvenido: {{ auth()->user()->name }}</h4>
                            <h1 class="mb-3 h1 ">Historial de envios</h1>

                        </div>
                        <div id="fade"></div>
                        <div class="d-flex flex-row-reverse">
                            <a href="{{ route('quote.index') }}" class="btn blue-btn text-white btn-rounded"
                                style="margin-left: 49px;">
                                Hacer envio
                                <i class="fas fa-shipping-fast"></i>
                            </a>
                        </div>

                        <div class="col-md-12 ">
                            <table class="table table-striped table-hover hover-shadow responsive nowrap" id="dt">
                                <caption>
                                    Lista de envios
                                </caption>
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Numero de compra</th>
                                        <th scope="col">Numero de orden</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Costo</th>
                                        <th scope="col">Origen</th>
                                        <th scope="col">Destino</th>
                                        <th scope="col">Guia</th>
                                        <th scope="col">Recibo</th>
                                        <th scope="col">Acciones</th>

                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                <button type="button" class="btn-close" data-mdb-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">...</div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
                                                    Close
                                                </button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


@push('scripts')

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/jq-3.3.1/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/date-1.0.3/fc-3.3.2/fh-3.1.8/r-2.2.7/sc-2.0.3/datatables.min.js">
    </script>
    <script>
        // A $( document ).ready() block.
        $(document).ready(function() {
            // build your modal content


            $.ajax({
                type: "GET", // la variable type guarda el tipo de la peticion GET,POST,..
                url: "/notifications", //url guarda la ruta hacia donde se hace la peticion
                crossDomain: true,

                success: function success(notifications) {
                    notifications.response.forEach((dato, index) => {
                        console.log(dato);
                        nextID = dato.nextId;


                        $("#modals").append(`<div class="modal fade show" id="exampleModal-${dato.id}" tabindex="-1" aria-labelledby="exampleModalLabel-${dato.id}" style="padding-right: 17px; display: block;" aria-modal="true" role="dialog">

                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel-${dato.id}">Notificacion</h5>
                                <button
                                type="button"
                                class="btn-close"
                                data-mdb-dismiss="modal-${dato.id}"
                                aria-label="Close"
                                ></button>
                            </div>
                            <div class="modal-body">
                                <img style="width: 100%;" src="https://servicios.dagpacket.org/${dato.photo}" />
                                ${dato.message}    
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary ripple-surface" data-mdb-dismiss="modal-${dato.id}" onclick="">
                                                    Cerrar
                                </button>
                            </div>
                            </div>
                        </div>
                        </div>`);
                        $(document).on('click',`#exampleModal-${dato.id}`,function(){
                            $(`#exampleModal-${dato.id}`).modal('hide');
                        })

                        $(`#exampleModal-${dato.id}`).modal('show');

                    })
                }
            });
            var table = $('#dt').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('shipment.show') }}",
                order: [
                    [0, "desc"]
                ],
                language: {
                    processing: '<div class="spinner-border text-success" role="status"> <span class="sr-only">Loading...</span> </div>'
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'purchase_number',
                        name: 'purchase_number'
                    },
                    {
                        data: 'object_id_quote',
                        name: 'object_id_quote'
                    },
                    {
                        data: 'fecha',
                        name: 'fecha'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'municipio_from',
                        name: 'municipio_from'
                    },
                    {
                        data: 'municipio_to',
                        name: 'municipio_to'
                    },
                    {
                        data: 'label',
                        name: 'label'
                    },
                    {
                        data: 'invoice',
                        name: 'invoice'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            });
        });
    </script>
@endpush
