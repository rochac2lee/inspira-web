<!DOCTYPE html>
{{-- <html lang="pt-br"> --}}
<html lang="{{ app()->getLocale() }}">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TSFWC7Z7Y1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-TSFWC7Z7Y1');
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Edulabzz">

    @php
        //verifica o privilegio do usuario, se for super admin ou admin pega os dados da primeira instituicao do
        //usuario e o template

                $instituicao_id = auth()->user()->instituicao_id;
                $templateId    = 2;



    @endphp


    @if(Auth::check() ? (Auth::user()->escolas) : false)
        <title>{{ ucfirst(Auth::user()->escolas->instituicao->titulo) }} - @yield('title')</title>
        <link rel="icon" href="{{ config('app.cdn') }}/fr/interface_2018/assets/custom/{{ $instituicao_id }}/images/icon.png">
    @else
        <title>{{ config('app.name') }} - @yield('title')</title>
    @endif

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/flexboxgrid/6.3.1/flexboxgrid.min.css"
      type="text/css"
    />

    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/interface_2018/assets/css/app.min.css">

    <!-- Bootstrap CSS -->
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous"> --}}

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>

    <!-- Bootstrap Select -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">

    <!-- Font Awesome -->
    {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/interface_2018/assets/css/all.css">

    <!-- Font Body -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>

    <!-- Animations -->
    {{-- <link href="{{ config('app.cdn') }}/assets/css/animated.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" />

    <!-- Laravel Full calendar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/core.js"></script>

    <!-- Jquery UI datepicker -->
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/interface_2018/assets/css/jquery-ui.min.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/interface_2018/assets/css/jquery-ui.theme.min.css">

    <!-- datetimepicker bootstrap -->
    <link href="{{config('app.cdn')}}/fr/interface_2018/assets/css/gijgo.min.css" rel="stylesheet" type="text/css" />


    <!-- SCEditor -->
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/interface_2018/assets/sceditor/themes/default.min.css" />
    <script src="{{config('app.cdn')}}/fr/interface_2018/assets/sceditor/sceditor.min.js"></script>
    <script src="{{config('app.cdn')}}/fr/interface_2018/assets/sceditor/languages/pt-BR.js"></script>
    <script src="{{config('app.cdn')}}/fr/interface_2018/assets/sceditor/formats/bbcode.js"></script>

    @php
        $cores = session('cores');

        if (!$cores) {
            $cores = [];
        }
    @endphp

    <style>

        /* VARIAVEIS PARA A CONF. DE CORES DO SITE */
        :root {
            --bg-color: #f8fbff !important;
            --primary-color: #ff8040 !important;
            --secundary-color: #c25022 !important;
            --text-color: #f8fbff !important;
            --nb-background-color: #000000 !important;
            --nb-links: #e65a24 !important;
            --nb-links-hover: #e65a24 !important;
            --sb-links-color: #ffffff !important;
            --sb-background-color: #c43b12 !important;
            --sb-links-hover: #f8fbff !important;
            --sb-bglinks-hover: #e65a24 !important;
            --sb-bglinks-active: #c43b12 !important;
            --sb-links-color-hover-active: #f8fbff !important;
            --ft-background-color: #e65a24 !important;
            --ft-color: #000000 !important;
            --ft-color-links: #f8fbff !important;
            --hub-primary-color: #e65a24 !important;
            --hub-background-color: #202020 !important;
            --btn-text-color: #400000 !important;
            --btn-text-color-hover: #c43b12 !important;
        }

        .div_teste{
            position:absolute;
            top:0;
            left:0;
            z-index:11;
            background-color:#000;
            width:100%;
            height:100%;
            opacity: .50;
            filter: alpha(opacity=65);
        }

        .modalNova{
            opacity: 1.0;
            position:absolute;
            top: 20%;
            border-radius: 20px;
            left: 50%;
            transform: translatex(-50%);
            width: 50%;
            height: 40%;
            background-color:#ffffff;
            color:white;
            z-index: 10;
        }
    </style>

    {{--  Estilização customizada  --}}
    @if(Auth::check() ? (Auth::user()->escola) : false)

        <link rel="icon" href="{{ config('app.cdn') }}/fr/interface_2018/assets/custom/{{ Auth::user()->escolas }}/images/icon.png">
        <style>
            :root {
                --logoUrl: url("{{ config('app.cdn') }}/uploads/escolas/capas/{{ Auth::user()->escola->capa }}") !important;
                --logoHubUrl: url("{{ config('app.cdn') . config('styles.logo_hub_url') }}") !important;
            }
        </style>

    @else

        <link rel="icon" href="{{ config('styles.icon_url') }}">

        <style>
            :root {
                --primary-color: {{ config("styles.primary_color") }} ;
                --logoUrl: url("{{ config('app.url') . config('styles.logo_url') }}") ;

                --logoHubUrl: url("{{ config('app.url') . config('styles.logo_hub_url') }}") ;
                --hub-primary-color: {{ config('styles.hub_primary_color') }} ;
                --hub-background-color: {{ config('styles.hub_background_color') }} ;
            }
        </style>
    @endif

    <!-- Custom styles main -->
    @if(Request::is('hub') || Request::is('hub/*'))
        <link rel="stylesheet" href="{{ config('app.cdn') }}/assets/css/hub-main.css">
    @endif
    {{--
    @if(strtolower(config('app.name')) == "toolzz")
        @if(Auth::check())
            @switch(Auth::user()->privilegio_id)
                @case(1) @case(2)
                    <link rel="stylesheet" href="{{ config('app.cdn') }}/assets/css/custom/manager.css">
                    @break
                @case(3)
                    <link rel="stylesheet" href="{{ config('app.cdn') }}/assets/css/custom/master.css">
                    @break
                @case(5)
                    <link rel="stylesheet" href="{{ config('app.cdn') }}/assets/css/custom/school.css">
                    @break
                @default
                    <link rel="stylesheet" href="{{ config('app.cdn') }}/assets/css/custom/play.css">
                    @break
            @endswitch
        @elseif(!Auth::check())
            <link rel="stylesheet" href="{{ config('app.cdn') }}/assets/css/custom/play.css">
        @endif
    @endif
     --}}

    @yield('headend')


    @if(Request::is('gestao/*'))
    <style>
        @media (max-width: 768px) {
            footer {
                display: none !important;
            }

            #menu-conteudo-cursos {
                left: 15px !important;
            }

            #menu-conteudo-cursos .menu-button {
                left: 15px !important;

            }
            #menu-conteudo-cursos .menu-item {
                right:0px !important;
            }
        }
    </style>
    @endif

</head>

<!-- CONFIG. TEMPLATE -->
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/interface_2018/assets/template/2/css/style.css">
<script src="{{config('app.cdn')}}/fr/interface_2018/assets/js/lazysizes.min.js" async=""></script>
<script src="{{config('app.cdn')}}/fr/interface_2018/assets/js/ls.unveilhooks.min.js" async=""></script>
<body id="page-top" class="new-theme">

<!-- Função para verificar se expirou o trial do usuario, se espirou direciona ele para pagina de pagamento -->

<!--Fim função para verificar se expirou o trial do usuario, se espirou direciona ele para pagina de pagamento -->
    <!-- Navigation -->
    @if(Request::is('hub') || Request::is('hub/*'))
        @include('utilities.hub-navbar')
    @else
        @if($templateId)
            @include('utilities.template.'.$templateId.'.main-navbar')
        @else
            @if(!Auth::check() && isset($plataforma->template_id))
                @include('utilities.template.'.$plataforma->template_id.'.main-navbar')
            @else
                @include('utilities.main-navbar')
            @endif
        @endif
    @endif

    <div id="divBackgroundTipos" onclick="closeConteudos();" class="d-none" style="transition: 0.3s all ease-in-out; opacity: 0; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 5;">
    </div>

    @yield('content')

    <!-- notifications -->
    {{-- @include('utilities.notifications') --}}

    @if($templateId)
        @include('utilities.template.'.$templateId.'.footer')
    @else
        @if(!Auth::check() && isset($plataforma->template_id))
            @include('utilities.template.'.$plataforma->template_id.'.footer')
        @else
            @include('utilities.footer')
        @endif
    @endif




    <!-- Bootstrap core JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>


    <!-- Jquery UI -->
    <script src="{{config('app.cdn')}}/fr/interface_2018/assets/js/jquery-ui.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

    <!-- Sweet Alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Jquery Easing -->
    <script src="{{config('app.cdn')}}/fr/interface_2018/assets/js/jquery.easing.compatibility.min.js"></script>

    <!-- Custom Scrolling Nav -->
    <script src="{{config('app.cdn')}}/fr/interface_2018/assets/js/scrolling-nav.js"></script>

    <!-- jQuery-Mask-Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

    <!-- Laravel Full calendar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/locale/pt-br.js"></script>


    <!-- Bootstrap select -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

    <!-- Bootstrap select -->
    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-pt_BR.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/core.js"></script>


    <!-- Custom Smooth Scrolling -->
    {{--  <script src="{{ config('app.cdn') }}/assets/js/smooth-scrolling.js"></script>  --}}

    <!-- Declare App URL -->
    <script>var appurl = '{{ url('') }}';</script>

    <!-- Custom JavaScript -->
    <script src="{{config('app.cdn')}}/fr/interface_2018/assets/js/main.js"></script>


    <!-- Multiple Select CSS and JS: -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

    <!-- Tabelas css/js -->
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>


    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>

    <!-- Impost sweetalert -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script> -->
    @if(Auth::check())
        @if(Auth::user()->privilegio_id == 4 && Request::is('gestao/*'))
            <script>
                /*
            window.onload = function(){

                swal({
                    title: 'Sem acesso!',
                    text: 'Seu usuário não tem acesso.',
                    allowEscapeKey     : false,
                    allowOutsideClick  : false,
                    imageWidth         : 600,
                    imageHeight        : 200,
                    timer              : 3000,
                    })

                const home = 'catalogo';
                //Função que redireciona para pagina home
                setTimeout(function () {
                    window.location.href = home;
                },100);
            }
*/
            </script>

        @endif

        @php
            $rota = \Request::route()->getName();

            $dataCadastro = strtotime(date('Y-m-d', strtotime(Auth::user()->created_at)));
            $dataAtual = strtotime(date('Y-m-d'));

            $diasCorridos = ($dataAtual - $dataCadastro) / 60 / 60 / 24;
        @endphp

        @if(Auth::user()->trial == 1 && $diasCorridos >= 7 &&
        $rota != \Request::is('planos') && $rota != \Request::is('planos/*')
        && $rota != \Request::is('pedidos/*') && $rota != \Request::is('carrinho') )

        <script>
            swal({
            title: 'Seu período traial acabou!',
            text: 'Sua conta trial atingiu 7 dias e expirou vamos te redirecionar para a página de compra em segundos.',
            imageUrl: '{{ config('app.cdn') }}/svg/404.svg',
            allowEscapeKey     : false,
            allowOutsideClick  : false,
            imageWidth         : 600,
            imageHeight        : 200,
            timer              : 3000,
            })

            //Função que redireciona para pagina de pagamento
            setTimeout(function () {
                    window.location.href= 'http://toolzz.com.br/beta/pricing';
                    },3200);

        </script>
        @endif


    @endif


    <!--Fim  função para verificar se expirou o trial do usuario, se espirou direciona ele para pagina de pagamento -->
    <script>

        $( document ).ready(function(){

            if($('.sidebar .side-group').length > 0 && $('.sidebar .side-group-item.active, .sidebar .side-sub-group-item.active').length > 0)
            {
                $('.sidebar .side-group').animate({
                    scrollTop:  $('.sidebar .side-group').scrollTop() - $('.sidebar .side-group').offset().top + $('.sidebar .side-group-item.active, .sidebar .side-sub-group-item.active').offset().top - 25
                }, 250);
            }

            //DSP BLOCK NA OVERLAY QUANDO ABRE O MENU DE CONFIGURAÇÕES
            $('.nav-item').on('show.bs.dropdown', function () {
                $('<div class="overlay"></div>').appendTo(document.body);
                $('.overlay').fadeIn(200);
            })
            $('.nav-item').on('hide.bs.dropdown', function () {
                $('.overlay').remove();
            })
            //END - DSP BLOCK NA OVERLAY QUANDO ABRE O MENU DE CONFIGURAÇÕES

            //DSP BLOCK NA OVERLAY QUANDO ABRE O MENU DE VISUALIZAR AS MENSAGENS
            setTimeout(() => {
                $('.fa-bell').addClass('animated tada');
            }, 3000);
            $('.dropdown-hover').on('show.bs.dropdown', function () {
                $('<div class="overlay"></div>').appendTo(document.body);
                $('.overlay').fadeIn(200);
            })
            $('.dropdown-hover').on('hide.bs.dropdown', function () {
                $('.fa-bell').removeClass('animated text-primary').addClass('text-secondary');
                $('.badgeMsgs').fadeOut(200);
                $('.overlay').remove();
            })
            //END - DSP BLOCK NA OVERLAY QUANDO ABRE O MENU DE VISUALIZAR AS MENSAGENS



            if(window.screen.width < 768)
            {
                $(".sidebar").removeClass("show");
            }

            @if( isset($errors) ? $errors->any() : false)
                swal("", '{{ $errors->first() }}', 'error');
            @elseif(session()->has('success'))
                swal("", '{{ session()->get('success') }}', 'success');
            @elseif(session()->has('message'))
                swal("", '{{ session()->get('message') }}', 'success');
            @elseif(session()->has('warning'))
                swal("Atenção!", '{{ session()->get('warning') }}', 'warning');
            @elseif(session()->has('error'))
                swal("", '{{ session()->get('error') }}', 'error');
            @endif

            $('[data-toggle="tooltip"]').tooltip();


            $('form [type=submit]').on('click', (element) => {

                window.checarPreenchimento($(element.target).closest("form"));
            });

            $('form').on('submit', (element) => {

                window.checarPreenchimento($(element.target).closest("form"));
            });


        });

    </script>

    <!-- Adicionando embedded da instituicao, caso exista -->
    @if(isset(Auth::user()->escola->instituicao->embedded))
        {{-- {!! Auth::user()->escola->instituicao->embedded !!} --}}
    @endif

    @yield('bodyend')


    @yield('bodyend-component')

    <!-- Custom JavaScript -->
    <script src="{{config('app.cdn')}}/fr/interface_2018/assets/js/app.min.js"></script>
    <!-- USERWAY acessibilidade -->
    <script>
        (function(d){
            var s = d.createElement("script");
            s.setAttribute("data-account", "km7YAnlPrA");
            s.setAttribute("src", "https://accessibilityserver.org/widget.js");
            (d.body || d.head).appendChild(s);
        })
        (document)
    </script>
    <noscript>Please ensure Javascript is enabled for purposes of <a href="https://accessibilityserver.org"website> accessibility</a></noscript>

</body>

</html>
