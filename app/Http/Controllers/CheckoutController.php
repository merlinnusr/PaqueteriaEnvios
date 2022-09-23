<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CheckoutPostRequest;

class CheckoutController extends Controller
{
    public function index(CheckoutPostRequest $request)
    {

        if ($request->session()->has('pre_cart')) {
            $validatedRequest = $request->validated();
            $preCart = NULL;
            if ($request->session()->has('pre_cart')) {
                $preCart = session('pre_cart');
                $lastIndex = key(array_slice($preCart, -1, 1, true));
                foreach ($validatedRequest as $key =>  $item) {
                    $preCart[$lastIndex][$key] = $item;
                }
            }
            if ($request->session()->has('cart') && $request->session()->has('pre_cart')) {

                $cart = session('cart');
                $cart[] = $preCart[$lastIndex];
                session(['cart'=> $cart]);
                //dd(session('cart'));
            } else {

                session(['cart' => $preCart]);
            }
            $request->session()->forget('pre_cart');
            return redirect()->route('checkout.show');
            // return redirect('users.checkout.index', [
            //     'shipments' => json_decode(json_encode(session('cart')))
            // ]);
        }
        if ($request->session()->has('cart')) {
            // return view('users.checkout.index', [
            //     'shipments' => json_decode(json_encode(session('cart')))
            // ]);
            return redirect()->route('checkout.show');
        }
    }
    public function show()
    {
        return view('users.checkout.index', [
            'shipments' => session('cart')
        ]);
    }
}
