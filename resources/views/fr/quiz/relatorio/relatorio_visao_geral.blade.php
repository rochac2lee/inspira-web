<style>
    .errado {
        background-color: #fad7d7!important;
        color: #a72828;
    }
    .certo {
        background-color: #d7fad8!important;
        color: #1b692b;
    }
</style>
<table class="table table-bordered">
    <tbody>
            <tr>
                <th>Estudante</th>
                <th class="text-center">Pontuação Tempo<br><small>(H:m:s)</small></th>
                @php $i=1; @endphp
                @foreach($dados['totalizador'] as $d)
                    <th class="text-center">
                        <a href="javascript:void(0)" title="Visualizar" onclick="exibirPergunta({{$d['id']}})"> P{{$i}} <i class="fas fa-eye"></i></a>
                        <br>
                        <h6 style="margin-bottom: 0px;"><span class="badge badge-secondary"> {{number_format($d['valor'],0,',','.')}}%</span></h6>
                    </th>
                    @php $i++; @endphp
                @endforeach
            </tr>
        @foreach($dados['lista'] as $d)
            <tr>
                <td width="30%">@if($d['nome_completo'] != ''){{$d['nome_completo']}}@else{{$d['name']}}@endif</td>
                <td width="10%" class="text-center">{{number_format($d['pontuacao'],0,',','.')}} <small>({{number_format($d['porcentagem_acerto'],0,',','.')}}%)<br><i class="far fa-clock"></i> {{$d['tempo']}}</small></td>
                @php $k = 0; @endphp
                @foreach($d['perguntas'] as $p)
                    <td class="text-center @if(isset($p[0])) @if($p[0]==1) certo @else errado @endif @endif">
                        @if(isset($p[0]))
                            @if($p[0]==1)
                                <i class="fa fa-check" aria-hidden="true"></i><br> <small><i class="far fa-clock"></i> {{$p[1]}}</small>
                            @else
                                <i class="fa fa-times" aria-hidden="true"></i><br> <small><i class="far fa-clock"></i> {{$p[1]}}</small>
                            @endif
                        @endif
                    </td>
                    @php $k++; @endphp
                @endforeach

            </tr>
        @endforeach
        @foreach($naoFinalizados as $d)
            <tr>
                <td width="30%">@if($d->nome_completo != ''){{$d->nome_completo}}@else{{$d->name}}@endif</td>
                    <td colspan="{{2+$k}}">
                        Não finalizado.
                    </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Exibir quiz -->
<div class="modal fade" id="formExibir" tabindex="-1" role="dialog" aria-labelledby="formExibir" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>

            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$quiz->titulo}}</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" id="conteudoExibirPergunta">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FIM Exibir quiz -->
<script>
    function exibirPergunta(id)
    {
        url = '{{url('gestao/quiz/relatorio-pergunta')}}?q={{$quiz->id}}&p='+id+'&f=1&frame=1&r=1';
        $('#conteudoExibirPergunta').html('<iframe style="border:none;" width="100%" height="600px" src="'+url+'"></iframe>')
        $('#formExibir').modal('show');
    }

    $('#formExibir').on('hidden.bs.modal', function () {
        $('#conteudoExibirPergunta').html('');
    });
</script>
