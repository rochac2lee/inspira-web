<!-- RESPOSTAS --->
<div class="container mt-4">
    <div class="row justify-content-md-center">
        <div class="col-md-12 grid-item">
            <div class="questao text-justify text-break text-muted">
                <div class="row ">
                    <div class="col-12 mb-1">
                        {!!$q->pergunta!!}
                    </div>
                    <div class="col-12 resposta mb-3" style="font-size: 14px;" >
                        <p><b>Resposta:</b></p>
                        <p>
                            <?php echo nl2br($q->resposta) ?>
                        </p>

                    </div>
                    <div class="col-12 resposta mb-3" style="font-size: 14px;" >
                        <hr>
                        <form id="formCorrecao" action="{{url('/gestao/avaliacao/correcaoPergunta/salvar')}}" method="post">
                            @csrf
                            <input type="hidden" name="questao_id" value="{{$request['questao_id']}}">
                            <input type="hidden" name="user_id" value="{{$request['user_id']}}">
                            <input type="hidden" name="avaliacao_id" value="{{$request['avaliacao_id']}}">
                            <input type="hidden" id="correcaoPeso" value="{{$q->peso}}">
                            <div class="form-group">
                                <label>Correção *</label>
                                <select class="form-control"  id="corrigirAvaliacao" name="avaliacao" onchange="mudaPeso(this)" >
                                    <option value="">Selecione</option>
                                    <option value="1">Questão adequada</option>
                                    <option value="2">Questão parcialmente adequada </option>
                                    <option value="3">Questão não adequada</option>
                                </select>
                                <div class="invalid-feedback" id="FeedCorrigirAvaliacao"></div>
                            </div>
                            <div class="form-group" id="pesoParcial" style="display: none">
                                <label>Peso parcial * (<small>Valor máximo para essa questão é de <b>{{number_format($q->peso,2,',','.')}}</b></small>)</label>
                                <input type="number" class="form-control" id="correcaoPesoParcial"  name="peso_parcial" value="" max="{{$q->peso}}" min="0.1" step="0.1">
                                <div class="invalid-feedback" id="FeedCorrecaoPesoParcial"></div>
                            </div>
                            <div class="form-group">
                                <label>Feedback </label>
                                <textarea class="form-control" name="feedback"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function mudaPeso(elemento){
        if($(elemento).val() == 2){
            $('#pesoParcial').show();
        }else{
            $('#pesoParcial').hide();
        }
    }
</script>
