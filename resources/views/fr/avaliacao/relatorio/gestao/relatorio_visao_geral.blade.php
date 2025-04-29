@if(count($dados['lista'])>0)
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/css/style_avaliacao_online_v2.css">
<style>
    .errado {
        background-color: #fad7d7!important;
        color: #a72828;
    }
    .certo {
        background-color: #d7fad8!important;
        color: #1b692b;
    }
    .neutro {
        background-color: #f7f7f7!important;
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
<table class="table table-bordered">
    <tbody>
            <tr>
                <th>Aluno</th>
                <th class="text-center">Pontuação Tempo<br><small>(H:m:s)</small></th>
                @php $i=1; @endphp
                @foreach($dados['totalizador'] as $d)
                    <th class="text-center">
                        <a href="javascript:void(0)" title="Visualizar" onclick="$('#modalQuestao{{$d['id']}}').modal('show');"> P{{$i}} <i class="fas fa-eye"></i></a>
                        <br>
                        <h6 style="margin-bottom: 0px;"><span class="badge badge-secondary"> {{number_format($d['valor'],0,',','.')}}%</span></h6>
                    </th>
                    @php $i++; @endphp
                @endforeach
            </tr>
        @foreach($dados['lista'] as $d)
            <tr>
                <td width="30%">
                    @if($d['nome_completo'] != ''){{$d['nome_completo']}}@else{{$d['name']}}@endif
                    @php /*<a style="color: #0d8186" href="{{url('/gestao/avaliacao/relatorio/online/'.$avaliacao->id.'/ocorrencia/'.$d['user_id'])}}"><i class="fas fa-satellite-dish"></i> Ocorrências</a>*/ @endphp
                </td>
                <td width="10%" class="text-center">
                    <i class="fab fa-medapps"></i> <b>{{number_format($d['pontuacao'],2,',','.')}}</b> <br>
                    <small>({{number_format($d['porcentagem_acerto'],0,',','.')}}%)</small><br>
                    <small><i class="far fa-clock"></i> {{$d['tempo']}}</small>
                </td>
                @php $i=1; @endphp
                @foreach($d['perguntas'] as $p)
                    <td class="text-center @if(isset($p[0])) @if($p[0]==1) certo @elseif($p[3]=='o' || ($p[0]===0)) errado @else neutro @endif @endif" @if($p[0]=='' && $p[3]!='o')style="cursor: pointer" onclick="corrigir({{$i}},{{$p[4]}},{{$d['user_id']}})" @endif>
                        @if(isset($p[0]))
                            @if($p[0]==1)
                                <i class="fa fa-check" aria-hidden="true"></i>
                            @elseif($p[3]=='o' || ($p[0]===0))
                                <i class="fa fa-times" aria-hidden="true"></i>
                            @else
                                <i class="fas fa-hourglass-half"></i>
                            @endif
                                <br> <small><i class="far fa-clock"></i> {{$p[1]}}</small>
                                @php //<br> <small><i class="fas fa-clock"></i> {{$p[2]}}</small> @endphp
                                <br> <small><i class="fab fa-medapps"></i> <b>{{number_format((float)$p[5], 2, ',', '.')}}</b> de <b>{{number_format((float)$p[6], 2, ',', '.')}}</b></small>
                        @endif
                    </td>
                    @php $i++; @endphp
                @endforeach
            </tr>
        @endforeach

    </tbody>
</table>
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
    $questoes = unserialize($avaliacao->perguntas);
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
@else
    <div class="col">
        <div class="card text-center">
            <div class="card-header"></div>
            <div class="card-body">
                <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado.</h5>
                <p class="card-text mb-2">
                    <b>Nenhum aluno finalizou a avaliação.</b>
                </p>
            </div>
            <div class="card-footer text-muted"></div>
        </div>
    </div>
@endif
