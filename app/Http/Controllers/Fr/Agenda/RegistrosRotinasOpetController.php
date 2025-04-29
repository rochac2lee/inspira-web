<?php

namespace App\Http\Controllers\Fr\Agenda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\Agenda\RegistrosRotinasOpetRequest;
use App\Services\Fr\Agenda\RegistroRotinaOpetService;
use Illuminate\Http\Request;

class RegistrosRotinasOpetController extends Controller
{
    public function __construct( RegistroRotinaOpetService $rotinaService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->permissao != 'Z') {
                return redirect('/');
            }
            return $next($request);
        });

        $this->rotinaService = $rotinaService;
    }

    public function index(Request $request){
        $view = [
            'vetRotinas' => $this->rotinaService->defineQtdRotinas(),
            'dados' => $this->rotinaService->lista($request->all()),
        ];
        return view('fr.agenda.registro.opet.lista',$view);
    }

    public function novo(){
        $view = [
            'vetRotinas' => $this->rotinaService->defineQtdRotinas(),
        ];
        return view('fr.agenda.registro.opet.form', $view);
    }

    public function add(RegistrosRotinasOpetRequest $request){
        $retorno = $this->rotinaService->inserir($request->all());

        if($retorno===true){
            return redirect('/gestao/agenda/registros/rotinas/opet/')->with('certo', 'Rotina cadastrada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar registro.');
        }
    }

    public function excluir($idEnquete){
        $retorno = $this->rotinaService->excluir($idEnquete);

        if($retorno===true){
            return redirect('/gestao/agenda/registros/rotinas/opet/')->with('certo', 'Rotina excluída.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir registro.');
        }
    }

    public function editar($idEnquete){
        $dados = $this->rotinaService->getEditar($idEnquete);
        if($dados){
            $view = [
                'vetRotinas' => $this->rotinaService->defineQtdRotinas(),
                'dados'=> $dados,
            ];
            return view('fr.agenda.registro.opet.form', $view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar rotina.');
        }
    }

    public function update(RegistrosRotinasOpetRequest $request){
        $retorno = $this->rotinaService->update($request->all());

        if($retorno===true){
            return redirect('/gestao/agenda/registros/rotinas/opet/')->with('certo', 'Rotina editada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar rotina.');
        }
    }

    public function ordenar(Request $request){
        $retorno = $this->rotinaService->ordem($request->all());
        if($retorno){
            return response()->json( true, 200 );
        }else{
            return response()->json( false, 500 );
        }
    }
}
