<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelDeleteRequest;
use App\Models\Shipment;
use App\Models\ShipmentDeleted;
use App\Models\ShipmentDetail;
use App\Models\User;
use App\Models\LogPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
class LabelController extends Controller
{
    public function create(Request $request)
    {
        $shipmentId = $request->idPedido;
        $shipmentData = Shipment::with('details')->where('id', $shipmentId)->first();

        $data = [
            'data' => $shipmentData,
        ];
        $pdf = PDF::loadView('label.pdf_template',  $data);
        header("Content-type:application/pdf");

        return $pdf->download(now().'.pdf');
    }
    public function delete(LabelDeleteRequest $request)
    {
        DB::beginTransaction();

        try {

            $label = (object)$request->validated();
            $purchaseNumber = $label->packetId;
    
            $shipment = [
                'activo' => 0
            ];
            
            $shipment = tap(Shipment::where('purchase_number', $purchaseNumber))->update($shipment)->first();
            $shipmentDeleted =[
                'reason' => $label->reason,
                'user_id' => auth()->id()
            ];
            $user = User::find(auth()->id());
            $wallet = $user->wallet;
            //$user->wallet =  floatval($shipment->amount) + floatval($wallet) ;
            $user->save();

            // LogPurchase::create(['message' => "Se reembolso {$shipment->amount} estado de wallet {$user->wallet}",'user_id' => auth()->id()]);
            LogPurchase::create(['message' => "Se Canceló Guia {$purchaseNumber}",'user_id' => auth()->id()]);
            ShipmentDeleted::create($shipmentDeleted);
            ShipmentDetail::where('product_id', $purchaseNumber)->update(['paquetes_cancelados_id' => $shipment->id]);
    
            DB::commit();
            return response()->json(['status' => 'success', 'response' => 'Se borro correctamente este envio' ]);


        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json(['status' => 'error', 'response' => $th->getMessage() ]);
        }
    }
}
