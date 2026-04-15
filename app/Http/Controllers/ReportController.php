<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Total Sales Today
        $totalSalesToday = Transaction::whereDate('created_at', $today)->sum('total_price');

        // Total Profit Today
        $details = TransactionDetail::with('product')
            ->whereDate('created_at', $today)
            ->get();
            
        $totalProfitToday = 0;
        foreach ($details as $detail) {
            $purchasePrice = $detail->product ? $detail->product->purchase_price : 0;
            
            // Determine if the sale was likely bulk based on price paid
            $factor = 1;
            if ($detail->product && $detail->product->conversion_factor && $detail->product->bulk_price) {
                // We use float comparison approximation
                if (abs($detail->price - $detail->product->bulk_price) < 0.01) {
                    $factor = $detail->product->conversion_factor;
                }
            }
            
            $costForThisItem = $purchasePrice * $factor;
            $profitPerItem = $detail->price - $costForThisItem;
            
            $totalProfitToday += ($profitPerItem * $detail->quantity);
        }

        // Transaction History (Today's history per standard retail tracking)
        $transactions = Transaction::with('user')
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        // Items Sold Summary
        $itemsSoldSummary = [];
        foreach ($details as $detail) {
            $productId = $detail->product_id;
            
            $factor = 1;
            if ($detail->product && $detail->product->conversion_factor && $detail->product->bulk_price) {
                if (abs($detail->price - $detail->product->bulk_price) < 0.01) {
                    $factor = $detail->product->conversion_factor;
                }
            }

            if (!isset($itemsSoldSummary[$productId])) {
                $itemsSoldSummary[$productId] = [
                    'name' => $detail->product ? $detail->product->name : 'Unknown Product',
                    'unit' => $detail->product ? $detail->product->unit : '',
                    'quantity' => 0,
                    'revenue' => 0,
                ];
            }
            // Add base units to be consistent
            $itemsSoldSummary[$productId]['quantity'] += ($detail->quantity * $factor);
            $itemsSoldSummary[$productId]['revenue'] += $detail->subtotal; // Qty * Price
        }

        // Sort items sold by revenue descending
        usort($itemsSoldSummary, function($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });

        // Daily Expenses (Restock)
        $expenseToday = \App\Models\StockEntry::whereDate('created_at', $today)
            ->get()
            ->sum(function($entry) {
                return $entry->quantity_added * $entry->purchase_price_at_time;
            });
            
        // Monthly Expenses (Restock)
        $expenseThisMonth = \App\Models\StockEntry::whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->get()
            ->sum(function($entry) {
                return $entry->quantity_added * $entry->purchase_price_at_time;
            });

        // Net Cash Flow Today = Sales - Restock Purchase
        $netCashFlowToday = $totalSalesToday - $expenseToday;

        // Set Today filter request (just for view awareness, though not strictly needed here since we enforce today)
        $filter = request('filter', 'today'); // if we want to expand this later

        return view('reports.index', compact(
            'totalSalesToday', 
            'totalProfitToday', 
            'transactions', 
            'itemsSoldSummary', 
            'expenseToday', 
            'expenseThisMonth', 
            'netCashFlowToday', 
            'filter'
        ));
    }
}
