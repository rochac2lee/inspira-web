@extends('fr/master')
@section('content')
    <section class="section section-interna">
        <div class="container">
            <div class="row pb-4">
                <div class="col-md-12">
                    <h3>
                        <a href="{{ url('/gestao/escola/relatorio/importacao/usuarios')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Detalhes da importação de usuários <small><br>{{\Carbon\Carbon::parse($dados['created_at'])->format('d/m/Y')}} - {{$dados['name']}}</small>
                    </h3>
                    <h4 style="margin-bottom: 14px;">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a  class="nav-link  active">Erros ({{number_format($dados['qtd_errado'],0,',','.')}})</a>
                            </li>

                        </ul>
                    </h4>
                </div>

            </div>
            <div class="row">
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
                            @foreach($log as $d)
                            <tr>
                                <td>{{$d->linha}}</td>
                                <td>
                                    {{$d->nome}}
                                    <br>{{$d->email}}
                                </td>
                                <td>{{$d->escola_id}}</td>
                                <td>{{$d->instituicao_id}}</td>
                                <td>{{$d->permissao}}</td>
                                <td>
                                    @if($d->inserir)
                                    Inserção
                                    @else
                                    Exclusão
                                    @endif

                                </td>
                                <td>
                                    @if($d->erro_banco=='')
                                        Validação
                                    @else
                                        Banco de dados
                                    @endif
                                </td>
                                <td>
                                    @if($d->erro_banco=='')
                                        @php
                                            $errors = unserialize($d->erro_validacao);
                                        @endphp
                                        @if($errors->any())
                                            <ul>
                                                {!!implode('', $errors->all('<li>:message</li>'))!!}
                                            </ul>
                                        @endif
                                    @else
                                        {{$d->erro_banco}}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <nav class="mt-4" aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                {{$log->appends(Request::all())->links()}}
                            </ul>
                        </nav>
                    </div>
                </section>


            </div>

        </div>
    </section>

    <script>

    $('.table-responsive').on('show.bs.dropdown', function () {
         $('.table-responsive').css( "overflow", "inherit" );
    });

    $('.table-responsive').on('hide.bs.dropdown', function () {
         $('.table-responsive').css( "overflow", "auto" );
    })

    </script>
@stop
