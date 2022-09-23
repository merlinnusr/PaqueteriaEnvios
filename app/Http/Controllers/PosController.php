<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {

        $data = [
            'title' => 'Dagpacket - Menú recargas y servicios'
        ];
        return view('pos.index');
    }
    public function ticket(Request $request)
    {
        $response = $request->response;
        if ($response['status'] === 'success') {
            return view('pos.success', ['data' => $response['response']]);
        } else {
            return view('pos.failed', ['data' => $response['response']]);
        }
    }
    public function service()
    {
        ini_set("soap.wsdl_cache_enabled", 0);

        $wsdlURL = $this->config->item('endpoint_emida'); //"https://ws.terecargamos.com:8448/soap/webServices.wsdl";
        try {

            $response = $this->service_model->getProductFlowResponse();
            $objXML  = new \SimpleXMLElement($response);
            $products =  $objXML->ResponseMessage->Products->Product;
            $categories = [];
            foreach ($products as $product) {
                if (!in_array((string)$product->ProductCategory, $categories)) {
                    $categories[] = (string)$product->ProductCategory;
                }
            }
            $data["categories"] = $categories;

            $data["title"]  = 'Dagpacket - Categorías de servicios';
            $this->load->view('header', $data);

            $this->load->view('emida/categories_service', $data);
        } catch (\Throwable $th) {
            //throw $th;
            return var_dump($th);
        }
    }

    public function subCategoryServices()
    {
        $category = urldecode($this->uri->segment('3'));
        try {



            $response = $this->service_model->getProductFlowResponse();

            $objXML  = new SimpleXMLElement($response);
            $products =  $objXML->ResponseMessage->Products->Product;

            $subCategories = [];
            foreach ($products as $product) {
                if ((string)$product->ProductCategory === $category) {
                    if (!in_array((string)$product->ProductSubCategory, $subCategories)) {
                        $subCategories[] = (string)$product->ProductSubCategory;
                    }
                }
            }
            $data["subCategories"] = $subCategories;
            $data["title"]  = 'Dagpacket - Subcategorías de servicios';

            $this->load->view('header', $data);

            $this->load->view('emida/service_sub_categories', $data);
        } catch (\Throwable $th) {
            //throw $th;
            return var_dump($th);
        }
    }
    public function productsRecharge()
    {
        $category = urldecode($this->uri->segment('3'));
        $wsdlURL = $this->config->item('endpoint_emida'); //"https://ws.terecargamos.com:8448/soap/webServices.wsdl";
        try {

            $lastInvoiceNumber = $this->invoice_model->lastInvoiceNumber();
            $data = [
                "version" => $this->config->item('version'),
                "terminalId" => $this->config->item('terminal_id_recharges'),
                "invoiceNo" => $lastInvoiceNumber,
                "language" => $this->config->item('language_id'),
                "clerkId" => $this->config->item('clerk_id')
            ];
            $jsonData = json_encode($data);
            $productFlowService  = $this->db->select('*')->from('productflowinfoservice')->where('type', 'recharge')->get()->result();
            $response = '';
            if (!empty($productFlowService)) {
                $currentDateTime = strtotime(date("Y-m-d h:i:s"));
                $dbDateTime = strtotime($productFlowService[0]->date);
                $hour = abs($dbDateTime - $currentDateTime) / (60 * 60);

                if ($hour >= 24) {
                    $clientWS = new SoapClient($wsdlURL);

                    $response = $clientWS->executeCommand("ProductFlowInfoService", $jsonData); //("GetCarrierList", $data);
                    $data = [
                        'type' => 'recharge',
                        'data' => $response,
                        'date' => date("Y-m-d H:i:s", mktime(6, 0, 0))
                    ];
                    ini_set('default_socket_timeout', 60); // or whatever new value you want}

                    $this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);

                    $this->db->where('id', $productFlowService[0]->id)->update('productflowinfoservice', $data);
                } else {

                    $response = $productFlowService[0]->data;
                }
            } else {
                $clientWS = new SoapClient($wsdlURL);

                $response = $clientWS->executeCommand("ProductFlowInfoService", $jsonData); //("GetCarrierList", $data);
                $data = [
                    'type' => 'recharge',
                    'data' => $response,
                    'date' => date("Y-m-d H:i:s", mktime(6, 0, 0))
                ];
                ini_set('default_socket_timeout', 60); // or whatever new value you want}

                $this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);

                $this->db->insert('productflowinfoservice', $data);
            }
            $objXML  = new SimpleXMLElement($response);
            $products =  $objXML->ResponseMessage->Products->Product;

            $productsFiltered = [];
            foreach ($products as $key => $product) {

                if ((string)$product->ProductCategory === $category) {

                    $productsFiltered[] = $product;
                }
            }
            usort($productsFiltered, function ($a, $b) {
                return strcmp((string)$a->ProductName, (string)$b->ProductName);
            });


            $data["products"] = $productsFiltered;
            $data["title"] = "Dagpacket - Productos de recargas";
            $this->load->view('header', $data);

            $this->load->view('emida/welcome_message', $data);
        } catch (\Throwable $th) {
            //throw $th;
            return var_dump($th);
        }
    }
    public function recharge()
    {
        $wsdlURL = $this->config->item('endpoint_emida'); //"https://ws.terecargamos.com:8448/soap/webServices.wsdl";
        try {

            $lastInvoiceNumber = $this->invoice_model->lastInvoiceNumber();
            $data = [
                "version" => $this->config->item('version'),
                "terminalId" => $this->config->item('terminal_id_recharges'),
                "invoiceNo" => $lastInvoiceNumber,
                "language" => $this->config->item('language_id'),
                "clerkId" => $this->config->item('clerk_id')
            ];
            $jsonData = json_encode($data);
            $productFlowService  = $this->db->select('*')->from('productflowinfoservice')->where('type', 'recharge')->get()->result();
            $response = '';
            if (!empty($productFlowService)) {
                $currentDateTime = strtotime(date("Y-m-d h:i:s"));
                $dbDateTime = strtotime($productFlowService[0]->date);
                $hour = abs($dbDateTime - $currentDateTime) / (60 * 60);
                if ($hour >= 24) {
                    $clientWS = new SoapClient($wsdlURL);

                    $response = $clientWS->executeCommand("ProductFlowInfoService", $jsonData); //("GetCarrierList", $data);
                    $data = [
                        'type' => 'recharge',
                        'data' => $response,
                        'date' => date("Y-m-d H:i:s", mktime(6, 0, 0))

                    ];
                    ini_set('default_socket_timeout', 60); // or whatever new value you want}
                    $this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);

                    $this->db->where('id', $productFlowService[0]->id)->update('productflowinfoservice', $data);
                } else {

                    $response = $productFlowService[0]->data;
                }
            } else {
                $clientWS = new SoapClient($wsdlURL);

                $response = $clientWS->executeCommand("ProductFlowInfoService", $jsonData); //("GetCarrierList", $data);
                $data = [
                    'type' => 'recharge',
                    'data' => $response,
                    'date' => date("Y-m-d H:i:s", mktime(6, 0, 0))

                ];
                $this->db->insert('productflowinfoservice', $data);
                ini_set('default_socket_timeout', 60); // or whatever new value you want}
                $this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);
            }
            $objXML  = new SimpleXMLElement($response);
            $products =  $objXML->ResponseMessage->Products->Product;
            $categories = [];
            foreach ($products as $product) {
                if (!in_array((string)$product->ProductCategory, $categories)) {
                    $categories[] = (string)$product->ProductCategory;
                }
            }

            $data["categories"] = $categories;
            $data["title"] = "Dagpacket - Categorias de recargas";

            $this->load->view('header', $data);

            $this->load->view('emida/categories', $data);
        } catch (\Throwable $th) {
            //throw $th;
            return var_dump($th);
        }
    }
    public function productServiceSubcategories()
    {
        $subCategory = urldecode($this->uri->segment('3'));
        try {



            $response = $this->service_model->getProductFlowResponse();

            $objXML  = new SimpleXMLElement($response);


            $products =  $objXML->ResponseMessage->Products->Product;

            $serviceProducts = [];
            foreach ($products as $product) {
                if ((string)$product->ProductSubCategory === $subCategory) {
                    $serviceProducts[] = $product;
                }
            }

            $data["products"] = $serviceProducts;
            $data["title"] = "Dagpacket - Productos";
            $this->load->view('header', $data);

            $this->load->view('emida/product_service', $data);
        } catch (\Throwable $th) {
            //throw $th;
            return var_dump($th);
        }
    }
    public function productService()
    {
        $data = [
            'ProductId' => $this->input->post('ProductId'),
            'LengthMin' => $this->input->post('LengthMin'),
            'LengthMax' => $this->input->post('LengthMax'),
            'Amount' => $this->input->post('Amount'),
            'AmountMin' => $this->input->post('AmountMin'),
            'AmountMax' => $this->input->post('AmountMax'),
            'CarrierName' => $this->input->post('CarrierName'),
            'ProductName' => $this->input->post('ProductName'),
            'ReferenceName' => $this->input->post('ReferenceName'),
            'FieldType' => $this->input->post('FieldType'),
            'ProductUFee' => $this->input->post('ProductUFee'),
            'PaymentType' => $this->input->post('PaymentType')

        ];

        $data["products"] = $data;
        $data["title"] = "Dagpacket - Pagar servicio";

        $this->load->view('header', $data);
        $this->load->view('emida/service', $data);
    }
    public function product()
    {
        $data = [
            'ProductId' => $this->input->post('ProductId'),
            'LengthMin' => $this->input->post('LengthMin'),
            'LengthMax' => $this->input->post('LengthMax'),
            'Amount' => $this->input->post('Amount'),
            'CarrierName' => $this->input->post('CarrierName'),
            'ProductName' => $this->input->post('ProductName'),
            'ReferenceName' => $this->input->post('ReferenceName'),
            'FieldType' => $this->input->post('FieldType')
        ];
        $data["products"] = $data;
        $data["title"] = "Dagpacket - Compra una recarga";
        $this->load->view('header', $data);
        $this->load->view('emida/product', $data);
    }

    public function balanceout()
    {
        $data["title"] = "Dagpacket - Fondos insuficientes";
        $this->load->view('header', $data);
        $this->load->view('emida/balanceout');
    }
    public function buyRecharge()
    {
        if ($this->session->has_userdata('rand') != $this->input->post('randcheck')) {
            redirect("/");
        }
        $this->session->unset_userdata('rand');
        $balance = $this->balance_model->getCurrentBalanceRecharges();
        if (empty($balance) || $balance[0]->balance < floatval($this->input->post('amount'))) {

            $this->session->set_flashdata('error', 'No tienes suficiente saldo, pide una recarga a tu cuenta');
            redirect('/pos/balanceout', 'refresh');
        }
        $wsdlURL = $this->config->item('endpoint_emida'); //"https://ws.terecargamos.com:8448/soap/webServices.wsdl";
        $time_start = microtime(true);
        (string)$lastInvoiceNumber = $this->invoice_model->lastInvoiceNumber();
        try {
            try {
                $clientWS = new SoapClient($wsdlURL);
                ini_set('default_socket_timeout', 40); // or whatever new value you want}
                $clientWS = new SoapClient($wsdlURL, array(
                    'trace' => true,
                    'connection_timeout' => 40,
                    'cache_wsdl' => WSDL_CACHE_NONE,
                    'keep_alive' => false,
                ));
                $accountId = $this->input->post('productIdentification');
                $amount = $this->input->post('amount');
                $productId = $this->input->post('ProductId');
                $productName = $this->input->post('ProductName');
                $response = $clientWS->PinDistSale(
                    $this->config->item('version'),
                    $this->config->item('terminal_id_recharges'),
                    $this->config->item('clerk_id'),
                    $productId,
                    $accountId,
                    $amount,
                    $lastInvoiceNumber,
                    $this->config->item('language_id')
                ); //("GetCarrierList", $data);
                $objXML  = new SimpleXMLElement($response);
                ini_set('default_socket_timeout', 60); // or whatever new value you want}
                $this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);
                if ((string)$objXML->ResponseCode != 00) {
                    $data["errors"] = $objXML;
                    $data["MethodResponse"] = "PinDistSale::Invalid Response Code";
                    $transaction = [
                        'user_id' => $this->session->userdata('id'),
                        'terminal_id' => $this->config->item('terminal_id_recharges'),
                        'invoice_id' => $lastInvoiceNumber,
                        'product_id' => $productId,
                        'amount' => $amount,
                        'responseCode' => (string)$objXML->ResponseCode,
                    ];
                    if (!$this->transaction_model->saveTransaction($transaction)) {
                        return var_dump("Error saving transaction");
                    }
                    $this->load->view('header');
                    $this->load->view('emida/failed', $data);
                    return;
                }

                //execution time of the script
                //echo '<b>Total Execution Time:</b> '.$execution_time.' Segs';
                //echo "<pre>";
                $transaction = [
                    'user_id' => $this->session->userdata('id'),
                    'date_time' => (string)$objXML->TransactionDateTime,
                    'terminal_id' => $this->config->item('terminal_id_recharges'),
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
                if (!$this->transaction_model->saveTransaction($transaction)) {
                    return var_dump("Error saving transaction");
                }

                $data["paymentResponse"] = $objXML;
                $data["MethodResponse"] = "PinDistSale";

                $data["ticket"] = [
                    "TransactionDateTime" => (string)$objXML->TransactionDateTime,
                    "ProductName" => $productName,
                    "Pin" => (string)$objXML->Pin,
                    "Amount" => $amount,
                    "CarrierControlNo" => (string)$objXML->CarrierControlNo,
                    "ResponseMessage" => (string)$objXML->ResponseMessage
                ];
                $updatedBalanceResponse = $this->balance_model->updateBalanceRecharge(floatval($amount));
                if (!$updatedBalanceResponse) {
                    return var_dump("Error updating balance");
                }

                $movement = [
                    'message' => "Se realizo una compra en recargas de {$amount}.",
                    'user_id' => $this->session->userdata('id'),
                ];
                $this->db->insert('movements', $movement);

                $this->load->view('header');
                $this->load->view('emida/success', $data);
                return;
            } catch (\Throwable $th) {
                //echo "REQUEST HEADERS:\n" . $clientWS->__getLastRequestHeaders() . "\n";

                //$this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);

                //return var_dump($th->getMessage());
                //throw $th;
                $intentos = 4;
                ini_set('default_socket_timeout', 10); // or whatever new value you want
                for ($i = 0; $i <  $intentos; $i++) {


                    $clientWS2 = new SoapClient($wsdlURL, array(
                        'trace' => true,
                        'connection_timeout' => 10,
                        'cache_wsdl' => WSDL_CACHE_NONE,
                        'keep_alive' => false,
                    ));
                    $response = $clientWS2->LookUpTransactionByInvocieNo(
                        "01",
                        $this->config->item('terminal_id_recharges'), //"8627128",
                        $this->config->item('clerk_id'),
                        $lastInvoiceNumber
                    );

                    $objXML  = new SimpleXMLElement($response);
                    //var_dump((string)$objXML->ResponseCode);
                    if ((string)$objXML->ResponseCode == '32') {
                    } else {
                        ini_set('default_socket_timeout', 60); // or whatever new value you want}
                        $this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);
                        $execution_time = $this->invoice_model->calculateExecutionTime($time_start);

                        $data["paymentResponse"] = $objXML;
                        $transaction = [
                            'user_id' => $this->session->userdata('id'),
                            'date_time' => (string)$objXML->TransactionDateTime,
                            'terminal_id' => $this->config->item('terminal_id_recharges'),
                            'responseTransaction_id' => (string)$objXML->TransactionId,
                            'invoice_id' => $lastInvoiceNumber,
                            'product_id' => $productId,
                            'amount' => $amount,
                            'account_id' => (string)$objXML->PIN,
                            'responseCode' => (string)$objXML->ResponseCode,
                        ];
                        if (!$this->transaction_model->saveTransaction($transaction)) {
                            return var_dump("Error saving transaction");
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
                        $updatedBalanceResponse = $this->balance_model->updateBalanceRecharge(floatval($amount));
                        if (!$updatedBalanceResponse) {
                            return var_dump("Error updating balance");
                        }
                        $movement = [
                            'message' => "Se realizo una compra en recargas de {$amount}.",
                            'user_id' => $this->session->userdata('id'),
                        ];
                        $this->db->insert('movements', $movement);

                        $this->load->view('header');
                        $this->load->view('emida/success', $data);
                        return;
                    }

                    sleep(10);
                }

                $this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);
                $execution_time = $this->invoice_model->calculateExecutionTime($time_start);
                $data["errors"] = $objXML;
                $data["MethodResponse"] = "Generic Timeout";

                $transaction = [
                    'user_id' => $this->session->userdata('id'),
                    'terminal_id' => $this->config->item('terminal_id_recharges'),
                    'invoice_id' => $lastInvoiceNumber,
                    'product_id' => $productId,
                    'amount' => $amount,
                    'responseCode' => (string)$objXML->ResponseCode,
                ];
                if (!$this->transaction_model->saveTransaction($transaction)) {
                    return var_dump("Error saving transaction");
                }
                $this->load->view('header');
                $this->load->view('emida/failed', $data);
                //execution time of the script
                //echo '<b>Total Execution Time:</b> '.$execution_time.' Segs';
                //echo "<h1>No se proceso la transaccion</h1>";
                //echo "<pre>";
                //return var_dump($objXML);

            }

            return 'Hubo un error al procesar su transaccion';
            //$products =  $objXML->ResponseMessage->Products->Product ;
            //$data["products"] = $products; 
            $this->load->view('header');
            $this->load->view('emida/welcome_message', $data);
        } catch (\Throwable $th) {
            //throw $th;
            $execution_time = $this->invoice_model->calculateExecutionTime($time_start);

            //execution time of the script
            echo '<b>Total Execution Time:</b> ' . $execution_time . ' Segs';
            echo "<h1>Catch Throw</h1>";
            echo "<pre>";
            return var_dump($th);
        }
    }
    public function successa()
    {
        $this->load->view('header');
        $this->load->view('emida/success');
    }

    public function buyService()
    {
        if ($this->session->has_userdata('rand') != $this->input->post('randcheck')) {
            redirect("/");
        }
        $this->session->unset_userdata('rand');
        $comision = $this->input->post('ProductUFee');
        $balance = $this->balance_model->getCurrentBalanceServices();
        $amount = NULL;
        if (null !== ($this->input->post('amountToPay'))) {
            $amount = $this->input->post('amountToPay');
        } else {
            $amount = $this->input->post('amount');
        }
        if (empty($balance) || $balance[0]->balance_services < ($amount) + floatval($comision)) {

            $this->session->set_flashdata('error', 'No tienes suficiente saldo, pide una recarga a tu cuenta');
            redirect('/pos/balanceout', 'refresh');
            return;
        }

        $wsdlURL = $this->config->item('endpoint_emida'); //"https://ws.terecargamos.com:8448/soap/webServices.wsdl";
        $time_start = microtime(true);
        $lookUpTiming = [];
        //$amount = 0;

        (string)$lastInvoiceNumber = $this->invoice_model->lastInvoiceNumber();
        try {
            try {
                $lookUpTiming[] = date("h:i:sa") . ' :: ' . "Start BillpaymentUserFee";

                //$clientWS = new SoapClient($wsdlURL);
                ini_set('default_socket_timeout', 60); // or whatever new value you want}
                $clientWS = new SoapClient($wsdlURL, array(
                    'trace' => true,
                    'connection_timeout' => 60,
                    'cache_wsdl' => WSDL_CACHE_NONE,
                    'keep_alive' => false,
                ));
                $accountId = $this->input->post('productIdentification');
                //$amount = $this->input->post('amountToPay');
                $productId = $this->input->post('ProductId');
                $productName = $this->input->post('ProductName');
                $response = $clientWS->BillpaymentUserFee(
                    $this->config->item('version'),
                    $this->config->item('terminal_id_services'),
                    $this->config->item('clerk_id'),
                    $productId,
                    $amount,
                    $accountId,
                    $lastInvoiceNumber,
                    $this->config->item('language_id')
                ); //("GetCarrierList", $data);

                $request = [
                    'user_id' => $this->session->userdata('id'),
                    'request' => $clientWS->__getLastRequest()
                ];
                $this->db->insert('requests', $request);
                //return var_dump("REQUEST:\n" . $clientWS->__getLastRequest() . "\n");

                $objXML  = new SimpleXMLElement($response);

                ini_set('default_socket_timeout', 60); // or whatever new value you want}

                $this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);
                if ((string)$objXML->ResponseCode != 00) {
                    $data["errors"] = $objXML;
                    $data["responseMethod"] = "BillpaymentUserFee :: Invalid response code";
                    $data["ExecutionTime"] = 1; //$execution_time;

                    $transaction = [
                        'user_id' => $this->session->userdata('id'),
                        'terminal_id' => $this->config->item('terminal_id_services'),
                        'invoice_id' => $lastInvoiceNumber,
                        'product_id' => $productId,
                        'amount' => (string)$objXML->Amount,
                        'responseCode' => (string)$objXML->ResponseCode,
                    ];
                    if (!$this->transaction_model->saveTransaction($transaction)) {
                        return var_dump("Error saving transaction");
                    }
                    $this->load->view('header');
                    $this->load->view('emida/failed', $data);
                    return;
                }

                $execution_time = $this->invoice_model->calculateExecutionTime($time_start);

                $transaction = [
                    'user_id' => $this->session->userdata('id'),
                    'date_time' => (string)$objXML->TransactionDateTime,
                    'terminal_id' => $this->config->item('terminal_id_services'),
                    'responseTransaction_id' => (string)$objXML->TRANSACTION,
                    'invoice_id' => $lastInvoiceNumber,
                    'product_id' => $productId,
                    'amount' => (string)$objXML->Amount,
                    'account_id' => (string)$objXML->Pin,
                    'responseCode' => (string)$objXML->ResponseCode,
                    'carrierControlNo' => (string)$objXML->CarrierControlNo,
                    'responseMessage' => (string)$objXML->ResponseMessage,
                    'productName' => $productName,
                    'fee' => $comision

                ];

                if (!$this->transaction_model->saveTransaction($transaction)) {
                    return var_dump("Error saving transaction");
                }

                $data["paymentResponse"] = $objXML;
                $data["MethodResponse"] =  "BillpaymentUserFee";
                $data["ExecutionTime"] = $execution_time;
                $data["jsonResponse"] = json_encode($lookUpTiming);
                $data["ticket"] = [
                    "TransactionDateTime" => (string)$objXML->TransactionDateTime,
                    "ProductName" => $productName,
                    "Pin" => (string)$objXML->Pin,
                    "Amount" => (string)$objXML->Amount,
                    "CarrierControlNo" => (string)$objXML->CarrierControlNo,
                    "ResponseMessage" => (string)$objXML->ResponseMessage,
                ];
                $updatedBalanceResponse = $this->balance_model->updateBalanceServices(floatval((string)$objXML->Amount)); //(floatval((string)$objXML-> Amount));
                if (!$updatedBalanceResponse) {
                    return var_dump("Error updating balance");
                }
                $movement = [
                    'message' => "Se realizo una compra en servicios de {$amount}.",
                    'user_id' => $this->session->userdata('id'),
                ];
                $this->db->insert('movements', $movement);

                $this->load->view('header');
                $this->load->view('emida/success', $data);
            } catch (\Throwable $th) {
                $lookUpTiming[] = date("h:i:sa") . ' :: ' . "End BillpaymentUserFee";

                //echo "REQUEST HEADERS:\n" . $clientWS->__getLastRequestHeaders() . "\n";

                //$this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);

                //return var_dump($th->getMessage());
                //throw $th;
                $responses = [];
                $intentos = 4;
                for ($i = 0; $i <  $intentos; $i++) {
                    ini_set('default_socket_timeout', 10); // or whatever new value you want
                    $lookUpTiming[] = date("h:i:sa") . ' :: ' . "Start LookUpTransactionByInvocieNo::" . $i;

                    $clientWS2 = new SoapClient($wsdlURL, array(
                        'trace' => true,
                        'connection_timeout' => 10,
                        'cache_wsdl' => WSDL_CACHE_NONE,
                        'keep_alive' => false,
                    ));
                    $response = $clientWS2
                        ->LookUpTransactionByInvocieNo(
                            "01",
                            $this->config->item('terminal_id_services'),
                            $this->config->item('clerk_id'), //"95484", 
                            $lastInvoiceNumber
                        );

                    $objXML  = new SimpleXMLElement($response);
                    $responses[] = json_encode($objXML);
                    //var_dump((string)$objXML->ResponseCode);
                    if ((string)$objXML->ResponseCode == '32') {
                    } else {
                        ini_set('default_socket_timeout', 60); // or whatever new value you want}

                        $this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);
                        $execution_time = $this->invoice_model->calculateExecutionTime($time_start);

                        $data["paymentResponse"] = $objXML;

                        $transaction = [
                            'user_id' => $this->session->userdata('id'),
                            'date_time' => (string)$objXML->TransactionDateTime,
                            'terminal_id' => $this->config->item('terminal_id_services'),
                            'responseTransaction_id' => (string)$objXML->TransactionId,
                            'invoice_id' => $lastInvoiceNumber,
                            'product_id' => $productId,
                            'amount' => (float)$amount + (int)$comision, //(string)$objXML-> Amount,
                            'account_id' => (string)$objXML->PIN,
                            'responseCode' => (string)$objXML->ResponseCode,
                            'carrierControlNo' => (string)$objXML->CarrierControlNo,
                            'responseMessage' => (string)$objXML->ResponseMessage,
                            'productName' => $productName,
                            'fee' => $comision


                        ];

                        if (!$this->transaction_model->saveTransaction($transaction)) {
                            return var_dump("Error saving transaction");
                        }

                        $data["MethodResponse"] =  "LookUpTransactionByInvocieNo::" . $i;
                        $data["ExecutionTime"] = $execution_time;
                        $data["jsonResponse"] = json_encode($lookUpTiming);


                        $data["ticket"] = [
                            "TransactionDateTime" => (string)$objXML->TransactionDateTime,
                            "ProductName" => $productName,
                            "Pin" => (string)$objXML->PIN,
                            "Amount" => (int)$amount + (int)$comision, //(string)$objXML-> Amount,
                            "CarrierControlNo" => (string)$objXML->CarrierControlNo,
                            "ResponseMessage" => (string)$objXML->ResponseMessage,

                        ];
                        $updatedBalanceResponse = $this->balance_model->updateBalanceServices(floatval($amount) + (int)$comision);
                        if (!$updatedBalanceResponse) {
                            return var_dump("Error updating balance");
                        }
                        $movement = [
                            'message' => "Se realizo una compra en recargas de {$amount}.",
                            'user_id' => $this->session->userdata('id'),
                        ];
                        $this->db->insert('movements', $movement);

                        $this->load->view('header');
                        $this->load->view('emida/success', $data);
                        return;
                    }
                    sleep(10);
                    $lookUpTiming[] = date("h:i:sa") . ' :: ' . "End LookUpTransactionByInvocieNo::" . $i;
                }
                ini_set('default_socket_timeout', 60); // or whatever new value you want}
                $this->invoice_model->createNextInvoiceNumber($lastInvoiceNumber);
                $execution_time = $this->invoice_model->calculateExecutionTime($time_start);
                $data["responses"] = json_encode($responses);
                $data["errors"] = $objXML;
                $data["jsonResponse"] = json_encode($lookUpTiming);
                $data["MethodResponse"] =  "General Timeout";
                $data["ExecutionTime"] = $execution_time;
                $transaction = [
                    'user_id' => $this->session->userdata('id'),
                    'terminal_id' => $this->config->item('terminal_id_services'),
                    'invoice_id' => $lastInvoiceNumber,
                    'product_id' => $productId,
                    'amount' => (int)$amount + (int)$comision,  //(string)$objXML-> Amount,
                    'responseCode' => (string)$objXML->ResponseCode,
                ];
                if (!$this->transaction_model->saveTransaction($transaction)) {
                    return var_dump("Error saving transaction");
                }
                $this->load->view('header');
                $this->load->view('emida/failed', $data);
                /*
				//execution time of the script
				echo '<b>Total Execution Time:</b> '.$execution_time.' Segs';
				echo "<h1>No se proceso la transaccion</h1>";
				echo "<pre>";
				return var_dump($objXML);
				*/
            }

            return 'Hubo un error al procesar su transaccion';
            //$products =  $objXML->ResponseMessage->Products->Product ;
            //$data["products"] = $products; 
            $this->load->view('header');
            $this->load->view('emida/welcome_message', $data);
        } catch (\Throwable $th) {
            //throw $th;
            $execution_time = $this->invoice_model->calculateExecutionTime($time_start);

            //execution time of the script
            echo '<b>Total Execution Time:</b> ' . $execution_time . ' Segs';
            echo "<h1>Catch Throw</h1>";
            echo "<pre>";
            return var_dump($th);
        }
    }
}
