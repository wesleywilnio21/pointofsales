<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cash_received' => 'required|numeric',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01', // Allow decimal quantities!
            'items.*.price' => 'required|numeric',
            'items.*.subtotal' => 'required|numeric',
            'items.*.sale_type' => 'nullable|string|in:retail,bulk'
        ]);

        try {
            DB::beginTransaction();

            $totalPrice = array_sum(array_column($validated['items'], 'subtotal'));
            $cashReceived = $validated['cash_received'];

            if ($cashReceived < $totalPrice) {
                return redirect()->back()->withErrors('Cash received is less than total price.');
            }

            $cashReturn = $cashReceived - $totalPrice;

            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'invoice_number' => 'INV-' . time(),
                'total_price' => $totalPrice,
                'cash_received' => $cashReceived,
                'cash_return' => $cashReturn
            ]);

            foreach ($validated['items'] as $item) {
                // Fetch product first to figure out conversion factor
                $product = Product::findOrFail($item['product_id']);
                
                $quantityToDeduct = $item['quantity'];
                
                if (isset($item['sale_type']) && $item['sale_type'] === 'bulk' && $product->conversion_factor) {
                    $quantityToDeduct = $item['quantity'] * $product->conversion_factor;
                }

                // Extra safety check against base units
                if ($product->stock < $quantityToDeduct) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }

                // 1. Create Transaction Detail
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    // You could add a 'unit' column here if you need to show it on receipts
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal']
                ]);

                // 2. Decrease Product Stock
                $product->stock -= $quantityToDeduct;
                $product->save();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Transaction completed successfully! Invoice: ' . $transaction->invoice_number);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Transaction failed: ' . $e->getMessage());
        }
    }
}
