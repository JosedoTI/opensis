@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="container">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:70px;height:70px;font-size:1.8rem;font-weight:700">
                        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    @if($user->username)
                        <div class="text-muted small mb-1">@{{ $user->username }}</div>
                    @endif
                    <span class="badge {{ $user->isAdmin() ? 'bg-warning text-dark' : 'bg-secondary' }}">
                        {{ $user->isAdmin() ? 'Administrador' : 'Usuário' }}
                    </span>
                    @if($user->bio)
                        <p class="text-muted small mt-3 mb-0">{{ $user->bio }}</p>
                    @endif
                    <div class="mt-3 text-muted small">
                        Membro desde {{ $user->created_at->format('M/Y') }}
                    </div>
                </div>
                <div class="card-footer bg-transparent d-flex gap-2">
                    <a href="{{ route('perfil.edit') }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                        <i class="bi bi-pencil me-1"></i>Editar perfil
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-bold small">
                    <i class="bi bi-bar-chart me-1"></i>Estatísticas
                </div>
                <div class="list-group list-group-flush small">
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Respostas publicadas</span>
                        <span class="fw-bold">{{ $respostas->total() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Votos úteis recebidos</span>
                        <span class="fw-bold">{{ $user->respostas()->sum('votos_util') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <h5 class="fw-bold mb-3"><i class="bi bi-chat-dots me-2"></i>Minhas respostas</h5>

            @forelse($respostas as $resposta)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="small text-muted mb-1">
                        <span class="badge text-white me-1"
                              style="background:{{ $resposta->questao->prova->instituicao->cor_primaria ?? '#666' }}">
                            {{ $resposta->questao->prova->instituicao->sigla ?? '—' }}
                        </span>
                        {{ $resposta->questao->prova->nome }} — Questão #{{ $resposta->questao->numero }}
                        <span class="ms-2">{{ $resposta->created_at->format('d/m/Y') }}</span>
                    </div>
                    <p class="mb-2 small">{{ Str::limit($resposta->conteudo, 200) }}</p>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted small">
                            <i class="bi bi-hand-thumbs-up me-1"></i>{{ $resposta->votos_util }} úteis
                        </span>
                        <a href="{{ route('questoes.show', $resposta->questao_id) }}#resposta-{{ $resposta->id }}"
                           class="btn btn-sm btn-outline-primary ms-auto">
                            Ver questão
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-5">
                <i class="bi bi-chat-dots fs-2 d-block mb-2"></i>
                <p>Você ainda não publicou nenhuma resposta.</p>
                <a href="{{ route('questoes.index') }}" class="btn btn-primary btn-sm">
                    Explorar questões
                </a>
            </div>
            @endforelse

            {{ $respostas->links() }}
        </div>
    </div>
</div>
@endsection
