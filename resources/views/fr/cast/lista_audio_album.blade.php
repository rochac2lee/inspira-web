@foreach($audios as $a)
<li id="audio{{$a->id}}" class="list-group-item rounded">
    <input type="hidden" name="lista_audio[]" value="{{$a->id}}">
    <div class="w-100 mb-1">
        <button type="button" class="btn btn-sm btn-outline-secondary disabled fs-9 p-1"><i class="fas fa-layer-group"></i> {{@$a->ciclo->titulo}} / {{@$a->cicloetapa->titulo}}</button>
        <button type="button" class="btn btn-sm btn-outline-secondary disabled fs-9 p-1"><i class="fas fa-book"></i> {{@$a->rel_disciplina->titulo}} </button>
        <button type="button" class="btn btn-sm btn-outline-secondary disabled fs-9 p-1"><i class="fas fa-book"></i> {{@$a->categoria->titulo}} </button>
    </div>
    <div class="questao text-justify text-break text-muted fs-15">
        <div class="row">
            <div class="col-2">
                <img class="img-responsive" width="100%" style="height: auto" src="{{$a->capa_audio}}">
            </div>
            <div class="col-10">
                <b>{{$a->titulo}}</b>
            </div>
        </div>

    </div>
    <div class="w-100 border-top mt-1 text-right pt-1">
        <button type="button" class="btn btn-sm btn-outline-secondary fs-10 p-1" onclick="visualizarConteudo({{$a->id}})"><i class="fas fa-play"></i> Ouvir</button>
        <button type="button" class="btn btn-sm btn-info fs-10 p-1 adicionar" onclick="adicionarAudio({{$a->id}})"><i class="fas fa-sign-out-alt"></i> adicionar</button>
        <button type="button" class="btn btn-sm btn-danger fs-10 p-1 remover" onclick="removerAudio({{$a->id}}, this)"><i class="fas fa-sign-out-alt fa-flip-horizontal"></i> remover</button>
    </div>
</li>
@endforeach
