@extends('layouts.app')

@section('title', 'Editar Resposta')

@section('content')
<div class="container" style="max-width:700px">
    <h2 class="fw-bold mb-4"><i class="bi bi-pencil me-2"></i>Editar sua resposta</h2>

    <div class="card border-0 shadow-sm mb-4 p-3 bg-light small">
        <strong>Questão {{ $resposta->questao->numero }}</strong> —
        {{ $resposta->questao->prova->nome }}
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('respostas.update', $resposta) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Resposta que você indica (opcional)</label>
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach(['A','B','C','D','E'] as $letra)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio"
                                   name="resposta_indicada" id="ind_{{ $letra }}"
                                   value="{{ $letra }}"
                                   {{ $resposta->resposta_indicada === $letra ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="ind_{{ $letra }}">{{ $letra }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mb-3">
                    <label for="conteudo" class="form-label fw-semibold">Sua explicação / resolução *</label>
                    <textarea id="conteudo" name="conteudo" rows="7"
                              class="form-control @error('conteudo') is-invalid @enderror"
                              required>{{ old('conteudo', $resposta->conteudo) }}</textarea>
                    @error('conteudo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-1"></i>Salvar alterações
                    </button>
                    <a href="{{ route('questoes.show', $resposta->questao_id) }}"
                       class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
