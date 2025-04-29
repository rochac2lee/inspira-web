<div class="row">
    <div class="col-12 text-justify">
        <p class="text-right">
            <span class="avatar"><img class="img-fluid" width="40px" src="{{$dados->usuario->avatar}}"></span>
            <b>{{$dados->usuario->nome}}</b>

        </p>

    </div>
    <div class="col-12">
        <h5>{{$dados->titulo}}</h5>

    </div>
    <div class="col-12 text-justify">
        <p>@php echo nl2br($dados->descricao) @endphp</p>
    </div>
    @if($dados->imagem != '')
    <div class="col-12 text-justify">
        <p>
            <img class="img-fluid" width="300" src="{{$dados->link_imagem}}">
        </p>
    </div>
    @endif
    <div class="col-12 text-right">
        <p>postado em: {{$dados->updated_at->format('d/m/Y')}}</p>
    </div>

</div>
