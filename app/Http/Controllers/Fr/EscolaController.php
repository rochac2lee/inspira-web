<?php

namespace App\Http\Controllers\Fr;

use App\Models\InstituicaoTipo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Services\Fr\EscolaService;
use App\Services\Fr\InstituicaoService;
use App\Services\Fr\LivroService;
use App\Services\Fr\BibliotecaService;
use App\Services\Fr\UsuarioService;
use App\Services\Fr\ImportaUsuarioService;

use App\Http\Requests\Fr\EscolaRequest;
use App\Http\Requests\Fr\ImportacaoProfessorRequest;
use App\Models\Disciplina;
use App\Models\Escola;
use Illuminate\Support\Facades\Storage;

class EscolaController extends Controller
{
    public function __construct(EscolaService $escolaService, InstituicaoService $instituicaoService, LivroService $livroService, BibliotecaService $bibliotecaService, UsuarioService $usuarioService, ImportaUsuarioService $importaUsuarioService)
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao != 'Z' && Auth::user()->permissao != 'I')
            {
                return back();
            }
            return $next($request);
        });

        $this->escolaService        = $escolaService;
        $this->instituicaoService   = $instituicaoService;
        $this->livroService         = $livroService;
        $this->usuarioService       = $usuarioService;
        $this->bibliotecaService    = $bibliotecaService;
        $this->importaUsuarioService    = $importaUsuarioService;
    }

    public function index(Request $request)
    {
    	$view = [
                'dados' => $this->escolaService->getLista(20,$request),
                'inst' => $this->instituicaoService->getLista(),
                'disciplinas' => Disciplina::orderBy('titulo')->get(),
                'cicloEtapa' => $this->escolaService->cicloEtapa(),
            ];
        return view('fr/gestao/escola/lista',$view);
    }

    public function mudaStatus(Request $request)
    {

        $retorno = $this->escolaService->mudaStatus($request);

        if($retorno){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function excluir($id)
    {
        if(auth()->user()->permissao != 'Z'){
            return back();
        }
		$retorno = $this->escolaService->excluir($id);

		if($retorno){
			return back()->with('certo', 'Escola excluída.');
    	}
    	else{
			return back()->with('erro', 'Erro ao tentar excluir escola.');
    	}
	}

	public function add(EscolaRequest $request)
    {
        if(auth()->user()->permissao != 'Z'){
            return back();
        }

    	$retorno = $this->escolaService->inserir($request,auth()->user()->id);

		if($retorno===true){
			return back()->with('certo', 'Escola cadastrada.');
    	}
    	else{
			return back()->with('erro', 'Erro ao tentar cadastrar escola. '.$retorno);
    	}
	}

    public function get(Request $request)
    {
        if(auth()->user()->permissao != 'Z'){
            return back();
        }
        $retorno = $this->escolaService->getForm($request->input('id'));
        if($retorno){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function editar(EscolaRequest $request)
    {
        if(auth()->user()->permissao != 'Z'){
            return back();
        }

        $retorno = $this->escolaService->editar($request);

        if($retorno===true){
            return back()->with('certo', 'Escola Editada.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar editar escola.</b>')->with('editar', '1');
        }
    }

    public function material($idEscola)
    {
        if(auth()->user()->permissao != 'Z'){
            return back();
        }
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $view = [
            'escola' => $this->escolaService->get($idEscola),
        ];

        return view('fr/gestao/escola/material',$view);
    }

    /// gerenciar livros na escola

    public function colecaoLivro($idEscola)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $view = [
            'escola'    => Escola::find($idEscola),
            'colecaoParaAdd'    => $this->livroService->colecaoForaDaEscola($idEscola),
            'dados'     => $this->livroService->colecaoNaEscola($idEscola),

        ];

        return view('fr/gestao/escola/colecao_livro',$view);
    }

    public function removerColecaoLivro($idEscola,$idColecao)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $retorno = $this->livroService->removerColecaoEscola($idEscola,$idColecao);

        if($retorno===true){
            return back()->with('certo', 'Coleção removida.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar remover coleção.</b>');
        }
    }

    public function addColecaoLivro(Request $request)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $escola = $this->escolaService->get($dados['escola_id']);

        $dados['instituicao_id'] = $escola->instituicao_id;

        $retorno = $this->livroService->addColecaoEscola($dados);
        if($retorno===true){
            return back()->with('certo', 'Coleção adicionada.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar adicionar coleção.</b>');
        }
    }

    public function permissaoColecaoLivro(Request $request)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $retorno = $this->livroService->permissaoColecaoEscola($dados);

        if($retorno===true){
            return back()->with('certo', 'Etapa / Ano atualizado.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar atualizar Etapa / Ano.</b>');
        }

    }

/// gerenciar audios na escola

    public function colecaoAudio($idEscola)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $view = [
            'escola'    => Escola::find($idEscola),
            'colecaoParaAdd'    => $this->bibliotecaService->colecaoAudioForaDaEscola($idEscola),
            'dados'     => $this->bibliotecaService->colecaoAudioNaEscola($idEscola),

        ];

        return view('fr/gestao/escola/colecao_audio',$view);
    }

    public function removerColecaoAudio($idEscola,$idColecao)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $retorno = $this->bibliotecaService->removerColecaoAudioEscola($idEscola,$idColecao);

        if($retorno===true){
            return back()->with('certo', 'Coleção removida.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar remover coleção.</b>');
        }
    }

    public function addColecaoAudio(Request $request)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $escola = $this->escolaService->get($dados['escola_id']);

        $dados['instituicao_id'] = $escola->instituicao_id;

        $retorno = $this->bibliotecaService->addColecaoAudioEscola($dados);
        if($retorno===true){
            return back()->with('certo', 'Coleção adicionada.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar adicionar coleção.</b>');
        }
    }

    public function permissaoColecaoAudio(Request $request)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $retorno = $this->bibliotecaService->permissaoColecaoAudioEscola($dados);

        if($retorno===true){
            return back()->with('certo', 'Etapa / Ano atualizado.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar atualizar Etapa / Ano.</b>');
        }

    }

    /// gerenciar documentos na escola

    public function colecaoDocumento($idEscola)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $view = [
            'escola'    => Escola::find($idEscola),
            'colecaoParaAdd'    => $this->bibliotecaService->colecaoDocumentoForaDaEscola($idEscola),
            'dados'     => $this->bibliotecaService->colecaoDocumentoNaEscola($idEscola),

        ];

        return view('fr/gestao/escola/colecao_documento',$view);
    }

    public function removerColecaoDocumento($idEscola,$idColecao)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $retorno = $this->bibliotecaService->removerColecaoDocumentoEscola($idEscola,$idColecao);

        if($retorno===true){
            return back()->with('certo', 'Coleção removida.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar remover coleção.</b>');
        }
    }

    public function addColecaoDocumento(Request $request)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $escola = $this->escolaService->get($dados['escola_id']);

        $dados['instituicao_id'] = $escola->instituicao_id;

        $retorno = $this->bibliotecaService->addColecaoDocumentoEscola($dados);
        if($retorno===true){
            return back()->with('certo', 'Coleção adicionada.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar adicionar coleção.</b>');
        }
    }

    public function permissaoColecaoDocumento(Request $request)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $retorno = $this->bibliotecaService->permissaoColecaoDocumentoEscola($dados);

        if($retorno===true){
            return back()->with('certo', 'Etapa / Ano atualizado.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar atualizar Etapa / Ano.</b>');
        }

    }

/// gerenciar provas na escola

    public function colecaoProva($idEscola)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $view = [
            'escola'    => Escola::find($idEscola),
            'colecaoParaAdd'    => $this->bibliotecaService->colecaoProvaForaDaEscola($idEscola),
            'dados'     => $this->bibliotecaService->colecaoProvaNaEscola($idEscola),

        ];

        return view('fr/gestao/escola/colecao_prova',$view);
    }

    public function removerColecaoProva($idEscola,$idColecao)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $retorno = $this->bibliotecaService->removerColecaoProvaEscola($idEscola,$idColecao);

        if($retorno===true){
            return back()->with('certo', 'Coleção removida.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar remover coleção.</b>');
        }
    }

    public function addColecaoProva(Request $request)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $escola = $this->escolaService->get($dados['escola_id']);

        $dados['instituicao_id'] = $escola->instituicao_id;

        $retorno = $this->bibliotecaService->addColecaoProvaEscola($dados);
        if($retorno===true){
            return back()->with('certo', 'Coleção adicionada.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar adicionar coleção.</b>');
        }
    }

    public function permissaoColecaoProva(Request $request)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $retorno = $this->bibliotecaService->permissaoColecaoProvaEscola($dados);

        if($retorno===true){
            return back()->with('certo', 'Etapa / Ano atualizado.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar atualizar Etapa / Ano.</b>');
        }

    }


    /*  GESTÃO DE USUÁRIOS DA ESCOLA  */

    public function listaAlunos(Request $request, $idEscola)
    {
        $dados = $request->all();
        $view = [
                'dados' => $this->usuarioService->getListaAlunos($idEscola, $dados),
                'escola' => Escola::find($idEscola),
            ];
        return view('fr/gestao/usuarios/lista_aluno',$view);
    }

    public function importarUsuariosEmLote(ImportacaoProfessorRequest $request)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $retorno = $this->importaUsuarioService->importar($request);
        if($retorno){
            return back()->with('certo', 'Arquivo enviado para a fila.');
        }else{
            return back()->with('erro', 'Erro ao adicionar arquivo na fila.' );
        }
    }

    public function relatorioImportarUsuarios()
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $view = [
            'dados' => $this->importaUsuarioService->lista_relatorio(),
        ];
        return view('fr/gestao/escola/importacao_usuarios/lista',$view);
    }

    public function downloadArquivoImportaUsuario($id){
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $caminho = $this->importaUsuarioService->caminhoDownloadArquivo($id);
        ob_end_clean();
        return Storage::download($caminho);
    }

    public function relatorioImportarUsuariosDetalhes($id){
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $this->importaUsuarioService->relatorioDetalhes($id);
        if(!isset($dados['log'])){
            $view = [
                'dados' => $dados,
            ];
            return view('fr/gestao/escola/importacao_usuarios/detalhes',$view);
        }
        else{
            $view = [
                'dados' => $dados['dados'],
                'log' => $dados['log'],
            ];
            return view('fr/gestao/escola/importacao_usuarios/detalhes_fila',$view);
        }

    }

    /// gestão de acessos
    ///
    public function relatorioAcessos(Request $request, $idEscola = null){
        if(auth()->user()->permissao != 'Z' && auth()->user()->permissao != 'I')
        {
            return back();
        }

        $view = [
            'dados'     => $this->usuarioService->relatorioAcesso($request->all(), $idEscola, 50),
            'instituicaoTipo' => InstituicaoTipo::orderBy('id')->get(),
        ];
        //dd($view['dados']);
        if($idEscola){
            $view['escola'] = Escola::find($idEscola);
        }
        return view('fr/gestao/escola/acessos/lista',$view);

    }

    public function downloadRelatorioAcessos(Request $request){
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }

        $arquivo = $this->usuarioService->downloadRelatorioAcessos($request->all());
        return Storage::download($arquivo, 'relatorio_acesso_'.date('d-m-Y H:i:s').'.csv');
        //return response()->download($arquivo,'relatorio_acesso_'.date('d-m-Y H:i:s').'.csv');
    }

}
