<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /// para personalizar a view da paginação
        Paginator::defaultView('vendor/pagination/bootstrap-4');
        Paginator::defaultSimpleView('vendor/pagination/bootstrap-4');


        //// variaveis de ambiente do EDULAZZ
        /// RETIRAR após troca de roteiros e trilhas
        #Função para passar as nomenclaturas globais
        view()->composer('*', function($view){

                $langCurso = "roteiro";
                $langAula = "tema";
                $langAulaP = "Temas";
                $langCursoP = "Roteiros";
                $langAulaG = "o Tema";
                $langBaralho = "coleção de cartas";
                $langBaralhoG = "a coleção de carta";
                $langPlaylist = "lista de áudio";
                $langBancoDeQuestoes = "banco de itens";
                $langQuestoes = "itens";
                $langResposta = "alternativa";
                $langRespostaP = "Alternativas";
                $langQuestao = "item";
                $langDigital = "livro";
                $langDigitalG = "o livro";
            $view->with('langRespostaP', $langRespostaP);
            $view->with('langCursoP', $langCursoP);
            $view->with('langBaralhoG', $langBaralhoG);
            $view->with('langAulaG', $langAulaG);
            $view->with('langAulaP', $langAulaP);
            $view->with('langCurso', $langCurso);
            $view->with('langAula', $langAula);
            $view->with('langBaralho', $langBaralho);
            $view->with('langPlaylist', $langPlaylist);
            $view->with('langBancoDeQuestoes', $langBancoDeQuestoes);
            $view->with('langQuestoes', $langQuestoes);
            $view->with('langResposta', $langResposta);
            $view->with('langQuestao', $langQuestao);
            $view->with('langDigital', $langDigital);
        });
    }
}
