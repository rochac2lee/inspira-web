@extends('fr/master')
@section('content')
<section class="section section-interna">
            <div class="container">
                <h2 class="title-page">
                    @isset($ead)
                        <a href="{{ url('/ead/listar')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Trilha EAD - {{$dados->titulo}}
                    @else
                        <a href="{{ url('/trilhas/listar')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Trilha - {{$dados->titulo}}
                    @endisset
                </h2>
                <div class="list-grid-menu">
                    <form class="form-inline">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-secondary p-2 no-list-grid-btn active">
                                <input type="radio" name="options" id="option1" autocomplete="off" checked>
                                <i class="fas fa-th-large"></i>
                            </label>
                            <label class="btn btn-secondary list-grid-btn">
                                <input type="radio" name="options" id="option2" autocomplete="off">
                                <i class="fas fa-list"></i>
                            </label>
                        </div>
                    </form>
                </div>
                @isset($ead)
                    @if($matricula->porcentagem_acerto >= 70)
                        <div class="row section-grid">
                            <div class="col-12">
                                <h4 class="text-center text-success"><a href="{{url('/ead/certificado/'.$matricula->chave_validacao)}}" target="blank">Seu certificado está liberado, acesse aqui.</a></h4>
                            </div>
                        </div>
                    @endif
                @endisset
                <div class="row section-grid">
                    @if(count($dados->cursos)>0)
                        @foreach ($dados->cursos as $c)
                            <div class="col-md-3 grid-item mb-4">
                                @isset($ead)
                                    <a href="{{url('ead/roteiros/iniciarRoteiro/'.$c->id.'/'.$dados->id)}}"  class="wrap">
                                @else
                                    <a href="{{url('roteiros/iniciarRoteiro/'.$c->id.'/'.$dados->id)}}"  class="wrap">
                                @endisset
                                    <div class="card h-100 text-center">
                                        <div class="card-body">
                                            <div class="img mb-1">
                                                <img src="{{  $c->url_capa }}" />
                                                @if(auth()->user()->permissao != 'A')
                                                <button class="btn detalhes">Iniciar Curso</button>
                                                @else
                                                <button class="btn detalhes">Iniciar</button>
                                                @endif
                                            </div>
                                            <div class="text fs-13">
                                                <h6 class="title font-weight-bold mt-2">{{ $c->titulo }}</h6>
                                                <strong>Autor:</strong><br> {{ ($c->autor != '') ? $c->autor: $dados->user->name }}
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="fs-13 mb-2 text-left font-weight-bold">Progresso</div>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar" style="width: @if(isset($estatistica[$c->id])) {{$estatistica[$c->id]['perc']}}%; @else 0% @endif" aria-valuenow="@if(isset($estatistica[$c->id])) {{$estatistica[$c->id]['perc']}} @else 0 @endif" aria-valuemin="0" aria-valuemax="100">@if(isset($estatistica[$c->id])) {{$estatistica[$c->id]['perc']}}% @else 0% @endif</div>
                                            </div>
                                            <div class="fs-13 mt-2"><strong>@if(isset($estatistica[$c->id])) {{$estatistica[$c->id]['feito']}} @else 0 @endif</strong> de <strong>@if(isset($estatistica[$c->id])) {{$estatistica[$c->id]['total']}} @else 0 @endif</strong> aulas concluídos</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                        @if(isset($ead) && $dados->avaliacao)
                                <div class="col-md-3 grid-item mb-4">

                                        <div class="card h-100 text-center">
                                            @if($estatisticaTrilha[$trilhaId]['perc']>=100)
                                                @if($avaliacaoRealizada)
                                                    <a href="{{url('ead/avaliacao/resultado/')}}/{{$dados->avaliacao->id}}/{{$trilhaId}}"  class="wrap">
                                                @else
                                                    <a href="javascript:void(0)" onclick="modalIniciarAvaliacao()"  class="wrap">
                                                @endif
                                            @else
                                                <a href="javascript:void(0)"  class="wrap">
                                            @endif

                                            <div class="card-body">
                                                <div class="img mb-1">
                                                    <img src="{{config('app.cdn')}}/fr/imagens/provas_bimestrais1.png" />
                                                        @if($estatisticaTrilha[$trilhaId]['perc']>=100)
                                                            @if($avaliacaoRealizada)
                                                                <button class="btn detalhes">Ver resultado</button>
                                                            @else
                                                                <button class="btn detalhes">Iniciar avaliação</button>
                                                            @endif
                                                        @else
                                                            <button class="btn detalhes">Aguardando conclusão da Trilha</button>
                                                        @endif
                                                </div>
                                                <div class="text fs-13">
                                                    <h6 class="title font-weight-bold mt-2">{{ $dados->avaliacao->titulo }}</h6>
                                                    <strong>Autor:</strong><br> Editora Opet
                                                    @if($estatisticaTrilha[$trilhaId]['perc']>=100)
                                                        @if($avaliacaoRealizada)
                                                            <p><span id="badgeParcial" class="badge badge-warning" >Ver resultado</span></p>
                                                        @else
                                                            <p><span id="badgeParcial" class="badge badge-success" >Iniciar avaliação</span></p>
                                                        @endif
                                                    @else
                                                        <p><span id="badgeParcial" class="badge badge-warning" >Bloqueado, guardando finalização da trilha</span></p>
                                                    @endif
                                                </div>
                                            </div>

                                                </a>
                                            @if($estatisticaTrilha[$trilhaId]['perc']>=100 && $avaliacaoRealizada && $matricula->porcentagem_acerto < 70)
                                                <div class="card-footer">
                                                    <div class="fs-13 mb-3  font-weight-bold text-center text-danger">Você não conseguiu a nota mínima para seu certificado.</div>
                                                    @if($matricula->tentativas_avaliacao < 3)
                                                        @if(3 - $matricula->tentativas_avaliacao == 1)
                                                            <div class="fs-12 mb-0 text-center ">Resta apenas {{3 - $matricula->tentativas_avaliacao}} tentativa! <br> <button class="btn btn-sm btn-secondary" onclick="modalIniciarAvaliacao()"> Iniciar nova avaliação</button></div>
                                                        @else
                                                            <div class="fs-12 mb-0 text-center ">Restam apenas {{3 - $matricula->tentativas_avaliacao}} tentativas! <br> <button class="btn btn-sm btn-secondary" onclick="modalIniciarAvaliacao()"> Iniciar nova avaliação</button></div>
                                                        @endif
                                                    @else
                                                        <div class="fs-12 mb-0 text-center ">Você realizou a avaliação <b>{{$matricula->tentativas_avaliacao}} vezes</b></div>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="card-footer">
                                                <div class="fs-13 mb-2 text-left font-weight-bold">Progresso da Trilha</div>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar" style="width: {{$estatisticaTrilha[$trilhaId]['perc']}}%;" aria-valuenow="{{@$estatisticaTrilha[$trilhaId]['perc']}}" aria-valuemin="0" aria-valuemax="100">{{@$estatisticaTrilha[$trilhaId]['perc']}}%</div>
                                                </div>
                                                <div class="fs-13 mt-2"><strong>{{@$estatisticaTrilha[$trilhaId]['feito']}}</strong> de <strong>{{@$estatisticaTrilha[$trilhaId]['total']}}</strong> aulas concluídos</div>
                                            </div>
                                        </div>
                                </div>
                        @endisset
                    @else
                        <div class="col">
                            <div class="card text-center">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado</h5>
                                </div>
                                <div class="card-footer text-muted"></div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </section>
@if(isset($ead) && $dados->avaliacao)
<!-- Iniciar avaliacao -->
<div class="modal fade" id="formPublicar" tabindex="-1" role="dialog" aria-labelledby="formPublicar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Iniciar avaliação</h5>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="row">
                        <div class="col-12">
                            Você está prestes a iniciar a avaliação:<br>
                            <p><b id="tituloAvaliacao"></b></p>
                            <br><br>

                            <p ><b class="text-danger">Termos sobre a avaliação</b> </p>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                <button type="button" onclick="iniciarAvaliacao()" class="btn btn-success">Sim, iniciar</button>
            </div>
        </div>
    </div>
</div>
<!-- Iniciar avaliacao -->
@endif

<script type="text/javascript">

    var idMatricular;

    $('.list-grid-btn').click(function() {
        $('.grid-item').addClass('list-grid');
    });

    $('.no-list-grid-btn').click(function() {
        $('.grid-item').removeClass('list-grid');
    });
@if(isset($ead) && $dados->avaliacao)
    function modalIniciarAvaliacao()
    {

        $('#tituloAvaliacao').html( '{{$dados->avaliacao->titulo}}');
        $('#formPublicar').modal('show');
    }

    function iniciarAvaliacao()
    {
        window.location.href = '{{url("/ead/avaliacao/exibir")}}?a='+{{$dados->avaliacao->id}}+'&trilha='+{{$dados->id}};
    }
@endisset
</script>
@stop
