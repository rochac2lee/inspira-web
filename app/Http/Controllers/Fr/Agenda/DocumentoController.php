<?php

namespace App\Http\Controllers\Fr\Agenda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\Agenda\DocumentoRequest;
use App\Models\FrAgendaDocumentosRecebidos;
use App\Models\User;
use App\Services\Fr\Agenda\CalendarioService;
use App\Services\Fr\Agenda\DocumentoService;
use App\Services\Fr\Agenda\PushNotificationService;
use App\Services\Fr\TurmaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function __construct( DocumentoService $documentoService, TurmaService $turmaService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->permissao != 'I' && auth()->user()->permissao != 'R' && auth()->user()->permissao != 'C') {
                return redirect('/');
            }
            return $next($request);
        });

        $this->documentoService = $documentoService;
        $this->turmaService = $turmaService;
    }

    public function index(Request $request){
        $view = [
            'dados' => $this->documentoService->lista($request->all()),
        ];
        return view('fr.agenda.documentos.lista',$view);
    }

    public function novo(){
        $view = [];
        return view('fr.agenda.documentos.form', $view);
    }

    public function add(DocumentoRequest $request){
        $retorno = $this->documentoService->inserir($request->all(), $request->file('arquivo'));

        if($retorno===true){
            return redirect('/gestao/agenda/documentos')->with('certo', 'Documento cadastrado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar documento.');
        }
    }

    public function excluir($idTarefa){
        $retorno = $this->documentoService->excluir($idTarefa);

        if($retorno===true){
            return redirect('/gestao/agenda/documentos')->with('certo', 'Documento excluído.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir documento.');
        }
    }

    public function publicar($idTarefa){
        $retorno = $this->documentoService->publicar($idTarefa);

        if($retorno===true){
            return redirect('/gestao/agenda/documentos')->with('certo', 'Documento publicado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar publicar documento.');
        }
    }

    public function editar($idComunicado){
        $dados = $this->documentoService->get($idComunicado);
        if($dados){
            $view = [
                'dados'=> $dados,
            ];
            return view('fr.agenda.documentos.form', $view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar documento.');
        }
    }

    public function update(DocumentoRequest $request){
        $retorno = $this->documentoService->update($request->all(),$request->file('arquivo'));

        if($retorno===true){
            return redirect('/gestao/agenda/documentos')->with('certo', 'Documento editado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar documento.');
        }
    }

    public function downloadArquivo($idTarefa){
        $retorno = $this->documentoService->get($idTarefa);
        if($retorno->link_arquivo) {
            ob_end_clean();
            return Storage::download($retorno->link_arquivo_download);
        }
        else{
            return back()->with('erro', 'Erro ao tentar realizar download do arquivo.');
        }
    }

    public function exibirTarefa($idTarefa){
        $dados = $this->documentoService->get($idTarefa);
        $view = [
            'dados' => $dados,
        ];
        $retorno =  view('fr.agenda.documentos.exibir',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function getTurmas(Request $request){
        $dados = $this->turmaService->lista(auth()->user()->escola_id, $request->all(), 10);
        $view = [
            'dados' => $dados,
            'turma' => $request->input('turma'),
            'aluno' => $request->input('aluno'),
        ];
        $retorno =  view('fr.agenda.documentos.tabelaTurmas',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function getTurmasSelecionadas(Request $request){
        $dados = $this->documentoService->getTurmasSelecionadas($request->all());
        $view = [
            'dados' => $dados,
            'alunos' => $request->input('aluno'),
        ];
        $retorno =  view('fr.agenda.documentos.listaTurmasAlunosSelecionados',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function recebidos(Request $request, $documentosId){
        $view =[
            'documento' => $this->documentoService->get($documentosId),
            'dados' => $this->documentoService->getRecebidos($documentosId, $request->all()),
        ];
        return view('fr.agenda.documentos.listaRecebidos',$view);
    }

    public function getRecebidos(Request $request){
        $view =[
            'dados' => $this->documentoService->getRecebidosArquivos($request->all()),
        ];
        $retorno = view('fr.agenda.documentos.listaRecebidosArquivos',$view)->render();
        return response()->json($retorno);
    }

    public function downloadRecebidos($recebidoId){
        $retorno = $this->documentoService->findRecebido($recebidoId);
        if($retorno->arquivo != '') {
            ob_end_clean();
            return Storage::download($retorno->link_arquivo_download);
        }
        else{
            return back()->with('erro', 'Erro ao tentar realizar download do arquivo.');
        }
    }

    public function teste(){
        $not = new CalendarioService();
        $not = $not->getCalendarioApi();
        dd($not);

        /*$not = new PushNotificationService();
        $dados =[
            'id' => 9,
            'titulo' => 'INspira Agenda - Espaço da Família',
            'corpo' => 'teste',
            'tipo' => 'agenda',
            'escola_id' => 183,
            'tipo_permissao' => 'C',
        ];
        $not->addNotificacaoUsuario($dados);
        return 1;
        */
    }
}
