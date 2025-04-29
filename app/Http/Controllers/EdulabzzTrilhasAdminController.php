<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Escola;

use App\Entities\Trilhas\Trilha;
use App\Entities\Trilhas\TrilhasCurso;
use App\Entities\Trilhas\TrilhasMatricula;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Instituicao;
use App\Models\Ciclo;
use App\Models\CicloEtapa;
use App\Models\Disciplina;

Use App\Models\Categoria;
Use App\Models\CursosPermissoes;
Use App\Models\TrilhasPermissoes;
Use App\Models\CiclosPermissoes;
Use App\Models\CicloEtapasPermissoes;
Use App\Models\DisciplinaPermissoes;
/*
use App\Models\InstituicaoUser;
use App\Models\ResponsavelEscola;
*/
class EdulabzzTrilhasAdminController extends Controller
{
    public function index(Request $request)
    {
        $trilhas = Trilha::query();

        //$instituicaoUser = InstituicaoUser::where('user_id', Auth::user()->id)->first()->instituicao_id;
        $instituicao = session('instituicao');
        $instituicaoUser = $instituicao['id'];
        $tem_pesquisa = false;
        if($request->input('pesquisa')!=''){
            $tem_pesquisa = $request->input('pesquisa');
        }
        //$tem_pesquisa = Input::has('c') ? (Input::get('pesquisa') != null && Input::get('pesquisa') != "") : false;

        $trilhas->when($tem_pesquisa, function ($query) {
            return $query->where('trilhas.status_id', '!=', 2)
            ->where('trilhas.titulo', 'like', '%' . Input::get('pesquisa') . '%')
                    ->orWhere('trilhas.descricao', 'like', '%' . Input::get('pesquisa') . '%');
        });

        if(Auth::user()->privilegio_id == 2){
            $trilhas = $trilhas->where('trilhas.status_id', '!=', 2)
                                ->where('trilhas.instituicao_id', $instituicaoUser)
                                ->orwhere('trilhas.user_id',  Auth::user()->id);

        }elseif(Auth::user()->privilegio_id ==3){
            $trilhas = $trilhas->where('trilhas.status_id', '!=', 2)
                                //->orwhere('visibilidade_id',  1)
                                ->where('trilhas.user_id',  Auth::user()->id);

            }elseif(Auth::user()->privilegio_id == 5){

            $trilhas = $trilhas->where([['trilhas.status_id', '!=', 2], ['responsavel_escolas.user_id', Auth::user()->id]])
                                ->leftjoin('responsavel_escolas', 'trilhas.escola_id', 'responsavel_escolas.escola_id')
                                    ->orwhere('trilhas.user_id',  Auth::user()->id);

            }elseif(Auth::user()->permissao == "A" || Auth::user()->privilegio_id == 4){
                return redirect()->route('home')->with('error', 'Sem acesso a essa página!');
            }

        $trilhas = $trilhas->where('trilhas.status_id', '!=', 2)
                        ->leftjoin('users', 'trilhas.user_id', 'users.id')
                        ->leftjoin('escolas', 'trilhas.escola_id', 'escolas.id')
                        ->leftjoin('ciclo_etapas', 'trilhas.cicloetapa_id', 'ciclo_etapas.id')
                        ->leftjoin('ciclos', 'ciclo_etapas.ciclo_id', 'ciclos.id')
                                ->select('trilhas.*', 'users.name', 'escolas.titulo as escola', 'ciclo_etapas.titulo as etapa', 'ciclos.titulo as ciclo')
                                        ->orderBy('trilhas.id', 'DESC')
                                        ->groupby('trilhas.id')->paginate(20);

        return view('pages.trilhas.admin.index', compact('trilhas'));
       //return view('fr/trilhas/index', compact('trilhas'));

    }



    public function create()
    {
        $user = auth()->user();

        $categorias = Categoria::all();

        //$instituicaoUser = InstituicaoUser::where('user_id', $user->id)->first()->instituicao_id;
        $instituicao = session('instituicao');
        $instituicaoUser = $instituicao['id'];

        if(Auth::user()->privilegio_id != 1){
            #mudar quando outros perfis forem relacionados a mais de uma instituicao
            $instituicoes = Instituicao::where('id', $instituicaoUser)->get();
        }else{
            $instituicoes = Instituicao::all();
        }

        $escolas = Escola::where('escolas.status_id', '!=', 2);


        // $escolas = Escola::permissionamentoEscolas($escolas);
        #privilegio school
        if(Auth::user()->privilegio_id == 5){
            $escolas = $escolas->where('responsavel_escolas.user_id', Auth::user()->id)
                                ->join('responsavel_escolas', 'escolas.id', 'responsavel_escolas.escola_id');
        #privilegio professor
        }elseif(Auth::user()->privilegio_id == 3){
            $escolas = $escolas->where('professor_escolas.user_id', Auth::user()->id)
                                ->join('professor_escolas', 'escolas.id', 'professor_escolas.escola_id');

        }else{
                $escolas = $escolas->where('instituicao_id', $instituicaoUser);
            }

        $escolas = $escolas->where('escolas.status_id', '!=', 2)
            ->groupby('escolas.id')
                ->orderby('escolas.titulo', 'ASC')
                ->select('escolas.*')->get();

        $etapas = Ciclo::all();

        $cicloEtapas = CicloEtapa::all();

        $disciplinas = Disciplina::all();

        $visibilidades =[];

        return view('pages.trilhas.admin.create', compact( 'escolas', 'categorias', 'instituicoes',
                    'etapas', 'cicloEtapas', 'disciplinas', 'visibilidades'));
    }

    public function store(Request $request)
    {
        if (!$request->get('curso_id')) {
            return redirect()->back()->withErrors(['Selecione pelo menos um conteúdo para sua trilha!'])->withInput();
        }

        // Função para validar as trilhas
        $this->validateTrilha($request);

        if($request->input('instituicao_id')=='') {
            $instituicao = session('instituicao');
            $request->instituicao_id = $instituicao['id'];
        }

        $trilha = Trilha::create([
            'user_id'        => Auth::user()->id,
            'instituicao_id' => $request->instituicao_id,
            'ciclo_id'       =>  $request->ciclo_id,
            'cicloetapa_id'  =>  $request->cicloetapa_id,
            'disciplina_id'  =>  $request->disciplina_id,
            'visibilidade_id'=> $request->visibilidade,
            'tag'            =>  $request->tag,
            'titulo'         => $request->titulo,
            'capa'           => $request->capa,
            'descricao'      => $request->descricao,
            'escola_id'      => $request->escola_id
        ]);

        // $trilha->cursos()->attach($request->get('curso_id'));

        foreach ($request->get('curso_id') as $cursoId) {
            TrilhasCurso::create([
                'trilha_id' => $trilha->id,
                'curso_id'  => $cursoId,
                'ordem'  => $request->get("curso_id_ordem_$cursoId")
            ]);
            if(!empty($request->escola_id)) {
                $cursoPermissoes = CursosPermissoes::create([
                    'curso_id' => $cursoId,
                    'permissao' => '1',
                    'status' => '1',
                    'escola_id' => $request->escola_id,
                    'instituicao_id' => $request->instituicao_id,
                    'trilha_id' => $trilha->id,
                ]);
            }else{

                $escolas = Escola::where('instituicao_id',$request->instituicao_id )->get();
                if(!empty($escolas)){
                  foreach($escolas as $e){
                    $cursoPermissoes = CursosPermissoes::create([
                        'curso_id' => $cursoId,
                        'permissao' => '1',
                        'status' => '1',
                        'escola_id' => $e->id,
                        'instituicao_id' => $request->instituicao_id,
                        'trilha_id' => $trilha->id,
                    ]);
                  }
                }
            }
        }

        if ($request->capa != null) {
            //$fileExtension = \File::extension($request->capa->getClientOriginalName());
            //$newFileNameCapa = md5($request->capa->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

            $path = $request->file('capa')->store(config('app.frStorage').'uploads/trilhas/capas');

            //if (!Storage::disk('public_uploads')->put($pathCapa, file_get_contents($request->capa))) {
            //    \Session::flash('middle_popup', 'Ops! Não foi possivel enviar a capa.');
            //    \Session::flash('popup_style', 'danger');
           // } else {
                $trilha->capa = basename($path);
                $trilha->save();
           // }

        }
    /*
         vericicar se a trilha é criada para uma escola ou para todas as escolas
         da instituição.

    */
    if(!empty($request->escola_id)) {
            $trilhaPermissao = TrilhasPermissoes::create([
                   'trilha_id' => $trilha->id,
                   'permissao' => '1',
                   'status' => '1',
                   'escola_id' => $request->escola_id,
                   'instituicao_id' => $request->instituicao_id
            ]);
       /*
         vericicar se a trilha é criada para um circulo ou para todos os ciruclos da instituicao
         da instituição.
      */
      if(!empty($request->ciclo_id)){
          $circuloPermissao = CiclosPermissoes::create([
            'ciclo_id' => $request->ciclo_id,
            'permissao' => '1',
            'status' => '1',
            'escola_id' => $request->escola_id,
            'instituicao_id' => $request->instituicao_id,
            'trilha_id' => $trilha->id
          ]);
          /*
            Verificar se foi selecioanado um ciclo etapa especifico  para o permissionamento do código
          */
          if(!empty($request->cicloetapa_id)){
            $cicloEtapasPermissoes = CicloEtapasPermissoes::create([
                'ciclo_etapa_id' => $request->cicloetapa_id,
                'permissao' => '1',
                'status' => '1',
                'escola_id' => $request->escola_id,
                'instituicao_id' => $request->instituicao_id,
                'trilha_id' => $trilha->id
               ]);
          }else{
            $cicloEtapas = CicloEtapa::all();
            foreach($cicloEtapas as $ce){
               $cicloEtapasPermissoes = CicloEtapasPermissoes::create([
                'ciclo_etapa_id' => $ce->id,
                'permissao' => '1',
                'status' => '1',
                'escola_id' => $request->escola_id,
                'instituicao_id' => $request->instituicao_id,
                'trilha_id' => $trilha->id
               ]);
            }
          }

  }else{
     $etapas = Ciclo::all();
       foreach($etapas as $ep){
        $circuloPermissao = CiclosPermissoes::create([
            'ciclo_id' => $ep->id,
            'permissao' => '1',
            'status' => '1',
            'escola_id' => $request->escola_id,
            'instituicao_id' => $request->instituicao_id,
            'trilha_id' => $trilha->id
          ]);
       }

        $cicloEtapas = CicloEtapa::all();
        foreach($cicloEtapas as $ce){

           $cicloEtapasPermissoes = CicloEtapasPermissoes::create([
            'ciclo_etapa_id' => $ce->id,
            'permissao' => '1',
            'status' => '1',
            'escola_id' => $request->escola_id,
            'instituicao_id' => $request->instituicao_id,
            'trilha_id' => $trilha->id
           ]);
        }
    }
    /*
     vericicar se a trilha é criada para uma disciplina ou para todas as disciplinas
     da instituição.
  */
    if(!empty($request->disciplina_id)){
        $disciplinaPermissoes = DisciplinaPermissoes::create([
            'disciplina_id' => $request->disciplina_id,
            'permissao' => '1',
            'status' => '1',
            'escola_id' => $request->escola_id,
            'instituicao_id' => $request->instituicao_id,
            'trilha_id' => $trilha->id
        ]);

    }else{
        $disciplinas = Disciplina::all();
        if(!empty($disciplinas)){
        foreach($disciplinas as $d){
            $disciplinaPermissoes = DisciplinaPermissoes::create([
                'disciplina_id' => $request->disciplina_id,
                'permissao' => '1',
                'status' => '1',
                'escola_id' => $request->escola_id,
                'instituicao_id' => $request->instituicao_id,
                'trilha_id' => $trilha->id
            ]);
        }
        }else{
            $disciplinas = Disciplina::all();
            foreach($disciplinas as $d){
                $disciplinaPermissoes = DisciplinaPermissoes::create([
                    'disciplina_id' => $d->id,
                    'permissao' => '1',
                    'status' => '1',
                    'escola_id' => $request->escola_id,
                    'instituicao_id' => $request->instituicao_id,
                    'trilha_id' => $trilha->id
                ]);
            }
        }
    }

      }else{
          $escolas = Escola::where('instituicao_id',$request->instituicao_id )->get();
          if(!empty($escolas)){
          foreach($escolas as $e){
            $trilhaPermissao = TrilhasPermissoes::create([
                'trilha_id' => $trilha->id,
                'permissao' => '1',
                'status' => '1',
                'escola_id' => $e->id,
                'instituicao_id' => $request->instituicao_id
         ]);
           /*
         vericicar se a trilha é criada para um circulo ou para todos os ciruclos da instituicao
         da instituição.
      */
      if(!empty($request->ciclo_id)){
        $circuloPermissao = CiclosPermissoes::create([
          'ciclo_id' => $request->ciclo_id,
          'permissao' => '1',
          'status' => '1',
          'escola_id' => $e->id,
          'instituicao_id' => $request->instituicao_id,
          'trilha_id' => $trilha->id
         ]);
      }
  }
  /*
   vericicar se a trilha é criada para uma disciplina ou para todas as disciplinas
   da instituição.
 */
  if(!empty($request->disciplina_id)){
      $disciplinaPermissoes = DisciplinaPermissoes::create([
          'disciplina_id' => $request->disciplina_id,
          'permissao' => '1',
          'status' => '1',
          'escola_id' => $e->id,
          'instituicao_id' => $request->instituicao_id,
          'trilha_id' => $trilha->id
      ]);
  }else{
      $disciplinas = Disciplina::all();
      foreach($disciplinas as $d){

          if(!empty($d->id)){
          $disciplinaPermissoes = DisciplinaPermissoes::create([
              'disciplina_id' => $request->disciplina_id,
              'permissao' => '1',
              'status' => '1',
              'escola_id' => $e->id,
              'instituicao_id' => $request->instituicao_id,
              'trilha_id' => $trilha->id
          ]);
          }
      }
    }
} }
        return redirect()->route('gestao.trilhas.listar')->with('message', 'Trilha criada com sucesso!');
    }

    public function edit($idTrilha)
    {
        $trilha = Trilha::find($idTrilha);

        $trilhaCurso = TrilhasCurso::with('curso')->where('trilha_id', '=', $trilha->id)->orderBy('ordem')->get();

        #mostrando todos os cursos porque os bancos são separados atualmente, mudar isso posteriormente
        $cursos = Curso::where('status_id', '!=', 2)->orderBy('titulo', 'ASC')->get();

        $cursosTrilha = [];
        foreach ($trilha->cursos as $curso) {
            $cursosTrilha[] = $curso->id;
        }

        $trilha->trilhas_cursos = $trilhaCurso;

        foreach ($cursos as $curso) {
            $curso->naTrilha = 0;
            if (in_array($curso->id, $cursosTrilha)) {
                $curso->naTrilha = 1;
            }
        }

        //$instituicaoUser = InstituicaoUser::where('user_id', Auth::user()->id)->first()->instituicao_id;

        $instituicao = session('instituicao');
        $instituicaoUser = $instituicao['id'];

        if(Auth::user()->privilegio_id != 1){
            #mudar quando outros perfis forem relacionados a mais de uma instituicao
            $instituicoes = Instituicao::where('id', $instituicaoUser)->get();
        }else{
            $instituicoes = Instituicao::all();
        }

/*        $escolas = Escola::where('escolas.status_id', '!=', 2);

        #privilegio school
        if(Auth::user()->privilegio_id == 5){
            $escolas = Escola::where('responsavel_escolas.user_id', Auth::user()->id)
                                ->join('responsavel_escolas', 'escolas.id', 'responsavel_escolas.escola_id');

        #privilegio professor
        }elseif(Auth::user()->privilegio_id == 3){
            $escolas = Escola::where('professor_escolas.user_id', Auth::user()->id)
                                ->join('professor_escolas', 'escolas.id', 'professor_escolas.escola_id');


        }else{
                $escolas = Escola::where('instituicao_id', $instituicaoUser);
            }

        $escolas = $escolas->select('escolas.*')->orderBy('escolas.titulo')->get();
*/
        $escolas = Escola::where('escolas.status_id', '!=', 2)->where('instituicao_id',$trilha->instituicao_id)->orderBy('escolas.titulo')->get();

        $categorias = Categoria::all();

        $etapas = Ciclo::all();

        $cicloEtapas = CicloEtapa::all();

        $disciplinas = Disciplina::all();

        $visibilidades = [];

        return view('pages.trilhas.admin.edit', compact('trilha', 'cursos', 'escolas', 'categorias', 'instituicoes',
                'escolas', 'etapas', 'cicloEtapas', 'disciplinas', 'visibilidades'));
    }

    public function update(Request $request, $idTrilha)
    {
        if (!$request->get('curso_id')) {
            return redirect()->back()->withErrors(['Selecione pelo menos um Conteúdo para sua trilha!'])->withInput();
        }

        // Função para validar as trilhas
        $this->validateTrilha($request);

        $trilha = Trilha::find($idTrilha);

        if(!$request->instituicao_id){
            $request->instituicao_id = $trilha->instituicao_id;
        }

        $capa = $request->get('capa_atual');

        if($request->file('capa'))
            $capa = $request->file('capa');
        else
            $capa = $request->get('capa_atual');

        if ($request->file('capa')) {
            $fileExtension = \File::extension($request->capa->getClientOriginalName());
            $newFileNameCapa = md5($request->capa->getClientOriginalName() . date("Y-m-d H:i:s") . time()) . '.' . $fileExtension;

            $pathCapa = $request->capa->storeAs('trilhas/capas', $newFileNameCapa, 'public_uploads');

            if (!Storage::disk('public_uploads')->put($pathCapa, file_get_contents($request->capa))) {
                \Session::flash('middle_popup', 'Ops! Não foi possivel enviar a capa.');
                \Session::flash('popup_style', 'danger');
            } else {
                $capa = $newFileNameCapa;
                Storage::disk('public_uploads')->delete('trilhas/capas/' . $request->get('capa_atual'));
            }
        }


        $trilha->update([
            'user_id'        => Auth::user()->id,
            'instituicao_id' => $request->instituicao_id,
            'ciclo_id'       => $request->ciclo_id,
            'cicloetapa_id'  => $request->cicloetapa_id,
            'disciplina_id'  => $request->disciplina_id,
            'visibilidade_id'=> $request->visibilidade,
            'escola_id'      => $request->escola_id,
            'tag'            => $request->tag,
            'titulo'         => $request->titulo,
            'capa'           => $capa,
            'descricao'      => $request->descricao
        ]);

        $trilha->cursos()->sync($request->get('curso_id'));

        $trilhasCurso = TrilhasCurso::where('trilha_id', '=', $idTrilha)->get();
        foreach ($trilhasCurso as $trilhaCurso) {
            $trilhaCurso->update([
                'ordem'  => $request->get("curso_id_ordem_{$trilhaCurso->curso_id}")
            ]);
        }

        return redirect()->route('gestao.trilhas.listar')->with('message', 'Trilha atualizada com sucesso!');
    }

    public function destroy($idTrilha)
    {
        $trilha = Trilha::find($idTrilha);
        #Codigos para serem refinados e definidos como será a exclusao das trilhas
        if (!$trilha) {
            return redirect()->back()->withErrors(['Trilha não encontrada!']);
        }

        try{
            #Codigos para serem refinados e definidos como será a exclusao das trilhas
            #varificar se irá apagar a capa do curso no servidor
            // if($trilhas_cursos){
            //     $trilhas_cursos->delete();
            // }

            // if($trilhas_matriculas){
            //     $trilhas_matriculas->delete();
            // }

            // if($ciclo_etapas_permissoes){
            //     $ciclo_etapas_permissoes->delete();
            // }

            // if (Storage::disk('public_uploads')->has('trilhas/capas/' . $trilha->capa)) {
            //     Storage::disk('public_uploads')->delete('trilhas/capas/' . $trilha->capa);
            // }
            //A trilha não sera deletada e sim update do status para inativo

            $delTrilha = $trilha->update([
                'status_id' => 2
            ]);
            $trilha->delete();
            if($delTrilha)
                return redirect()->back()->with('message', 'Registro excluido com sucesso!');
            else
                return redirect()->back()->with('error', 'Erro para excluir registro!');

        } catch (\Exception $exception) {
            return response()->json(['error' => 'Erro para excluir trilha. ' . $exception->getMessage()]);
        }
    }

    #Valida o input de trilhas
    protected function validateTrilha(Request $request)
    {
        $validator = $this->validate($request, [
            'titulo' => 'required',
            'ciclo_id' => 'required',
            'cicloetapa_id' => 'required',
            'disciplina_id'   => 'required',
        ], [
            'required' => 'O campo :attribute é obrigatório',
        ], [
            'titulo'      => 'titulo',
            'ciclo_id'     => 'etapa',
            'cicloetapa_id'  => 'Ano',
            'disciplina_id'  => 'componente curricular',
        ]);

        return $validator;
    }
}
