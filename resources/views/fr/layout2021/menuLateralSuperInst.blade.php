<button class="menu-icon" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>
<div class="main-menu" id="sidebar">
    <button class="close-menu" onclick="toggleSidebar()">
        <i class="fas fa-times"></i>
    </button>
    <div class="main">
        <ul class="menu-list">
            <li><a href="{{ url('catalogo') }}"><img class="ico" src="fr/imagens/icones_sidebar/inicio.png"> Home</a></li>

        <li>
                <a href="#" class="has-submenu active">
                    <img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/biblioteca.png">
                    Conteúdo
                    <i class="fas fa-sort-down"></i>
                </a>
                <ul class="submenu">
             <!-- SUPERADMIN/ADMIN DA INSTITUIÇÃO -->
             <li><a href="{{ route('gestao.usuarios') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/usuario.png"> Usuários</a></li>

              <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR -->
             <li><a href="{{ route('gestao.biblioteca') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/biblioteca.png"> Biblioteca</a></li>

             <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR -->
            <li><a href="{{ url('gestao/cursos') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/roteiro.png"> Roteiros de estudo</a></li>

            <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR -->
            <li><a href="{{route('gestao.trilhas.listar')}}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/trilha.png"> Trilhas</a></li>

             <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR -->
            <li><a href="{{route('gestao.cursoslivres')}}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/cursoslivres.png"> Cursos Livres</a></li>

             <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR/ALUNO -->
            <li><a href="{{ route('gestao.audios.listar') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/cast.png"> INspira Cast</a></li>

            <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR/ALUNO -->
            <li><a href="{{ route('gestao.baralhos.listar') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/cards.png"> INspira Cards</a></li>

            <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR/ALUNO -->
            <li><a href="{{ route('gestao.aplicacoes') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/hub.png"> INspira Hub</a></li>
            </ul>
            </li>
            <li>
                <a href="#" class="has-submenu active">
                    <img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/sala.png">
                    Sala de Estudos
                    <i class="fas fa-sort-down"></i>
                </a>
                <ul class="submenu">
                    <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR -->
                    <li><a href="{{ route('colecao_livro') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/livrodigital.png"> Livro Digital</a></li>
                    <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR -->
                    <li><a href="{{ route('gestao.artigos.index') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/artigos.png"> Artigos</a></li>
                    <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR -->
                   <li><a href="{{ route('gestao.avaliacoes-lista') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/avaliacao.png"> Avaliações</a></li>

                   <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR/ALUNO -->
                     <li><a href="{{ route('gestao.banco-imagens.index') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/imagens.png"> Banco de Imagens</a></li>
                     <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR/ALUNO -->
                    <li><a href="{{ route('gestao.glossario.index') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/glossario.png"> Glossário</a></li>

                </ul>
            </li>

            <li>
                <a href="#" class="has-submenu active">
                    <img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/gamificacao.png">
                    Portal de Gamificação
                    <i class="fas fa-sort-down"></i>
                </a>
                <ul class="submenu">
                    <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR -->
                    <li><a href="{{ route('gestao.gamificacao.index') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/configuracoes.png"> Configurações</a></li>
                </ul>
            </li>
            <li>
                <a href="#" class="has-submenu">
                    <img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/gestaoacademica.png">
                    Gestão Acadêmica
                    <i class="fas fa-sort-down"></i>
                </a>
                <ul class="submenu">
                     <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR -->
                    <li><a href="{{ route('gestao.plano-aulas.listar') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/planodeaula.png"> Plano de Aula</a></li>
                     <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR -->
                    <li><a href="{{ route('gestao.entregaveis') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/entregaveis.png"> Entregáveis</a></li>
                     <!-- SUPERADMIN/ADM INSTITUICAO/GESTOR/COORDENADOR/PROFESSOR -->
                    <li><a href="{{ route('gestao.professor.duvidas', ['idProfessor' => Auth::user()->id]) }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/duvidasdoaluno.png"> Dúvidas do Aluno</a></li>
                </ul>
            </li>

            <li>
                <a href="#" class="has-submenu active">
                    <img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/gestaodaplataforma.png">
                    Gestão da Plataforma
                    <i class="fas fa-sort-down"></i>
                </a>
                <ul class="submenu">
                     <!-- SUPERADMIN/ADM INSTITUICAO -->
                    <li><a href="{{ route('gestao.categorias') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/categorias.png"> Categorias</a></li>
                    <!-- SUPERADMIN/ADM INSTITUICAO -->
                    <li><a href="{{ route('gestao.escola.mural', ['escola_id' => Auth::user()->escola_id]) }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/escola.png"> Mural da Escola</a></li>
                    <!-- SUPERADMIN/ADM INSTITUICAO -->
                    <li><a href="{{ route('gestao.relatorios') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/relatorio.png"> Relatórios</a></li>
                    <!-- SUPERADMIN/ADM INSTITUICAO -->
                    <li><a href="#"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/categorias.png"> Políticas de Uso</a></li>
                    <!-- SUPERADMIN/ADM INSTITUICAO -->
                    <li><a href="{{ route('gestao.ajuda.artigos') }}"><img class="ico" src="{{config('app.cdn')}}/fr/imagens/icones_sidebar/faq.png"> Ajuda/Faq</a></li>
                </ul>
            </li>
            <li><a href="{{ url('/colecao_tutorial') }}"> Tutoriais</a></li>

        </ul>
    </div>
</div>
