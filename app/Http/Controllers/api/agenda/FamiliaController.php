<?php

namespace App\Http\Controllers\api\agenda;

use App\Http\Controllers\Controller;

use App\Http\Resources\Api\Agenda\FamiliaResource;
use App\Services\Fr\Agenda\FamiliaService;
use Illuminate\Http\Request;

class FamiliaController extends Controller
{
    public function __construct(FamiliaService $familiaService)
    {
        $this->middleware('auth:api');
        $this->familiaService = $familiaService;
    }

    public function index(Request $request){
        $dados = $this->familiaService->lista(['publicado'=>1]);

        $dados = FamiliaResource::collection($dados);
        return response()->json($dados->response()->getData(true));
    }
}
