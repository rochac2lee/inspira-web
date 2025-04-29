

<!-- RESPOSTAS --->
<div class="container mt-4">
    <div class="row justify-content-md-center">
        <div class="col-md-12 grid-item">
            <div class="questao text-justify text-break text-muted">
                <div class="row">
                    <div class="col-12 mb-1">
                        @if($p->fonte!='')
                            ({{$p->fonte}})
                        @endif
                        {!!$p->pergunta!!}
                    </div>
                @php $j=1; @endphp
                @for($k=1;$k<=$p->qtd_alternativa;$k++)

                    @if(isset($resultado[$p->id]) && $resultado[$p->id] == $j)
                        <script>
                            $(document).ready(function(){
                                marcaAlternativa({{$p->id}}, $('#questaoObjetiva{{$p->id.$j}}'), {{$j}})
                            });
                        </script>
                    @endif
                    <div class="col-12 resposta mb-3" >
                        <div class="row">
                            <div class="col-1">
                                <div id="questaoObjetiva{{$p->id.$j}}"  class="questaoObjetiva{{$p->id}} letraQuestaoAberta" resposta="" style="cursor: pointer;" @if(isset($resultado[$p->id])) onclick="alert('Impossível alterar. Você marcou essa questão na tentativa anterior.')" @else onclick="marcaAlternativa({{$p->id}}, this, {{$j}})"@endif>{{$alternativas[$j]}} </div>
                            </div>
                            <div class="col-11" style="cursor: pointer;" onclick="$('#questaoObjetiva{{$p->id.$j}}').click()" >
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
