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
<style>
    .ico-groups {
        background: url({{config('app.cdn')}}/fr/ed_infantil/imagens/ico-groups-cinza_v1.png) no-repeat left top;
    }
</style>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/ed_infantil/includes/css/educacao_infantil_v2.css">

    <title>Opet Inspira - Plataforma Educacional</title>

    <script>
        $(document).ready(function(){
          	$('[data-toggle="tooltip"]').tooltip();

			$(document).bind("contextmenu",function(e){
				return false;
			});
		});
		</script>
	</head>
	<body class="home">
		<section class="section topo">
			<div class="row topo">
				<div class="col-6">
					<a href="{{url('/catalogo')}}" class="logo"><img src="{{ config('app.cdn') }}/fr/ed_infantil/imagens/logo.svg"></a>
				</div>
				<div class="col-6 text-right">
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
				</div>
				<h1 class="titulo"><img src="{{ config('app.cdn') }}/fr/ed_infantil/imagens/h1_educacao_infantil.png"></h1>
			</div>
		</section>
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


		<script type="text/javascript">

		    $(document).ready(function(){
	        	var audioElement = document.createElement('audio');

	            /* seleciona item */
	            $( ".menu .hover" ).click(function() {
	                var sound = $(this).find(".audio");
	          		audioElement.setAttribute('src', sound.attr('src'));
	          		audioElement.play();
	            });
	        });
		</script>


	</body>
</html>
