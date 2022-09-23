<?php

use App\Models\InvoiceNumber;
use App\Models\User;

if (!function_exists("get_balance")) {

    function get_balance()
    {
        $id = auth()->user()->id;
        $balance = User::select('wallet')->where('id', $id)->first()->wallet;
        return "$ " . number_format($balance, 2) . " MXN";
    }
}

if (!function_exists("number_local_format")) {

    function number_local_format($amount)
    {
        return "$ " . number_format($amount, 2) . " MXN";
    }
}

if (!function_exists("number_format_two_digits")) {

    function number_format_two_digits($amount)
    {
        return number_format($amount, 2);
    }
}

if (!function_exists("ganancia")) {
    function  ganancia($paqueteria)
    {
        $paqueterias = [

            'DHL',
            'Estafeta',
            'FedEx'
        ];
        $precioEspacial = [
            'Redpack',
            'Flecha Amarilla',
        ];
        if (in_array($paqueteria, $precioEspacial)) {
            return 1.6;
        } else {
            return 1.3;
        }
    }
}
if (!function_exists("diffDays")) {
    function diffDays($fecha_clave, $hoy)
    {
        $fecha_clave = strtotime(date('Y-m-d', $fecha_clave));
        $hoy = strtotime(date('Y-m-d', $hoy));

        $days = 0;
        if ($fecha_clave >= $hoy) {
            return 0;
        }

        while ($fecha_clave < $hoy) {
            $fecha_clave += 86400;

            if (strtotime('Sunday this week', $fecha_clave) != $fecha_clave) {
                $days++;
            }
        }
        return $days;
    }
}

if (!function_exists("money_to_local_format")) {
    function money_to_local_format($amount){
        return "$ ".number_format($amount, 2)." MXN."; 
    }
    
}

if (!function_exists('generate_invoice_number')) {
    function generate_invoice_number()
    {
        $lastInvoiceNumber = InvoiceNumber::latest()->first();
        $newInvoiceNumber = isset($lastInvoiceNumber->invoice_number) ? intval($lastInvoiceNumber->invoice_number) + 1 : 1;
        $invoiceNumber = InvoiceNumber::create(['invoice_number' => $newInvoiceNumber]);
        return $invoiceNumber->invoice_number;
    }
}