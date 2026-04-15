<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockEntry;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockEntryController extends Controller
{
    public function index()
    {
        // For Restock View and History
        $products = Product::all();
        $suppliers = Supplier::orderBy('name')->get();
        $stockEntries = StockEntry::with(['product', 'user', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('stock_entries.index', compact('products', 'suppliers', 'stockEntries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_added' => 'required|numeric|min:0.01',
            'restock_type' => 'required|in:retail,bulk', 
            'purchase_price_at_time' => 'required|numeric|min:0', // Note: this is the price for the chosen type
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::findOrFail($validated['product_id']);
            
            $inputQty = $validated['quantity_added'];
            $inputPrice = $validated['purchase_price_at_time'];
            
            $baseAddedQty = $inputQty;
            $baseCostPerUnit = $inputPrice;

            // Convert bulk inputs to base metrics
            if ($validated['restock_type'] === 'bulk' && $product->conversion_factor) {
                $baseAddedQty = $inputQty * $product->conversion_factor;
                $baseCostPerUnit = $inputPrice / $product->conversion_factor;
            }

            // 1. Create Stock Entry (Store exactly what and how they bought it, cost is cost-per-base-unit)
            StockEntry::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'quantity_added' => $baseAddedQty, // Always log in base units logically
                'purchase_price_at_time' => $baseCostPerUnit,
                'supplier_id' => $validated['supplier_id'],
            ]);

            // 2. Moving Average Calculation
            $currentStock = max($product->stock, 0); // Don't average negatively
            $newTotalStock = $currentStock + $baseAddedQty;
            
            // ((current_stock * current_purchase_price) + (added_quantity * new_purchase_price)) / (current_stock + added_quantity)
            $newAveragePrice = (($currentStock * $product->purchase_price) + ($baseAddedQty * $baseCostPerUnit)) / $newTotalStock;

            // 3. Update Product Stock and Price
            $product->stock = $newTotalStock;
            $product->purchase_price = $newAveragePrice;
            $product->save();
        });

        return redirect()->back()->with('success', 'Stock added successfully. Average price updated.');
    }
}
