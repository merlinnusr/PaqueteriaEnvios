<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Services\Pos\RechargeService;

class PosRechargeController extends Controller
{
    public function index()
    {
        $categories = Product::where('type', 'recharge')->first();


        $objXML  = new \SimpleXMLElement($categories->data);
        $products =  $objXML->ResponseMessage->Products->Product;
        $categories = [];
        foreach ($products as $product) {
            if (!in_array((string)$product->ProductCategory, $categories)) {
                $categories[] = (string)$product->ProductCategory;
            }
        }
        return view('pos.recharge.index', ['categories' => $categories]);
    }
    public function subcategory($category)
    {
        $category = urldecode($category);

        $subCategories = Product::where('type', 'recharge')->first();

        $objXML  = new \SimpleXMLElement($subCategories->data);
        $products =  $objXML->ResponseMessage->Products->Product;

        $subCategories = [];
        foreach ($products as $product) {
            if ((string)$product->ProductCategory === $category) {
                if (!in_array((string)$product->ProductSubCategory, $subCategories)) {
                    $subCategories[] = $product;
                }
            }
        }
        usort($subCategories, function ($a, $b) {
            return strcmp((string)$a->ProductName, (string)$b->ProductName);
        });


        return view('pos.recharge.product', ['products' => $subCategories]);
    }
    public function product($product)
    {
        $category = urldecode($product);
        $products = Product::where('type', 'recharge')->first();

        $objXML  = new \SimpleXMLElement($products->data);


        $products =  $objXML->ResponseMessage->Products->Product;

        $serviceProducts = [];
        foreach ($products as $product) {
            if ((string)$product->ProductSubCategory === $category) {
                $serviceProducts[] = $product;
            }
        }
        return var_dump($serviceProducts);
        return view('pos.recharge.product', ['products' => $serviceProducts]);
    }
    public function item(Request $request)
    {
        $data = [
            'ProductId' => $request->post('ProductId'),
            'LengthMin' => $request->post('LengthMin'),
            'LengthMax' => $request->post('LengthMax'),
            'Amount' => $request->post('Amount'),
            'AmountMin' => $request->post('AmountMin'),
            'AmountMax' => $request->post('AmountMax'),
            'CarrierName' => $request->post('CarrierName'),
            'ProductName' => $request->post('ProductName'),
            'ReferenceName' => $request->post('ReferenceName'),
            'FieldType' => $request->post('FieldType'),
            'ProductUFee' => $request->post('ProductUFee'),
            'PaymentType' => $request->post('PaymentType')

        ];

        $data["products"] = $data;

        return view('pos.recharge.item', $data);
    }
    public function buy(Request $request)
    {
        $product = (array)$request->post();
        $response = (new RechargeService)->buy($product, auth()->id());
        if ($response['status'] === 'success') {

            $user = User::where('id', auth()->id())->first();
            $amountToPay = floatval(number_format($response['response']['ticket']['Amount'], 2, '.', ''));
            $wallet = floatval($user->wallet);
            $newBalance = $wallet - $amountToPay;
            User::where('id', auth()->id())->update(['wallet' => $newBalance]);
            return redirect()->route('pos.ticket', ['response' => $response]);
        } else {
            return redirect()->route('pos.ticket', ['response' => $response]);
        }
    }
}
