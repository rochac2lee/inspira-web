<div class="row">
    <div class="col-12 text-justify">
        <p class="text-right">
            <span class="avatar"><img class="img-fluid" width="40px" src="{{$dados->professor->avatar}}"></span>
            <b>{{$dados->professor->nome}}</b>

        </p>

    </div>
    <div class="col-12">
        <h5>{{$dados->titulo}}</h5>

    </div>
    <div class="col-12 text-justify">
        <p>@php echo nl2br($dados->descricao) @endphp</p>
    </div>
    @if($dados->arquivo != '')
    <div class="col-12 text-justify">
        <p>
            <b>Anexo:</b>
            <a href="{{url('gestao/agenda/tarefas/arquivo/donwload/'.@$dados->id)}}"><span class="badge badge-info">{{$dados->nome_arquivo_original}} <i class="fas fa-download"></i></span></a>
        </p>
    </div>
    @endif
    <div class="col-12 text-right">
        <p>postado em: {{$dados->updated_at->format('d/m/Y')}}</p>
    </div>

</div>
