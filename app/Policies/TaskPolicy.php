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
        // O usuário pode ver a tarefa se ele for membro do projeto correspondente.
        return $task->project->teamMembers->contains($user) || $task->project->created_by === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): bool
    {
        // O usuário pode criar uma tarefa se for membro da equipe do projeto.
        return $project->teamMembers->contains($user) || $project->created_by === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        // O usuário pode atualizar a tarefa se for o responsável por ela OU um gerente do projeto.
        return $user->id === $task->assigned_to ||
               $task->project->teamMembers()->where('user_id', $user->id)->where('role', 'manager')->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        // Apenas um gerente de projeto OU o criador do projeto podem deletar a tarefa.
        return $user->id === $task->project->created_by ||
               $task->project->teamMembers()->where('user_id', $user->id)->where('role', 'manager')->exists();
    }
}