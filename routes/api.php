<?php

use App\Http\Controllers\Api\TransactionController;

Route::get('/recent-transactions', [TransactionController::class, 'recentTransactions']);