<?php

namespace App\Http\Controllers\Fr;

use Auth;
use App\Models\CicloEtapa;
use Illuminate\Http\Request;
use App\Models\ColecaoLivros;
use App\Models\Disciplina;
use App\Services\Fr\QrCodeService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\QRCodeRequest;
use Illuminate\Support\Facades\Storage;


/* Remover Teste Local */
use App\Models\FrQrCode;


class QrCodeController extends Controller
{
    public function __construct( QrCodeService $qrCodeService)
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao != 'Z')
            {
                return back();
            }
            return $next($request);
        });
        $this->qrCodeService = $qrCodeService;
    }

    public function index(Request $request)
    {
        $view = [
            'qrcode' => $this->qrCodeService->lista($request),
            'disciplinas' => $this->qrCodeService->listaDisciplina($request),
            'cicloEtapa' => $this->qrCodeService->cicloEtapaList($request),
            'colecaoLivros' => $this->qrCodeService->listaColecaoLivros($request),

        ];
        return view('fr.gestao.qrcode.lista', $view);
    }


    public function novo()
    {
        $view = [
            'cicloEtapa' => $this->qrCodeService->cicloEtapaList(),
            'colecaoLivros' => $this->qrCodeService->listaColecaoLivros(),
            'disciplinas' => $this->qrCodeService->listaDisciplina(),
        ];
        return view('fr.gestao.qrcode.form', $view);
    }

    public function hub()
    {
        $view = [
           
        ];
        return view('fr.hub.hub', $view);
    }

    public function edita($id)
    {
        $qr = $this->qrCodeService->info($id);

        $colecaoLivros = ColecaoLivros::where('tipo', 21)->get();
        $cicloEtapas = CicloEtapa::join('ciclos', 'ciclos.id', 'ciclo_etapas.ciclo_id')
                                ->orderBy('ciclos.titulo')
                                ->orderBy('ciclo_etapas.titulo')
                                ->selectRaw('ciclo_etapas.id, ciclos.titulo as ciclo, ciclo_etapas.titulo as ciclo_etapa')
                                ->get();
        $disciplinas = Disciplina::all();
        $view = [
            'dados' => $qr,
            'colecaoLivros' => $colecaoLivros,
            'cicloEtapa' => $cicloEtapas,
            'disciplinas' => $disciplinas,
        ];

        return view('fr.gestao.qrcode.form', $view);
    }



    public function add(QRCodeRequest $request)
    {
        // Capturar o ID do usuário autenticado
        $userId = auth()->user()->id;
        $dados = [
            'user_id' => $userId,  // Incluindo o ID do usuário nos dados
            'id' => $request->input('id'),
            'descricao' => $request->input('descricao'),
            'url' => $request->input('url'),
            'observacao' => $request->input('observacao'),
            'tipo_midia' => $request->input('tipo_midia'),
            'disciplina_id' => $request->input('disciplina'),
            'colecao_livro_id' => $request->input('colecaoLivros'),
            'ciclo_etapa_id' => $request->input('cicloEtapa'),
        ];

        $retorno = $this->qrCodeService->inserir($dados);

        if ($retorno === true) {
            return redirect('gestao/qrcode/')->with('certo', 'QRCode cadastrado.');
        } else {
            return redirect('gestao/qrcode/')->with('erro', 'Erro ao tentar cadastrar QRCode. ' . $retorno);
        }
    }


    public function edit(QRCodeRequest $request)
    {
        $retorno = $this->qrCodeService->editar($request->all());

        if ($retorno === true) {
            return redirect('gestao/qrcode/')->with('certo', 'QRCode alterado.');
        } else {
            return redirect('gestao/qrcode/')->with('erro', 'Erro ao tentar alterar QRCode. ' . $retorno);
        }
    }
    
    public function downloadImagem($id)
    {

        $caminho = $this->qrCodeService->imagemQrCode($id);
        ob_end_clean();
        return Storage::download($caminho);

    }    

    public function deletar($id)
    {

        $retorno = $this->qrCodeService->deletar($id);
        if($retorno===true){
            return redirect('gestao/qrcode/')->with('certo', 'QRCode excluido.');
        }
        else{
            return redirect('gestao/qrcode/')->with('erro', 'Erro ao tentar excluir QRCode. '.$retorno);
        }

    }
}
