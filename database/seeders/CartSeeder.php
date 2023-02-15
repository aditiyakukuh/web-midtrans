<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        $cart = Cart::factory([
            'user_id' => $user->id,
        ])->create();
        $cart_item = CartItem::factory([
            'price' => rand(100,1000),
            'qty' => rand(1,5),
            'cart_id' => $cart->id
        ])->count(3)->create();
    }
}
