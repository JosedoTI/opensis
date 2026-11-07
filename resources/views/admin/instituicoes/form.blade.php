@extends('layouts.admin')
@section('title', isset($instituicao) ? 'Editar Instituição' : 'Nova Instituição')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.instituicoes.index') }}">Instituições</a></li>
    <li class="breadcrumb-item active">{{ isset($instituicao) ? 'Editar' : 'Nova' }}</li>
@endsection

@section('content')
<h2 class="fw-bold mb-4">{{ isset($instituicao) ? 'Editar Instituição' : 'Nova Instituição' }}</h2>

<div class="card border-0 shadow-sm" style="max-width:600px">
    <div class="card-body p-4">
        <form action="{{ isset($instituicao) ? route('admin.instituicoes.update', $instituicao) : route('admin.instituicoes.store') }}"
              method="POST">
            @csrf
            @if(isset($instituicao)) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-sm-4">
                    <label class="form-label fw-semibold">Sigla *</label>
                    <input type="text" name="sigla" class="form-control @error('sigla') is-invalid @enderror"
                           value="{{ old('sigla', $instituicao->sigla ?? '') }}"
                           placeholder="UEA" required>
                    @error('sigla')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-8">
                    <label class="form-label fw-semibold">Nome completo *</label>
                    <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                           value="{{ old('nome', $instituicao->nome ?? '') }}" required>
                    @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4">
                    <label class="form-label fw-semibold">Nome do processo *</label>
                    <input type="text" name="nome_processo" class="form-control"
                           value="{{ old('nome_processo', $instituicao->nome_processo ?? '') }}"
                           placeholder="SIS" required>
                </div>
                <div class="col-sm-8">
                    <label class="form-label fw-semibold">Descrição do processo</label>
                    <input type="text" name="descricao_processo" class="form-control"
                           value="{{ old('descricao_processo', $instituicao->descricao_processo ?? '') }}"
                           placeholder="Sistema de Ingresso Seriado">
                </div>
                <div class="col-sm-4">
                    <label class="form-label fw-semibold">Cor</label>
                    <input type="color" name="cor_primaria" class="form-control form-control-color w-100"
                           value="{{ old('cor_primaria', $instituicao->cor_primaria ?? '#003087') }}">
                </div>
                <div class="col-sm-8">
                    <label class="form-label fw-semibold">Site oficial</label>
                    <input type="url" name="site_oficial" class="form-control"
                           value="{{ old('site_oficial', $instituicao->site_oficial ?? '') }}"
                           placeholder="https://www.uea.edu.br">
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check me-1"></i>{{ isset($instituicao) ? 'Atualizar' : 'Cadastrar' }}
                </button>
                <a href="{{ route('admin.instituicoes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
