@extends('layouts.admin')
@section('title','Questões — Admin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Questões</li>
@endsection

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <h2 class="fw-bold mb-0">Questões</h2>
    <span class="badge bg-secondary">{{ $questoes->total() }}</span>
    <a href="{{ route('admin.questoes.create') }}" class="btn btn-primary btn-sm ms-auto">
        <i class="bi bi-plus-circle me-1"></i>Nova questão
    </a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-4">
                <select name="prova_id" class="form-select form-select-sm">
                    <option value="">Todas as provas</option>
                    @foreach($provas as $p)
                        <option value="{{ $p->id }}" {{ request('prova_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <select name="disciplina_id" class="form-select form-select-sm">
                    <option value="">Todas disciplinas</option>
                    @foreach($disciplinas as $d)
                        <option value="{{ $d->id }}" {{ request('disciplina_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos status</option>
                    <option value="completa" {{ request('status') === 'completa' ? 'selected' : '' }}>Completa</option>
                    <option value="incompleta" {{ request('status') === 'incompleta' ? 'selected' : '' }}>Incompleta</option>
                    <option value="revisao" {{ request('status') === 'revisao' ? 'selected' : '' }}>Revisão</option>
                </select>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary btn-sm w-100">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle small">
            <thead class="table-light">
                <tr>
                    <th>Nº</th>
                    <th>Prova</th>
                    <th>Disciplina</th>
                    <th>Enunciado</th>
                    <th>Status</th>
                    <th>Gabarito</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questoes as $q)
                <tr>
                    <td><span class="badge bg-primary">{{ $q->numero }}</span></td>
                    <td>
                        <span class="badge text-white"
                              style="background:{{ $q->prova->instituicao->cor_primaria }};font-size:.65rem">
                            {{ $q->prova->instituicao->sigla }}
                        </span>
                        {{ $q->prova->ano }} {{ $q->prova->etapa }}
                    </td>
                    <td>
                        @if($q->disciplina)
                            <span class="badge text-white"
                                  style="background:{{ $q->disciplina->cor ?? '#6c757d' }};font-size:.65rem">
                                {{ $q->disciplina->nome }}
                            </span>
                        @else <span class="text-muted">—</span> @endif
                    </td>
                    <td style="max-width:250px">{{ Str::limit(strip_tags($q->enunciado), 80) }}</td>
                    <td>
                        <span class="badge {{ $q->status === 'completa' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                            {{ $q->status }}
                        </span>
                    </td>
                    <td>
                        @if($q->gabaritoOficial)
                            <i class="bi bi-check-circle-fill text-success"></i>
                        @else
                            <i class="bi bi-dash text-muted"></i>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.questoes.show', $q) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.questoes.edit', $q) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.questoes.destroy', $q) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('Remover questão?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Nenhuma questão encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($questoes->hasPages())
    <div class="card-footer bg-transparent">{{ $questoes->links() }}</div>
    @endif
</div>
@endsection
