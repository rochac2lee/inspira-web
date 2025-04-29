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
        <meta name="title" content="Novo Ensino Médio - Editora Opet" />
        <meta name="description" content="Novo Ensino Médio - Editora Opet" />
        <meta name="keywords" content="Novo Ensino Médio - Editora Opet" />

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Novo Ensino Médio - Editora Opet</title>

        <link rel="icon" href="https://cf.opetinspira.com.br/storage/novo_em/imagens/favicon.webp" type="image/x-icon" />

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://cf.opetinspira.com.br/fr/includes/css/bootstrap/bootstrap.min.css" crossorigin="anonymous">

        <!-- Fonts e Styles -->
        <link rel="stylesheet" href="https://cf.opetinspira.com.br/storage/novo_em/includes/fonts/stylesheet.css" type="text/css" charset="utf-8" />
        <link rel="stylesheet" href="https://cf.opetinspira.com.br/storage/novo_em/includes/css/style_v2.css" type="text/css" charset="utf-8" />

        <!-- Plugin para a Ordenacao -->
        <script src="https://cf.opetinspira.com.br/storage/novo_em/includes/js/jquery/jquery-3.4.1.min.js"></script>

        <!--<script type="text/javascript" src="includes/js/bootstrap/popper.min.js"></script>-->
        <script type="text/javascript" src="https://cf.opetinspira.com.br/storage/novo_em/includes/js/bootstrap/bootstrap.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                var video = "";

                $( ".video-link" ).on( "click", function() {
                    var titulo = $(this).attr('title');

                    if(video != $(this).attr('video')){
                        video = $(this).attr('video');
                        $("#video_iframe").attr('src',video);
                    }

                    $(".modal-footer").html('<p>'+titulo+'</p>');
                    $('#novo-topico').modal('show');
                });
                $('#novo-topico').on('hidden.bs.modal', function () {
                    $("#video_iframe").attr('src','');
                });

            });


        </script>
    </head>

    <body>
    <div class="container container-xl">
        <div class="card shadow-lg mb-5 mt-4 bg-white rounded">
            <div class="card-body fundo-1">
                @php
                $instituicao = session('instituicao');
                @endphp
                @if( !isset( $instituicao['tipo'])|| $instituicao['tipo'] == 1) <!-- publica -->
                    <div class="tit-01"><a href="https://cf.opetinspira.com.br/storage/novo_em/e_book_nem.pdf" target="_blank"><img src="https://cf.opetinspira.com.br/storage/novo_em/imagens/titulo_01.webp"></a></div>
                @else
                    <div class="tit-01"><a href="https://cf.opetinspira.com.br/storage/novo_em/e_book_nem_pub.pdf" target="_blank"><img src="https://cf.opetinspira.com.br/storage/novo_em/imagens/titulo_01.webp"></a></div>
                @endif
                <!-- Video 1 -->
                <a class="video-link" title="PRINCIPAIS MUDANÇAS DO NOVO ENSINO MÉDIO" video="https://player.vimeo.com/video/594619533">
                    <div class="camada_video mt-2">
                        <img src="https://cf.opetinspira.com.br/storage/novo_em/imagens/video_01.webp">
                        <p style="margin: 62px 260px 0 0;">PRINCIPAIS<br>MUDANÇAS DO<br>NOVO ENSINO MÉDIO</p>
                    </div>
                </a>

                <!-- Video 2 -->
                <a class="video-link" title="POSSIBILIDADES DE ARRANJOS DA CARGA HORÁRIO DO NOVO ENSINO MÉDIO" video="https://player.vimeo.com/video/594617455">
                    <div class="camada_video left mt-3">
                        <img src="https://cf.opetinspira.com.br/storage/novo_em/imagens/video_02.webp">
                        <p style="margin: 58px 31px 0 260px;">POSSIBILIDADES DE<br>ARRANJOS DA CARGA<br>HORÁRIO DO NOVO<br>ENSINO MÉDIO</p>
                    </div>
                </a>

                <!-- Video 3 -->
                <a class="video-link" title="O QUE CORRESPONDE À FORMAÇÃO GERAL BÁSICA E AOS ITINERÁRIOS FORMATIVOS?" video="https://player.vimeo.com/video/594614611">
                    <div class="camada_video mt-3">
                        <img src="https://cf.opetinspira.com.br/storage/novo_em/imagens/video_03.webp">
                        <p style="margin: 41px 256px 0 0;">O QUE CORRESPONDE<br>À FORMAÇÃO<br>GERAL BÁSICA E<br>AOS ITINERÁRIOS<br>FORMATIVOS?</p>
                    </div>
                </a>

                <!-- Video 4 -->
                <a class="video-link" title="DETALHAMENTO DOS ITINERÁRIOS FORMATIVOS: PROJETO DE VIDA E TRAVESSIAS POR ÁREA DO CONHECIMENTO" video="https://player.vimeo.com/video/594608299">
                    <div class="camada_video left mt-3">
                        <img src="https://cf.opetinspira.com.br/storage/novo_em/imagens/video_04.webp">
                        <p style="margin: 27px 31px 0 247px;">DETALHAMENTO<br>DOS ITINERÁRIOS<br>FORMATIVOS:<br>PROJETO DE VIDA E<br>TRAVESSIAS POR ÁREA<br>DO CONHECIMENTO</p>
                    </div>
                </a>
            </div>
            <div class="card-body fundo-2">
                <div class="row justify-content-md-center" style="margin-top: 318px;">
                    <div class="col-auto">
                        <a href="https://cf.opetinspira.com.br/storage/novo_em/arquivos/material_01.pdf" target="_blank"><img src="https://cf.opetinspira.com.br/storage/novo_em/imagens/livro_1.webp" class="shadow-sm rounded"></a>
                    </div>
                    <div class="col-auto">
                        <a href="https://cf.opetinspira.com.br/storage/novo_em/arquivos/material_02.pdf" target="_blank"><img src="https://cf.opetinspira.com.br/storage/novo_em/imagens/livro_2.webp" class="shadow-sm rounded"></a>
                    </div>
                </div>
                <div class="row justify-content-md-center mt-4">
                    <div class="col-auto">
                        <a href="https://cf.opetinspira.com.br/storage/novo_em/arquivos/material_03.pdf" target="_blank"><img src="https://cf.opetinspira.com.br/storage/novo_em/imagens/livro_3.webp" class="shadow-sm rounded"></a>
                    </div>
                    <div class="col-auto">
                        <a href="https://cf.opetinspira.com.br/storage/novo_em/arquivos/material_04.pdf" target="_blank"><img src="https://cf.opetinspira.com.br/storage/novo_em/imagens/livro_4.webp" class="shadow-sm rounded"></a>
                    </div>
                    <div class="col-auto">
                        <a href="https://cf.opetinspira.com.br/storage/novo_em/arquivos/material_05.pdf" target="_blank"><img src="https://cf.opetinspira.com.br/storage/novo_em/imagens/livro_5.webp" class="shadow-sm rounded"></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL 1-->
        <div class="modal fade" id="novo-topico" tabindex="-1" role="dialog" aria-labelledby="novo-topico" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe id="video_iframe" class="embed-responsive-item" src="" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <!-- FIM MODAL 1 -->
    </body>
</html>
