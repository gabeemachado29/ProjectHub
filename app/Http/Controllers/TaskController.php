<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Pega os IDs dos projetos que o usuário criou.
        $createdProjectsIds = $user->projectsCreated()->pluck('id');

        // CORREÇÃO: Especificamos 'projects.id' para remover a ambiguidade.
        $teamProjectsIds = $user->teamProjects()->pluck('projects.id');

        // Junta as duas listas e remove duplicatas.
        $allProjectIds = $createdProjectsIds->merge($teamProjectsIds)->unique();

        // Busca as tarefas que pertencem a esses projetos.
        $tasks = Task::whereIn('project_id', $allProjectIds)
                     ->with(['project', 'assignedUser'])
                     ->latest()
                     ->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        // Pega os IDs dos projetos que o usuário criou.
        $createdProjectsIds = $user->projectsCreated()->pluck('id');
        
        // CORREÇÃO: Especificamos 'projects.id' para remover a ambiguidade.
        $teamProjectsIds = $user->teamProjects()->pluck('projects.id');

        $allProjectIds = $createdProjectsIds->merge($teamProjectsIds)->unique();

        $projects = Project::whereIn('id', $allProjectIds)->get();

        if ($projects->isEmpty()) {
            abort(403, 'Você não tem permissão para criar tarefas em nenhum projeto.');
        }
            
        $users = User::all(); 
    
        $selectedProject = $request->query('project_id');

        return view('tasks.create', compact('projects', 'users', 'selectedProject'));
    }

    public function store(StoreTaskRequest $request)
    {
        Task::create($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Tarefa criada com sucesso!');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $projects = Project::all();
        $users = User::all();
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->update($request->validated());
        return redirect()->route('tasks.show', $task)->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Tarefa removida com sucesso!');
    }
}