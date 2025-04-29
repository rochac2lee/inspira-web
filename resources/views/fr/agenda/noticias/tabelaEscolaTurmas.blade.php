<table class="table table-striped">
    @foreach($dados as $p)
    <tr>
        <td>
            <div class="form-check">
                <input class="form-check-input" @if($p->turmas == null || count($p->turmas) ==0) disabled @else @if( is_array($escola) && in_array($p->id, $escola)) checked @endif @endif type="checkbox" value="{{$p->id}}" escola_id="{{$p->id}}" id="ckEscola{{$p->id}}" onclick="clicouEscola({{$p->id}})">
                <label class="form-check-label" for="ckEscola{{$p->id}}">
                    <b><span id="nomeEscola{{$p->id}}">{{$p->titulo}}</span></b>
                    @if($p->turmas == null || count($p->turmas) ==0)
                        <span class="text-danger ml-2">(Escola sem turmas cadastradas)</span>
                    @else
                        <span class="text-info ml-2" id="msgTodasTurmas{{$p->id}}" @if(isset($turma[$p->id][0])  && is_array($escola) && in_array($p->id, $escola) && ($turma[$p->id][0] == 0 || count($turma[$p->id]) == count($p->turmas)) ) style="display: block" @else style="display: none" @endif>Todas as turmas selecionadas.</span>
                    @endif
                </label>
            </div>
            <div id="divEscola{{$p->id}}" class="row mt-2" style="margin-left: 5%; display: none">
                @foreach($p->turmas as $t)
                    <div class="col-4">
                        <div class="form-check">
                            <input class="form-check-input ckEscola{{$p->id}}" @if(isset($turma[$p->id]) && is_array($turma[$p->id]) && (in_array($t->id, $turma[$p->id]) || $turma[$p->id][0] ==0 ) ) checked @endif type="checkbox" escola_id="{{$p->id}}" turma_id="{{$t->id}}" value="{{$t->id}}" id="ckTurma{{$t->id}}" onclick="clicouTurma({{$t->id}}, {{$p->id}})">
                            <label class="form-check-label" for="ckTurma{{$t->id}}">
                                <span id="nomeTurma{{$t->id}}">{{$t->ciclo}}  / {{$t->ciclo_etapa}} - {{$t->titulo}}</span>
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

    function clicouEscola(id){
        if ($('#ckEscola' + id).prop('checked')) {
            $('.ckEscola' + id).prop('checked', true)
            $('#msgTodasTurmas' + id).show();
            adicionaEscola(id,-1);
        } else {
            $('.ckEscola' + id).prop('checked', false)
            $('#msgTodasTurmas' + id).hide();
            $('#selecionadaEscola'+id).remove();
        }
    }

    function clicouTurma(turmaId, escolaId){

        if($('#ckTurma'+turmaId).prop('checked')){
            adicionaEscola(escolaId,turmaId);
            $('#ckEscola'+escolaId).prop('checked',true)
            var checado = 1;
            $('.ckEscola' + escolaId).each(function(index){
                if(!$(this).prop('checked')){
                    checado = 0;
                }
            })
            if(checado == 1){
                $('#msgTodasTurmas' + escolaId).show();
                adicionaEscola(escolaId,-1);
            }else{
                $('#msgTodasTurmas' + escolaId).hide();
            }
        }else{
            var checado = 0;
            $('#listaTurmasSelecionadasEscola'+escolaId).html('');
            $('.ckEscola' + escolaId).each(function(index){
                if($(this).prop('checked')){
                    checado = 1;
                    adicionaEscola(escolaId,($(this).attr('turma_id')));
                }
            })
            $('#msgTodasTurmas' + escolaId).hide();
            if(checado == 0){
                $('#ckEscola'+escolaId).prop('checked',false);
                $('#selecionadaEscola'+escolaId).remove();
            }
            $('#selecionadaTurma'+turmaId).remove();
        }
    }
</script>
