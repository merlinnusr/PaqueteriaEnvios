@extends('layouts.app')
@section('content')

<div class="container">
    
    <div class="col-xs-12 col-sm-8 ng-binding my-2">
        <h3 class="display-4">
            🧮 Cotizaciones
        </h3>
    </div>
    <div class="offset-md-8 col-md-4 col-sm-4 text-center my-2">
        <form method="get" action="{{route('quote.index')}}">
            @csrf
            <button type="submit" onClick="this.form.submit(); this.disabled=true; this.innerHtml='Espere'; "  value="information_quote" name="information_quote" class="btn btn-primary boton_naranja">
                Crea otra cotizacion
            </button>
        </form>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($rates->results as $rate)
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <strong>{{$rate->provider}}</strong> | {{number_local_format($rate->amount)}}
                        </h5>
                        <div class="card-text">
                            <p>📕 {{ucfirst($rate->servicelevel)}}</p>

                           <p> 📆 {{ strip_tags($rate->duration_terms) }} </p>
                        </div>

                        <form method="post" action="{{route('shipment.details')}}">
                            @csrf
                            <input hidden id="provider_name" name="provider_name" value="{{$rate->provider}}">
                            <input hidden id="duration_terms" name="duration_terms" value="{{$rate->duration_terms}}">
                            <input hidden id="service_level" name="service_level" value="{{$rate->servicelevel}}">
                            <input hidden id="amount" name="amount" value="{{$rate->amount}}">
                            <input hidden id="rate_id" name="rate_id" value="{{$rate->object_id}}">

                            <div class="mx-auto">
                                <button type="submit" onClick="this.form.submit(); this.disabled=true; this.innerHtml='Espere'; "  value="pedido" name="pedido" class="btn btn-primary boton_naranja btnRate" 
                                    style="color: white; border-color:transparent;">
                                    Seleccionar
                                </button>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection


@push('scripts')
<script>
    $(document).ready(function() {
        $(document).on('click', ".btnRate", function(){
            
            $('body').addClass('overlay');
            $('.btn').addClass( "d-none" );
            $( this ).submit();

        });
    });
    
</script>
@endpush