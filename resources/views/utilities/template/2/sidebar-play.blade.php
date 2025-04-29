<div id="sidebar">
    <span id="ret-cinza">
      <i class="close-sidebar-open" onClick="toggleSidebar()">X</i>
    </span>

    <ul class="side-group">

      <li><a href="{{ url('catalogo') }}">Início</a></li>
      <li><a href="{{ url('catalogo') }}">Catálogo</a></li>
      <!--<li><a href="{{ route('aluno.grade-aula.index', ['date' => date('Y-m-d'), 'turma' => 'todas']) }}">Grade de Aula</a></li>-->
      <li><a href="{{ route('trilhas.listar') }}">Trilhas</a></li>
      <li><a href="{{ route('agenda.index') }}">Calendário</a></li>
      {{--<li><a href="{{ route('teste.listar') }}">Teste de nivelamento</a></li>
      <li><a href="{{ route('habilidades.estatisticas.listar') }}">Estatísticas e habilidades</a></li>--}}
      <!--<li><a href="{{ route('perfil.recompensas') }}">Conquistas e recompensas</a></li>-->
      <!--<li><a href="{{ route('biblioteca') }}">Biblioteca</a></li>-->
      {{-- <li><a href="{{ route('playlists.listar') }}">{{ucfirst($langPlaylist)}}</a></li> --}}

     <!-- <li>
        <a href="#" class="has-submenu">Sala de estudos <i class="fas fa-sort-down"></i></a>
        <ul class="submenu">
          <li><a href="{{ route('artigos.index') }}">Artigos</a></li>
          <li><a href="{{ route('glossario.index') }}">Glossário</a></li>
        </ul>
      </li> -->

      <!--<li>
        <a href="#" class="has-submenu">Comunidade <i class="fas fa-sort-down"></i></a>
        <ul class="submenu">
          <li><a href="{{ route('escola.mural', ['escola_id' => Auth::user()->escola_id]) }}">Mural da {{ __('nomenclaturas.escola') }}</a></li>-->
           {{-- @if(App\AlunoTurma::where('user_id', '=', Auth::user()->id)->first() != null)
            <li><a href="{{ route('turmas') }}">Mural da {{ __('nomenclaturas.turma') }}</a></li>
              @if(App\Turma::find( App\AlunoTurma::where('user_id', '=', Auth::user()->id)->first()->turma_id ) != null)
              <li><a href="{{ route('professor.duvidas', ['idProfessor' => App\Turma::find( App\AlunoTurma::where('user_id', '=', Auth::user()->id)->first()->turma_id )->user_id]) }}">Fale com o {{ __('nomenclaturas.professor') }}</a></li>
               <li><a href="{{ route('canal-professor.index', 1) }}">Canal do {{ __('nomenclaturas.professor') }}</a></li>
              @endif
            @endif--}}
          <!-- <li><a href="{{ route('turmas') }}">Mural da Turma </a></li>
            <li><a href="#">Fale com o Professor</a></li>
        </ul>
      </li>-->

      <!--<li>
        <a href="#" class="has-submenu">Configurações <i class="fas fa-sort-down"></i></a>
        <ul class="submenu">
        <li><a href="{{ route('configuracao.trocar-email') }}">Alterar e-mail ou senha</a></li>
        <li><a href="#">Alterar dados cadastrais</a></li>

        <li><a href="{{ route('gestao.escola.mural', ['escola_id' => Auth::user()->escola_id]) }}">Gerenciar Notificações</a></li>

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
        <li><a href="{{url('colecao_tutorial')}}"> Tutoriais</a></li>
    </ul>
</div>
