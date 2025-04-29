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
    <style>
        .letraQuestaoAberta {
            border: 1px solid;
            border-radius: 100%;
            width: 20px;
            height: 20px;
            text-align: center;
            margin: 0 5px 0 0;
            float: left;
        }
    </style>

    <section class="section section-interna" style="padding-top: 50px">
        <div class="container">
            @include('fr.agenda.menu')

            <div class="row pb-4 border-top pt-4">
                <div class="col-md-12">
                    <h3>
                        <a href="{{url()->previous()}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Resultado da enquete
                    </h3>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-3 text-center">
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{$percRespondido}}%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">{{number_format($percRespondido,2 ,',','.')}}% Respondido</div>
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: {{$percAguardando}}%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">{{number_format($percAguardando,2 ,',','.')}}% Aguardando resposta</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-3 text-center">
                    <h5><b>{{$enquete->pergunta}}</b></h5>
                </div>
                @for($k=1;$k<=$enquete->qtd_alternativa;$k++)
                    <div class="col-12 resposta mb-3" >
                        <div class="row p-2 @if($respondido['maior'] != 0 && isset($respondido[$k]) && $respondido['maior'] == $respondido[$k])border-success border @endif"  >
                            <div class="col-1 pr-0 text-center">
                                @if(isset($respondido[$k]))
                                    <span><b>
                                        {{ number_format(($respondido[$k]*100)/ $enquete->respondidos[0]->qtd, 2,',','.')}}%
                                    </b></span>
                                    <small><br>
                                        {{$respondido[$k]}} votos
                                    </small>
                                @else
                                    <span><b>0%</b></span>
                                    <small><br>
                                        0 votos
                                    </small>
                                @endif
                            </div>
                            <div class="col-auto">
                                <div class=" letraQuestaoAberta" >{{$alternativas[$k]}}</div>
                            </div>
                            <div class="col-8">
                                {{$enquete->getAttribute('alternativa_'.$k)}}
                            </div>
                        </div>
                    </div>
                @endfor
                @if($enquete->imagem != '')
                    <div class="col-12 text-justify">
                        <p>
                            <b>Imagem:</b><br>
                            <img src="{{$enquete->link_imagem}}" class="img-fluid" width="250px">
                        </p>
                    </div>
                @endif
                <div class="col-12 text-right">
                    <p>postado em: {{$enquete->updated_at->format('d/m/Y')}}</p>
                </div>
            </div>

        </div>
    </section>

    <!-- VISUALIZAR -->
    <div class="modal fade" id="formVisualizar" tabindex="-1" role="dialog" aria-labelledby="formVisualizar" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title">Visualizar Documentos Recebidos
                    <small><br> <span id="nomeEstudante"></span> - <span id="nomeTurma"></span> - <span id="nomeCiclo"></span></small>
                    </h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12" id="corpoVisualizarComunidado">

                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM VISUALIZAR -->

    <script>
        function modalVisualizar(idAluno, idTurma, idDocumento, aluno, turma, ciclo)
        {
            $.ajax({
                url: '{{url('gestao/agenda/documentos/getRecebidos')}}',
                type: 'post',
                dataType: 'json',
                data: {
                    _token: '{{csrf_token()}}',
                    aluno_id: idAluno,
                    turma_id: idTurma,
                    documento_id: idDocumento,
                },
                success: function (data) {
                    $('#nomeEstudante').html(aluno);
                    $('#nomeTurma').html(turma);
                    $('#nomeCiclo').html(ciclo);
                    $('#corpoVisualizarComunidado').html(data);
                    $('#formVisualizar').modal('show');
                },
                error: function () {
                    swal("", "Não foi possível carregar a solicitação de documentos.", "error");
                }
            });

        }
    </script>
@stop
