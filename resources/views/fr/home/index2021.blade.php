@extends('fr/master')
@section('content')
<style>
    .googleWorkspace {
        width: 100%;
        border: 2px solid #ffffff;
        border-radius: 10px;
        margin: 6px 0;
    }
    .googleWorkspace .titulo{
        color: #ffffff;
        font-size: 14px;
        background-color: #e75a24;
        position: absolute;
        margin: -8px 0 0px 16px;
        padding: 0 11px 0 11px;
    }
</style>
<section id="banner" class="section">
    <div class="slide-banner">
        @if(auth()->user()->permissao != 'A')
            <a href="{{url('nem')}}" target="blank" class="item"><img src="{{ config('app.cdn').'/storage/banner_home/fixo_1.webp'}}" /></a>
        @else
            <a href="#" class="item"><img src="{{ config('app.cdn').'/storage/banner_home/fixo_2022.webp'}}" /></a>
        @endif
    @foreach($banner as $b)
            <a href="{{$b->url ?? '#'}}" @if($b->target_blanck == 1) target="blank" @endif class="item"><img src="{{ config('app.cdn').'/storage/banner_home/'.$b->img}}" /></a>
        @endforeach
    </div>
</section>
<script type="text/javascript">
    $('.slide-banner').slick({
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        cssEase: 'linear',
        arrows: true,
        autoplay: true,
        autoplaySpeed: 4500,
        responsive: [
            {
                breakpoint: 940,
                settings: {
                    dots: true,
                    arrows: false
                }
            }
        ]
    });
</script>
<section class="section atividade">

            <div class="container" style="margin-top: 80px; margin-bottom:30px">
                <div class="quadro-atividades  my-2">
                    <div class="googleWorkspace">
                        <div class="titulo">Google Workspace for Education</div>
                        <div class="row d-flex justify-content-between">
                            <div class="col-sm-6 col-md-2 my-4">
                                <a href="https://classroom.google.com/u/0/h" target="blank" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/google/classroom.webp" />
                                    <span class="title">Sala de Aula</span>
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-2 my-4">
                                <a href="https://drive.google.com/drive/u/0/my-drive" target="blank" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/google/drive.webp" />
                                    <span class="title">Meu Drive </span>
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-2 my-4">
                                <a href="https://calendar.google.com/calendar/u/0/r?tab=oc" target="blank" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/google/calendario.webp" />
                                    <span class="title">Calendário</span>
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-2 my-4">
                                <a href="https://mail.google.com/mail/u/0/?tab=om#inbox" target="blank" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/google/gmail.webp" />
                                    <span class="title">Gmail</span>
                                </a>
                            </div>
                            @if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')
                                <div class="col-sm-6 col-md-2 my-4">
                                    <a href="https://docs.google.com/presentation/u/0/?tgif=d" target="blank" class="item">
                                        <img src="{{config('app.cdn')}}/fr/imagens/icones/google/apresentacao.webp" />
                                        <span class="title">Apresentações</span>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-md-2 my-4">
                                    <a href="https://meet.google.com/" target="blank" class="item">
                                        <img src="{{config('app.cdn')}}/fr/imagens/icones/google/meet.webp" />
                                        <span class="title">Meet</span>
                                    </a>
                                </div>
                            @else
                                <div class="col-sm-6 col-md-2 my-4">
                                    <a href="https://docs.google.com/document/u/0/?tgif=d" target="blank" class="item">
                                        <img src="{{config('app.cdn')}}/fr/imagens/icones/google/documentos.webp" />
                                        <span class="title">Documentos</span>
                                    </a>
                                </div>
                                <!--
                                Recurso Inativado pelo Google
                                <div class="col-sm-6 col-md-2 my-4">
                                    <a href="https://jamboard.google.com/u/0/" target="blank" class="item">
                                        <img src="{{config('app.cdn')}}/fr/imagens/icones/google/jamboard.webp" />
                                        <span class="title">Jamboard</span>
                                    </a>
                                </div>
                                -->
                            @endif
                        </div>
                    </div>
                    <div class="row d-flex justify-content-between">
                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="{{url('colecao_livro')}}" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/livro_didático.png" />
                                <span class="title">Livros Digitais</span>
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="{{url('/infantil')}}" target="_blank" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/educacao_infantil.png" />
                                <span class="title">Educação Infantil</span>
                            </a>
                        </div>
                        @php
                            $instituicao = session('instituicao');
                        @endphp
                        @if($instituicao['tipo'] == 1 && 1==2) <!-- privada -->
                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="javascript:$('#formAgenda').submit()" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/agenda.png" />
                                <span class="title">Agenda</span>
                            </a>
                        </div>
                        @endif

                        @php
                        /*
                        retirado o SSO das avaliacoes
                            {{url('/provafacil/0')}}
                        */
                        @endphp
                               <!-- <div class="col-sm-6 col-md-2 my-4">
                                    <a target="blank" href="{{url('gestao/cursos')}}" class="item">
                                        <img src="{{config('app.cdn')}}/fr/imagens/icones/roteiro.png" />
                                        <span class="title">Roteiros</span>
                                    </a>
                                </div>
                            -->
                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="{{url('/editora/conteudos?conteudo=103')}}" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/jogos_atividades.png" />
                                <span class="title">Jogos</span>
                            </a>
                        </div>

                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="{{url('/quiz/colecao')}}" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/quizzes.png" />
                                <span class="title">Quizzes</span>
                            </a>
                        </div>

                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="{{url('/editora/conteudos/colecao?conteudo=3')}}" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/video_2.png" />
                                <span class="title">Vídeos</span>
                            </a>
                        </div>

                        @if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')
                            <div class="col-sm-6 col-md-2 my-4">
                                <a href="{{url('/editora/conteudos/colecao?conteudo=4')}}" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/apresentacoes1.png" />
                                    <span class="title">Apresentações</span>
                                </a>
                            </div>
                            @if($bncc)
                                <div class="col-sm-6 col-md-2 my-4">
                                    <a href="{{url('/editora/conteudos/colecao?conteudo=104')}}" class="item">
                                        <img src="{{config('app.cdn')}}/fr/imagens/icones/tabela_bncc.webp" />
                                        <span class="title">Tabelas BNCC</span>
                                    </a>
                                </div>
                            @endif
                        @endif
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
                            <div class="col-sm-6 col-md-2 my-4">
                                <a href="{{url('/editora/conteudos/colecao?conteudo=105')}}" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/icone_estrutura_trimestral.png" />
                                    <span class="title">Estrutura Trimestral</span>
                                </a>
                            </div>
                        @endif    
                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="{{url('editora/conteudos/colecao?conteudo=2')}}" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/audio.png" />
                                <span class="title">Áudios</span>
                            </a>
                        </div>

                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="{{ url('editora/conteudos/colecao?conteudo=100')}}" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/banco_imagens.png" />
                                <span class="title">Imagens</span>
                            </a>
                        </div>
                        @php
                            $escolaId = auth()->user()->escola_id ?? null;
                            $idColecao = 238; // O tipo de conteúdo desejado

                            $temColecao238 = false;

                            if ($escolaId) {
                                $temColecao238 = DB::table('users')
                                    ->join('colecao_livro_escola', 'users.escola_id', '=', 'colecao_livro_escola.escola_id')
                                    ->where('colecao_livro_escola.colecao_id', $idColecao)
                                    ->where('users.escola_id', $escolaId)                                    
                                    ->exists();
                            }
                        @endphp
                        @if($temColecao238 || auth()->user()->permissao == 'Z')   
                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="https://learn.eltngl.com/?_ga=2.199148079.1406641445.1695911952-1521637895.1695911950" class="item" target="_blank">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/icone_natgeo.png" />
                                <span class="title">Look</span>
                            </a>
                        </div>
                        @endif
                        @if($instituicao['id'] == 229 && auth()->user()->permissao == 'P' || auth()->user()->permissao == 'Z')
                            <div class="col-sm-6 col-md-2 my-4">
                                <a href="https://sites.google.com/opeteducation.com.br/acessoead-editoraopet-sp/in%C3%ADcio" class="item" target="_blank">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/ead.png" />
                                    <span class="title">EAD</span>
                                </a>
                            </div>
                        @endif
                        @if(auth()->user()->permissao == 'Z')
                            <div class="col-sm-6 col-md-2 my-4">
                                <a href="{{ url('/avaliacao_ead/gestao/avaliacao')}}" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/ead.png" />
                                    <span class="title">EAD - Gestão Avaliações</span>
                                </a>
                            </div>
                        @endif

                        @if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')
                            <div class="col-sm-6 col-md-2 my-4">
                                <a target="blank" href="{{ url('/gestao/roteiros')}}" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/plano_aula.png" />
                                    <span class="title">Roteiros</span>
                                </a>
                            </div>
                        @endif
                        <div class="col-sm-6 col-md-2 my-4">
                            @if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R' )
                                <a target="blank" href="{{ url('/gestao/trilhass')}}" class="item">
                            @else
                                <a target="blank" href="{{ url('/trilhas/listar')}}" class="item">
                            @endif
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/trilhas.png" />
                                <span class="title">Trilhas</span>
                            </a>
                        </div>
                        @php
                        /*
                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="{{ url('/agenda')}}" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/calendario.png" />
                                <span class="title">Calendário</span>
                            </a>
                        </div>
                        */
                        @endphp
                        @if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')
                            <?php
                            // Verifica se há registros na tabela colecao_prova_escola para a escola atual
                            $escola_id = auth()->user()->escola_id;
                            $registros = DB::table('colecao_prova_escola')->where('escola_id', $escola_id)->exists();
                            ?>

                            @if($registros)
                                <div class="col-sm-6 col-md-2 my-4">
                                    <a href="{{ url('editora/conteudos/colecao?conteudo=102')}}" class="item">
                                        <img src="{{config('app.cdn')}}/fr/imagens/icones/provas_bimestrais.png" />
                                        <span class="title">Avaliações</span>
                                    </a>
                                </div>
                            @endif

                        @endif
                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="{{url('/editora/conteudos?conteudo=101')}}" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/simuladores.png" />
                                <span class="title">Simuladores</span>
                            </a>
                        </div>
                        <!--
                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="{{ route('gestao.biblioteca') }}?catalogo=8" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/quizzes.png" />
                                <span class="title">Quiz</span>
                            </a>
                        </div>
                        -->
                        @if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')
                        <div class="col-sm-6 col-md-2 my-4">
                            <!--<a href="{{url('/editora/conteudos/colecao?conteudo=22')}}" class="item">-->
                                <a href="{{url('/editora/conteudos?conteudo=22')}}" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/documentos_oficiais.png" />
                                <span class="title">Documentos Oficiais</span>
                            </a>
                        </div>
                        @endif
                        <!--<div class="col-sm-6 col-md-2 my-4">
                            <a target="blank" href="https://escola.britannica.com.br/" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/brittanicca.png" />
                                <span class="title">Britannica Escola</span>
                            </a>
                        </div>
                        -->
                        @php
                            $instituicao = session('instituicao');
                        @endphp
                        @if($instituicao['tipo'] == 2 && auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R') <!-- publica -->
                            @if( isset($instituicao['permissao_ead']) && $instituicao['permissao_ead'] == 1)
                                <div class="col-sm-6 col-md-2 my-4">
                                    <a target="blank" href="https://uniopet.myopenlms.net/login/index.php" class="item">
                                        <img src="{{config('app.cdn')}}/fr/imagens/icones/ead.png" />
                                        <span class="title">EaD</span>
                                    </a>
                                </div>
                            @endif
                            @if( isset($instituicao['permissao_indica']) && $instituicao['permissao_indica'] == 1)
                                <div class="col-sm-6 col-md-2 my-4">
                                    <a href="#" class="item">
                                        <img src="{{config('app.cdn')}}/fr/imagens/icones/indica.png" />
                                        <span class="title">inDICA</span>
                                    </a>
                                </div>
                            @endif
                        @endif
                        @php
                        /*
                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="https://www-homol.opetinspira.com.br/sistemasolar/" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/sistema_solar.png" />
                                <span class="title">Planetário</span>
                            </a>
                        </div>

                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="https://www-homol.opetinspira.com.br/corpohumano/" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/sistemas_corpo_humano.png" />
                                <span class="title">Sistemas do Corpo Humano</span>
                            </a>
                        </div>
                        */
                        @endphp
                        @if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')
                        <div class="col-sm-6 col-md-2 my-4">
                            @if($instituicao['tipo'] == 2) <!-- publica -->
                                <a target="blank" href="https://editoraopet.com.br/blog_opet/category/educacao-publica/" class="item">
                            @else
                                <a target="blank" href="https://editoraopet.com.br/blog_opet/category/educacao-privada/" class="item">
                            @endif
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/blog.png" />
                                    <span class="title">Notícias Educacionais</span>
                                </a>
                        </div>
                        @endif

                        <div class="col-sm-6 col-md-2 mt-4">
                            <a href="https://cidades.ibge.gov.br/" target="blank" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/ibge.png" />
                                <span class="title">IBGE Cidades</span>
                            </a>
                        </div>

                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="{{url('/colecao_tutorial')}}" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/tutoriais.png" />
                                <span class="title">Tutoriais</span>
                            </a>
                        </div>

                        <!-- if($instituicao['tipo'] == 2)  publica -->
                        @if((auth()->user()->permissao  == 'Z')||(($instituicao['tipo'] == 2)&&((auth()->user()->permissao  == 'I')||(auth()->user()->permissao  == 'C')||(auth()->user()->permissao  == 'P'))))
                        <div class="col-sm-6 col-md-2 my-4">
                            <a href="{{url('/editora/conteudos/colecao?conteudo=106')}}" class="item">
                                <img src="{{config('app.cdn')}}/fr/imagens/icones/acao_destaque.png" />
                                <span class="title">Ação Destaque</span>
                            </a>
                        </div>
                        @endif

                            <div class="col-sm-6 col-md-2 my-4">
                                    <a href="{{url('/sistemasolar')}}" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/sistema_solar.png" />
                                    <span class="title">Sistema Solar</span>
                                </a>
                            </div>                        
                            <div class="col-sm-6 col-md-2 my-4">
                                <a href="{{url('/tabelaperiodica')}}" target="blank" class="item">
                                    <img src="/fr/imagens/icones/tabela_periodica.webp" />
                                    <span class="title">Tabela Periódica</span>
                                </a>
                            </div>                    
                        @if(auth()->user()->escola_id==14 && auth()->user()->permissao  == 'A')
                            <div class="col-sm-6 col-md-2 my-4">

                                    <a href="{{url('/indica/avaliacao')}}" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/prova_indica.png" />
                                    <span class="title">Prova INdica</span>
                                </a>
                            </div>
                        @endif
                        @if(auth()->user()->permissao  == 'Z')
                            <div class="col-sm-6 col-md-2 my-4">

                                    <a href="{{url('/indica/gestao/avaliacao')}}" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/prova_indica.png" />
                                    <span class="title">Gestão INdica</span>
                                </a>
                            </div>
                        @endif
                        @if(auth()->user()->escola_id==958 && auth()->user()->permissao  == 'Z')
                            <div class="col-sm-6 col-md-2 my-4">

                                    <a href="{{url('/gestao/relatorios/acessos')}}" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/relatorios.png" />
                                    <span class="title">Relatórios</span>
                                </a>
                            </div>

                        @endif
                        @if((auth()->user()->escola_id==958 || auth()->user()->escola_id==3082) && auth()->user()->permissao  != 'R' && auth()->user()->permissao  != 'A')
                            <div class="col-sm-6 col-md-2 my-4">
                                <a href="{{ url('/gestao/avaliacao/minhas_questoes')}}" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/banco_questoes.png" />
                                    <span class="title">Banco de Questões</span>
                                </a>
                            </div>
                        @endif

                        @if((auth()->user()->escola_id==958 || auth()->user()->escola_id==3082) && auth()->user()->permissao  != 'R' && auth()->user()->permissao  != 'A')
                            <div class="col-sm-6 col-md-2 my-4">
                                <a href="{{ url('gestao/avaliacao')}}" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/avaliacoes_interativas.png" />
                                    <span class="title">Minhas Avaliações</span>
                                </a>
                            </div>
                        @endif
                        @if((auth()->user()->escola_id==958 || auth()->user()->escola_id==3082) && (auth()->user()->permissao  == 'A'))
                            <div class="col-sm-6 col-md-2 my-4">
                                <a href="{{ url('avaliacao')}}" class="item">
                                    <img src="{{config('app.cdn')}}/fr/imagens/icones/avaliacoes_interativas.png" />
                                    <span class="title">Avaliações Interativas</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

<!-- MODAL VIDEO-->
<div class="modal fade" id="novo-topico" tabindex="-1" role="dialog" aria-labelledby="novo-topico" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title">Protocolo para a retomada das atividades presenciais.</h5>
            </div>
            <div class="modal-body p-0">
                <div class="embed-responsive embed-responsive-16by9">

                </div>
            </div>
            <div class="modal-footer">
                <p>Com os palhaços: Alípio e Sombinha</p>
            </div>
        </div>
    </div>
</div>
    <script>
            $('#novo-topico').on('show.bs.modal', function (event) {
            var modal = $(this)
            modal.find('.embed-responsive').html('<iframe class="embed-responsive-item" src="https://player.vimeo.com/video/508949205?autoplay=1&color=ffffff&title=0&byline=0&portrait=0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        });

            $('#novo-topico').on('hide.bs.modal', function (event) {
            var modal = $(this)
            modal.find('.embed-responsive').html('');
        });
    </script>
@stop
