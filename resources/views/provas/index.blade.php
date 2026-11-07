@extends('layouts.app')

@section('title', 'Provas')

@section('content')
<div class="container">
    <div class="d-flex align-items-center gap-3 mb-4">
        <h1 class="fw-bold mb-0"><i class="bi bi-file-earmark-text me-2 text-primary"></i>Provas</h1>
        <span class="badge bg-secondary fs-6">{{ $provas->total() }}</span>
    </div>

    {{-- Filtros --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('provas.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-sm-4">
                    <label class="form-label small fw-semibold">Instituição</label>
                    <select name="instituicao" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($instituicoes as $inst)
                            <option value="{{ $inst->id }}"
                                {{ request('instituicao') == $inst->id ? 'selected' : '' }}>
                                {{ $inst->sigla }} — {{ $inst->nome_processo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <label class="form-label small fw-semibold">Ano</label>
                    <select name="ano" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($anos as $ano)
                            <option value="{{ $ano }}" {{ request('ano') == $ano ? 'selected' : '' }}>
                                {{ $ano }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <label class="form-label small fw-semibold">Etapa</label>
                    <input type="text" name="etapa" class="form-control form-control-sm"
                           value="{{ request('etapa') }}" placeholder="Ex: 1ª Etapa, PSC 2">
                </div>
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>
                </div>
                @if(request()->hasAny(['instituicao','ano','etapa']))
                    <div class="col-12">
                        <a href="{{ route('provas.index') }}" class="btn btn-link btn-sm text-muted p-0">
                            <i class="bi bi-x me-1"></i>Limpar filtros
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    @if($provas->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-file-earmark-x fs-1 d-block mb-3"></i>
            <p>Nenhuma prova encontrada com os filtros selecionados.</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($provas as $prova)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm card-questao h-100">
                    <div class="card-body">
                        <div class="d-flex gap-2 mb-2 flex-wrap">
                            <span class="badge text-white"
                                  style="background:{{ $prova->instituicao->cor_primaria }}">
                                {{ $prova->instituicao->sigla }}
                            </span>
                            <span class="badge bg-secondary">{{ $prova->ano }}</span>
                            <span class="badge bg-light text-dark border">{{ $prova->etapa }}</span>
                        </div>
                        <h6 class="fw-bold mb-2">{{ $prova->nome }}</h6>
                        <div class="small text-muted mb-3">
                            @if($prova->total_questoes)
                                <span class="me-2">
                                    <i class="bi bi-list-ol me-1"></i>{{ $prova->total_questoes }} questões
                                </span>
                            @endif
                            @if($prova->gabarito_disponivel)
                                <span class="text-success me-2">
                                    <i class="bi bi-check-circle-fill me-1"></i>Gabarito
                                </span>
                            @endif
                            @if($prova->questoes_individualizadas)
                                <span class="text-primary">
                                    <i class="bi bi-card-list me-1"></i>Questões cadastradas
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('provas.show', $prova) }}"
                           class="btn btn-sm btn-outline-primary stretched-link">
                            Ver detalhes
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $provas->links() }}
        </div>
    @endif
</div>
@endsection
