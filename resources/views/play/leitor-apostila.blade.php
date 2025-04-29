<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <!-- Animations -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" />

        <style>

            body
            {
                padding: 0px;
                margin: 0px;

                text-align: center !important;
                text-align: -webkit-center !important;

                /* overflow: hidden; */

                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                min-height: 100vh;

                /* background-color: #F9F9F9 !important; */
                background-color: #525659 !important;

                padding: 25px;
            }

            iframe.player
            {
                width: 50vw;
                min-width: 790px;
                /* height: 99vh; */
                height: auto;
                /* min-height: calc(1024px - 52px); */
                min-height: 1028px;
                background: white;
                /* background: #d4d4d4; */

                overflow: hidden;

                transition: 0.2s all ease-in-out;

                box-shadow: rgba(0, 0, 0, 0.66) 0px 4px 6px 2px;
            }

            iframe.player.fullscreen
            {
                width: 100vw;
                height: 100vh;
                overflow: hidden;
            }

            .box-ferramentas
            {
                justify-self: center;
                position: fixed;
                top: 0px;
                margin: 8px;
                background: rgba(47, 47, 47, 0.9);
                padding: 8px;
                border-radius: 5px;
                z-index: 1
            }

            .aba-anotacoes
            {
                position: fixed;
                background: white;
                top: 0px;
                right: 0px;
                height: 100vh;
                width: 70px;
                box-shadow: 0px 3px 6px rgba(0,0,0,0.16);
                text-align: left;
                text-align: -webkit-left;
                transition: 0.2s all ease-in-out;
            }

            .aba-anotacoes.show
            {
                width: 340px !important;
                max-width: 40vw;
            }

            .aba-anotacoes .box-anotacao
            {
                display: none;
            }

            .aba-anotacoes.show .box-anotacao
            {
                display: block;
            }

        </style>

         <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous">

        <!-- Bootstrap Select -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

        <!-- Font Body -->
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>

        <!-- Animations -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" />

    </head>

    <body>
        <div id="divCarregando">Carregando</div>

        <iframe sandbox="allow-same-origin allow-scripts" class="player" id="iframePlayer" src="" frameborder="0">

        </iframe>

        <div id="divAbaAnotacoes" class="p-2 aba-anotacoes">

            <button class="btn d-block ml-auto mb-3" onclick="toggleAbaAnotacoes();" style="background-color: #E3E5F0;">
                <i class="fas fa-comment-dots fa-lg fa-fw text-secondary"></i>
            </button>

            <div class="box-anotacao text-center" style="overflow-x: auto; max-height: calc(100vh - 52px);">

                {{-- <h5>Marcadores</h5>

                <div id="divMarcadores" class="text-center mb-3">
                    <div id="divSemMarcadores" class="item w-100 mb-3 mb-auto position-relative text-muted small {{ count($marcadores ) > 0 ? 'd-none' : '' }}">
                        Você ainda não fez marcações.
                    </div>
                </div> --}}

                <h5>Anotações</h5>

                <div id="divAnotacoes" class="text-center mb-3">
                    <div id="divSemAnotacoes" class="item w-100 mb-3 mb-auto position-relative text-muted small {{ count($anotacoes ) > 0 ? 'd-none' : '' }}">
                        Você ainda não salvou nenhuma anotação.
                    </div>
                </div>

                @foreach ($anotacoes as $anotacao)
                    <div id="divAnotacao{{ $anotacao->pagina }}" class="item w-100 mb-3 mb-auto position-relative px-4 py-2 border-bottom">
                        <button onclick="goToPage({{ $anotacao->pagina }});" class="btn btn-link bg-transparent d-block px-0 mb-1 text-wrap mw-100 text-left" style="color: #0D1033; font-weight: 600;">
                            Anotação pg. {{ $anotacao->pagina }}
                        </button>
                        <button onclick="deletarAnotacao({{ $anotacao->id }}, {{ $conteudo->id }})" class="btn bg-transparent text-danger position-absolute" style="top:5px; right: 0px;"><i class="fas fa-trash" data-toggle="tooltip" title="Excluir anotação"></i></button>
                        <p class="text-wrap mb-1" style="text-align: justify;">{{ $anotacao->anotacao }}</p>
                    </div>
                @endforeach

            </div>

        </div>

        <div class="box-ferramentas">

            <button class="btn bg-transparent text-white" id="btnAnterior" onclick="voltarPagina();">
                <i class="fas fa-chevron-left fa-fw"></i>
                <!-- <span>
                    Página anterior
                </span> -->
            </button>

            <div id="divIndicadorPaginas" class="d-inline-block mb-0 text-white align-middle">
                Página
                <span id="lblPaginaAtual">-</span>
                /
                <span id="lblPaginasTotal">-</span>
            </div>

            <button class="btn bg-transparent text-white" id="btnProxima" onclick="proximaPagina();">
                <i class="fas fa-chevron-right fa-fw"></i>
                <!-- <span>
                    Próxima página
                </span> -->
            </button>

            <button class="btn bg-transparent text-white" id="btnZoomOut" onclick="zoomOut();">
                <i class="fas fa-search-minus fa-fw"></i>
                <!-- <span>
                    Zoom out
                </span> -->
            </button>

            <button class="btn bg-transparent text-white" id="btnZoomIn" onclick="zoomIn();">
                <i class="fas fa-search-plus fa-fw"></i>
                <!-- <span>
                    Zoom in
                </span> -->
            </button>

            <button class="btn bg-transparent text-white" id="btnResetZoom" onclick="resetZoom();">
                <span>
                    100%
                </span>
            </button>

            <button class="btn bg-transparent text-white" id="btnFullscreen" onclick="fullscreenPage();">
                <i class="fas fa-compress fa-fw"></i>
                <!-- <span>
                    Fullscreen
                </span> -->
            </button>

            <button class="btn bg-transparent text-white" id="btnRotateRight" onclick="rotatePageRight();">
                <i class="fas fa-undo fa-fw"></i>
                <!-- <span>
                    Rotate Right
                </span> -->
            </button>

            <button class="btn bg-transparent text-white" id="btnRotateLeft" onclick="rotatePageLeft();">
                <i class="fas fa-undo fa-flip-horizontal fa-fw"></i>
                <!-- <span>
                    Rotate Left
                </span> -->
            </button>

            <button class="btn bg-transparent text-white" id="btnProxima" onclick="habilitarMarcacaoPin();">
                <i class="fas fa-thumbtack fa-fw"></i>
                <!-- <span>
                    Habilitar anotação
                </span> -->
            </button>

            <button id="btnMarcarPagina" class="btn bg-transparent text-white" id="btnProxima" onclick="marcarPagina();">
                <i class="far fa-bookmark fa-fw"></i>
                <!-- <span>
                    Habilitar anotação
                </span> -->
            </button>

        </div>

    </body>

    <!-- Bootstrap core JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

    <!-- Sweet Alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- <script type="text/javascript" src="index.js"></script> -->

    <script>

        var pages = [];

        var current_url = new URL(window.location.href);

        var url_apostila = current_url.searchParams.get("url");

        var conteudo_id = current_url.searchParams.get("conteudo_id");


        var pages_apostila = document.createElement('script');

        pages_apostila.setAttribute('src', url_apostila + "/index.js");

        document.head.appendChild(pages_apostila);


        var paginaMarcada = null;

        var paginaAtual = 1;

        //var fullscreen = false;

        var rotate = 0;

        var zoom = 1;

        {{-- var anotacoes = [
            {id: 1, text: 'teste', x: 50, y: 50, pagina: 1},
            {id: 2, text: 'algo', x: 250, y: 250, pagina: 3},
            {id: 3, text: 'algo', x: 350, y: 250, pagina: 4},
            {id: 4, text: 'algo', x: 350, y: 450, pagina: 4},
            {id: 5, text: 'algo', x: 350, y: 550, pagina: 4},
        ]; --}}

        var anotacoes = {!! json_encode($anotacoes) !!};


        $(document).keyup(function(e) {


            if (e.key === "Escape")
            {
                fullscreenPage(false);
            }
            else if (e.key === "ArrowRight")
            {
                proximaPagina();
            }
            else if (e.key === "ArrowLeft")
            {
                voltarPagina();
            }
        });

        $( pages_apostila ).on('load', function () {

            if(pages != null ? pages.length > 0 : false)
            {
                $("#iframePlayer").attr('src', url_apostila + "/" + pages[paginaAtual - 1]);

                if(pages.length == 1)
                {
                    if(pages[0] == "index.html")
                    {
                        $("#divIndicadorPaginas").removeClass('d-inline-block');
                        $("#divIndicadorPaginas").addClass('d-none');

                        $("#btnAnterior").addClass('d-none');
                        $("#btnProxima").addClass('d-none');

                        $("#iframePlayer").css("min-width", "1025px");
                        $("#iframePlayer").css("min-height", "768px");
                    }
                }
            }
            else if(pages == null || pages == [])
            {
                $("#divIndicadorPaginas").removeClass('d-inline-block');
                $("#divIndicadorPaginas").addClass('d-none');

                $("#btnAnterior").addClass('d-none');
                $("#btnProxima").addClass('d-none');

                $("#iframePlayer").css("min-width", "1025px");
                $("#iframePlayer").css("min-height", "768px");
            }

            $("#lblPaginaAtual").text(paginaAtual)
            $("#lblPaginasTotal").text(pages.length);

        })

        $( document ).ready(function() {


            $.ajax({
                url: parent.appurl + '/play/conteudo/' + conteudo_id + '/pagina-marcada',
                type: 'get',
                dataType: 'json',
                success: function( _response )
                {
                    

                    if(_response.response === true)
                    {
                        paginaMarcada = _response.paginaMarcada;

                        if(paginaMarcada != null)
                        {
                            goToPage(paginaMarcada);
                        }
                    }

                },
                error: function( _response )
                {
                    
                }
            });

            $("#divCarregando").remove();



            if(pages != null ? pages.length > 0 : false)
            {
                $("#iframePlayer").attr('src', url_apostila + "/" + pages[paginaAtual - 1]);
            }

            document.getElementById('iframePlayer').onload = function(e) {

                // $('#iframePlayer').fadeIn();

                if(paginaMarcada == paginaAtual)
                {
                    $("#btnMarcarPagina i").removeClass('far');
                    $("#btnMarcarPagina i").addClass('fas');
                }
                else
                {
                    $("#btnMarcarPagina i").removeClass('fas');
                    $("#btnMarcarPagina i").addClass('far');
                }

                document.getElementById('iframePlayer').contentWindow.document.body.addEventListener("click", (e) => getClickPosition(e), false);

                $('#iframePlayer').contents().find('head').append( `
                    <!-- Font Awesome -->
                    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

                    <style type="text/css">
                        .textLayer
                        {
                            opacity: 0.3 !important;
                            /* display: none !important; */
                        }

                        .btn-deletar-anotacao
                        {
                            background: transparent;
                            border: 0px;
                            position: absolute;
                            right: -12px;
                            top: -12px;
                            color: red;
                        }

                        .indicador-anotacao
                        {
                            font-family: "Arial";
                            padding: 8px 7.5px;
                            background: var(--primary-color);
                            color: rgb(255, 255, 255) !important;
                            font-weight: bold;
                            font-size: 14px;
                            border-radius: 50%;
                            position: absolute;
                            z-index: 99999;
                            cursor: pointer;
                            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px;
                            left: 2%;
                        }

                        .indicador-texto
                        {
                            font-family: "Arial";
                            position: absolute;
                            background: #3f3f3ff2;
                            width: auto;
                            min-width: 200px;
                            text-align: center;
                            padding: 5px;
                            border-radius: 10px;
                            left: calc(100% + 10px);
                            top: 0px;
                            height: auto;
                            box-shadow: 0px 3px 6px rgba(0,0,0,0.5);
                            opacity: 0;
                            transition: opacity 0.2s ease-in-out;
                        }

                        .indicador-anotacao:hover .indicador-texto
                        {
                            opacity: 1 !important;
                        }
                    </style>
                ` );

                popularAnotacoesPaginaAtual();
            };

            $("#lblPaginaAtual").text(paginaAtual)
            $("#lblPaginasTotal").text(pages.length);

        });

        function updateIframeTransform()
        {
            $("#iframePlayer").css("transform", "rotate(" + rotate + "deg) scale(" + zoom + ")");
        }

        function zoomIn()
        {
            zoom += 0.1;

            updateIframeTransform();

            toggleAbaAnotacoes(false);
        }

        function zoomOut()
        {
            zoom -= 0.1;

            updateIframeTransform();

            toggleAbaAnotacoes(false);
        }

        function rotatePageRight()
        {
            rotate -= 90;

            updateIframeTransform();

            toggleAbaAnotacoes(false);
        }

        function rotatePageLeft()
        {
            rotate += 90;

            updateIframeTransform();

            toggleAbaAnotacoes(false);
        }

        var elem = document.documentElement;

        function resetZoom()
        {
            zoom = 1;

            updateIframeTransform();

            toggleAbaAnotacoes(false);
        }

        function fullscreenPage(action = null)
        {
            if(action === true)
            {
                $("#iframePlayer").addClass("fullscreen");

                if (elem.requestFullscreen)
                {
                    elem.requestFullscreen();
                }
                else if (elem.mozRequestFullScreen)
                { /* Firefox */
                    elem.mozRequestFullScreen();
                }
                else if (elem.webkitRequestFullscreen)
                { /* Chrome, Safari and Opera */
                    elem.webkitRequestFullscreen();
                }
                else if (elem.msRequestFullscreen)
                { /* IE/Edge */
                    elem.msRequestFullscreen();
                }
            }
            else if(action === false)
            {
                $("#iframePlayer").removeClass("fullscreen");

                if (document.exitFullscreen)
                {
                    document.exitFullscreen();
                }
                else if (document.mozCancelFullScreen)
                { /* Firefox */
                    document.mozCancelFullScreen();
                }
                else if (document.webkitExitFullscreen)
                { /* Chrome, Safari and Opera */
                    document.webkitExitFullscreen();
                }
                else if (document.msExitFullscreen)
                { /* IE/Edge */
                    document.msExitFullscreen();
                }
            }
            else
            {
                if($("#iframePlayer").hasClass("fullscreen"))
                {
                    $("#iframePlayer").removeClass("fullscreen");

                    if (document.exitFullscreen)
                    {
                        document.exitFullscreen();
                    }
                    else if (document.mozCancelFullScreen)
                    { /* Firefox */
                        document.mozCancelFullScreen();
                    }
                    else if (document.webkitExitFullscreen)
                    { /* Chrome, Safari and Opera */
                        document.webkitExitFullscreen();
                    }
                    else if (document.msExitFullscreen)
                    { /* IE/Edge */
                        document.msExitFullscreen();
                    }
                }
                else
                {
                    $("#iframePlayer").addClass("fullscreen");

                    if (elem.requestFullscreen)
                    {
                        elem.requestFullscreen();
                    }
                    else if (elem.mozRequestFullScreen)
                    { /* Firefox */
                        elem.mozRequestFullScreen();
                    }
                    else if (elem.webkitRequestFullscreen)
                    { /* Chrome, Safari and Opera */
                        elem.webkitRequestFullscreen();
                    }
                    else if (elem.msRequestFullscreen)
                    { /* IE/Edge */
                        elem.msRequestFullscreen();
                    }
                }
            }

            toggleAbaAnotacoes(false);
        }

        function voltarPagina()
        {
            if(paginaAtual > 1)
            {
                paginaAtual --;

                $("#iframePlayer").attr('src', url_apostila + "/" + pages[paginaAtual - 1]);
            }

            $("#lblPaginaAtual").text(paginaAtual);

            toggleAbaAnotacoes(false);
        }

        function proximaPagina()
        {
            // $('#iframePlayer').fadeOut();

            if(paginaAtual <= (pages.length - 1))
            {
                paginaAtual ++;

                $("#iframePlayer").attr('src', url_apostila + "/" + pages[paginaAtual - 1]);
            }

            $("#lblPaginaAtual").text(paginaAtual);

            toggleAbaAnotacoes(false);
        }

        function goToPage(pagina)
        {
            if(pagina >= 1 && pagina <= (pages.length - 1))
            {
                paginaAtual = pagina;

                $("#iframePlayer").attr('src', url_apostila + "/" + pages[paginaAtual - 1]);
            }

            $("#lblPaginaAtual").text(paginaAtual);

            toggleAbaAnotacoes(false);
        }

        var marcandoPin = false;

        function habilitarMarcacaoPin()
        {
            if($("#divPopUpMarcacaoHabilitada").length > 0)
            {
                $("#divPopUpMarcacaoHabilitada").remove();

                marcandoPin = false;
            }
            else
            {
                var popupMarcacao = `<div id="divPopUpMarcacaoHabilitada" style="position: fixed;right: 0px;z-index: 9999;background: rgba(0, 0, 0, 0.85);color: white;padding: 20px;font-size: 16px;border-radius: 10px;margin: 10px;transition: all 0.3s ease-in 0s;top: 0px;opacity: 1;max-width: 200px;text-align: center;">
                    <button class="btn btn-link text-white" onclick="$(this.parentNode).remove();marcandoPin = false;" style=" position: absolute; right: 0px; top: 0px; padding: 8px; ">
                        <i class="fa-times-circle fa-fw fa-lg my-auto fas" data-toggle="tooltip" title="" data-original-title="Marcar página atual"></i>
                    </button>
                    <i class="fa-thumbtack fa-fw fa-lg my-auto fas text-primary d-block mx-auto mb-3" data-toggle="tooltip" title="" data-original-title="Marcar página atual" style=" margin-bottom: 14px !important; "></i>
                    Marcação habilitada, clique no conteúdo do PDF ou revista para realizar uma anotação!
                </div>`;

                $("body").prepend( popupMarcacao );

                marcandoPin = true;

                $("div.aba-anotacoes").addClass("show");
            }
        }

        function getClickPosition(e)
        {
          


            if(!marcandoPin)
            {

                return;
            }

          

            marcandoPin = false;

            $("#divPopUpMarcacaoHabilitada").remove();

            //alert(dragging);

            //if (dragging)
            //return;

            var offset = $('#iframePlayer').contents().find('body').offset();

            var x = (e.pageX - offset.left) - 21;
            var y = (e.pageY - offset.top) - 21;

            var indicador = $('<div class="indicador-anotacao"><i class="fas fa-thumbtack fa-fw" onclick="window.parent.$(\'html, body\').animate({ scrollTop: window.parent.$(\'#divAnotacoes\').offset().top - 135 }, 2000);"></i><button class="btn-deletar-anotacao"><i class="fas fa-times-circle"></i></button></div>');
            indicador.css('left', x + "px");
            indicador.css('top', y + "px");


            swal({
                title: "Marcar anotação",
                text: "Utilize o campo abaixo para escrever sua anotação:",
                content: "input",
                icon: "warning",
                buttons: ["Cancelar", "Marcar"],
                dangerMode: true,
            })
            .then((salvar) =>
            {

                if (salvar != null)
                {
                    //swal("Anotação salva!", _response.success, "success");

                    $.ajax({
                        url: parent.appurl + '/play/conteudo/' + conteudo_id + '/anotacao/nova',
                        type: 'post',
                        data: { "_token":"{{ csrf_token() }}", 'conteudo_id' : '{{ $conteudo->id }}', 'pagina': paginaAtual, 'trecho' : '', 'anotacao' : salvar, 'pos_y': y, 'pos_x': x, 'start': '', 'end' : '' },
                        dataType: 'json',
                        success: function( _response )
                        {
                            //swal("Anotação salva!", _response.success, "success");
                            var popupAnotacao = `<div id="divPopUpAnotacao" style="position: fixed;right: 0px;z-index: 9999;background: rgba(0, 0, 0, 0.65);color: white;padding: 20px;font-size: 16px;border-radius: 10px;margin: 10px;transition: all 0.3s ease-in 0s;top: 0px;opacity: 1;">
                                <i class="fa-thumbtack fa-fw fa-lg my-auto fas text-primary"></i>
                                Anotação salva com sucesso!
                                <button class="btn btn-link text-white" onclick="$(this.parentNode).remove();">
                                    <i class="fa-times-circle fa-fw fa-lg my-auto fas" data-toggle="tooltip" title="" data-original-title="Dispensar aviso"></i>
                                </button>
                            </div>`;

                            $("body").prepend( popupAnotacao );
                            setTimeout( () => {

                                $("#divPopUpAnotacao").css('top', '-50px');

                                $("#divPopUpAnotacao").css('opacity', '0');

                                setTimeout( () => {

                                    $("#divPopUpAnotacao").remove();

                                }, 1000);

                            }, 1500);

                            $("#btnShowAnotacoes").removeClass('d-none');

                            var htmlAnotacao = '<div id="divAnotacao' + _response.anotacao_id + '" class="item w-100 mb-3 mb-auto position-relative px-4 py-2 border-bottom">'+
                                '<button onclick="goToPage(' + paginaAtual + ');" class="btn btn-link bg-transparent d-block px-0 mb-1 text-wrap mw-100 text-left" style="color: #0D1033; font-weight: 600;">'+
                                    'Anotação pg. ' + paginaAtual +
                                '</button>'+
                                '<button onclick="deletarAnotacao(' + _response.anotacao_id + ', {{ $conteudo->id }})" class="btn bg-transparent text-danger position-absolute" style="top:5px; right: 0px;"><i class="fas fa-trash" data-toggle="tooltip" title="Excluir anotação"></i></button>'+
                                '<p class="text-wrap mb-1" style="text-align: justify;">' + salvar + '</p>'+
                                '</div>';

                            $("#divAnotacoes").append( htmlAnotacao );

                            indicador.attr('id', 'marcadorAnotacao' + _response.anotacao_id);

                            indicador.find('.btn-deletar-anotacao').css("display", "none");

                            indicador.find('.btn-deletar-anotacao').attr("onclick", "parent.deletarAnotacao(" + _response.anotacao_id + ", {{ $conteudo->id }});");

                            if(salvar != "")
                            {
                                indicador.append("<div class='indicador-texto'>" + salvar + "</div>");
                            }

                            if(_response.anotacao != null)
                            {
                                anotacoes.push(_response.anotacao);
                            }

                            $('#iframePlayer').contents().find('body').append( indicador );

                            if($('#divAnotacoes .item').length > 0)
                            {
                                $("#divSemAnotacoes").addClass('d-none');
                            }

                        },
                        error: function( _response )
                        {
                            
                        }
                    });
                }
            });
        }

        function popularAnotacoesPaginaAtual()
        {
            var anotacoesPaginaAtual = anotacoes.filter( e => e.pagina == paginaAtual);


            if(anotacoesPaginaAtual.length > 0)
            {
                anotacoesPaginaAtual.forEach((value, index, array) => {

                    var indicador = $('<div id="divAnotacao' + value.id + '" class="indicador-anotacao"><i class="fas fa-thumbtack fa-fw" onclick="window.parent.$(\'html, body\').animate({ scrollTop: window.parent.$(\'#divAnotacoes\').offset().top - 135 }, 2000);"></i><button class="btn-deletar-anotacao"><i class="fas fa-times-circle"></i></button></div>');
                    indicador.css('left', value.pos_x + "px");
                    indicador.css('top', value.pos_y + "px");

                    if(value.anotacao != "")
                    {
                        indicador.append("<div class='indicador-texto'>" + value.anotacao + "</div>");
                    }

                    indicador.find('.btn-deletar-anotacao').on("click", () => deletarAnotacao(value.id, {{ $conteudo->id }}));

                    $('#iframePlayer').contents().find('body').append( indicador );
                });
            }
        }


        function deletarAnotacao(anotacao_id, conteudo_id)
        {
            if(anotacoes.find( e => e.id == anotacao_id))
            {
                anotacoes.splice(anotacoes.findIndex( e => e.id == anotacao_id), 1);
            }

            swal({
                title: "Deletar",
                text: "Você deseja mesmo deletar esta anotação?",
                icon: "warning",
                buttons: ["Não", "Sim"],
                dangerMode: true,
            })
            .then((deletar) =>
            {
                if (deletar)
                {
                    $.ajax({
                        url: parent.appurl + '/play/conteudo/' + conteudo_id + '/anotacao/' + anotacao_id + '/deletar',
                        type: 'get',
                        dataType: 'json',
                        success: function( _response )
                        {
                            

                            if(_response.success != null)
                            {
                                swal("Anotação deletada!", _response.success, "success");

                               

                                $("#divAnotacao" + anotacao_id).remove();

                                $("#marcadorAnotacao" + anotacao_id).remove();

                                $(document.getElementById('iframePlayer').contentWindow.document.getElementById('marcadorAnotacao' + anotacao_id)).remove();

                                $('#iframePlayer').contents().find("body #divAnotacao" + anotacao_id).remove();

                                if($('#divAnotacoes .item').length == 1)
                                {
                                    $("#divSemAnotacoes").removeClass('d-none');
                                }
                            }
                        },
                        error: function( _response )
                        {
                            
                        }
                    });
                }
            });

            if(window.event != null)
            {
                if (!e)
                {
                    var e = window.event;
                    e.cancelBubble = true;
                }
                if (e.stopPropagation)
                {
                    e.stopPropagation();
                }
            }
        }

        function toggleAbaAnotacoes(action = null)
        {
            if(action === true)
            {
                $("div.aba-anotacoes").addClass("show")
            }
            else if(action === false)
            {
                $("div.aba-anotacoes").removeClass("show")
            }
            else
            {
                if(!$("div.aba-anotacoes").hasClass("show"))
                {
                    $("div.aba-anotacoes").addClass("show")
                }
                else
                {
                    $("div.aba-anotacoes").removeClass("show")
                }
            }
        }

        // function marcarPagina()
        // {
        //     paginaMarcada = paginaAtual;

        //     $("#btnMarcarPagina i").removeClass('far');
        //     $("#btnMarcarPagina i").addClass('fas');
        // }

        function marcarPagina()
        {
            if((conteudo_id > 0) == false)
            {
                return;
            }

            if(paginaMarcada == paginaAtual)
            {
                $("#btnMarcarPagina i").removeClass('far');
                $("#btnMarcarPagina i").addClass('fas');

                return;
            }

            popupCounter = 0;

            let pagina = paginaAtual;

            paginaMarcada = pagina;

            $("#txtPaginaMarcada").text('Página marcada: ' + pagina);

            $("#btnMarcarPagina i").removeClass('far');
            $("#btnMarcarPagina i").addClass('fas');
            $("#btnMarcarPagina i").addClass('animated rubberBand');

            //Animacao botao
            $("#btnMarcarPagina").addClass('animated rubberBand');

            let url = parent.appurl + '/play/conteudo/' + conteudo_id + '/marcar-pagina/' + pagina;

          

            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                success: function( _response )
                {
                    

                    var popupMarcacao = `<div id="divPopUpMarcacao${ pagina }" style="position: fixed;bottom:0px; left: 0px;z-index: 9999;background: rgba(0, 0, 0, 0.65);color: white;padding: 20px;font-size: 16px;border-radius: 10px;margin: 10px;transition: all 0.3s ease-in 0s;opacity: 1;">
                        <i class="fa-bookmark fa-fw fa-lg my-auto fas text-primary" data-toggle="tooltip" title="" data-original-title="Marcar página atual"></i>
                        Página ${ pagina } marcada com sucesso!
                        <button class="btn btn-link text-white" onclick="$(this.parentNode).remove();">
                            <i class="fa-times-circle fa-fw fa-lg my-auto fas" data-toggle="tooltip" title="" data-original-title="Marcar página atual"></i>
                        </button>
                    </div>`;

                    $("body").prepend( popupMarcacao );

                    setTimeout( () => {

                        $("#divPopUpMarcacao" + pagina).css('bottom', '-100px');

                        $("#divPopUpMarcacao" + pagina).css('opacity', '0');

                        setTimeout( () => {

                            $("#divPopUpMarcacao" + pagina).remove();

                        }, 1000);

                    }, 1500);

                },
                error: function( _response )
                {
                    
                }
            });
        }

    </script>

</html>
