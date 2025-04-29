<!-- RESPOSTAS --->
<div class="container mt-4">
    <div class="row justify-content-md-center">
        <div class="col-md-12 grid-item">
            <div class="questao text-justify text-break text-muted">
                <div class="row ">
                    <div class="col-12 mb-1">
                        {!!$q->pergunta!!}
                    </div>
                    @if( isset($q->eh_correto) )
                    <div class="col-12 resposta mb-3" style="font-size: 14px;" >
                        <p><b>Sua resposta:</b></p>
                        <p>
                            @if($q->resposta!= '')
                                <?php echo nl2br($q->resposta) ?>
                            @else
                                Não respondida.
                            @endif
                        </p>
                            @if($q->eh_correto == 1)
                                @if($q->peso == $q->peso_avaliado)
                                    <p class="text-center text-success"  style="font-size: 18px"><b>Resposta adequada!</b></p>
                                @else
                                    <p class="text-center text-success"  style="font-size: 18px"><b>Resposta parcialmente adequada!</b></p>
                                @endif
                            @elseif($q->eh_correto == 0 && $q->eh_correto != '')
                                <p class="text-center text-danger"  style="font-size: 18px"><b>Resposta inadequada!</b></p>
                            @else
                                <p class="text-center text-warning"  style="font-size: 18px"><b>Aguardando correção!</b></p>
                            @endif

                            @if($q->feedback!='')
                                <p style="font-size: 14px">
                                    <b>Feedback do docente:</b> <br>
                                    <?php echo nl2br($q->feedback) ?>
                                </p>
                            @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
