<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Cart;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Title("Cart - Fillamers")]
class CartPage extends Component
{
    public $cart_items = [];
    public $grand_total;

    // Mendapatkan data cart dari database saat mount
    public function mount()
    {
        // Mengambil semua item cart dari database untuk user yang sedang login
        $this->cart_items = Cart::where('user_id', Auth::id())->with('product', 'productVariant')->get();
        $this->grand_total = CartManagement::calculateGrandTotal();
    }

    // Menambah kuantitas item di cart
    public function increaseQty($cart_id)
{
    // Dapatkan item di keranjang berdasarkan cart_id
    $cart_item = Cart::where('id', $cart_id)->where('user_id', Auth::id())->with('productVariant')->first();

    if (!$cart_item) {
        $this->alert('error', 'Item tidak ditemukan di keranjang.', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true
        ]);
        return;
    }

    // Jika kuantitas belum melebihi stok, tambahkan kuantitas item di keranjang
    CartManagement::incrementQuantityToCartItem($cart_id);

    // Refresh data keranjang dan total keseluruhan
    $this->cart_items = Cart::where('user_id', Auth::id())->with('product', 'productVariant')->get();
    $this->grand_total = CartManagement::calculateGrandTotal();
}


    // Mengurangi kuantitas item di cart
    public function decreaseQty($cart_id)
    {
        // Cari item di tabel cart berdasarkan cart_id
        CartManagement::decrementQuantityToCartItem($cart_id);

        // Refresh cart items dan grand total setelah update
        $this->cart_items = Cart::where('user_id', Auth::id())->with('product', 'productVariant')->get();
        $this->grand_total = CartManagement::calculateGrandTotal();
    }

    // Menghapus item dari cart
   // Di method removeItem(), hilangkan toArray() untuk tetap menggunakan Collection
public function removeItem($product_id, $variant_id = null)
{
    // Hapus item dari tabel cart
    CartManagement::removeCartItem($product_id, $variant_id);
    
    // Refresh cart items dan grand total setelah update
    $this->cart_items = Cart::where('user_id', Auth::id())
        ->with('product', 'productVariant')
        ->get(); // Tetap sebagai Collection, tidak perlu diubah menjadi array
    
    $this->grand_total = CartManagement::calculateGrandTotal();

    // Menggunakan dispatch untuk memancarkan event update jumlah item di cart
    $total_count = $this->cart_items instanceof \Illuminate\Support\Collection 
                   ? $this->cart_items->count() 
                   : count($this->cart_items);

    // Menggunakan dispatch untuk memancarkan event update jumlah item di cart
    $this->dispatch('update-cart-count', ['total_count' => $total_count]);
}



    public function render()
    {
        return view('livewire.cart-page', [
            'cart_items' => $this->cart_items,
            'grand_total' => $this->grand_total,
        ]);
    }
}
