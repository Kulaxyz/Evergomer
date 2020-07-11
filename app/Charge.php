<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $fillable = ['user_id', 'device_id', 'invoice_id', 'status', 'type', 'port', 'finished_at', 'charging_time', 'power', 'custom_id'];
    protected $dates = ['finished_at'];

    public const CHARGE_STATUS_CHARGING = 1;
    public const CHARGE_STATUS_PREPARING = 2;
    public const CHARGE_STATUS_COMPLETED = 3;
    public const CHARGE_STATUS_ABORTED = 4;
    public const CHARGE_STATUS_DECLINED = 5;

    public const CHARGE_TYPE_AUTO = 1;
    public const CHARGE_TYPE_HOURS = 2;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function setCustomId() : void
    {
        $date = $this->finished_at->format('YmdHi');
        $this->custom_id = $date.$this->id;
    }
}
