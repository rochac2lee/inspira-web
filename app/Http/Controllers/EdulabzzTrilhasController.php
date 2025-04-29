<?php

namespace App\Http\Controllers;


use App\Models\Curso;
use App\Models\Escola;
use App\Models\AlunoCicloEtapa;
use App\Models\CicloEtapa;
use App\Models\CursoCompleto;
use App\Models\ConteudoCompleto;
use App\Entities\Trilhas\Trilha;
use App\Entities\Trilhas\TrilhasCurso;
use App\Entities\Trilhas\TrilhasMatricula;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EdulabzzTrilhasController extends Controller
{
    public function index(Request $request)
    {

        $trilhas = Trilha::query();

        $tem_pesquisa = $request->input('pesquisa');
        if($tem_pesquisa == ''){
            $tem_pesquisa = false;
        }

        $trilhas->when($tem_pesquisa, function ($query) use($tem_pesquisa){
            return $query
                ->where('titulo', 'like', '%' . $tem_pesquisa . '%')
                ->where('escola_id', Auth::user()->escola_id)
                ->where('status_id', '!=', 2)
                ->orWhere('descricao', 'like', '%' . $tem_pesquisa . '%');
        });

        $idUser = Auth::user()->id;



        // Todas as trilhas da escola e o progresso do aluno em cada uma
        $trilhas = $trilhas
            ->select('trilhas.*', 'trilhas_matriculas.id as trilha_matricula', DB::Raw(' IFNULL( trilhas_matriculas.qtd_concluido , 0 ) as qtd_concluido'))
            ->where(function ($q){
                $q->where('escola_id', Auth::user()->escola_id);
                $q->orWhere('trilhas.instituicao_id', 5);
            });

        if(Auth::user()->permissao=='A'){
            /// select para fazer a regra de negocio quais alunos podem ver as trilhas
            $cicloIdTurma = AlunoCicloEtapa::where('user_id',Auth::user()->id)->first();
            if(isset($cicloIdTurma->ciclo_id)){
                $cicloIdTodos = CicloEtapa::where('ciclo_id',$cicloIdTurma->ciclo_id)->where('titulo','Todos')->first();
                if(isset($cicloIdTodos->id))
                {
                    $cicloIdTurma = $cicloIdTurma->ciclo_etapa_id;
                    $cicloIdTodos = $cicloIdTodos->id;

                    $dadosCiclo =[
                        'cicloIdTurma' => $cicloIdTurma,
                        'cicloIdTodos' => $cicloIdTodos,
                    ];

                    $trilhas = $trilhas->where(function($q) use ($dadosCiclo){
                        $q->orWhere('cicloetapa_id',$dadosCiclo['cicloIdTurma'])
                            ->orWhere('cicloetapa_id',$dadosCiclo['cicloIdTodos'])
                            ->orWhere('cicloetapa_id',22);
                    });
                }
            }
            else
            {
                $trilhas->where('cicloetapa_id',22);
            }
        }
        $trilhas = $trilhas->where('status_id', '!=', 2)
            ->leftJoin('trilhas_matriculas', function ($join) use($idUser) {
                $join->where('trilhas_matriculas.user_id', '=', $idUser)
                    ->on('trilhas_matriculas.trilha_id', '=', 'trilhas.id');
            })
            /*ALTERADO TMPORARIAMENTE POR F&R
                PARA ACABAR COM DUPLICIDADE NA LISTA
            */
            ->orderBy(DB::Raw('- trilhas.ordem'),'DESC')
            /*FIM*/

            ->orderBy('trilhas.id', 'DESC')

            /*ALTERADO TMPORARIAMENTE POR F&R
                PARA ACABAR COM DUPLICIDADE NA LISTA
            */
            ->groupBy('trilhas.id')
            /*FIM*/
            ->get();

        foreach($trilhas as $trilha)
        {
            // Aluno está matriculado na trilha?
            $trilhaMatricula = TrilhasMatricula::where([
                ['user_id', '=', Auth::user()->id],
                ['trilha_id', '=', $trilha->id],
            ])->first();

            $trilha->matriculado = false;
            $trilha->progresso = 0;

            if ($trilhaMatricula) {
                $trilha->matriculado = $trilhaMatricula->id;
                $trilha->progresso = $trilhaMatricula->progresso;
            }
        }

        return view('pages.trilhas.aluno.index', compact('trilhas'));
    }

    public function progresso(Request $request, $idTrilha)
    {

        $trilha = Trilha::find($idTrilha);

        $tem_pesquisa = $request->input('pesquisa');
        if($tem_pesquisa==''){
            $tem_pesquisa = false;
        }

        // Todos os cursos da trilha
        $cursos = TrilhasCurso::where('trilha_id', $idTrilha)
            ->orderBy('ordem', 'ASC')
            ->get();

        // Aluno está matriculado na trilha?
        $trilhaMatricula = TrilhasMatricula::where([
            ['user_id', '=', Auth::user()->id],
            ['trilha_id', '=', $idTrilha],
        ])->first();

        $trilha->matriculado = false;
        $trilha->progresso = 0;

        if ($trilhaMatricula) {
            $trilha->matriculado = $trilhaMatricula->id;
            $trilha->progresso = $trilhaMatricula->progresso;
        }

        // Todos os cursos concluídos pelo aluno
        $cursosConcluidos = CursoCompleto::where('user_id', '=', Auth::user()->id)
            ->pluck('curso_id')
            ->toArray();

        // Todas as aulas concluídas pelo aluno
        $aulasConcluidas = ConteudoCompleto::where('user_id', '=', Auth::user()->id)
            ->groupBy('curso_id')
            ->pluck('curso_id')
            ->toArray();

        // Verifica o progresso do aluno em cada curso
        $atual = null;
        foreach ($cursos as $curso) {
            $curso->concluido = 0;
            $curso->iniciado = 0;

            // Verifica se o curso já foi concluido
            if (in_array($curso->curso_id, $cursosConcluidos)) {
                $curso->concluido = 1;
                $curso->iniciado = 1;
                // Verifica se o curso já foi iniciado
            } elseif (in_array($curso->curso_id, $aulasConcluidas)) {
                $curso->iniciado = 1;
            }

            // Verifica se é o curso atual da trilha
            $curso->atual = 0;
            if (!$curso->concluido && !$atual) {
                $curso->atual = 1;
                $atual = true;
            }
        }

        // Filtro é realizado no final para não perder os bloqueios realizados acima
        if ($tem_pesquisa) {
            $strPesquisa = Input::get('pesquisa');
            $cursos = $cursos->filter(function($curso) use($strPesquisa)
            {
                return ((strpos(mb_strtolower($curso->curso->titulo), mb_strtolower($strPesquisa)) > -1) ||
                    (strpos(mb_strtolower($curso->curso->descricao), mb_strtolower($strPesquisa)) > -1));
            });
        }

        return view('pages.trilhas.aluno.progresso', compact('trilha', 'cursos'));
    }

    public function matricular($idTrilha)
    {
        $trilhasCurso = TrilhasCurso::
        where('trilha_id', $idTrilha)
            ->pluck('curso_id')
            ->toArray();

        $qtdCursos = count($trilhasCurso);

        $qtdConcluido = CursoCompleto::
        where('user_id', '=', Auth::user()->id)
            ->whereIn('curso_id', $trilhasCurso)
            ->count();

        // Calcula o progresso atual do aluno (O aluno pode ter cursado algum curso antes de iniciar a trilha)
        $progresso = ceil((100*$qtdConcluido)/$qtdCursos);

        TrilhasMatricula::create([
            'trilha_id'     => $idTrilha,
            'user_id'       => Auth::user()->id,
            'progresso'     => $progresso,
            'qtd_concluido' => $qtdConcluido
        ]);

        return redirect()->route('trilhas.progresso', ['idTrilha' => $idTrilha])->with('message', 'Trilha iniciada com sucesso!');
    }

    public function abandonar($idTrilha)
    {

        $trilhaMatricula = TrilhasMatricula::find($idTrilha);

        if ($trilhaMatricula) {
            $trilhaMatricula->delete();
        }

        return redirect()->route('trilhas.listar')->with('message', 'Trilha abandonada com sucesso!');
    }
}
