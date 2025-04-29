<?php

namespace App\Http\Controllers\api\agenda;

use App\Http\Controllers\Controller;

use App\Http\Resources\Api\Agenda\AutorizacaoResource;
use App\Http\Resources\Api\Agenda\CanaisAtendimentoResource;
use App\Services\Fr\Agenda\AutorizacaoService;
use App\Services\Fr\Agenda\CanaisAtendimentoService;
use Illuminate\Http\Request;

class CanaisAtendimentoController extends Controller
{
    public function __construct(CanaisAtendimentoService $canaisAtendimentoService)
    {
        $this->middleware('auth:api');
        $this->canaisAtendimentoService = $canaisAtendimentoService;
    }

    public function index(Request $request){
        $query = [
            'publicado' => 1,
            'aluno_id' => $request->input('aluno_id'),
        ];
        $dados = $this->canaisAtendimentoService->getCanaisAtendimentoResponsavelApi($query);
        $dados = CanaisAtendimentoResource::collection($dados);
        return response()->json($dados->response()->getData(true));
    }
}
