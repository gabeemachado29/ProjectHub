@extends('layouts.app-bootstrap')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h2">Projetos</h1>
        @can('create', App\Models\Project::class)
            <a href="{{ route('projects.create') }}" class="btn btn-primary">Novo Projeto</a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @forelse ($projects as $project)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('projects.show', $project->id) }}" class="text-decoration-none fs-5">{{ $project->title }}</a>
                                <p class="mb-1 text-muted">Criado por: {{ $project->creator->name }}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('team.index', ['project_id' => $project->id]) }}" class="btn btn-outline-secondary btn-sm">Equipe</a>
                                @can('update', $project)
                                <a href="{{-- route('projects.edit', $project) --}}" class="btn btn-secondary btn-sm">Editar</a>
                                @endcan
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-center">Nenhum projeto encontrado.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection