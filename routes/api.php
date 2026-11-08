<?php

use App\Http\Controllers\Api\V1\InstituicaoApiController;
use App\Http\Controllers\Api\V1\ProvaApiController;
use App\Http\Controllers\Api\V1\QuestaoApiController;
use App\Http\Controllers\Api\V1\RespostaApiController;
use Illuminate\Support\Facades\Route;

/*
|----------------------------------------------------------------------
| API Routes — versão 1
| Prefixo: /api/v1
| Autenticação: session (cookies compartilhados com web)
|----------------------------------------------------------------------
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    // ─── Endpoints públicos ──────────────────────────────────────────
    Route::middleware('throttle:120,1')->group(function () {
        Route::get('instituicoes',                   [InstituicaoApiController::class, 'index']);
        Route::get('instituicoes/{instituicao}',      [InstituicaoApiController::class, 'show']);

        Route::get('provas',                          [ProvaApiController::class, 'index']);
        Route::get('provas/{prova}',                  [ProvaApiController::class, 'show']);

        Route::get('questoes',                        [QuestaoApiController::class, 'index']);
        Route::get('questoes/{questao}',              [QuestaoApiController::class, 'show']);
        Route::get('questoes/{questao}/alternativas', [QuestaoApiController::class, 'alternativas']);
        Route::get('questoes/{questao}/gabarito',     [QuestaoApiController::class, 'gabarito']);
        Route::get('questoes/{questao}/resolucao',    [QuestaoApiController::class, 'resolucao']);
        Route::get('questoes/{questao}/respostas',    [RespostaApiController::class, 'index']);
    });

    // ─── Endpoints autenticados ──────────────────────────────────────
    Route::middleware(['auth', 'throttle:60,1'])->group(function () {
        Route::post('questoes/{questao}/respostas',   [RespostaApiController::class, 'store']);
        Route::put('respostas/{resposta}',             [RespostaApiController::class, 'update']);
        Route::delete('respostas/{resposta}',          [RespostaApiController::class, 'destroy']);
        Route::post('respostas/{resposta}/votar',      [RespostaApiController::class, 'votar']);
    });
});
