<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartManagement
{
    // Add item to cart
    public static function addItemToCart($product_id, $variant_id = null)
    {
        // Get existing cart item for the logged-in user
        $cart_item = Cart::where('product_id', $product_id)
            ->where('product_variant_id', $variant_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($cart_item) {
            // If the item already exists in the cart, increase the quantity
            $cart_item->quantity++;
            $cart_item->total_price = $cart_item->quantity * self::getPriceFromVariant($variant_id);
            $cart_item->save();
        } else {
            // If the item does not exist, add it to the cart
            $product = Product::find($product_id);
            $variant = ProductVariant::find($variant_id);
            
            if ($product && $variant) {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product_id,
                    'product_variant_id' => $variant_id,
                    'quantity' => 1,
                    'total_price' => $variant->price,  // Total dihitung dari varian
                ]);
            }
        }

        return Cart::where('user_id', Auth::id())->count();
    }

    
    public static function addItemToCartWithQty($product_id, $variant_id = null, $qty = 1)
{
    // Dapatkan varian produk untuk memeriksa stok
    $variant = ProductVariant::find($variant_id);
    
    if (!$variant) {
        return ['error' => 'Varian produk tidak ditemukan.'];
    }

    // Ambil item keranjang yang sudah ada
    $cart_item = Cart::where('product_id', $product_id)
        ->where('product_variant_id', $variant_id)
        ->where('user_id', Auth::id())
        ->first();

    // Hitung kuantitas saat ini di keranjang (jika ada)
    $current_quantity = $cart_item ? $cart_item->quantity : 0;

    // Cek apakah kuantitas baru melebihi stok
    if ($current_quantity + $qty > $variant->stock) {
        return [
            'error' => 'Jumlah item yang ditambahkan melebihi stok yang tersedia. Anda sudah memiliki ' . $current_quantity . ' item di keranjang.'
        ];
    }

    // Jika item sudah ada di keranjang, tambahkan kuantitas
    if ($cart_item) {
        $cart_item->quantity += $qty;
        $cart_item->total_price = $cart_item->quantity * $variant->price;
        $cart_item->save();
    } else {
        // Jika item belum ada di keranjang, tambahkan item baru
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $product_id,
            'product_variant_id' => $variant_id,
            'quantity' => $qty,
            'total_price' => $variant->price * $qty,
        ]);
    }

    // Kembalikan jumlah total item di keranjang
    return Cart::where('user_id', Auth::id())->count();
}


    // Remove item from cart
    public static function removeCartItem($product_id, $variant_id = null)
{
    $cart_item = Cart::where('product_id', $product_id)
        ->where('product_variant_id', $variant_id)
        ->where('user_id', Auth::id())
        ->first();

    if ($cart_item) {
        $cart_item->delete(); // Menghapus item cart
    }

    return Cart::where('user_id', Auth::id())->get();
}


    // Increment item quantity in cart
    public static function incrementQuantityToCartItem($cart_id)
    {
        $cart_item = Cart::where('id', $cart_id)->where('user_id', Auth::id())->first();
    
        // Jika item ditemukan dan kuantitas lebih dari 1
        if ($cart_item && $cart_item->quantity >= 1) {
            // Kurangi kuantitas
            $cart_item->quantity++;
            
            // Hitung ulang total harga berdasarkan kuantitas baru
            $cart_item->total_price = $cart_item->quantity * self::getPriceFromVariant($cart_item->product_variant_id);
            
            // Simpan perubahan
            $cart_item->save();
        }
    
        return $cart_item;
    }

    // Decrement item quantity in cart
    public static function decrementQuantityToCartItem($cart_id)
    {
        // Cari item keranjang berdasarkan cart_id
        $cart_item = Cart::where('id', $cart_id)->where('user_id', Auth::id())->first();
    
        // Jika item ditemukan dan kuantitas lebih dari 1
        if ($cart_item && $cart_item->quantity > 1) {
            // Kurangi kuantitas
            $cart_item->quantity--;
            
            // Hitung ulang total harga berdasarkan kuantitas baru
            $cart_item->total_price = $cart_item->quantity * self::getPriceFromVariant($cart_item->product_variant_id);
            
            // Simpan perubahan
            $cart_item->save();
        }
    
        return $cart_item;
    }
    

    // Calculate grand total of the cart
    public static function calculateGrandTotal()
    {
        $cart_items = Cart::where('user_id', Auth::id())->get();
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item->quantity * self::getPriceFromVariant($item->product_variant_id);
        }
        return $total;
    }

    // Get price from product variant
    private static function getPriceFromVariant($variant_id)
    {
        $variant = ProductVariant::find($variant_id);
        return $variant ? $variant->price : 0;
    }
}
