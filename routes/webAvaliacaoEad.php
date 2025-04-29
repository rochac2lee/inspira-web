<?php

use App\Http\Controllers\Fr\AvaliacaoEAD\AvaliacaoController;
use App\Http\Controllers\Fr\AvaliacaoEAD\GestaoAvaliacaoController;
use App\Http\Controllers\Fr\AvaliacaoEAD\GestaoQuestaoController;
use App\Http\Middleware\Aceite;
use Illuminate\Support\Facades\Route;


Route::middleware(Aceite::class)->group(function () {
    Route::get('/gestao/questao', [GestaoQuestaoController::class, 'minhasQuestoes']);
    Route::get('/gestao/questao/duplicar/{id}', [GestaoQuestaoController::class, 'duplicarQuestoes']);
    Route::get('/gestao/questao/nova', [GestaoQuestaoController::class, 'formQuestoes']);
    Route::post('/gestao/questao/nova', [GestaoQuestaoController::class, 'addQuestoes']);
    Route::get('/gestao/questao/editar/{id}', [GestaoQuestaoController::class, 'formQuestoesEditar']);
    Route::post('/gestao/questao/editar/{id}', [GestaoQuestaoController::class, 'updateQuestoes']);
    Route::get('/gestao/questao/excluir/{id}', [GestaoQuestaoController::class, 'excluirQuestoes']);
    Route::get('/gestao/questao/ver/{id}', [GestaoQuestaoController::class, 'verQuestao']);
    Route::get('/gestao/questao/importa', [GestaoQuestaoController::class, 'formImportaQuestao']);
    Route::post('/gestao/questao/importa', [GestaoQuestaoController::class, 'importaQuestao']);
    Route::get('/gestao/questao/download/{id}', [GestaoQuestaoController::class, 'download']);
    Route::get('/gestao/questao/detalhes/{id}', [GestaoQuestaoController::class, 'detalhes']);

    Route::get('/gestao/avaliacao/', [GestaoAvaliacaoController::class, 'minhasAvaliacoes']);
    Route::get('/gestao/avaliacao/nova', [GestaoAvaliacaoController::class, 'formAvaliacao']);
    Route::post('/gestao/avaliacao/nova', [GestaoAvaliacaoController::class, 'addAvaliacao']);
    Route::get('/gestao/avaliacao/excluir/{id}', [GestaoAvaliacaoController::class, 'excluirAvaliacao']);
    Route::get('/gestao/avaliacao/cancelar/{id}', [GestaoAvaliacaoController::class, 'cancelarAvaliacao']);
    Route::post('/gestao/avaliacao/permissoes/', [GestaoAvaliacaoController::class, 'permissoes']);
    Route::get('/gestao/avaliacao/getQuestaoAjax', [GestaoAvaliacaoController::class, 'getQuestaoAjax']);
    Route::post('/gestao/avaliacao/getQuestaoSelecionadas', [GestaoAvaliacaoController::class, 'getQuestaoSelecionadasAjax']);
    Route::get('/gestao/avaliacao/editar/{id}', [GestaoAvaliacaoController::class, 'formAvaliacaoEditar']);
    Route::post('/gestao/avaliacao/editar/{id}', [GestaoAvaliacaoController::class, 'updateAvaliacao']);
    Route::post('/gestao/avaliacao/publicar', [GestaoAvaliacaoController::class, 'publicar']);
    Route::get('/gestao/avaliacao/duplicar/{id}', [GestaoAvaliacaoController::class, 'duplicar']);
    Route::get('/gestao/avaliacao/minhas_questoes/ver/{id}', [GestaoQuestaoController::class, 'verQuestao']);

    Route::get('/gestao/avaliacao/revisao/{id}', [GestaoAvaliacaoController::class, 'revisao']);
    Route::get('/gestao/avaliacao/revisao/questao/editar/{idAvaliacao}/{idQuestao}', [GestaoAvaliacaoController::class, 'revisaoEditarQuestao']);
    Route::post('/gestao/avaliacao/revisao/questao/editar/{idAvaliacao}/{idQuestao}', [GestaoAvaliacaoController::class, 'revisaoUpdateQuestoes']);

    /// avaliacao para alunos
    Route::get('/avaliacao/', [AvaliacaoController::class, 'index']);
    Route::get('/avaliacao/exibir', [AvaliacaoController::class, 'avaliar']);
    Route::post('/avaliacao/logGeral', [AvaliacaoController::class, 'logGeral']);
    Route::post('/avaliacao/logAtividade', [AvaliacaoController::class, 'logAtividade']);
    Route::post('/avaliacao/finalizar', [AvaliacaoController::class, 'finalizar']);
    Route::get('/avaliacao/resultado/{idAvalicao}', [AvaliacaoController::class, 'resultado']);
});
