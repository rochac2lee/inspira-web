@if(count($dados)>0)
    @foreach($dados as $d)
        <li id="{{$d->id}}" class="roteiro{{$d->id}} list-group-item rounded mb-2">
            <div class="row">
                <div class="col-4" style="padding: 0px;">
                    <img src="{{$d->url_capa}}" class="img-fluid" />
                </div>
                <div class="col-8" style="padding: 0px; padding-left: 6px">
                    <div class=" text-break ">

                        <b>{{$d->titulo}}</b>

                    </div>
                    <div class="btn_degrade w-100 text-right pt-1">
                        <button type="button" class="btn btn-sm btn-info fs-10 p-1 adicionar" onclick="adicionarRoteiro({{$d->id}})"><i class="fas fa-sign-out-alt"></i> adicionar</button>
                        <button type="button" class="btn btn-sm btn-danger fs-10 p-1 remover" onclick="removerRoteiro({{$d->id}}, this)"><i class="fas fa-sign-out-alt fa-flip-horizontal"></i> remover</button>
                    </div>
                </div>
            </div>
        </li>
    @endforeach
@else
    Nenhum Roteiro encontrado na biblioteca.
@endif
