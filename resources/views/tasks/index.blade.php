@extends('layouts.app')

@section('content')
<h2>Tarefas</h2>
<a href="{{ route('tasks.create') }}">Nova Tarefa</a>
<ul>
    @foreach($tasks as $task)
        <li>
            <a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a>
            (Projeto: {{ $task->project->title }}, Atribuído a: {{ $task->assignedUser->name }})
        </li>
    @endforeach
</ul>
@endsection
