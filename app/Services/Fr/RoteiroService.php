<?php
namespace App\Services\Fr;
use App\Library\Slim;
use App\Models\Altitude\Aula;
use App\Models\Altitude\CursosInstituicaoEscolaBiblioteca;
use App\Models\ConteudoAula;
use App\Models\TrilhasCurso;
use DB;

use App\Models\Ciclo;
use App\Models\Altitude\Curso;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class RoteiroService {

    private function restricaoAcesso($roteiro, $pesquisa){
        if(isset($pesquisa['biblioteca']) && $pesquisa['biblioteca'] == 1 && (auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')){
            $roteiro->where('instituicao_id',1)
                    ->where('publicado',1)
                    ->where('permite_biblioteca',1)
                    ->where(function($q){
                        //// TOTAS AS INSTITUICOES
                        $q->orWhere('tipo_permissao_biblioteca',1);
                        $instituicao = session('instituicao');

                        if($instituicao['tipo'] == 1){
                            //// TODAS AS PRIVADAS
                            $q->orWhere('tipo_permissao_biblioteca',3);
                        }elseif ($instituicao['tipo'] == 2){
                            //// TODAS AS PUBLICAS
                            $q->orWhere('tipo_permissao_biblioteca',2);
                        }

                        $q->orWhereHas('permissaoBiblioteca',function($query){
                            $query->orWhere(function($qq){
                                $qq->where('instituicao_id', auth()->user()->instituicao_id)
                                    ->where('escola_id',0);
                            });
                            $query->orWhere(function($qq){
                                $qq->where('instituicao_id', auth()->user()->instituicao_id)
                                    ->where('escola_id', auth()->user()->escola_id);
                            });
                        });
                    });
        }else{
            if(auth()->user()->permissao == 'Z') {
                $roteiro->where('instituicao_id', 1);
            }else{
                $roteiro->where('instituicao_id', auth()->user()->instituicao_id)
                ->where('escola_id', auth()->user()->escola_id)
                ->where('user_id', auth()->user()->id);
            }
        }
        return $roteiro;
    }

    public function getLista($pagina, $pesquisa)
    {
        $curso = Curso::where('novo',1);

        $curso = $this->restricaoAcesso($curso, $pesquisa);

        /// filtro da pesquisa
        if(isset($pesquisa['texto']) && $pesquisa['texto']!='')
        {
            $curso->where(function($q) use ($pesquisa){
                $q->orWhere('cursos.titulo','like','%'.$pesquisa['texto'].'%')
                    ->orWhere('cursos.tag','like','%'.$pesquisa['texto'].'%');
            });
        }
        if(isset($pesquisa['componente']) && $pesquisa['componente']!='')
        {
            $curso->where(function($q) use ($pesquisa){
                $q->orWhere('cursos.disciplina_id',$pesquisa['componente']);
            });
        }
        if(isset($pesquisa['ciclo_etapa']) && $pesquisa['ciclo_etapa']!='')
        {
            $curso->where(function($q) use ($pesquisa){
                $q->orWhere('cursos.cicloetapa_id',$pesquisa['ciclo_etapa']);
            });
        }
        if(isset($pesquisa['publicado']) && $pesquisa['publicado'] != '') {
            $curso = $curso->where('cursos.publicado',$pesquisa['publicado']);
        }
        /*
        if(isset($pesquisa['exibicao']) && $pesquisa['exibicao'] != '' &&  $pesquisa['biblioteca']!=1) {
            if ($pesquisa['exibicao'] == 1) {
                $curso = $curso->where('cursos.publicado',1);
            }
            else{
                $curso->where('cursos.publicado',0);
            }
        }
        */
        if(isset($pesquisa['id']) && is_array($pesquisa['id']))
        {
            return $curso->whereIn('id', $pesquisa['id'])
                            ->orderByRaw('FIND_IN_SET(Id, ?)', [ implode(',',$pesquisa['id'])])
                            ->get();
        }
        if(isset($pesquisa['notId']) && is_array($pesquisa['notId']))
        {
             $curso->whereNotIn('id', $pesquisa['notId']);
        }
        $curso = $curso->paginate($pagina);
        return $curso;
    }

    public function inserir($request){
        $dados = $request->all();
        DB::beginTransaction();
        try
        {
            $dados['novo']		    = 1;
            $dados['user_id']		= auth()->user()->id;
            $dados['escola_id']		= auth()->user()->escola_id;
            $dados['instituicao_id']= auth()->user()->instituicao_id;
            $c = explode(';',$dados['ciclo_etapa_id']);
            $dados['ciclo_id'] = $c[0];
            $dados['cicloetapa_id'] = $c[1];
            $dados['tipo_permissao_biblioteca'] = @$dados['tipo_permissao_biblioteca'] ?? 0;
            $dados['permite_biblioteca'] = @$dados['permite_biblioteca'] ?? 0;

            $capa = $this->addCapa();
            if($capa!=null) {
                $dados['capa'] = $capa;
            }

            $roteiro = new Curso($dados);
            $roteiro->save();


            $roteiro->escolasBiblioteca()->detach();
            $escolas = $this->preparaEscolasBiblioteca($dados);
            $roteiro->escolasBiblioteca()->attach($escolas);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function update($request){
        $dados = $request->all();
        DB::beginTransaction();
        try
        {
            $dados['user_id']		= auth()->user()->id;
            $dados['escola_id']		= auth()->user()->escola_id;
            $dados['instituicao_id']= auth()->user()->instituicao_id;
            $c = explode(';',$dados['ciclo_etapa_id']);
            $dados['ciclo_id'] = $c[0];
            $dados['cicloetapa_id'] = $c[1];
            $dados['tipo_permissao_biblioteca'] = @$dados['tipo_permissao_biblioteca'] ?? 0;

            if(!isset($dados['permite_biblioteca']) || $dados['permite_biblioteca'] != 1 ){
                $dados['permite_biblioteca'] = 0;
                $dados['tipo_permissao_biblioteca'] = 0;
            }

            if(!isset($dados['existeImg'])){
                $capa = $this->addCapa();
                if($capa!=null) {
                    $dados['capa'] = $capa;
                }
            }

            $roteiro = $this->get($dados['id']);
            unset($roteiro->escolasBiblioteca);
            $roteiro->update($dados);

            $roteiro->escolasBiblioteca()->detach();
            $escolas = $this->preparaEscolasBiblioteca($dados);
            $roteiro->escolasBiblioteca()->attach($escolas);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function preparaEscolasBiblioteca($dados){
        $escolas = [];
        if($dados['tipo_permissao_biblioteca'] == 1){
            $escolas[]=[
                'escola_id'     => 0,
                'instituicao_id'=> 0,
                'publico'       => 0,
                'privado'       => 0,
            ];
        }elseif($dados['tipo_permissao_biblioteca'] == 2) {
            $escolas[]=[
                'escola_id'     => 0,
                'instituicao_id'=> 0,
                'publico'       => 1,
                'privado'       => 0,
            ];
        }elseif($dados['tipo_permissao_biblioteca'] == 3) {
            $escolas[]=[
                'escola_id'     => 0,
                'instituicao_id'=> 0,
                'publico'       => 0,
                'privado'       => 1,
            ];
        }elseif($dados['tipo_permissao_biblioteca'] == 4) {
            foreach($dados['instituicaoBiblioteca'] as $e){
                foreach($dados['escolaBiblioteca'][$e] as $t){
                    $escola = [
                        'escola_id' => $t,
                        'instituicao_id' => $e,
                    ];
                    $escolas[] = $escola;
                }
            }
        }
        return $escolas;
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
            $img->resize(1920, 550, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp', 90);

            $resource = $img->stream()->detach();
            $fileName = Str::random().$img->filename.'.webp';

            Storage::disk()->put(config('app.frStorage').'uploads/cursos/capas/'.$fileName, $resource);
            return $fileName;
        }
        else
        {
            return null;
        }

    }

    public function excluir($id){
        DB::beginTransaction();
        try
        {
            $roteiro = $this->get($id);
            $roteiro->delete();
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function publicar($id,$dados){
        DB::beginTransaction();
        try
        {
            $publicado = 0;
            if($dados['publicado'] == 0){
                $publicado = 1;
            }
            $roteiro = $this->get($id);
            unset($roteiro->escolasBiblioteca);
            $roteiro->update(['publicado'=>$publicado]);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function duplicar($id, $dados, $trilhaParaAdicionar = null, $ordemTrilhaParaAdicionar =null){

        DB::beginTransaction();
        try
        {
            if(isset($dados['biblioteca']) && $dados['biblioteca'] == 1){
                $roteiro = Curso::where('novo',1)->find($id);
            }else{
                $roteiro = $this->get($id,false,false,$dados);
            }

            unset($roteiro->escolasBiblioteca);

            $roteiroNovo = $roteiro->replicate();
            $roteiroNovo->user_id = auth()->user()->id;
            $roteiroNovo->publicado = 0;
            if(auth()->user()->permissao != 'Z'){
                $roteiroNovo->escola_id                 = auth()->user()->escola_id;
                $roteiroNovo->instituicao_id            = auth()->user()->instituicao_id;
                $roteiroNovo->permite_biblioteca        = 0;
                $roteiroNovo->tipo_permissao_biblioteca = 0;
            }
            if(isset($dados['biblioteca']) && $dados['biblioteca'] == 1){
                $roteiroNovo->titulo = $roteiroNovo->titulo . ' (Favoritado)';
            }else{
                $roteiroNovo->titulo = $roteiroNovo->titulo . ' (Cópia)';
            }
            $roteiroNovo->save();
            if(auth()->user()->permissao == 'Z') {
                $escolas = CursosInstituicaoEscolaBiblioteca::where('curso_id',$id)->get();
                $inserir = [];
                foreach($escolas as $e){
                    $novo = $e->toArray();
                    $novo['curso_id'] = $roteiroNovo->id;
                    $inserir[] = $novo;
                }
                if(count($inserir) > 0) {
                    CursosInstituicaoEscolaBiblioteca::insert($inserir);
                }
            }
            $temas = $roteiro->temas;
            foreach($temas as $t){
                $this->duplicarTema($id, $t->id, $roteiroNovo->id, $dados);
            }

            if($trilhaParaAdicionar){
                $novo = [
                    'trilha_id' => $trilhaParaAdicionar,
                    'curso_id' => $roteiroNovo->id,
                    'ordem' =>  $ordemTrilhaParaAdicionar,
                ];
                TrilhasCurso::create($novo);
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

    public function get($id, $matriculado=false, $trilhaId=false, $dados =[]){
        $curso = Curso::where('novo',1);
        if($matriculado){
            $curso->whereHas('trilhas',function($q) use($id,$trilhaId){
                $q->join('trilha_usuario_matricula','trilhas.id', 'trilha_usuario_matricula.trilha_id')
                    ->where('curso_id',$id)
                    ->where('trilha_usuario_matricula.user_id',auth()->user()->id)
                    ->where('trilhas.id',$trilhaId);
            });
        }else{
            $curso = $this->restricaoAcesso($curso, $dados);
        }


        $curso = $curso->with([ 'temas'=>function($q){
            $q->with('conteudo');
        }])->find($id);


        $escolas = CursosInstituicaoEscolaBiblioteca::where('curso_id',$id)->get();
        $curso->escolasBiblioteca = $escolas;

        return $curso;
    }

    public function cicloEtapa()
    {
        return Ciclo::join('ciclo_etapas','ciclos.id','ciclo_etapas.ciclo_id')
            ->orderBy('ciclos.titulo')
            ->orderBy('ciclo_etapas.titulo')
            ->selectRaw('ciclo_etapas.id, ciclos.id as ciclo_id, ciclos.titulo as ciclo, ciclo_etapas.titulo as ciclo_etapa')
            ->get();
    }

    public function inserirTema($dados){
        DB::beginTransaction();
        try
        {
            $dados['user_id'] = auth()->user()->id;
            $aula = new Aula($dados);
            $aula->save();

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function editarTema($dados){
        DB::beginTransaction();
        try
        {
            $roteiro = $this->get($dados['curso_id']);
            $aula = Aula::where('curso_id',$roteiro->id)->find($dados['aula_id']);
            $aula->update($dados);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function getTema($dados){
        $roteiro = $this->get($dados['curso_id']);
        return  Aula::with('conteudo')
                    ->where('curso_id', $roteiro->id)->find($dados['aula_id']);
    }

    public function ordemTema($dados){
        DB::beginTransaction();
        try
        {
            $roteiro = $this->get($dados['curso_id']);
            $i=0;
            foreach($dados['ordem'] as $tema){
                $aula =  Aula::where('curso_id', $roteiro->id)->find($tema);
                $aula->update(['ordem'=>$i]);
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

    public function excluirTema($cursoId, $temaId){
        DB::beginTransaction();
        try
        {
            $roteiro = $this->get($cursoId);
            $aula = Aula::where('curso_id', $roteiro->id)->find($temaId);
            $aula->delete();

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function duplicarTema($cursoId, $temaId, $cursoRecebe = false, $dados = []){
        DB::beginTransaction();
        try
        {
            if(isset($dados['biblioteca']) && $dados['biblioteca'] == 1){
                $roteiro = Curso::where('novo',1)->find($cursoId);
            }else{
                $roteiro = $this->get($cursoId, false,false,$dados);
            }

            $aula = Aula::where('curso_id', $roteiro->id)->find($temaId);

            $aulaDuplicada = $aula->replicate();

            if($cursoRecebe)
            {
                $aulaDuplicada->curso_id = $cursoRecebe;
                $aulaDuplicada->user_id = auth()->user()->id;
            }else{
                $aulaDuplicada->titulo = $aulaDuplicada->titulo . ' (Cópia)';
            }
            $aulaDuplicada->save();

            $roteiroConteudoService = new RoteiroConteudoService();
            $conteudos = ConteudoAula::where('curso_id', $cursoId)->where('aula_id',$temaId)->get();

            foreach($conteudos as $c){
                $roteiroConteudoService->duplicar($cursoId, $temaId,$c->conteudo_id,$aulaDuplicada->id, $cursoRecebe);
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
}
