@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<h2 class="fw-bold mb-4">Dashboard</h2>

<div class="row g-3 mb-5">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <div class="display-6 fw-bold text-primary">{{ $stats['total_provas'] }}</div>
                <div class="text-muted small mt-1"><i class="bi bi-file-earmark-text me-1"></i>Provas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <div class="display-6 fw-bold text-success">{{ $stats['total_questoes_completas'] }}</div>
                <div class="text-muted small mt-1"><i class="bi bi-question-circle me-1"></i>Questões completas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <div class="display-6 fw-bold text-info">{{ $stats['total_usuarios'] }}</div>
                <div class="text-muted small mt-1"><i class="bi bi-people me-1"></i>Usuários</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <div class="display-6 fw-bold {{ $stats['total_denuncias'] > 0 ? 'text-danger' : 'text-secondary' }}">
                    {{ $stats['total_denuncias'] }}
                </div>
                <div class="text-muted small mt-1"><i class="bi bi-flag me-1"></i>Denúncias pendentes</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-bold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Provas recentes</span>
                <a href="{{ route('admin.provas.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div>
            <div class="list-group list-group-flush">
                @foreach($provasRecentes as $prova)
                <a href="{{ route('admin.provas.show', $prova) }}"
                   class="list-group-item list-group-item-action py-2 px-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge text-white"
                              style="background:{{ $prova->instituicao->cor_primaria }}">
                            {{ $prova->instituicao->sigla }}
                        </span>
                        <span class="small fw-semibold">{{ $prova->nome }}</span>
                        <span class="ms-auto text-muted small">{{ $prova->created_at->format('d/m/Y') }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-bold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-flag me-2 text-danger"></i>Denúncias pendentes</span>
                <a href="{{ route('admin.moderacao.index') }}" class="btn btn-sm btn-outline-danger">Ver todas</a>
            </div>
            @forelse($denunciasPendentes as $denuncia)
            <a href="{{ route('admin.moderacao.show', $denuncia) }}"
               class="list-group-item list-group-item-action py-2 px-3">
                <div class="small">
                    <span class="badge bg-warning text-dark me-1">{{ $denuncia->motivo }}</span>
                    Por: {{ $denuncia->denunciante->name }}
                    <span class="text-muted ms-2">{{ $denuncia->created_at->diffForHumans() }}</span>
                </div>
            </a>
            @empty
            <div class="p-4 text-center text-muted small">
                <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>
                Nenhuma denúncia pendente.
            </div>
            @endforelse
        </div>
    </div>
</div>

<div class="row g-3 mt-2">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-bold"><i class="bi bi-lightning me-2"></i>Ações rápidas</div>
            <div class="card-body d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.provas.create') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Nova prova
                </a>
                <a href="{{ route('admin.questoes.create') }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Nova questão
                </a>
                <a href="{{ route('admin.disciplinas.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-tags me-1"></i>Disciplinas
                </a>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-info btn-sm">
                    <i class="bi bi-people me-1"></i>Usuários
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
