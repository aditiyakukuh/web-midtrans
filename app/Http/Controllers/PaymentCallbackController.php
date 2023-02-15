<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Midtrans\CallbackService;
use Illuminate\Http\Request;

class PaymentCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $callback = new CallbackService;

        if ($callback->isSignatureKeyVerified()) {
            $order = $callback->getOrder();
            $notification = $callback->getNotification();

            if ($callback->isSuccess()) {
                Order::where('id', $order->id)->update([
                    'status' => 'settlement',
                ]);
            }
 
            if ($callback->isExpire()) {
                Order::where('id', $order->id)->update([
                    'status' => $notification->transaction_status,
                ]);
            }
 
            if ($callback->isCancelled()) {
                Order::where('id', $order->id)->update([
                    'status' => $notification->transaction_status,
                ]);
            }
 
            return response()
                ->json([
                    'success' => true,
                    'message' => 'Notification Process Successfully',
                ]);
        }else {
            return response()->json([
                'error' => true,
                'message' => 'signature key is unverified',
            ], 403);
        }
    }
}
