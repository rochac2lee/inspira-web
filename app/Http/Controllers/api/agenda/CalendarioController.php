<?php

namespace App\Http\Controllers\api\agenda;

use App\Http\Controllers\Controller;

use App\Services\Fr\Agenda\CalendarioService;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    public function __construct(CalendarioService $calendarioService)
    {
        $this->middleware('auth:api');
        $this->calendarioService = $calendarioService;
    }

    public function index(Request $request){
        $query = [
            'aluno_id'  => $request->input('aluno_id'),
        ];
        $dados = $this->calendarioService->getCalendarioApi($query);
        $obj = new \stdClass();
        $obj->data = $dados;
        return response()->json($obj);
    }
}
