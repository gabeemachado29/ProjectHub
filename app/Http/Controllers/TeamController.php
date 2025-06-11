<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index($project_id)
    {
        $project = Project::with('teamMembers')->findOrFail($project_id);
        $users = User::all();
        return view('team.index', compact('project', 'users'));
    }

    public function store(Request $request, $project_id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:member,manager'
        ]);

        Team::create([
            'project_id' => $project_id,
            'user_id' => $request->user_id,
            'role' => $request->role
        ]);

        return redirect()->back()->with('success', 'Membro adicionado à equipe.');
    }
}
