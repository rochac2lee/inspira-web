<?php

namespace App\Http\Controllers\Fr;

use App\Models\Instituicao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Services\Fr\InstituicaoService;
use App\Services\Fr\LivroService;
use App\Services\Fr\BibliotecaService;

use App\Http\Requests\Fr\InstituicaoRequest;

class InstituicaoController extends Controller
{
    public function __construct(InstituicaoService $instituicaoService, LivroService $livroService, BibliotecaService $bibliotecaService)
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao != 'Z')
            {
                return back();
            }
            return $next($request);
        });

        $this->instituicaoService = $instituicaoService;
        $this->livroService = $livroService;
        $this->bibliotecaService = $bibliotecaService;

    }

    public function index(Request $request)
    {
    	$view = [
                'dados' => $this->instituicaoService->getLista(20,$request),
                'tipo' => $this->instituicaoService->getTipos(),
            ];
        return view('fr/gestao/instituicao/lista',$view);
    }

    public function excluir($id)
    {
		$retorno = $this->instituicaoService->excluir($id);

		if($retorno){
			return back()->with('certo', 'Instituição excluída.');
    	}
    	else{
			return back()->with('erro', 'Erro ao tentar excluir instituição.');
    	}
	}

	public function add(InstituicaoRequest $request)
    {
    	$user = Auth::user();

    	$retorno = $this->instituicaoService->inserir($request,$user->id);

		if($retorno===true){
			return back()->with('certo', 'Instituição cadastrada.');
    	}
    	else{
			return back()->with('erro', 'Erro ao tentar cadastrar instituição.');
    	}
	}

    public function get(Request $request)
    {
        $retorno = $this->instituicaoService->getForm($request->input('id'));
        if($retorno){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function editar(InstituicaoRequest $request)
    {

        $retorno = $this->instituicaoService->editar($request);

        if($retorno===true){
            return back()->with('certo', 'Instituição Editada.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar editar instituição.</b>')->with('editar', '1');
        }
    }

    public function mudaStatus(Request $request)
    {

        $retorno = $this->instituicaoService->mudaStatus($request);

        if($retorno){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function material($id)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $view = [
            'instituicao' => $this->instituicaoService->get($id),
        ];

        return view('fr/gestao/instituicao/gestao_material/material',$view);
    }

    /// gerenciar livros na instituicao

    public function colecaoLivro($idInst)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $view = [
            'instituicao'    => Instituicao::find($idInst),
            'colecaoParaAdd'    => $this->livroService->colecaoForaDaInstituicao($idInst),
            'dados'     => $this->livroService->colecaoNaInstituicao($idInst),
        ];

        return view('fr/gestao/instituicao/gestao_material/colecao_livro',$view);
    }

    public function removerColecaoLivro($idInsti,$idColecao)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $retorno = $this->instituicaoService->removerColecaoLivro($idInsti,$idColecao);

        if($retorno===true){
            return back()->with('certo', 'Coleção removida.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar remover coleção.</b>');
        }
    }

    public function addColecaoLivro(Request $request)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $retorno = $this->instituicaoService->addColecaoLivro($dados);
        if($retorno===true){
            return back()->with('certo', 'Coleção adicionada.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar adicionar coleção.</b>');
        }
    }

    public function permissaoColecaoLivro(Request $request)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();
        $retorno = $this->instituicaoService->permissaoColecaoLivro($dados);

        if($retorno===true){
            return back()->with('certo', 'Etapa / Ano atualizado.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar atualizar Etapa / Ano.</b>');
        }

    }

    /// gerenciar audios na instituicao

    public function colecaoAudio($idInst)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $view = [
            'instituicao'       => Instituicao::find($idInst),
            'colecaoParaAdd'    => $this->bibliotecaService->colecaoAudioForaDaInstituicao($idInst),
            'dados'             => $this->bibliotecaService->colecaoAudioNaInstituicao($idInst),

        ];

        return view('fr/gestao/instituicao/gestao_material/colecao_audio',$view);
    }

    public function removerColecaoAudio($idInst,$idColecao)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $retorno = $this->instituicaoService->removerColecaoAudio($idInst,$idColecao);

        if($retorno===true){
            return back()->with('certo', 'Coleção removida.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar remover coleção.</b>');
        }
    }

    public function addColecaoAudio(Request $request)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $retorno = $this->instituicaoService->addColecaoAudio($dados);
        if($retorno===true){
            return back()->with('certo', 'Coleção adicionada.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar adicionar coleção.</b>');
        }
    }

    public function permissaoColecaoAudio(Request $request)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $retorno = $this->instituicaoService->permissaoColecaoAudio($dados);

        if($retorno===true){
            return back()->with('certo', 'Etapa / Ano atualizado.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar atualizar Etapa / Ano.</b>');
        }

    }


/// gerenciar provas na instituicao

    public function colecaoProva($idInst)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $view = [
            'instituicao'    => Instituicao::find($idInst),
            'colecaoParaAdd'    => $this->bibliotecaService->colecaoProvaForaDaInstituicao($idInst),
            'dados'     => $this->bibliotecaService->colecaoProvaNaInstituicao($idInst),
        ];

        return view('fr/gestao/instituicao/gestao_material/colecao_prova',$view);
    }

    public function removerColecaoProva($idInst,$idColecao)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $retorno = $this->instituicaoService->removerColecaoProva($idInst,$idColecao);

        if($retorno===true){
            return back()->with('certo', 'Coleção removida.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar remover coleção.</b>');
        }
    }

    public function addColecaoProva(Request $request)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $retorno = $this->instituicaoService->addColecaoProva($dados);
        if($retorno===true){
            return back()->with('certo', 'Coleção adicionada.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar adicionar coleção.</b>');
        }
    }

    public function permissaoColecaoProva(Request $request)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $retorno = $this->instituicaoService->permissaoColecaoProva($dados);

        if($retorno===true){
            return back()->with('certo', 'Etapa / Ano atualizado.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar atualizar Etapa / Ano.</b>');
        }

    }
    /// gerenciar documentos na instituicao

    public function colecaoDocumento($idInst)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $view = [
            'instituicao'    => Instituicao::find($idInst),
            'colecaoParaAdd'    => $this->bibliotecaService->colecaoDocumentoForaDaInstituicao($idInst),
            'dados'     => $this->bibliotecaService->colecaoDocumentoNaInstituicao($idInst),
        ];

        return view('fr/gestao/instituicao/gestao_material/colecao_documento',$view);
    }

    public function removerColecaoDocumento($idInst,$idColecao)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $retorno = $this->instituicaoService->removerColecaoDocumento($idInst,$idColecao);

        if($retorno===true){
            return back()->with('certo', 'Coleção removida.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar remover coleção.</b>');
        }
    }

    public function addColecaoDocumento(Request $request)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $retorno = $this->instituicaoService->addColecaoDocumento($dados);

        if($retorno===true){
            return back()->with('certo', 'Coleção adicionada.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar adicionar coleção.</b>');
        }
    }

    public function permissaoColecaoDocumento(Request $request)
    {
        if(Auth::user()->permissao != 'Z')
        {
            return back();
        }

        $dados = $request->all();

        $retorno = $this->instituicaoService->permissaoColecaoProva($dados);

        if($retorno===true){
            return back()->with('certo', 'Etapa / Ano atualizado.');
        }
        else{
            return back()->with('erro', '<b>Erro ao tentar atualizar Etapa / Ano.</b>');
        }

    }
}
