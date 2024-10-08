<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Shopping Cart</h1>
        <div class="grid md:grid-cols-3 gap-8">

            <!-- Bagian Order Item yang Bisa di Scroll -->
            <div class="md:col-span-2 space-y-4">

                @forelse ($cart_items as $item)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md flex justify-between items-center">
                        <div class="flex items-start gap-4">
                            <!-- Gambar Produk -->
                            <div class="w-28 h-28 bg-gray-100 dark:bg-gray-800 p-2 rounded-md">
                                <img class="w-full h-full object-cover"
                                    src="{{ isset($item['productVariant']) ? url('storage', $item['productVariant']['image']) : 'default_image_path_here' }}"
                                    alt="{{ $item['product']['name'] }}" />

                            </div>
                            <!-- Detail Produk -->
                            <div class="flex flex-col">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                    {{ $item['product']['name'] }}</h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Type:
                                    {{ $item['productVariant']['variant_value'] }}</span>
                                <button
                                    wire:click="removeItem({{ $item['product_id'] }}, {{ $item['product_variant_id'] }})"
                                    class="mt-4 font-semibold text-red-500 text-sm flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M19 7a1 1 0 0 0-1 1v11.191A1.92 1.92 0 0 1 15.99 21H8.01A1.92 1.92 0 0 1 6 19.191V8a1 1 0 0 0-2 0v11.191A3.918 3.918 0 0 0 8.01 23h7.98A3.918 3.918 0 0 0 20 19.191V8a1 1 0 0 0-1-1Zm1-3h-4V2a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v2H4a1 1 0 0 0 0 2h16a1 1 0 0 0 0-2ZM10 4V3h4v1Z" />
                                    </svg>
                                    Remove
                                </button>

                            </div>
                        </div>
                        <!-- Harga dan Pengaturan Jumlah -->
                        <div class="ml-auto flex flex-col items-center">
                            <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                {{ Number::currency($item->productVariant->price, 'IDR') }}
                            </span>
                            <div class="mt-2 flex items-center">
                                <button wire:click="decreaseQty({{ $item['id'] }})"
                                    class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-md">-</button>
                                <span class="mx-3">{{ $item['quantity'] }}</span>
                                <button wire:click="increaseQty({{ $item['id'] }})"
                                    class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-md"
                                    @if ($item['quantity'] >= $item->productVariant->stock) disabled @endif>
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <h3 class="text-2xl font-semibold text-gray-500 dark:text-gray-400 mb-4">Your cart is empty!
                        </h3>
                        <a href="/products" class="bg-blue-500 dark:bg-gray-800 text-white px-4 py-2 rounded-md">Back to
                            Products</a>
                    </div>
                @endforelse
            </div>

            <!-- Bagian Summary -->
            <div class="bg-gray-100 dark:bg-gray-900 rounded-md p-6 shadow-md">
                <h3
                    class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-300 dark:border-gray-600 pb-2">
                    Order Summary
                </h3>
                <div class="flex justify-between mt-6 text-gray-700 dark:text-gray-300">
                    <span>Subtotal</span>
                    <span>{{ Number::currency($grand_total, 'IDR') }}</span>
                </div>
                <div class="flex justify-between mt-2 text-gray-700 dark:text-gray-300">
                    <span>Taxes</span>
                    <span>{{ Number::currency(0, 'IDR') }}</span>
                </div>
                <div class="flex justify-between mt-2 text-gray-700 dark:text-gray-300">
                    <span>Shipping</span>
                    <span>{{ Number::currency(0, 'IDR') }}</span>
                </div>
                <hr class="my-4 border-gray-300 dark:border-gray-600">
                <div class="flex justify-between font-bold text-gray-900 dark:text-white">
                    <span>Total</span>
                    <span>{{ Number::currency($grand_total, 'IDR') }}</span>
                </div>
                @if ($cart_items)
                    <a href="/checkout"
                        class="mt-6 block bg-blue-500 hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-800 text-center text-white py-2 rounded-md transition-colors duration-200">
                        Checkout
                    </a>
                @endif
            </div>

        </div>
    </div>
</div>
