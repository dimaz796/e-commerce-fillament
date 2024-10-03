<div class="min-h-screen bg-gray-50 py-6 flex flex-col justify-center sm:py-12">
    <div class="py-3 sm:max-w-xl sm:mx-auto">
        <div class="bg-white min-w-1xl flex flex-col rounded-xl shadow-lg">
            <!-- Product Image and Name -->
            <div class="text-center px-12 py-5">
                <img src="{{ url('storage', $product->images[0]) }}" alt="Product Image"
                    class="w-32 h-32 rounded-full mx-auto mb-4">
                <h1 class="text-3xl font-semibold text-gray-800">{{ $product->name }}</h1>
            </div>

            @if ($hasRated)
                <div class="px-12 py-5 text-center">
                    <span class="text-red-500">You have already rated this product.</span>
                </div>
            @else
                <div class="bg-gray-100 w-full flex flex-col items-center py-6">
                    <div class="flex flex-col items-center py-6 space-y-3">
                        <span class="text-lg text-gray-800">How would you rate this product?</span>
                        <div class="flex space-x-2">
                            <!-- Rating stars -->
                            @for ($i = 1; $i <= 5; $i++)
                                <svg wire:click="$set('rating', {{ $i }})"
                                    class="w-10 h-10 cursor-pointer {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                    </div>

                    <!-- Comment Box -->
                    <div class="w-3/4 flex flex-col">
                        <textarea wire:model="comment" rows="3"
                            class="p-4 text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200 focus:border-indigo-500 resize-none"
                            placeholder="Leave a message, if you want"></textarea>

                        <!-- Image Upload -->
                        <div class="mt-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Upload Image</label>
                            <input type="file" id="image" wire:model="image"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                    class="w-20 h-20 mt-2 rounded-md shadow-md">
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button wire:click="submitRating"
                            class="py-3 my-8 text-lg bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-lg text-white shadow-lg">Rate
                            now</button>
                    </div>
                </div>
            @endif

        </div>

    </div>
</div>
