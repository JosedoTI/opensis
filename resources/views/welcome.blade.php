@extends('layouts.app')

@section('title', 'Início')

@section('content')
<div class="container-fluid px-0">

    {{-- Hero --}}
    <div class="py-5" style="background: linear-gradient(135deg, #1a3a6e 0%, #0d2347 100%)">
        <div class="container text-center text-white">
            <h1 class="display-5 fw-bold mb-3">
                <i class="bi bi-journal-bookmark-fill me-2 text-warning"></i>
                Banco de Provas SIS/PSC
            </h1>
            <p class="lead mb-4 opacity-75">
                Consulte provas e questões do SIS/UEA e PSC/UFAM — com gabaritos oficiais e resoluções da comunidade.
            </p>
            <form action="{{ route('questoes.index') }}" method="GET" class="d-flex justify-content-center gap-2 flex-wrap">
                <input type="text" name="busca" class="form-control form-control-lg"
                       style="max-width:450px"
                       placeholder="Buscar questões, provas ou disciplinas...">
                <button type="submit" class="btn btn-warning btn-lg px-4 fw-bold">
                    <i class="bi bi-search me-1"></i>Buscar
                </button>
            </form>
        </div>
    </div>

    <div class="container py-5">

        {{-- Indicadores --}}
        <div class="row g-3 mb-5">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body">
                        <div class="display-6 fw-bold text-primary">{{ number_format($stats['provas']) }}</div>
                        <div class="text-muted small mt-1"><i class="bi bi-file-earmark-text me-1"></i>Provas</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body">
                        <div class="display-6 fw-bold text-success">{{ number_format($stats['questoes']) }}</div>
                        <div class="text-muted small mt-1"><i class="bi bi-question-circle me-1"></i>Questões</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body">
                        <div class="display-6 fw-bold" style="color:#003087">UEA</div>
                        <div class="text-muted small mt-1">SIS — Sistema de Ingresso Seriado</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <div class="card-body">
                        <div class="display-6 fw-bold" style="color:#006400">UFAM</div>
                        <div class="text-muted small mt-1">PSC — Processo Seletivo Contínuo</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Acesso rápido por instituição --}}
        <h2 class="fw-bold mb-4">Acesso por processo seletivo</h2>
        <div class="row g-4 mb-5">
            @foreach($instituicoes as $inst)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100"
                     style="border-left: 5px solid {{ $inst->cor_primaria }} !important;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width:50px;height:50px;background:{{ $inst->cor_primaria }};color:#fff;font-weight:700;font-size:1.1rem">
                                {{ $inst->sigla }}
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">{{ $inst->nome_processo }}</h5>
                                <small class="text-muted">{{ $inst->nome }}</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-3">
                            <strong>{{ $inst->descricao_processo }}</strong> —
                            Provas de 2016 a 2026 disponíveis para consulta.
                        </p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('provas.index', ['instituicao' => $inst->id]) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-file-earmark-text me-1"></i>Ver Provas ({{ $inst->provas_count }})
                            </a>
                            <a href="{{ route('questoes.index', ['instituicao' => $inst->id]) }}"
                               class="btn btn-sm"
                               style="background:{{ $inst->cor_primaria }};color:#fff">
                                <i class="bi bi-question-circle me-1"></i>Questões
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Provas recentes --}}
        @if($provasRecentes->isNotEmpty())
        <h2 class="fw-bold mb-4">Provas disponíveis</h2>
        <div class="row g-3 mb-5">
            @foreach($provasRecentes as $prova)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm card-questao h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 gap-2">
                            <span class="badge text-white"
                                  style="background:{{ $prova->instituicao->cor_primaria }}">
                                {{ $prova->instituicao->sigla }}
                            </span>
                            <span class="badge bg-secondary">{{ $prova->ano }}</span>
                            <span class="badge bg-light text-dark">{{ $prova->etapa }}</span>
                        </div>
                        <h6 class="fw-bold mb-1">{{ $prova->nome }}</h6>
                        <p class="text-muted small mb-2">
                            @if($prova->total_questoes)
                                <i class="bi bi-list-ol me-1"></i>{{ $prova->total_questoes }} questões
                            @endif
                            @if($prova->gabarito_disponivel)
                                <span class="ms-2 text-success">
                                    <i class="bi bi-check-circle-fill me-1"></i>Gabarito
                                </span>
                            @endif
                        </p>
                        <a href="{{ route('provas.show', $prova) }}"
                           class="btn btn-sm btn-outline-primary stretched-link">
                            Ver prova
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center">
            <a href="{{ route('provas.index') }}" class="btn btn-outline-primary px-4">
                <i class="bi bi-collection me-1"></i>Ver todas as provas
            </a>
        </div>
        @endif

        {{-- CTA cadastro --}}
        @guest
        <div class="card border-0 shadow-sm mt-5 text-center p-5"
             style="background: linear-gradient(135deg, #e8f4fd 0%, #d4edda 100%)">
            <h3 class="fw-bold mb-2">Participe da comunidade</h3>
            <p class="text-muted mb-4">
                Cadastre-se para publicar resoluções, marcar respostas úteis e contribuir com outros estudantes.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('register') }}" class="btn btn-primary px-5">
                    <i class="bi bi-person-plus me-1"></i>Criar conta grátis
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-primary px-4">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Entrar
                </a>
            </div>
        </div>
        @endguest

    </div>
</div>
@endsection
