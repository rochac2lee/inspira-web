@php
    $instituicao = session('instituicao');

@endphp
<li><a href="{{url('/catalogo')}}"><div class="ico home"></div>Início</a></li>
<!--
    Bloqueio Cadastros p/ Instituição
    119 SME SANTA TEREZINHA DE ITAIPU
    196 SEDUC CHAPECÓ
-->
@if(auth()->user()->permissao=='Z' && auth()->user()->instituicao_id!=119 && auth()->user()->instituicao_id!=196)
    <li><a href="javascript:void(0)"  class="has-submenu"><div class="ico cadastro"></div>Cadastros <i class="fas fa-sort-down"></i></a>
        <ul class="submenu">
            <li><a href="{{url('/gestao/instituicao')}}"><div class="ico instituicao"></div>Instituições</a></li>
            <li><a href="{{url('/gestao/escolas')}}"><div class="ico escolas"></div>Escolas</a></li>
            <li><a href="{{url('/gestao/usuario')}}"><div class="ico usuarios"></div>Usuários</a></li>
            <li><a href="{{url('/gestao/biblioteca')}}"><div class="ico biblioteca"></div>Biblioteca</a></li>
            <li><a href="https://livrosdigitais.opetinspira.com.br/admin" target="blank"><div class="ico livrointerativo-painel"></div>Painel Adm. LDI</a></li>
            <li><a href="{{url('/gestao/qrcode')}}"><div class="ico biblioteca"></div>QR Code</a></li>
            <li><a href="https://3d.opetinspira.com/dashboard/#/" target="blank"><div class="ico redinterativos"></div>RED INterativos</a></li>
            @if(auth()->user()->escola_id==958 && auth()->user()->permissao  == 'Z')
                <li><a href="{{url('/gestao/relatorios')}}"><div class="ico relatorios"></div>Relatórios</a></li>
            @endif
        </ul>
    </li>
    <li><a href="javascript:void(0)"  class="has-submenu"><div class="ico agenda"></div>App INspira Agenda <i class="fas fa-sort-down"></i></a>
        <ul class="submenu">
            <li><a href="{{url('/gestao/agenda/familia')}}"><div class="ico agenda-espaco-familia"></div>Espaço da Família</a></li>
            <li><a href="{{url('/gestao/agenda/registros/rotinas/opet')}}"><div class="ico agenda-rotinas"></div>Rotinas Tarefas e Atividades</a></li>
        </ul>
    </li>

@endif
<!--
    Bloqueio Cadastros p/ Instituição
    119 SME SANTA TEREZINHA DE ITAIPU
    196 SEDUC CHAPECÓ
-->
@if(auth()->user()->permissao=='I' && auth()->user()->instituicao_id!=119 && auth()->user()->instituicao_id!=196)
    <li><a href="javascript:void(0)"  class="has-submenu"><div class="ico cadastro"></div>Cadastros <i class="fas fa-sort-down"></i></a>
        <ul class="submenu">
            <li><a href="{{url('/gestao/escolas')}}"><div class="ico escolas"></div>Escolas</a></li>
        </ul>
    </li>
@endif
<!--
    Bloqueio Cadastros p/ Instituição
    119 SME SANTA TEREZINHA DE ITAIPU
    196 SEDUC CHAPECÓ
-->
@if(auth()->user()->permissao=='C' && auth()->user()->instituicao_id!=119 && auth()->user()->instituicao_id!=196)
    <li><a href="javascript:void(0)"  class="has-submenu"><div class="ico cadastro"></div>Cadastros <i class="fas fa-sort-down"></i></a>
        <ul class="submenu">
            <li><a href="{{url('/gestao/escola/'.auth()->user()->escola_id.'/turmas')}}"><div class="ico turmas"></div>Turmas</a></li>
            <li><a href="{{url('/gestao/escola/'.auth()->user()->escola_id.'/responsaveis/')}}"><div class="ico responsaveis"></div>Responsáveis</a></li>
        </ul>
    </li>
@endif
<li><a href="javascript:void(0)" class="has-submenu"><div class="ico conteudo"></div>Conteúdos <i class="fas fa-sort-down"></i></a>
    <ul class="submenu">
        <li><a href="{{url('/editora/conteudos/colecao?conteudo=4')}}"><div class="ico apresentacoes"></div>Apresentações</a></li>
        <li><a href="{{url('/editora/conteudos/colecao?conteudo=2')}}"><div class="ico audios"></div>Áudios</a></li>
        <!--<li><a href="#"><div class="ico entregaveis"></div>Avaliações</a></li>-->
        <!--<li><a target="blank" href="https://escola.britannica.com.br/"><div class="ico britannica-escola"></div>Britannica Escola</a></li>-->
        <li><a href="{{url('/editora/conteudos?conteudo=22')}}"><div class="ico documentos-oficiais"></div>Documentos Oficiais</a></li>
        <li><a href="{{ url('editora/conteudos/colecao?conteudo=100')}}"><div class="ico banco-de-imagens"></div>Imagens</a></li>
        <li><a href="{{url('/editora/conteudos?conteudo=103')}}"><div class="ico jogos"></div>Jogos Digitais</a></li>
        <li><a href="{{url('colecao_livro')}}"><div class="ico livro-didatico"></div>Livros Digitais</a></li>
        @if(auth()->user()->escola_id==958)
            <li><a href="https://livrosdigitais.opetinspira.com.br/index.php?livro=94" target="blank"><div class="ico livrointerativo"></div>Livros Digitais INterativos</a></li>
        @endif
        @if($instituicao['tipo'] == 2) <!-- publica -->
            <li><a target="blank" href="http://www.editoraopet.com.br/blog/category/educacao-publica/"><div class="ico noticias-educacionais"></div>Notícias Educacionais</a></li>
        @else
            <li><a target="blank" href="http://www.editoraopet.com.br/blog/category/educacao-privada/"><div class="ico noticias-educacionais"></div>Notícias Educacionais</a></li>
        @endif
        <!--<li><a href="#"><div class="ico duvidas"></div>Planetário</a></li>-->
        <li><a href="{{ url('editora/conteudos/colecao?conteudo=102')}}"><div class="ico provas-bimestrais"></div>Provas Bimestrais</a></li>
        <li><a href="{{url('/quiz/colecao')}}"><div class="ico quizzes"></div>Quizzes</a></li>
        <li><a href="{{url('/editora/conteudos?conteudo=101')}}"><div class="ico simuladores"></div>Simuladores</a></li>
        <li><a href="{{url('/sistemasolar')}}" target="blank_"><div class="ico sistema_solar"></div>Sistema Solar</a></li>
        <li><a href="{{url('/tabelaperiodica')}}" target="blank_"><div class="ico tabela_periodica"></div>Tabela Periódica</a></li>
        <li><a target="blank" href="{{url('/gestao/roteiros')}}"><div class="ico roteiro"></div>Roteiros de Estudo</a></li>
        <!--<li><a href="#"><div class="ico duvidas"></div>Sistemas do corpo humano</a></li>-->
        <li><a target="blank" href="{{ url('/gestao/trilhass')}}"><div class="ico trilha"></div>Trilhas de Aprendizagem</a></li>
        <li><a href="{{url('colecao_tutorial')}}"><div class="ico tutorial-videos"></div>Tutoriais</a></li>
        <li><a href="{{url('/editora/conteudos/colecao?conteudo=3')}}"><div class="ico videos-02"></div>Vídeos</a></li>
    </ul>
</li>
<!--
<li><a href="javascript:void(0)" class="has-submenu"><div class="ico sala"></div>Colaboração <i class="fas fa-sort-down"></i></a>
   <ul class="submenu">
       <li><a href="#"><div class="ico agenda"></div>Agenda</a></li>
      <li><a href="#"><div class="ico entregaveis"></div>Dúvidas dos estudantes</a></li>
       <li><a href="#"><div class="ico entregaveis"></div>Mural da escola</a></li>

    </ul>
</li>
-->
<li><a href="javascript:void(0)"  class="has-submenu"><div class="ico criacao"></div>Criação <i class="fas fa-sort-down"></i></a>
    <ul class="submenu">
        <!--<li><a href="{{url('')}}"><div class="ico plano-aulas"></div>Meus entregáveis</a></li>-->
        <li><a href="{{url('/gestao/quiz')}}"><div class="ico meus-quizzes"></div>Meus quizzes</a></li>
        <li><a target="blank" href="{{url('/gestao/roteiros')}}"><div class="ico meus-roteiros"></div>Meus roteiros</a></li>
        <li><a target="blank" href="{{url('/gestao/trilhass')}}"><div class="ico minhas-trilhas"></div>Minhas trilhas</a></li>
        @if(auth()->user()->permissao=='Z')
            <li><a href="{{url('/gestao/avaliacao')}}"><div class="ico minhas-avaliacoes"></div>Minhas avaliações</a></li>
        @endif
        @if(auth()->user()->escola_id==958)
            <li><a href="{{url('/gestao/avaliacao/minhas_questoes')}}"><div class="ico minhas-questoes"></div>Banco de Questões</a></li>
        @endif
    </ul>
</li>
<!--
    Bloqueio Agenda p/ Instituição
    119 SME SANTA TEREZINHA DE ITAIPU
    196 SEDUC CHAPECÓ
-->
@if((auth()->user()->permissao=='I' || auth()->user()->permissao=='C') && auth()->user()->instituicao_id!=119 && auth()->user()->instituicao_id!=196)
    <li><a href="{{url('/gestao/agenda/familia')}}"><div class="ico agenda"></div>App INspira Agenda</a></li>
@endif
<!--
    Liberação Agenda p/ Docentes Escolas:
    1022 PINGUINHO DE GENTE - COHAJAP
    1023 PINGUINHO DE GENTE - RENASCENÇA
    14 EDITORA OPET - PRIVADO
    2191 ESCOLA AGENDA
    Liberação Agenda p/ Docentes Instituições:
    490 - Agenda Tecnologia Educacional - Editorial
-->
@if((auth()->user()->instituicao_id==490 && auth()->user()->permissao=='P') || (auth()->user()->escola_id==2191 && auth()->user()->permissao=='P') || (auth()->user()->escola_id==14 && auth()->user()->permissao=='P') || (auth()->user()->escola_id==1022 && auth()->user()->permissao=='P') || (auth()->user()->escola_id==1023 && auth()->user()->permissao=='P'))
    <li><a href="{{url('/gestao/agenda/familia')}}"><div class="ico agenda"></div>App INspira Agenda</a></li>
@endif
<li>
    <a href="javascript:void(0)" class="has-submenu"><div class="ico google-conta"></div>Google for Education<i class="fas fa-sort-down"></i></a>

        <ul class="submenu">
            <li><a href="https://classroom.google.com/" target="blank"><div class="ico google-classroom"></div>Sala de aula</a></li>
            <li><a href="https://docs.google.com/document/" target="blank"><div class="ico google-documentos"></div>Documentos</a></li>
            <li><a href="https://docs.google.com/spreadsheets/" target="blank"><div class="ico google-planilhas"></div>Planilhas</a></li>
            <li><a href="https://docs.google.com/presentation/" target="blank"><div class="ico google-apresentacao"></div>Apresentações</a></li>
            <li><a href="https://drive.google.com/" target="blank"><div class="ico google-drive"></div>Drive</a></li>
            <li><a href="https://docs.google.com/forms/" target="blank"><div class="ico google-formularios"></div>Formulários</a></li>
            <li><a href="https://jamboard.google.com/" target="blank"><div class="ico google-jamboard"></div>Jamboard</a></li>
            <li><a href="https://mail.google.com/" target="blank"><div class="ico google-gmail"></div>Gmail</a></li>
            <li><a href="https://meet.google.com/" target="blank"><div class="ico google-meet"></div>Meet</a></li>
            <li><a href="https://chat.google.com/" target="blank"><div class="ico google-chat"></div>Chat</a></li>
            <li><a href="https://contacts.google.com/" target="blank"><div class="ico google-contato"></div>Contatos</a></li>
            <li><a href="https://groups.google.com/" target="blank"><div class="ico google-grupos"></div>Grupos</a></li>
            <li><a href="https://news.google.com/" target="blank"><div class="ico google-noticias"></div>Notícias</a></li>
            <li><a href="https://keep.google.com/" target="blank"><div class="ico google-keep"></div>Keep</a></li>
            <li><a href="https://calendar.google.com/calendar/" target="blank"><div class="ico google-calendario"></div>Calendário</a></li>
            <li><a href="https://sites.google.com/" target="blank"><div class="ico google-sites"></div>Sites</a></li>
            <li><a href="https://www.youtube.com/channel/UCPXnBBshEViOKyv9EoVKRaQ" target="blank"><div class="ico google-youtube"></div>Youtube</a></li>
            <li><a href="https://www.google.com.br/maps/" target="blank"><div class="ico google-maps"></div>Maps</a></li>
            <li><a href="https://photos.google.com/" target="blank"><div class="ico google-fotos"></div>Fotos</a></li>
            <li><a href="https://myaccount.google.com/" target="blank"><div class="ico google-conta"></div>Conta</a></li>
            <li><a href="https://myaccount.google.com/signinoptions/password" target="blank"><div class="ico google-senha"></div>Mudar Senha Google</a></li>
        </ul>

</li>

<li><a href="javascript:void(0)"  class="has-submenu"><div class="ico contato"></div>Contato <i class="fas fa-sort-down"></i></a>
    <ul class="submenu">
        <li><a href="{{url('contato')}}"><div class="ico fale-conosco"></div>Fale Conosco</a></li>
        <li><a href="{{url('contato')}}"><div class="ico faq"></div>FAQ</a></li>
    </ul>
</li>
<li>
    <a href="javascript:void(0)"  class="has-submenu"><div class="ico coletivo"></div>Coletivos <i class="fas fa-sort-down"></i></a>
    <ul class="submenu">
        @if($instituicao['tipo'] == 2)
            @if( isset($instituicao['permissao_ead']) && $instituicao['permissao_ead'] == 1)
                <li><a target="blank" href="https://uniopet.myopenlms.net/login/index.php"><div class="ico ead"></div>EaD</a></li>
            @endif
            @if( isset($instituicao['permissao_indica']) && $instituicao['permissao_indica'] == 1)
                <li><a href="https://indicaopet.com.br" target="blank"><div class="ico indica"></div>Indica</a></li>
            @endif
        @endif
        <li><a href="{{url('politica-de-privacidade')}}"><div class="ico politica-privacidade"></div>Política de privacidade</a></li>
        <li><a href="{{url('termos-de-uso')}}"><div class="ico termos-uso"></div>Termos de uso</a></li>
    </ul>
</li>
