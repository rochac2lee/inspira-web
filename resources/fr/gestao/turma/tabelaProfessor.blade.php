<table class="table table-striped">
    @foreach($professores as $p)
    <tr>
        <td><span class="mr-2"><img src="{{$p->avatar}}" style="height:35px;"></span> {{$p->nome}}</td>
        <td>{{$p->email}}</td>
        <td id="tdProfessor{{$p->id}}">
            @php
            $displayExcluir = 'none';
            $displayAdicionar = 'block';
            if(is_array($selecionados) && in_array($p->id, $selecionados)){
                $displayExcluir = 'block';
                $displayAdicionar = 'none';
            }
            @endphp
            <button id="btnExcluirProfessor{{$p->id}}" style="display: {{$displayExcluir}}" type="button" class="btn btn-sm btn-danger" onclick="excluirProfessor({{$p->id}})" data-toggle="tooltip" data-placement="top" title="Remover docente"><i class="fas fa-trash-alt"></i></button>
            <button id="btnAdicionarProfessor{{$p->id}}" style="display: {{$displayAdicionar}}" type="button" class="btn btn-sm btn-success " onclick="adicionarProfessor({{$p->id}},'{{$p->avatar}}', '{{$p->nome}}')" data-toggle="tooltip" data-placement="top" title="Adicionar docente"><i class="fas fa-plus"></i></button>
        </td>
    </tr>
    @endforeach
</table>
<nav class="mt-4" aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        {{ $professores->appends(['nome'=>Request::input('nome')])->links() }}
    </ul>
</nav>
