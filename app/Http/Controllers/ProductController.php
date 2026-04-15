<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'bulk_unit' => 'nullable|string|max:50',
            'conversion_factor' => 'nullable|numeric|min:0.01',
            'bulk_price' => 'nullable|numeric|min:0'
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            // Update name and category as well to be a complete backend
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'min_stock' => 'required|numeric|min:0',
            'bulk_unit' => 'nullable|string|max:50',
            'conversion_factor' => 'nullable|numeric|min:0.01',
            'bulk_price' => 'nullable|numeric|min:0'
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
