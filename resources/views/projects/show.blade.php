@extends('layouts.app-bootstrap')

@section('header')
    <h1 class="h2">Projeto: {{ $project->title }}</h1>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Descrição</h5>
            <p class="card-text">{{ $project->description }}</p>
        </div>
        <div class="card-footer text-muted">
            Criado por: {{ $project->creator->name }} em {{ $project->created_at->format('d/m/Y') }}
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Tarefas do Projeto</h5>
        </div>
        <ul class="list-group list-group-flush">
            @forelse ($project->tasks as $task)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a>
                    <span class="badge bg-info rounded-pill">{{ $task->status }}</span>
                </li>
            @empty
                <li class="list-group-item">Nenhuma tarefa neste projeto ainda.</li>
            @endforelse
        </ul>
    </div>
@endsection