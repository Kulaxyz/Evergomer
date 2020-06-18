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
        if (auth()->guest()) {
            return $next($request);
        }
        $date = Carbon::createFromFormat('Y-m-d H:i:s', auth()->user()->last_online_at);
        if ($date->diffInMinutes(Carbon::now()) !== 0)
        {
            DB::table("users")
                ->where("id", auth()->id())
                ->update(["last_online_at" => Carbon::now()]);
        }

        return $next($request);
    }
}
