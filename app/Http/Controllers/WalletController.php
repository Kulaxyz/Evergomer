<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Notifications\MoneyDeposited;
use App\Payment;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Input;
use Softon\Indipay\Facades\Indipay;

class WalletController extends Controller
{
    use SerializesModels;

    public function index()
    {
        if (backpack_user()->can('edit_settings')) {
            return redirect()->route('backpack.dashboard');
        }
        return view('vendor.backpack.base.wallet');
    }

    public function walletDeposit()
    {
        $amount = Input::get('amount');
        if ($amount <= 0) {
            session()->flash('error_message', 'Please, enter the amount');
            return redirect()->back();
        }

       return $this->sendPayment('wallet', $amount);
    }

    public function paymentStatus(Request $request)
    {
        $response = Indipay::response($request);

        if ($response['order_status'] == 'Success') {
            $payment = Payment::with('invoice', 'user')->find($response['order_id']);
            $payment->status = true;
            $payment->payment_method = 'CCAvenue Payment Gateway';
            $payment->paid_at = Carbon::now();
            $payment->save();

            if ($payment->type == 'wallet') {
                $user = $payment->user;
                $user->balance += $payment->amount;
                $user->save();
                $user->notify(new MoneyDeposited($payment));

            } elseif ($payment->type == 'invoice') {
                $this->paid_invoice($payment->invoice);
            }

            session()->flash('success_message', 'Successfully Paid!');
        } else {
            session()->flash('error_message', 'Something went wrong.');
        }

        return redirect(backpack_url('invoice'));
    }

    private function sendPayment($type, $amount, $invoice_id = null)
    {
        $payment = new Payment;
        $payment->type = $type;
        $payment->amount = $amount;
        $payment->user_id = backpack_user()->id;
        $payment->invoice_id = $invoice_id;

        $payment->save();

        $parameters = [
            'order_id' => $payment->id,
            'amount' => $amount,
        ];

        $order = Indipay::prepare($parameters);

        return Indipay::process($order);
    }

}

