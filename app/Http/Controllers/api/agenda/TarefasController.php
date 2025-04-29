<?php

namespace App\Http\Controllers\api\agenda;

use App\Http\Controllers\Controller;

use App\Http\Resources\Api\Agenda\TarefaResource;
use App\Services\Fr\Agenda\TarefaService;
use Illuminate\Http\Request;

class TarefasController extends Controller
{
    public function __construct(TarefaService $tarefaService)
    {
        $this->middleware('auth:api');
        $this->tarefaService = $tarefaService;
    }

    public function index(Request $request){
        $query = [
            'publicado' => 1,
            'aluno_id'  => $request->input('aluno_id'),
        ];
        $dados = $this->tarefaService->lista($query);
        $dados = TarefaResource::collection($dados);
        return response()->json($dados->response()->getData(true));
    }
}
