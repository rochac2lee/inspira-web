@if(count($dados)>0)
    @foreach($dados as $d)
        <li class="ui-state-default text-truncate" id="selecionadaEscola{{$d->id}}">
            <input type="hidden" name="escola[]" value="{{$d->id}}">
            <b>{{$d->titulo}}</b>
            <p id="listaTurmasSelecionadasEscola{{$d->id}}" style="white-space: normal;">
                @if(count($d->turmas)>0 )
                    @foreach($d->turmas as $t)
                        <input type="hidden" name="turma[{{$d->id}}][]" value="{{$t->id}}"><span class="badge badge-light ml-2" id="selecionadaTurma{{$t->id}}">{{$t->titulo}}</span>
                    @endforeach
                @else
                    <input type="hidden" name="turma[{{$d->id}}][]" value="0"><span id="todasTurmasEscola{{$d->id}}" class="badge badge-secondary">Todas as turmas selecionadas.</span>
                @endif
            </p>
        </li>
    @endforeach
@else
    <li class="ui-state-default text-truncate"><input type="hidden" name="escola[]" value="0"><input type="hidden" name="turma[0][]" value="0"> <i class="fas fa-check"></i><span class="m-2"></span> Todas as escolas e turmas.</li>
@endif
