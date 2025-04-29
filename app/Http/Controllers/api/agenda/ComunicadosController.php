<?php

namespace App\Http\Controllers\api\agenda;

use App\Http\Controllers\Controller;

use App\Http\Resources\Api\Agenda\ComunicadoResource;
use App\Services\Fr\Agenda\ComunicadoService;
use Illuminate\Http\Request;

class ComunicadosController extends Controller
{
    public function __construct(ComunicadoService $comunicadoService)
    {
        $this->middleware('auth:api');
        $this->comunicadoService = $comunicadoService;
    }

    public function index(Request $request){
        $dados = $this->comunicadoService->lista(['publicado'=>1]);
        $dados = ComunicadoResource::collection($dados);
        return response()->json($dados->response()->getData(true));
    }
}
