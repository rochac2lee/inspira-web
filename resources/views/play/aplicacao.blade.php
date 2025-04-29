@extends('layouts.master')

@section('title', ucfirst($aplicacao->titulo))

@section('headend')

    <!-- Custom styles for this template -->
    <style>

        main
        {
            align-items: center;
            display: flex;
        }

        .webgl-content, .game-container
        {

            {{--  width: 67.5vw !important;
            height: 38.3vw !important;
            max-width: 972px;
            max-height: 552px;  --}}

            width: calc(85vw + 12px) !important;
            height: calc(48vw + 12px) !important;
            max-width: 1292px;
            max-height: 732px;
        }

        .game-container
        {
            border: 6px solid var(--primary-color) !important;
        }

        .game-container canvas
        {
            width: 100% !important;
            height: 100% !important;
        }

    </style>

    <link rel="stylesheet" href="{{ config('app.local') }}/assets/unity/TemplateData/style.css">

@endsection

@section('content')

<main role="main">

    <div class="container-fluid">
        <div class="row">

            <div class="col-12 col-xl-10 mx-auto px-1 px-xl-3">

                <div class="row">
                    <div class="col align-middle my-auto">
                        <h3>
                            {{--  <small>Aplicação: </small>  --}}
                        </h3>
                    </div>
                </div>

                <div class="row">

                    <div class="col-12">

                        <div class="webgl-content mx-auto">

                            <div class="game-container mx-auto" id="gameContainer"></div>

                            <div class="footer">
                                {{--  <div class="webgl-logo"></div>  --}}
                                <div class="fullscreen" onclick="gameInstance.SetFullscreen(1)"></div>
                                <div class="title">{{ ucfirst($aplicacao->titulo) }}</div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</main>

@endsection

@section('bodyend')

    <!-- Unity player js -->
    <script src="{{ config('app.local') }}/assets/unity/TemplateData/UnityProgress.js"></script>
    <script src="{{ config('app.local') }}/uploads/aplicacoes/{{ $aplicacao->id }}/UnityLoader.js"></script>
    <script>
        var gameInstance = UnityLoader.instantiate("gameContainer", "{{ config('app.local') }}/uploads/aplicacoes/{{ $aplicacao->id }}/aplicacao.json", {onProgress: UnityProgress});
    </script>

    <script>

        $( document ).ready(function()
        {
            //toggleSideMenu();
        });

        $(window).on('unload', function() {


            var fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('tipo', 1);
            fd.append('inicio', '{{ date("Y-m-d H:i:s") }}');
            {{--  fd.append('inicio', new Date().toISOString().slice(0, 19).replace('T', ' '));  --}}

            navigator.sendBeacon('{{ config('app.local') }}/play/conteudo/{{ $aplicacao->id }}/interacao/enviar', fd);
        });

    </script>

@endsection
