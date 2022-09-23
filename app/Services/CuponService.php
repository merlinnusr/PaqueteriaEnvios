<?php

namespace App\Services;

use App\Models\Cupon;
use App\Models\CuponUsed;
use App\Models\User;

class CuponService
{
    public function getDimensiones($price)
    {
        if ($price == 1) {
            return [
                'precio' => 10,
                'dimensiones' => 'carta'
            ];
        }
        if ($price == 2) {
            return [
                'precio' => 10,
                'dimensiones' => '20x20x20'
            ];
        } else if ($price == 3) {
            return [
                'precio' => 13,
                'dimensiones' => '30x30x30'
            ];
        }
        if ($price == 4) {
            return [
                'precio' => 20,
                'dimensiones' => '60x60x60'
            ];
        } else {
            return false;
        }
    }
    public function checkCupon($name, $for, $price, $phone = null)
    {
        //$cupon =   $this->cupones_model->cupon($nombre)[0] ?? false;
        $name = str_replace(' ', '_', strtoupper($name));
        $for = strtolower($for);
        $cupon = Cupon::where('nombre', $name)->first();
        if ($cupon) {
            
            if (isset($cupon->cantidad) && !empty($cupon->cantidad) && $cupon->cantidad < 1) {
                $cupon = false;
            } //comprobando stock

            if (isset($cupon->fecha_caducidad) && !empty($cupon->fecha_caducidad) && $cupon->fecha_caducidad < date('Y-m-d H:i:s')) {

                $cupon = false;
            } // comprobando fecha

            if ($for != 'picking' && $for != 'paqueteria'){

                return dd($cupon);
                $cupon = false;
            }
            if ($for == 'picking' && $cupon->picking == null)
                $cupon = false;
            if ($for == 'paqueteria' && $cupon->paqueteria == null){
                $cupon = false;

            }


            $users = User::join(
                'cupones_usuario',
                'usuarios_b.id',
                '=',
                'cupones_usuario.usuario_id'
            )
                ->where('cupones_usuario.cupon_id', $cupon->id)
                ->get();
            if (count($users) > 0) // si el cupon esta relacionado
            {

                $users = User::join(
                    'cupones_usuario',
                    'usuarios_b.id',
                    '=',
                    'cupones_usuario.usuario_id'
                )
                    ->where('cupones_usuario.cupon_id', $cupon->id)
                    ->where('cupones_usuario.usuario_id', auth()->id())
                    ->get();
                if (empty($users)) {
                    $cupon = false;
                }
            }
        }

        if ( $cupon != false) {
            $cuponUsed = CuponUsed
                ::where('cupon_id', $phone, $cupon->id)
                ->where('telefono', $phone)
                ->first() ?? false;
        } else {

            $cupon = false;
        }




        if (!isset($cuponUsed) || $cuponUsed != false)
            $cupon = false;

        if (!$cupon) {
            return ['response' => false];
            return;
        }
        if ($for == 'picking'){

            $precioRegular = isset($this->getDimensiones($price)['precio']) ? $this->getDimensiones($price)['precio'] : FALSE;
        }
        else{
            
            $precioRegular = $price;
        } // paqueteria

        if(!$precioRegular){
            $precioRegular = $price;

        }
        $conDescuento = '';
        if ($cupon->tipo_cupon == 1) //fijo
        {

            $conDescuento = $precioRegular - $cupon->descuento;
        }
        if ($cupon->tipo_cupon == 2) //porcentaje
        {

            $conDescuento = ($precioRegular / 100) * (100 - $cupon->descuento);
        }
        $conDescuento = ceil($conDescuento);
        $conDescuento =  ($conDescuento < 0) ? 0 : $conDescuento;




        return [
            'response' => true,
            'precio' => $conDescuento,
            'cupon' => $cupon
        ];
    }
}
