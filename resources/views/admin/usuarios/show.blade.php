@extends('layouts.admin')
@section('title', $usuario->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.usuarios.index') }}">Usuários</a></li>
    <li class="breadcrumb-item active">{{ $usuario->name }}</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:70px;height:70px;font-size:1.8rem;font-weight:700">
                    {{ mb_strtoupper(mb_substr($usuario->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $usuario->name }}</h5>
                <div class="text-muted small mb-2">{{ $usuario->email }}</div>
                <span class="badge {{ $usuario->isAdmin() ? 'bg-warning text-dark' : 'bg-secondary' }} mb-1">
                    {{ $usuario->isAdmin() ? 'Admin' : 'Usuário' }}
                </span>
                <span class="badge {{ $usuario->ativo ? 'bg-success' : 'bg-danger' }}">
                    {{ $usuario->ativo ? 'Ativo' : 'Inativo' }}
                </span>
                @if($usuario->bio)
                    <p class="text-muted small mt-3 mb-0">{{ $usuario->bio }}</p>
                @endif
                <div class="text-muted small mt-2">Cadastro: {{ $usuario->created_at->format('d/m/Y') }}</div>
            </div>
            <div class="card-footer bg-transparent d-flex flex-column gap-2">
                @if($usuario->id !== auth()->id())
                <form action="{{ route('admin.usuarios.toggle-ativo', $usuario) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm {{ $usuario->ativo ? 'btn-outline-warning' : 'btn-outline-success' }} w-100">
                        {{ $usuario->ativo ? 'Desativar conta' : 'Ativar conta' }}
                    </button>
                </form>
                @if(!$usuario->isAdmin())
                <form action="{{ route('admin.usuarios.promover', $usuario) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger w-100"
                            onclick="return confirm('Promover {{ $usuario->name }} a administrador?')">
                        Promover a admin
                    </button>
                </form>
                @else
                <form action="{{ route('admin.usuarios.rebaixar', $usuario) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary w-100"
                            onclick="return confirm('Remover privilégios de admin?')">
                        Remover admin
                    </button>
                </form>
                @endif
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <h5 class="fw-bold mb-3">Respostas publicadas ({{ $usuario->respostas->count() }})</h5>
        @forelse($usuario->respostas as $resp)
        <div class="card border-0 shadow-sm mb-2">
            <div class="card-body py-2 px-3 small">
                <div class="text-muted mb-1">
                    Q#{{ $resp->questao->numero }} — {{ $resp->questao->prova->nome }}
                    <span class="ms-2">{{ $resp->created_at->format('d/m/Y') }}</span>
                </div>
                <div>{{ Str::limit($resp->conteudo, 150) }}</div>
                <div class="mt-1 d-flex gap-2">
                    <span class="badge bg-light text-dark">{{ $resp->votos_util }} úteis</span>
                    <a href="{{ route('questoes.show', $resp->questao_id) }}#resposta-{{ $resp->id }}"
                       class="btn btn-sm btn-link p-0" target="_blank">Ver</a>
                </div>
            </div>
        </div>
        @empty
        <p class="text-muted">Nenhuma resposta publicada.</p>
        @endforelse
    </div>
</div>
@endsection
