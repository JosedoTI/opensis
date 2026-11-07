@extends('layouts.app')

@section('title', 'Questões')

@section('content')
<div class="container">
    <div class="d-flex align-items-center gap-3 mb-4">
        <h1 class="fw-bold mb-0"><i class="bi bi-question-circle me-2 text-primary"></i>Questões</h1>
        <span class="badge bg-secondary fs-6">{{ $questoes->total() }}</span>
    </div>

    {{-- Filtros --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('questoes.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-sm-4 col-lg-2">
                    <label class="form-label small fw-semibold">Instituição</label>
                    <select name="instituicao" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($instituicoes as $inst)
                            <option value="{{ $inst->id }}" {{ request('instituicao') == $inst->id ? 'selected' : '' }}>
                                {{ $inst->sigla }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 col-lg-2">
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
                <div class="col-sm-4 col-lg-2">
                    <label class="form-label small fw-semibold">Etapa</label>
                    <select name="etapa" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($etapas as $etapa)
                            <option value="{{ $etapa }}" {{ request('etapa') == $etapa ? 'selected' : '' }}>
                                {{ $etapa }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label small fw-semibold">Disciplina</label>
                    <select name="disciplina" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($disciplinas as $disc)
                            <option value="{{ $disc->id }}" {{ request('disciplina') == $disc->id ? 'selected' : '' }}>
                                {{ $disc->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3 col-lg-1">
                    <label class="form-label small fw-semibold">Nº</label>
                    <input type="number" name="numero" class="form-control form-control-sm"
                           value="{{ request('numero') }}" min="1" placeholder="1…">
                </div>
                <div class="col-sm-3 col-lg-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>
                </div>
                @if(request()->hasAny(['instituicao','ano','etapa','disciplina','numero']))
                    <div class="col-12">
                        <a href="{{ route('questoes.index') }}" class="btn btn-link btn-sm text-muted p-0">
                            <i class="bi bi-x me-1"></i>Limpar filtros
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    @if($questoes->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-question-diamond fs-1 d-block mb-3"></i>
            <p>Nenhuma questão encontrada.</p>
            <p class="small">As questões de 2016–2019 podem estar disponíveis apenas como documentos PDF.</p>
        </div>
    @else
        <div class="list-group shadow-sm mb-4">
            @foreach($questoes as $questao)
            <a href="{{ route('questoes.show', $questao) }}"
               class="list-group-item list-group-item-action py-3 px-4">
                <div class="d-flex align-items-start gap-3">
                    <span class="badge bg-primary rounded-pill mt-1" style="min-width:2.5rem;font-size:.85rem">
                        {{ $questao->numero }}
                    </span>
                    <div class="flex-grow-1 min-width-0">
                        <div class="d-flex gap-1 flex-wrap mb-1">
                            <span class="badge text-white"
                                  style="background:{{ $questao->prova->instituicao->cor_primaria }};font-size:.65rem">
                                {{ $questao->prova->instituicao->sigla }}
                            </span>
                            <span class="badge bg-secondary" style="font-size:.65rem">{{ $questao->prova->ano }}</span>
                            <span class="badge bg-light text-dark border" style="font-size:.65rem">{{ $questao->prova->etapa }}</span>
                            @if($questao->disciplina)
                                <span class="badge text-white"
                                      style="background:{{ $questao->disciplina->cor ?? '#6c757d' }};font-size:.65rem">
                                    {{ $questao->disciplina->nome }}
                                </span>
                            @endif
                        </div>
                        <p class="mb-0 text-dark" style="font-size:.9rem">
                            {{ Str::limit(strip_tags($questao->enunciado), 200) }}
                        </p>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1 ms-2 flex-shrink-0">
                        @if($questao->gabaritoOficial)
                            <span class="badge-oficial">
                                <i class="bi bi-check-circle-fill me-1"></i>Gabarito
                            </span>
                        @endif
                        <i class="bi bi-chevron-right text-muted mt-1"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        {{ $questoes->links() }}
    @endif
</div>
@endsection
