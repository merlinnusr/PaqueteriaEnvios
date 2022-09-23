<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CuponService;
use App\Http\Requests\CuponPostRequest;
class CuponController extends Controller
{
    public function create(CuponPostRequest $request)
    {
        $validatedRequest = (object)$request->validated();
        $price = NULL;
        if($validatedRequest->for == 'picking'){
            $price = (new CuponService())->getDimensiones($validatedRequest->price)['precio'];

        }
 
   
        $checkCupon = (new CuponService)->checkCupon(
            $validatedRequest->cupon,
            $validatedRequest->for,
            $validatedRequest->price
        );

        return response()->json($checkCupon);

    }
}
