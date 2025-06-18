@extends('layouts.app-bootstrap')

@section('header')
    <h1 class="h2"><i class="bi bi-check2-square me-2"></i>Detalhes da Tarefa</h1>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center bg-light">
        <h4 class="mb-0">{{ $task->title }}</h4>
        @can('update', $task)
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-secondary btn-sm"><i class="bi bi-pencil me-1"></i>Editar</a>
        @endcan
    </div>
    <div class="card-body">
        <p class="card-text">{{ $task->description }}</p>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><strong>Status:</strong>
            @php
                $statusClass = [
                    'pending' => 'warning',
                    'in_progress' => 'info',
                    'completed' => 'success'
                ][$task->status] ?? 'secondary';
            @endphp
            <span class="badge bg-{{$statusClass}} fs-6">{{ str_replace('_', ' ', $task->status) }}</span>
        </li>
        <li class="list-group-item"><strong>Projeto:</strong> <a href="{{ route('projects.show', $task->project) }}">{{ $task->project->title }}</a></li>
        <li class="list-group-item"><strong>Responsável:</strong> {{ $task->assignedUser->name }}</li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-chat-dots-fill me-2"></i>Comentários</h5>
    </div>
    <div class="card-body">
        @forelse($task->comments->sortByDesc('created_at') as $comment)
            <div class="d-flex mb-3">
                <div class="flex-shrink-0"><i class="bi bi-person-circle fs-3 text-muted"></i></div>
                <div class="ms-3 flex-grow-1">
                    <strong>{{ $comment->user->name }}</strong>
                    <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                    <div class="mt-1 bg-light p-2 rounded">{{ $comment->content }}</div>
                </div>
            </div>
        @empty
            <p class="text-muted">Nenhum comentário ainda.</p>
        @endforelse

        <hr>

        <form method="POST" action="{{ route('comments.store', ['task' => $task->id]) }}">
            @csrf
            <div class="mb-3">
                <label for="content" class="form-label"><strong>Adicionar Comentário</strong></label>
                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="3" required placeholder="Escreva seu comentário aqui..."></textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-send-fill me-1"></i>Comentar</button>
        </form>
    </div>
</div>
@endsection