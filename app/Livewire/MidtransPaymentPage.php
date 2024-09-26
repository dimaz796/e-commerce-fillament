<?php

namespace App\Http\Livewire;

use Midtrans\Config;
use Midtrans\Snap;
use App\Helpers\CartManagement;

class MidtransPaymentPage
{
    public function processPayment($cart_items, $first_name, $last_name, $phone, $email)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Parameter pembayaran
        $params = [
            'transaction_details' => [
                'order_id' => 'order-' . time(),
                'gross_amount' => CartManagement::calculateGrandTotal($cart_items),
            ],
            'customer_details' => [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone,
            ],
            'item_details' => array_map(function ($item) {
                return [
                    'id' => $item['product_id'],
                    'price' => $item['unit_amount'],
                    'quantity' => $item['quantity'],
                    'name' => $item['name'],
                ];
            }, $cart_items),
        ];

        // Generate Snap token
        $snapToken = Snap::getSnapToken($params);

        // Redirect ke halaman pembayaran Midtrans
        return "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken;
    }
}
