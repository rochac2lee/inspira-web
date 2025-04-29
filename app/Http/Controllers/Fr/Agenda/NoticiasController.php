<?php

namespace App\Http\Controllers\Fr\Agenda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\Agenda\NoticiaRequest;
use App\Services\Fr\Agenda\NoticiaService;
use App\Services\Fr\EscolaService;
use Illuminate\Http\Request;

class NoticiasController extends Controller
{
    public function __construct( EscolaService $escolaService, NoticiaService $noticiaService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->permissao != 'I' && auth()->user()->permissao != 'C' && auth()->user()->permissao != 'P') {
                return redirect('/');
            }
            return $next($request);
        });

        $this->escolaService = $escolaService;
        $this->noticiaService = $noticiaService;
    }

    public function index(Request $request){

        $view = [
            'dados' => $this->noticiaService->lista($request->all()),
        ];
        return view('fr.agenda.noticias.lista',$view);
    }

    public function novo(){
        $view = [

        ];
        return view('fr.agenda.noticias.form', $view);
    }

    public function add(NoticiaRequest $request){
        $retorno = $this->noticiaService->inserir($request->all(), $request->file('imagem'));

        if($retorno===true){
            return redirect('/gestao/agenda/noticias')->with('certo', 'Noticia cadastrada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar noticia.');
        }
    }

    public function excluir($idNoticia){
        if(!$this->permissao) {
            return back();
        }
        $retorno = $this->noticiaService->excluir($idNoticia);

        if($retorno===true){
            return redirect('/gestao/agenda/noticias')->with('certo', 'Noticia excluída.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir noticia.');
        }
    }

    public function publicar($idNoticia){
        $retorno = $this->noticiaService->publicar($idNoticia);

        if($retorno===true){
            return redirect('/gestao/agenda/noticias')->with('certo', 'Noticia publicada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar publicar noticia.');
        }
    }

    public function editar($idNoticia){
        $dados = $this->noticiaService->getEditar($idNoticia);
        if($dados){
            $view = [
                'dados' => $dados,
            ];
            return view('fr.agenda.noticias.form', $view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar noticia.');
        }
    }

    public function update(NoticiaRequest $request){
        $retorno = $this->noticiaService->update($request->all());

        if($retorno===true){
            return redirect('/gestao/agenda/noticias')->with('certo', 'Noticia editada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar noticia.');
        }
    }

    public function indexImagens($idNoticia){
        $dados = $this->noticiaService->getEditar($idNoticia);
        if($dados){
            $view = [
                'dados' => $dados,
            ];
            return view('fr.agenda.noticias.listaImagens',$view);
        }else{
            return back()->with('erro', 'Erro ao tentar encontrar noticia.');
        }

    }

    public function getEscolasTurmas(Request $request){
        $dados = $this->escolaService->getLista(10, $request->all());
        $view = [
            'dados' => $dados,
            'escola' => $request->input('escola'),
            'turma' => $request->input('turma'),
        ];
        $retorno =  view('fr.agenda.noticias.tabelaEscolaTurmas',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function getEscolasTurmasSelecionados(Request $request){
        $dados = $this->noticiaService->getEscolasTurmasSelecionados($request->all());
        $view = [
            'dados' => $dados,
        ];
        $retorno =  view('fr.agenda.noticias.listaEscolaTurmasSelecionados',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function uploadImagensAjax($idNoticia, Request $request){
        $retorno = $this->noticiaService->uploadImagensAjax($idNoticia, $request->file('file'));
        if($retorno){
            return response()->json( $retorno, 200 );
        }else{
            return response()->json( 'Erro ao tentar gravar imagem.', 500 );
        }
    }

    public function excluirImagem($idNoticia, $idImagem){
        $retorno = $this->noticiaService->excluirImagem($idNoticia, $idImagem);
        if($retorno){
            return back()->with('certo', 'Imagem excluída.');
        }else{
            return back()->with('erro', 'Erro ao tentar excluir imagem.');
        }
    }

    public function ordemImagem($idNoticia, Request $request){
        $retorno = $this->noticiaService->ordernarImagem($idNoticia, $request->all());
        if($retorno){
            return response()->json( true, 200 );
        }else{
            return response()->json( false, 500 );
        }
    }

    public function exibirNoticia($idNoticia){
        $dados = $this->noticiaService->getExibir($idNoticia);
        $view = [
            'dados' => $dados,
        ];
        $retorno =  view('fr.agenda.noticias.exibir',$view)->render();
        return response()->json( $retorno, 200 );
    }
}
