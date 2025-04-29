@extends('layouts.master')

@section('title', 'Novo '.$langCurso)

@section('headend')

<!-- Encapsula o nome do usuário logado para uma função JS -->
@php
    $nome = Auth::user()->name;
    $appName = config('app.name');
@endphp
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
            color: #525870;
            font-weight: bold;
            font-size: 16px;
            border: 0px;
            border-radius: 8px;
            box-shadow: 0px 1px 2px rgba(0,0,0,0.16);
        }

        .form-control::placeholder
        {
            color: #B7B7B7;
        }

        .custom-select option:first-child
        {
            color: #B7B7B7;
        }

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
            background: var(--primary-color);
            border-radius: 90px;
            border: 8px solid #E3E5F0;
        }

    </style>

@endsection

@section('content')

<main role="main" class="">

    <div class="container">

        <div class="row">

                <div class="col-12 col-md-11 mx-auto mb-5 mt-3">

                    <div class="title">
                        <h2>{{ucfirst($langCursoP)}}</h2>
                    </div>

                    <nav aria-label="breadcrumb" class="bg-transparent position-relative">
                        <ol class="breadcrumb p-0 pb-3 mb-4 mt-3 w-100 bg-transparent border-bottom">
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{ url('gestao/cursos') }}" >
                                    <i class="fas fa-chevron-left mr-2"></i>
                                    <span>Meus {{$langCursoP}}</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Adicionar {{$langCurso}}</li>
                        </ol>
                    </nav>

                    <form id="formNovoCurso" class="w-100" action="{{ route('gestao.novo-curso') }}" method="post" enctype="multipart/form-data">

                        @csrf

                        <div class="form-group m-0">
                            <label for="tipo">Título</label>
                            <input type="text" class="px-4 py-3 form-control rounded-0 text-truncate shadow-none border-bottom" name="titulo" id="titulo" maxlength="150" placeholder="Clique para adicionar o título do {{$langCurso}}." style="font-size: 1.5rem;" required>
                            <input type="hidden" class="form-control" name="cursos_tipo_id" id="cursos_tipo_id" value="1">
                        </div>

                        <div style="background-color: #FBFBFB;min-height: calc(100vh - 284px);">

                            <div class="container-fluid">

                                <div class="row">

                                    <div class="col-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="capa" id="divFileInputCapa" class="file-input-area input-capa mt-3 mb-5 w-100 text-center">
                                                <input type="file" class="custom-file-input" id="capa" name="capa" required style="top: 0px;height:  100%;position:  absolute;left:  0px;" accept="image/jpg, image/jpeg, image/png" oninput="mudouArquivoCapa(this);">

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
                                        </div>
                                        {{--
                                        <div class="form-group mb-3 mt-3">
                                        <label class="" for="descricao_curta">Descrição curta do {{$langCurso}}</label>
                                        <textarea class="form-control" name="descricao_curta" id="descricao_curta" rows="3" maxlength="250" placeholder="Clique para digitar." required></textarea>
                                        </div> --}}

                                        <div class="form-group mb-3">
                                        <label class="" for="descricao">Descrição</label>
                                            <textarea name="descricao" id="descricao" class="form-control" rows="5"></textarea>
                                        </div>

                                        <div class="form-group mb-3">
                                        </div>

                                        {{-- <div class="form-group mb-3">
                                            <label for="categoria">Categoria do {{$langCurso}}</label>
                                            <select class="custom-select form-control" name="categoria" id="categoria" required>
                                                <option disabled="disabled" value="0" selected>Selecione uma categoria</option>
                                                @foreach ($categorias as $categoria)
                                                    <option value="{{ $categoria->id }}">{{ ucfirst($categoria->titulo) }}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <!--
                                            <div class="form-group mb-3 mt-3">
                                                <label for="tipo">Instituição</label>
                                                <select class="custom-select form-control" name="instituicao_id" id="instituicao_id" required>
                                                    @foreach($instituicoes as $instituicao)
                                                        <option value="{{$instituicao->id}}"
                                                        {{ $instituicao->id == 1 ? 'selected' : ''}}>
                                                            {{$instituicao->titulo}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div  class="form-group mb-3 mt-3">
                                                <label for="tipo">Unidade Escolar</label>
                                                <select class="selectpicker show-tick custom-select form-control" name="escola_id[]" data-style="btn-primary" id="unidade" style="width: 200px;" required>
                                                    <selected>Selecione uma escola</option>
                                                    @foreach($escolas as $escola)
                                                        <option value="{{$escola->id}}">{{$escola->titulo}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        -->
                                        @php
                                            $inst = session('instituicao');
                                            $escola = session('escola');
                                        @endphp

                                        <input type="hidden" name="instituicao_id" value="{{$inst['id']}}">
                                        <input type="hidden" name="escola_id[]"  value="{{$escola['id']}}">

                                        <!-- <div class="form-group my-3">
                                            <label for="categoria">Visibilidade</label>
                                            <select class="custom-select form-control" name="visibilidade" id="visibilidade" required>
                                                @foreach($visibilidades as $visibilidade)
                                                    <option value="{{$visibilidade->id}}"
                                                    {{ $visibilidade->id == 1 ? 'selected' : ''}}>
                                                    {{$visibilidade->titulo}}</option>
                                                @endforeach
                                            </select>
                                        </div> -->

                                        {{-- @if((strtoupper(Auth::user()->permissao) == "E" || strtoupper(Auth::user()->permissao) == "Z"))
                                            <div class="form-group mb-3">
                                                <label class="" for="senha">Senha do {{$langCurso}} <small>(opcional)</small></label>
                                                <input type="text" class="form-control" name="senha" id="senha" maxlength="50" aria-describedby="helpId" placeholder="Clique para digitar.">
                                            </div>
                                        @endif

                                        <div class="form-group mb-3">
                                            @if((strtoupper(Auth::user()->permissao) == "E" || strtoupper(Auth::user()->permissao) == "Z"))
                                                <label class="" for="preco">Preço do {{$langCurso}} (Opcional)</label>
                                            @else
                                                <label class="" for="preco">Preço do {{$langCurso}} (Opcional)</label>
                                            @endif
                                        <input type="text" class="form-control money" name="preco" id="preco" maxlength="12" aria-describedby="helpId" placeholder="Clique para digitar.">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="" for="preco">Link para checkout (Opcional)</label>
                                            <input type="text" class="form-control" maxlength="150" name="link_checkout" id="link_checkout" aria-describedby="helpId" placeholder="Clique para digitar.">
                                        </div> --}}

                                        <!--<div class="form-group mb-3">
                                            <label class="" for="autor">Autor</label>
                                            <input type="text" class="form-control" maxlength="150" name="autor" id="autor" aria-describedby="helpId" value="{{Auth::user()->name}}" readonly>
                                        </div>
                                        -->
                                            <input type="hidden" name="autor" value="{{Auth::user()->name}}">

                                        {{-- <div class="form-group mb-3">
                                            <label class="" for="periodo">Período do {{$langCurso}} (dias)</label>
                                            <label class="float-right" id="lblPeriodo" for="periodo">1</label>
                                            <input type="range" class="custom-range" min="1" max="366" value="0" name="periodo" id="periodo" oninput="mudouPeriodo(this);">
                                        </div> --}}

                                        {{-- <div class="form-group mb-3">
                                            <label class="" for="vagas">Vagas do {{$langCurso}}</label>
                                            <label class="float-right" id="lblVagas" for="vagas">1</label>
                                            <input type="range" class="custom-range" min="1" max="101" value="0" name="vagas" id="vagas" oninput="mudouVagas(this);">
                                        </div> --}}


                                        <div class="form-group mb-3 mt-3">
                                            <label for="tipo">Etapa</label>
                                            <select class="custom-select form-control" name="ciclo_id" id="ciclo_id" >
                                                <option value="" selected="true" disabled="disabled">Selecione uma etapa</option>
                                                @foreach($etapas as $etapa)
                                                    <option @if($etapa->id) selected @endif value="{{$etapa->id}}">{{$etapa->titulo}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3 mt-3">
                                            <label for="tipo">Ano</label>
                                            <select class="custom-select form-control" name="cicloetapa_id" id="cicloetapa_id" >
                                                <option value="">Selecione um ano</option>
                                            </select>
                                        </div>


                                        <div class="form-group mb-3 mt-3">
                                            <label for="disciplina_id">Componente Curricular</label>
                                            <select class=" form-control" name="disciplina_id" id="disciplina_id"  data-style="btn-primary" style="width: 200px;">
                                                <!-- <option>Componente Curricular</option> -->
                                                @foreach($disciplinas as $disciplina)
                                                    <option value="{{$disciplina->id}}">{{$disciplina->titulo}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3 mt-3">
                                           <!-- <label for="disciplina_id">Professor</label>
                                            <select class="selectpicker show-tick custom-select form-control" name="professor_id[]" id="professor_id"  data-style="btn-primary" multiple style="width: 200px;">
                                                @foreach($professores as $professor)
                                                    <option value="{{$professor->id}}">{{$professor->name}}</option>
                                                @endforeach
                                            </select>
                                            -->

                                            <input type="hidden" name="professor_id[]" value="{{Auth::user()->id}}">
                                            <label class="" for="descricao">Palavras-chave</label>
                                            <input name="tag" id="tag" class="form-control"  maxlength="255" placeholder="Clique para digitar.">

                                        </div>

                                        <input id="rasunho" value="false" type="text" hidden>

                                        <div class="">
                                            <div class="row">
                                                <div class="col-12 col-xl-7 mb-2">
                                                    <button type="button" onclick="salvar(true);" class="btn btn-outline-primary btn-block font-weight-bold text-truncate">
                                                        Salvar como rascunho
                                                    </button>
                                                </div>
                                                <div class="col-12 col-xl-5">
                                                    <button type="button" onclick="salvar(true);" class="btn btn-primary btn-block font-weight-bold text-truncate">
                                                        Continuar
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

<!-- Encapsula o nome do usuário logado para uma função JS -->
@php $nome = Auth::user()->name; @endphp

@endsection

@section('bodyend')

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>

    <!-- Summernote css/js -->
    {{-- <link href="{{ config('app.local') }}/assets/css/summernote-lite-cerulean.min.css" rel="stylesheet"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/lang/summernote-pt-BR.min.js" crossorigin="anonymous"></script>

    <!-- Mask JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

    <!-- Sweet Alert -->
    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <!-- Sweet Alert  208-->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script> -->

    <script src="{{config('app.local') }}assets/js/pages/gestao/curso.js"></script>

    <script>

    //Mudar depois, incluir no JS externo
    $( document ).ready(function()
    {
        //Função para mostrar apenas os anos de etapa
        $('#ciclo_id').change(function(){
            ciclo = $(this).val();
            const newLocal = '{{ route('gestao.searchcicloetapa')}}';
            $.ajax({
                url: newLocal,
                type: 'GET',
                dataType: 'json',
                data: {ciclo: ciclo},
                success: function (data) {
                    $('#cicloetapa_id').html(data);
                },
                    error: function (data) {
                        console.log(data);
                }
            });
        });

        $('#ciclo_id').change();


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

        $('#txtDatePicker').datepicker({
            weekStart: 0,
            language: "pt-BR",
            daysOfWeekHighlighted: "0,6",
            autoclose: true,
            todayHighlight: true
        });

        $('.money').mask('#.##0,00', {reverse: true});



        $('.selectpicker').selectpicker({
            style: 'btn-default',
            title: 'Selecione uma opção.',
            liveSearchPlaceholder: 'btn'
        });

    });

    function salvar(rascunho)
    {
        var isValid = true;

        $('input, textarea, select').each(function() {
            $(this).removeClass('is-invalid');
            if ( ($(this).val() == '' || $(this).val() == '0' || $(this).val() == null) && $(this).attr('required') )
            {
                $(this).focus();
                isValid = false;
            }

        });

        if(!isValid)
        {
            swal("", "Preencha os seguintes campos: \n\nTítulo e adicione uma imagem na capa do roteiro.", "error");
            return;
        }

        $("#rascunho").val(rascunho);
        $("#divSalvando").removeClass('d-none');
        $("#formNovoCurso").submit();
    }

    function mudouPeriodo(el)
    {
        if(el.value > 0 && el.value <= 365)
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
        if(el.value > 0 && el.value <= 100)
        {
            $("#lblVagas").text(el.value);
        }
        else
        {
            $("#lblVagas").text("Ilimitado");
        }
    }





    </script>

@endsection
