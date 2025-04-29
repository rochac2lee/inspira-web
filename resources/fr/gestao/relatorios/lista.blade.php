@extends('fr/master')
@section('content')
<link rel="stylesheet" href="{{ asset('fr/includes/relatorios/css/style_relatorios.css') }}">
<section class="section section-interna relatorios">
    <div class="container">
        <div class="row mb-3" style="margin-top: -40px">
            <div class="col-md-12 text-center">
                <h3>Lista de Relatórios</h3>
            </div>
        </div>
        <div class="subtitle-page mt-0">Relatórios de Cadastros</div>

        <div class="col-md-12 mb-2 pl-0 pr-0">
            <div class="relat_lista">
                <a href="/gestao/relatorios/instituicao">
                   <div class="fundo bg_1"><img src="{{ asset('fr/includes/relatorios/imagens/ico_1.png') }}"></div>
                   <p class="mt-1">Instituições</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="{{url('/gestao/relatorios/acessos')}}">
                   <div class="fundo bg_2"><img src="{{ asset('fr/includes/relatorios/imagens/ico_2.png') }}"></div>
                   <p class="mt-1">Escolas</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="/gestao/relatorios/usuarios">
                   <div class="fundo bg_3"><img src="{{ asset('fr/includes/relatorios/imagens/ico_3.png') }}"></div>
                   <p class="mt-1">Usuários</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="/gestao/relatorios/biblioteca">
                   <div class="fundo bg_4"><img src="{{ asset('fr/includes/relatorios/imagens/ico_4.png') }}"></div>
                   <p class="mt-1">Biblioteca</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="{{url('/gestao/relatorios/qrcode')}}">
                   <div class="fundo bg_5"><img src="{{ asset('fr/includes/relatorios/imagens/ico_5.png') }}"></div>
                   <p class="mt-1">QR Code</p>
                </a>
            </div>
        </div>
        <!--
        <div class="subtitle-page">Relatórios de Conteúdos</div>
        <div class="col-md-12 mb-2 pl-0 pr-0">
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_6"><img src="{{ asset('fr/includes/relatorios/imagens/ico_7.png') }}"></div>
                   <p class="mt-1">Apresentação</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_7"><img src="{{ asset('fr/includes/relatorios/imagens/ico_8.png') }}"></div>
                   <p class="mt-1">Áudios</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_8"><img src="{{ asset('fr/includes/relatorios/imagens/ico_9.png') }}"></div>
                   <p class="mt-1">Banners</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_9"><img src="{{ asset('fr/includes/relatorios/imagens/ico_10.png') }}"></div>
                   <p class="mt-1">Jogos Digitais</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_10"><img src="{{ asset('fr/includes/relatorios/imagens/ico_11.png') }}"></div>
                   <p class="mt-1">Livros Digitais</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_1"><img src="{{ asset('fr/includes/relatorios/imagens/ico_12.png') }}"></div>
                   <p class="mt-1">Imagens</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_2"><img src="{{ asset('fr/includes/relatorios/imagens/ico_13.png') }}"></div>
                   <p class="mt-1">Quizzes</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_3"><img src="{{ asset('fr/includes/relatorios/imagens/ico_14.png') }}"></div>
                   <p class="mt-1">Questões</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_4"><img src="{{ asset('fr/includes/relatorios/imagens/ico_15.png') }}"></div>
                   <p class="mt-1">Simuladores</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_5"><img src="{{ asset('fr/includes/relatorios/imagens/ico_16.png') }}"></div>
                   <p class="mt-1">Trilhas</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_6"><img src="{{ asset('fr/includes/relatorios/imagens/ico_17.png') }}"></div>
                   <p class="mt-1">Vídeos</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_7"><img src="{{ asset('fr/includes/relatorios/imagens/ico_18.png') }}"></div>
                   <p class="mt-1">Provas Bimestrais</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_8"><img src="{{ asset('fr/includes/relatorios/imagens/ico_19.png') }}"></div>
                   <p class="mt-1">Avaliações INterativas</p>
                </a>
            </div>
        </div>

        <div class="subtitle-page">Objetos Educacionais Digitais</div>
        <div class="col-md-12 mb-2 pl-0 pr-0">
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_1"><img src="{{ asset('fr/includes/relatorios/imagens/ico_20.png') }}"></div>
                   <p class="mt-1">Avaliações INterativas</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_2"><img src="{{ asset('fr/includes/relatorios/imagens/ico_21.png') }}"></div>
                   <p class="mt-1">Roteiros</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_3"><img src="{{ asset('fr/includes/relatorios/imagens/ico_22.png') }}"></div>
                   <p class="mt-1">Cursos EaD</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_4"><img src="{{ asset('fr/includes/relatorios/imagens/ico_23.png') }}"></div>
                   <p class="mt-1">Documentos Oficiais</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_5"><img src="{{ asset('fr/includes/relatorios/imagens/ico_24.png') }}"></div>
                   <p class="mt-1">Tutoriais</p>
                </a>
            </div>
        </div>

        <div class="subtitle-page">Meus Relatórios</div>
        <div class="col-md-12 mb-2 pl-0 pr-0">
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_8"><img src="{{ asset('fr/includes/relatorios/imagens/ico_25.png') }}"></div>
                   <p class="mt-1">Lista de Inscrição</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_5"><img src="{{ asset('fr/includes/relatorios/imagens/ico_26.png') }}"></div>
                   <p class="mt-1">Minha Trilha</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_4"><img src="{{ asset('fr/includes/relatorios/imagens/ico_27.png') }}"></div>
                   <p class="mt-1">Roteiro Concluídos</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_6"><img src="{{ asset('fr/includes/relatorios/imagens/ico_28.png') }}"></div>
                   <p class="mt-1">Perfil de Inscrição</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_7"><img src="{{ asset('fr/includes/relatorios/imagens/ico_29.png') }}"></div>
                   <p class="mt-1">Status / Período de Inscrição</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_3"><img src="{{ asset('fr/includes/relatorios/imagens/ico_30.png') }}"></div>
                   <p class="mt-1">Nota Final</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_1"><img src="{{ asset('fr/includes/relatorios/imagens/ico_31.png') }}"></div>
                   <p class="mt-1">Quadro Geral de Nota</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_2"><img src="{{ asset('fr/includes/relatorios/imagens/ico_32.png') }}"></div>
                   <p class="mt-1">Tarefas não avaliadas</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_9"><img src="{{ asset('fr/includes/relatorios/imagens/ico_33.png') }}"></div>
                   <p class="mt-1">Histórico de Acesso</p>
                </a>
            </div>
        </div>


        <div class="subtitle-page">Outros Relatórios</div>
        <div class="col-md-12 mb-2 pl-0 pr-0">
            <div class="relat_lista">
                <a href="relatorios_acessos.php">
                   <div class="fundo bg_7"><img src="{{ asset('fr/includes/relatorios/imagens/ico_34.png') }}"></div>
                   <p class="mt-1">Acessos por Login</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_6"><img src="{{ asset('fr/includes/relatorios/imagens/ico_35.png') }}"></div>
                   <p class="mt-1">Livros Digitais INterativos</p>
                </a>
            </div>
            <div class="relat_lista">
                <a href="#">
                   <div class="fundo bg_7"><img src="{{ asset('fr/includes/relatorios/imagens/ico_36.png') }}"></div>
                   <p class="mt-1">Totais de OED</p>
                </a>
            </div>
        </div>
        <p><br></p>
        -->
    </div>
</section>
@stop
