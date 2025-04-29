<?php

use App\Http\Controllers\AplicacaoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CanalProfessor\CanalProfessorController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\ConteudoController;
use App\Http\Controllers\EdulabzzBibliotecaController;
use App\Http\Controllers\EdulabzzCursoController;
use App\Http\Controllers\EdulabzzEscolaController;
use App\Http\Controllers\EdulabzzTrilhasAdminController;
use App\Http\Controllers\EdulabzzTrilhasController;
use App\Http\Controllers\EntregaveisController;
use App\Http\Controllers\GestaoRelatoriosController;
use App\Http\Controllers\Exportacao\ExportacaoAulaConteudoController;
use App\Http\Controllers\Exportacao\ExportacaoAulaController;
use App\Http\Controllers\Exportacao\ExportacaoCursoController;
use App\Http\Controllers\Fr\AlunoController;
use App\Http\Controllers\Fr\AvaliacaoController;
use App\Http\Controllers\Fr\BibliotecaController;
use App\Http\Controllers\Fr\CastController;
use App\Http\Controllers\Fr\ContatoController;
use App\Http\Controllers\Fr\EadController;
use App\Http\Controllers\Fr\EdInfantilController;
use App\Http\Controllers\Fr\EscolaController;
use App\Http\Controllers\Fr\ExibicaoGoogleController;
use App\Http\Controllers\Fr\GestaoAvaliacaoController;
use App\Http\Controllers\Fr\GestaoCastController;
use App\Http\Controllers\Fr\GestaoQuestaoController;
use App\Http\Controllers\Fr\GestaoQuizController;
use App\Http\Controllers\Fr\GestaoRoteiroConteudoController;
use App\Http\Controllers\Fr\GestaoRoteiroController;
use App\Http\Controllers\Fr\GestaoTermosController;
use App\Http\Controllers\Fr\GestaoTrilhasController;
use App\Http\Controllers\Fr\GestaoUsuarioController;

use App\Http\Controllers\Fr\ColecaoLivroController;

use App\Http\Controllers\Fr\HomeController;
use App\Http\Controllers\Fr\ImportacaoController;
use App\Http\Controllers\Fr\InstituicaoController;
use App\Http\Controllers\Fr\LeituraQrCodeController;
use App\Http\Controllers\Fr\LoginAppController;
use App\Http\Controllers\Fr\ProfessorController;
use App\Http\Controllers\Fr\QrCodeController;
use App\Http\Controllers\Fr\HubController;
use App\Http\Controllers\Fr\QuizController;
use App\Http\Controllers\Fr\QuizPublicoController;
use App\Http\Controllers\Fr\ResponsavelController;
use App\Http\Controllers\Fr\RoteiroController;
use App\Http\Controllers\Fr\SocialAuthController;
use App\Http\Controllers\Fr\TermosController;
use App\Http\Controllers\Fr\TrilhasController;
use App\Http\Controllers\Fr\TurmaController;
use App\Http\Controllers\Fr\TutorialController;
use App\Http\Controllers\Fr\UploadController;
use App\Http\Controllers\Fr\UserController;
use App\Http\Controllers\Fr\SimuladoresController;
use App\Http\Controllers\GestaoController;
use App\Http\Controllers\Importacao\ImportacaoAulaConteudoController;
use App\Http\Controllers\Importacao\ImportacaoAulaController;
use App\Http\Controllers\Importacao\ImportacaoCursoController;
use App\Http\Controllers\TopicoCursoController;
use App\Http\Middleware\Aceite;
use Illuminate\Support\Facades\Route;


////// teste de flipbook


Route::get('/teste_pdf', [HomeController::class,'teste_pdf']);


/*
	Rota para importacao dos livros digitais
	descomentar apenas quando for necessário importar os livros
*/

//Route::get('clonaPermissaoEscolaParaInstituicao', [ImportacaoController::class,'clonaPermissaoEscolaParaInstituicao']);
//Route::get('permissaoLivros', [ImportacaoController::class,'resetaPermissaoPeriodoLivro']);

//Route::get('resetarSenha/{v}', [ImportacaoController::class,'resetarSenha']);
//Route::get('disparaEmail/{v}', [ImportacaoController::class,'dispararViaMailgun']);
//Route::get('importaUsuario/{v}', [ImportacaoController::class,'usuarios']);
Route::get('hashConteudoGoogle', [ImportacaoController::class,'hashConteudoGoogle']);
//Route::get('corrigeImportaUsuario', [ImportacaoController::class,'corrigeImportaUsuario']);
//Route::get('verificaLivroPDF', [ImportacaoController::class,'verificaLivroPDF']);

//Route::get('importaConteudo/{nomeArq}', [ImportacaoController::class,'conteudo']);
//Route::get('updateConteudo/{nomeArq}', [ImportacaoController::class,'updateConteudo']);
//Route::get('permissaoLivro', [ImportacaoController::class,'adcionaLivroUsuario']);
//Route::get('permissaoLivro2', [ImportacaoController::class,'adcionaLivroUsuario2']);
//Route::get('capaVideo', [ImportacaoController::class,'capaVideo']);
//Route::get('carregaConteudo', [ImportacaoController::class,'carregaConteudo']);
//Route::get('conteudoPPT', [ImportacaoController::class,'conteudoPPT']);
//Route::get('conteudoGenerico/{arq}', [ImportacaoController::class,'conteudoGenerico']);
//Route::get('conteudoSimulacoes/{arq}', [ImportacaoController::class,'conteudoSimulacoes']);

//Route::get('carregaAudio', [ImportacaoController::class,'carregaAudio']);
//Route::get('carregaImg', [ImportacaoController::class,'carregaImg']);

///Route::get('migraPermissaoLivro', [ImportacaoController::class,'migraPermissaoLivro']);
//Route::get('migraPermissaoAudio', [ImportacaoController::class,'migraPermissaoAudio']);
//Route::get('EscolaPermissaoLivro', [ImportacaoController::class,'EscolaPermissaoLivro']);
//Route::get('ProfessorComoAluno', [ImportacaoController::class,'ProfessorComoAluno']);
//Route::get('migraPermissaoProvaPublica', [ImportacaoController::class,'migraPermissaoProvaPublica']);
//Route::get('migraPermissaoProvaParticular', [ImportacaoController::class,'migraPermissaoProvaParticular']);
//Route::get('importarBncc', [ImportacaoController::class,'importarBncc']);
//Route::get('importarBncc2', [ImportacaoController::class,'importarBncc2']);
//Route::get('permissaoColecaoLivroInstituicao', [ImportacaoController::class,'permissaoColecaoLivroInstituicao']);
//Route::get('permissaoColecaoLivroEscola', [ImportacaoController::class,'permissaoColecaoLivroEscola']);
/*
Route::get('normalizaUsuario', [ImportacaoController::class,'normalizaUsuario']);
Route::get('fulltextQuiz', [ImportacaoController::class,'fulltextQuiz']);
Route::get('qtdPaginas', [ImportacaoController::class,'qtdPaginas']);

Route::get('livroPorLinha', [ImportacaoController::class,'livroPorLinha']);
*/
Route::get('fulltextConteudo', [ImportacaoController::class,'fulltextConteudo']);
Route::get('xlsQuiz', [ImportacaoController::class,'xlsQuiz']);
Route::get('copiaQuestaoQuiz/{quiz}', [ImportacaoController::class,'copiaQuestaoQuiz']);
//Route::get('idPublicoQuiz', [ImportacaoController::class,'idPublicoQuiz']);

Route::get('fullTextQuestao', [GestaoQuestaoController::class,'filaFullText']);


Route::middleware(Aceite::class)->group(function () {

    Route::get('sistemasolar',[SimuladoresController::class,'sistemaSolar']);
    Route::get('tabelaperiodica',[SimuladoresController::class,'tabelaPeriodica']);
    /*
        Livros Digitais
    Route::get('hashConteudoGoogle', [ImportacaoController::class,'hashConteudoGoogle']);
    */
    Route::get('catalogo', [HomeController::class,'index']);
    Route::get('colecao_livro', [ColecaoLivroController::class,'index']);
    Route::get('colecao_livro/{idColecao}/livros', [ColecaoLivroController::class,'livros']);
    Route::get('colecao_livro/livro/{idLivro}', [ColecaoLivroController::class,'verLivro']);
    Route::get('colecao_livro/livro/pdfview/{idLivro}', [ColecaoLivroController::class,'verLivroPdfView']);
    Route::get('colecao_livro/download/livro/{idLivro}', [ColecaoLivroController::class,'downloadLivro']);
    Route::post('colecao_livro/colecoesLivroAjaxOption', [ColecaoLivroController::class,'ajaxListaColecoesLivroOption']);
    Route::post('colecao_livro/listaPaginas', [ColecaoLivroController::class,'listaPaginas']);


    /*
        Conteudos da Biblioteca
    */
    Route::get('editora/conteudos', [BibliotecaController::class,'conteudoEditora']);
    Route::get('editora/conteudos/colecao', [BibliotecaController::class,'colecaoConteudoEditora']);
    Route::get('editora/conteudos/download/{idConteudo}', [BibliotecaController::class,'downloadConteudoEditora']);
    Route::post('biblioteca/conteudosAjax', [BibliotecaController::class,'ajaxListaConteudosParaRoteiros']);
    Route::post('biblioteca/colecaoAjax', [BibliotecaController::class,'ajaxListaColecaoParaRoteiros']);
    Route::get('biblioteca/conteudosAjax/{id}', [BibliotecaController::class,'ajaxListaConteudosParaRoteiros']);
    Route::get('editora/conteudos/bncc', [BibliotecaController::class,'conteudoEditoraBncc']);


    /*
        Conteudos Quiz
    */

    Route::get('/gestao/quiz', [GestaoQuizController::class,'lista']);
    Route::post('/gestao/quiz/add', [GestaoQuizController::class,'add']);
    Route::get('/gestao/quiz/excluir/{id}', [GestaoQuizController::class,'excluir']);
    Route::get('/gestao/quiz/confirmar/excluir/{id}', [GestaoQuizController::class,'confirmarExcluir']);
    Route::post('/gestao/quiz/confirmar/excluir/{id}', [GestaoQuizController::class,'excluirTotal']);
    Route::post('/gestao/quiz/getAjax', [GestaoQuizController::class,'getAjax']);
    Route::post('/gestao/quiz/editar', [GestaoQuizController::class,'editar']);
    Route::get('/gestao/quiz/publicar/{id}/{tipo}', [GestaoQuizController::class,'publicar']);
    Route::get('/gestao/quiz/duplicar/{id}', [GestaoQuizController::class,'duplicar']);
    Route::post('/gestao/quiz/ordemPergunta', [GestaoQuizController::class,'ordemPergunta']);

    Route::post('/gestao/quiz/gravarAudioTemporario', [GestaoQuizController::class,'gravarAudioTemporario']);

    Route::get('/gestao/quiz/{id}/perguntas', [GestaoQuizController::class,'listaPerguntas']);
    Route::get('/gestao/quiz/pergunta/excluir/{id}', [GestaoQuizController::class,'excluirPergunta']);
    Route::get('/gestao/quiz/duplicarPergunta/{id}', [GestaoQuizController::class,'duplicarPergunta']);

    Route::get('/gestao/quiz/add_pergunta/formTipo1/{id}', [GestaoQuizController::class,'formPerguntaTipo1']);
    Route::get('/gestao/quiz/add_pergunta/editarFormTipo1/{id}/{perguntaId}', [GestaoQuizController::class,'getPerguntaTipo1']);
    Route::post('/gestao/quiz/add_pergunta/tipo1', [GestaoQuizController::class,'addPerguntaTipo1']);
    Route::post('gestao/quiz/editar_pergunta/tipo1', [GestaoQuizController::class,'editarPerguntaTipo1']);

    Route::get('/gestao/quiz/add_pergunta/formTipo2/{id}', [GestaoQuizController::class,'formPerguntaTipo2']);
    Route::get('/gestao/quiz/add_pergunta/editarFormTipo2/{id}/{perguntaId}', [GestaoQuizController::class,'getPerguntaTipo2']);
    Route::post('/gestao/quiz/add_pergunta/tipo2', [GestaoQuizController::class,'addPerguntaTipo2']);
    Route::post('gestao/quiz/editar_pergunta/tipo2', [GestaoQuizController::class,'editarPerguntaTipo2']);

    Route::get('/gestao/quiz/add_pergunta/formTipo3/{id}', [GestaoQuizController::class,'formPerguntaTipo3']);
    Route::get('/gestao/quiz/add_pergunta/editarFormTipo3/{id}/{perguntaId}', [GestaoQuizController::class,'getPerguntaTipo3']);
    Route::post('/gestao/quiz/add_pergunta/tipo3', [GestaoQuizController::class,'addPerguntaTipo3']);
    Route::post('gestao/quiz/editar_pergunta/tipo3', [GestaoQuizController::class,'editarPerguntaTipo3']);

    Route::get('/gestao/quiz/add_pergunta/formTipo4/{id}', [GestaoQuizController::class,'formPerguntaTipo4']);
    Route::get('/gestao/quiz/add_pergunta/editarFormTipo4/{id}/{perguntaId}', [GestaoQuizController::class,'formPerguntaTipo4']);
    Route::post('/gestao/quiz/add_pergunta/tipo4', [GestaoQuizController::class,'addPerguntaTipo4']);
    Route::post('/gestao/quiz/pergunta/tipo4/getAjax/', [GestaoQuizController::class,'getAjaxPerguntaTipo4']);
    Route::post('gestao/quiz/editar_pergunta/tipo4', [GestaoQuizController::class,'editarPerguntaTipo4']);
    Route::get('gestao/quiz/limparPlacar/{id}', [GestaoQuizController::class,'limparPlacar']);
    Route::get('gestao/quiz/relatorio/{id}', [GestaoQuizController::class,'relatorio']);
    Route::get('gestao/quiz/relatorio-pergunta', [GestaoQuizController::class,'relatorioPergunta']);

    Route::get('/quiz/colecao', [QuizController::class,'colecao']);
    Route::get('/quiz/exibir/', [QuizController::class,'index']);
    Route::get('/quiz/publico/{id}', [QuizPublicoController::class,'publico']);
    Route::post('/gestao/quiz/verificarCorreta', [QuizController::class,'verificaResposta']);

    Route::get('/quiz/listar/', [QuizController::class,'listar']);

    Route::get('/quiz/finalizado/', [QuizController::class,'finalizado']);


    /*
        Upload de imagens do editor Froala
    */
    Route::post('/upload/froala/', [UploadController::class,'froala']);

    /*
        Gestão de instituicao
    */
    Route::get('/gestao/instituicao', [InstituicaoController::class,'index']);
    Route::get('/gestao/instituicao/excluir/{id}', [InstituicaoController::class,'excluir']);
    Route::post('/gestao/instituicao/add/', [InstituicaoController::class,'add']);
    Route::post('/gestao/instituicao/get/', [InstituicaoController::class,'get']);
    Route::post('/gestao/instituicao/editar/', [InstituicaoController::class,'editar']);
    Route::post('/gestao/instituicao/mudaStatus/', [InstituicaoController::class,'mudaStatus']);
    Route::get('/gestao/instituicao/{idInst}/material', [InstituicaoController::class,'material']);
    /// gerenciar permissao de livros na instituicao
    Route::get('/gestao/instituicao/{idInst}/material/colecaoLivro', [InstituicaoController::class,'colecaoLivro']);
    Route::get('/gestao/instituicao/{idInst}/material/removerColecaoLivro/{idColecao}', [InstituicaoController::class,'removerColecaoLivro']);
    Route::post('/gestao/instituicao/material/addColecaoLivro', [InstituicaoController::class,'addColecaoLivro']);
    Route::post('/gestao/instituicao/material/permissaoColecaoLivro', [InstituicaoController::class,'permissaoColecaoLivro']);
    /// gerenciar permissao de áudios na instituicao
    Route::get('/gestao/instituicao/{idInst}/material/colecaoAudio', [InstituicaoController::class,'colecaoAudio']);
    Route::get('/gestao/instituicao/{idInst}/material/removerColecaoAudio/{idColecao}', [InstituicaoController::class,'removerColecaoAudio']);
    Route::post('/gestao/instituicao/material/addColecaoAudio', [InstituicaoController::class,'addColecaoAudio']);
    Route::post('/gestao/instituicao/material/permissaoColecaoAudio', [InstituicaoController::class,'permissaoColecaoAudio']);
    /// gerenciar permissao de provas na instituicao
    Route::get('/gestao/instituicao/{idInst}/material/colecaoProva', [InstituicaoController::class,'colecaoProva']);
    Route::get('/gestao/instituicao/{idInst}/material/removerColecaoProva/{idColecao}', [InstituicaoController::class,'removerColecaoProva']);
    Route::post('/gestao/instituicao/material/addColecaoProva', [InstituicaoController::class,'addColecaoProva']);
    Route::post('/gestao/instituicao/material/permissaoColecaoProva', [InstituicaoController::class,'permissaoColecaoProva']);
    /// gerenciar permissao de documentos na instituicao
    Route::get('/gestao/instituicao/{idInst}/material/colecaoDocumento', [InstituicaoController::class,'colecaoDocumento']);
    Route::get('/gestao/instituicao/{idInst}/material/removerColecaoDocumento/{idColecao}', [InstituicaoController::class,'removerColecaoDocumento']);
    Route::post('/gestao/instituicao/material/addColecaoDocumento', [InstituicaoController::class,'addColecaoDocumento']);
    Route::post('/gestao/instituicao/material/permissaoColecaoDocumento', [InstituicaoController::class,'permissaoColecaoDocumento']);
    /*
        Gestão de escola
    */
    Route::get('/gestao/escolas', [EscolaController::class,'index']);
    Route::get('/gestao/escola/excluir/{id}', [EscolaController::class,'excluir']);
    Route::post('/gestao/escola/add/', [EscolaController::class,'add']);
    Route::post('/gestao/escola/get/', [EscolaController::class,'get']);
    Route::post('/gestao/escola/editar/', [EscolaController::class,'editar']);
    Route::post('/gestao/escola/mudaStatus/', [EscolaController::class,'mudaStatus']);
    Route::get('/gestao/escola/{idEscola}/material', [EscolaController::class,'material']);
    /// gerenciar permissao de livros na escola
    Route::get('/gestao/escola/{idEscola}/material/colecaoLivro', [EscolaController::class,'colecaoLivro']);
    Route::get('/gestao/escola/{idEscola}/material/removerColecaoLivro/{idColecao}', [EscolaController::class,'removerColecaoLivro']);
    Route::post('/gestao/escola/material/addColecaoLivro', [EscolaController::class,'addColecaoLivro']);
    Route::post('/gestao/escola/material/permissaoColecaoLivro', [EscolaController::class,'permissaoColecaoLivro']);
    /// gerenciar permissao de áudios na escola
    Route::get('/gestao/escola/{idEscola}/material/colecaoAudio', [EscolaController::class,'colecaoAudio']);
    Route::get('/gestao/escola/{idEscola}/material/removerColecaoAudio/{idColecao}', [EscolaController::class,'removerColecaoAudio']);
    Route::post('/gestao/escola/material/addColecaoAudio', [EscolaController::class,'addColecaoAudio']);
    Route::post('/gestao/escola/material/permissaoColecaoAudio', [EscolaController::class,'permissaoColecaoAudio']);
    /// gerenciar permissao de provas na escola
    Route::get('/gestao/escola/{idEscola}/material/colecaoProva', [EscolaController::class,'colecaoProva']);
    Route::get('/gestao/escola/{idEscola}/material/removerColecaoProva/{idColecao}', [EscolaController::class,'removerColecaoProva']);
    Route::post('/gestao/escola/material/addColecaoProva', [EscolaController::class,'addColecaoProva']);
    Route::post('/gestao/escola/material/permissaoColecaoProva', [EscolaController::class,'permissaoColecaoProva']);
    /// gerenciar permissao de documentos na escola
    Route::get('/gestao/escola/{idEscola}/material/colecaoDocumento', [EscolaController::class,'colecaoDocumento']);
    Route::get('/gestao/escola/{idEscola}/material/removerColecaoDocumento/{idColecao}', [EscolaController::class,'removerColecaoDocumento']);
    Route::post('/gestao/escola/material/addColecaoDocumento', [EscolaController::class,'addColecaoDocumento']);
    Route::post('/gestao/escola/material/permissaoColecaoDocumento', [EscolaController::class,'permissaoColecaoDocumento']);
    /// importacao de usuarios em lote na escola
    Route::post('/gestao/escola/importacao/usuarios/lote', [EscolaController::class,'importarUsuariosEmLote']);
    Route::get('/gestao/escola/relatorio/importacao/usuarios', [EscolaController::class,'relatorioImportarUsuarios']);
    Route::get('/gestao/escola/importacao/usuarios/download/{id}', [EscolaController::class,'downloadArquivoImportaUsuario']);
    Route::get('/gestao/escola/relatorio/importacao/usuarios/detalhes/{id}', [EscolaController::class,'relatorioImportarUsuariosDetalhes']);
    /// gerenciar professores na escola
    Route::get('/gestao/escola/{idEscola}/docentes', [ProfessorController::class,'index']);
    Route::post('/gestao/escola/importacao/docentes', [ProfessorController::class,'importacao']);
    Route::get('/gestao/escola/{idEscola}/docentes/logar/{idProfessor}', [ProfessorController::class,'logar']);
    /// gerenciar alunos na escola
    Route::get('/gestao/escola/{idEscola}/alunos', [AlunoController::class,'index']);
    /// gerenciar responsaveis na escola
    Route::get('/gestao/escola/{idEscola}/responsaveis', [ResponsavelController::class,'index']);
    Route::get('/gestao/escola/{idEscola}/responsaveis/novo', [ResponsavelController::class,'novo']);
    Route::get('/gestao/escola/{idEscola}/responsaveis/editar/{idResposanvel}', [ResponsavelController::class,'get']);
    Route::post('/gestao/escola/responsaveis/add', [ResponsavelController::class,'add']);
    Route::post('/gestao/escola/responsaveis/editar', [ResponsavelController::class,'editar']);
    Route::get('/gestao/escola/{idEscola}/responsaveis/excluir/{idResposanvel}', [ResponsavelController::class,'excluir']);

    /// relatório de acessos Escola
    Route::get('/gestao/escola/{idEscola}/acessos', [EscolaController::class,'relatorioAcessos']);
    Route::get('/gestao/escola/acessos/{idEscola?}', [EscolaController::class,'relatorioAcessos']);
    Route::get('/gestao/escola/acessos/download/a', [EscolaController::class,'downloadRelatorioAcessos']);
    Route::get('/gestao/relatorios/acessos', [EscolaController::class,'relatorioAcessos']);


    //Gestão Relatorios
    Route::get('/gestao/relatorios', [GestaoRelatoriosController::class,'index']);
    Route::get('/gestao/relatorios/instituicao', [GestaoRelatoriosController::class,'instituicao']);
    Route::get('/gestao/relatorios/usuarios', [GestaoRelatoriosController::class,'usuarios']);
    Route::get('/gestao/relatorios/biblioteca', [GestaoRelatoriosController::class,'biblioteca']);
    Route::get('/gestao/relatorios/qrcode', [GestaoRelatoriosController::class,'qrcode']);

    /// gerenciar turmas
    Route::get('/gestao/escola/{idEscola}/turmas', [TurmaController::class,'index']);
    Route::get('/gestao/escola/{idEscola}/nova_turma', [TurmaController::class,'nova']);
    Route::post('/gestao/escola/turma/add', [TurmaController::class,'add']);
    Route::get('/gestao/escola/{idEscola}/editar_turma/{idTurma}', [TurmaController::class,'formEditar']);
    Route::post('/gestao/escola/turma/editar', [TurmaController::class,'editar']);
    Route::get('/gestao/escola/turma/excluir/{id}', [TurmaController::class,'excluir']);
    Route::post('/gestao/escola/turmas/getProfessoresTabela', [TurmaController::class,'getProfessoresTabela']);
    Route::post('/gestao/escola/turmas/getAlunosTabela', [TurmaController::class,'getAlunosTabela']);
    Route::post('/gestao/escola/turmas/getProfessorAluno', [TurmaController::class,'getProfessorAluno']);

    /*
    Gestão de usuarios
    */
    Route::get('/gestao/usuario', [GestaoUsuarioController::class,'index']);
    Route::post('/gestao/usuario/add', [GestaoUsuarioController::class,'add']);
    Route::get('/gestao/usuario/novaSenha/{idUser}', [GestaoUsuarioController::class,'novaSenha']);
    Route::get('/gestao/usuario/logar/{idUser}', [GestaoUsuarioController::class,'logar']);
    Route::get('/gestao/usuario/excluir/{idUser}', [GestaoUsuarioController::class,'excluir']);
    Route::post('/gestao/usuario/getEscolas/', [GestaoUsuarioController::class,'getEscolas']);
    Route::post('/gestao/usuario/get/', [GestaoUsuarioController::class,'getAjax']);
    Route::post('/gestao/usuario/editar/', [GestaoUsuarioController::class,'editar']);

    /*
    Gestão de QRCode
    */
    Route::get('/gestao/qrcode/', [QrCodeController::class,'index']);
    Route::get('/gestao/qrcode/novo', [QrCodeController::class,'novo']);
    Route::post('/gestao/qrcode/novo', [QrCodeController::class,'add']);
    Route::get('/gestao/qrcode/edita/{id}', [QrCodeController::class,'edita']);
    Route::post('/gestao/qrcode/edita/{id}', [QrCodeController::class,'edit']);
    Route::get('/gestao/qrcode/deletar/{id}', [QrCodeController::class,'deletar']);
    Route::get('/gestao/qrcode/baixar/{id}', [QrCodeController::class,'downloadImagem']);

       /*
    Hub de QRCode
    */
    Route::get('/hub/gabaritos', [HubController::class,'hub']);

    /*
    Gestão de cast
    */
    Route::get('/gestao/cast/', [GestaoCastController::class,'index']);
    /// gerenciamento de audios
    Route::post('/gestao/cast/add', [GestaoCastController::class,'add']);
    Route::get('/gestao/cast/excluir/{id}', [GestaoCastController::class,'excluir']);
    Route::post('/gestao/cast/getAjax/', [GestaoCastController::class,'getAjax']);
    Route::post('/gestao/cast/editar/', [GestaoCastController::class,'editar']);

    /// gerenciamento de álbuns
    Route::get('/gestao/cast/album/add', [GestaoCastController::class,'formAlbum']);
    Route::post('/gestao/cast/album/add', [GestaoCastController::class,'addAlbum']);
    Route::get('/gestao/cast/album/excluir/{id}', [GestaoCastController::class,'excluirAlbum']);
    Route::get('/gestao/cast/album/editar/{id}', [GestaoCastController::class,'getAlbum']);
    Route::post('/gestao/cast/album/editar/', [GestaoCastController::class,'editarAlbum']);
    Route::get('/gestao/cast/getAudiosAjax/', [GestaoCastController::class,'getAudiosAjax']);

    /// gerenciamento de playlist
    Route::get('/gestao/cast/playlist/add', [GestaoCastController::class,'formPlaylist']);
    Route::post('/gestao/cast/playlist/add', [GestaoCastController::class,'addPlaylist']);
    Route::get('/gestao/cast/playlist/excluir/{id}', [GestaoCastController::class,'excluirPlaylist']);
    Route::get('/gestao/cast/playlist/editar/{id}', [GestaoCastController::class,'getPlaylist']);
    Route::post('/gestao/cast/playlist/editar/', [GestaoCastController::class,'editarPlaylist']);

    /// duplicar objetos do cast
    Route::get('/gestao/cast/duplicar', [GestaoCastController::class,'duplicar']);
    /// publicar objetos do cast
    Route::get('/gestao/cast/publicar', [GestaoCastController::class,'publicar']);

    /// rotas para todos os usuarios
    Route::get('/cast/exibirPlayList', [CastController::class,'exibirPlayList']);
    Route::get('/cast', [CastController::class,'index']);

    /*
        Gestão de roteiros
    */
    //Route::get('/gestao/roteiros/teste', [GestaoRoteiroController::class,'teste']);
    Route::get('/gestao/roteiros/', [GestaoRoteiroController::class,'index']);
    Route::get('/gestao/roteiros/add', [GestaoRoteiroController::class,'form']);
    Route::post('/gestao/roteiros/add', [GestaoRoteiroController::class,'add']);
    Route::get('/gestao/roteiros/{id}/editar', [GestaoRoteiroController::class,'editar']);
    Route::post('/gestao/roteiros/{id}/editar', [GestaoRoteiroController::class,'update']);
    Route::get('/gestao/roteiros/excluir/{id}', [GestaoRoteiroController::class,'excluir']);
    Route::get('/gestao/roteiros/publicar/{id}', [GestaoRoteiroController::class,'publicar']);
    Route::get('/gestao/roteiros/duplicar/{id}', [GestaoRoteiroController::class,'duplicar']);

    Route::get('/gestao/roteiros/{id}/editar_conteudo', [GestaoRoteiroController::class,'editarConteudo']);
    Route::post('/gestao/roteiros/add_update_conteudos', [GestaoRoteiroController::class,'addUpdateConteudos']);
    Route::post('/gestao/roteiros/getTemaConteudoAjax', [GestaoRoteiroController::class,'getTemaConteudoAjax']);
    Route::post('/gestao/roteiros/getTemaAjax', [GestaoRoteiroController::class,'getTemaAjax']);
    Route::post('/gestao/roteiros/ordemTema', [GestaoRoteiroController::class,'ordemTema']);
    Route::get('/gestao/roteiros/excluirTema/{cursoId}/{temaId}', [GestaoRoteiroController::class,'excluirTema']);
    Route::get('/gestao/roteiros/duplicarTema/{cursoId}/{temaId}', [GestaoRoteiroController::class,'duplicarTema']);

    Route::post('/gestao/roteiros/addConteudo', [GestaoRoteiroConteudoController::class,'add']);
    Route::post('/gestao/roteiros/addConteudoBiblioteca', [GestaoRoteiroConteudoController::class,'addBiblioteca']);
    Route::post('/gestao/roteiros/ordenarConteudo', [GestaoRoteiroConteudoController::class,'ordemConteudo']);
    Route::get('/gestao/roteiros/excluirConteudo/{cursoId}/{temaId}/{conteudoId}', [GestaoRoteiroConteudoController::class,'delete']);
    Route::get('/gestao/roteiros/duplicarConteudo/{cursoId}/{temaId}/{conteudoId}', [GestaoRoteiroConteudoController::class,'duplicar']);
    Route::post('/gestao/roteiros/getConteudoAjax', [GestaoRoteiroConteudoController::class,'getConteudoAjax']);
    Route::get('/gestao/roteiros/download/{id}/{nome}', [GestaoRoteiroConteudoController::class,'download']);

    Route::get('/gestao/roteiros/iniciarRoteiro/{roteiroId}', [GestaoRoteiroController::class,'iniciarRoteiro']);
    Route::get('/gestao/roteiros/realizarRoteiro/{roteiroId}', [GestaoRoteiroController::class,'realizarRoteiro']);

    Route::get('/gestao/roteiros/getRoteiroAjax', [GestaoRoteiroController::class,'getRoteiroAjax']);
    Route::get('/gestao/roteiros/getRoteiroSelecionadosAjax', [GestaoRoteiroController::class,'getRoteiroSelecionadosAjax']);




    /// roteiro para realizar o curso
    Route::get('/roteiros/iniciarRoteiro/{roteiroId}/{trilhaId}', [RoteiroController::class,'iniciarRoteiro']);
    Route::get('/roteiros/realizarRoteiro/{roteiroId}/{trilhaId}', [RoteiroController::class,'realizarRoteiro']);
    Route::post('/roteiros/getConteudoAjax', [RoteiroController::class,'getConteudoAjax']);
    Route::post('/roteiros/salvaEntregavel', [RoteiroController::class,'salvaEntregavel']);
    Route::get('/roteiros/listaEntregavel', [RoteiroController::class,'listaEntregavel']);
    Route::get('/roteiros/downloadEntregavel', [RoteiroController::class,'downloadEntregavel']);
    Route::get('/roteiros/getDiscursiva', [RoteiroController::class,'getDiscursiva']);
    Route::post('/roteiros/salvaDiscursiva', [RoteiroController::class,'salvaDiscursiva']);
    Route::get('/roteiros/download/{id}/{nome}', [RoteiroController::class,'download']);
    /*
    Trilhas
    */
    Route::get('gestao/trilhass', [GestaoTrilhasController::class,'index']);
    Route::get('/gestao/trilhass/add', [GestaoTrilhasController::class,'form']);
    Route::post('/gestao/trilhass/add', [GestaoTrilhasController::class,'add']);
    Route::get('/gestao/trilhass/{id}/editar', [GestaoTrilhasController::class,'editar']);
    Route::post('/gestao/trilhass/{id}/editar', [GestaoTrilhasController::class,'update']);
    Route::get('/gestao/trilhass/excluir/{id}', [GestaoTrilhasController::class,'excluir']);
    Route::get('/gestao/trilhass/publicar/{id}', [GestaoTrilhasController::class,'publicar']);
    Route::get('/gestao/trilhass/duplicar/{id}', [GestaoTrilhasController::class,'duplicar']);
    Route::post('/gestao/trilhass/periodo_matricula/{id}', [GestaoTrilhasController::class,'periodoMatricula']);
    Route::get('/gestao/trilhass/get_periodo_matricula/{id}', [GestaoTrilhasController::class,'getPeriodoMatricula']);

    /// relatorios
    Route::get('/gestao/trilhass/{id}/relatorio', [GestaoTrilhasController::class,'relatorio']);
    Route::post('/gestao/trilhass/getInteracao', [GestaoTrilhasController::class,'getInteracao']);

    /// alunos
    Route::get('trilhas/listar', [TrilhasController::class,'index']);
    Route::get('trilhas/detalhes/{id}', [TrilhasController::class,'detalhes']);
    Route::get('trilhas/matricular/{id}', [TrilhasController::class,'matricular']);
    Route::get('trilhas/matriculado/{id}/roteiro', [TrilhasController::class,'listaRoteiro']);

    /// alunos EAD
    Route::get('ead/listar', [EadController::class,'index']);
    Route::get('ead/detalhes/{id}', [EadController::class,'detalhes']);
    Route::get('ead/matricular/{id}', [EadController::class,'matricular']);
    Route::get('ead/matriculado/{id}/roteiro', [EadController::class,'listaRoteiro']);

    /// EAD roteiro para realizar o curso
    Route::get('ead/roteiros/iniciarRoteiro/{roteiroId}/{trilhaId}', [EadController::class,'iniciarRoteiro']);
    Route::get('ead/roteiros/realizarRoteiro/{roteiroId}/{trilhaId}', [EadController::class,'realizarRoteiro']);
    Route::post('ead/roteiros/getConteudoAjax', [EadController::class,'getConteudoAjax']);
    Route::post('ead/roteiros/salvaEntregavel', [EadController::class,'salvaEntregavel']);
    Route::post('ead/roteiros/salvaDiscursiva', [EadController::class,'salvaDiscursiva']);
    Route::get('ead/roteiros/listaEntregavel', [EadController::class,'listaEntregavel']);
    Route::get('ead/roteiros/getDiscursiva', [EadController::class,'getDiscursiva']);

    /// EAD avaliacao
    Route::get('ead/avaliacao/exibir', [\App\Http\Controllers\Fr\AvaliacaoEAD\AvaliacaoController::class, 'avaliar']);
    Route::post('ead/avaliacao/logGeral', [\App\Http\Controllers\Fr\AvaliacaoEAD\AvaliacaoController::class, 'logGeral']);
    Route::post('ead/avaliacao/logAtividade', [\App\Http\Controllers\Fr\AvaliacaoEAD\AvaliacaoController::class, 'logAtividade']);
    Route::post('ead/avaliacao/finalizar', [\App\Http\Controllers\Fr\AvaliacaoEAD\AvaliacaoController::class, 'finalizar']);
    Route::get('ead/avaliacao/resultado/{idAvalicao}/{idTrilha}', [\App\Http\Controllers\Fr\AvaliacaoEAD\AvaliacaoController::class, 'resultado']);

    /// EAD relatorios
    Route::get('/ead/trilhas/{id}/relatorio', [EadController::class,'relatorio']);
    Route::get('/ead/certificado/{id}', [EadController::class,'certificado']);

    /*
        Educação infantil
    */

    Route::get('/infantil/', [EdInfantilController::class,'index']);
    Route::get('/infantil/colecao/{colecaoId}', [EdInfantilController::class,'colecao']);
    Route::get('/infantil/colecao/ajaxMaterial/{colecaoId}', [EdInfantilController::class,'getConteudoAjax']);
    Route::get('/infantil/colecao_professor', [EdInfantilController::class,'colecaoProfessor']);
    Route::get('/infantil/colecao/professor/{colecaoId}', [EdInfantilController::class,'materialColecaoProfessor']);

    /*
        Avaliação
    */

    Route::get('/gestao/avaliacao/minhas_questoes', [GestaoQuestaoController::class, 'minhasQuestoes']);
    Route::post('/gestao/avaliacao/minhas_questoes/mudaStatus', [GestaoQuestaoController::class, 'mudaStatusMinhasQuestoes']);
    Route::get('/gestao/avaliacao/minhas_questoes/duplicar/{id}', [GestaoQuestaoController::class, 'duplicarQuestoes']);
    Route::get('/gestao/avaliacao/minhas_questoes/nova', [GestaoQuestaoController::class, 'formQuestoes']);
    Route::post('/gestao/avaliacao/minhas_questoes/nova', [GestaoQuestaoController::class, 'addQuestoes']);
    Route::post('/gestao/avaliacao/getBnccAjaxLista', [GestaoQuestaoController::class, 'getBnccAjaxLista']);
    Route::post('/gestao/avaliacao/getBnccAjax', [GestaoQuestaoController::class, 'getBnccAjax']);
    Route::post('/gestao/avaliacao/getUnidadeTematicaAjaxLista', [GestaoQuestaoController::class, 'getUnidadeTematicaAjaxLista']);
    Route::post('/gestao/avaliacao/getUnidadeTematicaAjax', [GestaoQuestaoController::class, 'getUnidadeTematicaAjax']);
    Route::post('/gestao/avaliacao/getTemaAjaxLista', [GestaoQuestaoController::class, 'getTemaAjaxLista']);
    Route::post('/gestao/avaliacao/getTemaAjax', [GestaoQuestaoController::class, 'getTemaAjax']);
    Route::get('/gestao/avaliacao/minhas_questoes/editar/{id}', [GestaoQuestaoController::class, 'formQuestoesEditar']);
    Route::post('/gestao/avaliacao/minhas_questoes/editar/{id}', [GestaoQuestaoController::class, 'updateQuestoes']);
    Route::get('/gestao/avaliacao/minhas_questoes/excluir/{id}', [GestaoQuestaoController::class, 'excluirQuestoes']);
    Route::get('/gestao/avaliacao/minhas_questoes/ver/{id}', [GestaoQuestaoController::class, 'verQuestao']);
    Route::get('/gestao/avaliacao/minhas_questoes/publicar/{id}', [GestaoQuestaoController::class, 'publicar']);

    Route::get('/gestao/avaliacao/', [GestaoAvaliacaoController::class, 'minhasAvaliacoes']);
    Route::get('/gestao/avaliacao/nova', [GestaoAvaliacaoController::class, 'formAvaliacao']);
    Route::post('/gestao/avaliacao/nova', [GestaoAvaliacaoController::class, 'addAvaliacao']);
    Route::get('/gestao/avaliacao/excluir/{id}', [GestaoAvaliacaoController::class, 'excluirAvaliacao']);
    Route::get('/gestao/avaliacao/getQuestaoAjax', [GestaoAvaliacaoController::class, 'getQuestaoAjax']);
    Route::post('/gestao/avaliacao/getQuestaoSelecionadas', [GestaoAvaliacaoController::class, 'getQuestaoSelecionadasAjax']);
    Route::get('/gestao/avaliacao/editar/{id}', [GestaoAvaliacaoController::class, 'formAvaliacaoEditar']);
    Route::post('/gestao/avaliacao/editar/{id}', [GestaoAvaliacaoController::class, 'updateAvaliacao']);
    Route::post('/gestao/avaliacao/publicar', [GestaoAvaliacaoController::class, 'publicar']);
    Route::get('/gestao/avaliacao/duplicar/{id}', [GestaoAvaliacaoController::class, 'duplicar']);

    Route::get('/gestao/avaliacao/relatorio/online/{id}', [GestaoAvaliacaoController::class, 'relatorioAvaliacaoOnline']);
    Route::get('/gestao/avaliacao/relatorio/online/{id}/ocorrencia/{idAluno}', [GestaoAvaliacaoController::class, 'relatorioAvaliacaoOcorrenciasOnline']);
    Route::post('/gestao/avaliacao/correcaoPergunta', [GestaoAvaliacaoController::class, 'getCorrecaoPergunta']);
    Route::post('/gestao/avaliacao/correcaoPergunta/salvar', [GestaoAvaliacaoController::class, 'salvarCorrecaoPergunta']);

    Route::get('/gestao/avaliacao/relatorio/detalhes/{id}', [GestaoAvaliacaoController::class, 'detalhes']);
    Route::get('/gestao/avaliacao/relatorio/impressao/{id}', [GestaoAvaliacaoController::class, 'impressao']);

    /// avaliacao para alunos
    Route::get('/avaliacao/', [AvaliacaoController::class, 'index']);
    Route::get('/avaliacao/exibir', [AvaliacaoController::class, 'avaliar']);
    Route::post('/avaliacao/logGeral', [AvaliacaoController::class, 'logGeral']);
    Route::post('/avaliacao/logAtividade', [AvaliacaoController::class, 'logAtividade']);
    Route::post('/avaliacao/finalizar', [AvaliacaoController::class, 'finalizar']);
    Route::get('/avaliacao/resultado/{idAvalicao}', [AvaliacaoController::class, 'resultado']);


    /*
        Gestão da plataforma
    */
    Route::get('/gestao/plataforma/termos', [GestaoTermosController::class,'form']);
    Route::post('/gestao/plataforma/termos', [GestaoTermosController::class,'update']);

    // geraSenha criptografada

    Route::get('/geraSenha/{id}', [ImportacaoController::class,'geraSenha']);

    /*
        Busca Geral
    */
    Route::get('/busca_geral', [HomeController::class,'busca']);

/*
 * rotas EDULABz
 *
 */
    /// rota configuracao de usuario
    Route::get('/configuracao', [ConfiguracaoController::class,'index']);
    Route::post('/usuario/salvar', [ConfiguracaoController::class,'salvarDados'])->name('configuracao.salvar-dados');
    Route::post('/usuario/trocar-email', [ConfiguracaoController::class,'trocarEmail'])->name('configuracao.trocar-email');
    Route::post('/usuario/trocar-perfil', [ConfiguracaoController::class,'trocarFotoPerfil'])->name('configuracao.trocar-foto');
    Route::post('/usuario/trocar-senha', [ConfiguracaoController::class,'trocarSenha'])->name('configuracao.trocar-senha');

    /// rotas para Roteiros
    //Route::get('gestao/cursos',[GestaoController::class,'cursos'])->name('gestao.cursos');

    //AQUI
    Route::get('gestao/cursos', function () { return view('errors/503_roteiros'); });

    Route::get('gestao/curso/novo',[GestaoController::class,'novoCurso'])->name('gestao.novo-curso');
    Route::post('gestao/curso/novo',[GestaoController::class,'postNovoCurso'])->name('gestao.novo-cursop');
    Route::get('gestao/curso/{idCurso}',[GestaoController::class,'conteudoCurso'])->name('gestao.curso-conteudo');
    Route::post('gestao/curso/{idCurso}/salvar',[GestaoController::class,'postSalvarCurso'])->name('gestao.curso-salvar');
    Route::post('gestao/curso/{idCurso}/excluir',[GestaoController::class,'postExcluirCurso'])->name('gestao.curso-excluir');
    Route::post('gestao/curso/{idCurso}/publicar',[GestaoController::class,'postPublicarCurso'])->name('gestao.curso-publicar');
    Route::post('gestao/curso/{idCurso}/aula/nova',[GestaoController::class,'postNovaAula'])->name('gestao.curso.nova-aula');
    Route::get('gestao/curso/{idCurso}/aula/{idAula}/editar',[GestaoController::class,'editarAula'])->name('gestao.curso.aula-editar');

    Route::get('gestao/curso/{idCurso}/exportar',[ExportacaoCursoController::class,'curso'])->name('gestao.curso.exportar');
    Route::get('gestao/curso/{idCurso}/aula/{idAula}/exportar',[ExportacaoAulaController::class,'aula'])->name('gestao.curso.aula-exportar');
    Route::get('gestao/curso/{idCurso}/aula/{idAula}/conteudo/{idConteudo}/exportar',[ExportacaoAulaConteudoController::class,'aulaConteudo'])->name('gestao.curso.aula-conteudo-exportar');

    Route::post('gestao/curso/importar',[ImportacaoCursoController::class,'curso'])->name('gestao.curso.importar');
    Route::post('gestao/curso/{idCurso}/aula/{idAula}/importar',[ImportacaoAulaController::class,'aula'])->name('gestao.curso.aula-importar');
    Route::post('gestao/curso/{idCurso}/aula/{idAula}/conteudo/importar',[ImportacaoAulaConteudoController::class,'aulaConteudo'])->name('gestao.curso.aula-conteudo-importar');

    Route::get('gestao/curso/{idCurso}/aula/{idAula}/reordenar/{index}',[GestaoController::class,'reordenarAula'])->name('gestao.curso.aula-reordenar');
    Route::post('gestao/curso/{idCurso}/aula/{idAula}/reordenar/position',[GestaoController::class,'reordenarAulaV2'])->name('gestao.curso.aula-reordenar-v2'); // Método correto


    Route::post('gestao/curso/{idCurso}/aula/editar',[GestaoController::class,'postEditarAula'])->name('gestao.curso.aula-salvar');
    Route::post('gestao/curso/{idCurso}/aula/duplicar',[GestaoController::class,'postDuplicarAula'])->name('gestao.curso-aula-duplicar');
    Route::post('gestao/curso/{idCurso}/aula/excluir',[GestaoController::class,'postExcluirAula'])->name('gestao.curso-aula-excluir');

    Route::get('gestao/curso/{idCurso}/aula/{idAula}/conteudos',[GestaoController::class,'aulaConteudos'])->name('gestao.curso.aula-conteudos');
    Route::post('gestao/curso/{idCurso}/aula/conteudos/novo',[GestaoController::class,'postNovoConteudoCurso'])->name('gestao.curso.aula-conteudos-novo');
    Route::post('gestao/curso/{idCurso}/aula/conteudos/selecionados',[GestaoController::class,'postAulaSelecaoConteudos'])->name('gestao.curso.aula-selecao-conteudos');
    Route::get('gestao/curso/{idCurso}/aula/{idAula}/conteudos/{idConteudo}/editar',[GestaoController::class,'editarConteudoCurso'])->name('gestao.curso.aula-conteudos-editar');
    Route::post('gestao/curso/{idCurso}/aula/conteudos/salvar',[GestaoController::class,'postSalvarConteudoCurso'])->name('gestao.curso.aula-conteudos-salvar');

    Route::get('gestao/curso/{idCurso}/aula/{idAula}/conteudo/{idConteudo}/reEscolaordenar/{index}',[GestaoController::class,'reordenarConteudo'])->name('gestao.curso.conteudo-reordenar');
    Route::post('gestao/curso/{idCurso}/aula/{idAula}/conteudo/{idConteudo}/reEscolaordenar/position',[GestaoController::class,'reordenarConteudoV2'])->name('gestao.curso.conteudo-reordenar-v2'); // Método correto

    Route::post('gestao/curso/{idCurso}/conteudo/duplicar',[GestaoController::class,'postDuplicarConteudoCurso'])->name('gestao.curso-conteudo-duplicar');
    Route::post('gestao/curso/{idCurso}/conteudo/excluir',[GestaoController::class,'postExcluirConteudoCurso'])->name('gestao.curso-conteudo-excluir');

    Route::get('curso/{idCurso}',[EdulabzzCursoController::class,'index'])->name('curso');
    Route::get('curso/{idCurso}/checkout',[EdulabzzCursoController::class,'checkoutCurso'])->name('curso.checkout');
    Route::get('curso/{idCurso}/trancado',[EdulabzzCursoController::class,'cursoTrancado'])->name('curso.trancado');
    Route::post('curso/{idCurso}/trancado/acessar',[EdulabzzCursoController::class,'postAcessarCursoTrancado'])->name('curso.trancado-acessar');
    Route::get('cursos-livre',[EdulabzzCursoController::class,'cursoLivre'])->name('curso-livre');

    #Rota para filtrar cursos
    Route::get('gestao/search-cursos',[EdulabzzCursoController::class,'searchCursos'])->name('gestao.search-cursos');
    #Rota para filtro do ciclo etapa
    Route::get('gestao/searchcicloetapa',[EdulabzzCursoController::class,'searchCicloEtapa'])->name('gestao.searchcicloetapa');

    Route::get('escola/searchescola', [EdulabzzEscolaController::class, 'searchEscola'])->name('gestao.search_escola');

    Route::group(['prefix' => 'canal-do-professor', 'as' => 'canal-professor.'], function () {

        Route::get('/{idProfessor}/canal', [CanalProfessorController::class,'index'])->name('index');
        Route::get('/{idProfessor}/biblioteca', [CanalProfessorController::class,'biblioteca'])->name('biblioteca');
        Route::get('/{idProfessor}/avaliacoes', [CanalProfessorController::class,'avaliacoes'])->name('avaliacoes');

        Route::get('/{idProfessor}/duvidas', [CanalProfessorController::class,'duvidas'])->name('duvidas');
        Route::get('/{idProfessor}/duvida/{idDuvida}/respostas', [CanalProfessorController::class,'duvida'])->name('duvida-respostas');

    });
    Route::get('/play/{idCurso}', [EdulabzzCursoController::class, 'playCurso'])->name('curso.play');
    Route::post('/play/{idCurso}/avalicao/enviar', [EdulabzzCursoController::class, 'postEnviarAvaliacaoCurso'])->name('curso.play.enviar-avaliacao-curso');
    Route::any('/play/{idCurso}/professor/avalicao/enviar', [EdulabzzCursoController::class, 'postEnviarAvaliacaoProfessor'])->name('curso.play.enviar-avaliacao-professor');
    Route::post('/play/{idCurso}/escola/avaliacao/enviar', [EdulabzzCursoController::class, 'postEnviarAvaliacaoEscola'])->name('curso.play.enviar-avaliacao-escola');
    Route::get('/play/{idCurso}/conteudo/{idAula}', [EdulabzzCursoController::class, 'playGetAula'])->name('curso.play.get-aula');
    Route::get('/play/{idCurso}/conteudo/{idAula}/{idConteudo}', [EdulabzzCursoController::class, 'playGetConteudo'])->name('curso.play.get-conteudo');
    Route::get('/play/{idCurso}/conteudo/{idAula}/{idConteudo}/proximo', [EdulabzzCursoController::class, 'playGetProximoConteudo'])->name('curso.play.get-proximo-conteudo');
    Route::post('/play/{idCurso}/{idAula}/{idConteudo}/avaliacao/enviar', [EdulabzzCursoController::class, 'postEnviarAvaliacaoConteudo'])->name('curso.play.enviar-avaliacao-conteudo');
    Route::post('/play/{idCurso}/{idAula}/{idConteudo}/comentario/enviar', [EdulabzzCursoController::class, 'postEnviarComentarioConteudo'])->name('curso.play.enviar-comentario-conteudo');
    Route::get('/play/{idCurso}/{idAula}/{idConteudo}/mensagens', [EdulabzzCursoController::class, 'getMensagensTransmissao'])->name('curso.play.mensagens-transmissao');
    Route::post('/play/{idCurso}/{idAula}/{idConteudo}/mensagem/enviar', [EdulabzzCursoController::class, 'postEnviarMensagemTransmissao'])->name('curso.play.enviar-mensagem-transmissao');
    Route::get('/play/{idCurso}/{idAula}/{idConteudo}/comentario/{idComentario}/excluir', [EdulabzzCursoController::class, 'getExcluirComentarioConteudo'])->name('curso.play.excluir-comentario-conteudo');
    Route::post('/play/{idCurso}/{idAula}/{idConteudo}/enviar/resposta', [EdulabzzCursoController::class, 'postEnviarResposta'])->name('curso.play.enviar-resposta');
    Route::post('/play/{idCurso}/{idAula}/{idConteudo}/enviar/entregavel', [EdulabzzCursoController::class, 'postEnviarEntregavel'])->name('curso.play.enviar-entregavel');
    Route::get('entregaveis/arquivo/{idResposta}', [EntregaveisController::class, 'getArquivoEntregavel'])->name('gestao.entregaveis-arquivo');
    Route::get('/play/{idCurso}/conteudo/{idAula}/{idConteudo}/arquivo', [EdulabzzCursoController::class, 'playGetArquivo'])->name('curso.play.get-arquivo');

    Route::get('curso/{idCurso}/matricular', [EdulabzzCursoController::class, 'matricular'])->name('curso.matricular');

    // Fórum/Tópicos do Ccurso
    Route::get('curso/{curso_id}/topicos', [TopicoCursoController::class,'index'])->name('curso.topicos');
    Route::post('curso/{curso_id}/topico/enviar', [TopicoCursoController::class,'postNovoTopico'])->name('curso.topico-enviar');
    Route::get('curso/{curso_id}/topico/{topico_curso_id}/comentarios', [TopicoCursoController::class,'topico'])->name('curso.topico-respostas');
    Route::post('curso/{curso_id}/topico/{topico_curso_id}/atualizar', [TopicoCursoController::class,'postAtualizarTopico'])->name('curso.topico-atualizar');
    Route::post('curso/{curso_id}/topico/{topico_curso_id}/excluir', [TopicoCursoController::class,'postExcluirTopico'])->name('curso.topico-excluir');
    Route::post('curso/{curso_id}/topico/{topico_curso_id}/comentario/enviar', [TopicoCursoController::class,'postEnviarComentarioTopico'])->name('curso.topico-comentario-enviar');
    Route::post('curso/{curso_id}/topico/{topico_curso_id}/comentario/{idComentario}/excluir', [TopicoCursoController::class,'postExcluirComentarioTopico'])->name('curso.topico-comentario-excluir');


    /// rotas para Trilhas
    //AQUI
    //Route::get('gestao/cursos',[GestaoController::class,'cursos'])->name('gestao.trilhas.createG');

    // Trilhas
    Route::group(['prefix' => 'trilhas', 'as' => 'trilhas.'], function () {
        //AQUI
        //Route::get('listar', [EdulabzzTrilhasController::class,'index'])->name('listar');
        //Route::get('listar', function () { return view('errors/503_roteiros');});
        Route::get('{idTrilha}/progresso', [EdulabzzTrilhasController::class,'progresso'])->name('progresso');
        Route::get('{idTrilha}/matricular', [EdulabzzTrilhasController::class,'matricular'])->name('matricular');
        Route::get('{idTrilha}/abandonar', [EdulabzzTrilhasController::class,'abandonar'])->name('abandonar');
    });
    Route::group(['prefix' => 'gestao', 'as' => 'gestao.'], function () {
        Route::group(['prefix' => 'trilhas', 'as' => 'trilhas.'], function () {
            //AQUI
            //Route::get('/', [EdulabzzTrilhasAdminController::class,'index'])->name('listar');
            Route::get('/', function () { return view('errors/503_roteiros');});
            Route::get('/nova', [EdulabzzTrilhasAdminController::class,'create'])->name('create');
            Route::post('/store', [EdulabzzTrilhasAdminController::class,'store'])->name('store');
            Route::get('/{idTrilha}/editar', [EdulabzzTrilhasAdminController::class,'edit'])->name('edit');
            Route::post('/{idTrilha}/update', [EdulabzzTrilhasAdminController::class,'update'])->name('update');
            Route::post('/{idTrilha}/excluir', [EdulabzzTrilhasAdminController::class,'destroy'])->name('destroy');
        });
        // Biblioteca de aplicacoes e conteudo
        Route::get('biblioteca', [GestaoController::class,'biblioteca'])->name('biblioteca');
        //Route::get('biblioteca/{idConteudo}/visualizar', [GestaoController::class,'getConteudoVisualizar'])->name('biblioteca.visualizar');
        //Gestão de cursos e conteudo
        Route::post('conteudos/novo', [GestaoController::class, 'postNovoConteudo'])->name('conteudo-novo');
        Route::get('conteudos/{idConteudo}/editar', [GestaoController::class, 'editarConteudo'])->name('conteudos-editar');
        Route::post('conteudos/salvar', [GestaoController::class, 'postSalvarConteudo'])->name('conteudos-salvar');
        Route::post('conteudo/{idConteudo}/excluir', [GestaoController::class, 'postExcluirConteudo'])->name('conteudo-excluir');
        //  Gestão de Aplicações
        Route::get('aplicacoes', [AplicacaoController::class, 'gestaoAplicacoes'])->name('aplicacoes');
        Route::post('aplicacao/enviar', [AplicacaoController::class, 'postCriarAplicacao'])->name('aplicacao-nova');
        Route::get('aplicacao/{idAplicacao}/editar', [AplicacaoController::class, 'getAplicacao'])->name('aplicacao-editar');
        Route::post('aplicacao/salvar', [AplicacaoController::class, 'postSalvarAplicacao'])->name('aplicacao-salvar');
        Route::post('aplicacao/{idAplicacao}/excluir', [AplicacaoController::class, 'postExcluirAplicacao'])->name('aplicacao-excluir');


    });
    /// rota modal dos conteudos
    Route::get('gestao/biblioteca/{idConteudo}/visualizar', [EdulabzzBibliotecaController::class, 'getConteudoVisualizar']);
    /// rota pra construir objetos dentro do modal (audio)
    Route::get('play/conteudo/{idConteudo}/arquivo', [ConteudoController::class,'playGetArquivo'])->name('conteudo.play.get-arquivo');



});
/*
 *
 *     url sem aceite de termos
 *
 */

Route::get('termo-aceite',[TermosController::class,'aceitar'] )->name('termo-aceite');
Route::post('aceitar', [TermosController::class,'aceitar'])->name('aceitar.termo');

/*
	Contato
*/
Route::get('contato', [ContatoController::class,'index']);
Route::get('contato/infantil', [ContatoController::class,'edInfantil']);
Route::post('contato', [ContatoController::class,'enviar']);

/*
	Multiplas permissoes
*/
Route::get('/multiplasPermissoes', [UserController::class,'listaPermissoes']);
Route::post('/multiplasPermissoes/entrar', [UserController::class,'alteraPermissao']);


/*
	Tutoriais
*/
Route::get('/colecao_tutorial', [TutorialController::class,'colecao']);
Route::get('/tutorial/{id}', [TutorialController::class,'index']);
Route::get('/tutorial/download/{id}', [TutorialController::class,'downloadTutorial']);

Route::get('termos-de-uso', [TutorialController::class,'termosUso']);
Route::get('politica-de-privacidade', [TutorialController::class,'termosPrivacidade']);


/*
	Permitidos para o google
*/
Route::get('/exibicao/google/', [ExibicaoGoogleController::class,'exibir']);

/*
	Leitura de QrCode dos materiais físicos
*/
Route::get('/leitura/qrcode', [LeituraQrCodeController::class,'exibir']);

/*
	HOT SITE NOVO ENSINO MÉDIO
*/
Route::get('/nem', function () {
    return view('fr.nem.index');
});

/// games demostração
  /*
Route::get('/jogos', function(){
    return view('games');
});
*/
Route::post('/hO9EndasHtVQlEs5BTA1G8V5Ypff_login/get_token', [LoginAppController::class,'getToken']);
Route::get('/oDV1JnV1oUmKp1YQd1hQQxSaXD3x_logar/normal', [LoginAppController::class,'logarNormal']);
Route::get('/dsvTyeDgGRZlddGfGHZYahNTPrym_refresh/normal', [LoginAppController::class,'refresh']);

Route::get('/ejVSWtgCp4wtLxHUm2Y2bsDklEXL_logar/social', [LoginAppController::class,'logarSocial']);
Route::get('/D6Z8jzEGcAx9goCVJDKYidG9b2Aw_logar/validaSocial', [LoginAppController::class, 'logarSocialValido']);

/*
	Rotas alteradas permanentemente
*/
Route::permanentRedirect('/', '/login');
Route::permanentRedirect('/home', '/catalogo');

/*
	Rotas para login social
*/
Route::get('/auth/google', [SocialAuthController::class,'redirectToSocial']);
Route::get('/auth/social', [SocialAuthController::class,'handleSocialCallback']);
Route::get('/login/social', function () {
    return redirect('login');
});

Route::get('/logout', [LoginController::class,'logout']);

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);
