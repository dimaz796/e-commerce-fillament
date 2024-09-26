<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class SuccessPage extends Component
{
    public $order;

    public function mount()
    {
        // Ambil order terbaru pengguna (bisa menyesuaikan sesuai kebutuhan)
        $this->order = Order::with('address')->where('user_id', auth()->user()->id)->latest()->first();
    }

    public function render()
    {
        return view('livewire.success-page', [
            'order' => $this->order,
        ]);
    }
}
