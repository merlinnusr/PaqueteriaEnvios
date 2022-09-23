<?php

namespace App\Services\Pos;

use App\Models\BalancePos;
use App\Models\InvoiceNumber;
use App\Models\Movement;
use App\Models\PosLog;
use App\Models\ProductTransaction;
use App\Models\Request;
use App\Models\Transaction;
use App\Models\User;
use Stripe\Balance;

class RechargeService
{
    public $clientWS;
    public $wsdlURL;
    public function __construct()
    {
        $this->wsdlURL = config('emida.endpoint');
        $this->clientWS = new \SoapClient(
            $this->wsdlURL,
            array(
                'trace' => true,
                'connection_timeout' => 40,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'keep_alive' => false,
            )
        );
    }
    public function buy($product, $userId)
    {
        (string)$lastInvoiceNumber = generate_invoice_number(); //$this->invoice_model->lastInvoiceNumber();
        $accountId = $product['productIdentification'];
        $amount = $product['amount'];
        $productId = $product['ProductId'];
        $productName = $product['ProductName'];
        try {
            try {
                ini_set('default_socket_timeout', 40); // or whatever new value you want}


                $pinDistSaleResponse = $this->clientWS->PinDistSale(
                    config('emida.version'),
                    config('emida.terminal_id_recharges'),
                    config('emida.clerk_id'),
                    $productId,
                    $accountId,
                    $amount,
                    $lastInvoiceNumber,
                    config('emida.language_id'),
                );

                $objXML  = new \SimpleXMLElement($pinDistSaleResponse);
                ini_set('default_socket_timeout', 60); // or whatever new value you want}
                //$this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);
                generate_invoice_number();

                if ((string)$objXML->ResponseCode != 00) {

                    $data["errors"] = $objXML;
                    $data["MethodResponse"] = "PinDistSale::Invalid Response Code";

                    $transaction = [
                        'user_id' => auth()->id(),
                        'terminal_id' => config('emida.terminal_id_recharges'),
                        'invoice_id' => $lastInvoiceNumber,
                        'product_id' => $productId,
                        'amount' => $amount,
                        'responseCode' => (string)$objXML->ResponseCode,
                    ];
                    $transactionResponse = Transaction::create($transaction);
                    if (empty($transactionResponse)) {
                        $data["ticket"] = $transaction;
                        return ['status' => 'error', 'response' => $data];
                    }

                    $data["ticket"] = $transaction;

                    return ['status' => 'error', 'response' => $data];
                }
                // return var_dump('hola');

                $transaction = [
                    'user_id' => auth()->id(),
                    'date_time' => (string)$objXML->TransactionDateTime,
                    'terminal_id' => config('emida.terminal_id_recharges'),
                    'responseTransaction_id' => (string)$objXML->TransactionId,
                    'invoice_id' => $lastInvoiceNumber,
                    'product_id' => $productId,
                    'amount' => $amount,
                    'account_id' => (string)$objXML->Pin,
                    'responseCode' => (string)$objXML->ResponseCode,
                    'carrierControlNo' => (string)$objXML->CarrierControlNo,
                    'responseMessage' => (string)$objXML->ResponseMessage,
                    'productName' => $productName
                ];
                $transactionResponse = Transaction::create($transaction);
                $data["ticket"] = [
                    "TransactionDateTime" => (string)$objXML->TransactionDateTime,
                    "ProductName" => $productName,
                    "Pin" => (string)$objXML->Pin,
                    "Amount" => $amount,
                    "CarrierControlNo" => (string)$objXML->CarrierControlNo,
                    "ResponseMessage" => (string)$objXML->ResponseMessage
                ];

                if (empty($transactionResponse)) {
                    return ['status' => 'error', 'response' => $data];
                }

                $data["paymentResponse"] = $objXML;
                $data["MethodResponse"] = "PinDistSale";


                $movement = [
                    'message' => "Se realizo una compra en recargas de {$amount}.",
                    'user_id' => $userId,
                ];

                $posLog = Movement::create($movement);

                if (empty($posLog)) {
                    return response()->json(['status' => 'error', 'response' => 'Error guardando log de compra']);
                }
                if ((string)$objXML->ResponseCode != '00') {
                    $status = 'error';
                } else {
                    $status = 'success';
                }
                return ['status' => 'success', 'response' => $data];
            } catch (\Throwable $th) {
                $intentos = 4;
                return response()->json(['response' => $th->getMessage()]);
                ini_set('default_socket_timeout', 10); // or whatever new value you want
                for ($i = 0; $i <  $intentos; $i++) {
                    $lookUpTransactionByInvocieNoResponse = $this->clientWS->LookUpTransactionByInvocieNo(
                        "01",
                        config('emida.terminal_id_recharges'), //"8627128",
                        config('emida.clerk_id'),
                        $lastInvoiceNumber
                    );

                    $objXML  = new \SimpleXMLElement($lookUpTransactionByInvocieNoResponse);
                    //var_dump((string)$objXML->ResponseCode);
                    if ((string)$objXML->ResponseCode == '32') {
                    } else {
                        ini_set('default_socket_timeout', 60); // or whatever new value you want}

                        //$this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);

                        //$execution_time = $this->invoice_model->calculateExecutionTime($time_start);

                        $data["paymentResponse"] = $objXML;
                        $transaction = [
                            'user_id' => $userId,
                            'date_time' => (string)$objXML->TransactionDateTime,
                            'terminal_id' => config('emida.terminal_id_recharges'),
                            'responseTransaction_id' => (string)$objXML->TransactionId,
                            'invoice_id' => $lastInvoiceNumber,
                            'product_id' => $productId,
                            'amount' => $amount,
                            'account_id' => (string)$objXML->PIN,
                            'responseCode' => (string)$objXML->ResponseCode,
                        ];
                        $productTransactionResponse = Transaction::create($transaction);
                        if (empty($productTransactionResponse)) {
                            return response()->json(['status' => 'error', 'response' => 'Erro al guardar la transacción']);
                        }
                        $data["MethodResponse"] = "LookUpTransactionByInvocieNo::" . $i;
                        $data["ticket"] = [
                            "TransactionDateTime" => (string)$objXML->TransactionDateTime,
                            "ProductName" => $productName,
                            "Pin" => (string)$objXML->PIN,
                            "Amount" => $amount,
                            "CarrierControlNo" => (string)$objXML->CarrierControlNo,
                            "ResponseMessage" => (string)$objXML->ResponseMessage
                        ];
                        $movement = [
                            'message' => "Se realizo una compra en recargas de {$amount}.",
                            'user_id' => $userId,
                        ];
                        $posLog = Movement::create($movement);

                        if (empty($posLog)) {
                            return response()->json(['status' => 'error', 'response' => 'Error guardando log de compra']);
                        }
                        return ['status' => 'success', 'response' => $data];
                    }

                    sleep(10);
                }

                //$this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);
                // $execution_time = $this->invoice_model->calculateExecutionTime($time_start);
                // $data["errors"] = $objXML;
                // $data["MethodResponse"] = "Generic Timeout";

                $transaction = [
                    'user_id' => $userId,
                    'terminal_id' => config('emida.terminal_id_recharges'),
                    'invoice_id' => $lastInvoiceNumber,
                    'product_id' => $productId,
                    'amount' => $amount,
                    'responseCode' => (string)$objXML->ResponseCode,
                ];
                $productTransactionResponse = Transaction::create($transaction);
                if (empty($productTransactionResponse)) {
                    return response()->json(['status' => 'error', 'response' => 'Error al guardar el error de una transacción']);
                }
                return response()->json(['status' => 'error', 'response' => 'Error al procesar la recarga']);

                // $this->load->view('header');
                // $this->load->view('emida/failed', $data);
                //execution time of the script
                //echo '<b>Total Execution Time:</b> '.$execution_time.' Segs';
                //echo "<h1>No se proceso la transaccion</h1>";
                //echo "<pre>";
                //return var_dump($objXML);
            }

            // return 'Hubo un error al procesar su transaccion';
            // //$products =  $objXML->ResponseMessage->Products->Product ;
            // //$data["products"] = $products;
            // $this->load->view('header');
            // $this->load->view('emida/welcome_message', $data);
        } catch (\Throwable $th) {
            //throw $th;
            // $execution_time = $this->invoice_model->calculateExecutionTime($time_start);

            return response()->json(['status' => 'error', 'response' => $th->getMessage()]);
        }
    }
}
