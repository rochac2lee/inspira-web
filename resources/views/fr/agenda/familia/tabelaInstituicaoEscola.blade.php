<table class="table table-striped">
    @foreach($dados as $p)
    <tr>
        <td>
            <div class="form-check">
                <input class="form-check-input" @if($p->escolas == null || count($p->escolas) ==0) disabled @else @if( is_array($instituicao) && in_array($p->id, $instituicao)) checked @endif @endif type="checkbox" value="{{$p->id}}" instituicao_id="{{$p->id}}" id="ckInstituicao{{$p->id}}" onclick="clicouInstituicao({{$p->id}})">
                <label class="form-check-label" for="ckInstituicao{{$p->id}}">
                    <b><span id="nomeInstituicao{{$p->id}}">{{$p->titulo}}</span></b>
                    @if($p->escolas == null || count($p->escolas) ==0)
                        <span class="text-danger ml-2">(Instituicao sem escolas cadastradas)</span>
                    @else
                        <span class="text-info ml-2" id="msgTodasEscolas{{$p->id}}" @if(isset($escola[$p->id][0])  && is_array($instituicao) && in_array($p->id, $instituicao) && ($escola[$p->id][0] == 0 || count($escola[$p->id]) == count($p->escolas)) ) style="display: block" @else style="display: none" @endif>Todas as escolas selecionadas.</span>
                    @endif
                </label>
            </div>
            <div id="divInstituicao{{$p->id}}" class="row mt-2" style="margin-left: 5%; display: none">
                @foreach($p->escolas as $t)
                    <div class="col-4">
                        <div class="form-check">
                            <input class="form-check-input ckEscola{{$p->id}} ckInstituicao{{$p->id}}" @if(isset($escola[$p->id]) && is_array($escola[$p->id]) && (in_array($t->id, $escola[$p->id]) || $escola[$p->id][0] ==0 ) ) checked @endif type="checkbox" instituicao_id="{{$p->id}}" escola_id="{{$t->id}}" value="{{$t->id}}" id="ckEscola{{$t->id}}" onclick="clicouEscola({{$t->id}}, {{$p->id}})">
                            <label class="form-check-label" for="ckEscola{{$t->id}}">
                                <span id="nomeEscola{{$t->id}}">{{$t->titulo}}</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </td>
        <td class="text-right">
            <button id="flechaInstituicao{{$p->id}}" type="button" class="btn btn-light btn-sm" onclick="clicouFlecha({{$p->id}})"><i class="fas fa-angle-down"></i></button>
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

    biblioteca = 0;
    @if(Request::input('biblioteca') == 1)
        biblioteca = 1;
    @endif

    function clicouFlecha(id){
        if($('#divInstituicao' + id).is(":visible")){
            $('#divInstituicao' + id).hide();
            $('#flechaInstituicao' + id).html('<i class="fas fa-angle-down"></i>')
        }else{
            $('#divInstituicao' + id).fadeIn();
            $('#flechaInstituicao' + id).html('<i class="fas fa-angle-up"></i>')
        }

    }

    function clicouInstituicao(id){
        if ($('#ckInstituicao' + id).prop('checked')) {
            $('.ckEscola' + id).prop('checked', true)
            $('#msgTodasEscolas' + id).show();
            adicionaInstituicao(id,-1);
        } else {
            $('.ckEscola' + id).prop('checked', false)
            $('#msgTodasEscolas' + id).hide();
            if(biblioteca == 0){
                $('#selecionadaInstituicao'+id).remove();
            }else{
                $('#selecionadaInstituicaoBiblioteca'+id).remove();
            }


        }
    }

    function clicouEscola(escolaId, instituicaoId){
        console.log(instituicaoId)
        if($('#ckEscola'+escolaId).prop('checked')){

            adicionaInstituicao(instituicaoId,escolaId);
            $('#ckInstituicao'+instituicaoId).prop('checked',true)
            var checado = 1;
            $('.ckInstituicao' + instituicaoId).each(function(index){
                if(!$(this).prop('checked')){
                    checado = 0;
                }
            })
            if(checado == 1){
                $('#msgTodasEscolas' + instituicaoId).show();
                //adicionaEscola(instituicaoId,-1);
            }else{
                $('#msgTodasEscolas' + instituicaoId).hide();
            }
        }else{
            var checado = 0;
            if(biblioteca == 0) {
                $('#listaEscolasSelecionadasInstituicao' + instituicaoId).html('');
            }else {
                $('#listaEscolasSelecionadasInstituicaoBiblioteca' + instituicaoId).html('');
            }
            $('.ckEscola' + instituicaoId).each(function(index){
                if($(this).prop('checked')){
                    checado = 1;
                    adicionaInstituicao(instituicaoId,($(this).attr('escola_id')));
                }
            })
            $('#msgTodasEscolas' + instituicaoId).hide();
            if(checado == 0){
                $('#ckInstituicao'+instituicaoId).prop('checked',false);
                if(biblioteca == 0) {
                    $('#selecionadaInstituicao' + instituicaoId).remove();
                }else {
                    $('#selecionadaInstituicaoBiblioteca' + instituicaoId).remove();
                }
            }
            if(biblioteca == 0) {
                $('#selecionadaEscola' + escolaId).remove();
            }else {
                $('#selecionadaEscolaBiblioteca' + escolaId).remove();
            }
        }
    }
</script>
