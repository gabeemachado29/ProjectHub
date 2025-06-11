@extends('layouts.app')

@section('content')
<h2>Criar Tarefa</h2>
<form method="POST" action="{{ route('tasks.store') }}">
    @csrf
    <input type="text" name="title" placeholder="Título" required>
    <textarea name="description" placeholder="Descrição" required></textarea>
    <select name="project_id">
        @foreach($projects as $project)
            <option value="{{ $project->id }}">{{ $project->title }}</option>
        @endforeach
    </select>
    <input type="number" name="assigned_to" placeholder="ID do usuário responsável">
    <button type="submit">Criar</button>
</form>
@endsection
