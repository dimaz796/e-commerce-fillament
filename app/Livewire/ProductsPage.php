<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("Product - Fillamers")]
class ProductsPage extends Component
{
    use LivewireAlert;

    use WithPagination;
    
    #[Url]
    public $selected_categories = [];

    #[Url]
    public $selected_brands = [];

    #[Url]
    public $featured = [];

    #[Url]
    public $on_sale = [];

    #[Url]
    public  $range_price = 0;

    #[Url]
    public $sort = 'latest';

    //Add product to cart methods
    public function addToCart($product_id){
     $total_count = CartManagement::addItemToCart($product_id);

     $this->dispatch('update-cart-count', total_count : $total_count)->to(Navbar::class);

     $this->alert('success', 'Product added to the cart successfully!',[
        'position' =>'bottom-end',
        'timer'=> 3000,
        'toast' => true
     ]);
    }

    public function render()
    {
        // Query produk yang aktif
        $productQuery = Product::query()
        ->where('is_active', 1)
        ->withCount([
            'ratings as average_rating' => function ($query) {
                $query->select(DB::raw('ROUND(coalesce(avg(rating), 0), 1)')); // Menggunakan ROUND untuk 1 angka desimal
            },
            'orderItems as sold_count'
        ]);
    
        // Filter berdasarkan kategori
        if(!empty($this->selected_categories)){
            $productQuery->whereIn('category_id', $this->selected_categories); 
        }
    
        // Filter berdasarkan merek
        if(!empty($this->selected_brands)){
            $productQuery->whereIn('brand_id', $this->selected_brands); 
        }
    
        // Filter berdasarkan produk featured
        if($this->featured){
            $productQuery->where('is_featured', 1);
        }
    
        // Filter berdasarkan produk yang sedang sale
        if($this->on_sale){
            $productQuery->where('on_sale', 1);
        }
    
        // Filter berdasarkan harga
        if($this->range_price > 0){
            $productQuery->where('price', '<=', $this->range_price);
        }
    
        // Sortir berdasarkan pilihan
        if ($this->sort == 'latest') {
            $productQuery->latest();
        }
        if ($this->sort == 'price') {
            $productQuery->orderBy('price');
        }
    
        // Return view dengan pagination
        return view('livewire.products-page', [
            'products' => $productQuery->paginate(6),
            'brands' => Brand::where('is_active', 1)->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),
        ]);
    }
    
}
