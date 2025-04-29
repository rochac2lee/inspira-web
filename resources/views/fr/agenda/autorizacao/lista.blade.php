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
                                <input size="40" type="text" name="nome" value="{{Request::input('nome')}}" placeholder="Digite o título ou código para buscar" class="form-control" />
                            </div>
                            @if(auth()->user()->permissao != 'P')
                                <div class="input-group ml-1">
                                    <select class="form-control" name="publicado">
                                        <option value="">Status</option>
                                        <option @if(Request::input('publicado') == 1) selected @endif value="1">Publicado</option>
                                        <option @if(Request::input('publicado') == 0 && Request::input('publicado') == '0') selected @endif value="0">Rascunho</option>
                                    </select>
                                </div>
                            @endif
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
                <div class="col-md-4">
                    <h3>
                        Autorizações
                    </h3>
                </div>
                @if(auth()->user()->permissao != 'P')
                    <div class="col-md-8 text-right">
                        <a href="{{url('gestao/agenda/autorizacoes/novo')}}" class="btn btn-success" >
                            <i class="fas fa-plus"></i>
                            Nova Autorização
                        </a>
                    </div>
                @endif
            </div>
            <div class="row">
                @if(count($dados)>0)
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Título</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Participantes</th>
                                <th scope="col"></th>
                                <th scope="col">Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($dados as $d)
                                    <tr>
                                        <td>#{{$d->id}}</td>
                                        <td>
                                            {{$d->titulo}}<br>
                                            @if(auth()->user()->permissao == 'I')
                                                <span class="badge badge-light" style="font-weight: normal"><i class="fas fa-school"></i> {{$d->escola->titulo}}</span>
                                            @endif
                                            <span class="badge badge-light" style="font-weight: normal"><i class="fas fa-user"></i> {{$d->usuario->nome}}</span>

                                        </td>
                                        <td>
                                            {{substr($d->descricao,0,150)}}@if(strlen($d->descricao)>150)... @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-secondary" style="font-weight: normal">@if(isset($d->alunos[0]->qtd)){{$d->alunos[0]->qtd}}@else 0 @endif participantes</span><br>
                                            <span class="badge badge-primary" style="font-weight: normal">@if(isset($d->respondidos[0]->qtd)){{$d->respondidos[0]->qtd}}@else 0 @endif respondidos</span>
                                        </td>
                                        <td>
                                            @if($d->publicado == 1)
                                                <span class="badge badge-success">Publicado<br>{{$d->updated_at->format('d/m/Y H:i:s')}}</span>
                                            @else
                                                <span class="badge badge-danger">Rascunho<br>{{$d->updated_at->format('d/m/Y H:i:s')}}</span>
                                            @endif
                                        </td>
                                        <td class="text-right text-nowrap">
                                            <span><button onclick="modalVisualizar({{$d->id}})" class="btn btn-success btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Visualizar"><i class="fas fa-eye"></i></button></span>
                                            @if( ( auth()->user()->permissao == 'I' ||  (auth()->user()->permissao == 'P' && $d->user_id == auth()->user()->id) || (auth()->user()->permissao == 'C' && ($d->user_id == auth()->user()->id || $d->permissao_usuario == 'P') ) )  )
                                                @if($d->publicado == 0 )
                                                <span><button class="btn btn-success btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="@if($d->publicado==1) Despublicar @else Publicar @endif" onclick="modalPublicar({{$d->id}}, '{{$d->titulo}}')"><i class="fas fa-bullhorn"></i></button></span>
                                                <span><a href="{{url('/gestao/agenda/autorizacoes/editar/'.$d->id)}}" class="btn btn-success btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pencil-alt"></i></a></span>
                                                <span><button class="btn btn-danger btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Excluir" onclick="modalExcluir({{$d->id}}, '{{$d->titulo}}')"><i class="fas fa-trash-alt"></i></button></span>
                                                @endif
                                            @endif
                                            @if($d->publicado == 1 )
                                                <span><a href="{{url('/gestao/agenda/autorizacoes/respondidos/'.$d->id)}}" class="btn btn-success btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Ver Respondidos"><i class="fas fa-clipboard-list"></i></a></span>
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
                                <a class="btn btn-danger fs-13 mb-2" href="{{Request::url()}}" title="Excluir"><i class="fas fa-undo-alt"></i> Limpar Filtro</a>
                            </div>
                            <div class="card-footer text-muted"></div>
                        </div>
                    </div>
                @endif
            </div>
            <nav class="mt-4" aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    {{ $dados->appends(Request::all())->links() }}
                </ul>
            </nav>
        </div>
    </section>

    <!-- EXCLUIR -->
    <div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Exclusão de Autorização</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                Você deseja mesmo excluir esse registro?<br><br>
                                <b id="nomeEscola"></b><br>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <button type="button" onclick="excluir()" class="btn btn-danger">Sim, excluir</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM EXCLUIR -->

    @include('fr.agenda.autorizacao.modalPublicar')

    <!-- VISUALIZAR -->
    <div class="modal fade" id="formVisualizar" tabindex="-1" role="dialog" aria-labelledby="formVisualizar" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title">Visualizar Autorização</h5>
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
    var idExcluir =0;
    function modalExcluir(id, nome)
    {
        idExcluir = id;
        $('#nomeEscola').html(nome);
        $('#formExcluir').modal('show');
    }
    function excluir()
    {
        window.location.href = '{{url("/gestao/agenda/autorizacoes/excluir")}}/'+idExcluir;
    }

    var idPublicar =0;
    function modalPublicar(id, nome)
    {
        idPublicar = id;
        $('#nomeComuncadoPublicar').html(nome);
        $('#formPublicar').modal('show');
    }
    function publicar()
    {
        window.location.href = '{{url("/gestao/agenda/autorizacoes/publicar")}}/'+idPublicar;
    }

    function modalVisualizar(id)
    {
        $.ajax({
            url: '{{url('gestao/agenda/autorizacoes/exibir')}}/'+id,
            type: 'post',
            dataType: 'json',
            data: {
                _token: '{{csrf_token()}}'
            },
            success: function (data) {
                $('#corpoVisualizarComunidado').html(data);
                $('#formVisualizar').modal('show');
            },
            error: function () {
                swal("", "Não foi possível carregar a solicitação de autorização.", "error");
            }
        });

    }
</script>
@stop
