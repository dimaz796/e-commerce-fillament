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
use Illuminate\Database\Eloquent\Builder;

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



    public function render()
    {
        // Query produk yang aktif
   

        $productQuery = Product::query()
        ->where('is_active', 1)
        ->withCount([
            'ratings as average_rating' => function ($query) {
                $query->select(DB::raw('ROUND(coalesce(avg(rating), 0), 1)'));
            },
        ])
        ->leftjoin('product_variants', 'products.id', '=', 'product_variants.product_id') // Join dengan tabel product_variants
        ->leftJoin('ratings', 'products.id', '=', 'ratings.product_id') // Join dengan tabel ratings
        ->select(
            'products.*',
            DB::raw('(SELECT SUM(order_items.quantity) FROM order_items WHERE order_items.product_id = products.id) as sold_count '),
            DB::raw('MIN(product_variants.price) as min_price'),
            DB::raw('ROUND(coalesce(avg(ratings.rating), 0), 1) as average_rating') // Menghitung rata-rata rating
        )
        ->groupBy('products.id');

    
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
