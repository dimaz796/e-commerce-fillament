<div class="font-sans bg-white dark:bg-gray-700">
    <div class="p-4 lg:max-w-7xl max-w-4xl mx-auto">
        <div
            class="grid items-start grid-cols-1 lg:grid-cols-5 gap-12 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] p-6 rounded-lg dark:bg-gray-800">
            <!-- Bagian Gambar Produk -->
            <div class="lg:col-span-3 w-full lg:sticky top-0 text-center">
                <div class="px-4 py-10 rounded-lg shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] relative dark:bg-gray-700">
                    <!-- Gambar produk utama -->
                    <img src="{{ url('storage', $selected_variant_image) }}" alt="{{ $product->name }}"
                        class="w-3/4 rounded object-cover mx-auto" />
                    <button type="button" class="absolute top-4 right-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" fill="#ccc"
                            class="mr-1 hover:fill-[#333] dark:fill-white dark:hover:fill-gray-400" viewBox="0 0 64 64">
                            <path
                                d="M45.5 4A18.53 18.53 0 0 0 32 9.86 18.5 18.5 0 0 0 0 22.5C0 40.92 29.71 59 31 59.71a2 2 0 0 0 2.06 0C34.29 59 64 40.92 64 22.5A18.52 18.52 0 0 0 45.5 4ZM32 55.64C26.83 52.34 4 36.92 4 22.5a14.5 14.5 0 0 1 26.36-8.33 2 2 0 0 0 3.27 0A14.5 14.5 0 0 1 60 22.5c0 14.41-22.83 29.83-28 33.14Z">
                            </path>
                        </svg>
                    </button>
                </div>

            </div>

            <!-- Bagian Deskripsi Produk -->
            <div class="lg:col-span-2 dark:text-gray-200">
                <h2 class="text-2xl font-extrabold text-gray-800 dark:text-gray-100">{{ $product->name }}</h2>

                <!-- Rating dan Review -->
                <div class="flex space-x-2 mt-4">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="w-5 fill-blue-600 dark:fill-blue-400" viewBox="0 0 14 13" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7 0L9.4687 3.60213L13.6574 4.83688L10.9944 8.29787L11.1145 12.6631L7 11.2L2.8855 12.6631L3.00556 8.29787L0.342604 4.83688L4.5313 3.60213L7 0Z">
                            </path>
                        </svg>
                    @endfor
                    <h4 class="text-gray-800 text-base dark:text-gray-400">500 Reviews</h4>
                </div>

                <!-- Harga Produk -->
                <div class="flex flex-wrap gap-4 mt-8">
                    <p class="text-gray-800 text-3xl font-bold dark:text-gray-100">
                        @if ($selected_variant_price)
                            {{ Number::currency($selected_variant_price, 'IDR') }}
                        @else
                            {{ Number::currency($product->price, 'IDR') }}
                        @endif
                    </p>

                </div>

                <!-- Stock -->
                <div class="mt-2">
                    <p class="text-gray-700 dark:text-gray-400">Stock :
                        @if ($selected_variant_stock)
                            {{ $selected_variant_stock }}
                        @else
                            {{ $product->stock }}
                        @endif
                    </p>

                </div>

                <!-- Pilih Varian -->
                <div class="mt-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Pilih Varian:</h3>
                    <div class="flex flex-wrap gap-4 mt-4">
                        @foreach ($productVariants as $variant)
                            <button
                                wire:click="selectVariant({{ $variant->id }}, '{{ $variant->price }}', '{{ url('storage', $variant->image) }}', {{ $variant->stock }})"
                                class="px-4 py-2 rounded-md border-2 transition-all 
                                {{ $selected_variant_id == $variant->id ? 'border-green-500 text-green-700 bg-green-100 dark:bg-green-900' : 'border-gray-300 text-gray-500 dark:border-gray-600' }}
                                hover:border-green-500 hover:text-green-700">
                                {{ $variant->variant_value }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Bagian Quantity -->
                <div class="w-32 mt-8">
                    <label
                        class="w-full pb-1 text-xl font-semibold text-gray-900 border-b border-blue-300 dark:border-blue-500 dark:text-gray-100">
                        Quantity
                    </label>
                    <div class="relative flex flex-row w-full h-10 mt-6 bg-transparent rounded-lg">
                        <button wire:click="decreaseQty"
                            class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer dark:text-gray-200 dark:bg-gray-900 dark:hover:bg-gray-600 hover:text-gray-700 hover:bg-gray-400"
                            @if ($quantity <= 1) disabled @endif>
                            <span class="m-auto text-2xl font-thin">-</span>
                        </button>
                        <input type="number" readonly wire:model.defer="quantity"
                            class="flex items-center w-full font-semibold text-center text-gray-700 placeholder-gray-700 bg-gray-300 outline-none dark:text-gray-200 dark:placeholder-gray-400 dark:bg-gray-900 focus:outline-none text-md">
                        <button wire:click="increaseQty"
                            class="w-20 h-full text-gray-600 bg-gray-300 rounded-r outline-none cursor-pointer dark:text-gray-200 dark:bg-gray-900 dark:hover:bg-gray-600 hover:text-gray-700 hover:bg-gray-400"
                            @if ($quantity >= $variant->stock) disabled @endif>
                            <span class="m-auto text-2xl font-thin">+</span>
                        </button>

                    </div>
                </div>

                <!-- Tombol Add to Cart -->
                <div class="flex flex-wrap gap-4 mt-8">
                    <button wire:click="addToCart({{ $product->id }})"
                        class="min-w-[200px] px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded dark:bg-blue-700 dark:hover:bg-blue-600"
                        {{-- @if (!$selected_variant_id) disabled @endif> --}} <span wire:loading.remove
                        wire:target='addToCart({{ $product->id }})'>Add to cart</span>
                        <span wire:loading wire:target='addToCart({{ $product->id }})'>Adding...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Informasi Produk -->
        <div class="mt-16 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] p-6 dark:bg-gray-800 dark:text-gray-200">
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Product Information</h3>
            <ul class="mt-4 space-y-4 text-gray-800 dark:text-gray-300">
                <li class="flex justify-between items-center border-b pb-2 dark:border-gray-700">
                    <span class="font-medium text-gray-600 dark:text-gray-400">Brand</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $product->brand->name }}</span>
                </li>
                <li class="flex justify-between items-center border-b pb-2 dark:border-gray-700">
                    <span class="font-medium text-gray-600 dark:text-gray-400">Category</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $product->category->name }}</span>
                </li>
                <li class="flex justify-between items-start border-b pb-2 dark:border-gray-700">
                    <span class="font-medium text-gray-600 dark:text-gray-400">Description</span>
                    <span class="text-gray-900 dark:text-gray-100 max-w-md">{{ $product->description }}</span>
                </li>
            </ul>
        </div>

        <!-- Ulasan Produk -->
        <div class="mt-16 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] p-6 dark:bg-gray-800 dark:text-gray-200">
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Reviews ({{ $reviews->count() }})</h3>
            <div class="grid md:grid-cols-2 gap-12 mt-4">
                @foreach ($reviews as $review)
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <img src="{{ $review->user->profile_image }}"
                                class="w-12 h-12 rounded-full border-2 border-white dark:border-gray-700" />
                            <div class="ml-3">
                                <h4 class="text-sm font-bold text-gray-800 dark:text-gray-100">
                                    {{ $review->user->name }}</h4>
                                <div class="flex space-x-1 mt-1">
                                    @for ($i = 0; $i < 5; $i++)
                                        <svg class="w-4 fill-blue-600 dark:fill-blue-400" viewBox="0 0 14 13"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7 0L9.4687 3.60213L13.6574 4.83688L10.9944 8.29787L11.1145 12.6631L7 11.2L2.8855 12.6631L3.00556 8.29787L0.342604 4.83688L4.5313 3.60213L7 0Z">
                                            </path>
                                        </svg>
                                    @endfor
                                    <p class="text-xs !ml-2 font-semibold text-gray-800 dark:text-gray-400">
                                        {{ $review->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="text-sm mt-4 text-gray-800 dark:text-gray-100">{{ $review->comment }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
