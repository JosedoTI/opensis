@extends('layouts.admin')
@section('title', isset($questao) ? 'Editar Questão' : 'Nova Questão')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.questoes.index') }}">Questões</a></li>
    <li class="breadcrumb-item active">{{ isset($questao) ? 'Editar' : 'Nova' }}</li>
@endsection

@section('content')
<h2 class="fw-bold mb-4">{{ isset($questao) ? "Editar Questão #{$questao->numero}" : 'Nova Questão' }}</h2>

<form action="{{ isset($questao) ? route('admin.questoes.update', $questao) : route('admin.questoes.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($questao)) @method('PUT') @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-8">
                            <label class="form-label fw-semibold">Prova *</label>
                            <select name="prova_id" class="form-select @error('prova_id') is-invalid @enderror" required>
                                <option value="">Selecione a prova...</option>
                                @foreach($provas as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('prova_id', $questao->prova_id ?? $provaId ?? '') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prova_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold">Número *</label>
                            <input type="number" name="numero"
                                   class="form-control @error('numero') is-invalid @enderror"
                                   value="{{ old('numero', $questao->numero ?? '') }}"
                                   min="1" max="200" required>
                            @error('numero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Disciplina</label>
                            <select name="disciplina_id" class="form-select" id="selectDisciplina">
                                <option value="">Sem disciplina</option>
                                @foreach($disciplinas as $d)
                                    <option value="{{ $d->id }}"
                                        {{ old('disciplina_id', $questao->disciplina_id ?? '') == $d->id ? 'selected' : '' }}>
                                        {{ $d->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Assunto</label>
                            <select name="assunto_id" class="form-select">
                                <option value="">Sem assunto</option>
                                @foreach($assuntos as $a)
                                    <option value="{{ $a->id }}"
                                        {{ old('assunto_id', $questao->assunto_id ?? '') == $a->id ? 'selected' : '' }}>
                                        {{ $a->disciplina->nome }} — {{ $a->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Enunciado *</label>
                            <textarea name="enunciado" rows="6"
                                      class="form-control @error('enunciado') is-invalid @enderror"
                                      required placeholder="Cole o enunciado da questão aqui. Pode usar HTML básico para formatação.">{{ old('enunciado', $questao->enunciado ?? '') }}</textarea>
                            @error('enunciado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Conteúdo complementar (tabelas, fórmulas...)</label>
                            <textarea name="conteudo_complementar" rows="3" class="form-control"
                                      placeholder="HTML opcional (tabelas, LaTeX, etc.)">{{ old('conteudo_complementar', $questao->conteudo_complementar ?? '') }}</textarea>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold">Imagem da questão</label>
                            <input type="file" name="imagem" class="form-control" accept="image/*">
                            @if(isset($questao) && $questao->imagem)
                                <div class="mt-1 small text-muted">Imagem atual: {{ basename($questao->imagem) }}</div>
                            @endif
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold">Alt da imagem</label>
                            <input type="text" name="imagem_alt" class="form-control"
                                   value="{{ old('imagem_alt', $questao->imagem_alt ?? '') }}"
                                   placeholder="Descrição acessível">
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label fw-semibold">Página doc.</label>
                            <input type="number" name="pagina_documento" class="form-control"
                                   value="{{ old('pagina_documento', $questao->pagina_documento ?? '') }}" min="1">
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                @foreach(['completa','incompleta','revisao'] as $s)
                                    <option value="{{ $s }}"
                                        {{ old('status', $questao->status ?? 'completa') === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alternativas --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-bold">
                    Alternativas *
                    <small class="text-muted fw-normal">(mínimo 2, máximo 5)</small>
                </div>
                <div class="card-body p-4">
                    @php
                        $letras = ['A','B','C','D','E'];
                        $alts = old('alternativas', isset($questao) ? $questao->alternativas->keyBy('letra')->toArray() : []);
                    @endphp
                    @foreach($letras as $idx => $letra)
                    <div class="mb-3 d-flex gap-2 align-items-start">
                        <span class="fw-bold text-primary mt-2" style="min-width:1.5rem">{{ $letra }})</span>
                        <div class="flex-grow-1">
                            <textarea name="alternativas[{{ $idx }}][texto]" rows="2"
                                      class="form-control @error("alternativas.{$idx}.texto") is-invalid @enderror"
                                      placeholder="Texto da alternativa {{ $letra }}">{{ $alts[$letra]['texto'] ?? ($alts[$idx]['texto'] ?? '') }}</textarea>
                            <input type="hidden" name="alternativas[{{ $idx }}][letra]" value="{{ $letra }}">
                            @error("alternativas.{$idx}.texto")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top:1rem">
                <div class="card-body p-4">
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-check me-1"></i>
                        {{ isset($questao) ? 'Atualizar questão' : 'Cadastrar questão' }}
                    </button>
                    <a href="{{ route('admin.questoes.index') }}" class="btn btn-outline-secondary w-100">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
