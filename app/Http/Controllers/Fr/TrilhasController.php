<?php

namespace App\Http\Controllers\Fr;

use App\Models\Disciplina;
use App\Services\Fr\TrilhasService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrilhasController extends Controller
{
	public function __construct(TrilhasService $trilhasService)
    {
        $this->middleware('auth');
        $this->trilhasService = $trilhasService;
    }

    public function index(Request $request)
    {
        $busca = $request->all();
        $busca['realizar'] = true;
        $trilhas = $this->trilhasService->getLista(20,$busca);
        $estatistica =  $this->trilhasService->getEstatistica( $trilhas->pluck('id')->toArray());
        $view = [
            'trilhas' => $trilhas,
            'estatistica' =>$estatistica,
            'cicloEtapa' => $this->trilhasService->cicloEtapa(),
            'disciplina' => Disciplina::orderBy('titulo')->get(),
        ];
       return view('fr/trilhas/index_aluno', $view);
    }

    public function detalhes($id)
    {
        $retorno = $this->trilhasService->detalhes(['id'=>$id, 'realizar' => true]);

        if($retorno){
            $view = [
                'dados' => $retorno,

            ];
            return view('fr/trilhas/detalhes', $view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar mostrar detalhes.');
        }
    }

    public function matricular($id)
    {
        $retorno = $this->trilhasService->matricular(['id'=>$id, 'realizar' => true]);

        if($retorno===true){

            return redirect('trilhas/matriculado/'.$id.'/roteiro');
        }
        else{
            return back()->with('erro', 'Erro ao tentar realizar matricula.');
        }
    }

    public function listaRoteiro($id)
    {
        $retorno = $this->trilhasService->listaRoteiro(['id'=>$id, 'realizar' => true]);

        if($retorno!==false){
            $estatistica =  $this->trilhasService->getEstatisticaRoteiro( $retorno->cursos->pluck('id')->toArray());
            $view = [
                'dados'        => $retorno,
                'estatistica'  => $estatistica,
            ];
            return view('fr/roteiro/index_aluno', $view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar listar roteiros.');
        }
    }
}
