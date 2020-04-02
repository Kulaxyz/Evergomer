<?php

namespace App\Providers;

use App\Msg;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class LoadSettingsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $msg = DB::select('select * from msgs limit 1')[0];
        config([
            'msg91.auth_key' => $msg->auth_key
        ]);
        config([
            'msg91.default_route' => $msg->route
        ]);
        config([
            'msg91.default_country' => $msg->country_code
        ]);
        config([
            'msg91.default_sender' => $msg->sender
        ]);
        config([
            'backpack.custom.default_role' => \Illuminate\Support\Facades\DB::select('select * from roles where name=:name', ['name' => 'user'])[0]->id,
        ]);

        config([
            'backpack.custom.owner_role' => \Illuminate\Support\Facades\DB::select('select * from roles where name=:name', ['name' => 'owner'])[0]->id,
        ]);

    }
}
