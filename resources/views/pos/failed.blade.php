@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card my-5">
                    <img class="check_mark mx-auto" src="{{ asset('/assets/images/error.png') }}" alt="Card image cap"
                        style="
                        width: 145px;">
                    <div class="card-body">
                        <div class="card-title">Error:</div>
                        <a class="btn dagpacket_orange" href="{{ route('pos.index') }}">Volver</a>
                        <div class="card-text">
                            <!--
                                Method Response
                                    {{ isset($MethodResponse) ? $MethodResponse : 'gj' }}  ?>
                                    {{ isset($ExecutionTime) ? $ExecutionTime : 'noagy' }}  ?>
                                    ---
                                    
                                    {{ isset($responses) ? $responses : 'noagy' }} ?>
                                    {{ isset($jsonResponse) ? $jsonResponse : 'fsa' }}  ?> 

                                -->
                            <label class="font-weight-bold" for="responseCode">Codigo de error</label>
                            <p class="responseCode"> {{ (string) $data['errors']['ResponseCode'] }} </p>
                            <label class="font-weight-bold" for="responseMessage">Mensaje de error</label>
                            <p class="responseMessage"> {{ (string) $data['errors']['ResponseMessage'] }}</p>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
