<?php

namespace App;

use App\Models\BackpackUser;
use App\Notifications\InvoicePaid;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use CrudTrait;

    protected $fillable = [
        'user_rfid', 'device_serial', 'amount',
        'status', 'charge_duration', 'charge_power',
        'port_number', 'payment_method', 'paid_at',
        'charge_id', 'auth_type', 'charge_hours',
        'finished_at', 'started_at',
    ];

    protected $dates = ['paid_at', 'finished_at', 'started_at'];
    public $timestamps = true;

    const STATUS_CHARGING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_ERROR = 3;


    public function device()
    {
        return $this->belongsTo(Device::class, 'device_serial', 'serial_number');
    }

    public function charge()
    {
        return $this->hasOne(Charge::class);
    }

    public function user()
    {
        return $this->belongsTo(BackpackUser::class, 'user_rfid', 'rfid');
    }

    public function owner()
    {
        return $this->device->owner;
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function deviceLink()
    {
        $name = $this->device->name;
        if (backpack_user()->can('view_devices') || backpack_user()->can('edit_devices')) {
            $link = '/cabinet/device/' . $this->device->id . '/show';
            return "<a href=" . $link . ">$name</a>";
        }
        return $name;
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

    public function charge_info()
    {
        if (!$this->charge) {
            return null;
        }

        return $this->charge->custom_id;
    }

    public function payInvoice()
    {
//        if (backpack_user()->rfid == $this->user_rfid) {
//            return view('vendor.backpack.crud.buttons.pay_wallet', ['id' => $this->id]);
//        }
        return false;
    }

    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case self::STATUS_CHARGING:
                return 'Charging';
            case self::STATUS_COMPLETED:
                return 'Completed';
            case self::STATUS_ERROR:
                return 'Aborted';
        }
        return null;
    }

    public function charge_hours_text()
    {
        if (!$this->charge_hours) {
            return 'Auto';
        }

        return $this->charge_hours;
    }

    public static function countAmount(Device $device, $request)
    {
        $wasted = $request['charge_power'] * $device->hour_cost;
        $owner_charge = $wasted * ($device->owner_cost / 100);
        $service_charge = $wasted * ($device->service_cost / 100);

        return round($wasted + $owner_charge + $service_charge, 2);
    }

    public function make_paid($from_wallet = true) : Invoice
    {
        if ($this->paid_at != null && $this->status) {
            abort(500, 'Invoice is already paid!');
        }
        $this->status = true;
        if (!$from_wallet) {
            $this->payment_method = 'CCAvenue Payment Gateway';
        } else {
            $this->payment_method = 'Wallet';
        }
        $this->paid_at = Carbon::now();
        $this->save();

        $device = $this->device;
        $charge = $device->owner_cost + $device->service_cost;

        $original_cost = 100*$this->amount / (100 + $charge);
        $owner_profit = $original_cost + $original_cost*($device->owner_cost / 100);
        $owner = $device->owner;
        $owner->balance += $owner_profit;

        $owner->save();
        $owner->notify(new InvoicePaid($this));

        return $this;
    }
}
