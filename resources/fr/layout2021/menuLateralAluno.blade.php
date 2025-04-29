<li><a href="{{url('/catalogo')}}"><div class="ico home"></div>Home</a></li>
<li><a href="javascript:void(0)" class="has-submenu"><div class="ico conteudo"></div>Conteúdos <i class="fas fa-sort-down"></i></a>
    <ul class="submenu">
        <li><a href="{{url('/editora/conteudos/colecao?conteudo=2')}}"><div class="ico audios"></div>Áudios</a></li>
        <!--<li><a href="#"><div class="ico entregaveis"></div>Avaliações</a></li>-->
        <!--<li><a target="blank" href="https://escola.britannica.com.br/"><div class="ico britannica-escola"></div>Britannica Escola</a></li>-->
        <li><a href="{{ url('editora/conteudos/colecao?conteudo=100')}}"><div class="ico banco-de-imagens"></div>Imagens</a></li>
        <li><a href="{{url('/editora/conteudos?conteudo=103')}}"><div class="ico jogos"></div>Jogos Digitais</a></li>
        <li><a href="{{url('colecao_livro')}}"><div class="ico livro-didatico"></div>Livros Digitais</a></li>
        <!--<li><a href="#"><div class="ico duvidas"></div>Planetário</a></li>-->
        <li><a href="{{url('/quiz/colecao')}}"><div class="ico quizzes"></div>Quizzes</a></li>
        <li><a href="{{url('/editora/conteudos?conteudo=101')}}"><div class="ico simuladores"></div>Simuladores</a></li>
        <!--<li><a href="#"><div class="ico duvidas"></div>Sistemas do corpo humano</a></li>-->
        <li><a target="blank" href="{{ url('/trilhas/listar')}}"><div class="ico trilha"></div>Trilhas</a></li>
        <li><a href="{{url('/sistemasolar')}}" target="blank_"><div class="ico sistema_solar"></div>Sistema Solar</a></li>
        <li><a href="{{url('/tabelaperiodica')}}" target="blank_"><div class="ico tabela_periodica"></div>Tabela Periódica</a></li>
        <li><a href="{{url('colecao_tutorial')}}"><div class="ico tutorial-videos"></div>Tutoriais</a></li>
        <li><a href="{{url('/editora/conteudos/colecao?conteudo=3')}}"><div class="ico videos-02"></div>Vídeos</a></li>
    </ul>
</li>
<!--
<li><a href="javascript:void(0)" class="has-submenu"><div class="ico sala"></div>Colaboração <i class="fas fa-sort-down"></i></a>
    <ul class="submenu">
        <li><a href="#"><div class="ico agenda"></div>Agenda</a></li>
        <li><a href="#"><div class="ico entregaveis"></div>Mural da escola</a></li>
    </ul>
</li>
-->
<li><a href="javascript:void(0)"  class="has-submenu"><div class="ico contato"></div>Contato <i class="fas fa-sort-down"></i></a>
    <ul class="submenu">
        <li><a href="{{url('contato')}}"><div class="ico fale-conosco"></div>Fale Conosco</a></li>
        <li><a href="{{url('contato')}}"><div class="ico faq"></div>FAQ</a></li>
    </ul>
</li>
<li>
    <a href="javascript:void(0)"  class="has-submenu"><div class="ico coletivo"></div>Coletivos <i class="fas fa-sort-down"></i></a>
    <ul class="submenu">
        <li><a href="{{url('politica-de-privacidade')}}"><div class="ico politica-privacidade"></div>Política de privacidade</a></li>
        <li><a href="{{url('termos-de-uso')}}"><div class="ico termos-uso"></div>Termos de uso</a></li>
    </ul>
</li>
