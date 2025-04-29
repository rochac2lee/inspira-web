<?php

use App\Http\Controllers\api\agenda\AgendaController;
use App\Http\Controllers\api\agenda\AutorizacaoController;
use App\Http\Controllers\api\agenda\CalendarioController;
use App\Http\Controllers\api\agenda\CanaisAtendimentoController;
use App\Http\Controllers\api\agenda\ComunicadosController;
use App\Http\Controllers\api\agenda\DocumentosController;
use App\Http\Controllers\api\agenda\EnquetesController;
use App\Http\Controllers\api\agenda\FamiliaController;
use App\Http\Controllers\api\agenda\NoticiasController;
use App\Http\Controllers\api\agenda\RegistrosController;
use App\Http\Controllers\api\agenda\ServiceController;
use App\Http\Controllers\api\agenda\TarefasController;
use Illuminate\Support\Facades\Route;



Route::post('/estudantes/responsavel',[AgendaController::class, 'estudantesResponsavel']);

Route::post('/comunicados/index',[ComunicadosController::class, 'index']);
Route::post('/familia/index',[FamiliaController::class, 'index']);
Route::post('/noticias/index',[NoticiasController::class, 'index']);
Route::post('/tarefas/index',[TarefasController::class, 'index']);
Route::post('/registros/index',[RegistrosController::class, 'index']);
Route::post('/calendario/index',[CalendarioController::class, 'index']);
Route::post('/documentos/index',[DocumentosController::class, 'index']);
Route::post('/documentos/envio',[DocumentosController::class, 'envio']);
Route::post('/autorizacoes/index',[AutorizacaoController::class, 'index']);
Route::post('/autorizacoes/envio',[AutorizacaoController::class, 'envio']);
Route::post('/enquetes/index',[EnquetesController::class, 'index']);
Route::post('/enquetes/envio',[EnquetesController::class, 'envio']);
Route::post('/canais_atendimento/index',[CanaisAtendimentoController::class, 'index']);

Route::post('/services/ultimosCadastrados',[ServiceController::class, 'ultimosCadastrados']);



/*
 * colocar filtro por aluno
 *
 * lista canais de atendimento
 * lista documentos
 *
 */
