@extends('layouts.master')

@section('title', ucfirst($conteudo->titulo))

@section('headend')

    <!-- Custom styles for this template -->
    <style>

        main
        {
            align-items: center;
            display: flex;
        }

        .conteudo-container
        {
            /*width: calc(85vw + 12px) !important;*/
            min-height: calc(20vw) !important;
            width: auto;
            height: auto;
            /*max-width: 1292px;*/
            /*max-height: 732px;*/
            border: 6px solid var(--primary-color) !important;
            background-color: white;

            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }

    </style>

@endsection

@section('content')

<main role="main">

    <div class="container">
        <div class="row">

            <div class="col-12 col-md-11 mx-auto pt-4">

                <div class="row my-3">
                    <div class="col align-middle my-auto">


                        <div class="title">
                            <h2>{{ ucfirst($conteudo->titulo) }} •
                                <span class="ml-2 text-primary">
                                    @if($conteudo->tipo == 1 || ($conteudo->tipo == 4 && (strpos($conteudo->conteudo, ".ppt") == false && strpos($conteudo->conteudo, ".pptx") == false)) )
                                        <i class="fas fa-file-alt align-middle mr-2"></i>
                                        <span class="align-middle">Documento</span>
                                    @elseif($conteudo->tipo == 2)
                                        <i class="fas fa-music align-middle mr-2"></i>
                                        <span class="align-middle">Áudio</span>
                                    @elseif($conteudo->tipo == 3)
                                        <i class="fas fa-file-powerpoint align-middle mr-2"></i>
                                        <span class="align-middle">Vídeo1</span>
                                    @elseif($conteudo->tipo == 4 && (strpos($conteudo->conteudo, ".ppt") !== false || strpos($conteudo->conteudo, ".pptx") !== false))
                                        <i class="fas fa-file-powerpoint align-middle mr-2"></i>
                                        <span class="align-middle">Documento</span>
                                    @elseif($conteudo->tipo == 11)
                                        <i class="fas fa-file-alt align-middle mr-2"></i>
                                        <span class="align-middle">PDF</span>
                                    @endif
                                </span>
                            </h2>
                        </div>


                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0 py-3 mb-0 bg-transparent border-bottom">
                                <li class="breadcrumb-item active" aria-current="page">
                                    <a href="{{ route('biblioteca') }}" >
                                        <i class="fas fa-chevron-left mr-2"></i>
                                        <span>Biblioteca</span>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{ ucfirst($conteudo->titulo) }}</li>
                            </ol>
                        </nav>
                        @if($conteudo->descricao != "")
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb p-0 py-3 mb-0 bg-transparent border-bottom">
                                    <p>
                                        {{ ucfirst($conteudo->descricao) }}
                                    </p>
                                </ol>
                            </nav>
                        @endif


                        <br>
                    </div>
                </div>

                <div class="row mb-3">

                    <div class="col-12">

                        <div class="mx-auto">
                            {!! $conteudo->conteudo !!}
                        </div>

                    </div>

                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <hr class="">

                        <div class="text-right">
                            <div id="divAvaliacaoNegativa" class="d-inline-block">
                                <span class="quantidade my-auto d-inline-block mr-1">{{ $conteudo->qtAvaliacoesNegativas }}</span>
                                <button id="btnNegativo" type="button" onclick="enviarAvaliacaoConteudo(0);" class="btn btn-link p-1">
                                    <i class="fas fa-thumbs-down fa-fw fa-lg text-lightgray {{ $conteudo->minhaAvaliacao != null ? ($conteudo->minhaAvaliacao->avaliacao == 0 ? 'text-danger' : '') : '' }}"></i>
                                </button>
                            </div>

                            <div id="divAvaliacaoPositiva" class="d-inline-block mr-2">
                                <span class="quantidade my-auto d-inline-block mr-1">{{ $conteudo->qtAvaliacoesPositivas }}</span>
                                <button id="btnPositivo" type="button" onclick="enviarAvaliacaoConteudo(1);" class="btn btn-link p-1">
                                    <i class="fas fa-thumbs-up fa-fw fa-lg text-lightgray {{ $conteudo->minhaAvaliacao != null ? ($conteudo->minhaAvaliacao->avaliacao == 1 ? 'text-success' : '') : '' }}"></i>
                                </button>
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

    <script>

        $( document ).ready(function()
        {
            //toggleSideMenu();
        });

        function enviarAvaliacaoConteudo(avaliacao)
        {
            if(avaliacao == 1 && $('#btnPositivo i.text-success').length > 0)
            {
                return;
            }
            else if(avaliacao == 0 && $('#btnNegativo i.text-danger').length > 0)
            {
                return;
            }
            
            $('#btnPositivo').attr('disabled', true);
            $('#btnNegativo').attr('disabled', true);
            var formData = { '_token' : '{{ csrf_token() }}', 'avaliacao': avaliacao };

            $.ajax({
                url: '{{ config('app.local') }}/play/conteudo/{{ $conteudo->id }}/avaliacao/enviar',
                type: 'post',
                data: formData,
                success: function( _response )
                {
                    

                    $('#btnPositivo').attr('disabled', false);
                    $('#btnNegativo').attr('disabled', false);

                    if(_response.success != null)
                    {
                        if(avaliacao == 1)
                        {
                            $("#divAvaliacaoPositiva .quantidade").text( parseInt($("#divAvaliacaoPositiva .quantidade").text()) + 1 );

                            if($("#divAvaliacaoNegativa button i").hasClass('text-danger'))
                            {
                                $("#divAvaliacaoNegativa .quantidade").text( parseInt($("#divAvaliacaoNegativa .quantidade").text()) - 1 );
                            }

                            $("#divAvaliacaoPositiva button i").addClass('text-success');
                            $("#divAvaliacaoNegativa button i").removeClass('text-danger');
                        }
                        else
                        {
                            $("#divAvaliacaoNegativa .quantidade").text( parseInt($("#divAvaliacaoNegativa .quantidade").text()) + 1 );

                            if($("#divAvaliacaoPositiva button i").hasClass('text-success'))
                            {
                                $("#divAvaliacaoPositiva .quantidade").text( parseInt($("#divAvaliacaoPositiva .quantidade").text()) - 1 );
                            }

                            $("#divAvaliacaoPositiva button i").removeClass('text-success');
                            $("#divAvaliacaoNegativa button i").addClass('text-danger');
                        }
                    }
                },
                error: function( _response )
                {
                    
                }
            });
        }

        $(window).on('unload', function() {


            var fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('tipo', 2);
            fd.append('inicio', '{{ date("Y-m-d H:i:s") }}');
            {{--  fd.append('inicio', new Date().toISOString().slice(0, 19).replace('T', ' '));  --}}

            navigator.sendBeacon('{{ config('app.local') }}/play/conteudo/{{ $conteudo->id }}/interacao/enviar', fd);
        });

        function mudouPerguntaProvaAtual()
        {
            $("#divCont #divPerguntas > div").addClass("d-none");
            $("#divCont #divPerguntas #divPergunta" + $("#divCont #cmbQuestaoAtual").val()).removeClass("d-none");
        }

    </script>

@endsection
