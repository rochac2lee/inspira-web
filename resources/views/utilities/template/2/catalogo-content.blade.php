<style>body{padding-top: 65px !important;}
.rounded-top{border-top-left-radius:8px!important;border-top-right-radius:8px!important;}
.rounded-bottom{border-bottom-left-radius:8px!important;border-bottom-right-radius:8px!important;}

.btt{
    cursor: default;
    color: #FFFFFF !important;
}
</style>
@if(!Auth::check())
    <style>
        body{padding-top: 92px !important;}
    </style>
@endif

<main role="main">
    <div class="d-block template-2">

    <!-- Ace Responsive Menu -->
    <nav class="menu_horizontal">
        <div class="menu-toggle">
            <button type="button" id="menu-btn">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <ul id="respMenu" class="opet-responsive-menu" data-menu-style="horizontal">
            <li>
                <a href="#">
                    <span>Educação Infantil</span>
                </a>
                <ul>
                    {{-- <div class="submenu">
                        <li>
                            <a href="#" class="titulo_submenu btt">Anos</a>
                        </li>
                        <li><a href="#" class="btt">Infantil 1</a></li>
                        <li><a href="#" class="btt">Infantil 2</a></li>
                        <li><a href="#" class="btt">Infantil 3</a></li>
                        <li><a href="#" class="btt">Infantil 4</a></li>
                        <li><a href="#" class="btt">Infantil 5</a></li>
                    </div> --}}
                    <div class="submenu submenu-border-right">
                        <li>
                            <a href="#" class="titulo_submenu">Conteúdos</a>
                        </li>
                        <li><a href="{{route('gestao.trilhas.listar')}}">Trilhas de Aprendizagem</a></li>
                        <li><a href="{{ route('gestao.cursos') }}">Cursos</a></li>
                        <li><a href="{{ route('gestao.banco-imagens.index') }}">Banco de Imagens</a></li>
                        <li><a href="{{ route('gestao.biblioteca') }}?catalogo=9">Avaliações</a></li>
                    </div>
                    <div class="submenu submenu-border-right">
                        <li><a href="#" class="titulo_submenu">Material Didático</a></li>
                        <li><a href="{{ route('gestao.livro-lista') }}">{{ucfirst($langDigital)}} Digital</a></li>
                        <li><a href="{{ route('gestao.plano-aulas.listar') }}">Plano de Aula</a></li>
                        <li><a target="_blank" href="https://www-homol.opetinspira.com.br/corpohumano/">Sistemas do Corpo Humano</a></li>
                        <li><a target="_blank" href="https://www-homol.opetinspira.com.br/sistemasolar/">Planetário </a></li>
                        {{-- <li><a href="#">Mais Brasil</a></li>
                        <li><a href="#">Tabela periódica</a></li> --}}
                    </div>
                </ul>
            </li>

            <li>
                <a href="#">
                    <span>Ensino Fundamental Anos Iniciais</span>
                </a>
                <ul>
                  {{--   <div class="submenu">
                        <li>
                            <a href="#" class="titulo_submenu btt">Anos</a>
                        </li>
                        <li><a href="#" class="btt">1º ano</a></li>
                        <li><a href="#" class="btt">2º ano</a></li>
                        <li><a href="#" class="btt">3º ano</a></li>
                        <li><a href="#" class="btt">4º ano</a></li>
                        <li><a href="#" class="btt">5º ano</a></li>
                    </div> --}}
                    <div class="submenu submenu-border-right">
                        <li>
                            <a href="#" class="titulo_submenu">Conteúdos</a>
                        </li>
                        <li><a href="{{route('gestao.trilhas.listar')}}">Trilhas de Aprendizagem</a></li>
                        <li><a href="{{ route('gestao.cursos') }}">Cursos</a></li>
                        <li><a href="{{ route('gestao.banco-imagens.index') }}">Banco de Imagens</a></li>
                        <li><a href="{{ route('gestao.biblioteca') }}?catalogo=9">Avaliações</a></li>
                    </div>
                    <div class="submenu submenu-border-right">
                        <li>
                            <a href="#" class="titulo_submenu">Material Didático</a>
                        </li>
                        <li><a href="{{ route('gestao.livro-lista') }}">{{ucfirst($langDigital)}} Digital</a></li>
                        <li><a href="{{ route('gestao.plano-aulas.listar') }}">Plano de Aula</a></li>
                        <li><a target="_blank" href="https://www-homol.opetinspira.com.br/corpohumano/">Sistemas do Corpo Humano</a></li>
                        <li><a target="_blank" href="https://www-homol.opetinspira.com.br/sistemasolar/">Planetário </a></li>
                        {{-- <li><a href="#">Mais Brasil</a></li>
                        <li><a href="#">Tabela periódica</a></li> --}}
                    </div>
                </ul>
            </li>

            <li>
                <a href="#">
                    <span>Ensino Fundamental Anos Finais</span>
                </a>
                <ul>
                   {{--  <div class="submenu">
                        <li>
                            <a href="#" class="titulo_submenu btt">Anos</a>
                        </li>
                        <li><a href="#" class="btt">6º ano</a></li>
                        <li><a href="#" class="btt">7º ano</a></li>
                        <li><a href="#" class="btt">8º ano</a></li>
                        <li><a href="#" class="btt">9º ano</a></li>
                    </div> --}}
                    <div class="submenu submenu-border-right">
                        <li>
                            <a href="#" class="titulo_submenu">Conteúdos</a>
                        </li>
                        <li><a href="{{route('gestao.trilhas.listar')}}">Trilhas de Aprendizagem</a></li>
                        <li><a href="{{ route('gestao.cursos') }}">Cursos</a></li>
                        <li><a href="{{ route('gestao.banco-imagens.index') }}">Banco de Imagens</a></li>
                        <li><a href="{{ route('gestao.biblioteca') }}?catalogo=9">Avaliações</a></li>
                    </div>
                    <div class="submenu submenu-border-right">
                        <li>
                            <a href="#" class="titulo_submenu">Material didático</a>
                        </li>
                        <li><a href="{{ route('gestao.livro-lista') }}">{{ucfirst($langDigital)}} Digital</a></li>
                        <li><a href="{{ route('gestao.plano-aulas.listar') }}">Plano de Aula</a></li>
                        <li><a target="_blank" href="https://www-homol.opetinspira.com.br/corpohumano/">Sistemas do Corpo Humano</a></li>
                        <li><a target="_blank" href="https://www-homol.opetinspira.com.br/sistemasolar/">Planetário </a></li>
                        {{-- <li><a href="#">Mais Brasil</a></li>
                        <li><a href="#">Tabela periódica</a></li> --}}
                    </div>
                </ul>
            </li>
            <li>
                <a href="#">
                    <span>Ensino Médio</span>
                </a>
                <ul>
                    {{-- <div class="submenu">
                        <li>
                            <a href="#" class="titulo_submenu btt">Anos</a>
                        </li>
                        <li><a href="#" class="btt">1º ano</a></li>
                        <li><a href="#" class="btt">2º ano</a></li>
                        <li><a href="#" class="btt">3º ano</a></li>
                        <li><a href="#" class="btt">4º ano</a></li>
                        <li><a href="#" class="btt">5º ano</a></li>
                    </div> --}}
                    <div class="submenu submenu-border-right">
                        <li>
                            <a href="#" class="titulo_submenu">Conteúdos</a>
                        </li>
                        <li><a href="{{route('gestao.trilhas.listar')}}">Trilhas de Aprendizagem</a></li>
                        <li><a href="{{ route('gestao.cursos') }}">Cursos</a></li>
                        <li><a href="{{ route('gestao.banco-imagens.index') }}">Banco de Imagens</a></li>
                        <li><a href="{{ route('gestao.biblioteca') }}?catalogo=9">Avaliações</a></li>
                    </div>
                    <div class="submenu submenu-border-right">
                        <li>
                            <a href="#" class="titulo_submenu">Material didático</a>
                        </li>
                        <li><a href="{{ route('gestao.livro-lista') }}">{{ucfirst($langDigital)}} Digital</a></li>
                        <li><a href="{{ route('gestao.plano-aulas.listar') }}">Plano de Aula</a></li>
                        <li><a target="_blank" href="https://www-homol.opetinspira.com.br/corpohumano/">Sistemas do Corpo Humano</a></li>
                        <li><a target="_blank" href="https://www-homol.opetinspira.com.br/sistemasolar/">Planetário </a></li>
                        {{-- <li><a href="#">Mais Brasil</a></li>
                        <li><a href="#">Tabela periódica</a></li> --}}
                    </div>
                </ul>
            </li>
            {{-- <li>
                <a href="#">
                    <span>Família</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span>Docentes</span>
                </a>
            </li> --}}
        </ul>
    </nav>


    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{config('app.cdn')}}/images/slide_catalogo1.png" />
            </div>
            <div class="carousel-item">
                <img src="{{config('app.cdn')}}/images/slide_catalogo2.png" />
            </div>
            <div class="carousel-item">
                <img src="{{config('app.cdn')}}/images/slide_catalogo3.png" />
            </div>
        </div>
    </div>


    <!-- // CATEGORIAS //-->
    <div class="d-block d-md-flex justify-content-between categorias pt-5 rounded-top">
        <div>
            <a href="{{ route('gestao.livro-lista') }}">
                <img src="{{config('app.cdn')}}/images/icones/livro-didatico.svg" />
                <span>LIVRO <br> DIDÁTICO</span>
            </a>
        </div>
        <div>
            <!--{{ route('agenda.index') }} -->
            <a target="blank" href="https://iscoolapp.net/#/main">
                <img src="{{config('app.cdn')}}/images/icones/agenda.svg" />
                <span>AGENDA</span>
            </a>
        </div>
        <div>
            <a target="blank" href="http://app.provafacilnaweb.com.br/#/login">
                <img src="{{config('app.cdn')}}/images/icones/avaliacoes.svg" />
                <span>AVALIAÇÕES</span>
            </a>
        </div>
        <div>
            <a href="{{ route('gestao.banco-imagens.index')}}">
                <img src="{{config('app.cdn')}}/images/icones/conteudo-de-imagens.svg" />
                <span>IMAGENS</span>
            </a>
        </div>
        <div>
            <a href="{{ route('gestao.biblioteca') }}?catalogo=3">
                <img src="{{config('app.cdn')}}/images/icones/icone-video.svg" />
                <span>VÍDEO</span>
            </a>
        </div>
        <div>
            <a href="{{ route('gestao.biblioteca') }}?catalogo=2">
                <img src="{{config('app.cdn')}}/images/icones/audio.svg" />
                <span>ÁUDIO</span>
            </a>
        </div>
        <div>
            <a href="{{ route('gestao.biblioteca') }}?catalogo=8">
                <img src="{{config('app.cdn')}}/images/icones/quizze.svg" />
                <span>QUIZ</span>
            </a>
        </div>
    </div>
    
    <div class="d-flex justify-content-between categorias pt-5 pb-5 rounded-bottom">
        <div>
            <a href="{{ route('gestao.biblioteca') }}?catalogo=22">
                <img src="{{config('app.cdn')}}/images/icones/documento-oficial.svg" />
                <span>DOCUMENTO OFICIAL</span>
            </a>
        </div>
        {{-- <div>
            <a href="{{ route('gestao.biblioteca') }}">
                <img src="{{config('app.cdn')}}/images/icones/jogos-atividades.svg" />
                <span>JOGOS E <br> ATIVIDADES</span>
            </a>
        </div> --}}
        <div>
            <a href="https://escola.britannica.com.br/" target="_blank">
                <img src="{{config('app.cdn')}}/images/icones/britannica-escola.svg" />
                <span>BRITANNICA <br> ESCOLA</span>
            </a>
        </div>
        <div>
            <a href="https://editora-uniopet.moodle.crossknowledge.com/ead/login/index.php" target="_blank">
                <img src="{{config('app.cdn')}}/images/icones/ead.svg" />
                <span>EAD</span>
            </a>
        </div>
        <div>
            <a target="_blank" href="https://www-homol.opetinspira.com.br/sistemasolar/">
                <img src="{{config('app.cdn')}}/images/icones/plantetario.png" />
                <span>PLANETÁRIO</span>
            </a>
        </div>
        <div>
            <a target="_blank" href="https://www-homol.opetinspira.com.br/corpohumano/">
                <img src="{{config('app.cdn')}}/images/icones/atlas-corpo-humano.png" />
                <span>SISTEMAS DO CORPO HUMANO</span>
            </a>
        </div>
        <div>
            <a href="#stop()">
                <img src="{{config('app.cdn')}}/images/icones/indica.svg" />
                <span>INDICA</span>
            </a>
        </div>
    </div>

        {{-- LINKS ANTIGOS --}}
        {{-- <div class="container fast-access">
            <div class="row justify-content-center">
                <nav>
                    <div class="col-xs-8">
                    <div class="box">
                        <div class="col-xs-12">
                        <ul class="itens-catalogo">
                            <li>
                                <figure>
                                    <img src="{{config('app.cdn')}}/images/icones/correction.svg" />
                                    <figcaption>Correção de atividades</figcaption>
                                </figure>
                            </li>
                            <li>
                                <figure>
                                    <img src="{{config('app.cdn')}}/images/icones/question.svg" />
                                    <figcaption>Dúvidas dos alunos</figcaption>
                                </figure>
                            </li>
                            <li>
                                <a href="{{config('app.url')}}/teste-nivelamento">
                                    <figure>
                                        <img src="{{config('app.cdn')}}/images/icones/graph.svg" />
                                        <figcaption>Teste de nivelamento</figcaption>
                                    </figure>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('glossario.index') }}">
                                    <figure>
                                        <img src="{{config('app.cdn')}}/images/icones/hashtag.svg" />
                                        <figcaption>Glossário</figcaption>
                                    </figure>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('biblioteca') }}">
                                    <figure>
                                        <img src="{{config('app.cdn')}}/images/icones/books-stack-of-three.svg" />
                                        <figcaption>Biblioteca</figcaption>
                                    </figure>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('gestao.missoes.listar') }}">
                                    <figure>
                                        <img src="{{config('app.cdn')}}/images/icones/surface1.svg" />
                                        <figcaption>Gamificação</figcaption>
                                    </figure>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('gestao.ajuda.artigos') }}">
                                    <figure>
                                        <img src="{{config('app.cdn')}}/images/icones/question (1).svg" />
                                        <figcaption>Ajuda</figcaption>
                                    </figure>
                                </a>
                            </li>
                        </ul>
                        </div>
                        
                        @if(Auth::user() && Auth::user()->permissao !== "A")

                        <div class="col-xs-12">
                            <ul class="itens-catalogo">
                                <li>
                                    <a href="https://enem.inep.gov.br/" target="_blank">
                                        <figure>
                                            <img src="{{config('app.cdn')}}/images/icones/enem.svg" />
                                            <figcaption>ENEM</figcaption>
                                        </figure>
                                    </a>
                                </li>
                                <li>
                                    <a href="http://ideb.inep.gov.br/" target="_blank">
                                        <figure>
                                            <img src="{{config('app.cdn')}}/images/icones/line-chart.svg" />
                                            <figcaption>IDEB</figcaption>
                                        </figure>
                                    </a>
                                </li>
                                <li>
                                    <a href="http://portal.inep.gov.br/pisa" target="_blank">    
                                        <figure>
                                            <img src="{{config('app.cdn')}}/images/icones/world.svg" />
                                            <figcaption>PISA</figcaption>
                                        </figure>
                                    </a>
                                </li>
                                <li>
                                    <a href="http://portal.inep.gov.br/educacao-basica/saeb" target="_blank">
                                        <figure>
                                            <img src="{{config('app.cdn')}}/images/icones/brazil-map.svg" />
                                            <figcaption>SAEB</figcaption>
                                        </figure>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.mec.gov.br/" target="_blank">
                                        <figure>
                                            <img src="{{config('app.cdn')}}/images/icones/students-cap.svg" />
                                            <figcaption>MEC</figcaption>
                                        </figure>
                                    </a>
                                </li>
                                <li>
                                    <figure>
                                        <img src="{{config('app.cdn')}}/images/icones/indica.svg" />
                                        <figcaption>INDICA</figcaption>
                                    </figure>
                                </li>
                                <li>
                                    <a href="https://escola.britannica.com.br" target="_blank">
                                        <figure>
                                            <img src="{{config('app.cdn')}}/images/icones/britannicaescola.svg" />
                                            <figcaption>BRITANICA ESCOLA</figcaption>
                                        </figure>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @endif

                    </div>
                    </div>

                    <div class="col-xs-4">
                    <div class="box box-2">
                        <div class="col-xs-12">
                        <ul class="row itens-catalogo">
                            <li class="col-xs-4">
                                <a href="{{config('app.url')}}/agenda">
                                    <figure>
                                        <img src="{{config('app.cdn')}}/images/icones/agenda.svg" />
                                        <figcaption>Agenda</figcaption>
                                    </figure>
                                </a>
                            </li>
                            <li class="col-xs-4">
                                <a href="{{ route('gestao.escola.mural', ['escola_id' => (Auth::check() ? Auth::user()->escola_id : 1)]) }}">
                                    <figure>
                                        <img src="{{config('app.cdn')}}/images/icones/murais.svg" />
                                        <figcaption>Murais</figcaption>
                                    </figure>
                                </a>
                            </li>
                            <li class="col-xs-4">
                                <a href="{{ route('gestao.estatisticas.index') }}"> 
                                    <figure>
                                        <img src="{{config('app.cdn')}}/images/icones/pie-chart.svg" />
                                        <figcaption>Estatísticas</figcaption>
                                    </figure>
                                </a>
                            </li>

                            @if(Auth::user() && Auth::user()->permissao !== "A")

                                <li class="col-xs-4">
                                    <a href="{{ route('gestao.baralhos.listar') }}">
                                        <figure>
                                            <img src="{{config('app.cdn')}}/images/icones/poker-playing-cards.svg" />
                                            <figcaption>Cards Creator</figcaption>
                                        </figure>
                                    </a>
                                </li>
                                <li class="col-xs-4">
                                    <a href="{{ route('gestao.Áudios.listar') }}">
                                        <figure>
                                            <img src="{{config('app.cdn')}}/images/icones/castcreator.svg" />
                                            <figcaption>Cast Creator</figcaption>
                                        </figure>
                                    </a>
                                </li>
                                <li class="col-xs-4">
                                    <figure>
                                        <img src="{{config('app.cdn')}}/images/icones/game.svg" />
                                        <figcaption>Hub Creator</figcaption>
                                    </figure>
                                </li>

                            @endif

                        </ul>
                        </div>
                    </div>
                    </div>
                </nav>
            </div>
        </div> --}}

    </div>
</main>