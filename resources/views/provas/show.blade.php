@extends('layouts.app')

@section('title', $prova->nome)

@section('content')
<div class="container">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Início</a></li>
            <li class="breadcrumb-item"><a href="{{ route('provas.index') }}">Provas</a></li>
            <li class="breadcrumb-item active">{{ $prova->nome }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Cabeçalho da prova --}}
            <div class="card border-0 shadow-sm mb-4"
                 style="border-left: 5px solid {{ $prova->instituicao->cor_primaria }} !important">
                <div class="card-body p-4">
                    <div class="d-flex gap-2 mb-2 flex-wrap">
                        <span class="badge text-white fs-6 px-3"
                              style="background:{{ $prova->instituicao->cor_primaria }}">
                            {{ $prova->instituicao->sigla }}
                        </span>
                        <span class="badge bg-secondary fs-6">{{ $prova->ano }}</span>
                        <span class="badge bg-light text-dark border fs-6">{{ $prova->etapa }}</span>
                    </div>
                    <h1 class="h3 fw-bold mb-3">{{ $prova->nome }}</h1>
                    <div class="row g-3 text-muted small">
                        <div class="col-6 col-sm-3">
                            <div class="fw-semibold text-dark">Instituição</div>
                            {{ $prova->instituicao->nome }}
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="fw-semibold text-dark">Ano</div>
                            {{ $prova->ano }}
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="fw-semibold text-dark">Etapa</div>
                            {{ $prova->etapa }}
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="fw-semibold text-dark">Questões</div>
                            {{ $prova->total_questoes ?? '—' }}
                        </div>
                    </div>

                    {{-- Disponibilidade --}}
                    <div class="d-flex gap-2 mt-3 flex-wrap">
                        @if($prova->gabarito_disponivel)
                            <span class="badge bg-success-subtle text-success border border-success px-3 py-2">
                                <i class="bi bi-check-circle-fill me-1"></i>Gabarito oficial disponível
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border px-3 py-2">
                                <i class="bi bi-dash-circle me-1"></i>Gabarito não disponível
                            </span>
                        @endif
                        @if($prova->resolucao_disponivel)
                            <span class="badge bg-info-subtle text-info border border-info px-3 py-2">
                                <i class="bi bi-lightbulb-fill me-1"></i>Resolução oficial disponível
                            </span>
                        @endif
                        @if($prova->questoes_individualizadas)
                            <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2">
                                <i class="bi bi-card-list me-1"></i>Questões individualizadas
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Questões cadastradas --}}
            @if($prova->questoes->isNotEmpty())
            <h5 class="fw-bold mb-3">
                <i class="bi bi-list-ol me-2 text-primary"></i>
                Questões cadastradas ({{ $prova->questoes->count() }})
            </h5>
            <div class="list-group list-group-flush shadow-sm mb-4">
                @foreach($prova->questoes as $questao)
                <a href="{{ route('questoes.show', $questao) }}"
                   class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                    <span class="badge bg-primary rounded-pill" style="min-width:2rem">
                        {{ $questao->numero }}
                    </span>
                    <div class="flex-grow-1">
                        <div class="small text-truncate" style="max-width:400px">
                            {{ Str::limit(strip_tags($questao->enunciado), 120) }}
                        </div>
                        @if($questao->disciplina)
                            <span class="badge me-1 mt-1"
                                  style="background:{{ $questao->disciplina->cor ?? '#6c757d' }};color:#fff;font-size:.65rem">
                                {{ $questao->disciplina->nome }}
                            </span>
                        @endif
                    </div>
                    @if($questao->gabaritoOficial)
                        <i class="bi bi-check-circle-fill text-success" title="Gabarito disponível"></i>
                    @endif
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
                @endforeach
            </div>
            @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                @if($prova->ano < 2020)
                    As questões desta prova ({{ $prova->ano }}) ainda não foram cadastradas individualmente.
                    Utilize o documento original abaixo para consulta.
                @else
                    As questões desta prova ainda não foram cadastradas. Verifique o documento original.
                @endif
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Documentos --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent fw-bold">
                    <i class="bi bi-folder2-open me-2"></i>Documentos
                </div>
                <div class="card-body p-0">
                    @forelse($prova->documentos as $doc)
                        <div class="list-group-item p-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                                <div class="flex-grow-1 min-width-0">
                                    <div class="small fw-semibold">
                                        {{ ucfirst($doc->tipo) }}
                                    </div>
                                    <div class="text-muted" style="font-size:.7rem">
                                        {{ $doc->nome_arquivo }}
                                        @if($doc->total_paginas)
                                            · {{ $doc->total_paginas }} pág.
                                        @endif
                                    </div>
                                </div>
                                @if($doc->url_acesso)
                                    <a href="{{ $doc->url_acesso }}"
                                       target="_blank"
                                       class="btn btn-outline-danger btn-sm"
                                       title="Acessar PDF">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-3 text-muted small text-center">
                            <i class="bi bi-file-earmark-x d-block mb-1 fs-4"></i>
                            Nenhum documento cadastrado.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Outras provas do mesmo ano --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-bold small">
                    <i class="bi bi-collection me-1"></i>
                    Outras provas de {{ $prova->ano }}
                </div>
                <div class="list-group list-group-flush">
                    @php
                        $outras = \App\Models\Prova::where('ano', $prova->ano)
                            ->where('id', '!=', $prova->id)
                            ->with('instituicao')
                            ->take(5)->get();
                    @endphp
                    @forelse($outras as $outra)
                        <a href="{{ route('provas.show', $outra) }}"
                           class="list-group-item list-group-item-action small py-2 px-3">
                            <span class="badge text-white me-1"
                                  style="background:{{ $outra->instituicao->cor_primaria }};font-size:.65rem">
                                {{ $outra->instituicao->sigla }}
                            </span>
                            {{ $outra->etapa }}
                        </a>
                    @empty
                        <div class="p-3 text-muted small text-center">Nenhuma outra prova deste ano.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
