@extends('layouts.app-bootstrap')

@section('header')
    <h1 class="h2">Editar Projeto: {{ $project->title }}</h1>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('projects.update', $project) }}">
            @csrf
            @method('PUT') {{-- Importante para indicar que é uma atualização --}}

            <div class="mb-3">
                <label for="title" class="form-label">Título do Projeto</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $project->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $project->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection