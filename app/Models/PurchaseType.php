<?php

namespace App\Models;

use App\Device;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class PurchaseType extends Model
{
    use CrudTrait;

    protected $table = 'purchase_types';
    protected $fillable = ['name', 'description'];
    public $timestamps = false;

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
