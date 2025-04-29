<?php


namespace App\Services\Fr;
use App\Library\Slim;
use App\Models\Altitude\Curso;
use App\Models\Altitude\CursoAulaUsuarioDiscursiva;
use App\Models\Altitude\CursoAulaUsuarioEntregavel;
use App\Models\Altitude\CursoAulaUsuarioMatricula;
use App\Models\Altitude\TrilhasInstituicaoEscolaBiblioteca;
use App\Models\Altitude\TrilhasInstituicaoEscolaRealizar;
use App\Models\Ciclo;
use App\Models\FrConteudo;
use App\Models\Trilha;
use App\Models\Altitude\TrilhasMatriculasUsuario;
use App\Models\TrilhasCurso;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class TrilhasService
{

    private function restricaoAcesso($trilhas, $pesquisa){
        //// TRILHAS DA BIBLIOTECA
        if(isset($pesquisa['biblioteca']) && $pesquisa['biblioteca'] == 1 && (auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')){
            $trilhas->where('instituicao_id',1)
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

                    $q->orWhereHas('permissaoBiblioteca',function($queryy){
                        $queryy->where(function($query){
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
                });
        }
        //// FAZER A TRILHA
        elseif(isset($pesquisa['realizar']) && $pesquisa['realizar']){
            //// é EAD
            if(isset($pesquisa['ead']) && $pesquisa['ead']) {
                $trilhas->where('trilhas.ead', 1)
                    ->where('trilhas.publicado', 1);
                if(!isset($pesquisa['index']))
                {
                    $trilhas->where('perfil_permissao_realizar', 'like', '%-' . auth()->user()->permissao . '-%');
                }else{
                    if(auth()->user()->permissao!='Z' && auth()->user()->permissao!='I' && auth()->user()->permissao!='C') {
                        $trilhas->where('perfil_permissao_realizar', 'like', '%-' . auth()->user()->permissao . '-%');
                    }else{
                        if(auth()->user()->permissao == 'C'){
                            $trilhas->where('perfil_permissao_realizar', '<>' ,'-Z-');
                        }
                    }
                }
                $trilhas->where(function($q){
                        //// TOTAS AS INSTITUICOES
                        $q->orWhere('tipo_permissao_realizar',1);
                        $instituicao = session('instituicao');

                        if($instituicao['tipo'] == 1){
                            //// TODAS AS PRIVADAS
                            $q->orWhere('tipo_permissao_realizar',3);
                        }elseif ($instituicao['tipo'] == 2){
                            //// TODAS AS PUBLICAS
                            $q->orWhere('tipo_permissao_realizar',2);
                        }

                        $q->orWhereHas('permissaoEad',function($query){
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
                $trilhas->where('trilhas.ead', 0)
                    ->where('instituicao_id', auth()->user()->instituicao_id)
                    ->where('escola_id', auth()->user()->escola_id)
                    ->where('trilhas.publicado', 1);

            }
        }
        ///// GESTAO DAS TRILHAS
        else{
            if(auth()->user()->permissao == 'Z'){
                $trilhas->where('instituicao_id', 1);
                if(!isset($pesquisa['ead_normal'])) {
                    if (isset($pesquisa['ead']) && $pesquisa['ead'] == 1) {
                        $trilhas->where('ead', 1);
                    } else {
                        $trilhas->where('ead', 0);
                    }
                }
            }
            else{
                $trilhas->where('instituicao_id', auth()->user()->instituicao_id)
                    ->where('escola_id', auth()->user()->escola_id)
                    ->where('user_id', auth()->user()->id);
            }
        }
        return $trilhas;
    }

    public function getLista($pagina = null, $pesquisa = null)
    {

        $trilhas = Trilha::where('nova',1)
                        ->join('disciplinas','disciplinas.id','trilhas.disciplina_id')

                        ->with('user')
                        ->with(['cursos'=>function($query){
                            $query->with(['matriculados'=>function($q){
                                $q->where('user_id',auth()->user()->id);
                            }]);
                        }])
                        ->with(['matriculados'=>function($q){
                            $q->where('user_id',auth()->user()->id);
                        }]);

        if(isset($pesquisa['ead']) && $pesquisa['ead']){
            $trilhas->with('avaliacao');
        }

        if( isset($pesquisa['realizar']) && $pesquisa['realizar'] && !isset($pesquisa['ead'])){
            $aluno = User::with('turmaDeAlunos')->find(auth()->user()->id);
            $turmaAluno = $aluno->turmaDeAlunos->pluck('id');
            $trilhas->join('fr_turma_professor','fr_turma_professor.professor_id','trilhas.user_id')
                ->whereIn('fr_turma_professor.turma_id',$turmaAluno);
        }

        $trilhas = $this->restricaoAcesso($trilhas, $pesquisa);

        /// filtro da pesquisa
        if(isset($pesquisa['texto']) && $pesquisa['texto']!='')
        {
            $trilhas->where(function($q) use ($pesquisa){
                $q->orWhere('trilhas.titulo','like','%'.$pesquisa['nome'].'%')
                    ->orWhere('trilhas.tag','like','%'.$pesquisa['nome'].'%');
            });
        }
        if(isset($pesquisa['componente']) && $pesquisa['componente']!='')
        {
            $trilhas->where(function($q) use ($pesquisa){
                $q->orWhere('trilhas.disciplina_id',$pesquisa['componente']);
            });
        }
        if(isset($pesquisa['ciclo_etapa']) && $pesquisa['ciclo_etapa']!='')
        {
            $trilhas->where(function($q) use ($pesquisa){
                $q->orWhere('trilhas.cicloetapa_id',$pesquisa['ciclo_etapa']);
            });
        }

        if(isset($pesquisa['id']) && $pesquisa['id']!=''){
            return $trilhas->selectRaw('trilhas.*,  disciplinas.titulo as disciplina')->where('trilhas.id',$pesquisa['id'])->first();
        }

        return  $trilhas->selectRaw('distinct trilhas.*,  disciplinas.titulo as disciplina')
            ->orderBy('trilhas.id', 'DESC')
           // ->groupby('trilhas.id')
            ->paginate($pagina);
    }

    public function getEstatistica($trilhas, $userId = null)
    {
        if(!$userId){
            $userId = auth()->user()->id;
        }
        $trilha = Trilha::whereIn('trilhas.id',$trilhas)
                        ->join('trilhas_cursos', 'trilhas_cursos.trilha_id','trilhas.id')
                        ->join('cursos', 'cursos.id','trilhas_cursos.curso_id' )
                        ->join('aulas', 'cursos.id','aulas.curso_id')
                        ->join('conteudo_aula', function($join){
                            $join->on('conteudo_aula.curso_id','trilhas_cursos.curso_id');
                            $join->on('conteudo_aula.aula_id','aulas.id');
                        } )
                        ->join('conteudos', 'conteudo_aula.conteudo_id','conteudos.id')
                        ->leftJoin('curso_aula_usuario_matricula',function($join) use ($userId){
                            $join->on('curso_aula_usuario_matricula.trilha_id','trilhas.id')
                                ->on('curso_aula_usuario_matricula.curso_id','conteudo_aula.curso_id')
                                ->on('curso_aula_usuario_matricula.conteudo_id','conteudo_aula.conteudo_id')
                                ->on('curso_aula_usuario_matricula.aula_id','conteudo_aula.aula_id')
                                ->on('curso_aula_usuario_matricula.user_id',DB::raw($userId));
                        })
                        ->whereNull('aulas.deleted_at')
                        ->where(function($q){
                            $q->orWhere('conteudos.status',1)
                                ->orWhere('conteudos.tipo',21);
                        })
                        ->where('cursos.status_id',1)
                        ->selectRaw('trilhas.id, count(conteudo_aula.id) as total, count(curso_aula_usuario_matricula.user_id) as feito' )
                        ->groupBy('trilhas.id')
                        ->get();
        $ret = [];
        foreach ($trilha as $t){
            $perc = 0;
            if($t->total > 0 && $t->feito > 0)
            {
                $perc = round(($t->feito*100)/$t->total);
            }
            $ret[$t->id] = [
                'perc' => $perc,
                'total' => $t->total,
                'feito' => $t->feito,
            ];
        }
        return $ret;

    }

    public function getEstatisticaRoteiro($roteiros)
    {
        $curso = Curso::whereIn('cursos.id',$roteiros)
            ->join('aulas', 'cursos.id','aulas.curso_id')
            ->join('conteudo_aula', function($join){
                    $join->on('conteudo_aula.curso_id','aulas.curso_id');
                    $join->on('conteudo_aula.aula_id','aulas.id');
            } )
            ->join('conteudos', 'conteudo_aula.conteudo_id','conteudos.id')
            ->leftJoin('curso_aula_usuario_matricula',function($join){
                $join->on('curso_aula_usuario_matricula.curso_id','conteudo_aula.curso_id')
                    ->on('curso_aula_usuario_matricula.conteudo_id','conteudo_aula.conteudo_id')
                    ->on('curso_aula_usuario_matricula.aula_id','conteudo_aula.aula_id')
                    ->on('curso_aula_usuario_matricula.user_id',DB::raw(auth()->user()->id));
            })
            ->whereNull('aulas.deleted_at')
            ->where(function($q){
                $q->orWhere('conteudos.status',1)
                    ->orWhere('conteudos.tipo',21);
            })
            ->where('cursos.status_id',1)
            ->selectRaw('cursos.id, count(cursos.id) as total, count(curso_aula_usuario_matricula.user_id) as feito' )
            ->groupBy('cursos.id')
            ->get();
        $ret = [];
        foreach ($curso as $t){
            $perc = 0;
            if($t->total > 0 && $t->feito > 0)
            {
                $perc = round(($t->feito*100)/$t->total);
            }
            $ret[$t->id] = [
                'perc' => $perc,
                'total' => $t->total,
                'feito' => $t->feito,
            ];
        }
        return $ret;

    }

    public function cicloEtapa()
    {
        return Ciclo::join('ciclo_etapas','ciclos.id','ciclo_etapas.ciclo_id')
            ->orderBy('ciclos.titulo')
            ->orderBy('ciclo_etapas.titulo')
            ->selectRaw('ciclo_etapas.id, ciclos.titulo as ciclo, ciclo_etapas.titulo as ciclo_etapa')
            ->get();
    }


    public function inserir($request){
        $dados = $request->all();
        DB::beginTransaction();
        try
        {
            $dados['nova']		    = 1;
            $dados['user_id']		= auth()->user()->id;
            $dados['escola_id']		= auth()->user()->escola_id;
            $dados['instituicao_id']= auth()->user()->instituicao_id;
            $c = explode(';',$dados['ciclo_etapa_id']);
            $dados['ciclo_id'] = $c[0];
            $dados['cicloetapa_id'] = $c[1];
            $dados['tipo_permissao_biblioteca'] = @$dados['tipo_permissao_biblioteca'] ?? 0;
            $dados['tipo_permissao_realizar']   = @$dados['tipo_permissao_realizar'] ?? 0;

            if(!isset($dados['ead']) || $dados['ead'] != 1 ){
                $dados['ead'] = 0;
            }
            if(!isset($dados['permite_biblioteca']) || $dados['permite_biblioteca'] != 1 ){
                $dados['permite_biblioteca'] = 0;
            }

            $capa = $this->addCapa();
            if($capa!=null) {
                $dados['capa'] = $capa;
            }

            if($dados['ead'] == 1){
                $dados['perfil_permissao_realizar'] = '-'.implode('-', $dados['perfil_permissao_realizar']).'-';
            }else{
                $dados['perfil_permissao_realizar'] = '';
            }

            $trilha = new Trilha($dados);
            $trilha->save();
            $escolas = $this->preparaEscolas($dados);
            $trilha->escolasEAD()->attach($escolas);
            $escolas = $this->preparaEscolasBiblioteca($dados);
            $trilha->escolasBiblioteca()->attach($escolas);

            $roteiros = [];
            $i=0;
            foreach ($dados['roteiros'] as $r){
                $roteiros[] = [
                    'curso_id' => $r,
                    'ordem' => $i
                ];
                $i++;
            }
            $trilha->cursos()->sync($roteiros);

            if($dados['tipo_permissao_realizar']  == 4 && $dados['ead'] == 1){
                $trilha->escolasEAD()->detach();
                $escolas = $this->preparaEscolas($dados);
                $trilha->escolasEAD()->attach($escolas);
            }

            if($dados['tipo_permissao_biblioteca']  == 4 && $dados['permite_biblioteca'] == 1){
                $trilha->escolasBiblioteca()->detach();
                $escolas = $this->preparaEscolasBiblioteca($dados);
                $trilha->escolasBiblioteca()->attach($escolas);
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
            $dados['tipo_permissao_realizar']   = @$dados['tipo_permissao_realizar'] ?? 0;

            if(!isset($dados['ead']) || $dados['ead'] != 1 ){
                $dados['ead'] = 0;
                $dados['carga_horaria'] = null;
                $dados['tipo_permissao_realizar'] = 0;
            }
            if(!isset($dados['permite_biblioteca']) || $dados['permite_biblioteca'] != 1 ){
                $dados['permite_biblioteca'] = 0;
                $dados['tipo_permissao_biblioteca'] = 0;
            }

            if($dados['ead'] == 1){
                $dados['perfil_permissao_realizar'] = '-'.implode('-', $dados['perfil_permissao_realizar']).'-';
            }else{
                $dados['perfil_permissao_realizar'] = '';
            }

            if(!isset($dados['existeImg'])){
                $capa = $this->addCapa();
                if($capa!=null) {
                    $dados['capa'] = $capa;
                }
            }
            $trilha = $this->get($dados['id']);
            unset($trilha->escolas);
            unset($trilha->escolasBiblioteca);
            $trilha->update($dados);
            $roteiros = [];
            $i=0;
            foreach ($dados['roteiros'] as $r){
                $roteiros[] = [
                    'curso_id' => $r,
                    'ordem' => $i
                ];
                $i++;
            }
            $trilha->cursos()->sync($roteiros);


            $trilha->escolasEAD()->detach();
            $escolas = $this->preparaEscolas($dados);
            $trilha->escolasEAD()->attach($escolas);

            $trilha->escolasBiblioteca()->detach();
            $escolas = $this->preparaEscolasBiblioteca($dados);
            $trilha->escolasBiblioteca()->attach($escolas);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function preparaEscolas($dados){
        $escolas = [];
        if($dados['tipo_permissao_realizar'] == 1){
            $escolas[]=[
                'escola_id'     => 0,
                'instituicao_id'=> 0,
                'publico'       => 0,
                'privado'       => 0,
            ];
        }elseif($dados['tipo_permissao_realizar'] == 2) {
            $escolas[]=[
                'escola_id'     => 0,
                'instituicao_id'=> 0,
                'publico'       => 1,
                'privado'       => 0,
            ];
        }elseif($dados['tipo_permissao_realizar'] == 3) {
            $escolas[]=[
                'escola_id'     => 0,
                'instituicao_id'=> 0,
                'publico'       => 0,
                'privado'       => 1,
            ];
        }elseif($dados['tipo_permissao_realizar'] == 4) {
            foreach($dados['instituicao'] as $e){
                foreach($dados['escola'][$e] as $t){
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

    public function excluir($id){
        DB::beginTransaction();
        try
        {
            $trilha = $this->get($id);
            unset($trilha->escolas);
            unset($trilha->escolasBiblioteca);
            $trilha->delete();
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

            $trilha = $this->get($id);
            $publicado = 0;
            if($trilha->publicado == 0){
                $publicado = 1;
            }
            unset($trilha->escolas);
            unset($trilha->escolasBiblioteca);
            $trilha->update(['publicado'=>$publicado]);
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function duplicar($id,$dados){
        DB::beginTransaction();
        try
        {

            $trilha = $this->get($id,$dados);
            unset($trilha->escolas);
            unset($trilha->escolasBiblioteca);
            $trilhaNova = $trilha->replicate();
            $trilhaNova->user_id = auth()->user()->id;
            $trilhaNova->publicado = 0;
            $trilhaNova->ead_matricula_inicio = null;
            $trilhaNova->ead_matricula_fim = null;
            if(auth()->user()->permissao != 'Z'){
                $trilhaNova->escola_id             = auth()->user()->escola_id;
                $trilhaNova->instituicao_id        = auth()->user()->instituicao_id;
                $trilhaNova->permite_biblioteca    = 0;
                $trilhaNova->tipo_permissao_biblioteca = 0;
                $trilhaNova->tipo_permissao_realizar = 0;
                $trilhaNova->perfil_permissao_realizar = null;
                $trilhaNova->ead = 0;
                $trilhaNova->carga_horaria = null;
            }

            if(isset($dados['biblioteca']) && $dados['biblioteca'] == 1){
                $trilhaNova->titulo = $trilhaNova->titulo . ' (Favoritado)';
            }else{
                $trilhaNova->titulo = $trilhaNova->titulo . ' (Cópia)';
            }
            $trilhaNova->save();

            if(auth()->user()->permissao == 'Z') {
                $escolas = TrilhasInstituicaoEscolaBiblioteca::where('trilha_id',$id)->get();
                $inserir = [];
                foreach($escolas as $e){
                    $novo = $e->toArray();
                    $novo['trilha_id'] = $trilhaNova->id;
                    $inserir[] = $novo;
                }
                if(count($inserir) > 0) {
                    TrilhasInstituicaoEscolaBiblioteca::insert($inserir);
                }

                $escolas = TrilhasInstituicaoEscolaRealizar::where('trilha_id',$id)->get();
                $inserir = [];
                foreach($escolas as $e){
                    $novo = $e->toArray();
                    $novo['trilha_id'] = $trilhaNova->id;
                    $inserir[] = $novo;
                }

                if(count($inserir) > 0) {
                    TrilhasInstituicaoEscolaRealizar::insert($inserir);
                }
                $cursos = $trilha->cursos;
                $insert = [];
                $ordem = 0;
                foreach ($cursos as $c){
                    $insert[] = [
                        'trilha_id' =>  $trilhaNova->id,
                        'curso_id' =>  $c->id,
                        'ordem' =>  $ordem,
                    ];
                    $ordem++;
                }
                if(count($insert) > 0) {
                    TrilhasCurso::insert($insert);
                }

            }else{
                $cursos = $trilha->cursos;
                if($trilha->instituicao_id == 1){
                    $roteiroService = new RoteiroService();
                    $i=0;
                    foreach ($cursos as $c){
                        $roteiroService->duplicar($c->id,$dados,$trilhaNova->id,$i);
                        $i++;
                    }
                }
                else{
                    /// novo
                    $insert =[];
                    $insert =[];
                    $ordem = 0;
                    foreach ($cursos as $c){
                        $insert[] = [
                            'trilha_id' =>  $trilhaNova->id,
                            'curso_id' =>  $c->id,
                            'ordem' =>  $ordem,
                        ];
                        $ordem++;
                    }
                    if(count($insert) > 0) {
                        TrilhasCurso::insert($insert);
                    }
                }
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

    public function periodoMatricula($id,$dados){
        DB::beginTransaction();
        try
        {
            $trilha = $this->get($id,['ead'=>1]);
            unset($trilha->escolas);
            unset($trilha->escolasBiblioteca);
            $trilha->update([
                'ead_matricula_inicio' => dataUS($dados['ead_matricula_inicial']),
                'ead_matricula_fim' => dataUS($dados['ead_matricula_final']),
            ]);

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
            $img->resize(400, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp', 90);

            $resource = $img->stream()->detach();
            $fileName = Str::random().$img->filename.'.webp';

            Storage::disk()->put(config('app.frStorage').'uploads/trilhas/capas/'.$fileName, $resource);
            return $fileName;
        }
        else
        {
            return null;
        }
    }

    public function get($id, $dados =[]){
        if(auth()->user()->permissao == 'Z' && !isset($dados['ead'])){
            $dados['ead_normal'] = true;
        }
        $trilha = Trilha::where('nova',1);
        $trilha = $this->restricaoAcesso($trilha, $dados);

        $trilha = $trilha->with(['cursos'])->find($id);
        $escolas = TrilhasInstituicaoEscolaRealizar::where('trilha_id',$id)->get();
        @$trilha->escolas = $escolas;
        $escolas = TrilhasInstituicaoEscolaBiblioteca::where('trilha_id',$id)->get();
        @$trilha->escolasBiblioteca = $escolas;
        return $trilha;
    }

    public function detalhes($dados){

        $trilha = $this->getLista(null, $dados);
        if($trilha){
            if(count($trilha->matriculados) == 0){
                return $trilha;
            }
        }
        return false;
    }

    public function matricular($dados){
        $trilha = $this->getLista(null, $dados);
        if($trilha){
            if(count($trilha->matriculados) < 2){
                if($trilha->ead == 1){
                    if(!Carbon::now()->between($trilha->ead_matricula_inicio.' 00:00:01',$trilha->ead_matricula_fim.'23:59:59')){
                        return false;
                    }
                    if($this->qtdMatriculasEADSemestre() >= 2){
                        return false;
                    }
                }

                $dados=[
                    'trilha_id' => $dados['id'],
                    'user_id' => auth()->user()->id,
                ];
                $matricula = new TrilhasMatriculasUsuario($dados);
                return $matricula->save();
            }
        }
        return false;
    }

    public function listaRoteiro($dados){
        $trilha = $this->getLista(null, $dados);
        if($trilha) {
            if (count($trilha->matriculados) == 1) {
                return $trilha;
            }
        }
        return false;
    }

    public function getRoteiroConteudoRelatorio($id, $ead = false){
        return Trilha::with(['cursos'=>function($query){
                                    $query->with(['temas'=>function($q){
                                        return $q->with('conteudo');
                                    }]);
                                }])
            ->with(['matriculas'=>function($q) use($ead){
                if($ead && auth()->user()->permissao != 'Z'){
                    $q->join('user_permissao',function($join){
                        $join->on('user_permissao.user_id', 'users.id');
                        $join->on('user_permissao.instituicao_id', DB::raw(auth()->user()->instituicao_id));
                        if(auth()->user()->permissao != 'I') {
                            $join->on('user_permissao.escola_id', DB::raw(auth()->user()->escola_id));
                            $join->on('user_permissao.permissao', '<>' , "I");
                        }
                    })->selectRaw('distinct users.*');
                }
                $q->orderBy('name');
            }])
            ->find($id);
    }

    public function getInteracao($dados){
        $conteudo = FrConteudo::find($dados['conteudo_id']);
        if($dados['tipo'] == 7){
            $dados = CursoAulaUsuarioDiscursiva::where('user_id',$dados['user_id'])
                ->where('trilha_id',$dados['trilha_id'])
                ->where('curso_id',$dados['curso_id'])
                ->where('aula_id',$dados['aula_id'])
                ->where('conteudo_id',$dados['conteudo_id'])->first();
            $view = [
                'conteudo' => $conteudo,
                'dados' => $dados,
            ];
            return view('fr.trilhas.interacao_discursiva',$view)->render();
        }else{
            $dados = CursoAulaUsuarioEntregavel::where('user_id',$dados['user_id'])
                ->where('trilha_id',$dados['trilha_id'])
                ->where('curso_id',$dados['curso_id'])
                ->where('aula_id',$dados['aula_id'])
                ->where('conteudo_id',$dados['conteudo_id'])->get();
            $view = [
                'conteudo' => $conteudo,
                'dados' => $dados,
            ];
            return view('fr.trilhas.interacao_entregavel',$view)->render();
        }

    }

    public function getRealizadosTrilhasRoteiros($id){
        $dados = CursoAulaUsuarioMatricula::where('trilha_id',$id)->get();
        $retorno = [];
        foreach ($dados as $d){
            $retorno[$d['user_id']] [$d['curso_id']] [$d['aula_id']] [$d['conteudo_id']] = 1;
        }
        return $retorno;
    }

    public function getPercAlunosTrilha($id, $matriculados){
        $retorno = [];
        foreach ($matriculados as $m){
            $retorno[$m] = $this->getEstatistica([$id], $m);
            $retorno[$m] =$retorno[$m][$id];
        }
        return $retorno;
    }

    public function qtdMatriculasEADSemestre(){
        $mes = date('m');
        $ano = date('Y');
        if($mes<=7){
            return TrilhasMatriculasUsuario::join('trilhas','trilhas.id','trilha_usuario_matricula.trilha_id')
                ->where('trilha_usuario_matricula.user_id',auth()->user()->id)
                ->where('ead',1)
                ->where('trilha_usuario_matricula.created_at','>=',$ano.'-1-1 00:00:01')
                ->where('trilha_usuario_matricula.created_at','<=',$ano.'-7-31 23:59:59')->count();
        }else{
            return TrilhasMatriculasUsuario::join('trilhas','trilhas.id','trilha_usuario_matricula.trilha_id')
                ->where('ead',1)
                ->where('trilha_usuario_matricula.user_id',auth()->user()->id)
                ->where('trilha_usuario_matricula.created_at','>=',$ano.'-8-1 00:00:01')
                ->where('trilha_usuario_matricula.created_at','<=',$ano.'-12-31 23:59:59')->count();
        }
    }

}
