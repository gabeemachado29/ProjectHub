<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can create comments on a given task.
     */
    public function create(User $user, Task $task): bool
    {
        // O usuário pode comentar se ele puder ver a tarefa.
        // A lógica de visualização da tarefa está na TaskPolicy, mas aqui podemos
        // simplesmente checar se ele é membro do projeto da tarefa.
        return $task->project->teamMembers->contains($user) || $task->project->created_by === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        // Apenas o próprio autor do comentário pode editá-lo.
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // O autor do comentário OU um gerente do projeto podem apagar o comentário.
        return $user->id === $comment->user_id ||
               $comment->task->project->teamMembers()->where('user_id', $user->id)->where('role', 'manager')->exists();
    }
}