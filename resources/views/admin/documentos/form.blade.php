@extends('layouts.admin')
@section('title', 'Editar Documento')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.documentos.index') }}">Documentos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<h2 class="fw-bold mb-4"><i class="bi bi-file-earmark-pdf me-2 text-danger"></i>Editar Documento</h2>

<div class="card border-0 shadow-sm" style="max-width:680px">
    <div class="card-body p-4">
        <div class="mb-3 p-3 bg-light rounded">
            <div class="text-muted small mb-1">Prova associada</div>
            <div class="fw-bold">{{ $documento->prova->nome }}</div>
            <div class="small text-muted">{{ $documento->prova->instituicao->nome }}</div>
        </div>

        <form action="{{ route('admin.documentos.update', $documento) }}" method="POST">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Nome do arquivo *</label>
                    <input type="text" name="nome_arquivo"
                           class="form-control @error('nome_arquivo') is-invalid @enderror"
                           value="{{ old('nome_arquivo', $documento->nome_arquivo) }}" required
                           placeholder="Ex: PSC_2024_Etapa1_Prova.pdf">
                    @error('nome_arquivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Tipo *</label>
                    <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                        @foreach(['prova','gabarito','resolucao','edital','outro'] as $tipo)
                            <option value="{{ $tipo }}"
                                {{ old('tipo', $documento->tipo) === $tipo ? 'selected' : '' }}>
                                {{ ucfirst($tipo) }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Link externo</label>
                    <input type="url" name="url_externa"
                           class="form-control @error('url_externa') is-invalid @enderror"
                           value="{{ old('url_externa', $documento->url_externa) }}"
                           placeholder="https://drive.google.com/...">
                    @error('url_externa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Observações</label>
                    <textarea name="observacoes" class="form-control" rows="2"
                              placeholder="Informações adicionais sobre o documento...">{{ old('observacoes', $documento->observacoes) }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="ativo" value="1"
                               id="ativo" {{ old('ativo', $documento->ativo) ? 'checked' : '' }}>
                        <label class="form-check-label" for="ativo">Documento ativo (visível)</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check me-1"></i>Salvar alterações
                </button>
                <a href="{{ route('admin.documentos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
