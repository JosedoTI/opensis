@extends('layouts.app')

@section('title', 'Questão ' . $questao->numero . ' — ' . $questao->prova->nome)

@section('content')
<div class="container">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Início</a></li>
            <li class="breadcrumb-item"><a href="{{ route('questoes.index') }}">Questões</a></li>
            <li class="breadcrumb-item"><a href="{{ route('provas.show', $questao->prova) }}">{{ $questao->prova->nome }}</a></li>
            <li class="breadcrumb-item active">Questão {{ $questao->numero }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">

            {{-- 1. Questão --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent d-flex align-items-center gap-2 py-3">
                    <span class="badge bg-primary rounded-circle" style="width:2rem;height:2rem;line-height:1.5rem;font-size:.9rem">
                        {{ $questao->numero }}
                    </span>
                    <div class="d-flex gap-1 flex-wrap">
                        <span class="badge text-white"
                              style="background:{{ $questao->prova->instituicao->cor_primaria }}">
                            {{ $questao->prova->instituicao->sigla }}
                        </span>
                        <span class="badge bg-secondary">{{ $questao->prova->ano }}</span>
                        <span class="badge bg-light text-dark border">{{ $questao->prova->etapa }}</span>
                        @if($questao->disciplina)
                            <span class="badge text-white"
                                  style="background:{{ $questao->disciplina->cor ?? '#6c757d' }}">
                                {{ $questao->disciplina->nome }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="fs-6 lh-lg mb-4">{!! $questao->enunciado !!}</div>

                    @if($questao->imagem)
                        <div class="text-center mb-4">
                            <img src="{{ Storage::url($questao->imagem) }}"
                                 alt="{{ $questao->imagem_alt ?? 'Imagem da questão' }}"
                                 class="img-fluid rounded border" style="max-height:350px">
                        </div>
                    @endif

                    @if($questao->conteudo_complementar)
                        <div class="alert alert-light border mb-4">
                            {!! $questao->conteudo_complementar !!}
                        </div>
                    @endif

                    {{-- Alternativas --}}
                    <div class="d-flex flex-column gap-2">
                        @foreach($questao->alternativas as $alt)
                        <div class="d-flex align-items-start gap-3 p-3 rounded border
                            @if($questao->gabaritoOficial && $questao->gabaritoOficial->resposta_correta === $alt->letra)
                                border-success bg-success-subtle
                            @else
                                bg-white
                            @endif
                            ">
                            <span class="fw-bold text-primary" style="min-width:1.5rem">{{ $alt->letra }})</span>
                            <div class="flex-grow-1">
                                {!! $alt->texto !!}
                                @if($alt->imagem)
                                    <img src="{{ Storage::url($alt->imagem) }}"
                                         alt="{{ $alt->imagem_alt ?? '' }}"
                                         class="img-fluid mt-2 rounded" style="max-height:150px">
                                @endif
                            </div>
                            @if($questao->gabaritoOficial && $questao->gabaritoOficial->resposta_correta === $alt->letra)
                                <i class="bi bi-check-circle-fill text-success fs-5" title="Resposta correta"></i>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 2. Gabarito oficial --}}
            <div id="gabarito" class="card border-0 shadow-sm mb-4"
                 style="border-left: 4px solid #1a3a6e !important">
                <div class="card-header bg-transparent d-flex align-items-center gap-2">
                    <span class="badge-oficial px-3 py-2 fs-6">
                        <i class="bi bi-patch-check-fill me-1"></i>Gabarito Oficial
                    </span>
                </div>
                <div class="card-body">
                    @if($questao->gabaritoOficial)
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold"
                                 style="width:3rem;height:3rem;font-size:1.4rem">
                                {{ $questao->gabaritoOficial->resposta_correta }}
                            </div>
                            <div>
                                <div class="fw-semibold">Alternativa {{ $questao->gabaritoOficial->resposta_correta }}</div>
                                @if($questao->gabaritoOficial->fonte_descricao)
                                    <div class="small text-muted">
                                        Fonte: {{ $questao->gabaritoOficial->fonte_descricao }}
                                        @if($questao->gabaritoOficial->fonte_url)
                                            — <a href="{{ $questao->gabaritoOficial->fonte_url }}"
                                                 target="_blank" class="text-decoration-none">
                                                <i class="bi bi-link-45deg"></i>Link oficial
                                            </a>
                                        @endif
                                    </div>
                                @endif
                                @if($questao->gabaritoOficial->data_publicacao)
                                    <div class="small text-muted">
                                        Publicado em: {{ $questao->gabaritoOficial->data_publicacao->format('d/m/Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-muted d-flex align-items-center gap-2">
                            <i class="bi bi-dash-circle text-secondary fs-4"></i>
                            <div>
                                <div class="fw-semibold">Gabarito oficial não disponível</div>
                                <div class="small">O gabarito desta questão ainda não foi cadastrado na plataforma.</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 3. Resolução oficial --}}
            @if($questao->resolucaoOficial && $questao->resolucaoOficial->ativa)
            <div class="card border-0 shadow-sm mb-4"
                 style="border-left: 4px solid #0d6efd !important">
                <div class="card-header bg-transparent">
                    <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 fs-6">
                        <i class="bi bi-lightbulb-fill me-1"></i>Resolução Oficial
                    </span>
                </div>
                <div class="card-body">
                    <div class="lh-lg">{!! $questao->resolucaoOficial->conteudo !!}</div>
                    @if($questao->resolucaoOficial->fonte_descricao)
                        <div class="small text-muted mt-3 border-top pt-2">
                            Fonte: {{ $questao->resolucaoOficial->fonte_descricao }}
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- 4. Respostas da comunidade --}}
            <div id="respostas">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-chat-dots me-2 text-info"></i>
                    Respostas da comunidade
                    <span class="badge bg-secondary ms-1">{{ $questao->respostasComunidade->count() }}</span>
                </h5>

                <div class="alert alert-light border small mb-4">
                    <i class="bi bi-info-circle me-1"></i>
                    As respostas abaixo foram elaboradas por usuários da plataforma e
                    <strong>não representam gabaritos oficiais</strong>.
                    O gabarito oficial está identificado acima.
                </div>

                @forelse($questao->respostasComunidade as $resposta)
                <div class="card border-0 shadow-sm mb-3 resposta-card is-comunidade"
                     id="resposta-{{ $resposta->id }}">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                     style="width:2rem;height:2rem;font-size:.8rem;font-weight:700">
                                    {{ mb_strtoupper(mb_substr($resposta->autor->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="fw-semibold small">{{ $resposta->autor->name }}</span>
                                    <span class="text-muted ms-2" style="font-size:.75rem">
                                        {{ $resposta->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                @if($resposta->resposta_indicada)
                                    <span class="badge-comunidade">
                                        Indica: {{ $resposta->resposta_indicada }}
                                    </span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light text-dark border small">
                                    <i class="bi bi-hand-thumbs-up me-1"></i>{{ $resposta->votos_util }}
                                </span>
                            </div>
                        </div>
                        <div class="lh-lg small">{!! nl2br(e($resposta->conteudo)) !!}</div>

                        {{-- Ações --}}
                        <div class="d-flex gap-2 mt-3 flex-wrap">
                            @auth
                                <button class="btn btn-sm btn-outline-secondary btn-votar"
                                        data-id="{{ $resposta->id }}"
                                        data-votado="{{ in_array($resposta->id, $interacoesUsuario) ? '1' : '0' }}">
                                    <i class="bi bi-hand-thumbs-up{{ in_array($resposta->id, $interacoesUsuario) ? '-fill' : '' }} me-1"></i>
                                    Útil (<span class="votos-count">{{ $resposta->votos_util }}</span>)
                                </button>

                                @if(auth()->id() === $resposta->user_id)
                                    <a href="{{ route('respostas.edit', $resposta) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil me-1"></i>Editar
                                    </a>
                                    <form action="{{ route('respostas.destroy', $resposta) }}" method="POST"
                                          onsubmit="return confirm('Excluir esta resposta?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash me-1"></i>Excluir
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDenunciar"
                                            data-resposta-id="{{ $resposta->id }}">
                                        <i class="bi bi-flag me-1"></i>Denunciar
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Entre para interagir
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="bi bi-chat-dots fs-2 d-block mb-2"></i>
                    <p>Ainda não há respostas da comunidade para esta questão.</p>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                            Cadastre-se para ser o primeiro a responder
                        </a>
                    @endguest
                </div>
                @endforelse
            </div>

            {{-- 5. Publicar nova resposta --}}
            @auth
            <div class="card border-0 shadow-sm mt-4" id="nova-resposta">
                <div class="card-header bg-transparent fw-bold">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Publicar sua resolução
                </div>
                <div class="card-body">
                    <div class="alert alert-warning small mb-3">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Sua resposta será identificada como contribuição da comunidade,
                        <strong>não como gabarito oficial</strong>.
                    </div>
                    <form action="{{ route('respostas.store', $questao) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Resposta que você indica (opcional)
                            </label>
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach(['A','B','C','D','E'] as $letra)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                           name="resposta_indicada" id="ind_{{ $letra }}"
                                           value="{{ $letra }}"
                                           {{ old('resposta_indicada') === $letra ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="ind_{{ $letra }}">
                                        {{ $letra }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="conteudo" class="form-label fw-semibold">Sua explicação / resolução *</label>
                            <textarea id="conteudo" name="conteudo" rows="5"
                                      class="form-control @error('conteudo') is-invalid @enderror"
                                      placeholder="Explique o raciocínio para resolver esta questão. Mínimo de 20 caracteres."
                                      required>{{ old('conteudo') }}</textarea>
                            @error('conteudo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i>Publicar resposta
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm mt-4 text-center p-4 bg-light">
                <p class="mb-2 fw-semibold">Quer compartilhar sua resolução?</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Entrar
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">
                        Cadastrar-se
                    </a>
                </div>
            </div>
            @endauth

        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3 sticky-top" style="top:1rem">
                <div class="card-header bg-transparent fw-bold small">
                    <i class="bi bi-info-circle me-1"></i>Informações
                </div>
                <div class="list-group list-group-flush small">
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Prova</span>
                        <span class="fw-semibold">{{ $questao->prova->nome }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Questão</span>
                        <span class="fw-semibold">#{{ $questao->numero }}</span>
                    </div>
                    @if($questao->disciplina)
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Disciplina</span>
                        <span class="badge text-white"
                              style="background:{{ $questao->disciplina->cor ?? '#6c757d' }}">
                            {{ $questao->disciplina->nome }}
                        </span>
                    </div>
                    @endif
                    @if($questao->assunto)
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Assunto</span>
                        <span>{{ $questao->assunto->nome }}</span>
                    </div>
                    @endif
                    @if($questao->pagina_documento)
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Página (doc.)</span>
                        <span>{{ $questao->pagina_documento }}</span>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('provas.show', $questao->prova) }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-arrow-left me-1"></i>Voltar à prova
                    </a>
                </div>
            </div>

            {{-- Navegação entre questões --}}
            @php
                $anterior = \App\Models\Questao::where('prova_id', $questao->prova_id)
                    ->where('numero', '<', $questao->numero)
                    ->where('ativa', true)
                    ->orderByDesc('numero')->first();
                $proxima = \App\Models\Questao::where('prova_id', $questao->prova_id)
                    ->where('numero', '>', $questao->numero)
                    ->where('ativa', true)
                    ->orderBy('numero')->first();
            @endphp
            @if($anterior || $proxima)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-bold small">
                    <i class="bi bi-arrow-left-right me-1"></i>Navegar na prova
                </div>
                <div class="card-body d-flex gap-2">
                    @if($anterior)
                        <a href="{{ route('questoes.show', $anterior) }}"
                           class="btn btn-outline-secondary btn-sm flex-grow-1">
                            <i class="bi bi-chevron-left me-1"></i>Q{{ $anterior->numero }}
                        </a>
                    @endif
                    @if($proxima)
                        <a href="{{ route('questoes.show', $proxima) }}"
                           class="btn btn-outline-secondary btn-sm flex-grow-1">
                            Q{{ $proxima->numero }}<i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Denúncia --}}
<div class="modal fade" id="modalDenunciar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-flag me-2"></i>Denunciar resposta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDenunciar" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Motivo *</label>
                        <select name="motivo" class="form-select" required>
                            <option value="">Selecione...</option>
                            <option value="incorreta">Resposta incorreta</option>
                            <option value="ofensiva">Conteúdo ofensivo</option>
                            <option value="spam">Spam</option>
                            <option value="plagio">Plágio</option>
                            <option value="outro">Outro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Descrição (opcional)</label>
                        <textarea name="descricao" class="form-control" rows="3"
                                  placeholder="Descreva o problema em detalhes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-flag me-1"></i>Enviar denúncia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Votar em resposta (AJAX)
document.querySelectorAll('.btn-votar').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id      = this.dataset.id;
        const url     = `/respostas/${id}/votar`;
        const token   = document.querySelector('meta[name="csrf-token"]').content;

        try {
            const res  = await fetch(url, { method:'POST', headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'} });
            const data = await res.json();

            const countEl = this.querySelector('.votos-count');
            if (countEl) countEl.textContent = data.votos;

            const icon = this.querySelector('i');
            if (data.acao === 'adicionado') {
                icon.classList.replace('bi-hand-thumbs-up','bi-hand-thumbs-up-fill');
                this.dataset.votado = '1';
            } else {
                icon.classList.replace('bi-hand-thumbs-up-fill','bi-hand-thumbs-up');
                this.dataset.votado = '0';
            }
        } catch (e) {
            console.error('Erro ao votar:', e);
        }
    });
});

// Modal Denúncia
const modalDenunciar = document.getElementById('modalDenunciar');
if (modalDenunciar) {
    modalDenunciar.addEventListener('show.bs.modal', function(e) {
        const btn      = e.relatedTarget;
        const respostaId = btn.dataset.respostaId;
        document.getElementById('formDenunciar').action = `/respostas/${respostaId}/denunciar`;
    });
}
</script>
@endpush
