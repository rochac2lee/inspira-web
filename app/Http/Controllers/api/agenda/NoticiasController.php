<?php

namespace App\Http\Controllers\api\agenda;

use App\Http\Controllers\Controller;

use App\Http\Resources\Api\Agenda\NoticiaResource;
use App\Services\Fr\Agenda\NoticiaService;
use Illuminate\Http\Request;

class NoticiasController extends Controller
{
    public function __construct(NoticiaService $noticiaService)
    {
        $this->middleware('auth:api');
        $this->noticiaService = $noticiaService;
    }

    public function index(Request $request){
        $dados = $this->noticiaService->lista(['publicado'=>1]);

        $dados = NoticiaResource::collection($dados);
        return response()->json($dados->response()->getData(true));
    }
}
