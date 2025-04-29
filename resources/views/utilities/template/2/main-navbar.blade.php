
<script type="text/javascript">
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("active");
    }

    function toggleConfigMenu(){
        document.getElementById("configMenu").classList.toggle("active")
    }

    $(document).ready(function() {
        $('#sidebar ul.side-group > li > a.has-submenu').on('click', function(e) {
            e.preventDefault();
            $(this).next().slideToggle();
        });
    });
</script>


<style>

    :root {
        --primary-color: #E75A24!important;
        --hub-primary-color: #E75A24!important;
        --hub-background-color: #000!important;
        --color-toolzz: #E75A24!important;
    }
    .navbar{
        background: #1d2345 !important;
    }
    .opet-custom-search-bar {
        width: 60%;
        margin: auto;
        margin-left: 220px;


    }

    .opet-search-bar-input {

        border: 1px solid #FFAE00;
        padding: 2%;
        width: 100%;
        border-bottom-right-radius: 5px;
        border-top-right-radius: 5px;
        color: #202A36 !important;

    }

    .opet-search-font-color-fa {
        color: #FFFFFF !important;
    }

    .opet-search-back-color-fa {
        background-color: #FFAE00 !important;
        border: 1px solid #FFAE00 !important;
    }
    @media screen and (max-width: 1024px) {
        .logotipos img{
            width: 100%;
        }
        .opet-custom-search-bar{
            display: none;
        }
    }

    @media screen and (max-width: 700px) {
        .logotipos{
            width:60%!important;
        }
    }

    .navbar .nav-link p{
        color: #fff!important;

    }

    .dropdown-menu{
        padding-bottom:0!important;
    }
    .btn-apagar-notificacoes{
        margin: 0!important;
        border-radius: 8px!important;
        border-top-right-radius:0!important;
        border-top-left-radius:0!important;
        margin-top: 15px!important;
    }
    .btn.btn-apagar-notificacoes{
        color: var(--btn-text-color)!important;
    }
</style>

<?php
$user = Auth::user();

if(Auth::check()){
    if($user->privilegio_id == 1 || 2 || strtoupper($user->permissao) == "Z" || "G"){
        //$instituicao_id = App\InstituicaoUser::where('user_id', Auth::user()->id)->first();
        //$instituicao = $instituicao_id->instituicao;

        $escola = App\Models\Escola::where('id',$user->escola_id )->first();
        $instituicao_id = App\Models\Instituicao::where('id',$escola->instituicao_id)->first();
        $instituicao = App\Models\Instituicao::where('id',$escola->instituicao_id)->first();

    }else{

        $instituicao_id = Auth::user()->escola()->instituicao_id;

    }


}

$myPath = [
    'a' => '/catalogo',
    'g' => '/catalogo',
    'z' => '/catalogo',
    'p' => '/catalogo',
    'e' => '/catalogo',
];
?>

<nav class="navbar fixed-top d-flex justify-content-between template-2 pl-3">

    @if(!Auth::check())

        <div class="d-inline-flex align-items-center logotipos pl-3">
            <div class="flex-shrink-1" >
                <a href="{{ url('/catalogo') }}"><img src="{{config('app.cdn')}}/images/logo.png" alt="logo" class="float-left" style="max-width:100px;" /></a>
            </div>
        </div>


        <div class="container-right-menu d-flex align-items-center">

            <div class="nav-item dropdown">

                <a href="{{ route('login') }}" class="nav-link d-block d-lg-none font-weight-bold">
                    Entrar
                </a>

                <a href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false" class="nav-link dropdown-toggle font-weight-bold d-none d-lg-block">
                    Entrar
                </a>


                <div class="dropdown-menu dropdown-signin-menu" aria-labelledby="navbarDropdown">
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}" class="form-signin"
                          style="background-color: #13141D;">
                        @csrf

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                          <span class="input-group-text bg-transparent border-0" id="basic-addon1">
                              <i class="fas fa-at"></i>
                          </span>
                            </div>

                            <input id="email" type="text" name="email" value="{{ old('email') }}"
                                   class="form-control{{ isset($errors) ? ($errors->has('email') ? ' is-invalid' : '') : '' }}" placeholder="E-mail"
                                   required>

                            @if( isset($errors) ? $errors->has('email') : false )
                                <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('email') }}</strong>
                          </span>
                            @endif
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                          <span class="input-group-text bg-transparent border-0" id="basic-addon1">
                              <i class="fas fa-lock"></i>
                          </span>
                            </div>

                            <input id="password" name="password" type="password"
                                   class="form-control{{ isset($errors) ? ($errors->has('password') ? ' is-invalid' : '') : '' }}"
                                   placeholder="Senha" required>

                            @if ( isset($errors) ? $errors->has('password') : false )
                                <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('password') }}</strong>
                          </span>
                            @endif
                        </div>

                        <button class="btn btn-lg btn-block signin-button my-4" type="submit">{{ __('Entrar') }}</button>






                    </form>
                </div>

            </div>
        </div>

    @endif

    @if(Auth::check() && $user->terms == 1)
        {{--@if(in_array(Auth::user()->permissao, ["P"]) and strtolower ( config('app.name') ) == "opet")

          @else  --}}

        {{--@endif  --}}

        <div class="d-inline-flex align-items-center w-75 logotipos">

            <div class="flex-shrink-1 ml-3" >
                <a href="{{ url('catalogo') }}">
                    <img src="{{ config('app.cdn') }}/fr/imagens/2021/logo.png" alt="logo"  class="img-fluid float-left" />
                </a>
            </div>

        </div>
        <!--- menu avatar --->
        <div class=" mr-5">
            <span class="mr-3" style="color: #FFFFFF">{{ ucwords(Auth::user()->name) }}</span>

            @php
                $fotoAvatar = config('app.cdn').'/fr/imagens/avatar-user.png';
                 if(Auth::user()->avatar_social!='')
                 {
                     $fotoAvatar = Auth::user()->avatar_social;
                 }
                 elseif(Auth::user()->img_perfil!='')
                 {
                     $fotoAvatar = config('app.cdn').'/storage/uploads/usuarios/perfil/'.Auth::user()->img_perfil;
                 }

            @endphp
            <div class="avatar-img avatar-sm mr-2" style="background: #DEE2F0 url({{$fotoAvatar}}); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;">
            </div>


        </div>


    @endif

</nav>
