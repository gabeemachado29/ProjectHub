<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProjectRequest; // Importa o novo Form Request

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('creator')->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        // Autoriza a criação usando a ProjectPolicy
        $this->authorize('create', Project::class);

        return view('projects.create');
    }

    // Usa o StoreProjectRequest para validação automática
    public function store(StoreProjectRequest $request)
    {
        // A autorização e validação são tratadas pelo Form Request e Policy
        $project = Auth::user()->projectsCreated()->create($request->validated());

        return redirect()->route('projects.index')->with('success', 'Projeto criado com sucesso!');
    }

    public function show(Project $project)
    {
        // Autoriza a visualização usando a ProjectPolicy
        $this->authorize('view', $project);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        // Autoriza a edição usando a ProjectPolicy
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        // Autoriza a atualização usando a ProjectPolicy
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Projeto atualizado com sucesso!');
    }

    public function destroy(Project $project)
    {
        // Autoriza a exclusão usando a ProjectPolicy
        $this->authorize('delete', $project);

        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Projeto removido com sucesso!');
    }
}