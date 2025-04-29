@extends('layouts.master')

@section('title', 'Tópico ' . $topico->titulo)

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
            box-shadow: 0px 3px 6px rgba(0,0,0,0.16);
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
            box-shadow: 0px 3px 6px rgba(0,0,0,0.16);
            background: #5678ef;
            border-radius: 90px;
            border: 8px solid #E3E5F0;
        }

        @media (min-width: 576px)
        {
            .side-menu
            {
                min-height: calc(100vh - 162px);
            }
        }

        .bg-postagem, .bg-card
        {
            background-color: white !important;
        }

        main:not(.darkmode) .text-white.text-darkmode
        {
            color: #13141D !important;
        }

        main:not(.darkmode) .bg-light
        {
            background-color: white !important;
        }

        main.darkmode .bg-light
        {
            background-color: #272A3B !important;
        }


    </style>

@endsection

@section('content')

<main role="main" class="">

    <div class="container-fluid pt-4">

        <div class="px-3 w-100">

            <div class="row">
                <div class="col-12 mr-auto align-middle my-auto text-left">
                    <a href="{{ route( 'curso', $topico->curso_id) }}" class="btn bg-transparent btn-outline  mb-2 text-primary box-shadow rounded px-4 py-2">
                        <i class="fas fa-chevron-left fa-fw fa-lg mr-2"></i>
                        Voltar ao {{$langCurso}}
                    </a>
                    @if(Auth::user()->id == $topico->user_id && $topico->status == 0)
                        <form class="mt-3 mt-lg-0 flot-none float-lg-right" action="{{ route('curso.topico-atualizar', ['curso_id' => $topico->curso_id, 'topico_curso_id' => $topico->id]) }}" method="post">
                            @csrf
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="btn bg-success text-white box-shadow rounded px-4 py-2 mb-2">
                                <i class="fas fa-lock fa-fw fa-lg mr-2"></i>
                                Fechar tópico
                            </button>
                        </form>
                    @elseif(Auth::user()->id == $topico->user_id && $topico->status == 1)
                        <form class="mt-3 mt-lg-0 float-none float-lg-right" action="{{ route('curso.topico-atualizar', ['curso_id' => $topico->curso_id, 'topico_curso_id' => $topico->id]) }}" method="post">
                            @csrf
                            <input type="hidden" name="status" value="0">
                            <button type="submit" class="btn bg-warning text-white box-shadow rounded px-4 py-2">
                                <i class="fas fa-unlock fa-fw fa-lg mr-2"></i>
                                Reabrir tópico
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="row">

                <div class="col-12 col-md-10 col-lg-8 mx-auto">

                    <div class="bg-postagem rounded-10 box-shadow py-4 mb-2">

                        <div class="container-fluid px-0">

                            <div class="row pl-5 pr-4 mb-4">

                                <div class="col-auto my-auto text-left">
                                    @if($topico->status == 0)
                                        <i class="fas fa-unlock fa-fw fa-lg text-success"></i>
                                    @else
                                        <i class="fas fa-lock fa-fw fa-lg text-danger"></i>
                                    @endif
                                </div>
                                <div class="col-auto my-auto text-left text-break">
                                    <h2 class="text-dark font-weight-bold">
                                        {{ ucfirst($topico->titulo) }}
                                    </h2>
                                </div>
                            </div>

                            <hr class="mb-4">

                            <div class="row pl-5 pr-4">

                                <div class="col-auto text-left px-0">
                                    <div class="avatar-img my-0 d-inline-block" style="width: 54px;height: 54px; background: url({{ route('usuario.perfil.image', [$topico->user['id']]) }}); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;"></div>
                                </div>
                                <div class="col text-left px-1 px-lg-5 text-break">
                                    <span class="d-inline-block align-middle" style="color: #999FB4;">
                                        {{ $topico->user['name'] }}
                                        @if($topico->user_id == $topico->curso_id || $topico->user->permissao != "A")
                                            <i class="fas fa-check-circle fa-fw ml-1" style="color: #798AC4;"></i>
                                        @endif
                                    </span>

                                    <div class="float-right text-break" style="color: #999FB4;">
                                        {{ $topico->created_at->diffForHumans() }}
                                    </div>

                                    <div class="pt-2 pb-5 text-white text-darkmode text-break">
                                        {!! $topico->descricao !!}
                                    </div>
                                </div>
                                @if($topico->user_id == Auth::user()->id || $topico->curso_id == Auth::user()->id)
                                    <div class="col-auto ml-auto pr-0 mx-0 text-right">
                                        <form class="" action="{{ route('curso.topico-excluir', ['curso_id' => $topico->curso_id, 'topico_curso_id' => $topico->id]) }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn bg-transparent p-1 mr-2">
                                                <i class="fas fa-trash fa-fw text-lighter text-hover-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif

                            </div>

                            <hr>

                            <div class="row px-5">
                                <div class="col-12">
                                    <p class="small text-break" style="color: #999FB4;">
                                        Comentário{{ count($topico->comentarios) != 1 ? 's' : '' }} ({{ count($topico->comentarios) }})
                                    </p>
                                </div>
                            </div>

                            @foreach ($topico->comentarios as $comentario)

                                <div class="row pl-5 pr-4 py-3">
                                    <div class="col-auto text-left px-0">
                                        <div class="avatar-img avatar-sm my-0 d-inline-block" style="width: 54px;height: 54px; background: url({{ route('usuario.perfil.image', [$comentario->user['id']]) }}); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;"></div>
                                    </div>
                                    <div class="col text-left text-break">
                                        <span class="d-inline-block align-middle" style="color: #999FB4;">
                                            {{ $comentario->user['name'] }}
                                            @if($comentario->user['id'] == $topico->curso_id || $topico->user->permissao != "A")
                                                <i class="fas fa-check-circle fa-fw ml-1" style="color: #798AC4;"></i>
                                            @endif
                                        </span>

                                        <div class="float-right text-lightgray" style="color: #999FB4;">
                                            {{ $comentario->created_at->diffForHumans() }}
                                            {{-- {{ $comentario->created_at->format('d \d\e M \d\e Y') }} --}}
                                        </div>

                                        <div class="pt-2 text-white text-darkmode">
                                            {!! $comentario->conteudo !!}
                                        </div>
                                    </div>
                                    @if($comentario->user_id == Auth::user()->id || $turma->user_id == Auth::user()->id)
                                        <div class="col-auto ml-auto pr-0 mx-0 text-right">
                                            <form class="" action="{{ route('curso.topico-comentario-excluir', ['curso_id' => $topico->curso_id, 'topico_curso_id' => $topico->id, 'idComentario' => $comentario->id]) }}" method="post">
                                                @csrf
                                                <button type="submit" class="btn bg-transparent p-1">
                                                    <i class="fas fa-trash fa-fw text-lighter text-hover-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>

                            @endforeach

                        </div>

                    </div>

                    @if($topico->status == 0)
                        <div class="row mb-5">

                            <div class="col-12 m-0">
                                <form action="{{ route('curso.topico-comentario-enviar', ['curso_id' => $topico->curso_id, 'topico_curso_id' => $topico->id]) }}" method="post">
                                    <div class="input-group" style="font-size: 16px;margin-top:  20px;">
                                        @csrf
                                        <input type="text" id="txtComentarioTopico" class="form-control bg-light box-shadow rounded-10 text-dark mx-auto p-3" maxlength="1000" name="conteudo" aria-describedby="helpId" placeholder="Escreva um comentário" style="border-right: 0px;font-weight: normal;border-radius: 10px 0px 0px 10px;">
                                        <div class="input-group-append bg-light box-shadow rounded-10 text-secondary m-0" style="border-left: 0px;box-shadow: -3px 3px 6px rgba(0,0,0,0.16);">
                                            <button type="submit" class="btn bg-light text-lighter text-hover-primary btn-block" style="border-radius: 0px 10px 10px 0px;">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
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

    <script>

        $( document ).ready(function()
        {

        });

    </script>

@endsection
