<?php

namespace App\Http\Controllers\api\agenda;

use App\Http\Controllers\Controller;

use App\Http\Resources\Api\Agenda\RegistroResource;
use App\Services\Fr\Agenda\RegistroService;
use Illuminate\Http\Request;

class RegistrosController extends Controller
{
    public function __construct(RegistroService $registroService)
    {
        $this->middleware('auth:api');
        $this->registroService = $registroService;
    }

    public function index(Request $request){
        $query = [
            'aluno_id'  => $request->input('aluno_id'),
        ];
        $dados = $this->registroService->getRegistroResposavelApi($query);
        $dados = RegistroResource::collection($dados);
        return response()->json($dados->response()->getData(true));
    }
}
