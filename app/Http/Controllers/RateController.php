<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PackageDetailPostRequest;
use App\Services\MiEnvio\MiEnvioService;
use App\Models\Discount;
class RateController extends Controller
{

    public function create(PackageDetailPostRequest $request)
    {

        $validatedRequest = (object)$request->validated();
        $request_from =  array(
            "object_type" => "PURCHASE",
            'name' => '-',
            'street' => '-',
            'street2' => '-',
            'reference' => '-',
            'zipcode' => $validatedRequest->zipcode_from,
            'email' => 'algo@algo.com',
            'alias' => 'origen',
            'phone' => '-'
        );
        $request_to =  array(
            "object_type"=> "PURCHASE",
            'name' => '-',
            'street' => '-',
            'street2' => '-',
            'reference' => '-',
            'zipcode' => $validatedRequest->zipcode_to,
            'email' => 'algo@algo.com',
            'alias' => 'destino',
            'phone' => '-'
        );
        $createdAddressFromResponse = (new MiEnvioService())->createAddress($request_from);
        if (!isset($createdAddressFromResponse->address)){
            return back()->withErrors(['errors' => 'Error en el domicilio de origen', 'response' => json_encode($createdAddressFromResponse) ]);

        }
        $createdAddressToResponse = (new MiEnvioService())->createAddress($request_to);

        if (!isset($createdAddressToResponse->address)){
            return back()->withErrors(['errors' => 'Error en el domicilio de destino', 'response' => json_encode($createdAddressToResponse) ]);

        }
        $shipment = [
            'object_purpose' => 'QUOTE',
            'address_from'   => $createdAddressFromResponse->address->object_id,
            'address_to'     => $createdAddressToResponse->address->object_id,
            'width'  => ceil($validatedRequest->width),
            'length' => ceil($validatedRequest->length),
            'height' => ceil($validatedRequest->height),
            'weight' => max(ceil($validatedRequest->weight), ceil($request->weight_real)) ,
            'description' => $validatedRequest->description
        ];
        
        $createdShipmentResponse = (new MiEnvioService())->createShipment($shipment);
        if (!isset($createdShipmentResponse->shipment)){
            return back()->withErrors(['errors' => 'Error al crear el envio', 'response' => json_encode($createdShipmentResponse) ]);

        }
        $shipmentId = $createdShipmentResponse->shipment->object_id;
        $ratesResponse =  (new MiEnvioService())->getRates($shipmentId);
        if (empty($ratesResponse)){
            return back()->withErrors(['errors' => 'Error obtener las cotizaciones', 'response' => json_encode($ratesResponse) ]);

        }

        $discount = Discount::where('usuarios_b_id', auth()->id())->first();

//        $keys = array_column($ratesResponse['results'], 'amount');
//        array_multisort($keys, SORT_ASC, $ratesResponse['results']);
        $priceCompare = [];
        foreach ($ratesResponse->results as $index =>  $rateResponse ) {
            $roundedQuantity = ceil($rateResponse->amount * ganancia($rateResponse->provider));

            $priceCompare[] = "{$rateResponse->amount} | {$roundedQuantity}";
            if (isset($discount->discount)) {

                $roundedQuantity = ceil($roundedQuantity / $discount->discount);
            }
            //return var_dump($discount->discount);

            $ratesResponse->results[$index]->amount = $roundedQuantity; //ceil($ratesResponse['results'][$i]['amount'] * 1.6);
        }

        session(
            [
                'pre_cart' =>  
                [
                    [
                        "zipcode_from" => $validatedRequest->zipcode_from,
                        "zipcode_to" => $validatedRequest->zipcode_to,
                        "description" => $validatedRequest->description,
                        "width" => ceil($validatedRequest->width),
                        "length" => ceil($validatedRequest->length),
                        "height" => ceil($validatedRequest->height),
                        "weight" => max(ceil($validatedRequest->weight),ceil($validatedRequest->weight_real)),
                        "city_from" => $createdAddressFromResponse->address->city,
                        "state_from" => $createdAddressFromResponse->address->state,
                        "municipio_from" => $createdAddressFromResponse->address->level_2,
                        "city_to" => $createdAddressToResponse->address->city,
                        "state_to" => $createdAddressToResponse->address->state,
                        "municipio_to" =>  $createdAddressToResponse->address->level_2,
                    ]
                ]
            ]);

        return view('users.home.rate', [
            'rates' => $ratesResponse 
        ]);

        /*
        $id =  $this->session->userdata('user_data')['id'];
        $discount = $this
            ->db
            ->select('discount')
            ->from('discounts')
            ->where('usuarios_b_id', $id)
            ->get()
            ->result();

        $keys = array_column($ratesResponse['results'], 'amount');
        array_multisort($keys, SORT_ASC, $ratesResponse['results']);
        for ($i = 0; $i < count($ratesResponse['results']); $i++) {
            $roundedQuantity = ceil($ratesResponse['results'][$i]['amount'] * 1.6);
            if (isset($discount[0]->discount)) {

                $roundedQuantity = ceil($roundedQuantity / $discount[0]->discount);
            }

            $ratesResponse['results'][$i]['amount'] = $roundedQuantity; //ceil($ratesResponse['results'][$i]['amount'] * 1.6);

        }

        $zipcode_to = $this->input->post('zipcode_to');
        $width = $this->input->post('width');
        $length = $this->input->post('length');
        $height = $this->input->post('height');
        $weight = $this->input->post('weight');
        $description = $this->input->post('description');
        */
    }
}
