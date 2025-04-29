<!doctype html>
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
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/js/slick/slick.css" type="text/css" charset="utf-8" />

    <!-- JS -->
    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/jquery/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="{{ config('app.cdn') }}/fr/includes/js/bootstrap/bootstrap.min.js"></script>
    <script src="{{ config('app.cdn') }}/fr/includes/js/slick/slick.min.js" type="text/javascript"></script>

    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/2021/style_v2.css">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/2021/style_2021_v1.css">
    <!-- <script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet"> -->
    <title>Opet Inspira - Plataforma Educacional</title>
<style>
    .cadastro.filtro .itens .item .user-desc{
        height:380px;
    }
</style>
    <script type="text/javascript">
        $(document).ready(function(){
        /*    var selectEscola = new SlimSelect({
                select: '.multipleEscola',
                placeholder: 'Buscar',
                searchPlaceholder: 'Buscar',
                closeOnSelect: true,
                allowDeselectOption: true,
                selectByGroup: true,
            });
*/
            $(document).bind("contextmenu",function(e){
                return false;
            });

            $('.ht-skip').children().css('height','50px'); /// div do acessivel em libras
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>
<body class="cadastro filtro">
<main class="content">
    <section class="section conteudo-interna">
        <div class="area-interna mx-auto">
            <section class="user">
                <div class="d-flex flex-row justify-content-center">
                    <div class="col-auto">
                        <div class="foto-user mb-2" style="background-image: url({{auth()->user()->avatar}});"><img src="{{ config('app.cdn').'/fr/imagens/2021/foto-userFundo.png'}}"></div>
                    </div>
                </div>
                <div class="d-flex flex-row justify-content-center">
                    <div class="col-auto">
                        <p class="mb-1 fs-14">{{Auth::user()->nome}}</p>
                    </div>
                </div>
            </section>

            <h1 class="">Escolha o perfil que deseja utilizar no sistema</h1>

            <div class="itens slide-banner">
                @if(isset($dados[0]))
                    @foreach($dados[0] as $d)
                        @if($d->permissao == 'Z')
                            @include('fr/usuario/multiPermissao/card_super_usuario')
                        @elseif($d->permissao == 'I' && is_array($d->escola_id))
                            @include('fr/usuario/multiPermissao/card_gestor_instituicao')
                        @endif
                    @endforeach
                @endif
                @if(isset($dados[1]))
                    @foreach($dados[1] as $escola)
                        @foreach($escola as $d)
                            @if($d->permissao == 'C')
                                @include('fr/usuario/multiPermissao/card_coordenador')
                            @elseif($d->permissao == 'P')
                                @include('fr/usuario/multiPermissao/card_professor')
                            @elseif($d->permissao == 'A')
                                @include('fr/usuario/multiPermissao/card_aluno')
                            @elseif($d->permissao == 'R')
                                    @include('fr/usuario/multiPermissao/card_pai_responsavel')
                            @endif
                        @endforeach
                    @endforeach
                @endif
            </div>
        </div>
    </section>
</main>
</body>

<script type="text/javascript">
    $('.slide-banner').on('init', function(event, slick){
        $('.cadastro.filtro .itens').css("overflow", "initial");
    });

    $('.slide-banner').slick({
        dots: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
        cssEase: 'linear',
        arrows: true,
        autoplay: true,
        autoplaySpeed: 4500,
        responsive: [
            {
                breakpoint: 940,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true
                }
            }
        ]
    });
</script>
</html>
