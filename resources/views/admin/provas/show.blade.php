@extends('layouts.admin')
@section('title', $prova->nome)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.provas.index') }}">Provas</a></li>
    <li class="breadcrumb-item active">{{ $prova->nome }}</li>
@endsection

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <h2 class="fw-bold mb-0">{{ $prova->nome }}</h2>
    <a href="{{ route('admin.provas.edit', $prova) }}" class="btn btn-outline-primary btn-sm ms-auto">
        <i class="bi bi-pencil me-1"></i>Editar
    </a>
    <a href="{{ route('admin.questoes.create', ['prova_id' => $prova->id]) }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Adicionar questão
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-bold">
                <i class="bi bi-list-ol me-2"></i>
                Questões ({{ $prova->questoes->count() }})
            </div>
            @if($prova->questoes->isEmpty())
                <div class="p-4 text-muted text-center">
                    <i class="bi bi-question-diamond fs-2 d-block mb-2"></i>
                    Nenhuma questão cadastrada.
                    <a href="{{ route('admin.questoes.create', ['prova_id' => $prova->id]) }}"
                       class="d-block mt-2">Adicionar agora</a>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($prova->questoes as $questao)
                    <div class="list-group-item d-flex align-items-center gap-2 py-2">
                        <span class="badge bg-primary rounded-pill" style="min-width:2rem">{{ $questao->numero }}</span>
                        <div class="flex-grow-1 small text-truncate" style="max-width:400px">
                            {{ Str::limit(strip_tags($questao->enunciado), 100) }}
                        </div>
                        @if($questao->disciplina)
                            <span class="badge text-white small"
                                  style="background:{{ $questao->disciplina->cor ?? '#6c757d' }}">
                                {{ $questao->disciplina->nome }}
                            </span>
                        @endif
                        <span class="badge {{ $questao->status === 'completa' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} ms-auto">
                            {{ $questao->status }}
                        </span>
                        <a href="{{ route('admin.questoes.show', $questao) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Documentos --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-bold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-folder2-open me-2"></i>Documentos ({{ $prova->documentos->count() }})</span>
            </div>
            @foreach($prova->documentos as $doc)
            <div class="list-group-item p-3 d-flex align-items-center gap-3">
                <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                <div class="flex-grow-1">
                    <div class="small fw-semibold">{{ ucfirst($doc->tipo) }} — {{ $doc->nome_arquivo }}</div>
                    <div class="text-muted" style="font-size:.7rem">
                        {{ $doc->tamanho_formatado }}
                        @if($doc->total_paginas) · {{ $doc->total_paginas }} pág. @endif
                        @if($doc->url_externa)
                            · <a href="{{ $doc->url_externa }}" target="_blank">Link externo</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            <div class="card-footer bg-transparent">
                <form action="{{ route('admin.provas.documentos.store', $prova) }}"
                      method="POST" enctype="multipart/form-data" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-sm-3">
                        <select name="tipo" class="form-select form-select-sm" required>
                            @foreach(['prova','gabarito','resolucao','edital','outro'] as $t)
                                <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <input type="file" name="arquivo" class="form-control form-control-sm" accept=".pdf">
                    </div>
                    <div class="col-sm-4">
                        <input type="url" name="url_externa" class="form-control form-control-sm"
                               placeholder="Ou cole URL externa (Drive, site...)">
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-sm btn-success w-100">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-bold">Detalhes</div>
            <div class="list-group list-group-flush small">
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Instituição</span>
                    <span class="fw-semibold">{{ $prova->instituicao->sigla }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Ano</span>
                    <span>{{ $prova->ano }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Etapa</span>
                    <span>{{ $prova->etapa }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Tipo</span>
                    <span>{{ ucfirst($prova->tipo) }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Q. individualizadas</span>
                    <span>{{ $prova->questoes_individualizadas ? '✅ Sim' : '❌ Não' }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Gabarito oficial</span>
                    <span>{{ $prova->gabarito_disponivel ? '✅ Sim' : '❌ Não' }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Resolução oficial</span>
                    <span>{{ $prova->resolucao_disponivel ? '✅ Sim' : '❌ Não' }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Status</span>
                    <span class="badge {{ $prova->ativa ? 'bg-success' : 'bg-secondary' }}">
                        {{ $prova->ativa ? 'Ativa' : 'Inativa' }}
                    </span>
                </div>
            </div>
            <div class="card-footer bg-transparent d-flex flex-column gap-2">
                <a href="{{ route('provas.show', $prova) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-eye me-1"></i>Ver no site
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
