<style>
    .user-area .username {
        margin: 8px 0;
        display: inline-block;
        color: #fff;
        max-width: 200px;
    }
   #dropdownMenuUser{
       left: unset;
       right: 0;
   }
    #dropdownMenuUser.active{
        display: block;
    }
</style>
<header class="section">
    <div class="container-fluid">
        <div class="flex-container">
            <div class="flexgrow-0">
                <!-- Menu -->
                <button class="menu-icon" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="main-menu" id="sidebar">
                    <button class="close-menu" onclick="toggleSidebar()">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="main">
                        <ul class="menu-list">
                            <!-- Menu -->
                            @if(auth()->user()->permissao == 'Z' || auth()->user()->permissao == 'C' || auth()->user()->permissao == 'I')
                                @include('fr/layout2021/menuLateralProf')
                            @elseif(auth()->user()->permissao == 'P')
                                @include('fr/layout2021/menuLateralProf')
                            @elseif(auth()->user()->permissao == 'A' || auth()->user()->permissao == 'R')
                                @include('fr/layout2021/menuLateralAluno')
                            @endif
                            <!-- Fim Menu -->
                        </ul>
                    </div>
                </div>
                <!-- Fim Menu -->
            </div>

            <div class="flexgrow-0">
                <!-- Logo -->
                @php
                    $instituicao = session('instituicao');
                @endphp
                <div id="logo">
                    <a href="{{url('/catalogo')}}">
                        <img src="{{ config('app.cdn') }}/fr/imagens/2021/logo.png" />
                    </a>
                    @if($instituicao['tipo'] == 2) <!-- publica -->
                        <a href="{{url('/catalogo')}}" class="parceiros not-mobile">
                            <img style="height: 60px;" src="{{config('app.cdn')}}/fr/imagens/2021/logo_sefe.png" />
                        </a>
                    @elseif($instituicao['tipo'] == 1)<!-- privada -->
                        <a href="{{url('/catalogo')}}" class="parceiros not-mobile">
                            <img src="{{ config('app.cdn') }}/fr/imagens/2021/logo_solucoes_educacionais.png" />
                        </a>
                    @endif

                </div>
                <!-- Fim logo -->
            </div>

            <div class="flexgrow-1 d-flex justify-content-center">
                <!-- Busca -->

                    <div id="search">
                        <form action="{{url('/busca_geral')}}" method="get">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend icon-search">
                                <!-- Altitude -->
                                <button class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></button>
                                <!-- <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-search"></i>
                                </span> -->
                            </div>
                            @php
                                $busca = '';
                                if ( strpos(Request::path(), 'busca_geral')!==false )
                                {
                                    $busca = Request::input('busca');
                                }
                            @endphp
                            <input type="text" value="{{$busca}}" name="busca" class="form-control" placeholder="Busca INteligente" aria-label="Busca Inteligente" aria-describedby="basic-addon1">
                        </div>
                        </form>
                    </div>

                <!-- Fim Busca -->
            </div>

            <div class="flexgrow-0">
                <!-- User -->
                <div id="user">
                    <div class="notification-area dropdown">
                        <button class="notification dropdown-toggle-split" id="dropdownMenuNot" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                        </button>
                        <div class="notifications dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuNot">
                            <!--<a href="#">Lorem Ipsum Dolor Sit</a>
                            <a href="#">Lorem Ipsum Dolor Sit</a>
                            <a href="#">Lorem Ipsum Dolor Sit</a>
                            <a href="#">Lorem Ipsum Dolor Sit</a>
                            <a href="#" class="erase">
                                <i class="fas fa-bell"></i>
                                Apagar todas as notificações
                            </a>-->
                            <a href="#" class="erase">
                                <i class="fas fa-bell"></i>
                                Nenhuma notificação.
                            </a>
                        </div>
                    </div>
                    @php
                        $escola = session('escola');
                    @endphp

                    <div class="user-area dropdown">
                        <button class="user-area" onclick="toggleDropdownMenuUser()">
                            <span class="avatar">
                                <img src="{{auth()->user()->avatar}}">
                            </span>
                            <span class="username text-truncate">
                                 {{ ucwords(auth()->user()->name) }}
                                @if(auth()->user()->permissao == 'R')
                                    <small>({{ session('alunoDoResponsavel') }})</small>
                                @endif
                                 <br><small>{{$escola['titulo']}}</small>
                            </span>
                        </button>
                        <div class="dropdown-menu drop-not" id="dropdownMenuUser">
                            @if(auth()->user()->habilitar_troca_senha)
                                @if(strpos(auth()->user()->email, 'opeteducation.com.br') === false && strpos(auth()->user()->email, 'souopet.com.br') === false)
                                <a class="dropdown-item" href="{{ url('configuracao') }}">Configurações</a>
                                @endif
                            @endif
                            @if(session('dadosPermissoesEscolhido') != null)
                                <a class="dropdown-item" href="{{ url('multiplasPermissoes') }}">Trocar Perfil</a>
                            @endif
                            <a class="dropdown-item" href="{{ url('colecao_tutorial') }}">Ajuda</a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="confirmLogout();">Sair</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf </form>
                        </div>
                    </div>

                </div>
                <!-- Fim User -->
            </div>
        </div>
    </div>
</header>

<section class="menu-section section">
    <div class="container-fluid">
        <button class="menu-icon-list" onclick="toggleSidebarMenu()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="menu-main-list" id="drop-menu">
            <ul class="menu-b d-flex justify-content-around">
                <li>
                    <a href="#" class="main-link has-submenu">EDUCAÇÃO INFANTIL</a>
                    <!-- Sub Menu -->
                    <div class="main-menu submenu-list">
                        <div class="main">
                            <ul class="menu-list">
                                <li><a href="{{url('/infantil')}}" target="blank"><div class="ico educacao-infantil"></div>Espaço da Educação Infantil</a></li>
                                <li><a href="{{url('/editora/conteudos?conteudo=2&etapa=1')}}"><div class="ico audios"></div>Áudios</a></li>
                                <li><a href="{{url('/editora/conteudos?conteudo=100')}}"><div class="ico banco-de-imagens"></div>Imagens</a></li>
                                <li><a href="{{url('/editora/conteudos?conteudo=103&etapa=1')}}"><div class="ico jogos"></div>Jogos Digitais</a></li>
                                <li><a href="{{url('/colecao_livro?etapa=1')}}"><div class="ico livro-didatico"></div>Livros Digitais</a></li>
                                <!--Verifica se escola do usuário tem a Tabela BNCC e se ele não é aluno-->
                                @if(auth()->user()->escola_id && auth()->user()->permissao !== 'A')
                                    @php
                                        $escolaId = auth()->user()->escola_id;
                                        $conteudoTipo = 104; // ID do conteúdo desejado
                                        $cicloEtapa = 1; // Etapa do usuário (você deve ajustar isso com base na sua lógica)

                                        $temConteudo = DB::table('colecao_livro_escola')
                                            ->leftJoin('conteudos', 'conteudos.colecao_livro_id', '=', 'colecao_livro_escola.colecao_id')
                                            ->leftJoin('colecao_livros', 'colecao_livros.id', '=', 'colecao_livro_escola.colecao_id')
                                            ->select('colecao_livro_escola.colecao_id', 'colecao_livro_escola.escola_id', 'conteudos.tipo', 'conteudos.cicloetapa_id')
                                            ->where('colecao_livro_escola.escola_id', $escolaId)
                                            ->where('conteudos.tipo', $conteudoTipo)
                                            ->where('conteudos.cicloetapa_id', $cicloEtapa)
                                            ->exists();
                                    @endphp

                                    @if($temConteudo)
                                        <li><a href="{{ url('/editora/conteudos?conteudo=' . $conteudoTipo . '&etapa=' . $cicloEtapa) }}"><div class="ico tabelas-bncc"></div>Tabelas BNCC</a></li>
                                    @endif
                                @endif
                                <li><a href="{{url('/editora/conteudos?conteudo=22&etapa=1')}}"><div class="ico documentos-oficiais"></div>Documentos Oficiais</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Fim Sub Menu -->
                </li>
                <li>
                    <a href="#" class="main-link has-submenu">ANOS INICIAIS</a>
                    <!-- Sub Menu -->
                    <div class="main-menu submenu-list">
                        <div class="main">
                            <ul class="menu-list">
                                <!--<li><a href="{{url('/editora/conteudos?conteudo=2&etapa=2')}}"><div class="ico agenda"></div>Agenda</a></li>-->
                                <li><a href="{{url('/editora/conteudos?conteudo=2&etapa=2')}}"><div class="ico audios"></div>Áudios</a></li>
                                <!--<li><a href="{{url('/editora/conteudos?conteudo=2&etapa=2')}}"><div class="ico conteudo"></div>Avaliações</a></li>-->
                                <!--<li><a href="https://escola.britannica.com.br/" target="blank"><div class="ico britannica-escola"></div>Britannica Escola</a></li>-->
                                <li><a href="{{url('/editora/conteudos?conteudo=100')}}"><div class="ico banco-de-imagens"></div>Imagens</a></li>
                                <li><a href="{{url('/editora/conteudos?conteudo=103&etapa=2')}}"><div class="ico jogos"></div>Jogos Digitais</a></li>
                                <li><a href="{{url('/colecao_livro?etapa=2')}}"><div class="ico livro-didatico"></div>Livros Digitais</a></li>
                                <!--Verifica se escola do usuário tem a Tabela BNCC e se ele não é aluno-->
                                @if(auth()->user()->escola_id && auth()->user()->permissao !== 'A')
                                    @php
                                        $escolaId = auth()->user()->escola_id;
                                        $conteudoTipo = 104; // ID do conteúdo desejado
                                        $cicloEtapa = 2; // Etapa do usuário (você deve ajustar isso com base na sua lógica)

                                        $temConteudo = DB::table('colecao_livro_escola')
                                            ->leftJoin('conteudos', 'conteudos.colecao_livro_id', '=', 'colecao_livro_escola.colecao_id')
                                            ->leftJoin('colecao_livros', 'colecao_livros.id', '=', 'colecao_livro_escola.colecao_id')
                                            ->select('colecao_livro_escola.colecao_id', 'colecao_livro_escola.escola_id', 'conteudos.tipo', 'conteudos.cicloetapa_id')
                                            ->where('colecao_livro_escola.escola_id', $escolaId)
                                            ->where('conteudos.tipo', $conteudoTipo)
                                            ->where('conteudos.cicloetapa_id', $cicloEtapa)
                                            ->exists();
                                    @endphp

                                    @if($temConteudo)
                                        <li><a href="{{ url('/editora/conteudos?conteudo=' . $conteudoTipo . '&etapa=' . $cicloEtapa) }}"><div class="ico tabelas-bncc"></div>Tabelas BNCC</a></li>
                                    @endif
                                @endif
                                <!--<li><a href="{{url('/colecao_livro?etapa=2')}}"><div class="ico conteudo"></div>Planetário</a></li>-->
                                <li><a href="{{url('/quiz/colecao')}}"><div class="ico quizzes"></div>Quizzes</a></li>
                                <!--<li><a href="{{url('/colecao_livro?etapa=2')}}"><div class="ico conteudo"></div>Sistema do corpo humano</a></li>-->
                               <!--
                                @if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')
                                    <li><a href="{{url('gestao/cursos')}}"><div class="ico roteiro"></div>Roteiros</a></li>
                                @endif
                                @if(auth()->user()->permissao == 'A'|| auth()->user()->permissao == 'R')
                                    <li><a href="{{url('trilhas/listar')}}"><div class="ico trilha"></div>Trilhas</a></li>
                                @else
                                    <li><a href="{{url('/gestao/trilhas')}}"><div class="ico trilha"></div>Trilhas</a></li>
                                @endif
                                -->
                                <li><a href="{{url('/editora/conteudos?conteudo=3&etapa=2')}}"><div class="ico videos-02"></div>Vídeos</a></li>
                                    <li><a href="{{url('/editora/conteudos?conteudo=22&etapa=2')}}"><div class="ico documentos-oficiais"></div>Documentos Oficiais</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Fim Sub Menu -->
                </li>
                <li>
                    <a href="#" class="main-link has-submenu">ANOS FINAIS</a>
                    <!-- Sub Menu -->
                    <div class="main-menu submenu-list">
                        <div class="main">
                            <ul class="menu-list">
                            <!--<li><a href="{{url('')}}"><div class="ico conteudo"></div>Agenda</a></li>-->
                                @if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')
                                    <li><a href="{{url('/editora/conteudos?conteudo=4&etapa=3')}}"><div class="ico apresentacoes"></div>Apresentações</a></li>
                                @endif
                                    <li><a href="{{url('/editora/conteudos?conteudo=2&etapa=3')}}"><div class="ico audios"></div>Áudios</a></li>
                            <!-- <li><a href="{{url('/editora/conteudos?conteudo=2&etapa=3')}}"><div class="ico conteudo"></div>Avaliações</a></li> -->
                                <!--<li><a href="https://escola.britannica.com.br/" target="blank"><div class="ico britannica-escola"></div>Britannica Escola</a></li>-->
                                <li><a href="{{url('/editora/conteudos?conteudo=100')}}"><div class="ico banco-de-imagens"></div>Imagens</a></li>
                                <li><a href="{{url('/colecao_livro?etapa=3')}}"><div class="ico livro-didatico"></div>Livros Digitais</a></li>
                                <!--Verifica se escola do usuário tem a Tabela BNCC e se ele não é aluno-->
                                @if(auth()->user()->escola_id && auth()->user()->permissao !== 'A')
                                    @php
                                        $escolaId = auth()->user()->escola_id;
                                        $conteudoTipo = 104; // ID do conteúdo desejado
                                        $cicloEtapa = 3; // Etapa do usuário (você deve ajustar isso com base na sua lógica)

                                        $temConteudo = DB::table('colecao_livro_escola')
                                            ->leftJoin('conteudos', 'conteudos.colecao_livro_id', '=', 'colecao_livro_escola.colecao_id')
                                            ->leftJoin('colecao_livros', 'colecao_livros.id', '=', 'colecao_livro_escola.colecao_id')
                                            ->select('colecao_livro_escola.colecao_id', 'colecao_livro_escola.escola_id', 'conteudos.tipo', 'conteudos.cicloetapa_id')
                                            ->where('colecao_livro_escola.escola_id', $escolaId)
                                            ->where('conteudos.tipo', $conteudoTipo)
                                            ->where('conteudos.cicloetapa_id', $cicloEtapa)
                                            ->exists();
                                    @endphp

                                    @if($temConteudo)
                                        <li><a href="{{ url('/editora/conteudos?conteudo=' . $conteudoTipo . '&etapa=' . $cicloEtapa) }}"><div class="ico tabelas-bncc"></div>Tabelas BNCC</a></li>
                                    @endif
                                @endif
                            <!--<li><a href="{{url('/colecao_livro?etapa=3')}}"><div class="ico conteudo"></div>Planetário</a></li>-->
                                <li><a href="{{url('/quiz/colecao')}}"><div class="ico quizzes"></div>Quizzes</a></li>
                                <li><a href="{{url('/editora/conteudos?conteudo=101&etapa=3')}}"><div class="ico simuladores"></div>Simuladores</a></li>
                                <li><a href="{{url('/sistemasolar')}}"><div class="ico sistema_solar"></div>Sistema Solar</a></li>
                                <li><a href="{{url('/tabelaperiodica')}}"><div class="ico tabela_periodica"></div>Tabela Periódica</a></li>
                                <!--<li><a href="{{url('/colecao_livro?etapa=3')}}"><div class="ico conteudo"></div>Sistema do corpo humano</a></li>-->
                            <!--
                                @if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')
                                <li><a href="{{url('gestao/cursos')}}"><div class="ico roteiro"></div>Roteiros</a></li>
                                @endif
                                @if(auth()->user()->permissao == 'A' || auth()->user()->permissao == 'R')
                                <li><a href="{{url('trilhas/listar')}}"><div class="ico trilha"></div>Trilhas</a></li>
                                @else
                                <li><a href="{{url('/gestao/trilhas')}}"><div class="ico trilha"></div>Trilhas</a></li>
                                @endif
                                -->
                                <li><a href="{{url('/editora/conteudos?conteudo=3&etapa=3')}}"><div class="ico videos-02"></div>Vídeos</a></li>
                                <li><a href="{{url('/editora/conteudos?conteudo=22&etapa=3')}}"><div class="ico documentos-oficiais"></div>Documentos Oficiais</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Fim Sub Menu -->
                </li>
                <li>
                    <a href="#" class="main-link has-submenu">ENSINO MÉDIO</a>
                    <!-- Sub Menu -->
                    <div class="main-menu submenu-list">
                        <div class="main">
                            <ul class="menu-list">
                            <!--<li><a href="{{url('')}}"><div class="ico conteudo"></div>Agenda</a></li>-->
                            <!--<li><a href="{{url('/editora/conteudos?conteudo=4&etapa=4')}}"><div class="ico apresentacoes"></div>Apresentações</a></li>-->
                                <li><a href="{{url('/editora/conteudos?conteudo=2&etapa=4')}}"><div class="ico audios"></div>Áudios</a></li>
                            <!--<li><a href="{{url('/editora/conteudos?conteudo=2&etapa=4')}}"><div class="ico conteudo"></div>Avaliações</a></li>-->
                            <!--<li><a href="https://escola.britannica.com.br/" target="blank"><div class="ico britannica-escola"></div>Britannica Escola</a></li>-->
                                <li><a href="{{url('/editora/conteudos?conteudo=100')}}"><div class="ico banco-de-imagens"></div>Imagens</a></li>
                                <li><a href="{{url('/colecao_livro?etapa=4')}}"><div class="ico livro-didatico"></div>Livros Digitais</a></li>
                            <!--<li><a href="{{url('/editora/conteudos?conteudo=104&etapa=4')}}"><div class="ico tabelas-bncc"></div>Tabelas BNCC</a></li> -->
                            <!--<li><a href="{{url('/colecao_livro?etapa=4')}}"><div class="ico conteudo"></div>Planetário</a></li>-->
                                <li><a href="{{url('/quiz/colecao')}}"><div class="ico quizzes"></div>Quizzes</a></li>
                                <li><a href="{{url('/editora/conteudos?conteudo=101&etapa=4')}}"><div class="ico simuladores"></div>Simuladores</a></li>
                                <li><a href="{{url('/sistemasolar')}}"><div class="ico sistema_solar"></div>Sistema Solar</a></li>
                                <li><a href="{{url('/tabelaperiodica')}}"><div class="ico tabela_periodica"></div>Tabela Periódica</a></li>
                                <!--<li><a href="{{url('/colecao_livro?etapa=4')}}"><div class="ico conteudo"></div>Sistema do corpo humano</a></li>-->
                            <!--
                                @if(auth()->user()->permissao != 'A')
                                <li><a href="{{url('gestao/cursos')}}"><div class="ico roteiro"></div>Roteiros</a></li>
                                @endif
                                @if(auth()->user()->permissao == 'A')
                                <li><a href="{{url('trilhas/listar')}}"><div class="ico trilha"></div>Trilhas</a></li>
                                @else
                                <li><a href="{{url('/gestao/trilhas')}}"><div class="ico trilha"></div>Trilhas</a></li>
                                @endif
                                -->
                                <li><a href="{{url('/editora/conteudos?conteudo=3&etapa=4')}}"><div class="ico videos-02"></div>Vídeos</a></li>
                                <li><a href="{{url('/editora/conteudos?conteudo=22&etapa=4')}}"><div class="ico documentos-oficiais"></div>Documentos Oficiais</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Fim Sub Menu -->
                </li>
                <!--
                <li>
                    <a href="#" class="main-link has-submenu">FAMÍLIA</a>
                </li>
                -->
                @if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')
                <li>
                    <a href="#" class="main-link has-submenu">DOCENTES</a>
                    <!-- Sub Menu -->
                    <div class="main-menu submenu-list">
                        <div class="main">
                            <ul class="menu-list">
                            <!--<li><a href="{{url('/editora/conteudos?conteudo=4&etapa=4')}}"><div class="ico conteudo"></div>Agenda</a></li>-->
                                <li><a href="{{url('/editora/conteudos?conteudo=22')}}"><div class="ico documentos-oficiais"></div>Documentos Oficiais</a></li>
                            <!--<li><a href="{{url('/editora/conteudos?conteudo=2&etapa=4')}}"><div class="ico conteudo"></div>Dúvidas dos estudantes</a></li>-->
                            @if($instituicao['tipo'] == 2 || $instituicao['tipo'] == 3) <!-- publica ou licitação -->
                                <!-- <li><a href="https://sites.google.com/opeteducation.com.br/eadacesse/início" target="blank"><div class="ico ead"></div>EaD</a></li>-->
                                <!-- <li><a href="https://uniopet.myopenlms.net/login/index.php" target="blank"><div class="ico ead"></div>EaD</a></li> -->
                            @endif
                            @if($instituicao['tipo'] == 2) <!-- publica -->
                                <li><a href="https://indicaopet.com.br" target="blank"><div class="ico indica"></div>InDica</a></li>
                            @endif
                                <li><a href="{{url('/colecao_livro')}}"><div class="ico livro-didatico"></div>Livros Digitais</a></li>
                            @if($instituicao['tipo'] == 2) <!-- publica -->
                                <li><a target="blank" href="http://www.editoraopet.com.br/blog/category/educacao-publica/"><div class="ico noticias-educacionais"></div>Notícias Educacionais</a></li>
                            @else
                                    <li><a target="blank" href="http://www.editoraopet.com.br/blog/category/educacao-privada/"><div class="ico noticias-educacionais"></div>Notícias Educacionais</a></li>
                            @endif
                            <!--<li><a href="{{url('/editora/conteudos?conteudo=101&etapa=4')}}"><div class="ico conteudo"></div>Minhas avaliações</a></li>-->
                            <!--<li><a href="{{url('/colecao_livro?etapa=4')}}"><div class="ico conteudo"></div>Meus entregaveis</a></li>-->
                                <li><a href="{{url('gestao/quiz')}}"><div class="ico meus-quizzes"></div>Meus quizzes</a></li>
                                <li><a href="{{url('/gestao/roteiros')}}"><div class="ico meus-roteiros"></div>Meus roteiros</a></li>
                                <li><a href="{{url('/gestao/trilhass')}}"><div class="ico minhas-trilhas"></div>Minhas trilhas</a></li>
                                <li><a href="{{url('/editora/conteudos?conteudo=3&colecao=')}}"><div class="ico videos-02"></div>Vídeos</a></li>
                                <li><a href="{{url('/editora/conteudos/colecao?conteudo=102')}}"><div class="ico provas-bimestrais"></div>Provas Bimestrais</a></li>
                                @php
                                    $escolaId = auth()->user()->escola_id ?? null;
                                    $conteudoTipo = 105; // O tipo de conteúdo desejado
                                    $temEstruturaTrimestral = false;

                                    if ($escolaId && auth()->user()->permissao != 'A') {
                                        $temEstruturaTrimestral = DB::table('users as u')
                                            ->join('escolas as e', 'u.escola_id', '=', 'e.id')
                                            ->join('instituicao as i', 'e.instituicao_id', '=', 'i.id')
                                            ->where('i.estrutura_trimestral', 1)
                                            ->where('u.escola_id', $escolaId)
                                            ->where('u.permissao', '!=', 'A')
                                            ->exists();
                                    }
                                @endphp
                                @if($temEstruturaTrimestral || auth()->user()->permissao == 'Z')
                                    <li><a href="{{ url('/editora/conteudos/colecao?conteudo=' . $conteudoTipo) }}"><div class="ico estrutura_trimestral"></div>Estrutura Trimestral</a></li>
                                @endif   
                            </ul>
                        </div>
                    </div>
                    <!-- Fim Sub Menu -->
                </li>
                @endif

                <!-- Menu Google -->
                <li>
                    <a href="#" class="main-link has-submenu">GOOGLE FOR EDUCATION</a>
                    <div class="main-menu submenu-list">
                        <div class="main">
                            <ul class="menu-list">
                                <li><a href="javascript:void(0)" class="has-submenu"><div class="ico google-colaboracao"></div>Colaboração <i class="fas fa-sort-down"></i></a>
                                    <ul class="submenu">
                                        <li><a href="https://classroom.google.com/" target="blank"><div class="ico google-classroom"></div>Sala de aula</a></li>
                                        <li><a href="https://docs.google.com/document/" target="blank"><div class="ico google-documentos"></div>Documentos</a></li>
                                        <li><a href="https://docs.google.com/spreadsheets/" target="blank"><div class="ico google-planilhas"></div>Planilhas</a></li>
                                        <li><a href="https://docs.google.com/presentation/" target="blank"><div class="ico google-apresentacao"></div>Apresentações</a></li>
                                        <li><a href="https://drive.google.com/" target="blank"><div class="ico google-drive"></div>Drive</a></li>
                                        <li><a href="https://docs.google.com/forms/" target="blank"><div class="ico google-formularios"></div>Formulários</a></li>
                                        <!--
                                        Recurso Inativado Pelo Google
                                        <li><a href="https://jamboard.google.com/" target="blank"><div class="ico google-jamboard"></div>Jamboard</a></li>
                                        -->
                                    </ul>
                                </li>
                                <li><a href="javascript:void(0)" class="has-submenu"><div class="ico google-comunicacao"></div>Comunicação <i class="fas fa-sort-down"></i></a>
                                    <ul class="submenu">
                                        <li><a href="https://mail.google.com/" target="blank"><div class="ico google-gmail"></div>Gmail</a></li>
                                        <li><a href="https://meet.google.com/" target="blank"><div class="ico google-meet"></div>Meet</a></li>
                                        <li><a href="https://chat.google.com/" target="blank"><div class="ico google-chat"></div>Chat</a></li>
                                        <li><a href="https://contacts.google.com/" target="blank"><div class="ico google-contato"></div>Contatos</a></li>
                                        <li><a href="https://groups.google.com/" target="blank"><div class="ico google-grupos"></div>Grupos</a></li>
                                        <li><a href="https://news.google.com/" target="blank"><div class="ico google-noticias"></div>Notícias</a></li>
                                    </ul>
                                </li>
                                <li><a href="javascript:void(0)" class="has-submenu"><div class="ico conteudo"></div>Organização <i class="fas fa-sort-down"></i></a>
                                    <ul class="submenu">
                                        <li><a href="https://keep.google.com/" target="blank"><div class="ico google-keep"></div>Keep</a></li>
                                        <li><a href="https://calendar.google.com/calendar/" target="blank"><div class="ico google-calendario"></div>Calendário</a></li>
                                        <li><a href="https://sites.google.com/" target="blank"><div class="ico google-sites"></div>Sites</a></li>
                                    </ul>
                                </li>
                                <li><a href="javascript:void(0)" class="has-submenu"><div class="ico google-ferramentas"></div>Ferramentas <i class="fas fa-sort-down"></i></a>
                                    <ul class="submenu">
                                        <li><a href="https://www.youtube.com/channel/UCPXnBBshEViOKyv9EoVKRaQ" target="blank"><div class="ico google-youtube"></div>Youtube</a></li>
                                        <li><a href="https://www.google.com.br/maps/" target="blank"><div class="ico google-maps"></div>Maps</a></li>
                                        <li><a href="https://photos.google.com/" target="blank"><div class="ico google-fotos"></div>Fotos</a></li>
                                        <li><a href="https://myaccount.google.com/" target="blank"><div class="ico google-conta"></div>Conta</a></li>
                                        <li><a href="https://myaccount.google.com/signinoptions/password" target="blank"><div class="ico google-senha"></div>Mudar Senha Google</a></li>
                                    </ul>
                                </li>
                            </ul>

                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</section>
<script type="text/javascript">
    $('.dropdown-toggle').dropdown();

    function toggleDropdownMenuUser() {

        document.getElementById("dropdownMenuUser").classList.toggle("active");

    }

    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("active");
    }

    function toggleSidebarMenu() {
        document.getElementById("drop-menu").classList.toggle("active");
    }

    $(document).ready(function() {
        $('#sidebar ul > li > a.has-submenu').on('click', function(e) {
            e.preventDefault();
            var liAnterior = $(this).parent();
            var submenu = $(this).next();

            if(liAnterior.hasClass( "ativo" ) === false){
                liAnterior.addClass('ativo');
                submenu.slideDown();
            }else{
                submenu.slideUp("fast", function() {
                    liAnterior.removeClass('ativo');
                });
            }
        });

        $('.submenu-list ul > li > a.has-submenu').on('click', function(e) {
            e.preventDefault();
            var liAnterior = $(this).parent();
            var submenu = $(this).next();

            if(liAnterior.hasClass( "ativo" ) === false){
                liAnterior.addClass('ativo');
                submenu.slideDown();
            }else{
                submenu.slideUp("fast", function() {
                    liAnterior.removeClass('ativo');
                });
            }
        });
    });
</script>
