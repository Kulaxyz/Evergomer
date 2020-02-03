<?php

namespace App\Models;

use App\Device;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait; // <------------------------------- this one


class ConnectionMethod extends Model
{
    use CrudTrait;
    protected $table = 'connection_methods';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'description'];
    public $timestamps = false;

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
