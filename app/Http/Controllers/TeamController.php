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
        $this->authorize('update', $project); // Apenas quem pode atualizar o projeto pode ver a equipe

        // Carrega os usuários que ainda não estão na equipe para popular o dropdown
        $users = User::whereNotIn('id', $project->teamMembers()->pluck('users.id'))
                 ->where('id', '!=', $project->created_by)
                 ->get();

        return view('teams.index', compact('project', 'users'));
    }

    // Usando Route-Model Binding aqui também
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project); // Apenas quem pode atualizar pode adicionar membros

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
}