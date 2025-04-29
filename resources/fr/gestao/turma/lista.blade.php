@extends('fr/master')
@section('content')
    <section class="section section-interna">
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="filter">
                        <form class="form-inline d-flex justify-content-end">
                            <div class="input-group ml-1">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </div>
                                <input size="30" type="text" name="nome" value="{{Request::input('nome')}}" placeholder="Nome da turma ou código" class="form-control" />
                            </div>
                            <div class="input-group ml-1">
                                <select class="form-control" name="ciclo_etapa_id">
                                    <option value="">Etapa / Ano</option>
                                    @foreach($cicloEtapa as $c)
                                        <option @if( Request::input('ciclo_etapa_id') == $c->id) selected @endif value="{{$c->id}}">{{$c->ciclo}} - {{$c->ciclo_etapa}}</option>
                                    @endforeach
                                    <option value="">Todos</option>
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
            <div class="row border-top pt-4 pb-4">
                <div class="col-md-4">

                    <h3>
                        <a href="{{ url('/gestao/escolas')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Gestão de Turmas
                        <small><br>{{$escola->titulo}}</small>
                    </h3>
                </div>

                <div class="col-md-8 text-right">
                    <a href="{{url('/gestao/escola/'.$escola->id.'/nova_turma')}}" class="btn btn-success" >
                        <i class="fas fa-plus"></i>
                        Nova Turma
                    </a>
                </div>
            </div>
            <div class="row">
                @if(count($turmas)>0)
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Etapa / Ano</th>
                                <th scope="col">Turma</th>
                                <th scope="col">Turno</th>
                                <th scope="col">Docentes</th>
                                <th scope="col">Alunos</th>
                                <th scope="col" class="text-right" style="min-width: 220px">Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($turmas as $d)
                                <tr>
                                    <td>
                                        #{{$d->id}}
                                    </td>
                                    <td>
                                        {{$d->ciclo}}  / {{$d->ciclo_etapa}}
                                    </td>
                                    <td >
                                        {{$d->titulo}}
                                    </td>
                                    <td >
                                        {{$d->turno}}
                                    </td>
                                    <td >{{count($d->professores)}}</td>
                                    <td >{{count($d->alunos)}}</td>

                                    <td class="text-right">
                                        <span>
                                            <a href="{{url('/gestao/escola/'.$escola->id.'/editar_turma/'.$d->id)}}" title="Editar"  data-toggle="tooltip" data-placement="top"  class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                        </span>
                                        <span>
                                            <button title="Excluir" data-toggle="tooltip" data-placement="top" onclick="modalExcluir('{{$d->id}}', '{{$d->titulo}}', '{{$d->ciclo}}  / {{$d->ciclo_etapa}}')" class="btn btn-danger btn-sm" data-placement="left" title="Excluir"><i class="fas fa-trash-alt"></i></button>
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
                    {{ $turmas->appends(Request::all())->links() }}
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
                    <h5 class="modal-title" id="exampleModalLabel">Exclusão de Turma</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                Você deseja mesmo excluir esse registro?<br><br>
                                <b id="nomeEscola"></b><br>
                                <small id="etapa"></small>
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
    var idExcluir =0;
    function modalExcluir(id, nome, etapa)
    {
        idExcluir = id;
        $('#nomeEscola').html(nome);
        $('#etapa').html(etapa);
        $('#formExcluir').modal('show');
    }
    function excluir()
    {
        window.location.href = '{{url("/gestao/escola/turma/excluir")}}/'+idExcluir;
    }
</script>
@stop
