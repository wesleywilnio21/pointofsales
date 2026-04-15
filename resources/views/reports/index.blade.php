<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daily Summary Report') }}
            </h2>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Filter:</span>
                <select class="rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 font-bold text-gray-700 pointer-events-none">
                    <option value="today" selected>Today's Sales</option>
                </select>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Sales -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col border-l-4 border-blue-500">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Expected Cash in Drawer (Total Sales)</span>
                    <span class="text-2xl font-extrabold text-blue-600 mt-2">Rp {{ number_format($totalSalesToday, 0, ',', '.') }}</span>
                </div>

                <!-- Total Profit -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col border-l-4 border-green-500">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Net Profit Today</span>
                    <span class="text-2xl font-extrabold text-green-600 mt-2">Rp {{ number_format($totalProfitToday, 0, ',', '.') }}</span>
                </div>

                <!-- Total Expense Today -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col border-l-4 border-red-500">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Restock Expense Today</span>
                    <span class="text-2xl font-extrabold text-red-600 mt-2">Rp {{ number_format($expenseToday, 0, ',', '.') }}</span>
                    <span class="text-xs text-gray-400 mt-1">This Month: Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}</span>
                </div>

                <!-- Net Cash Flow -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col border-l-4 {{ $netCashFlowToday >= 0 ? 'border-purple-500' : 'border-red-500' }}">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Net Cash Flow Today</span>
                    <span class="text-2xl font-extrabold {{ $netCashFlowToday >= 0 ? 'text-purple-600' : 'text-red-600' }} mt-2">
                        {{ $netCashFlowToday < 0 ? '-' : '' }}Rp {{ number_format(abs($netCashFlowToday), 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-gray-400 mt-1">(Total Sales - Restock Expense)</span>
                </div>
            </div>

            <!-- Items Sold Summary Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Items Sold Summary</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold text-left">
                                <tr>
                                    <th class="py-3 px-6 border-b">Product Name</th>
                                    <th class="py-3 px-6 border-b text-center">Total Quantity Sold Today</th>
                                    <th class="py-3 px-6 border-b text-right">Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700">
                                @forelse($itemsSoldSummary as $item)
                                    <tr class="hover:bg-gray-50 border-b border-gray-100">
                                        <td class="py-3 px-6 font-medium text-gray-900">{{ $item['name'] }}</td>
                                        <td class="py-3 px-6 text-center font-bold text-blue-600">{{ rtrim(rtrim(number_format($item['quantity'], 2, ',', '.'), '0'), ',') }} {{ $item['unit'] }}</td>
                                        <td class="py-3 px-6 text-right font-bold text-green-600">Rp {{ number_format($item['revenue'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-8 text-center text-gray-500">No items sold today yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Transactions Table Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Transaction History (Today)</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold text-left">
                                <tr>
                                    <th class="py-3 px-6 border-b">Invoice No</th>
                                    <th class="py-3 px-6 border-b">Time</th>
                                    <th class="py-3 px-6 border-b">Cashier Name</th>
                                    <th class="py-3 px-6 border-b text-right">Total Price</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700">
                                @forelse($transactions as $trx)
                                    <tr class="hover:bg-gray-50 border-b border-gray-100">
                                        <td class="py-3 px-6 font-medium text-gray-900">{{ $trx->invoice_number }}</td>
                                        <td class="py-3 px-6">{{ $trx->created_at->format('H:i A') }}</td>
                                        <td class="py-3 px-6">{{ $trx->user ? $trx->user->name : 'Unknown' }}</td>
                                        <td class="py-3 px-6 text-right font-bold text-gray-800">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-500">No transactions found for today.</td>
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
