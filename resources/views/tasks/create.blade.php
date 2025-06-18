@extends('layouts.app-bootstrap')

@section('header')
    <h1 class="h2">Criar Nova Tarefa</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="project_id" class="form-label">Projeto</label>
                <select name="project_id" id="project_id" class="form-select @error('project_id') is-invalid @enderror">
                    @foreach($projects as $project)
                        {{-- 
                           A CORREÇÃO ESTÁ AQUI.
                           A view usa a variável "$selectedProject" que o Controller enviou.
                           Ela NÃO TENTA usar "$request->query('project_id')" ou algo parecido.
                        --}}
                        <option value="{{ $project->id }}" @selected(old('project_id', $selectedProject ?? '') == $project->id)>
                            {{ $project->title }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="assigned_to" class="form-label">Atribuir Para</label>
                <select name="assigned_to" id="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Criar Tarefa</button>
        </form>
    </div>
</div>
@endsection