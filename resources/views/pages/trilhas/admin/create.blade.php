@extends('layouts.master')

@section('title', 'Nova trilha')


@section('content')

<!-- Encapsula o nome do usuário logado para uma função JS -->
@php
    $nome = Auth::user()->name;
    $appName = config('app.name');
@endphp

<!-- Modal Novo Curso-->

<style>
    header {
        padding: 154px 0 100px;
    }

    @media (min-width: 992px) {
        header {
            padding: 156px 0 100px;
        }
    }

    .capa-curso {
        min-height: 160px;
        border-radius: 10px 10px 0px 0px;
        background-image: url('/jpiaget-web/public/images/default-cover.jpg');
        background-size: cover;
        background-position: 50% 50%;
        background-repeat: no-repeat;
    }

    .input-group input.text-secondary::placeholder {
        color: #989EB4;
    }
    .form-control {
        color: #525870;
        font-weight: bold;
        font-size: 16px;
        border: 0px;
        border-radius: 8px;
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.16);
    }

    .form-control::placeholder {
        color: #B7B7B7;
    }

    .custom-select option:first-child {
        color: #B7B7B7;
    }

    input[type=range]::-webkit-slider-thumb {
        -webkit-appearance: none;
        border: 0px;
        height: 20px;
        width: 20px;
        border-radius: 50%;
        background: #525870;
        cursor: pointer;
        margin-top: 0px;
        /* You need to specify a margin in Chrome, but in Firefox and IE it is automatic */
    }

    input[type=range]::-webkit-slider-runnable-track {
        width: 100%;
        height: 36px;
        cursor: pointer;
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.16);
        background: var(--primary-color);
        border-radius: 90px;''
        border: 8px solid #E3E5F0;
    }
</style>

<main role="main" class="">
    <div class="container trilhas mt-4">

        <div class="col-12 mb-0 title pl-0">
            <h2>Trilhas</h2>
        </div>
        <nav aria-label="breadcrumb" class="bg-transparent position-relative">
            <ol class="breadcrumb p-0 pb-3 my-4  w-100 bg-transparent border-bottom">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('gestao.trilhas.listar') }}">
                        <i class="fas fa-chevron-left mr-2"></i>
                        <span>Minhas trilhas</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Adicionar trilha</li>
            </ol>
        </nav>


        <form id="formNovaTrilha" action="{{ route('gestao.trilhas.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 col-md-5 mb-3 mt-5 mt-sm-0">
                    <div class="col-12 bg-white rounded-10 pb-2">


                        <div class="col-12 pl-0 pr-0 mt-2">
                            <div class="row">
                                <div class="col-8 bg-white pb-2">
                                    <h5 class="modal-title text-dark font-weight-bold mt-3 text-left mb-2 ">{{ucfirst($langCursoP)}}</h5>
                                </div>
                                <div class="col-4 bg-white pb-0 text-right pt-3">

                                    <!--

                                    <button type="button" class="btn btn-master" data-toggle="modal" data-target="#modalNovoCurso">
                                        + Novo Curso
                                    </button>

                                    -->
                                </div>
                            </div>

                            <div class="input-group bg-white box-shadow rounded-10 mb-3">
                                <input type="text" id="search-text" class="form-control border-0 text-secondary py-0 mx-1 text-truncate" placeholder="Digite o nome do {{$langCurso}} que está procurando" style="font-size: 14px;" maxlength="50">
                                <div class="input-group-append border-0" onclick="filtrarCursos();">
                                    <button type="button" class="btn btn-link" id="search">
                                        <i class="fas fa-search fa-xs fa-fw text-secondary"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de cursos -->
                        <div class="col-12 pl-0 box-cursos-add {{ Auth::user()->permissao == 'Z' ? 'box-cursos-admin' : 'box-cursos'}} row-card">
                        </div>

                    </div><!-- col-12-->

                </div><!-- Fim modal adicionar album-->

                <div class="col-12 col-md-7 mb-4 mb-sm-0">
                    <div class="bg-white rounded-10">
                        <div class="col-12 d-flex justify-content-center">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title text-dark font-weight-bold">Nova trilha</h5>
                            </div>
                        </div>
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-12 col-lg-12">

                                    <!--  row 1 -->

                                    <div class="row">
                                        <div class="col-12 col-md-8">
                                            <div class="borderless-col form-group mb-3">
                                                <input type="text" class="form-control font-weight-bold" name="titulo" value="{{ old('titulo') }}" placeholder="Título da trilha" maxlength="150" required>
                                                <input type="hidden" name="tcursos" value="0" id="tcursos">
                                            </div>
                                            <!--
                                            <div class="d-block">
                                                <div class="form-group mb-3">
                                                    <textarea class="form-control" name="descricao" id="descricao" rows="3" maxlength="1000" tituloplaceholder="Descrição breve da trilha" style="resize: none">{{ old('descricao') }}</textarea>
                                                </div>
                                            </div>
                                        -->
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label for="capa" id="divFileInputCapa" class="file-input-area input-capa text-center d-flex align-items-center justify-content-center">
                                                <input type="file" class="custom-file-input" id="capa" name="capa" value="{{ old('capa')}}" style="top: 0px;height:  100%; width:100%; position:  absolute;left:  0px; backgroun-size=cover;" accept="image/jpg, image/jpeg, image/png" oninput="mudouArquivoCapa(this);" required>

                                                <h6 id="placeholder">
                                                    <i class="fas fa-camera fa-2x d-block mb-2 w-100"></i>
                                                    Escolher capa
                                                </h6>
                                                </h5>
                                            </label>
                                        </div>
                                    </div>
                                    <!--fim row 1 -->

                                    <!--  row 2 -->

                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <!--<div class="{{ Auth::user()->permissao == 'Z' ? 'col-12 d-flex justify-content-center form-group' : 'd-none'}}">-->
                                            <div class="form-group mb-3 mt-3">
                                                @if(Auth::user()->permissao == "G" || Auth::user()->permissao == "Z" ||
                                                Auth::user()->privilegio_id == 2 || Auth::user()->privilegio_id == 1)
                                                <label for="instituicao_id">Instituição</label>
                                                <select class="custom-select form-control" onchange="mudaEscola(this)" name="instituicao_id" id="instituicao_id" required>
                                                    @foreach($instituicoes as $instituicao)
                                                    <option value="{{$instituicao->id}}" {{ $instituicao->id == 1 ? 'selected' : ''}}>
                                                        {{$instituicao->titulo}}</option>
                                                    @endforeach
                                                </select>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <!--<div class="{{ Auth::user()->permissao == 'Z' ? 'col-12 d-flex justify-content-center form-group' : 'd-none'}}">-->
                                            <div class="form-group mb-3 mt-3">
                                                <label>Unidade Escolar<BR></label>
                                                <select class="custom-select form-control" name="escola_id" id="escola_id">
                                                    <option value="" selected>Selecione uma escola</option>
                                                    @foreach ($escolas as $escola)
                                                    <option @if(old('escola_id') == $escola->id) selected @endif value="{{ $escola->id }}">{{ ucfirst($escola->titulo) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!--<div class="col-12 col-md-6">
                                            <div class="form-group mb-3 mt-3">
                                                <label>Tags<BR></label>
                                                <input type="text" class="form-control" name="tag" id="tag" maxlength="255" value="{{ old('tag') }}" placeholder="Clique para digitar.">
                                            </div>
                                        </div> -->
                                    </div>
                                    <!--fim row 2 -->

                                    <!--  row 3 -->

                                    <div class="row">

                                        <!--<div class="col-12 col-md-6">
                                            <div class="form-group mb-3 mt-3">
                                                <label for="categoria">Visibilidade<BR></label>
                                                <select class="custom-select form-control" name="visibilidade" id="visibilidade" required>
                                                    @foreach($visibilidades as $visibilidade)
                                                    <option @if(old('visibilidade') == $visibilidade->id) selected @endif value="{{$visibilidade->id}}" {{ $visibilidade->id == 1 ? 'selected' : ''}}>{{$visibilidade->titulo}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    -->
                                    </div>
                                    <!--fim row 3 -->

                                    <!--  row 4 -->

                                    <div class="row">
                                       <!-- <div class="col-12 col-md-6">

                                            <div class="{{ Auth::user()->permissao == 'Z' ? 'col-12 d-flex justify-content-center form-group' : 'd-none'}}">
                                            <div class="form-group mb-3 mt-3">
                                                <label>Autor<BR></label>
                                                <input type="text" class="form-control" name="autor" id="autor" value="" readonly>


                                            </div>

                                        </div>
                                        -->
                                        <div class="col-12 col-md-6">

                                            <div class="form-group mb-3 mt-3">
                                                <label>Etapa<BR></label>
                                                <select class="custom-select form-control ciclo_id_nova_trilha" name="ciclo_id" id="ciclo_id" required>
                                                    <option value="" selected>Selecione uma etapa</option>
                                                    @foreach($etapas as $etapa)
                                                    <option @if(old('ciclo_id') == $etapa->id) selected @endif value="{{$etapa->id}}">{{$etapa->titulo}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            @if(old('cicloetapa_id'))
                                                <input type="hidden" name="_old_cicloetapa_id" value="{{ old('cicloetapa_id') }}">
                                            @endif
                                            <!--<div class="{{ Auth::user()->permissao == 'Z' ? 'col-12 d-flex justify-content-center form-group' : 'd-none'}}">-->
                                            <div class="form-group mb-3 mt-3">
                                                <label for="tipo">Ano<BR></label>
                                                <select class="custom-select form-control cicloetapa_id_nova_trilha" name="cicloetapa_id" id="cicloetapa_id" required>
                                                    <option disabled="disabled" value="" selected>Selecione um ano</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--fim row 4 -->

                                    <!--  row 5 -->

                                    <div class="row">


                                        <div class="col-12 col-md-6">
                                            <div class="form-group mb-3 mt-3">
                                                <label>Componente Curricular<BR></label>
                                                <select class="custom-select form-control" name="disciplina_id" id="disciplina_id" required>
                                                    <option value="" selected>Componente Curricular</option>
                                                    @foreach($disciplinas as $disciplina)
                                                    <option @if(old('disciplina_id') == $disciplina->id) selected @endif value="{{$disciplina->id}}">{{$disciplina->titulo}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--fim row 5 -->

                                    <div class="box-cursos-trilhas form-group mt-3" style="height:auto !important">
                                        <div class="row-card cursosAddInline  text-secondary" id="CursosSelect"></div>
                                    </div>

                                </div>
                                <!--fim col-12 -->
                            </div>
                            <!--fim row -->
                        </div>
                    </div> <!-- Fim modal descrição album-->

                    <div class="modal-footer px-0">
                        <div class="col-12 d-block d-sm-flex justify-content-between px-0">
                            <button type="button" data-dismiss="modal" onclick="window.location='{{ route('gestao.trilhas.listar') }}'" class="btn btn-cancelar col-auto col-sm-3">
                                Cancelar
                            </button>
                            <button id="adicionarBtn" type="submit" id="subm" class="btn btn-master col-auto col-sm-3">
                                Adicionar
                            </button>
                        </div>
                    </div>
                    <!--fim modal-footer -->
                </div>
                <!--row -->
                <div class="cursosIds"></div>
                <input type="hidden" id="ordem" value="0" />
        </form>
    </div>
    <!--row -->


    <div class="modal fade bd-example-modal-xl" id="modalNovoCurso" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="TituloModalCentralizado"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal-xl">
                    <!-- Modal da criacao de curso -->
                    <!-- Custom styles for this template -->

                    <main role="main" class="">

                        <div class="container">

                            <div class="row">

                                <div class="col-12 col-md-11 mx-auto mb-5 mt-4">

                                    <div class="title">
                                        <h2>Adicionar {{$langCursoP}}</h2>
                                    </div>
                                    <form id="formNovoCurso" class="w-100" action="{{ route('gestao.novo-curso') }}" method="post" enctype="multipart/form-data">
                                        <meta name="csrf-token" content="{{ csrf_token() }}">
                                        @csrf
                                        <input type="hidden" value="1" id="trilhaModal" name="trilhaModal">
                                        <div class="form-group m-0">
                                            <input type="text" class="px-4 py-3 form-control rounded-0 text-truncate shadow-none border-bottom" name="titulo" id="titulo" maxlength="150" placeholder="Clique para adicionar o título do {{$langCurso}}." style="font-size: 1.5rem;" required>
                                        </div>

                                        <div style="background-color: #FBFBFB;min-height: calc(100vh - 284px);">

                                            <div class="container-fluid">

                                                <div class="row">

                                                    <div class="col-12 col-lg-6">

                                                        <label for="capa" id="divFileInputCapa" class="file-input-area input-capa mt-3 mb-5 w-100 text-center">
                                                            <input type="file" class="custom-file-input" id="capa" name="capa" required="" style="top: 0px;height:  100%;position:  absolute;left:  0px;" accept="image/jpg, image/jpeg, image/png" oninput="mudouArquivoCapa(this);">

                                                            <h5 id="placeholder" class="text-white">
                                                                <i class="far fa-image fa-2x d-block text-white mb-2 w-100 w-100" style="vertical-align: sub;"></i>
                                                                CAPA DO {{strtoupper($langCurso)}}
                                                                <small class="text-uppercase d-block text-white small mt-2 mx-auto w-50" style="font-size:  70%;">
                                                                    (Arraste o arquivo para esta área)
                                                                    <br>
                                                                    JPG ou PNG
                                                                </small>
                                                            </h5>

                                                            </h5>
                                                        </label>

                                                        <div class="form-group mb-3 mt-3">
                                                            <label class="" for="descricao_curta">Descrição curta do {{$langCurso}}</label>
                                                            <textarea class="form-control" name="descricao_curta" id="descricao_curta" rows="1" maxlength="250" placeholder="Clique para digitar." required style="resize: none"></textarea>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label class="" for="descricao">Descrição do {{$langCurso}}</label>
                                                            <textarea name="descricao" id="descricao" class="summernote" maxlength="2000" placeholder="Clique para digitar." style="resize: none"></textarea>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="categoria">Categoria do {{$langCurso}}</label>
                                                            <select class="custom-select form-control" name="categoria" id="categoria" required>
                                                                <option disabled="disabled" value="0" selected>Selecione uma categoria</option>
                                                                {{-- @foreach ($categorias as $categoria) --}}
                                                                {{-- <option value="{{ $categoria->id }}">{{ ucfirst($categoria->titulo) }}</option> --}}
                                                                {{-- @endforeach --}}
                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="col-12 col-lg-6">

                                                        {{-- @if($getPrivilegio == 1 || $getPrivilegio == 2) --}}
                                                        <div class="form-group mb-3">
                                                            <label for="tipo">Tipo de {{$langCurso}}</label>
                                                            <select class="custom-select form-control" name="tipo" id="tipo" required>
                                                                <option disabled="disabled" value="1" selected>Selecione um tipo</option>
                                                                <option value="1">{{ucfirst($langCurso)}} padrão / Para alunos</option>
                                                                <option value="2">{{ucfirst($langCurso)}} para Professores / Gestores</option>
                                                            </select>
                                                        </div>
                                                        {{-- @endif --}}
                                                        {{-- @if($getinstituicao == 1) --}}
                                                        <div class="form-group mb-3">
                                                            <label for="unidadeEscolar">Unidade Escolar</label>
                                                            <select class="custom-select form-control" name="unidadeEscolar" id="unidadeEscolar" required>
                                                                <option disabled="disabled" value="0" selected>Selecione uma categoria</option>

                                                            </select>
                                                        </div>

                                                        @if(Auth::user()->permissao == "G" || Auth::user()->permissao == "Z" ||
                                                        Auth::user()->privilegio_id == 2 || Auth::user()->privilegio_id == 1)
                                                        <div class="form-group mb-3 mt-3">
                                                            <label for="instituicao_id">Instituição</label>
                                                            <select class="custom-select form-control" name="instituicao_id" id="instituicao_id" required>
                                                                <option dis abled="disabled" value="" selected>Selecione uma Instituição</option>
                                                                @foreach($instituicoes as $instituicao)
                                                                <option value="{{$instituicao->id}}">{{$instituicao->titulo}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @endif
                                                        {{-- @endif --}}

                                                        <div class="form-group my-3">
                                                            <label for="categoria">Visibilidade do {{$langCurso}}</label>
                                                            <select class="custom-select form-control" name="visibilidade" id="visibilidade" required>
                                                                <option disabled="disabled" value="" selected>Selecione uma visibilidade</option>
                                                                <option value="1">Compartilhado toda Instituição</option>
                                                                <option value="2">Compartilhado para a Escola</option>
                                                                <option value="3">Compartilhado por disciplina</option>
                                                                <option value="4">Compartilhado por etapa</option>
                                                                <option value="5">Compartilhado por Ano</option>
                                                                <option value="6">Compartilhado pelo docente</option>
                                                                {{-- @if(in_array($getPrivilegio,[1,2,4])) --}}
                                                                <option value="0">Oculto</option>
                                                                {{-- @endif --}}
                                                            </select>
                                                        </div>
                                                        {{-- @if($getinstituicao == 1) --}}
                                                        <div class="form-group mb-3">
                                                            <label class="" for="autorCurso">Autor 2</label>
                                                            <input type="text" class="form-control" maxlength="150" name="autorCurso" id="autorCurso" readonly="true" value="" aria-describedby="helpId">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="etapa">Etapa</label>
                                                            <select class="custom-select form-control" name="ciclo_id" id="ciclo_id" required>
                                                                <option disabled="disabled" value="" selected>Selecione uma etapa</option>
                                                                @foreach($etapas as $etapa)
                                                                <option value="{{$etapa->id}}">{{$etapa->titulo}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="" for="ano">Ano</label>
                                                            <div class="form-group mb-3 mt-3">
                                                                <label for="tipo">Ano</label>
                                                                <select class="custom-select form-control" name="cicloetapa_id" id="cicloetapa_id" required>
                                                                    <option disabled="disabled" value="" selected>Selecione um ano</option>
                                                                    @foreach($cicloEtapas as $cicloEtapa)
                                                                    <option value="{{$cicloEtapa->id}}">{{$cicloEtapa->titulo}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="componenteCurricular">Componente Curricular</label>
                                                            <select class="custom-select form-control" name="disciplina_id" id="disciplina_id" required>
                                                                <option disabled="disabled" value="" selected>Componente Curricular</option>
                                                                @foreach($disciplinas as $disciplina)
                                                                <option value="{{$disciplina->id}}">{{$disciplina->titulo}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        {{-- @endif --}}
                                                        {{-- @if($getinstituicao != 1) --}}
                                                        {{-- @if($getPrivilegio == 4 || $getPrivilegio == 2)
                                                                        <div class="form-group mb-3">
                                                                            <label class="" for="senha">Senha do {{$langCurso}} <small>(opcional)</small></label>
                                                                            <input type="text" class="form-control" name="senha" id="senha" maxlength="50" aria-describedby="helpId" placeholder="Clique para digitar.">
                                                                        </div>
                                                                        {{--   @endif --}}

                                                        <div class="form-group mb-3">
                                                            {{-- @if($getPrivilegio == 4 || $getPrivilegio == 2) --}}
                                                            <label class="" for="preco">Preço do {{$langCurso}} (Opcional)</label>
                                                            {{-- @else --}}
                                                            <label class="" for="preco">Preço do {{$langCurso}} (Opcional)</label>
                                                            {{-- @endif --}}
                                                            <input type="text" class="form-control money" name="preco" id="preco" maxlength="12" aria-describedby="helpId" placeholder="Clique para digitar.">
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label class="" for="preco">Link para checkout (Opcional)</label>
                                                            <input type="text" class="form-control" maxlength="150" name="link_checkout" id="link_checkout" aria-describedby="helpId" placeholder="Clique para digitar.">
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label class="" for="preco">Identificador externo (Opcional)</label>
                                                            <input type="text" class="form-control" maxlength="150" name="identificador" id="identificador" aria-describedby="helpId" placeholder="Clique para digitar.">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="" for="periodo">Período do {{$langCurso}} (dias)</label>
                                                            <label class="float-right" id="lblPeriodo" for="periodo">1</label>
                                                            <input type="range" class="custom-range" min="1" max="366" value="0" name="periodo" id="periodo" oninput="mudouPeriodo(this);">
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label class="" for="vagas">Vagas do {{$langCurso}}</label>
                                                            <label class="float-right" id="lblVagas" for="vagas">1</label>
                                                            <input type="range" class="custom-range" min="1" max="101" value="0" name="vagas" id="vagas" oninput="mudouVagas(this);">
                                                        </div>
                                                        {{-- @endif --}}

                                                        <input id="rasunho" value="false" type="text" hidden>

                                                        <div class="">
                                                            <div class="row">
                                                                <div class="col-12 col-xl-7 mb-2">
                                                                    <!--    <button type="button" onclick="salvar(true);" class="btn btn-secondary btn-block font-weight-bold text-truncate">
                                                                                Salvar como rascunho
                                                                            </button> -->
                                                                </div>
                                                                <div class="col-12 col-xl-5">
                                                                    <button type="submit" onclick="salvar(true);" class="btn btn-primary btn-block font-weight-bold text-truncate">
                                                                        Salvar
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </form>

                                </div>


                            </div>

                        </div>

                    </main>

                </div>

            </div>
        </div>
    </div>

    <!-- Fim do body da modal criacao de curso -->



</main>

<!-- Encapsula o nome do usuário logado para uma função JS -->
@php $nome = Auth::user()->name; @endphp

@endsection

@section('bodyend')

<!-- Js externo -->
<script src="{{ config('app.cdn') }}/old/assets/js/pages/trilhas/trilhas.js"></script>

<!-- Summernote css/js -->
{{-- <link href="{{ config('app.local') }}/assets/css/summernote-lite-cerulean.min.css" rel="stylesheet"> --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/lang/summernote-pt-BR.min.js" crossorigin="anonymous"></script>
<!-- include summernote css/js -->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>

<script>
    $(document).ready(function() {

        // EtapaAno
        setTimeout(function() {
            if($('input[name="_old_cicloetapa_id"]').length) {
                $('.ciclo_id_nova_trilha').change();
                console.log('here');
                setTimeout(function() {
                    $('.cicloetapa_id_nova_trilha').val($('input[name="_old_cicloetapa_id"]').val());
                }, 300);
            }
        }, 500);


        /**
         * Verifica a cada 100ms se todos os campos required do formulário foram
         * preenchidos e habilita o botão de salvar.
         */
        var enableCreateButton = function() {
            var formValid = $('#formNovaTrilha')[0].checkValidity();
            if(!formValid) {
                $('#adicionarBtn').attr('disabled', 'disabled');
                return;
            }
            $('#adicionarBtn').attr('disabled', false);
        }
        setInterval(enableCreateButton, 100);

        $('#txtDatePicker').datepicker({
            weekStart: 0,
            language: "pt-BR",
            daysOfWeekHighlighted: "0,6",
            autoclose: true,
            todayHighlight: true
        });

        $('.money').mask('#.##0,00', {
            reverse: true
        });

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
                ['insert', ['hr', 'picture', 'video', 'link', /*'table',*/ 'image', 'doc']],
                ['misc', ['undo', 'redo', ]],
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

        //Função para mostrar apenas os anos de etapa
        $('#ciclo_id').change(function() {
            ciclo = $(this).val();
            const newLocal = '{{ route('gestao.searchcicloetapa')}}';
            $.ajax({
                url: newLocal,
                type: 'GET',
                dataType: 'json',
                data: {
                    ciclo: ciclo
                },
                success: function(data) {
                    $('#cicloetapa_id').html(data);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
    });

    function salvar(rascunho) {
        var isValid = true;

        $('#modalNovoCurso').each(function() {
            if (($(this).val() === '' || $(this).val() == null) && $(this).attr('required')) {
                console.log(this);

                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Preencha todos os campos!'
                })

                $(this).focus();

                isValid = false;
            }
        });

        if (!isValid)
            return;

        $("#rascunho").val(rascunho);
        $("#divSalvando").removeClass('d-none');
        $("#formNovoCurso").submit();
        $('#modalNovoCurso').modal('hide');
    }

    function mudouPeriodo(el) {
        if (el.value > 0 && el.value <= 365) {
            $("#lblPeriodo").text(el.value);
        } else {
            $("#lblPeriodo").text("Ilimitado");
        }
    }

    function mudouVagas(el) {
        if (el.value > 0 && el.value <= 100) {
            $("#lblVagas").text(el.value);
        } else {
            $("#lblVagas").text("Ilimitado");
        }
    }

    //Correção do filtro de cursos
    function filtrarCursos() {

        var filtroCurso = $("#search-text").val();
        var excetoCurso = [];
        // var escola = $("#escola_select").val();
        const newLocal = '{{ route('gestao.search-cursos')}}';

        $("[name='curso_id[]']").each(function() {
            excetoCurso.push($(this).val());
        });

        $.ajax({
            url: newLocal,
            type: 'GET',
            data: {
                filtro: filtroCurso,
                exceto: excetoCurso
            },
            dataType: 'json',
            success: function(_response) {
                $('.box-cursos-add').empty();

                if (_response == 0) {
                    let htmlCurso = `<div class="card-body w-100 d-flex align-items-center">
                                            <div class="col-12 align-middle handle">
                                                <h5 class="curso-title text-center">{{ucfirst($langCurso)}} não encontrado</h5>
                                            </div>
                                        </div>`;
                    $('.box-cursos-add').append(htmlCurso);
                } else {

                    // CRIA OS CARDS DE CURSO
                    for (const key in _response) {

                        let htmlCurso = `
                                <div class="card removeContainerCurso_${_response[key].id} mb-3">
                                    <div class="card-body w-100 d-flex align-items-center">
                                        <div class="col-3 capa-item h-100 handle"
                                        style="background-image: url('{{ config('app.cdn') }}/uploads/cursos/capas/${_response[key].capa}');'">
                                        </div>
                                        <div class="col-7 align-middle handle" data-toggle="tooltip" data-placement="top" title="${_response[key].titulo}">
                                            <h5 class="curso-title text-truncate">${_response[key].titulo}</h5>
                                        </div>
                                        <div style="cursor: pointer;" class="addCurso col-2 text-right" onclick="adicionarCurso(this.id)"
                                            id="${_response[key].id}" data-id="${_response[key].titulo}">
                                            <i class="fas fa-plus fa-lg text-primary"></i>
                                        </div>

                                    </div>
                                </div>`;
                        $('.box-cursos-add').append(htmlCurso);
                    }
                }
                /** Fim da criação de cards */
            }
        });
    }

        //Função para mudar o nome do autor, se for instituição o nome fica editora
    //Se for diferente pega o nome do usuário logado
    function mudaEscola(){

        var instituicao = document.getElementById("instituicao_id").value;

        if (instituicao == 1) {
            $('#autor').val("Editora <?php echo $appName ?>");
        } else {
            $('#autor').val("<?php echo $nome; ?>");
        }
        // Segunda parte do codigo para quando selecionar instituicao filtrar as escolas
        const newLocal = '{{ route('gestao.search_escola')}}';
        $.ajax({
            url: newLocal,
            type: 'GET',
            dataType: 'json',
            data: {
                id: instituicao
            },
            success: function(data) {
                //Limpa o campo de escolas
                document.getElementById("escola_id").innerHTML ='';
                // recupera o json da pesquisa escolas
                var list_escolas = data.success;

                list_escolas.forEach(listaEscolas);

                function listaEscolas(item, index) {
                    document.getElementById("escola_id").innerHTML += '<option value="'+ item.id+'">'+item.titulo+'</option>';
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }
</script>

@endsection
