@if(count($dados)>0)
    @foreach($dados as $d)
        <li class="ui-state-default text-truncate" id="selecionadaInstituicao{{$biblioteca}}{{$d->id}}" >
            <input type="hidden" name="instituicao{{$biblioteca}}[]" value="{{$d->id}}">
            <b>
                @if(!isset($sem_excluir))
                    <a href="javascript:void(0)" class="mr-1"  onclick="$('#selecionadaInstituicao{{$biblioteca}}{{$d->id}}').remove()"><i class="far fa-trash-alt text-danger"></i></a>
                @endif
                {{$d->titulo}}
            </b>
            <p id="listaEscolasSelecionadasInstituicao{{$biblioteca}}{{$d->id}}" style="white-space: normal;">
                @if(count($d->escolas)>0 )
                    @foreach($d->escolas as $t)
                        <input type="hidden" name="escola{{$biblioteca}}[{{$d->id}}][]" value="{{$t->id}}"><span class="badge badge-light ml-2" id="selecionadaescola{{$biblioteca}}{{$t->id}}">{{$t->titulo}}</span>
                    @endforeach
                @else
                    <input type="hidden" name="escola{{$biblioteca}}[{{$d->id}}][]" value="0"><span id="todasEscolasInstituicao{{$d->id}}" class="badge badge-secondary">Todas as Escolas selecionadas.</span>
                @endif
            </p>
        </li>
    @endforeach
@else
    <li class="ui-state-default text-truncate"><input type="hidden" name="instituicao{{$biblioteca}}[]" value="0"><input type="hidden" name="escola{{$biblioteca}}[0][]" value="0"> <i class="fas fa-check"></i><span class="m-2"></span> Todas as Instituicaos e Escolas.</li>
@endif
