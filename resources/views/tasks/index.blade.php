@extends('layouts.app-bootstrap')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h2">Tarefas</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Nova Tarefa</a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @forelse($tasks as $task)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none fs-5">{{ $task->title }}</a>
                            <div class="text-muted small">
                                Projeto: {{ $task->project->title }} | Atribuído a: {{ $task->assignedUser->name }}
                            </div>
                        </div>
                        <span class="badge bg-info rounded-pill">{{ $task->status }}</span>
                    </li>
                @empty
                    <li class="list-group-item">Nenhuma tarefa encontrada.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection