@extends('layouts.app')
@section('content')

    <section class="shipping_resume my-4">
        <div class="col-md-12">
            <div class="card hover-shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="mb-3 h1 ">Tus paquetes entregados en oficina</h1>

                        </div>
                        <div class="row justify-content-end mb-3">
                            <div class="col-2 col-example">
                                <a href="{{ route('picking.create') }}" class="btn blue-btn text-white btn-rounded"
                                    style="margin-left: 49px;">
                                    Nueva solicitud
                                    <i class="fas fa-shipping-fast"></i>
                                </a>


                            </div>


                        </div>
                        <div class="col-md-12 ">
                            <table class="table table-striped table-hover hover-shadow" id="dt">
                                <caption>
                                    Lista de envios
                                </caption>
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Correo</th>
                                        <th scope="col">Dimensiones</th>
                                        <th scope="col">Costo</th>
                                        <th scope="col">Fecha de creación</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Recibo</th>

                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
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
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.3.1/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/date-1.0.3/fc-3.3.2/fh-3.1.8/r-2.2.7/sc-2.0.3/datatables.min.js"></script>

    <script>
        // A $( document ).ready() block.
        $(document).ready(function() {

            var table = $('#dt').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('picking.datatables.index') }}",
                order: [
                    [0, "desc"]
                ],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },
                    {
                        data: 'correo',
                        name: 'correo'
                    },

                    {
                        data: 'dimensiones',
                        name: 'dimensiones'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },

                    {
                        data: 'fecha_creacion',
                        name: 'fecha_creacion'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'invoices',
                        name: 'invoices'
                    },
                ]
            });
        });

    </script>
@endpush
