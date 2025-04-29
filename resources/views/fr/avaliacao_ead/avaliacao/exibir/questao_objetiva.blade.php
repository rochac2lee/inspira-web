

<!-- RESPOSTAS --->
<div class="container mt-4">
    <div class="row justify-content-md-center">
        <div class="col-md-12 grid-item">
            <div class="questao text-justify text-break text-muted">
                <div class="row">
                    <div class="col-12 mb-1">
                        #{{$p->id}}
                        @if($p->fonte!='')
                            ({{$p->fonte}})
                        @endif
                        {!!$p->pergunta!!}
                    </div>
                @php $j=1; @endphp
                @for($k=1;$k<=$p->qtd_alternativa;$k++)

                    <div class="col-12 resposta mb-3" >
                        <div class="row">
                            <div class="col-1">
                                <div id="questaoObjetiva{{$p->id.$j}}"  class="questaoObjetiva{{$p->id}} letraQuestaoAberta" resposta="" style="cursor: pointer;" onclick="marcaAlternativa({{$p->id}}, this, {{$j}})">{{$alternativas[$j]}}</div>
                            </div>
                            <div class="col-11" style="cursor: pointer;" onclick="$('#questaoObjetiva{{$p->id.$j}}').click()">
                                {!!$p->getAttribute('alternativa_'.$k);!!}
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
