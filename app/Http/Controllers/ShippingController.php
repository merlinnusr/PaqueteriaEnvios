<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MiEnvio\MiEnvioService;
class ShippingController extends Controller
{
    public function index()
    {
        $addressFrom = [
            "object_type"=> "PURCHASE",
            "name"=> "John Doe",
            "street"=> "Calle 5 de Mayo 206",
            "street2"=> "La cruz",
            "reference"=> "Oficina con portón blanco",
            "zipcode"=> "73905",
            "alias"=> "Warehouse",
            "email"=> "dev@mienvio.mx",
            "phone"=> "4421184040",
        ];
        $addressTo = [
            "object_type"=> "PURCHASE",
            "name"=> "John Doe",
            "street"=> "Calle 5 de Mayo 206",
            "street2"=> "La cruz",
            "reference"=> "Oficina con portón blanco",
            "zipcode"=> "73905",
            "alias"=> "Warehouse",
            "email"=> "dev@mienvio.mx",
            "phone"=> "4421184040",
        ];

        $createdAddressFromResponse = (new MiEnvioService())->createAddress($addressFrom);
        $createdAddressToResponse = (new MiEnvioService())->createAddress($addressTo);
        
        
       $packageDetails = [
            'width'  => "2",
            'length' => "2",
            'height' => "2",
            'weight' => "2",
            'description' => 'Cafe'
       ];
        $shipment = [
            'object_purpose' => 'QUOTE',
            'address_from'   => $createdAddressFromResponse->address->object_id,
            'address_to'     => $createdAddressToResponse->address->object_id,
            'width'  => $packageDetails['width'],
            'length' => $packageDetails['length'],
            'height' => $packageDetails['height'], 
            'weight' => $packageDetails['weight'],
            'description' => $packageDetails['description'],
            "declared_value"=> "2100",
            "qty" => "1",
        ];
        $createdShipmentResponse = (new MiEnvioService())->createShipment($shipment);
        // $createdShipmentResponse->response->
        // return var_dump($createdShipmentResponse);
    }
}
