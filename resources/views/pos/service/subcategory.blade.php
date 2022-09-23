@extends('layouts.app')
@section('content')

    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12 mx-2">
                <div class="card">
                    <div class="card-body">
                        <div class="row ">
                            @foreach ($subcategories as $subcategory)
                                <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-3 my-2">
                                    <a href='{{ route('pos.service.product', $subcategory) }}'>
                                        <div class="card dagpacket_purple">
                                            <div class="card-body">
                                                <p class="card-text text-white"> <?= $subcategory ?> </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
