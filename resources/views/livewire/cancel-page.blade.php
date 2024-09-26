<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <section class="flex items-center font-poppins dark:bg-gray-800">
        <div
            class="justify-center flex-1 max-w-6xl px-4 py-4 mx-auto bg-white border rounded-md dark:border-gray-900 dark:bg-gray-900 md:py-10 md:px-10">
            <div class="text-center">
                <h1 class="mb-4 text-3xl font-semibold tracking-wide text-red-500 dark:text-gray-300">
                    Payment Failed! Order Cancelled!
                </h1>
                <p class="mb-6 text-lg text-gray-600 dark:text-gray-400">
                    {{ $errorMessage }}
                </p>
                <div class="flex justify-center space-x-4">
                    <button wire:click="tryAgain"
                        class="px-6 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Try Again
                    </button>
                    <a href="/products"
                        class="px-6 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
