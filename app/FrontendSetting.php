<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FrontendSetting extends Model
{
    protected $guarded = ['id'];

    public static function get($name)
    {
        $setting = self::where('name', $name)->first();
        if (!$setting) {
            return null;
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
