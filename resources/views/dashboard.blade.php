@extends('layouts.app-bootstrap')

@section('header')
    <h1 class="h2"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h1>
@endsection

@section('content')
    <div class="alert alert-info">
        Bem-vindo de volta, <strong>{{ Auth::user()->name }}</strong>! Aqui está um resumo das suas atividades.
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fs-5"><i class="bi bi-kanban me-2"></i>Meus Projetos</div>
                <div class="list-group list-group-flush">
                    @forelse ($projects as $project)
                        <a href="{{ route('projects.show', $project) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">{{ $project->title }}</h5>
                                <small class="text-muted">Criado por: {{ $project->creator->name }}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $project->tasks_count }} {{ Str::plural('tarefa', $project->tasks_count) }}</span>
                        </a>
                    @empty
                        <div class="list-group-item text-muted">Você ainda não faz parte de nenhum projeto.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fs-5"><i class="bi bi-check2-square me-2"></i>Minhas Tarefas Recentes</div>
                <ul class="list-group list-group-flush">
                    @forelse ($tasks as $task)
                        <li class="list-group-item">
                            <a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Projeto: {{ $task->project->title }}</small>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'in_progress' => 'info',
                                        'completed' => 'success'
                                    ][$task->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{$statusClass}}">{{ str_replace('_', ' ', $task->status) }}</span>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Nenhuma tarefa atribuída a você.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection