<?php

namespace App\Models;

use App\Device;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait; // <------------------------------- this one

class BillingCategory extends Model
{
    use CrudTrait;

    protected $fillable = ['name', 'description'];
    public $timestamps = false;

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

}
