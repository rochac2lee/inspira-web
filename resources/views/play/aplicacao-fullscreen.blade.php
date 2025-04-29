<!DOCTYPE html>
{{-- <html lang="pt-br"> --}}
<html lang="{{ app()->getLocale() }}">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Plataforma de {{$langCursoP}} digitais">
        <meta name="author" content="Edulabzz">

        @if(Auth::check() ? (Auth::user()->escola->instituicao != null) : false)
            <title>{{ ucfirst(Auth::user()->escola->instituicao->titulo) }} - {{ ucfirst($aplicacao->titulo) }}</title>

            <link rel="icon" href="{{ config('app.cdn') }}/assets/custom/{{ Auth::user()->escola->instituicao->id }}/images/icon.png">
        @else
            <title>{{ config('app.name') }} - {{ ucfirst($aplicacao->titulo) }}</title>
        @endif

        <!-- Bootstrap CSS -->
        {{--  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous">  --}}

        <!-- Font Awesome -->
        {{--  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">  --}}

        <!-- Font Body -->
        {{--  <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>  --}}

        <!-- Animations -->
        {{-- <link href="{{ config('app.cdn') }}/assets/css/animated.css" rel="stylesheet"> --}}
        {{--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" />  --}}


        <!-- Font Edulabzz -->

        <!-- Custom styles main -->
        {{--  <link rel="stylesheet" href="{{ config('app.cdn') }}/assets/css/app.min.css">  --}}

        <!-- Custom styles for this template -->
        <style>

            main
            {
                align-items: center;
                display: flex;
                overflow: hidden;
            }

            body
            {
                margin: 0px;
                padding: 0px;
            }

            .webgl-content, .game-container
            {
                width: 100vw !important;
                height: 100vh !important;
            }

            .game-container
            {
                border: 0px solid var(--primary-color) !important;
            }

            .game-container canvas
            {
                width: 100% !important;
                height: 100% !important;
            }

        </style>

        <link rel="stylesheet" href="{{ config('app.local') }}/assets/unity/TemplateData/style.css">

    </head>

    <body id="page-top">


        <main role="main">

            <div class="game-container mx-auto" id="gameContainer"></div>

        </main>

        <!-- Bootstrap core JavaScript -->
        {{--  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>  --}}

        <!-- Jquery UI -->
        {{--  <script src="{{ config('app.cdn') }}/assets/js/jquery-ui.min.js"></script>  --}}

        {{--  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>  --}}
        {{--  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  --}}

        <!-- Sweet Alert -->
        {{--  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>  --}}

        <!-- Jquery Easing -->
        {{--  <script src="{{ config('app.cdn') }}/assets/js/jquery.easing.compatibility.min.js"></script>  --}}

        <!-- Custom Scrolling Nav -->
        {{--  <script src="{{ config('app.cdn') }}/assets/js/scrolling-nav.js"></script>  --}}

        <!-- Custom Smooth Scrolling -->
        {{--  <script src="{{ config('app.cdn') }}/assets/js/smooth-scrolling.js"></script>  --}}

        <!-- Declare App URL -->
        <script>var appurl = '{{ config('app.local') }}';</script>

        <!-- Custom JavaScript -->
        {{--  <script src="{{ config('app.cdn') }}/assets/js/main.js"></script>  --}}


        <!-- Unity player js -->
        <script src="{{ config('app.local') }}/assets/unity/TemplateData/UnityProgress.js"></script>
        <script src="{{ config('app.local') }}/uploads/aplicacoes/{{ $aplicacao->id }}/UnityLoader.js"></script>
        <script>
            var gameInstance = UnityLoader.instantiate("gameContainer", "{{ config('app.local') }}/uploads/aplicacoes/{{ $aplicacao->id }}/aplicacao.json", {onProgress: UnityProgress});
        </script>

    </body>

</html>
