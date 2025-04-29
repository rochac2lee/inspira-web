<?php

namespace App\Http\Controllers\api\agenda;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Agenda\DocumentosRecebidosRequest;
use App\Http\Resources\Api\Agenda\DocumentoResource;
use App\Services\Fr\Agenda\DocumentoService;
use Illuminate\Http\Request;

class DocumentosController extends Controller
{
    public function __construct(DocumentoService $documentosService)
    {
        $this->middleware('auth:api');
        $this->documentosService = $documentosService;
    }



    public function index(Request $request){
        $query = [
            'publicado' => 1,
            'aluno_id' => $request->input('aluno_id'),
        ];
        $dados = $this->documentosService->lista($query);
        $dados = DocumentoResource::collection($dados);
        return response()->json($dados->response()->getData(true));
    }

    public function envio(DocumentosRecebidosRequest $request){
        if(auth()->user()->permissao != 'R'){
            return response()->json(['message'=>'Operação não permitida com essa permissão do usuário.'],500);
        }

        $dados = $this->documentosService->gravaArquivoEnviado($request);
        if($dados===true){
            return response()->json(['message'=>true]);
        }else{
            return response()->json(['message'=>$dados],500);
        }
    }
}
