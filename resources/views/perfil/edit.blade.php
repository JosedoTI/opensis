@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="container" style="max-width:600px">
    <h2 class="fw-bold mb-4"><i class="bi bi-person-gear me-2"></i>Editar Perfil</h2>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent fw-semibold">Dados básicos</div>
        <div class="card-body p-4">
            <form action="{{ route('perfil.update') }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nome completo *</label>
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">Nome de usuário</label>
                    <div class="input-group">
                        <span class="input-group-text">@</span>
                        <input type="text" id="username" name="username"
                               class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username', $user->username) }}"
                               placeholder="seunome">
                    </div>
                    @error('username')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="bio" class="form-label fw-semibold">Bio (opcional)</label>
                    <textarea id="bio" name="bio" rows="3"
                              class="form-control @error('bio') is-invalid @enderror"
                              placeholder="Conte um pouco sobre você...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-1"></i>Salvar alterações
                    </button>
                    <a href="{{ route('perfil.show') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent fw-semibold">Alterar senha</div>
        <div class="card-body p-4">
            <form action="{{ route('perfil.senha') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="senha_atual" class="form-label fw-semibold">Senha atual *</label>
                    <input type="password" id="senha_atual" name="senha_atual"
                           class="form-control @error('senha_atual') is-invalid @enderror" required>
                    @error('senha_atual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Nova senha *</label>
                    <input type="password" id="password" name="password"
                           class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirmar nova senha *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control" required>
                </div>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-key me-1"></i>Alterar senha
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
