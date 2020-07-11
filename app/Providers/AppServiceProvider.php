<?php

namespace App\Providers;

use App\Observers\PaymentObserver;
use App\Payment;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set('America/Toronto');
        Role::findOrCreate('admin');
        Role::findOrCreate('user');
        Role::findOrCreate('owner');
        Payment::observe(PaymentObserver::class);

    }
}
