@extends('fr/master')
@section('content')
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
    <section class="section section-interna mb-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="pb-3 border-bottom mb-4">
                        <a href="{{url('/gestao/avaliacao')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Avaliação INterativa <i>#{{$avaliacao->id}} {{$avaliacao->titulo}}</i></h4>
                    <div class="shadow-sm font-weight-bold p-3 mb-5 bg-light rounded">
                        <h5>{{$avaliacao->titulo}}</h5>

                        <div class="container">
                            <div class="row">
                                <div class="col-sm">
                                    <p class="mt-3 font-weight-normal"><i class="fas fa-book fs-12"></i> <b>Componente Curricular:</b> {{$avaliacao->disciplina->titulo}}</p>
                                    <p class="font-weight-normal"><i class="fas fa-bookmark fs-12"></i> <b>Peso:</b> {{$avaliacao->peso}}</p>
                                </div>
                                <div class="col-sm">
                                    <p class="mt-3 font-weight-normal"><i class="fas fa-book-reader fs-12"></i> <b>Aplicação:</b> @if($avaliacao->aplicacao == 'o') Online @else Impressa @endif </p>
                                    <p class="font-weight-normal"><i class="fas fa-bookmark fs-12"></i> <b>Tipo:</b>  @if($avaliacao->tipo == 'p') Prova @elseif($avaliacao->tipo == 's') Simulado @else Lista @endif</p>
                                </div>
                                <div class="col-sm">
                                    <p class="mt-3 font-weight-normal"><i class="fas far fa-clock fs-12"></i> <b>Data Inicial:</b> {{$avaliacao->data_hora_inicial->format('d/m/Y H:i:s')}}</p>
                                    @if($avaliacao->data_hora_final != '')
                                        <p class="font-weight-normal"><i class="fas far fa-clock fs-12"></i> <b>Data Final:</b> {{$avaliacao->data_hora_final->format('d/m/Y  H:i:s')}}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="shadow-sm p-3 mb-3 bg-white rounded text-center">
                        <div>
                            <h2>{{$avaliacao->qtd_questao}}</h2>
                            <p><strong>Questões</strong></p>
                            <p>Número de questões incluídas na avaliação.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="shadow-sm p-3 mb-3 bg-white rounded text-center">
                        <div>
                            <h2>{{count($avaliacao->alunos)}}</h2>
                            <p><strong>Estudantes</strong></p>
                            <p>Número de estudantes apto para realização da avaliação.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    @if($avaliacao->aplicacao == 'o')
                        <div class="shadow-sm p-3 mb-3 bg-white rounded text-center">
                            <div>
                                <h2>{{count($avaliacao->placar)}}</h2>
                                <p><strong>Realizada</strong></p>
                                <p>Quantidade de avalições já realizadas.</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-3">
                    <div class="shadow-sm p-3 mb-3 bg-white rounded text-center">
                        <div>
                            @if($avaliacao->aplicacao == 'o')
                                @if( $avaliacao->data_hora_inicial->gte(date('Y-m-d H:i:s')) )
                                    <h2 class="text-secondary">Aguardando</h2>
                                @elseif( $avaliacao->data_hora_final->gte(date('Y-m-d H:i:s')) )
                                    <h2 class="text-warning">Em andamento</h2>
                                @else
                                    <h2 class="text-success">Finalizada</h2>
                                @endif
                            @else
                                <h2 class="text-success">Impressa</h2>
                            @endif

                           <!-- <p><strong>Período</strong></p>
                            <p>11/05/2020 16:30 à 18/05/2020 23:59</p>-->
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-4 pt-3">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-prova-tab" data-toggle="tab" href="#nav-prova" role="tab" aria-controls="nav-prova" aria-selected="true">Visualizar Avaliação</a>
                            <a class="nav-item nav-link" id="nav-alunos-tab" data-toggle="tab" href="#nav-alunos" role="tab" aria-controls="nav-alunos" aria-selected="false">Alunos</a>
                            <a class="nav-item nav-link" id="nav-gabarito-tab" data-toggle="tab" href="#nav-gabarito" role="tab" aria-controls="nav-gabarito" aria-selected="false">Gabarito</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show border border-top-0 p-2 active" id="nav-prova" role="tabpanel" aria-labelledby="nav-prova-tab">
                            <iframe src="{{url('/gestao/avaliacao/relatorio/impressao/'.$avaliacao->id)}}" name="conteudo" width="100%" height="800" border="0" frameborder="0"></iframe>
                        </div>
                        <div class="tab-pane fade border border-top-0" id="nav-alunos" role="tabpanel" aria-labelledby="nav-alunos-tab">
                            <section class="table-page w-100">
                                <div class="table-responsive table-hover">
                                    <table class="table table-striped">
                                        <thead class="thead-dark">
                                        <tr>
                                            @if(auth()->user()->permissao != 'P')
                                            <th scope="col">Escola</th>
                                            @endif
                                            <th scope="col">Turma</th>
                                            <th scope="col">Estudante</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($avaliacao->alunos as $a)
                                                <tr>
                                                    @if(auth()->user()->permissao != 'P')
                                                    <td>{{$a->escola}}</td>
                                                    @endif
                                                    <td>{{$a->ciclo}} / {{$a->ciclo_etapa}} - {{$a->titulo}}</td>
                                                    <td>{{$a->aluno}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>
                        <div class="tab-pane fade border border-top-0" id="nav-gabarito" role="tabpanel" aria-labelledby="nav-gabarito-tab">
                            <section class="table-page w-100">
                                <div class="table-responsive table-hover">
                                    <table class="table table-striped">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Questão</th>
                                            <th scope="col">Peso</th>
                                            <th scope="col">Resposta</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($perguntas as $p)
                                                <tr>
                                                    <td>
                                                        @php $per = strip_tags($p->pergunta); @endphp
                                                        {{$loop->index+1}} - {{substr($per,0,60)}}@if(strlen($per)>60)... @endif
                                                    </td>
                                                    <td>
                                                        {{$p->pivot->peso}}
                                                    </td>
                                                    @if($p->tipo == 'o')
                                                        <td>{{$alternativas[$p->correta]}}</td>
                                                    @else
                                                        <td><i>(discursiva)</i></td>
                                                    @endif
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
