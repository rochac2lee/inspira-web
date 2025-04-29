<?php

namespace App\Http\Controllers\Fr\Agenda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\Agenda\EnqueteRequest;
use App\Services\Fr\Agenda\EnqueteService;
use App\Services\Fr\EscolaService;
use Illuminate\Http\Request;

class EnqueteController extends Controller
{
    public function __construct( EnqueteService $enqueteService, EscolaService $escolaService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->permissao != 'I' && auth()->user()->permissao != 'C') {
                return redirect('/');
            }
            return $next($request);
        });

        $this->enqueteService = $enqueteService;
        $this->escolaService = $escolaService;
    }

    public function index(Request $request){
        $view = [
            'dados' => $this->enqueteService->lista($request->all()),
        ];
        return view('fr.agenda.enquetes.lista',$view);
    }

    public function novo(){
        $view = [];
        return view('fr.agenda.enquetes.form', $view);
    }

    public function add(EnqueteRequest $request){
        $retorno = $this->enqueteService->inserir($request->all(), $request->file('arquivo'));

        if($retorno===true){
            return redirect('/gestao/agenda/enquetes')->with('certo', 'Enquete cadastrada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar enquete.');
        }
    }

    public function excluir($idEnquete){
        $retorno = $this->enqueteService->excluir($idEnquete);

        if($retorno===true){
            return redirect('/gestao/agenda/enquetes')->with('certo', 'Enquete excluída.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir enquete.');
        }
    }

    public function publicar($idEnquete){
        $retorno = $this->enqueteService->publicar($idEnquete);

        if($retorno===true){
            return redirect('/gestao/agenda/enquetes')->with('certo', 'Enquete publicada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar publicar enquete.');
        }
    }

    public function editar($idEnquete){
        $dados = $this->enqueteService->getEditar($idEnquete);
        if($dados){
            $view = [
                'dados'=> $dados,
            ];
            return view('fr.agenda.enquetes.form', $view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar enquete.');
        }
    }

    public function update(EnqueteRequest $request){
        $retorno = $this->enqueteService->update($request->all(), $request->file('arquivo'));

        if($retorno===true){
            return redirect('/gestao/agenda/enquetes')->with('certo', 'Enquete editada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar enquete.');
        }
    }

    public function exibirEnquete($idEnquete){
        $dados = $this->enqueteService->getExibir($idEnquete);
        $view = [
            'dados' => $dados,
        ];
        $retorno =  view('fr.agenda.enquetes.exibir',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function getTurmasSelecionadas(Request $request){
        $dados = $this->enqueteService->getTurmasSelecionadas($request->all());
        $view = [
            'dados' => $dados,
            'alunos' => $request->input('aluno'),
        ];
        $retorno =  view('fr.agenda.enquetes.listaTurmasAlunosSelecionados',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function respondidos(Request $request, $enqueteId){
        $enquete = $this->enqueteService->getExibir($enqueteId,1);
        if(!$enquete){
            return back()->with('erro','Não foi possível localizar respondidos.');
        }
        $view =[
            'enquete' => $enquete,
            'dados' => $this->enqueteService->getRecebidos($enqueteId, $request->all()),
        ];
        return view('fr.agenda.enquetes.listaRespondidos',$view);
    }

    public function resultado( $enqueteId){
        $enquete = $this->enqueteService->getExibir($enqueteId,1);
        if(!$enquete){
            return back()->with('erro','Não foi possível localizar resultado');
        }
        $percAguardando = 0;
        $percRespondido = 0;
        $aguardando = $enquete->alunos[0]->qtd - $enquete->respondidos[0]->qtd;
        $respondido = $enquete->respondidos[0]->qtd;
        if($aguardando > 0){
            $percAguardando = ($aguardando*100) / $enquete->alunos[0]->qtd;
        }
        if($respondido>0){
            $percRespondido = ($respondido*100) / $enquete->alunos[0]->qtd;
        }
        $view =[
            'enquete' => $enquete,
            'respondido' => $this->enqueteService->getRespondidos($enqueteId),
            'percAguardando' => $percAguardando,
            'percRespondido' => $percRespondido,
        ];
        return view('fr.agenda.enquetes.resultado',$view);
    }

}
