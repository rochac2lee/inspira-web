<?php
namespace App\Services\Fr;
use App\Models\Ciclo;
use App\Models\Conteudo;
use App\Models\FrAlbumAudioCast;
use App\Models\FrAudioAlbumAudioCast;
use App\Models\FrAudioCast;
use App\Models\FrAudioPlaylistAudioCast;
use App\Models\FrConteudo;
use App\Models\FrPlaylistAudioCast;
use App\Library\Slim;
use DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class CastService {

    public function __construct(){

    }


    public function getListaAudioFormAlbum($pagina, $pesquisa = null)
    {
        $sql = Conteudo::where('tipo',2)
                        ->with(['ciclo', 'cicloetapa', 'rel_disciplina', 'categoria']);
        if(auth()->user()->permissao == 'Z') /// albuns da Opet
        {
            $sql = $sql->where('conteudos.instituicao_id',1);
        }else{
            $sql = $sql->join('fr_audio_cast','conteudos.id','fr_audio_cast.conteudo_id')
                        ->where(function($query){
                            $query->orWhere(function($q){
                                $q->where('fr_audio_cast.instituicao_id',1)
                                    ->where('publicado',1)
                                    ->where('eh_dono',1);
                            })
                            ->orWhere(function($q){
                                $q->where('fr_audio_cast.user_id',auth()->user()->id)
                                    ->where('fr_audio_cast.instituicao_id',auth()->user()->instituicao_id)
                                    ->where('fr_audio_cast.escola_id',auth()->user()->escola_id);
                            });
                        });

        }

        /// pesquisa
        if(isset($pesquisa['palavra_chave']) && $pesquisa['palavra_chave'] != ''){
            $sql = $sql->where(function($q) use($pesquisa){
                $q->orWhere('conteudos.apoio','like','%'.$pesquisa['palavra_chave'].'%');
                $q->orWhere('conteudos.titulo','like','%'.$pesquisa['palavra_chave'].'%');
            });
        }
        if(isset($pesquisa['disciplina']) && $pesquisa['disciplina'] != ''){
            $sql = $sql->where('conteudos.disciplina_id',$pesquisa['disciplina']);
        }
        if(isset($pesquisa['categoria']) && $pesquisa['categoria'] != ''){
            $sql = $sql->where('conteudos.categoria_id',$pesquisa['categoria']);
        }
        if(isset($pesquisa['etapa']) && $pesquisa['etapa'] != ''){
            $sql = $sql->where('conteudos.cicloetapa_id',$pesquisa['etapa']);
        }
        if(isset($pesquisa['selecionados']) && $pesquisa['selecionados'] != ''){
            $sel = explode(',',$pesquisa['selecionados']);
            $sql = $sql->whereNotIn('conteudos.id',$sel);
        }

        if(isset($pesquisa['com_selecionados']) && $pesquisa['com_selecionados'] != ''){
            $sel = '';
            foreach($pesquisa['com_selecionados'] as $s){
                $sel .= ','.$s;
            }
            $sql = $sql->whereIn('conteudos.id',$pesquisa['com_selecionados'])
                        ->orderByRaw('field( conteudos.id'.$sel.')');
        }

        /// variaveis para construir a url no select, devido a diferença de armazenamento fora do storage feito pela edulabz
        $cdn = config('app.cdn');
        $url = url('');
        $sql = $sql->selectRaw("IF(conteudos.colecao_livro_id is null, CONCAT('".$cdn."/storage/cast/',conteudos.user_id,'/audio/',conteudos.conteudo), CONCAT('".$url."/public/',conteudos.conteudo)) as audio, IF(conteudos.colecao_livro_id is null, CONCAT('".$cdn."/storage/cast/',conteudos.user_id,'/capa/',conteudos.capa), CONCAT('".$cdn."/storage/capa_audios/',conteudos.capa)) as capa_audio,  conteudos.*")
                                ->orderBy('conteudos.id','DESC');
        if($pagina>0){
            return $sql->paginate($pagina);
        }else{
            return $sql->get();
        }

    }

    public function get($pagina, $pesquisa = null)
    {
        if(!isset($pesquisa['tipo']) || $pesquisa['tipo']==''){
            return $this->getListaAudio($pagina, $pesquisa );
        }elseif(isset($pesquisa['tipo']) && $pesquisa['tipo']=='1') {
            return $this->getListaAlbum($pagina, $pesquisa );
        }else{
            return $this->getListaPlaylist($pagina, $pesquisa );
        }
    }

    public function getListaAlbum($pagina = null, $pesquisa = null)
    {
        if(auth()->user()->permissao == 'Z') /// albuns da Opet
        {
            $sql = FrAlbumAudioCast::where('fr_album_audio_cast.instituicao_id',1);
        }elseif( auth()->user()->permissao != 'A') /// albuns do professor
        {
            if(!isset($pesquisa['biblioteca']) || $pesquisa['biblioteca']!=1)
            {
                $sql = FrAlbumAudioCast::where('fr_album_audio_cast.instituicao_id', auth()->user()->instituicao_id)
                    ->where('fr_album_audio_cast.escola_id', auth()->user()->escola_id)
                    ->where('fr_album_audio_cast.user_id', auth()->user()->id);
            }
            else
            {
                $sql = FrAlbumAudioCast::where('fr_album_audio_cast.instituicao_id',1)
                                        ->where('publicado',1);
            }
        }else{
            $sql = FrAlbumAudioCast::where('fr_album_audio_cast.instituicao_id', auth()->user()->instituicao_id)
                ->where('fr_album_audio_cast.escola_id', auth()->user()->escola_id)
                ->where('publicado',1);
        }

        /// pesquisa
        if(isset($pesquisa['texto']) && $pesquisa['texto'] != ''){
            $sql = $sql->where(function($q) use($pesquisa){
                $q->orWhere('fr_album_audio_cast.palavras_chave','like','%'.$pesquisa['texto'].'%');
                $q->orWhere('fr_album_audio_cast.titulo','like','%'.$pesquisa['texto'].'%');
            });
        }
        if(isset($pesquisa['componente']) && $pesquisa['componente'] != ''){
            $sql = $sql->where('fr_album_audio_cast.disciplina_id',$pesquisa['componente']);
        }
        if(isset($pesquisa['categoria']) && $pesquisa['categoria'] != ''){
            $sql = $sql->where('fr_album_audio_cast.categoria_id',$pesquisa['categoria']);
        }
        if(isset($pesquisa['ciclo_etapa']) && $pesquisa['ciclo_etapa'] != ''){
            $sql = $sql->where('fr_album_audio_cast.cicloetapa_id',$pesquisa['ciclo_etapa']);
        }
        if(isset($pesquisa['exibicao']) && $pesquisa['exibicao'] != '' &&  $pesquisa['biblioteca']!=1){
            $sql = $sql->where('fr_album_audio_cast.publicado',$pesquisa['exibicao']);
        }

        $cdn = config('app.cdn');
        return $sql->selectRaw(" CONCAT('".$cdn."/storage/cast/',user_id,'/capa/',capa) as capa_album, fr_album_audio_cast.*")
            ->paginate($pagina);
    }

    public function getListaPlaylist($pagina = null, $pesquisa = null)
    {
        if(auth()->user()->permissao == 'Z') /// albuns da Opet
        {
            $sql = FrPlaylistAudioCast::where('fr_playlist_audio_cast.instituicao_id',1);
        }
        elseif( auth()->user()->permissao != 'A') /// albuns do professor
        {
            if(!isset($pesquisa['biblioteca']) || $pesquisa['biblioteca']!=1)
            {
                $sql = FrPlaylistAudioCast::where('fr_playlist_audio_cast.instituicao_id', auth()->user()->instituicao_id)
                    ->where('fr_playlist_audio_cast.escola_id', auth()->user()->escola_id)
                    ->where('fr_playlist_audio_cast.user_id', auth()->user()->id);
            }
            else
            {
                $sql = FrPlaylistAudioCast::where('fr_playlist_audio_cast.instituicao_id',1)
                                            ->where('publicado',1);
            }
        }else{
            $sql = FrPlaylistAudioCast::where('fr_playlist_audio_cast.instituicao_id', auth()->user()->instituicao_id)
                ->where('fr_playlist_audio_cast.escola_id', auth()->user()->escola_id)
                ->where('publicado',1);
        }

        /// pesquisa
        if(isset($pesquisa['texto']) && $pesquisa['texto'] != ''){
            $sql = $sql->where(function($q) use($pesquisa){
                $q->orWhere('fr_playlist_audio_cast.palavras_chave','like','%'.$pesquisa['texto'].'%');
                $q->orWhere('fr_playlist_audio_cast.titulo','like','%'.$pesquisa['texto'].'%');
            });
        }

        if(isset($pesquisa['exibicao']) && $pesquisa['exibicao'] != '' && $pesquisa['biblioteca']!=1 ){
            $sql = $sql->where('fr_playlist_audio_cast.publicado',$pesquisa['exibicao']);
        }


        $cdn = config('app.cdn');
        return $sql->selectRaw(" CONCAT('".$cdn."/storage/cast/',user_id,'/capa/',capa) as capa_album, fr_playlist_audio_cast.*")
            ->paginate($pagina);
    }

    public function getListaAudio($pagina = null, $pesquisa = null)
    {
        $sql = FrAudioCast::join('conteudos','conteudos.id','fr_audio_cast.conteudo_id')
                            ->where('conteudos.tipo',2);

        /// filtro das TABS
        if(auth()->user()->permissao == 'Z') /// áudios da Opet
        {
            $sql = $sql->where('fr_audio_cast.instituicao_id',1)
                        ->where('fr_audio_cast.eh_dono',1);
        }
        elseif( auth()->user()->permissao != 'A') /// áudios do professor
        {
            if(!isset($pesquisa['biblioteca']) || $pesquisa['biblioteca']!=1)
            {
                $sql = $sql->where('fr_audio_cast.instituicao_id', auth()->user()->instituicao_id)
                    ->where('fr_audio_cast.escola_id', auth()->user()->escola_id)
                    ->where('fr_audio_cast.user_id', auth()->user()->id);
            }
            else
            {
                $sql = $sql->where('fr_audio_cast.instituicao_id',1)
                        ->where('fr_audio_cast.eh_dono',1)
                        ->where('publicado',1);
            }
        }else{  /// áudios do aluno
            $sql = $sql->where('fr_audio_cast.instituicao_id', auth()->user()->instituicao_id)
                ->where('fr_audio_cast.escola_id', auth()->user()->escola_id)
                ->where('publicado',1);
        }

        /// pesquisa
        if(isset($pesquisa['texto']) && $pesquisa['texto'] != ''){
            $sql = $sql->where(function($q) use($pesquisa){
                $q->orWhere('conteudos.apoio','like','%'.$pesquisa['texto'].'%');
                $q->orWhere('conteudos.titulo','like','%'.$pesquisa['texto'].'%');
            });
        }
        if(isset($pesquisa['componente']) && $pesquisa['componente'] != ''){
            $sql = $sql->where('conteudos.disciplina_id',$pesquisa['componente']);
        }
        if(isset($pesquisa['categoria']) && $pesquisa['categoria'] != ''){
            $sql = $sql->where('conteudos.categoria_id',$pesquisa['categoria']);
        }
        if(isset($pesquisa['ciclo_etapa']) && $pesquisa['ciclo_etapa'] != ''){
            $sql = $sql->where('conteudos.cicloetapa_id',$pesquisa['ciclo_etapa']);
        }
        if(isset($pesquisa['exibicao']) && $pesquisa['exibicao'] != '' && $pesquisa['biblioteca']!=1){
            $sql = $sql->where('fr_audio_cast.publicado',$pesquisa['exibicao']);
        }

        /// variaveis para construir a url no select, devido a diferença de armazenamento fora do storage feito pela edulabz
        $cdn = config('app.cdn');
        $url = url('');

        return $sql->selectRaw("IF(conteudos.colecao_livro_id is null, CONCAT('".$cdn."/storage/cast/',conteudos.user_id,'/audio/',conteudos.conteudo), CONCAT('".$url."/public/',conteudos.conteudo)) as audio, IF(conteudos.colecao_livro_id is null, CONCAT('".$cdn."/storage/cast/',conteudos.user_id,'/capa/',conteudos.capa), CONCAT('".$cdn."/storage/capa_audios/',conteudos.capa)) as capa_audio,  conteudos.*, fr_audio_cast.id as cast_id, fr_audio_cast.instituicao_id as cast_instituicao_id ,fr_audio_cast.escola_id as cast_escola_id, fr_audio_cast.user_id as cast_user_id, eh_dono, publicado")
                    ->paginate($pagina);
    }

    public function inserir($request){
        $dados = $request->all();
        DB::beginTransaction();
        try
        {
            $dados['tipo']		    = 2;
            $dados['user_id']		= auth()->user()->id;
            $dados['escola_id']		= auth()->user()->escola_id;
            $dados['instituicao_id']= auth()->user()->instituicao_id;
            $c = explode(';',$dados['ciclo_etapa_id']);
            $dados['ciclo_id'] = $c[0];
            $dados['cicloetapa_id'] = $c[1];
            $capa = $this->addCapa();
            if($capa!=null) {
                $dados['capa'] = $capa;
            }

            $audio = $this->addAudio($request);
            if($audio!=null) {
                $dados['conteudo'] = $audio;
            }
            $conteudo = new FrConteudo($dados);
            $conteudo->save();
            $dadosAudio =[
                'conteudo_id'  => $conteudo->id,
                'user_id'       => $dados['user_id'],
                'escola_id'     => $dados['escola_id'],
                'instituicao_id'=> $dados['instituicao_id'],
                'eh_dono'       => 1,
            ];
            $audio = new FrAudioCast($dadosAudio);
            $audio->save();

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            dd($e->getMessage());
            DB::rollback();
            return false;
        }
    }

    public function editar($request){
        $dados = $request->all();
        DB::beginTransaction();
        try
        {
            $conteudo = Conteudo::where('tipo',2);
            if(auth()->user()->permissao == 'Z'){
                $conteudo = $conteudo->where('instituicao_id',1);
            }else{
                $conteudo = $conteudo->where('user_id',auth()->user()->id);
            }
            $conteudo = $conteudo->find($dados['id']);
            $c = explode(';',$dados['ciclo_etapa_id']);
            $dados['ciclo_id'] = $c[0];
            $dados['cicloetapa_id'] = $c[1];

            if(!isset($dados['existeImg']) || $dados['existeImg']=='') {
                $capa = $this->addCapa();
                if ($capa != null) {
                    $dados['capa'] = $capa;
                }
                Storage::delete('public/cast/' . auth()->user()->id .'/capa/'.$conteudo->capa);
            }

            if(!isset($dados['existeAudio']) || $dados['existeAudio']=='') {
                $audio = $this->addAudio($request);
                if ($audio != null) {
                    $dados['conteudo'] = $audio;
                }
                Storage::delete('public/cast/'.auth()->user()->id.'/audio/'.$conteudo->conteudo);
            }
            $conteudo->update($dados);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            dd($e->getMessage());
            DB::rollback();
            return false;
        }
    }


    public function excluir($id){
        DB::beginTransaction();
        try
        {
            $conteudo = Conteudo::where('tipo',2);
            if(auth()->user()->permissao == 'Z'){
                $conteudo = $conteudo->where('instituicao_id',1);
            }else{
                $conteudo = $conteudo->where('user_id',auth()->user()->id);
            }
            $conteudo = $conteudo->find($id);

            if(isset($conteudo->id) ) { //// ele é dono do audio
                $audioCast = FrAudioCast::where('fr_audio_cast.conteudo_id',$id)
                    ->get();
                foreach($audioCast as $a){
                    FrAudioAlbumAudioCast::where('audio_id',$a->id)->delete();
                }
                FrAudioCast::where('fr_audio_cast.conteudo_id',$id)->delete();
                if ($conteudo->colecao_livro_id == '') { /// só deleta o conteudo se não for de coleção
                    if ($conteudo->capa != '') {
                        Storage::disk()->delete(config('app.frStorage').'cast/' . auth()->user()->id . '/capa/' . $conteudo->capa);
                    }
                    if ($conteudo->conteudo != '') {
                        Storage::disk()->delete(config('app.frStorage').'cast/' . auth()->user()->id . '/audio/' . $conteudo->conteudo);
                    }
                    $conteudo->delete();
                }
            }
            else{
                FrAudioCast::where('fr_audio_cast.conteudo_id',$id)
                            ->where('user_id',auth()->user()->id)
                            ->where('escola_id',auth()->user()->escola_id)
                            ->where('instituicao_id',auth()->user()->instituicao_id)
                            ->delete();
            }

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e->getMessage());
            return false;
        }
    }

    public function getForm($id){
        $cdn = config('app.cdn');
        $url = url('');
        $conteudo = Conteudo::where('tipo',2);
        if(auth()->user()->permissao == 'Z'){
            $conteudo = $conteudo->where('instituicao_id',1);
        }else{
            $conteudo = $conteudo->where('user_id',auth()->user()->id);
        }
        return  $conteudo->whereNull('colecao_livro_id')
            ->selectRaw("IF(conteudos.colecao_livro_id is null, CONCAT('".$cdn."/storage/cast/',conteudos.user_id,'/audio/',conteudos.conteudo), CONCAT('".$url."/public/',conteudos.conteudo)) as audio, IF(conteudos.colecao_livro_id is null, CONCAT('".$cdn."/storage/cast/',conteudos.user_id,'/capa/',conteudos.capa), CONCAT('".$cdn."/storage/capa_audios/',conteudos.capa)) as capa_audio,
                        conteudos.id, conteudos.titulo, conteudos.disciplina_id, conteudos.ciclo_id, conteudos.cicloetapa_id, conteudos.categoria_id, conteudos.descricao, conteudos.apoio")
            ->find($id);
    }

    private function addCapa()
    {
        $images = Slim::getImages('imagem');
        if(count($images)>0)
        {
            $image = $images[0];
            // let's create some shortcuts
            $name = explode('.',$image['input']['name']);
            $ext = '.'.$name[count($name)-1];
            $data = $image['output']['data'];

            // store the file
            $file = Slim::saveFile($data, $ext,config('app.frTmp'));
            $fileName = $file['name'];
            $img = Image::make(config('app.frTmp').$fileName);
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp', 90);

            $resource = $img->stream()->detach();
            $fileName = $img->filename.'.webp';

            Storage::disk()->put(config('app.frStorage').'cast/'.auth()->user()->id.'/capa/'.$fileName, $resource);
            return $fileName;
        }
        else
        {
            return null;
        }

    }

    private function addAudio($request)
    {
        if($request->input('audio_gravado')!=''){
            $audio = explode('/', $request->input('audio_gravado'));
            $audio = $audio[count($audio)-1];
            Storage::move($request->input('audio_gravado'), config('app.frStorage').'cast/'.auth()->user()->id.'/audio/'.$audio);
            return $audio;
        }
        elseif($request->file('audio'))
        {
            $audio = $request->file('audio');
            $audio = $audio->store(
                config('app.frStorage').'cast/'.auth()->user()->id.'/audio/'
            );
            $audio = explode('/', $audio);
            $nomeArquivo = $audio[count($audio)-1];
            return $nomeArquivo;
        }
        else
        {
            return null;
        }

    }

    public function cicloEtapa()
    {
        return Ciclo::join('ciclo_etapas','ciclos.id','ciclo_etapas.ciclo_id')
            ->orderBy('ciclos.titulo')
            ->orderBy('ciclo_etapas.titulo')
            ->selectRaw('ciclo_etapas.id, ciclos.id as ciclo_id, ciclos.titulo as ciclo, ciclo_etapas.titulo as ciclo_etapa')
            ->get();
    }


    /// Gestão álbum


    public function inserirAlbum($request){
        $dados = $request->all();
        DB::beginTransaction();
        try
        {
            $dados['user_id']		= auth()->user()->id;
            $dados['escola_id']		= auth()->user()->escola_id;
            $dados['instituicao_id']= auth()->user()->instituicao_id;

            $capa = $this->addCapa();
            if($capa!=null) {
                $dados['capa'] = $capa;
            }

            $album = new FrAlbumAudioCast($dados);
            $album->save();
            $this->addAudioAlbum($album, $dados['lista_audio']);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function editarAlbum($request){
        $dados = $request->all();
        DB::beginTransaction();
        try
        {
            if(auth()->user()->permissao == 'Z'){
                $album = FrAlbumAudioCast::where('instituicao_id',1)->find($dados['id']);
            }else{
                $album = FrAlbumAudioCast::where('user_id',auth()->user()->id)->find($dados['id']);
            }
            if(!isset($dados['existeImg']) || $dados['existeImg']=='') {
                $capa = $this->addCapa();
                if ($capa != null) {
                    $dados['capa'] = $capa;
                }
                Storage::delete('public/cast/' . auth()->user()->id .'/capa/'.$album->capa);
            }

            $album->update($dados);
            $this->addAudioAlbum($album, $dados['lista_audio']);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e->getMessage());
            return false;
        }
    }

    private function addAudioAlbum($album,$selecionados){
        if(auth()->user()->permissao == 'Z'){
            $audios = Conteudo::where('tipo',2)->whereIn('id',$selecionados)->get();
            foreach($audios as $a)
            {
                $tem = FrAudioCast::where('conteudo_id',$a->id)->where('instituicao_id',1)->first();
                if(!$tem){
                    $audio = [
                        'conteudo_id'   => $a->id,
                        'user_id'       => auth()->user()->id,
                        'instituicao_id'=> 1,
                        'escola_id'     => auth()->user()->escola_id,
                        'eh_dono'       => 1,
                    ];
                    $frAudio = new FrAudioCast($audio);
                    $frAudio->save();
                }
            }
        }

        $ordem = 1;
        $audios = [];
        if(auth()->user()->permissao == 'Z') {
            $sel = FrAudioCast::whereIn('conteudo_id', $selecionados)->where('instituicao_id', 1)->where('eh_dono', 1)->selectRaw('conteudo_id, id')->get();
        }
        else{
            $sel = FrAudioCast::whereIn('conteudo_id', $selecionados)->where('user_id', auth()->user()->id)->where('eh_dono', 1)->selectRaw('conteudo_id, id')->get();
        }

        foreach($selecionados as $s){
            foreach ($sel as $q){
                if($q->conteudo_id == $s){
                    $audio = [];
                    $audio['audio_id'] = $q->id;
                    $audio['ordem'] = $ordem;
                    $audios[] = $audio;
                    $ordem++;
                }
            }
        }
        $album->audios()->detach();
        $album->audios()->attach($audios);
    }

    public function excluirAlbum($id){
        DB::beginTransaction();
        try
        {
            if(auth()->user()->permissao == 'Z'){
                $album = FrAlbumAudioCast::where('instituicao_id',1)->find($id);
            }else{
                $album = FrAlbumAudioCast::where('user_id',auth()->user()->id)->find($id);
            }
            if($album->capa != '') {
                Storage::delete('public/cast/' . auth()->user()->id . '/capa/'.$album->capa);
            }
            FrAudioAlbumAudioCast::where('album_id',$id)->delete();
            $album->delete();
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function getAlbum($id, $lista=null){
        $cdn = config('app.cdn');
        if(auth()->user()->permissao == 'Z'){
            $sql = FrAlbumAudioCast::where('instituicao_id',1);
        }elseif( auth()->user()->permissao != 'A') /// áudios do professor
        {
            $sql = FrAlbumAudioCast::whereOr(function($q){
                $q->where('instituicao_id', auth()->user()->instituicao_id)
                    ->where('escola_id', auth()->user()->escola_id)
                    ->where('user_id', auth()->user()->id)
                    ->where('eh_dono',1);
            })->whereOr(function($q){
                $q->where('instituicao_id',1);
            });

        }else{
            $sql = FrAlbumAudioCast::where('user_id',auth()->user()->id);
        }
        if($lista){
            $sql = $sql->with('lista');
        }else{
            $sql = $sql->with('audios');
        }
        $sql = $sql->selectRaw(" CONCAT('".$cdn."/storage/cast/',user_id,'/capa/',capa) as capa_album, fr_album_audio_cast.*")
            ->find($id);

        return $sql;
    }

    public function audiosParaAlBum($pesquisa){
        $pagina = null;
        if(isset($pesquisa['page']) && $pesquisa['page'] != ''){
            $pagina = 30;
        }

        $lista = $this->getListaAudioFormAlbum($pagina, $pesquisa);
        $view = [
            'audios' => $lista,
        ];

        if($pagina) {
            return [
                'questao' => view('fr/cast/lista_audio_album', $view)->render(),
                'total' => $lista->total(),
                'exibindo' => count($lista),
            ];
        }else{
            return view('fr/cast/lista_audio_album', $view)->render();
        }
    }

    /// Gestão playlist


    public function inserirPlaylist($request){
        $dados = $request->all();
        DB::beginTransaction();
        try
        {
            $dados['user_id']		= auth()->user()->id;
            $dados['escola_id']		= auth()->user()->escola_id;
            $dados['instituicao_id']= auth()->user()->instituicao_id;

            $capa = $this->addCapa();
            if($capa!=null) {
                $dados['capa'] = $capa;
            }

            $album = new FrPlaylistAudioCast($dados);
            $album->save();
            $this->addAudioPlaylist($album, $dados['lista_audio']);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e->getMessage());
            return false;
        }
    }

    public function editarPlaylist($request){
        $dados = $request->all();
        DB::beginTransaction();
        try
        {
            if(auth()->user()->permissao == 'Z'){
                $album = FrPlaylistAudioCast::where('instituicao_id',1)->find($dados['id']);
            }else{
                $album = FrPlaylistAudioCast::where('user_id',auth()->user()->id)->find($dados['id']);
            }

            if(!isset($dados['existeImg']) || $dados['existeImg']=='') {
                $capa = $this->addCapa();
                if ($capa != null) {
                    $dados['capa'] = $capa;
                }
                Storage::delete('public/cast/' . auth()->user()->id .'/capa/'.$album->capa);
            }

            $album->update($dados);
            $this->addAudioPlaylist($album, $dados['lista_audio']);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e->getMessage());
            return false;
        }
    }

    private function addAudioPlaylist($playlist,$selecionados){
        if(auth()->user()->permissao == 'Z'){
            $audios = Conteudo::where('tipo',2)->whereIn('id',$selecionados)->get();
            foreach($audios as $a)
            {
                $tem = FrAudioCast::where('conteudo_id',$a->id)->where('instituicao_id',1)->first();
                if(!$tem){
                    $audio = [
                        'conteudo_id'   => $a->id,
                        'user_id'       => auth()->user()->id,
                        'instituicao_id'=> 1,
                        'escola_id'     => auth()->user()->escola_id,
                        'eh_dono'       => 1,
                    ];
                    $frAudio = new FrAudioCast($audio);
                    $frAudio->save();
                }
            }
        }

        $ordem = 1;
        $audios = [];
        if(auth()->user()->permissao == 'Z') {
            $sel = FrAudioCast::whereIn('conteudo_id', $selecionados)->where('instituicao_id', 1)->where('eh_dono', 1)->selectRaw('conteudo_id, id')->get();
        }
        else{
            $sel = FrAudioCast::whereIn('conteudo_id', $selecionados)->where('user_id', auth()->user()->id)->where('eh_dono', 1)->selectRaw('conteudo_id, id')->get();
        }

        foreach($selecionados as $s){
            foreach ($sel as $q){
                if($q->conteudo_id == $s){
                    $audio = [];
                    $audio['audio_id'] = $q->id;
                    $audio['ordem'] = $ordem;
                    $audios[] = $audio;
                    $ordem++;
                }
            }
        }
        $playlist->audios()->detach();
        $playlist->audios()->attach($audios);
    }

    public function excluirPlaylist($id){
        DB::beginTransaction();
        try
        {
            if(auth()->user()->permissao == 'Z'){
                $album = FrPlaylistAudioCast::where('instituicao_id',1)->find($id);
            }else{
                $album = FrPlaylistAudioCast::where('user_id',auth()->user()->id)->find($id);
            }
            if($album->capa != '') {
                Storage::delete('public/cast/' . auth()->user()->id . '/capa/'.$album->capa);
            }
            FrAudioPlaylistAudioCast::where('playlist_id',$id)->delete();
            $album->delete();
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e->getMessage());
            return false;
        }
    }

    public function getPlaylist($id,$lista =null){
        $cdn = config('app.cdn');
        if(auth()->user()->permissao == 'Z'){
            $sql = FrPlaylistAudioCast::where('instituicao_id',1);
        }elseif( auth()->user()->permissao != 'A') /// áudios do professor
        {
            $sql = FrPlaylistAudioCast::whereOr(function($q){
                $q->where('instituicao_id', auth()->user()->instituicao_id)
                    ->where('escola_id', auth()->user()->escola_id)
                    ->where('user_id', auth()->user()->id)
                    ->where('eh_dono',1);
            })->whereOr(function($q){
                $q->where('instituicao_id',1);
            });

        }else{
            $sql = FrPlaylistAudioCast::where('user_id',auth()->user()->id);
        }
        if($lista){
            $sql = $sql->with('lista');
        }else{
            $sql = $sql->with('audios');
        }
        $sql = $sql->selectRaw(" CONCAT('".$cdn."/storage/cast/',user_id,'/capa/',capa) as capa_album, fr_playlist_audio_cast.*")
                    ->find($id);
        return $sql;
    }

    public function getListaAudioAlbumPlayList($dados){

        if($dados['tipo'] == 1){
            return $this->getAlbum($dados['c'],true);
        }elseif($dados['tipo'] == 2){
            return $this->getPlaylist($dados['c'],true);
        }else{
            return null;
        }

    }

    public function duplicar($dados){
        if($dados['tipo'] == ''){
            return $this->duplicarAudio($dados['c']);
        }elseif($dados['tipo'] == 1){
            return $this->duplicarAlbum($dados['c']);
        }elseif($dados['tipo'] == 2){
            return $this->duplicarPlaylist($dados['c']);
        }else{
            return false;
        }
    }

    private function duplicarAudio($id){
        DB::beginTransaction();
        try
        {
            $audio = FrAudioCast::where('instituicao_id',1)
                                ->where('eh_dono',1)
                                ->where('conteudo_id',$id)
                                ->first();
            $audio = $audio->replicate();
            $audio->save();
            $dados = [
                'user_id'		=> auth()->user()->id,
                'escola_id'		=> auth()->user()->escola_id,
                'instituicao_id'=> auth()->user()->instituicao_id,
                'eh_dono'       => 0,
            ];
            $audio->update($dados);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e->getMessage());
            return false;
        }
    }

    private function duplicarAlbum($id){
        DB::beginTransaction();
        try
        {
            $album = FrAlbumAudioCast::where('instituicao_id',1)
                ->find($id)
                ->replicate();
            $album->save();

            Storage::copy('public/cast/'.$album->user_id.'/capa/'.$album->capa, 'public/cast/'.auth()->user()->id.'/capa/'.$album->id.$album->capa);

            $lista = FrAudioAlbumAudioCast::where('album_id',$id)->get();
            $listaAlbum = [];
            foreach ($lista as $l){
                $listaAlbum[] = [
                    'album_id'  => $album->id,
                    'audio_id'  => $l->audio_id,
                    'ordem'     => $l->ordem,
                ];
            }

            $dados = [
                'user_id'		=> auth()->user()->id,
                'escola_id'		=> auth()->user()->escola_id,
                'instituicao_id'=> auth()->user()->instituicao_id,
                'capa'          => $album->id.$album->capa,
            ];
            $album->update($dados);
            $album->audios()->attach($listaAlbum);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e->getMessage());
            return false;
        }
    }

    private function duplicarPlaylist($id){
        DB::beginTransaction();
        try
        {
            $playlist = FrPlaylistAudioCast::where('instituicao_id',1)
                ->find($id)
                ->replicate();
            $playlist->save();

            Storage::copy('public/cast/'.$playlist->user_id.'/capa/'.$playlist->capa, 'public/cast/'.auth()->user()->id.'/capa/'.$playlist->id.$playlist->capa);

            $lista = FrAudioPlaylistAudioCast::where('playlist_id',$id)->get();
            $listaAlbum = [];
            foreach ($lista as $l){
                $listaAlbum[] = [
                    'playlist_id'  => $playlist->id,
                    'audio_id'  => $l->audio_id,
                    'ordem'     => $l->ordem,
                ];
            }

            $dados = [
                'user_id'		=> auth()->user()->id,
                'escola_id'		=> auth()->user()->escola_id,
                'instituicao_id'=> auth()->user()->instituicao_id,
                'capa'          => $playlist->id.$playlist->capa,
            ];
            $playlist->update($dados);
            $playlist->audios()->attach($listaAlbum);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e->getMessage());
            return false;
        }
    }

    public function publicar($dados){
        DB::beginTransaction();
        try
        {
            $status = 1;
            if($dados['status']==1){
                $status = 0;
            }
            if($dados['tipo'] == ''){
                if(auth()->user()->permissao=='Z'){
                    $publicar = FrAudioCast::where('instituicao_id',1)->find($dados['c']);
                }else{
                    $publicar = FrAudioCast::where('user_id',auth()->user()->id)->find($dados['c']);
                }
            }elseif($dados['tipo'] == 1){
                if(auth()->user()->permissao=='Z'){
                    $publicar = FrAlbumAudioCast::where('instituicao_id',1)->find($dados['c']);
                }else{
                    $publicar = FrAlbumAudioCast::where('user_id',auth()->user()->id)->find($dados['c']);
                }
            }elseif($dados['tipo'] == 2){
                if(auth()->user()->permissao=='Z'){
                    $publicar = FrPlaylistAudioCast::where('instituicao_id',1)->find($dados['c']);
                }else{
                    $publicar = FrPlaylistAudioCast::where('user_id',auth()->user()->id)->find($dados['c']);
                }
            }else{
                return false;
            }
            $publicar->update(['publicado'=> $status]);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e->getMessage());
            return false;
        }
    }
}
