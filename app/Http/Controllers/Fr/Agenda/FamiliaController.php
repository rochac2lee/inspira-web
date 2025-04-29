<?php

namespace App\Http\Controllers\Fr\Agenda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\Agenda\FamiliaRequest;
use App\Models\InstituicaoTipo;
use App\Services\Fr\Agenda\FamiliaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class FamiliaController extends Controller
{
    public function __construct( FamiliaService $familiaService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->permissao == 'A' || auth()->user()->permissao == 'R' ) {
                return redirect('/');
            }

            if(auth()->user()->permissao != 'Z'){
                $action = Route::getCurrentRoute()->getActionMethod();
                if($action != 'index' && $action != 'exibirComunicado'){
                    return redirect('/');
                }
            }
            return $next($request);
        });

        $this->familiaService = $familiaService;
    }

    public function index(Request $request){
        $view = [
            'dados' => $this->familiaService->lista($request->all()),
        ];
        return view('fr.agenda.familia.lista',$view);
    }

    public function novo(){
        $view = [
            'tipoInstituicao' => InstituicaoTipo::orderBy('titulo')->get(),
        ];
        return view('fr.agenda.familia.form', $view);
    }

    public function add(FamiliaRequest $request){
        $retorno = $this->familiaService->inserir($request->all(), $request->file('imagem'));

        if($retorno===true){
            return redirect('/gestao/agenda/familia')->with('certo', 'Comunicado da Família cadastrado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar comunicado da família.');
        }
    }

    public function excluir($idFamilia){
        $retorno = $this->familiaService->excluir($idFamilia);

        if($retorno===true){
            return redirect('/gestao/agenda/familia')->with('certo', 'Comunicado da Família excluído.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir comunicado da família.');
        }
    }

    public function publicar($idFamilia){
        $retorno = $this->familiaService->publicar($idFamilia);
        if($retorno===true){
            return redirect('/gestao/agenda/familia')->with('certo', 'Comunicado da Família publicado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar publicar comunicado da família.');
        }
    }

    public function editar($idFamilia){
        $dados = $this->familiaService->getEditar($idFamilia);
        if($dados){
            $view = [
                'dados' => $dados,
                'tipoInstituicao' => InstituicaoTipo::orderBy('titulo')->get(),
            ];
            return view('fr.agenda.familia.form', $view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar comunicado da família.');
        }
    }

    public function update(FamiliaRequest $request){
        $retorno = $this->familiaService->update($request->all());

        if($retorno===true){
            return redirect('/gestao/agenda/familia')->with('certo', 'Comunicado da Família editado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar comunicado da família.');
        }
    }

    public function indexImagens($idFamilia){
        $dados = $this->familiaService->getEditar($idFamilia);
        if($dados){
            $view = [
                'dados' => $dados,
            ];
            return view('fr.agenda.familia.listaImagens',$view);
        }else{
            return back()->with('erro', 'Erro ao tentar encontrar comunicado da família.');
        }

    }

    public function getInstituicoesEscolas(Request $request){
        $dados = $this->familiaService->getListaInstituicao($request->all());
        $view = [
            'dados' => $dados,
            'escola' => $request->input('escola'),
            'instituicao' => $request->input('instituicao'),
        ];
        $retorno =  view('fr.agenda.familia.tabelaInstituicaoEscola',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function getInstituicaoSelecionadas(Request $request){
        $dados = $this->familiaService->getEscolasTurmasSelecionados($request->all());
        $biblioteca = '';
        if($request->input('biblioteca') == 1){
            $biblioteca = 'Biblioteca';
        }
        $view = [
            'dados' => $dados,
            'biblioteca' => $biblioteca,
        ];
        $retorno =  view('fr.agenda.familia.listaInstituicaoEscolaSelecionados',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function uploadImagensAjax($idFamilia, Request $request){
        $retorno = $this->familiaService->uploadImagensAjax($idFamilia, $request->file('file'));
        if($retorno){
            return response()->json( $retorno, 200 );
        }else{
            return response()->json( 'Erro ao tentar gravar imagem.', 500 );
        }
    }

    public function excluirImagem($idFamilia, $idImagem){
        $retorno = $this->familiaService->excluirImagem($idFamilia, $idImagem);
        if($retorno){
            return back()->with('certo', 'Imagem excluída.');
        }else{
            return back()->with('erro', 'Erro ao tentar excluir imagem.');
        }
    }

    public function ordemImagem($idFamilia, Request $request){
        $retorno = $this->familiaService->ordernarImagem($idFamilia, $request->all());
        if($retorno){
            return response()->json( true, 200 );
        }else{
            return response()->json( false, 500 );
        }
    }

    public function exibirComunicado($idFamilia){
        $dados = $this->familiaService->getExibir($idFamilia);
        $view = [
            'dados' => $dados,
        ];
        $retorno =  view('fr.agenda.familia.exibir',$view)->render();
        return response()->json( $retorno, 200 );
    }
}
