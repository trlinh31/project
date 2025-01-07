<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Gate::define('verify-email', function ($user) {
        //     return true; // Adjust logic as needed - typically users should be able to verify their own email
        // });


        Passport::tokensExpireIn(Carbon::now()->addDays(7));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}
