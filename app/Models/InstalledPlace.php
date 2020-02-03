<?php

namespace App\Models;

use App\Device;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;


class InstalledPlace extends Model
{
    use CrudTrait;
    protected $table = 'installed_places';
    protected $fillable = ['name', 'description'];
    public $timestamps = false;

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
