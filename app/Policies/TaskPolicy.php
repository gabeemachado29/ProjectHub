<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        // A lógica aqui está correta e não causa ambiguidade.
        return $task->project->teamMembers->contains($user) || $task->project->created_by === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): bool
    {
        // A lógica aqui está correta e não causa ambiguidade.
        return $project->teamMembers->contains($user) || $project->created_by === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        // CORREÇÃO: Especificamos 'team.role' para remover a ambiguidade.
        return $user->id === $task->assigned_to ||
               $task->project->teamMembers()->where('user_id', $user->id)->where('team.role', 'manager')->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        // CORREÇÃO: Especificamos 'team.role' para remover a ambiguidade.
        return $user->id === $task->project->created_by ||
               $task->project->teamMembers()->where('user_id', $user->id)->where('team.role', 'manager')->exists();
    }
}