<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;

class Payment extends Model
{
    use CrudTrait;

    protected $fillable = [
        'type', 'amount', 'user_id',
        'invoice_id', 'paid_at',
        'payment_method', 'by_admin',
        'reference', 'custom_id',
        'status',
    ];
    protected $dates = ['paid_at'];
    public const STATUS_DUE = 0;
    public const STATUS_PAID = 1;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function userLink()
    {
        $name = $this->user->name;
        if (backpack_user()->can('view_users') || backpack_user()->can('edit_users')) {
            $link = '/cabinet/user/' . $this->user->id . '/show';
            return "<a href=" . $link . ">$name</a>";
        }
        return $name;
    }

    public function open_pdf()
    {
        return "<a href='".route('payment.pdf', $this->number)."'>PDF</a>";
    }

    public function pdf()
    {
        $client = new Party([
            'name'          => 'Energomer Server',
            'phone'         => '(520) 318-9486',
            'custom_fields' => [
                'note'        => 'IDDQD',
                'business id' => '365#GG',
            ],
        ]);

        $customer = new Party([
            'name'          => $this->user->name,
            'address'       => $this->user->email,
        ]);
        $payment = $this;
        $items = [
            (new InvoiceItem())->title('Wallet Refill')->pricePerUnit($this->amount),
        ];

        $notes = [];
        $status = $payment->status == self::STATUS_DUE ? 'Due' : 'Paid';
        $notes[] = 'Payment Status: '.$status;
        $notes[] = 'Invoice By: '.$this->invoice_by();

        if ($payment->reference) {
            $notes[] = 'Payment Reference: '.$payment->reference;
        }
        if ($payment->payment_method) {
            $notes[] = 'Payment Type: '.$payment->payment_method;
        }


        $notes = implode("<br>", $notes);

        $invoice = \App\Services\InvoicePDF::make()
            ->payment($payment)
            ->template('station_invoice')
            ->series('BIG')
            ->sequence(667)
            ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($client)
            ->buyer($customer)
            ->date(now()->subWeeks(3))
            ->dateFormat('m/d/Y')
            ->taxRate(\App\CustomSettings::get('payment_pdf_tax'))
            ->payUntilDays(14)
            ->currencySymbol('₹')
            ->currencyCode('INR')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename($client->name . ' ' . $customer->name)
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('vendor/invoices/sample-logo.png'));

        return $invoice->stream();
    }

    public function invoice_by()
    {
        return $this->by_admin ? 'Manually' : 'Auto';
    }
}
