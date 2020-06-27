<?php

namespace App\Http\Controllers;

use App\Payment;
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

    public function getPDF(Payment $payment)
    {
        return $payment->pdf();
    }
}
