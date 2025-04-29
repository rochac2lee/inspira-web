<div id="sidebar">
        <span id="ret-cinza">
          <i class="close-sidebar-open" onClick="toggleSidebar()">X</i>
        </span>

        <ul class="side-group">

          @if(in_array(Auth::user()->permissao, ["G","Z"]))
          <!-- inicio do menu manager -->
          <li><a href="{{ url('catalogo') }}">Início</a></li>
          <li><a href="{{ route('gestao.escolas') }}">Escolas </a></li>
          <li><a href="{{ route('gestao.usuarios') }}">Administradores</a></li>
          <li><a href="{{route('gestao.trilhas.listar')}}">Trilhas</a></li>
          <li><a href="{{ route('gestao.cursos') }}">{{ ucfirst($langCursoP) }}</a></li>
          <li><a href="{{route('gestao.cursoslivres')}}">Cursos Livres</a></li>
          <li>
            <a href="#" class="has-submenu">Cast Creator <i class="fas fa-sort-down"></i></span>
              <ul class="submenu">
                <li><a href="{{ route('gestao.audios.listar') }}">Áudios</a></li>
                <li><a href="{{ route('gestao.albuns.listar') }}">Álbuns</a></li>
                <li><a href="{{ route('gestao.playlists.listar') }}">{{ucfirst($langPlaylist)}}s</a></li>
              </ul>
          </li>
          <li>
            <a href="#" class="has-submenu">Cards Creator <i class="fas fa-sort-down"></i></a>
              <ul class="submenu">
                <li><a href="{{ route('gestao.baralhos.listar') }}">{{ucfirst($langBaralho)}}</a></li>
                <li><a href="{{ route('gestao.baralhos.listar') }}">Carreiras</a></li>
              </ul>
          </li>
          <li><a href="{{ route('gestao.aplicacoes') }}">Hub Creator</a></li>
          <li>
            <a href="#" class="has-submenu">Sala de Estudos <i class="fas fa-sort-down"></i></a>
            <ul class="submenu">
              <li><a href="{{ route('gestao.artigos.index') }}">Artigos</a></li>
              <li><a href="#">Banco de Imagens</a></li>
              <li><a href="#">{{ucfirst($langDigital)}} Digital</li>
              <li><a href="{{ route('gestao.biblioteca') }}">Biblioteca</a></li>
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
              <li><a href="#">Planos de Estudos</a></li>
              <li><a href="{{config('app.url')}}/plano-de-aulas">Plano de Aula</a></li>
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
          <li>
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

             <!--
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
         <!--
          <li><a href="{{ route('gestao.turmas') }}">Turmas</a></li>
          <li><a href="{{ route('gestao.usuarios') }}">Membros</a></li>
          <li><a href="{{config('app.url')}}/trilhas">Trilhas </a></li>
          <li><a href="#">{{ucfirst($langCurso)}}s Livres </a></li>

          <!-- fim do menu manager -->
          @endif
          <!-- inicio do menu school -->
          @if(in_array(Auth::user()->permissao, ["E"]))
          <li><a href="{{ url('catalogo') }}">Início</a></li>
          <li><a href="{{ route('gestao.escola.mural', ['escola_id' => Auth::user()->escola_id]) }}">Minha escola</a></li>
          <li><a href="{{ route('gestao.usuarios') }}">Administradores</a></li>
          <li><a href="{{ route('gestao.trilhas.listar') }}">Trilhas</a></li>
          <li><a href="{{ route('gestao.cursos') }}">{{ucfirst($langCursoP)}}</a></li>
          <li><a href="{{ route('gestao.cursoslivres') }}">Cursos Livres</a></li>
          <li>
            <a href="#" class="has-submenu">Cast Creator <i class="fas fa-sort-down"></i></span>
              <ul class="submenu">
                <li><a href="{{ route('gestao.audios.listar') }}">Áudios</a></li>
                <li><a href="{{ route('gestao.albuns.listar') }}">Álbuns</a></li>
                <li><a href="{{ route('gestao.playlists.listar') }}">{{ucfirst($langPlaylist)}}s</a></li>
              </ul>
          </li>
          <li>
            <a href="#" class="has-submenu">Cards Creator <i class="fas fa-sort-down"></i></a>
              <ul class="submenu">
                <li><a href="{{ route('gestao.baralhos.listar') }}">{{ucfirst($langBaralho)}}</a></li>
                <li><a href="{{ route('gestao.baralhos.listar') }}">Carreiras</a></li>

              </ul>
          </li>
          <li><a href="{{ route('gestao.aplicacoes') }}">Hub Creator</a></li>
          <li>
            <a href="#" class="has-submenu">Sala de Estudos <i class="fas fa-sort-down"></i></a>
            <ul class="submenu">
              <li><a href="{{ route('gestao.artigos.index') }}">Artigos</a></li>
              <li><a href="#">Banco de Imagens</a></li>
              <li><a href="#">{{ucfirst($langDigital)}} Digital</li>
              <li><a href="{{ route('gestao.biblioteca') }}">Biblioteca</a></li>
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
              <li><a href="#">Planos de Estudos</a></li>
              <li><a href="{{config('app.url')}}/plano-de-aulas">Plano de Aula</a></li>
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
          <li>
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
              </ul>
          </li>
          <!-- fim do menu school -->
          @endif

          @if(in_array(Auth::user()->permissao, ["P"]))
          <!-- inicio do menu master -->
          <li><a href="{{ url('catalogo') }}">Início</a></li>
          <li>
            <a href="#" class="has-submenu">Comunidade <i class="fas fa-sort-down"></i></a>
             <ul class="submenu">
              <li><a href="{{ route('gestao.escola.mural', ['escola_id' => Auth::user()->escola_id]) }}">Mural da Escola</a></li>
              <li><a href="{{ route('gestao.turmas') }}">Mural da Turma</a></li>
              <li><a href="{{ route('gestao.escola.mural', ['escola_id' => Auth::user()->escola_id]) }}#alunos">Alunos</a></li>
              </ul>
          </li>
          <li><a href="{{config('app.url')}}/trilhas">Trilhas </a></li>
          <li><a href="{{ route('gestao.cursos') }}">{{ ucfirst($langCursoP) }}</a></li>
          <li><a href="{{route('gestao.cursoslivres')}}">Cursos livres</a></li>
          <li>
            <a href="#" class="has-submenu">Cast Creator <i class="fas fa-sort-down"></i></span>
              <ul class="submenu">
                <li><a href="{{ route('gestao.audios.listar') }}">Áudios</a></li>
                <li><a href="{{ route('gestao.albuns.listar') }}">Álbuns</a></li>
                <li><a href="{{ route('gestao.playlists.listar') }}">{{ucfirst($langPlaylist)}}s</a></li>
              </ul>
          </li>
          <li>
            <a href="#" class="has-submenu">Cards Creator <i class="fas fa-sort-down"></i></a>
              <ul class="submenu">
                <li><a href="{{ route('gestao.baralhos.listar') }}">{{ucfirst($langBaralho)}}</a></li>
                <li><a href="#">Carreiras</a></li>
              </ul>
          </li>
          <li><a href="{{ route('gestao.aplicacoes') }}">Hub Creator</a></li>
          <li>
            <a href="#" class="has-submenu">Sala de Estudos <i class="fas fa-sort-down"></i></a>
            <ul class="submenu">
              <li><a href="{{ route('gestao.artigos.index') }}">Artigos</a></li>
              <li><a href="#">Banco de Imagens</a></li>
              <li><a href="#">{{ucfirst($langDigital)}} Digital</a></li>
              <li><a href="{{ route('gestao.biblioteca') }}">Biblioteca</a></li>
              <li><a href="{{ route('gestao.glossario.index') }}">Glossário</a></li>
              <!--
                <li><a href="{{ route('gestao.questoes.listar') }}">Banco de itens</a></li>
                <li><a href="{{ route('gestao.teste.listar') }}">Teste de nivelamento</a></li>
                <li><a href="{{ route('gestao.plano-aulas.listar') }}">Plano de aulas</a></li> -->
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
            <li><a href="#">Planos de Estudos</a></li>
            <li><a href="{{config('app.url')}}/plano-de-aulas">Plano de Aula</a></li>
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
            </ul>
          </li>
          <li>
            <a href="#" class="has-submenu">Configurações <i class="fas fa-sort-down"></i></a>
            <ul class="submenu">
              <li><a href="{{ route('configuracao.trocar-email') }}">Alterar e-mail ou senha</a></li>
              <li><a href="#">Alterar dados cadastrais</a></li>
              <li><a href="#">Meus Pagamentos</a></li>
              <li><a href="#">Gerenciar Notificações</a></li>
            </ul>
          </li>

        @endif
          <li><a href="{{ url('/tutorial') }}"> Tutoriais</a></li>

        </ul>
  </div>
