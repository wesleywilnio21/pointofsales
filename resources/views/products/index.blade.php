<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-700">Inventory List</h3>
                        <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Add New Product
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <th class="py-3 px-6">Name</th>
                                    <th class="py-3 px-6">Category</th>
                                    <th class="py-3 px-6 text-right">Purchase Price</th>
                                    <th class="py-3 px-6 text-right">Selling Price</th>
                                    <th class="py-3 px-6 text-center">Stock</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 text-sm">
                                @forelse($products as $product)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-3 px-6 font-medium">{{ $product->name }}</td>
                                        <td class="py-3 px-6">{{ $product->category }}</td>
                                        <td class="py-3 px-6 text-right">${{ number_format($product->purchase_price, 2) }}</td>
                                        <td class="py-3 px-6 text-right font-bold text-blue-600">${{ number_format($product->sell_price, 2) }}</td>
                                        <td class="py-3 px-6 text-center">
                                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $product->stock <= $product->min_stock ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex item-center justify-center space-x-2">
                                                <a href="{{ route('products.edit', $product) }}" class="text-blue-500 hover:text-blue-700 underline text-sm">Edit</a>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 underline text-sm">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-gray-500">No products found. Add some!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
