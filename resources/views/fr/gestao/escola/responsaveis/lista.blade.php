@extends('fr/master')
@section('content')

    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/formUtilities.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/jquery.inputmask.bundle.min.js"></script>
    <script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet"></link>

    <section class="section section-interna">
        <div class="container">
            <div class="row mb-3" >
                <div class="col-md-12">
                    <div class="filter">
                        <form class="form-inline d-flex justify-content-end">
                            <div class="input-group ml-1">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </div>
                                <input type="text" size="50px" name="pesquisa" value="{{Request::input('pesquisa')}}" placeholder="Nome, e-mail ou código" class="form-control" />
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
            <div class="row border-top pt-4 pb-4">
                <div class="col-md-8">
                    <h3>
                        <a href="{{url('/gestao/escolas')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Gestão de Responsáveis
                        <small><br>{{$escola->titulo}}</small>
                    </h3>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{url('gestao/escola/'.$escola->id.'/responsaveis/novo')}}" class="btn btn-success">
                        <i class="fas fa-plus"></i>
                        Novo Responsável
                    </a>
                </div>
            </div>
            <div class="row">
                @if(count($dados)>0)
                    <section class="table-page w-100">
                        <div class="table-responsive table-hover">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Código</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">E-mail</th>
                                        <th scope="col">Estudantes vinculados</th>
                                        <th scope="col" class="text-right" style="min-width: 220px">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dados as $d)
                                    <tr>
                                        <td >
                                            {{$d->id}}
                                        </td>
                                        <td >
                                            {{$d->nome}}
                                        </td>
                                        <td >
                                            {{$d->email}}
                                        </td>
                                        <td >
                                            @foreach($d->alunosDoResponsavel as $a)
                                                <span class="badge badge-secondary">{{$a->nome}}</span>
                                            @endforeach
                                        </td>
                                        <td class="text-right">
                                            <span>
                                                <a href="{{url('gestao/escola/'.$escola->id.'/responsaveis/editar/'.$d->id)}}" data-toggle="tooltip" data-placement="top" title="Editar" class="btn btn-success btn-sm" ><i class="fas fa-edit"></i></a>
                                            </span>
                                            <span>
                                                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Excluir" class="btn btn-danger btn-sm" onclick="modalExcluir({{$d->id}}, '{{$d->nome}}')" ><i class="fas fa-trash"></i></a>
                                            </span>
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
                    <h5 class="modal-title" id="exampleModalLabel">Exclusão de Responsável</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                Você deseja mesmo excluir esse registro?<br><br>
                                <b id="nomeResponsavel"></b>
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


<script>
    function modalExcluir(id, nome)
    {
        idExcluir = id;
        $('#nomeResponsavel').html(nome);
        $('#formExcluir').modal('show');
    }

    function excluir()
    {
        window.location.href = '{{url('gestao/escola/'.$escola->id.'/responsaveis/excluir/')}}/'+idExcluir;
    }
</script>
@stop
