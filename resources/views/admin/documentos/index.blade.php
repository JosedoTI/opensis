@extends('layouts.admin')
@section('title', 'Inventário de Documentos')
@section('breadcrumb')
    <li class="breadcrumb-item active">Documentos</li>
@endsection

@section('content')
<h2 class="fw-bold mb-4"><i class="bi bi-folder2-open me-2"></i>Inventário de Documentos</h2>

{{-- Estatísticas --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold fs-4 text-primary">{{ $stats['total_provas'] }}</div>
            <div class="text-muted small">Provas</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold fs-4 text-success">{{ $stats['provas_com_documento'] }}</div>
            <div class="text-muted small">Com documento</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold fs-4 text-danger">{{ $stats['provas_sem_documento'] }}</div>
            <div class="text-muted small">Sem documento</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold fs-4 text-info">{{ $stats['total_documentos'] }}</div>
            <div class="text-muted small">Documentos</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold fs-4">{{ $stats['documentos_externos'] }}</div>
            <div class="text-muted small">Links externos</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold fs-4">{{ $stats['documentos_locais'] }}</div>
            <div class="text-muted small">Arquivos locais</div>
        </div>
    </div>
</div>

{{-- Fontes oficiais --}}
<div class="alert alert-info d-flex gap-3 align-items-start mb-4">
    <i class="bi bi-info-circle-fill fs-5 mt-1 flex-shrink-0"></i>
    <div>
        <strong>Fontes oficiais dos documentos:</strong><br>
        <a href="https://drive.google.com/drive/folders/1BWvhgtRPzvSJ-6kkGuZohAzrEzfNVdQg"
           target="_blank" class="me-3">
            <i class="bi bi-google me-1"></i>PSC/UFAM — Google Drive
        </a>
        <a href="https://drive.google.com/drive/folders/1zj3y07rGTJli6nVw_EEpRntV6DSXewlW"
           target="_blank">
            <i class="bi bi-google me-1"></i>SIS/UEA — Google Drive
        </a>
        <div class="small text-muted mt-1">
            Para cadastrar um documento: acesse a pasta, copie o link do arquivo e use
            <strong>Provas → selecione a prova → Adicionar documento</strong>.
        </div>
    </div>
</div>

{{-- Filtros --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-4">
                <label class="form-label small fw-semibold">Instituição</label>
                <select name="instituicao" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    @foreach($instituicoes as $inst)
                        <option value="{{ $inst->id }}" {{ request('instituicao') == $inst->id ? 'selected' : '' }}>
                            {{ $inst->sigla }} — {{ $inst->nome_processo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label class="form-label small fw-semibold">Ano</label>
                <select name="ano" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    @foreach($anos as $ano)
                        <option value="{{ $ano }}" {{ request('ano') == $ano ? 'selected' : '' }}>{{ $ano }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-search me-1"></i>Filtrar
                </button>
            </div>
            @if(request()->hasAny(['instituicao','ano']))
            <div class="col-sm-2">
                <a href="{{ route('admin.documentos.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                    Limpar
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="row g-4">
    {{-- Provas sem documento --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-danger-subtle d-flex justify-content-between align-items-center">
                <span class="fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Provas sem documento ({{ $provasSemDocumento->total() }})
                </span>
            </div>
            @forelse($provasSemDocumento as $prova)
            <div class="list-group-item d-flex align-items-center gap-2 py-2 px-3">
                <span class="badge text-white" style="background:{{ $prova->instituicao->cor_primaria }};font-size:.65rem">
                    {{ $prova->instituicao->sigla }}
                </span>
                <span class="small flex-grow-1">{{ $prova->nome }}</span>
                <a href="{{ route('admin.provas.show', $prova) }}"
                   class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size:.75rem">
                    <i class="bi bi-plus"></i> Doc
                </a>
            </div>
            @empty
            <div class="p-4 text-center text-success">
                <i class="bi bi-check-circle-fill fs-3 d-block mb-2"></i>
                Todas as provas possuem documentos!
            </div>
            @endforelse
            @if($provasSemDocumento->hasPages())
            <div class="p-2">{{ $provasSemDocumento->links() }}</div>
            @endif
        </div>
    </div>

    {{-- Documentos cadastrados --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success-subtle d-flex justify-content-between align-items-center">
                <span class="fw-bold text-success">
                    <i class="bi bi-check-circle me-2"></i>
                    Documentos cadastrados ({{ $documentos->total() }})
                </span>
            </div>
            @forelse($documentos as $doc)
            <div class="list-group-item py-2 px-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                    <div class="flex-grow-1 min-width-0">
                        <div class="small fw-semibold text-truncate">
                            {{ $doc->prova->nome }} — {{ ucfirst($doc->tipo) }}
                        </div>
                        <div class="text-muted" style="font-size:.7rem">
                            {{ $doc->nome_arquivo }}
                            @if($doc->total_paginas) · {{ $doc->total_paginas }} pág. @endif
                            @if($doc->url_externa)
                                · <a href="{{ $doc->url_externa }}" target="_blank" class="text-decoration-none">
                                    <i class="bi bi-link-45deg"></i>Link externo
                                </a>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('admin.provas.show', $doc->prova) }}"
                       class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size:.75rem">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('admin.documentos.edit', $doc) }}"
                       class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size:.75rem">
                        <i class="bi bi-pencil"></i>
                    </a>
                    @if(!$doc->ativo)
                    <form action="{{ route('admin.documentos.ativar', $doc) }}" method="POST" class="m-0">
                        @csrf
                        <button class="btn btn-outline-success btn-sm py-0 px-2" style="font-size:.75rem" title="Reativar">
                            <i class="bi bi-toggle-off"></i>
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.documentos.destroy', $doc) }}" method="POST" class="m-0"
                          onsubmit="return confirm('Desativar este documento?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size:.75rem" title="Desativar">
                            <i class="bi bi-toggle-on"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-4 text-center text-muted small">Nenhum documento cadastrado.</div>
            @endforelse
            @if($documentos->hasPages())
            <div class="p-2">{{ $documentos->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
