@if(count($dados)>0)
    @foreach($dados as $d)
        <li class="ui-state-default text-truncate" id="selecionadaEscola{{$d->id}}">
            <input type="hidden" name="escola[]" value="{{$d->id}}">
            <b>{{$d->titulo}}</b>
        </li>
    @endforeach
@else
    <li class="ui-state-default text-truncate"><input type="hidden" name="escola[]" value="0"><input type="hidden" name="turma[0][]" value="0"> <i class="fas fa-check"></i><span class="m-2"></span> Todas as escolas e turmas.</li>
@endif
