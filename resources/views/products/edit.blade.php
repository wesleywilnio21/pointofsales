<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product: ') }} <span class="text-blue-600">{{ $product->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    
                    <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Product Name</label>
                                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <input type="text" name="category" value="{{ old('category', $product->category) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Unit</label>
                                <select name="unit" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="pcs" {{ old('unit', $product->unit) == 'pcs' ? 'selected' : '' }}>pcs</option>
                                    <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>kg</option>
                                    <option value="liter" {{ old('unit', $product->unit) == 'liter' ? 'selected' : '' }}>liter</option>
                                </select>
                                @error('unit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Purchase Price</label>
                                <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('purchase_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Selling Price</label>
                                <input type="number" step="0.01" name="sell_price" value="{{ old('sell_price', $product->sell_price) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('sell_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Minimum Stock Alert</label>
                                <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('min_stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="mt-6 border-t pt-4">
                            <h3 class="text-md font-bold text-gray-800 mb-4">Bulk Configuration (Optional)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Bulk Unit Name</label>
                                    <input type="text" name="bulk_unit" value="{{ old('bulk_unit', $product->bulk_unit) }}" placeholder="e.g. Sack or Box" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Conversion Factor</label>
                                    <input type="number" step="0.01" name="conversion_factor" value="{{ old('conversion_factor', $product->conversion_factor) }}" placeholder="e.g. 25 (if 1 Sack = 25 kg)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Bulk Selling Price</label>
                                    <input type="number" step="0.01" name="bulk_price" value="{{ old('bulk_price', $product->bulk_price) }}" placeholder="e.g. 700000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4">
                            <a href="{{ route('products.index') }}" class="text-gray-600 hover:underline">Cancel</a>
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow hover:bg-blue-700 transition">Update Product</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
