@extends('layouts.app-bootstrap')

@section('header')
    <h1 class="h2">Equipe do Projeto: {{ $project->title }}</h1>
@endsection

@section('content')
<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Membros da Equipe</div>
            <ul class="list-group list-group-flush">
                @foreach($project->teamMembers as $member)
                    <li class="list-group-item d-flex justify-content-between">
                        {{ $member->name }}
                        <span class="badge bg-secondary">{{ $member->pivot->role }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Adicionar Membro</div>
            <div class="card-body">
                <form method="POST" action="{{ route('team.store', $project->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Usuário</label>
                        <select name="user_id" id="user_id" class="form-select">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Função</label>
                        <select name="role" id="role" class="form-select">
                            <option value="member">Membro</option>
                            <option value="manager">Gerente</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Adicionar à Equipe</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection