<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title("Detail Product")]
class ProductDetailPage extends Component
{
    use LivewireAlert;
    public $slug;
    public $quantity = 1;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function increaseQty(){
        $this->quantity++;
    }
    
    public function decreaseQty(){
        if($this->quantity > 1){
            $this->quantity--;
        }
    }

    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCartWithQty($product_id,$this->quantity);
   
        $this->dispatch('update-cart-count', total_count : $total_count)->to(Navbar::class);
   
        $this->alert('success', 'Product added to the cart successfully!',[
           'position' =>'bottom-end',
           'timer'=> 3000,
           'toast' => true
        ]);
       }


       public function render()
       {
           // Fetch the product and ratings
           $product = Product::where('slug', $this->slug)
               ->with('ratings.user') // Eager load to avoid N+1 problem
               ->firstOrFail();
       
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
