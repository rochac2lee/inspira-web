@extends('fr/master')
@section('content')

    <link href="{{config('app.cdn')}}/fr/includes/js/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css" rel="stylesheet">
    <script src="{{config('app.cdn')}}/fr/includes/js/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>

    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.jquery.min.js"></script>
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.css">
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/formUtilities.js"></script>
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
            <div class="row mb-3" style="margin-top: -40px">
                <div class="col-md-12">
                    <div class="filter">
                        <form class="form-inline d-flex justify-content-end" action="" method="get">
                            <div class="input-group ml-1">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </div>
                                <input type="text" value="{{Request::input('nome')}}" name="nome" placeholder="Nome da Instituição" class="form-control" />
                            </div>
                            <div class="input-group ml-1">
                                <select class="form-control" name="status">
                                    <option value="" @if(Request::input('status')=='') selected @endif>Status</option>
                                    <option value="1" @if(Request::input('status')=='1') selected @endif>Ativo</option>
                                    <option value="0" @if(Request::input('status')=='0') selected @endif>Inativo</option>
                                    <option value="">Todos</option>
                                </select>
                            </div>
                            <div class="input-group ml-1">
                                <select class="form-control" name="tipo">
                                    <option value="" @if(Request::input('tipo')=='') selected @endif>Tipo</option>
                                    @foreach($tipo as $t){
                                    <option @if(Request::input('tipo')==$t->id) selected @endif value="{{$t->id}}">{{$t->titulo}}</option>
                                    @endforeach
                                    <option value="">Todos</option>
                                </select>
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
                    <h3>Gestão de Instituições</h3>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success w-100" onclick="tipoOperacao = 'add'" data-toggle="modal" data-target="#formIncluir">
                        <i class="fas fa-plus"></i>
                        Nova Instituição
                    </button>
                </div>
            </div>
            <div class="row">
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Instituição</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Ativo</th>
                                <th scope="col" class="text-right">Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dados as $d)
                                <tr>
                                    <td >{{$d->id}}</td>
                                    <td>
                                        {{$d->titulo}}
                                        <p></p>
                                    </td>
                                    <td>@if($d->tipo_instituicao!=null)<b>{{$d->tipo_instituicao->titulo}}<b>@endif</td>

                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input onchange="mudaStatus({{$d->id}},this)" type="checkbox" @if($d->status_id==1) checked @endif  class="custom-control-input" id="status{{$d->id}}" value="1">
                                            <label class="custom-control-label pt-1" for="status{{$d->id}}"></label>
                                        </div>
                                    </td>

                                    <td class="text-right">
                                           <span>
                                            <a href="{{url('/gestao/escolas?inst='.$d->id)}}" data-toggle="tooltip" data-placement="top" title="Ver escolas" class="btn btn-success btn-sm" ><i class="fas fa-school"></i></a>
                                           </span>
                                        <span>
                                                <a href="{{url('/gestao/instituicao/'.$d->id.'/material')}}" data-toggle="tooltip" data-placement="top" title="Gerenciar material" class="btn btn-success btn-sm" ><i class="far fa-address-card"></i></a>
                                            </span>
                                        <span>
                                                <button title="Editar" data-toggle="tooltip"  onclick="editarInst({{$d->id}})" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></button>
                                            </span>
                                        <span>
                                                <button title="Excluir" data-toggle="tooltip" data-placement="top" onclick="modalExcluir('{{$d->id}}', '{{$d->titulo}}')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
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

    @include('fr/gestao/instituicao/form')

    <!-- EXCLUIR -->
    <div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Exclusão de Instituição</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                Você deseja mesmo excluir esse registro?<br><br>
                                <b id="nomeInstituicao">Nome da Instituição</b>
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

        var idExcluir = 0;
        var tipoOperacao = '';
        var idInstituicao = '{{old('id')}}';
        var idUserAdm = '{{old('idUserAdm')}}';
        var userADM = '';
        var userEmail ='';

        function editarInst(id){
            tipoOperacao = 'editar';
            idInstituicao = id;
            $('#formIncluir').modal('show');
        }

        function editarADM()
        {
            $('#adm_inst').val(userADM);
            $('#adm_email').val(userEmail);
            $('#adm_inst').prop('readonly', false);
            $('#adm_email').prop('readonly', false);
        }

        function novoADM()
        {
            $('#adm_inst').val('');
            $('#adm_email').val('');
            $('#adm_inst').prop('readonly', false);
            $('#adm_email').prop('readonly', false);

        }

        function modalExcluir(id, nome)
        {
            idExcluir = id;
            $('#nomeInstituicao').html(nome);
            $('#formExcluir').modal('show');
        }
        function excluir()
        {
            window.location.href = '{{url("/gestao/instituicao/excluir/")}}/'+idExcluir;
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
            $('#adm_inst').prop('readonly', false);
            $('#adm_email').prop('readonly', false);
            $('#editarADM').hide();
            if(tipoOperacao== 'editar')
            {
                $('#editarADM').show();
                $.ajax({
                    url: '{{url('/gestao/instituicao/get/')}}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        id: idInstituicao,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data) {
                        popularForm($('#formIncluir'), data);
                        userADM   = data['adm_instituicao'];
                        userEmail = data['adm_email'];

                        if(data['logo_url'] != '' && data['logo_url'] != null){
                            $('#imgLogo').attr('src','{{ config('app.app').'/storage/logo_instituicao/'}}'+data['logo_url']);
                            $('#existeImg').val(data['logo_url']);
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
                        swal("", "Instituição não encontrada", "error");
                    }
                });
                $('#editarADM').show();
                $('#adm_inst').prop('readonly', true);
                $('#adm_email').prop('readonly', true);
                $('#tituloForm').html('Edição de Instituição');
                $('#btnForm').html('Editar');

            }
            else
            {
                $('#tituloForm').html('Cadastro de Instituição');
                $('#btnForm').html('Cadastrar');
                $('#logoCadastro').hide();
                $('#novaLogo').show();
            }
            $('#cor_primaria').trigger('change');
            $('#cor_secundaria').trigger('change');
        });

        $('#formIncluir').on('hidden.bs.modal', function () {
            limpaForm('#formIncluir');
            tipoOperacao = '';

            $('#cor_primaria').val('#ff8040');
            $('#cor_secundaria').val('#c25022');
            $('#cor_primaria').trigger('change');
            $('#cor_secundaria').trigger('change');
        });

        function enviaFormulario()
        {
            if(tipoOperacao == 'add')
            {
                $('#idInstituicao').val('');
                $('#idUserAdm').val('');
                $('#formFormulario').attr('action','{{url('/gestao/instituicao/add/')}}');
                $('#formFormulario').submit();
            }
            else if(tipoOperacao == 'editar')
            {
                $('#formFormulario').attr('action','{{url('/gestao/instituicao/editar/')}}');
                $('#formFormulario').submit();
            }
        }

        function mudaStatus(idInstituicao,elemento)
        {
            status = 0;
            if($(elemento).is(":checked"))
            {
                status = 1;
            }
            $.ajax({
                url: '{{url('/gestao/instituicao/mudaStatus/')}}',
                type: 'post',
                dataType: 'json',
                data: {
                    id: idInstituicao,
                    status_id: status,
                    _token: '{{csrf_token()}}'
                },
                error: function(data) {
                    swal("", "Não foi possível alterar o status", "error");
                }
            });
        }

        $(document).ready(function(){
            @if($errors->all() != null)
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
                $('#formIncluir').modal('show');
            @endif
        })





    </script>


@stop
