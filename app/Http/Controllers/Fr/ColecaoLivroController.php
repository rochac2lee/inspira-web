<?php

namespace App\Http\Controllers\Fr;

use App\Models\Conteudo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

use App\Services\Fr\LivroService;

use App\Models\ColecaoLivros;


class ColecaoLivroController extends Controller
{

	public function __construct(LivroService $livroService)
    {
        $this->middleware('auth');

        $this->livroService = $livroService;
    }

    public function index(Request $request)
    {
    	$dados = $this->livroService->colecoes($request->input('etapa'));
    	$view = [
    		'dados' => $dados,
    	];
    	return view('fr/colecaoLivro/index',$view);
    }

    public function livros($idColecao, Request $request)
    {
        $view = [
            'pesquisa' => $this->livroService->defineMenuPesquisa($idColecao, $request),
            'dados'    => $this->livroService->livroColecao($idColecao, $request),
            'colecao'  => ColecaoLivros::find($idColecao),
            'tipo_livro' => $this->livroService->tipoLivro($idColecao),
            'ano_livro' => $this->livroService->anoLivro($idColecao),
        ];
        return view('fr/colecaoLivro/livros', $view);
    }


    public function verLivro( $idLivro, Request $request)
    {
        $flipSozinho=$request->input('flip_sozinho');
        $livro = $this->livroService->getLivro($idLivro);
        if(isset($livro->id) && $livro->id >0)
        {
            $view = [
                'livro'         => $livro,
                'flipSozinho'   =>$flipSozinho,
            ];
            if($flipSozinho ==null)
            {
                return view('fr/colecaoLivro/exibe_livro',$view);
            }
            else
            {
                return view('/fr/colecaoLivro/flip_livro',$view);
            }
        }
        else
        {
            if($flipSozinho ==null)
            {
                return back();
            }
            else
            {
                return 'Você não tem permissão de acesso a esse conteúdo.';
            }
        }
    }

    public function verLivroPdfView($idLivro)
    {
        $livro = $this->livroService->getLivro($idLivro);
        if(isset($livro->id) && $livro->id >0)
        {

            $view = [
                'livro' => $livro,
            ];
            return view('fr/colecaoLivro/pdf_view',$view);
        }
        else
        {
            return back();
        }
    }

    public function downloadLivro($idLivro)
    {
        $livro = $this->livroService->getLivro($idLivro);
        if(isset($livro->id) && $livro->id >0 && $livro->permissao_download==1)
        {
            return Storage::download(config('app.frStorage').'livrodigital/'.$livro->id.'/livro.pdf');
        }
        else
        {
            return back();
        }
    }

    public function ajaxListaColecoesLivroOption()
    {

        $colecoes = $this->livroService->colecaoNaEscola(auth()->user()->escola_id,null,false);
        $retorno = '<option>Selecione uma coleção</option>';
        $i = 0;
        foreach($colecoes as $c)
        {
            $selected = '';
            if($i == 0){
                $selected = 'selected';
            }
            $retorno .= '<option '.$selected.' value="'.$c->id.'">'.$c->nome.'</option>';
            $i++;
        }
        return $retorno;
    }

    public function listaPaginas(Request $request){
        $livro = Conteudo::find($request->input('id'));
        $paginas = [];
        for($i=1; $i<=$livro->qtd_paginas_livro; $i++){
            $paginas[]=$i;
        }
        $dados = $this->paginate($paginas, 10, $request->input('page'));
        $view = [
            'dados'     => $dados,
            'livro_id'  => $request->input('id'),
        ];
        $retorno = view('fr/colecaoLivro/lista_paginas',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }




}
