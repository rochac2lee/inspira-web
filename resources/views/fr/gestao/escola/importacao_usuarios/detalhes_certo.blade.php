<section class="table-page w-100">
    <div class="table-responsive table-hover">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th >Linha</th>
                    <th scope="col">Usuário</th>
                    <th scope="col">Escola</th>
                    <th scope="col">Instituição</th>
                    <th scope="col">Perfil</th>
                    <th scope="col">Operação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados['acertos'] as $d)
                    <tr>
                        <td>{{$d['linha']}}</td>
                        <td>
                            {{$d['nome_completo']}}
                            <br>{{$d['email']}}
                        </td>
                        <td>{{$d['escola_id']}}</td>
                        <td>{{$d['instituicao_id']}}</td>
                        <td>{{$d['permissao']}}</td>
                        <td>
                            @if($d['inserir'])
                                Inserção
                            @else
                                Exclusão
                            @endif

                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
