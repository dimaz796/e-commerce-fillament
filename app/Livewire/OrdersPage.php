<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Title("My Orders - Fillamers")]

class OrdersPage extends Component
{
    use WithPagination,WithoutUrlPagination;
    public function render()
    {
        $my_order = Order::where("user_id",auth()->id())->latest()->paginate(2);
        return view('livewire.orders-page',['orders' => $my_order]);
    }
}
