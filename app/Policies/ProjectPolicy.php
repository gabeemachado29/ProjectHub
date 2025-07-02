<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Permite que qualquer utilizador autenticado veja a lista de projetos.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Permite que um utilizador veja os detalhes de um projeto se ele for
     * o criador ou um membro da equipa.
     */
    public function view(User $user, Project $project): bool
    {
        // Se o utilizador for o criador, permita.
        if ($user->id === $project->created_by) {
            return true;
        }

        // Se o utilizador for membro da equipa, permita.
        return $project->teamMembers->contains($user);
    }

    /**
     * Permite que apenas Admin ou Gerente criem novos projetos.
     * Utilizadores com a role 'user' serão bloqueados aqui.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Permite que apenas o criador ou um gerente da equipa atualizem o projeto.
     * Um utilizador com a role 'user' (e que não seja o criador) será bloqueado.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->created_by || 
               $project->teamMembers()->where('user_id', $user->id)->where('team.role', 'manager')->exists();
    }

    /**
     * Apenas o criador do projeto pode deletá-lo.
     */
    public function delete(User $user, Project $project): bool
    {
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

    public function addTeamMember(User $user, Project $project): bool
    {
        // O Admin já tem permissão através do Gate::before.

        // Permite se o utilizador for o criador do projeto.
        if ($user->id === $project->created_by) {
            return true;
        }

        // Permite se o utilizador for um 'manager' nesta equipa específica.
        return $project->teamMembers()->where([
            ['user_id', '=', $user->id],
            ['role', '=', 'manager']
        ])->exists();
    }

    /**
     * Determina se o utilizador pode remover membros da equipa do projeto.
     */
    public function removeTeamMember(User $user, Project $project): bool
    {
        // A lógica para remover é a mesma que para adicionar.
        return $this->addTeamMember($user, $project);
    }
}