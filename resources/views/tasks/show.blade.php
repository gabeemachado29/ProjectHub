@extends('layouts.app')

@section('content')
<h2>{{ $task->title }}</h2>
<p>{{ $task->description }}</p>

<h4>Comentários</h4>
<ul>
    @foreach($task->comments as $comment)
        <li><strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}</li>
    @endforeach
</ul>

<h4>Adicionar Comentário</h4>
<form method="POST" action="{{ route('comments.store', $task->id) }}">
    @csrf
    <textarea name="content" required></textarea>
    <button type="submit">Comentar</button>
</form>
@endsection
