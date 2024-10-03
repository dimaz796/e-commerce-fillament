<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <section class="py-10 bg-gray-50 font-poppins dark:bg-gray-800 rounded-lg">
        <div class="px-4 py-4 mx-auto max-w-7xl lg:py-6 md:px-6">
            <div class="flex flex-wrap mb-24 -mx-3">
                <div class="w-full pr-2 lg:w-1/4 lg:block">
                    <!-- Categories Section -->
                    <div
                        class="p-4 mb-5 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-900 dark:bg-gray-900 transition-all duration-300">
                        <h2 class="text-2xl font-bold dark:text-gray-200 text-gray-800">Categories</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                        <ul>
                            @foreach ($categories as $category)
                                <li class="mb-4" wire:live="{{ $category->id }}">
                                    <label for="{{ $category->slug }}"
                                        class="flex items-center dark:text-gray-300 text-gray-800 hover:text-rose-600 dark:hover:text-rose-400 transition duration-200">
                                        <input type="checkbox" id="{{ $category->slug }}"
                                            wire:model.live="selected_categories" value="{{ $category->id }}"
                                            class="w-4 h-4 mr-2 rounded-md focus:ring-rose-500 border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                        <span class="text-lg">{{ $category->name }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Brands Section -->
                    <div
                        class="p-4 mb-5 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-900 dark:border-gray-900 transition-all duration-300">
                        <h2 class="text-2xl font-bold dark:text-gray-200 text-gray-800">Brand</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                        <ul>
                            @foreach ($brands as $brand)
                                <li class="mb-4" wire:live="{{ $brand->id }}">
                                    <label for="{{ $brand->slug }}"
                                        class="flex items-center dark:text-gray-300 text-gray-800 hover:text-rose-600 dark:hover:text-rose-400 transition duration-200">
                                        <input type="checkbox" wire:model.live="selected_brands"
                                            id="{{ $brand->slug }}" value="{{ $brand->id }}"
                                            class="w-4 h-4 mr-2 rounded-md focus:ring-rose-500 border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                        <span class="text-lg">{{ $brand->name }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Product Status Section -->
                    <div
                        class="p-4 mb-5 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-900 dark:border-gray-900 transition-all duration-300">
                        <h2 class="text-2xl font-bold dark:text-gray-200 text-gray-800">Product Status</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                        <ul>
                            <li class="mb-4">
                                <label for="featured"
                                    class="flex items-center dark:text-gray-300 text-gray-800 hover:text-rose-600 dark:hover:text-rose-400 transition duration-200">
                                    <input type="checkbox" id="featured" wire:model.live="featured" value="1"
                                        class="w-4 h-4 mr-2 rounded-md focus:ring-rose-500 border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                    <span class="text-lg">Featured Product</span>
                                </label>
                            </li>
                            <li class="mb-4">
                                <label for="on_sale"
                                    class="flex items-center dark:text-gray-300 text-gray-800 hover:text-rose-600 dark:hover:text-rose-400 transition duration-200">
                                    <input type="checkbox" id="on_sale" wire:model.live="on_sale" value="1"
                                        class="w-4 h-4 mr-2 rounded-md focus:ring-rose-500 border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                    <span class="text-lg">On Sale</span>
                                </label>
                            </li>
                        </ul>
                    </div>

                    <!-- Price Range Section -->
                    <div
                        class="p-4 mb-5 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-900 dark:border-gray-800 transition-all duration-300">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Price</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-gray-300 dark:border-gray-700"></div>
                        <div class="mt-2 mb-4 text-center">
                            <span class="inline-block text-lg font-bold text-gray-800 dark:text-gray-200">
                                {{ Number::currency($range_price, 'IDR') }}
                            </span>
                        </div>
                        <input type="range"
                            class="w-full h-2 rounded-lg bg-gray-200 dark:bg-gray-700 appearance-none cursor-pointer transition-all duration-300"
                            min="0" max="10000000" step="50000" wire:model="range_price"
                            value="{{ $range_price }}">
                        <div class="flex justify-between mt-4 text-gray-700 dark:text-gray-300">
                            <span class="text-lg">Rp. 0</span>
                            <span class="text-lg">Rp. 10.000.000</span>
                        </div>
                    </div>
                </div>

                <div class="w-full px-3 lg:w-3/4">
                    <div class="px-3 mb-4">
                        <div
                            class="items-center justify-between hidden px-3 py-2 bg-gray-100 md:flex dark:bg-gray-900 ">
                            <div class="flex items-center justify-between">
                                <select name="" id="" wire:model.live="sort"
                                    class="block w-40 text-base bg-gray-100 cursor-pointer dark:text-gray-400 dark:bg-gray-900">
                                    <option value="latest">Sort by latest</option>
                                    <option value="price">Sort by Price</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Grid Container -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 py-10">

                        @foreach ($products as $product)
                            <!-- Product Card -->
                            <div
                                class="bg-white border border-gray-200 shadow-md rounded-lg overflow-hidden dark:bg-gray-900 dark:border-gray-700">
                                <!-- Product Image -->
                                <div class="relative overflow-hidden h-60">
                                    <a href="/products/{{ $product->slug }}">
                                        <img src="{{ url('storage', $product->images[0]) }}"
                                            alt="{{ $product->name }}"
                                            class="object-cover w-full h-full transition-transform duration-500 ease-in-out hover:scale-110">
                                    </a>
                                    <!-- Sale, Hot, or New Tags -->
                                    @if ($product->is_new)
                                        <span
                                            class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">New</span>
                                    @elseif ($product->is_hot)
                                        <span
                                            class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Hot</span>
                                    @elseif ($product->discount_percentage)
                                        <span
                                            class="absolute top-2 left-2 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">
                                            -{{ $product->discount_percentage }}%
                                        </span>
                                    @endif
                                </div>
                                <!-- Product Details -->
                                <div class="p-4">
                                    <!-- Product Title -->
                                    <h4
                                        class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 line-clamp-2">
                                        {{ Str::words($product->name, 5, '...') }}
                                    </h4>
                                    <!-- Price and Old Price (if discounted) -->
                                    <div class="flex items-center space-x-2 mb-4">
                                        <span
                                            class="text-xl font-bold text-green-600 dark:text-blue-300">{{ Number::currency($product->price, 'IDR') }}</span>
                                        @if ($product->old_price)
                                            <span
                                                class="text-sm line-through text-gray-500 dark:text-gray-400">{{ Number::currency($product->old_price, 'IDR') }}</span>
                                        @endif
                                    </div>
                                    <!-- Rating and Sold Count -->
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="flex items-center space-x-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($product->average_rating))
                                                    <!-- Full star -->
                                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @elseif ($i == ceil($product->average_rating) && $product->average_rating - floor($product->average_rating) >= 0.5)
                                                    <!-- Half star -->
                                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        <path fill-rule="evenodd"
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <!-- Empty star -->
                                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endif
                                            @endfor
                                            <span
                                                class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ number_format($product->average_rating, 1) }}/5</span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Terjual:
                                            {{ $product->sold_count }} pcs</span>
                                    </div>


                                    <!-- Add to Cart Button -->
                                    <div class="flex justify-center">
                                        <button wire:click.prevent='addToCart({{ $product->id }})'
                                            class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md font-medium shadow transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="w-4 h-4 inline mr-1 bi bi-cart3"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5.5 0 0 1 0 1H4a.5.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                            </svg>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                    {{ $products->links('pagination::tailwind') }}
                </div>

            </div>
        </div>
    </section>

</div>
