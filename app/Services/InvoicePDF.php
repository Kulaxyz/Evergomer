<?php
namespace App\Services;

use App\CustomSettings;
use App\Payment;
use Exception;
use LaravelDaily\Invoices\Invoice;

class InvoicePDF extends Invoice
{
    private $payment;

    /**
     * @param float $amount
     * @return $this
     * @throws Exception
     */
    public function taxRate($amount)
    {
        if (CustomSettings::get('payment_pdf_tax')) {
            $this->totalTaxes($amount, true);
        }

        return $this;
    }

    public function getPaymentId()
    {
        if ($this->payment->custom_id) {
            return $this->payment->custom_id;
        }
        return $this->payment->id;
    }

    public function payment(Payment $payment)
    {
        $this->payment = $payment;

        return $this;
    }

    public function getSerialNumber()
    {
        $date = $this->payment->created_at->format('YmdHi');

        return CustomSettings::get('payment_pdf_prefix').'-'.$date.$this->getPaymentId();
    }

    public function getPaymentRefference()
    {
        return $this->payment->reference;
    }

    public function getPaymentStatus()
    {
        return $this->payment->status == Payment::STATUS_DUE ? 'Due' : 'Paid';
    }

    public function getPaymentType()
    {
        return $this->payment->payment_method;
    }

    public function getPaymentBy()
    {
        return $this->payment->invoice_by();
    }
}
