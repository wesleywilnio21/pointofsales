<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Supplier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <form method="POST" action="{{ route('suppliers.store') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Supplier Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4">
                            <a href="{{ route('suppliers.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                                Save Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
