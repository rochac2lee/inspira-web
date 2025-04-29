<?php

namespace App\Http\Controllers\Fr\Agenda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\Agenda\ConfiguracaoEstiloRequest;
use App\Http\Requests\Fr\Agenda\ConfiguracaoRegistrosRotinasTurmaRequest;
use App\Http\Requests\Fr\Agenda\ConfiguracaoRotuloRequest;
use App\Http\Requests\Fr\Agenda\RegistrosRotinasOpetRequest;
use App\Models\Escola;
use App\Services\Fr\Agenda\ConfiguracoesService;
use Illuminate\Http\Request;

class ConfiguracoesController extends Controller
{
    public function __construct( ConfiguracoesService $configuracoesService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->permissao != 'I') {
                return redirect('/');
            }
            return $next($request);
        });

        $this->configuracoesService = $configuracoesService;
    }

    public function index(Request $request){
        $view = [
        ];
        return view('fr.agenda.configuracoes.index',$view);
    }

    /*
    ///Estilo
    ///
    */
    public function estiloIndex(Request $request){
        $view = [
            'dados' => $this->configuracoesService->getEstilo(),
        ];
        return view('fr.agenda.configuracoes.formEstilo',$view);
    }

    public function estiloEditar(ConfiguracaoEstiloRequest $request){
        $retorno = $this->configuracoesService->editarEstilo($request->all());

        if($retorno===true){
            return back()->with('certo', 'Estilo alterado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar alterar estilo.');
        }
    }

    public function estiloLimpar(Request $request){
        $retorno = $this->configuracoesService->limpaEstilo();

        if($retorno===true){
            return redirect('/gestao/agenda/configuracoes')->with('certo', 'Estilo alterado para o original.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar alterar o estilo para o orignal.');
        }
    }
    /*
    ///Rotulos
    ///
    */
    public function rotulosIndex(Request $request){
        $view = [
            'dados' => $this->configuracoesService->getRotulosCalendario(),
        ];
        return view('fr.agenda.configuracoes.formRotulosCalendario',$view);
    }

    public function rotulosEditar(ConfiguracaoRotuloRequest $request){
        $retorno = $this->configuracoesService->editarRotuloCalendario($request->all());

        if($retorno===true){
            return back()->with('certo', 'Etiquetas alteradas.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar alterar etiquetas.');
        }
    }

    public function rotulosExcluir(Request $request){
        $retorno = $this->configuracoesService->excluirRotuloCalendario($request->all());

        if($retorno===true){
            return back()->with('certo', 'Etiquetas removida.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar remover etiquetas.');
        }
    }

    /*
    ///Rotinas
    ///
    */

    public function rotinasIndex(Request $request){
        $view = [
            'dados' => $this->configuracoesService->listaRotina($request->all()),
            'turmas' => $this->configuracoesService->listaRotinaTurmas($request->all()),
            'escolas' => Escola::where('instituicao_id',auth()->user()->instituicao_id)->orderBy('titulo')->get(),
        ];
        return view('fr.agenda.configuracoes.registro_rotina.lista',$view);
    }

    public function rotinasEditar($idRotina){
        $dados = $this->configuracoesService->getEditarRotina($idRotina);
        if($dados){
            $view = [
                'dados'=> $dados,
            ];
            return view('fr.agenda.configuracoes.registro_rotina.form', $view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar rotina.');
        }
    }
    public function rotinasUpdate(RegistrosRotinasOpetRequest $request){
        $retorno = $this->configuracoesService->updateRotina($request->all());

        if($retorno===true){
            return redirect('/gestao/agenda/configuracoes/registros/rotinas/editar')->with('certo', 'Rotina editada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar rotina.');
        }
    }

    public function rotinasOrdenar(Request $request){
        $retorno = $this->configuracoesService->rotinasOrdem($request->all());
        if($retorno){
            return response()->json( true, 200 );
        }else{
            return response()->json( false, 500 );
        }
    }

    public function rotinasAtivar(Request $request){
        $retorno = $this->configuracoesService->rotinasAtivar($request->all());
        if($retorno){
            return response()->json( true, 200 );
        }else{
            return response()->json( false, 500 );
        }
    }

    public function rotinasGetTurmas(Request $request){
        $retorno = $this->configuracoesService->rotinasGetTurmas($request->all());
        if($retorno){
            return response()->json( $retorno, 200 );
        }else{
            return response()->json( false, 500 );
        }
    }
    public function rotinasAddTurmas(ConfiguracaoRegistrosRotinasTurmaRequest $request){
        $retorno = $this->configuracoesService->rotinasAddTurmas($request->all());
        if($retorno){
            return back()->with('certo', 'Turma adicionada na rotina.');
        }else{
            return back()->with('erro', 'Erro ao tentar adicionar turma.');
        }
    }
    public function rotinasRemoverTurma($idTurma){
        $retorno = $this->configuracoesService->rotinasRemoverTurma($idTurma);
        if($retorno){
            return back()->with('certo', 'Turma removida da rotina.');
        }else{
            return back()->with('erro', 'Erro ao tentar remover turma.');
        }
    }
}
