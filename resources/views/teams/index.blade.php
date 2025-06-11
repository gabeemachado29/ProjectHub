@extends('layouts.app')

@section('content')
<h2>Equipe do Projeto: {{ $project->title }}</h2>

<ul>
    @foreach($project->teamMembers as $member)
        <li>{{ $member->name }} ({{ $member->pivot->role }})</li>
    @endforeach
</ul>

<h3>Adicionar Membro</h3>
<form method="POST" action="{{ route('team.store', $project->id) }}">
    @csrf
    <select name="user_id">
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
    <select name="role">
        <option value="member">Membro</option>
        <option value="manager">Gerente</option>
    </select>
    <button type="submit">Adicionar</button>
</form>
@endsection
