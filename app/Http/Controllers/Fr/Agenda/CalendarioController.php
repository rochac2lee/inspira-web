<?php

namespace App\Http\Controllers\Fr\Agenda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\Agenda\CalendarioRequest;
use App\Models\Escola;
use App\Services\Fr\Agenda\CalendarioService;
use App\Services\Fr\Agenda\ConfiguracoesService;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    public function __construct( CalendarioService $calendarioService, ConfiguracoesService $configuracoesService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->permissao != 'I' && auth()->user()->permissao != 'C' && auth()->user()->permissao != 'P') {
                return redirect('/');
            }
            return $next($request);
        });

        $this->calendarioService = $calendarioService;
        $this->configuracoesService = $configuracoesService;
    }

    public function lista(Request $request){
        $retorno = $this->calendarioService->lista($request->all());
        return response()->json( $retorno, 200 );
    }

    public function get(Request $request){
        $retorno = $this->calendarioService->get($request->input('id'));
        if($retorno){
            return response()->json( $retorno );
        }else{
            return response()->json( null, 400 );
        }
    }

    public function index(Request $request){
        $escola = Escola::where('instituicao_id',auth()->user()->instituicao_id)
                        ->orderBy('titulo')
                        ->get();
        $view = [
            'escola' => $escola,
            'etiquetas' => $this->configuracoesService->getRotulosCalendario(),
        ];
        return view('fr.agenda.calendario.lista', $view);
    }

    public function add(CalendarioRequest $request){
        $retorno = $this->calendarioService->inserir($request->all());

        if($retorno===true){
            return redirect('/gestao/agenda/calendario')->with('certo', 'Evento cadastrado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar evento.');
        }
    }

    public function update(CalendarioRequest $request){
        $retorno = $this->calendarioService->editar($request->all());

        if($retorno===true){
            return redirect('/gestao/agenda/calendario')->with('certo', 'Evento editado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar evento.');
        }
    }

    public function excluir($id){
        $retorno = $this->calendarioService->excluir($id);

        if($retorno===true){
            return redirect('/gestao/agenda/calendario')->with('certo', 'Evento excluído.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir evento.');
        }
    }

    public function setNovaData(Request $request){
        $retorno = $this->calendarioService->setNovaData($request->all());

        if($retorno){
            return response()->json( $retorno );
        }else{
            return response()->json( null, 400 );
        }
    }
}
