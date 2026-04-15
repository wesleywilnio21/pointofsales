<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stock / Restock Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Success!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Error!</p>
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Restock Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Add New Stock</h3>
                    <form action="{{ route('restock.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Product Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select Product</label>
                                <select name="product_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="" disabled selected>-- Choose Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }} (Cur: {{ $product->stock }} {{ $product->unit }} | Avg Cost: Rp {{ number_format($product->purchase_price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-xs text-gray-500">Avg Cost is used for Moving Average calculation.</span>
                            </div>

                            <!-- Restock Type / Mode -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Restock Type</label>
                                <select name="restock_type" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="retail" selected>Retail (Base Unit)</option>
                                    <option value="bulk">Bulk (Sack/Box etc.)</option>
                                </select>
                                <span class="text-xs text-gray-500">Auto-converts bulk to base units if bulk is set on product.</span>
                            </div>

                            <!-- Quantity Added -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Added</label>
                                <input type="number" step="0.01" name="quantity_added" required placeholder="e.g. 10 or 1.5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Purchase Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Current Purchase Price (Rp)</label>
                                <input type="number" name="purchase_price_at_time" required placeholder="Cost per unit/bulk" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Supplier (Optional) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier (Optional)</label>
                                <select name="supplier_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- No Supplier / Walk-in --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">
                                Restock Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Restock History Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Restock History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold text-left">
                                <tr>
                                    <th class="py-3 px-6 border-b">Date / Time</th>
                                    <th class="py-3 px-6 border-b">Product</th>
                                    <th class="py-3 px-6 border-b text-center">Quantity Added</th>
                                    <th class="py-3 px-6 border-b text-right">Purchase Price (Per Item)</th>
                                    <th class="py-3 px-6 border-b">Supplier</th>
                                    <th class="py-3 px-6 border-b">Admin</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700">
                                @forelse($stockEntries as $entry)
                                    <tr class="hover:bg-gray-50 border-b border-gray-100">
                                        <td class="py-3 px-6">{{ $entry->created_at->format('l, d M Y H:i') }}</td>
                                        <td class="py-3 px-6 font-medium text-gray-900">{{ $entry->product->name ?? 'Deleted Product' }}</td>
                                        <td class="py-3 px-6 text-center font-bold text-blue-600">
                                            +{{ rtrim(rtrim(number_format($entry->quantity_added, 2, ',', '.'), '0'), ',') }}
                                            {{ $entry->product->unit ?? '' }}
                                        </td>
                                        <td class="py-3 px-6 text-right font-bold text-gray-800">
                                            Rp {{ number_format($entry->purchase_price_at_time, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 px-6">{{ $entry->supplier->name ?? 'None' }}</td>
                                        <td class="py-3 px-6">{{ $entry->user->name ?? 'Unknown' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-500">No restock history available.</td>
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
