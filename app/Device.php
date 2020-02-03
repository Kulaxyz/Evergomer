<?php

namespace App;

use App\Models\BackpackUser;
use App\Models\BillingCategory;
use App\Models\ConnectionMethod;
use App\Models\InstalledPlace;
use App\Models\PurchaseType;
use App\Traits\SavesImages;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Device extends Model
{
    use CrudTrait;
    use SavesImages;

    protected $table = 'devices';

    public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = ['longitude', 'latitude', 'hour_cost', 'service_cost', 'owner_cost', 'owner_id'];
    protected $casts = [
        'charging_time' => 'array',
    ];
    protected $dates = [
        'installed_at',
        'activated_at',
        'expires_at',
    ];

    public function owner()
    {
        return $this->belongsTo(BackpackUser::class);
    }

    public function connectionMethod()
    {
        return $this->belongsTo(ConnectionMethod::class);
    }
    public function installedPlace()
    {
        return $this->belongsTo(InstalledPlace::class);
    }
    public function purchaseType()
    {
        return $this->belongsTo(PurchaseType::class);
    }
    public function billingCategory()
    {
        return $this->belongsTo(BillingCategory::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'device_serial', 'serial_number');
    }

    public function openGoogleMaps()
    {
        return '<a href="">Map</a>';
    }

    public function tariffPrice()
    {
        return $this->hour_cost.'$ + Owner '.$this->owner_cost . '% + Service '.$this->service_cost.'%';
    }

    public function chargingTimes()
    {
        $result = '';
        $i = 1;
        foreach ($this->charging_time as $time) {
            $result .= 'Charging time #'.$i.': '.$time.'hours <br>';
            $i++;
        }
        return $result;
    }

    public function ownerInfo()
    {
        return view('vendor.backpack.additional.details_row.device_owner', ['entry' => $this]);
    }

    public function ownerLink()
    {
        $name = $this->owner->name;
        $link = '/cabinet/user/'.$this->owner->id.'/show';
        return "<a href=".$link.">$name</a>";
    }

    public function setChargingTimeAttribute($value)
    {
        $times = json_encode($value);
        $this->attributes['charging_time'] = $times;
    }

    public function setInstallationPhotoAttribute($value)
    {
        $attribute_name = "installation_photo";
        $destination_path = "public/images/devices"; // path relative to the disk above
        $this->attributes[$attribute_name] = $this->storeImage($value, $attribute_name, $destination_path);
    }
}
