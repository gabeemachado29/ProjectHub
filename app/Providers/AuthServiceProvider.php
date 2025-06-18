<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User; // Importe o model User
use App\Policies\CommentPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate; // Importe o Gate

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
        Task::class => TaskPolicy::class,
        Team::class => TeamPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // ADICIONE ESTE BLOCO DE CÓDIGO
        // Define uma regra "antes" de todas as outras para o "Super Admin"
        Gate::before(function (User $user, string $ability) {
            if ($user->role === 'admin') {
                return true; // Se for admin, autoriza qualquer habilidade
            }
        });
    }
}