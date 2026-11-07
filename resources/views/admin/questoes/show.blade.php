@extends('layouts.admin')
@section('title', "Questão #{$questao->numero}")
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.questoes.index') }}">Questões</a></li>
    <li class="breadcrumb-item active">Questão #{{ $questao->numero }}</li>
@endsection

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <h2 class="fw-bold mb-0">Questão #{{ $questao->numero }}</h2>
    <a href="{{ route('admin.questoes.edit', $questao) }}" class="btn btn-outline-primary btn-sm ms-auto">
        <i class="bi bi-pencil me-1"></i>Editar
    </a>
    <a href="{{ route('questoes.show', $questao) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-eye me-1"></i>Ver no site
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent">
                <span class="badge text-white" style="background:{{ $questao->prova->instituicao->cor_primaria }}">
                    {{ $questao->prova->instituicao->sigla }}
                </span>
                <span class="badge bg-secondary ms-1">{{ $questao->prova->nome }}</span>
            </div>
            <div class="card-body p-4">
                <div class="lh-lg">{!! $questao->enunciado !!}</div>
                @if($questao->conteudo_complementar)
                    <div class="alert alert-light border mt-3">{!! $questao->conteudo_complementar !!}</div>
                @endif
                <hr>
                @foreach($questao->alternativas as $alt)
                <div class="d-flex gap-2 mb-2">
                    <strong class="text-primary" style="min-width:1.5rem">{{ $alt->letra }})</strong>
                    <div>{!! $alt->texto !!}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Gabarito Oficial --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-bold">
                <i class="bi bi-patch-check me-2"></i>Gabarito Oficial
            </div>
            <div class="card-body">
                @if($questao->gabaritoOficial)
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold"
                             style="width:3rem;height:3rem;font-size:1.4rem">
                            {{ $questao->gabaritoOficial->resposta_correta }}
                        </div>
                        <div>
                            <div class="fw-semibold">Alternativa {{ $questao->gabaritoOficial->resposta_correta }}</div>
                            <div class="small text-muted">{{ $questao->gabaritoOficial->fonte_descricao }}</div>
                        </div>
                    </div>
                @else
                    <p class="text-muted">Gabarito não cadastrado.</p>
                @endif
                <hr>
                <form action="{{ route('admin.questoes.gabarito.store', $questao) }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-sm-2">
                        <label class="form-label small fw-semibold">Resposta *</label>
                        <select name="resposta_correta" class="form-select form-select-sm" required>
                            <option value="">—</option>
                            @foreach(['A','B','C','D','E'] as $l)
                                <option value="{{ $l }}"
                                    {{ old('resposta_correta', $questao->gabaritoOficial->resposta_correta ?? '') === $l ? 'selected' : '' }}>
                                    {{ $l }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold">Fonte (descrição)</label>
                        <input type="text" name="fonte_descricao" class="form-control form-control-sm"
                               value="{{ old('fonte_descricao', $questao->gabaritoOficial->fonte_descricao ?? '') }}"
                               placeholder="Site oficial UEA/UFAM">
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold">URL fonte</label>
                        <input type="url" name="fonte_url" class="form-control form-control-sm"
                               value="{{ old('fonte_url', $questao->gabaritoOficial->fonte_url ?? '') }}">
                    </div>
                    <div class="col-sm-2 d-flex align-items-end">
                        <button class="btn btn-success btn-sm w-100">Salvar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Resolução Oficial --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-bold">
                <i class="bi bi-lightbulb me-2"></i>Resolução Oficial
            </div>
            <div class="card-body">
                @if($questao->resolucaoOficial)
                    <div class="lh-lg small mb-3">{!! $questao->resolucaoOficial->conteudo !!}</div>
                    <hr>
                @endif
                <form action="{{ route('admin.questoes.resolucao.store', $questao) }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Conteúdo da resolução *</label>
                        <textarea name="conteudo" rows="4" class="form-control form-control-sm"
                                  placeholder="Explicação passo a passo da resolução oficial..."
                                  required>{{ old('conteudo', $questao->resolucaoOficial->conteudo ?? '') }}</textarea>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" name="fonte_descricao" class="form-control form-control-sm"
                               placeholder="Fonte da resolução"
                               value="{{ old('fonte_descricao', $questao->resolucaoOficial->fonte_descricao ?? '') }}">
                    </div>
                    <div class="col-sm-5">
                        <input type="url" name="fonte_url" class="form-control form-control-sm"
                               placeholder="URL da fonte"
                               value="{{ old('fonte_url', $questao->resolucaoOficial->fonte_url ?? '') }}">
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary btn-sm w-100">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-bold small">Detalhes</div>
            <div class="list-group list-group-flush small">
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Prova</span>
                    <a href="{{ route('admin.provas.show', $questao->prova) }}">{{ $questao->prova->nome }}</a>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Disciplina</span>
                    <span>{{ $questao->disciplina?->nome ?? '—' }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Assunto</span>
                    <span>{{ $questao->assunto?->nome ?? '—' }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Status</span>
                    <span class="badge {{ $questao->status === 'completa' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                        {{ $questao->status }}
                    </span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Alternativas</span>
                    <span>{{ $questao->alternativas->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
