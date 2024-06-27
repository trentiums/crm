<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Schema::defaultStringLength(191);

        Validator::extend('unique_email_except', function ($attribute, $value, $parameters, $validator) {
            $user = User::where('id', $parameters[0])->first();
            if ($user) {
                $userCount = User::where('email', $value)
                    ->when($parameters[0], function ($query) use ($parameters) {
                        $query->where('id', '!=', $parameters[0]);
                    })
                    ->count();

                return ($userCount === 0);
            } else {
                return false;
            }
        });
    }
}
