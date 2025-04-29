@extends('layouts.master')

@section('title', 'Dúvidas professor')

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

        .nav-tabs
        {
            border-bottom: 1px solid #EEEEEE;
        }
        .nav-tabs .nav-item
        {
            margin-bottom: 0px;
        }
        .nav-tabs .nav-link
        {
            border: 0px;
            font-size: 20px;
            border-bottom: 4px solid transparent;
            color: #525870;
            font-weight: bold;
            padding-bottom: 20px;
        }
        .nav-tabs .nav-link.active
        {
            color: var(--primary-color);
            border-bottom: 4px solid var(--primary-color);
        }

        .summernote-holder
        {
            padding: .375rem .75rem;
            border-radius: 0px;
            /*border: 1px solid #B7B7B7;*/
            border: 2px solid #B7B7B7;
            box-shadow: 0px 3px 6px rgba(0,0,0,0.16);
            font-size: initial;
            text-align: initial;
            color: initial;
        }

        .fa-star
        {
            color: #525870;
        }

        .text-lightyellow
        {
            color: #FFDC4E;
        }


    </style>

@endsection

@section('content')

<main role="main" class="">

    <div class="container-fluid pt-4">

        <div class="px-3 w-100">

            <!-- Modal Nova duvida -->
            <div class="modal fade" id="divModalNovaDuvida" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog modal-dialog-centered px-1 px-md-5" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>

                            <form id="formNovaDuvida" method="POST" action="{{ route('professor.duvidas-enviar', ['idProfessor' => $professor->id]) }}" class="text-center px-3 shadow-none border-0">

                                @csrf

                                <div id="divEnviando" class="text-center d-none">
                                    <i class="fas fa-spinner fa-pulse fa-3x text-primary mb-3"></i>
                                    <h4>Enviando</h4>
                                </div>

                                <div id="divEditar" class="form-page">

                                    <div id="page1" class="form-page">

                                        <h4 class="mb-4">Nova dúvida</h4>

                                        <div class="form-group mb-4 text-left">
                                            <label class="" for="txtTituloNovoConteudo">Título da dúvida</label>
                                            <input type="text" class="form-control" name="titulo" id="txtTituloNovoConteudo" placeholder="Clique para digitar." required>
                                        </div>

                                        <div class="form-group mb-4 text-left">
                                            <label class="" for="txtDescricaoNovoConteudo">Descrição da dúvida</label>
                                            <textarea class="form-control" name="descricao" id="txtDescricaoNovoConteudo" rows="3" placeholder="Clique para digitar." required style="resize: none"></textarea>
                                        </div>

                                        <div class="row">
                                            <button type="button" data-dismiss="modal" class="btn btn-cancelar mt-4 mb-0 col-4 ml-auto mr-4 font-weight-bold">Cancelar</button>
                                            <button type="submit" onclick="$('#formNovaDuvida')[0].checkValidity() ? enviarDuvida() : event.preventDefault();" class="btn btn-lg btn-primary btn-block mt-4 mb-0 col-4 ml-4 mr-auto font-weight-bold">Criar</button>
                                        </div>

                                    </div>



                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>
            <!-- Fim modal nova duvida -->

            @if(Auth::user()->id != $professor->id)
                <div class="row">
                    <div class="col-12 ml-auto align-middle my-auto text-right">
                        <button type="button" data-toggle="modal" data-target="#divModalNovaDuvida" class="btn bg-primary box-shadow text-white rounded px-4 py-2">
                            <i class="fas fa-plus fa-fw fa-lg mr-2"></i>
                            Enviar dúvida
                        </button>
                    </div>
                </div>
            @endif

            <div class="row mb-5 text-center">
                <div class="col-auto mx-auto">
                        <div class="avatar-img avatar-lg my-3 mx-auto box-shadow" style="width: 54px;height: 54px; background: url({{ route('usuario.perfil.image', [Auth::user()->id]) }}); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat; border: 0px;"></div>
                        <h2 class="text-dark">
                            {{ ucfirst($professor->name) }}
                            <i class="fas fa-check-circle fa-fw fa-sm ml-1" style="color: #798AC4;"></i>
                        </h2>
                        <small class="d-block mt-1 mb-3 text-left">
                            {{--  <span class="text-lightgray">({{ $avaliacaoInstrutor }}) </span>  --}}
                            <span>
                                @for ($i = 0; $i < floor($avaliacaoInstrutor); $i++)
                                    <i class="fas fa-star text-lightyellow"></i>
                                @endfor
                                @for ($i = 0; $i < (5 - floor($avaliacaoInstrutor)); $i++)
                                    <i class="far fa-star text-lightyellow"></i>
                                @endfor
                            </span>
                        </small>
                        <h5 class="text-left font-weight-normal" style="color: #989AC1;">
                            {{ ucfirst($professor->escola->titulo) }}
                        </h5>
                </div>
            </div>

            <hr style="border-color: #989AC1;">

            <div class="row">

                <div class="col-12">

                    <ul class="list-group py-3">

                        <li class="list-group-item font-weight-bold bg-transparent" style="border: 0px;">

                            @foreach ($duvidas as $duvida)

                                <div class="row py-3 px-2 mb-3 box-shadow bg-white">
                                    <div class="col-auto my-auto text-left">
                                        <div class="d-inline-block font-weight-normal align-middle mb-2">
                                            <small style="color: #989AC1;">
                                                {{ Carbon\Carbon::parse($duvida->created_at)->format('d/m/Y \à\s H:i')}}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col my-auto text-left">
                                        <div class="d-inline-block align-middle mb-2">
                                            <h5 class="text-dark font-weight-bold mb-0">

                                                <span class="text-muted">{{ $duvida->user['name'] }}:</span>
                                                <span class="mr-2">{{ ucfirst($duvida->titulo) }}</span>

                                                @if($duvida->status == 0)
                                                    <span class="" style="color: #B2AC83;">
                                                        Aberto
                                                    </span>
                                                @else
                                                    <span class="" style="color: #3FC5F5;">
                                                        Resolvido
                                                    </span>
                                                @endif

                                            </h5>
                                            {{-- <span class="d-block text-muted font-weight-bold small mr-2">
                                                Autor: {{ $duvida->user['name'] }}
                                            </span> --}}
                                            <small class="font-weight-bold">
                                                {{ $duvida->qt_comentarios }} comentário{{ $duvida->qt_comentarios != 1 ? 's' : '' }}
                                            </small>
                                        </div>
                                    </div>
                                    <a href="{{ route( (Request::is('gestao/*') ? 'gestao.' : '') . 'professor.duvida-respostas', ['idProfessor' => $duvida->professor_id, 'idDuvida' => $duvida->id]) }}" class="col-auto my-auto text-right ml-auto">
                                        <div class="d-inline-block align-middle mb-2">
                                            <small class="font-weight-bold text-uppercase ml-2" style="color: #3FC5F5;">
                                                Ler dúvida
                                            </small>
                                        </div>
                                    </a>
                                </div>

                            @endforeach

                            @if(count($duvidas) == 0)

                                <div class="row">
                                    <div class="col text-left">
                                        <p class="font-weight-normal">
                                            Nenhuma dúvida encontrada.
                                        </p>
                                    </div>
                                </div>

                            @endif

                        </li>

                    </ul>

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

        function enviarDuvida()
        {
            $("#formNovaDuvida #divEnviando").removeClass('d-none');

            $("#formNovaDuvida #divEditar").addClass('d-none');
        }

    </script>

@endsection
