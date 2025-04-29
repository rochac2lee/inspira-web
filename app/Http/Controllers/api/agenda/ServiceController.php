<?php

namespace App\Http\Controllers\api\agenda;

use App\Http\Controllers\Controller;

use App\Services\Fr\Agenda\AutorizacaoService;
use App\Services\Fr\Agenda\CalendarioService;
use App\Services\Fr\Agenda\ComunicadoService;
use App\Services\Fr\Agenda\DocumentoService;
use App\Services\Fr\Agenda\EnqueteService;
use App\Services\Fr\Agenda\FamiliaService;
use App\Services\Fr\Agenda\NoticiaService;
use App\Services\Fr\Agenda\RegistroService;
use App\Services\Fr\Agenda\TarefaService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(FamiliaService $familiaService, ComunicadoService $comunicadoService, NoticiaService $noticiaService,
                                TarefaService $tarefaService, RegistroService $registroService, CalendarioService $calendarioService, DocumentoService $documentoService,
                                AutorizacaoService $autorizacaoService, EnqueteService $enqueteService)
    {
        $this->middleware('auth:api');
        $this->familiaService       = $familiaService;
        $this->comunicadoService    = $comunicadoService;
        $this->noticiaService       = $noticiaService;
        $this->tarefaService        = $tarefaService;
        $this->registroService      = $registroService;
        $this->calendarioService    = $calendarioService;
        $this->documentoService     = $documentoService;
        $this->autorizacaoService   = $autorizacaoService;
        $this->enqueteService       = $enqueteService;
    }

    public function ultimosCadastrados(Request $request){
        $query = [
            'publicado' => 1,
            'aluno_id' => $request->input('aluno_id'),
            'ultimo' => 1,
        ];

        if(auth()->user()->permissao == 'R'){
            $registro = $this->registroService->getUltimoResgistroResponsavel()->ultimo;
        }else{
            $registro = 0;
        }

        $retorno = [
            'familia'       => (int)$this->familiaService->lista($query)->ultimo,
            'comunicado'    => (int)$this->comunicadoService->lista($query)->ultimo,
            'tarefa'        => (int)$this->tarefaService->lista($query)->ultimo,
            'noticia'       => (int)$this->noticiaService->lista($query)->ultimo,
            'registro'      => (int)$registro,
            'calendario'    => (int)$this->calendarioService->getCalendarioApi($query)->ultimo,
            'documento'     => (int)$this->documentoService->lista($query)->ultimo,
            'autorizacao'   => (int)$this->autorizacaoService->lista($query)->ultimo,
            'enquete'       => (int)$this->enqueteService->lista($query)->ultimo,
        ];

        $obj = new \stdClass();
        $obj->data = $retorno;
        return response()->json($obj);
    }


}
