<?php

namespace App\Services\Midtrans;

use App\Models\Order;
use Midtrans\Notification;

class CallbackService extends Midtrans
{
    protected $notification;
    protected $order;
    protected $server_key;

    public function __construct()
    {
        parent::__construct();

        $this->server_key = env('MIDTRANS_SERVER_KEY');
        $this->_handleNotification();
    }

    public function isSignatureKeyVerified()
    {
        return ($this->_createLocalSignatureKey() == $this->notification->signature_key);
    }

    public function isSuccess()
    {
        $statusCode = $this->notification->status_code;
        $transactionStatus = $this->notification->transaction_status;
        $fraudStatus = !empty($this->notification->fraud_status) ? ($this->notification->fraud_status == 'accept') : true;
 
        return ($statusCode == 200 && $fraudStatus && ($transactionStatus == 'capture' || $transactionStatus == 'settlement'));
    }
 
    public function isExpire()
    {
        return ($this->notification->transaction_status == 'expire');
    }
 
    public function isCancelled()
    {
        return ($this->notification->transaction_status == 'cancel');
    }

    public function getNotification()
    {
        return $this->notification;
    }

    public function getMidtransSignature()
    {
        return $this->notification->signature_key;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function _createLocalSignatureKey()
    {
        $orderId = $this->order->order_id;
        $statusCode = $this->notification->status_code;
        $grossAmount = $this->order->total_amount;
        $input = $orderId . $statusCode . $grossAmount . $this->server_key;
        $signature = openssl_digest($input, 'sha512');
 
        return $signature;
    }

    public function _handleNotification()
    {
        $notification = new Notification();
        $order_id = $notification->order_id;
        $order = Order::where('order_id', $order_id)->first();
        if (!isset($order)) {
            $order = Order::create([
                'order_id' => $notification->order_id,
                'payment_code' => $notification->payment_code,
                'payment_type' => $notification->payment_type,
                'status' => $notification->transaction_status,
                'transaction_id' => $notification->transaction_id,
                'invoice_url' => $notification->pdf_url,
                'total_amount' => $notification->gross_amount,
            ]);
        }

        $this->notification = $notification;
        $this->order = $order;
    }
}