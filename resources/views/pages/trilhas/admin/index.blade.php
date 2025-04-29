@extends('layouts.master')

@section('title', 'Gestão de trilhas')

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
            min-height: 160px;
            border-radius: 10px 10px 0px 0px;
            background-image: url('{{ config('app.cdn') }}/images/default-cover.jpg');
            background-size: cover;
            background-position: 50% 50%;
            background-repeat: no-repeat;
        }

        .input-group input.text-secondary::placeholder {
            color: #989EB4;
        }
        .mt-0 {
            margin-left: -0.93rem;
        }

    </style>

@endsection

@section('content')

    <main role="main">

        <div class="container">

            <div class="row">

                <div id="divMainMenu" class="col-12 p-0" style="width: calc(100% - 1px); flex: inherit; transition: 0.3s all ease-in-out;">

                    <div class="col-12 col-md-11 mb-4 mx-auto pt-4 px-4 px-sm-0">

                        <div class="col-12 mb-3 title pl-0">
                            <h2>Trilhas</h2>
                        </div>

                        @if(count($trilhas) > 0)
                            <div class="row">
                                <div class="col-sm-12 col-md-8 col-xl-9 my-auto">
                                    @include('components.search-input', ['placeholder' => 'Procurar trilhas...'])
                                </div>

                                <div class="col-sm-12 col-md-4 col-xl-3 mt-3 mt-sm-0">
                                    <a href="{{ route('gestao.trilhas.create') }}" class="btn btn-block btn-primary text-truncate text-uppercase d-flex align-items-center justify-content-center font-weight-bold mr-3 mb-2 mb-sm-0 h-100">
                                        <i class="fas fa-plus mr-2"></i>
                                        Nova trilha
                                    </a>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('gestao.trilhas.create') }}" class="btn btn-primary text-truncate text-uppercase font-weight-bold mr-3 mb-2 mb-sm-0">
                                <i class="fas fa-plus mr-2"></i>
                                Nova trilha
                            </a>
                        @endif

                        @if(Request::has('pesquisa'))
                            @include('components.search-response', ['pesquisa' => Request::get('pesquisa')])
                        @endif

                    </div>


                    <div class="col-12 col-md-11 mb-4 mx-auto ">

                        <div class="row">

                            @foreach ($trilhas as $trilha)
                             {{--    @if($trilha->status_id !=2)--}}
                                <div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-4 pl-4 pl-sm-0 pr-4 pr-sm-3">
                                    <div class="card rounded-10 shadow-sm border-0 h-100 px-0 mx-0">
                                        <div class="w-100 py-5 capa-curso lazyload"
                                            data-bg="{{ ($trilha->capa != '') ?  config('app.cdn') . '/storage/uploads/trilhas/capas/' . $trilha->capa : '' }}">
                                        </div>

                                        <div class="card-body">
                                            <span class="text-secondary font-weight-bold" overflow="auto"  data-toggle="tooltip" data-placement="right" title="{{ $trilha->titulo }}">
                                                {{ $trilha->titulo }}</span>
                                                <small class="d-block text-gray">
                                                    Autor: {{ ucfirst($trilha->name) }}
                                                    <span class="d-block mt-2">{{$trilha->escola}} </span>
                                                    <span class="d-block mt-2">{{$trilha->ciclo}} {{$trilha->etapa}} </span>
                                                    <span class="d-block mt-2"> {{$trilha->tag}} </span>
                                                </small>

                                                <form id="formExcluirTrilha{{ $trilha->id }}"
                                                action="{{ route('gestao.trilhas.destroy', ['idTrilha' => $trilha->id]) }}"
                                                method="post">@csrf</form>
                                        </div>

                                        <div class="card-footer bg-white">
                                            <div class="text-center">
                                                <a href="{{ route('gestao.trilhas.edit', ['idTrilha' => $trilha->id]) }}"
                                                class="btn btn-cards">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <button type="button" onclick="excluirTrilha({{ $trilha->id }})"
                                                        class="btn btn-cards">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {{--  @endif--}}
                            @endforeach

                            @if(count($trilhas) == 0)
                                <div class="col-12 mb-4 mx-auto px-4 px-sm-0">
                                    <div class="card p-3">
                                        <p class="mb-0">
                                            Você ainda não criou nenhuma trilha.
                                        </p>
                                    </div>
                                </div>
                            @endif

                        </div>
                        <div class="mt-0 ">
                            {{ $trilhas->links() }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </main>

@endsection

@section('bodyend')

    <script>

        function excluirTrilha(id) {
            if ($("#formExcluirTrilha" + id).length == 0)
                return;

            swal({
                title: 'Excluir trilha?',
                text: "Você deseja mesmo excluir esta trilha? Todo seu conteúdo será apagado!",
                icon: "warning",
                buttons: ['Não', 'Sim, excluir!'],
                dangerMode: true,
            }).then((result) => {
                if (result == true) {
                    $("#formExcluirTrilha" + id).submit();
                }
            });
        }



    </script>

@endsection
