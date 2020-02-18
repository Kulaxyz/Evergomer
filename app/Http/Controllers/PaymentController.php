<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function wallet_pay(Request $request)
    {
        //
        return response()->json([
            'success' => true,
            'Invoice was paid!',
        ], 200);
    }
}
