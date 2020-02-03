<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Closure;

class LastOnlineAt
{
    public function handle($request, Closure $next)
    {
        if (backpack_auth()->guest()) {
            return $next($request);
        }
        $date = Carbon::createFromFormat('Y-m-d H:i:s', backpack_user()->last_online_at);
        if ($date->diffInMinutes(now()) !== 0)
        {
            DB::table("users")
                ->where("id", backpack_user()->id)
                ->update(["last_online_at" => Carbon::now()]);
        }
        return $next($request);
    }
}
