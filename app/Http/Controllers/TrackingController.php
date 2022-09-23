<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Services\MiEnvio\MiEnvioService;

class TrackingController extends Controller
{
    public function show(Request $request)
    {
        $trackingResponse = (new MiEnvioService())->getTracking($request->trackingId,$request->providerName);
        $shipment = Shipment::where('tracking_number', $request->trackingId)->first();
        $trackingObj = json_decode($trackingResponse->getData());
        $data = [
            'shipment' => $shipment,
            'trackingData' => $trackingObj
        ];
        return view('users.tracking.index', $data);
    }
}
