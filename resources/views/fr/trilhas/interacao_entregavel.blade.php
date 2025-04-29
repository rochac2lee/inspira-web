<div class="modal-header">
    <h5 class="modal-title" id="tituloModalConteudo">{{$conteudo->titulo}}</h5>
</div>
<div class="modal-body">
    <p>
        {!! $conteudo->conteudo !!}
    </p>
    <p>
        <b>Arquivos enviados</b><br>
        @if( count($dados) > 0)
            <table class="table table-bordered">
            @foreach($dados as $d)
                <tr>
                    <td> <a href="{{url('/roteiros/downloadEntregavel?curso_id='.$d->curso_id.'&aula_id='.$d->aula_id.'&conteudo_id='.$d->conteudo_id.'&trilha_id='.$d->trilha_id.'&user_id='.$d->user_id.'&id='.$d->id)}}" alt="Download" title="Download"  data-toggle="tooltip" data-placement="top"> <i class="fas fa-download"></i> entregavel_{{$d->nome_arquivo}} </a></td >
                </tr>
            @endforeach
            </table>
        @else
            <span class="text-danger">Nenhum arquivo enviado</span>
        @endif
    </p>
</div>
