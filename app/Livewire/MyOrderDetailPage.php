<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Detail Order")]
class MyOrderDetailPage extends Component
{
    public $order_id;

    public function mount($order_id){
        $this->order_id = $order_id;
    }

    public function render()
    {
        // Ambil order items dan pastikan data variannya diambil juga
        $order_items = OrderItem::with(['product' => function($query) {
            $query->with('product_variants');
        }])->where('order_id', $this->order_id)->get();

        // Ambil address dan order terkait
        $address = Address::where('order_id', $this->order_id)->first();
        $order = Order::where('id', $this->order_id)->first();

        return view('livewire.my-order-detail-page', [
            'order_items' => $order_items,
            'address' => $address,
            'order' => $order
        ]);
    }
}
