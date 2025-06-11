@extends('layouts.app')

@section('content')
<h2>Projetos</h2>
<a href="{{ route('projects.create') }}">Novo Projeto</a>

<ul>
@foreach ($projects as $project)
    <li>
        <a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a>
        (Criado por {{ $project->creator->name }})
    </li>
@endforeach
</ul>
@endsection
