<section class="bg-gray-900 dark:bg-gray-900 flex flex-col items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-gray-800 p-8 rounded-lg shadow-lg">
        <!-- Product Image and Name -->
        <div class="text-center">
            <img src="{{ url('storage', $product->images[0]) }}" alt="Product Image"
                class="w-24 h-24 rounded-full mx-auto mb-4 shadow-md">
            <h2 class="text-xl font-semibold text-gray-100">{{ $product->name }}</h2>
        </div>

        @if ($hasRated)
            <div class="text-center py-4">
                <span class="text-red-500">You have already rated this product.</span>
            </div>
        @else
            <div class="bg-gray-700 w-full rounded-lg py-6 px-4">
                <div class="text-center">
                    <span class="text-lg text-gray-300">How would you rate this product?</span>
                </div>

                <!-- Rating stars -->
                <div class="flex justify-center space-x-1 mt-3">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg wire:click="$set('rating', {{ $i }})"
                            class="w-8 h-8 cursor-pointer {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-400' }}"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>

                <!-- Comment Box -->
                <div class="mt-4">
                    <textarea wire:model="comment" rows="3"
                        class="w-full p-3 bg-gray-600 border border-gray-500 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"
                        placeholder="Leave a message, if you want"></textarea>
                </div>

                <!-- Image Upload -->
                <div class="mt-4">
                    <label for="image" class="block text-sm font-medium text-gray-400">Upload Image</label>
                    <input type="file" id="image" wire:model="image"
                        class="mt-1 block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                            class="w-20 h-20 mt-2 rounded-md shadow-md">
                    @endif
                </div>

                <!-- Submit Button -->
                <button wire:click="submitRating"
                    class="w-full mt-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    Rate now
                </button>
            </div>
        @endif
    </div>
</section>
