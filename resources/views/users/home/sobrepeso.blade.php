@extends('layouts.app')
@section('content')

<section class="shipping_resume my-4">
    <div class="col-md-12">
        <div class="card hover-shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-1 h1 ">
                            Bienvenido: {{auth()->user()->name}}</h4>
                        <h1 class="mb-3 h1 ">Envios con Sobrepeso</h1>
                        <a href="{{route('logs.index')}}" class="btn blue-btn text-white btn-rounded " style="margin-left: 49px;">
                            Movimientos 
                            <i class="fas fa-list-ol"></i>
                        </a>
                        <a href="{{route('logs.sobrepeso')}}" class="btn blue-btn text-white btn-rounded disabled" style="margin-left: 49px;">
                            Sobrepesos 
                            <i class="fas fa-weight-hanging"></i></i>
                        </a>
                        <a href="{{route('logs.cancelados')}}" class="btn blue-btn text-white btn-rounded " style="margin-left: 49px;">
                            Cancelados 
                            <i class="fas fa-trash-restore-alt"></i>
                        </a>
                        <a href="{{route('logs.manuales')}}" class="btn blue-btn text-white btn-rounded " style="margin-left: 49px;">
                            Envios Especiales
                            <i class="fas fa-box-open"></i>
                        </a>
                    </div>
                    <div class="d-flex flex-row-reverse">
                        <a href="{{route('quote.index')}}" class="btn blue-btn text-white btn-rounded" style="margin-left: 49px;">
                            Hacer envio
                            <i class="fas fa-shipping-fast"></i>
                        </a>
                      </div>                      
                    <div class="col-md-12 ">
                        <table class="table table-striped table-hover hover-shadow responsive nowrap"   id="dt">
                            <caption>
                                Lista de movimientos en su cuenta
                            </caption>
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Origen-Destino</th>
                                    <th scope="col">Costo</th>
                                    <th scope="col">Costo Extra</th>
                                    <th scope="col">Paquetería</th>
                                    <th scope="col">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listado as $item)
                                    <tr>
                                        <th scope="col">{{$item->packet_id}}</th>
                                        <th scope="col">{{substr($item->created_at,0,10)}}</th>
                                        <th scope="col">
                                            <div class="text-center">
                                                {{$item->zipcode_from}} -> {{$item->zipcode_to}}<br />
                                                {{$item->ciudad_from}} -> {{$item->ciudad_to}}
                                            </div>
                                        </th>
                                        <th scope="col">${{$item->original_price}}</th>
                                        <th scope="col">${{$item->extra_price}}</th>
                                        <th scope="col">{{$item->rate_provider}}</th>
                                        <th scope="col">
                                            <div class="{{(strlen($item->estatus)>0)?'text-danger':'text-warning'}}">
                                                {{(strlen($item->estatus)>0)?'Aplicado':'Pendiente'}}
                                                <br />
                                                <small class="small text-dark">{{(strlen($item->estatus)>0)? substr($item->estatus,0,10) :''}}</small>
                                            </div>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- USER:{{$id}} --}}
    <br>
    {{-- {{print_r($listado)}} --}}
</section>

@endsection


@push('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.3.1/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/date-1.0.3/fc-3.3.2/fh-3.1.8/r-2.2.7/sc-2.0.3/datatables.min.js"></script>

<script>
    // A $( document ).ready() block.
    $(document).ready(function() {
        /*
        var table = $('#dt').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('logs.datatables.index') }}",
            order: [[0, "desc"]],
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'message',
                    name: 'Movimiento'
                },
            ]
        });
        */
    });
</script>
@endpush