<?php

namespace App\Http\Controllers\Fr\Agenda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\Agenda\TarefaRequest;
use App\Models\Disciplina;
use App\Services\Fr\Agenda\TarefaService;
use App\Services\Fr\TurmaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TarefaController extends Controller
{
    public function __construct( TarefaService $tarefaService, TurmaService $turmaService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->permissao != 'I' && auth()->user()->permissao != 'C' && auth()->user()->permissao != 'P') {
                return redirect('/');
            }
            return $next($request);
        });

        $this->tarefaService = $tarefaService;
        $this->turmaService = $turmaService;
    }

    public function index(Request $request){
        $view = [
            'dados' => $this->tarefaService->lista($request->all()),
        ];
        return view('fr.agenda.tarefa.lista',$view);
    }

    public function novo(){
        $view = [
            'disciplina' => Disciplina::orderBy('titulo')->get(),
        ];
        return view('fr.agenda.tarefa.form', $view);
    }

    public function add(TarefaRequest $request){
        $retorno = $this->tarefaService->inserir($request->all(), $request->file('arquivo'));

        if($retorno===true){
            return redirect('/gestao/agenda/tarefas')->with('certo', 'Tarefa cadastrada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar tarefa.');
        }
    }

    public function excluir($idTarefa){
        $retorno = $this->tarefaService->excluir($idTarefa);

        if($retorno===true){
            return redirect('/gestao/agenda/tarefas')->with('certo', 'Tarefa excluída.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir tarefa.');
        }
    }

    public function publicar($idTarefa){
        $retorno = $this->tarefaService->publicar($idTarefa);

        if($retorno===true){
            return redirect('/gestao/agenda/tarefas')->with('certo', 'Tarefa publicada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar publicar tarefa.');
        }
    }

    public function editar($idComunicado){
        $dados = $this->tarefaService->get($idComunicado);
        if($dados){
            $view = [
                'disciplina' => Disciplina::orderBy('titulo')->get(),
                'dados'=> $dados,
            ];
            return view('fr.agenda.tarefa.form', $view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar comunicado.');
        }
    }

    public function update(TarefaRequest $request){
        $retorno = $this->tarefaService->update($request->all(),$request->file('arquivo'));

        if($retorno===true){
            return redirect('/gestao/agenda/tarefas')->with('certo', 'Tarefa editada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar tarefa.');
        }
    }

    public function downloadArquivo($idTarefa){
        $retorno = $this->tarefaService->get($idTarefa);
        if($retorno->link_arquivo) {
            ob_end_clean();
            return Storage::download($retorno->link_arquivo_download);
        }
        else{
            return back()->with('erro', 'Erro ao tentar realizar download do arquivo.');
        }
    }

    public function exibirTarefa($idTarefa){
        $dados = $this->tarefaService->get($idTarefa);
        $view = [
            'dados' => $dados,
        ];
        $retorno =  view('fr.agenda.tarefa.exibir',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function getTurmas(Request $request){
        $dados = $this->turmaService->lista(auth()->user()->escola_id, $request->all(), 10);
        $view = [
            'dados' => $dados,
            'turma' => $request->input('turma'),
            'aluno' => $request->input('aluno'),
        ];
        $retorno =  view('fr.agenda.tarefa.tabelaTurmas',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function getTurmasSelecionadas(Request $request){
        $dados = $this->tarefaService->getTurmasSelecionadas($request->all());
        $view = [
            'dados' => $dados,
            'alunos' => $request->input('aluno'),
        ];
        $retorno =  view('fr.agenda.tarefa.listaTurmasAlunosSelecionados',$view)->render();
        return response()->json( $retorno, 200 );
    }


}
