<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\Order;
use App\Models\Address;
use Livewire\Component;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Http\Request;

class MidtransWebhookPage extends Component
{
    public function handleMidtransWebhook(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Ambil notifikasi dari Midtrans
        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $order_id = $notif->order_id;

        if ($transaction == 'settlement') {
            // Jika pembayaran berhasil
            $this->saveOrder($order_id);
        }
    }

    public function saveOrder($order_id)
    {
        // Simpan data order ke dalam database setelah pembayaran sukses
        $cart_items = CartManagement::getCartItemsFromCookie();
        $order = new Order;
        $order->user_id = auth()->user()->id;
        $order->order_id = $order_id;
        $order->grand_total = CartManagement::calculateGrandTotal($cart_items);
        $order->payment_method = 'midtrans';
        $order->payment_status = "paid";
        $order->status = "new";
        $order->save();

        // Simpan alamat pengiriman
        $address = new Address;
        $address->first_name = request('first_name');
        $address->last_name = request('last_name');
        $address->phone = request('phone');
        $address->street_address = request('street_address');
        $address->city = request('city');
        $address->state = request('state');
        $address->zip_code = request('zip_code');
        $address->order_id = $order->id;
        $address->save();
    }
}
