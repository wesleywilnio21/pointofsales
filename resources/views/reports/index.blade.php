<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily Sales Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Total Sales -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col border-l-4 border-blue-500">
                    <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Sales Today</span>
                    <span class="text-3xl font-extrabold text-blue-600 mt-2">${{ number_format($totalSalesToday, 2) }}</span>
                </div>

                <!-- Total Profit -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col border-l-4 border-green-500">
                    <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Profit Today</span>
                    <span class="text-3xl font-extrabold text-green-600 mt-2">${{ number_format($totalProfitToday, 2) }}</span>
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
                                        <td class="py-3 px-6 text-right font-bold text-gray-800">${{ number_format($trx->total_price, 2) }}</td>
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
