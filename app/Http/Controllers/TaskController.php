<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['project', 'assignedUser'])->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('tasks.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
        ]);

        Task::create($request->all());

        return redirect()->route('tasks.index')->with('success', 'Tarefa criada!');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Tarefa atualizada!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Tarefa removida!');
    }
}
