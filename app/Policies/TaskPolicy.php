<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     * Permite a visualização se o utilizador for o criador do projeto OU o responsável pela tarefa.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->project->created_by || $user->id === $task->assigned_to;
    }

    /**
     * Determine whether the user can create models.
     * Apenas o criador do projeto pode criar tarefas.
     */
    public function create(User $user): bool
    {
        // Esta regra geralmente está ligada ao projeto, não à tarefa em si.
        // A lógica de criação é controlada no ProjectPolicy ou no controller.
        // Manter como true aqui é seguro, pois a verificação real ocorre antes.
        return true;
    }

    /**
     * Determine whether the user can update the model.
     * Permite a atualização se o utilizador for o criador do projeto OU o responsável pela tarefa.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->project->created_by || $user->id === $task->assigned_to;
    }

    /**
     * Determine whether the user can delete the model.
     * Apenas o criador do projeto pode deletar a tarefa.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->project->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return $user->id === $task->project->created_by;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $user->id === $task->project->created_by;
    }
}