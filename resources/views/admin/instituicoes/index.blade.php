@extends('layouts.admin')
@section('title','Instituições — Admin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Instituições</li>
@endsection

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <h2 class="fw-bold mb-0">Instituições</h2>
    <a href="{{ route('admin.instituicoes.create') }}" class="btn btn-primary btn-sm ms-auto">
        <i class="bi bi-plus-circle me-1"></i>Nova instituição
    </a>
</div>

<div class="row g-3">
    @foreach($instituicoes as $inst)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100"
             style="border-left: 4px solid {{ $inst->cor_primaria }} !important">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                         style="width:45px;height:45px;background:{{ $inst->cor_primaria }}">
                        {{ $inst->sigla }}
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">{{ $inst->nome }}</h6>
                        <small class="text-muted">{{ $inst->nome_processo }} — {{ $inst->descricao_processo }}</small>
                    </div>
                </div>
                <div class="small text-muted mb-3">
                    <i class="bi bi-collection me-1"></i>{{ $inst->provas_count }} provas cadastradas
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.instituicoes.edit', $inst) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Editar
                    </a>
                    <a href="{{ $inst->site_oficial }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-link-45deg me-1"></i>Site oficial
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
