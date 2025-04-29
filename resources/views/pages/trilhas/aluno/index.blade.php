@extends('layouts.master')

@section('title', 'Trilhas')

@section('content')


    <main role="main">
        <div class="container trilhas-aluno">
            <div id="divMainMenu" class="col-12 col-md-11 mb-4 mx-auto mt-4" style="width: calc(100% - 1px); flex: inherit; transition: 0.3s all ease-in-out;">
                <div class="row d-flex align-items-center mb-4">
                    <div class="col-12 col-sm-9 pl-0">
                        <h2 class="mb-0">Trilhas</h2>
                    </div>

                    <div class="col-12 col-sm-3 d-flex justify-content-end">
                        @include('components.search-input', ['placeholder' => 'Buscar trilhas...'])
                    </div>
                </div>

                @if(Request::has('pesquisa'))
                    @include('components.search-response', ['pesquisa' => Request::get('pesquisa')])
                @endif


                <div class="row">
                    @foreach ($trilhas as $trilha)
                        @php
                            if($trilha->trilha_matricula){
                                $comportamentoBotao = 'onclick="window.location = '. route('trilhas.progresso', ['idTrilha' => $trilha->id]) .'"';
                            } else {
                                $comportamentoBotao = "data-toggle='modal' data-target='#modalMatricula_$trilha->id'";
                            }
                        @endphp

                        <div class="col-12 col-sm-6 col-md-4 mb-4">
                            <div class="card-curso shadow-sm bg-white rounded-10 pb-3 h-100">
                                <div class="w-100 py-5 capa-curso lazyload"
                                    data-bg="{{ $trilha->capa != '' ? config('app.cdn') .'/storage/uploads/trilhas/capas/'. $trilha->capa : '' }}"
                                    @if($trilha->trilha_matricula)
                                        onclick="window.location = '{{ route('trilhas.progresso', ['idTrilha' => $trilha->id]) }}'"
                                    @else
                                        data-toggle="modal" data-target="#modalMatricula_{{ $trilha->id }}"
                                    @endif
                                    >
                                </div>

                                <div class="px-3 py-1">

                                    <p class="text-dark font-weight-bold mt-2 text-truncate">
                                        <span class="fs-1 text-truncate"  data-toggle="tooltip" data-placement="right" title="{{ $trilha->titulo }}">{{ $trilha->titulo }}</span>
                                        <small class="text-small text-truncate">
                                            {{ ucfirst($trilha->descricao) }}
                                        </small>
                                        <small class="text-dark font-weight-bold">
                                            @if($trilha->trilha_matricula)
                                                {{ $trilha->qtd_concluido }} de {{ count($trilha->cursos) }} {{ucfirst($langCurso)}}s
                                            @else
                                                {{ count($trilha->cursos) }} {{ucfirst($langCursoP)}}
                                            @endif
                                        </small>
                                    </p>

                                    @if($trilha->matriculado)
                                        <p class="text-dark font-weight-bold mt-2 text-truncate">Progresso</p>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width:{{ $trilha->progresso }}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>



                        <div class="modal fade" tabindex="-1" id="modalMatricula_{{ $trilha->id }}" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-none bg-transparent">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card-curso bg-white rounded-top pb-3 h-100">
                                                    <div class="w-100 py-5 capa-curso lazyload"
                                                        data-bg="{{ $trilha->capa != '' ? config('app.cdn') .'/storage/uploads/trilhas/capas/'. $trilha->capa  : '' }}">
                                                    </div>

                                                    <div class="px-3 py-1">

                                                        <p class="text-dark font-weight-bold mt-2 text-truncate">
                                                            <span class="fs-1" data-toggle="tooltip" data-placement="right" title="{{ $trilha->titulo }}">{{ $trilha->titulo }}</span>
                                                            <small class="text-small">
                                                                {{ ucfirst($trilha->descricao) }}
                                                            </small>
                                                            <small class="text-dark font-weight-bold">
                                                                {{ count($trilha->cursos) }} {{ucfirst($langCursoP)}}
                                                            </small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <a class="btn btn-matricular col-12"
                                                    href="{{ route('trilhas.matricular', ['idTrilha' => $trilha->id]) }}">
                                                    Iniciar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    @endforeach

                    @if(count($trilhas) == 0)
                        <div class="w-100">
                            <div class="card p-3">
                                <h5>
                                   Não há nenhuma trilha
                                </h5>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

@endsection

@section('bodyend')

    <script>
    </script>

@endsection
