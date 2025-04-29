@extends('fr/master')
@section('content')
<style>

    .card-body {
        flex: 1 1 auto;
        padding: 1.25rem
    }

    .vertical-timeline {
        width: 100%;
        position: relative;
        padding: 1.5rem 0 1rem
    }

    .vertical-timeline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 67px;
        height: 100%;
        width: 4px;
        background: #e9ecef;
        border-radius: .25rem
    }

    .vertical-timeline-element {
        position: relative;
        margin: 0 0 1rem
    }

    .vertical-timeline--animate .vertical-timeline-element-icon.bounce-in {
        visibility: visible;
        animation: cd-bounce-1 .8s
    }

    .vertical-timeline-element-icon {
        position: absolute;
        top: 0;
        left: 60px
    }

    .vertical-timeline-element-icon .badge-dot-xl {
        box-shadow: 0 0 0 5px #fff
    }

    .badge-dot-xl {
        width: 18px;
        height: 18px;
        position: relative
    }

    .badge:empty {
        display: none
    }

    .badge-dot-xl::before {
        content: '';
        width: 10px;
        height: 10px;
        border-radius: .25rem;
        position: absolute;
        left: 50%;
        top: 50%;
        margin: -5px 0 0 -5px;
        background: #fff
    }

    .vertical-timeline-element-content {
        position: relative;
        margin-left: 90px;
        font-size: .8rem
    }

    .vertical-timeline-element-content .timeline-title {
        font-size: .8rem;
        text-transform: uppercase;
        margin: 0 0 .5rem;
        padding: 2px 0 0;
        font-weight: bold
    }

    .vertical-timeline-element-content .vertical-timeline-element-date {
        display: block;
        position: absolute;
        left: -90px;
        top: 0;
        padding-right: 10px;
        text-align: right;
        color: #adb5bd;
        font-size: .7619rem;
        white-space: nowrap
    }

    .vertical-timeline-element-content:after {
        content: "";
        display: table;
        clear: both
    }
</style>
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
@endphp
    <section class="section section-interna">
        <div class="container">
            <h2 class="title-page">
                <a href="{{ url()->previous() }}" title="Voltar" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                Relatório de ocorrências  <i>{{$avaliacao->titulo}}</i>
            </h2>
            <div class="row">
                <div class="col-12">
                    <div class="row d-flex justify-content-center">
                    <div class="col-md-12">
                        <div class="main-card mb-3">
                            <div class="card-body">
                                <h4 class="card-title text-center"><img src="{{$aluno->avatar}}" class="img-fluid rounded mr-2" width="50px"> {{$aluno->nome}}</h4>
                                <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                @php $i = 0; $qAberta = ''; $entrou=1;@endphp
                                @foreach($timeLine as $t)
                                    @if($i == 0)
                                        <div class="vertical-timeline-item vertical-timeline-element">
                                            <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-success"></i> </span>
                                                <div class="vertical-timeline-element-content bounce-in">
                                                    <h4 class="timeline-title">Início da Avaliação</h4>
                                                    <p style="margin-bottom: 6px"><b>Questão: </b>{{$t['questao_titulo']}}</p>
                                                    <p style="margin-bottom: 60px"></p>

                                                    <span class="vertical-timeline-element-date">
                                                        {!!$t['data_hora']!!}
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <b><i class="fas fa-arrows-alt-v"></i>
                                                        {{$t['tempo_ativo']}}
                                                        </b>

                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif(isset($t['finalizado']) && $t['finalizado']== 1)
                                            <div class="vertical-timeline-item vertical-timeline-element">
                                                <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-success"></i> </span>
                                                    <div class="vertical-timeline-element-content bounce-in">
                                                        <h4 class="timeline-title">Finalizado</h4>
                                                        <p style="margin-bottom: 60px"></p>

                                                        <span class="vertical-timeline-element-date">
                                                        {!!$t['data_hora']!!}

                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                    @elseif( isset($t['sem_finalizar']) && $t['sem_finalizar']== 1)
                                            <div class="vertical-timeline-item vertical-timeline-element">
                                                <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-danger "> </i> </span>
                                                    <div class="vertical-timeline-element-content bounce-in">
                                                        <h4 class="timeline-title">Conexão Perdida</h4>
                                                        <p style="margin-bottom: 6px"> O aluno pode ter fechado a janela da prova, sem clicar no finalizar. </p>
                                                        <p style="margin-bottom: 60px"></p>

                                                        <span class="vertical-timeline-element-date"></span>
                                                    </div>
                                                </div>
                                            </div>
                                    @else

                                        <div class="vertical-timeline-item vertical-timeline-element">
                                            <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl @if($t['tempo_ativo']!='00:00:00' && $t['tempo_ativo']!= '') @if($t['resposta']!= '')badge-success @else badge-warning @endif @elseif($t['tempo_inativo']!='00:00:00' && $t['tempo_inativo']!= '') badge-danger @endif"> </i> </span>
                                                <div class="vertical-timeline-element-content bounce-in">
                                                    @if($t['resposta']!= '')
                                                        @if($t['qtd_resposta']>0)
                                                            <h4 class="timeline-title">Alterou a questão ({{$t['qtd_resposta']}}&ordf; vez)</h4>
                                                        @else
                                                            <h4 class="timeline-title">Respondeu a questão</h4>
                                                        @endif

                                                    @elseif($t['questao_id']!= $timeLine[$i-1]['questao_id'])
                                                        <h4 class="timeline-title">Trocou de questão</h4>
                                                    @elseif($t['tempo_inativo']!='00:00:00' && $t['tempo_inativo']!= '')
                                                        <h4 class="timeline-title">Saiu da tela na questão</h4>
                                                    @elseif( $timeLine[$i-1]['tempo_inativo']!='00:00:00' && $timeLine[$i-1]['tempo_inativo']!= '')
                                                        <h4 class="timeline-title">Voltou para a tela na questão</h4>
                                                    @elseif($t['questao_tipo'] == 'd')
                                                        @if( $qAberta != $t['questao_id'] || $entrou == 1)
                                                            @php $qAberta = $t['questao_id']; $entrou = 0; @endphp
                                                            <h4 class="timeline-title">Entrou para digitar a questão</h4>
                                                        @else
                                                            @php $qAberta = $t['questao_id']; $entrou = 1; @endphp
                                                            <h4 class="timeline-title">Saiu de digitar a questão</h4>
                                                        @endif
                                                    @endif

                                                    <p style="margin-bottom: 6px"><b>Questão: </b>{{$t['questao_titulo']}}</p>

                                                    @if($t['resposta']!= '')
                                                        <p style="margin-bottom: 6px"><b>Resposta: </b>
                                                            @if($t['questao_tipo'] == 'd')
                                                                <?php echo nl2br($t['resposta']) ?>
                                                            @else
                                                                {{$alternativas[$t['resposta']]}}
                                                            @endif
                                                        </p>
                                                    @endif

                                                    <p style="margin-bottom: 60px"></p>

                                                    <span class="vertical-timeline-element-date">
                                                        {!!$t['data_hora']!!}
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <b><i class="fas fa-arrows-alt-v"></i>
                                                        @if($t['tempo_ativo']!='00:00:00' && $t['tempo_ativo']!= '')
                                                            {{$t['tempo_ativo']}}
                                                        @elseif($t['tempo_inativo']!='00:00:00' && $t['tempo_inativo']!= '')
                                                            {{$t['tempo_inativo']}}
                                                        @endif
                                                        </b>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @php $i++; @endphp
                                @endforeach
                                    <!--
                                    <div class="vertical-timeline-item vertical-timeline-element">
                                        <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-danger"> </i> </span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <h4 class="timeline-title">Discussion with team about new product launch</h4>
                                                <p>meeting with team mates about the launch of new product. and tell them about new features</p> <span class="vertical-timeline-element-date">6:00 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="vertical-timeline-item vertical-timeline-element">
                                        <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-primary"> </i> </span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <h4 class="timeline-title text-success">Discussion with marketing team</h4>
                                                <p>Discussion with marketing team about the popularity of last product</p> <span class="vertical-timeline-element-date">9:00 AM</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="vertical-timeline-item vertical-timeline-element">
                                        <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-success"> </i> </span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <h4 class="timeline-title">Purchase new hosting plan</h4>
                                                <p>Purchase new hosting plan as discussed with development team, today at <a href="javascript:void(0);" data-abc="true">10:00 AM</a></p> <span class="vertical-timeline-element-date">10:30 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="vertical-timeline-item vertical-timeline-element">
                                        <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-warning"> </i> </span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <p>Another conference call today, at <b class="text-danger">11:00 AM</b></p>
                                                <p>Yet another one, at <span class="text-success">1:00 PM</span></p> <span class="vertical-timeline-element-date">12:25 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="vertical-timeline-item vertical-timeline-element">
                                        <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-warning"> </i> </span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <p>Another meeting with UK client today, at <b class="text-danger">3:00 PM</b></p>
                                                <p>Yet another one, at <span class="text-success">5:00 PM</span></p> <span class="vertical-timeline-element-date">12:25 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="vertical-timeline-item vertical-timeline-element">
                                        <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-danger"> </i> </span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <h4 class="timeline-title">Discussion with team about new product launch</h4>
                                                <p>meeting with team mates about the launch of new product. and tell them about new features</p> <span class="vertical-timeline-element-date">6:00 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="vertical-timeline-item vertical-timeline-element">
                                        <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-primary"> </i> </span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <h4 class="timeline-title text-success">Discussion with marketing team</h4>
                                                <p>Discussion with marketing team about the popularity of last product</p> <span class="vertical-timeline-element-date">9:00 AM</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="vertical-timeline-item vertical-timeline-element">
                                        <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-success"> </i> </span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <h4 class="timeline-title">Purchase new hosting plan</h4>
                                                <p>Purchase new hosting plan as discussed with development team, today at <a href="javascript:void(0);" data-abc="true">10:00 AM</a></p> <span class="vertical-timeline-element-date">10:30 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="vertical-timeline-item vertical-timeline-element">
                                        <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-warning"> </i> </span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <p>Another conference call today, at <b class="text-danger">11:00 AM</b></p>
                                                <p>Yet another one, at <span class="text-success">1:00 PM</span></p> <span class="vertical-timeline-element-date">12:25 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                    -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>

        </div>
    </section>
@stop
