@extends('layouts.admin')
@section('title','Respostas da Comunidade — Admin')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.moderacao.index') }}">Moderação</a></li>
    <li class="breadcrumb-item active">Respostas</li>
@endsection

@section('content')
<h2 class="fw-bold mb-4">Respostas da comunidade</h2>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle small">
            <thead class="table-light">
                <tr>
                    <th>Autor</th>
                    <th>Questão</th>
                    <th>Conteúdo</th>
                    <th>Votos</th>
                    <th>Visível</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($respostas as $r)
                <tr class="{{ $r->oculta ? 'table-secondary' : '' }}">
                    <td>{{ $r->autor->name ?? '—' }}</td>
                    <td>Q#{{ $r->questao->numero ?? '?' }} — {{ Str::limit($r->questao->prova->nome ?? '', 30) }}</td>
                    <td>{{ Str::limit($r->conteudo, 80) }}</td>
                    <td>{{ $r->votos_util }}</td>
                    <td>
                        @if($r->oculta)
                            <span class="badge bg-danger-subtle text-danger">Oculta</span>
                        @else
                            <span class="badge bg-success-subtle text-success">Visível</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <form action="{{ route('admin.moderacao.toggle', $r) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm {{ $r->oculta ? 'btn-outline-success' : 'btn-outline-warning' }}">
                                <i class="bi bi-{{ $r->oculta ? 'eye' : 'eye-slash' }}"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Nenhuma resposta.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($respostas->hasPages())
    <div class="card-footer bg-transparent">{{ $respostas->links() }}</div>
    @endif
</div>
@endsection
