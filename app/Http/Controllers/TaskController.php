<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Gate; // Importe o Gate

class TaskController extends Controller
{
    public function index()
    {
        // IMPORTANTE: Filtra as tarefas para mostrar apenas as de projetos que o usuário participa.
        $user = auth()->user();
        $tasks = Task::whereIn('project_id', function ($query) use ($user) {
            $query->select('id')->from('projects')->where('created_by', $user->id)
                ->orWhereHas('teamMembers', function ($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id);
                });
        })->with(['project', 'assignedUser'])->latest()->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $projects = Project::where('created_by', auth()->id())
            ->orWhereHas('teamMembers', function ($query) {
                $query->where('user_id', auth()->id());
            })->get();

        // Se o usuário não pode criar tarefas em nenhum projeto, não mostre o formulário.
        if ($projects->isEmpty()) {
            abort(403, 'Você não tem permissão para criar tarefas em nenhum projeto.');
        }
            
        $users = User::all();
        return view('tasks.create', compact('projects', 'users'));
    }

    public function store(StoreTaskRequest $request)
    {
        // A autorização é feita automaticamente pelo StoreTaskRequest
        Task::create($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Tarefa criada com sucesso!');
    }

    public function show(Task $task)
    {
        // Aplica a policy 'view'
        $this->authorize('view', $task);

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        // Aplica a policy 'update'
        $this->authorize('update', $task);

        $projects = Project::all();
        $users = User::all();
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        // Aplica a policy 'update'
        $this->authorize('update', $task);
        
        $task->update($request->validated());

        return redirect()->route('tasks.show', $task)->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Task $task)
    {
        // Aplica a policy 'delete'
        $this->authorize('delete', $task);
        
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Tarefa removida com sucesso!');
    }
}