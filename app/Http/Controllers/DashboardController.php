<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Task; // Importe o modelo Task
use Illuminate\Support\Facades\DB; // Importe o DB Facade

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

        // --- LÓGICA DO GRÁFICO ADICIONADA AQUI ---
        // Pega todas as tarefas visíveis para o usuário (criador do projeto ou responsável pela tarefa)
        $userTaskIds = $user->assignedTasks()->pluck('id');
        $userProjectIds = $user->projectsCreated()->pluck('id');

        $tasksForChart = Task::whereIn('id', $userTaskIds)
                                ->orWhereIn('project_id', $userProjectIds)
                                ->get();
        
        // Agrupa e conta as tarefas por status
        $tasksByStatus = $tasksForChart->groupBy('status')->map->count();

        // Prepara os dados para o gráfico
        $chartLabels = $tasksByStatus->keys();
        $chartData = $tasksByStatus->values();
        // --- FIM DA LÓGICA DO GRÁFICO ---


        return view('dashboard', [
            'projects' => $projects,
            'tasks' => $tasks,
            'chartLabels' => $chartLabels, // Envia os dados para a view
            'chartData' => $chartData,     // Envia os dados para a view
        ]);
    }
}