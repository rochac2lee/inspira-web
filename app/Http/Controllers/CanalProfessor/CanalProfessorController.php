<?php

namespace App\Http\Controllers\CanalProfessor;

use Auth;
use Redirect;
use Session;
use Carbon\Carbon;

use App\User;

use App\AvaliacaoInstrutor;
use App\DuvidaProfessor;

use App\Escola;
use App\Conteudo;
use App\Metrica;
use App\ComentarioDuvidaProfessor;
use App\AlunoTurma;
use App\Turma;

use App\Aplicacao;
use App\Categoria;

use App\Curso;
use App\Aula;
use App\ConteudoAula;
use App\Matricula;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class CanalProfessorController extends Controller
{

    public function index($idProfessor)
    {
        $professor = User::find($idProfessor);

        $podcasts = Conteudo::where('user_id', $idProfessor)
            ->where('tipo', 2)->get();
        $transmissoes = Conteudo::where('user_id', $idProfessor)
            ->where('tipo', 5)->get();

        //echo '<pre>'; print_r($conteudos); die;

        return view('pages.canal_professor.index', compact('podcasts', 'transmissoes', 'professor', 'idProfessor'));
    }


    public function biblioteca($idProfessor)
    {
        $professor = User::find($idProfessor);

        $conteudos = Conteudo::where('user_id', $idProfessor)->get();

        $aplicacoes = Aplicacao::where('user_id', $idProfessor)->get();

        $videos = $conteudos->filter(function ($c) {
            return $c->tipo == 3;
        });

        $slides = $conteudos->filter(function ($c) {
            return ($c->tipo == 4 && (strpos($c->conteudo, ".ppt") !== false || strpos($c->conteudo, ".pptx") !== false));
        });

        $documentos = $conteudos->filter(function ($c) {
            return $c->tipo == 1 || ($c->tipo == 4 && (strpos($c->conteudo, ".ppt") == false && strpos($c->conteudo, ".pptx") == false));
        });

        $apostilas = $conteudos->filter(function ($c) {
            return $c->tipo == 11;
        });

        return view('pages.canal_professor.biblioteca')->with(compact('conteudos', 'videos', 'slides', 'apostilas', 'documentos', 'aplicacoes', 'professor', 'idProfessor'));
    }

    public function avaliacoes($idProfessor)
    {
        $professor = User::find($idProfessor);

        $avaliacoes = DB::table('avaliacoes_instrutor')
            ->join('users', 'avaliacoes_instrutor.user_id', '=', 'users.id')
            ->select(DB::raw('users.id as id,
                users.name as nome,
                users.img_perfil as img_perfil,
                avaliacoes_instrutor.descricao as avaliacao,
                avaliacoes_instrutor.avaliacao as estrelas'))
            ->where('avaliacoes_instrutor.instrutor_id', '=', $idProfessor)
            ->get();

        return view('pages.canal_professor.avaliacoes', compact('avaliacoes', 'professor', 'idProfessor'));
    }

    public function duvidas($idProfessor)
    {
        $professor = User::with('escola')->find($idProfessor);

        if($professor == null)
        {
            return redirect()->back()->withErrors("Professor não encontrado!");
        }
        else if(strtoupper($professor->permissao) != "P" && strtoupper($professor->permissao) != "G" && strtoupper($professor->permissao) != "Z")
        {
            return redirect()->back()->withErrors("Professor não encontrado!");
        }

        if(AvaliacaoInstrutor::where('instrutor_id', '=', $idProfessor)->avg('avaliacao') > 0)
            $avaliacaoInstrutor = AvaliacaoInstrutor::where('instrutor_id', '=', $idProfessor)->avg('avaliacao');
        else
            $avaliacaoInstrutor = '-';

        $duvidas = DuvidaProfessor::where([['professor_id', '=', $idProfessor]])
        ->orderBy('status', 'asc')
        ->orderBy('created_at', 'desc')
        ->get();
        // ->sortBy('status');

        foreach ($duvidas as $duvida)
        {
            $duvida->qt_comentarios = ComentarioDuvidaProfessor::where([['duvida_id', '=', $duvida->id]])->count();
        }

        return view('pages.canal_professor.duvidas')->with(compact('duvidas', 'professor', 'avaliacaoInstrutor', 'idProfessor'));
    }

    public function duvida($idProfessor, $idDuvida)
    {
        $duvida = DuvidaProfessor::with('professor', 'user', 'comentarios')->has('professor')->has('user')->find($idDuvida);

        if($duvida == null)
        {
            return redirect()->route('professor.duvidas', $idProfessor)->withErrors("Dúvida não encontrada!");
        }

        if(AvaliacaoInstrutor::where('instrutor_id', '=', $duvida->professor->id)->avg('avaliacao') > 0)
            $avaliacaoInstrutor = AvaliacaoInstrutor::where('instrutor_id', '=', $duvida->professor->id)->avg('avaliacao');
        else
            $avaliacaoInstrutor = '-';

        $turma = Turma::where([['user_id', '=', Auth::user()->id]])->first();

        return view('pages.canal_professor.duvida-respostas')->with(compact('turma', 'duvida', 'avaliacaoInstrutor', 'idProfessor'));
    }

}
