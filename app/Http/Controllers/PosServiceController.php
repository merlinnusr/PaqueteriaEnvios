<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Services\Pos\ServiceService;
use Illuminate\Http\Request;

class PosServiceController extends Controller
{
    public function index()
    {
        $categories = Product::where('type', 'service')->first();


        $objXML  = new \SimpleXMLElement($categories->data);
        $products =  $objXML->ResponseMessage->Products->Product;
        $categories = [];
        foreach ($products as $product) {
            if (!in_array((string)$product->ProductCategory, $categories)) {
                $categories[] = (string)$product->ProductCategory;
            }
        }
        return view('pos.service.index', ['categories' => $categories]);
    }
    public function subcategory($category)
    {
        $category = urldecode($category);

        $response = Product::where('type', 'service')->first();
        $subCategories = Product::where('type', 'service')->first();

        $objXML  = new \SimpleXMLElement($subCategories->data);
        $products =  $objXML->ResponseMessage->Products->Product;

        $subCategories = [];
        foreach ($products as $product) {
            if ((string)$product->ProductCategory === $category) {
                if (!in_array((string)$product->ProductSubCategory, $subCategories)) {
                    $subCategories[] = (string)$product->ProductSubCategory;
                }
            }
        }
        return view('pos.service.subcategory', ['subcategories' => $subCategories]);
    }
    public function product($product)
    {
        $category = urldecode($product);
        $products = Product::where('type', 'service')->first();

        $objXML  = new \SimpleXMLElement($products->data);


        $products =  $objXML->ResponseMessage->Products->Product;

        $serviceProducts = [];
        foreach ($products as $product) {
            if ((string)$product->ProductSubCategory === $category) {
                $serviceProducts[] = $product;
            }
        }
        // return var_dump($serviceProducts);
        return view('pos.service.product', ['products' => $serviceProducts]);
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

        return view('pos.service.item', $data);
    }
    public function buy(Request $request)
    {
        $product = (array)$request->post();
        // return response()->json($product);
        $response = (new ServiceService)->buy($product, auth()->id());
        return var_dump($response);
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
