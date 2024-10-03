<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Rating;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ProductRating extends Component
{
    use WithFileUploads;

    public $productId;
    public $orderId;
    public $rating;
    public $comment;
    public $image;
    public $hasRated = false;
    public $averageRating;
    public $product;

    public function mount($productId, $orderId){
        $this->productId = $productId;
        $this->orderId = $orderId;

        $this->product = Product::findOrFail($this->productId);

        // Cek apakah user sudah memberi rating untuk produk ini dalam order ini
        $this->hasRated = Rating::where('product_id', $this->productId)
                                ->where('order_id', $this->orderId)
                                ->where('user_id', auth()->id())
                                ->exists();

        // Ambil average rating untuk produk
        $this->averageRating = Rating::where('product_id', $this->productId)->avg('rating');
    }

    public function submitRating(){
        
        //Validasi Input
        $this->validate([
            'rating' => ['required', 'numeric', 'between:1,5'],
            'comment' => ['required','string','max:255'],
            'image' => ['nullable', 'image','max:2048'],
        ]);

        // Periksa apakah user sudah memberi rating untuk produk ini
        if ($this->hasRated) {
            session()->flash('error', 'Anda sudah memberikan rating pada produk ini.');
            return;
        }

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('ratings', 'public');
        }

        // Simpan Rating ke database
        Rating::create([
            'product_id' => $this->productId,
            'user_id' => auth()->id(),
            'rating' => $this->rating,
            'comment' => $this->comment,
            'image' => $imagePath,
            'order_id' => $this->orderId,
        ]);

        // Update bahwa rating sudah dilakukan untuk produk ini
        $this->hasRated = true;
        $this->averageRating = Rating::where('product_id', $this->productId)->avg('rating');
        $this->reset(['rating', 'comment', 'image']);

        // Redirect ke halaman "My Orders" setelah berhasil
        return redirect()->route('my-orders.show', ['order_id' => $this->orderId]);

    }

    public function render()
    {
        return view('livewire.product-rating');
    }
}
