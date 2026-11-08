@extends('layouts.admin')
@section('title','Disciplinas — Admin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Disciplinas</li>
@endsection

@section('content')
<h2 class="fw-bold mb-4">Disciplinas e Assuntos</h2>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-bold">Nova disciplina</div>
            <div class="card-body">
                <form action="{{ route('admin.disciplinas.store') }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-8">
                        <input type="text" name="nome" class="form-control form-control-sm"
                               placeholder="Nome da disciplina" required>
                    </div>
                    <div class="col-4">
                        <input type="color" name="cor" class="form-control form-control-color form-control-sm w-100"
                               value="#6c757d" title="Cor">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-plus-circle me-1"></i>Adicionar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        @foreach($disciplinas as $d)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="rounded-circle d-inline-block"
                      style="width:1rem;height:1rem;background:{{ $d->cor ?? '#6c757d' }}"></span>
                <strong>{{ $d->nome }}</strong>
                <span class="badge bg-secondary ms-1 small">{{ $d->questoes_count }} questões</span>
                <div class="ms-auto d-flex gap-1">
                    <span class="badge {{ $d->ativa ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                        {{ $d->ativa ? 'Ativa' : 'Inativa' }}
                    </span>
                    {{-- Botão editar --}}
                    <button class="btn btn-outline-secondary btn-sm py-0 px-2"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#edit-disc-{{ $d->id }}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    {{-- Botão excluir --}}
                    @if($d->questoes_count == 0)
                    <form action="{{ route('admin.disciplinas.destroy', $d) }}" method="POST" class="m-0"
                          onsubmit="return confirm('Excluir a disciplina {{ $d->nome }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm py-0 px-2">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            {{-- Form de edição (colapsável) --}}
            <div class="collapse" id="edit-disc-{{ $d->id }}">
                <div class="card-body border-bottom py-2 px-3 bg-light">
                    <form action="{{ route('admin.disciplinas.update', $d) }}" method="POST" class="row g-2 align-items-end">
                        @csrf @method('PUT')
                        <div class="col-7">
                            <input type="text" name="nome" class="form-control form-control-sm"
                                   value="{{ $d->nome }}" required>
                        </div>
                        <div class="col-2">
                            <input type="color" name="cor" class="form-control form-control-color form-control-sm w-100"
                                   value="{{ $d->cor ?? '#6c757d' }}">
                        </div>
                        <div class="col-1">
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" name="ativa" value="1"
                                       {{ $d->ativa ? 'checked' : '' }} id="ativa-{{ $d->id }}">
                                <label class="form-check-label small" for="ativa-{{ $d->id }}">Ativa</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <button class="btn btn-primary btn-sm w-100">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-3">
                {{-- Assuntos --}}
                <div class="d-flex flex-wrap gap-1 mb-2">
                    @foreach($d->assuntos as $a)
                    <span class="badge bg-light text-dark border small d-inline-flex align-items-center gap-1">
                        {{ $a->nome }}
                        @if(($a->questoes_count ?? 0) == 0)
                        <form action="{{ route('admin.disciplinas.assuntos.destroy', $a) }}" method="POST" class="m-0"
                              onsubmit="return confirm('Excluir assunto {{ $a->nome }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-close btn-close-sm"
                                    style="font-size:.4rem" aria-label="Excluir"></button>
                        </form>
                        @endif
                    </span>
                    @endforeach
                </div>
                <form action="{{ route('admin.disciplinas.assuntos.store', $d) }}"
                      method="POST" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="nome" class="form-control form-control-sm"
                           placeholder="Novo assunto..." required>
                    <button class="btn btn-outline-success btn-sm">
                        <i class="bi bi-plus"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
        {{ $disciplinas->links() }}
    </div>
</div>
@endsection
