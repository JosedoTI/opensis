@extends('layouts.admin')
@section('title','Provas — Admin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Provas</li>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <h2 class="fw-bold mb-0">Provas</h2>
    <a href="{{ route('admin.provas.create') }}" class="btn btn-primary btn-sm ms-auto">
        <i class="bi bi-plus-circle me-1"></i>Nova prova
    </a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form action="{{ route('admin.provas.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-sm-3">
                <select name="instituicao" class="form-select form-select-sm">
                    <option value="">Todas instituições</option>
                    @foreach($instituicoes as $inst)
                        <option value="{{ $inst->id }}" {{ request('instituicao') == $inst->id ? 'selected' : '' }}>
                            {{ $inst->sigla }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <select name="ano" class="form-select form-select-sm">
                    <option value="">Todos anos</option>
                    @foreach($anos as $a) <option {{ request('ano') == $a ? 'selected' : '' }}>{{ $a }}</option> @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <input type="text" name="etapa" class="form-control form-control-sm"
                       placeholder="Etapa" value="{{ request('etapa') }}">
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary btn-sm w-100">Filtrar</button>
            </div>
            @if(request()->hasAny(['instituicao','ano','etapa']))
                <div class="col-auto">
                    <a href="{{ route('admin.provas.index') }}" class="btn btn-link btn-sm">Limpar</a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Prova</th>
                    <th>Ano</th>
                    <th>Etapa</th>
                    <th>Questões</th>
                    <th>Gabarito</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($provas as $prova)
                <tr>
                    <td>
                        <span class="badge text-white me-1"
                              style="background:{{ $prova->instituicao->cor_primaria }}">
                            {{ $prova->instituicao->sigla }}
                        </span>
                        {{ $prova->nome }}
                    </td>
                    <td>{{ $prova->ano }}</td>
                    <td>{{ $prova->etapa }}</td>
                    <td>{{ $prova->total_questoes ?? '—' }}</td>
                    <td>
                        @if($prova->gabarito_disponivel)
                            <i class="bi bi-check-circle-fill text-success"></i>
                        @else
                            <i class="bi bi-dash text-muted"></i>
                        @endif
                    </td>
                    <td>
                        @if($prova->ativa)
                            <span class="badge bg-success-subtle text-success">Ativa</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">Inativa</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.provas.show', $prova) }}" class="btn btn-sm btn-outline-secondary" title="Ver">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.provas.edit', $prova) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.provas.destroy', $prova) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Remover esta prova?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Remover">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Nenhuma prova encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($provas->hasPages())
    <div class="card-footer bg-transparent">
        {{ $provas->links() }}
    </div>
    @endif
</div>
@endsection
