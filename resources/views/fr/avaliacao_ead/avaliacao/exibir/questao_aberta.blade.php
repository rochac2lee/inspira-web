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
                    <div class="col-12 resposta mb-3" >
                        <textarea id="questaoAberta{{$p->id}}" class="form-control" onblur="marcaAberta({{$p->id}})" rows="10" placeholder="Escreva aqui sua resposta!"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
