@extends('layouts.app-bootstrap')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h2"><i class="bi bi-kanban me-2"></i>Projetos</h1>
        @can('create', App\Models\Project::class)
            <a href="{{ route('projects.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Novo Projeto</a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @forelse ($projects as $project)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('projects.show', $project->id) }}" class="text-decoration-none fs-5">{{ $project->title }}</a>
                            <p class="mb-1 text-muted">Criado por: {{ $project->creator->name }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('team.index', $project) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-people-fill me-1"></i>Equipe</a>
                            @can('update', $project)
                                {{-- Link de edição corrigido --}}
                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-secondary btn-sm"><i class="bi bi-pencil-fill me-1"></i>Editar</a>
                            @endcan
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted">Nenhum projeto encontrado.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection