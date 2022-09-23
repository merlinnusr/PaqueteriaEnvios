<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use DataTables;
use App\Services\MiEnvio;
use App\Http\Requests\ShipmentPostRequest;
use App\Services\MiEnvio\MiEnvioService;
use Illuminate\Support\Facades\Http;

class ShipmentController extends Controller
{
    public function show(Request $request)
    {

        if ($request->ajax()) {
            $data = Shipment::where('user',  auth()->id())->where('activo', 1)->get();
            $url = route('label.create');
            
            $inexistingLabels = Shipment::where('user', auth()->id())
                ->where('activo', 1)
                ->where('tracking_url', NULL)
                ->get();
            if (empty($inexistingLabels)) {
                return TRUE;
            }
            foreach ($inexistingLabels as $inexistingLabel) {
                $body = (new MiEnvioService())->getPurchases($inexistingLabel->purchase_number);
                if ($body->purchase->shipments[0]->status !== 'CANCELADO') {
                    if (isset($body->purchase->shipments[0]->label) && !empty($body->purchase->shipments[0]->label)) {
                        $dataShip = [
                            'tracking_number' => $body->purchase->shipments[0]->label->tracking_number,
                            'tracking_url' => $body->purchase->shipments[0]->label->tracking_url,
                            'tracking_label_url' => $body->purchase->shipments[0]->label->label_url,
                        ];
                        Shipment::where('purchase_number', $inexistingLabel->purchase_number)
                            ->update($dataShip);
                    }
                } else if ($body->purchase->shipments[0]->status == 'CANCELADO') {
                    Shipment::where('purchase_number', $inexistingLabel->purchase_number)->update(['activo' => 0]);
                }
            }
            
            $csrf = csrf_field();
            $trackingView = route('tracking.show');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($trackingView, $csrf) {
                    $actionBtn = '';
                    if (!empty($row->tracking_label_url)) {
                        if (!empty($row->purchase_number) && $row->purchase_number != 111) {
                            $actionBtn = "
                            <form method='post' action='{$trackingView}'>
                                {$csrf}    
                                <input type='text' hidden name='shipmentId' value='{$row->id}' readonly>
                                <input type='text' hidden name='trackingId' id='tracking' value='{$row->tracking_number}'>
                                <input type='text' hidden name='providerName' id='provider' value='{$row->rate_provider}'>

                                <button type='submit' value='detalles'  onClick='this.form.submit(); this.disabled=true; this.innerHtml='Espere'; ' name='detalles' class='btn btn-primary boton_naranja' style='color: white; border-color:transparent;'>
                                    Rastrear
                                </button>
                            </form>
                            ";
                        } else {
                            $actionBtn = "
                            <a href='{{$row->tracking_url}}' value='detalles' name='detalles' class='btn btn-primary boton_naranja' style='color: white; border-color:transparent;'>
                                Rastrear
                            </a>";
                        }
                    } else {
                        $actionBtn = '';
                    }


                    return $actionBtn;
                })
                ->addColumn('invoice', function ($row) use ($url, $csrf) {
                    $invoiceBtn = "
                    <form method='post' action='{$url}'>
                        {$csrf}
                        <input type='text' hidden name='idPedido' value='{$row->id}' readonly>
                        <button type='submit' value='pdf'  onClick='this.form.submit(); this.disabled=true; this.innerHtml='Espere'; ' name='pdf' class='' style='background: transparent; color: white; border-color:transparent;'>
                            <i class='fas fa-file-pdf fa-2x' style='color:red'></i>
                        </button>
                    </form>                
                    ";

                    return $invoiceBtn;
                })
                ->addColumn('price', function ($row) use ($url, $csrf) {
                    $price = number_local_format($row->amount);
                    $price = "{$price}";

                    return $price;
                })
                ->addColumn('label', function ($row) use ($url, $csrf) {
                    if (!empty($row->tracking_label_url)) {
                        $labelBtn = "
                            <a href='{$row->tracking_label_url}' target='_blank' value='guia' name='guia' class='' style='background: transparent; color: white; border-color:transparent;'>
                                <i class='fas fa-file-pdf fa-2x' style='color:red'></i>
                            </a>
                   
                        ";
                    } else {
                        $labelBtn = '';
                    }

                    return $labelBtn;
                })
                ->rawColumns(['action', 'label', 'invoice'])
                ->make(true);
        }
    }
    public function delete($index)
    {
        $cart = session('cart');
        unset($cart[$index]);
        session(['cart' => $cart]);
        return redirect()->route('checkout.show');
    }
    public function details(ShipmentPostRequest $request)
    {
        $validatedRequest = (object)$request->validated();
        $preCart = session('pre_cart');
        $lastIndex = key(array_slice($preCart, -1, 1, true));

        $preCart[$lastIndex]['provider_name'] = $validatedRequest->provider_name;
        $preCart[$lastIndex]['duration_terms'] = $validatedRequest->duration_terms;
        $preCart[$lastIndex]['service_level'] = $validatedRequest->service_level;
        $preCart[$lastIndex]['amount'] = $validatedRequest->amount;
        $preCart[$lastIndex]['rate_id'] = $validatedRequest->rate_id;

        session(['pre_cart' => $preCart]);
        /*return view('users.shipment.details', [
            'shipment' => json_decode(json_encode($preCart[$lastIndex]))
        ]);*/
        return redirect()->route('shipment.detailsView');
    }
    public function detailsView()
    {
        $preCart = session('pre_cart');
        $lastIndex = key(array_slice($preCart, -1, 1, true));

        return view('users.shipment.details', [
            'shipment' => json_decode(json_encode($preCart[$lastIndex]))
        ]);
    }
}
