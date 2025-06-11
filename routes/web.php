<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TeamController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // CRUD de projetos
    Route::resource('projects', ProjectController::class);

    // CRUD de tarefas
    Route::resource('tasks', TaskController::class);

    // Apenas o método store para comentários (comentários são adicionados dentro de tarefas)
    Route::resource('comments', CommentController::class)->only(['store']);

    // Gerenciamento de equipe por projeto
    Route::get('/team/{project_id}', [TeamController::class, 'index'])->name('team.index');
    Route::post('/team/{project_id}/add', [TeamController::class, 'store'])->name('team.store');
});

require __DIR__.'/auth.php';
