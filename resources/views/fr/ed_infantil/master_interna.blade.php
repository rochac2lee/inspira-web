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

	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <link rel="icon" href="{{ config('app.cdn') }}/fr/imagens/favicon.png" type="image/x-icon" />

	    <!-- Fonts -->
	    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/ed_infantil/includes/fonts/stylesheet.css?v=006" type="text/css" charset="utf-8" />
	    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/fonts/fontawesome/css/all.min.css" type="text/css" charset="utf-8" />
	    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/js/slick/slick.css" type="text/css" charset="utf-8" />

	    <!-- JS -->
	    <!-- <script type="text/javascript" src="includes/js/jquery/jquery-3.4.1.min.js"></script> -->

	    <!-- Plugin para a Ordenacao -->
	    <script src="{{ config('app.cdn') }}/fr/includes/js/jquery/jquery-1.12.4.js"></script>
	    <script src="{{ config('app.cdn') }}/fr/includes/js/jquery/jquery-ui.js"></script>

	    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/bootstrap/popper.min.js"></script>
	    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/bootstrap/bootstrap.min.js"></script>


	    <script src="{{ config('app.cdn') }}/fr/includes/js/slick/slick.min.js" type="text/javascript"></script>
	    <script src="{{ config('app.cdn') }}/fr/includes/js/jquery-mask/1.7.7/jquery.mask.min.js"></script>
	    <script src="{{ config('app.cdn') }}/fr/includes/js/form-validation.js"></script>
	    <script src="{{ config('app.cdn') }}/fr/includes/js/form-mask.js"></script>

	    <!-- CSS -->
        <style>
            .ico-groups {
                background: url({{config('app.cdn')}}/fr/ed_infantil/imagens/ico-groups-cinza_v1.png) no-repeat left top;
            }
        </style>
	    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/bootstrap/bootstrap.min.css">
	    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/ed_infantil/includes/css/educacao_infantil_v2.css">

	    <title>Opet Inspira - Plataforma Educacional</title>

	    <script>
	        $(document).ready(function(){
	          	$('[data-toggle="tooltip"]').tooltip();

		        $(document).bind("contextmenu",function(e){
					return false;
				});

				$('.ht-skip').children().css('height','50px');
			});
	    </script>
	</head>
	<body class="home interna {{$classe}}">
		<section class="section topo">
			<div class="row topo">
				<div class="row menu-topo not-mobile">
					<div class="menu menu-1 sabido">
						<div class="hover no-mobile">
							<i class="fas fa-volume-up mt-4"></i>
							<audio class="audio" src="{{ config('app.cdn') }}/fr/ed_infantil/audio/1_para_ficar_mais_sabido.mp3" autostart="false" ></audio>
						</div>
						<a href="{{url('infantil/colecao/1')}}"><div class="menu_chamada"></div></a>
					</div>
					<div class="menu menu-2 historia">
						<div class="hover no-mobile">
							<i class="fas fa-volume-up mt-4"></i>
							<audio class="audio" src="{{ config('app.cdn') }}/fr/ed_infantil/audio/2_uma_historia_por_favor.mp3" autostart="false" ></audio>
						</div>
						<a href="{{url('infantil/colecao/2')}}"><div class="menu_chamada"></div></a>
					</div>
					<div class="menu menu-3 canta">
						<div class="hover no-mobile">
							<i class="fas fa-volume-up mt-4"></i>
							<audio class="audio" src="{{ config('app.cdn') }}/fr/ed_infantil/audio/3_canta_canta_minha_gente.mp3" autostart="false" ></audio>
						</div>
						<a href="{{url('infantil/colecao/3')}}"><div class="menu_chamada"></div></a>
					</div>
					<div class="menu menu-4 brincadeira">
						<div class="hover no-mobile">
							<i class="fas fa-volume-up mt-4"></i>
							<audio class="audio" src="{{ config('app.cdn') }}/fr/ed_infantil/audio/4_brincadeira_divertida.mp3" autostart="false" ></audio>
						</div>
						<a href="{{url('infantil/colecao/4')}}"><div class="menu_chamada"></div></a>
					</div>
					<div class="menu menu-5 massa">
						<div class="hover no-mobile">
							<i class="fas fa-volume-up mt-4"></i>
							<audio class="audio" src="{{ config('app.cdn') }}/fr/ed_infantil/audio/5_maos_na_massa.mp3" autostart="false" ></audio>
						</div>
						<a href="{{url('infantil/colecao/5')}}"><div class="menu_chamada"></div></a>
					</div>
					@if(auth()->user()->permissao != 'A' && 1 == 2)
						<div class="menu menu-6 professor">
							<div class="hover no-mobile">
								<i class="fas fa-volume-up mt-4"></i>
								<audio class="audio" src="{{ config('app.cdn') }}/fr/ed_infantil/audio/6_docente.mp3" autostart="false" ></audio>
							</div>
							<a href="{{url('infantil/colecao_professor')}}"><div class="menu_chamada"></div></a>
						</div>
					@endif
				</div>
				<button class="menu-icon" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
				<div class="main-menu" id="sidebar">
                    <button class="close-menu" onclick="toggleSidebar()">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="main">
                        <ul class="menu-list">
                            <li><a href="{{url('infantil')}}"><img class="ico" src="{{config('app.cdn')}}/fr/ed_infantil/imagens/icones_sidebar/7.png"> HOME</a></li>
                            <li><a href="{{url('infantil/colecao/1')}}"><img class="ico" src="{{config('app.cdn')}}/fr/ed_infantil/imagens/icones_sidebar/1.png"> PARA FICAR MAIS SABIDO</a></li>
                            <li><a href="{{url('infantil/colecao/2')}}"><img class="ico" src="{{config('app.cdn')}}/fr/ed_infantil/imagens/icones_sidebar/2.png"> UMA HISTÓRIA, POR FAVOR!</a></li>
                            <li><a href="{{url('infantil/colecao/3')}}"><img class="ico" src="{{config('app.cdn')}}/fr/ed_infantil/imagens/icones_sidebar/3.png"> CANTA, CANTA MINHA GENTE!</a></li>
                            <li><a href="{{url('infantil/colecao/4')}}"><img class="ico" src="{{config('app.cdn')}}/fr/ed_infantil/imagens/icones_sidebar/4.png"> BRINCADEIRA DIVERTIDA</a></li>
                            <li><a href="{{url('infantil/colecao/5')}}"><img class="ico" src="{{config('app.cdn')}}/fr/ed_infantil/imagens/icones_sidebar/5.png"> MÃO NA MASSA</a></li>
                            @if(auth()->user()->permissao != 'A' && 1 == 2)
                            	<li><a href="{{url('infantil/colecao_professor')}}"><img class="ico" src="{{config('app.cdn')}}/fr/ed_infantil/imagens/icones_sidebar/6.png"> PROFESSOR</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
				<div class="col col-logo">
					<a href="{{url('infantil')}}" class="logo"><img src="{{ config('app.cdn') }}/fr/ed_infantil/imagens/logo.svg"></a>
					<h1 class="titulo not-mobile"><img src="{{ config('app.cdn') }}/fr/ed_infantil/imagens/h1_educacao_infantil.png"></h1>
				</div>
				<div class="col-md-auto text-right user_search">
					<a href="#" class="user-area">
                        <span class="username text-truncate no-mobile">{{ ucwords(Auth::user()->name) }}</span>
                        <span class="avatar">
                            @if(auth()->user()->avatar_social!='')
                                <img src="{{auth()->user()->avatar_social}}" />
                            @elseif(auth()->user()->img_perfil!='')
                                <img src="{{config('app.cdn')}}/storage/uploads/usuarios/perfil/{{auth()->user()->img_perfil}}" />
                            @else
                                <img src="{{config('app.cdn')}}/fr/imagens/avatar-user.png" />
                            @endif
                        </span>
                    </a>
                    <form class="buscar mt-5" method="get" action="">
						<div class="form-inline">
							<input type="text" name="busca" value="{{request()->get('busca')}}" class="form-control" placeholder="BUSCAR" style="box-shadow:none;">
							<button type="submit"><img src="{{ config('app.cdn') }}/fr/ed_infantil/imagens/ico_buscar.png"></button>
						</div>
					</form>
				</div>
			</div>
		</section>

		<script type="text/javascript">
	        $(document).ready(function(){

	        	$( ".banner" ).click(function() {
	                $('#novo-topico').modal('show')
	            });


	        	var audioElement = document.createElement('audio');
	            /* seleciona item */
	            $( ".menu .hover" ).click(function() {
	                var sound = $(this).find(".audio");
	          		audioElement.setAttribute('src', sound.attr('src'));
	          		audioElement.play();
	            });
	        });

			function toggleSidebar() {
				document.getElementById("sidebar").classList.toggle("active");
			}
	    </script>
        <div vw class="enabled">
        	<div vw-access-button class="active"></div>
        	<div vw-plugin-wrapper>
        		<div class="vw-plugin-top-wrapper"></div>
        	</div>
        </div>
		@yield('content')

		<footer class="section">
			<div class="container-fluid">
				<div class="row">
                    <div class="col-md-7 pt-4 text-center">
                        <a href="{{url('contato/infantil')}}">Fale conosco</a> &nbsp;&nbsp; | &nbsp;&nbsp; <a href="{{url('termos-de-uso')}}">Termos de uso</a> &nbsp;&nbsp; | &nbsp;&nbsp; <a href="{{url('politica-de-privacidade')}}">Política de privacidade</a>
                    </div>
                    <div class="col-md-auto mt-2 text-right">
                        <a target="blanck" href="https://www.linkedin.com/company/editoraopet/"><i class="ico-groups i8 amarelo mr-0"></i></a>
                        <a target="blanck" href="https://br.pinterest.com/editoraopet/"><i class="ico-groups i7 amarelo mr-0"></i></a>
                        <a target="blanck" href="https://soundcloud.com/editoraopet/"><i class="ico-groups i6 amarelo mr-0"></i></a>
                        <a target="blanck" href="https://www.youtube.com/channel/UCPXnBBshEViOKyv9EoVKRaQ"><i class="ico-groups i5 amarelo mr-0"></i></a>
                        <a target="blanck" href="https://www.instagram.com/editoraopet/"><i class="ico-groups i4 amarelo mr-0"></i></a>
                        <a target="blanck" href="https://www.facebook.com/editora.opet.3"><i class="ico-groups i3 amarelo mr-0"></i></a>
                        <a target="blanck" href="https://editoraopet.com.br/"><i class="ico-groups i2 amarelo mr-0"></i></a>
                    </div>
				</div>
			</div>
		</footer>
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
	</body>
</html>
