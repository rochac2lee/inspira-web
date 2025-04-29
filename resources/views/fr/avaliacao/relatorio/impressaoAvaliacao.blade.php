<html lang="pt">
<head>
    <!-- Required meta tags -->
    <meta name="title" content="Opet INspira" />
    <meta name="description" content="Opet Inspira" />
    <meta name="keywords" content="Opet Inspira" />

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ config('app.cdn') }}/fr/imagens/favicon.ico" type="image/x-icon" />
    <!-- Fonts -->
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/fonts/2021/stylesheet.css" type="text/css" charset="utf-8" />
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/fonts/fontawesome/css/all.min.css" type="text/css" charset="utf-8" />

    <!-- JS -->
    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/jquery/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/bootstrap/bootstrap.min.js"></script>


    <!-- CSS -->
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/2021/style_v5.css">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/2021/style_2021_v1.css">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/style_impressao_avaliacao_v2.css" crossorigin="anonymous">

    <script type="text/javascript" src="{{url('fr/includes/froala_editor/js/plugins/froala_wiris/integration/WIRISplugins.js?viewer=image')}}"></script>

    <title>Opet Inspira - Plataforma Educacional</title>
    <style type="text/css">
        /* Define se é 1 ou 2 colunas */
        .colunas_questoes{
            -moz-column-count: 1;
            -webkit-column-count: 1;
            column-count: 1;
            -webkit-column-gap: 20px;
            -moz-column-gap: 20px;
            column-gap: 20px;
            -webkit-column-rule: 1px outset #CCCCCC;
            -moz-column-rule: 1px outset #CCCCCC;
            column-rule: 1px outset #CCCCCC;
        }

        .letra {
            border: 1px solid;
            border-radius: 100%;
            width: 20px;
            height: 20px;
            text-align: center;
            margin: 0 5px 0 0;
            float: left;
        }
        .questao .resposta {
            width: 100%;
            margin-bottom: 10px;
        }

        @media  print {
            #imprimir {
                display: none;
            }
        }

        @page  {
            margin: 20mm;
            @top-center {
                content: "";
            }
            @bottom-right {
                content: "Página" counter(page)
            }
        }

        /* Cabeçalho Avaliação */
        .cabecalho{
            font-size: 14px;
            color: #404040;
            margin-bottom: 0px;
        }

        .cabecalho label{
            margin-bottom: 0.25rem;
        }
        .cabecalho .linha{
            border-bottom: 1px solid #dee2e6;
        }

    </style>
</head>
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
@endphp
    <body class="impressao_avaliacao">
        <p id="imprimir" class="text-center">
            <a href="{{url('/gestao/avaliacao/relatorio/impressao/'.$avaliacao->id).'?print=1'}}" target="blank" class="btn btn-secondary">Imprimir</a>
        </p>
        @include('fr.avaliacao.relatorio.impressaoAvaliacaoCorpo')
    </body>
<script>
    @if(Request::input('print') == 1)
        window.print();
    @endif
</script>
</html>
