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

class ServiceService
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

        $startGeneralExecutionTime = microtime(true);

        $comision = $product['ProductUFee'];
        $amount = $product['amount'];
        $lookUpTiming = [];
        $productId = $product['ProductId'];

        $lastInvoiceNumber = generate_invoice_number();
        try {
            try {
                
                ini_set('default_socket_timeout', 60);

                $accountId = $product['productIdentification'];
                $productName = $product['ProductName'];
                
                $responseBillpaymentUserFee = $this->clientWS->BillpaymentUserFee(
                    config('emida.version'),
                    config('emida.terminal_id_services'),
                    config('emida.clerk_id'),
                    $productId,
                    $amount,
                    $accountId,
                    $lastInvoiceNumber,
                    config('emida.language_id'),
                );
                $request = [
                    'user_id' => $userId,
                    'request' => $this->clientWS->__getLastRequest()
                ];

                Request::create($request);
                $objXML  = new \SimpleXMLElement($responseBillpaymentUserFee);
                ini_set('default_socket_timeout', 60); // or whatever new value you want}
                generate_invoice_number();

                if ((string)$objXML->ResponseCode != 00) {
                    $transaction = [
                        'user_id' => $userId,
                        'terminal_id' => config('emida.terminal_id_services'),
                        'invoice_id' => $lastInvoiceNumber,
                        'product_id' => $productId,
                        'amount' => (string)$objXML->Amount,
                        'responseCode' => (string)$objXML->ResponseCode,
                        'responseMessage' => (string)$objXML->ResponseMessage,

                    ];
                    $productTransactionResponse = Transaction::create($transaction);
                    $data['ticket'] = $transaction;
                    if (empty($productTransactionResponse)) {
                        return ['status' => 'error', 'response' => $data];
                    }
                    return ['status' => 'error', 'response' => $data];
                }
                $transaction = [
                    'user_id' => $userId,
                    'date_time' => (string)$objXML->TransactionDateTime,
                    'terminal_id' => config('emida.terminal_id_services'),
                    'responseTransaction_id' => (string)$objXML->TRANSACTION,
                    'invoice_id' => $lastInvoiceNumber,
                    'product_id' => $productId,
                    'amount' => (string)$objXML->Amount,
                    'account_id' => (string)$objXML->Pin,
                    'response_code' => (string)$objXML->ResponseCode,
                    'carrierControlNo' => (string)$objXML->CarrierControlNo,
                    'responseMessage' => (string)$objXML->ResponseMessage,
                    'productName' => $productName,
                    'fee' => $comision
                ];
                $productTransactionResponse = Transaction::create($transaction);
                $data["ticket"] = [
                    "TransactionDateTime" => (string)$objXML->TransactionDateTime,
                    "ProductName" => $productName,
                    "Pin" => (string)$objXML->Pin,
                    "Amount" => (string)$objXML->Amount,
                    "CarrierControlNo" => (string)$objXML->CarrierControlNo,
                    "ResponseMessage" => (string)$objXML->ResponseMessage,
                    "ResponseCode" => (string)$objXML->ResponseCode
                ];
                if (empty($productTransactionResponse)) {
                    return ['status' => 'error', 'response' => $data];
                }

                $movement = [
                    'message' => "Se realizo una compra en servicios de {$amount}.",
                    'user_id' => $userId
                ];
                Movement::create($movement);
                // End the clock time in seconds
                $endGeneralExecutionTime = microtime(true);

                // Calculate the script execution time
                $executionTime = ($endGeneralExecutionTime - $startGeneralExecutionTime);
                if ((string)$objXML->ResponseCode != '00') {
                    $status = 'error';
                } else {
                    $status = 'success';
                }
                return ['status' => $status, 'execution_time' => $executionTime, 'response' => $data];
            } catch (\Throwable $th) {
                return response()->json($th->getMessage());
                $lookUpTiming[] = date("h:i:sa") . ' :: ' . "End BillpaymentUserFee";
                $responses = [];
                $intentos = 4;
                for ($i = 0; $i <  $intentos; $i++) {
                    ini_set('default_socket_timeout', 10); // or whatever new value you want
                    $lookUpTiming[] = date("h:i:sa") . ' :: ' . "Start LookUpTransactionByInvocieNo::" . $i;

                    $response = $this->clientWS
                        ->LookUpTransactionByInvocieNo(
                            "01",
                            config('emida.terminal_id_services'),
                            config('emida.clerk_id'),
                            $lastInvoiceNumber
                        );

                    $objXML  = new \SimpleXMLElement($response);
                    $responses[] = json_encode($objXML);
                    if ((string)$objXML->ResponseCode == '32') {
                    } else {
                        ini_set('default_socket_timeout', 60); // or whatever new value you want}
                        generate_invoice_number();
                        $data["paymentResponse"] = $objXML;
                        $transaction = [
                            'user_id' => $userId,
                            'terminal_id' => config('emida.terminal_id_services'),
                            'responseTransaction_id' => (string)$objXML->TransactionId,
                            'invoice_id' => $lastInvoiceNumber,
                            'product_id' => $productId,
                            'amount' => (float)$amount + (int)$comision, //(string)$objXML-> Amount,
                            'carrierControlNo' => (string)$objXML->CarrierControlNo,
                            'responseMessage' => (string)$objXML->ResponseMessage,
                            'response_code' => (string)$objXML->ResponseCode,
                            'productName' => $productName,
                            'account_id' => (string)$objXML->PIN,
                            'date_time' => (string)$objXML->TransactionDateTime,
                            'fee' => $comision
                        ];
                        $productTransactionResponse = Transaction::create($transaction);
                        if (empty($productTransactionResponse)) {
                            $data['errors'] = $transaction;

                            return ['status' => 'error', 'response' => $data];
                        }
                        $data["ticket"] = [
                            "TransactionDateTime" => (string)$objXML->TransactionDateTime,
                            "ProductName" => $productName,
                            "Pin" => (string)$objXML->PIN,
                            "Amount" => (int)$amount + (int)$comision,
                            "CarrierControlNo" => (string)$objXML->CarrierControlNo,
                            "ResponseMessage" => (string)$objXML->ResponseMessage,
                        ];
                        $movement = [
                            'message' => "Se realizo una compra en recargas de {$amount}.",
                            'user_id' => $userId,
                        ];
                        Movement::create($movement);
                        // End the clock time in seconds
                        $endGeneralExecutionTime = microtime(true);

                        // Calculate the script execution time
                        $executionTime = ($endGeneralExecutionTime - $startGeneralExecutionTime);
                        return ['status' => $objXML->ResponseCode != '00' ? 'error' : 'success', 'execution_time' => $executionTime, 'response' => $data];
                    }
                    sleep(10);
                }
                ini_set('default_socket_timeout', 60); // or whatever new value you want}
                generate_invoice_number();

                $data["errors"] = $objXML;
                $transaction = [
                    'user_id' => $userId,
                    'terminal_id' => config('emida.terminal_id_services'),
                    'invoice_id' => $lastInvoiceNumber,
                    'product_id' => $productId,
                    'amount' => (int)$amount + (int)$comision,  //(string)$objXML-> Amount,
                    'responseCode' => (string)$objXML->ResponseCode,
                ];
                $productTransactionResponse = Transaction::create($transaction);

                if (empty($productTransactionResponse)) {
                    return ['status' => 'error', 'response' => $productTransactionResponse];
                }
                $data["errors"] =  $objXML;
                return ['status' => 'error', 'response' => $data];
            }
        } catch (\Throwable $th) {
            return ['status' => 'error', 'response' => $th->getMessage()];
        }
    }
}
