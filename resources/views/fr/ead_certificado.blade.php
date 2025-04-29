<!DOCTYPE html>
<html lang="pt-BR">
<meta charset="utf-8">
<link rel="icon" type="image/ico" href="{{ config('app.cdn') }}/fr/imagens/favicon.ico">
<title>Certificado</title>

<!-- Carrega paper.css -->
<link rel="stylesheet" href="{{ config('app.cdn') }}/fr/certificado-ead/includes/plugins/bootstrap-5/css/bootstrap.min.css?v1.1">
<link rel="stylesheet" href="{{ config('app.cdn') }}/fr/certificado-ead/includes/css/paper.css?v1.1">

<script src="{{ config('app.cdn') }}/fr/certificado-ead/includes/js/jquery-3.6.1.min.js"></script>
<script src="{{ config('app.cdn') }}/fr/certificado-ead/includes/plugins/bootstrap-5/js/bootstrap.min.js"></script>

<body class="A4 landscape">
    <div id="livro">
        <!-- Pagina Inicial p1 -->
        <section class="pagina p1">
            <article>
                <div class="tit1">A Editora Opet tem a grata satisfação de certificar</div>
                <div class="titNome">{{@$certificado->usuario->nome_completo}}</div>
                <div class="tit2">que concluiu, com êxito, o curso de qualificação EAD</div>
                <div class="titCurso">{{@$certificado->trilha->titulo}}</div>
                <div class="data">Conclusão:<br> {{@$certificado->updated_at->format('d/m/Y')}}</div>
                <!--<div class="validador">Validador:<br> {{@$certificado->chave_validacao}}</div>-->
            </article>
        </section>
        <!-- Pagina 2 -->
        <section class="pagina p2">
            <article>
                <div class="desc">
                    <strong>{{@$certificado->trilha->titulo}}</strong><br>
                    Carga-horária: {{@$certificado->trilha->carga_horaria}} Horas
                </div>
                <div class="desc2">
                    @foreach($certificado->trilha->cursos as $c)
                        Aula {{$loop->iteration}}: {{$c->titulo}}<br>
                    @endforeach
                </div>
                <!--<div class="qrcode"><p>Valide o seu certificado<br> escaneando o Qr Corde<br> abaixo</p><img src="imagens/qrcodeDemo.png"></div>-->
            </article>
        </section>
    </div>

<div class="btn_flutuante no-print no-mobile">
    <div class="btn_2" id="btn_zoom1"><img src="{{ config('app.cdn') }}/fr/certificado-ead/imagens/btn_zoom1.png"></div>
    <div class="btn_3" id="btn_full"><img src="{{ config('app.cdn') }}/fr/certificado-ead/imagens/btn_full.png"></div>
    <div class="btn_4" id="btn_zoom2"><img src="{{ config('app.cdn') }}/fr/certificado-ead/imagens/btn_zoom2.png"></div>
    <div class="btn_1" id="btn_imprimir"><img src="{{ config('app.cdn') }}/fr/certificado-ead/imagens/btn_print.png"></div>
</div>
<script type="text/javascript">
    var fFullScreen = false;
    var zoom = 1;
    $( document ).ready(function() {

        /*Imprimir*/
        $( "#btn_imprimir" ).click(function() {
            zoom = 1;
            $('#livro').css('transform', 'scale(' + zoom + ')');
            window.print();
        });

        /*Full Screen*/
        $( "#btn_full" ).click(function() {
            if(fFullScreen === false){
                fFullScreen = true;
                entrarFullScreen();
            }else{
                fFullScreen = false;
                sairFullScreen();
            }
        });

        /*ZOOM*/
        $( "#btn_zoom1" ).click(function() {
            zoom += 0.1;
            if(zoom < 1.5){
                $('#livro').css('transform', 'scale(' + zoom + ')');
            }
        });

        /*ZOOM*/
        $( "#btn_zoom2" ).click(function() {
            zoom = 1;
            $('#livro').css('transform', 'scale(' + zoom + ')');
        });


        /*  Funcão FullScreen  */
        function entrarFullScreen(){
            if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.msRequestFullscreen) {
                    document.documentElement.msRequestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            }
        }

        function sairFullScreen(){
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        }
    });
</script>
</body>
</html>
