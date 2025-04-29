<?php

namespace App\Http\Controllers\api\agenda;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Agenda\AutorizacoesAutorizarRequest;
use App\Http\Resources\Api\Agenda\AutorizacaoResource;
use App\Services\Fr\Agenda\AutorizacaoService;
use Illuminate\Http\Request;

class AutorizacaoController extends Controller
{
    public function __construct(AutorizacaoService $autorizacaoService)
    {
        $this->middleware('auth:api');
        $this->autorizacaoService = $autorizacaoService;
    }

    public function index(Request $request){
        $query = [
            'publicado' => 1,
        ];
        $dados = $this->autorizacaoService->lista($query);
        $dados = AutorizacaoResource::collection($dados);
        return response()->json($dados->response()->getData(true));
    }

    public function envio(AutorizacoesAutorizarRequest $request){
        if(auth()->user()->permissao != 'R'){
            return response()->json(['message'=>'Operação não permitida com essa permissão do usuário.'],500);
        }

        $dados = $this->autorizacaoService->autorizar($request->all());
        if($dados===true){
            return response()->json(['message'=>true]);
        }else{
            return response()->json(['message'=>$dados],500);
        }
    }
}
