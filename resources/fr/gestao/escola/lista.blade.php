@extends('fr/master')
@section('content')

    <link href="{{config('app.cdn')}}/fr/includes/js/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css" rel="stylesheet">
    <script src="{{config('app.cdn')}}/fr/includes/js/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>

    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.jquery.min.js"></script>
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.css">
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/formUtilities.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/jquery.inputmask.bundle.min.js"></script>
    <script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet"></link>
    <script type="text/javascript">

            $(document).ready(function(){
                $('.input-colorpicker').colorpicker();


                /* configuracoes basicas do plugin de recortar imagem */
                var configuracao = {
                    ratio: '4:3',
                    crop: {
                        x: 800,
                        y: 600,
                        width: 800,
                        height: 600
                    },
                    download: false,
                    label: '<label for="exampleFormControlFile1">Insira uma Imagem</label> <i class="fas fa-file h5"></i> <br>Tamanho da imagem: 800px X 600px ',
                    buttonConfirmLabel: 'Ok',
                }

                /* carrega o plugin de recortar imagem */
                $(".myCropper").slim(configuracao);
            });

        </script>

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
                                <input type="text" name="nome" value="{{Request::input('nome')}}" placeholder="Nome da Escola" class="form-control" />
                            </div>
                            @if(auth()->user()->permissao == 'Z')
                                <div class="input-group ml-1">
                                    <input type="text" name="inst" value="{{Request::input('inst')}}" placeholder="Nome da Instituição" class="form-control" />
                                </div>
                                <div class="input-group ml-1">
                                    <select class="form-control" name="status">
                                        <option value="" @if(Request::input('status')=='') selected @endif>Status</option>
                                        <option value="1" @if(Request::input('status')=='1') selected @endif>Ativo</option>
                                        <option value="0" @if(Request::input('status')=='0') selected @endif>Inativo</option>
                                        <option value="">Todos</option>
                                    </select>
                                </div>
                            @endif
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
                <div class="col-md-4">
                    <h3>Gestão de Escolas</h3>
                </div>
                @if(auth()->user()->permissao == 'Z')
                    <div class="col-md-6 text-right">
                        <a href="{{url('/gestao/escola/acessos/')}}" class="btn btn-warning text-light mr-5" title="Relatório de acessos dos usuários" data-toggle="tooltip" data-placement="top">
                            <i class="fas fa-clipboard-list"></i> Acessos
                        </a>
                        <button class="btn btn-warning text-light" data-toggle="modal" data-target="#formImportar">
                            <i class="fas fa-file-upload"></i>
                            Importar usuários em lote
                        </button>
                        <a href="/gestao/escola/relatorio/importacao/usuarios" class="btn btn-warning text-light" title="Relatório de importações" data-toggle="tooltip" data-placement="top">
                            <i class="fas fa-clipboard-list"></i>
                        </a>

                    </div>
                    <div class="col-md-2 text-right">
                        <button class="btn btn-success" data-toggle="modal" data-target="#formIncluir" onclick="tipoOperacao = 'add'">
                            <i class="fas fa-plus"></i>
                            Nova Escola
                        </button>
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
                                    <th scope="col">Escola</th>
                                    <th scope="col">Turmas</th>
                                    <th scope="col">Docentes</th>
                                    <th scope="col">Estudantes</th>
                                    <th scope="col">Responsáveis</th>
                                    <th scope="col">Status</th>
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
                                        {{$d->titulo}}
                                        <small><p>{{$d->instituicao}}</p></small>
                                    </td>
                                    <td >@if(isset($d->qtdTurmas[0])){{$d->qtdTurmas[0]->qtd}} @else 0 @endif</td>
                                    <td >@if(isset($d->qtdProfessores[0])){{$d->qtdProfessores[0]->qtd}} @else 0 @endif</td>
                                    <td >@if(isset($d->qtdAlunos[0])){{$d->qtdAlunos[0]->qtd}} @else 0 @endif</b></td>
                                    <td >@if(isset($d->responsavelEscola[0])){{$d->responsavelEscola[0]->qtd}} @else 0 @endif</td>
                                    <td >
                                        <div class="custom-control custom-switch">
                                            <input onchange="mudaStatus({{$d->id}},this)" type="checkbox" @if($d->status_id==1) checked @endif  class="custom-control-input" id="statusCadastro{{$d->id}}" value="1">
                                            <label class="custom-control-label pt-1" for="statusCadastro{{$d->id}}"></label>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        @if(auth()->user()->permissao == 'Z')
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown">
                                            <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{url('/gestao/escola/'.$d->id.'/material/')}}">Gerenciar Material</a>

                                                <a class="dropdown-item" href="{{url('/gestao/escola/'.$d->id.'/docentes')}}">Gerenciar Docentes</a>
                                                <a class="dropdown-item" href="{{url('/gestao/escola/'.$d->id.'/alunos/')}}">Gerenciar Alunos</a>
                                                <a class="dropdown-item" href="{{url('/gestao/escola/'.$d->id.'/turmas/')}}">Gerenciar Turmas</a>
                                                <a class="dropdown-item" href="{{url('/gestao/escola/'.$d->id.'/responsaveis/')}}">Gerenciar Responsáveis</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{url('/gestao/escola/'.$d->id.'/acessos/')}}">Log de acessos</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" onclick="tipoOperacao = 'editar'; idEscola= {{$d->id}}" data-toggle="modal" data-target="#formIncluir">Editar</a>
                                                <a class="dropdown-item" onclick="modalExcluir('{{$d->id}}', '{{$d->titulo}}')">Excluir</a>
                                            </div>
                                        </div>
                                        @elseif(auth()->user()->permissao == 'I')
                                            <span>
                                                <a href="{{url('/gestao/escola/acessos/'.$d->id)}}" data-toggle="tooltip" data-placement="top" title="Relatório de acessos" class="btn btn-success btn-sm" ><i class="fas fa-clipboard-list"></i> </a>
                                            </span>
                                            <span>
                                                <a href="{{url('/gestao/escola/'.$d->id.'/docentes')}}" data-toggle="tooltip" data-placement="top" title="Gerenciar Docentes" class="btn btn-success btn-sm" ><i class="fas fa-user-graduate"></i> </a>
                                            </span>
                                            <span>
                                                <a href="{{url('/gestao/escola/'.$d->id.'/alunos/')}}" data-toggle="tooltip" data-placement="top" title="Gerenciar Alunos" class="btn btn-success btn-sm" ><i class="fas fa-user"></i></a>
                                            </span>
                                            <span>
                                                <a href="{{url('/gestao/escola/'.$d->id.'/turmas/')}}" data-toggle="tooltip" data-placement="top" title="Gerenciar Turmas" class="btn btn-success btn-sm" ><i class="fas fa-users"></i></a>
                                            </span>
                                            <span>
                                                <a href="{{url('/gestao/escola/'.$d->id.'/responsaveis/')}}" data-toggle="tooltip" data-placement="top" title="Gerenciar Responsáveis" class="btn btn-success btn-sm" ><i class="fas fa-user-tie"></i></a>
                                            </span>

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

 @include('fr/gestao/escola/form')

    <!-- EXCLUIR -->
    <div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Exclusão de Instituição</h5>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="row">
                        <div class="col-12">
                            Você deseja mesmo excluir esse registro?<br><br>
                            <b id="nomeEscola">Nome da Instituição</b>
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
    <!-- IMPORTAR ARQUIVO -->
    <div class="modal fade" id="formImportar" tabindex="-1" role="dialog" aria-labelledby="formImportar" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Importação de usuários em lote</h5>
                </div>
                <div class="modal-body">
                    <form action="{{url('/gestao/escola/importacao/usuarios/lote')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Formato do arquivo *</label>
                                    <select class="form-control rounded {{ $errors->has('tipo_arquivo') ? 'is-invalid' : '' }}" name="tipo_arquivo" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
                                        <option value="">Selecione</option>
                                        <option value="2">Importação google fila</option>
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

    function mudaStatus(idEscola,elemento)
    {
        status = 0;
        if($(elemento).is(":checked"))
        {
            status = 1;
        }
        $.ajax({
            url: '{{url('/gestao/escola/mudaStatus/')}}',
            type: 'post',
            dataType: 'json',
            data: {
                id: idEscola,
                status_id: status,
                _token: '{{csrf_token()}}'
            },
            error: function(data) {
                swal("", "Não foi possível alterar o status", "error");
            }
        });
    }

        var idExcluir = 0;
        var tipoOperacao = '';
        var idEscola = '{{old('id')}}';
        var idUserAdm = '{{old('idUserAdm')}}';
        var userADM = '';
        var userEmail ='';

        function editarADM()
        {
            $('#adm_escola').val(userADM);
            $('#adm_email').val(userEmail);
            $('#adm_escola').prop('readonly', false);
            $('#adm_email').prop('readonly', false);
        }

        function novoADM()
        {
            $('#adm_escola').val('');
            $('#adm_email').val('');
            $('#adm_escola').prop('readonly', false);
            $('#adm_email').prop('readonly', false);

        }

        function modalExcluir(id, nome)
        {
            idExcluir = id;
            $('#nomeEscola').html(nome);
            $('#formExcluir').modal('show');
        }
        function excluir()
        {
            window.location.href = '{{url("/gestao/escola/excluir/")}}/'+idExcluir;
        }

        function excluirLogo()
        {
            $('#logoCadastro').hide();
            $('#novaLogo').show();
        }

        $('#formExcluir').on('hidden.bs.modal', function () {
            idExcluir = 0;
        });

        $('#formIncluir').on('show.bs.modal', function () {
            $('#adm_escola').prop('readonly', false);
            $('#adm_email').prop('readonly', false);
            $('#editarADM').hide();

            if(tipoOperacao== 'editar')
            {
                $('#editarADM').show();
                $.ajax({
                    url: '{{url('/gestao/escola/get/')}}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        id: idEscola,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data) {
                        popularForm($('#formIncluir'), data);
                        userADM   = data['adm_escola'];
                        userEmail = data['adm_email'];

                        selectEtapaAno.set(data['etapa_ano']);
                        selectInst.set(data['instituicao_id']);

                        if(data['logo'] != '' && data['logo'] != null){
                            $('#imgLogo').attr('src','{{ config('app.cdn').'/storage/logo_escola/'}}'+data['logo']);
                            $('#existeImg').val(data['logo']);
                            $('#logoCadastro').show();
                            $('#novaLogo').hide();
                        }
                        else
                        {
                            $('#logoCadastro').hide();
                            $('#novaLogo').show();
                        }
                    },
                    error: function(data) {
                        swal("", "Escola não encontrada", "error");
                    }
                });
                $('#editarADM').show();
                $('#adm_escola').prop('readonly', true);
                $('#adm_email').prop('readonly', true);

                $('#tituloForm').html('Edição de Escola');
                $('#btnForm').html('Editar');

            }
            else
            {
                $('#tituloForm').html('Cadastro de Escola');
                $('#btnForm').html('Cadastrar');
                $('#logoCadastro').hide();
                $('#novaLogo').show();
            }
            $('#cor_primaria').trigger('change');
        });

        $('#formIncluir').on('hidden.bs.modal', function () {
            limpaForm('#formIncluir');
            tipoOperacao = '';

            $('#cor_primaria').val('#ff8040');
            $('#cor_primaria').trigger('change');
        });

        function enviaFormulario()
        {

            if(tipoOperacao == 'add')
            {
                $('#idEscola').val('');
                $('#idUserAdm').val('');
                $('#formFormulario').attr('action','{{url('/gestao/escola/add/')}}');
                $('#formFormulario').submit();
            }
            else if(tipoOperacao == 'editar')
            {
                $('#formFormulario').attr('action','{{url('/gestao/escola/editar/')}}');
                $('#formFormulario').submit();
            }
        }

        $(document).ready(function(){
            @if($errors->all() != null && !$errors->has('tipo_arquivo') && !$errors->has('arquivo'))
                $('#formIncluir').modal('show');
                @if(old('id')!='')
                    tipoOperacao = 'editar';
                    $('#editarADM').show();
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

            @if($errors->has('tipo_arquivo') || $errors->has('arquivo'))
                $('#formImportar').modal('show');
            @endif
        });

        $("#cep").focusout(function() {
            //Início do Comando AJAX
            cep = $(this).val();
            $.ajax({
                url: 'https://viacep.com.br/ws/' + cep + '/json/unicode/',
                dataType: 'json',
                success: function(resposta) {

                    if (resposta.localidade == undefined) {}

                    $("#logradouro").val(resposta.logradouro);
                    $("#bairro").val(resposta.bairro);
                    $("#uf").val(resposta.uf);
                    $("#cidade").val(resposta.localidade);
                    $("#numero").focus();
                }
            });
        });

        $("#cep").inputmask({
            mask: ["99999-999", ],
            keepStatic: true
        });

        $("#cnpj").inputmask({
            mask: ["99.999.999/9999-99", ],
            keepStatic: true
        });

        var selectEtapaAno = new SlimSelect({
                select: '.multipleEtapa',
                placeholder: 'Buscar',
                searchPlaceholder: 'Buscar',
                closeOnSelect: true,
                allowDeselectOption: true,
                selectByGroup: true,
            });

        var selectInst = new SlimSelect({
                select: '.multipleInst',
                placeholder: 'Buscar',
                searchPlaceholder: 'Buscar',
                closeOnSelect: true,
                allowDeselectOption: true,
                selectByGroup: true,
            });


    </script>


@stop
