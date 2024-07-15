<?php

namespace App\Providers;

use App\Models\CompanyUser;
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
                $userCount = User::withTrashed()->where('email', $value)
                    ->when($parameters[0], function ($query) use ($parameters) {
                        $query->where('id', '!=', $parameters[0]);
                    })
                    ->count();

                return ($userCount === 0);
            } else {
                return false;
            }
        });

        Validator::extend('unique_user_name', function ($attribute, $value, $parameters, $validator) {
            $companyId = $parameters[0];
            $userId = isset($parameters[1]) ? $parameters[1] : null;

            $userCount = CompanyUser::where('company_id', $companyId)
                ->whereHas('user', function ($query) use ($value, $userId) {
                    $query->where('name', $value);
                    if ($userId) {
                        $query->where('id', '!=', $userId);
                    }
                })
                ->count();

            return ($userCount === 0);
        });
    }
}
