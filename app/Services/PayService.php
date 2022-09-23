<?php

namespace App\Services;

class PayService
{
    public $cart;
    public function __construct($cart)
    {
        $this->cart = $cart;
    }    
    public function getTotal()
    {
        $total = 0;
        foreach($this->cart as $cart){
            $total += $cart->amount;
        }
        return number_format_two_digits($total);
    }
}
