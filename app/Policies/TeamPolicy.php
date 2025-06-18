<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    /**
     * Determine whether the user can add members to a project.
     * O método recebe um Projeto, pois a ação de gerenciar a equipe está atrelada a ele.
     */
    public function addMember(User $user, Project $project): bool
    {
        // Apenas o criador do projeto ou um 'manager' da equipe podem adicionar membros.
        return $user->id === $project->created_by ||
               $project->teamMembers()->where('user_id', $user->id)->where('role', 'manager')->exists();
    }

    /**
     * Determine whether the user can remove a member from a project.
     */
    public function removeMember(User $user, Project $project): bool
    {
        // A regra para remover é a mesma que para adicionar.
        return $this->addMember($user, $project);
    }
}