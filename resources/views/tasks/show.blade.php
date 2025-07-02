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
                    'completed' => 'success',
                    'expired' => 'danger'
                ][$task->status] ?? 'secondary';
            @endphp
            <span class="badge bg-{{$statusClass}} fs-6">{{ str_replace('_', ' ', $task->status) }}</span>
        </li>
        @if($task->due_date)
        <li class="list-group-item"><strong>Data de Entrega:</strong> {{ $task->due_date->format('d/m/Y') }}</li>
        @endif
        <li class="list-group-item"><strong>Projeto:</strong> <a href="{{ route('projects.show', $task->project) }}">{{ $task->project->title }}</a></li>
        <li class="list-group-item"><strong>Responsável:</strong> {{ $task->assignedUser->name }}</li>
    </ul>
</div>

{{-- SEÇÃO DE ARQUIVOS ANEXADOS --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-paperclip me-2"></i>Anexos</h5>
    </div>
    <div class="card-body">
        {{-- Formulário de Upload --}}
        @can('update', $task)
        <form action="{{ route('files.store', $task) }}" method="POST" enctype="multipart/form-data" class="mb-3">
            @csrf
            <div class="input-group">
                <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                <button class="btn btn-outline-secondary" type="submit">Enviar Arquivo</button>
            </div>
             @error('file')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </form>
        <hr>
        @endcan

        {{-- Lista de Arquivos --}}
        <ul class="list-group list-group-flush">
            @forelse ($task->files as $file)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-file-earmark me-2"></i>
                        <a href="{{ route('files.download', $file) }}">{{ $file->original_name }}</a>
                        <small class="text-muted ms-2">({{ round($file->size / 1024, 2) }} KB)</small>
                    </div>
                    @can('update', $task)
                    <form action="{{ route('files.destroy', $file) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este arquivo?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">&times;</button>
                    </form>
                    @endcan
                </li>
            @empty
                <li class="list-group-item text-muted">Nenhum arquivo anexado.</li>
            @endforelse
        </ul>
    </div>
</div>

{{-- SEÇÃO PARA CONCLUIR TAREFA --}}
@if ($task->status !== 'completed' && Auth::id() === $task->assigned_to)
<div class="card mb-4 border-success">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-check-circle-fill me-2"></i>Concluir Tarefa</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('tasks.complete', $task) }}">
            @csrf
            <div class="mb-3">
                <label for="comment" class="form-label"><strong>Comentário de Conclusão</strong></label>
                <textarea name="comment" id="comment" class="form-control @error('comment') is-invalid @enderror" rows="3" required placeholder="Descreva o que foi feito para concluir esta tarefa..."></textarea>
                @error('comment')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-lg me-1"></i>Marcar como Concluída</button>
        </form>
    </div>
</div>
@endif

{{-- SEÇÃO DE COMENTÁRIOS --}}
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

        {{-- Adicionar Comentário --}}
        @can('update', $task)
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
        @endcan
    </div>
</div>
@endsection