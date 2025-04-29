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
<div class="row">
    <div class="col-12 text-justify">
        <p class="text-right">
            <span class="avatar"><img class="img-fluid" width="40px" src="{{$dados->usuario->avatar}}"></span>
            <b>{{$dados->usuario->nome}}</b>
        </p>
    </div>
    <div class="col-12 mb-3">
        <h5>{{$dados->pergunta}}</h5>
    </div>
    @for($k=1;$k<=$dados->qtd_alternativa;$k++)
        <div class="col-12 resposta mb-3" >
            <div class="row">
                <div class="col-1">
                    <div class=" letraQuestaoAberta" >{{$alternativas[$k]}}</div>
                </div>
                <div class="col-11">
                    {{$dados->getAttribute('alternativa_'.$k)}}
                </div>
            </div>
        </div>
    @endfor
    @if($dados->imagem != '')
    <div class="col-12 text-justify">
        <p>
            <b>Imagem:</b><br>
            <img src="{{$dados->link_imagem}}" class="img-fluid" width="250px">
        </p>
    </div>
    @endif
    <div class="col-12 text-right">
        <p>postado em: {{$dados->updated_at->format('d/m/Y')}}</p>
    </div>

</div>
