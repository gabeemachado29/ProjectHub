<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\DashboardController; // Adicione esta linha
use App\Http\Controllers\ProfileController;

// Rota para a página inicial
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rota para o Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // CRUD de projetos
    Route::resource('projects', ProjectController::class);

    // CRUD de tarefas
    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::resource('tasks', TaskController::class);

    // Apenas o método store para comentários (comentários são adicionados dentro de tarefas)
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');
    // A linha abaixo pode ser removida se a de cima for usada
    Route::resource('comments', CommentController::class)->only(['store']);


    // Gerenciamento de equipe por projeto
    Route::get('/projects/{project}/team', [TeamController::class, 'index'])->name('team.index');
    Route::post('/projects/{project}/team', [TeamController::class, 'store'])->name('team.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';