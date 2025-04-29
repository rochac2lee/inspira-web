@if(count($dados)>0)
<style>
    .avaliado {
        background-color: #F8AD18!important;
        color: #FFFFFF;
    }
    .neutro {
        background-color: #f7f7f7!important;
    }
</style>

    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th width="20%">Aluno</th>
                @php $i=1; @endphp
                @foreach($dados[0]['perguntas'] as $k => $d)
                    <th class="text-center">
                        P{{$i}}
                    </th>
                    @php $i++; @endphp
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($dados as $d)
                <tr>
                    <td width="25%">
                        @if($d['nome_completo'] != ''){{$d['nome_completo']}}@else{{$d['name']}}@endif
                    </td>
                    @php $i=1; @endphp
                    @foreach($dados[0]['perguntas'] as $p)
                        <td class="text-center @if(count($p)>0) avaliado @else neutro @endif" >
                            @if(count($p)>0)
                                <a href="javascript:void(0)" onclick="corrigir({{$i}},{{$p[4]}},{{$d['user_id']}})"><b><i class="fas fa-exclamation-triangle"></i> Corrigir</b><br>
                                corrigir agora essa questão.</a>
                            @else
                                <i class="fas fa-thumbs-up"></i> Corrigido
                            @endif
                        </td>
                        @php $i++; @endphp
                    @endforeach
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
                    Sua avaliação não possui  <b>Questões abertas</b>,
                    <br>ou você já corrigiu todas elas.
                </p>
            </div>
            <div class="card-footer text-muted"></div>
        </div>
    </div>
@endif
