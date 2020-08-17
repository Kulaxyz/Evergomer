<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FrontendSetting extends Model
{
    protected $guarded = ['id'];
    const GENERAL_SETTINGS = ['name', 'desc', 'email', 'phone', 'logo'];

    public static function get($name, $default = null)
    {
        $setting = self::where('name', $name)->first();
        if (!$setting || !$setting->value) {
            return $default;
        }
        return $setting->value;
    }

    public static function set($name, $value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $setting = self::firstOrCreate([
            'name' => $name,
        ]);
        $setting->value = $value;
        $setting->save();
    }
}
