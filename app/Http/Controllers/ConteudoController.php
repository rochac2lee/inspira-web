<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Illuminate\Support\Facades\Storage;
use Redirect;
use Session;
use Carbon\Carbon;

use App\Models\Conteudo;
use App\Models\ConteudoAula;
//use App\Models\ProgressoConteudo;
//use App\Models\InteracaoConteudo;
use App\Models\AvaliacaoConteudo;
use App\Models\AvaliacaoInstrutor;
//use App\Models\MensagemTransmissao;
//use App\Services\ConteudoService;


class ConteudoController extends Controller
{
    function playConteudo($idConteudo, Request $request)
    {
        if (Conteudo::find($idConteudo))
        {
            $conteudo = Conteudo::find($idConteudo);
        }
        else
        {
            return Redirect::back()->withErrors(['Conteúdo não encontrado!']);
        }

        // Histórico
        if (Historico::where([
                ['user_id', Auth::user()->id],
                ['referencia_id', $idConteudo],
                ['tipo', 2],
                ['created_at', '>', (Carbon::now()->subMinutes(15))]])
                ->exists() == false)
                {
            Historico::create([
                'user_id'       => Auth::user()->id,
                'referencia_id' => $idConteudo,
                'tipo'          => 2
            ]);
        }

        if ($conteudo->status != 1)
        {
            if (Auth::check() ? (strtolower(Auth::user()->permissao) != "z" && $conteudo->user_id != Auth::user()->id) : true)
            {
                return Redirect::back()->withErrors(['Conteúdo não encontrado!']);
            }
            else
            {
                Session::flash('previewMode', true);
            }
        }

        $conteudo->conteudo = $this->playGetConteudoHtml($idConteudo, $conteudo);

        $conteudo->qtAvaliacoesPositivas = AvaliacaoConteudo::where([['avaliacao', '=', '1'], ['conteudo_id', '=', $idConteudo]])->count();

        $conteudo->qtAvaliacoesNegativas = AvaliacaoConteudo::where([['avaliacao', '=', '0'], ['conteudo_id', '=', $idConteudo]])->count();

        $conteudo->minhaAvaliacao = AvaliacaoConteudo::where([['user_id', '=', Auth::user()->id], ['conteudo_id', '=', $idConteudo]])->first();

        if (!ProgressoConteudo::where([['conteudo_id', '=', $idConteudo], ['tipo', '=', 2], ['user_id', '=', Auth::user()->id]])->exists()) {
            ProgressoConteudo::create([
                'conteudo_id' => $idConteudo,
                'tipo'        => 2,
                'user_id'     => Auth::user()->id
            ]);
        }

        \App\ConsumoConteudo::create([
            'user_id' => Auth::user()->id,
            'curso_id' => null,
            'aula_id' => null,
            'conteudo_id' => $idConteudo,
            'consumo' => $conteudo->file_size
        ]);

        return view('play.conteudo')->with(compact('conteudo'));
    }

    public function playGetConteudoHtml($idConteudo, $conteudo)
    {
        switch ($conteudo->tipo) {
            case 2:
                return $this->setConteudoAudio($idConteudo, $conteudo);
                break;
            case 3:
            case 106:
                return $this->setConteudoVideo($idConteudo, $conteudo);
                break;
            case 4:
                return $this->setConteudoSlide($idConteudo, $conteudo);
                break;
            case 5:
                return $this->setConteudoTransmissao($idConteudo, $conteudo);
                break;
            case 6:
                return $this->setConteudoUpload($idConteudo, $conteudo);
                break;
            case 7:
                $questoes = $conteudo->questoes;
                $conteudo->conteudo = ConteudoService::ajustarQuestoes($questoes);
                return $this->setConteudoDissertativa($idConteudo, $conteudo);
                break;
            case 8:
                $questoes = $conteudo->questoes;
                $conteudo->conteudo = ConteudoService::ajustarQuestoes($questoes);
                return $this->setConteudoQuiz($idConteudo, $conteudo);
                break;
            case 9:
                $questoes = $conteudo->questoes;
                $conteudo->conteudo = ConteudoService::ajustarQuestoes($questoes);
                return $this->setConteudoProva($idConteudo, $conteudo);
                break;
            case 10:
                return $this->setConteudoEntregavel($idConteudo, $conteudo);
                break;
            case 11:
                return $this->setConteudoLivroDigital($idConteudo, $conteudo);
                break;
            case 15:
                return $this->setConteudoPDF($idConteudo, $conteudo);
                break;
            case 22:
                return $this->setConteudoPDF($idConteudo, $conteudo);
                break;
            case 100:
                return $this->setConteudoBancoImagem($idConteudo, $conteudo);
                break;
            case 101:
                return $this->setConteudoSimuladores($idConteudo, $conteudo);
                break;
            case 102:
                return $this->setConteudoProvasBimestrais($idConteudo, $conteudo);
                break;
            case 103:
                return $this->setConteudoJogos($idConteudo, $conteudo);
                break;
            case 104:
                return $this->setConteudoTabelaBncc($idConteudo, $conteudo);
                break;
            default:
                return $conteudo->conteudo;
                break;
        }
    }

    function playGetArquivo($idConteudo)
    {
        $conteudo = Conteudo::where([['id', '=', $idConteudo]])->first();
        if ($conteudo != null)
        {
            if (strpos($conteudo->conteudo, ".ppt") === false && strpos($conteudo->conteudo, ".html") === false)
            {
                if (!Auth::check())
                {
                    return response()->json(['error' => 'Usuário não autenticado!']);
                }
            }else{
                    return response()->json(['error' => 'Arquivo não aceito! Arquivos aceitos são apenas  .ppt, .html e .jpg']);
            }

            $idCurso = ConteudoAula::where([['conteudo_id', '=', $idConteudo]])->first() != null ? ConteudoAula::where([['conteudo_id', '=', $idConteudo]])->first()->curso_id : null;

            if (Storage::disk()->has(config('app.frStorage') . $conteudo->conteudo))
            {
                $filePath = config('app.frStorage') . $conteudo->conteudo;
                if(strpos($conteudo->conteudo, ".jpg") !== false || strpos($conteudo->conteudo, ".png") !== false){
                    $path = asset('storage/' . $conteudo->conteudo);
                   return $conteudo->conteudo = ' <img style="width: 100%; height: 41vw;"
                                                    src="'.$path.'">';
                }else{
                    return Storage::disk()->response($filePath);
                }
            }
            else if(Storage::disk()->has(config('app.frStorage') . $conteudo->conteudo) && $idCurso != null)
            {
                $filePath = config('app.frStorage').$conteudo->conteudo;
                if(strpos($conteudo->conteudo, ".jpg") !== false || strpos($conteudo->conteudo, ".png") !== false){
                    $path = asset('storage/' . $conteudo->conteudo);
                   return $conteudo->conteudo = ' <img style="width: 100%; height: 41vw;"
                                                    src="'.$path.'">';
                }else{
                    return Storage::disk()->response($filePath);
                }

            }
            else
            {
                return response()->view('errors.404');
            }
        }
        else
        {
            return response()->view('errors.404');
        }
    }

    public function postEnviarAvaliacaoConteudo($idConteudo, Request $request)
    {
        if (Conteudo::where([['id', '=', $idConteudo]])->first() != null) {
            AvaliacaoConteudo::updateOrCreate(
                [
                    'user_id'     => Auth::user()->id,
                    'conteudo_id' => $idConteudo,
                ],
                ['avaliacao' => $request->avaliacao]
            );

            return response()->json(['success' => 'Avaliação enviada com sucesso!']);
        } else {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }
    }

    public function postEnviarInteracaoConteudo($idConteudo, Request $request)
    {
        if (Conteudo::where([['id', '=', $idConteudo]])->first() != null) {
            InteracaoConteudo::create([
                'conteudo_id' => $idConteudo,
                'user_id'     => Auth::user()->id,
                'tipo'        => $request->tipo,
                'inicio'      => $request->inicio
            ]);

            return response()->json(['success' => 'Interação enviada com sucesso!']);
        } else {
            return response()->json(['error' => 'Conteúdo não encontrado!']);
        }
    }

    public function postEnviarAvaliacaoProfessor($idInstrutor, Request $request)
    {
        if ($request->comentario == null) {
            $request->comentario = '';
        }

        AvaliacaoInstrutor::updateOrCreate(
            [
                'user_id'      => Auth::user()->id,
                'instrutor_id' => $idInstrutor
            ],
            ['avaliacao' => $request->avaliacao, 'descricao' => $request->comentario]
        );

        return response()->json(['success' => 'Avaliação enviada com sucesso!']);
    }

    public function setConteudoAudio($idConteudo, $conteudo)
    {
        if (strpos($conteudo->conteudo, "soundcloud.com") !== false) {
            return '<iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=' . $conteudo->conteudo . '&color=%236766a6&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true"></iframe>';
        }
        else{
            if (strpos($conteudo->conteudo, "http") !== false || strpos($conteudo->conteudo, "www") !== false) {
                $url = $conteudo->conteudo;
            }
            else
            {
                if($conteudo->colecao_livro_id == ''){
                    $url = config('app.cdn').'/storage/cast/'.$conteudo->user_id.'/audio/'.$conteudo->conteudo;
                }else{
                    $url = route('conteudo.play.get-arquivo', ['idConteudo' => $idConteudo]);
                }
            }
            if($conteudo->capa == null)
            {
                $capa = config('app.cdn').'/fr/imagens/audio.png';
            }
            else
            {
                if($conteudo->colecao_livro_id == ''){ /// se for audio do cast
                    $capa = config('app.cdn').'/storage/cast/'.$conteudo->user_id.'/capa/'.$conteudo->capa;
                }else{
                    $capa = config('app.cdn').'/storage/capa_audios/'.$conteudo->capa;
                }
            }
            return '
                <div id="player1" data-playerid="200" class="audioplayer-tobe is-single-player " style="width:100%; margin-top:10px; margin-bottom: 10px;" data-thumb="'.$capa.'" data-thumb_link="'.$capa.'" data-type="audio" data-source="'.$url.'" data-fakeplayer="#ap1"  >
                    <div class="meta-artist"></div>
                </div>
            ';
        }


        /*elseif (strpos($conteudo->conteudo, "http") !== false || strpos($conteudo->conteudo, "www") !== false) {
            return '<audio controls style="width: 100%;">
                <source src="' . $conteudo->conteudo . '" type="audio/mp3">
                Your browser does not support the audio element.
            </audio>';
        } else {
            return '<audio controls style="width: 100%;">
                <source src="' . route('conteudo.play.get-arquivo', ['idConteudo' => $idConteudo]) . '" type="audio/mp3">
                Your browser does not support the audio element.
            </audio>';
        }
        */
    }

    public function setConteudoVideo($idConteudo, $conteudo)
    {
        if (strpos($conteudo->conteudo, "youtube") !== false || strpos($conteudo->conteudo, "youtu.be") !== false) {
            if (strpos($conteudo->conteudo, "youtu.be") !== false) {
                $conteudo->conteudo = str_replace("youtu.be", "youtube.com", $conteudo->conteudo);
            }

            $conteudo->conteudo = str_replace("/watch?v=", "/embed/", $conteudo->conteudo);

            if (strpos($conteudo->conteudo, "&") !== false) {
                $conteudo->conteudo = substr($conteudo->conteudo, 0, strpos($conteudo->conteudo, "&"));
            }

            return '<iframe src="' . $conteudo->conteudo . '" style="width: 100%; height: 32vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
            </iframe>';

        } elseif (strpos($conteudo->conteudo, "vimeo") !== false) {
            if (strpos($conteudo->conteudo, "player.vimeo.com") === false)
                $conteudo->conteudo = str_replace("vimeo.com/", "player.vimeo.com/video/", $conteudo->conteudo);

            return '<iframe src="' . $conteudo->conteudo . '" style="width: 100%; height: 32vw;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen>
            </iframe>';
        } else {
          /*  return '<video controls style="width: 100%; height: 32vw;">
                <source src="' . route('conteudo.play.get-arquivo', ['idConteudo' => $idConteudo]) . '" type="video/mp4">
                Your browser does not support the audio element.
            </video>'; */

            $path = asset('storage/' . $conteudo->conteudo);

            return  '<video id="player" controls style="width: 100%; height: 41vw;">
                            <source src="' . $path . '" type="video/mp4">
                            Your browser does not support the video element.
                        </video>
                        <script>
                            if(player != undefined)
                                player = new Plyr(\'#player\');
                            else
                                player = new Plyr(\'#player\');
                        </script> ';

        }
    }

    public function setConteudoSlide($idConteudo, $conteudo)
    {
        if(strpos($conteudo->conteudo, "http") === false && strpos($conteudo->conteudo, "www") === false) {
            //$url_conteudo = route('conteudo.play.get-arquivo', ['idConteudo' => $idConteudo]);
            $url_conteudo = config('app.cdn').'/storage/' . $conteudo->conteudo;
        } else {
            $url_conteudo = $conteudo->conteudo;
        }

        if (strpos($conteudo->conteudo, ".ppt") !== false || strpos($conteudo->conteudo, ".pptx") !== false)
        {
           return '<iframe src="https://docs.google.com/viewer?url=' . $url_conteudo . '&embedded=true" style="width: 100%; height: 41vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
        }
        elseif (strpos($conteudo->conteudo, ".html") !== false)
        {
            return '<iframe src="' . $url_conteudo . '" style="width: 100%; height: 41vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
            </iframe>';
        }
        elseif (strpos($conteudo->conteudo, "drive.google.com/file") !== false)
        {
            if (strpos($conteudo->conteudo, "/view") !== false)
            {
                $url_conteudo = str_replace("/view", "/preview", $url_conteudo);
            }

            return '<iframe src="' . $url_conteudo . '" style="width: 100%; height: 41vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
            </iframe>';
        } else {
            return '<object data="' . $url_conteudo . '" type="application/pdf" style="width: 100%; height: 41vw;">
            </object>';
        }
    }

    public function setConteudoApostila($idConteudo, $conteudo)
    {
        $url_apostila = config('app.local') . '/uploads/apostilas/' . $conteudo->id;

        return '<iframe src="' . config('app.local') . '/leitor_apostila/' . $conteudo->id . '?conteudo_id=' . $conteudo->id . '&url=' . $url_apostila . '" style="width: 100%; height: 115vh;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
            </iframe>';
    }

    public function setConteudoTransmissao($idConteudo, $conteudo)
    {
        $mensagensTransmissao = '';

        foreach (MensagemTransmissao::with('user')->where([['conteudo_id', '=', $idConteudo]])->orderBy('created_at', 'asc')->get() as $mensagem)
        {
            $mensagensTransmissao = $mensagensTransmissao . '<div id="divMensagem' . $mensagem->id . '" style="font-size: 1vw/*18px*/;color:  #BCBCBC;max-width: 100%;padding:  12px 8px;">
                <div style="display: inline-block;border: 2px solid var(--primary-color);vertical-align: middle;margin: 0px;background: url(' . route('usuario.perfil.image', [$mensagem->user->id]) . ');width: 45px;height: 45px;background-size: cover !important;background-repeat: no-repeat !important;background-position: 50% 50% !important;border-radius: 50%;margin-right:  8px;">
                </div>
                <div style="vertical-align:  middle;display:  inline-block;max-width: calc(100% - 58px);text-align:  -webkit-left;color: var(--primary-color);font-weight: bold;">
                    ' . ucfirst($mensagem->user->name) . ':
                    <span style="color: #354353;">
                        ' . $mensagem->mensagem . '
                    </span>
                </div>
            </div>';
        }

        return '<video id="my_video_1" class="video-js vjs-default-skin" controls preload="auto" style="width: 70%; height: 41vw; display: inline-block;"
        data-setup="">
            <source src="' . $conteudo->conteudo . '" type="application/x-mpegURL">
        </video>
        <div id="divMainChat" style="display:  inline-block;width: calc( 30% - 10px);vertical-align: top;text-align:  -webkit-center;height: 41vw;position: relative;transition: all .3s ease-in-out;">
            <div style="width:  100%;height:  100%;background-color: #F9F9F9;">
                <div style="padding: 14px 4px;color: white;font-size: 1.2vw;background-color: var(--primary-color);max-height: 51px;overflow: hidden;text-overflow:  ellipsis;white-space:  nowrap;text-transform:  uppercase;">
                    Chat de transmissão ao vivo
                </div>
                <div id="divConteudoMensagens" style="text-align:  -webkit-left;height: calc( 92% - 52px);overflow: auto;">
                    ' . $mensagensTransmissao . '
                </div>
            </div>
        </div>
        <script>
            var player = videojs("my_video_1");
            timestamp = "' . strtotime(date('Y-m-d H:i:s')) . '";
        </script>';
    }

    public function setConteudoUpload($idConteudo, $conteudo)
    {
        return '<div>
            <h4>' . ucfirst($conteudo->titulo) . '</h4>
            <p>' . ucfirst($conteudo->descricao) . '</p>
            <a href="' . route('conteudo.play.get-arquivo', ['idConteudo' => $idConteudo]) . '" target="_blank" class="btn btn-primary btn-lg">
                <i class="fas fa-arrow-alt-circle-down mr-2"></i>
                Clique para baixar o arquivo
            </a>
        </div>';
    }

    public function setConteudoDissertativa($idConteudo, $conteudo)
    {
        $tempCont = json_decode($conteudo->conteudo);

        $conteudo->conteudo = '<div class="px-3 py-2">
            <h2>' . ucfirst($tempCont->pergunta) . '</h2>
            </div>';

        return $conteudo->conteudo;
    }

    public function setConteudoQuiz($idConteudo, $conteudo)
    {
        $tempCont = json_decode($conteudo->conteudo);

        if($conteudo->completo)
        {
            $conteudo->correto = ($tempCont->correta == $resposta);
        }
        else
        {
            $conteudo->correto = null;
        }

        $conteudo->conteudo = '<div class="px-3 py-2">
            <h2>' . ucfirst($tempCont->pergunta) . '</h2>';

        foreach ($tempCont->alternativas as $key => $alternativa)
        {
            $conteudo->conteudo = $conteudo->conteudo . '
            <div id="boxAlternativa' . ($key) .'" class="box-alternativa box-shadow px-4 py-4 rounded-10 my-3 ' . ($conteudo->completo ? ($resposta == ($key) ? ($conteudo->correto ? 'alternativa-correta' : 'alternativa-incorreta') : 'alternativa-desativada') : '') . '">
                <div class="custom-control custom-radio h4">
                    <input type="radio" id="alternativa' . ($key) .'" name="alternativas" onchange="selecionarAlternativa(this.id);" class="custom-control-input" ' . ($conteudo->completo ? ($resposta == ($key) ? 'checked' : '' ) : '') . '>
                    <label class="custom-control-label pl-4 d-block" for="alternativa' . ($key) .'">' . $alternativa . '</label>
                </div>
            </div>';
        }

        $conteudo->conteudo .= '</div>';

        return $conteudo->conteudo;
    }

    public function setConteudoProva($idConteudo, $conteudo)
    {
        $perguntas = json_decode($conteudo->conteudo);

        $cmbPerguntas = '';

        for ($i=0; $i < count($perguntas); $i++)
        {
            $cmbPerguntas = $cmbPerguntas . '<option value="' . ($i + 1) . '" ' . ($i == 0 ? 'selected' : '') . '>Item ' . ($i + 1) . '</option>';
        }

        $conteudo->conteudo = '<div class="px-3 py-2">
        <div class="form-group mb-3">
            <select class="select-mppa custom-select form-control d-inline-block mr-2" id="cmbQuestaoAtual" style="width: auto; min-width: 200px;">
                ' . $cmbPerguntas . '
            </select>
        </div>';

        $divPerguntas = '';

        $totalCorretas = 0;
        $totalPontos = 0;

        foreach ($perguntas as $key => $pergunta)
        {
            $divPerguntas = $divPerguntas . '<div id="divPergunta' . ($key + 1) . '" data-tipo="' . $pergunta->tipo . '" class="' . ($key > 0 ? 'd-none' : '') . '">';

            $divPerguntas = $divPerguntas . '<h2>' . ucfirst($pergunta->pergunta) . ' <small class="text-muted mr-3">(Peso: ' . $pergunta->peso . ')</small> ' . ($key + 1) . '/' . count($perguntas) . '</h2>';

            if($pergunta->tipo != 1) {
                foreach ($pergunta->alternativas as $key2 => $alternativa)
                {
                    $divPerguntas = $divPerguntas . '
                    <div id="boxAlternativa' . ($key2 + 1) .'" class="box-alternativa box-shadow px-4 py-4 rounded-10 my-3">
                        <div class="custom-control custom-radio h4">
                            <input type="radio" id="alternativa' . ($key + 1) .'-' . ($key2 + 1) .'" class="custom-control-input" readonly>
                            <label class="custom-control-label pl-4 d-block" for="alternativa' . ($key + 1) .'-' . ($key2 + 1) .'">' . $alternativa .  '</label>
                        </div>
                    </div>';
                }
            }

            $divPerguntas = $divPerguntas . '</div>';
        }

        $conteudo->conteudo = $conteudo->conteudo . '<div id="divPerguntas">
            ' . $divPerguntas . '
        </div>';

        return $conteudo->conteudo;
    }

    public function setConteudoEntregavel($idConteudo, $conteudo)
    {
        return '<div class="px-3 py-2">
            <h2>' . ($conteudo->conteudo) . '</h2>
            </div>';
    }

    public function setConteudoLivroDigital($idConteudo, $conteudo)
    {
        $url_apostila = config('app.cdn') . '/uploads/apostilas/' . $conteudo->id;

        /* return '<iframe src="' . config('app.local') . '/leitor_apostila/' . $conteudo->id . '?conteudo_id=' . $conteudo->id . '&url=' . $url_apostila . '" style="width: 100%; height: 115vh;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
            </iframe>'; */

            return '<iframe src="' . config('app.cdn') . '/uploads/apostilas/' . $conteudo->id . '/" style="width: 100%; height: 100vh;" frameborder="0" allowfullscreen>
                        </iframe>';


    }

    public function setConteudoPDF($idConteudo, $conteudo)
    {
        if(strpos($conteudo->conteudo, "http") === false && strpos($conteudo->conteudo, "www") === false) {
            $url_pdf = route('conteudo.play.get-arquivo', ['idConteudo' => $idConteudo]);
        } else {
            $url_pdf = $conteudo->conteudo;
        }

        return '<object data="' . $url_pdf . '" type="application/pdf" style="width: 100%; height: 31vw;">
        </object>';
    }

    public function setConteudoBancoImagem($idConteudo, $conteudo)
    {
        $img = explode('.',$conteudo->conteudo);
        $img = $img[0].'_view'.'.'.$img[1];
        $img = config('app.cdn').'/storage/banco_imagens/views/'.$img;

        //$img = config('app.cdn').'/storage/banco_imagens/'.$conteudo->conteudo;
        return '<div width="100%" align="center"><img src="'.$img.'" /><p> </p><p style="font-size: 11px" >Fonte: '.$conteudo->fonte.'</p></div>';
    }

    public function setConteudoSimuladores($idConteudo, $conteudo)
    {
        return '<iframe  src="'.$conteudo->conteudo.'" width="100%" height="600" scrolling="no" allowfullscreen></iframe>
        <p> </p><p><b>'.$conteudo->descricao.'</b></p>
        <p style="font-size: 11px" >Fonte: '.$conteudo->fonte.'</p>';
    }

    public function setConteudoJogos($idConteudo, $conteudo)
    {
        return '<iframe  src="'.$conteudo->conteudo.'" width="100%" height="600" scrolling="no" allowfullscreen></iframe>
        <p> </p>
        <div class="row">
            <div class="col-9">
                <p><b>'.$conteudo->descricao.'</b></p>
                <p style="font-size: 11px" >Fonte: '.$conteudo->fonte.'</p>
            </div>
            <div class="col-3">
                <a target="blank" href="'.$conteudo->conteudo.'" class="btn btn-outline-secondary" >Abrir em nova aba</a>
            </div>
        </div>';
    }
    public function setConteudoTabelaBncc($idConteudo, $conteudo)
    {
        return $conteudo->conteudo;
    }
    public function setConteudoProvasBimestrais($idConteudo, $conteudo)
    {
        $url_conteudo = config('app.cdn').'/storage/provas/' . $conteudo->conteudo;
        //$url_conteudo = 'https://s3.amazonaws.com/static.opetinspira.com/storage/provas/' . $conteudo->conteudo;
       // return '<iframe src="https://view.officeapps.live.com/op/view.aspx?src=' . $url_conteudo . '" style="width: 100%; height: 41vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
        return '<iframe src="https://docs.google.com/viewer?url=' . $url_conteudo . '&embedded=true" style="width: 100%; height: 41vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
    }


}
