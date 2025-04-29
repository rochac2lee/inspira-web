@extends('layouts.master')

@section('title', 'Atualizar trilha')


@section('content')
<!-- Encapsula o nome do usuário logado para uma função JS -->
@php
$nome = Auth::user()->name;
$appName = config('app.name');
@endphp
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
                <li class="breadcrumb-item active" aria-current="page">Editar trilha</li>
            </ol>
        </nav>
        <form id="formUpdateTrilha" action="{{ route('gestao.trilhas.update', ['idTrilha' => $trilha->id]) }}" method="post" enctype="multipart/form-data">
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
                                    <!-- <button type="button" class="btn btn-master" data-toggle="modal" data-target="#modalNovoCurso">
                                        + Novo Roteiro
                                    </button> -->
                                </div>
                            </div>
                            <div class="input-group bg-white box-shadow rounded-10 mb-3">
                                <input type="text" id="search-text" class="form-control border-0 text-secondary py-0 text-truncate" placeholder="Digite o nome do {{$langCurso}} que está procurando" style="font-size: 14px;" maxlength="50">
                                <div class="input-group-append border-0">
                                    <button type="button" class="btn btn-link" id="search" onclick="filtrarCursos();">
                                        <i class="fas fa-search fa-xs fa-fw text-secondary"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 pl-0 box-cursos-add {{ Auth::user()->permissao == 'Z' ? 'box-cursos-admin' : 'box-cursos'}} row-card mb-3">
                        </div>

                    </div><!-- col-12-->

                </div><!-- Fim modal adicionar album-->

                <div class="col-12 col-md-7 mb-4 mb-sm-0 mt-3 mt-sm-0">
                    <div class="bg-white rounded-10">
                        <div class="col-12 d-flex justify-content-center">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title text-dark font-weight-bold">Atualizar trilha</h5>
                            </div>
                        </div>
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-12 col-lg-12">

                                    <div class="row">
                                        <div class="col-12 col-md-8">
                                            <div class="col-12 borderless-col form-group mb-3">
                                                <input type="text" class="form-control font-weight-bold" name="titulo" placeholder="Título da trilha" value="{{ $trilha->titulo }}" maxlength="150" required>
                                            </div>
                                            <!--
                                            <div class="d-block">
                                                <div class="form-group mb-3">
                                                    <textarea class="form-control" name="descricao" id="descricao" rows="4" maxlength="1000" placeholder="Descrição breve da trilha" style="resize: none">{{ $trilha->descricao }}</textarea>
                                                </div>
                                            </div>
                                        -->
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label for="capa" id="divFileInputCapa" class="file-input-area input-capa text-center d-flex align-items-center justify-content-center" style="background-image: url('{{ config('app.cdn') }}/storage/uploads/trilhas/capas/{{ $trilha->capa }}'); background-size:cover; ">
                                                <input type="file" class="custom-file-input" id="capa" name="capa" style="top: 0px;height:  100%; width:100%; position:  absolute;left:  0px;" accept="image/jpg, image/jpeg, image/png" oninput="mudouArquivoCapa(this);">
                                                @if(!$trilha->capa)
                                                <h6 id="placeholder">
                                                    <i class="fas fa-camera fa-2x d-block mb-2 w-100"></i>
                                                    Escolher capa
                                                </h6>
                                                @endif

                                            </label>
                                        </div>
                                    </div>
                                    <!--fim row -->

                                    <!--  row 2 -->

                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group mb-3 mt-3">
                                                @if(Auth::user()->permissao == "G" || Auth::user()->permissao == "Z" ||
                                                Auth::user()->privilegio_id == 2 || Auth::user()->privilegio_id == 1)
                                                <label for="instituicao_id">Instituição</label>
                                                <select class="custom-select form-control" onchange="mudaEscola(this)" name="instituicao_id" id="instituicao_id" required>
                                                    @foreach($instituicoes as $instituicao)
                                                    <option value="{{ $instituicao->id }}" {{ $trilha->instituicao_id == $instituicao->id ? 'selected' : ''}}>
                                                        {{ ucfirst($instituicao->titulo) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group mb-3 mt-3">
                                                <label>Unidade Escolar<BR></label>
                                                <select class="custom-select form-control" name="escola_id" id="escola_id">
                                                    @foreach ($escolas as $escola)
                                                    <option value="{{ $escola->id }}" {{ $trilha->escola_id == $escola->id ? 'selected' : ''}}>
                                                        {{ ucfirst($escola->titulo) }}
                                                    </option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <!--
                                        <div class="col-12 col-md-6">
                                            <div class="form-group mb-3 mt-3">
                                                <label>Tags<BR></label>
                                                <input type="text" class="form-control" name="tag" id="tag" value="{{ $trilha->tag }}">

                                            </div>
                                        </div>
                                        -->
                                    </div>
                                    <!--fim row 2 -->

                                    <!--  row 3 -->
                                    <div class="row">

                                        <!--
                                        <div class="col-12 col-md-6">
                                            <div class="form-group mb-3 mt-3">
                                                <label>Visibilidade<BR></label>
                                                <select class="custom-select form-control" name="visibilidade" id="visibilidade" required>
                                                    @foreach($visibilidades as $visibilidade)
                                                    <option value="{{ $visibilidade->id }}" {{ $trilha->visibilidade_id == $visibilidade->id ? 'selected' : ''}}>
                                                        {{ ucfirst($visibilidade->titulo) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        -->
                                    </div>
                                    <!--fim row 3 -->

                                    <!--  row 4 -->
                                    <div class="row">
                                        <!--<div class="col-12 col-md-6">
                                            <div class="form-group mb-3 mt-3">
                                                <label>Autor<BR></label>
                                                <input type="text" class="form-control" name="autor" id="autor" value="{{$trilha->instituicao_id ==1 ? "Editora $appName" : strtoupper(Auth::user()->name)}}" readonly>
                                            </div>
                                        </div>
                                    -->
                                        <div class="col-12 col-md-6">
                                            <div class="form-group mb-3 mt-3">
                                                <label>Etapa<BR></label>
                                                <select class="custom-select form-control" name="ciclo_id" id="ciclo_id" required>
                                                    @foreach($etapas as $etapa)
                                                    <option value="{{ $etapa->id }}" {{ $trilha->ciclo_id == $etapa->id ? 'selected' : ''}}>
                                                        {{ ucfirst($etapa->titulo) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group mb-3 mt-3">
                                                <label>Ano<BR></label>
                                                <select class="custom-select form-control" name="cicloetapa_id" id="cicloetapa_id" required>
                                                    @foreach($cicloEtapas as $cicloEtapa)
                                                    <option value="{{ $cicloEtapa->id }}" {{ $trilha->cicloetapa_id == $cicloEtapa->id ? 'selected' : ''}}>
                                                        {{ ucfirst($cicloEtapa->titulo) }}
                                                    </option>
                                                    @endforeach
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
                                                    @foreach($disciplinas as $disciplina)
                                                    <option value="{{ $disciplina->id }}" {{ $trilha->disciplina_id == $disciplina->id ? 'selected' : ''}}>
                                                        {{ ucfirst($disciplina->titulo) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--fim row 5 -->

                                    <div class="col-12 box-cursos-trilhas form-group mt-3" style="height:auto !important">
                                        <div class="row-card cursosAddInline col-lg-12 col-12 text-secondary">
                                            @foreach($trilha->trilhas_cursos as $curso)
                                            <div class="card addContainerCurso_{{ $curso->curso->id }} mb-3" cursoid="{{ $curso->curso->id }}">
                                                <div class="card-body w-100 d-flex align-items-center" data-toggle="tooltip" data-placement="top" title="{{ $curso->curso->titulo }}">
                                                    <div class="col-3 capa-item h-100 handle" style="background-image: url('{{ config('app.cdn') }}/uploads/cursos/capas/{{ $curso->curso->capa }}');'">
                                                    </div>
                                                    <div class="col-7 align-middle handle">
                                                        <h5 class="curso-title text-truncate">{{ $curso->curso->titulo }}</h5>
                                                    </div>
                                                    <div style="cursor: pointer;" class="addCurso col-2 text-right" onclick="removerCurso(this.id)" id="{{ $curso->curso->id }}" data-id="{{ $curso->curso->titulo }}">
                                                        <i class="fas fa-times fa-lg text-primary removeCurso{{ $curso->curso->id}} removeCurso" id="{{ $curso->curso->id}}"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div><!--fim col-12 -->

                            </div><!--fim row -->

                        </div>
                    </div> <!-- Fim modal descrição album-->

                    <div class="modal-footer px-0">
                        <div class="col-12 d-block d-sm-flex justify-content-between px-0">
                            <button type="button" data-dismiss="modal" onclick="window.location='{{ route('gestao.trilhas.listar') }}'" class="btn btn-cancelar col-auto col-sm-3">
                                Cancelar
                            </button>
                            <button id="atualizarBtn" type="submit" class="btn btn-master col-auto col-sm-3">
                                Atualizar
                            </button>
                        </div>
                    </div>
                    <!--fim modal-footer -->
                </div>
                <!--row -->

                <div class="cursosIds">
                    @foreach($trilha->trilhas_cursos as $curso)
                    <input type="hidden" id="curso_id_{{$curso->curso->id}}" name="curso_id[]" value="{{$curso->curso->id}}">
                    <input type="hidden" id="curso_id_ordem_{{$curso->curso->id}}" name="curso_id_ordem_{{$curso->curso->id}}" value="{{$curso->ordem}}">
                    @endforeach
                </div>
                <input type="hidden" name="capa_atual" value="{{$trilha->capa}}">
                <input type="hidden" id="ordem" value="{{ count($trilha->cursos) }}" />
        </form>
    </div>
    <!--row -->
</main>

<!-- Encapsula o nome do usuário logado para uma função JS -->
@php
$nome = Auth::user()->name; @endphp
@endsection

@section('bodyend')
<!-- Js externo -->
<script src="{{ config('app.cdn') }}/old/assets/js/pages/trilhas/trilhas.js"></script>

<script>
    $(document).ready(function() {

        /**
         * Verifica a cada 100ms se todos os campos required do formulário foram
         * preenchidos e habilita o botão de salvar.
         */
        var enableCreateButton = function() {
            var formValid = $('#formUpdateTrilha')[0].checkValidity();
            if(!formValid) {
                $('#atualizarBtn').attr('disabled', 'disabled');
                return;
            }
            $('#atualizarBtn').attr('disabled', false);
        }
        setInterval(enableCreateButton, 100);


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

    //Correção do filtro de cursos
    function filtrarCursos() {

        var filtroCurso = $("#search-text").val();
        var excetoCurso = [];
        var escola = $("#escola_select").val();
        $("[name='curso_id[]']").each(function() {
            excetoCurso.push($(this).val());
        });
        const newLocal = '{{ route('gestao.search-cursos')}}';
        $.ajax({
            url: newLocal,
            type: 'get',
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
                    // <p class="curso-desc text-secundary text-truncate">` + _response[key].descricao + `</p>
                    $('.box-cursos-add').append(htmlCurso);
                } else {

                    // CRIA OS CARDS DE CURSO
                    for (const key in _response) {

                        let htmlCurso = `
                                <div class="card removeContainerCurso_` + _response[key].id + ` mb-3">
                                    <div class="card-body w-100 d-flex align-items-center">
                                        <div class="col-3 capa-item h-100 handle"
                                        style="background-image: url('{{ config('app.cdn') }}/storage/uploads/cursos/capas/` + _response[key].capa + `');'">
                                        </div>
                                        <div class="col-7 align-middle handle" data-toggle="tooltip" data-placement="top" title=` + _response[key].titulo + `>
                                            <h5 class="curso-title text-truncate">` + _response[key].titulo + `</h5>
                                        </div>
                                        <div style="cursor: pointer;" class="addCurso col-2 text-right" onclick="adicionarCurso(this.id)"
                                            id="` + _response[key].id + `" data-id="` + _response[key].titulo + `">
                                            <i class="fas fa-plus fa-lg text-primary"></i>
                                        </div>
                                    </div>
                                </div>`;
                        // <p class="curso-desc text-secundary text-truncate">` + _response[key].descricao + `</p>
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
