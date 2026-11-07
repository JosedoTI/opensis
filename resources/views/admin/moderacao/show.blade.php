@extends('layouts.admin')
@section('title','Analisar Denúncia')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.moderacao.index') }}">Moderação</a></li>
    <li class="breadcrumb-item active">Denúncia #{{ $denuncia->id }}</li>
@endsection

@section('content')
<h2 class="fw-bold mb-4">Denúncia #{{ $denuncia->id }}</h2>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-bold">Resposta denunciada</div>
            <div class="card-body">
                <div class="d-flex gap-2 mb-2 small text-muted">
                    <span>Autor: <strong>{{ $denuncia->resposta->autor->name }}</strong></span>
                    <span>· Q#{{ $denuncia->resposta->questao->numero }}</span>
                    <span>· {{ $denuncia->resposta->questao->prova->nome }}</span>
                </div>
                <div class="lh-lg bg-light p-3 rounded">{!! nl2br(e($denuncia->resposta->conteudo)) !!}</div>
                <div class="mt-2 small text-muted">
                    Publicada em {{ $denuncia->resposta->created_at->format('d/m/Y H:i') }}
                    · {{ $denuncia->resposta->votos_util }} votos úteis
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-bold">Denúncia</div>
            <div class="list-group list-group-flush small">
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Denunciante</span>
                    <span>{{ $denuncia->denunciante->name }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Motivo</span>
                    <span class="badge bg-warning text-dark">{{ $denuncia->motivo }}</span>
                </div>
                @if($denuncia->descricao)
                <div class="list-group-item">
                    <span class="text-muted">Descrição:</span> {{ $denuncia->descricao }}
                </div>
                @endif
                <div class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Status</span>
                    <span class="badge {{ $denuncia->status === 'pendente' ? 'bg-danger' : 'bg-secondary' }}">
                        {{ $denuncia->status }}
                    </span>
                </div>
            </div>
        </div>

        @if($denuncia->status === 'pendente')
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-bold">Analisar</div>
            <div class="card-body">
                <form action="{{ route('admin.moderacao.analisar', $denuncia) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ação *</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="acao" value="ocultar" id="ocultar" required>
                                <label class="form-check-label text-danger" for="ocultar">Ocultar resposta</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="acao" value="manter" id="manter">
                                <label class="form-check-label text-success" for="manter">Manter resposta</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="acao" value="encerrar" id="encerrar">
                                <label class="form-check-label text-secondary" for="encerrar">Encerrar sem ação</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Parecer (opcional)</label>
                        <textarea name="parecer_admin" class="form-control" rows="3"
                                  placeholder="Justificativa da decisão..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-1"></i>Confirmar análise
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
