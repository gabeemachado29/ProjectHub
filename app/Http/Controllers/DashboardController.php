<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard do usuário.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Carrega os projetos onde o usuário é o criador ou membro da equipe
        $projects = $user->teamProjects()->with('creator')->latest()->get()->merge(
            $user->projectsCreated()->with('creator')->latest()->get()
        )->unique('id');

        // Carrega as tarefas atribuídas ao usuário
        $tasks = $user->assignedTasks()->with('project')->latest()->take(10)->get();

        return view('dashboard', [
            'projects' => $projects,
            'tasks' => $tasks,
        ]);
    }
}