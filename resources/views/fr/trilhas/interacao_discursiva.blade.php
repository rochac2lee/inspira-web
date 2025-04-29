<div class="modal-header">
    <h5 class="modal-title" id="tituloModalConteudo">{{$conteudo->titulo}}</h5>
</div>
<div class="modal-body">
    <p>
        @php $cont = json_decode($conteudo->conteudo) @endphp
        {!! $cont->pergunta !!}
    </p>
    <p>
        <b>Resposta</b><br>
        @if( isset($dados->conteudo) && trim($dados->conteudo) != '')
            {{$dados->conteudo}}
        @else
            <span class="text-danger">Resposta em branco</span>
        @endif
    </p>
</div>
