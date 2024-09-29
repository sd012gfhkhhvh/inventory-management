<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function recentTransactions()
    {
        $recentTransactions = Transaction::with('item')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'item_name' => $transaction->item->name,
                    'type' => $transaction->type,
                    'quantity' => $transaction->quantity,
                    'date' => $transaction->created_at->format('M d, Y H:i'),
                ];
            });

        return response()->json($recentTransactions);
    }
}