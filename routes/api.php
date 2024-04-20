<?php

use App\Http\Controllers\Api\ListingController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    // return $request->user();
    return response()->json([
        'success' => true,
        'message' => 'Detail login user..',
        'data' => $request->user()

    ]);
});

require __DIR__.'/auth.php';

Route::resource('listing', ListingController::class)->only(['index', 'show']);

Route::post('transaction/is-avaiable', [TransactionController::class, 'isAvaiable'])->middleware(['auth:sanctum']);
Route::resource('transaction', TransactionController::class)->only(['store', 'index', 'show'])->middleware(['auth:sanctum']);
