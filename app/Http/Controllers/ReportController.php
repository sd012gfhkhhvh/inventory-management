<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        $totalItems = Item::count();
        $totalQuantity = Item::sum('quantity');
        $lowStockItems = Item::where('quantity', '<', 10)->count();
        $recentTransactions = Transaction::whereDate('created_at', '>=', now()->subDays(30))->count();

        return view('reports.index', compact('totalItems', 'totalQuantity', 'lowStockItems', 'recentTransactions'));
    }

    public function generatePdf()
    {
        $items = Item::all();
        $pdf = PDF::loadView('reports.pdf', compact('items'));
        return $pdf->download('inventory_report.pdf');
    }
}
