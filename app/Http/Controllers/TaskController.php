<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- ADICIONE ESTA LINHA

class TaskController extends Controller
{
    public function index()
    {
        // ... (o resto do seu método index)
        $user = Auth::user();
        $tasks = Task::where('assigned_to', $user->id)
                     ->orWhereIn('project_id', $user->projectsCreated->pluck('id'))
                     ->orWhereIn('project_id', $user->teamProjects->pluck('id'))
                     ->with('project')
                     ->latest()
                     ->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $user = Auth::user();
        // Carrega projetos onde o usuário é o criador ou membro da equipe.
        $projects = Project::where('created_by', $user->id)
                           ->orWhereHas('teamMembers', function ($query) use ($user) {
                               $query->where('user_id', $user->id);
                           })
                           ->get();

        // Carrega todos os usuários para o campo 'assigned_to'
        $users = User::all();

        return view('tasks.create', compact('projects', 'users'));
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());
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
        return redirect()->route('tasks.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Tarefa removida com sucesso!');
    }

    /**
     * Marca a tarefa como concluída e adiciona um comentário.
     */
    public function complete(Request $request, Task $task)
    {
        // Garante que apenas o usuário autorizado possa concluir a tarefa
        $this->authorize('update', $task);

        // Valida se o comentário foi preenchido
        $request->validate([
            'comment' => 'required|string|min:5',
        ]);

        // Atualiza o status da tarefa para 'completed'
        $task->update(['status' => 'completed']);

        // Adiciona o comentário de conclusão
        $task->comments()->create([
            'content' => 'Tarefa concluída com o seguinte comentário: ' . $request->input('comment'),
            'user_id' => Auth::id() // Esta linha precisa da importação correta
        ]);

        return redirect()->route('tasks.show', $task)->with('success', 'Tarefa marcada como concluída!');
    }
}