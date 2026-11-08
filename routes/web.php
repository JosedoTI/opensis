<?php

use App\Http\Controllers\Admin\DocumentoAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DisciplinaAdminController;
use App\Http\Controllers\Admin\InstituicaoAdminController;
use App\Http\Controllers\Admin\ModeracaoController;
use App\Http\Controllers\Admin\ProvaAdminController;
use App\Http\Controllers\Admin\QuestaoAdminController;
use App\Http\Controllers\Admin\UsuarioAdminController;
use App\Http\Controllers\DenunciaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProvaController;
use App\Http\Controllers\QuestaoController;
use App\Http\Controllers\RespostaComunidadeController;
use Illuminate\Support\Facades\Route;

// ─── Página inicial ──────────────────────────────────────────────────────
Route::get('/', [\App\Http\Controllers\HomePublicoController::class, 'index'])->name('home');

// ─── Autenticação ────────────────────────────────────────────────────────
Auth::routes();

// ─── Área pública — Provas ───────────────────────────────────────────────
Route::get('/provas', [ProvaController::class, 'index'])->name('provas.index');
Route::get('/provas/{prova}', [ProvaController::class, 'show'])->name('provas.show');

// ─── Área pública — Questões ─────────────────────────────────────────────
Route::get('/questoes', [QuestaoController::class, 'index'])->name('questoes.index');
Route::get('/questoes/{questao}', [QuestaoController::class, 'show'])->name('questoes.show');

// ─── Respostas da comunidade (requer auth) ───────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/questoes/{questao}/respostas', [RespostaComunidadeController::class, 'store'])
        ->name('respostas.store');
    Route::get('/respostas/{resposta}/editar', [RespostaComunidadeController::class, 'edit'])
        ->name('respostas.edit');
    Route::put('/respostas/{resposta}', [RespostaComunidadeController::class, 'update'])
        ->name('respostas.update');
    Route::delete('/respostas/{resposta}', [RespostaComunidadeController::class, 'destroy'])
        ->name('respostas.destroy');
    Route::post('/respostas/{resposta}/votar', [RespostaComunidadeController::class, 'votar'])
        ->name('respostas.votar');

    // Denúncias
    Route::post('/respostas/{resposta}/denunciar', [DenunciaController::class, 'store'])
        ->name('denuncias.store');

    // Perfil
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil.show');
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::post('/perfil/senha', [PerfilController::class, 'alterarSenha'])->name('perfil.senha');
});

// ─── Painel administrativo ───────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Instituições
    Route::get('instituicoes', [InstituicaoAdminController::class, 'index'])->name('instituicoes.index');
    Route::get('instituicoes/criar', [InstituicaoAdminController::class, 'create'])->name('instituicoes.create');
    Route::post('instituicoes', [InstituicaoAdminController::class, 'store'])->name('instituicoes.store');
    Route::get('instituicoes/{instituicao}/editar', [InstituicaoAdminController::class, 'edit'])->name('instituicoes.edit');
    Route::put('instituicoes/{instituicao}', [InstituicaoAdminController::class, 'update'])->name('instituicoes.update');
    Route::delete('instituicoes/{instituicao}', [InstituicaoAdminController::class, 'destroy'])->name('instituicoes.destroy');

    // Provas
    Route::get('provas', [ProvaAdminController::class, 'index'])->name('provas.index');
    Route::get('provas/criar', [ProvaAdminController::class, 'create'])->name('provas.create');
    Route::post('provas', [ProvaAdminController::class, 'store'])->name('provas.store');
    Route::get('provas/{prova}', [ProvaAdminController::class, 'show'])->name('provas.show');
    Route::get('provas/{prova}/editar', [ProvaAdminController::class, 'edit'])->name('provas.edit');
    Route::put('provas/{prova}', [ProvaAdminController::class, 'update'])->name('provas.update');
    Route::delete('provas/{prova}', [ProvaAdminController::class, 'destroy'])->name('provas.destroy');
    Route::post('provas/{prova}/documentos', [ProvaAdminController::class, 'storeDocumento'])->name('provas.documentos.store');

    // Questões
    Route::get('questoes', [QuestaoAdminController::class, 'index'])->name('questoes.index');
    Route::get('questoes/criar', [QuestaoAdminController::class, 'create'])->name('questoes.create');
    Route::post('questoes', [QuestaoAdminController::class, 'store'])->name('questoes.store');
    Route::get('questoes/{questao}', [QuestaoAdminController::class, 'show'])->name('questoes.show');
    Route::get('questoes/{questao}/editar', [QuestaoAdminController::class, 'edit'])->name('questoes.edit');
    Route::put('questoes/{questao}', [QuestaoAdminController::class, 'update'])->name('questoes.update');
    Route::delete('questoes/{questao}', [QuestaoAdminController::class, 'destroy'])->name('questoes.destroy');
    Route::post('questoes/{questao}/gabarito', [QuestaoAdminController::class, 'storeGabarito'])->name('questoes.gabarito.store');
    Route::post('questoes/{questao}/resolucao', [QuestaoAdminController::class, 'storeResolucao'])->name('questoes.resolucao.store');

    // Disciplinas e Assuntos
    Route::get('disciplinas', [DisciplinaAdminController::class, 'index'])->name('disciplinas.index');
    Route::post('disciplinas', [DisciplinaAdminController::class, 'store'])->name('disciplinas.store');
    Route::put('disciplinas/{disciplina}', [DisciplinaAdminController::class, 'update'])->name('disciplinas.update');
    Route::delete('disciplinas/{disciplina}', [DisciplinaAdminController::class, 'destroy'])->name('disciplinas.destroy');
    Route::post('disciplinas/{disciplina}/assuntos', [DisciplinaAdminController::class, 'storeAssunto'])->name('disciplinas.assuntos.store');
    Route::delete('assuntos/{assunto}', [DisciplinaAdminController::class, 'destroyAssunto'])->name('disciplinas.assuntos.destroy');

    // Usuários
    Route::get('usuarios', [UsuarioAdminController::class, 'index'])->name('usuarios.index');
    Route::get('usuarios/{usuario}', [UsuarioAdminController::class, 'show'])->name('usuarios.show');
    Route::post('usuarios/{usuario}/toggle-ativo', [UsuarioAdminController::class, 'toggleAtivo'])->name('usuarios.toggle-ativo');
    Route::post('usuarios/{usuario}/promover', [UsuarioAdminController::class, 'promoverAdmin'])->name('usuarios.promover');
    Route::post('usuarios/{usuario}/rebaixar', [UsuarioAdminController::class, 'rebaixar'])->name('usuarios.rebaixar');

    // Documentos — inventário
    Route::get('documentos', [DocumentoAdminController::class, 'index'])->name('documentos.index');
    Route::get('documentos/{documento}/editar', [DocumentoAdminController::class, 'edit'])->name('documentos.edit');
    Route::put('documentos/{documento}', [DocumentoAdminController::class, 'update'])->name('documentos.update');
    Route::post('documentos/{documento}/ativar', [DocumentoAdminController::class, 'ativar'])->name('documentos.ativar');
    Route::delete('documentos/{documento}', [DocumentoAdminController::class, 'destroy'])->name('documentos.destroy');

    // Moderação — rotas literais ANTES das rotas com parâmetro {denuncia}
    Route::get('moderacao', [ModeracaoController::class, 'index'])->name('moderacao.index');
    Route::get('moderacao/respostas/listar', [ModeracaoController::class, 'respostas'])->name('moderacao.respostas');
    Route::post('moderacao/respostas/{resposta}/toggle', [ModeracaoController::class, 'toggleOcultar'])->name('moderacao.toggle');
    Route::get('moderacao/{denuncia}', [ModeracaoController::class, 'show'])->name('moderacao.show');
    Route::post('moderacao/{denuncia}/analisar', [ModeracaoController::class, 'analisar'])->name('moderacao.analisar');
});
