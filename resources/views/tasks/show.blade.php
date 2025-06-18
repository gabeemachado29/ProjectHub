@extends('layouts.app-bootstrap')

@section('header')
    <h1 class="h2">Detalhes da Tarefa</h1>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">{{ $task->title }}</h4>
        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-secondary btn-sm">Editar</a>
    </div>
    <div class="card-body">
        <p class="card-text">{{ $task->description }}</p>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><strong>Status:</strong> <span class="badge bg-primary">{{ $task->status }}</span></li>
        <li class="list-group-item"><strong>Projeto:</strong> {{ $task->project->title }}</li>
        <li class="list-group-item"><strong>Responsável:</strong> {{ $task->assignedUser->name }}</li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Comentários</h5>
    </div>
    <div class="card-body">
        @forelse($task->comments as $comment)
            <div class="mb-3 border-bottom pb-2">
                <strong>{{ $comment->user->name }}:</strong>
                <p class="mb-1">{{ $comment->content }}</p>
                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
        @empty
            <p>Nenhum comentário ainda.</p>
        @endforelse

        <hr>

        <form method="POST" action="{{ route('comments.store', ['task' => $task->id]) }}">
            @csrf
            <div class="mb-3">
                <label for="content" class="form-label">Adicionar Comentário</label>
                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="3" required></textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Comentar</button>
        </form>
    </div>
</div>
@endsection