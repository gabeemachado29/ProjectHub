@extends('layouts.app-bootstrap')

@section('header')
    <h1 class="h2">Editar Tarefa</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('tasks.update', $task) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $task->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="pending" @selected(old('status', $task->status) == 'pending')>Pendente</option>
                    <option value="in_progress" @selected(old('status', $task->status) == 'in_progress')>Em Progresso</option>
                    <option value="completed" @selected(old('status', $task->status) == 'completed')>Concluída</option>
                </select>
                 @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

             <div class="mb-3">
                <label for="assigned_to" class="form-label">Atribuir Para</label>
                <select name="assigned_to" id="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(old('assigned_to', $task->assigned_to) == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
    </div>
</div>
@endsection