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
                                {{ $variant->stock < 1 ? 'disabled' : '' }}
                                class="px-4 py-2 rounded-md border-2 transition-all
                                {{ $selected_variant_id == $variant->id ? 'border-blue-500 text-gray-200 bg-blue-100 dark:bg-blue-900' : 'border-gray-300 text-gray-500 dark:border-gray-600' }}
                                {{ $variant->stock < 1 ? '' : 'hover:border-blue-500 hover:text-gray-200' }}">
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
            <!-- Header -->
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Reviews</h2>

            <!-- Average Rating and Total Reviews -->
            <div class="mt-2 flex items-center gap-2 sm:mt-0">
                <div class="flex items-center gap-0.5">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="h-4 w-4 {{ $i <= floor($averageRating) ? 'text-yellow-300' : 'text-gray-300' }}"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                        </svg>
                    @endfor
                </div>
                <p class="text-sm font-medium leading-none text-gray-500 dark:text-gray-400">
                    ({{ number_format($averageRating, 1) }})</p>
                <a href="#"
                    class="text-sm font-medium leading-none text-gray-900 underline hover:no-underline dark:text-white">{{ $ratingsCount }}
                    Reviews</a>
            </div>

            <!-- Distribution of Ratings -->
            <div class="my-6 gap-8 sm:flex sm:items-start md:my-8">
                <div class="shrink-0 space-y-4">
                    <p class="text-2xl font-semibold leading-none text-gray-900 dark:text-white">
                        {{ number_format($averageRating, 2) }} out of 5</p>
                    <button type="button" data-modal-target="review-modal" data-modal-toggle="review-modal"
                        class="mb-2 me-2 rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Write
                        a review</button>
                </div>

                <div class="mt-6 min-w-0 flex-1 space-y-3 sm:mt-0">
                    <!-- Distribution of Stars -->
                    @foreach ([
        '5' => $star_5_percentage,
        '4' => $star_4_percentage,
        '3' => $star_3_percentage,
        '2' => $star_2_percentage,
        '1' => $star_1_percentage,
    ] as $star => $percentage)
                        <div class="flex items-center gap-2">
                            <p
                                class="w-2 shrink-0 text-start text-sm font-medium leading-none text-gray-900 dark:text-white">
                                {{ $star }}</p>
                            <svg class="h-4 w-4 shrink-0 text-yellow-300" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                            </svg>
                            <div class="h-1.5 w-80 rounded-full bg-gray-200 dark:bg-gray-700">
                                <div class="h-1.5 rounded-full bg-yellow-300" style="width: {{ $percentage }}%">
                                </div>
                            </div>
                            <a href="#"
                                class="w-8 shrink-0 text-right text-sm font-medium leading-none text-primary-700 hover:underline dark:text-primary-500 sm:w-auto sm:text-left">{{ ${'star_' . $star . '_count'} }}
                                <span class="hidden sm:inline">reviews</span></a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-8">
                @foreach ($reviews as $review)
                    <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg p-6 flex flex-col space-y-4">
                        <!-- Row layout for image and info -->
                        <div class="flex items-start space-x-4">
                            <!-- User Image -->
                            <img src="{{ url('storage', $product->images[0]) }}" alt="{{ $review->user->name }}"
                                class="w-12 h-12 rounded-full border-2 border-gray-200 dark:border-gray-700">

                            <!-- User Info (Name and Rating) -->
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                    {{ $review->user->name }}</h4>
                                <!-- Rating Stars -->
                                <div class="flex items-center mt-1">
                                    @for ($i = 0; $i < 5; $i++)
                                        <svg class="h-4 w-4 {{ $i < $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <hr>

                        <!-- Review Comment -->
                        <p class="text-sm text-gray-600 dark:text-gray-200">{{ $review->comment }}</p>


                    </div>
                @endforeach
            </div>


        </div>


    </div>
</div>
