<?php

namespace App\Http\Controllers\Fr\Agenda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\Agenda\AutorizacaoRequest;
use App\Services\Fr\Agenda\AutorizacaoService;
use App\Services\Fr\TurmaService;
use Illuminate\Http\Request;

class AutorizacaoController extends Controller
{
    public function __construct( AutorizacaoService $autorizacaoService, TurmaService $turmaService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->permissao != 'I' && auth()->user()->permissao != 'C' && auth()->user()->permissao != 'P') {
                return redirect('/');
            }
            return $next($request);
        });

        $this->autorizacaoService = $autorizacaoService;
        $this->turmaService = $turmaService;
    }

    public function index(Request $request){
        $view = [
            'dados' => $this->autorizacaoService->lista($request->all()),
        ];
        return view('fr.agenda.autorizacao.lista',$view);
    }

    public function novo(){
        if(auth()->user()->permissao != 'I' && auth()->user()->permissao != 'C'){
            return back();
        }
        $view = [];
        return view('fr.agenda.autorizacao.form', $view);
    }

    public function add(AutorizacaoRequest $request){
        if(auth()->user()->permissao != 'I' && auth()->user()->permissao != 'C'){
            return back();
        }

        $retorno = $this->autorizacaoService->inserir($request->all());

        if($retorno===true){
            return redirect('/gestao/agenda/autorizacoes')->with('certo', 'Autorização cadastrada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar autorização.');
        }
    }

    public function excluir($idTarefa){
        if(auth()->user()->permissao != 'I' && auth()->user()->permissao != 'C'){
            return back();
        }
        $retorno = $this->autorizacaoService->excluir($idTarefa);

        if($retorno===true){
            return redirect('/gestao/agenda/autorizacoes')->with('certo', 'Autorização excluída.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir autorização.');
        }
    }

    public function publicar($idTarefa){
        if(auth()->user()->permissao != 'I' && auth()->user()->permissao != 'C'){
            return back();
        }
        $retorno = $this->autorizacaoService->publicar($idTarefa);

        if($retorno===true){
            return redirect('/gestao/agenda/autorizacoes')->with('certo', 'Autorização publicada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar publicar autorização.');
        }
    }

    public function editar($idComunicado){
        if(auth()->user()->permissao != 'I' && auth()->user()->permissao != 'C'){
            return back();
        }
        $dados = $this->autorizacaoService->get($idComunicado);
        if($dados){
            $view = [
                'dados'=> $dados,
            ];
            return view('fr.agenda.autorizacao.form', $view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar autorização.');
        }
    }

    public function update(AutorizacaoRequest $request){
        if(auth()->user()->permissao != 'I' && auth()->user()->permissao != 'C'){
            return back();
        }
        $retorno = $this->autorizacaoService->update($request->all());

        if($retorno===true){
            return redirect('/gestao/agenda/autorizacoes')->with('certo', 'Autorização editada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar autorização.');
        }
    }

    public function exibirTarefa($idTarefa){
        $dados = $this->autorizacaoService->get($idTarefa);
        $view = [
            'dados' => $dados,
        ];
        $retorno =  view('fr.agenda.autorizacao.exibir',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function getTurmas(Request $request){
        $dados = $this->turmaService->lista(auth()->user()->escola_id, $request->all(), 10);
        $view = [
            'dados' => $dados,
            'turma' => $request->input('turma'),
            'aluno' => $request->input('aluno'),
        ];
        $retorno =  view('fr.agenda.autorizacao.tabelaTurmas',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function getTurmasSelecionadas(Request $request){
        $dados = $this->autorizacaoService->getTurmasSelecionadas($request->all());
        $view = [
            'dados' => $dados,
            'alunos' => $request->input('aluno'),
        ];
        $retorno =  view('fr.agenda.autorizacao.listaTurmasAlunosSelecionados',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function respondidos(Request $request, $autorizacaoId){
        $view =[
            'autorizacao' => $this->autorizacaoService->get($autorizacaoId,1),
            'dados' => $this->autorizacaoService->getRecebidos($autorizacaoId, $request->all()),
        ];
        return view('fr.agenda.autorizacao.listaRespondidos',$view);
    }



}
