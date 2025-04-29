<?php
namespace App\Services\Fr;
use DB;
use Illuminate\Support\Facades\Storage;
use \Exception;

use App\Library\Slim;
use App\Models\Ciclo;
use App\Models\Disciplina;
use App\Models\FrQuiz;
use App\Models\FrQuizPerguntas;
use App\Models\FrQuizResposta;
use App\Models\FrQuizLogAtividade;
use App\Models\FrQuizPlacar;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class QuizService {

    public function listaColecao()
    {
        $retorno = Disciplina::join('fr_quiz','disciplinas.id','fr_quiz.disciplina_id')
            ->groupBy('disciplinas.id')
            ->orderBy('disciplinas.titulo')
            ->selectRaw('disciplinas.*');
        if(auth()->user()->permissao == 'A' || auth()->user()->permissao == 'R')
        {
            $retorno = $retorno->where('escola_id',auth()->user()->escola_id)
                ->where('publicado',1);
        }
        if(auth()->user()->permissao == 'P' || auth()->user()->permissao == 'C' || auth()->user()->permissao == 'I')
        {
            if(auth()->user()->permissao == 'P'){
                $retorno = $retorno->orwhere('user_id',auth()->user()->id);
            }
            else{
                $retorno = $retorno->orwhere('escola_id',auth()->user()->escola_id);
            }

            $retorno = $retorno->orwhere(function($q){
                    $q->where('instituicao_id',1)
                        ->where('publicado',1);
                });
        }
        $retorno = $retorno->get();
        return $retorno;
    }


    public function getLista($pagina = null, $pesquisa = null, $busca =null)
    {
        $quiz = FrQuiz::join('users','fr_quiz.user_id','users.id')
            ->with(['disciplina','qtdPerguntas'])
            ->orderBy('fr_quiz.titulo');


        if($busca != ''){
            $quiz = $quiz->join('ciclo_etapas','ciclo_etapas.id','fr_quiz.ciclo_etapa_id')
                ->join('disciplinas','fr_quiz.disciplina_id','disciplinas.id')
                ->join('ciclos','ciclos.id','ciclo_etapas.ciclo_id')
                ->where(function($q) use ($busca){
                    $q->orWhereRaw("MATCH(fr_quiz.titulo, fr_quiz.palavras_chave, fr_quiz.full_text)AGAINST('".$busca."' IN BOOLEAN MODE)");
                })
                ->selectRaw('fr_quiz.titulo, fr_quiz.id ,disciplinas.titulo as disciplina, ciclo_etapas.titulo as etapa, ciclos.titulo as ciclo');

        }
        else{
            $quiz->selectRaw('fr_quiz.*, users.nome_completo as usuario');
        }

        /// filtro da aba biblioteca
        if(auth()->user()->permissao == 'Z')
        {
            $quiz->where('fr_quiz.instituicao_id',1);
        }
        else
        {
            if( (isset($pesquisa['biblioteca']) && $pesquisa['biblioteca']!='') || auth()->user()->permissao== 'P' || auth()->user()->permissao== 'C' || auth()->user()->permissao== 'I') {
                if(!isset($pesquisa['biblioteca']) || $pesquisa['biblioteca']!=1)
                {
                    if(auth()->user()->permissao== 'P') {
                        $quiz->where('fr_quiz.user_id', auth()->user()->id);
                    }
                    $quiz = $quiz->where('fr_quiz.escola_id',auth()->user()->escola_id)
                        ->with(['respondido'=>function($q){
                            $q->groupBy('user_id')
                                ->groupBy('quiz_id');
                        }])
                        ->with(['finalizado'=>function($q){
                            $q->groupBy('quiz_id')
                                ->selectRaw('id, quiz_id, count(id) as qtd, avg(porcentagem_acerto) as media_acerto');
                        }]);
                }
                else
                {
                    $quiz->where('fr_quiz.instituicao_id',1)
                        ->where('fr_quiz.publicado',1);
                }
            }

        }
        /// filtro da pesquisa
        if(isset($pesquisa['id']) && $pesquisa['id']!='')
        {
            $quiz->where('fr_quiz.id',$pesquisa['id']);
        }
        if(isset($pesquisa['nome']) && $pesquisa['nome']!='')
        {
            $quiz->where(function($q) use ($pesquisa){
                $q->orWhere('fr_quiz.titulo','like','%'.$pesquisa['nome'].'%')
                    ->orWhere('users.nome_completo',$pesquisa['nome']);
            });
        }

        if(isset($pesquisa['texto']) && $pesquisa['texto']!='')
        {
            $quiz->where(function($q) use ($pesquisa){
                $q->orWhere('fr_quiz.titulo','like','%'.$pesquisa['texto'].'%');
            });
        }

        if(isset($pesquisa['componente']) && $pesquisa['componente']!='')
        {
            $quiz->where(function($q) use ($pesquisa){
                $q->where('fr_quiz.disciplina_id',$pesquisa['componente']);
            });
        }

        if(isset($pesquisa['ciclo_etapa']) && $pesquisa['ciclo_etapa']!='')
        {
            $quiz->where(function($q) use ($pesquisa){
                $q->where('fr_quiz.ciclo_etapa_id',$pesquisa['ciclo_etapa']);
            });
        }

        if( (isset($pesquisa['exibicao']) && $pesquisa['exibicao']!='') && (!isset($pesquisa['biblioteca']) || $pesquisa['biblioteca']!=1))
        {
            $quiz->where('fr_quiz.publicado',$pesquisa['exibicao']);
        }

        /// define se terá paginação
        if($pagina!= null){
            return $quiz->paginate($pagina);
        }
        else{
            return $quiz->get();
        }
    }

    public function listaAluno($pesquisa)
    {
        $pesquisa = $pesquisa->all();
        $quiz = FrQuiz::join('users','fr_quiz.user_id','users.id')
            ->with('disciplina')
            ->with(['finalizado'=>function($q){
                $q->where('user_id',auth()->user()->id);
            }])
            ->with(['respondido'=>function($q){
                $q->where('user_id',auth()->user()->id);
            }])
            ->orderBy('fr_quiz.titulo')
            ->selectRaw('fr_quiz.*, users.nome_completo as usuario')
            ->where('fr_quiz.escola_id',auth()->user()->escola_id)
            ->where('fr_quiz.publicado',1);

        /// filtro da pesquisa
        if(isset($pesquisa['texto']) && $pesquisa['texto']!='')
        {
            $quiz->where(function($q) use ($pesquisa){
                $q->orWhere('fr_quiz.titulo','like','%'.$pesquisa['texto'].'%')
                    ->orWhere('users.nome_completo','like','%'.$pesquisa['texto'].'%');
            });
        }

        if(isset($pesquisa['componente']) && $pesquisa['componente']!='')
        {
            $quiz->where(function($q) use ($pesquisa){
                $q->where('fr_quiz.disciplina_id',$pesquisa['componente']);
            });
        }

        return $quiz->paginate(21);
    }

    private function addLogo($id)
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

            Storage::disk()->put(config('app.frStorage').'quiz/'.$id.'/capa/'.$fileName, $resource);
            return $fileName;
        }
        else
        {
            return null;
        }

    }

    private function deletaLogoAtual($logo,$idQuiz)
    {
        if( Storage::disk()->exists(config('app.frStorage').'quiz/'.$idQuiz.'/capa/'.$logo) && $logo!='')
        {
            Storage::disk()->delete(config('app.frStorage').'quiz/'.$idQuiz.'/capa/'.$logo);
        }
    }

    public function inserir($request)
    {

        /// retira o campo id pois o mesmo fomulário html faz insert e update
        $dados = $request->except(['id']);

        /// adiciona os dados do usuário
        $dados['user_id']		= auth()->user()->id;
        $dados['escola_id']		= auth()->user()->escola_id;
        $dados['instituicao_id']= auth()->user()->instituicao_id;

        DB::beginTransaction();
        try
        {

            $quiz = new FrQuiz($dados);
            $quiz->save();

            $capa = $this->addLogo($quiz->id);
            $dados = [];
            if(auth()->user()->instituicao_id == 1){
                $str = Str::random(9);
                $str = $str.$quiz->id;
                $dados['public_id'] = $str;
            }
            if($capa!=null)
            {
                $dados['capa'] = $capa;
            }
            if(count($dados)>0){
                $quiz->update($dados);
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function temParticipantes($id){
        $log = FrQuizLogAtividade::where('quiz_id',$id)->first();
        $placar = FrQuizPlacar::where('quiz_id',$id)->first();
        if($log != null || $placar != null){
            return true;
        }
        else{
            return false;
        }

    }

    public function excluir($id, $total = null)
    {
        DB::beginTransaction();
        try
        {
            $quiz = $this->get($id,auth()->user()->id);
            if($total!=null){
                FrQuizLogAtividade::where('quiz_id',$quiz->id)->delete();
                FrQuizPlacar::where('quiz_id',$quiz->id)->delete();
            }

            $perguntas = FrQuizPerguntas::where('quiz_id',$quiz->id)->get();
            foreach($perguntas as $p)
            {
                FrQuizResposta::where('pergunta_id',$p->id)->delete();
            }
            FrQuizPerguntas::where('quiz_id',$quiz->id)->delete();
            $quiz->delete();
            Storage::deleteDirectory(config('app.frStorage').'quiz/'.$id);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    public function get($id, $usuario = null, $adm=null)
    {
        $retorno = FrQuiz::where('id',$id)
            ->where(function ($sql) use ($usuario, $adm) {
                if(auth()->user()->permissao != 'I') {
                    $sql->orWhere('fr_quiz.user_id', auth()->user()->id);
                }else
                {
                    $sql->orWhere('fr_quiz.escola_id', auth()->user()->escola_id);
                }
                $sql->orWhere('fr_quiz.instituicao_id', 1);
            });

        $retorno = $retorno->first();
        return $retorno;
    }

    public function getForm($id,$usuario = null, $adm=null)
    {
        return $this->get($id,$usuario, $adm);
    }

    public function editar($request)
    {
        $dados = $request->all();
        if(!isset($dados['aleatorizar_questoes']))
        {
            $dados['aleatorizar_questoes']=0;
        }

        DB::beginTransaction();
        try
        {
            $quiz = $this->get($dados['id'], auth()->user()->id);

            if($quiz->publicado == 1 && auth()->user()->instituicao_id!=1)
            {
                throw new Exception('Não é possível editar quiz publicado.');
            }

            if($dados['existeImg']=='')
            {
                $capa = '';
                if($dados['imagem'] != '')
                {
                    $capa = $this->addLogo($quiz->id);
                }
                $dados['capa'] = $capa;
                $this->deletaLogoAtual($quiz->logo,$quiz->id);
            }

            $quiz->update($dados);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }
    }

    public function publicar($quizId, $publicado)
    {
        DB::beginTransaction();
        try
        {
            $quiz = $this->get($quizId, auth()->user()->id);

            if($quiz->publicado == 1 && auth()->user()->instituicao_id!=1)
            {
                throw new Exception('Não é possível despublicar.');
            }

            if($publicado == 0)
            {
                $publicado = 1;
            }
            else
            {
                $publicado = 0;
            }
            $quiz->update(['publicado'=>$publicado]);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function duplicar($quizId)
    {

        $user = auth()->user();
        DB::beginTransaction();
        try
        {
            $quiz = $this->get($quizId,auth()->user()->id);
            $quiz = $quiz->replicate();
            $quiz->save();

            Storage::copy(config('app.frStorage').'quiz/'.$quizId.'/capa/'.$quiz->capa, config('app.frStorage').'quiz/'.$quiz->id.'/capa/'.$quiz->capa);
            $titulo = 'Cópia '.$quiz->titulo;
            if(auth()->user()->permissao == 'P' && $quiz->instituicao_id == 1)
            {
                $titulo = $quiz->titulo;
            }
            $str = Str::random(9);
            $str = $str.$quiz->id;
            $quiz->update([
                'titulo' => $titulo,
                'publicado'=>0,
                'user_id'=>$user->id,
                'escola_id'=>$user->escola_id,
                'instituicao_id'=>$user->instituicao_id,
                'public_id'=>$str,
            ]);

            $perguntas = FrQuizPerguntas::with('respostas')->where('quiz_id',$quizId)->get();
            foreach ($perguntas as $p) {
                $this->duplicarPergunta($quizId,$quiz->id,$p);
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function duplicarPergunta($quizIdOriginal,$quizIdNovo,$p)
    {
        $novaPergunta = $p->replicate();
        $novaPergunta->save();
        $dadosPergunta = ['quiz_id'=>$quizIdNovo];
        if($quizIdOriginal==$quizIdNovo)
        {
            $quiz = FrQuiz::find($quizIdOriginal);
            if($quiz->publicado == 1 && auth()->user()->instituicao_id!=1)
            {
                throw new Exception('Não é possível duplicar pergunta de quiz publicado.');
            }
            $dadosPergunta['titulo'] = 'Cópia '.$p->titulo;
        }
        $novaPergunta->update($dadosPergunta);
        if($p->imagem != '')
        {
            Storage::copy(config('app.frStorage').'quiz/'.$quizIdOriginal.'/pergunta/'.$p->id.'/'.$p->imagem, config('app.frStorage').'quiz/'.$quizIdNovo.'/pergunta/'.$novaPergunta->id.'/'.$p->imagem);
        }
        if($p->audio_titulo != '')
        {
            Storage::copy(config('app.frStorage').'quiz/'.$quizIdOriginal.'/pergunta/'.$p->id.'/'.$p->audio_titulo, config('app.frStorage').'quiz/'.$quizIdNovo.'/pergunta/'.$novaPergunta->id.'/'.$p->audio_titulo);
        }
        if($p->audio_sub_titulo != '')
        {
            Storage::copy(config('app.frStorage').'quiz/'.$quizIdOriginal.'/pergunta/'.$p->id.'/'.$p->audio_sub_titulo, config('app.frStorage').'quiz/'.$quizIdNovo.'/pergunta/'.$novaPergunta->id.'/'.$p->audio_sub_titulo);
        }
        foreach($p->respostas as $r)
        {
            $novaResposta = $r->replicate();
            $novaResposta->save();
            $novaResposta->update(['pergunta_id'=>$novaPergunta->id]);
            if($r->imagem != '')
            {
                Storage::copy(config('app.frStorage').'quiz/'.$quizIdOriginal.'/pergunta/'.$p->id.'/respostas/'.$r->imagem, config('app.frStorage').'quiz/'.$quizIdNovo.'/pergunta/'.$novaPergunta->id.'/respostas/'.$r->imagem);
            }
            if($r->audio != '')
            {
                Storage::copy(config('app.frStorage').'quiz/'.$quizIdOriginal.'/pergunta/'.$p->id.'/respostas/'.$r->audio, config('app.frStorage').'quiz/'.$quizIdNovo.'/pergunta/'.$novaPergunta->id.'/respostas/'.$r->audio);
            }
        }
    }

    public function ordemPergunta($request)
    {
        DB::beginTransaction();
        try
        {
            $quiz = $this->get($request->input('quizId'),auth()->user()->id);
            if($quiz->user_id != auth()->user()->id && auth()->user()->permissao != 'Z' || (auth()->user()->permissao == 'Z' && $quiz->instituicao_id !=1 ))
            {
                throw new Exception('sem permissão');
            }

            $ordem = $request->input('ordem');
            $i=0;
            foreach ($ordem as $v) {
                FrQuizPerguntas::where('quiz_id',$quiz->id)->where('id',$v)->update(['ordem'=>$i]);
                $i++;
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }


    public function cicloEtapa()
    {
        return Ciclo::join('ciclo_etapas','ciclos.id','ciclo_etapas.ciclo_id')
            ->orderBy('ciclos.titulo')
            ->orderBy('ciclo_etapas.titulo')
            ->selectRaw('ciclo_etapas.id, ciclos.titulo as ciclo, ciclo_etapas.titulo as ciclo_etapa')
            ->get();
    }

    /*
    /* GESTÃO DAS PERGUNTAS E RESPOSTAS
    /*
    */

    public function getPerguntas($quizId)
    {
        return FrQuizPerguntas::where('quiz_id',$quizId)->orderBy('ordem')->get();
    }

    public function inserirPerguntaTipo1($request)
    {
        /// retira o campo id pois o mesmo fomulário html faz insert e update
        $dados = $request->except(['id','imagem', 'feedback']);
        $dados['tipo'] = 1;
        DB::beginTransaction();
        try
        {
            $quiz = $this->get($request->input('quiz_id'),auth()->user()->id);
            if($quiz->publicado == 1 && auth()->user()->instituicao_id!=1)
            {
                throw new Exception('Não é possível editar quiz publicado.');
            }
            $pergunta = new FrQuizPerguntas($dados);
            $pergunta->save();

            if($request->input('tipoAudio') == 'arquivo')
            {
                $audioTitulo = $this->addAudioTituloPergunta($request->input('audio_pergunta'), $pergunta->id, $pergunta->quiz_id);
            }
            else
            {
                $audioTitulo = $this->addAudioTituloPerguntaGravado($request->input('audio_pergunta_gravado'), $pergunta->id, $pergunta->quiz_id);
            }
            if($audioTitulo!=null)
            {
                $dados = [];
                $dados['audio_titulo'] = $audioTitulo;
                $pergunta->update($dados);
            }

            $this->addRespostasTipo1($request, $pergunta->id);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function addRespostasTipo1($request, $perguntaId)
    {
        $dadosRequest = $request->all();

        for($i=0; $i<count($dadosRequest['imagem']); $i++)
        {
            $dados = [
                'pergunta_id' => $perguntaId,
            ];
            $dados['titulo'] = $dadosRequest['titulo_alternativa'][$i];
            $dados['feedback'] = $dadosRequest['feedback'][$i];
            $dados['correta'] = 0;
            if($i == $dadosRequest['correta'])
            {
                $dados['correta'] = 1;
            }

            $resposta = new FrQuizResposta($dados);
            $resposta->save();
            $img   = $this->addImagemResposta($request->quiz_id, $perguntaId, $i);
            $audio = null;
            if($dadosRequest['tipoAudioAlternativa'.$i]=='arquivo' && isset($dadosRequest['audio_alternativa']) && isset($dadosRequest['audio_alternativa'][$i]))
            {
                $audio = $this->addAudioResposta($request->quiz_id, $perguntaId, $dadosRequest['audio_alternativa'][$i]);
            }
            elseif($dadosRequest['tipoAudioAlternativa'.$i]=='gravado' && isset($dadosRequest['audio_alternativa_gravado']) && isset($dadosRequest['audio_alternativa_gravado'][$i]))
            {
                $audio = $this->addAudioRespostaGravado($request->quiz_id, $perguntaId, $dadosRequest['audio_alternativa_gravado'][$i]);
            }

            $dadosResposta = ['imagem'=>$img, 'audio'=>$audio];
            $resposta->update($dadosResposta);

        }
    }

    private function addAudioResposta($idQuiz, $idPergunta, $audio)
    {
        if($audio)
        {
            $audio =  $audio->store(
                config('app.frStorage').'quiz/'.$idQuiz.'/pergunta/'.$idPergunta.'/respostas'
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

    private function addAudioRespostaGravado($idQuiz, $idPergunta, $audio)
    {
        if($audio)
        {
            $gravado = explode('/', $audio);
            $gravado = $gravado[count($gravado)-1];

            Storage::move($audio, config('app.frStorage').'quiz/'.$idQuiz.'/pergunta/'.$idPergunta.'/respostas/'.$gravado);

            return $gravado;
        }
        else
        {
            return null;
        }

    }

    private function addImagemPergunta($idQuiz, $idPergunta, $campo, $posicao=0)
    {
        $images = Slim::getImages($campo);
        if(count($images)>0)
        {
            $image = $images[$posicao];
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

            Storage::disk()->put(config('app.frStorage').'quiz/'.$idQuiz.'/pergunta/'.$idPergunta.'/'.$fileName, $resource);
            return $fileName;
        }
        else
        {
            return null;
        }
    }

    private function addImagemResposta($idQuiz, $idPergunta, $posicao=0)
    {
        $images = Slim::getImages('imagem');
        if(count($images)>0)
        {
            $image = $images[$posicao];
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

            Storage::disk()->put(config('app.frStorage').'quiz/'.$idQuiz.'/pergunta/'.$idPergunta.'/respostas/'.$fileName, $resource);
            return $fileName;
        }
        else
        {
            return null;
        }
    }

    private function addAudioTituloPergunta($audio,$perguntaId,$quizId)
    {
        if($audio)
        {
            $audio = $audio->store(
                config('app.frStorage').'quiz/'.$quizId.'/pergunta/'.$perguntaId
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

    private function addAudioTituloPerguntaGravado($audio, $perguntaId,$quizId)
    {
        if($audio)
        {
            $gravado = explode('/', $audio);
            $gravado = $gravado[count($gravado)-1];

            Storage::move($audio, config('app.frStorage').'quiz/'.$quizId.'/pergunta/'.$perguntaId.'/'.$gravado);

            return $gravado;
        }
        else
        {
            return null;
        }

    }

    public function excluirPergunta($id)
    {
        DB::beginTransaction();
        try
        {
            $pergunta = FrQuizPerguntas::find($id);
            $quiz = $this->get($pergunta->quiz_id, auth()->user()->id);
            if($quiz->publicado == 1 && auth()->user()->instituicao_id!=1)
            {
                throw new Exception('Não é possível excluir pergunta de quiz publicado.');
            }
            if($quiz->id > 0)
            {
                FrQuizResposta::where('pergunta_id',$id)->delete();
                $pergunta->delete();
                Storage::deleteDirectory(config('app.frStorage').'quiz/'.$quiz->id.'/pergunta/'.$id);
            }
            else
            {
                return false;
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function getPerguntaTipo1Form($id)
    {
        try
        {
            $pergunta = FrQuizPerguntas::with('respostas')->where('tipo',1)->find($id);
            $quiz = $this->get($pergunta->quiz_id,auth()->user()->id);
            if($quiz->id>0)
            {
                return $pergunta;
            }
            else
            {
                return false;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public function editarPerguntaTipo1($request)
    {
        $dados = $request->except(['imagem', 'feedback']);
        if(!isset($dados['aleatorizar_respostas'])){
            $dados['aleatorizar_respostas'] =0;
        }
        DB::beginTransaction();
        try
        {
            $pergunta = FrQuizPerguntas::with('respostas')->where('tipo',1)->find($dados['id']);
            $quiz = $this->get($pergunta->quiz_id, auth()->user()->id);
            if($quiz->publicado == 1 && auth()->user()->instituicao_id!=1)
            {
                throw new Exception('Não é possível editar quiz publicado.');
            }
            if($quiz->id>0)
            {

                if($request->input('tipoAudio') == 'arquivo')
                {
                    $audioTitulo = $this->addAudioTituloPergunta($request->file('audio_pergunta'), $pergunta->id, $pergunta->quiz_id);
                }
                else
                {
                    $audioTitulo = $this->addAudioTituloPerguntaGravado($request->input('audio_pergunta_gravado'), $pergunta->id, $pergunta->quiz_id);
                }

                if($audioTitulo!=null)
                {
                    $dados['audio_titulo'] = $audioTitulo;
                }
                else{
                    $dados['audio_titulo'] = $dados['existeAudioPergunta'];
                }
                $pergunta->update($dados);
                $this->deletaRespostasAusentes($request->input('idResposta'), $pergunta->id);
                $this->editarRespostasTipo1($request, $pergunta->id);
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function deletaRespostasAusentes($respostasId, $perguntaId)
    {
        FrQuizResposta::whereNotIn('id',$respostasId)->where('pergunta_id',$perguntaId)->delete();
    }

    private function editarRespostasTipo1($request, $perguntaId)
    {

        $dadosRequest = $request->all();
        $respostaParaEditar = [];
        $temImagem = 0;
        for($i=0; $i<count($dadosRequest['imagem']); $i++)
        {
            $dados = [
                'pergunta_id' => $perguntaId,
            ];
            $dados['titulo'] = $dadosRequest['titulo_alternativa'][$i];
            $dados['feedback'] = $dadosRequest['feedback'][$i];
            $dados['correta'] = 0;
            if($i == $dadosRequest['correta'])
            {
                $dados['correta'] = 1;
            }

            if(isset($dadosRequest['idResposta'][$i]) && $dadosRequest['idResposta'][$i]!='')
            {
                $resposta = FrQuizResposta::where('pergunta_id',$perguntaId)->find($dadosRequest['idResposta'][$i]);
                $resposta->update($dados);
            }
            else
            {
                $resposta = new FrQuizResposta($dados);
                $resposta->save();
            }

            $audio = null;
            $img = null;
            if($dadosRequest['existeImg'][$i] == '')
            {
                $img = $this->addImagemResposta($request->quiz_id, $perguntaId, $temImagem);
                $temImagem++;
            }
            else
            {
                $img = $dadosRequest['existeImg'][$i];
            }

            if($dadosRequest['tipoAudioAlternativa'.$i]=='arquivo' && isset($dadosRequest['audio_alternativa']) && isset($dadosRequest['audio_alternativa'][$i]))
            {
                $audio = $this->addAudioResposta($request->quiz_id, $perguntaId, $dadosRequest['audio_alternativa'][$i]);
            }
            elseif($dadosRequest['tipoAudioAlternativa'.$i]=='gravado' && isset($dadosRequest['audio_alternativa_gravado']) && isset($dadosRequest['audio_alternativa_gravado'][$i]))
            {
                $audio = $this->addAudioRespostaGravado($request->quiz_id, $perguntaId, $dadosRequest['audio_alternativa_gravado'][$i]);
            }
            else{
                $audio = $dadosRequest['existe_audio_alternativa'][$i];
            }

            $dadosResposta = ['imagem'=>$img, 'audio'=>$audio];
            $resposta->update($dadosResposta);

        }
    }

    public function copyPergunta($perguntaId)
    {
        DB::beginTransaction();
        try
        {
            $pergunta = FrQuizPerguntas::find($perguntaId);
            $quiz = $this->get($pergunta->quiz_id);
            $this->duplicarPergunta($pergunta->quiz_id,$pergunta->quiz_id,$pergunta);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }


    public function inserirPerguntaTipo2($request)
    {
        /// retira o campo id pois o mesmo fomulário html faz insert e update
        $dados = $request->except(['id']);
        $dadosGravar = [
            'tipo' 					=> 2,
            'titulo' 				=> $request->input('titulo_tipo2'),
            'sub_titulo' 			=> $request->input('sub_titulo_tipo2'),
            'aleatorizar_respostas' => $request->input('aleatorizar_respostas_tipo2'),
            'quiz_id' 				=> $request->input('quiz_id'),
            'feedback' 				=> $request->input('feedback_tipo2'),
            'feedback_correta' 		=> $request->input('feedback_tipo2_correta'),
        ];

        DB::beginTransaction();
        try
        {
            $quiz = $this->get($request->input('quiz_id'),auth()->user()->id);
            if($quiz->publicado == 1 && auth()->user()->instituicao_id!=1)
            {
                throw new Exception('Não é possível editar quiz publicado.');
            }

            $pergunta = new FrQuizPerguntas($dadosGravar);
            $pergunta->save();

            if($request->input('tipoAudio') == 'arquivo')
            {
                $audioTitulo = $this->addAudioTituloPergunta($request->file('audio_titulo_tipo2'), $pergunta->id, $pergunta->quiz_id);
            }
            else
            {
                $audioTitulo = $this->addAudioTituloPerguntaGravado($request->input('audio_pergunta_gravado'), $pergunta->id, $pergunta->quiz_id);
            }

            $img = $this->addImagemPergunta($dados['quiz_id'], $pergunta->id, 'imagem_tipo2', $posicao=0);

            if($audioTitulo!=null || $img!=null)
            {
                $dadosObj = [
                    'audio_titulo' 	=> $audioTitulo,
                    'imagem'		=> $img,
                ];
                $pergunta->update($dadosObj);
            }

            $this->addRespostasTipo2($dados['resposta_tipo2'], $dados['frase_correta'], $pergunta->id);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function addRespostasTipo2($palavras, $corretas, $perguntaId)
    {
        $i=0;
        $v= [];
        foreach($palavras as $f)
        {
            $correta = array_search($f,$corretas);
            $v[] = ($correta);
            $dados = [
                'pergunta_id' 	=> $perguntaId,
                'titulo' 		=> $f,
                'ordem' 		=> $i,
            ];

            if($correta!==false)
            {
                $dados['ordem_correta']	= $correta;
            }
            $resposta = new FrQuizResposta($dados);
            $resposta->save();
            $i++;
        }

    }

    public function getPerguntaTipo2Form($id)
    {
        try
        {
            $pergunta = FrQuizPerguntas::with('respostas')->where('tipo',2)->find($id);
            $quiz = $this->get($pergunta->quiz_id,auth()->user()->id);
            if($quiz->id>0)
            {
                $correta = [];

                foreach($pergunta->respostas as $r) {
                    if ($r->ordem_correta !== null) {
                        $correta[(int)$r->ordem_correta] = $r->titulo;
                    }


                }
                ksort($correta);
                $pergunta->corretas = $correta;
                return $pergunta;
            }
            else
            {
                return false;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public function editarPerguntaTipo2($request)
    {
        /// retira o campo id pois o mesmo fomulário html faz insert e update

        $dadosGravar = [
            'tipo' 					=> 2,
            'titulo' 				=> $request->input('titulo_tipo2'),
            'sub_titulo' 			=> $request->input('sub_titulo_tipo2'),
            'aleatorizar_respostas' => $request->input('aleatorizar_respostas_tipo2'),
            'quiz_id' 				=> $request->input('quiz_id'),
            'feedback' 				=> $request->input('feedback_tipo2'),
            'feedback_correta' 		=> $request->input('feedback_tipo2_correta'),
            'id'					=> $request->input('id'),
        ];
        DB::beginTransaction();
        try
        {
            $pergunta = FrQuizPerguntas::where('tipo',2)->find($dadosGravar['id']);
            $quiz = $this->get($pergunta->quiz_id,auth()->user()->id);
            if($quiz->publicado == 1 && auth()->user()->instituicao_id!=1)
            {
                throw new Exception('Não é possível editar quiz publicado.');
            }
            if($quiz->id > 0)
            {
                if($request->input('existeAudioTituloTipo2') == '')
                {
                    if($request->input('tipoAudio') == 'arquivo')
                    {
                        $audioTitulo = $this->addAudioTituloPergunta($request->file('audio_titulo_tipo2'), $pergunta->id, $pergunta->quiz_id);
                    }
                    else
                    {
                        $audioTitulo = $this->addAudioTituloPerguntaGravado($request->input('audio_pergunta_gravado'), $pergunta->id, $pergunta->quiz_id);
                    }

                    $dadosGravar['audio_titulo'] = $audioTitulo;
                }

                if($request->input('existeImgTipo2') == '')
                {
                    $img = $this->addImagemPergunta($pergunta->quiz_id, $pergunta->id, 'imagem_tipo2', $posicao=0);
                    $dadosGravar['imagem'] = $img;
                }
                $pergunta->update($dadosGravar);

                FrQuizResposta::where('pergunta_id',$pergunta->id)->delete();
                $this->addRespostasTipo2($request->input('resposta_tipo2'), $request->input('frase_correta'),  $pergunta->id);

                DB::commit();
                return true;
            }
            else
            {
                return false;
            }
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }



    public function inserirPerguntaTipo3($request)
    {

        /// retira o campo id pois o mesmo fomulário html faz insert e update
        $dados = $request->except(['id']);

        $dadosGravar = [
            'tipo' 					=> 3,
            'titulo' 				=> $request->input('titulo_tipo3'),
            'sub_titulo' 			=> $request->input('sub_titulo_tipo3'),
            'aleatorizar_respostas' => $request->input('aleatorizar_respostas_tipo3'),
            'quiz_id' 				=> $request->input('quiz_id'),
            'feedback' 				=> $request->input('feedback_tipo3'),
            'feedback_correta' 		=> $request->input('feedback_tipo3_correta'),
        ];
        DB::beginTransaction();
        try
        {
            $quiz = $this->get($request->input('quiz_id'),auth()->user()->id);
            if($quiz->publicado == 1 && auth()->user()->instituicao_id!=1)
            {
                throw new Exception('Não é possível editar quiz publicado.');
            }

            $pergunta = new FrQuizPerguntas($dadosGravar);
            $pergunta->save();

            if($request->input('tipoAudio') == 'arquivo')
            {
                $audioTitulo = $this->addAudioTituloPergunta($request->file('audio_titulo_tipo3'), $pergunta->id, $pergunta->quiz_id);
            }
            else
            {
                $audioTitulo = $this->addAudioTituloPerguntaGravado($request->input('audio_pergunta_gravado'), $pergunta->id, $pergunta->quiz_id);
            }

            $img = $this->addImagemPergunta($dados['quiz_id'], $pergunta->id, 'imagem_tipo3', $posicao=0);

            if($audioTitulo!=null || $img!=null)
            {
                $dadosObj = [
                    'audio_titulo' 	=> $audioTitulo,
                    'imagem'		=> $img,
                ];
                $pergunta->update($dadosObj);
            }

            $this->addRespostasTipo3($dados['resposta_tipo3'], $pergunta->id);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    private function addRespostasTipo3($respostas, $perguntaId)
    {
        $i=0;
        foreach($respostas as $f)
        {
            $dados = [
                'pergunta_id' 	=> $perguntaId,
                'titulo' 		=> $f,
                'ordem' 		=> $i,
                'correta'		=> 1,
            ];
            $resposta = new FrQuizResposta($dados);
            $resposta->save();
            $i++;
        }
    }

    public function getPerguntaTipo3Form($id)
    {
        try
        {
            $pergunta = FrQuizPerguntas::with('respostas')->where('tipo',3)->find($id);
            $quiz = $this->get($pergunta->quiz_id,auth()->user()->id);
            if($quiz->id>0)
            {
                return $pergunta;
            }
            else
            {
                return false;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public function editarPerguntaTipo3($request)
    {
        /// retira o campo id pois o mesmo fomulário html faz insert e update

        $dadosGravar = [
            'tipo' 					=> 3,
            'titulo' 				=> $request->input('titulo_tipo3'),
            'sub_titulo' 			=> $request->input('sub_titulo_tipo3'),
            'aleatorizar_respostas' => $request->input('aleatorizar_respostas_tipo3'),
            'quiz_id' 				=> $request->input('quiz_id'),
            'feedback' 				=> $request->input('feedback_tipo3'),
            'feedback_correta' 		=> $request->input('feedback_tipo3_correta'),

            'id'					=> $request->input('id'),
        ];
        DB::beginTransaction();
        try
        {
            $pergunta = FrQuizPerguntas::where('tipo',3)->find($dadosGravar['id']);
            $quiz = $this->get($pergunta->quiz_id,auth()->user()->id);
            if($quiz->publicado == 1 && auth()->user()->instituicao_id!=1)
            {
                throw new Exception('Não é possível editar quiz publicado.');
            }
            if($quiz->id > 0)
            {
                if($request->input('existeAudioTituloTipo3') == '')
                {
                    if($request->input('tipoAudio') == 'arquivo')
                    {
                        $audioTitulo = $this->addAudioTituloPergunta($request->file('audio_titulo_tipo3'), $pergunta->id, $pergunta->quiz_id);
                    }
                    else
                    {
                        $audioTitulo = $this->addAudioTituloPerguntaGravado($request->input('audio_pergunta_gravado'), $pergunta->id, $pergunta->quiz_id);
                    }

                    $dadosGravar['audio_titulo'] = $audioTitulo;
                }

                if($request->input('existeImgTipo3') == '')
                {
                    $img = $this->addImagemPergunta($pergunta->quiz_id, $pergunta->id, 'imagem_tipo3', $posicao=0);
                    $dadosGravar['imagem'] = $img;
                }
                $pergunta->update($dadosGravar);

                FrQuizResposta::where('pergunta_id',$pergunta->id)->delete();
                $this->addRespostasTipo3($request->input('resposta_tipo3'), $pergunta->id);

                DB::commit();
                return true;
            }
            else
            {
                return false;
            }
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function inserirPerguntaTipo4($request)
    {
        /// retira o campo id pois o mesmo fomulário html faz insert e update
        $dados = $request->except(['id']);
        $dadosGravar = [
            'tipo' 					=> 4,
            'titulo' 				=> $request->input('titulo_tipo4'),
            'sub_titulo' 			=> $request->input('sub_titulo_tipo4'),
            'aleatorizar_respostas' => $request->input('aleatorizar_respostas_tipo4'),
            'quiz_id' 				=> $request->input('quiz_id'),
            'feedback' 				=> $request->input('feedback_tipo4'),
            'feedback_correta' 		=> $request->input('feedback_tipo4_correta'),

        ];
        DB::beginTransaction();
        try
        {
            $quiz = $this->get($request->input('quiz_id'),auth()->user()->id);
            if($quiz->publicado == 1 && auth()->user()->instituicao_id!=1)
            {
                throw new Exception('Não é possível editar quiz publicado.');
            }

            $pergunta = new FrQuizPerguntas($dadosGravar);
            $pergunta->save();

            $this->addRespostasTipo4($dados['resposta_tipo4'], $pergunta->id, $request->input('corretaTipo4'), $request->input('qtda_alternativa'));

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();

            return false;
        }
    }

    private function addRespostasTipo4($respostas, $perguntaId, $correta, $qtd)
    {
        $i=0;
        foreach($respostas as $f)
        {
            if($i<$qtd){
                $ehCorreta = 0;
                if($i+1 == $correta)
                {
                    $ehCorreta = 1;
                }

                $dados = [
                    'pergunta_id' 	=> $perguntaId,
                    'titulo' 		=> $f,
                    'ordem' 		=> $i,
                    'correta'		=> $ehCorreta,
                ];
                $resposta = new FrQuizResposta($dados);
                $resposta->save();
            }
            $i++;
        }
    }

    public function getPerguntaTipo4Form($id)
    {
        try
        {
            $pergunta = FrQuizPerguntas::with('respostas')->where('tipo',4)->find($id);
            $quiz = $this->get($pergunta->quiz_id,auth()->user()->id);
            if($quiz->id>0)
            {
                $dados = [
                    'id' 				=> $pergunta->id,
                    'titulo_tipo4' 		=> $pergunta->titulo,
                    'sub_titulo_tipo4' 	=> $pergunta->sub_titulo,
                    'aleatorizar_respostas_tipo4' => $pergunta->aleatorizar_respostas,
                    'quiz_id' 			=> $pergunta->quiz_id,
                    'tipo' 				=>$pergunta->tipo,
                    'feedback_tipo4'	=>$pergunta->feedback,
                    'feedback_tipo4_correta'	=>$pergunta->feedback_correta,

                    'respostas'			=> $pergunta->respostas,
                ];

                return $dados;
            }
            else
            {
                return false;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public function editarPerguntaTipo4($request)
    {
        /// retira o campo id pois o mesmo fomulário html faz insert e update

        $dadosGravar = [
            'tipo' 					=> 4,
            'titulo' 				=> $request->input('titulo_tipo4'),
            'sub_titulo' 			=> $request->input('sub_titulo_tipo4'),
            'aleatorizar_respostas' => $request->input('aleatorizar_respostas_tipo4'),
            'quiz_id' 				=> $request->input('quiz_id'),
            'feedback' 				=> $request->input('feedback_tipo4'),
            'feedback_correta' 		=> $request->input('feedback_tipo4_correta'),

            'id'					=> $request->input('id'),
        ];
        DB::beginTransaction();
        try
        {
            $pergunta = FrQuizPerguntas::where('tipo',4)->find($dadosGravar['id']);
            $quiz = $this->get($pergunta->quiz_id,auth()->user()->id);
            if($quiz->publicado == 1 && auth()->user()->instituicao_id!=1)
            {
                throw new Exception('Não é possível editar quiz publicado.');
            }
            if($quiz->id > 0)
            {
                $pergunta->update($dadosGravar);

                FrQuizResposta::where('pergunta_id',$pergunta->id)->delete();
                $this->addRespostasTipo4($request->input('resposta_tipo4'), $pergunta->id, $request->input('corretaTipo4'), $request->input('qtda_alternativa'));

                DB::commit();
                return true;
            }
            else
            {
                return false;
            }
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }


    /*
    /*
    /*   Exibir quiz
    /*
    */

    public function exibir($request)
    {
        $placar = FrQuizPlacar::where('quiz_id',$request->input('q'))->where('user_id',auth()->user()->id)->first();
        if($placar)
        {
            $quiz = json_decode($placar->quiz_respondido);
            $quiz->respondido = 1;
            return $quiz;
        }
        else
        {
            return $this->getExibir($request->input('q'));
        }

    }

    public function getExibir($quizId, $publico= false)
    {
        if($publico){
            $quiz = FrQuiz::where('public_id',$quizId)->first();
        }else{
            if(auth()->user()->permissao == 'A' || auth()->user()->permissao == 'R'){
                $quiz = FrQuiz::where('escola_id',auth()->user()->escola_id)->find($quizId);

            }
            else{
                $quiz = FrQuiz::where(function($query){
                    $query->orWhere(function($q){
                        $q->where('escola_id',auth()->user()->escola_id)
                            ->where('user_id',auth()->user()->id);
                    })
                        ->orWhere('instituicao_id',1);
                })
                    ->find($quizId);
            }
        }

        if($quiz) {

            $perguntas = FrQuizPerguntas::with('log')->where('quiz_id',$quiz->id);

            if ($quiz->aleatorizar_questoes) {
                $perguntas = $perguntas->inRandomOrder();
            } else {
                $perguntas = $perguntas->orderBy('ordem')->orderBy('id');
            }
            $perguntas = $perguntas->get();

            foreach ($perguntas as $p) {
                $respostas = FrQuizResposta::where('pergunta_id', $p->id);
                if ($p->aleatorizar_respostas) {
                    $respostas = $respostas->inRandomOrder();
                } else {
                    $respostas = $respostas->orderBy('ordem')->orderBy('id');
                }
                $p->respostas = $respostas->get();
            }
            $quiz->perguntas = $perguntas;

            return $quiz;
        }else{
            return false;
        }

    }

    public function verificaResposta($request)
    {
        $dados = $request->all();

        if($dados['envio']['tipo'] == 1)
        {
            return $this->verificaRespostaTipo1($dados);
        }
        elseif($dados['envio']['tipo'] == 2)
        {
            return $this->verificaRespostaTipo2($dados);
        }
        elseif($dados['envio']['tipo'] == 3)
        {
            return $this->verificaRespostaTipo3($dados);
        }
        elseif($dados['envio']['tipo'] == 4)
        {
            return $this->verificaRespostaTipo1($dados);
        }
        else
        {
            return false;
        }
    }

    private function logQuiz($dados)
    {
        if(auth()->user()->permissao != 'I') {
            $logTem = FrQuizLogAtividade::where('user_id', $dados['user_id'])->where('quiz_id', $dados['quiz_id'])->where('pergunta_id', $dados['pergunta_id'])->first();
            if (isset($logTem->id)) {
                $dados['tentativa'] = DB::raw('tentativa + 1');
                $dados['tempo'] = DB::raw('tempo + ' . $dados['tempo']);
                $logTem->update($dados);
            } else {
                $log = new FrQuizLogAtividade($dados);
                $log->save();
            }
        }
    }

    private function verificaRespostaTipo1($dadosOriginal)
    {
        $dados = $dadosOriginal['envio'];
        $correta = FrQuizResposta::where('pergunta_id',$dados['pergunta'])->where('id',$dados['correta'])->first();

        $dadosLog =[
            'user_id' 	=> auth()->user()->id,
            'quiz_id' 	=> $dadosOriginal['quiz_id'],
            'pergunta_id' => $dados['pergunta'],
            'resposta' 	=> $dados['correta'],
            'feedback' 	=> $correta->feedback,
            'tempo'		=> $dadosOriginal['tempo'],
        ];

        if($correta->correta)
        {
            $dadosLog['eh_correta'] = 1;
            $this->logQuiz($dadosLog);
            if($dadosOriginal['envio']['tipo'] == 1){
                return ['correta' => 1, 'feedback_correta' => $correta->feedback];
            }
            else
            {
                $pergunta = FrQuizPerguntas::find($dados['pergunta']);
                return ['correta' => 1, 'feedback_correta' => $pergunta->feedback_correta];
            }
        }
        else
        {

            $dadosLog['eh_correta'] = 0;
            $this->logQuiz($dadosLog);
            if($dadosOriginal['envio']['tipo'] == 1){
                return ['correta' => 0, 'feedback' => $correta->feedback];
            }
            else
            {
                $pergunta = FrQuizPerguntas::find($dados['pergunta']);
                return ['correta' => 0, 'feedback' => $pergunta->feedback];
            }
        }
    }

    private function verificaRespostaTipo2($dadosOriginal)
    {
        $dados = $dadosOriginal['envio'];

        $pergunta = FrQuizPerguntas::find($dados['pergunta']);
        $vetCorreta = [];
        $resposta = implode('',$dados['correta']);
        $correta = FrQuizResposta::where('pergunta_id',$dados['pergunta'])->whereNotNull('ordem_correta')->orderBy('ordem_correta')->orderBy('id')->get();

        foreach ($correta as $c) {
            $vetCorreta[] = $c->id;
        }

        $dadosLog =[
            'user_id' 	=> auth()->user()->id,
            'quiz_id' 	=> $dadosOriginal['quiz_id'],
            'pergunta_id' => $dados['pergunta'],
            'resposta' 	=> json_encode($dados['correta']),
            'tempo'		=> $dadosOriginal['tempo'],
        ];

        $correta = implode('', $vetCorreta);

        if($correta == $resposta)
        {
            $dadosLog['eh_correta'] = 1;
            $dadosLog['feedback'] = $pergunta->feedback_correta;
            $this->logQuiz($dadosLog);
            return ['correta' => 1, 'feedback_correta' => $pergunta->feedback_correta];
        }
        else
        {
            $dadosLog['eh_correta'] = 0;
            $dadosLog['feedback'] = $pergunta->feedback;
            $this->logQuiz($dadosLog);

            return ['correta' => 0, 'feedback' => $pergunta->feedback];
        }
    }

    private function verificaRespostaTipo3($dadosOriginal)
    {
        $dados = $dadosOriginal['envio'];

        $pergunta = FrQuizPerguntas::find($dados['pergunta']);
        $correta = FrQuizResposta::where('pergunta_id',$dados['pergunta'])->where('titulo',trim($dados['correta']))->first();

        $dadosLog =[
            'user_id' 	=> auth()->user()->id,
            'quiz_id' 	=> $dadosOriginal['quiz_id'],
            'pergunta_id' => $dados['pergunta'],
            'resposta' 	=> trim($dados['correta']),
            'tempo'		=> $dadosOriginal['tempo'],
        ];
        //if(isset($correta->id) && mb_strtolower($correta->titulo) == mb_strtolower(trim($dados['correta'])) )
        if(isset($correta->id))
        {
            $dadosLog['eh_correta'] = 1;
            $dadosLog['feedback'] = $pergunta->feedback_correta;
            $this->logQuiz($dadosLog);
            return ['correta' => 1, 'feedback_correta' => $pergunta->feedback_correta];
        }
        else
        {
            $dadosLog['eh_correta'] = 0;
            $dadosLog['feedback'] = $pergunta->feedback;
            $this->logQuiz($dadosLog);

            return ['correta' => 0, 'feedback' => $pergunta->feedback];
        }
    }

    public function finalizado($quizId)
    {
        $placar = FrQuizPlacar::where('quiz_id',$quizId)
            ->where('user_id',auth()->user()->id)
            ->first();
        if($placar)
        {
            return $placar;
        }
        else
        {
            return $this->finalizarQuiz($quizId);
        }
    }

    private function finalizarQuiz($quizId)
    {
        $log = FrQuizLogAtividade::where('quiz_id',$quizId)->where('user_id',auth()->user()->id)->get();

        if(count($log)>0)
        {
            $certo = 0;
            $erro = 0;
            $tempo = 0;
            foreach ($log as $l) {
                if($l->eh_correta == 1)
                {
                    $certo++;
                }
                else
                {
                    $erro++;
                }
                $tempo += (int)$l->tempo;
            }

            $quiz = $this->getExibir($quizId);
            $dadosPlacar = [
                'quiz_id' => $quizId,
                'user_id' => auth()->user()->id,
                'qtd_acerto' => $certo,
                'qtd_erro' => $erro,
                'tempo' =>  gmdate("H:i:s", $tempo),
                'qtd_questoes_total' => count($quiz->perguntas),
                'qtd_questoes_respondida' => $certo+$erro,
                'porcentagem_acerto' => (int)(($certo*100)/($certo+$erro)),
                'porcentagem_erro' => 100 - ((int) (($certo*100)/($certo+$erro))),
                'pontuacao' => (int)($quiz->pontuacao/count($quiz->perguntas)) * $certo,
                'quiz_respondido' => $quiz->toJson(JSON_PRETTY_PRINT),

            ];

            DB::beginTransaction();
            try
            {
                $placar = new FrQuizPlacar($dadosPlacar);
                $placar->save();
                FrQuizLogAtividade::where('quiz_id',$quizId)->where('user_id',auth()->user()->id)->delete();
                DB::commit();
                return FrQuizPlacar::where('quiz_id',$quizId)->where('user_id',auth()->user()->id)->first();
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return false;
            }
        }
        else
        {
            return false;
        }

    }

    public function totalizadorPlacar($quizId)
    {
        return FrQuizPlacar::where('quiz_id',$quizId)
            ->where('user_id',auth()->user()->id)
            ->selectRaw('sum(pontuacao) as pontuacao')
            ->first();
    }

    public function limparPlacar($quizId)
    {
        if(auth()->user()->permissao=='A')
        {
            return false;
        }

        DB::beginTransaction();
        try
        {
            FrQuizPlacar::where('user_id',auth()->user()->id)->where('quiz_id',$quizId)->delete();
            FrQuizLogAtividade::where('user_id',auth()->user()->id)->where('quiz_id',$quizId)->delete();
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }


    }

}
