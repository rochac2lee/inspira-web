@if(count($dados)>0)
    @foreach($dados as $d)
        <li class="ui-state-default text-truncate" id="selecionadaTurma{{$d->id}}">
            <input type="hidden" name="turma[]" value="{{$d->id}}">
            <b>{{$d->ciclo}}  / {{$d->ciclo_etapa}} - {{$d->titulo}}</b>
            <p id="listaAlunosSelecionadosTurma{{$d->id}}" style="white-space: normal;">
                @if( isset($d->qtdAlunos) && count($d->qtdAlunos)>0 && count($d->alunos) == $d->qtdAlunos[0]->qtd )
                    <span id="todosAlunosTurmas{{$d->id}}" class="badge badge-secondary">Todos os estudantes selecionados.</span>
                    @foreach($d->alunos as $t)
                        <input type="hidden" name="aluno[{{$d->id}}][]" value="{{$t->id}}">
                    @endforeach
                @else
                    @foreach($d->alunos as $t)
                        <input type="hidden" name="aluno[{{$d->id}}][]" value="{{$t->id}}"><span class="badge badge-light ml-2" id="selecionadaAluno{{$t->id}}">{{$t->nome}}</span>
                    @endforeach
                @endif
                @php /*
                @if( isset($d->qtdAlunos) && count($d->qtdAlunos)>0 && count($d->alunos) == $d->qtdAlunos[0]->qtd )
                    <input type="hidden" name="aluno[{{$d->id}}][]" value="0"><span id="todosAlunosTurmas{{$d->id}}" class="badge badge-secondary">Todos os estudantes selecionados.</span>
                @else
                    @foreach($d->alunos as $t)
                        <input type="hidden" name="aluno[{{$d->id}}][]" value="{{$t->id}}"><span class="badge badge-light ml-2" id="selecionadaAluno{{$t->id}}">{{$t->nome}}</span>
                    @endforeach
                @endif */
                @endphp
            </p>
        </li>
    @endforeach
@endif
