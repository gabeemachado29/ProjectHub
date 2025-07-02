<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    // Usando Route-Model Binding: Laravel injeta o Project automaticamente
    public function index(Project $project)
    {
        $this->authorize('view', $project);

        // Carrega os usuários que ainda não estão na equipe para popular o dropdown
        $users = User::whereNotIn('id', $project->teamMembers()->pluck('users.id'))
                 ->where('id', '!=', $project->created_by)
                 ->get();

        return view('teams.index', compact('project', 'users'));
    }

    // Usando Route-Model Binding aqui também
    public function store(Request $request, Project $project)
    {
        $this->authorize('addTeamMember', $project); // Apenas quem pode atualizar pode adicionar membros

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:member,manager'
        ]);

        // Evita adicionar um membro que já existe
        if ($project->teamMembers()->where('user_id', $request->user_id)->exists()) {
            return redirect()->back()->with('error', 'Este usuário já faz parte da equipe.');
        }

        $project->teamMembers()->attach($request->user_id, ['role' => $request->role]);

        return redirect()->back()->with('success', 'Membro adicionado à equipe.');
    }

    public function destroy(Project $project, User $user)
    {
        $this->authorize('removeTeamMember', $project); // Apenas quem gerencia o projeto pode remover

        // Impede que o criador do projeto seja removido
        if ($project->created_by === $user->id) {
            return redirect()->back()->with('error', 'O criador do projeto não pode ser removido da equipe.');
        }

        $project->teamMembers()->detach($user->id);

        return redirect()->back()->with('success', 'Membro removido da equipe.');
    }
}