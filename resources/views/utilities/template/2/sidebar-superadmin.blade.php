<div id="sidebar">
  <span id="ret-cinza">
    <i class="close-sidebar-open" onClick="toggleSidebar()">X</i>
  </span>

  <ul class="side-group">
    <li><a href="{{ url('catalogo') }}">Início</a></li>
    <li><a href="{{ route('gestao.instituicao-create') }}">Instituições</a></li>
    <li><a href="{{ route('gestao.escolas') }}">Escolas </a></li>
    <li><a href="{{ route('gestao.usuarios') }}">Administradores</a></li>
    <li><a href="{{ route('gestao.cursos') }}">{{ ucfirst($langCursoP) }}</a></li>
    <li><a href="{{route('gestao.cursoslivres')}}">Cursos Livres</a></li>
    <li><a href="{{route('gestao.trilhas.listar')}}">Trilhas</a></li>
    <li><a href="{{ route('gestao.audios.listar') }}">Cast Creator</a></li>
    <li><a href="{{ route('gestao.baralhos.listar') }}">Cards Creator</a></li>
    <li><a href="{{ route('gestao.aplicacoes') }}">Hub Creator</a></li>
    <li><a href="{{ route('gestao.biblioteca') }}">Biblioteca</a></li>
    <li>
      <a href="#" class="has-submenu">Sala de Estudos <i class="fas fa-sort-down"></i></a>
      <ul class="submenu">
          <li><a href="{{ route('gestao.artigos.index') }}">Artigos</a></li>
          <li><a href="{{ route('gestao.avaliacoes-lista') }}">Avaliações</li>
          <li><a href="{{ route('gestao.banco-imagens.index') }}">Banco de Imagens</a></li>
          <li><a href="{{ route('gestao.livro-lista') }}">{{ucfirst($langDigital)}} Digital</li>

          <li><a href="{{ route('gestao.glossario.index') }}">Glossário</a></li>
        <!--
        <li><a href="{{ route('gestao.questoes.listar') }}">Banco de itens</a></li>
        <li><a href="{{ route('gestao.plano-aulas.listar') }}">Plano de aulas</a></li> -->
      </ul>
    </li>
    <li>
      <a href="#" class="has-submenu">Portal de gamificação <i class="fas fa-sort-down"></i></a>
        <ul class="submenu">
      <!--   <li><a href="{{ route('gestao.missoes.listar') }}">Missões</a></li>
          <li><a href="{{ route('gestao.desafios.listar') }}">Desafios</a></li>
          <li><a href="{{ route('gestao.conquistas.listar') }}">Conquistas</a></li>
          <li><a href="{{ route('gestao.recompensas-virtuais.listar') }}">Recompensas virtuais</a></li>
          <li><a href="{{ route('gestao.recompensas-extra-jogo.listar') }}">Recompensas extra-jogo</a></li>
          <li><a href="{{ route('gestao.badges.listar') }}">Medalhas</a></li>
          <li><a href="{{ route('gestao.habilidades.listar') }}">Habilidades</a></li> -->
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
      <!--   <li><a href="{{ route('gestao.missoes.listar') }}">Missões</a></li>
          <li><a href="{{ route('gestao.desafios.listar') }}">Desafios</a></li>
          <li><a href="{{ route('gestao.conquistas.listar') }}">Conquistas</a></li>
          <li><a href="{{ route('gestao.recompensas-virtuais.listar') }}">Recompensas virtuais</a></li>
          <li><a href="{{ route('gestao.recompensas-extra-jogo.listar') }}">Recompensas extra-jogo</a></li>
          <li><a href="{{ route('gestao.badges.listar') }}">Medalhas</a></li>
          <li><a href="{{ route('gestao.habilidades.listar') }}">Habilidades</a></li>
          <li><a href="{{ route('gestao.gamificacao.index') }}">Configurações</a></li> -->
        </ul>
    </li>
    <li>
      <a href="#" class="has-submenu">Gestão da Plataforma <i class="fas fa-sort-down"></i></a>
        <ul class="submenu">
        <li><a href="{{ route('gestao.categorias') }}">Categorias</a></li>
        <li><a href="{{ route('gestao.escola.mural', ['escola_id' => Auth::user()->escola_id]) }}">Mural da escola</a></li>
        <li><a href="{{ route('gestao.relatorios') }}">Relatórios</a></li>
        <li><a href="{{ route('gestao.ajuda.artigos') }}">Ajuda / FAQ</a></li>
        <li><a href="{{ route('gestao.plataforma') }}">Gestão da Plataforma</a></li>
        <!--
        <li><a href="#">Disciplinas</a></li>
        <li><a href="#">Permissões</a></li>
        <li><a href="{{ route('dashboard.financeiro') }}">Financeiro</a></li>
        <li><a href="{{ route('gestao.licencas.index') }}">Licenças</a></li>
        <li><a href="{{ route('gestao.professor.duvidas', ['idProfessor' => Auth::user()->id]) }}">Dúvidas de alunos</a></li>
        <li><a href="#">Planos de estudos</a></li>
        <li><a href="{{config('app.url')}}/plano-de-aulas">Plano de aulas</a></li>
        <li><a href="{{ route('gestao.missoes.listar') }}">Missões</a></li>
        <li><a href="{{ route('gestao.desafios.listar') }}">Desafios</a></li>
        <li><a href="{{ route('gestao.conquistas.listar') }}">Conquistas</a></li>
        <li><a href="{{ route('gestao.recompensas-virtuais.listar') }}">Recompensas virtuais</a></li>
        <li><a href="{{ route('gestao.recompensas-extra-jogo.listar') }}">Recompensas extra-jogo</a></li>
        <li><a href="{{ route('gestao.badges.listar') }}">Medalhas</a></li>
        <li><a href="{{ route('gestao.habilidades.listar') }}">Habilidades</a></li>
        <li><a href="{{ route('gestao.gamificacao.index') }}">Configurações</a></li> -->
        </ul>
    </li>
    <!--<li>
      <a href="#" class="has-submenu">Configurações <i class="fas fa-sort-down"></i></a>
        <ul class="submenu">
        <li><a href="{{ route('configuracao.trocar-email') }}">Alterar e-mail ou senha</a></li>
        <li><a href="#">Alterar dados cadastrais</a></li>
        <li><a href="#">Meus Pagamentos</a></li>
        <li><a href="#">Gerenciar Notificações</a></li>
        <li><a href="{{ route('gestao.relatorios') }}">Integrações</a></li>
        <li><a href="#">Customização de Layout</a></li>
        <li><a href="#">Customização de Certificado</a></li>
        <li><a href="{{ route('dashboard.doc.api.index') }}">Documentação API</a></li>


        <li><a href="{{ route('gestao.professor.duvidas', ['idProfessor' => Auth::user()->id]) }}">Dúvidas de alunos</a></li>
        <li><a href="#">Planos de estudos</a></li>
        <li><a href="{{config('app.url')}}/plano-de-aulas">Plano de aulas</a></li>
        <li><a href="{{ route('gestao.missoes.listar') }}">Missões</a></li>
        <li><a href="{{ route('gestao.desafios.listar') }}">Desafios</a></li>
        <li><a href="{{ route('gestao.conquistas.listar') }}">Conquistas</a></li>
        <li><a href="{{ route('gestao.recompensas-virtuais.listar') }}">Recompensas virtuais</a></li>
        <li><a href="{{ route('gestao.recompensas-extra-jogo.listar') }}">Recompensas extra-jogo</a></li>
        <li><a href="{{ route('gestao.badges.listar') }}">Medalhas</a></li>
        <li><a href="{{ route('gestao.habilidades.listar') }}">Habilidades</a></li>
        <li><a href="{{ route('gestao.gamificacao.index') }}">Configurações</a></li>
      </ul>
    </li> -->
    <!--
    <li><a href="{{ route('gestao.turmas') }}">Turmas</a></li>
    <li><a href="{{ route('gestao.usuarios') }}">Membros</a></li>
    <li><a href="{{config('app.url')}}/trilhas">Trilhas </a></li>
    <li><a href="#">{{ucfirst($langCurso)}}s Livres </a></li>
    <li><a href="">{{ucfirst($langCurso)}}s</a></li>
    -->
    <li><a href="{{url('colecao_tutorial')}}"> Tutoriais</a></li>
  </ul>
</div>
