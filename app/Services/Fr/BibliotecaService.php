<?php
namespace App\Services\Fr;
use App\Models\ColecaoAudioInstituicao;
use App\Models\ColecaoAudioInstituicaoPermissao;
use App\Models\ColecaoDocumentoInstituicao;
use App\Models\ColecaoDocumentoInstituicaoPermissao;
use App\Models\ColecaoProvaInstituicao;
use App\Models\ColecaoProvaInstituicaoPermissao;
use App\Models\Disciplina;
use Illuminate\Support\Facades\Auth;
use DB;

use App\Models\Conteudo;
use App\Models\ColecaoLivros;
use App\Models\ColecaoAudioEscola;
use App\Models\ColecaoAudioEscolaPermissao;
use App\Models\ColecaoDocumentoEscola;
use App\Models\ColecaoDocumentoEscolaPermissao;
use App\Models\ColecaoProvaEscola;
use App\Models\ColecaoProvaEscolaPermissao;

class BibliotecaService {

    public function __construct(LivroService $livroService)
    {
        $this->livroService = $livroService;
    }

    private function scopoQueryConteudoTipoAudio($sql, $idEscola, $idColecao)
    {
        $permissao = ColecaoAudioEscola::with(['permissao'=>function($q) use ($idEscola){
            $q->where('escola_id',$idEscola)
                ->selectRaw('cicloetapa_id, colecao_id');
        }])
            ->where('escola_id',$idEscola);
        if($idColecao != '') {
            $permissao = $permissao->where('colecao_id', $idColecao);
        }
        $permissaoTotal = $permissao->get();

        if(count ($permissaoTotal)>0) {

            $sql->where(function ($qGeral) use ($permissaoTotal) {
                foreach ($permissaoTotal as $permissao) {
                    $qGeral->orWhere(function ($qFor) use ($permissao) {
                        ////
                        $qFor->where('colecao_livro_id', $permissao->colecao_id);
                        if ($permissao->todos != 1) {
                            $cicloetapa = [];
                            foreach ($permissao->permissao as $p) {
                                $cicloetapa[] = $p->cicloetapa_id;
                            }

                            $qFor->whereIn('cicloetapa_id', $cicloetapa);
                        }
                        /////
                    });
                }
            });
        }
        else{
            $sql->where('conteudos.id','0');
        }

        return $sql;
    }

    private function scopoQueryConteudoTipoDocumento($sql, $idEscola, $idColecao)
    {
        $permissao = ColecaoDocumentoEscola::with(['permissao'=>function($q) use ($idEscola){
            $q->where('escola_id',$idEscola)
                ->selectRaw('cicloetapa_id, colecao_id');
        }])
            ->where('escola_id',$idEscola);
        if($idColecao != '') {
            $permissao = $permissao->where('colecao_id', $idColecao);
        }
        $permissaoTotal = $permissao->get();

        if(count ($permissaoTotal)>0) {

            $sql->where(function ($qGeral) use ($permissaoTotal) {
                foreach ($permissaoTotal as $permissao) {
                    $qGeral->orWhere(function ($qFor) use ($permissao) {
                        ////
                        $qFor->where('colecao_livro_id', $permissao->colecao_id);
                        if ($permissao->todos != 1) {
                            $cicloetapa = [];
                            foreach ($permissao->permissao as $p) {
                                $cicloetapa[] = $p->cicloetapa_id;
                            }

                            $qFor->whereIn('cicloetapa_id', $cicloetapa);
                        }
                        /////
                    });
                }
            });
        }
        else{
            $sql->where('conteudos.id','0');
        }

        return $sql;
    }

    private function scopoQueryConteudoTipoProva($sql, $idEscola, $idColecao)
    {
        $permissao = ColecaoProvaEscola::with(['permissao'=>function($q) use ($idEscola){
            $q->where('escola_id',$idEscola)
                ->selectRaw('cicloetapa_id, colecao_id');
        }])
            ->where('escola_id',$idEscola);
        if($idColecao != '') {
            $permissao = $permissao->where('colecao_id', $idColecao);
        }
        $permissaoTotal = $permissao->get();

        if(count ($permissaoTotal)>0) {

            $sql->where(function ($qGeral) use ($permissaoTotal) {
                foreach ($permissaoTotal as $permissao) {
                    $qGeral->orWhere(function ($qFor) use ($permissao) {
                        ////
                        $qFor->where('colecao_livro_id', $permissao->colecao_id);
                        if ($permissao->todos != 1) {
                            $cicloetapa = [];
                            foreach ($permissao->permissao as $p) {
                                $cicloetapa[] = $p->cicloetapa_id;
                            }

                            $qFor->whereIn('cicloetapa_id', $cicloetapa);
                        }
                        /////
                    });
                }
            });
        }
        else{
            $sql->where('conteudos.id','0');
        }

        return $sql;


    }

    public function scopoQueryConteudo($tipoConteudo, $editora, $idColecao=null, $busca = null)
    {
        $sql = Conteudo::where('conteudos.tipo',$tipoConteudo)
            ->where('conteudos.tipo','!=',21)
            ->join('disciplinas', 'conteudos.disciplina_id','disciplinas.id');
        if($tipoConteudo == 103){
            $sql = $sql->where('conteudos.status',1);
        }
        if($editora != 1){
            $sql->where(function($q){
                $q->orWhere('user_id',auth()->user()->id);
                $q->orWhere('instituicao_id',1);
            });

        }else{
            $sql->where('instituicao_id',1);
        }

        if($busca != ''){
            $sql = $sql->join('ciclo_etapas','ciclo_etapas.id','conteudos.cicloetapa_id')
                ->join('ciclos','ciclos.id','ciclo_etapas.ciclo_id')
                ->where(function($q) use ($busca){
                    $q->orWhereRaw("MATCH(conteudos.titulo, conteudos.apoio, conteudos.full_text)AGAINST('".$busca."' IN BOOLEAN MODE)");
                });
        }

        /// filtro permissao por tipo_livro

        if(auth()->user()->permissao == 'A' || auth()->user()->permissao == 'R') {
            $sql = $sql->where(function($q){
                $q->orWhereNull('tipo_livro')
                    ->orWhere('tipo_livro', 'AA')
                    ->orWhere('tipo_livro', 'A');
            });
        }

        if($tipoConteudo == 2 && auth()->user()->permissao != 'Z'){
            $escola = session('escola');
            $idEscola = $escola['id'];
            $sql = $this->scopoQueryConteudoTipoAudio($sql, $idEscola, $idColecao);
        /*}elseif($tipoConteudo == 22 && auth()->user()->permissao != 'Z'){
            $escola = session('escola');
            $idEscola = $escola['id'];
            $sql = $this->scopoQueryConteudoTipoDocumento($sql, $idEscola, $idColecao);*/
        }elseif($tipoConteudo == 102 && auth()->user()->permissao != 'Z'){
            $escola = session('escola');
            $idEscola = $escola['id'];
            $sql = $this->scopoQueryConteudoTipoProva($sql, $idEscola, $idColecao);

        }elseif($idColecao!=''){
            $sql->where('colecao_livro_id',$idColecao);
        }
        return $sql;
    }

    /* 
    //Código antigo da function listaConteudo, não apagar até validação do pedagogico
    public function listaConteudo($tipoConteudo,$request,$editora = null)
    {
        $sql = $this->scopoQueryConteudo($tipoConteudo,$editora,$request->input('colecao'));

        if($request->input('id')!=''){
            $sql->where('conteudos.id',$request->input('id'));
        }
        if($request->input('componente')!=''){
            $sql->where('disciplina_id',$request->input('componente'));
        }
        if($request->input('etapa')!=''){
            $sql->where('ciclo_id',$request->input('etapa'));
        }
        if($request->input('ano')!=''){
            $sql->where('cicloetapa_id',$request->input('ano'));
        }
        if($request->input('texto')!=''){
            $sql->where('conteudos.titulo','LIKE','%'.DB::raw($request->input('texto')).'%');
        }
        if($request->input('periodo')!=''){
            $sql->where('periodo',$request->input('periodo'));
        }
        $sql = $sql->selectRaw('conteudos.*, disciplinas.titulo as disciplina')
            ->orderBy('conteudos.titulo')
            ->paginate(20);

        return $sql;
    }
    */
    //Alteração no if ($tipoConteudo == 104), para regra de visualização da BNCC
    public function listaConteudo($tipoConteudo,$request,$editora = null)
    {
        $escolaDoUsuario = Auth::user()->escola;
        $colecaoIdSelect = DB::raw('colecao_livro_escola.colecao_id');

        $sql = $this->scopoQueryConteudo($tipoConteudo,$editora,$request->input('colecao'));

        if($request->input('id')!=''){
            $sql->where('conteudos.id',$request->input('id'));
        }
        if($request->input('componente')!=''){
            $sql->where('disciplina_id',$request->input('componente'));
        }
        if($request->input('etapa')!=''){
            $sql->where('ciclo_id',$request->input('etapa'));
        }
        if($request->input('ano')!=''){
            $sql->where('cicloetapa_id',$request->input('ano'));
        }
        if($request->input('texto')!=''){
            $sql->where('conteudos.titulo','LIKE','%'.DB::raw($request->input('texto')).'%');
        }
        if($request->input('periodo')!=''){
            $sql->where('periodo',$request->input('periodo'));
        }
        if ($tipoConteudo == 104) {
            $sql->leftJoin('colecao_livro_escola', 'conteudos.colecao_livro_id', '=', 'colecao_livro_escola.colecao_id')
                ->select('conteudos.*', 'colecao_livro_escola.colecao_id')
                ->where('colecao_livro_escola.escola_id', '=', $escolaDoUsuario->id);
        }
    
        $sql = $sql->selectRaw('conteudos.*, disciplinas.titulo as disciplina')
            ->where('conteudos.status', '=', 1) // filtro por status
            ->orderBy('conteudos.titulo')
            ->paginate(20);
    
        return $sql;
    }

    public function listaConteudoBibliotecaRoteiros($tipoConteudo,$request,$editora = null)
    {
        $sql = $this->scopoQueryConteudo($tipoConteudo,$editora,$request->input('colecao'));

        if($request->input('id')!=''){
            $sql->where('conteudos.id',$request->input('id'));
        }
        if($request->input('componente')!=''){
            $sql->where('disciplina_id',$request->input('componente'));
        }
        if($request->input('etapa')!=''){
            $sql->where('ciclo_id',$request->input('etapa'));
        }
        if($request->input('ano')!=''){
            $sql->where('cicloetapa_id',$request->input('ano'));
        }
        if($request->input('texto')!=''){
            $sql->where('conteudos.titulo','LIKE','%'.DB::raw($request->input('texto')).'%');
        }
        if($request->input('periodo')!=''){
            $sql->where('periodo',$request->input('periodo'));
        }
        $sql = $sql->selectRaw('conteudos.*, disciplinas.titulo as disciplina')
            ->orderBy('conteudos.titulo')
            ->get();

        return $sql;
    }

    public function disciplinasDisponiveis($tipoConteudo,$editora = null)
    {
        $sql = $this->scopoQueryConteudo($tipoConteudo,$editora);

        return 	$sql->groupBy('disciplinas.id')
            ->selectRaw('disciplinas.*')
            ->get();
    }

    public function defineMenuPesquisa($tipoConteudo, $colecao, $etapa, $editora = null)
    {
        $retorno = [
            'periodo'       => $this->periodoConteudos($tipoConteudo, $editora, $colecao),
            'etapas'		=> $this->etapasConteudo($tipoConteudo, $editora, $colecao),
            'anos'			=> null,
            'disciplinas' 	=> $this->disciplinasConteudo($tipoConteudo, $editora, $colecao),
        ];

        if($etapa!='')
        {
            $retorno['anos'] = $this->anosConteudo($tipoConteudo, $etapa, $editora, $colecao);
        }

        return $retorno;
    }

    private function periodoConteudos($tipoConteudo, $editora, $colecao){
        $sql = $this->scopoQueryConteudo($tipoConteudo, $editora, $colecao);

        return $sql->groupBy('periodo')
                   ->orderBy('periodo')
                   ->get();
    }

    private function etapasConteudo($tipoConteudo, $editora, $colecao)
    {
        $sql = $this->scopoQueryConteudo($tipoConteudo,$editora, $colecao);

        return 	$sql->join('ciclos','ciclos.id','conteudos.ciclo_id')
            ->groupBy('ciclos.id')
            ->orderBy('ciclos.id')
            ->selectRaw('ciclos.*')
            ->get();
    }

    private function anosConteudo($tipoConteudo, $idEtapa, $editora, $colecao)
    {
        $sql = $this->scopoQueryConteudo($tipoConteudo, $editora, $colecao);

        return 	$sql->join('ciclo_etapas','ciclo_etapas.id','conteudos.cicloetapa_id')
            ->where('conteudos.ciclo_id',$idEtapa)
            ->groupBy('ciclo_etapas.id')
            ->orderBy('ciclo_etapas.id')
            ->selectRaw('ciclo_etapas.*')
            ->get();
    }

    private function disciplinasConteudo($tipoConteudo, $editora, $colecao)
    {
        $sql = $this->scopoQueryConteudo($tipoConteudo, $editora, $colecao);

        return 	$sql->groupBy('disciplinas.id')
            ->selectRaw('disciplinas.*')
            ->orderBy('disciplinas.titulo')
            ->get();
    }

    public function getConteudo($idConteudo)
    {
        //$sql = $this->scopoQueryLivro();
        return Conteudo::where('conteudos.id',$idConteudo)->first();

    }

    private function colecoesGerais($tipoConteudo,$editora)
    {
        $retorno = $this->scopoQueryConteudo($tipoConteudo,$editora);

        if($tipoConteudo!=3) {
            $retorno = $retorno->join('colecao_livros', 'colecao_livros.id', 'conteudos.colecao_livro_id')
                ->groupBy('colecao_livros.id')
                ->orderBy('colecao_livros.ordem')
                ->selectRaw('colecao_livros.*, conteudos.tipo as tipo_conteudo');
        }
        else
        {
            $retorno = $retorno->groupBy('disciplinas.id')
                ->orderBy('disciplinas.titulo')
                ->selectRaw("disciplinas.id, '3' as tipo, concat(disciplinas.id,'.webp') as img, disciplinas.titulo as nome ");
        }
        return $retorno->paginate(16);
    }

    private function colecoesGeraisRoteiro($tipoConteudo,$editora)
    {
        $retorno = $this->scopoQueryConteudo($tipoConteudo,$editora);

        if($tipoConteudo!=3) {
            $retorno = $retorno->join('colecao_livros', 'colecao_livros.id', 'conteudos.colecao_livro_id')
                ->groupBy('colecao_livros.id')
                ->orderBy('colecao_livros.ordem')
                ->selectRaw('colecao_livros.*, conteudos.tipo as tipo_conteudo');
        }
        else
        {
            $retorno = $retorno->groupBy('disciplinas.id')
                ->orderBy('disciplinas.titulo')
                ->selectRaw("disciplinas.id, '3' as tipo, concat(disciplinas.id,'.webp') as img, disciplinas.titulo as nome ");
        }
        return $retorno->get();
    }

    private function colecaoAudio()
    {
        $escola = session('escola');
        $idEscola = $escola['id'];

        return ColecaoLivros::join('colecao_audio_escola','colecao_livros.id','colecao_audio_escola.colecao_id')
            ->where('escola_id',$idEscola)
            ->selectRaw('colecao_livros.*')
            ->orderBy('ordem')
            ->paginate(16);
    }

    private function colecaoAudioRoteiro()
    {
        $escola = session('escola');
        $idEscola = $escola['id'];

        return ColecaoLivros::join('colecao_audio_escola','colecao_livros.id','colecao_audio_escola.colecao_id')
            ->where('escola_id',$idEscola)
            ->selectRaw('colecao_livros.*')
            ->orderBy('ordem')
            ->get();
    }

    private function colecaoDocumento()
    {
        $escola = session('escola');
        $idEscola = $escola['id'];

        return ColecaoLivros::join('colecao_documento_escola','colecao_livros.id','colecao_documento_escola.colecao_id')
            ->where('escola_id',$idEscola)
            ->selectRaw('colecao_livros.*')
            ->orderBy('ordem')
            ->paginate(16);
    }

    private function colecaoProva()
    {
        $escola = session('escola');
        $idEscola = $escola['id'];

        return ColecaoLivros::join('colecao_prova_escola','colecao_livros.id','colecao_prova_escola.colecao_id')
            ->where('escola_id',$idEscola)
            ->selectRaw('colecao_livros.*')
            ->orderBy('ordem')
            ->paginate(16);
    }

    private function colecaoLivro($colecao=null)
    {
        $retorno =  ColecaoLivros::join('colecao_livro_escola','colecao_livros.id','colecao_livro_escola.colecao_id')
            ->where('escola_id',auth()->user()->escola_id);

        $conteudo = Conteudo::where('tipo',104)
            ->groupBy('colecao_livro_id')
            ->get()->pluck('colecao_livro_id');
        $retorno = $retorno->whereIn('colecao_livros.id', $conteudo);

        return $retorno->selectRaw('colecao_livros.*, 104 as tipo_conteudo')
            ->orderBy('ordem')
            ->paginate(16);
    }

    private function colecaoLivroRoteiro($colecao=null)
    {
        $retorno =  ColecaoLivros::join('colecao_livro_escola','colecao_livros.id','colecao_livro_escola.colecao_id')
            ->where('escola_id',auth()->user()->escola_id);

        $conteudo = Conteudo::where('tipo',104)
            ->groupBy('colecao_livro_id')
            ->get()->pluck('colecao_livro_id');
        $retorno = $retorno->whereIn('colecao_livros.id', $conteudo);

        return $retorno->selectRaw('colecao_livros.*, 104 as tipo_conteudo')
            ->orderBy('ordem')
            ->get();
    }

    public function listaColecaoConteudo($tipoConteudo,$request,$editora)
    {
        if($tipoConteudo == 2){
            return $this->colecaoAudio();
        }
        /*
        if($tipoConteudo == 22){
            return $this->colecaoDocumento();
        }
        */
        elseif($tipoConteudo == 102)
        {
            return $this->colecaoProva();
        }
        elseif($tipoConteudo == 104)
        {
            return $this->colecaoLivro();
        }
        else
        {
            return $this->colecoesGerais($tipoConteudo,$editora);
        }
    }

    public function listaColecaoConteudoRoteiro($tipoConteudo,$request,$editora)
    {
        if($tipoConteudo == 2){
            return $this->colecaoAudioRoteiro();
        }
        /*
        if($tipoConteudo == 22){
            return $this->colecaoDocumento();
        }
        */
        elseif($tipoConteudo == 102)
        {
            return $this->colecaoProva();
        }
        elseif($tipoConteudo == 104)
        {
            return $this->colecaoLivroRoteiro();
        }
        else
        {
            return $this->colecoesGeraisRoteiro($tipoConteudo,$editora);
        }
    }

    public function definirExebirConteudo($dados)
    {
        $ret = [];
        foreach ($dados as $d) {
            switch ($d->tipo) {
                case 2:
                    $ret[$d->id]['conteudo'] = '<audio controls style="width: 100%;"><source src="' . route('conteudo.play.get-arquivo', ['idConteudo' => $d->id]) . '" type="audio/mp3">Seu navegado não suporte o formato de audio.</audio>';
                    $ret[$d->id]['rodape'] = '';
                    break;
                case 3:
                    $cont = str_replace("vimeo.com/", "player.vimeo.com/video/", $d->conteudo);
                    $ret[$d->id]['conteudo'] =  '<iframe  src="'.$cont.'" scrolling="no" allowfullscreen></iframe>';
                    $ret[$d->id]['rodape'] = '';
                    break;
                case 4:
                    $url = config('app.cdn').'/storage/' . $d->conteudo;
                    $ret[$d->id]['conteudo'] = '<iframe src="https://view.officeapps.live.com/op/view.aspx?src=' . $url . '" style="width: 100%; height: 41vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
                    $ret[$d->id]['rodape'] = '';
                    break;
                case 22:
                    $url = config('app.cdn').'/storage/' .$d->conteudo;
                    $ret[$d->id]['conteudo'] =  '<object data="' . $url  . '" type="application/pdf" style="width: 100%; height: 41vw;"></object>';
                    $ret[$d->id]['rodape'] = '';
                    break;
                case 100:
                    $img = explode('.',$d->conteudo);
                    $img = $img[0].'_view'.'.'.$img[1];
                    $img = config('app.cdn').'/storage/banco_imagens/views/'.$img;
                    $ret[$d->id]['conteudo'] =  '<img class="img-fluid" src="'.$img.'" style="object-fit:cover;max-width:100%;" />';
                    $ret[$d->id]['rodape'] = '';
                    break;
                case 101:
                    $ret[$d->id]['conteudo'] =  '<iframe  src="'.$d->conteudo.'" scrolling="no" allowfullscreen></iframe>';
                    $ret[$d->id]['rodape'] = '<br><p><b>'.$d->descricao.'</b></p><br><p style="font-size: 11px" >Fonte: '.$d->fonte.'</p>';
                    break;
                case 102:
                    $url_conteudo = config('app.cdn').'/storage/provas/' . $d->conteudo;
                    $ret[$d->id]['conteudo'] = '<iframe src="https://view.officeapps.live.com/op/view.aspx?src=' . $url_conteudo . '" style="width: 100%; height: 41vw;" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
                    $ret[$d->id]['rodape'] = '';
                    break;
            }
        }
        return $ret;
    }

    public function getConteudosParaRoteiros($idTipo,$request)
    {
        if($idTipo!=21){
            if($idTipo == 2 || $idTipo == 3 || $idTipo == 4) {
                return $this->listaConteudoBibliotecaRoteiros($idTipo,$request,1);
            }

            $conteudo = Conteudo::join('users','conteudos.user_id','users.id')->where('tipo',$idTipo)
                ->where(function($q){
                    if(auth()->user()->permissao == 'Z'){
                        $q->where('users.instituicao_id',1);
                    }
                    else{
                        $q->where('user_id',auth()->user()->id);
                        /*$q->where(function($qq){
                            $qq->orWhere('user_id',auth()->user()->id);
                            $qq->orWhere('instituicao_id',1);
                        });*/
                    }
                });


            $conteudo =  $conteudo->selectRaw('conteudos.*,users.nome_completo')
            ->get();

            return $conteudo;
        }
        else
        {
            $escola = session('escola');
            return $this->livroService->livrosParaTrilhas($escola['id'],$request->input('colecao'));
        }

    }


    /*
    /*   Funções para gerenciar audios nas instituicoes
    /*
    /*
    */

    public function colecaoAudioNaInstituicao($idInst,$idColecao = null)
    {
        $retorno =  ColecaoAudioInstituicao::join('colecao_livros','colecao_livros.id','colecao_audio_instituicao.colecao_id')
            ->with(['cicloEtapaColecao' => function($q) use($idInst){
                $q->leftJoin('colecao_audio_instituicao_permissao', function($qq) use($idInst){
                    $qq->on('colecao_audio_instituicao_permissao.instituicao_id',DB::raw($idInst))
                        ->on('colecao_audio_instituicao_permissao.colecao_id','conteudos.colecao_livro_id')
                        ->on('colecao_audio_instituicao_permissao.cicloetapa_id','conteudos.cicloetapa_id');
                });

            }])
            ->where('instituicao_id',$idInst)
            ->selectRaw('colecao_livros.*, colecao_audio_instituicao.todos');
        if($idColecao == null)
        {
            $retorno = $retorno->paginate(10);
        }
        else
        {
            $retorno = $retorno->where('colecao_livros.id',$idColecao)
                ->first();
        }
        return $retorno;

    }

    public function colecaoAudioForaDaInstituicao($idInst)
    {
        $conteudos =  ColecaoLivros::join('colecao_audio_instituicao','colecao_livros.id','colecao_audio_instituicao.colecao_id')
            ->where('instituicao_id',$idInst)
            ->selectRaw('colecao_livros.id as id')
            ->get();

        $conteudos = $conteudos->pluck('id');

        return ColecaoLivros::whereNotIn('id',$conteudos)->where('tipo',2)->orderBy('ordem')->get();
    }

    public function removerColecaoAudioInstituicao($idInst,$idColecao)
    {
        DB::beginTransaction();
        try
        {
            ColecaoAudioInstituicaoPermissao::where('instituicao_id',$idInst)->where('colecao_id',$idColecao)->delete();
            ColecaoAudioInstituicao::where('instituicao_id',$idInst)->where('colecao_id',$idColecao)->delete();

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }

    public function addColecaoAudioInstituicao($dados)
    {
        DB::beginTransaction();
        try
        {
            foreach($dados['colecao'] as $c){
                ColecaoAudioInstituicao::firstOrCreate([
                    'colecao_id'    => $c,
                    'instituicao_id'     => $dados['instituicao_id'],
                    'todos'         => '1',
                ]);
            }

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }

    public function permissaoColecaoAudioInstituicao($dados)
    {
        DB::beginTransaction();
        try
        {
            $colecao = $this->colecaoAudioNaInstituicao($dados['instituicao_id'],$dados['colecao_id']);
            $totalColecao = count($colecao->cicloEtapaColecao);


            ColecaoAudioInstituicaoPermissao::where('colecao_id',$dados['colecao_id'])->where('instituicao_id',$dados['instituicao_id'])->delete();

            if($totalColecao==count($dados['cicloetapa']))
            {
                $todos = '1';
            }
            else
            {
                $a = $dados['cicloetapa'];
                $todos = '0';
                foreach ($a as $d) {
                    ColecaoAudioInstituicaoPermissao::create([
                        'colecao_id'    => $dados['colecao_id'],
                        'instituicao_id'     => $dados['instituicao_id'],
                        'cicloetapa_id' => $d,
                    ]);
                }
            }

            DB::select('update colecao_audio_instituicao set todos = '.$todos.' where colecao_id= '.$dados['colecao_id'].' and instituicao_id ='.$dados['instituicao_id']);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {

            DB::rollback();
            return $e;
        }

    }
/*
    /*   Funções para gerenciar documentos nas instituicoes
    /*
    /*
    */

    public function colecaoDocumentoNaInstituicao($idInst,$idColecao = null)
    {
        $retorno =  ColecaoDocumentoInstituicao::join('colecao_livros','colecao_livros.id','colecao_documento_instituicao.colecao_id')
            ->with(['cicloEtapaColecao' => function($q) use($idInst){
                $q->leftJoin('colecao_documento_instituicao_permissao', function($qq) use($idInst){
                    $qq->on('colecao_documento_instituicao_permissao.instituicao_id',DB::raw($idInst))
                        ->on('colecao_documento_instituicao_permissao.colecao_id','conteudos.colecao_livro_id')
                        ->on('colecao_documento_instituicao_permissao.cicloetapa_id','conteudos.cicloetapa_id');
                });

            }])
            ->where('instituicao_id',$idInst)
            ->selectRaw('colecao_livros.*, colecao_documento_instituicao.todos');
        if($idColecao == null)
        {
            $retorno = $retorno->paginate(10);
        }
        else
        {
            $retorno = $retorno->where('colecao_livros.id',$idColecao)
                ->first();
        }
        return $retorno;

    }

    public function colecaoDocumentoForaDaInstituicao($idInst)
    {
        $conteudos =  ColecaoLivros::join('colecao_documento_instituicao','colecao_livros.id','colecao_documento_instituicao.colecao_id')
            ->where('instituicao_id',$idInst)
            ->selectRaw('colecao_livros.id as id')
            ->get();

        $conteudos = $conteudos->pluck('id');

        return ColecaoLivros::whereNotIn('id',$conteudos)->where('tipo',22)->orderBy('ordem')->get();
    }

    public function removerColecaoDocumentoInstituicao($idInst,$idColecao)
    {
        DB::beginTransaction();
        try
        {
            ColecaoDocumentoInstituicaoPermissao::where('instituicao_id',$idInst)->where('colecao_id',$idColecao)->delete();
            ColecaoDocumentoInstituicao::where('instituicao_id',$idInst)->where('colecao_id',$idColecao)->delete();

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }

    public function addColecaoDocumentoInstituicao($dados)
    {
        DB::beginTransaction();
        try
        {
            foreach($dados['colecao'] as $c){
                ColecaoDocumentoInstituicao::firstOrCreate([
                    'colecao_id'    => $c,
                    'instituicao_id'     => $dados['instituicao_id'],
                    'todos'         => '1',
                ]);
            }

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();

            return $e;
        }

    }

    public function permissaoColecaoDocumentoInstituicao($dados)
    {
        DB::beginTransaction();
        try
        {
            $colecao = $this->colecaoDocumentoNaInstituicao($dados['instituicao_id'],$dados['colecao_id']);
            $totalColecao = count($colecao->cicloEtapaColecao);


            ColecaoDocumentoInstituicaoPermissao::where('colecao_id',$dados['colecao_id'])->where('instituicao_id',$dados['instituicao_id'])->delete();

            if($totalColecao==count($dados['cicloetapa']))
            {
                $todos = '1';
            }
            else
            {
                $a = $dados['cicloetapa'];
                $todos = '0';
                foreach ($a as $d) {
                    ColecaoDocumentoInstituicaoPermissao::create([
                        'colecao_id'    => $dados['colecao_id'],
                        'instituicao_id'     => $dados['instituicao_id'],
                        'cicloetapa_id' => $d,
                    ]);
                }
            }

            DB::select('update colecao_documento_instituicao set todos = '.$todos.' where colecao_id= '.$dados['colecao_id'].' and instituicao_id ='.$dados['instituicao_id']);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {

            DB::rollback();
            return $e;
        }

    }

    /*
    /*   Funções para gerenciar provas nas instituicoes
    /*
    /*
    */

    public function colecaoProvaNaInstituicao($idInst,$idColecao = null)
    {
        $retorno =  ColecaoProvaInstituicao::join('colecao_livros','colecao_livros.id','colecao_prova_instituicao.colecao_id')
            ->with(['cicloEtapaColecao' => function($q) use($idInst){
                $q->leftJoin('colecao_prova_instituicao_permissao', function($qq) use($idInst){
                    $qq->on('colecao_prova_instituicao_permissao.instituicao_id',DB::raw($idInst))
                        ->on('colecao_prova_instituicao_permissao.colecao_id','conteudos.colecao_livro_id')
                        ->on('colecao_prova_instituicao_permissao.cicloetapa_id','conteudos.cicloetapa_id');
                });

            }])
            ->where('instituicao_id',$idInst)
            ->selectRaw('colecao_livros.*, colecao_prova_instituicao.todos');
        if($idColecao == null)
        {
            $retorno = $retorno->paginate(10);
        }
        else
        {
            $retorno = $retorno->where('colecao_livros.id',$idColecao)
                ->first();
        }
        return $retorno;

    }

    public function colecaoProvaForaDaInstituicao($idInst)
    {
        $conteudos =  ColecaoLivros::join('colecao_prova_instituicao','colecao_livros.id','colecao_prova_instituicao.colecao_id')
            ->where('instituicao_id',$idInst)
            ->selectRaw('colecao_livros.id as id')
            ->get();

        $conteudos = $conteudos->pluck('id');

        return ColecaoLivros::whereNotIn('id',$conteudos)->where('tipo',102)->orderBy('ordem')->get();
    }

    public function removerColecaoProvaInstituicao($idInst,$idColecao)
    {
        DB::beginTransaction();
        try
        {
            ColecaoProvaInstituicaoPermissao::where('instituicao_id',$idInst)->where('colecao_id',$idColecao)->delete();
            ColecaoProvaInstituicao::where('instituicao_id',$idInst)->where('colecao_id',$idColecao)->delete();

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }

    public function addColecaoProvaInstituicao($dados)
    {
        DB::beginTransaction();
        try
        {
            foreach($dados['colecao'] as $c){
                ColecaoProvaInstituicao::firstOrCreate([
                    'colecao_id'    => $c,
                    'instituicao_id'     => $dados['instituicao_id'],
                    'todos'         => '1',
                ]);
            }

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }

    public function permissaoColecaoProvaInstituicao($dados)
    {
        DB::beginTransaction();
        try
        {
            $colecao = $this->colecaoProvaNaInstituicao($dados['instituicao_id'],$dados['colecao_id']);
            $totalColecao = count($colecao->cicloEtapaColecao);


            ColecaoProvaInstituicaoPermissao::where('colecao_id',$dados['colecao_id'])->where('instituicao_id',$dados['instituicao_id'])->delete();

            if($totalColecao==count($dados['cicloetapa']))
            {
                $todos = '1';
            }
            else
            {
                $a = $dados['cicloetapa'];
                $todos = '0';
                foreach ($a as $d) {
                    ColecaoProvaInstituicaoPermissao::create([
                        'colecao_id'    => $dados['colecao_id'],
                        'instituicao_id'     => $dados['instituicao_id'],
                        'cicloetapa_id' => $d,
                    ]);
                }
            }

            DB::select('update colecao_prova_instituicao set todos = '.$todos.' where colecao_id= '.$dados['colecao_id'].' and instituicao_id ='.$dados['instituicao_id']);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }

    /*
    /*   Funções para gerenciar audios nas escolas
    /*
    /*
    */

    public function colecaoAudioNaEscola($idEscola,$idColecao = null)
    {
        $retorno =  ColecaoAudioEscola::join('colecao_livros','colecao_livros.id','colecao_audio_escola.colecao_id')
            ->with(['cicloEtapaColecao' => function($q) use($idEscola){
                $q->leftJoin('colecao_audio_escola_permissao', function($qq) use($idEscola){
                    $qq->on('colecao_audio_escola_permissao.escola_id',DB::raw($idEscola))
                        ->on('colecao_audio_escola_permissao.colecao_id','conteudos.colecao_livro_id')
                        ->on('colecao_audio_escola_permissao.cicloetapa_id','conteudos.cicloetapa_id');
                });

            }])
            ->where('escola_id',$idEscola)
            ->selectRaw('colecao_livros.*, colecao_audio_escola.todos');
        if($idColecao == null)
        {
            $retorno = $retorno->paginate(10);
        }
        else
        {
            $retorno = $retorno->where('colecao_livros.id',$idColecao)
                ->first();
        }
        return $retorno;

    }

    public function colecaoAudioForaDaEscola($idEscola)
    {
        $conteudos =  ColecaoLivros::join('colecao_audio_escola','colecao_livros.id','colecao_audio_escola.colecao_id')
            ->where('escola_id',$idEscola)
            ->selectRaw('colecao_livros.id as id')
            ->get();

        $conteudos = $conteudos->pluck('id');



        return ColecaoLivros::whereNotIn('id',$conteudos)->where('tipo',2)->orderBy('ordem')->get();
    }

    public function removerColecaoAudioEscola($idEscola,$idColecao)
    {
        DB::beginTransaction();
        try
        {
            ColecaoAudioEscolaPermissao::where('escola_id',$idEscola)->where('colecao_id',$idColecao)->delete();
            ColecaoAudioEscola::where('escola_id',$idEscola)->where('colecao_id',$idColecao)->delete();

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }

    public function addColecaoAudioEscola($dados)
    {
        DB::beginTransaction();
        try
        {
            foreach($dados['colecao'] as $c){
                ColecaoAudioEscola::firstOrCreate([
                    'colecao_id'    => $c,
                    'escola_id'     => $dados['escola_id'],
                    'todos'         => '1',
                ]);
            }

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }

    public function permissaoColecaoAudioEscola($dados)
    {
        DB::beginTransaction();
        try
        {
            $colecao = $this->colecaoAudioNaEscola($dados['escola_id'],$dados['colecao_id']);
            $totalColecao = count($colecao->cicloEtapaColecao);


            ColecaoAudioEscolaPermissao::where('colecao_id',$dados['colecao_id'])->where('escola_id',$dados['escola_id'])->delete();

            if($totalColecao==count($dados['cicloetapa']))
            {
                $todos = '1';
            }
            else
            {
                $a = $dados['cicloetapa'];
                $todos = '0';
                foreach ($a as $d) {
                    ColecaoAudioEscolaPermissao::create([
                        'colecao_id'    => $dados['colecao_id'],
                        'escola_id'     => $dados['escola_id'],
                        'cicloetapa_id' => $d,
                    ]);
                }
            }

            DB::select('update colecao_audio_escola set todos = '.$todos.' where colecao_id= '.$dados['colecao_id'].' and escola_id ='.$dados['escola_id']);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {

            DB::rollback();
            return $e;
        }

    }

/*
    /*   Funções para gerenciar documentos nas escolas
    /*
    /*
    */

    public function colecaoDocumentoNaEscola($idEscola,$idColecao = null)
    {
        $retorno =  ColecaoDocumentoEscola::join('colecao_livros','colecao_livros.id','colecao_documento_escola.colecao_id')
            ->with(['cicloEtapaColecao' => function($q) use($idEscola){
                $q->leftJoin('colecao_documento_escola_permissao', function($qq) use($idEscola){
                    $qq->on('colecao_documento_escola_permissao.escola_id',DB::raw($idEscola))
                        ->on('colecao_documento_escola_permissao.colecao_id','conteudos.colecao_livro_id')
                        ->on('colecao_documento_escola_permissao.cicloetapa_id','conteudos.cicloetapa_id');
                });

            }])
            ->where('escola_id',$idEscola)
            ->selectRaw('colecao_livros.*, colecao_documento_escola.todos');
        if($idColecao == null)
        {
            $retorno = $retorno->paginate(10);
        }
        else
        {
            $retorno = $retorno->where('colecao_livros.id',$idColecao)
                ->first();
        }
        return $retorno;

    }

    public function colecaoDocumentoForaDaEscola($idEscola)
    {
        $conteudos =  ColecaoLivros::join('colecao_documento_escola','colecao_livros.id','colecao_documento_escola.colecao_id')
            ->where('escola_id',$idEscola)
            ->selectRaw('colecao_livros.id as id')
            ->get();

        $conteudos = $conteudos->pluck('id');

        return ColecaoLivros::whereNotIn('id',$conteudos)->where('tipo',22)->orderBy('ordem')->get();
    }

    public function removerColecaoDocumentoEscola($idEscola,$idColecao)
    {
        DB::beginTransaction();
        try
        {
            ColecaoDocumentoEscolaPermissao::where('escola_id',$idEscola)->where('colecao_id',$idColecao)->delete();
            ColecaoDocumentoEscola::where('escola_id',$idEscola)->where('colecao_id',$idColecao)->delete();

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }

    public function addColecaoDocumentoEscola($dados)
    {
        DB::beginTransaction();
        try
        {
            foreach($dados['colecao'] as $c){
                ColecaoDocumentoEscola::firstOrCreate([
                    'colecao_id'    => $c,
                    'escola_id'     => $dados['escola_id'],
                    'todos'         => '1',
                ]);
            }

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e);
            return $e;
        }

    }

    public function permissaoColecaoDocumentoEscola($dados)
    {
        DB::beginTransaction();
        try
        {
            $colecao = $this->colecaoDocumentoNaEscola($dados['escola_id'],$dados['colecao_id']);
            $totalColecao = count($colecao->cicloEtapaColecao);


            ColecaoDocumentoEscolaPermissao::where('colecao_id',$dados['colecao_id'])->where('escola_id',$dados['escola_id'])->delete();

            if($totalColecao==count($dados['cicloetapa']))
            {
                $todos = '1';
            }
            else
            {
                $a = $dados['cicloetapa'];
                $todos = '0';
                foreach ($a as $d) {
                    ColecaoDocumentoEscolaPermissao::create([
                        'colecao_id'    => $dados['colecao_id'],
                        'escola_id'     => $dados['escola_id'],
                        'cicloetapa_id' => $d,
                    ]);
                }
            }

            DB::select('update colecao_documento_escola set todos = '.$todos.' where colecao_id= '.$dados['colecao_id'].' and escola_id ='.$dados['escola_id']);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {

            DB::rollback();
            return $e;
        }

    }


    /*
    /*   Funções para gerenciar provas nas escolas
    /*
    /*
    */

    public function colecaoProvaNaEscola($idEscola,$idColecao = null)
    {
        $retorno =  ColecaoProvaEscola::join('colecao_livros','colecao_livros.id','colecao_prova_escola.colecao_id')
            ->with(['cicloEtapaColecao' => function($q) use($idEscola){
                $q->leftJoin('colecao_prova_escola_permissao', function($qq) use($idEscola){
                    $qq->on('colecao_prova_escola_permissao.escola_id',DB::raw($idEscola))
                        ->on('colecao_prova_escola_permissao.colecao_id','conteudos.colecao_livro_id')
                        ->on('colecao_prova_escola_permissao.cicloetapa_id','conteudos.cicloetapa_id');
                });

            }])
            ->where('escola_id',$idEscola)
            ->selectRaw('colecao_livros.*, colecao_prova_escola.todos');
        if($idColecao == null)
        {
            $retorno = $retorno->paginate(10);
        }
        else
        {
            $retorno = $retorno->where('colecao_livros.id',$idColecao)
                ->first();
        }
        return $retorno;

    }

    public function colecaoProvaForaDaEscola($idEscola)
    {
        $conteudos =  ColecaoLivros::join('colecao_prova_escola','colecao_livros.id','colecao_prova_escola.colecao_id')
            ->where('escola_id',$idEscola)
            ->selectRaw('colecao_livros.id as id')
            ->get();

        $conteudos = $conteudos->pluck('id');

        return ColecaoLivros::whereNotIn('id',$conteudos)->where('tipo',102)->orderBy('ordem')->get();
    }

    public function removerColecaoProvaEscola($idEscola,$idColecao)
    {
        DB::beginTransaction();
        try
        {
            ColecaoProvaEscolaPermissao::where('escola_id',$idEscola)->where('colecao_id',$idColecao)->delete();
            ColecaoProvaEscola::where('escola_id',$idEscola)->where('colecao_id',$idColecao)->delete();

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }

    public function addColecaoProvaEscola($dados)
    {
        DB::beginTransaction();
        try
        {
            foreach($dados['colecao'] as $c){
                ColecaoProvaEscola::firstOrCreate([
                    'colecao_id'    => $c,
                    'escola_id'     => $dados['escola_id'],
                    'todos'         => '1',
                ]);
            }

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }

    }

    public function permissaoColecaoProvaEscola($dados)
    {
        DB::beginTransaction();
        try
        {
            $colecao = $this->colecaoProvaNaEscola($dados['escola_id'],$dados['colecao_id']);
            $totalColecao = count($colecao->cicloEtapaColecao);


            ColecaoProvaEscolaPermissao::where('colecao_id',$dados['colecao_id'])->where('escola_id',$dados['escola_id'])->delete();

            if($totalColecao==count($dados['cicloetapa']))
            {
                $todos = '1';
            }
            else
            {
                $a = $dados['cicloetapa'];
                $todos = '0';
                foreach ($a as $d) {
                    ColecaoProvaEscolaPermissao::create([
                        'colecao_id'    => $dados['colecao_id'],
                        'escola_id'     => $dados['escola_id'],
                        'cicloetapa_id' => $d,
                    ]);
                }
            }

            DB::select('update colecao_prova_escola set todos = '.$todos.' where colecao_id= '.$dados['colecao_id'].' and escola_id ='.$dados['escola_id']);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {

            DB::rollback();
            return $e;
        }

    }

    public function conteudoBncc($id){
        $conteudo = Conteudo::find($id);
        $colecao = $this->colecaoLivro($conteudo->colecao_livro_id);
        if($colecao){
            return $conteudo;
        }else{
            return false;
        }
    }

}
