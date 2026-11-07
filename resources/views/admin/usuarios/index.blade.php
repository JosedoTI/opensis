@extends('layouts.admin')
@section('title','Usuários — Admin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Usuários</li>
@endsection

@section('content')
<h2 class="fw-bold mb-4">Usuários</h2>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-5">
                <input type="text" name="busca" class="form-control form-control-sm"
                       placeholder="Buscar por nome ou e-mail..."
                       value="{{ request('busca') }}">
            </div>
            <div class="col-sm-2">
                <select name="role" class="form-select form-select-sm">
                    <option value="">Todos perfis</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="usuario" {{ request('role') === 'usuario' ? 'selected' : '' }}>Usuário</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select name="ativo" class="form-select form-select-sm">
                    <option value="">Todos status</option>
                    <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativo</option>
                    <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary btn-sm w-100">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle small">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Perfil</th>
                    <th>Status</th>
                    <th>Cadastro</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $u)
                <tr class="{{ $u->trashed() ? 'table-secondary' : '' }}">
                    <td>
                        {{ $u->name }}
                        @if($u->trashed()) <span class="badge bg-secondary">Excluído</span> @endif
                    </td>
                    <td>{{ $u->email }}</td>
                    <td>
                        <span class="badge {{ $u->isAdmin() ? 'bg-warning text-dark' : 'bg-secondary' }}">
                            {{ $u->isAdmin() ? 'Admin' : 'Usuário' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $u->ativo ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                            {{ $u->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td>{{ $u->created_at->format('d/m/Y') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.usuarios.show', $u) }}"
                           class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                        <form action="{{ route('admin.usuarios.toggle-ativo', $u) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm {{ $u->ativo ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                    title="{{ $u->ativo ? 'Desativar' : 'Ativar' }}">
                                <i class="bi bi-{{ $u->ativo ? 'pause' : 'play' }}"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Nenhum usuário encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($usuarios->hasPages())
    <div class="card-footer bg-transparent">{{ $usuarios->links() }}</div>
    @endif
</div>
@endsection
