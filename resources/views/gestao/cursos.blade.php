@extends('layouts.master')

@section('title', 'Gestão de '.$langCurso)

@section('headend')

<!-- Custom styles for this template -->
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
        min-height: 200px;
        border-radius: 10px 10px 0px 0px;
        background-image: url('{{ config('app.cdn') }}/images/default-cover.jpg');
        background-size: cover;
        background-position: 50% 50%;
        background-repeat: no-repeat;

        {{--  margin-left: -15px;  --}}
        {{--  width: calc(100% + 30px) !important;  --}}
    }

    .card-footer {
        border-radius: 0px 0px 10px 10px;
    }

    .input-group input.text-secondary::placeholder {
        color: #989EB4; }

</style>

@endsection

@section('content')


<main role="main">

    <div class="container">

        <div class="row" style="height: 100%; min-height: calc(100vh - 114px);">

            {{--  @include('gestao.side-menu')  --}}

            <div id="divMainMenu" class="col-12 p-0" style="width: calc(100% - 1px); flex: inherit; transition: 0.3s all ease-in-out;">

                <div class="col-12 col-md-11 mb-4 mx-auto pt-4">

                    <div class="col-12 mb-3 title pl-0">
                        <h2>{{ucfirst($langCursoP)}}</h2>
                    </div>

                        <div class="row d-flex align-items-center mb-4">
                            @if(count($cursos) > 0)


                            <div class="row w-100 mx-0">
                                <div class="col-md-12 col-lg-6 mb-2 mb-lg-0">
                                    @include('components.search-input', ['placeholder' => 'Procurar '.$langCurso.'...'])
                                </div>
                                @endif

                                <div class="col-md-12 col-lg-3 mb-2 mb-lg-0">
                                    <a href="{{ route('gestao.novo-curso') }}" class="btn btn-block btn-primary text-truncate text-uppercase d-flex align-items-center justify-content-center font-weight-bold mr-3 mb-2 mb-sm-0 h-100">
                                        <i class="fas fa-plus mr-2"></i>
                                        Novo {{$langCurso}}
                                    </a>
                                </div>

                                <div class="col-md-12 col-lg-3 mb-2 mb-lg-0">
                                    <button type="button" onclick="importarCurso()" title="Importar {{$langCurso}}" class="btn btn-block btn-primary text-truncate text-uppercase d-flex align-items-center justify-content-center font-weight-bold mr-3 mb-2 mb-sm-0 h-100">
                                        <i class="fas fa-upload mr-2"></i>
                                        Importar {{$langCurso}}
                                    </button>
                                </div>

                                <!-- <div class="row">
                                    <div class="col-sm-12 col-md-2">
                                        @include('components.search-input', ['placeholder' => 'Procurar trilhas...'])
                                    </div>

                                    <div class="col-sm-12 col-md-4 col-xl-3 mt-3 mt-sm-0">
                                        <a href="{{ route('gestao.trilhas.create') }}" class="btn btn-block btn-primary text-truncate text-uppercase d-flex align-items-center justify-content-center font-weight-bold mr-3 mb-2 mb-sm-0 h-100">
                                            <i class="fas fa-plus mr-2"></i>
                                            Nova trilha
                                        </a>
                                    </div>
                                </div> -->
                            </div>
                        </div>

                    <form id="formImportarCurso" action="{{ route('gestao.curso.importar') }}" method="post" enctype="multipart/form-data" class="targetImportCurso">@csrf <input type="file" id="fileImportCurso" name="fileImportCurso" style="display:none" /></form>

                    @if(Request::has('pesquisa'))
                        @include('components.search-response', ['pesquisa' => Request::get('pesquisa')])
                    @endif

                </div>

                <div class="col-12 col-md-11 mx-auto">

                    <div class="card-deck">
                        @foreach ($cursos as $curso)

                        @php

                          $vinculo =  app(App\Http\Controllers\GestaoController::class)->verificaVinculoCursoTrilha( [$curso->id] )

                        @endphp

                        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4 px-0 px-md-3">

                            <div class="card border-0 h-100 px-0 mx-0 card-cursos">
                                <div style="overflow: hidden;">
                                    <div class="capa-curso"
                                        style="{{ $curso->capa != '' ? 'background-image: url('. config('app.cdn') .'/storage/uploads/cursos/capas/'. $curso->capa .');' : '' }}">
                                    </div>
                                </div>

                                <div class="card-body pb-0">
                                    <h6 class="card-title text-dark text-truncate w-100" style="width: 70%; overflow: hidden;	text-overflow: ellipsis; white-space: wrap;	display: block;"
                                    data-toggle="tooltip" data-placement="right" title="{{$curso->titulo}}">
                                        {{ $curso->titulo }}
                                        {{-- {{ $curso->preco > 0 ? 'R$ ' . number_format($curso->preco, 2, ',', '.') : 'Gratuito' }} --}}
                                    </h6>

                                    <p class="small text-lightgray my-0">
                                        <i class="fas fa-circle {{ $curso->status == 0 ? 'text-lightgray' : 'text-accepted' }} mr-1"></i>
                                        {{ $curso->status_name }}
                                        {{--•
                                        <i class="fas fa-eye{{ $curso->visibilidade == 0 ? '-slash' : '' }} mr-1"></i>
                                        {{ $curso->visibilidade == 1 ? 'Listado' : 'Não listado' }}
                                        •
                                        {{ $curso->matriculados }} Matriculado{{ $curso->matriculados != 1 ? 's' : '' }} --}}
                                    </p>
                                    <p class="text-lightgray font-weight-bold mb-3">
                                        {{-- <span class="text-truncate w-75 d-block font-weight-normal">{{ ucfirst($curso->descricao_curta) }}</span> --}}
                                        {{-- @if(Auth::user()->id != $curso->user->id) --}}
                                        <small class="d-block">
                                            <i class="fas fa-user"></i>
                                            @if(isset($curso->user->nome_completo))
                                                {{ strtoupper($curso->user->nome_completo) }}
                                            @endif
                                            <span class="d-block mt-2"> <i class="fas fa-tags"></i> {{$curso->tag}} </span>
                                        </small>
                                        {{-- @endif --}}

                                    </p>
                                    @if(Auth::user()->permissao == "Z")
                                        <p class="font-weight-bold info-consumo-dados bg-danger">
                                            <span>
                                                <small>
                                                    <i class="fas fa-database"></i>
                                                    Consumo: {{ number_format( $curso->consumo , 2, ",", ".") }} GB
                                                </small>
                                            </span>
                                        </p>
                                    @endif
                                </div>

                                <div class="card-footer bg-white">
                                    <div class="text-center">
                                        <a href="{{ route('curso', ['idCurso' => $curso->id]) }}" data-toggle="tooltip" data-placement="top" title="Visualizar {{$langCurso}}"
                                            class="btn btn-cards">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!in_array(Auth::user()->privilegio_id, [4]) || $curso->user_id == Auth::user()->id)
                                            @if(count($curso->aulas) > 0)
                                                <a href="{{ route('gestao.curso.exportar', ['idCurso' => $curso->id]) }}" data-toggle="tooltip" data-placement="top" title="Exportar {{$langCurso}}"
                                                    class="btn btn-cards">
                                                    <i class="fas fa-file-export"></i>
                                                </a>
                                            @else
                                                <button href="#" data-toggle="tooltip" data-placement="top" title="{{ucfirst($langCurso)}} sem conteúdo"
                                                    class="btn btn-cards" disabled>
                                                    <i class="fas fa-file-export"></i>
                                                </button>
                                            @endif

                                            <a href="{{ route('gestao.curso-conteudo', ['idCurso' => $curso->id]) }}" data-toggle="tooltip" data-placement="top" title="Editar {{$langCurso}}"
                                                class="btn btn-cards">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-cards" hidden>
                                                <i class="fas fa-copy"></i>
                                            </a>
                                        @endif

                                        @if($curso->user_id == Auth::user()->id || in_array(Auth::user()->privilegio_id, [1, 2]))
                                            <button type="button" onclick="excluirCurso({{ $curso->id }})" data-toggle="tooltip" data-placement="top" title="Apagar {{$langCurso}}"
                                                class="btn btn-cards">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif

                                    </div>
                                </div>

                                <form id="formExcluirCurso{{ $curso->id }}" action="{{ route('gestao.curso-excluir', ['idCurso' => $curso->id]) }}" method="post">@csrf</form>

                            </div>

                        </div>

                        @endforeach

                    </div>
                    {{ $cursos->links() }}

                </div>

                <div class="col-12 col-md-11 mx-auto">

                    @if(count($cursos) == 0)
                    <div class="col-12 px-0 mb-4">
                        <div class="card-curso box-shadow rounded-10 p-3">
                            <h5>
                                Você ainda não criou nenhum {{$langCurso}}.
                            </h5>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</main>

@endsection

@section('bodyend')

<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.pt-BR.min.js">
</script>

<script>



    $('#txtDatePicker').datepicker({
        weekStart: 0,
        language: "pt-BR",
        daysOfWeekHighlighted: "0,6",
        autoclose: true,
        todayHighlight: true
    });

    $(document).ready(function () {
        @if(Auth::check())
        if (window.location.hash) {
            var hash = window.location.hash.substring(1);

            if (hash == "divulgar-revista") {
                $('#divModalNovaEdicao').modal('show');
            }
        }
        @endif
    });

    function showListMode(mode, button) {
        if (mode == 1) {
            $('.book-item').addClass('col-lg-6');
            $('#btnListMode1').addClass("text-primary");
            $('#btnListMode2').removeClass("text-primary");
        } else {
            $('.book-item').removeClass('col-lg-6');
            $('#btnListMode2').addClass("text-primary");
            $('#btnListMode1').removeClass("text-primary");
        }
    }

    function importarCurso()
    {
        $('#fileImportCurso').trigger('click');
        $("#fileImportCurso").change(function() {
            $(this).parent('form').submit();
        });
    }

    function excluirCurso(id) {
        if ($("#formExcluirCurso" + id).length == 0)
            return;

        swal({
            title: 'Excluir {{$langCurso}}?',
            text: "Você deseja mesmo excluir? Todo seu conteúdo será apagado!",
            icon: "warning",
            buttons: ['Não', 'Sim, excluir!'],
            dangerMode: true,
        }).then((result) => {
            if (result == true) {
                $("#formExcluirCurso" + id).submit();
            }
        });
    }

</script>

@endsection
