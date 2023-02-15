<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;

class CreateSnapTokenService extends Midtrans
{
    protected $order;
    protected $customer;
    protected $params;

    public function __construct($order, $customer)
    {
        parent::__construct();

        $this->order = $order;
        $this->customer = $customer;
        $this->params = $this->_handleParams();
    }

    public function getSnapToken()
    {
        $snapToken = Snap::getSnapToken($this->params);

        return $snapToken;
    }

    public function getSnapUrl()
    {
        $snapToken = Snap::getSnapUrl($this->params);

        return $snapToken;
    }

    public function _handleParams()
    {
        return [
            'transaction_details' => array(
                'order_id' => $this->order['order_id'],
                'gross_amount' =>  $this->order['total_amount'],
            ),
            'item_details' => $this->order['products'],
            'customer_details' => [
                'first_name' => $this->customer['first_name'],
                'email' => $this->customer['email'],
                'phone' => $this->customer['phone'],
            ]
        ];
    }
}