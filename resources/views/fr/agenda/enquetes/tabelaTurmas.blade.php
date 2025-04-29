<table class="table table-striped">
    @foreach($dados as $p)
        <tr>
            <td>
                <div class="form-check">
                    <input class="form-check-input" @if($p->alunos == null || count($p->alunos) ==0) disabled @else @if( is_array($turma) && in_array($p->id, $turma)) checked @endif @endif  type="checkbox" value="{{$p->id}}" tuma_id="{{$p->id}}" id="ckTurma{{$p->id}}" onclick="clicouTurma({{$p->id}})">
                    <label class="form-check-label" for="ckTurma{{$p->id}}">
                        <b><span id="nomeTurma{{$p->id}}">{{$p->ciclo}}  / {{$p->ciclo_etapa}} - {{$p->titulo}}</span></b>
                        @if($p->alunos == null || count($p->alunos) ==0)
                            <span class="text-danger ml-2">(Turma sem estudantes)</span>
                        @else
                            <span class="text-info ml-2" id="msgTodosAlunos{{$p->id}}" @if(isset($aluno[$p->id][0])  && is_array($turma) && in_array($p->id, $turma) && ($aluno[$p->id][0] == 0 || count($aluno[$p->id]) == count($p->alunos)) ) style="display: block" @else style="display: none" @endif>Todos os estudantes selecionados.</span>
                        @endif
                    </label>
                </div>
                <div id="divEscola{{$p->id}}" class="row mt-2" style="margin-left: 5%; display: none; ">
                    @foreach($p->alunos as $a)
                        <div class="col-4">
                            <div class="form-check">
                                <input class="form-check-input ckTurma{{$p->id}}" @if(isset($aluno[$p->id]) && is_array($aluno[$p->id]) && (in_array($a->id, $aluno[$p->id]) || $aluno[$p->id][0] ==0 ) ) checked @endif type="checkbox" turma_id="{{$p->id}}" aluno_id="{{$a->id}}" value="{{$a->id}}" id="ckAluno{{$a->id}}-{{$p->id}}" onclick="clicouAluno({{$a->id}}, {{$p->id}})">
                                <label class="form-check-label" for="ckAluno{{$a->id}}-{{$p->id}}">
                                    <span id="nomeAluno{{$a->id}}-{{$p->id}}">{{$a->nome}}</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </td>
            <td class="text-right">
                <button id="flechaEscola{{$p->id}}" type="button" class="btn btn-light btn-sm" onclick="clicouFlecha({{$p->id}})"><i class="fas fa-angle-down"></i></button>
            </td>
        </tr>
    @endforeach
</table>
<nav class="mt-4" aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        {{ $dados->appends(['nome'=>Request::input('nome')])->links() }}
    </ul>
</nav>
<script>
    function clicouFlecha(id){
        if($('#divEscola' + id).is(":visible")){
            $('#divEscola' + id).hide();
            $('#flechaEscola' + id).html('<i class="fas fa-angle-down"></i>')
        }else{
            $('#divEscola' + id).fadeIn();
            $('#flechaEscola' + id).html('<i class="fas fa-angle-up"></i>')
        }

    }

    function clicouTurma(id){
        if ($('#ckTurma' + id).prop('checked')) {
            $('.ckTurma' + id).prop('checked', true)
            $('#msgTodosAlunos' + id).show();
            adicionaTurma(id,0);
        } else {
            $('.ckTurma' + id).prop('checked', false)
            $('#msgTodosAlunos' + id).hide();
            $('#selecionadaTurma'+id).remove();
        }
    }

    function clicouAluno(alunoId, turmaId){

        if($('#ckAluno'+alunoId+'-'+turmaId).prop('checked')){
            adicionaTurma(turmaId,alunoId);
            $('#ckTurma'+turmaId).prop('checked',true)
            var checado = 1;
            $('.ckTurma' + turmaId).each(function(index){
                if(!$(this).prop('checked')){
                    checado = 0;
                }
            })
            if(checado == 1){
                $('#msgTodosAlunos' + turmaId).show();
                adicionaTurma(turmaId,0);
            }else{
                $('#msgTodosAlunos' + turmaId).hide();
            }
        }else{
            var checado = 0;
            $('#listaAlunosSelecionadosTurma'+turmaId).html('');
            $('.ckTurma' + turmaId).each(function(index){
                if($(this).prop('checked')){
                    checado = 1;
                    adicionaTurma(turmaId,($(this).attr('aluno_id')));
                }
            })
            $('#msgTodasTurmas' + turmaId).hide();
            if(checado == 0){
                $('#ckTurma'+turmaId).prop('checked',false);
                $('#selecionadaTurma'+turmaId).remove();
            }
            $('#selecionadaTurma'+alunoId).remove();
        }
    }
</script>
