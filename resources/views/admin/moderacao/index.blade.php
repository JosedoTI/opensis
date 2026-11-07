@extends('layouts.admin')
@section('title','Moderação — Admin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Moderação</li>
@endsection

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <h2 class="fw-bold mb-0">Denúncias</h2>
    <div class="ms-auto d-flex gap-2">
        <a href="?status=pendente" class="btn btn-sm {{ request('status','pendente') === 'pendente' ? 'btn-danger' : 'btn-outline-danger' }}">
            Pendentes
        </a>
        <a href="?status=analisada" class="btn btn-sm {{ request('status') === 'analisada' ? 'btn-secondary' : 'btn-outline-secondary' }}">
            Analisadas
        </a>
        <a href="{{ route('admin.moderacao.respostas') }}" class="btn btn-sm btn-outline-primary">
            Todas as respostas
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="list-group list-group-flush">
        @forelse($denuncias as $d)
        <a href="{{ route('admin.moderacao.show', $d) }}"
           class="list-group-item list-group-item-action py-3 px-4">
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-warning text-dark">{{ $d->motivo }}</span>
                <div class="flex-grow-1 min-width-0">
                    <div class="small fw-semibold">
                        Denunciante: {{ $d->denunciante->name }}
                    </div>
                    <div class="small text-muted text-truncate" style="max-width:500px">
                        Resposta de: {{ $d->resposta->autor->name ?? '—' }}
                        — Q#{{ $d->resposta->questao->numero ?? '?' }}
                        {{ $d->resposta->questao->prova->nome ?? '' }}
                    </div>
                </div>
                <div class="text-end flex-shrink-0">
                    <span class="badge {{ $d->status === 'pendente' ? 'bg-danger' : 'bg-secondary' }}">
                        {{ $d->status }}
                    </span>
                    <div class="small text-muted mt-1">{{ $d->created_at->diffForHumans() }}</div>
                </div>
            </div>
        </a>
        @empty
        <div class="p-5 text-center text-muted">
            <i class="bi bi-shield-check text-success fs-1 d-block mb-2"></i>
            Nenhuma denúncia {{ request('status','pendente') === 'pendente' ? 'pendente' : '' }} encontrada.
        </div>
        @endforelse
    </div>
    @if($denuncias->hasPages())
    <div class="card-footer bg-transparent">{{ $denuncias->links() }}</div>
    @endif
</div>
@endsection
