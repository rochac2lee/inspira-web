<?php

namespace App\Http\Controllers\api\agenda;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Agenda\EnqueteRespostaRequest;
use App\Http\Resources\Api\Agenda\EnqueteResource;
use App\Services\Fr\Agenda\EnqueteService;
use Illuminate\Http\Request;

class EnquetesController extends Controller
{
    public function __construct(EnqueteService $enqueteService)
    {
        $this->middleware('auth:api');
        $this->enqueteService = $enqueteService;
    }

    public function index(Request $request){
        $query = [
            'publicado' => 1,
        ];
        $dados = $this->enqueteService->lista($query);
        $dados = EnqueteResource::collection($dados);
        return response()->json($dados->response()->getData(true));
    }

    public function envio(EnqueteRespostaRequest $request){
        if(auth()->user()->permissao != 'R'){
            return response()->json(['message'=>'Operação não permitida com essa permissão do usuário.'],500);
        }

        $dados = $this->enqueteService->responder($request->all());
        if($dados===true){
            return response()->json(['message'=>true]);
        }else{
            return response()->json(['message'=>$dados],500);
        }
    }
}
