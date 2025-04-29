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
                                <input type="text" name="pesquisa" value="{{Request::input('pesquisa')}}" placeholder="Nome, e-mail ou código" class="form-control" />
                            </div>
                            <div class="input-group ml-1">
                                <button type="submit" class="btn btn-secondary">Localizar</button>
                            </div>
                            <div class="input-group ml-1">
                                <button type="button" onclick="javascript:window.location.href='{{url()->current()}}'" class="btn btn-secondary">Limpar Filtros</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row border-top pt-4 pb-4">
                <div class="col-md-8">
                    <h3>
                        <a href="{{url()->previous()}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Gestão de Alunos
                        <small><br>{{$escola->titulo}}</small>
                    </h3>
                </div>
                @if(auth()->user()->permissao == 'Z')
                <div class="col-md-4 text-right">
                    <button class="btn btn-warning text-light" data-toggle="modal" data-target="#formImportar">
                        <i class="fas fa-file-upload"></i>
                        Importar arquivo
                    </button>
                    <!--
                    <button class="btn btn-success" data-toggle="modal" data-target="#formIncluir" onclick="tipoOperacao = 'add'">
                        <i class="fas fa-plus"></i>
                        Novo Aluno
                    </button>
                    -->
                </div>
                @endif
            </div>
            <div class="row">
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Código</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Email</th>
                                    <th scope="col" class="text-right" style="min-width: 220px">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dados as $d)
                                <tr>
                                    <td data-toggle="modal" data-target="#formAlterar">
                                        {{$d->id}}
                                    </td>
                                    <td data-toggle="modal" data-target="#formAlterar">
                                        {{$d->nome}}
                                    </td>
                                    <td data-toggle="modal" data-target="#formAlterar">
                                        {{$d->email}}
                                    </td>
                                    <td class="text-right">
                                        @if(auth()->user()->permissao == 'Z')
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown">
                                                <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <nav class="mt-4" aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    {{ $dados->appends(Request::all())->links() }}
                </ul>
            </nav>
        </div>
    </section>
<!-- IMPORTAR ARQUIVO -->
    <div class="modal fade" id="formImportar" tabindex="-1" role="dialog" aria-labelledby="formImportar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Importação de arquivos de alunos</h5>
            </div>
            <div class="modal-body">
                <form action="{{url('/gestao/escola/importacao/alunos')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="escola_id" value="{{$idEscola}}">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Formato do arquivo *</label>
                                <select class="form-control rounded {{ $errors->has('tipo_arquivo') ? 'is-invalid' : '' }}" name="tipo_arquivo" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
                                    <option value="">Selecione</option>
                                    <option value="1">Importação google</option>
                                </select>
                            <div class="invalid-feedback">{{ $errors->first('tipo_arquivo') }}</div>

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Arquivo *</label>
                                <input type="file" name="arquivo" accept=".csv" value="" class="form-control rounded {{ $errors->has('arquivo') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('arquivo') }}</div>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Importar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                </form>
            </div>
            </div>
        </div>
    </div>
    <!-- FIM IMPORTAR ARQUIVO -->

    <script>

        $('.table-responsive').on('show.bs.dropdown', function () {
             $('.table-responsive').css( "overflow", "inherit" );
        });

        $('.table-responsive').on('hide.bs.dropdown', function () {
             $('.table-responsive').css( "overflow", "auto" );
        })

        $(document).ready(function(){
            @if($errors->has('tipo_arquivo') || $errors->has('arquivo'))
                $('#formImportar').modal('show');
            @endif
        });
    </script>


@stop
