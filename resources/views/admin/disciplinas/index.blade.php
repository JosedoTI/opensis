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
                </div>
            </div>
            <div class="card-body p-3">
                {{-- Assuntos --}}
                <div class="d-flex flex-wrap gap-1 mb-2">
                    @foreach($d->assuntos as $a)
                    <span class="badge bg-light text-dark border small">{{ $a->nome }}</span>
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
