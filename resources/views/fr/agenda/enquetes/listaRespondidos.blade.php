@extends('fr/master')
@section('content')
    <section class="section section-interna" style="padding-top: 50px">
        <div class="container">
            @include('fr.agenda.menu')
            <div class="row mb-3 border-top pt-4">
                <div class="col-md-12">
                    <div class="filter">
                        <form class="form-inline d-flex justify-content-end">
                            <div class="input-group ml-1">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </div>
                                <input size="40" type="text" name="nome" value="{{Request::input('nome')}}" placeholder="Escola, turma ou estudante" class="form-control" />
                            </div>
                            <div class="input-group ml-1">
                                <select class="form-control" name="tipo">
                                    <option @if(Request::input('tipo') == '') selected @endif value="">Status</option>
                                    <option @if(Request::input('tipo') == '1') selected @endif value="1">Respondidos</option>
                                    <option @if(Request::input('tipo') == '2') selected @endif value="2">Não Respondidos</option>
                                </select>
                            </div>
                            <div class="input-group ml-1">
                                <button type="submit" class="btn btn-secondary">Localizar</button>
                            </div>
                            <div class="input-group ml-1">
                                <a type="button" href="{{url()->current()}}" class="btn btn-secondary">Limpar Filtros</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row pb-4">
                <div class="col-md-12">
                    <h3>
                        <a href="{{url()->previous()}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Enquetes respondidas <small><br>{{$enquete->pergunta}}</small>
                    </h3>
                </div>
            </div>
            <div class="row">
                @if(count($dados)>0)
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Escola</th>
                                <th scope="col">Turma</th>
                                <th scope="col">Estudante</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($dados as $d)
                                    @php
                                        $estudante = $d->aluno_nome_completo == '' ? $d->aluno_nome : $d->aluno_nome_completo;
                                        $responsavel = '';
                                        if(isset($d->resp_nome_completo) && isset($d->resp_nome)){
                                            $responsavel = $d->resp_nome_completo == '' ? $d->resp_nome : $d->resp_nome_completo;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{$d->escola}}</td>
                                        <td>
                                            {{$d->turma}}<br>
                                            <small>{{$d->ciclo_etapa}} / {{$d->ciclo}}</small>
                                        </td>
                                        <td>
                                            {{$estudante}}
                                        </td>
                                        <td class="text-right text-nowrap">
                                            @if(isset($d->resposta))
                                                <span class="badge badge-success">Respondido<br>{{$d->updated_at->format('d/m/Y H:i:s')}}<br>{{$responsavel}}</span>
                                            @else
                                                <span class="badge badge-secondary">Não Respondido</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
                @else
                    <div class="col">
                        <div class="card text-center">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado</h5>
                                <p class="card-text ">Não foi encontrado resultado contendo todos os seus termos de pesquisa, clique no botão abaixo para reiniciar a pesquisa</p>
                                <a class="btn btn-danger fs-13 mb-2" href="{{Request::url()}}" title="Limpar filtro"><i class="fas fa-undo-alt"></i> Limpar Filtro</a>
                            </div>
                            <div class="card-footer text-muted"></div>
                        </div>
                    </div>
                @endif
            </div>
            <nav class="mt-4" aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
               {{ $dados->appends(Request::all())->links()}}
                </ul>
            </nav>
        </div>
    </section>

    <!-- VISUALIZAR -->
    <div class="modal fade" id="formVisualizar" tabindex="-1" role="dialog" aria-labelledby="formVisualizar" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title">Visualizar Documentos Recebidos
                    <small><br> <span id="nomeEstudante"></span> - <span id="nomeTurma"></span> - <span id="nomeCiclo"></span></small>
                    </h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12" id="corpoVisualizarComunidado">

                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM VISUALIZAR -->

    <script>
        function modalVisualizar(idAluno, idTurma, idDocumento, aluno, turma, ciclo)
        {
            $.ajax({
                url: '{{url('gestao/agenda/documentos/getRecebidos')}}',
                type: 'post',
                dataType: 'json',
                data: {
                    _token: '{{csrf_token()}}',
                    aluno_id: idAluno,
                    turma_id: idTurma,
                    documento_id: idDocumento,
                },
                success: function (data) {
                    $('#nomeEstudante').html(aluno);
                    $('#nomeTurma').html(turma);
                    $('#nomeCiclo').html(ciclo);
                    $('#corpoVisualizarComunidado').html(data);
                    $('#formVisualizar').modal('show');
                },
                error: function () {
                    swal("", "Não foi possível carregar a solicitação de documentos.", "error");
                }
            });

        }
    </script>
@stop
