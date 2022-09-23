<?php

namespace App\Services\MiEnvio;

use Illuminate\Support\Facades\Http;

class MiEnvioService
{
    public $token ; 
    public $endpoint;
    public function __construct()
    {
        $this->token = config('mienvio.TOKEN');
        $this->endpoint = config('mienvio.ENDPOINT');
    }
    public function createAddress($address)
    {
        try {
            $CREATE_ADDRESS_ENDPOINT = config('mienvio.ENDPOINT') . 'addresses';
            $TOKEN = config('mienvio.TOKEN');
            $response = Http::withToken($TOKEN)->post($CREATE_ADDRESS_ENDPOINT, $address);

            

 
            if ($response->failed() || $response->serverError() || $response->clientError()) {
                throw new \Exception($response->body());
            }
            return json_decode($response->body());
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function createShipment($shipment)
    {
        $CREATE_ADDRESS_ENDPOINT = config('mienvio.ENDPOINT') . 'shipments';
        $TOKEN = config('mienvio.TOKEN');
        $response = Http::withToken($TOKEN)->post($CREATE_ADDRESS_ENDPOINT, $shipment);
        return json_decode($response->body());
    }


    public function getRates($shipmentId)
    {
        $GET_SHIPMENT_ENDPOINT = config('mienvio.ENDPOINT').'shipments/'.$shipmentId.'/rates';

        $response = Http::withToken($this->token)->get($GET_SHIPMENT_ENDPOINT);
        return json_decode($response->body());
    }
    public function getTracking($trackingNumber, $providerName)
    {
        $GET_TRACKING = "{$this->endpoint}tracking/{$trackingNumber}/{$providerName}";
        $response = Http::withToken($this->token)->get($GET_TRACKING);
        return response()->json($response->body());   
    }

    public function updateShipment($shipmentId, $shipment)
    {
        $GET_SHIPMENT_ENDPOINT = config('mienvio.ENDPOINT').'shipments/'.$shipmentId;

        $response = Http::withToken($this->token)->put($GET_SHIPMENT_ENDPOINT, $shipment);
        return json_decode($response->body());
    }

    public function createPurchase($purchase)
    {
        $SHIPMENT_PURCHASE = config('mienvio.ENDPOINT').'purchases';

        $response = Http::withToken($this->token)->post($SHIPMENT_PURCHASE, $purchase);
        return json_decode($response->body());
    }
    public function getPurchases($inexistingLabel)
    {
        $SHIPMENT_PURCHASE = config('mienvio.ENDPOINT').'purchases/'.$inexistingLabel;

        $response = Http::withToken($this->token)->get($SHIPMENT_PURCHASE);
        return json_decode($response->body());
    }
}
