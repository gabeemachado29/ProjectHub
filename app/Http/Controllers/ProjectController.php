<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('creator')->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'manager') {
            abort(403, 'Você não tem permissão para criar projetos.');
        }

        return view('projects.create');
    }


    public function store(Request $request, Project $project)
    {
        $this->authorize('addMember', $project);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'created_by' => $request->user()->projectsCreated()->create($request->validated()),
        ]);

        return redirect()->route('projects.index')->with('success', 'Projeto criado!');
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $project->update($request->only('title', 'description'));

        return redirect()->route('projects.index')->with('success', 'Projeto atualizado!');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Projeto removido!');
    }
}
