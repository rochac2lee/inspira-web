@if($tipo == 104)
    <html lang="pt">
        {!!$dados['conteudo']!!}
    </html>
@else
<html lang="pt">
	<head>
		    <!-- Required meta tags -->
    <meta name="title" content="Opet INspira" />
    <meta name="description" content="Opet Inspira" />
    <meta name="keywords" content="Opet Inspira" />

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ config('app.cdn') }}/fr/imagens/favicon.png" type="image/x-icon" />

    <!-- Fonts -->

    <!-- JS -->

    <!-- Plugin para a Ordenacao -->
    <script src="{{ config('app.cdn') }}/fr/includes/js/jquery/jquery-1.12.4.js"></script>

    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/bootstrap/bootstrap.min.js"></script>




    <!-- CSS -->
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/bootstrap/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/2021/style_v2.css">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/2021/style_2021.css">

    <title>{{$titulo}}</title>

	</head>
	<body class="interna">
		<header class="section" style="height: 75px">
		    <div class="container-fluid">
		        <div class="flex-container">
		            <div class="flexgrow-0">
		                <!-- Logo -->
		                <div id="logo">
		                    <a href="{{url('')}}">
		                        <img src="{{ config('app.cdn') }}/fr/imagens/2021/logo.png" />
		                    </a>
		                </div>
		                <!-- Fim logo -->
		            </div>
		        </div>
		    </div>
		</header>

		<div style="text-align: center; position: absolute; width: 100%; height: 82%; top:18%;">
			{!!$dados['conteudo']!!}
		</div>
	</body>

    @if($tipo == 2)
    <link rel="stylesheet" type="text/css" href="{{config('app.cdn')}}/fr/includes/audioplayer/audioplayer.css">
    <script src="{{config('app.cdn')}}/fr/includes/audioplayer/audioplayer.js" type="text/javascript"></script>
        <script>
            var audio = {
                disable_volume: "off"
                ,autoplay: "off"
                ,cue: "on"
                ,disable_scrub: "default"
                ,design_skin: "skin-wave"
                ,skinwave_dynamicwaves:"on"
                ,skinwave_enableSpectrum: "off"
                ,settings_backup_type:"full"
                ,settings_useflashplayer:"auto"
                ,skinwave_spectrummultiplier: "4"
                ,skinwave_comments_enable:"on"
                ,skinwave_mode: "small"
                ,skinwave_comments_retrievefromajax:"on"
                ,pcm_data_try_to_generate: "on"
            };
            $("#player1").audioplayer(audio);

        </script>
    @endif

</html>
@endif
