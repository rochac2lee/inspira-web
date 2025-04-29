<?php
namespace App\Services\Fr;
use DB;
use Validator;
use App\Services\Fr\LivroService;
use App\Services\Fr\BibliotecaService;
use App\Services\Fr\QuizService;

class BuscaGeralService {

    public function __construct(QuestaoService $questaoService, LivroService $livroService, BibliotecaService $bibliotecaService, QuizService $quizService){
        $this->livroService = $livroService;
        $this->bibliotecaService = $bibliotecaService;
        $this->quizService = $quizService;
        $this->questaoService = $questaoService;
    }

    private function escopoQueryBusca($sql, $porPagina){

        return $sql->selectRaw("conteudos.titulo, conteudos.id, conteudos.tipo, conteudos.colecao_livro_id ,disciplinas.titulo as disciplina, ciclo_etapas.titulo as etapa, ciclos.titulo as ciclo")
                ->paginate($porPagina);

    }

    private function buscaQuizzes($porPagina,$busca)
    {
        return $this->quizService->getLista($porPagina, ['biblioteca'=>1], $busca);
    }

    public function buscar($busca, $tipo){
        $porPagina= 50;
        $retorno = [
            'dados'         => array(),
            'livros'        => 0,
            'jogos'         => 0,
            'videos'        => 0,
            'apresentacoes' => 0,
            'audios'        => 0,
            'imagens'       => 0,
            'provas'        => 0,
            'simuladores'   => 0,
            'questoes'      => 0,
        ];

        $livro = $this->livroService->scopoQueryLivro(null, null, $busca);
        $livro = $this->escopoQueryBusca($livro, $porPagina, 21);
        $retorno['livros'] = number_format($livro->total(),0,'','.');

        $jogos = $this->bibliotecaService->scopoQueryConteudo(103, 1, null, $busca );
        $jogos = $this->escopoQueryBusca($jogos, $porPagina);
        $retorno['jogos'] = number_format($jogos->total(),0,'','.');

        $videos = $this->bibliotecaService->scopoQueryConteudo(3, 1, null, $busca );
        $videos = $this->escopoQueryBusca($videos, $porPagina);
        $retorno['videos'] = number_format($videos->total(),0,'','.');

        $apresentacoes = $this->bibliotecaService->scopoQueryConteudo(4, 1, null, $busca );
        $apresentacoes = $this->escopoQueryBusca($apresentacoes, $porPagina);
        $retorno['apresentacoes'] = number_format($apresentacoes->total(),0,'','.');

        $audios = $this->bibliotecaService->scopoQueryConteudo(2, 1, null, $busca );
        $audios = $this->escopoQueryBusca($audios, $porPagina);
        $retorno['audios'] = number_format($audios->total(),0,'','.');

        if(auth()->user()->permissao != 'A') {
            $provas = $this->bibliotecaService->scopoQueryConteudo(102, 1, null, $busca);
            $provas = $this->escopoQueryBusca($provas, $porPagina);
            $retorno['provas'] = number_format($provas->total(),0,'','.');

            $questoes = $this->questaoService->buscaGeral($porPagina, $busca);
            $retorno['questoes'] = number_format($questoes->total(),0,'','.');

        }
        else{
            $provas = [];
            $retorno['provas'] = 0;

            $questoes = [];
            $retorno['questoes'] = 0;
        }

        $imagens = $this->bibliotecaService->scopoQueryConteudo(100, 1, null, $busca );
        $imagens = $this->escopoQueryBusca($imagens, $porPagina);
        $retorno['imagens'] = number_format($imagens->total(),0,'','.');

        $simuladores = $this->bibliotecaService->scopoQueryConteudo(101, 1, null, $busca );
        $simuladores = $this->escopoQueryBusca($simuladores, $porPagina);
        $retorno['simuladores'] = number_format($simuladores->total(),0,'','.');

        $quizzes = $this->buscaQuizzes($porPagina,$busca);
        $retorno['quizzes'] = number_format($quizzes->total(),0,'','.');


        switch ($tipo) {
            case 'jogos':
                $retorno['dados'] = $jogos;
                break;
            case 'videos':
                $retorno['dados'] = $videos;
                break;
            case 'apresentacoes':
                $retorno['dados'] = $apresentacoes;
                break;
            case 'audios':
                $retorno['dados'] = $audios;
                break;
            case 'imagens':
                $retorno['dados'] = $imagens;
                break;
            case 'provas':
                $retorno['dados'] = $provas;
                break;
            case 'questoes':
                $retorno['dados'] = $questoes;
                break;
            case 'simuladores':
                $retorno['dados'] = $simuladores;
                break;
            case 'quizzes':
                $retorno['dados'] = $quizzes;
                break;
            default:
                $retorno['dados'] = $livro;
        }

        return $retorno;
    }

}
