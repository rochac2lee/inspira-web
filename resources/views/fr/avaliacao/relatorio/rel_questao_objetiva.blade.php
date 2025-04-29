<!-- RESPOSTAS --->
<div class="container mt-4">
    <div class="row justify-content-md-center">
        <div class="col-md-12 grid-item">
            <div class="questao text-justify text-break text-muted">
                <div class="row">
                    <div class="col-12 mb-1">
                        {!!$q->pergunta!!}
                    </div>
                @php $j=1; @endphp
                @for($k=1;$k<=$q->qtd_alternativa;$k++)

                    <div class="col-12 resposta mb-3" >
                        <div class="row @if($q->correta == $k) border rounded-lg border-success @endif">
                            <div class="col-1">
                                <div @if($q->resposta == $k && $q->correta != $k)style="background-color: darkgrey;" @elseif($q->resposta == $k && $q->correta == $k)style="background-color: #a8f986;" @endif class="letraQuestaoAberta" >{{$alternativas[$j]}}</div>
                            </div>
                            <div class="col-11" >
                                {!!$q->getAttribute('alternativa_'.$k);!!}
                            </div>

                        </div>
                    </div>
                    @php $j++; @endphp
                @endfor
                </div>
            </div>
        </div>
    </div>
</div>
