<section
    class="bg-gray-100 dark:bg-gray-800 flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
    <div class="max-w-4xl w-full bg-white dark:bg-gray-900 p-8 rounded-xl shadow-lg">
        <!-- Notification for success -->
        @if (session()->has('success'))
            <div class="bg-green-500 text-white py-2 px-4 rounded-lg text-center">
                {{ session('success') }}
            </div>
        @endif

        <h3
            class="block text-2xl font-semibold text-gray-800 sm:text-2xl lg:text-3xl lg:leading-tight mb-10 dark:text-white">
            My Account</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Profile Photo and Upload (left column) -->
            <div class="flex flex-col items-center justify-center">
                <div class="relative w-40 h-40 mb-4 group">
                    <label for="photo-upload" class="cursor-pointer relative">
                        <img src="{{ $photo ? $photo->temporaryUrl() : url('storage', $currentPhoto) }}"
                            alt="Profile Photo"
                            class="w-40 h-40 rounded-full border-2 border-white dark:border-gray-700 object-cover shadow-lg transition-transform duration-200 hover:scale-105">
                        <div
                            class="absolute bottom-0 left-0 right-0 h-1/3 flex items-center justify-center bg-black bg-opacity-50 rounded-b-full opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9a3 3 0 100-6 3 3 0 000 6zm0 2a5 5 0 00-5 5v3h10v-3a5 5 0 00-5-5zm5-9h3v3h-3z" />
                            </svg>
                        </div>
                    </label>
                    <input type="file" id="photo-upload" wire:model="photo" class="hidden">
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Click photo to update</p>
            </div>

            <!-- Name and Email Form (right column) -->
            <div class="space-y-4">
                <div>
                    <label class="block text-base font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" wire:model.defer="name"
                        class="block w-full text-sm text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800 border rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500" />

                    @error('name')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-base font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" wire:model.defer="email"
                        class="block w-full text-sm text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800 border rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500" />

                    @error('email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <button wire:click="updateAccount"
                    class="w-full py-2 bg-blue-500 hover:bg-blue-600 text-white text-base rounded-lg shadow-lg transition duration-300">
                    Save Account Info
                </button>
            </div>
        </div>
    </div>
</section>
