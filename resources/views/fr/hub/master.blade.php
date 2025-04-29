<!doctype html>
<html lang="pt-BR">
	<head>    
	<!-- Required meta tags -->
    <meta name="title" content="Opet INspira" />
    <meta name="description" content="Opet Inspira" />
    <meta name="keywords" content="Opet Inspira" />

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <link rel="icon" href="{{ config('app.cdn') }}/fr/imagens/favicon.png" type="image/x-icon" />

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/gabaritos/includes/fonts/stylesheet.css?v=006" type="text/css" charset="utf-8" />
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/fonts/fontawesome/css/all.min.css" type="text/css" charset="utf-8" />

    <!-- Plugin para a Ordenacao -->
    <script src="{{ config('app.cdn') }}/fr/includes/js/jquery/jquery-1.12.4.js"></script>
    <script src="{{ config('app.cdn') }}/fr/includes/js/jquery/jquery-ui.js"></script>

    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/bootstrap/popper.min.js"></script>
    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/bootstrap/bootstrap.min.js"></script>
<style>
    .ico-groups {
        background: url({{config('app.cdn')}}/fr/gabaritos/imagens/ico-groups-cinza_v1.png) no-repeat left top;
    }
</style>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/gabaritos/includes/css/gabaritos.css">

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
					<a href="{{url('/catalogo')}}" class="logo"><img src="{{ config('app.cdn') }}/fr/gabaritos/imagens/logo.svg"></a>
				</div>						
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
	</body>
</html>
