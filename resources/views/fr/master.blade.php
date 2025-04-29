<!doctype html>
<html lang="pt">
	<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TSFWC7Z7Y1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-TSFWC7Z7Y1');
    </script>
        <!-- Required meta tags -->
        <meta name="title" content="Opet INspira" />
        <meta name="description" content="Opet Inspira" />
        <meta name="keywords" content="Opet Inspira" />
        <meta http-equiv="Content-Language" content="pt-br" />
        <meta name="robots" content="index, follow" />
        <meta name="author" content="Opet">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Open Graph / Facebook  Metas-->
        <meta
        property="og:image"
        content="{{ config('app.cdn') }}/fr/imagens/favicon.ico"
        />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://opetinspira.com.br/" />
        <meta property="og:title" content="Opet INspira - Plataforma Educacional" />
        <meta property="og:description" content="Opet INspira - Plataforma Educacional" />

        <!-- Twitter Metas-->
        <meta
        property="twitter:image"
        content="{{ config('app.cdn') }}/fr/imagens/favicon.ico"
        />
        <meta property="twitter:url" content="https://opetinspira.com.br/" />
        <meta property="twitter:title" content="Opet INspira - Plataforma Educacional" />
        <meta property="twitter:description" content="Opet INspira - Plataforma Educacional" />
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="Opet INspira - Plataforma Educacional" />
        <meta name="author" content="Edulabzz, Opet, Altitude" />

        <link rel = "canonical" href = "https://opetinspira.com.br/" /> 

        <link rel="icon" href="{{ config('app.cdn') }}/fr/imagens/favicon.ico" type="image/x-icon" />
	    <!-- Fonts -->
	    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/fonts/2021/stylesheet.css" type="text/css" charset="utf-8" />
	    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/fonts/fontawesome/css/all.min.css" type="text/css" charset="utf-8" />
	    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/js/slick/slick.css" type="text/css" charset="utf-8" />

	    <!-- JS -->
	    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/jquery/jquery-3.4.1.min.js"></script>
	    <script src="{{ config('app.cdn') }}/fr/includes/js/popper.min.js"></script>
	    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/bootstrap/bootstrap.min.js"></script>

	    <script src="{{ config('app.cdn') }}/fr/includes/js/slick/slick.min.js" type="text/javascript"></script>
	    <script src="{{ config('app.cdn') }}/fr/includes/js/jquery-mask/1.7.7/jquery.mask.min.js"></script>
	    <script src="{{ config('app.cdn') }}/fr/includes/js/form-validation.js"></script>
	    <script src="{{ config('app.cdn') }}/fr/includes/js/form-mask.js"></script>

	    <!-- SCRIPT DO CÓDIGO ANTIGO-->
		<script src="{{ config('app.cdn') }}/fr/includes/js/sweetalert.min.js"></script>
		<!-- FIM SCRIPT DO CÓDIGO ANTIGO-->

	    <!-- CSS -->
	    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/bootstrap/bootstrap.min.css">
        <style>
            /* icones do menu e sidebar*/
            .main-menu .main ul li .ico.home{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/home.webp) top left no-repeat; }
            .main-menu .main ul li .ico.conteudo{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/conteudo.webp) top left no-repeat; }
            .main-menu .main ul li .ico.educacao-infantil{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/educacao_infantil.webp) top left no-repeat; }
            .main-menu .main ul li .ico.agenda{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/agenda.webp) top left no-repeat; }
            .main-menu .main ul li .ico.apresentacoes{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/apresentacoes.webp) top left no-repeat; }
            .main-menu .main ul li .ico.audios{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/audios.webp) top left no-repeat; }
            .main-menu .main ul li .ico.banco-de-imagens{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/banco_de_imagens.webp) top left no-repeat; }
            .main-menu .main ul li .ico.banco-de-questoes{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/banco_de_questoes.webp) top left no-repeat; }
            .main-menu .main ul li .ico.britannica-escola{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/britannica_escola.webp) top left no-repeat; }
            .main-menu .main ul li .ico.dialogos-pedagogicos{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/dialogos_pedagogicos.webp) top left no-repeat; }
            .main-menu .main ul li .ico.documentos-oficiais{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/documentos_oficiais.webp) top left no-repeat; }
            .main-menu .main ul li .ico.ead{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/ead.webp) top left no-repeat; }
            .main-menu .main ul li .ico.escola{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/escola.webp) top left no-repeat; }
            .main-menu .main ul li .ico.gestor{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/gestor.webp) top left no-repeat; }
            .main-menu .main ul li .ico.indica{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/indica.webp) top left no-repeat; }
            .main-menu .main ul li .ico.jogos{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/jogos.webp) top left no-repeat; }
            .main-menu .main ul li .ico.livro-didatico{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/livro_didatico.webp) top left no-repeat; }
            .main-menu .main ul li .ico.mapas-geograficos{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/mapas_geograficos.webp) top left no-repeat; }
            .main-menu .main ul li .ico.noticias-educacionais{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/noticias_educacionais.webp) top left no-repeat; }
            .main-menu .main ul li .ico.plano-de-aula{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/plano_de_aula.webp) top left no-repeat; }
            .main-menu .main ul li .ico.professor{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/professor.webp) top left no-repeat; }
            .main-menu .main ul li .ico.provas-bimestrais{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/provas_bimestrais.webp) top left no-repeat; }
            .main-menu .main ul li .ico.quizzes{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/quizzes.webp) top left no-repeat; }
            .main-menu .main ul li .ico.realidade-virtual{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/realidade_virtual.webp) top left no-repeat; }
            .main-menu .main ul li .ico.roteiro{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/roteiro.webp) top left no-repeat; }
            .main-menu .main ul li .ico.sequencia-didatica{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/sequencia_didatica.webp) top left no-repeat; }
            .main-menu .main ul li .ico.simuladores{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/simuladores.webp) top left no-repeat; }
            .main-menu .main ul li .ico.sistema_solar{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/icone_sistema_solar.webp) top left no-repeat; }
            .main-menu .main ul li .ico.tabela_periodica{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/icone_tabela_periodica.webp) top left no-repeat; }
            .main-menu .main ul li .ico.sistema-do-corpo-humano{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/sistema_do_corpo_humano.webp) top left no-repeat; }
            .main-menu .main ul li .ico.sistema-solar{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/sistema_solar.webp) top left no-repeat; }
            .main-menu .main ul li .ico.tabela-periodica{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/tabela_periodica.webp) top left no-repeat; }
            .main-menu .main ul li .ico.estrutura_trimestral{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/icone_estrutura_trimestral.webp) top left no-repeat; }
            .main-menu .main ul li .ico.natgeo{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/natgeo.webp) top left no-repeat; }
            .main-menu .main ul li .ico.trilha{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/trilha.webp) top left no-repeat; }
            .main-menu .main ul li .ico.tutorial-pdf{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/tutorial_pdf.webp) top left no-repeat; }
            .main-menu .main ul li .ico.tutorial-videos{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/tutorial_videos.webp) top left no-repeat; }
            .main-menu .main ul li .ico.videos-01{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/videos_01.webp) top left no-repeat; }
            .main-menu .main ul li .ico.videos-02{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/videos_02.webp) top left no-repeat; }
            .main-menu .main ul li .ico.cadastro{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/cadastro.webp) top left no-repeat; }
            .main-menu .main ul li .ico.coletivo{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/coletivo.webp) top left no-repeat; }
            .main-menu .main ul li .ico.contato{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/contato.webp) top left no-repeat; }
            .main-menu .main ul li .ico.criacao{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/criacao.webp) top left no-repeat; }
            .main-menu .main ul li .ico.meus-entegaveis{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/meus_entregaveis.webp) top left no-repeat; }
            .main-menu .main ul li .ico.meus-quizzes{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/meus_quizzes.webp) top left no-repeat; }
            .main-menu .main ul li .ico.meus-roteiros{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/meus_roteiros.webp) top left no-repeat; }
            .main-menu .main ul li .ico.minhas-avaliacoes{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/minhas_avaliacoes.webp) top left no-repeat; }
            .main-menu .main ul li .ico.minhas-questoes{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/minhas_questoes.webp) top left no-repeat; }
            .main-menu .main ul li .ico.minhas-trilhas{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/minhas_trilhas.webp) top left no-repeat; }
            .main-menu .main ul li .ico.politica-privacidade{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/politica_privacidade.webp) top left no-repeat; }
            .main-menu .main ul li .ico.termos-uso{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/termos_uso.webp) top left no-repeat; }
            .main-menu .main ul li .ico.fale-conosco{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/fale_conosco.webp) top left no-repeat; }
            .main-menu .main ul li .ico.faq{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/faq.webp) top left no-repeat; }

            /* icones google */
            .main-menu .main ul li .ico.google-apresentacao{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/apresentacao.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-calendario{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/calendario.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-chat{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/chat.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-classroom{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/classroom.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-colaboracao{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/colaboracao.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-colecoes{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/colecoes.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-comunicacao{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/comunicacao.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-contato{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/contatos.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-documentos{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/documentos.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-drive{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/drive.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-ferramentas{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/ferramentas.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-formularios{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/formularios.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-fotos{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/fotos.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-gmail{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/gmail.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-grupos{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/grupos.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-jamboard{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/jamboard.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-keep{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/keep.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-maps{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/maps.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-meet{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/meet.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-noticias{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/noticias.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-planilhas{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/planilhas.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-sites{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/sites.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-youtube{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/youtube.webp) top left no-repeat; }
            .main-menu .main ul li .ico.google-senha{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/senha.png) top left no-repeat; }
            .main-menu .main ul li .ico.google-conta{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/google/conta.webp) top left no-repeat; }
            .main-menu .main ul li .ico.agenda{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/agenda.webp) top left no-repeat; }
            .main-menu .main ul li .ico.escolas{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/escolas.webp) top left no-repeat; }
            .main-menu .main ul li .ico.escolas{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/escolas.webp) top left no-repeat; }
            .main-menu .main ul li .ico.instituicao{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/instituicao.webp) top left no-repeat; }
            .main-menu .main ul li .ico.usuarios{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/usuarios.webp) top left no-repeat; }
            .main-menu .main ul li .ico.biblioteca{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/biblioteca.webp) top left no-repeat; }
            .main-menu .main ul li .ico.redinterativos{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/redinterativos.png) top left no-repeat; }
            .main-menu .main ul li .ico.livrointerativo{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/livro_interativo.png) top left no-repeat; }
            .main-menu .main ul li .ico.livrointerativo-painel{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/livro_interativo_painel.png) top left no-repeat; }
            .main-menu .main ul li .ico.relatorios{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/relatorios.webp) top left no-repeat; }
            .main-menu .main ul li .ico.tabelas-bncc{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/bncc.webp) top left no-repeat; }
            .main-menu .main ul li .ico.agenda-espaco-familia{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/agenda_espaco_familia.webp) top left no-repeat; }
            .main-menu .main ul li .ico.agenda-rotinas{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/agenda_rotinas.webp) top left no-repeat; }
            .main-menu .main ul li .ico.responsaveis{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/responsaveis.png) top left no-repeat; }
            .main-menu .main ul li .ico.turmas{ background: url({{config('app.cdn')}}/fr/imagens/2021/icones_sidebar/turmas.png) top left no-repeat; }




            /* imagens de fundo e rodapé */
            .section.atividade {
                background-image: url({{config('app.cdn')}}/fr/imagens/2021/deskFundoHome.webp), url({{config('app.cdn')}}/fr/imagens/2021/deskFundoHome-Back.webp);
                background-position: right top, right top;
                background-repeat: no-repeat, repeat-y;
            }
            .input-group-text.ico {
                background: url({{config('app.cdn')}}/fr/imagens/ico-groups-form.png) left top no-repeat;
            }
            .ico-groups {
                background: url({{config('app.cdn')}}/fr/imagens/2021/ico-groups_v1.png) no-repeat left top;
            }
            .ico-groups.amarelo {
                background: url({{config('app.cdn')}}/fr/imagens/2021/ico-groups-amarelo_v1.png) no-repeat left top;
            }

            .wrs_tickContainer {
                display: none !important;
            }
        </style>
        <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/2021/style_v5.css">
        <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/2021/style_2021_v1.css">

	    <title>Opet Inspira - Plataforma Educacional</title>



        <script type="text/javascript">
            var root = document.documentElement;
            // Tamanho da tela para posicionamento do rodapé
            root.style.setProperty('--tamanhoTela', window.innerHeight+'px');
            // Padroes de Cores
            root.style.setProperty('--corPrincipal', '#1d2345');
            root.style.setProperty('--corSecundaria', '#f8ad18');
            root.style.setProperty('--corMenu', '#f8ad18cc');

			$(document).ready(function(){

				$(document).bind("contextmenu",function(e){
					return false;
				});

				$('.ht-skip').children().css('height','50px'); /// div do acessivel em libras
				$('[data-toggle="tooltip"]').tooltip();
			});
		</script>
	</head>
	<body class="interna">
		@include('fr/layout2021/topo_menu')


		@if (session('certo'))
			<section class="section" style="margin-top: 50px">
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong>{!! session('certo') !!}</strong>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</section>
        @endif

        @if (session('erro') )
	        <section class="section" style="margin-top: 50px">
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>{!! session('erro') !!}</strong>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</section>
        @endif

        @if (session('info'))
	        <section style="margin-top: 50px">
				<div class="alert alert-info alert-dismissible fade show" role="alert">
					<strong>{{ session('info') }}</strong>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</section>
        @endif
        <div vw class="enabled">
            <div vw-access-button class="active"></div>
            <div vw-plugin-wrapper>
                <div class="vw-plugin-top-wrapper"></div>
            </div>
        </div>


		@yield('content')

		<footer class="section">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-auto">
						@php
							$escola = session('escola');
						@endphp
						<span class="school-title">
							@if($escola['capa']!='')
								<!--<img src="{{config('app.cdn')}}/{{$escola['capa']}}" />-->
							@endif
							{{$escola['titulo']}}
						</span>
					</div>
					<div class="col-md text-center">
						<a href="{{url('colecao_tutorial')}}">Tutoriais</a> &nbsp;•&nbsp; <a href="{{url('contato')}}">Fale conosco</a> &nbsp;•&nbsp; <a href="{{url('termos-de-uso')}}">Termos de uso</a> &nbsp;•&nbsp; <a href="{{url('politica-de-privacidade')}}">Política de privacidade</a>
					</div>
					<div class="col-auto">
                            <a target="blanck" href="https://editoraopet.com.br/"><i class="ico-groups i2 amarelo ml-1 mr-1"></i></a>
                            <a target="blanck" href="https://www.facebook.com/editora.opet.3"><i class="ico-groups i3 amarelo ml-1 mr-1"></i></a>
                            <a target="blanck" href="https://www.instagram.com/editoraopet/"><i class="ico-groups i4 amarelo ml-1 mr-1"></i></a>
                            <a target="blanck" href="https://www.youtube.com/channel/UCPXnBBshEViOKyv9EoVKRaQ"><i class="ico-groups i5 amarelo ml-1 mr-1"></i></a>
                            <a target="blanck" href="https://soundcloud.com/editoraopet/"><i class="ico-groups i6 amarelo ml-1 mr-1"></i></a>
                            <a target="blanck" href="https://br.pinterest.com/editoraopet/"><i class="ico-groups i7 amarelo ml-1 mr-1"></i></a>
                            <a target="blanck" href="https://www.linkedin.com/company/editoraopet/"><i class="ico-groups i8 amarelo ml-1 mr-1"></i></a>
					</div>
				</div>
			</div>
		</footer>

    <script>
        function confirmLogout()
        {
            swal({
                title: "Saindo",
                text: "Deseja sair da plataforma?",
                icon: "warning",
                buttons: ["Voltar", "Sair"],
                dangerMode: true,
            })
                .then((willLogout) =>
                {
                    if (willLogout)
                    {
                        if(document.getElementById("logout-form") != null)
                        {
                            document.getElementById("logout-form").submit();
                        }
                        else
                        {
                            console.error('Error: logout-form not found!');
                        }
                    }
                });
        }
    </script>
@if(env('APP_ENV') != 'local' && !str_contains(url()->current(), 'gestao/avaliacao') && !str_contains(url()->current(), '/indica/gestao/'))
	<!-- Acessibilidade em Libras -->
    <script src="{{config('app.cdn')}}/fr/includes/js/vlibras/vlibras-plugin.min.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>


	<!-- USERWAY acessibilidade -->
        <script>
            (function(d){
                var s = d.createElement("script");
                s.setAttribute("data-account", "km7YAnlPrA");
                s.setAttribute("src", "https://accessibilityserver.org/widget.js");
                (d.body || d.head).appendChild(s);
            })
            (document)
        </script>
        <noscript>Please ensure Javascript is enabled for purposes of <a href="https://accessibilityserver.org"website> accessibility</a></noscript>
@endif
    </body>
</html>
