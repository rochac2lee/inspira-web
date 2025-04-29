<?php

namespace App\Http\Controllers\Fr\Agenda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\Agenda\ComunicadoRequest;
use App\Services\Fr\Agenda\ComunicadoService;
use App\Services\Fr\EscolaService;
use Illuminate\Http\Request;

class ComunicadosController extends Controller
{
    public function __construct( EscolaService $escolaService, ComunicadoService $comunicadoService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->permissao != 'I' && auth()->user()->permissao != 'C' && auth()->user()->permissao != 'P') {
                return redirect('/');
            }
            return $next($request);
        });

        $this->escolaService = $escolaService;
        $this->comunicadoService = $comunicadoService;
    }

    public function index(Request $request){
        $view = [
            'dados' => $this->comunicadoService->lista($request->all()),
        ];
        return view('fr.agenda.comunicados.lista',$view);
    }

    public function novo(){
        $view = [

        ];
        return view('fr.agenda.comunicados.form', $view);
    }

    public function add(ComunicadoRequest $request){
        $retorno = $this->comunicadoService->inserir($request->all(), $request->file('imagem'));

        if($retorno===true){
            return redirect('/gestao/agenda/comunicados')->with('certo', 'Comunicado cadastrado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar comunicado.');
        }
    }

    public function excluir($idComunicado){
        $retorno = $this->comunicadoService->excluir($idComunicado);

        if($retorno===true){
            return redirect('/gestao/agenda/comunicados')->with('certo', 'Comunicado excluído.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir comunicado.');
        }
    }

    public function publicar($idComunicado){
        $retorno = $this->comunicadoService->publicar($idComunicado);

        if($retorno===true){
            return redirect('/gestao/agenda/comunicados')->with('certo', 'Comunicado publicado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar publicar comunicado.');
        }
    }

    public function editar($idComunicado){
        $dados = $this->comunicadoService->getEditar($idComunicado);
        if($dados){
            $view = [
                'dados' => $dados,
            ];
            return view('fr.agenda.comunicados.form', $view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar comunicado.');
        }
    }

    public function update(ComunicadoRequest $request){
        $retorno = $this->comunicadoService->update($request->all());

        if($retorno===true){
            return redirect('/gestao/agenda/comunicados')->with('certo', 'Comunicado editado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar comunicado.');
        }
    }

    public function indexImagens($idComunicado){
        $dados = $this->comunicadoService->getEditar($idComunicado);
        if($dados){
            $view = [
                'dados' => $dados,
            ];
            return view('fr.agenda.comunicados.listaImagens',$view);
        }else{
            return back()->with('erro', 'Erro ao tentar encontrar comunicado.');
        }

    }

    public function getEscolasTurmas(Request $request){
        $dados = $this->escolaService->getLista(10, $request->all());
        $view = [
            'dados' => $dados,
            'escola' => $request->input('escola'),
            'turma' => $request->input('turma'),
        ];
        $retorno =  view('fr.agenda.comunicados.tabelaEscolaTurmas',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function getEscolasTurmasSelecionados(Request $request){
        $dados = $this->comunicadoService->getEscolasTurmasSelecionados($request->all());
        $view = [
            'dados' => $dados,
        ];
        $retorno =  view('fr.agenda.comunicados.listaEscolaTurmasSelecionados',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function uploadImagensAjax($idComunicado, Request $request){
        $retorno = $this->comunicadoService->uploadImagensAjax($idComunicado, $request->file('file'));
        if($retorno){
            return response()->json( $retorno, 200 );
        }else{
            return response()->json( 'Erro ao tentar gravar imagem.', 500 );
        }
    }

    public function excluirImagem($idComunicado, $idImagem){
        $retorno = $this->comunicadoService->excluirImagem($idComunicado, $idImagem);
        if($retorno){
            return back()->with('certo', 'Imagem excluída.');
        }else{
            return back()->with('erro', 'Erro ao tentar excluir imagem.');
        }
    }

    public function ordemImagem($idComunicado, Request $request){
        $retorno = $this->comunicadoService->ordernarImagem($idComunicado, $request->all());
        if($retorno){
            return response()->json( true, 200 );
        }else{
            return response()->json( false, 500 );
        }
    }

    public function exibirComunicado($idComunicado){
        $dados = $this->comunicadoService->getExibir($idComunicado);
        $view = [
            'dados' => $dados,
        ];
        $retorno =  view('fr.agenda.comunicados.exibir',$view)->render();
        return response()->json( $retorno, 200 );
    }
}
