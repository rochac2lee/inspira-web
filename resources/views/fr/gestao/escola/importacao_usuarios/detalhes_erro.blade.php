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
                    <th scope="col">Erro de</th>
                    <th scope="col">Mensagem</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados['erros'] as $d)
                    <tr>
                        <td>{{$d['dados']['linha']}}</td>
                        <td>
                            {{$d['dados']['nome_completo']}}
                            <br>{{$d['dados']['email']}}
                        </td>
                        <td>{{$d['dados']['escola_id']}}</td>
                        <td>{{$d['dados']['instituicao_id']}}</td>
                        <td>{{$d['dados']['permissao']}}</td>
                        <td>
                            @if($d['dados']['inserir'])
                                Inserção
                            @else
                                Exclusão
                            @endif

                        </td>
                        <td>
                            @if($d['erroBanco']=='')
                                Validação
                            @else
                                Banco de dados
                            @endif
                        </td>
                        <td>
                            @if($d['erroBanco']=='')
                                @php
                                    $errors = $d['erroValidator'];
                                @endphp
                                @if($errors->any())
                                    <ul>
                                        {!!implode('', $errors->all('<li>:message</li>'))!!}
                                    </ul>
                                @endif
                            @else
                                {{$d['erroBanco']}}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
