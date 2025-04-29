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
                    @endif
                </label>
            </div>
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
    function clicouEscola(id){
        if ($('#ckEscola' + id).prop('checked')) {
            adicionaEscola(id);
        } else {
            $('#selecionadaEscola'+id).remove();
        }
    }
</script>
