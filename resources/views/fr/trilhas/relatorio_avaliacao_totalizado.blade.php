@extends('fr/master')
@section('content')
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/css/style_avaliacao_online_v2.css">
    <script type="text/javascript" src="{{url('fr/includes/froala_editor/js/plugins/froala_wiris/integration/WIRISplugins.js?viewer=image')}}"></script>

    <style>
        .card-header.errado {
            background-color: #fad7d7!important;
            color: #a72828;
        }
        .card-header.certo {
            background-color: #d7fad8!important;
            color: #1b692b;
        }
        .card-header.neutro {
            font-size: 12px;
        }

        .letraQuestaoAberta {
            border: 1px solid;
            border-radius: 100%;
            width: 20px;
            height: 20px;
            text-align: center;
            margin: 0 5px 0 0;
            float: left;
        }
        .corretaAlternativa{
            background-color: #a8f986;
        }
    </style>
    <section class="section section-interna" style="padding-bottom: 15px" >
        <div class="container">
            <h2 class="title-page">
                <a href="{{ url()->previous() }}" title="Voltar" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                Avaliação EAD - {{$avaliacao->titulo}}
            </h2>
        </div>

        <div class="container">
            <div class="avaliacao-online mt-0">
                <div class="pergunta text-center">
                    <p><img src="{{config('app.cdn')}}/fr/imagens/fim.png" style="width: 50px;"></p>
                    <div class="mt-4">Parabéns, você finalizou a Avaliação on-line!</div>
                    <div class="mt-3 fs-14">Que tal conferir como você se saiu?</div>
                    <div class="mb-4"></div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 bg-light border-right pt-4 pb-4 pl-4 pr-5">
                        <h4 class="pb-3 border-bottom mb-4">Placar final</h4>
                        <div class="fs-13 mt-2 text-center mb-2"><strong>{{$dados->qtd_questoes_respondida}}</strong> de <strong>{{$dados->qtd_questoes_total}}</strong> questões respondidas</div>
                        <div class="mb-4 w-100">
                            <div class="fs-13 mt-2 mb-2">Adequadas / Não adequadas</div>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{$dados->porcentagem_acerto}}%;" aria-valuenow="{{$dados->porcentagem_acerto}}" aria-valuemin="0" aria-valuemax="100">{{(int)$dados->porcentagem_acerto}}%</div>
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{$dados->porcentagem_erro}}%;" aria-valuenow="{{$dados->porcentagem_erro}}" aria-valuemin="0" aria-valuemax="100">{{(int)$dados->porcentagem_erro}}%</div>
                            </div>
                            {{--
                            <div class="fs-13 mt-3 mb-2">Aproveitamento</div>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{$dados->porcentagem_acerto_peso}}%;" aria-valuenow="{{$dados->porcentagem_acerto_peso}}" aria-valuemin="0" aria-valuemax="100">{{(int)$dados->porcentagem_acerto_peso}}%</div>
                                <div class="progress-bar bg-secondary" role="progressbar" style="width: {{$dados->porcentagem_erro_peso}}%;" aria-valuenow="{{$dados->porcentagem_erro_peso}}" aria-valuemin="0" aria-valuemax="100">{{(int)$dados->porcentagem_erro_peso}}%</div>
                            </div>
                            --}}
                        </div>

                        <div class="col border p-2 text-center statsPerformace">
                            <label class="font-weight-bold mt-2">Status de Performance</label>
                            <div class="row">
                                <div class="col col-md-12"><div class="a a1"><label> {{number_format($dados->peso_total_acerto+$dados->peso_total_erro,2,',','.')}} </label>Peso da avaliação</div></div>
                                <div class="col col-md-6"><div class="a a1"><label>{{$dados->qtd_acerto}}</label>Respostas<br>adequadas</div></div>
                                <div class="col col-md-6"><div class="a a2"><label>{{$dados->qtd_erro}}</label>Respostas<br>não adequadas</div></div>
                                <div class="col col-md-6"><div class="a a3"><label>{{$dados->qtd_em_branco}}</label>Respostas<br>em branco</div></div>
                                <div class="col col-md-6"><div class="a a4"><label>{{$dados->tempo_total}}</label> Tempo total nesta avaliação (h:m:s)</div></div>
                                <div class="col col-md-12"><div class="a a4"><label>{{number_format($dados->peso_total_acerto,2,',','.')}}</label>Sua nota / <br>Seu conceito<br> </div></div>
                            </div>
                        </div>
                            <a href="{{ url()->previous() }}" class="btn btn-default mt-0 float-right ml-2 mt-4">Voltar para o início</a>
                    </div>
                    <div class="col-md-6 pl-5">
                        <div class="pt-4">
                            <h4 class="pb-3 border-bottom mb-4">Questões</h4>
                        </div>
                        <div class="col-md-12 p-0">
                            @php
                                $i=1;
                                $questoes = unserialize($dados->questoes);
                            @endphp
                            @foreach($questoes as $p)
                                @php
                                    $pergunta =  strip_tags($p->pergunta);
                                @endphp
                                <div class="card mb-3 p-0">
                                    <div class="card-header font-weight-bold  @if($p->eh_correto == 1) certo @elseif($p->tipo=='o' || ($p->eh_correto == 0 && $p->eh_correto != '') ) errado @else neutro @endif  ">
                                        {{$i}}. {{substr($pergunta,0,60)}}@if(strlen($pergunta)>60)... @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6 text-center">
                                                @if($p->peso_avaliado === '')
                                                    <b>Aguardando correção</b> <br>
                                                @else
                                                    <b>{{number_format($p->peso_avaliado, 2, ',','.')}}</b> de <b>{{number_format($p->peso, 2, ',','.')}}</b> <br>
                                                @endif
                                                Nota / Conceito
                                            </div>

                                            <div class="col-6 text-center">
                                                <button class="btn btn-link btn-sm mt-1 mb-1" onclick="$('#modalQuestao{{$p->id}}').modal('show');"><i class="fas fa-eye"></i> Veja a pergunta</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $i++;
                                @endphp
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @php
        $alternativas = [
            '1' => 'A',
            '2' => 'B',
            '3' => 'C',
            '4' => 'D',
            '5' => 'E',
            '6' => 'F',
            '7' => 'G',
        ];
        $i=1;
    @endphp
    <!-- Exibir questao -->
    @foreach($questoes as $q)
    <div class="modal fade" id="modalQuestao{{$q->id}}" tabindex="-1" role="dialog" aria-labelledby="modalQuestao" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" >{{$avaliacao->titulo}}</h5>
                </div>
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-12 text-center">
                            <span style="color: #757575; font-size: 24px">Questão {{$i}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if($q->tipo == 'o')
                                @include('fr/avaliacao/relatorio/rel_questao_objetiva')
                            @else
                                @include('fr/avaliacao/relatorio/rel_questao_aberta')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        @php $i++; @endphp
    @endforeach
    <!-- FIM Exibir questao -->
@stop
