@if(count($dados)>0)
<table class="table table-hover table-bordered">
    <tbody>
        @foreach($dados as $d)
            @php
                $perc_acerto = number_format($d->porcentagem_acerto,0,',','.');
            @endphp
            <tr>
                <td width="25%">
                    @if($d->nome_completo != ''){{$d->nome_completo}}@else{{$d->name}}@endif
                    @php /* <a style="color: #0d8186" href="{{url('/gestao/avaliacao/relatorio/online/'.$avaliacao->id.'/ocorrencia/'.$d['user_id'])}}"><i class="fas fa-satellite-dish"></i> Ocorrências</a>*/ @endphp
                </td>
                <td width="25%" class="text-center">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="background-color: #d7fad8; color: #1b692b; width: {{$d->porcentagem_acerto}}%" aria-valuenow="{{$d->porcentagem_acerto}}" aria-valuemin="0" aria-valuemax="100"><b>@if($perc_acerto>0){{$perc_acerto}}%@endif</b></div>
                        <div class="progress-bar" role="progressbar" style="background-color: #fad7d7; color: #a72828; width: {{$d->porcentagem_erro}}%" aria-valuenow="{{$d->porcentagem_erro}}" aria-valuemin="0" aria-valuemax="100"><b>@if(100-$perc_acerto>0){{100-$perc_acerto}}%@endif</b></div>
                    </div>
                    <small><b>{{$d->qtd_acerto}}</b> respostas adequadas / <b>{{$d->qtd_erro}}</b> respostas não adequadas</small>
                </td>
                <td class="text-center">{{$perc_acerto}}% <small><br>de acerto(s)</small></td>
                <td class="text-center">{{number_format($d->peso_total_acerto,0,',','.')}}<small><br>nota / conceito</small></td>
                <td class="text-center">{{$d->qtd_questao_para_avaliar}} <small><br>questão(ões) aguardando correção</small></td>
                <td class="text-center">{{$d->tempo_ativo}} <small><br>tempo ativo</small></td>
                <td class="text-center">{{$d->tempo_inativo}} <small><br>tempo inativo</small></td>
                <td class="text-center">{{$d->tempo_total}} <small><br>tempo total</small></td>
            </tr>
        @endforeach
    </tbody>
</table>
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
