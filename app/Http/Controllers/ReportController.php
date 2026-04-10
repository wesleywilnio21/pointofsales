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
            $profitPerItem = $detail->price - $purchasePrice;
            $totalProfitToday += ($profitPerItem * $detail->quantity);
        }

        // Transaction History (Today's history per standard retail tracking)
        $transactions = Transaction::with('user')
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reports.index', compact('totalSalesToday', 'totalProfitToday', 'transactions'));
    }
}
