<!doctype html>
<html lang="pt">
    <head>
        <!-- Fonts -->
        <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/fonts/stylesheet.css" type="text/css" charset="utf-8" />
        <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/fonts/fontawesome/css/all.min.css" type="text/css" charset="utf-8" />
        <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/js/slick/slick.css" type="text/css" charset="utf-8" />

        <!-- JS -->
        <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/jquery/jquery-3.4.1.min.js"></script>
        <script src="{{ config('app.cdn') }}/fr/includes/js/popper.min.js"></script>
        <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/bootstrap/bootstrap.min.js"></script>

        <script src="{{ config('app.cdn') }}/fr/includes/js/slick/slick.min.js" type="text/javascript"></script>

        <!-- CSS -->
        <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/style_v1.css">
        <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/style_avaliacao_online_v2.css">

    <style type="text/css">
        table, td, th {
            border: 1px solid gray;
        }
        table {
            display: table;
            box-sizing: border-box;
            text-indent: initial;
            border-spacing: 2px;
            border-color: gray;
        }
        .letraQuestaoAberta {
            border: 1px solid;
            border-radius: 100%;
            width: 20px;
            height: 20px;
            text-align: center;
            margin: 0 5px 0 0;
            float: left;
        }
        .corretaAlternativa{
            background-color: #a8f986;
        }
    </style>

    <!-- Codigo para mostrar o resultado --->
    <script type="text/javascript" src="{{url('/fr/includes/froala_editor/js/plugins/froala_wiris/integration/WIRISplugins.js?viewer=image')}}"></script>
    </head>
    <body class="interna">
        <section class="section">
            <div class="container">
                <div class="avaliacao-online" style="margin-top: 0px; width: 100%">

                    <!-- PEGUNTA --->
                    <div class="pergunta text-center">
                        <span style="color: #757575; font-size: 24px">
                            @if($dados->tipo == 'o')
                            Questão objetiva
                            @else
                            Questão Discursiva
                            @endif
                        </span>
                    </div>
                    @php
                        $alternativas = [
                            '1' => 'A',
                            '2' => 'B',
                            '3' => 'C',
                            '4' => 'D',
                            '5' => 'E',
                            '6' => 'F',
                            '7' => 'G',
                        ];

                        $conteudoAlternativas = [
                            '1' => $dados->alternativa_1,
                            '2' => $dados->alternativa_2,
                            '3' => $dados->alternativa_3,
                            '4' => $dados->alternativa_4,
                            '5' => $dados->alternativa_5,
                            '6' => $dados->alternativa_6,
                            '7' => $dados->alternativa_7,
                        ];
                    @endphp
                    <!-- RESPOSTAS --->
                    <div class="container mt-4">
                        <div class="row justify-content-md-center">
                            <div class="col-md-12 grid-item">
                                <div class="questao text-justify text-break text-muted">
                                    <div class="row">
                                        @if($dados->fonte!='')
                                            <div class="col-12 mb-1">
                                                ({{$dados->fonte}})
                                            </div>
                                        @endif
                                        <div class="col-12 mb-1">
                                            {!!$dados->pergunta!!}
                                        </div>
                                    @if($dados->tipo == 'o')
                                        @for($j=1; $j<= $dados->qtd_alternativa; $j++)
                                            <div class="col-12 resposta mb-3">
                                                <div class="row">
                                                    <div class="col-1">
                                                        <div class="letraQuestaoAberta  validado" style="cursor: pointer;">{{$alternativas[$j]}}</div>
                                                    </div>
                                                    <div class="col-11">
                                                        {!!$conteudoAlternativas[$j]!!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    @else
                                        <div id="preview_discursiva" class="col bg-white @if($dados->com_linhas != 1)border @endif">
                                            @php
                                            $linha = '<p>&nbsp;</p>';
                                            if($dados->com_linhas == 1)
                                                $linha = '<hr>';
                                            @endphp
                                            @for($i=0; $i<$dados->qtd_linhas; $i++)
                                                {!!$linha!!}
                                            @endfor
                                        </div>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </body>
</html>
