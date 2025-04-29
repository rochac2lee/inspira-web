@extends('layouts.master')

@section('title', 'Criação de conteúdo')

@section('headend')
<!-- Encapsula o nome do usuário logado para uma função JS -->
@php
    $nome = Auth::user()->name;
    $appName = config('app.name');
@endphp

    <!-- Custom styles for this template -->
    <style>
        body{
            padding-top: 42px!important;
        }
        header
        {
            padding: 154px 0 100px;
        }

        @media (min-width: 992px)
        {
            header
            {
                padding: 156px 0 100px;
            }
        }


        .capa-curso
        {
            min-height: 160px;
            border-radius: 10px 10px 0px 0px;
            background-image: url('{{ config('app.cdn') }}/images/default-cover.jpg');
            background-size: cover;
            background-position: 50% 50%;
            background-repeat: no-repeat;
        }

        .input-group input.text-secondary::placeholder
        {
            color: #989EB4;
        }

        .form-group label
        {
            color: #213245;
            font-weight: bold;
            font-size: 18px;
        }


        .form-control
        {
            border: 0px;
            border-radius: 8px;
            box-shadow: 0px 1px 2px rgba(0,0,0,0.16);
        }

        .form-control::placeholder
        {
            color: #B7B7B7;
        }

        /*.custom-select option:first-child
        {
            color: #B7B7B7;
        }*/

        input[type=range]::-webkit-slider-thumb
        {
            -webkit-appearance: none;
            border: 0px;
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: #525870;
            cursor: pointer;
            margin-top: 0px; /* You need to specify a margin in Chrome, but in Firefox and IE it is automatic */
        }

        input[type=range]::-webkit-slider-runnable-track
        {
            width: 100%;
            height: 36px;
            cursor: pointer;
            box-shadow: 0px 1px 2px rgba(0,0,0,0.16);
            background: #5678ef;
            border-radius: 90px;
            border: 8px solid #E3E5F0;
        }

        @media (min-width: 576px)
        {
            .side-menu
            {
                min-height: calc(100vh - 265px);
            }
        }

        .tipo-conteudo
        {
            transition: 0.3s all ease-in-out;
            opacity: 0;
            background:  #E3E5F0;
            position:  absolute;
            top: -4px;
            left: 32px;
            height:  100%;
            border-radius: 100px;
            width: auto;
            z-index:  5;
            padding:  7px;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .tipo-conteudo div:last-child
        {
            margin-right: 0px !important;
        }

        .handle
        {
            cursor: move;
        }

        #divListaConteudos table
        {
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        #divListaConteudos tr
        {
            font-size: 18px;
            background-color: #F5F5F5;
            border-radius: 10px;
            border: 0px;
        }

        #divListaConteudos tr td
        {
            border: 0px;
        }

        .note-popover.popover
        {
            display: none;
        }

        .list-group-item.active, .list-group-item-action.active
        {
            border: 0px;
            background-color: #F6F7F9 !important;
            color: var(--primary-color);
        }

        .modal form .form-control
        {
            border: 1px solid #B7B7B7;
            border-radius: 0px;
        }
        .modal form .form-control:focus, .modal form .form-control:active/*, .summernote-holder:focus, .summernote-holder:active*/
        {
            border: 2px solid var(--primary-color);
            border-radius: 0px;
            box-shadow: 0px 1px 2px rgba(0,0,0,0.16);
        }
        .summernote-holder
        {
            padding: .375rem .75rem;
            border-radius: 0px;
            /*border: 1px solid #B7B7B7;*/
            border: 2px solid #B7B7B7;
            box-shadow: 0px 1px 2px rgba(0,0,0,0.16);
            font-size: initial;
            text-align: initial;
            color: initial;
        }

        /* Custom radio buttons */
        .btn-group-toggle .btn:not(:disabled):not(.disabled)
        {
            color: var(--secundary-color)!important;
            background-color: white;
            border: 0px;
            border-radius: 0px;
            margin: 0px 10px;
            box-shadow: none;
        }
        .btn-group-toggle .btn:not(:disabled):not(.disabled).active
        {
            color: var(--primary-color)!important;
            border-bottom: 4px solid var(--primary-color);
        }

        .btn-group-toggle .btn:not(:disabled):not(.disabled):hover{
            color: #fff!important;
            background-color: var(--secundary-color)!important;
        }

        .btn-ico {
            background:#f1f1f1;
            color:#999FB4;
            border-radius:50%;
            padding:6px;
            width:36px;
            height:36px;

        }
        .btn-ico i{
            color:#999FB4!important;
        }
        .ball-links-menu {
            width:48px !important;
            height:48px !important;
            line-height:48px !important;
            padding:0!important;
        }

</style>

@endsection

@section('content')

<main role="main">
    <div class="container-fluid mainCard">

            <div class="row align-items-center shadow-sm py-4 position-relative" style="background-color: #FFFFFF; z-index: 16!important;">

                <div class="col col-12 col-sm-8 mb-md-0 mb-3">
                    <a href="{{ url('gestao/cursos') }}" class="text-primary">
                        <i class="fas fa-chevron-left mr-2"></i>
                        <span class="font-weight-normal">Meus {{$langCursoP}}</span>
                    </a>
                     /
                    <span class="font-weight-normal">Editar {{$langCurso}}</span>
                </div>

                <div class="col col-12 col-sm-4 text-sm-right" >

                    @if($curso->status == 0)
                        <a href="{{ route('curso', ['idCurso' => $curso->id]) }}" class="btn btn-outline-primary px-4 text-uppercase font-weight-bold text-break mr-3 mb-2 mb-sm-0 col-12 col-sm-auto">
                            Visualizar
                        </a>

                    @if(count($curso->aulas) > 0)
                            <button type="button" onclick="publicarCurso();" class="btn btn-primary px-4 text-uppercase font-weight-bold text-break col-12 col-sm-auto">
                                Publicar
                            </button>
                    @else
                        <div class="btn btn-primary px-4 text-uppercase font-weight-bold text-truncate col-12 col-sm-auto" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Crie {{$langAulaP}} para publicar o {{$langCurso}}">
                            Publicar
                        </div>
                    @endif

                    @else
                        <a href="{{ route('curso', ['idCurso' => $curso->id]) }}" class="btn btn-outline-primary px-4 text-uppercase font-weight-bold text-break mr-3 mb-2 mb-sm-0 col-12 col-sm-auto">
                            Visão do aluno
                            <i class="fas fa-search"></i>
                        </a>

                        <button type="button" onclick="publicarCurso();" class="btn btn-primary px-4 text-uppercase font-weight-bold text-break col-12 col-sm-auto">
                            Despublicar

                        </button>
                    @endif

                    <form id="formPublicarCurso" action="{{ route('gestao.curso-publicar', ['idCurso' => $curso->id]) }}" method="post">@csrf</form>

                </div>
            </div>

        <div class="row">

        </div> <!-- menu: edição/preview/publicar -->

        <div class="col-12 pt-0 px-0 mx-auto">
            <div class="row" style="height: calc(100% - 103px);">

                <div class="side-menu position-relative col-12 col-md-3 p-3 text-center rounded shadow-sm">
                    <h4 class="d-flex align-items-center font-weight-bold pr-1">
                        <span style="display:block;" class="text-left text-break">{{ ucfirst($curso->titulo) }}</span>
                        <button type="button" onclick="showEditarCurso();" class="btn btn-no-hover" style="position:absolute;right:0;top:18px;" data-toggle="tooltip" data-trigger="right" data-placement="top" title="Editar {{$langCurso}}">
                            <i class="fas fa-cog fa-lg"></i>
                        </button>
                    </h4>
                    <hr style="display:block;width:100%;float:left;">
                    <h5 class="mb-4">{{ucfirst($langAulaP)}}</h5>

                    <button type="button" onclick="showCriarAula();" class="btn btn-primary btn-block box-shadow text-white text-uppercase font-weight-bold py-2 text-wrap mt-3">
                        <i class="fas fa-plus mr-1"></i>
                        Nov{{$langAulaG}}
                    </button>

                    <button type="button" onclick="importarAula();" class="btn btn-primary btn-block box-shadow text-white text-uppercase font-weight-bold py-2 text-wrap mt-3 mb-4">
                        <i class="fas fa-file-upload mr-1"></i>
                        Importar {{$langAula}}
                    </button>

                    <div id="divAulas" class="list-group list-group-flush text-left sortable-aulas" style="max-height: 45vh; overflow-y: auto;">

                        @if(count($curso->aulas) == 0)
                            <div class="list-group-item list-group-item-action bg-transparent text-center">
                                {{--  <i class="fas fa-bars fa-fw mr-2 handle"></i>  --}}
                                Você ainda não criou nenhum {{$langAula}}
                            </div>
                        @endif

                        @foreach ($curso->aulas as $aula)
                            <a id="btnAula{{ $aula->id }}" href="javascript:void(0);" onclick="showAula({{ $aula->id }}); return false;" class="list-group-item list-group-item-action bg-transparent text-truncate">
                                <i class="fas fa-bars fa-fw mr-2 handle" data-toggle="tooltip" data-placement="left" title="{{ ucfirst($aula->titulo) }}"></i>
                                    {{ ucfirst($aula->titulo) }}
                            </a>
                        @endforeach
                    </div>

                    <hr>

                    <div class="text-left">

                        @if($curso->user_id != Auth::user()->id)
                            <p>
                                <b>Criador do {{$langCurso}}:</b>
                                {{ ucwords(\App\Models\User::find($curso->user_id)->name) }}
                            </p>
                        @endif

                        <p>
                            <b>Data de criação:</b>
                            {{ $curso->created_at->format('d/m/Y \à\s H:i') }}
                        </p>
                        <p>
                            <b>Data de publicação: </b>
                            {{ $curso->data_publicacao != null ? (new \Carbon\Carbon($curso->data_publicacao))->format('d/m/Y \à\s H:i') : '-' }}

                        </p>
                        <!--
                        <p>
                            <b>Período de validade: </b>
                            {{ $curso->periodo > 0 ? ($curso->periodo == 1 ? '1 dia' : $curso->periodo . ' dias') : 'Ilimitado' }}
                        </p>

                        <p>
                            <b>Período restante: </b>
                            {{ $curso->periodo > 0 ? ($curso->periodo_restante == 1 ? '1 dia' : $curso->periodo_restante . ' dias') : 'Ilimitado' }}
                        </p>
                        <p>
                            <b>Data de expiração: </b>
                            {{ $curso->data_expiracao != null ? $curso->data_expiracao->format('d/m/Y') : '-' }}
                        </p>
                        -->
                        <p>
                            <b>Status: </b>
                            {{ $curso->status == 1 ? "Publicado" : 'Não publicado' }}
                        </p>
                        <!--
                        <p>
                            <b>Visibilidade: </b>
                            {{ $curso->visibilidade == 1 ? "Visível para todos" : 'Oculto' }}
                        </p>
                        <p>
                            <b>Vagas totais: </b>
                            {{ $curso->vagas > 0 ? ($curso->vagas == 1 ? '1 vaga' : $curso->vagas . ' vagas') : 'Ilimitadas' }}
                        </p>
                         -->
                        <p>
                            <b>Matriculados: </b>
                            {{ $curso->matriculados == 1 ? '1 aluno' : $curso->matriculados . ' alunos' }}
                        </p>
                        <!--
                        <p>
                            <b>Vagas restantes: </b>
                            {{ $curso->vagas > 0 ? ($curso->vagasRestantes > 0 ? $curso->vagasRestantes . ' vagas' : '0 vagas restantes') : 'Ilimitadas' }}
                        </p>
                        <p>
                            <b>Preço: </b>
                            {{ $curso->preco > 0 ? ('R$ ' . number_format($curso->preco, 2, ',', '.')) : 'Gratuito' }}
                        </p>
                    -->
                    </div>

                </div>

                <!-- Modal Editar Aula -->
                <div class="modal fade" id="divModalEditarAula" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>

                                <h4 class="text-center">Editar {{$langAula}}</h4>

                                <form id="formEditarAula" method="POST" action="{{ route('gestao.curso.aula-salvar', ['idCurso' => $curso->id]) }}" class="px-3 shadow-none border-0">

                                    @csrf

                                    <input name="idAula" required hidden>

                                    <div id="divLoading" class="text-center">
                                        <i class="fas fa-spinner fa-pulse fa-3x text-primary"></i>
                                    </div>

                                    <div id="divEnviando" class="text-center d-none">
                                        <i class="fas fa-spinner fa-pulse fa-3x text-primary mb-3"></i>
                                        <h4>Enviando</h4>
                                    </div>

                                    <div id="divEditar" class="form-page d-none">
                                        <div id="page1" class="form-page">

                                            <div class="form-group mb-3">
                                                <label class="" for="txtEditarTitulo">Título</label>
                                                <input type="text" class="form-control " name="titulo" id="txtEditarTitulo" maxlength="150" placeholder="Clique para digitar."  required>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label class="" for="txtEditarDescricao">Descrição</label>
                                                <textarea class="form-control" name="descricao" id="txtEditarDescricao" rows="3" maxlength="1000" placeholder="Clique para digitar." required style="resize: none"></textarea>
                                            </div>
                                                <input type="hidden"  name="duracao" id="txtEditarDuracao" >

                                            <!--<div class="form-group mb-3">
                                                <label class="" for="txtEditarDuracao">Duração em minutos <small>(opcional)</small></label>
                                                <input type="time" class="form-control" min="00:00" max="01:30" name="duracao" id="txtEditarDuracao" min="0" maxlength="3" placeholder="Clique para digitar.">
                                            </div>
-->
                                            <div class="form-group mb-3">
                                                <label for="cmbRequisitoAula">Requisito para acesso <small>(opcional)</small></label>
                                                <select class="custom-select form-control" name="requisito" id="cmbRequisitoAula" onchange="mudouRequisitoAula(true);">
                                                    <option value="nenhum" selected>Nenhum</option>
                                                    <option value="anterior">{{ucfirst($langAula)}} anterior</option>
                                                    <option value="aula">{{ucfirst($langAula)}} específico</option>
                                                </select>
                                            </div>

                                            <div id="divAulaEspecifica" class="form-group mb-3 d-none">
                                                <label for="cmbAulaEspecifica">{{ucfirst($langAula)}} específico</label>
                                                <select class="custom-select form-control" name="aula_especifica" id="cmbAulaEspecifica">
                                                    <option value="0" disabled selected>Selecione uma {{$langAula}}</option>
                                                    @foreach ($curso->aulas as $aula)
                                                        <option value="{{ $aula->id }}">{{ ucfirst($aula->titulo) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="row">
                                                <button type="button" data-dismiss="modal" class="btn btn-lg btn-block btn-cancelar mt-4 mb-0 col-4 ml-auto mr-4 font-weight-bold">Cancelar</button>
                                                <button type="submit" onclick="showEnviandoEditarAula();" class="btn btn-lg btn-block bg-primary mt-4 mb-0 col-4 ml-4 mr-auto text-white font-weight-bold">Salvar</button>
                                            </div>

                                        </div>
                                    </div>

                                </form>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- Fim modal editar aula -->

                <!-- Modal Novo Conteudo -->
                @include('gestao.conteudo.modal-novo-conteudo')
                <!-- Fim modal novo conteudo -->

                <!-- Modal Editar Conteudo -->
                @include('gestao.conteudo.modal-editar-conteudo')
                <!-- Fim modal editar conteudo -->

                <!-- Modal Seleção de Conteúdos da Biblioteca -->
                @include('gestao.modal-selecao-conteudos', ['conteudos' => $conteudos])
                <!-- Fim modal Seleção Conteúdos da Biblioteca -->
                  <!-- Modal cards -->
                  @include('gestao.conteudo.modal-novo-cards-curso')
                <!-- Fim modal cards -->


                <div class="col-12 col-md-9 mx-auto col-lg-9">
                    <div class="container-fluid h-100">
                    <!-- Modal editar curso -->
                    @include('gestao.curso-edit-conteudo')
                    <!-- Fim modal curso -->

                        <div class="row h-100 d-none animated fadeIn" id="divNovaAula" style="background-color: #FBFBFB;">
                            <div class="col-12 px-1 py-3">

                                <div id="divEnviando" class="w-100 text-center d-none">
                                    <div style="position: absolute;left 50%;top: 50%;left:  50%;transform: translate(-50%, -50%);">
                                        <i class="fas fa-spinner fa-pulse fa-3x text-primary mb-3"></i>
                                        <h4>Enviando</h4>
                                    </div>
                                </div>

                                <div id="divEditar" class="w-100 d-none">
                                    <h4 class="text-center">Nov{{$langAulaG}}</h4>

                                    <form id="formNovaAula" action="{{ url('gestao/curso/'.$curso->id.'/aula/nova') }}" method="post">

                                        @csrf

                                        <div class="form-group mb-3">
                                            <label class="" for="txtTitulo">Título</label>
                                            <input type="text" class="form-control" name="titulo" id="txtTitulo" maxlength="150"  placeholder="Clique para digitar." maxlength="150" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="" for="txtDescricao">Descrição</label>
                                            <textarea class="form-control" name="descricao" id="txtDescricao" rows="3" maxlength="1000" placeholder="Clique para digitar." maxlength="1000" required style="resize: none"></textarea>
                                        </div>

                                        <input type="hidden"name="duracao" >

<!--
                                        <div class="form-group mb-3">
                                            <label class="" for="txtDuracao">Duração em minutos<small>(opcional)</small></label>
                                            <input type="time" class="form-control" min="00:00" max="01:30" name="duracao" id="txtDuracao" maxlength="5" placeholder="Clique para digitar.">
                                        </div>
-->
                                        <div class="form-group mb-3">
                                            <label for="cmbRequisitoAula">Requisito para acesso <small>(opcional)</small></label>
                                            <select class="custom-select form-control" name="requisito" id="cmbRequisitoAula" onchange="mudouRequisitoAula(false);">
                                                <option value="nenhum" selected>Nenhum</option>
                                                <option value="anterior">{{ucfirst($langAula)}} anterior</option>
                                                <option value="aula">{{ucfirst($langAula)}} específico</option>
                                            </select>
                                        </div>

                                        <div id="divAulaEspecifica" class="form-group mb-3 d-none">
                                            <label for="cmbAulaEspecifica">{{ucfirst($langAula)}} específico</label>
                                            <select class="custom-select form-control" name="aula_especifica" id="cmbAulaEspecifica">
                                                <option value="0" disabled selected>Selecione um {{$langAula}}</option>
                                                @foreach ($curso->aulas as $aula)
                                                    <option value="{{ $aula->id }}">{{ ucfirst($aula->titulo) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <button type="submit" onclick="showEnviandoAula();" class="btn btn-primary btn-lg">
                                            Salvar
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>

                        <div class="row animated fadeIn" id="divMainLoading" style="display:none;">
                            <div class="text-center" style="position: absolute;left 50%;top: 50%;left:  50%;transform: translate(-50%, -50%);">
                                <i class="fas fa-spinner fa-pulse fa-3x text-primary mb-3"></i>
                                <h4>Carregando</h4>
                            </div>
                        </div>

                        <div class="row pt-3 d-none" id="divConteudos"><!-- inicio de conteudos -->

                            <div class="col-auto p-0 ">
                                <ul id="menu-conteudo-cursos">
                                    <a class="menu-button icon-minus" href="#menu-conteudo-cursos" title="Show navigation"><i class="fas fa-plus"></i></a>
                                    <a class="menu-button icon-plus" href="#0" title="Hide navigation"><i class="fas fa-minus"></i></a>
                                    <li class="menu-item divConteudosTipo5 fas fa-book-open text-white rounded-circle ball-links-menu" data-toggle="modal" data-target="#modalConteudo"></li>
                                    <li class="menu-item divConteudosTipo4 fas fa-arrow-circle-up fa-lg fa-fw bg-blue text-white rounded-circle ball-links-menu" data-toggle="modal" data-target="#modalConteudo"></li>
                                    <li class="menu-item divConteudosTipo3 fas fa-gamepad fa-lg fa-fw bg-blue text-white rounded-circle ball-links-menu" data-toggle="modal" data-target="#modalConteudo"></li>
                                    <li class="menu-item divConteudosTipo2 fas fa-file-archive fa-lg fa-fw bg-blue text-white rounded-circle ball-links-menu" data-toggle="modal" data-target="#modalConteudo"></li>
                                    <li class="menu-item divConteudosTipo1 fas fa-book fa-lg fa-fw bg-blue text-white rounded-circle ball-links-menu" data-toggle="modal" data-target="#modalConteudo"></li>
                                </ul>
                                <!-- MODAL MENUS #menu-conteudo-cursos -->
                                <div class="modal modal-menu-links-cursos fade" id="modalConteudo" tabindex="-1" role="dialog" aria-labelledby="modalConteudoTitle" aria-hidden="true" data-backdrop="static">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle" data-dismiss="modal" aria-label="Close">
                                                <i class="fas fa-arrow-left"></i>
                                                Selecione o tipo de conteúdo
                                            </h5>
                                        </div>
                                        <div class="modal-body" id="modal-body">
                                            <!-- Monta o conteudo dos links dinamico -->
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END-MODAL MENUS #menu-conteudo-cursos -->



                                <div class="bg-primary px-0 menu-vertical-cursos">

                                    <div onclick="showConteudosTipo(1);" class="btn bg-transparent text-white border-0 btn-block text-uppercase text-center">
                                        <div class="position-relative">
                                            <i class="fas fa-book fa-lg fa-fw bg-blue text-white rounded-circle ball-links-menu" style=""></i>

                                            <div id="divConteudosTipo1" class="tipo-conteudo text-left d-none">

                                                <div class="iconeMenu align-top mr-3 text-uppercase font-weight-bold">
                                                    <i class="fas fa-book fa-fw bg-blue text-white rounded-circle ball-links-menu-modal align-icons-free-courses"></i>
                                                </div>
                                                <div onclick="showNovoConteudo(1)" class="btn-ball-links-menu btn btn-dark border-0 bg-transparent d-inline-block align-top p-0 mx-2 text-uppercase font-weight-bold">
                                                    <i class="fas fa-font fa-fw bg-blue rounded-circle ball-links-menu-modal  align-icons-free-courses"></i>
                                                    <p class="small font-weight-bold mt-3">Texto</p>
                                                </div>
                                                <div onclick="showNovoConteudo(2)" class="btn-ball-links-menu btn btn-dark border-0 bg-transparent d-inline-block align-top p-0 mx-2 text-uppercase font-weight-bold">
                                                    <i class="fas fa-podcast fa-fw bg-blue rounded-circle ball-links-menu-modal  align-icons-free-courses"></i>
                                                    <p class="small font-weight-bold mt-3">Áudio</p>
                                                </div>
                                                <div onclick="showNovoConteudo(3)" class="btn-ball-links-menu btn btn-dark border-0 bg-transparent d-inline-block align-top p-0 mx-2 text-uppercase font-weight-bold">
                                                    <i class="fas fa-video fa-fw bg-blue rounded-circle ball-links-menu-modal  align-icons-free-courses"></i>
                                                    <p class="small font-weight-bold mt-3">Vídeo</p>
                                                </div>
                                                <div onclick="showNovoConteudo(4)" class="btn-ball-links-menu btn btn-dark border-0 bg-transparent d-inline-block align-top p-0 mx-2 text-uppercase font-weight-bold">
                                                    <i class="fas fa-file-powerpoint fa-fw bg-blue rounded-circle ball-links-menu-modal  align-icons-free-courses"></i>
                                                    <p class="small font-weight-bold mt-3">Slide</p>
                                                </div>
                                                <div onclick="showNovoConteudo(15)" class="btn-ball-links-menu btn btn-dark border-0 bg-transparent d-inline-block align-top p-0 mx-2 text-uppercase font-weight-bold">
                                                    <i class="fas fa-file-pdf fa-fw bg-blue rounded-circle ball-links-menu-modal  align-icons-free-courses"></i>
                                                    <p class="small font-weight-bold mt-3">PDF</p>
                                                </div>

                                                <div onclick="showNovoConteudo(6)" class="btn-ball-links-menu btn btn-dark border-0 bg-transparent d-inline-block align-top p-0 mx-2 text-uppercase font-weight-bold">
                                                    <i class="fas fa-upload fa-fw bg-blue rounded-circle ball-links-menu-modal  align-icons-free-courses"></i>
                                                    <p class="small font-weight-bold mt-3">Arquivos</p>
                                                </div>


<!--
                                                @if((Auth::user()->privilegio_id == 1 && config('app.name') == "opet") ||
                                                    config('app.name') == "jpiaget")
                                                    <div onclick="showNovoConteudo(21)" class="btn-ball-links-menu btn btn-dark border-0 bg-transparent align-top p-0 mx-2 text-uppercase font-weight-bold d-inline-block">
                                                        <i class="fas fa-book-open fa-fw bg-blue rounded-circle ball-links-menu-modal align-icons-free-courses"></i>
                                                        <p class="small font-weight-bold mt-3">
                                                            @if(config('app.name') != "jpiaget")
                                                                Livro
                                                            @else
                                                                Revista
                                                            @endif
                                                                digital
                                                        </p>
                                                    </div>
                                                @endif

                                                <div onclick="showNovoConteudo(5)" class="btn-ball-links-menu btn btn-dark border-0 bg-transparent d-inline-block align-top p-0 mx-2 text-uppercase font-weight-bold">
                                                    <i class="fas fa-broadcast-tower fa-fw bg-blue rounded-circle ball-links-menu-modal align-icons-free-courses"></i>
                                                    <p class="small font-weight-bold mt-3">Transmissão</p>
                                                </div>
-->
                                            </div>
                                        </div>
                                        <span class="small font-weight-bold">Conteúdo</span>
                                    </div>

                                    <div onclick="showConteudosTipo(3);" class="btn bg-transparent text-white border-0 btn-block text-uppercase text-center">
                                        <div class="position-relative">
                                            <i class="fas fa-gamepad fa-lg fa-fw bg-blue text-white rounded-circle ball-links-menu" style=""></i>
                                            <div id="divConteudosTipo3" class="tipo-conteudo text-left d-none">
                                                <div class="d-inline-block align-top mr-3 text-uppercase font-weight-bold">
                                                    <i class="iconeMenu fas fa-gamepad fa-fw bg-blue rounded-circle ball-links-menu-modal align-icons-free-courses"></i>
                                                </div>
                                                <div onclick="showNovoConteudo(7)" class="btn-ball-links-menu btn btn-dark border-0 bg-transparent d-inline-block align-top p-0 mx-2 text-uppercase font-weight-bold">
                                                    <i class="fas fa-comment-alt fa-fw bg-blue rounded-circle ball-links-menu-modal align-icons-free-courses"></i>
                                                    <p class="small font-weight-bold mt-3">Dissertativa</p>
                                                </div>
                                                <div onclick="showNovoConteudo(8)" class="btn-ball-links-menu btn btn-dark border-0 bg-transparent d-inline-block align-top p-0 mx-2 text-uppercase font-weight-bold">
                                                    <i class="fas fa-list-ul fa-fw bg-blue rounded-circle ball-links-menu-modal align-icons-free-courses"></i>
                                                    <p class="small font-weight-bold mt-3">Quiz</p>
                                                </div>

                                                @if(config('app.name') == "jpiaget")
                                                <div onclick="showNovoConteudo(11)" class="btn-ball-links-menu btn btn-dark border-0 bg-transparent align-top p-0 mx-2 text-uppercase font-weight-bold d-inline-block">
                                                    <i class="fas fa-file-alt fa-fw bg-blue rounded-circle ball-links-menu-modal" style=""></i>
                                                    <p class="small font-weight-bold mt-3">{{ucfirst($langDigital)}} digital</p>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="small font-weight-bold">Atividades</span>
                                    </div>

                                    <div onclick="showConteudosTipo(4);" class="btn bg-transparent text-white border-0 btn-block text-uppercase text-center">
                                        <div class="position-relative">
                                            <i class="fas fa-arrow-circle-up fa-lg fa-fw bg-blue text-white rounded-circle ball-links-menu" style=""></i>
                                            <div id="divConteudosTipo4" class="tipo-conteudo text-left d-none">
                                                <div onclick="showNovoConteudo(10)" class="btn p-0 d-inline-block align-top mr-3 text-uppercase font-weight-bold" style="width: max-content;">
                                                    <i class="iconeMenu fas fa-arrow-circle-up fa-fw bg-blue text-white rounded-circle ball-links-menu-modal align-icons-free-courses"></i>
                                                    <small class="font-weight-bold text-darker mx-2">Receber arquivo do aluno  (Até 20Mb PDF, DOC, PNG, JPG)</small>
                                                    <i class="fas fa-upload text-darker mx-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="small font-weight-bold">Entregável</span>
                                    </div>

                                    <div onclick="showSelecaoConteudosBiblioteca()" class="btn bg-transparent text-white border-0 btn-block text-uppercase text-center">
                                        <div class="position-relative">
                                            <i class="fas fa-book-open text-white rounded-circle ball-links-menu"></i>
                                            <div id="" class="tipo-conteudo text-left d-none">
                                                <div class="btn p-0 d-inline-block align-top mr-3 text-uppercase font-weight-bold" style="width: max-content;">
                                                    <i class="iconeMenu fas fa-book-open bg-blue text-white rounded-circle ball-links-menu-modal" style=""></i>
                                                    <small class="font-weight-bold text-darker mx-2">Importar arquivo da biblioteca</small>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="small font-weight-bold">Biblioteca</span>
                                    </div>

                                </div>

                            </div>

                            <div class="col-12 col-sm-10 p-3" style="">

                                <h3>
                                    <div class="text-break mb-2 text-break" id="lblTituloAulaAtual">{{ucfirst($langAula)}} 1</div>
                                </h3>

                                <div class="btns_gestao_da_aula">
                                    @if(count($curso->aulas) > 0 && $curso->user_id == auth()->user()->id)
                                        <button type="button" onclick="showEditarAula()" class="btn btn-nova-aula " data-toggle="tooltip" data-placement="top" title="Editar {{$langAula}}" >
                                            <i class="fas fa-pencil-alt fa-fw"></i>
                                        </button>

                                        <button type="button" onclick="importarAulaConteudo()" class="btn btn-nova-aula" data-toggle="tooltip" data-placement="top" title="Importar conteúdo para {{$langAula}}" >
                                            <i class="fas fa-upload"></i>
                                        </button>

                                       <!-- <button type="button" onclick="duplicarAula()" class="btn btn-nova-aula" data-toggle="tooltip" data-placement="top" title="Duplicar {{$langAula}}" >
                                            <i class="fas fa-copy fa-fw"></i>
                                        </button>
                                    -->
                                    @endif
                                        <a href="{{ route('gestao.curso.aula-exportar', ['idCurso' => $curso->id, 'idAula' => '###']) }}" class="targetExportIdAula btn btn-nova-aula" title="Exportar {{$langAula}}" data-toggle="tooltip" data-placement="top" title="Exportar {{$langAula}}" >
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @if(count($curso->aulas) > 0 && $curso->user_id == auth()->user()->id)
                                        <button type="button" onclick="excluirAula()" class="btn btn-nova-aula" data-toggle="tooltip" data-placement="top" title="Excluir {{$langAula}}" >
                                            <i class="fas fa-trash fa-fw text-danger"></i>
                                        </button>
                                    @endif
                                </div>

                                <form id="formImportarAula" action="{{ route('gestao.curso.aula-importar', ['idCurso' => $curso->id, 'idAula' => '###']) }}" method="post" enctype="multipart/form-data" class="targetImportIdAula">@csrf <input type="file" id="fileImportAula" name="fileImportAula" style="display:none" />   </form>
                                <form id="formImportarAulaConteudo" action="{{ route('gestao.curso.aula-conteudo-importar', ['idCurso' => $curso->id, 'idAula' => '###']) }}" method="post" enctype="multipart/form-data" class="targetImportIdAulaConteudo">@csrf <input type="file" id="fileImportAulaConteudo" name="fileImportAulaConteudo" style="display:none" /> <input type="hidden" value="" name="aula_importacao_id" id="idAulaImportacao"> </form>

                                <form id="formDuplicarAula" action="{{ route('gestao.curso-aula-duplicar', ['idCurso' => $curso->id]) }}" method="post">@csrf <input id="idAula" name="idAula" hidden> </form>
                                <form id="formExcluirAula" action="{{ route('gestao.curso-aula-excluir', ['idCurso' => $curso->id]) }}" method="post">@csrf <input id="idAula" name="idAula" hidden> </form>

                                <div id="divListaConteudos" class="table-responsive">
                                    <table class="table-responsive">
                                        <tbody class="sortable-conteudos">
                                            <tr id="divItemAula1">
                                                <td class="align-middle handle"><i class="fas fa-bars fa-fw mr-2"></i></td>
                                                <td class="font-weight-bold align-middle" style="color: #999FB4;">
                                                    Conteúdo 1
                                                </td>
                                                <td class="font-weight-bold align-middle" style="color: #999FB4;">
                                                    <i class="fas fa-video fa-lg fa-fw mr-1" style="color: var(--primary-color);"></i>
                                                    <span style="color: #60748A;">Vídeo</span>
                                                </td>
                                                <td class="font-weight-bold align-middle">
                                                    <span style="color: #60748A;">Fundamentos-do-conteudo.mp4</span>
                                                </td>
                                                <td class="font-weight-bold align-middle">
                                                    <span style="color: #60748A;">31 de jan de 18 às 18:35</span>
                                                </td>
                                                <td class="font-weight-bold align-middle px-1">
                                                    <button type="button" class="btn btn-light bg-white box-shadow text-secondary">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </td>
                                                <td class="font-weight-bold align-middle px-1">
                                                    <button type="button" onclick="duplicarConteudo();" class="btn btn-light bg-white box-shadow text-secondary">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </td>
                                                <td class="font-weight-bold align-middle px-1">
                                                    <button type="button" onclick="excluirConteudo();" class="btn btn-light bg-white box-shadow text-secondary">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <form id="formDuplicarConteudo" action="{{ route('gestao.curso-conteudo-duplicar', ['idCurso' => $curso->id]) }}" method="post">@csrf <input id="idAula" name="idAula" hidden> <input id="idConteudo" name="idConteudo" hidden> </form>
                                <form id="formExcluirConteudo" action="{{ route('gestao.curso-conteudo-excluir', ['idCurso' => $curso->id]) }}" method="post">@csrf <input id="idAula" name="idAula" hidden> <input id="idConteudo" name="idConteudo" hidden> </form>

                            </div>
                        </div><!-- Fim de conteudos -->

                    </div>
                </div>

            </div>
        </div>

        <!-- campos para pegar os dados usuário -->
        <input type="hidden" id="user_id" value="{{Auth::user()->id}}">
        <input type="hidden" id="privilegio_id" value="{{Auth::user()->privilegio_id}}">

    </div><!-- Fim container fluid -->
</main>
<!-- Encapsula o nome do usuário logado para uma função JS -->
@php
    $nome = Auth::user()->name;
@endphp

@endsection

@section('bodyend')

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    {{--  <!-- HTML5 Sortable -->  --}}
    {{--  <script src="https://cdnjs.cloudflare.com/ajax/libs/html5sortable/0.9.3/html5sortable.min.js" integrity="sha256-wXQBwC9CsAvsJMSjVlVtHGr6ECvH8k9TSx7eM+wg1bA=" crossorigin="anonymous"></script>  --}}

    <!-- Summernote css/js -->
    {{-- <link href="{{ config('app.local') }}/assets/css/summernote-lite-cerulean.min.css" rel="stylesheet"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/lang/summernote-pt-BR.min.js" crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://pastebin.com/raw/QkBYGVub"></script>
    <script type="text/javascript" src="https://pastebin.com/raw/neg3Zijg" ></script>
    <script type="text/javascript" src="https://pastebin.com/raw/10z8dxLQ"></script>



    <script>

        $( document ).ready(function(){
            //Carrega a div para criar uma nova aula se não existir aulas, se existir vai para a primeira aula
            window.onload = function()
            {
                @if(Request::get('aula') != null)
                    @if(Request::get('aula') > 0)
                        showAula({{ Request::get('aula') }});
                    @endif
                @else
                    @if(count($curso->aulas) > 0)
                        showAula('{{ $curso->aulas[0]->id }}');
                    @else
                        showCriarAula();
                    @endif
                @endif
            }
/*

            $('textarea#txtConteudo').addClass('summernote');
            $('textarea#txtConteudoEntregavel').addClass('summernote');
            $('textarea#txtQuizAlternativa1').addClass('summernote');
            $('textarea#txtQuizAlternativa2').addClass('summernote');
            $('textarea#txtQuizAlternativa3').addClass('summernote');
            $('textarea#txtQuizAlternativa1NovoConteudo').addClass('summernote');
            $('textarea#txtQuizAlternativa2NovoConteudo').addClass('summernote');
            $('textarea#txtQuizAlternativa3NovoConteudo').addClass('summernote');
            $('textarea#txtQuizNovoConteudo').addClass('summernote');
            $('textarea#txtPerguntaDissertativa').addClass('summernote');
            $('textarea#txtDissertativaNovoConteudo').addClass('summernote');
            $('textarea#txtDissertativaNovoConteudo').addClass('summernote');
            $('textarea#txtPerguntaQuiz').addClass('summernote');
            */



            $('#txtDatePicker').datepicker({
                weekStart: 0,
                language: "pt-BR",
                daysOfWeekHighlighted: "0,6",
                autoclose: true,
                todayHighlight: true
            });

            $(".divConteudosTipo1").click(function() {
                var divConteudosTipo1 = document.getElementById('divConteudosTipo1').innerHTML;
                var ConteudosTipo1 = document.getElementById('modal-body');
                ConteudosTipo1.innerHTML = divConteudosTipo1;
            });

            $(".divConteudosTipo2").click(function() {
                var divConteudosTipo2 = document.getElementById('divConteudosTipo2').innerHTML;
                var ConteudosTipo2 = document.getElementById('modal-body');
                ConteudosTipo2.innerHTML = divConteudosTipo2;
            });

            $(".divConteudosTipo3").click(function() {
                var divConteudosTipo3 = document.getElementById('divConteudosTipo3').innerHTML;
                var ConteudosTipo3 = document.getElementById('modal-body');
                ConteudosTipo3.innerHTML = divConteudosTipo3;
            });

            $(".divConteudosTipo4").click(function() {
                var divConteudosTipo4 = document.getElementById('divConteudosTipo4').innerHTML;
                var ConteudosTipo4 = document.getElementById('modal-body');
                ConteudosTipo4.innerHTML = divConteudosTipo4;
            });

            $(".divConteudosTipo5").click(function() {
                var divConteudosTipo5 = document.getElementById('divConteudosTipo5').innerHTML;
                var ConteudosTipo5 = document.getElementById('modal-body');
                ConteudosTipo5.innerHTML = divConteudosTipo5;
            });
            $('.modal-menu-links-cursos').on('show.bs.modal', function (e) {
                $('.iconeMenu').css('display','none');
                $('.modal').click(function(){
                    $('#modalConteudo').modal('hide');
                });
            })

            //if(window.screen.width <= 1280)
            {
                $(".sidebar").removeClass("show");
            }

            $( "#info-alterar-titulo" ).click(function() {
                $( "#titulo" ).focus();
            });

            $('.sortable-aulas').sortable({
                handle: '.handle',
                update: function(event, ui){ reordenouAulas(event, ui) },
            });

            $('.sortable-conteudos').sortable({
                handle: '.handle',
                update: function(event, ui){ reordenouConteudos(event, ui) },
            });

            closeConteudos();

            @if(Request::get('aula') != null)
                @if(Request::get('aula') > 0)
                    showAula({{ Request::get('aula') }});
                @endif
            @else
                @if(count($curso->aulas) > 0)
                    showAula('{{ $curso->aulas[0]->id }}');
                @else
                    showCriarAula();
                @endif
            @endif

            $('.summernote').summernote({
                placeholder: "Clique para digitar.",
                lang: 'pt-BR',
                airMode: false,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['fontsize', 'color']],
                    // ['font', ['fontname']],
                    // ['para', ['paragraph']],
                    ['insert', ['hr', 'picture', 'video', 'link', 'image', 'doc']],
                    ['misc', ['undo', 'redo']],
                ],
                popover: {
                    image: [
                        ['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
                        ['float', ['floatLeft', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']]
                    ],
                    link: [
                        ['link', ['linkDialogShow', 'unlink']]
                    ],
                    air: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['fontsize', 'color']],
                        ['font', ['fontname']],
                        ['para', ['paragraph']],
                        ['table', ['table']],
                        ['insert', ['hr', 'picture', 'video', 'link', 'table', 'image', 'doc']],
                        ['misc', ['undo', 'redo']],
                    ],
                },
            });

            $('.summernote-airmode').summernote({
                placeholder: "Clique para digitar.",
                lang: 'pt-BR',
                airMode: true,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['fontsize', 'color']],
                    ['font', ['fontname']],
                    ['para', ['paragraph']],
                    ['insert', ['hr', 'picture', 'video', 'link', 'table', 'image', 'doc']],
                    ['misc', ['undo', 'redo', 'codeview', 'fullscreen', 'help']],
                ],
                popover: {
                    image: [
                        ['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
                        ['float', ['floatLeft', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']]
                    ],
                    link: [
                        ['link', ['linkDialogShow', 'unlink']]
                    ],
                    air: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['fontsize', 'color']],
                        ['font', ['fontname']],
                        ['para', ['paragraph']],
                        ['table', ['table']],
                        ['insert', ['hr', 'picture', 'video', 'link', 'table', 'image', 'doc']],
                        ['misc', ['undo', 'redo']],
                    ],
                },
            });
        });

        //função de perfil do usuário para editar
        var verificaPermissao = function(value_user_id) {

            // variaveis globais com informações do usuário
            let user_id_session = document.getElementById('user_id').value;
            let privilegio_id = document.getElementById('privilegio_id').value;

            if(privilegio_id == 1 || privilegio_id == 2 || privilegio_id == 5
                || value_user_id == user_id_session){

                return true;
            }
        }


        /**
         * Quando há uma reordenação de aulas, salvamos em um array a
         * posição de cada item do sortable e só então enviamos pro back-end.
         *
         */
        function reordenouAulas(event, ui)
        {
            var position = [];
            var aula_id;
            var index;
            var csrf = $('input[name="_token"]').val();

            $("#divAulas a").each(function(item) {
                aula_id = $(this).attr('id').replace('btnAula', '');
                index   = position.length;
                position.push({ 'aula_id': aula_id, 'position': index });
            });

            $.post(appurl + '/gestao/curso/' + {{ $curso->id }} + '/aula/' + aula_id + '/reordenar/position', { positions: position, _token: csrf }, function(response) {
                console.log(response);
            });
        }

        function reordenouConteudos(event, ui)
        {
            var position = [];
            var conteudo_id;
            var index;
            var csrf = $('input[name="_token"]').val();

            $(".sortable-conteudos tr").each(function(item) {
                conteudo_id = $(this).attr('id').replace('divConteudo', '');
                index       = position.length;
                position.push({ 'conteudo_id': conteudo_id, 'position': index });
            });

            $.post(appurl + '/gestao/curso/' + {{ $curso->id }} + '/aula/' + aulaAtual + '/conteudo/'+ conteudo_id +'/reEscolaordenar/position', { positions: position, _token: csrf }, function(response) {
                console.log(response);
            });

        }

        function showConteudosTipo(tipo)
        {
            $("#divBackgroundTipos").removeClass('d-none');
            $("#divConteudosTipo" + tipo).removeClass('d-none');

            setTimeout(function() {
                $("#divBackgroundTipos").css('opacity', 1);
                $("#divConteudosTipo" + tipo).css('opacity', 1);
            }, 100);

            setTimeout(function() {
                $(".tipo-conteudo").css('max-width', '900px');
                $(".tipo-conteudo").css('overflow', 'visible');
            }, 200);
        }

        function closeConteudos()
        {
            $("#divBackgroundTipos").css('opacity', 0);
            $(".tipo-conteudo").css('opacity', 0);

            $(".tipo-conteudo").css('max-width', '51px');
            $(".tipo-conteudo").css('overflow', 'hidden');

            setTimeout(function() {
                $("#divBackgroundTipos").addClass('d-none');
                $(".tipo-conteudo").addClass('d-none');
            }, 350);
        }

        function closeConteudosTipo(tipo)
        {
            $("#divBackgroundTipos").css('opacity', 0);
            $("#divConteudosTipo" + tipo).css('opacity', 0);

            setTimeout(function() {
                $("#divBackgroundTipos").addClass('d-none');
                $("#divConteudosTipo" + tipo).addClass('d-none');

            }, 350);
        }

        function closeAllPages()
        {
            $("#divEditarCurso").addClass('d-none');
            $("#divNovaAula").addClass('d-none');
            $("#divConteudos").addClass('d-none');
            $("#divMainLoading").addClass('d-none');

            $("#divAulas .list-group-item").removeClass('active');
        }

        function showEditarCurso()
        {
            closeAllPages();
            $("#divEditarCurso").removeClass('d-none');
            $("#divEditarCurso #divEditar").removeClass('d-none');
            $("#divEditarCurso #divEnviando").addClass('d-none');
        }

        function salvarCurso()
        {

            var isValid = true;

            /*$('#formEditarCurso input').each(function() {
                if ( $(this).val() === '' && $(this).attr('required') )
                {
                    swal("", "Preencha todos os campos", "error");
                    $(this).focus();
                    alert($(this).attr('name'));

                    isValid = false;
                }
            });
            */
            //Validação de text area não é necessario por enquanto para
            // if(!isValid || $("#formEditarCurso textarea").html() == '')
            //     return;

            $("#divEditarCurso #divEditar").addClass('d-none');
            $("#divEditarCurso #divEnviando").removeClass('d-none');

            $("#formEditarCurso").submit();
        }

        function publicarCurso()
        {
            swal({
                @if($curso->status == 0)
                    title: 'Deseja mesmo publicar?',
                    text: "Você deseja mesmo publicar?",
                    icon: "warning",
                    buttons: ['Não', 'Sim, pode publicar!'],
                @else
                    title: 'Deseja mesmo despublicar?',
                    text: "Você deseja mesmo despublicar?",
                    icon: "warning",
                    buttons: ['Não', 'Sim, pode despublicar!'],
                @endif
            }).then((result) => {
                if (result == true)
                {
                  $("#formPublicarCurso").submit();
                }
            });
        }

        function showCriarAula()
        {
            closeAllPages();
            $("#divNovaAula").removeClass('d-none');
            $("#divNovaAula #divEditar").removeClass('d-none');
            $("#divNovaAula #divEnviando").addClass('d-none');

            $("#formNovaAula [name='requisito']").val("nenhum");
            $("#formNovaAula [name='aula_especifica']").val("0");
            $("#formNovaAula #divAulaEspecifica").addClass('d-none');
        }

        function showEnviandoAula()
        {
            var isValid = true;

            $('#formNovaAula select, #formNovaAula input, #formNovaAula textarea').each(function() {
                if ( ($(this).val() === '' || $(this).val() == '0' || $(this).val() == null) && $(this).attr('required') )
                {
                    $(this).focus();

                    isValid = false;
                }
            });

            if($("#formNovaAula #cmbRequisitoAula").val() == "aula" && $("#formNovaAula #cmbAulaEspecifica").val() <= 0)
            {
                $("#formNovaAula #cmbAulaEspecifica").focus();

                isValid = false;
            }

            if(!isValid)
                return;

            $("#divNovaAula #divEditar").addClass('d-none');
            $("#divNovaAula #divEnviando").removeClass('d-none');
        }

        var aulaAtual = 0;

        function showAula(id)
        {

            if(id == aulaAtual)
            {
                closeAllPages();
                $("#divConteudos").removeClass('d-none');
                $("#divAulas #btnAula"+id).addClass('active');
                return;
            }

            closeAllPages();
            $("#divMainLoading").removeClass('d-none');
            $("#divAulas #btnAula"+id).addClass('active');

            $.ajax({
                url: '{{ url('') }}' + '/gestao/curso/' + {{ $curso->id }} + '/aula/' + id + '/conteudos',
                type: 'get',
                dataType: 'json',
                success: function( _response )
                {
                    if(_response.success)
                    {
                        _response.aula = JSON.parse(_response.aula);

                        $('#lblTituloAulaAtual').text(_response.aula.titulo.charAt(0).toUpperCase() + _response.aula.titulo.slice(1));

                        var targetExportIdAula = $('.targetExportIdAula').attr('href').replace('###', id);
                        $('.targetExportIdAula').attr('href', targetExportIdAula);

                        var targetImportIdAula = $('.targetImportIdAula').attr('action').replace('###', id);
                        $('.targetImportIdAula').attr('action', targetImportIdAula);

                        var targetImportIdAulaConteudo = $('.targetImportIdAulaConteudo').attr('action').replace('###', id);
                        $('.targetImportIdAulaConteudo').attr('action', targetImportIdAulaConteudo);

                        var conteudos = '';

                        if(_response.aula.conteudos.length == 0)
                        {
                            conteudos = `<div style="border:  2px solid #E3E5F0;background:  #F9F9F9;padding: 20px;max-width:100%;font-size: 18px;font-weight: bold;color:  #999FB4;">
                                <img class="mr-2 align-middle" src="{{ config('app.cdn') }}/images/instrucao.jpg" width="85" style="display:  inline-block;">
                                <div class="d-inline-block align-middle" style="width: calc(100% - 100px);">
                                    Utilize a <span style="color: var(--primary-color);">barra de criação</span> para adicionar algum conteúdo!
                                </div>
                            </div>`;
                        }

                        if(_response.aula.conteudos.length == undefined)
                        {
                            _response.aula.conteudos = Object.values(_response.aula.conteudos)
                        }

                        _response.aula.conteudos.forEach(aulaList);

                        function aulaList(value){

                            conteudos = conteudos + `<tr id="divConteudo${value.id}" class="bg-white box-shadow">
                                <td class="align-middle handle"><i class="fas fa-bars fa-fw mr-2"></i></td>
                                <td class="font-weight-bold align-middle text-justify" style="color: #999FB4;">
                                    ${value.titulo}
                                </td>
                                <td class="font-weight-bold align-middle" style="color: #999FB4;">
                                    <i class="${value.tipo_icon} fa-lg fa-fw mr-1" style="color: var(--primary-color);"></i>
                                    <span style="color: #60748A;">${value.tipo_nome}</span>
                                </td>
                                <td colspan="1" class="font-weight-bold align-middle">
                                    {{--  <span style="color: #60748A;">Nome do arquivo</span>  --}}
                                </td>
                                <td class="font-weight-bold align-middle">
                                    <span style="color: #60748A;">` + moment(value.created_at).format("LLL") + `</span>
                                </td>

                                <td class="font-weight-bold align-middle px-1">`;

                                if(verificaPermissao(value.user_id)){

                                    conteudos += `<button class="btn btn-link text-gray p-2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu text-left">
                                        <button type="button" onclick="editarConteudo(${value.id});" class="btn btn-link dropdown-item">
                                            <i class="fas fa-edit"></i>
                                            Editar conteúdo
                                        </button>
                                        <a href="` + appurl + '/gestao/curso/' + {{ $curso->id }} + '/aula/' + id + '/conteudo/' + value.id + '/exportar' + `" class="btn btn-link dropdown-item">
                                            <i class="fas fa-file-export mr-1"></i>
                                            Exportar conteúdo
                                        </a>

                                        <button type="button" onclick="excluirConteudo(${value.id});" class="btn btn-link dropdown-item text-danger">
                                            <i class="fas fa-trash mr-1"></i>
                                            Excluir conteúdo
                                        </button>
                                    </div>`;

                                    }
                                    conteudos += ` </td>
                            </tr>`;
                        }

                        $("#divListaConteudos").find('tbody').html(conteudos);

                        closeAllPages();
                        $("#divConteudos").removeClass('d-none');
                        $("#divAulas #btnAula"+id).addClass('active');

                        aulaAtual = id;
                    }
                    else
                    {
                        swal("", _response.error, "error");

                        closeAllPages();
                        $("#divNovaAula").removeClass('d-none');
                    }
                },
                error: function( _response )
                {

                }
            });
        }

        function showEditarAula()
        {
            $("#divModalEditarAula").modal({ keyboard: false, backdrop: 'static' });
            $("#divModalEditarAula #divLoading").removeClass('d-none');
            $("#divModalEditarAula #divEditar").addClass('d-none');
            $("#divModalEditarAula #divEnviando").addClass('d-none');

            $("#divModalEditarAula .form-page").addClass('d-none');
            $("#divModalEditarAula #page1").removeClass('d-none');

            $.ajax({
                url: '{{ url('') }}' + '/gestao/curso/' + {{ $curso->id }} + '/aula/' + aulaAtual + '/editar',
                type: 'get',
                dataType: 'json',
                success: function( _response )
                {

                    if(_response.success)
                    {
                        _response.aula = JSON.parse(_response.aula);

                        $("#divModalEditarAula [name='idAula']").val(_response.aula.id);
                        $("#divModalEditarAula [name='titulo']").val(_response.aula.titulo);
                        $("#divModalEditarAula [name='descricao']").val(_response.aula.descricao);
                        $("#divModalEditarAula [name='duracao']").val(_response.aula.duracao);
                        $("#divModalEditarAula [name='requisito']").val(_response.aula.requisito);
                        if(_response.aula.requisito == "aula")
                        {
                            $("#formEditarAula #divAulaEspecifica").removeClass('d-none');
                            $("#divModalEditarAula [name='aula_especifica']").val(_response.aula.requisito_id);
                        }
                        else
                        {
                            $("#formEditarAula #divAulaEspecifica").addClass('d-none');
                            $("#divModalEditarAula [name='aula_especifica']").val("0");
                        }

                        $("#divModalEditarAula #divLoading").addClass('d-none');
                        $("#divModalEditarAula #divEditar").removeClass('d-none');
                    }
                    else
                    {
                        swal("", _response.error, "error");

                        $("#divModalEditarAula").modal({ keyboard: false, backdrop: 'static' });
                    }
                },
                error: function( _response )
                {

                }
            });
        }

        function importarAula()
        {

            $('#fileImportAula').trigger('click');
            $("#fileImportAula").change(function() {
                $(this).parent('form').submit();
            });
        }

        function importarAulaConteudo()
        {
            $('#idAulaImportacao').val(aulaAtual);
            $('#fileImportAulaConteudo').trigger('click');
            $("#fileImportAulaConteudo").change(function() {
                $(this).parent('form').submit();
            });
        }

        function duplicarAula()
        {
            $("#formDuplicarAula #idAula").val(aulaAtual);

            swal({
                title: '',
                text: "Você deseja mesmo duplicar?",
                icon: "warning",
                buttons: ['Não', 'Sim, duplicar!'],
            }).then((result) => {
                if (result == true)
                {
                  $("#formDuplicarAula").submit();
                }
            });
        }

        function excluirAula(id)
        {
            $("#formExcluirAula #idAula").val(aulaAtual);

            swal({
                title: 'Excluir {{$langAula}}?',
                text: "Você deseja mesmo excluir?",
                icon: "warning",
                buttons: ['Não', 'Sim, excluir!'],
                dangerMode: true,
            }).then((result) => {
                if (result == true)
                {
                  $("#formExcluirAula").submit();
                }
            });
        }

        function showSelecaoConteudosBiblioteca()
        {
            closeConteudos();
            $("#modalSelecaoConteudos [name='idAulaB']").val(aulaAtual);

            $("#modalSelecaoConteudos").modal({ keyboard: false, backdrop: 'static' });
        }

        function showNovoConteudo(tipo)
        {
            closeConteudos();
            // jQuery.noConflict();
            $("#divModalNovoConteudo").modal({ keyboard: false, backdrop: 'static', focus: false });
            $("#divModalNovoConteudo #divLoading").addClass('d-none');
            $("#divModalNovoConteudo #divEnviando").addClass('d-none');
            $("#divModalNovoConteudo #divEditar").removeClass('d-none');

            $("#divModalNovoConteudo .tipos-conteudo .tipo").addClass('d-none');

            $("#divModalNovoConteudo .tipos-conteudo").find('#conteudoTipo' + tipo).removeClass('d-none');

            $("#divModalNovoConteudo [name='idAula']").val(aulaAtual);

            $("#divModalNovoConteudo [name='tipo']").val(tipo);

            $("#divModalNovoConteudo [name='titulo']").val('');
            $("#divModalNovoConteudo [name='descricao']").val('');
            $("#divModalNovoConteudo [name='obrigatorio']").prop('checked', true);
            $("#divModalNovoConteudo [name='tempo']").val('');

            switch(tipo)
            {
                /* case 1:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo de texto");
                    $("#formNovoConteudo #txtConteudo").summernote('code',`<div style="color: #525870;font-weight: bold;font-size: 16px;">
                        <p></p>
                        <ul>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </div>`);
                break; */
                case 2:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo de áudio");
                break;
                case 3:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo de vídeo");
                break;
                case 4:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo de slide");
                break;
                case 5:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo de transmissão ao vivo");
                break;
                case 6:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo de upload");
                break;
                case 7:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo dissertativo");
                break;
                case 8:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo de múltipla escolha");
                break;
                case 9:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo de prova");

                    let qtdPerguntas = perguntasProva.length;
                    perguntasProva = []

                    if (qtdPerguntas > 1) {
                        for (let i = 2; i < perguntasProva; i++) {
                            $("#divModalNovoConteudo #cmbQuestaoAtual option[value='"+i+"']").remove();
                        }
                    }
                break;
                case 10:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo entregável");
                break;
                case 11:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo livro digital");
                break;
                case 15:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo PDF");
                break;
                case 21:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo livro digital");
                break;
                default:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo de texto");
                break;
            }

            $("#divModalNovoConteudo [name='titulo']").focus();
        }

        function showNovoConteudoCards(tipo)
        {
            closeConteudos();
            // jQuery.noConflict();
            $("#divModalNovoConteudoCards").modal({ keyboard: false, backdrop: 'static' });
            $("#divModalNovoConteudoCards #divLoading").addClass('d-none');
            $("#divModalNovoConteudoCards #divEnviando").addClass('d-none');
            $("#divModalNovoConteudoCards #divEditar").removeClass('d-none');

            $("#divModalNovoConteudoCards .tipos-conteudo .tipo").addClass('d-none');

            $("#divModalNovoConteudoCards .tipos-conteudo").find('#conteudoTipo' + tipo).removeClass('d-none');

            $("#divModalNovoConteudoCards [name='idAula']").val(aulaAtual);

            $("#divModalNovoConteudoCards [name='tipo']").val(tipo);

            $("#divModalNovoConteudoCards [name='titulo']").val('');
            $("#divModalNovoConteudoCards [name='descricao']").val('');
            $("#divModalNovoConteudoCards [name='obrigatorio']").prop('checked', true);
            $("#divModalNovoConteudoCards [name='tempo']").val('');

            switch(tipo)
            {
                case 1:
                    $("#lblTipoNovoConteudoCards").text("Novo conteúdo de texto");

                    $(".btnAddquestao").hide();
                    $(".btnAddquestao1").hide();
                    $("#btncorrelacaopalavra1").hide();
                    $("#btncorrelacaopalavra").hide();
                break;
                case 2:
                    $("#lblTipoNovoConteudoCards").text("Novo conteúdo de áudio");
                break;
                case 3:
                    $("#lblTipoNovoConteudoCards").text("Novo conteúdo de vídeo");
                break;
                case 4:
                    $("#lblTipoNovoConteudoCards").text("Novo conteúdo de slide");
                break;
                case 5:
                    $("#lblTipoNovoConteudoCards").text("Novo conteúdo de transmissão ao vivo");
                break;
                case 6:
                    $("#lblTipoNovoConteudoCards").text("Novo conteúdo de upload");
                break;
                case 7:
                    $("#lblTipoNovoConteudoCards").text("Correlação de Palavras");
                    $(".btnAddquestao").hide();
                    $(".btnAddquestao1").show();
                break;
                case 8:
                    $("#lblTipoNovoConteudoCards").text("Quiz");
                    $(".btnAddquestao").show();
                    $(".btnAddquestao1").hide();
                break;
                case 9:
                    $("#lblTipoNovoConteudoCards").text("Novo conteúdo de prova");
                break;
                case 10:
                    $("#lblTipoNovoConteudoCards").text("Novo conteúdo entregável");
                break;
                case 12:
                    $("#lblTipoNovoConteudoCards").text("Descubra a Palavra");
                    $(".btnAddquestao1").hide();
                    $(".btnAddquestao").hide();
                    $("#btncorrelacaopalavra").hide();
                    $("#btncorrelacaopalavra1").hide();
                break;
                case 13:
                    $("#lblTipoNovoConteudoCards").text("Verdadeiro ou falso");
                    $(".btnAddquestao").show();
                    $(".btnAddquestao1").hide();
                    $("#btncorrelacaopalavra").hide();
                    $(".btnAddquestao2").hide();
                break;
                case 16:
                    $("#lblTipoNovoConteudoCards").text("Caça Palavra");
                    $(".btnAddquestao").hide();
                    $(".btncorrelacaopalavra1").hide();
                    $("#btncorrelacaopalavra").hide();
                    $(".btnAddquestao2").hide();
                break;
                case 17:
                    $("#lblTipoNovoConteudoCards").text("Quiz");
                    $(".btnAddquestao").show();
                    $(".btnAddquestao2").hide();
                    $(".btnAddquestao1").hide();
                    $("#btncorrelacaopalavra1").hide();
                break;
                case 19:
                    $("#lblTipoNovoConteudoCards").text("Correlação de Palavras");
                    $(".btnAddquestao").hide();
                    $(".btnAddquestao1").show();
                    $("#btncorrelacaopalavra").show();
                    $("#btncorrelacaopalavra1").hide();
                break;
                case 20:
                    $("#lblTipoNovoConteudoCards").text("Correlação de Imagens");
                    $(".btnAddquestao").hide();
                    $(".btnAddquestao1").show();
                    $("#btncorrelacaopalavra1").show();
                    $("#btncorrelacaopalavra").hide();

                break;
                default:
                    $("#lblTipoNovoConteudoCards").text("Novo conteúdo de texto");
                    $(".btnAddquestao").hide();
                    $(".btnAddquestao1").hide();
                    $("#btncorrelacaopalavra1").hide();
                    $("#btncorrelacaopalavra").hide();
                break;
            }

            $("#divModalNovoConteudo [name='titulo']").focus();
        }

        function salvarConteudo()
        {
            var isValid = true;

            $('#divModalNovoConteudo input').each(function() {
                if ( $(this).val() === '' && $(this).attr('required') )
                {
                    $(this).focus();

                    isValid = false;
                    swal("", "Preencha todos os campos", "error");
                }
            });

            switch ($("#divModalNovoConteudo #tipo").val()) {
                case '1':
                    if ($("#divModalNovoConteudo #txtConteudo").val() == '') {
                        $('.note-editable').trigger('focus');

                        isValid = false;
                    }
                    break;
                case '2':
                    if ($("#divModalNovoConteudo #inputAudioNovoConteudo").val() == '' &&
                        $("#divModalNovoConteudo #txtAudioNovoConteudo").val() == ''
                    ) {
                        $('#divModalNovoConteudo #txtAudioNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '3':
                    if ($("#divModalNovoConteudo #inputVideoNovoConteudo").val() == '' &&
                        $("#divModalNovoConteudo #txtVideoNovoConteudo").val() == ''
                    ) {
                        $('#divModalNovoConteudo #txtVideoNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '4':
                    if ($("#divModalNovoConteudo #inputSlideNovoConteudo").val() == '' &&
                        $("#divModalNovoConteudo #txtSlideNovoConteudo").val() == ''
                    ) {
                        $('#divModalNovoConteudo #txtSlideNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '5':
                    if ($("#divModalNovoConteudo #txtTransmissaoNovoConteudo").val() == '') {
                        $('#divModalNovoConteudo #txtTransmissaoNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '6':
                    if ($("#divModalNovoConteudo #inputArquivoNovoConteudo").val() == '' &&
                        $("#divModalNovoConteudo #txtArquivoNovoConteudo").val() == ''
                    ) {
                        $('#divModalNovoConteudo #txtArquivoNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '7':
                    if ($("#divModalNovoConteudo #txtDissertativaNovoConteudo").val() == '') {
                        $('#divModalNovoConteudo #txtDissertativaNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '8':
                    if ($("#divModalNovoConteudo #txtQuizNovoConteudo").val() == '') {
                        $('#divModalNovoConteudo #txtQuizNovoConteudo').focus();
                        isValid = false;
                    } else if ($("#divModalNovoConteudo #txtQuizAlternativa1NovoConteudo").val() == '') {
                        $('#divModalNovoConteudo #txtQuizAlternativa1NovoConteudo').focus();
                        isValid = false;
                    } else if ($("#divModalNovoConteudo #txtQuizAlternativa2NovoConteudo").val() == '') {
                        $('#divModalNovoConteudo #txtQuizAlternativa2NovoConteudo').focus();
                        isValid = false;
                    } else if ($("#divModalNovoConteudo #txtQuizAlternativa3NovoConteudo").val() == '') {
                        $('#divModalNovoConteudo #txtQuizAlternativa3NovoConteudo').focus();
                        isValid = false;
                    }
                    break;
                case '9':
                    if (perguntasProva.length == 0) {
                        $('#divModalNovoConteudo #btnAdicionarPergunta').focus();
                        swal("", 'Adicione ao menos uma pergunta!', "error");

                        isValid = false;
                    }
                    break;
                case '10':
                    if ($("#divModalNovoConteudo #txtConteudoEntregavel").val() == '') {
                        $('.note-editable').trigger('focus');

                        isValid = false;
                    }
                    break;
                case '11':
                    if ($("#divModalNovoConteudo #inputApostilaNovoConteudo").val() == '' &&
                        $("#divModalNovoConteudo #txtApostilaNovoConteudo").val() == ''
                    ) {
                        $('#divModalNovoConteudo #txtApostilaNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '12':
                    if ($("#divModalNovoConteudoCards #txtPalavraNovoConteudo").val() == '' ) {
                        $('#divModalNovoConteudoCards #txtPalavraNovoConteudo').focus();

                        isValid = false;
                    }
                    break;

                case '15':
                    if ($("#divModalNovoConteudo #inputPDFNovoConteudo").val() == '' &&
                        $("#divModalNovoConteudo #txtPDFNovoConteudo").val() == ''
                    ) {
                        $('#divModalNovoConteudo #txtPDFNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '21':
                    if ($("#divModalNovoConteudo #inputRevistaNovoConteudo").val() == '' &&
                        $("#divModalNovoConteudo #txtRevistaNovoConteudo").val() == ''
                    ) {
                        $('#divModalNovoConteudo #txtRevistaNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                default:
                    break;
            }
            if($('#divModalNovoConteudo .summernote').summernote('code') == '' && $("#divModalNovoConteudo .tipos-conteudo").find('#conteudoTipo1').hasClass('d-none') == false)
                return;

            if(!isValid)// || $("#divModalNovoConteudo textarea").html() == '')
                return;
            $("#formNovoConteudo").submit();

            $("#divModalNovoConteudo #divLoading").addClass('d-none');
            $("#divModalNovoConteudo #divEditar").addClass('d-none');
            $("#divModalNovoConteudo #divEnviando").removeClass('d-none');

            $("#divModalNovoConteudo #divLoading").addClass('d-none');
            $("#divModalNovoConteudo #divEditar").addClass('d-none');
            $("#divModalNovoConteudo #divEnviando").removeClass('d-none');
        }


        function salvarConteudoCards()
        {
            var isValid = true;

            $('#divModalNovoConteudoCards input').each(function() {
                if ( $(this).val() === '' && $(this).attr('required') )
                {
                    $(this).focus();

                    isValid = false;
                }
            });

            if(!isValid && $("#divModalNovoConteudoCards textarea").html() == '')
                return;

            {{--  $("#formNovoConteudoCrads").submit();  --}}

            var formData = new FormData(document.getElementById('formNovoConteudoCards'));



            $("#formNovoConteudoCards #divUploading").removeClass('d-none');

            $.ajax(
            {

                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    // Upload progress
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;


                            $("#formNovoConteudoCards #progressBar").val(percentComplete);
                            $("#formNovoConteudoCards #lblProgress").text((percentComplete * 100).toFixed(2) + "%" + " (" + (evt.loaded / 1000000).toFixed(2) + "MB / " + (evt.total / 1000000).toFixed(2) + "MB)");
                        }
                    }, false);

                    return xhr;
                },
                url: '{{ route('gestao.curso.aula-conteudos-novo',['idCurso' => $curso->id]) }}',
                type: 'POST',
                data : formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data, text)
                {
                    // swal('', "Modo DEBUG!", "warning");
                    location.reload();
                },
                error: function (request, error)
                {
                    swal('', "Ops, algo deu errado, por favor tente novamente.", "warning");
                }
            })
            .done(function(data)
            {
                {{--  location.reload();  --}}
            });


            $("#divModalNovoConteudoCards #divLoading").addClass('d-none');
            $("#divModalNovoConteudoCards #divEditar").addClass('d-none');
            $("#divModalNovoConteudoCards #divEnviando").removeClass('d-none');

            $("#divModalNovoConteudoCards #divLoading").addClass('d-none');
            $("#divModalNovoConteudocards #divEditar").addClass('d-none');
            $("#divModalNovoConteudo #divEnviando").removeClass('d-none');
        }


        function showEnviandoEditarAula()
        {
            var isValid = true;

            $('#formEditarAula select, #formEditarAula input, #formEditarAula textarea').each(function() {
                if ( ($(this).val() === '' || $(this).val() == '0' || $(this).val() == null) && $(this).attr('required') )
                {
                    $(this).focus();

                    isValid = false;
                }
            });

            if($("#formEditarAula #cmbRequisitoAula").val() == "aula" && $("#formEditarAula #cmbAulaEspecifica").val() <= 0)
            {
                $("#formEditarAula #cmbAulaEspecifica").focus();

                isValid = false;
            }

            if(!isValid)
                return;

            $("#divModalEditarAula #divLoading").addClass('d-none');
            $("#divModalEditarAula #divEditar").addClass('d-none');
            $("#divModalEditarAula #divEnviando").removeClass('d-none');

            $("#divModalEditarAula #divLoading").addClass('d-none');
            $("#divModalEditarAula #divEditar").addClass('d-none');
            $("#divModalEditarAula #divEnviando").removeClass('d-none');
        }



        function editarConteudo(idConteudo)
        {

            $("#divModalEditarConteudo").modal({ keyboard: false, backdrop: 'static', focus: false });
            $("#divModalEditarConteudo #divLoading").removeClass('d-none');
            $("#divModalEditarConteudo #divEditar").addClass('d-none');
            $("#divModalEditarConteudo #divEnviando").addClass('d-none');

            $("#divModalEditarConteudo .form-page").addClass('d-none');
            $("#divModalEditarConteudo #page1").removeClass('d-none');

            $.ajax({
                url: '{{ url('') }}' + '/gestao/curso/{{ $curso->id }}/aula/' + aulaAtual + '/conteudos/' + idConteudo + '/editar',
                type: 'get',
                dataType: 'json',
                success: function( _response )
                {


                    if(_response.success)
                    {


                        _response.conteudo = JSON.parse(_response.conteudo);

                        $("#divModalEditarConteudo .tipos-conteudo .tipo").addClass('d-none');

                        $("#divModalEditarConteudo .tipos-conteudo").find('#conteudoTipo' + _response.conteudo.tipo).removeClass('d-none');

                        var tipo = parseInt(_response.conteudo.tipo);

                        switch(tipo)
                        {
                            case 1:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo livre");
                                $("#formEditarConteudo #txtConteudo").val(_response.conteudo.conteudo);
                                 new FroalaEditor('#txtConteudo', {
                                      key: "1C%kZV[IX)_SL}UJHAEFZMUJOYGYQE[\\ZJ]RAe(+%$==",
                                      attribution: false, // to hide "Powered by Froala"
                                      heightMin: 132,
                                      language: 'pt_br',
                                      linkAlwaysBlank: true,
                                      imageUploadURL: '{{url('/upload/froala/')}}',
                                      imageUploadParams: {
                                        id: 'my_editor',
                                        _token: '{{ csrf_token() }}'
                                      },
                                      imageEditButtons: ['imageReplace', 'imageAlign', 'imageRemove', '|', 'imageLink', 'linkOpen', 'linkEdit', 'linkRemove', '-', 'imageDisplay', 'imageStyle', 'imageAlt', 'imageSize']
                                    });
                            break;

                            case 2:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo de áudio");
                                if(_response.conteudo.temArquivo)
                                {
                                    $("#divVerArquivoAtual").removeClass('d-none');
                                    $("#btnVerArquivoAtual").attr('href', '{{ config('app.local') }}/play/{{ $curso->id }}/conteudo/' + _response.conteudo.conteudo_aula.aula_id + '/' + _response.conteudo.id + '/arquivo');
                                    $("#divModalEditarConteudo [name='conteudoAudio']").val();
                                }
                                else
                                {
                                    $("#divVerArquivoAtual").addClass('d-none');
                                    $("#btnVerArquivoAtual").attr('href', '#');
                                    $("#divModalEditarConteudo [name='conteudoAudio']").val(_response.conteudo.conteudo);
                                }

                            break;

                            case 3:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo de vídeo");
                                if(_response.conteudo.temArquivo)
                                {
                                    $("#divVerArquivoAtual").removeClass('d-none');
                                    $("#btnVerArquivoAtual").attr('href', '{{ config('app.local') }}/play/{{ $curso->id }}/conteudo/' + _response.conteudo.conteudo_aula.aula_id + '/' + _response.conteudo.id + '/arquivo');
                                    $("#divModalEditarConteudo [name='conteudoVideo']").val();
                                }
                                else
                                {
                                    $("#divVerArquivoAtual").addClass('d-none');
                                    $("#btnVerArquivoAtual").attr('href', '#');
                                    $("#divModalEditarConteudo [name='conteudoVideo']").val(_response.conteudo.conteudo);
                                }
                            break;

                            case 4:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo de slide");
                                if(_response.conteudo.temArquivo)
                                {
                                    $("#divVerArquivoAtual").removeClass('d-none');
                                    $("#btnVerArquivoAtual").attr('href', '{{ config('app.local') }}/play/{{ $curso->id }}/conteudo/' + _response.conteudo.conteudo_aula.aula_id + '/' + _response.conteudo.id + '/arquivo');
                                    $("#divModalEditarConteudo [name='conteudoSlide']").val();
                                }
                                else
                                {
                                    $("#divVerArquivoAtual").addClass('d-none');
                                    $("#btnVerArquivoAtual").attr('href', '#');
                                    $("#divModalEditarConteudo [name='conteudoSlide']").val(_response.conteudo.conteudo);
                                }
                            break;

                            case 5:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo de transmissão ao vivo");
                                $("#divModalEditarConteudo [name='conteudoTransmissao']").val(_response.conteudo.conteudo);
                            break;

                            case 6:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo de upload");
                                if(_response.conteudo.temArquivo)
                                {
                                    $("#divVerArquivoAtual").removeClass('d-none');
                                    $("#btnVerArquivoAtual").attr('href', '{{ config('app.local') }}/play/{{ $curso->id }}/conteudo/' + _response.conteudo.conteudo_aula.aula_id + '/' + _response.conteudo.id + '/arquivo');
                                    $("#divModalEditarConteudo [name='conteudoArquivo']").val();
                                }
                                else
                                {
                                    $("#divVerArquivoAtual").addClass('d-none');
                                    $("#btnVerArquivoAtual").attr('href', '#');
                                    $("#divModalEditarConteudo [name='conteudoArquivo']").val(_response.conteudo.conteudo);
                                }
                            break;

                            case 7:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo dissertativo");
                                _response.conteudo.conteudo = JSON.parse(_response.conteudo.conteudo);
                                $("#divModalEditarConteudo [name='conteudoDissertativa']").val(_response.conteudo.conteudo.pergunta);
                                $("#divModalEditarConteudo [name='conteudoDissertativaDica']").val(_response.conteudo.conteudo.dica);
                                $("#divModalEditarConteudo [name='conteudoDissertativaExplicacao']").val(_response.conteudo.conteudo.explicacao);
                                $("#divModalEditarConteudo #questaoTipo7Id").val(_response.conteudo.questoes_id);
                            break;

                            case 8:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo de multipla escolha");
                                _response.conteudo.conteudo = JSON.parse(_response.conteudo.conteudo);
                                $("#divModalEditarConteudo [name='conteudoQuizPergunta']").val(_response.conteudo.conteudo.pergunta);
                                $("#divModalEditarConteudo #rdoAlternativa" + _response.conteudo.conteudo.correta).prop("checked", true);
                                $("#divModalEditarConteudo [name='conteudoQuizAlternativa1']").val(_response.conteudo.conteudo.alternativas[0]);
                                $("#divModalEditarConteudo [name='conteudoQuizAlternativa2']").val(_response.conteudo.conteudo.alternativas[1]);
                                $("#divModalEditarConteudo [name='conteudoQuizAlternativa3']").val(_response.conteudo.conteudo.alternativas[2]);
                                $("#divModalEditarConteudo [name='conteudoQuizDica']").val(_response.conteudo.conteudo.dica);
                                $("#divModalEditarConteudo [name='conteudoQuizExplicacao']").val(_response.conteudo.conteudo.explicacao);
                                $("#divModalEditarConteudo #questaoTipo8Id").val(_response.conteudo.questoes_id);
                                new FroalaEditor(' #txtQuizAlternativa1NovoConteudo, #txtQuizAlternativa2NovoConteudo, #txtQuizAlternativa3NovoConteudo, #txtQuizNovoConteudo,  #txtQuizAlternativa1, #txtQuizAlternativa2, #txtQuizAlternativa3', {
                                    key: "1C%kZV[IX)_SL}UJHAEFZMUJOYGYQE[\\ZJ]RAe(+%$==",
                                    attribution: false, // to hide "Powered by Froala"
                                    heightMin: 132,
                                    language: 'pt_br',
                                    linkAlwaysBlank: true,
                                    imageUploadURL: '{{url('/upload/froala/')}}',
                                      imageUploadParams: {
                                        id: 'my_editor',
                                        _token: '{{ csrf_token() }}'
                                      },
                                      imageEditButtons: ['imageReplace', 'imageAlign', 'imageRemove', '|', 'imageLink', 'linkOpen', 'linkEdit', 'linkRemove', '-', 'imageDisplay', 'imageStyle', 'imageAlt', 'imageSize']
                                });
                            break;

                            case 9:


                                let qtdPerguntas = perguntasProva.length;
                                perguntasProva = []
                                let prova = _response.conteudo.conteudo;
                                prova = JSON.parse(prova);
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo de prova");

                                for (let i = 2; i <= (qtdPerguntas+1); i++) {
                                    $("#divModalEditarConteudo #cmbQuestaoAtual option[value='"+i+"']").remove();
                                }

                                if (!Array.isArray(prova)) {
                                    $("#divModalEditarConteudo #txtPesoPergunta").val(prova.peso);
                                    $("#divModalEditarConteudo #rdoTipoResposta1").prop('checked', false);
                                    $("#divModalEditarConteudo #rdoTipoResposta2").prop('checked', false);
                                    $("#divModalEditarConteudo #rdoTipoResposta"+prova.tipo).prop('checked', true);
                                    if (prova.tipo == 1) {
                                        $("#divModalEditarConteudo #txtPerguntaDissertativa").val(prova.pergunta);
                                    } else {
                                        $("#divModalEditarConteudo #txtPerguntaQuiz").val(prova.pergunta);
                                        $("#divModalEditarConteudo [name='alternativaCorretaMultiplaEscolha']").prop('checked', false);
                                        $("#divModalEditarConteudo #rdoAlternativaMultiplaEscolha"+prova.correta).prop('checked', true);
                                        $("#divModalEditarConteudo #txtQuizAlternativa1").val(prova.alternativas[0]);
                                        $("#divModalEditarConteudo #txtQuizAlternativa2").val(prova.alternativas[1]);
                                        $("#divModalEditarConteudo #txtQuizAlternativa3").val(prova.alternativas[2]);
                                    }
                                    adicionarNovaPerguntaProva('divModalEditarConteudo');

                                    prova[0] = prova;
                                } else {
                                    for (const key in prova) {
                                        let questao = prova[key];

                                        $("#divModalEditarConteudo #txtPesoPergunta").val(questao.peso);
                                        $("#divModalEditarConteudo #rdoTipoResposta1").prop('checked', false);
                                        $("#divModalEditarConteudo #rdoTipoResposta2").prop('checked', false);
                                        $("#divModalEditarConteudo #rdoTipoResposta"+questao.tipo).prop('checked', true);
                                        if (questao.tipo == 1) {
                                            $("#divModalEditarConteudo #txtPerguntaDissertativa").val(questao.pergunta);
                                        } else {
                                            $("#divModalEditarConteudo #txtPerguntaQuiz").val(questao.pergunta);
                                            $("#divModalEditarConteudo [name='alternativaCorretaMultiplaEscolha']").prop('checked', false);
                                            $("#divModalEditarConteudo #rdoAlternativaMultiplaEscolha"+questao.correta).prop('checked', true);
                                            $("#divModalEditarConteudo #txtQuizAlternativa1").val(questao.alternativas[0]);
                                            $("#divModalEditarConteudo #txtQuizAlternativa2").val(questao.alternativas[1]);
                                            $("#divModalEditarConteudo #txtQuizAlternativa3").val(questao.alternativas[2]);
                                        }
                                        adicionarNovaPerguntaProva('divModalEditarConteudo')
                                    }
                                }

                                $("#divModalEditarConteudo #questaoTipo9Id").val(_response.conteudo.questoes_id);
                                $("#divModalEditarConteudo #txtDescricaoNovoConteudo").val(_response.conteudo.descricao);
                                $("#divModalEditarConteudo #cmbQuestaoAtual option[value=1]").prop("selected", true);
                                $("#divModalEditarConteudo #txtPesoPergunta").val(prova[0].peso);
                                $("#divModalEditarConteudo #rdoTipoResposta1").prop('checked', false);
                                $("#divModalEditarConteudo #rdoTipoResposta2").prop('checked', false);
                                $("#divModalEditarConteudo #rdoTipoResposta"+prova[0].tipo).prop('checked', true);
                                $("#divModalEditarConteudo #btnTipoResposta1").removeClass('active');
                                $("#divModalEditarConteudo #btnTipoResposta2").removeClass('active');
                                $("#divModalEditarConteudo #btnTipoResposta"+prova[0].tipo).addClass('active');
                                if (prova[0].tipo == 1) {
                                    $("#divModalEditarConteudo #divMultiplaEscolha").addClass('d-none');
                                    $("#divModalEditarConteudo #txtPerguntaDissertativa").val(prova[0].pergunta);
                                } else {
                                    $("#divModalEditarConteudo #divDissertativa").addClass('d-none');
                                    $("#divModalEditarConteudo [name='txtQuizAlternativa1']").val(prova[0].alternativas[0]);
                                    $("#divModalEditarConteudo [name='txtQuizAlternativa2']").val(prova[0].alternativas[1]);
                                    $("#divModalEditarConteudo [name='txtQuizAlternativa3']").val(prova[0].alternativas[2]);
                                }


                            break;

                            case 10:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo entregável");
                                $("#formEditarConteudo #txtConteudoEntregavel").val(_response.conteudo.conteudo);

                                new FroalaEditor('#txtConteudoEntregavel', {
                                      key: "1C%kZV[IX)_SL}UJHAEFZMUJOYGYQE[\\ZJ]RAe(+%$==",
                                      attribution: false, // to hide "Powered by Froala"
                                      heightMin: 132,
                                      language: 'pt_br',
                                      linkAlwaysBlank: true,
                                      imageUploadURL: '{{url('/upload/froala/')}}',
                                      imageUploadParams: {
                                        id: 'my_editor',
                                        _token: '{{ csrf_token() }}'
                                      },
                                      imageEditButtons: ['imageReplace', 'imageAlign', 'imageRemove', '|', 'imageLink', 'linkOpen', 'linkEdit', 'linkRemove', '-', 'imageDisplay', 'imageStyle', 'imageAlt', 'imageSize']
                                });
                            break;

                            case 11:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo livro digital");
                                $("#formEditarConteudo #txtConteudoApostila").summernote('code',_response.conteudo.conteudo);
                            break;

                            case 15:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo PDF");
                                if(_response.conteudo.temArquivo)
                                {
                                    $("#divVerArquivoAtual").removeClass('d-none');
                                    $("#btnVerArquivoAtual").attr('href', '{{ config('app.local') }}/play/{{ $curso->id }}/conteudo/' + _response.conteudo.conteudo_aula.aula_id + '/' + _response.conteudo.id + '/arquivo');
                                    $("#divModalEditarConteudo [name='conteudoPDF']").val();
                                }
                                else
                                {
                                    $("#divVerArquivoAtual").addClass('d-none');
                                    $("#btnVerArquivoAtual").attr('href', '#');
                                    $("#divModalEditarConteudo [name='conteudoPDF']").val(_response.conteudo.conteudo);
                                }

                            break;

                            case 21:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo livro digital");
                                $("#formEditarConteudo #txtConteudoApostila").summernote(_response.conteudo.conteudo);
                            break;

                            default:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo livre2");
                                $("#formEditarConteudo #txtConteudo").val('code',_response.conteudo.conteudo);
                            break;
                        }

                        $('#divModalEditarConteudo .ciclo_id').val(_response.conteudo.ciclo_id).change();
                        $('#divModalEditarConteudo .componente_curricular').val(_response.conteudo.disciplina_id);

                        setTimeout(function() {
                            $('#divModalEditarConteudo .cicloetapa_id').val(_response.conteudo.cicloetapa_id);
                        }, 500);

                        $('.cicloetapa_id').on('change', function(el) {
                            $('.cicloetapa_id').val($(this).val());
                        });

                        $("#divModalEditarConteudo [name='idAula']").val(_response.conteudo.conteudo_aula.aula_id);
                        $("#divModalEditarConteudo [name='idConteudo']").val(_response.conteudo.id);
                        $("#divModalEditarConteudo [name='tipo']").val(_response.conteudo.tipo);
                        $("#divModalEditarConteudo [name='titulo']").val(_response.conteudo.titulo);
                        $("#divModalEditarConteudo [name='obrigatorio']").prop("checked", _response.conteudo.obrigatorio);
                        if(_response.conteudo.tempo > 0)
                            $("#divModalEditarConteudo [name='tempo']").val(_response.conteudo.tempo);
                        else
                            $("#divModalEditarConteudo [name='tempo']").val("");

                        {{--  $("#divModalEditarConteudo [name='visibilidade']").val(_response.conteudo.visibilidade);  --}}
                        $("#divModalEditarConteudo [name='status']").val(_response.conteudo.status);

                        $("#divModalEditarConteudo [name='descricao']").val(_response.conteudo.descricao);
                        $("#divModalEditarConteudo [name='apoio']").val(_response.conteudo.apoio);
                        $("#divModalEditarConteudo [name='fonte']").val(_response.conteudo.fonte);
                        $("#divModalEditarConteudo [name='autores']").val(_response.conteudo.autores);

                        $("#divModalEditarConteudo #divLoading").addClass('d-none');
                        $("#divModalEditarConteudo #divEditar").removeClass('d-none');
                    }
                    else
                    {
                        swal("", _response.error, "error");

                        $("#divModalEditarConteudo").modal({ keyboard: false, backdrop: 'static' });
                    }
                },
                error: function( _response )
                {

                }
            });
        }

        function salvarEdicaoConteudo()
        {

            var isValid = true;

            $('#divModalEditarConteudo input').each(function() {
                if ( $(this).val() === '' && $(this).attr('required') )
                {

                    $(this).focus();

                    isValid = false;
                }
            });

            switch ($("#divModalEditarConteudo #tipo").val()) {
                case '1':
                    if ($("#divModalEditarConteudo #txtConteudo").val() == '') {
                        $('.note-editable').trigger('focus');

                        isValid = false;
                    }
                    break;
                case '2':
                    if ($("#divModalEditarConteudo #btnVerArquivoAtual").attr('href') == '' &&
                        $("#divModalEditarConteudo #inputAudioNovoConteudo").val() == '' &&
                        $("#divModalEditarConteudo #txtAudioNovoConteudo").val() == ''
                    ) {
                        $('#divModalEditarConteudo #txtAudioNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '3':
                    if ($("#divModalEditarConteudo #btnVerArquivoAtual").attr('href') == '' &&
                        $("#divModalEditarConteudo #inputVideoNovoConteudo").val() == '' &&
                        $("#divModalEditarConteudo #txtVideoNovoConteudo").val() == ''
                    ) {
                        $('#divModalEditarConteudo #txtVideoNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '4':
                    if ($("#divModalEditarConteudo #btnVerArquivoAtual").attr('href') == '' &&
                        $("#divModalEditarConteudo #inputSlideNovoConteudo").val() == '' &&
                        $("#divModalEditarConteudo #txtSlideNovoConteudo").val() == ''
                    ) {
                        $('#divModalEditarConteudo #txtSlideNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '5':
                    if ($("#divModalEditarConteudo #txtTransmissaoNovoConteudo").val() == '') {
                        $('#divModalEditarConteudo #txtTransmissaoNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '6':
                    if ($("#divModalEditarConteudo #btnVerArquivoAtual").attr('href') == '' &&
                        $("#divModalEditarConteudo #inputArquivoNovoConteudo").val() == '' &&
                        $("#divModalEditarConteudo #txtArquivoNovoConteudo").val() == ''
                    ) {
                        $('#divModalEditarConteudo #txtArquivoNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '7':
                    if ($("#divModalEditarConteudo #txtDissertativaNovoConteudo").val() == '') {
                        $('#divModalEditarConteudo #txtDissertativaNovoConteudo').focus();


                        isValid = false;
                    }
                    break;
                case '8':

                    if ($("#divModalEditarConteudo #txtQuizNovoConteudo").val() == '') {
                        $('#divModalEditarConteudo #txtQuizNovoConteudo').focus();
                        isValid = false;
                    } else if ($("#divModalEditarConteudo #txtQuizAlternativa1NovoConteudo").val() == '') {
                        $('#divModalEditarConteudo #txtQuizAlternativa1NovoConteudo').focus();
                        isValid = false;
                    } else if ($("#divModalEditarConteudo #txtQuizAlternativa2NovoConteudo").val() == '') {
                        $('#divModalEditarConteudo #txtQuizAlternativa2NovoConteudo').focus();
                        isValid = false;
                    } else if ($("#divModalEditarConteudo #txtQuizAlternativa3NovoConteudo").val() == '') {
                        $('#divModalEditarConteudo #txtQuizAlternativa3NovoConteudo').focus();
                        isValid = false;
                    }
                    break;
                case '9':
                    if (perguntasProva.length == 0) {
                        $('#divModalEditarConteudo #btnAdicionarPergunta').focus();
                        swal("", 'Adicione ao menos uma pergunta!', "error");

                        isValid = false;
                    }
                    break;
                case '10':
                    if ($("#divModalEditarConteudo #txtConteudoEntregavel").val() == '') {
                        $('.note-editable').trigger('focus');

                        isValid = false;
                    }
                    break;
                case '11':
                    if ($("#divModalEditarConteudo #btnVerArquivoAtual").attr('href') == '' &&
                        $("#divModalEditarConteudo #inputApostilaNovoConteudo").val() == '' &&
                        $("#divModalEditarConteudo #txtApostilaNovoConteudo").val() == ''
                    ) {
                        $('#divModalEditarConteudo #txtApostilaNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '15':
                    if ($("#divModalEditarConteudo #btnVerArquivoAtual").attr('href') == '' &&
                        $("#divModalNovoConteudo #inputPDFNovoConteudo").val() == '' &&
                        $("#divModalNovoConteudo #txtPDFNovoConteudo").val() == ''
                    ) {
                        $('#divModalNovoConteudo #txtPDFNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '21':
                    if ($("#divModalEditarConteudo #btnVerArquivoAtual").attr('href') == '' &&
                        $("#divModalEditarConteudo #inputRevistaNovoConteudo").val() == '' &&
                        $("#divModalEditarConteudo #txtRevistaNovoConteudo").val() == ''
                    ) {
                        $('#divModalEditarConteudo #txtRevistaNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                default:
                    break;
            }

            {{-- if($('#divModalNovoConteudo .summernote').summernote('code') == '' && $("#divModalNovoConteudo .tipos-conteudo").find('#conteudoTipo1').hasClass('d-none') == false)
                return; --}}

            if(!isValid)// || $("#divModalNovoConteudo textarea").html() == '')
                return;

            {{-- if(!isValid || $("#divModalEditarConteudo textarea").html() == '')
            {
                return;
            } --}}

            $("#formEditarConteudo").submit();

            $("#divModalEditarConteudo #divLoading").addClass('d-none');
            $("#divModalEditarConteudo #divEditar").addClass('d-none');
            $("#divModalEditarConteudo #divEnviando").removeClass('d-none');

            $("#divModalEditarConteudo #divLoading").addClass('d-none');
            $("#divModalEditarConteudo #divEditar").addClass('d-none');
            $("#divModalEditarConteudo #divEnviando").removeClass('d-none');
        }

        function duplicarConteudo(id)
        {
            $("#formDuplicarConteudo #idAula").val(aulaAtual);
            $("#formDuplicarConteudo #idConteudo").val(id);

            swal({
                title: 'Deseja mesmo duplicar?',
                text: "Você deseja mesmo duplicar este conteúdo?",
                icon: "warning",
                buttons: ['Não', 'Sim, duplicar!'],
            }).then((result) => {
                if (result == true)
                {
                  $("#formDuplicarConteudo").submit();
                }
            });
        }

        function excluirConteudo(id)
        {
            $("#formExcluirConteudo #idAula").val(aulaAtual);
            $("#formExcluirConteudo #idConteudo").val(id);

            swal({
                title: 'Deseja mesmo excluir?',
                text: "Você deseja mesmo excluir este conteúdo?",
                icon: "warning",
                buttons: ['Não', 'Sim, excluir!'],
                dangerMode: true,
            }).then((result) => {
                if (result == true)
                {
                  $("#formExcluirConteudo").submit();
                }
            });
        }

        function mudouPeriodo(el)
        {
            if(el.value > 0)
            {
                $("#lblPeriodo").text(el.value);
            }
            else
            {
                $("#lblPeriodo").text("Ilimitado");
            }
        }

        function mudouVagas(el)
        {
            if(el.value > 0)
            {
                $("#lblVagas").text(el.value);
            }
            else
            {
                $("#lblVagas").text("Ilimitado");
            }
        }

        function mudouRequisitoAula(editando)
        {
            if(editando)
            {

                //Desabilita a aula que está sendo editada
                $("#formEditarAula #cmbAulaEspecifica option").removeAttr('disabled');
                $("#formEditarAula #cmbAulaEspecifica option[value='0']").attr('disabled', true);
                $("#formEditarAula #cmbAulaEspecifica option[value='" + $("#divModalEditarAula [name='idAula']").val() + "']").attr('disabled', true);

                if($("#formEditarAula #cmbRequisitoAula").val() == "aula")
                {
                    $("#formEditarAula #divAulaEspecifica").removeClass('d-none');
                }
                else
                {
                    $("#formEditarAula #divAulaEspecifica").addClass('d-none');
                }
            }
            else
            {

                if($("#formNovaAula #cmbRequisitoAula").val() == "aula")
                {
                    $("#formNovaAula #divAulaEspecifica").removeClass('d-none');
                }
                else
                {
                    $("#formNovaAula #divAulaEspecifica").addClass('d-none');
                }
            }
        }

        function mudouTipoRespostaProva(op, modalId)
        {
            if(op == "rdoTipoResposta1")
            {
                $("#"+modalId+" #divDissertativa").removeClass("d-none");
                $("#"+modalId+" #divMultiplaEscolha").addClass("d-none");
            }
            else
            {
                $("#"+modalId+" #divMultiplaEscolha").removeClass("d-none");
                $("#"+modalId+" #divDissertativa").addClass("d-none");
            }
        }

        $('.modal').on('change', '.select-mppa', function() {
            var target = $(this).closest('.modal').attr('id');
            //mudouPerguntaProvaAtual(target);

            $('#' + target + ' #divPerguntas div').addClass('d-none');
            $('#' + target + ' #divPerguntas #divPergunta' + $(this).val()).removeClass('d-none');
            $('#' + target + ' #divPerguntas #divPergunta' + $(this).val() + ' .box-alternativa').removeClass('d-none');
            $('#' + target + ' #divPerguntas #divPergunta' + $(this).val() + ' .box-alternativa .custom-control').removeClass('d-none');
        });

        var perguntasProva = [];

        function mudouPerguntaProvaAtual(modalId)
        {
            var perguntaAtual = $("#"+modalId+" #cmbQuestaoAtual").val();

            if(perguntasProva.length >= perguntaAtual)
            {
                if(perguntasProva[perguntaAtual - 1].tipo == 1)
                {
                    $("#"+modalId+" #rdoTipoResposta1").prop('checked', true);
                    $("#"+modalId+" #btnTipoResposta1").button('toggle');
                    $("#"+modalId+" #divDissertativa").removeClass("d-none");
                    $("#"+modalId+" #divMultiplaEscolha").addClass("d-none");

                    $("#"+modalId+" #divDissertativa #txtPerguntaDissertativa").val(perguntasProva[perguntaAtual - 1].pergunta);
                }
                else
                {
                    $("#"+modalId+" #rdoTipoResposta2").prop('checked', true);
                    $("#"+modalId+" #btnTipoResposta2").button('toggle');
                    $("#"+modalId+" #divMultiplaEscolha").removeClass("d-none");
                    $("#"+modalId+" #divDissertativa").addClass("d-none");

                    $("#"+modalId+" #divMultiplaEscolha #txtPerguntaQuiz").val(perguntasProva[perguntaAtual - 1].pergunta);
                    $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa1").val(perguntasProva[perguntaAtual - 1].alternativas[0]);
                    $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa2").val(perguntasProva[perguntaAtual - 1].alternativas[1]);
                    $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa3").val(perguntasProva[perguntaAtual - 1].alternativas[2]);
                    $("#"+modalId+" #divMultiplaEscolha #rdoAlternativaMultiplaEscolha" + perguntasProva[perguntaAtual - 1].correta).prop("checked", true);
                }

                $("#"+modalId+" #txtPesoPergunta").val(perguntasProva[perguntaAtual - 1].peso);

                $("#"+modalId+" #btnAdicionarPergunta").text("Salvar pergunta");
            }
            else
            {
                if(perguntaAtual == (perguntasProva.length + 1))
                {
                    $("#"+modalId+" #rdoTipoResposta1").prop('checked', true);
                    $("#"+modalId+" #btnTipoResposta1").button('toggle');
                    $("#"+modalId+" #divDissertativa").removeClass("d-none");
                    $("#"+modalId+" #divMultiplaEscolha").addClass("d-none");

                    $("#"+modalId+" #divDissertativa #txtPerguntaDissertativa").val("");
                    $("#"+modalId+" #divMultiplaEscolha #rdoAlternativaMultiplaEscolha1").prop("checked", false);
                    $("#"+modalId+" #divMultiplaEscolha #rdoAlternativaMultiplaEscolha2").prop("checked", false);
                    $("#"+modalId+" #divMultiplaEscolha #rdoAlternativaMultiplaEscolha3").prop("checked", false);
                    $("#"+modalId+" #divMultiplaEscolha #txtPerguntaQuiz").val("");
                    $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa1").val("");
                    $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa2").val("");
                    $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa3").val("");

                    $("#"+modalId+" #txtPesoPergunta").val("");
                }
                else
                {
                    $("#"+modalId+" #cmbQuestaoAtual option[value='" + perguntaAtual + "']").remove();
                    $("#"+modalId+" #cmbQuestaoAtual").val(perguntasProva.length + 1);
                }

                $("#"+modalId+" #btnAdicionarPergunta").text("Adicionar nova pergunta");
            }
        }

        function adicionarNovaPerguntaProva(modalId)
        {
            if($("#"+modalId+" #rdoTipoResposta1").prop('checked'))
            {
                if($("#"+modalId+" #divDissertativa #txtPerguntaDissertativa").val() == "")
                {
                    $("#"+modalId+" #divDissertativa #txtPerguntaDissertativa").focus();
                    return;
                }
                else if($("#"+modalId+" #txtPesoPergunta").val() <= 0)
                {
                    $("#"+modalId+" #txtPesoPergunta").focus();
                    return;
                }

                if($("#"+modalId+" #cmbQuestaoAtual").val() == (perguntasProva.length + 1))
                {
                    perguntasProva.push({
                        'tipo' : 1,
                        'pergunta' : $("#"+modalId+" #divDissertativa #txtPerguntaDissertativa").val(),
                        'peso' : $("#"+modalId+" #txtPesoPergunta").val(),
                    });
                }
                else
                {
                    perguntasProva[$("#"+modalId+" #cmbQuestaoAtual").val() - 1] = {
                        'tipo' : 1,
                        'pergunta' : $("#"+modalId+" #divDissertativa #txtPerguntaDissertativa").val(),
                        'peso' : $("#"+modalId+" #txtPesoPergunta").val(),
                    };
                }

                $("#"+modalId+" #divDissertativa #txtPerguntaDissertativa").val("");

            }
            else
            {
                var correta = 0;
                if($("#"+modalId+" #divMultiplaEscolha #rdoAlternativaMultiplaEscolha1").prop("checked"))
                    correta = 1;
                else if($("#"+modalId+" #divMultiplaEscolha #rdoAlternativaMultiplaEscolha2").prop("checked"))
                    correta = 2;
                else if($("#"+modalId+" #divMultiplaEscolha #rdoAlternativaMultiplaEscolha3").prop("checked"))
                    correta = 3;

                if($("#"+modalId+" #divMultiplaEscolha #txtPerguntaQuiz").val() == "")
                {
                    $("#"+modalId+" #divMultiplaEscolha #txtPerguntaQuiz").focus();
                    return;
                }
                else if($("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa1").val() == "")
                {
                    $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa1").focus();
                    return;
                }
                else if($("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa2").val() == "")
                {
                    $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa2").focus();
                    return;
                }
                else if($("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa3").val() == "")
                {
                    $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa3").focus();
                    return;
                }
                else if(correta == 0)
                {
                    return;
                }
                else if($("#"+modalId+" #txtPesoPergunta").val() <= 0)
                {
                    $("#"+modalId+" #txtPesoPergunta").focus();
                    return;
                }

                if($("#"+modalId+" #cmbQuestaoAtual").val() == (perguntasProva.length + 1))
                {
                    perguntasProva.push({
                        'tipo' : 2,
                        'pergunta' : $("#"+modalId+" #divMultiplaEscolha #txtPerguntaQuiz").val(),
                        'alternativas' : [ $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa1").val(),
                            $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa2").val(),
                            $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa3").val()
                            ],
                        'correta' : correta,
                        'peso' : $("#"+modalId+" #txtPesoPergunta").val(),
                    });
                }
                else
                {
                    perguntasProva[$("#"+modalId+" #cmbQuestaoAtual").val() - 1] = {
                        'tipo' : 2,
                        'pergunta' : $("#"+modalId+" #divMultiplaEscolha #txtPerguntaQuiz").val(),
                        'alternativas' : [ $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa1").val(),
                            $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa2").val(),
                            $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa3").val()
                            ],
                        'correta' : correta,
                        'peso' : $("#"+modalId+" #txtPesoPergunta").val(),
                    };
                }

                $("#"+modalId+" #divMultiplaEscolha #rdoAlternativaMultiplaEscolha1").prop("checked", false);
                $("#"+modalId+" #divMultiplaEscolha #rdoAlternativaMultiplaEscolha2").prop("checked", false);
                $("#"+modalId+" #divMultiplaEscolha #rdoAlternativaMultiplaEscolha3").prop("checked", false);
                $("#"+modalId+" #divMultiplaEscolha #txtPerguntaQuiz").val("");
                $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa1").val("");
                $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa2").val("");
                $("#"+modalId+" #divMultiplaEscolha #txtQuizAlternativa3").val("");
            }

            $("#"+modalId+" #txtPesoPergunta").val("");

            $("#"+modalId+" #cmbQuestaoAtual option[value='" + perguntasProva.length + "']").removeAttr("disabled");

            if($("#"+modalId+" #cmbQuestaoAtual option").length < (perguntasProva.length + 1))
            {
                $("#"+modalId+" #cmbQuestaoAtual").append("<option value='" + (perguntasProva.length + 1) +"'>Pergunta " + (perguntasProva.length + 1) +"</option>");
            }

            $("#"+modalId+" #cmbQuestaoAtual").val(perguntasProva.length + 1);

            $("#"+modalId+" #txtPerguntas").val(JSON.stringify(perguntasProva));
        }

        $('#modalSelecaoConteudos .container-conteudos .item.row').on('click', function() {
            $(this).toggleClass('selected');
        });

        function clicaConteudoBiblioteca(elemento) {
            $(elemento).toggleClass('selected');
        };

        $('#txtColecaoLivro').change(function(){
            $.ajax({
                    url: '{{url('/biblioteca/conteudosAjax')}}',
                    type: 'post',
                    data: {idTipo: 21, _token:'{{ csrf_token() }}', colecao:$(this).val()},
                    success: function (data) {
                        $('.container-conteudos').html(data);
                    }
                });
        });
        $('#modalSelecaoConteudos #txtTipo').change(function(){
            idTipoConteudo = $(this).val();
            if(idTipoConteudo == 21)
            {
                $('.container-conteudos').html('Nenhuma coleção de livro selecionada.');
                $.ajax({
                    url: '{{url('/colecao_livro/colecoesLivroAjaxOption')}}',
                    type: 'post',
                    data: {_token:'{{ csrf_token() }}'},
                    success: function (data) {
                        $('#txtColecaoLivro').html(data);
                    }
                });

                $('#txtColecaoLivro').show();
            }
            else
            {
                $('#txtColecaoLivro').hide();
            }

            if(idTipoConteudo != 21){
                $.ajax({
                    url: '{{url('/biblioteca/conteudosAjax')}}',
                    type: 'post',
                    data: {idTipo: $(this).val(), _token:'{{ csrf_token() }}'},
                    success: function (data) {
                        $('.container-conteudos').html(data);
                    }
                });
            }

        });

        $('#modalSelecaoConteudos').on('hide.bs.modal', function () {
            $('#modalSelecaoConteudos .container-conteudos input:checkbox').removeAttr('checked');
            $('#modalSelecaoConteudos .container-conteudos .item').removeClass('selected');
            $('#txtColecaoLivro').hide();
            $('.container-conteudos').html('');
        });

        var count = 2;
        cloneform1 = $('.vf').html();
        $(document).on('click', '.remDiv, #addDiv1', function(e) {
            count = (count + 1);
            thisClass = e.target.className;
            thisClass == 'remDiv' ?
                ($('.' + thisClass).length > 1 ?
                    $(this).closest('.row').prev().add($(this).closest('.row')).remove() : 0) :
                $('.vf').append(
                    ' <div class="col-md-12  " id="verdadeirofalso">\n' +
                    '<input type="radio" data-width="150" data-height="-20" name="verdadeirofalso[]" value="[' + count + ']" checked data-toggle="toggle" data-on="Verdadeira" data-off="Falso" data-onstyle="success" data-offstyle="danger">\n' +
                    '</div>\n' +
                    '<div class="md-form mb-4 blue-textarea active-blue-textarea">\n' +
                    '<h12> Afirmação</h12>\n' +
                    '<textarea id="afirmacao1" name="afirmacao[]" class="md-textarea form-control" rows="2" style="resize: none"></textarea>\n' +
                    '</div>');
        });

        $('#modalSelecaoConteudos #txtPesquisa').keyup(function(){
            var mySearch = $(this).val();
            var myTipoSelected = $('#txtTipo').val();

            $('.container-conteudos .item').hide();

            if(myTipoSelected == '') {
                $('.container-conteudos .item:contains("'+mySearch+'")').not('.not-found').show();
            } else {
                $('.container-conteudos .item[data-tipo="'+myTipoSelected+'"]:contains("'+mySearch+'")').not('.not-found').show();
            }

            if($('.container-conteudos .item').is(':visible')) {
                $('.container-conteudos .item.row.not-found').hide();
            } else {
                $('.container-conteudos .item.row.not-found').show();
            }
         });

        //:contains case-insensitive
        $.expr[":"].contains = $.expr.createPseudo(function(arg) {
            return function( elem ) {
                return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
            };
        });

    //Mudar depois, incluir no JS externo
    $( document ).ready(function()
    {
        //Função para mostrar apenas os anos de etapa
        $('.ciclo_id').change(function(){
            ciclo = $(this).val();
            const newLocal = '{{ route('gestao.searchcicloetapa')}}';
            $.ajax({
                url: newLocal,
                type: 'GET',
                dataType: 'json',
                data: {ciclo: ciclo},
                success: function (data) {
                    $('.cicloetapa_id').html(data);
                },
                    error: function (data) {
                        console.log(data);
                }
            });
        });

        //Função para mudar o nome do autor, se for instituição o nome fica editora
        //Se for diferente pega o nome do usuário logado
        $('#instituicao_id').change(function(){
            instituicao = $(this).val();
            if(instituicao == 1){
                $('#autor').val("Editora <?php echo $appName ?>");
            }else{
                $('#autor').val("<?php echo $nome; ?>");
            }

            //Mostra escolas conforme a seleção de instituição
            const buscaEscola = '{{ route('gestao.search_escola')}}';
            $.ajax({
                url: buscaEscola,
                type: 'GET',
                dataType: 'json',
                data: {id: instituicao},
                success: function (data) {
                    //limpa os campos
                    $('#escola_id').empty();
                    //Se retornar algum registro monta o select
                    if(data.success){
                        var data = data.success;

                        data.forEach(myFunction);

                        function myFunction(item, index) {
                            document.getElementById("escola_id").innerHTML += '<option value="'+ item.id+'">'+ item.titulo+'</option> ';
                        }
                    }else{
                            document.getElementById("escola_id").innerHTML += '<option value="">Nenhum registro encontrado!</option> ';
                        }

                    },
                error: function (data) {
                    console.log(data);
                }
            });
        });
    });

</script>

@endsection
