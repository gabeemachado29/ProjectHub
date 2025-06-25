<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Permite que qualquer usuário logado veja a lista de projetos.
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // O usuário pode ver o projeto se for o criador ou um membro da equipe.
        return $user->id === $project->created_by || $project->teamMembers->contains($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Apenas 'admin' ou 'manager' podem criar projetos.
        // Certifique-se de que a coluna 'role' existe na sua tabela de usuários.
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        // O usuário pode atualizar se for o criador ou um gerente ('manager') do projeto.
        // CORREÇÃO: Especificamos 'team.role' para remover a ambiguidade.
        return $user->id === $project->created_by || $project->teamMembers()->where('user_id', $user->id)->where('team.role', 'manager')->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        // Apenas o criador do projeto pode deletá-lo.
        return $user->id === $project->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        // Apenas o criador pode restaurar.
        return $user->id === $project->created_by;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        // Apenas o criador pode deletar permanentemente.
        return $user->id === $project->created_by;
    }
}