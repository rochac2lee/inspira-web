@extends('fr/master')
@section('content')

    <script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet">

    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/formUtilities.js"></script>

    <section class="section section-interna">
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="filter">
                        <form class="form-inline d-flex justify-content-end" action="" method="get">
                            <div class="input-group ml-1 col-5">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </div>
                                <input type="text" value="{{Request::input('nome')}}" name="nome" placeholder="Nome, e-mail ou código" class="form-control" />
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
                <div class="col-md-9">
                    <h3>Gestão de Usuários</h3>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success w-100" onclick="tipoOperacao = 'add'" data-toggle="modal" data-target="#formIncluir">
                        <i class="fas fa-plus"></i>
                        Novo Usuário
                    </button>
                </div>
            </div>
            <div class="row">
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col"></th>
                                <th scope="col" width="30%">Usuário</th>
                                <th scope="col">Permissões</th>
                                <th scope="col" class="text-right" width="20%">Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dados as $d)
                                <tr>
                                    <td >#{{$d->id}}</td>
                                    <td>
                                        <b>{{$d->nome}}</b>
                                        <br>
                                        {{$d->email}}
                                    </td>

                                    <td>
                                        @if($d->getRawOriginal('permissao') == 'I')
                                            <span id="badgeParcial" class="badge @if(nomePermissao($d->getRawOriginal('permissao')) == '' || $d->instituicao=='' ) badge-danger @else badge-secondary @endif mt-2" >{{nomePermissao($d->getRawOriginal('permissao') )}} / {{$d->instituicao}}</span>
                                        @else
                                            <span id="badgeParcial" class="badge @if(nomePermissao($d->getRawOriginal('permissao')) == '' || $d->escola=='' ) badge-danger @else badge-secondary @endif mt-2" >{{nomePermissao($d->getRawOriginal('permissao') )}} / {{$d->escola}}</span>
                                        @endif
                                            @foreach($d->permissoes as $p)

                                                @if($p->permissao == 'I')
                                                    <span id="badgeParcial" class="badge @if(nomePermissao(($p->permissao)) == '' || $p->instituicao=='' ) badge-danger @else badge-secondary @endif mt-2" >{{nomePermissao($p->getRawOriginal('permissao') )}} / {{@$p->instituicao->titulo}}</span>
                                                @else
                                                    <span id="badgeParcial" class="badge @if(nomePermissao(($p->permissao)) == '' || $p->escola=='' ) badge-danger @else badge-secondary @endif mt-2" >{{nomePermissao($p->getRawOriginal('permissao') )}} / {{@$p->escola}}</span>
                                                @endif
                                        @endforeach
                                    </td>

                                    <td class="text-right">
                                           <span>
                                                <a href="{{url('/gestao/usuario/novaSenha/'.$d->id)}}" data-toggle="tooltip" data-placement="top" title="Resetar senha" class="btn btn-success btn-sm" ><i class="fas fa-key"></i></a>
                                            </span>
                                        <span>
                                                <a href="{{url('/gestao/usuario/logar/'.$d->id)}}" data-toggle="tooltip" data-placement="top" title="Login como usuário" class="btn btn-success btn-sm" ><i class="fas fa-sign-in-alt"></i></a>
                                            </span>
                                        <span>
                                                <button title="Editar"  data-toggle="modal" data-toggle="tooltip" data-placement="top" onclick="tipoOperacao = 'editar'; idUsuario = {{$d->id}}" data-target="#formIncluir" class="btn btn-success btn-sm" data-placement="left"><i class="fas fa-edit"></i></button>
                                            </span>
                                        <span>
                                                <button title="Excluir" data-toggle="tooltip" data-placement="top" onclick="modalExcluir('{{$d->id}}', '#{{$d->id}}')" class="btn btn-danger btn-sm" data-placement="left" title="Excluir"><i class="fas fa-trash-alt"></i></button>
                                            </span>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <nav class="mt-4" aria-label="Page navigation example">
                {{ $dados->appends(Request::all())->links() }}
            </nav>
        </div>
    </section>

    @include('fr/gestao/usuarios/form')

    <!-- EXCLUIR -->
    <div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"> <i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Exclusão de Usuário</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                Você deseja mesmo excluir esse registro?<br><br>
                                <b id="nomeUsuario"></b>
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

        var idUsuario = 0;
        var idExcluir = 0;
        var tipoOperacao = '';

        function modalExcluir(id, nome)
        {
            idExcluir = id;
            $('#nomeUsuario').html(nome);
            $('#formExcluir').modal('show');
        }
        function excluir()
        {
            window.location.href = '{{url("/gestao/usuario/excluir/")}}/'+idExcluir;
        }


        $('#formExcluir').on('hidden.bs.modal', function () {
            idExcluir = 0;
        });

        $('#formIncluir').on('show.bs.modal', function () {
            if(tipoOperacao== 'editar')
            {
                $.ajax({
                    url: '{{url('/gestao/usuario/get/')}}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        id: idUsuario,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data) {
                        popularForm($('#formIncluir'), data);

                        for(i=0; i<data.permissoes.length; i++) {
                            addPermissaoHtml(data.permissoes[i].permissao, data.permissoes[i].escola_id, data.permissoes[i].instituicao_id, data.permissoes[i].nome_permissao, data.permissoes[i].escola);
                        }
                    },
                    error: function(data) {
                        swal("", "Usuário não encontrado.", "error");
                    }
                });
                $('#tituloForm').html('Edição de Usuário');
                $('#btnForm').html('Editar');

            }
            else
            {
                $('#tituloForm').html('Cadastro de Usuário');
                $('#btnForm').html('Cadastrar');
            }
        });

        $('#formIncluir').on('hidden.bs.modal', function () {
            limpaForm('#formIncluir');
            tipoOperacao = '';
            vet = new Array();
            selectEscola.setData(vet);
            $('#listaPermissao').html('');
        });

        function enviaFormulario()
        {
            if(tipoOperacao == 'add')
            {
                $('#formFormulario').attr('action','{{url('/gestao/usuario/add/')}}');
                $('#formFormulario').submit();
            }
            else if(tipoOperacao == 'editar')
            {
                $('#formFormulario').attr('action','{{url('/gestao/usuario/editar/')}}');
                $('#formFormulario').submit();
            }
        }


        $(document).ready(function(){
            @if($errors->all() != null)
            $('#formIncluir').modal('show');
            @if(old('id')!='')
                tipoOperacao = 'editar';
            $('#tituloForm').html('Edição de Instituição');
            $('#btnForm').html('Editar');
            @if(old('existeImg'))
            $('#logoCadastro').show();
            $('#novaLogo').hide();
            @endif
                @else
                tipoOperacao = 'add';
            @endif
            @endif
        })





    </script>


@stop
