@extends('layouts.app-bootstrap')

@section('header')
    <h1 class="h2">Equipe do Projeto: {{ $project->title }}</h1>
@endsection

@section('content')
<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Membros da Equipe</span>
                <span class="badge bg-primary rounded-pill">{{ $project->teamMembers->count() + 1 }} membros</span>
            </div>
            <ul class="list-group list-group-flush">
                {{-- Criador do Projeto --}}
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $project->creator->name }}</strong>
                        <span class="text-muted small ms-1">(Criador)</span>
                    </div>
                    <span class="badge bg-success">Criador</span>
                </li>
                {{-- Outros Membros --}}
                @foreach($project->teamMembers as $member)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            {{ $member->name }}
                            <span class="badge bg-secondary ms-1">{{ $member->pivot->role }}</span>
                        </div>
                        {{-- Formulário de Remoção --}}
                        <form action="{{ route('team.destroy', ['project' => $project, 'user' => $member]) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este membro?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash-fill"></i> Remover
                            </button>
                        </form>
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
                        <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror">
                            <option value="">Selecione um usuário...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Função</label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror">
                            <option value="member">Membro</option>
                            <option value="manager">Gerente</option>
                        </select>
                         @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Adicionar à Equipe</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection