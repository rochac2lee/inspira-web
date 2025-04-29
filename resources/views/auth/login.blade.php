<!doctype html>
<html lang="pt">
<head>
    <!-- Required meta tags -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TSFWC7Z7Y1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-TSFWC7Z7Y1');
    </script>
    <meta name="title" content="Opet Inspira" />
    <meta name="description" content="Opet Inspira" />
    <meta name="keywords" content="Opet Inspira" />
    <meta name="google-signin-client_id" content="231538886847-q9m6031f6fouo68b54qc0f4q3kjhujoe.apps.googleusercontent.com">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="icon" href="{{config('app.cdn')}}/fr/imagens/favicon.ico" type="image/x-icon" />

    <!-- Fonts -->
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/css/login2023/fonts/stylesheet.css" type="text/css" charset="utf-8" />
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/fonts/fontawesome/css/all.min.css" type="text/css" charset="utf-8" />
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slick/slick.css" type="text/css" charset="utf-8" />

    <!-- JS -->
    <!-- <script type="text/javascript" src="includes/js/jquery/jquery-3.4.1.min.js"></script> -->

    <!-- Plugin para a Ordenacao -->
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery/jquery-1.12.4.js"></script>
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery/jquery-ui.js"></script>

    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/bootstrap/popper.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/bootstrap/bootstrap.min.js"></script>


    <script src="{{config('app.cdn')}}/fr/includes/js/slick/slick.min.js" type="text/javascript"></script>
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery-mask/1.7.7/jquery.mask.min.js"></script>
    <script src="{{config('app.cdn')}}/fr/includes/js/form-validation.js"></script>
    <script src="{{config('app.cdn')}}/fr/includes/js/form-mask.js"></script>

    <style>
        /* imagens de fundo e rodapé */
        .section.atividade {
            background-image: url({{config('app.cdn')}}/fr/imagens/2021/deskFundoHome.webp), url({{config('app.cdn')}}/fr/imagens/2021/deskFundoHome-Back.webp);
            background-position: right top, right top;
            background-repeat: no-repeat, repeat-y;
        }
        .input-group-text.ico {
            background: url({{config('app.cdn')}}/fr/imagens/ico-groups-form2.png) left top no-repeat;
        }
        .ico-groups {
            background: url({{config('app.cdn')}}/fr/imagens/2021/ico-groups.png) no-repeat left top;
        }
        .ico-groups.amarelo {
            background: url({{config('app.cdn')}}/fr/imagens/ico-groups-amarelo.png) no-repeat left top;
        }
    </style>
    <!-- CSS -->
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/css/bootstrap/bootstrap.min.css" crossorigin="anonymous">
    <!--
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/css/2021/style_v1.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/css/2021/style_2021.css">
    -->
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/css/login2023/style.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/css/login2023/style_login_2023.css">

    <script src="{{config('app.cdn')}}/fr/includes/js/sweetalert.min.js"></script>

    <title>Opet Inspira - Plataforma Educacional</title>

    <script type="text/javascript">
        var root = document.documentElement;
        // Tamanho da tela para posicionamento do rodapé
        root.style.setProperty('--tamanhoTela', window.innerHeight+'px');
        // Padroes de Cores
        root.style.setProperty('--corPrincipal', '#1d2345');
        root.style.setProperty('--corSecundaria', '#f8ad18');
        root.style.setProperty('--corMenu', '#f8ad18cc');

        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <style>
        .cadastro.login.fundo{
            background: url({{config('app.cdn')}}/fr/imagens/2021/fundos/deskFundoLogin-{{rand(1, 15)}}.webp) right top no-repeat; background-size: cover;
        }

        @media (max-width: 940px) {
            .cadastro.login.fundo{
                background: url({{config('app.cdn')}}/fr/imagens/2021/deskFundoPrincipalMobile-1.webp) center top no-repeat; background-size: cover;
            }
        }

    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="cadastro login fundo">
<div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
</div>
<main class="content">
    <section class="topo">
        <div class="row d-flex flex-row-reverse">
            <div class="row">
                <div class="col">
                    <a target="blanck" href="https://loja.editoraopet.com.br/"><i class="ico-groups i1"></i></a>
                    <a target="blanck" href="https://editoraopet.com.br/"><i class="ico-groups i2"></i></a>
                    <a target="blanck" href="https://www.facebook.com/editora.opet.3"><i class="ico-groups i3"></i></a>
                    <a target="blanck" href="https://www.instagram.com/editoraopet/"><i class="ico-groups i4"></i></a>
                    <a target="blanck" href="https://www.youtube.com/channel/UCPXnBBshEViOKyv9EoVKRaQ"><i class="ico-groups i5 mr-0"></i></a>
                </div>
            </div>
        </div>
    </section>

    <section class="formulario mb-4">
        <div class="row d-flex justify-content-center">
            <div class="row mr-45">
                <div class="col">
                    <div class="contentForm">
                        <div class="title mt-3 mb-4 pt-1">Seja bem-vindo</div>
                        <div class="mb-2"><img src="{{config('app.cdn')}}/fr/imagens/logo2.png"></div>

                        <form name="form_login" id="form_login" action="{{ route('login') }}" method="POST" class="default needs-validation" novalidate autocomplete="off">
                            @csrf
                            <!-- Botao Drive --->
                            <div class="row">
                                    	<div class="col-12 mt-4 mb-2">
											<div onclick="window.location.href = '{{url('/auth/google')}}'" class="login-google-button" role="button" tabindex="0" style="user-select: none;">
                                                <div class="login-google-icon"><img style="padding-bottom: 11px;" src="{{config('app.cdn')}}/fr/imagens/ico_google_login.png"></div><div class="login-google-text">Login Google</div>
                                                </div>
										</div>
										<div class="col-12 mb-3 fs-14">
                                    		@opeteducation - @souopet
                                		</div>
							</div>

                            <div class="row fs-14 mt-2 mb-1" style="align-items: center;">
                                        <div class="line-separator"></div>
                                        <div class="or-label">OU</div>
                                        <div class="line-separator"></div>
                            </div>

                            <div class="form-row">
                                <div class="form-group mb-2 mt-3">
                                    <div class="col">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text ico i2"></span>
                                            </div>
                                            <input type="email" name="email" value="{{ old('email') }}" class="form-control ico" placeholder="E-mail" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group mb-3 mt-1">
                                    <div class="col">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text ico i1"></span>
                                            </div>
                                            <input type="password" name="password" class="form-control ico" placeholder="Senha" value="" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button
                              type="submit"
                              class="btn btn-primary btn-opet btn-orange mb-3"
                            >
                              Entrar
                            </button>
                            @if ($errors->any() && old('esqueSenha')!=1)
                                @if((!$errors->has('email') || $errors->first('email')=='auth.failed') && !$errors->has('bloqueio') )
                                    <br><b>Usuario ou senha incorreto!</b>
                                @elseif($errors->has('email'))
                                    <br><b>{!! $errors->first('email') !!}</b>
                                @elseif($errors->has('bloqueio'))
                                    <br><b>{!! $errors->first('bloqueio') !!}</b>
                                @endif
                                @if($errors->has('g-recaptcha-response'))
                                    <br><b>{{$errors->first('g-recaptcha-response')}}</b>
                                @endif
                               <br><br>

                            @endif

                            <!--
                            <div class="row" style="align-items: center;">
                                <div class="line-separator"></div>
                                <div class="or-label">ou</div>
                                <div class="line-separator"></div>
                            </div>
                            -->

                            <!-- Botao Drive --->
                            <!-- <div class="row">
                                <div class="col-12 mt-2 ">
                                    <div onclick="window.location.href = '{{url('/auth/google')}}'" class="save-to-drive-button jfk-button mx-3" role="button" tabindex="0" style="user-select: none;">
                                        <div class="save-to-drive-image ">
                                            <img style="padding-bottom: 11px;" src="{{config('app.cdn')}}/fr/imagens/login-google.png">
                                        </div>
                                        <div class="save-to-drive-text"> Login com o Google</div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3 mt-2" style="font-size: 10px">
                                    @opeteducation.com.br - @souopet.com.br
                                </div>
                            </div>
                            -->

                            <!--<div class="row">
                                <div class="col-12 mb-1">
                                    <div class="form-group">
                                        @php
                                        /*
                                        <div class="form-check esqueciSenha">
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#modalEsqueciMinhaSenha" data-dismiss="modal">Esqueci a senha</a>
                                        </div>
                                        */
                                        @endphp
                                    </div>
                                </div>
                            </div>
                            -->
                    </div>
    </section>

    <section class="rodape">
        <div class="row d-flex justify-content-center">

        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="rodape">
        <div class="row d-flex justify-content-center">
            <div class="row mr-45">
                <div class="row mt-2">
                    <div class="col-12 m-0">
                        <a href="{{url('colecao_tutorial')}}" class="btn btn-primary btn-opet btn-orange pr-3 pl-4 mr-2" style="width: 105px;">Tutoriais</a>
                        <a href="{{url('contato')}}"  class="btn btn-primary btn-opet btn-orange pr-4 pl-4" style="width: 145px;">Fale Conosco </a>
                    </div>
                    <div class="col mt-2">
                        <a href="{{ url('/termos-de-uso') }}" class="btn btn-primary btn-opet btn-orange pr-3 pl-4 mr-2" style="width: 136px;">Termo de Uso</a>
                        <a href="{{ url('/politica-de-privacidade') }}" class="btn btn-primary btn-opet btn-orange pr-4 pl-4" style="width: 117px;">Privacidade</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- MODAL ESQUECI MINHA SENHA-->
<div class="modal fade" id="modalEsqueciMinhaSenha" tabindex="-1" role="dialog" aria-labelledby="modalEsqueciMinhaSenha" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <p class="text-center"><strong>Preencha os dados abaixo para recuperar sua senha:</strong></p>
                <form action="{{url('/esqueci-senha')}}" name="form_esqueci_senha" id="form_esqueci_senha" method="post" class="default needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="esqueSenha" value="1">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text ico"></span>
                            </div>
                            <input type="email" name="email" class="form-control ico" placeholder="Informe seu e-mail" inputmode="numeric" required>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="login mt-4">
                            Será enviado para seu e-mail os dados de acesso ao sistema.<br>
                            Caso precise de atendimento, entre em contato conosco.
                        </p>
                        <button type="button" class="g-recaptcha btn btn-primary btn-opet btn-orange mb-3" data-sitekey="{{config('app.GOOGLE_RECAPTCHA_KEY')}}" data-callback='onSubmitEsqueciSenha'>Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- FIM MODAL ESQUECI MINHA SENHA -->
</body>
<!--Vlibras-->
<script src="{{config('app.cdn')}}/fr/includes/js/vlibras/vlibras-plugin.min.js"></script>
<script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
</script>

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


<script type="text/javascript">

    @if ($errors->any() && old('esqueSenha')==1)

    swal("Erro ao tentar recuperar senha.", '{!! implode('', $errors->all(':message ')) !!}', "error");
    @endif

    var error = '{{session('erro')}}';
    var certo = '{{session('certo')}}'
    if(error != '')
        swal("Erro ao tentar recuperar senha.", error, "error");
    if(certo != '')
        swal("Senha recuperada.", certo, "success");

    function onSubmitEsqueciSenha(token) {
        document.getElementById("form_esqueci_senha").submit();
    }

    function onSubmit(token) {
        document.getElementById("form_login").submit();
    }


</script>
</html>
