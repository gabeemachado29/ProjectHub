@extends('layouts.app-bootstrap')

@section('header')
    <h1 class="h2">Dashboard</h1>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            Você está logado!
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Meus Projetos
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($projects as $project)
                        <a href="{{ route('projects.show', $project) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $project->title }}</h5>
                                <small>Criado por: {{ $project->creator->name }}</small>
                            </div>
                            <p class="mb-1 text-muted">{{ Str::limit($project->description, 100) }}</p>
                        </a>
                    @empty
                        <div class="list-group-item">Nenhum projeto encontrado.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Minhas Tarefas Recentes
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($tasks as $task)
                        <li class="list-group-item">
                            <a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a>
                            <div class="text-muted small">
                                Projeto: {{ $task->project->title }} | Status: <span class="badge bg-primary rounded-pill">{{ $task->status }}</span>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item">Nenhuma tarefa atribuída a você.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection