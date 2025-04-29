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
      background: #000000 !important;
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

        $escola = App\Escola::where('id',$user->escola_id )->first();
        $instituicao_id = App\Instituicao::where('id',$escola->instituicao_id)->first();
        $instituicao = App\Instituicao::where('id',$escola->instituicao_id)->first();

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
      <div class="toggle-btn" onclick="toggleSidebar()">
        <a>
          <span></span>
          <span></span>
          <span></span>
        </a>
      </div>
      {{--@endif  --}}

           <div class="d-inline-flex align-items-center w-75 logotipos">

                <div class="flex-shrink-1" >
                  <a href="{{ url('catalogo') }}"><img src="{{config('app.cdn')}}/images/logo.png" alt="logo" style="max-width:155px;" class="img-fluid float-left" /></a>
                </div>

                <!--<div>
                @if($instituicao->instituicao_tipo_id == 1)
                  <img src="{{config('app.cdn')}}/images/instituicao-publica.png"  class="img-fluid pl-3" />
                @else
                  <img src="{{config('app.cdn')}}/images/instituicao-privada.png"  class="img-fluid pl-3" />
                @endif
                </div>
                -->
                <div class="flex-grow-1 w-100 bar-nav">
                  <!--
                <form id="formPesquisaTopo" action="{{config('app.url')}}/gestao/biblioteca" method="get">
                    <div class="opet-custom-search-bar">
                        <div class="input-group flex-nowrap">
                          <div class="input-group-prepend">
                            <button type="submit" class="input-group-text opet-search-back-color-fa" id="addon-wrapping"><i class="fa fa-search opet-search-font-color-fa"></i></button>
                          </div>
                          <input type="text" name="pesquisa" class="opet-search-bar-input" placeholder="Busca" aria-label="Busca" aria-describedby="addon-wrapping">
                        </div>
                    </div>
                  </form>
                -->
                </div>

            </div>



<!--- menu avatar --->
<div class="nav-item dropdown  mr-5">
   <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <p class="d-none d-md-inline-block mr-2" style="color: #FFFFFF;">
                {{ ucwords(Auth::user()->name) }}
       </p>
       @php
       $fotoAvatar = config('app.cdn').'/fr/imagens/avatar-user.png';
        if(Auth::user()->avatar_social!='')
        {
            $fotoAvatar = Auth::user()->avatar_social;
        }
        elseif(Auth::user()->img_perfil!='')
        {
            $fotoAvatar = asset('storage/uploads/usuarios/perfil/'.Auth::user()->img_perfil);
        }

        @endphp
          <div class="avatar-img avatar-sm mr-2" style="background: #DEE2F0 url({{$fotoAvatar}}); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;">
              </div>

          </a>

          <div class="dropdown-menu dropdown-menu-lg-right bg-white" aria-labelledby="navbarDropdown">

            @if(Auth::user()->habilitar_troca_senha)
              <a class="dropdown-item" href="{{ route('configuracao.index') }}">
                  Configurações
              </a>
            @endif

              <a class="dropdown-item" href="{{ url('tutorial') }}">
                  Ajuda
              </a>

              <a class="dropdown-item" href="#" onclick="event.preventDefault(); confirmLogout();">
                  Sair
                  <form id="logout-form" action="{{ route('logout') }}" method="POST"
                        style="display: none;"> @csrf </form>
              </a>

          </div>
      </div>
  </div>

  <!--- menu notificacao --->
  <div class="container-right-menu d-flex align-items-center">
      <div class="dropdown-notifications dropdown dropdown-hover show">
          <a class="nav-link text-primary" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-bell fa-lg fa-fw" style="color: #F8AC15;" data-toggle="tooltip" data-placement="top" title="Notificações"></i>

              <span id="lblQtNotificacoes" class="badge badge-secondary {{ App\Notificacao::where([['user_id', '=', Auth::user()->id], ['lida', '=', 0]])->count() > 0 ? '' : 'd-none' }}" style="position: absolute;top: 0px;right: 8px;margin-left: 25%;background: #EF6969;font-size: 12px;">{{ App\Notificacao::where([['user_id', '=', Auth::user()->id], ['lida', '=', 0]])->count() }}</span>

          </a>
          <div id="divNotificacoes" class="dropdown-menu rounded-10 border-0 box-shadow" aria-labelledby="dropdownMenuLink" style="right: 0px;left: initial;">

              <div style="max-height: 20vh;overflow-y: auto;">
                  @if(App\Notificacao::where('user_id', '=', Auth::user()->id)->count() > 0)
                      <?php
                          foreach(App\Notificacao::where('user_id', '=', Auth::user()->id)->orderBy('created_at', 'desc')->get() as $notificacao)
                          {
                              if($notificacao->link != "")
                              {
                                  echo '<a href="' . $notificacao->link . '" target="_blank" id="divNotificacao' . $notificacao->id . '" class="dropdown-item py-2 box-notificacao" style="color: #60748A;min-width:  340px;border-bottom:  2px solid #E3E5F0;' . ($notificacao->lida == 0 ? 'background-color: #eafeff;' : '') . '">
                                      <button type="button" class="btn bg-transparent m-0 p-0 float-right" onclick="excluirNotificacao(' . $notificacao->id . ');"><i class="fas fa-times text-danger"></i></button>
                                      <div class="px-0 py-0">
                                          <b>' . ucfirst($notificacao->titulo) . '</b>
                                          <br>
                                          <small>' . ucfirst($notificacao->descricao) . '</small>
                                      </div>
                                  </a>';
                              }
                              else
                              {
                                  echo '<div class="dropdown-item py-2 box-notificacao" id="divNotificacao' . $notificacao->id . '" style="color: #60748A;min-width:  340px;border-bottom:  2px solid #E3E5F0;' . ($notificacao->lida == 0 ? 'background-color: #eafeff;' : '') . '">
                                      <button type="button" class="btn bg-transparent m-0 p-0 float-right"  onclick="excluirNotificacao(' . $notificacao->id . ');"><i class="fas fa-times text-danger"></i></button>
                                      <div class="px-0 py-0">
                                          <b>' . ucfirst($notificacao->titulo) . '</b>
                                          <br>
                                          <small>' . ucfirst($notificacao->descricao) . '</small>
                                      </div>
                                  </div>';
                              }
                              $notificacao->update(['lida' => 1]);
                          }
                      ?>
                  @else
                      <div class="dropdown-item px-4 py-3" style="color: #60748A;min-width:  340px;border-bottom:  2px solid #E3E5F0;">
                          Você não possui notificações.
                      </div>
                  @endif
              </div>

              @if(App\Notificacao::where('user_id', '=', Auth::user()->id)->count() > 0)
                  <button type="button" onclick="excluirNotificacao('todas');" class="btn btn-block btn-primary text-uppercase ont-weight-bold btn-apagar-notificacoes">
                    <i class="fas fa-trash-alt"></i>&nbsp; Apagar todas notificações
                  </button>
              @endif
          </div>
      </div>

      @endif

    {{-- @if(Auth::check())
      @if(in_array(Auth::user()->permissao, ["A"]))
        @include('utilities.template.2.play-sidebar')
      @else
        @include('utilities.template.2.manager-sidebar')
      @endif
    @endif --}}

    @if(Auth::check())
      @switch (Auth::user()->permissao)

        @case('A')
          {{-- PLAY --}}
          @include('utilities.template.2.sidebar-play')
        @break;

        @case('P')
            {{-- Master --}}
            @include('utilities.template.2.sidebar-master')
        @break;

        @case('E')
            {{-- School --}}
            @include('utilities.template.2.sidebar-schooll')
        @break;

        @case('G')
          @include('utilities.template.2.sidebar-manager')
        @break;

        @case('Z')
            {{-- Super Admin --}}
            @include('utilities.template.2.sidebar-superadmin')
        @break;

      @endswitch
    @endif


    @if(Auth::check())
      {{-- @if(in_array(Auth::user()->permissao, ["A"]))
        @include('utilities.template.2.play-sidebar')
      @endif
      @if(in_array(Auth::user()->permissao, ["G"]))
        @include('utilities.template.2.manager-sidebar')
      @endif
      @if(in_array(Auth::user()->permissao, ["G"]))
        @include('utilities.template.2.manager-sidebar')
      @endif --}}
    @endif


</nav>


<!-- Topo Antigo ------>
 <!-- busca -->
             <!-- <div class="form-row align-items-center col-8">
              <div class="col-3">

              </div>
              <div class="col-3">

              </div>
              <div class="col-4">

              </div>
            </div> -->




     <!-- <figure class="logo position-relative float-left">
      <img src="{{config('app.cdn')}}/images/logo.png" alt="logo" />
    </figure>

    <figure class="logo">
      <img src="{{config('app.cdn')}}/images/logo.png" alt="logo" />
    </figure>


      <div class="search btn">
        <img src="{{config('app.cdn')}}/images/baseline_search_black_18dp.png" />
      </div>




    @if(Auth::check())
      <div class="avatar">
        <img src="{{config('app.cdn')}}/images/avatar.png" alt="avatar" />
      </div>


      <div class="config hover-orange" id="configMenu" onclick="toggleConfigMenu()">
        <img src="{{config('app.cdn')}}/images/arrow-down.png" />

        <ul>
          <li>
            <a class="dropdown-item" href="{{ route('configuracao.index') }}">Configurações</a>
          </li>
          <li>
            <a class="dropdown-item dropdown-item-logout" href="#" onclick="event.preventDefault(); confirmLogout();">
              Sair
              <form id="logout-form" action="{{ route('logout') }}" method="POST"
              style="display: none;"> @csrf </form>
          </a></li>
        </ul>
      </div>
    @else
      <div class="config hover-orange" id="configMenu" onclick="toggleConfigMenu()">
        <a href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
          aria-expanded="false" class="dropdown-item">
          <img src="{{config('app.cdn')}}/images/arrow-down.png" />
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

              <button class="btn btn-lg btn-block signin-button ml-0 my-4" type="submit">{{ __('Entrar') }}</button>

              <span class="text-center d-block my-3 font-weight-bold"><a
                      href="">Esqueci minha senha</a></span>

              <h6 class="text-center d-block my-3 font-weight-bold" style="color: #989EB4">
                  Não tem uma conta? <a href="{{ route('register_trial') }}">Cadastre-se</a>
              </h6>


          </form>
        </div>
      </div>
    @endif
-->
