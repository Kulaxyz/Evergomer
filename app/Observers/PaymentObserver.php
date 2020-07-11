<?php

namespace App\Observers;

use App\Payment;
use Carbon\Carbon;

class PaymentObserver
{
    /**
     * Handle the payment "created" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function created(Payment $payment)
    {
        $start = $payment->created_at->startOfMinute();
        $end = $payment->created_at->endOfMinute();
        $payments = Payment::where('id', '!=', $payment->id)
            ->orderBy('id', 'DESC')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $data = $payment->created_at->format('YmdHi');
        $len = strlen($data);
        if (!$payments->isEmpty()) {
            $last = $payments->first();
            $id = (int) substr($last->number, $len);
            $payment->number = $data.($id+1);
        } else {
            $payment->number = $data. 1;
        }
        $payment->save();
    }

    /**
     * Handle the payment "updated" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function updated(Payment $payment)
    {
        //
    }

    /**
     * Handle the payment "deleted" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function deleted(Payment $payment)
    {
        //
    }

    /**
     * Handle the payment "restored" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function restored(Payment $payment)
    {
        //
    }

    /**
     * Handle the payment "force deleted" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function forceDeleted(Payment $payment)
    {
        //
    }
}
