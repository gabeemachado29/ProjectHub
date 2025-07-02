<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // ... (policies existentes)
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate para Super Admin (já existente)
        Gate::before(function (User $user, string $ability) {
            dd($user->toArray());

            if ($user->role === 'admin') {
                return true;
            }
        });

        // Gate específico para o painel de admin
        Gate::define('access-admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}