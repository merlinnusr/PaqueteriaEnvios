<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
class AddressController extends Controller
{
    public function show()
    {
        
        $originAddresses = Address::where('user_id', auth()->id())->where('rute', 0)->get();
        $destinationAddresses = Address::where('user_id', auth()->id())->where('rute', 1)->get();
        
        $addresses = [
            'originAddresses' => $originAddresses,
            'destinyAddresses' => $destinationAddresses 
        ];
        return response()->json($addresses);


    }
    public function createOrigin(Request $request)
    {
        try {
            //code...
            $address = [
                'address_info' => json_encode($request->all()),
                'user_id' => auth()->id(),
                'rute' => $request->ruta
            ];
            
            $address = Address::create($address);
            return response()->json([
                'status' => 'success',
                'response' => json_encode($address)
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 'error',
                'response' => $th->getMessage()
            ]);

        }

    }
    public function createDestination(Request $request)
    {
        try {
            //code...
            $address = [
                'address_info' => json_encode($request->all()),
                'user_id' => auth()->id(),
                'rute' => $request->ruta
            ];
            $address = Address::create($address);
            return response()->json([
                'status' => 'success',
                'response' => json_encode($address)
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 'error',
                'response' => 'Hubo un error al guardar el domicilio'
            ]);

        }

    }

}
