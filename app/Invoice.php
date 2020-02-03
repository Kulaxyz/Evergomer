<?php

namespace App;

use App\Models\BackpackUser;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use CrudTrait;

    protected $fillable = [
        'user_rfid', 'device_serial', 'amount',
        'status', 'charge_duration', 'charge_power',
        'port_number', 'payment_method', 'paid_at',
    ];

    protected $dates = ['paid_at'];
    public $timestamps = true;

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_serial', 'serial_number');
    }

    public function user()
    {
        return $this->belongsTo(BackpackUser::class, 'user_rfid', 'rfid');
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

    public function payInvoice()
    {
        if (backpack_user()->rfid == $this->user_rfid) {
            return "<a href='".route('invoice.pay', $this->id)."'>Pay</a>";
        }
        return false;
    }

    public static function countAmount($device, $request)
    {
        $wasted = $request['charge_power'] * $device->hour_cost;
        $owner_charge = $wasted * ($device->owner_cost / 100);
        $service_charge = $wasted * ($device->service_cost / 100);

        return round($wasted + $owner_charge + $service_charge, 2);
    }
}
