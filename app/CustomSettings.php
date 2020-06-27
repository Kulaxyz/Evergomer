<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomSettings extends Model
{
    protected $fillable = ['key', 'value', 'type'];
    public $timestamps = false;

    public static function get($key)
    {
        $setting = self::where('key', $key)->first();
        if ($setting && $setting->value) {
            return $setting->value;
        }
        return null;
    }

    public static function set($key, $val, $type = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) {
            $setting = self::create([
                'key' => $key,
                'value' => $val,
                'type' => $type,
            ]);
        } else {
            $setting->value = $val;
            $setting->save();
        }

        return $setting->val;
    }

}
