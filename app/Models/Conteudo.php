<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use App\Entities\Questoes\QuestaoConteudo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * App\Models\Conteudo
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $tipoprova_id
 * @property string|null $fim_avaliacao
 * @property string|null $ini_avaliacao
 * @property int|null $peso
 * @property int|null $ciclo_id
 * @property int|null $cicloetapa_id
 * @property int|null $disciplina_id
 * @property string $titulo
 * @property string|null $descricao
 * @property int $status
 * @property int $tipo
 * @property string $conteudo
 * @property float|null $file_size
 * @property float|null $tempo
 * @property int|null $duracao
 * @property string|null $apoio
 * @property string|null $fonte
 * @property string|null $autores
 * @property int $categoria_id
 * @property int $permissao_download 0 = não permitido / 1 = permitido
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $obrigatorio
 * @property int $privado
 * @property int $visibilidade
 * @property int $instituicao_id
 * @property int $escola_id
 * @property int $nivel_ensino
 * @property int $disciplina
 * @property string|null $alternativas
 * @property string|null $alternativa_correta
 * @property int|null $colecao_livro_id
 * @property string|null $tipo_livro
 * @property int|null $etapa_livro
 * @property int|null $ano_livro
 * @property int|null $componente_livro
 * @property string|null $capa
 * @property string|null $periodo
 * @property int|null $colecao_ed_infantil_id
 * @property string|null $comentario_pedagogico
 * @property string $compartilhado_google
 * @property string|null $id_google
 * @property string|null $full_text
 * @property int|null $qtd_paginas_livro
 * @property-read \App\Models\Categoria $categoria
 * @property-read \App\Models\Ciclo|null $ciclo
 * @property-read \App\Models\CicloEtapa|null $cicloetapa
 * @property-read \App\Models\ConteudoAula $conteudo_aula
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ConteudoAula[] $conteudos_aula
 * @property-read int|null $conteudos_aula_count
 * @property-read mixed $iframe
 * @property-read mixed $tipo_icon
 * @property-read mixed $tipo_nome
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entities\Questoes\Questoes[] $questoes
 * @property-read int|null $questoes_count
 * @property-read \App\Models\Disciplina|null $rel_disciplina
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereAlternativaCorreta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereAlternativas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereAnoLivro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereApoio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereAutores($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereCapa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereCategoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereCicloId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereCicloetapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereColecaoEdInfantilId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereColecaoLivroId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereComentarioPedagogico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereCompartilhadoGoogle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereComponenteLivro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereConteudo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereDisciplina($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereDisciplinaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereDuracao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereEtapaLivro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereFimAvaliacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereFonte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereFullText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereIdGoogle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereIniAvaliacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereNivelEnsino($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereObrigatorio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo wherePermissaoDownload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo wherePeso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo wherePrivado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereQtdPaginasLivro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereTempo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereTipoLivro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereTipoprovaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conteudo whereVisibilidade($value)
 * @mixin \Eloquent
 */
class Conteudo extends Model
{

    //Preenchiveis
    protected $fillable = [
        'id',
        'user_id',
        'titulo',
        'descricao',
        'status',
        'tipo',
        'conteudo',
        'file_size',
        'tempo',
        'duracao',
        'apoio',
        'fonte',
        'autores',
        'obrigatorio',
        'categoria_id',
        'permissao_download',
        'tipoprova_id',
        'fim_avaliacao',
        'ini_avaliacao',
        'peso',
        'ciclo_id',
        'cicloetapa_id',
        'disciplina_id',
        'colecao_livro_id',
        'tipo_livro',
        'etapa_livro',
        'ano_livro',
        'componente_livro',
        'capa',
        'periodo',
        'colecao_ed_infantil_id',
        'instituicao_id',
        'compartilhado_google',
        'id_google',
        'eh_audio_cast',
        'full_text',
        'qtd_paginas_livro',
        'criado_em_roteiros'
    ];

    protected $appends = [
        'eh_link',
        'grava_arquivo',
        'nome_arquivo',
        'pergunta',
        'alternativas',
        'correta',
        'iframe',
    ];
    /*
     * funcoes F&R
     */
    public function getGravaArquivoAttribute(){
        if($this->tipo == 2 || $this->tipo == 3 || $this->tipo == 4 || $this->tipo == 15 || $this->tipo == 6){
            return 1;
        }
        return 0;
    }

    public function getEhLinkAttribute(){
        if($this->grava_arquivo == 1 || $this->grava_arquivo == 6) {
            if( Str::startsWith($this->conteudo,'http') || Str::startsWith($this->conteudo,'www')){
                return 1;
            }
            else{
                return 0;
            }
        }else{
            return 0;
        }

    }

    public function getNomeArquivoAttribute(){
        if($this->grava_arquivo == 1) {
            if( !Str::startsWith($this->conteudo,'http') ){
                $ext = Str::of($this->conteudo)->explode('.');
                $ext = $ext->last();
                return Str::slug($this->titulo, '_').'.'.$ext;
            }
            else{
                return '';
            }
        }else{
            return '';
        }

    }

    public function getPerguntaAttribute(){
        if($this->tipo == 7 || $this->tipo == 8){
            $cont = json_decode($this->conteudo);
            return $cont->pergunta;
        }else{
            return '';
        }
    }

    public function getAlternativasAttribute(){
        if( $this->tipo == 8){
            $cont = json_decode($this->conteudo);
            return $cont->alternativas;
        }else{
            return '';
        }
    }

    public function getCorretaAttribute(){
        if( $this->tipo == 8){
            $cont = json_decode($this->conteudo);
            return $cont->correta;
        }else{
            return '';
        }
    }

    public function getIframeAttribute(){
        $cont = $this->conteudo;
        $link = '';
        if($this->tipo == 1) {
            return $cont;
        }
        elseif($this->tipo == 2){
            if (strpos($cont, "soundcloud.com") !== false) {
                $link = 'https://w.soundcloud.com/player/?url=' . $cont . '&color=%236766a6&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true';
            }
            else {
                if (strpos($cont, "http") !== false || strpos($cont, "www") !== false) {
                    $link = $cont;
                } else {
                    if ($this->colecao_livro_id == '' && $this->criado_em_roteiros == 0) {
                        $link = config('app.cdn') . '/storage/cast/' . $this->user_id . '/audio/' . $cont;
                    } elseif($this->criado_em_roteiros == 1) {
                        $link = config('app.cdn') . '/storage/uploads/conteudos/' . $cont;
                    }else{
                        $link = url('/play/conteudo/'.$this->id.'/arquivo');
                    }
                }
                //return '<div id="player1" data-playerid="200" class="audioplayer-tobe is-single-player " style="width:100%; margin-top:10px; margin-bottom: 10px;" data-type="audio" data-source="'.$link.'" data-fakeplayer="#ap1"  ><div class="meta-artist"></div></div>';
                return '<audio controls="controls"> <source src="' . $link . '" type="audio/mp3">&nbsp;seu navegador n&atilde;o suporta HTML5</audio>';
            }
        }elseif($this->tipo == 3) {
            if($this->eh_link) {
                if (strpos($cont, "youtube") !== false || strpos($cont, "youtu.be") !== false) {
                    if (strpos($cont, "youtu.be") !== false) {
                        $cont = str_replace("youtu.be", "youtube.com", $cont);
                    }

                    $cont = str_replace("/watch?v=", "/embed/", $cont);

                    if (strpos($cont, "&") !== false) {
                        $cont = substr($cont, 0, strpos($cont, "&"));
                    }

                    $link = $cont;

                } elseif (strpos($cont, "vimeo") !== false) {
                    if (strpos($cont, "player.vimeo.com") === false)
                        $cont = str_replace("vimeo.com/", "player.vimeo.com/video/", $cont);
                    $link = $cont;
                }
                return '<span class="fr-video fr-deletable fr-fvc fr-dvb fr-draggable" contenteditable="false" draggable="true"><iframe width="100%" height="360" src="'.$link.'" frameborder="0" allowfullscreen="" class="fr-draggable"></iframe></span>';
            }else{
                if($this->criado_em_roteiros == 1) {
                    $cont = config('app.cdn') . '/storage/uploads/conteudos/' . $cont;
                    return '<video width="100%" height="360" controls>
                          <source src="' . $cont . '" type="video/mp4">
                         Seu navegador não suporta vídeos
                        </video>';
                }
                else{
                    return null;
                }
            }

        }
        elseif($this->tipo == 4) {
            if(strpos($cont, "http") === false && strpos($cont, "www") === false) {
                if($this->criado_em_roteiros == 1){
                    $cont = config('app.cdn').'/storage/uploads/conteudos/' . $cont;
                }else{
                    $cont = config('app.cdn').'/storage/' . $cont;
                }
            }
            $link = 'https://docs.google.com/viewer?url=' . $cont . '&embedded=true';
        }
        elseif($this->tipo == 6){
            $ret = '<div>
                    <p>' . ucfirst($this->descricao) . '</p>';
            if($this->getEhLinkAttribute() == 1){
                $ret .= '<p class="text-center">
                        <a href="'.$cont.' " class="btn btn-primary btn" target="_blank">
                            <i class="fas fa-arrow-alt-circle-down mr-2"></i>
                            Clique para acessar o arquivo
                        </a>
                    </p>';
            }else{
                $path = '/gestao/roteiros/download/';
                if(auth()->user()->permissao == 'A'){
                    $path = '/roteiros/download/';
                }
                $ret .= '<p class="text-center">
                        <a href="'.url($path.$this->id.'/'.str_replace('.','_', $cont)).' " class="btn btn-primary btn">
                            <i class="fas fa-arrow-alt-circle-down mr-2"></i>
                            Clique para baixar o arquivo
                        </a>
                    </p>';
            }
            $ret .= '</div>';
            return $ret;
        }
        elseif($this->tipo == 8){
            $tempCont = json_decode($cont);
            $retorno = '<div class="px-3 py-2">
            <h4>' . ucfirst($tempCont->pergunta) . '</h4>';
            $i=1;
            foreach ($tempCont->alternativas as $key => $alternativa)
            {
                $js = "$('#quizIncorreto').addClass('d-none'); $('#quizCorreto').removeClass('d-none');";
                if($i != $tempCont->correta){
                    $js = "$('#quizIncorreto').removeClass('d-none'); $('#quizCorreto').addClass('d-none');";
                }
                $retorno .= '
                <div id="boxAlternativa' . ($key) .'" class="box-alternativa box-shadow px-4 py-4 rounded-10 my-3 ">
                    <div class="custom-control custom-radio h4">
                        <input type="radio" id="alternativa' . ($key) .'" name="alternativas" onchange="'.$js.' selecionarAlternativa();" class="custom-control-input" >
                        <label class="custom-control-label pl-4 d-block" for="alternativa' . ($key) .'">' . $alternativa .'</label>
                    </div>
                </div>';
                $i++;
            }

            return '</div>
                <div class="p-2 d-none" id="quizIncorreto" >
                    <h5 class="text-danger text-center">Que pena! Essa não é a resposta correta.</h5>
                </div>
                <div class="p-2 d-none" id="quizCorreto">
                    <h5 class="text-success text-center">Parabéns! Você acertou.</h5>
                </div>
            '.$retorno;
        }
        elseif($this->tipo == 7){
            $dados = json_decode($cont);
            return '<div class="px-3 py-2"> '.$dados->pergunta.'</div>
                    <form action="" method="post" id="formDiscursiva">
                        <input type="hidden" value="" name="trilha_id" id="trilhaIdEntregavel"/>
                        <input type="hidden" value="" name="curso_id" id="cursoIdEntregavel"/>
                        <input type="hidden" value="" name="aula_id" id="aulaIdEntregavel"/>
                        <input type="hidden" value="" name="conteudo_id" id="conteudoIdEntregavel"/>
                        <input type="hidden" value="" name="_token" id="tokenEntregavel"/>
                        <div class="px-3 py-2"><textarea id="respostaDiscursiva" name="conteudo" class="form-control" rows="7"></textarea></div>
                        <div class="px-3 py-2 text-right" id="btnDiscursiva"><button type="submit" class="btn btn-sm btn-success" >Salvar Resposta</button></div>
                        <div class="px-3 py-2 text-right text-success" id="discursivaEstaSalva" style="display: none"><b>Sua resposta já está salva.</b></div>
                    </form>';
        }
        elseif($this->tipo == 10){
            $cont .= '<div id="entregavel" style="display: none"><p class="text-center">
                <button type="button" class = "btn btn-primary" onclick="$(\'#escolhaArquivo\').click()">Upload de arquivo</button>
                <form action="" method="post" id="formEntregavel">
                    <input type="hidden" value="" name="trilha_id" id="trilhaIdEntregavel"/>
                    <input type="hidden" value="" name="curso_id" id="cursoIdEntregavel"/>
                    <input type="hidden" value="" name="aula_id" id="aulaIdEntregavel"/>
                    <input type="hidden" value="" name="conteudo_id" id="conteudoIdEntregavel"/>
                    <input type="hidden" value="" name="_token" id="tokenEntregavel"/>
                    <input id="escolhaArquivo" type="file" hidden name="arquivo_entregavel" onchange="$(\'#formEntregavel\').submit();">
                </form>
            </p>
            <p id="listaEntregavel" class="text-center">

            </p>
            </div>';
            return $cont;
        }
        elseif($this->tipo == 15){
            if(strpos($cont, "http") === false && strpos($cont, "www") === false) {
                $url = config('app.cdn').'/storage/uploads/conteudos/' . $cont;
                return '<object data="' . $url . '" type="application/pdf" style="width: 100%; height: 31vw;"></object>';
            } else {
                $link = $cont;
            }

        }
        elseif($this->tipo == 21){
            $link = url('colecao_livro/livro/'.$this->id.'?flip_sozinho=1');
        }
        elseif($this->tipo == 22){
            $cont = config('app.cdn') .'/'. $cont;
            $link = 'https://docs.google.com/viewer?url=' . $cont . '&embedded=true';
        }
        elseif($this->tipo == 100){
            $img = explode('.',$cont);
            $img = $img[0].'_view'.'.'.$img[1];
            $link = config('app.cdn').'/storage/banco_imagens/views/'.$img;
            return '<span><img src="'.$link.'" class="fr-dib"></span>';
        }
        elseif($this->tipo == 102){
            $cont = config('app.cdn').'/storage/provas/' . $cont;
            $link = 'https://docs.google.com/viewer?url=' . $cont . '&embedded=true';
        }
        elseif($this->tipo == 103 || $this->tipo == 101) {
            $link = $cont;
        }

        elseif($this->tipo == 104){
            $link = url('/exibicao/google?c=').$this->id_google;
        }
        return '<iframe src="'.$link.'" width="100%" height="400px" allowfullscreen=""></iframe>';
    }

    public function rel_disciplina(){

        return $this->belongsTo('App\Models\Disciplina', 'disciplina_id');

    }

    public function categoria(){

        return $this->belongsTo('App\Models\Categoria', 'categoria_id');

    }

    /*
     * Funcoes Edulabz
     */
    public function conteudos_aula()
    {
        return $this->hasMany('App\Models\ConteudoAula', 'conteudo_id');
    }

    public function conteudo_aula()
    {
        return $this->belongsTo('App\Models\ConteudoAula', 'id', 'conteudo_id');
    }

    public function instituicao()
    {
        return $this->hasMany('App\Models\ConteudoInstituicaoEscola', 'conteudo_id', 'id');
    }

    public function progressos()
    {
        return $this->hasMany('App\Models\ProgressoConteudo', 'conteudo_id')->where('tipo', '=', 2);
    }

    public function progressos_user()
    {
        return $this->hasMany('App\Models\ProgressoConteudo', 'conteudo_id')->with('user')->where('tipo', '=', 2);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function questoes()
    {
        return $this->belongsToMany('App\Entities\Questoes\Questoes','questoes_conteudos', 'conteudo_id', 'questao_id')->using('App\Entities\Questoes\QuestaoConteudo');
    }

    public static function detalhado($conteudos)
    {
        foreach ($conteudos as $key => $conteudo) {

            switch ($conteudo->tipo) {
                case 1:
                    $conteudo->tipo_nome = 'Texto';
                    $conteudo->tipo_icon = 'fas fa-font';
                    break;
                case 2:
                    $conteudo->tipo_nome = 'Áudio';
                    $conteudo->tipo_icon = 'fas fa-podcast';
                    break;
                case 3:
                    $conteudo->tipo_nome = 'Vídeo';
                    $conteudo->tipo_icon = 'fa fa-video';
                    break;
                case 4:
                    $conteudo->tipo_nome = 'Slide';
                    $conteudo->tipo_icon = 'fa fa-file-powerpoint';
                    break;
                case 5:
                    $conteudo->tipo_nome = 'Transmissão';
                    $conteudo->tipo_icon = 'fa fa-broadcast-tower';
                    break;
                case 6:
                    $conteudo->tipo_nome = 'Upload';
                    $conteudo->tipo_icon = 'fa fa-upload';
                    break;
                case 7:
                    $conteudo->tipo_nome = 'Dissertativa';
                    $conteudo->tipo_icon = 'fa fa-comment-alt';
                    break;
                case 8:
                    $conteudo->tipo_nome = 'Quiz';
                    $conteudo->tipo_icon = 'fa fa-list-ul';
                    break;
                case 9:
                    $conteudo->tipo_nome = 'Avaliação';
                    $conteudo->tipo_icon = 'fa fa-stopwatch';
                    break;
                case 10:
                    $conteudo->tipo_nome = 'Entregável';
                    $conteudo->tipo_icon = 'fa fa-arrow-circle-up';
                    break;
                case 11:
                    $conteudo->tipo_nome = 'Livro Digital';
                    $conteudo->tipo_icon = 'fa fa-book';
                    break;
                case 12:
                    $conteudo->tipo_nome = 'Descubra Palavra';
                    $conteudo->tipo_icon = 'fas fa-leaf';
                    break;

                case 13:
                    $conteudo->tipo_nome = 'Verdadeiro ou Falso';
                    $conteudo->tipo_icon = 'fad fa-file-list';
                    break;

                case 15:
                    $conteudo->tipo_nome = 'PDF';
                    $conteudo->tipo_icon = 'fa fa-file-pdf';
                    break;

                case 16:
                    $conteudo->tipo_nome = 'Caça Palavras';
                    $conteudo->tipo_icon = 'fa fa-table';
                    break;
                case 17:
                    $conteudo->tipo_nome = 'Quiz';
                    $conteudo->tipo_icon = 'fa fa-list-ul';
                    break;

                case 19:
                    $conteudo->tipo_nome = 'Corelação de Palavras';
                    $conteudo->tipo_icon = 'fa-sort-alpha-asc';
                    break;

                case 20:
                    $conteudo->tipo_nome = 'Correlação de Imagens';
                    $conteudo->tipo_icon = 'fas fa-image';
                    break;

                case 21:
                    $conteudo->tipo_nome = 'Livro Digital';
                    $conteudo->tipo_icon = 'fa fa-book';
                    break;

                case 21:
                    $conteudo->tipo_nome = 'Documentos Oficiais';
                    $conteudo->tipo_icon = 'fa fa-file-alt';
                    break;

                case 100:
                    $conteudo->tipo_nome = 'Imagem';
                    $conteudo->tipo_icon = 'fas fa-image';
                    break;
                case 106:
                    $conteudo->tipo_nome = 'Vídeo';
                    $conteudo->tipo_icon = 'fa fa-video';
                    break;
                default:
                    $conteudo->tipo_nome = 'Texto';
                    $conteudo->tipo_icon = 'fas fa-font';

                    break;
            }


        }

        return $conteudos;
    }

    //Função para retornar os livros digitais conforme o perfil
    public static function getLivroDigitalBiblioteca($conteudo_livro_gigital){

        $user = Auth::user();

        $conteudo_livro_gigital = $conteudo_livro_gigital->where('tipo', 21);

        switch($user->privilegio_id){
            case 1:
                $conteudo_livro_gigital = $conteudo_livro_gigital;
            break;
            case 2:
                $responsavel_id = InstituicaoUser::where('user_id', $user->id)->first()->instituicao_id;
                $conteudo_livro_gigital = $conteudo_livro_gigital->where('instituicao_id', $responsavel_id);
            break;
            case 3:
                $conteudo_livro_gigital = $conteudo_livro_gigital->where('user_professor', $user->id);
            break;
            case 4:
                $conteudo_livro_gigital = $conteudo_livro_gigital->where('escola_id', $user->escola_id);
            break;
            case 5:
                $conteudo_livro_gigital = $conteudo_livro_gigital->where('user_responsavel', $user->id);
            break;
            #Default não aparece nenhum livro digital
            default:
                $conteudo_livro_gigital = $conteudo_livro_gigital->where('id', 0);
            break;
        }

        return $conteudo_livro_gigital;
    }


    public function favorito($idUser, $idRef)
    {
        return $this->hasOne(Favorito::class, 'referencia_id')
            ->where([['user_id', $idUser], ['referencia_id', $idRef], ['tipo', 'conteudo']])->first();
    }



    public function getTipoNomeAttribute()
    {
        switch ($this->tipo) {
            case 1:
                return 'Texto';

                break;
            case 2:
                return 'Áudio';

                break;
            case 3:
                return 'Vídeo';

                break;
            case 4:
                return 'Slide';

                break;
            case 5:
                return 'Transmissão';

                break;
            case 6:
                return 'Upload';

                break;
            case 7:
                return 'Dissertativa';

                break;
            case 8:
                return 'Quiz';

                break;
            case 9:
                return 'Avaliação';

                break;
            case 10:
                return 'Entregável';

                break;
            case 11:
                return 'Livro Digital';

                break;
            case 15:
                return 'PDF';

                break;
            case 12:
                return 'Descubra a Palavra';

                break;
            case 13:
                return 'Verdadeiro ou Falso';

                break;
            case 16:
                return 'Caça Palavras';

                break;
            case 17:
                return 'Quiz';
                break;

            case 19:
                return 'Correlação de Palavras';

                break;
            case 20:
                return 'Correlação de Imagens';
                break;

            case 21:
                return 'Livro Digital';
                break;

            case 22:
                return 'Documentos Oficiais';
                break;

            case 100:
                return 'Imagem';
                break;

            case 101:
                return 'Simuladores';
                break;

            case 102:
                return 'Provas Bimestrais';
                break;
            case 103:
                return 'Jogos';
                break;
            case 106:
                return 'Ação Destaque';
                break;
            default:
                return 'Texto';

                break;
        }
    }

    public function getTipoIconAttribute()
    {
        switch ($this->tipo) {
            case 1:
                return 'fas fa-font';

                break;
            case 2:
                return 'fas fa-podcast';

                break;
            case 3:
                return 'fa fa-video';

                break;
            case 4:
                return 'fa fa-file-powerpoint';

                break;
            case 5:
                return 'fa fa-broadcast-tower';

                break;
            case 6:
                return 'fa fa-upload';

                break;
            case 7:
                return 'fa fa-comment-alt';

                break;
            case 8:
                return 'fa fa-list-ul';

                break;
            case 9:
                return 'fa fa-stopwatch';

                break;
            case 10:
                return 'fa fa-arrow-circle-up';

                break;
            case 11:
                return 'fa fa-book';

                break;
            case 13:
                return 'fas fa-list-alt';

                break;
            case 15:
                return 'fa fa-file-pdf';

                break;

            case 12:
                return 'fas fa-leaf';

                break;

            case 13:
                return 'fas fa-list-alt';

                break;
            case 16:
                return 'fa fa-table';

                break;

            case 17:
                return 'fa fa-list-ul';
            break;


            case 19:
            return 'fas fa-sort-alpha-up';
            break;

            case 20:
            return 'fas fa-image';

            break;

            case 21:
                return 'fas fa-book';

                break;

            case 22:
                return 'fas fa-file-alt';

                break;
            case 100:
                return 'fas fa-image';
                break;

            case 101:
                return 'fas fa-atom';
                break;

            case 102:
                return 'fas fa-file-word';
                break;

            case 103:
                return 'fas fa-gamepad';
                break;
            case 106:
                return 'fa fa-video';
           break;
            default:
                return 'fas fa-font';

                break;
        }
    }

    public function ciclo() {

        return $this->belongsTo('App\Models\Ciclo', 'ciclo_id');

    }

    public function cicloetapa(){

        return $this->belongsTo('App\Models\CicloEtapa', 'cicloetapa_id');

    }

}
