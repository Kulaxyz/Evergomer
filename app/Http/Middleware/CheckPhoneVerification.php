<?php

namespace App\Http\Middleware;

use App\Traits\MustVerifyPhoneNumber;
use Closure;

class CheckPhoneVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (backpack_user() instanceof MustVerifyPhoneNumber) {
            return $next($request);
        }
        return $next($request);
    }
}
