<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Detail Product")]
class ProductDetailPage extends Component
{
    use LivewireAlert;

    public $slug;
    public $quantity = 1;
    public $selected_variant_id = null;  // ID varian produk yang dipilih
    public $selected_variant_name = '';
    public $product;
    public $productVariants;
    public $selected_variant_image;
    public $selected_variant_price;
    public $selected_variant_stock;

    // Inisialisasi slug saat mount
    public function mount($slug)
    {
        // Cari produk berdasarkan slug
        $this->product = Product::where('slug', $slug)->with('product_variants')->firstOrFail();

        // Ambil semua varian produk
        $this->productVariants = $this->product->product_variants;

        // Set default values (untuk saat tidak ada varian yang dipilih)
        $this->selected_variant_image = $this->product->images[0]; // Gambar default
        $this->selected_variant_price = $this->productVariants->min('price'); // Harga default (harga minimum varian)
        $this->selected_variant_stock = $this->product->stock; // Stok default (gabungan semua varian)
    }

    // Fungsi untuk menambah quantity
    public function increaseQty()
{
    // Dapatkan jumlah item yang sudah ada di keranjang
    $cart_item = Cart::where('product_id', $this->product->id) // Menggunakan $this->product->id
                    ->where('product_variant_id', $this->selected_variant_id) // Menggunakan $this->selected_variant_id
                    ->where('user_id', Auth::id())
                    ->first();
    
    $quantity_in_cart = $cart_item ? $cart_item->quantity : 0; // Jika ada di keranjang, ambil kuantitas

    // Total kuantitas (di keranjang + yang ingin ditambahkan)
    $total_quantity = $quantity_in_cart + $this->quantity;

    // Cek apakah total kuantitas melebihi stok
    if ($total_quantity < $this->selected_variant_stock) { // Periksa berdasarkan stok varian yang dipilih
        // Jika masih dalam batas stok, tambahkan kuantitas baru
        $this->quantity++;
    } else {
        // Jika melebihi stok, tampilkan alert
        $this->alert('error', 'Jumlah item di keranjang dan yang ingin ditambahkan melebihi stok yang tersedia.', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true
        ]);
    }
}

    

    // Fungsi untuk mengurangi quantity (tidak boleh kurang dari 1)
    public function decreaseQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    // Fungsi untuk memilih varian produk
    public function selectVariant($variant_id, $price, $image, $stock)
    {

        $cleaned_image = str_replace('http://ecommerce-fillament.test/storage/', '', $image);
        $this->selected_variant_id = $variant_id;
        $this->selected_variant_price = $price;
        $this->selected_variant_image = $cleaned_image;
        $this->selected_variant_stock = $stock;
        $this->quantity = 1;


    }

    // Fungsi untuk menambahkan produk ke keranjang, termasuk varian dan quantity
    public function addToCart()
{
    if(!auth()->check()){
        return redirect()->route('login');
    }
    if ($this->selected_variant_id === null) {
        // Jika varian belum dipilih, tampilkan alert
        $this->alert('warning', 'Silakan pilih varian sebelum menambahkan ke keranjang!', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true
        ]);
        return;
    }

    // Panggil fungsi addItemToCartWithQty dengan ID varian dan produk yang dipilih
    $result = CartManagement::addItemToCartWithQty($this->product->id, $this->selected_variant_id, $this->quantity);

    // Cek apakah ada error (misalnya, melebihi stok)
    if (isset($result['error'])) {
        $this->alert('error', $result['error'], [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true
        ]);
    } else {
        // Jika sukses, dispatch event untuk mengupdate jumlah item di keranjang
        $this->dispatch('update-cart-count', total_count: $result)->to(Navbar::class);

        // Tampilkan alert sukses
        $this->alert('success', 'Produk berhasil ditambahkan ke keranjang!', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true
        ]);
    }
}

    
    public function render()
    {
        // Fetch the product, variants, and ratings
        $product = Product::where('slug', $this->slug)
            ->with(['ratings.user', 'product_variants']) // Eager load to avoid N+1 problem
            ->firstOrFail();

        // Fetch product variants
        $productVariants = $product->product_variants; // Ambil semua varian produk

        // Calculate the average rating and count of total ratings
        $averageRating = $product->ratings()->avg('rating');
        $ratingsCount = $product->ratings()->count();

        // Ensure that even with 0 ratings, we can pass the required variables
        $star_5_count = $product->ratings()->where('rating', 5)->count();
        $star_4_count = $product->ratings()->where('rating', 4)->count();
        $star_3_count = $product->ratings()->where('rating', 3)->count();
        $star_2_count = $product->ratings()->where('rating', 2)->count();
        $star_1_count = $product->ratings()->where('rating', 1)->count();

        // Prevent division by zero
        $star_5_percentage = $ratingsCount > 0 ? ($star_5_count / $ratingsCount) * 100 : 0;
        $star_4_percentage = $ratingsCount > 0 ? ($star_4_count / $ratingsCount) * 100 : 0;
        $star_3_percentage = $ratingsCount > 0 ? ($star_3_count / $ratingsCount) * 100 : 0;
        $star_2_percentage = $ratingsCount > 0 ? ($star_2_count / $ratingsCount) * 100 : 0;
        $star_1_percentage = $ratingsCount > 0 ? ($star_1_count / $ratingsCount) * 100 : 0;

        // Pass the data to the view
        return view('livewire.product-detail-page', [
            'product' => $product,
            'productVariants' => $productVariants, // Kirim varian produk ke view
            'reviews' => $product->ratings()->latest()->get(),
            'averageRating' => number_format($averageRating, 1),
            'ratingsCount' => $ratingsCount,
            'star_5_percentage' => $star_5_percentage,
            'star_4_percentage' => $star_4_percentage,
            'star_3_percentage' => $star_3_percentage,
            'star_2_percentage' => $star_2_percentage,
            'star_1_percentage' => $star_1_percentage,
            'star_5_count' => $star_5_count,
            'star_4_count' => $star_4_count,
            'star_3_count' => $star_3_count,
            'star_2_count' => $star_2_count,
            'star_1_count' => $star_1_count,
        ]);
    }
}
