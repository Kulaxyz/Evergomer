<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Msg extends Model
{
    public $timestamps = false;
    protected $fillable = ['auth_key', 'route', 'sender', 'country_code'];
}
