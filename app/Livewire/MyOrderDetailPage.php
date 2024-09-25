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

    // Menerima order_id dari parameter route
    public function mount($order_id)
    {
        $this->order_id = $order_id;
    }

    public function render()
    {
        // Mengambil order item dengan relasi produk
        $order_items = OrderItem::with('product')->where('order_id', $this->order_id)->get();

        // Mengambil alamat terkait order
        $address = Address::where('order_id', $this->order_id)->first();

        // Mengambil detail order
        $order = Order::find($this->order_id);

        // Melemparkan data ke view
        return view('livewire.my-order-detail-page', [
            'order_items' => $order_items,
            'address' => $address,
            'order' => $order,
        ]);
    }
}
