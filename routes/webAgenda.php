<?php

use App\Http\Controllers\Fr\Agenda\AutorizacaoController;
use App\Http\Controllers\Fr\Agenda\CalendarioController;
use App\Http\Controllers\Fr\Agenda\CanaisAtendimentoController;
use App\Http\Controllers\Fr\Agenda\ComunicadosController;
use App\Http\Controllers\Fr\Agenda\ConfiguracoesController;
use App\Http\Controllers\Fr\Agenda\DocumentoController;
use App\Http\Controllers\Fr\Agenda\EnqueteController;
use App\Http\Controllers\Fr\Agenda\FamiliaController;
use App\Http\Controllers\Fr\Agenda\NoticiasController;
use App\Http\Controllers\Fr\Agenda\RegistroController;
use App\Http\Controllers\Fr\Agenda\RegistrosRotinasOpetController;
use App\Http\Controllers\Fr\Agenda\RelatorioAcessoController;
use App\Http\Controllers\Fr\Agenda\TarefaController;
use App\Http\Controllers\Fr\Agenda\TarefasRotinasOpetController;
use App\Http\Middleware\Aceite;
use Illuminate\Support\Facades\Route;


Route::middleware(Aceite::class)->group(function () {

    /*
     * ESPACO DA FAMILIA
     */
    Route::get('familia/', [FamiliaController::class,'index']);
    Route::get('familia/novo', [FamiliaController::class,'novo']);
    Route::post('familia/getInstituicoesEscolas', [FamiliaController::class,'getInstituicoesEscolas']);
    Route::post('familia/getInstituicaoSelecionadas', [FamiliaController::class,'getInstituicaoSelecionadas']);
    Route::post('familia/add', [FamiliaController::class,'add']);
    Route::get('familia/excluir/{id}', [FamiliaController::class,'excluir']);
    Route::get('familia/publicar/{id}', [FamiliaController::class,'publicar']);
    Route::get('familia/editar/{id}', [FamiliaController::class,'editar']);
    Route::post('familia/editar', [FamiliaController::class,'update']);
    Route::get('familia/imagens/{id}', [FamiliaController::class,'indexImagens']);
    Route::post('familia/imagens/{id}/upload', [FamiliaController::class,'uploadImagensAjax']);
    Route::get('familia/imagens/{id}/excluir/{idImg}', [FamiliaController::class,'excluirImagem']);
    Route::post('familia/imagens/{id}/ordem', [FamiliaController::class,'ordemImagem']);
    Route::post('familia/exibir/{id}', [FamiliaController::class,'exibirComunicado']);


    /*
     * COMUNICADOS
     */
    Route::get('comunicados/', [ComunicadosController::class,'index']);
    Route::get('comunicados/novo', [ComunicadosController::class,'novo']);
    Route::post('comunicados/getEscolasTurmas', [ComunicadosController::class,'getEscolasTurmas']);
    Route::post('comunicados/getEscolasTurmasSelecionados', [ComunicadosController::class,'getEscolasTurmasSelecionados']);
    Route::post('comunicados/add', [ComunicadosController::class,'add']);
    Route::get('comunicados/excluir/{id}', [ComunicadosController::class,'excluir']);
    Route::get('comunicados/publicar/{id}', [ComunicadosController::class,'publicar']);
    Route::get('comunicados/editar/{id}', [ComunicadosController::class,'editar']);
    Route::post('comunicados/editar', [ComunicadosController::class,'update']);
    Route::get('comunicados/imagens/{id}', [ComunicadosController::class,'indexImagens']);
    Route::post('comunicados/imagens/{id}/upload', [ComunicadosController::class,'uploadImagensAjax']);
    Route::get('comunicados/imagens/{id}/excluir/{idImg}', [ComunicadosController::class,'excluirImagem']);
    Route::post('comunicados/imagens/{id}/ordem', [ComunicadosController::class,'ordemImagem']);
    Route::post('comunicados/exibir/{id}', [ComunicadosController::class,'exibirComunicado']);

    /*
     * NOTICIAS
     */
    Route::get('noticias/', [NoticiasController::class,'index']);
    Route::get('noticias/novo', [NoticiasController::class,'novo']);
    Route::post('noticias/getEscolasTurmas', [NoticiasController::class,'getEscolasTurmas']);
    Route::post('noticias/getEscolasTurmasSelecionados', [NoticiasController::class,'getEscolasTurmasSelecionados']);
    Route::post('noticias/add', [NoticiasController::class,'add']);
    Route::get('noticias/excluir/{id}', [NoticiasController::class,'excluir']);
    Route::get('noticias/publicar/{id}', [NoticiasController::class,'publicar']);
    Route::get('noticias/editar/{id}', [NoticiasController::class,'editar']);
    Route::post('noticias/editar', [NoticiasController::class,'update']);
    Route::get('noticias/imagens/{id}', [NoticiasController::class,'indexImagens']);
    Route::post('noticias/imagens/{id}/upload', [NoticiasController::class,'uploadImagensAjax']);
    Route::get('noticias/imagens/{id}/excluir/{idImg}', [NoticiasController::class,'excluirImagem']);
    Route::post('noticias/imagens/{id}/ordem', [NoticiasController::class,'ordemImagem']);
    Route::post('noticias/exibir/{id}', [NoticiasController::class,'exibirNoticia']);

    /*
     * Registros
     */
    Route::get('registros/', [RegistroController::class,'index']);
    Route::get('registros/buscar', [RegistroController::class,'buscar']);
    Route::post('registros/salvar', [RegistroController::class,'salvar']);
    Route::post('registros/getTurmaProf', [RegistroController::class,'getTurmaProf']);
    Route::post('registros/getProf', [RegistroController::class,'getProf']);
    Route::get('registros/teste', [RegistroController::class,'teste']);
    /*
     * TAREFAS
     */
    Route::get('tarefas/', [TarefaController::class,'index']);
    Route::get('tarefas/nova', [TarefaController::class,'novo']);
    Route::post('tarefas/add', [TarefaController::class,'add']);
    Route::get('tarefas/excluir/{id}', [TarefaController::class,'excluir']);
    Route::get('tarefas/publicar/{id}', [TarefaController::class,'publicar']);
    Route::get('tarefas/editar/{id}', [TarefaController::class,'editar']);
    Route::post('tarefas/editar', [TarefaController::class,'update']);
    Route::get('tarefas/arquivo/donwload/{id}', [TarefaController::class,'downloadArquivo']);
    Route::post('tarefas/exibir/{id}', [TarefaController::class,'exibirTarefa']);
    Route::post('tarefas/getTurmas', [TarefaController::class,'getTurmas']);
    Route::post('tarefas/getTurmasSelecionadas', [TarefaController::class,'getTurmasSelecionadas']);



/*
 * Calendario
 */
    Route::get('calendario/', [CalendarioController::class,'index']);
    Route::post('calendario/add', [CalendarioController::class,'add']);
    Route::post('calendario/lista', [CalendarioController::class,'lista']);
    Route::post('calendario/get', [CalendarioController::class,'get']);
    Route::post('calendario/editar', [CalendarioController::class,'update']);
    Route::get('calendario/excluir/{id}', [CalendarioController::class,'excluir']);
    Route::post('calendario/setNovaData', [CalendarioController::class,'setNovaData']);

    /*
     * Documentos
     */
    Route::get('documentos/', [DocumentoController::class,'index']);
    Route::get('documentos/novo', [DocumentoController::class,'novo']);
    Route::post('documentos/add', [DocumentoController::class,'add']);
    Route::get('documentos/excluir/{id}', [DocumentoController::class,'excluir']);
    Route::get('documentos/publicar/{id}', [DocumentoController::class,'publicar']);
    Route::get('documentos/editar/{id}', [DocumentoController::class,'editar']);
    Route::post('documentos/editar', [DocumentoController::class,'update']);
    Route::get('documentos/arquivo/donwload/{id}', [DocumentoController::class,'downloadArquivo']);
    Route::post('documentos/exibir/{id}', [DocumentoController::class,'exibirTarefa']);
    Route::post('documentos/getTurmas', [DocumentoController::class,'getTurmas']);
    Route::post('documentos/getTurmasSelecionadas', [DocumentoController::class,'getTurmasSelecionadas']);

    Route::get('documentos/recebidos/{id}', [DocumentoController::class,'recebidos']);
    Route::post('documentos/getRecebidos', [DocumentoController::class,'getRecebidos']);
    Route::get('documentos/recebidos/download/{id}', [DocumentoController::class,'downloadRecebidos']);


    Route::get('documentos/teste', [DocumentoController::class,'teste']);
    /*
     * Autorizacoes
     */
    Route::get('autorizacoes/', [AutorizacaoController::class,'index']);
    Route::get('autorizacoes/novo', [AutorizacaoController::class,'novo']);
    Route::post('autorizacoes/add', [AutorizacaoController::class,'add']);
    Route::get('autorizacoes/excluir/{id}', [AutorizacaoController::class,'excluir']);
    Route::get('autorizacoes/publicar/{id}', [AutorizacaoController::class,'publicar']);
    Route::get('autorizacoes/editar/{id}', [AutorizacaoController::class,'editar']);
    Route::post('autorizacoes/editar', [AutorizacaoController::class,'update']);
    Route::post('autorizacoes/exibir/{id}', [AutorizacaoController::class,'exibirTarefa']);
    Route::post('autorizacoes/getTurmas', [AutorizacaoController::class,'getTurmas']);
    Route::post('autorizacoes/getTurmasSelecionadas', [AutorizacaoController::class,'getTurmasSelecionadas']);
    Route::get('autorizacoes/respondidos/{id}', [AutorizacaoController::class,'respondidos']);




    /*
     * Enquetes
     */
    Route::get('enquetes/', [EnqueteController::class,'index']);
    Route::get('enquetes/novo', [EnqueteController::class,'novo']);
    Route::post('enquetes/add', [EnqueteController::class,'add']);
    Route::get('enquetes/excluir/{id}', [EnqueteController::class,'excluir']);
    Route::get('enquetes/publicar/{id}', [EnqueteController::class,'publicar']);
    Route::get('enquetes/editar/{id}', [EnqueteController::class,'editar']);
    Route::post('enquetes/editar', [EnqueteController::class,'update']);
    Route::post('enquetes/exibir/{id}', [EnqueteController::class,'exibirEnquete']);
    Route::post('enquetes/getTurmasSelecionadas', [EnqueteController::class,'getTurmasSelecionadas']);

    Route::get('enquetes/respondidos/{id}', [EnqueteController::class,'respondidos']);
    Route::get('enquetes/resultado/{id}', [EnqueteController::class,'resultado']);

    /*
     * Canais de atendimento
     */
    Route::get('canais-atendimento/', [CanaisAtendimentoController::class,'index']);
    Route::get('canais-atendimento/novo', [CanaisAtendimentoController::class,'novo']);
    Route::post('canais-atendimento/add', [CanaisAtendimentoController::class,'add']);
    Route::get('canais-atendimento/excluir/{id}', [CanaisAtendimentoController::class,'excluir']);
    Route::get('canais-atendimento/publicar/{id}/{tipo}', [CanaisAtendimentoController::class,'publicar']);
    Route::get('canais-atendimento/editar/{id}', [CanaisAtendimentoController::class,'editar']);
    Route::post('canais-atendimento/editar', [CanaisAtendimentoController::class,'update']);
    Route::post('canais-atendimento/getEscolas', [CanaisAtendimentoController::class,'getEscolas']);
    Route::post('canais-atendimento/getEscolasSelecionados', [CanaisAtendimentoController::class,'getEscolasSelecionados']);
    Route::post('canais-atendimento/ordem', [CanaisAtendimentoController::class,'ordenar']);

    /*
     * Tarefas e atividades OPET
     */
    Route::get('registros/rotinas/opet/', [RegistrosRotinasOpetController::class,'index']);
    Route::get('registros/rotinas/opet/novo', [RegistrosRotinasOpetController::class,'novo']);
    Route::post('registros/rotinas/opet/add', [RegistrosRotinasOpetController::class,'add']);
    Route::get('registros/rotinas/opet/editar/{id}', [RegistrosRotinasOpetController::class,'editar']);
    Route::post('registros/rotinas/opet/editar', [RegistrosRotinasOpetController::class,'update']);
    Route::get('registros/rotinas/opet/excluir/{id}', [RegistrosRotinasOpetController::class,'excluir']);
    Route::post('registros/rotinas/opet/ordem', [RegistrosRotinasOpetController::class,'ordenar']);
    /*
     * Configurações
     */
    Route::get('configuracoes/', [ConfiguracoesController::class,'index']);
    ///estilo
    Route::get('configuracoes/estilo/editar', [ConfiguracoesController::class,'estiloIndex']);
    Route::post('configuracoes/estilo/editar', [ConfiguracoesController::class,'estiloEditar']);
    Route::get('configuracoes/estilo/limpar', [ConfiguracoesController::class,'estiloLimpar']);
    /// rótulos do calendário
    Route::get('configuracoes/etiquetas/editar', [ConfiguracoesController::class,'rotulosIndex']);
    Route::post('configuracoes/etiquetas/editar', [ConfiguracoesController::class,'rotulosEditar']);
    Route::get('configuracoes/etiquetas/excluir', [ConfiguracoesController::class,'rotulosExcluir']);
    /// rotinas tarefas e atividades
    Route::get('configuracoes/registros/rotinas/editar', [ConfiguracoesController::class,'rotinasIndex']);
    Route::get('configuracoes/registros/rotinas/editar/form/{id}', [ConfiguracoesController::class,'rotinasEditar']);
    Route::post('configuracoes/registros/rotinas/editar/form', [ConfiguracoesController::class,'rotinasUpdate']);
    Route::post('configuracoes/registros/rotinas/ordem', [ConfiguracoesController::class,'rotinasOrdenar']);
    Route::post('configuracoes/registros/rotinas/ativar', [ConfiguracoesController::class,'rotinasAtivar']);
    Route::post('configuracoes/registros/rotinas/getTurmas', [ConfiguracoesController::class,'rotinasGetTurmas']);
    Route::post('configuracoes/registros/rotinas/addTurmas', [ConfiguracoesController::class,'rotinasAddTurmas']);
    Route::get('configuracoes/registros/rotinas/removerTurma/{id}', [ConfiguracoesController::class,'rotinasRemoverTurma']);

    /*
     * Relatórios
     */
    /// acesso
    Route::get('relatorio/acesso', [RelatorioAcessoController::class,'index']);
});
