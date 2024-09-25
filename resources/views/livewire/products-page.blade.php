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
                    <div class="flex flex-wrap items-center ">

                        @foreach ($products as $product)
                            <!-- Each product card -->
                            <div class="w-full px-3 mb-6 sm:w-1/2 md:w-1/3" wire:key="{{ $product->id }}">
                                <div
                                    class="flex flex-col bg-white shadow-sm border border-slate-200 rounded-lg h-full dark:bg-slate-900 dark:border-gray-700 min-h-[500px]">
                                    <!-- Product Image: Gambar dengan ukuran seragam dan sudut rounded pada bagian atas -->
                                    <div class="relative h-64 w-full overflow-hidden rounded-t-lg">
                                        <a href="/products/{{ $product->slug }}" class="block h-full w-full">
                                            <img class="w-full h-full object-cover"
                                                src="{{ url('storage', $product->images[0]) }}"
                                                alt="{{ $product->name }}" />
                                        </a>
                                    </div>

                                    <!-- Product Details: Menggunakan flex-grow agar tetap diisi secara proporsional -->
                                    <div class="flex flex-col justify-between flex-grow p-6 text-left">
                                        <!-- Judul Produk dengan pembatasan panjang dan tinggi yang konsisten -->
                                        <div class=""> <!-- Mengurangi jarak margin-bottom -->
                                            <h4
                                                class="text-xl font-semibold text-slate-800 dark:text-slate-200 capitalize line-clamp-2 h-12 mb-1">
                                                <!-- Mengurangi margin-bottom -->
                                                {{ Str::words($product->name, 5, '...') }}
                                            </h4>

                                            <p class="text-lg text-green-600 font-semibold dark:text-blue-300 mb-2">
                                                <!-- Mengurangi margin-bottom -->
                                                {{ Number::currency($product->price, 'IDR') }}
                                            </p>
                                        </div>

                                        <!-- Rating dan bintang di kanan -->
                                        <div class="flex items-center justify-between mb-2">
                                            <!-- Mengurangi margin-bottom -->
                                            <div class="text-left">
                                                <p
                                                    class="text-sm font-medium text-slate-500 uppercase dark:text-slate-400">
                                                    Rating: 4.5/5
                                                </p>
                                            </div>
                                            <div class="text-yellow-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                    class="w-5 h-5 bi bi-star" viewBox="0 0 16 16">
                                                    <path
                                                        d="M3.612 15.443c-.396.198-.847-.149-.746-.592l.83-4.73L.173 6.765c-.329-.32-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.063.612.63.283.95l-3.523 3.356.83 4.73c.101.443-.35.79-.746.592L8 13.187l-4.389 2.256" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Add to Cart Button -->
                                        <div class="flex justify-center pt-2"> <!-- Mengurangi padding-top -->
                                            <button wire:click.prevent='addToCart({{ $product->id }})'
                                                class="min-w-32 rounded-md bg-slate-800 py-2 px-6 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none dark:bg-slate-700 dark:hover:bg-slate-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="w-4 h-4 bi bi-cart3 inline mr-2"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5.5 0 0 1 0 1H4a.5.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                </svg>
                                                <span wire:loading.remove
                                                    wire:target='addToCart({{ $product->id }})'>Add to Cart</span>
                                                <span wire:loading
                                                    wire:target='addToCart({{ $product->id }})'>Adding...</span>
                                            </button>
                                        </div>
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
