<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Store;
use App\Models\Listing;
use App\Models\Transaction;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function _fullyBookedChecked(Store $request)
    {
        $listing = Listing::findOrFail($request->listing_id);
        $runningTransactionCount = Transaction::where('listing_id', $listing->id)
        ->where('status', '!=', 'canceled')
        ->where(function ($query) use ($request) {
            // Start date is between requested dates
            $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                // Or End date is between requested dates
                ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                // Or requested dates are between existing booking dates
                ->orWhere(function ($subquery) use ($request) {
                    $subquery->where('start_date', '<', $request->start_date)
                             ->where('end_date', '>', $request->end_date);
                });
        })->count();

        if($runningTransactionCount >= $listing->max_person){
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Kamar penuh gaess',
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            );
        }

        return true;
    }

    public function isAvaiable(Store $request)
    {
        $this->_fullyBookedChecked($request);

        return response()->json([
            'success' => true,
            'message' => 'Kamar masih ada'
        ]);
    }

    public function store(Store $request)
    {
        $this->_fullyBookedChecked($request);

        $transaction = Transaction::create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'listing_id' => $request->listing_id,
            'user_id' => auth()->id(),
        ]);

        $transaction->Lising;

        return response()->json([
            'success' => true,
            'message' => 'Transaksi baru',
            'data' => $transaction
        ]);
    }

    public function index(){
        $transactions = Transaction::with('listing')->whereUserId(auth()->id())->paginate();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil dapat semua transaksi',
            'data' => $transactions,
        ]);
    }

    public function show(Transaction $transaction)
    {
        if($transaction->user_id !== auth()->id()){
            return response()->json([
                'success' => false,
                'message' => 'Tidak diizinkan',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $transaction->Listing;

        return response()->json([
            'success' => true,
            'message' => 'Get Detail transaction',
            'data' => $transaction
        ]);
    }
}
