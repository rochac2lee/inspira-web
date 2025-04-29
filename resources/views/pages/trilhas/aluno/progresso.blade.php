
@extends('layouts.master')

@section('title', 'Trilha')

@section('content')

    <main role="main">
        <div class="container trilhas-progresso">
            <div id="divMainMenu" class="col-12 col-md-11 mb-4 px-0 mx-auto" style="width: calc(100% - 1px); flex: inherit; transition: 0.3s all ease-in-out;">

                <div class="row mb-3">
                    <div class="col-12">
                        <a class="text-primary" href="#"><small class="font-weight-bold">< Voltar</small></a>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-sm-12 col-md-6  mb-2 text-break">
                        <h4 class="text-dark font-weight-bold mb-2">{{ ucfirst($trilha->titulo) }}</h4>
                        <small class="text-small">
                            {{ ucfirst($trilha->descricao) }}
                        </small>
                        <small class="text-dark font-weight-bold">
                            {{ count($trilha->cursos) }} {{ucfirst($langCursoP)}}
                        </small>
                    </div>

                    <!-- <div class="w-100 d-none d-md-block"></div> -->

                    <div class="col-md-3 col-sm-12 mb-2">
                        @if($trilha->matriculado)
                            <button type="button" data-dismiss="modal" onclick="window.location='{{ route('trilhas.abandonar', [ $trilha->matriculado ]) }}'"
                                    class="btn btn-borda-vermelha col-sm-12">
                                Abandonar Trilha
                            </button>
                        @endif
                    </div>

                    <div class="col-md-3 col-sm-12 mb-2">
                        @include('components.search-input', ['placeholder' => 'Buscar na trilha...'])
                    </div>
                </div>

                    @if($trilha->matriculado)
                        <div class="barra-progresso-inicial ">
                            <div class="barra-progresso col-12" style="width:{{ $trilha->progresso }}%;"></div>
                        </div>
                    @endif



                @if(Request::has('pesquisa'))
                    @include('components.search-response', ['pesquisa' => Request::get('pesquisa')])
                @endif

                <div class="row mt-5">
                    @foreach ($cursos as $curso)
                        @if(isset($curso->curso))
                        <div class="col-12 col-sm-12 col-lg-3 mb-4">
                            <div class="card-curso shadow-sm bg-white rounded-10 h-100">
                                @php
                                    $temCapa = '';
                                    // Fundo do card
                                    $backgroundClass = ($curso->iniciado || $curso->atual) ? '' : 'fa fa-lock fa-4x curso-bloqueado';
                                    if(isset($curso->curso) && isset($curso->curso->capa))
                                    {
                                        $backgroundCurso = 'background-image: url('. config('app.cdn') .'/storage/uploads/cursos/capas/'. $curso->curso->capa .');';
                                        $temCapa = $backgroundCurso;
                                    }
                                    $style = $backgroundClass ? '' : $temCapa;

                                    // Opacidade texto
                                    $textClass = ($curso->iniciado || $curso->atual) ? '' : 'text-bloqueado';

                                    // botao Iniciar
                                    $cursoAtual = ($curso->atual && $trilha->matriculado) ? true : false;
                                   // $classeBotao = $cursoAtual && $curso->iniciado? 'btn-salvar btn-retomar' : 'btn-salvar';
                                   $classeBotao = 'btn-salvar btn-retomar';
                                   // $textoBotao = $cursoAtual && $curso->iniciado? 'Retomar' : 'Iniciar';
                                   $textoBotao = 'Acessar';
                                @endphp
                                <div class="w-100 py-5 capa-curso {{ $backgroundClass }}"
                                    style="{{ $style }}">
                                </div>

                                <div class="px-3 py-1 ">
                                    <p class="text-dark font-weight-bold mt-2  {{ $textClass }} text-truncate">
                                        <span class="fs-1 text-truncate" data-toggle="tooltip" data-placement="right" title="{{ $curso->curso->titulo }}">
                                            {{ $curso->curso->titulo }}
                                            @if($curso->concluido)
                                                <i class="fas fa-check-circle fa-fw ml-2 text-success"></i>
                                            @endif
                                        </span>
                                        <small class="text-small {{ $textClass }} text-truncate">
                                            @if(isset($curso->curso->_categoria[2])) {{ ucfirst($curso->curso->_categoria[2]) }} @endif
                                        </small>

                                    </p>
                                </div>

                                <div class="card-footer bg-white ">
                                    @if($cursoAtual && !$curso->concluido || 1==1)
                                        <a class="btn {{ $classeBotao }} btn-md bg-primary text-white mb-3 row d-flex justify-content-center"
                                            href="{{ route('curso', ['idCurso' => $curso->curso->id]) }}">
                                                {{ $textoBotao }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach

                    @if(count($cursos) == 0)
                        <div class="w-100">
                            <div class="card-curso shadow-sm bg-white rounded p-3">
                                <h5>
                                   Não há nenhum {{$langCurso}}
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
