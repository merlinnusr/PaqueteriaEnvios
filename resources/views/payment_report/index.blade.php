@extends('layouts.app')
@section('content')

    <section class="shipping_resume my-4">
        <div class="col-md-12">
            <div class="card hover-shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="mb-1 h1 ">
                                <h1 class="mb-3 h1 ">Historial de reporte de pagos</h1>

                        </div>
                        <div class="d-flex flex-row-reverse">
                            <a href="{{ route('payment_report.create') }}" class="btn blue-btn text-white btn-rounded"
                                style="margin-left: 49px;">
                                Crear reporte de pago
                                <i class="fas fa-atlas"></i> </a>
                        </div>

                        <div class="col-md-12 ">
                            <table class="table table-striped table-hover hover-shadow responsive nowrap" id="dt">
                                <caption>
                                    Lista de reportes de pago
                                </caption>
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Folio</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">Tipo de servicio</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Accion</th>

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
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cancelar reporte</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" id="report_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger" id="cancelBtnModal">Cancelar reporte</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/jq-3.3.1/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/date-1.0.3/fc-3.3.2/fh-3.1.8/r-2.2.7/sc-2.0.3/datatables.min.js">
    </script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.cancelBtn', function() {
                let id = $(this).data('id');
                $('#report_id').val(id);
            });
            $(document).on('click', '#cancelBtnModal', function() {
                let id = $('#report_id').val();

                $.post('/payment_report/destroy', {
                    id
                }, function(data) {
                    Swal.fire({
                        icon: data.status,
                        text: data.response,
                        footer: '<a class="btn btn-success mx-auto" onClick="document.location.reload(true)">Continuar</a>'
                    })
                });
            });

            
            var table = $('#dt').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('payment_report.list') }}",
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
                        data: 'folio',
                        name: 'folio'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'service_category_id',
                        name: 'service_category_id'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }

                ]
            });
        });
    </script>
@endpush
