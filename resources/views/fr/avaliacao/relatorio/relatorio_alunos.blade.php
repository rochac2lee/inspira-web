<table class="table table-hover table-bordered">
    <tbody>
        @foreach($dados as $d)
            @php
                $perc_acerto = number_format($d->porcentagem_acerto,0,',','.');
            @endphp
            <tr>
                <td width="30%">{{$d->nome}}</td>
                <td width="45%" class="text-center">
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{$d->porcentagem_acerto}}%" aria-valuenow="{{$d->porcentagem_acerto}}" aria-valuemin="0" aria-valuemax="100">@if($perc_acerto>0){{$perc_acerto}}%@endif</div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{$d->porcentagem_erro}}%" aria-valuenow="{{$d->porcentagem_erro}}" aria-valuemin="0" aria-valuemax="100">@if(100-$perc_acerto>0){{100-$perc_acerto}}%@endif</div>
                    </div>
                    <small><b>{{$d->qtd_acerto}}</b> respostas adequadas / <b>{{$d->qtd_erro}}</b> respostas não adequadas</small>
                </td>
                <td class="text-center">{{$perc_acerto}}% <small><br>de acerto(s)</small></td>
                <td class="text-center">{{number_format($d->pontuacao,0,',','.')}}<small><br>pontos</small></td>
                <td class="text-center">{{$d->tempo}} <small><br>tempo total</small></td>
            </tr>
        @endforeach
        @foreach($naoFinalizados as $d)
            <tr>
                <td width="30%">{{$d->nome}}</td>
                <td width="70%" colspan="4">
                    Não finalizado.
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
