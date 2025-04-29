<div id="sidebar">
        <span id="ret-cinza">
          <i class="close-sidebar-open" onClick="toggleSidebar()">X</i>
        </span>

        <ul class="side-group">
          <li><a href="{{ url('catalogo') }}">Início</a></li>
          <li><a href="{{ route('gestao.escola.mural-gestao', ['escola_id' => Auth::user()->escola]) }}">Minha escola</a></li>
          <li><a href="{{ route('gestao.usuarios') }}">Administradores</a></li>
          <li><a href="{{ route('gestao.cursos') }}">{{ucfirst($langCursoP)}}</a></li>
         <!-- <li><a href="{{ route('gestao.cursoslivres') }}">Cursos Livres</a></li>-->
          <li><a href="{{ route('gestao.trilhas.listar') }}">Trilhas</a></li>
          <li><a href="{{ route('gestao.audios.listar') }}">Cast Creator</a></li>
          <li><a href="{{ route('gestao.baralhos.listar') }}">Cards Creator</a></li>
          <li><a href="{{ route('gestao.aplicacoes') }}">Hub Creator</a></li>
          <li><a href="{{ route('gestao.biblioteca') }}">Biblioteca</a></li>
          <li>
            <a href="#" class="has-submenu">Sala de Estudos <i class="fas fa-sort-down"></i></a>
            <ul class="submenu">
              <li><a href="{{ route('gestao.artigos.index') }}">Artigos</a></li>
              <li><a href="{{ route('gestao.banco-imagens.index') }}">Banco de Imagens</a></li>
              <!--<li><a href="{{ route('gestao.livro-lista') }}">Livro Digital</li>-->
              <li><a href="{{ route('gestao.glossario.index') }}">Glossário</a></li>
            </ul>
          </li>
          <li>
            <a href="#" class="has-submenu">Portal de gamificação <i class="fas fa-sort-down"></i></a>
              <ul class="submenu">
                <li><a href="{{ route('gestao.gamificacao.index') }}">Configurações</a></li>
              </ul>
          </li>
          <li>
            <a href="#" class="has-submenu">Gestão Acadêmica <i class="fas fa-sort-down"></i></a>
            <ul class="submenu">
              <li><a href="{{ route('gestao.entregaveis') }}">Entregáveis</a></li>
              <li><a href="{{ route('gestao.professor.duvidas', ['idProfessor' => Auth::user()->id]) }}">Dúvidas do Aluno</a></li>
              <!--<li><a href="#">Planos de Estudos</a></li>-->
              <li><a href="{{ route('gestao.plano-aulas.listar') }}">Planos de Aula</a></li>
            </ul>
          </li>
          <li>
            <a href="#" class="has-submenu">Gestão da Plataforma <i class="fas fa-sort-down"></i></a>
            <ul class="submenu">
              <li><a href="{{ route('gestao.categorias') }}">Categorias</a></li>
              <li><a href="{{ route('gestao.escola.mural', ['escola_id' => Auth::user()->escola_id]) }}">Mural da escola</a></li>
              <li><a href="{{ route('gestao.relatorios') }}">Relatórios</a></li>
              <li><a href="{{ route('gestao.ajuda.artigos') }}">Ajuda / FAQ</a></li>
            </ul>
          </li>
          <li><a href="{{url('colecao_tutorial')}}"> Tutoriais</a></li>
        </ul>
  </div>
