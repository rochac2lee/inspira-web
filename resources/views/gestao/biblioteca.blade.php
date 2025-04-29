@extends('layouts.master')

@section('title', 'Gestão de conteúdo')

@section('headend')

    <!-- Custom styles for this template -->
    <style>

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

        .card
        {
            display: flex;
            flex-direction: row;
            padding: 6px;
            border-radius: 5px;
            shadow: 0px 1px 3px rgba(0, 0, 0, 0.16);
            background-color: #FFFFFF;
        }

        .card .form-check-inline{
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .card .form-check-inline input{
            width: 15px;
            height: 15px;
        }
        body.dark-mode .card
        {
            background-color: #1F212E;
        }

        .btn-group-toggle .btn:not(:disabled):not(.disabled).active
        {

            border-bottom: 4px solid var(--primary-color);
        }
        .search-icon-background{
            color: transparent!important;
        }
        .search-input-biblioteca .form-control{
            background-color: transparent!important;
        }
        .search-input-biblioteca .search-div-icon{
            padding:0!important;
            background-color: transparent!important;
        }
        .search-input-biblioteca .search-div-icon .bg-white{
            background-color: transparent!important;
        }
        .search-input-biblioteca .search-div-icon .bg-white span{
            margin:0!important;
        }
        .search-input-biblioteca form .search-div-input{
            box-shadow: none!important;
            border: 1px solid #ced4da;
        }
        .thumb-category{
            background-color: gray;
            margin: -6px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }
        .btn-drop-functions{
            position: absolute!important;
            bottom: 0;
            margin-right: 4px!important;
        }


    </style>

@endsection

@section('content')

<main role="main" class="">

    <div class="container">

        <div class="col-12 col-md-11 mx-auto pt-4">

        <div class="row">

            <div class="col-12 mb-3 title pl-0">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-2">
                        <div aria-label="breadcrumb">
                            <ol class="breadcrumb p-0 m-0 bg-transparent">
                                <li class="breadcrumb-item active">
                                    <small>Sala de estudos</small>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><small>Biblioteca</small></li>
                            </ol>
                        </div>
                        <h2>Biblioteca</h2>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3 pr-0">
                        <div class="form-group my-2">
                            <select id="disciplina" name="disciplina" class="form-control bg-transparent is-filter" id="exampleFormControlSelect1">
                              <option selected disabled value="">Componente curricular</option>
                               @foreach($disciplinas as $disciplina)
                                    <option value="{{$disciplina->id}}" @if(request('disciplina') == $disciplina->id) selected="selected" @endif>{{$disciplina->titulo}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-1 pr-0">
                        <div class="form-group my-2">
                            <select id="ciclo" name="ciclo" class="form-control bg-transparent is-filter" id="exampleFormControlSelect1">
                              <option selected disabled value="">Etapa</option>
                                @foreach($etapas as $etapa)
                                    <option value="{{$etapa->id}}" @if(request('ciclo') == $etapa->id) selected="selected" @endif>{{$etapa->titulo}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-1 pr-0">
                        <div class="form-group my-2">
                            <select id="ciclo_etapa" name="ciclo_etapa" class="form-control bg-transparent is-filter" id="exampleFormControlSelect1">
                              <option selected disabled value="">Ano</option>
                                @foreach($cicloEtapas as $cicloEtapa)
                                    <option value="{{$cicloEtapa->id}}" @if(request('ciclo_etapa') == $cicloEtapa->id) selected="selected" @endif>{{$cicloEtapa->titulo}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-2 py-2 pr-0">
                        <div class="form-group">
                            <select id="catalogo" name="catalogo" class="form-control bg-transparent is-filter" id="exampleFormControlSelect1">
                              <option selected disabled value="">Tipo de conteúdo</option>
                              <option value="1" @if(request('catalogo') == 1) selected="selected" @endif>Documentos</option>
                              <option value="2" @if(request('catalogo') == 2) selected="selected" @endif>Audios</option>
                              <option value="3" @if(request('catalogo') == 3) selected="selected" @endif>Vídeos</option>
                              <option value="4" @if(request('catalogo') == 4) selected="selected" @endif>Slides</option>
                              <option value="5" @if(request('catalogo') == 5) selected="selected" @endif>Transmissões</option>
                              <option value="6" @if(request('catalogo') == 6) selected="selected" @endif>Uploads</option>
                              <option value="7" @if(request('catalogo') == 7) selected="selected" @endif>Dissertativas</option>
                              <option value="8" @if(request('catalogo') == 8) selected="selected" @endif>Quizz</option>
                              <option value="9" @if(request('catalogo') == 9) selected="selected" @endif>Avaliações</option>
                              <option value="10" @if(request('catalogo') == 10) selected="selected" @endif>Entregáveis</option>
                              <option value="11" @if(request('catalogo') == 11) selected="selected" @endif>Apostilas</option>
                              <option value="15" @if(request('catalogo') == 15) selected="selected" @endif>PDF</option>
                              <option value="21" @if(request('catalogo') == 21) selected="selected" @endif>{{ucfirst($langDigital)}} Digital</option>
                              <option value="22" @if(request('catalogo') == 22) selected="selected" @endif>Documentos Oficiais</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-3 search-input-biblioteca mt-2">
                        @include('components.search-input', ['placeholder' => 'Procurar conteúdo...'])
                    </div>
                </div>
            </div>

            <div class="col-12 px-0 mb-4">

                <div class="row">
                    <div class="col-sm-12 col-md-8 col-xl-9 my-auto">
                        {{-- @include('components.search-input', ['placeholder' => 'Procurar conteúdo...']) --}}
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-3 mt-3 mt-sm-0 text-right">
                        {{-- @if($Privilegios == 2 || $Privilegios == 1)
                            <button type="button" data-toggle="modal" data-target="#divModalTiposConteudo" class="btn btn-block btn-primary text-truncate text-uppercase d-flex align-items-center justify-content-center font-weight-bold mr-3 mb-2 mb-sm-0 h-100">
                                <i class="fas fa-plus mr-2"></i>
                                Novo conteúdo
                            </button>
                        @endif --}}

                        <div class="dropdown col-sm-12">
                            <a class="btn btn-primary dropdown-toggle toggle-color-primary w-100" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-plus mr-2"></i>
                                Criar novo conteúdo
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <div class="d-flex bd-highlight">
                                    <div class="p-2 flex-fill bd-highlight">
                                        <a class="dropdown-item pr-1" href="#" onclick="showNovoConteudo(1)"><i class="fas fa-font"></i>&nbsp; Texto</a>
                                        <a class="dropdown-item pr-1" href="#" onclick="showNovoConteudo(2)"><i class="fas fa-podcast"></i>&nbsp; Áudio</a>
                                        <a class="dropdown-item pr-1" href="#" onclick="showNovoConteudo(3)"><i class="fas fa-video"></i>&nbsp; Vídeo</a>
                                        <a class="dropdown-item pr-1" href="#" onclick="showNovoConteudo(4)"><i class="fas fa-file-powerpoint"></i>&nbsp; Slide</a>
                                        <a class="dropdown-item pr-1" href="#" onclick="showNovoConteudo(15)"><i class="fas fa-file-pdf"></i>&nbsp; PDF</a>
                                        <a class="dropdown-item pr-1" href="#" onclick="showNovoConteudo(6)"><i class="fas fa-upload"></i>&nbsp; Arquivo</a>
                                        <a class="dropdown-item pr-1" href="#" onclick="showNovoConteudo(5)"><i class="fas fa-broadcast-tower"></i>&nbsp; Transmissão</a>
                                    </div>
                                    <div class="p-2 flex-fill bd-highlight">
                                        <a class="dropdown-item pr-1" href="#" onclick="showNovoConteudo(22)"><i class="fas fa-file-alt"></i>&nbsp; Documentos Oficiais&nbsp;&nbsp;</a>
                                        <a class="dropdown-item pr-1" href="#" onclick="showNovoConteudo(7)"><i class="fas fa-comment-alt"></i>&nbsp; Dissertativa</a>
                                        <a class="dropdown-item pr-1" href="#" onclick="showNovoConteudo(8)"><i class="fas fa-list-ul"></i>&nbsp; Quiz</a>
                                        {{-- @if(config('app.name') == "jpiaget") --}}
                                        <a class="dropdown-item pr-1" href="#" onclick="showNovoConteudo(21)"><i class="fas fa-book-open"></i>&nbsp; Livro Digital</a>
                                        {{-- @endif --}}
                                        <a class="dropdown-item pr-1" href="#" onclick="showNovoConteudo(12)"><i class="fas fa-leaf"></i>&nbsp; Descubra Palavra</a>
                                        <a class="dropdown-item pr-1" href="#" onclick="showNovoConteudo(10)"><i class="fas fa-arrow-circle-up"></i>&nbsp; Entregável</a>
                                        <a class="dropdown-item pr-1" href="#" onclick="showNovoConteudo(106)"><i class="fas fa-video"></i>&nbsp; Ação Destaque</a>
                                    </div>
                            </div>
ß
                                </div>
                        </div>
                    </div>

                </div>

                @if(Request::has('pesquisa'))
                    @include('components.search-response', ['pesquisa' => Request::get('pesquisa')])
                @endif

            </div>

            <div class="w-100">

                @if(Request::has('pesquisa') &&
                    count($conteudos)  == 0 &&
                    count($aplicacoes)  == 0)
                    <div class="row">
                        <div class="col-12">
                            <h4 class="w-100 text-center my-2">
                                <span class="font-weight-bold text-bluegray">Infelizmente não encontramos resultados para sua busca.</span>
                            </h4>
                            <div class="my-3">
                                <h5 class="w-100 text-center my-2">
                                    <span class="font-weight-normal text-bluegray align-middle">Recomendamos ajustar sua busca. Aqui estão algumas ideias:</span>
                                </h5>
                                <div class="d-flex justify-content-center pt-3">
                                    <ul class="no-result-page--idea-list--3YX3z">
                                        <li>Verifique se todas as palavras estão com a ortografia correta.</li>
                                        <li>Tente usar termos de pesquisa diferentes.</li>
                                        <li>Tente usar termos de pesquisa mais genéricos.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(count($aplicacoes) > 0)
                    <div class="row my-4">

                        <div class="col-12 mb-3 mb-md-0">
                            <h4 class="my-2 w-100 title">
                                <i class="fas fa-gamepad small-2 align-middle mr-2"></i>
                                <span class="font-weight-bold align-middle">Jogos</span>
                            </h4>
                        </div>

                    </div>
                    <!-- END HEADER -->

                    <div class="py-2">
                        <div class="row">
                            @foreach ($aplicacoes as $aplicacao)

                                <div id="divAplicacao{{ $aplicacao->id }}" class="col-12 col-sm-6 col-lg-4 mb-3">
                                    <div class="card rounded text-decoration-none h-100 border-0 shadow-sm">

                                        <div class="card-img-auto bg-dark h-100 rounded-0" style="flex: 0.6;background-image: url('{{ config('app.cdn') . '/uploads/aplicacoes/capas/' .  $aplicacao->capa }}');background-size: cover;background-position: 50% 50%;background-repeat: no-repeat;min-height: 115px;">
                                        </div>
                                        <div class="py-3 px-4 h-100 text-truncate" style="color: #525870;font-size: 16px;flex: 1;">
                                            <span class="d-block mb-2 text-truncate">
                                                {{ ucfirst($aplicacao->titulo) }}
                                            </span>
                                            <span class="d-block font-weight-bold" style="">
                                                <i class="fas fa-gamepad fa-fw mr-1"></i>
                                                Jogo
                                            </span>
                                        </div>

                                        <button class="btn btn-no-hover float-right p-2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: absolute;right: 0px;margin-right:0;top:0;">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a target="_blank" href="{{ route('hub.aplicacao', ['idAplicacao' => $aplicacao->id]) }}" class="btn btn-link dropdown-item">
                                                Abrir aplicação
                                            </a>
                                            @if(\HelperClass::perfilEditarAdmin($aplicacao->user_id))
                                            <button type="button" onclick="editarAplicacao({{ $aplicacao->id }})" class="btn btn-link dropdown-item">
                                                Editar aplicação
                                            </button>
                                            <button type="button" onclick="excluirAplicacao({{ $aplicacao->id }});" class="btn btn-link text-danger dropdown-item">
                                                Excluir aplicação
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>

            <!-- END SECTION APLICACOES -->
            @foreach ($conteudo_tipo as $key => $conteudos)
                @if(count($conteudos) > 0)
                    <section class="row">
                        <div class="w-100 title pb-1 mb-4">
                            <h4 class="my-2">
                                <i class="fas {{ $key == 1 ? 'fa-file-pdf' : $conteudos->first()->tipo_icon }} small-2 mr-2"></i>
                                <span class="font-weight-bold">

                                    @if($key == 1)
                                      Documentos
                                    @elseif($key == 21 && config('app.name') == "jpiaget")
                                    {{ucfirst($langDigital)}} Digital
                                    @else
                                      {{ $conteudos->first()->tipo_nome }}
                                    @endif

                                 </span>
                            </h4>
                        </div>

                        <div class="col-12 py-2 px-0">

                            <div class="row">
                                @foreach ($conteudos as $conteudo)

                                @php

                                   $vinculo =  app(App\Http\Controllers\GestaoController::class)->verificaVinculoConteudo( [$conteudo->id] ) ;

                                @endphp







                                    <div id="divConteudo{{ $conteudo->id }}" class="col-sm-12 col-md-4 col-lg-2 mb-3">
                                        <div class="card  d-block w-100 rounded-sm text-decoration-none shadow-sm">

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                            </div>

                                            <div class="thumb-category d-flex justify-content-center py-5">
                                             @if($conteudo->tipo == 21)
                                             <img src="{{asset('/storage/livrodigital').'/'.$conteudo->id. '/'.'1.jpg'}}" class="w-auto" style="width: 190px !important;
                                             height: 140px!important" >
                                                 @else
                                                 <img src=""  class="w-auto"  style="width: 190px !important;
                                                 height: auto;"/>
                                                @endif
                                                 </div>



                                            <div class="card-body px-2 pt-3 pb-2 text-truncate">
                                                <span class="w-100" data-toggle="tooltip" data-placement="right" title="{{ $conteudo->titulo }}" >
                                                    <i class="fas {{ $conteudo->tipo_icon }} fa-fw mr-1"></i> {{ ucfirst($conteudo->titulo) }}
                                                    <br>
                                                    <small>Data: {{ $conteudo->created_at->format('d/m/Y') }}</small>
                                                    <!-- <small>hello</small> -->
                                                    @if($vinculo > 0)
                                                    <i class="fas fa-link fa-fw mr-1 mt-2 float-right" title="Conteúdo Vinculado"></i>
                                                    @endif

                                                    <small class="d-block">Etapa: {{ $conteudo->ciclo_nome }}</small>
                                                    <small class="d-block">Ano: {{ $conteudo->cicloetapa_nome }}</small>
                                                    <small class="d-block">Disciplina: {{ $conteudo->disciplina_nome }}</small>
                                                </span>

                                                 <span class="d-block font-weight-bold text-truncate mt-4 mb-2" style="">
                                                    <i class="fas {{ $conteudo->tipo_icon }} fa-fw mr-1"></i>

                                                    @if($key == 21 && config('app.name') == "jpiaget")
                                                    {{ucfirst($langDigital)}} Digital
                                                    @else
                                                        {{ $conteudo->tipo_nome }}
                                                    @endif

                                                </span>
                                            </div>

                                            <button class="btn btn-no-hover btn-drop-functions text-gray float-right p-2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: absolute;right: 0px;margin-right: 10px;">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu">

                                                <button type="button" onclick="{{ $key == 21 ? 'visualizarConteudoLivro('.$conteudo->id.')' : 'visualizarConteudo('.$conteudo->id.')' }}" class="btn btn-link dropdown-item">
                                                    Abrir conteúdo
                                                </button>

                                                @if($vinculo == 0)
                                                    @if(\HelperClass::perfilEditarAdmin($conteudo->user_id))
                                                    <button type="button" onclick="editarConteudo({{ $conteudo->id }})" class="btn btn-link dropdown-item">
                                                        Editar conteúdo
                                                    </button>
                                                    <button type="button" onclick="excluirConteudo({{ $conteudo->id }});" class="btn btn-link text-danger dropdown-item">
                                                        Excluir conteúdo
                                                    </button>
                                                    @endif
                                                @endif
                                            </div>

                                        </div>
                                    </div>

                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif
            @endforeach

        </div>

        <!-- Modal Tipo de Conteudo -->
        <div class="modal fade" id="divModalTiposConteudo" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg px-1 px-md-5 text-center" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <input type="hidden" id="abrirNovo">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>

                        <h4 class="mb-3 pt-3">
                            Criação de conteúdo
                        </h4>
                        <p>Escolha abaixo um tipo de conteúdo</p>

                        <div class="container-fluid">

                            <h5 class="mb-2 title text-left">
                                <i class="fas fa-book fa-lg fa-fw text-primary" style=""></i>
                                Conteúdo
                            </h5>

                            <div class="row mb-4">

                                <button onclick="showNovoConteudo(1)" class="btn btn-link text-decoration-none col-12 col-md-6 col-lg-4">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-font fa-fw fa-2x "></i>
                                        <br>
                                        Texto
                                    </div>
                                </button>
                                <button onclick="showNovoConteudo(2)" class="btn btn-link text-decoration-none col-12 col-md-6 col-lg-4">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-podcast fa-fw fa-2x"></i>
                                        <br>
                                        Áudio
                                    </div>
                                </button>
                                <button onclick="showNovoConteudo(3)" class="btn btn-link text-decoration-none col-12 col-md-6 col-lg-4">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-video fa-fw fa-2x"></i>
                                        <br>
                                        Vídeo
                                    </div>
                                </button>
                                <button onclick="showNovoConteudo(4)" class="btn btn-link text-decoration-none col-12 col-md-6 col-lg-4">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-file-powerpoint fa-fw fa-2x"></i>
                                        <br>
                                        Slide ou Documento
                                    </div>
                                </button>
                                <button onclick="showNovoConteudo(15)" class="btn btn-link text-decoration-none col-12 col-md-6 col-lg-4">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-file-pdf fa-fw fa-2x"></i>
                                        <br>
                                        PDF
                                    </div>
                                </button>
                                <button onclick="showNovoConteudo(6)" class="btn btn-link text-decoration-none col-12 col-md-6 col-lg-4">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-upload fa-fw fa-2x"></i>
                                        <br>
                                        Arquivo
                                    </div>
                                </button>
                                <button onclick="showNovoConteudo(5)" class="btn btn-link text-decoration-none col-12 col-md-6 col-lg-4">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-broadcast-tower fa-fw fa-2x"></i>
                                        <br>
                                        Transmissão
                                    </div>
                                </button>



                                <button onclick="showNovoConteudo(21)" class="btn btn-link text-decoration-none col-12 col-md-6 col-lg-4">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-file-alt fa-fw fa-2x"></i>
                                        <br>
                                        @if(config('app.name') != "jpiaget")
                                        {{ucfirst($langDigital)}}
                                        @else
                                        {{ucfirst($langDigital)}}
                                        @endif
                                          Digital
                                    </div>
                                </button>

                                <button onclick="showNovoConteudo(22)" class="btn btn-link text-decoration-none col-12 col-md-6 col-lg-4">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-file-alt fa-fw fa-2x"></i>
                                        <br>
                                        Documentos Oficiais
                                    </div>
                                </button>

                            </div>



                            <h5 class="mb-2 title text-left">
                                <i class="fas fa-gamepad fa-lg fa-fw text-primary" style=""></i>
                                Atividades
                            </h5>

                            <div class="row mb-4">

                                <button onclick="showNovoConteudo(7)" class="btn btn-link text-decoration-none col-12 col-md-6 col-lg-4">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-comment-alt fa-fw fa-2x"></i>
                                        <br>
                                        Dissertativa
                                    </div>
                                </button>

                                <button onclick="showNovoConteudo(8)" class="btn btn-link text-decoration-none col-12 col-md-6 col-lg-4">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-list-ul fa-fw fa-2x"></i>
                                        <br>
                                        Quiz
                                    </div>
                                </button>

                                <button onclick="showNovoConteudo(9)" class="btn btn-link text-decoration-none col-12 col-md-6 col-lg-4">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-stopwatch fa-fw fa-2x"></i>
                                        <br>
                                        Avaliação
                                    </div>
                                </button>

                                @if(config('app.name') == "jpiaget")

                                <button onclick="showNovoConteudo(11)" class="btn btn-link text-decoration-none col-12 col-md-6 col-lg-4">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-file-alt fa-fw fa-2x"></i>
                                        <br>
                                        {{ucfirst($langDigital)}} Digital
                                    </div>
                                </button>

                                @endif

                                <button onclick="showNovoConteudo(12)" class="btn btn-link text-decoration-none col-12 col-md-6 col-lg-4">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-leaf fa-fw fa-2x"></i>
                                        <br>
                                        Descubra a Palavra
                                    </div>
                                </button>

                            </div>

                            <h5 class="mb-2 title text-left">
                                <i class="fas fa-arrow-circle-up fa-lg fa-fw text-primary" style=""></i>
                                Entregável
                            </h5>

                            <div class="row mb-4">

                                <button onclick="showNovoConteudo(10)" class="btn btn-link text-decoration-none col">
                                    <div class="shadow-sm rounded link-primary text-center py-3  px-2 text-truncate" style="">
                                        <i class="fas fa-arrow-circle-up fa-fw fa-2x"></i>
                                        <br>
                                        Receber arquivo do aluno  (Até 20Mb PDF, DOC, PNG, JPG)</small>
                                    </div>
                                </button>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
        <!-- Fim modal Tipo de Conteudo -->

        <!-- Modal Novo Conteudo -->
        @include('gestao.conteudo.modal-novo-conteudo')
        <!-- Fim modal novo conteudo -->

        <!-- Modal Editar Conteudo -->
        @include('gestao.conteudo.modal-editar-conteudo')
        <!-- Fim modal editar conteudo -->

        <!-- Modal Visualizar Conteudo -->
        @include('gestao.conteudo.modal-visualizar-conteudo')
        <!-- Fim modal visualizar conteudo -->



        <!-- Modal Instruçoes aplicação -->
        <div class="modal fade" id="divModalInstrucoesAplicacao" tabindex="-1" role="dialog" aria-labelledby="divModalInstrucoesAplicacao" aria-hidden="true" style="z-index: 99999999; background: #000000c4;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="close ml-auto mr-2" onclick="$('#divModalInstrucoesAplicacao').modal('hide');">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <div class="modal-body border-0">
                        <h5 class="modal-title text-center mb-4">
                            Instruções para enviar aplicação
                        </h5>
                        <p class="text-justify">
                            Para enviar uma nova aplicação à plataforma você deve exportar a aplicação desenvolvida em unity para WebGL,
                            para facilitar o processo realize uma build dentro de uma pasta nomeada 'aplicacao',
                            desta maneira ao fim do processo de build a unity você devera conter uma pasta chamada 'aplicacao' e dentro dela uma pasta chamda 'Build'.
                            Você deve anexar um .zip dos arquivos que estão dentro da pasta 'Build'.
                        </p>
                        <p class="text-justify">
                            Os arquivos dentro da pasta 'Build', que estarão dentro do .zip anexado deverão se parecer com esta imagem:
                        </p>
                        <img src="{{ config('app.cdn') }}/images/unity-arquivos-exemplo.jpg" width="100%" height="auto" alt="" style="max-width: 230px;">
                        <p class="text-justify">
                            E o arquivo 'aplicacao.json' deve ter seu conteúdo parecido com a imagem a seguir:
                        </p>
                        <img src="{{ config('app.cdn') }}/images/unity-json-exemplo.jpg" width="100%" height="auto" alt="" style="max-width: 420px;">
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" onclick="$('#divModalInstrucoesAplicacao').modal('hide');" class="btn btn-primary">
                            Entendi
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fim modal Instruçoes aplicação -->

        <!-- Modal Editar Aplicacao -->
        <div class="modal fade" id="divModalEditarAplicacao" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-md px-1 px-md-5" role="document">
                <div class="modal-content bg-card">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <form id="formEditarAplicacao" method="POST" action="{{ route('gestao.aplicacao-salvar') }}" enctype="multipart/form-data" class="text-center px-3 shadow-none border-0">

                            @csrf

                            <div id="divEnviando" class="text-center d-none">
                                <i class="fas fa-spinner fa-pulse fa-3x text-primary mb-3"></i>
                                <h4>Enviando</h4>
                            </div>

                            <div id="divEditar" class="form-page">

                                <div id="page1" class="form-page">

                                    <h4 class="mb-4">Editar aplicação</h4>

                                    <input type="hidden" name="idAplicacao" value="">

                                    <label for="capa" id="divFileInputCapa" class="file-input-area input-capa bg-dark text-primary border border-primary mt-3 mb-5 w-100 text-center" style="">
                                        <input type="file" class="custom-file-input" id="capa" name="capa" style="top: 0px;height:  100%;position:  absolute;left:  0px;" accept="image/jpg, image/jpeg, image/png" oninput="mudouArquivoCapa(this);">

                                        <h6 id="placeholder" class="">
                                            <i class="far fa-image fa-2x text-primary d-block mb-2 w-100" style="vertical-align: sub;"></i>
                                            CAPA DA APLICAÇÃO
                                            <small class="text-uppercase text-primary d-block small mt-2 mx-auto w-50" style="font-size:  70%;">
                                                (Arraste o arquivo para esta área ou clique para alterar)
                                            </small>
                                            <small class="text-uppercase text-primary d-block small mt-2 mx-auto w-50" style="font-size:  70%;">
                                                Opcional
                                            </small>
                                        </h6>
                                        <h5 id="file-name" class="float-left text-primary d-none font-weight-bold" style="margin-top: 145px;margin-left:  10px;margin-bottom:  20px;">
                                        </h5>
                                    </label>

                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtTituloEditarAplicacao">Título da aplicação</label>
                                        <input type="text" class="form-control" maxlength="150" name="titulo" id="txtTituloEditarAplicacao" maxlenght="80" placeholder="Clique para digitar." required>
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtDescricaoEditarAplicacao">Descrição da aplicação <small>(opcional)</small></label>
                                        <textarea class="form-control" maxlength="1000" name="descricao" id="txtDescricaoEditarAplicacao" rows="3" placeholder="Clique para digitar." style="resize: none"></textarea>
                                    </div>

                                    <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#divModalInstrucoesAplicacao">
                                        Instruções para envio de aplicação
                                    </button>

                                    <label for="file" class="custom-file-area btn btn-outline border-thin text-primary bg-transparent d-block py-2 mt-4 mb-3 text-center text-truncate" style="">
                                        <input type="file" class="custom-file-input" id="arquivo" name="arquivo" accept="application/zip" oninput="enviouArquivo(this);">
                                        <h6 class="m-0 text-truncate" id="placeholder">
                                            <i class="fas fa-plus fa-fw mr-2"></i>
                                            CLIQUE PARA ANEXAR A APLICAÇÃO
                                        </h6>
                                        <h6 class="m-0 file-name text-truncate d-none">
                                            nome-do-arquivo.zip
                                        </h6>
                                    </label>

                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="cmbVisibilidadeEditarAplicacao">Visibilidade da aplicação</label>
                                        <select id="cmbVisibilidadeEditarAplicacao" name="visibilidade" required class="custom-select rounded">
                                            <option disabled>Selecione uma visibilidade</option>
                                            <option value="0">Não listado</option>
                                            <option value="1">Listado</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="cmbStatusEditarAplicacao">Status da aplicação</label>
                                        <select id="cmbStatusEditarAplicacao" name="status" required class="custom-select rounded">
                                            <option disabled>Selecione um status</option>
                                            <option value="0">Não publicado</option>
                                            <option value="1">Publicado</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="cmbLiberadaEditarAplicacao">Acesso da aplicação</label>
                                        <select id="cmbLiberadaEditarAplicacao" name="liberada" required class="custom-select rounded">
                                            <option disabled>Selecione o acesso</option>
                                            <option value="0">Acesso mediante permissão</option>
                                            <option value="1">Acesso liberado à todos</option>
                                        </select>
                                    </div>

                                    <div class="row mb-2">
                                        <button type="button" data-dismiss="modal" class="btn btn-cancelar mt-4 mb-0 col-4 ml-auto mr-4 font-weight-bold">Cancelar</button>
                                        <button type="button" onclick="salvarEdicaoAplicacao();" class="btn btn-primary mt-4 mb-0 col-4 ml-4 mr-auto font-weight-bold">Salvar</button>
                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
        <!-- Fim modal editar aplicação -->

    </div>

</main>

@endsection

@section('bodyend')

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>

    <!-- Summernote css/js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/lang/summernote-pt-BR.min.js" crossorigin="anonymous"></script>


    <script>

        $('#txtDatePicker').datepicker({
            weekStart: 0,
            language: "pt-BR",
            daysOfWeekHighlighted: "0,6",
            autoclose: true,
            todayHighlight: true
        });

        $( document ).ready(function()
        {
            $('.summernote').summernote({
                placeholder: "Clique para digitar.",
                lang: 'pt-BR',
                airMode: false,
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

            $('#divModalVisualizarConteudo').on('hidden.bs.modal', function () {
                $(".conteudo-tipo").empty();
            });

            $('#divModalNovoConteudo').on('hidden.bs.modal', function () {
                //$("#divModalTiposConteudo").modal('show');
            });

            $('#divModalTiposConteudo').on('hidden.bs.modal', function () {
                if ($("#divModalTiposConteudo #abrirNovo").val() == 1){
                    $("#divModalNovoConteudo").modal({ show: true, keyboard: false, backdrop: 'static'});
                    $("#divModalTiposConteudo #abrirNovo").val(null);
                }
            });

            /**
             * Filtros
             *
             * Utiliza o atributo "name" como o parâmetro na URL e o valor.
             * Idealmente, utilizaríamos uma classe pra representar os inputs de
             * filtro ao invés de selecionar nome por nome.
             */
            $('.is-filter').on('change', function() {
                var params = {};
                params[$(this).attr('name')] = $(this).val();
                return reloadWithQueryStringVars(params);
            });

            $('#clean-filters').on('click', function() {
                var url = [location.protocol, '//', location.host, location.pathname].join('');
                window.location.href = url;
            });

        });

        /**
         * Redireciona o usuário com parâmetros da URL
         */
        function reloadWithQueryStringVars (queryStringVars) {
            var existingQueryVars = location.search ? location.search.substring(1).split("&") : [],
                currentUrl = location.search ? location.href.replace(location.search,"") : location.href,
                newQueryVars = {},
                newUrl = currentUrl + "?";
            if(existingQueryVars.length > 0) {
                for (var i = 0; i < existingQueryVars.length; i++) {
                    var pair = existingQueryVars[i].split("=");
                    newQueryVars[pair[0]] = pair[1];
                }
            }
            if(queryStringVars) {
                for (var queryStringVar in queryStringVars) {
                    newQueryVars[queryStringVar] = queryStringVars[queryStringVar];
                }
            }
            if(newQueryVars) {
                for (var newQueryVar in newQueryVars) {
                    newUrl += newQueryVar + "=" + newQueryVars[newQueryVar] + "&";
                }
                newUrl = newUrl.substring(0, newUrl.length-1);
                window.location.href = newUrl;
            } else {
                window.location.href = location.href;
            }
        }

        function showNovoConteudo(tipo)
        {
            $("#divModalNovoConteudo [name='tipo']").val(tipo);
            aux = 0;
            if(tipo == 106){
                $("#colecaoDestaque").removeClass('d-none');
                aux = 106;
                tipo=3;
            }
            else{
                $("#colecaoDestaque").addClass('d-none');
            }
           // $("#divModalTiposConteudo #abrirNovo").val(1);
           // $("#divModalTiposConteudo").modal('hide');
            $("#divModalNovoConteudo").modal({ show: true, focus: false });
            $("#divModalNovoConteudo #divLoading").addClass('d-none');
            $("#divModalNovoConteudo #divLoading").addClass('d-none');
            $("#divModalNovoConteudo #divEnviando").addClass('d-none');
            $("#divModalNovoConteudo #divEditar").removeClass('d-none');

            $("#divModalNovoConteudo .tipos-conteudo .tipo").addClass('d-none');


            $("#divModalNovoConteudo .tipos-conteudo").find('#conteudoTipo' + tipo).removeClass('d-none');



            $("#divModalNovoConteudo [name='titulo']").val('');
            $("#divModalNovoConteudo [name='descricao']").val('');
            $("#divModalNovoConteudo [name='obrigatorio']").prop('checked', true);
            $("#divModalNovoConteudo [name='tempo']").val('');

            if(aux==106)
            {
                tipo = aux;
            }

            switch(tipo)
            {
                case 1:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo de texto");
                    $("#formNovoConteudo #txtConteudo").val(`<div style="color: #525870;font-weight: bold;font-size: 16px;"></div>`);
                break;
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
                    $("#lblTipoNovoConteudo").text("Novo conteúdo Livro Digital");
                break;
                case 15:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo PDF");
                break;
                case 12:
                    $("#lblTipoNovoConteudo").text("Descubra a Palavra");
                break;
                case 13:
                    $("#lblTipoNovoConteudo").text("Verdadeiro ou Falso");
                break;
                case 21:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo Livro Digital");
                break;

                case 22:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo Documento Oficial");
                break;
                case 106:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo ação destaque");
                    break;
                default:
                    $("#lblTipoNovoConteudo").text("Novo conteúdo de texto");
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
                case '15':
                    if ($("#divModalNovoConteudo #inputPDFNovoConteudo").val() == '' &&
                        $("#divModalNovoConteudo #txtPDFNovoConteudo").val() == ''
                    ) {
                        $('#divModalNovoConteudo #txtPDFNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                default:
                    break;
            }

            //if($('#divModalNovoConteudo .summernote').summernote('code') == '' && $("#divModalNovoConteudo .tipos-conteudo").find('#conteudoTipo1').hasClass('d-none') == false)
                //return;

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

        function editarConteudo(idConteudo)
        {
            $("#divModalEditarConteudo").modal({ keyboard: false, backdrop: 'static', focus: false });
            $("#divModalEditarConteudo #divLoading").removeClass('d-none');
            $("#divModalEditarConteudo #divEditar").addClass('d-none');
            $("#divModalEditarConteudo #divEnviando").addClass('d-none');

            $("#divModalEditarConteudo .form-page").addClass('d-none');
            $("#divModalEditarConteudo #page1").removeClass('d-none');

            $.ajax({
                url: appurl + '/gestao/conteudos/' + idConteudo + '/editar',
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
                            break;

                            case 2:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo de áudio");
                                if(_response.conteudo.temArquivo)
                                {
                                    $("#divVerArquivoAtual").removeClass('d-none');
                                    $("#btnVerArquivoAtual").attr('href', '{{ config('app.local') }}/play/conteudo/' + _response.conteudo.id + '/arquivo');
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
                                    $("#btnVerArquivoAtual").attr('href', '{{ config('app.local') }}/play/conteudo/' + _response.conteudo.id + '/arquivo');
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
                                    $("#btnVerArquivoAtual").attr('href', '{{ config('app.local') }}/play/conteudo/' + _response.conteudo.id + '/arquivo');
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
                                    $("#btnVerArquivoAtual").attr('href', '{{ config('app.local') }}/play/' + _response.conteudo.id + '/arquivo');
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
                                $("#divModalEditarConteudo #rdoEditAlternativa" + _response.conteudo.conteudo.correta).prop("checked", true);
                                $("#divModalEditarConteudo [name='conteudoQuizAlternativa1']").val(_response.conteudo.conteudo.alternativas[0]);
                                $("#divModalEditarConteudo [name='conteudoQuizAlternativa2']").val(_response.conteudo.conteudo.alternativas[1]);
                                $("#divModalEditarConteudo [name='conteudoQuizAlternativa3']").val(_response.conteudo.conteudo.alternativas[2]);
                                $("#divModalEditarConteudo [name='conteudoQuizDica']").val(_response.conteudo.conteudo.dica);
                                $("#divModalEditarConteudo [name='conteudoQuizExplicacao']").val(_response.conteudo.conteudo.explicacao);
                                $("#divModalEditarConteudo #questaoTipo8Id").val(_response.conteudo.questoes_id);
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
                                        $("#divModalEditarConteudo #rdoEditAlternativaMultiplaEscolha"+prova.correta).prop('checked', true);
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
                                            $("#divModalEditarConteudo #rdoEditAlternativaMultiplaEscolha"+questao.correta).prop('checked', true);
                                            $("#divModalEditarConteudo #txtQuizAlternativa1").val(questao.alternativas[0]);
                                            $("#divModalEditarConteudo #txtQuizAlternativa2").val(questao.alternativas[1]);
                                            $("#divModalEditarConteudo #txtQuizAlternativa3").val(questao.alternativas[2]);
                                        }
                                        adicionarNovaPerguntaProva('divModalEditarConteudo');
                                    }
                                }
                                $("#divModalEditarConteudo #btnAdicionarPergunta").text("Salvar pergunta");
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
                                    $("#divModalEditarConteudo #txtPerguntaQuiz").val(prova[0].pergunta);
                                    $("#divModalEditarConteudo [name='alternativaCorretaMultiplaEscolha']").prop('checked', false);
                                    $("#divModalEditarConteudo #rdoEditAlternativaMultiplaEscolha"+prova[0].correta).prop('checked', true);
                                    $("#divModalEditarConteudo #txtQuizAlternativa1").val(prova[0].alternativas[0]);
                                    $("#divModalEditarConteudo #txtQuizAlternativa2").val(prova[0].alternativas[1]);
                                    $("#divModalEditarConteudo #txtQuizAlternativa3").val(prova[0].alternativas[2]);
                                }
                            break;

                            case 10:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo entregável");
                                $("#formEditarConteudo #txtConteudoEntregavel").val(_response.conteudo.conteudo);
                            break;

                            case 11:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo Livro Digital");
                                $("#formEditarConteudo #txtConteudoApostila").summernote('code',_response.conteudo.conteudo);
                            break;

                            case 15:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo PDF");

                                if(_response.conteudo.temArquivo)
                                {
                                    $("#divVerArquivoAtual").removeClass('d-none');
                                    $("#btnVerArquivoAtual").attr('href', '{{ config('app.local') }}/play/conteudo/' + _response.conteudo.id + '/arquivo');
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
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo Livro Digital");
                                $("#formEditarConteudo #txtConteudoApostila").summernote('code',_response.conteudo.conteudo);
                            break;

                            case 22:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo PDF");

                                if(_response.conteudo.temArquivo)
                                {
                                    $("#divVerArquivoAtual").removeClass('d-none');
                                    $("#btnVerArquivoAtual").attr('href', '{{ config('app.local') }}/play/conteudo/' + _response.conteudo.id + '/arquivo');
                                    $("#divModalEditarConteudo [name='conteudoPDF']").val();
                                }
                                else
                                {
                                    $("#divVerArquivoAtual").addClass('d-none');
                                    $("#btnVerArquivoAtual").attr('href', '#');
                                    $("#divModalEditarConteudo [name='conteudoPDF']").val(_response.conteudo.conteudo);
                                }
                            break;

                            default:
                                $("#divModalEditarConteudo #lblTipoConteudo").text("Editando conteúdo livre");
                                $("#formEditarConteudo #txtConteudo").summernote('code',_response.conteudo.conteudo);
                            break;
                        }

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
                        $("#divModalEditarConteudo #inputPDFNovoConteudo").val() == '' &&
                        $("#divModalEditarConteudo #txtPDFNovoConteudo").val() == ''
                    ) {
                        $('#divModalEditarConteudo #txtPDFNovoConteudo').focus();

                        isValid = false;
                    }
                    break;
                case '22':
                    if ($("#divModalEditarConteudo #btnVerArquivoAtual").attr('href') == '' &&
                        $("#divModalEditarConteudo #inputPDFNovoConteudo").val() == '' &&
                        $("#divModalEditarConteudo #txtPDFNovoConteudo").val() == ''
                    ) {
                        $('#divModalEditarConteudo #txtPDFNovoConteudo').focus();

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

        function excluirConteudo(idConteudo)
        {
            swal({
                title: "Excluir conteúdo",
                text: "Você deseja mesmo excluir esta conteúdo?",
                icon: "warning",
                buttons: ["Não", "Sim"],
                dangerMode: true,
            })
            .then((deletar) =>
            {
                if (deletar)
                {
                    $.ajax({
                        url: '{{ config('app.local') }}' + '/gestao/conteudo/' + idConteudo + '/excluir',
                        type: 'post',
                        data: { '_token' : '{{ csrf_token() }}' },
                        dataType: 'json',
                        success: function( _response )
                        {


                            if(_response.success)
                            {
                                swal("", _response.success, "success");

                                $( "#divConteudo" + idConteudo ).remove();
                            }
                            else
                            {
                                swal("", _response.error, "error");
                            }
                        },
                        error: function( _response )
                        {

                        }
                    });
                }
            });
        }

        function editarAplicacao(idAplicacao)
        {
            $("#divModalEditarAplicacao").modal({ keyboard: false, backdrop: 'static' });
            $("#divModalEditarAplicacao #divLoading").removeClass('d-none');
            $("#divModalEditarAplicacao #divEditar").addClass('d-none');
            $("#divModalEditarAplicacao #divEnviando").addClass('d-none');

            $("#divModalEditarAplicacao .form-page").addClass('d-none');
            $("#divModalEditarAplicacao #page1").removeClass('d-none');

            $.ajax({
                url: appurl + '/gestao/aplicacao/' + idAplicacao + '/editar',
                type: 'get',
                dataType: 'json',
                success: function( _response )
                {


                    if(_response.success)
                    {
                        $("#divModalEditarAplicacao [name='idAplicacao']").val(_response.aplicacao.id);

                        if(_response.aplicacao.capa != "")
                        {
                            $("#divModalEditarAplicacao [id='divFileInputCapa']").attr('style', 'background-image: url(\'' + appurl + "/uploads/aplicacoes/capas/" + _response.aplicacao.capa + '\');background-size: contain;background-position: 50% 50%;background-repeat: no-repeat;');
                            $("#divModalEditarAplicacao [id='divFileInputCapa'] #placeholder").addClass('d-none');
                            $("#divModalEditarAplicacao [id='divFileInputCapa'] #file-name").removeClass('d-none');
                            $("#divModalEditarAplicacao [id='divFileInputCapa'] #file-name").text("Clique para alterar a foto de capa");
                        }
                        else
                        {
                            $("#divModalEditarAplicacao [id='divFileInputCapa']").attr('style', 'background-image: none;');
                            $("#divModalEditarAplicacao [id='divFileInputCapa'] #placeholder").removeClass('d-none');
                            $("#divModalEditarAplicacao [id='divFileInputCapa'] #file-name").addClass('d-none');
                        }

                        $("#divModalEditarAplicacao [name='titulo']").val(_response.aplicacao.titulo);
                        $("#divModalEditarAplicacao [name='descricao']").val(_response.aplicacao.descricao);
                        $("#divModalEditarAplicacao [name='visibilidade']").val(_response.aplicacao.visibilidade);
                        $("#divModalEditarAplicacao [name='status']").val(_response.aplicacao.status);
                        $("#divModalEditarAplicacao [name='liberada']").val(_response.aplicacao.liberada);

                        $("#divModalEditarAplicacao #divLoading").addClass('d-none');
                        $("#divModalEditarAplicacao #divEditar").removeClass('d-none');
                    }
                    else
                    {
                        swal("", _response.error, "error");

                        $("#divModalEditarAplicacao").modal({ keyboard: false, backdrop: 'static' });
                    }
                },
                error: function( _response )
                {

                }
            });
        }

        function salvarEdicaoAplicacao()
        {
            var isValid = true;

            $('#divModalEditarAplicacao input').each(function() {
                if ( $(this).val() === '' && $(this).attr('required') )
                {
                    $(this).focus();

                    isValid = false;
                }
            });

            if(!isValid || $("#divModalEditarAplicacao textarea").html() == '')
                return;

            $("#formEditarAplicacao").submit();

            $("#divModalEditarAplicacao #divLoading").addClass('d-none');
            $("#divModalEditarAplicacao #divEditar").addClass('d-none');
            $("#divModalEditarAplicacao #divEnviando").removeClass('d-none');

            $("#divModalEditarAplicacao #divLoading").addClass('d-none');
            $("#divModalEditarAplicacao #divEditar").addClass('d-none');
            $("#divModalEditarAplicacao #divEnviando").removeClass('d-none');
        }

        function excluirAplicacao(idAplicacao)
        {
            swal({
                title: "Excluir aplicação",
                text: "Você deseja mesmo excluir esta aplicação?",
                icon: "warning",
                buttons: ["Não", "Sim"],
                dangerMode: true,
            })
            .then((deletar) =>
            {
                if (deletar)
                {
                    $.ajax({
                        url: '{{ config('app.local') }}' + '/gestao/aplicacao/' + idAplicacao + '/excluir',
                        type: 'post',
                        data: { '_token' : '{{ csrf_token() }}' },
                        dataType: 'json',
                        success: function( _response )
                        {


                            if(_response.success)
                            {
                                swal("", _response.success, "success");

                                $( "#divAplicacao" + idAplicacao ).remove();
                            }
                            else
                            {
                                swal("", _response.error, "error");
                            }
                        },
                        error: function( _response )
                        {

                        }
                    });
                }
            });
        }

        function visualizarConteudoLivro(idConteudo){


            window.location.href = '{{ config('app.url') }}' + '/gestao/livro/' + idConteudo;


        }

        function visualizarConteudo(idConteudo)
        {
            $.ajax({
                url: '{{ config('app.url') }}' + '/gestao/biblioteca/' + idConteudo + '/visualizar',
                type: 'get',
                dataType: 'json',
                success: function( _response )
                {


                    if(_response)
                    {

                        $(".conteudo-titulo").empty();
                        $(".conteudo-tipo").empty();

                        $(".conteudo-titulo").append(_response.titulo);
                        $(".conteudo-tipo").append(_response.descricao);

                        $('#divModalVisualizarConteudo').modal('show');
                    }

                },
                error: function( _response )
                {

                }
            });
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

            let tipo = '';
            if (modalId == 'divModalEditarConteudo') {
                tipo = 'Edit';
            }

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
                    $("#"+modalId+" #divMultiplaEscolha #rdo"+tipo+"AlternativaMultiplaEscolha" + perguntasProva[perguntaAtual - 1].correta).prop("checked", true);
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
                    $("#"+modalId+" #divMultiplaEscolha #rdo"+tipo+"AlternativaMultiplaEscolha1").prop("checked", false);
                    $("#"+modalId+" #divMultiplaEscolha #rdo"+tipo+"AlternativaMultiplaEscolha2").prop("checked", false);
                    $("#"+modalId+" #divMultiplaEscolha #rdo"+tipo+"AlternativaMultiplaEscolha3").prop("checked", false);
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
            let tipo = '';
            if (modalId == 'divModalEditarConteudo') {
                tipo = 'Edit';
            }
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
                if($("#"+modalId+" #divMultiplaEscolha #rdo"+tipo+"AlternativaMultiplaEscolha1").prop("checked"))
                    correta = 1;
                else if($("#"+modalId+" #divMultiplaEscolha #rdo"+tipo+"AlternativaMultiplaEscolha2").prop("checked"))
                    correta = 2;
                else if($("#"+modalId+" #divMultiplaEscolha #rdo"+tipo+"AlternativaMultiplaEscolha3").prop("checked"))
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

                $("#"+modalId+" #divMultiplaEscolha #rdo"+tipo+"AlternativaMultiplaEscolha1").prop("checked", false);
                $("#"+modalId+" #divMultiplaEscolha #rdo"+tipo+"AlternativaMultiplaEscolha2").prop("checked", false);
                $("#"+modalId+" #divMultiplaEscolha #rdo"+tipo+"AlternativaMultiplaEscolha3").prop("checked", false);
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

    </script>

@endsection
