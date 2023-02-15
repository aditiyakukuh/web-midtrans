<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Services\Midtrans\CreateSnapTokenService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $cart = Cart::with(['user', 'item'])->first();
        $data = $this->_handleCart($cart);
        $customer = $this->_handleCustomer($cart->user);
        
        $order = new CreateSnapTokenService($data, $customer);

        $snapToken = $order->getSnapToken();
        return view('payment.cart', compact('snapToken', 'cart'));
    }

    public function _handleCart($cart)
    {
        $items = array();
        foreach ($cart['item'] as $value) {
            $array = [
                "id" => $value['id'],
                "price" => $value['price'],
                "quantity" => $value['qty'],
                "name" => $value['product_name']
            ];
            array_push($items, $array);
        }
        
        return [
            "order_id" => rand(),
            "products" => $items,
            "total_amount" => $cart->getTotalAmount()
        ];
    }

    public function _handleCustomer($user)
    {
        return [
            'first_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];
    }
}
