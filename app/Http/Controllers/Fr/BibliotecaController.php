<?php

namespace App\Http\Controllers\Fr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\Models\ColecaoLivros;
use App\Services\Fr\BibliotecaService;
use Auth;

class BibliotecaController extends Controller
{
    protected $permitidos;

    public function __construct(BibliotecaService $bibliotecaService)
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao != 'A' && Auth::user()->permissao != 'R')
            {
                $this->permitidos = [2,3,4,22,100,101,102, 103, 104, 105, 106];
            }
            else
            {
                $this->permitidos = [2,3,22,100,101,103,106];
            }

            return $next($request);
        });

        $this->bibliotecaService = $bibliotecaService;

        /// define a pasta das imagens de capas dos conteúdos
        /// 'tipo_conteudo' => 'pasta_das_capas'

        $this->download = [
            '2'=> '',
            '3'=> '',
            '4'=> '',
            '8'=> '',
            '22'=> '',
            '100'=> 'banco_imagens/',
            '101'=> '',
            '102'=> 'provas/',
            '103'=> '',
            '104'=> 'pdf_bncc/',
            '105'=> 'pdf_tabelas_trimestrais/',
            '106'=> '',
        ];

        $this->capas = [
            '2'=> '/storage/capa_audios/',
            '3'=> '/storage/capa_videos/',
            '4'=> '/storage/capa_ppt/',
            '8'=> '',
            '22'=> '',
            '100'=> '/storage/banco_imagens/thumbs/',
            '101'=> '/storage/capa_simulacao/',
            '102'=> '/storage/capa_provas/',
            '103'=> '/storage/capa_jogos/',
            '104'=> '/storage/capa_bncc/',
            '105'=> '/storage/capa_tabela_trimestrais/',
            '106'=> '/storage/capa_videos_acao_destaque/',
        ];

        /// define a capa padrão
        $this->capaPadrao = [
            '2' => '/fr/imagens/audio.png',
            '3' => '/fr/imagens/video_play.png',
            '4' => '/fr/imagens/slide.png',
            '8' => '/fr/imagens/quiz.png',
            '22' => '/fr/imagens/documentos_oficiais.png',
            '100' => '/fr/imagens/documentos_oficiais.png',
            '101' => '/fr/imagens/simuladores.png',
            '102' => '/fr/imagens/provas_bimestrais1.png',
            '103' => '',
            '104' => '',
            '105' => '',
            '106' => '',

        ];

        /// define a capa padrão
        $this->tituloPadrao = [
            '2' => 'Áudios',
            '3' => 'Vídeos',
            '4' => 'Apresentações',
            '8' => 'Quiz',
            '22' => 'Documentos Oficiais',
            '100' => 'Banco de imagens',
            '101' => 'Simuladores',
            '102' => 'Provas',
            '103' => 'Jogos',
            '104' => 'Tabelas da BNCC',
            '105' => 'Tabelas Trimestrais',
            '106' => 'Ação Destaque',
        ];

        $this->capasColecoes = [
            '2'=> '/storage/colecaoaudio/',
            '3'=> '/storage/colecaovideo/',
            '4'=> '/storage/colecaoppt/',
            '8'=> '',
            '22'=> '',
            '100'=> '/storage/capa_banco_imagens/',
            '101'=> '',
            '102'=> '/storage/colecaoprova/',
            '103'=> '',
            '104'=> '/storage/colecaolivro/',
            '105'=> '/storage/colecaolivro/',
            '106'=> '/storage/colecao_videos_acao_destaque/',
        ];




    }

    public function colecaoConteudoEditora(Request $request)
    {
        //// tipo de conteudos permitidos para serem exebidos como editora
        $permitidos = $this->permitidos;

        /// tipo de conteudo selecionado na url
        $conteudoTipo = $request->input('conteudo');

                /// verifica se pode ser exibido como editora
        if(in_array($conteudoTipo, $permitidos))
        {
            $dados = $this->bibliotecaService->listaColecaoConteudo($conteudoTipo,$request,1);
            $view = [
                'dados'     => $dados,
                'titulo'    => $this->tituloPadrao[$conteudoTipo],
                'capa'      => $this->capasColecoes[$conteudoTipo],
            ];

            return view('fr/biblioteca/colecao',$view);
        }
        else
        {
            return back();
        }

    }

    public function conteudoEditora(Request $request)
    {
        //// tipo de conteudos permitidos para serem exebidos como editora
        $permitidos = $this->permitidos;

        /// tipo de conteudo selecionado na url
        $conteudoTipo = $request->input('conteudo');

        /// verifica se pode ser exibido como editora
        if(in_array($conteudoTipo, $permitidos))
        {
            $colecao = $request->input('colecao');
            if( $colecao != '')
            {
                $colecao = ColecaoLivros::find($colecao);
            }
            $dados = $this->bibliotecaService->listaConteudo($conteudoTipo,$request,1);
            $view = [
                'pesquisa'  => $this->bibliotecaService->defineMenuPesquisa($conteudoTipo,$request->input('colecao'), $request->input('etapa'), 1),
                'dados'     => $dados,
                'conteudos' => $this->bibliotecaService->definirExebirConteudo($dados),
                'titulo'    => $this->tituloPadrao[$conteudoTipo],
                'capaPadrao'=> $this->capaPadrao[$conteudoTipo],
                'capa'      => $this->capas[$conteudoTipo],
                'colecao'   => $colecao,
                'download' => $this->download,
            ];
            if($conteudoTipo==102)
            {
                $view['disciplinas'] = $this->bibliotecaService->disciplinasDisponiveis($conteudoTipo,1);
            }
            return view('fr/biblioteca/listaConteudo',$view);
        }
        else
        {
            return back();
        }

    }

    public function downloadConteudoEditora($idConteudo)
    {
        $conteudo = $this->bibliotecaService->getConteudo($idConteudo);
        if(isset($conteudo->id) && $conteudo->id >0)
        {
            if(in_array($conteudo->tipo, $this->permitidos) && ($conteudo->permissao_download==1 || $conteudo->tipo == 100)){
                ob_end_clean();
                if($conteudo->tipo != 104 && $conteudo->tipo != 105){
                    return Storage::download(config('app.frStorage').$this->download[$conteudo->tipo].$conteudo->conteudo);
                }else{
                    return Storage::download(config('app.frStorage').$this->download[$conteudo->tipo].$conteudo->id.'.pdf');
                }
            }
            else
            {
                return back();
            }
        }
        else
        {
            return back();
        }
    }

    public function ajaxListaColecaoParaRoteiros(Request $request)
    {
        $dados = $this->bibliotecaService->listaColecaoConteudoRoteiro($request->input('idTipo'),$request,1);
        $retorno = '<option>Selecione uma coleção</option>';
        $i = 0;
        foreach($dados as $c)
        {
            $selected = '';
            if($i == 0){
                $selected = 'selected';
            }
            $retorno .= '<option '.$selected.' value="'.$c->id.'">'.$c->nome.'</option>';
            $i++;
        }
        return $retorno;
    }

    public function ajaxListaConteudosParaRoteiros(Request $request)
    {

        $conteudos = $this->bibliotecaService->getConteudosParaRoteiros($request->input('idTipo'),$request);
        $retorno = '';
        foreach($conteudos as $conteudo){
            $retorno .= '<div class="item row" data-tipo="'.$conteudo['tipo'].'" onclick="clicaConteudoBiblioteca(this)">
                <input type="checkbox" name="conteudosIds[]" value="'.$conteudo['id'].'" />
                <div class="col col-md-12 d-flex align-items-center">
                    <div class="icon">
                        <i class="'.$conteudo->getTipoIconAttribute().' fa-fw fa-2x"></i>

                    </div>
                    <div class="title-author">
                        <h6>'.$conteudo['titulo'].'</h6>
                        <span>'.$conteudo->nome_completo.'</span>
                    </div>
                </div>
            </div>';
        }
        $style = '';
        if($retorno != ''){
            $style='style="display:none"';
        }
            $retorno .= '
                <div class="item row not-found" '.$style.'>
                    <div class="col col-md-12 d-flex align-items-center">
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="title-author">
                            <h6>Conteúdo não encontrado.</h6>
                        </div>
                    </div>
                </div>
            ';
        return $retorno;
    }

    public function conteudoEditoraBncc(Request $request){
        $dados = $this->bibliotecaService->conteudoBncc($request->input('conteudo'));
        if($dados) {
            return view('fr.biblioteca.conteudoBncc', ['dados' => $dados]);
        }
        else{
            return back();
        }
    }
}
