<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        // Serve POS UI with all products
        $products = Product::all();
        return view('pos', compact('products'));
    }
}
