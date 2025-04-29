<?php

namespace App\Http\Controllers\api\agenda;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Agenda\EstudantesResponsavelResource;
use App\Models\User;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function estudantesResponsavel(Request $request){
        if(auth()->user()->permissao != 'R'){
            return response()->json(['message'=>'É necessário que o usuário seja do tipo Responsável.'],500);
        }
        $dados = User::with('alunosDoResponsavel')->find(auth()->user()->id);
        $dados = EstudantesResponsavelResource::collection($dados->alunosDoResponsavel);
        return response()->json($dados->response()->getData(true));
    }
}
