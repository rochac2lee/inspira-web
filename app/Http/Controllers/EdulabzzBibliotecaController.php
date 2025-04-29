<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;

use App\Models\Categoria;
use App\Models\Conteudo;
use App\Models\Aplicacao;

class EdulabzzBibliotecaController extends Controller
{
    public function index()
    {
        $conteudos = Conteudo::query();

        if(Input::get('qt') == null)
            $amount = 100;
        else
            $amount = Input::get('qt');

        if(Input::get('ordem') == null)
            $ordem = "recentes";
        else
            $ordem = Input::get('ordem');

        if(Input::get('categoria') == null)
            $categoria = "";

        else
            $categoria = Input::get('categoria');

        if($categoria != null)
        {
            if(Categoria::where('titulo', '=', $categoria)->first() != null)
            {
                $categoria = Categoria::where('titulo', '=', $categoria)->first()->id;
            }
        }

        // Carregar aplicacoes
        if(Input::get('pesquisa') == null || Input::get('pesquisa') == "")
        {
            $pesquisa = null;

            if($categoria != null)
                $aplicacoes = Aplicacao::take($amount)->where([['status', '=', '1'], ['categoria', '=', $categoria]]);
            else
                $aplicacoes = Aplicacao::take($amount)->where([['status', '=', '1']]);

            if($categoria != null)
                $conteudos = Conteudo::take($amount)->where([['status', '=', '1'], ['categoria', '=', $categoria]]);
            else
                $conteudos = Conteudo::take($amount)->where([['status', '=', '1']]);
        }
        else
        {
            $aplicacoes = Aplicacao::take($amount)->where([['status', '=', '1'], ['titulo', 'like', '%' . Input::get('pesquisa') . '%']])
            ->orWhere('descricao', 'like', '%' . Input::get('pesquisa') . '%');

            $conteudos = Conteudo::take($amount)->where([['status', '=', '1'], ['titulo', 'like', '%' . Input::get('pesquisa') . '%']])
            ->orWhere('descricao', 'like', '%' . Input::get('pesquisa') . '%');
        }

        if($ordem == 'recentes')
        {
            $aplicacoes = $aplicacoes->orderBy('created_at', 'desc');

            $conteudos = $conteudos->orderBy('created_at', 'desc');

            $ordem = "Mais recentes";
        }
        elseif($ordem == 'antigos')
        {
            $aplicacoes = $aplicacoes->orderBy('created_at', 'asc');

            $conteudos = $conteudos->orderBy('created_at', 'asc');

            $ordem = "Mais antigos";
        }
        elseif($ordem == 'alfabetica')
        {
            $aplicacoes = $aplicacoes->orderBy('titulo', 'asc');

            $conteudos = $conteudos->orderBy('titulo', 'asc');

            $ordem = "Ordem Alfabética";
        }

        $aplicacoes = $aplicacoes->get();

        $conteudos = $conteudos->select('conteudos.*', 'conteudo_instituicao_escola.escola_id')
                        ->leftjoin('conteudo_instituicao_escola', 'conteudos.id', 'conteudo_instituicao_escola.conteudo_id');

        $conteudos = $conteudos->get();

        #filtra os conteudos para apenas livro digital e retorna os livros conforme o perfil do usuário
        $conteudo_livro_gigital = Conteudo::getLivroDigitalBiblioteca($conteudos);

        $conteudos = $conteudos
            ->whereNOTIn('conteudos.id',function($query){
                $query->select('conteudo_id')->from('conteudo_aula');
            });

        $conteudos = Conteudo::detalhado($conteudos);

        $audios = $conteudos->filter(function ($c) {
            return $c->tipo == 2;
        });

        $videos = $conteudos->filter(function ($c) {
            return $c->tipo == 3;
        });

        $slides = $conteudos->filter(function ($c) {
            return ($c->tipo == 4 && (strpos($c->conteudo, ".ppt") !== false || strpos($c->conteudo, ".pptx") !== false));
        });

        $documentos = $conteudos->filter(function ($c) {
            return $c->tipo == 1 || ($c->tipo == 4 && (strpos($c->conteudo, ".ppt") == false && strpos($c->conteudo, ".pptx") == false));
        });

        $transmissoes = $conteudos->filter(function ($c) {
            return $c->tipo == 5;
        });

        $uploads = $conteudos->filter(function ($c) {
            return $c->tipo == 6;
        });

        $dissertativas = $conteudos->filter(function ($c) {
            return $c->tipo == 7;
        });

        $quizzes = $conteudos->filter(function ($c) {
            return $c->tipo == 8;
        });

        $provas = $conteudos->filter(function ($c) {
            return $c->tipo == 9;
        });

        $entregaveis = $conteudos->filter(function ($c) {
            return $c->tipo == 10;
        });

        $apostilas = $conteudos->filter(function ($c) {
            return $c->tipo == 11;
        });

        $pdf = $conteudos->filter(function ($c) {
            return $c->tipo == 15;
        });

        $revista = $conteudo_livro_gigital->filter(function ($c) {
            return $c->tipo == 21;
        });

        $docoficial = $conteudos->filter(function ($c) {
            return $c->tipo == 22;
        });

        $conteudo_tipo[1] = $documentos;
        $conteudo_tipo[2] = $audios;
        $conteudo_tipo[3] = $videos;
        $conteudo_tipo[4] = $slides;
        $conteudo_tipo[5] = $transmissoes;
        $conteudo_tipo[6] = $uploads;
        $conteudo_tipo[7] = $dissertativas;
        $conteudo_tipo[8] = $quizzes;
        $conteudo_tipo[9] = $provas;
        $conteudo_tipo[10] = $entregaveis;
        $conteudo_tipo[11] = $apostilas;
        $conteudo_tipo[15] = $pdf;
        //Verificar se o aluno vai ter acesso a isso
        $conteudo_tipo[21] = $revista;
        $conteudo_tipo[22] = $docoficial;

        return view('biblioteca')->with( compact('conteudos', 'conteudo_tipo', 'aplicacoes', 'amount', 'ordem', 'categorias') );
    }

    public function getConteudoVisualizar($idConteudo)
    {
        $conteudo = Conteudo::where('id', $idConteudo)->get();
        $conteudo = Conteudo::detalhado($conteudo);
        $conteudo = $conteudo->first();

        $titulo = "
        <h2 style = 'font-size: 1.4rem;'> $conteudo->titulo •
            <span class='ml-2 text-primary'>
                    <i class='fas $conteudo->tipo_icon align-middle mr-2'></i>
                    <span class='align-middle'>$conteudo->tipo_nome</span>
            </span>
        </h2>";

        $conteudoController = new ConteudoController();

        $descricao = $conteudoController->playGetConteudoHtml($idConteudo, $conteudo);

        return response()->json(["titulo" => $titulo, "descricao" => $descricao]);
    }
}
