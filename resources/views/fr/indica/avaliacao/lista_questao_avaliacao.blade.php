@foreach($dados as $d)
	<li id="questao{{$d->id}}" class="list-group-item rounded ">
        <div class="form-group row">
            <div class="col-9 mb-1">
                <button type="button" class="btn btn-sm btn-outline-secondary disabled fs-9 p-1 font-weight-bold">#{{$d->id}}</button>
                @if($d->codigo!='')<button type="button" class="btn btn-sm btn-outline-secondary disabled fs-9 p-1 font-weight-bold">{{$d->codigo}}</button>@endif
                <button type="button" class="btn btn-sm btn-outline-secondary disabled fs-9 p-1"><i class="fas fa-layer-group"></i> {{@$d->cicloEtapa->ciclo}} / {{@$d->cicloEtapa->ciclo_etapa}}</button>
                <button type="button" class="btn btn-sm btn-outline-secondary disabled fs-9 p-1"><i class="fas fa-book"></i> {{$d->disciplina->titulo}}</button>
                <button type="button" class="btn btn-sm btn-outline-secondary disabled fs-9 p-1"><i class="fas fa-ruler-vertical"></i> {{$dificuldade[$d->dificuldade]}}</button>
                <button type="button" class="btn btn-sm btn-outline-secondary disabled fs-9 p-1"><i class="fas fa-lightbulb"></i> {{@$d->bncc->codigo_habilidade}}</button>
                @if($d->fonte)
                    <button type="button" class="btn btn-sm btn-outline-secondary disabled fs-9 p-1">{{$d->fonte}}</button>
                @endif
                <button type="button" class="btn btn-sm btn-outline-secondary disabled fs-9 p-1"><i class="fas fa-user"></i> {{$d->usuario->name}}</button>

            </div>
            <div class="col-3 spanPeso{{$d->id}} divPesoQuestaoAdicionada text-center"  style="display: none">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" onclick="diminuiPesoQuestao(this)" class="btn btn-sm btn-secondary "><i class="fas fa-minus"></i></button>
                    </div>
                    <input id="pesoQuestao{{$d->id}}" name="peso_questao[{{$d->id}}]" class="form-control form-control-sm pesoQuestaoAdicionada inputNumberReadOnly" type="number" step="0.5" min="0.5" value="" style="text-align:center;">
                    <div class="input-group-append">
                        <button type="button" onclick="aumentaPesoQuestao(this)" class="btn btn-sm btn-secondary "><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <p style="font-size: 10px" class="mb-1 mt-1">Peso da questão</p>
            </div>
        </div>
	    <div class="questao text-justify text-break text-muted fs-10">
	        <p>
                {{substr(strip_tags($d->pergunta),0,100)}}...
	        </p>
	    </div>
        <div class="btn_degrade w-100 border-top text-right pt-1">
            <div class="avaliacao_degrade"></div>
	        <button type="button" class="btn btn-sm btn-outline-secondary fs-10 p-1" onclick="exibir({{$d->id}})"><i class="fas fa-eye"></i> Visualizar</button>
	        <button type="button" class="btn btn-sm btn-info fs-10 p-1 adicionar" onclick="adicionarQuestao({{$d->id}})"><i class="fas fa-sign-out-alt"></i> adicionar</button>
	        <button type="button" class="btn btn-sm btn-danger fs-10 p-1 remover" onclick="removerQuestao({{$d->id}}, this)"><i class="fas fa-sign-out-alt fa-flip-horizontal"></i> remover</button>
	    </div>
	    <input type="hidden" name="questao[]" value="{{$d->id}}">
	</li>
@endforeach
