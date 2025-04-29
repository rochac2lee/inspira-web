<?php

namespace App\Http\Controllers\Fr\Agenda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\Agenda\RegistroBuscarRequest;
use App\Models\Escola;
use App\Models\User;
use App\Services\Fr\Agenda\RegistroService;
use App\Services\Fr\TurmaService;
use Illuminate\Http\Request;

class RegistroController extends Controller
{
    public function __construct(RegistroService $registroService, TurmaService $turmaService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->permissao != 'I' && auth()->user()->permissao != 'C' && auth()->user()->permissao != 'P') {
                return redirect('/');
            }
            return $next($request);
        });

        $this->registroService = $registroService;
        $this->turmaService = $turmaService;
    }

    public function index(Request $request){
        if((auth()->user()->permissao != 'I')) {
            $view = [
                'turmas' => $this->registroService->getTurmas()
            ];
        }
        if(auth()->user()->permissao == 'C'){
            $dados =[
                'escola_id' => auth()->user()->escola_id,
                'sem_page'  =>1,
            ];
            $view['professores'] = $this->turmaService->professorParaAdicionar($dados);
        }elseif(auth()->user()->permissao == 'I'){
            $escola = Escola::where('instituicao_id',auth()->user()->instituicao_id)
                                ->orderBy('titulo')
                                ->get();
            $view['escolas'] = $escola;
        }
        return view('fr.agenda.registro.index',$view);
    }

    public function buscar(RegistroBuscarRequest $request){
        $view = [
            'registro'=> $this->registroService->getRegistro($request->all()),
            'turmas'=> $this->registroService->getTurmas($request->all()),
            'data' => $request->input('data'),
            'professor_id' => $request->input('professor_id'),
            'escola_id' => $request->input('escola_id'),
            'nome_professor' => auth()->user()->nome,
        ];
        if(auth()->user()->permissao == 'C'){
            $dados =[
                'escola_id' => auth()->user()->escola_id,
                'sem_page'  =>1,
            ];
            $view['professores'] = $this->turmaService->professorParaAdicionar($dados);
            $prof = User::find( $view['professor_id']);
            $view['nome_professor'] = $prof->nome;
        }elseif(auth()->user()->permissao == 'I'){
            $escola = Escola::where('instituicao_id',auth()->user()->instituicao_id)
                ->orderBy('titulo')
                ->get();
            $view['escolas'] = $escola;
            $prof = User::find( $view['professor_id']);
            $view['nome_professor'] = $prof->nome;
        }
        return view('fr.agenda.registro.index',$view);
    }

    public function salvar(Request $request){
        $retorno = $this->registroService->salvar($request->all());
        if($retorno){
            return redirect('/gestao/agenda/registros')->with('certo', 'Registros salvos.');
        }else{
            return back()->with('erro', 'Erro ao tentar salvar registro.');
        }
    }

    public function getTurmaProf(Request $request){
        $retorno = $this->registroService->getTurmasProf($request->all());
        return response()->json( $retorno, 200 );
    }

    public function getProf(Request $request){
        $retorno = $this->registroService->getProf($request->all());
        return response()->json( $retorno, 200 );
    }
}
