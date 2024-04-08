<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::withCount('transaction')->orderBy('transaction_count', 'desc')->paginate();
        
        return response()->json([
            'success' => true,
            'message' => 'Data listing...',
            'data' => $listings
        ]);
    }

    public function show(Listing $listing)
    {
        return response()->json([
            'success' => true,
            'message' => 'Menampilkan data listing yang terpilih...',
            'data' => $listing
        ]);
    }
}
