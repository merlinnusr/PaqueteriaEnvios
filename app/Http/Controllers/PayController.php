<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayWalletPostRequest;
use App\Models\Log;
use App\Models\LogPurchase;
use App\Models\Reception;
use App\Models\Shipment;
use App\Models\ShipmentDetail;
use App\Models\User;
use App\Services\CuponService;
use App\Services\MiEnvio\MiEnvioService;
use App\Services\PayService;

use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;


class PayController extends Controller
{

    public function create(PayWalletPostRequest $request)
    {
        $validatedRequest = (object)$request->validated();
        $user = User::find(auth()->id());
        $carts = json_decode(json_encode(session('cart')));
        $purchasesResponse = [];
        $miEnvioPurchaseArray = [];
        $payed = FALSE;
        $totalAmount = (new PayService($carts))->getTotal();
        $userWallet = NULL;
        $charge  = NULL;
        $totalToPay = NULL;
        if (Hash::check($validatedRequest->password_pago, $user->password)) {

            if ($request->cupon) {
                $cuponDiscount = (new CuponService())->checkCupon($request->cupon, $request->for, $carts[0]->amount);
                if(!isset($cuponDiscount["precio"])){
                    return back()->withErrors(['errors' =>  "Cupón invalido"]);
   
                }
                $carts[0]->amount = $cuponDiscount["precio"];
            }

            $validPayment = NULL;
            foreach ($carts as $cart) {
                $total =  ceil(floatval($cart->amount));
                $totalFormated = number_format($total, 2, '.', '');

                $userWallet = User::find(auth()->id())->getWalletBalance();
                if (isset($validatedRequest->stripeToken)) {
                    $validPayment = 'cc';
                } else {
                    if (floatval($userWallet) >= floatval($totalFormated)) {
                        $validPayment = 'wallet';
                    }else {
                        return back()->withErrors(['errors' => 'No tienes suficiente saldo']);

                    }
                }


                if (!empty($validPayment)) {
                    if ($cart->numero_int_from  !== NULL) {
                        $int_numero_from = " Int {$cart->numero_int_from}";
                    } else {
                        $int_numero_from = '';
                    }
                    $addressFromOriginal = "{$cart->street_from} {$cart->numero_from}{$int_numero_from} Col {$cart->colonia_from}";
                    $addressFrom = mb_substr($addressFromOriginal, 0, 29);
                    $addressFrom2 = mb_substr($addressFromOriginal, 29, 60);
                    if (strlen($addressFrom2) < 3) {
                        $addressFrom2 = $addressFrom2 . '-';
                    }

                    $requestFrom =  array(
                        'object_type' => 'PURCHASE',
                        'name' => $cart->name_from,
                        'street' => $addressFrom,
                        'street2' => $addressFrom2,
                        'reference' => $cart->referencia_from ?? '-',
                        'zipcode' => $cart->zipcode_from,
                        'email' => $cart->email_from,
                        'phone' => $cart->phone_from,
                        'alias' => 'origen',
                    );


                    $createdAddressFromResponse = (new MiEnvioService())->createAddress($requestFrom);
                    if (!isset($createdAddressFromResponse->address)) {
                        Log::create(['cart' => json_encode($createdAddressFromResponse)]);

                        return back()->withErrors(['errors' => 'Error en el domicilio de origen, envia este error a desarrolloweb@dagpacket.com.mx', 'response' => json_encode($createdAddressFromResponse)]);
                    }


                    if ($cart->numero_int_to  !== NULL) {
                        $int_numero_to = " Int {$cart->numero_int_to}";
                    } else {
                        $int_numero_to = '';
                    }

                    $addressToOriginal = "{$cart->street_to} {$cart->numero_to}{$int_numero_to} Col {$cart->colonia_to}";
                    $addressTo = mb_substr($addressToOriginal, 0, 29);
                    $addressTo2 = mb_substr($addressToOriginal, 29, 60);
                    if (strlen($addressTo2) < 3) {
                        $addressTo2 = $addressTo2 . '-';
                    }


                    $requestTo =  array(
                        'object_type' => 'PURCHASE',
                        'name' => $cart->name_to,
                        'street' => $addressTo,
                        'street2' => $addressTo2,
                        'reference' => $cart->referencia_to ?? '-',
                        'zipcode' => $cart->zipcode_to,
                        'email' => $cart->email_to,
                        'phone' => $cart->phone_to,
                        'alias' => 'destino',
                    );
                    $createdAddressToResponse = (new MiEnvioService())->createAddress($requestTo);
                    if (!isset($createdAddressToResponse->address)) {
                        Log::create(['cart' => json_encode($requestTo)]);

                        return back()->withErrors(['errors' => 'Error en el domicilio de destino, envia este error a desarrolloweb@dagpacket.com.mx', 'response' => json_encode($createdAddressToResponse)]);
                    }
                    $shipment = [
                        'object_purpose' => 'QUOTE',
                        'address_from'   => $createdAddressFromResponse->address->object_id,
                        'address_to'     => $createdAddressToResponse->address->object_id,
                        'width'  => $cart->width,
                        'length' => $cart->length,
                        'height' => $cart->height,
                        'weight' => $cart->weight,
                        'description' => $cart->description
                    ];
                    $createdShipmentResponse = (new MiEnvioService())->createShipment($shipment);
                    if (!isset($createdShipmentResponse->shipment)){
                        return back()->withErrors(['errors' => 'Error al crear el envio desarrolloweb@dagpacket.com.mx', 'response' => json_encode($createdShipmentResponse)]);
                    }
                    $shipmentId = $createdShipmentResponse->shipment->object_id;

                    $ratesResponse = (new MiEnvioService())->getRates($shipmentId);
                    if (!isset($ratesResponse->results)){
                        return back()->withErrors(['errors' => 'Error al definir las tarifas desarrolloweb@dagpacket.com.mx', 'response' => json_encode($ratesResponse)]);
                    }
                    //$keys = array_column($ratesResponse->results, 'amount');
                    //array_multisort($keys, SORT_ASC, $ratesResponse['results']);
                    $rates_id = 0;
                    for ($i = 0; $i < count($ratesResponse->results); $i++) {
                        $objectId = $ratesResponse->results[$i]->object_id;
                        $ratesResponse->results[$i]->amount = $ratesResponse->results[$i]->amount * 1.6;
                        $temp_mount = ceil($ratesResponse->results[$i]->amount);
                        if ($cart->rate_id == $objectId) {
                            $rates_id = $ratesResponse->results[$i]->object_id;
                        }
                    }
                    $chosedRated = [
                        'object_purpose' => 'PURCHASE',
                        'rate' => $cart->rate_id
                    ];
                    $updatedShipmentResponse = (new MiEnvioService())->updateShipment($shipmentId, $chosedRated);

                    $purchase = [
                        'shipments' => array($shipmentId),
                        'payment' => array('provider' => 'wallet')
                    ];
                    $purchasesResponse = (new MiEnvioService())->createPurchase($purchase);

                    $purchaseID = '';
                    if (!isset($purchasesResponse->purchase->object_id)) {
                        Log::create(['cart' => json_encode($purchase)]);
                        return back()->withErrors(['errors' =>  "envia este error a desarrolloweb@dagpacket.com.mx {$cart->rate_id} - {$rates_id} ".json_encode($ratesResponse->results)]);
                    }
                    $purchaseID = $purchasesResponse->purchase->object_id;


                    $miEnvioPurchaseArray[] = $purchasesResponse;
                    $dataApiResponse = [
                        'object_id_quote' => $purchasesResponse->purchase->shipments[0]->object_id,
                        'rate_servicelevel' => $purchasesResponse->purchase->shipments[0]->rate->servicelevel,
                        'rate_duration_terms' => $purchasesResponse->purchase->shipments[0]->rate->duration_terms,
                        'rate_provider' => $purchasesResponse->purchase->shipments[0]->rate->provider,
                        'rate_provider_img' => $purchasesResponse->purchase->shipments[0]->rate->provider_img,
                        'purchase_number' => $purchasesResponse->purchase->object_id,
                        'amount' => $cart->amount
                    ];
                    try {
                        DB::beginTransaction();
                        $cart->user = auth()->id();
                        $cart->costo_extra = 0;
                        $cart->recepcionable = 1;
                        $cartArray = (array)$cart;
                        $shipmentResponse = Shipment::create($cartArray);

                        if (!isset($shipmentResponse->id)) {
                            throw new Exception('Hubo un error al guardar tu informacion, envia este error a desarrolloweb@dagpacket.com.mx');
                        }
                        $receptionResponse = Reception::create(['paquete_id' => $shipmentResponse->id]);
                        if(!isset($receptionResponse->id)){

                            throw new Exception('Hubo un error al guardar tu informacion, envia este error a desarrolloweb@dagpacket.com.mx');
                        }
                        $shipmentExtraInfo = Shipment::where('id', $shipmentResponse->id)->update($dataApiResponse);
                        if ($shipmentExtraInfo === 0) {
                            throw new Exception('Hubo un error al actualizar tu pedido, envia este error a desarrolloweb@dagpacket.com.mx');
                        }
                        //return var_dump($validPayment);
                        if ($validPayment === 'cc' && $payed === FALSE) {
                            Stripe::setApiKey(config('services.stripe.secret'));

                            $customerResponse = Customer::create([
                                'email' => $request->email,
                                'source' => $request->stripeToken
                            ]);
                            //dd($totalAmount);
                            $chargeResponse = Charge::create(array(
                                'customer' => $customerResponse->id,
                                'amount'   =>  floatval($totalAmount) * 100,
                                'currency' => 'mxn'
                            ));
                            $charge = $chargeResponse;
                            if (!isset($charge->balance_transaction)) {
                                Log::create(['cart' => json_encode($charge)]);

                                throw new Exception('Hubo un error en esa transaccion, envia este error a desarrolloweb@dagpacket.com.mx');
                            }
                            
                            $payed = TRUE;
                        } else if ($validPayment === 'wallet') {
                            $updatedWallet = User::find(auth()->id())->update([
                                'wallet' => floatval($userWallet) - floatval($totalFormated)
                            ]);
                            if ($updatedWallet === 0) {
                                throw new Exception('Hubo un error al actualizar tu cartera, envia este error a desarrolloweb@dagpacket.com.mx');
                            }
                            $payed = TRUE;
                        }

                        $shipmentDetail = array(
                            'product_id' => $purchasesResponse->purchase->object_id,
                            'buyer_name' => $cart->name_from,
                            'buyer_email' => $cart->email_from,
                            'paid_amount' => $cart->amount,
                            'paid_amount_currency' => 'mxn',
                            'txn_id' => '',//isset($charge->balance_transaction) ? $charge->balance_transaction :  '',
                            'payment_status' => 'succeeded',
                            'id_paquete' => $shipmentResponse->id
                        );
                        if ($validPayment === 'cc' && $payed === FALSE) {
                            $shipmentDetailResponse = ShipmentDetail::create($shipmentDetail);
                            if (!isset($shipmentDetailResponse->id)) {
                                throw new Exception('Hubo un error al guardar los detalles extras de tu pedido');
                            }
                        }

                        if ($validPayment === 'wallet') {
                            $shipmentDetailResponse = ShipmentDetail::create($shipmentDetail);
                            if (!isset($shipmentDetailResponse->id)) {
                                throw new Exception('Hubo un error al guardar los detalles extras de tu pedido');
                            }
                        }
                        $totalToPay = floatval($userWallet) - floatval($totalFormated);
                        LogPurchase::create(['message' => "Se realizo una compra. Saldo de Wallet: {$userWallet}. Monto a cobrar: {$cart->amount}. Resultado: {$totalToPay} Numero de paquete: {$purchasesResponse->purchase->shipments[0]->object_id}","user_id" => auth()->id()]);
                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollback();
                        $payed = FALSE;
                        return back()->withErrors(['errors' => $th->getMessage()]);
                    }
                }
            }
            if ($payed) {
                $request->session()->forget('cart');
                return view('users.pay.success');
            } else {
                DB::rollback();
            }
        } else {

            return back()->withErrors(['errors' => 'Contraseña incorrecta']);
        }
    }
}
