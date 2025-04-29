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

        <link rel="icon" href="{{config('app.cdn')}}/fr/imagens/favicon.ico" type="image/x-icon" />

	    <!-- Fonts -->
	    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/fonts/2021/stylesheet.css" type="text/css" charset="utf-8" />
	    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/fonts/fontawesome/css/all.min.css" type="text/css" charset="utf-8" />
	    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slick/slick.css" type="text/css" charset="utf-8" />

	    <!-- JS -->
	    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/jquery/jquery-3.4.1.min.js"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/bootstrap/bootstrap.min.js"></script>

	    <script src="{{config('app.cdn')}}/fr/includes/js/slick/slick.min.js" type="text/javascript"></script>
	    <script src="{{config('app.cdn')}}/fr/includes/js/jquery-mask/1.7.7/jquery.mask.min.js"></script>
	    <script src="{{config('app.cdn')}}/fr/includes/js/form-validation.js"></script>
	    <script src="{{config('app.cdn')}}/fr/includes/js/form-mask.js"></script>

	    <!-- SCRIPT DO CÓDIGO ANTIGO-->

		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<!-- FIM SCRIPT DO CÓDIGO ANTIGO-->
        <style>

            .ico-groups {
                background: url({{config('app.cdn')}}/fr/imagens/2021/ico-groups_v1.png) no-repeat left top;
            }
            .ico-groups.amarelo {
                background: url({{config('app.cdn')}}/fr/imagens/2021/ico-groups-amarelo_v1.png) no-repeat left top;
            }
        </style>
	    <!-- CSS -->
	    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/css/bootstrap/bootstrap.min.css">
	    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/css/2021/style_v5.css">
        <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/css/2021/style_2021_v1.css">

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
<header class="section">
    <div class="container-fluid">
        <div class="flex-container">
            <div class="flexgrow-0">

            </div>

            <div class="flexgrow-0">
                <!-- Logo -->
                <div id="logo">
                    <a href="{{url('/login')}}">
                        <img src="{{url('fr/imagens/2021/logo.png')}}" />
                    </a>


                </div>
                <!-- Fim logo -->
            </div>

            <div class="flexgrow-1 d-flex justify-content-center">

            </div>

            <div class="flexgrow-0">

                <!-- Fim User -->
            </div>
        </div>
    </div>
</header>
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
            <div class="col-md"></div>
            <div class="col-md">
                <a href="{{url('colecao_tutorial')}}">Tutoriais</a> &nbsp;•&nbsp; <a href="{{url('contato')}}">Fale conosco</a> &nbsp;•&nbsp; <a href="{{url('termos-de-uso')}}">Termos de uso</a>
            </div>
            <div class="col-auto">
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
