@extends('layouts.app')

@section('content')
<h2>Criar Projeto</h2>

<form method="POST" action="{{ route('projects.store') }}">
    @csrf
    <input type="text" name="title" placeholder="Título" required><br>
    <textarea name="description" placeholder="Descrição" required></textarea><br>
    <button type="submit">Criar</button>
</form>
@endsection
