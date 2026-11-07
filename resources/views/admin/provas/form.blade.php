@extends('layouts.admin')
@section('title', isset($prova) ? 'Editar Prova' : 'Nova Prova')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.provas.index') }}">Provas</a></li>
    <li class="breadcrumb-item active">{{ isset($prova) ? 'Editar' : 'Nova' }}</li>
@endsection

@section('content')
<h2 class="fw-bold mb-4">{{ isset($prova) ? 'Editar Prova' : 'Nova Prova' }}</h2>

<div class="card border-0 shadow-sm" style="max-width:700px">
    <div class="card-body p-4">
        <form action="{{ isset($prova) ? route('admin.provas.update', $prova) : route('admin.provas.store') }}"
              method="POST">
            @csrf
            @if(isset($prova)) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Instituição *</label>
                    <select name="instituicao_id" class="form-select @error('instituicao_id') is-invalid @enderror" required>
                        <option value="">Selecione...</option>
                        @foreach($instituicoes as $inst)
                            <option value="{{ $inst->id }}"
                                {{ old('instituicao_id', $prova->instituicao_id ?? '') == $inst->id ? 'selected' : '' }}>
                                {{ $inst->sigla }} — {{ $inst->nome_processo }}
                            </option>
                        @endforeach
                    </select>
                    @error('instituicao_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-3">
                    <label class="form-label fw-semibold">Ano *</label>
                    <input type="number" name="ano"
                           class="form-control @error('ano') is-invalid @enderror"
                           value="{{ old('ano', $prova->ano ?? '') }}" min="2016" max="2030" required>
                    @error('ano')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-3">
                    <label class="form-label fw-semibold">Tipo</label>
                    <select name="tipo" class="form-select">
                        @foreach(['objetiva','redacao','mista'] as $tipo)
                            <option value="{{ $tipo }}"
                                {{ old('tipo', $prova->tipo ?? 'objetiva') === $tipo ? 'selected' : '' }}>
                                {{ ucfirst($tipo) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Etapa *</label>
                    <input type="text" name="etapa"
                           class="form-control @error('etapa') is-invalid @enderror"
                           value="{{ old('etapa', $prova->etapa ?? '') }}"
                           placeholder="Ex: 1ª Etapa, PSC 2" required>
                    @error('etapa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Nome da prova *</label>
                    <input type="text" name="nome"
                           class="form-control @error('nome') is-invalid @enderror"
                           value="{{ old('nome', $prova->nome ?? '') }}"
                           placeholder="Ex: SIS 2023 — 1ª Etapa" required>
                    @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label fw-semibold">Total de questões</label>
                    <input type="number" name="total_questoes"
                           class="form-control"
                           value="{{ old('total_questoes', $prova->total_questoes ?? '') }}"
                           min="1" max="200">
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="questoes_individualizadas"
                               id="qi" value="1"
                               {{ old('questoes_individualizadas', $prova->questoes_individualizadas ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="qi">Questões individualizadas cadastradas</label>
                    </div>
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" name="gabarito_disponivel"
                               id="gd" value="1"
                               {{ old('gabarito_disponivel', $prova->gabarito_disponivel ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="gd">Gabarito oficial disponível</label>
                    </div>
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" name="resolucao_disponivel"
                               id="rd" value="1"
                               {{ old('resolucao_disponivel', $prova->resolucao_disponivel ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="rd">Resolução oficial disponível</label>
                    </div>
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" name="ativa"
                               id="at" value="1"
                               {{ old('ativa', $prova->ativa ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="at">Prova ativa (visível no site)</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check me-1"></i>{{ isset($prova) ? 'Atualizar' : 'Cadastrar' }}
                </button>
                <a href="{{ route('admin.provas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
