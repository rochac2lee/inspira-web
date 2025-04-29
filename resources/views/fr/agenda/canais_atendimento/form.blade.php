@extends('fr/master')
@section('content')

    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/jquery.inputmask.bundle.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.jquery.min.js"></script>
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.css">
<script>
    $(document).ready(function(){
        /* configuracoes basicas do plugin de recortar imagem */
        var configuracao = {
            ratio: '1:1',
            crop: {
                x: 200,
                y: 200,
                width: 200,
                height: 200
            },
            download: false,
            label: '<label for="exampleFormControlFile1">Insira uma Imagem</label> <i class="fas fa-file h5"></i> <br>Tamanho da imagem: 200px X 200px ',
            buttonConfirmLabel: 'Ok',
        }

        /* carrega o plugin de recortar imagem */
        $(".myCropper").slim(configuracao);
    });
</script>
    <section class="section section-interna mb-5" style="padding-top: 50px">
        <div class="container">
            @include('fr.agenda.menu')
            <div class="row border-top pt-4">
                <div class="col-md-4 pb-2">
                    <h3>
                        <a href="{{ url('/gestao/agenda/canais-atendimento')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        @if ( strpos(Request::path(),'editar')===false )
                            Novo Canal
                        @else
                            Editar Canal
                        @endif
                    </h3>
                </div>

            </div>
            <form id="formCadastroComunicado" action="@if ( strpos(Request::path(),'editar')===false ) {{url('/gestao/agenda/canais-atendimento/add')}} @else {{url('/gestao/agenda/canais-atendimento/editar')}} @endif " method="post" enctype="multipart/form-data">
                <div  class="row pt-1 pb-1">
                    <div class="col-12">
                        <small>* campos obrigatórios.</small>
                    </div>
                </div>
                <div class="row">
                    @csrf
                    @if ( strpos(Request::path(),'editar')!==false )
                        <input type="hidden" name="id" value="{{old('id',@$dados->id)}}">
                    @endif

                    <div class="col-md-6 bg-light border-right pt-4 pb-4 pl-4 pr-5">
                        <h4 class="pb-3 border-bottom mb-4">Dados de visualização</h4>

                        <div class="form-group">
                            <label>* Este canal de atendimento será visualizado por:</label>
                            <select class="form-control" name="visualizacao" id="visualizacao" onchange="mudaVisualizacao(this)">
                                <option @if(old('visualizacao') == 1) selected @endif value="1">
                                    Todas as escolas de sua instituição
                                </option>
                                <option @if(old('visualizacao') == 2 || (isset($dados->escolas) && count($dados->escolas)>0)) selected @endif value="2">
                                        Selecionar escolas específicas de sua instituição
                                </option>
                            </select>
                            <div class="invalid-feedback">{{ $errors->first('visualizacao') }}</div>
                        </div>
                        <div class="form-group" id="btnEscolaTurma">
                            <button type="button" class="btn btn-success" onclick="$('#formEscolasTrumas').modal('show')"><i class="fas fa-plus"></i> Adicione escolas</button>
                        </div>
                        <hr>
                        <h6 class="text-center">* Escolas selecionadas</h6>
                        <div class="row mb-4">
                            <div class="col">
                                <ul id="listaPermissao" class="list-style">

                                </ul>
                                <div class="invalid-feedback" style="display: block">{{ $errors->first('escola') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 pl-5">
                        <div class="pt-4">
                            <h4 class="pb-3 border-bottom mb-4">Canal de atendimento</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>* Imagem:</label>
                                    <div id="logoCadastro" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                        <input type="hidden" name="existeImg" id="existeImg" value="{{old('existeImg',@$dados->imagem)}}">
                                        <img id="imgLogo" class="img-fluid" width="100px" src="@if(@$dados->imagem != ''){{$dados->linkImagem}}@endif">
                                        <br>
                                        <a class="btn btn-secondary" onclick="excluirLogo()">Excluir Imagem</a>
                                    </div>
                                    <div id="novaLogo" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                        <input type="file" name="imagem" class="myCropper">
                                    </div>
                                    <div class="invalid-feedback" style="display: block">{{ $errors->first('imagem') }}</div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>* Nome:</label>
                                    <input name="nome" type="text" placeholder="" value="{{old('nome',@$dados->nome)}}" class="form-control rounded {{ $errors->has('nome') ? 'is-invalid' : '' }}">
                                    <div class="invalid-feedback">{{ $errors->first('nome') }}</div>
                                </div>
                                <div class="form-group">
                                    <label>* Cargo:</label>
                                    <input name="cargo" type="text" placeholder="" value="{{old('cargo',@$dados->cargo)}}" class="form-control rounded {{ $errors->has('cargo') ? 'is-invalid' : '' }}">
                                    <div class="invalid-feedback">{{ $errors->first('cargo') }}</div>
                                </div>

                            </div>
                                <div class="form-group col-12">
                                    <label>* E-mail:</label>
                                    <input name="email" type="text" placeholder="" value="{{old('email',@$dados->email)}}" class="form-control rounded {{ $errors->has('email') ? 'is-invalid' : '' }}">
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                </div>
                                <div class="form-group col-6">
                                    <label>* Telefone:</label>
                                    <input id="telefone" name="telefone" type="text" placeholder="" value="{{old('telefone',@$dados->telefone)}}" class="form-control rounded {{ $errors->has('telefone') ? 'is-invalid' : '' }}">
                                    <div class="invalid-feedback">{{ $errors->first('telefone') }}</div>
                                </div>
                                <div class="form-group col-6">
                                    <label>Aceita WhatsApp:</label>
                                    <div class="custom-control custom-switch">
                                        <input name="telefone_eh_zap" @if(old('telefone_eh_zap',@$dados->telefone_eh_zap) == '1') checked @endif  value="1" type="checkbox" class="custom-control-input" id="customSwitch2">
                                        <label class="custom-control-label pt-1" for="customSwitch2">O número de telefone recebe WhatsApp?</label>
                                        <small class="form-text w-100 text-muted">
                                            Ao ativar essa opção, os responsáveis saberão que, além do e-mail, também poderão entrar em contato com o profissional da escola via WhatsApp.
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group col-12 ">
                                    <div class="custom-control custom-switch">
                                        <input name="publicado" @if(old('publicado',@$dados->publicado) == '1') checked @endif  value="1" type="checkbox" class="custom-control-input" id="customSwitch1">
                                        <label class="custom-control-label pt-1" for="customSwitch1">Publicar Canal?</label>
                                        <small class="form-text w-100 text-muted">
                                            Se publicado, os usuários visualizarão imediatamente os dados do canal de atendimento cadastrado.
                                        </small>
                                    </div>
                                </div>

                            </div>
                        <a href="{{ url('/gestao/agenda/canais-atendimento')}}" class="btn btn-secondary float-left">Cancelar</a>
                        <button class="btn btn-default mt-0 float-right ml-2">Salvar</button>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </section>

    <div class="modal fade" id="formEscolasTrumas" tabindex="0" role="dialog" aria-labelledby="formEscolasTrumas" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloForm">Seleção de Escola e Turmas</h5>
                </div>
                <div class="modal-body pb-0">
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
                                        <input type="text" id="buscaNome" value="" placeholder="Nome da escola" class="form-control" />
                                    </div>

                                    <div class="input-group ml-1">
                                        <button type="button" onclick="buscarEscolas('')" class="btn btn-secondary btn-sm">Localizar</button>
                                    </div>
                                    <div class="input-group ml-1">
                                        <button type="button" onclick="$('#buscaNome').val(''); buscarEscolas(''); " class="btn btn-secondary btn-sm">Limpar Filtros</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12" id="tabelaEscolasTurmas">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Finalizar</button>
                </div>
            </div>
        </div>
    </div>
    <form id="formDadosEditar">
        @if(old('escola')!= '')
            @foreach(old('escola') as $e)
                <input type="hidden" name="escola[]" value="{{$e}}">
            @endforeach
        @elseif( isset($dados->escolas) && count($dados->escolas)>0)
            @foreach($dados->escolas as $e)
                <input type="hidden" name="escola[]" value="{{$e->id}}">
            @endforeach
        @else
            <input type="hidden" name="escola[]" value="0">
        @endif
    </form>
    <script>
        function excluirLogo()
        {
            $('#logoCadastro').hide();
            $('#novaLogo').show();
            $('#existeImg').val('');
        }

        $(document).ready(function(){
            @if(@$dados->imagem != '')
                $('#logoCadastro').show();
                $('#novaLogo').hide();
            @else
                $('#logoCadastro').hide();
                $('#novaLogo').show();
            @endif

            $("#telefone").inputmask({
                mask: ["(99) 9999-9999","(99) 99999-9999" ],
                keepStatic: true
            });

            $('#visualizacao').change();
            @if( ( (isset($dados->escolas) && $dados->escolas) || old('escola') ))
            if($('#visualizacao').val() != 1) {
                $.ajax({
                    url: '{{url('gestao/agenda/canais-atendimento/getEscolasSelecionados')}}?' + $('#formDadosEditar').serialize(),
                    type: 'post',
                    dataType: 'json',
                    data: {
                        _token: '{{csrf_token()}}'
                    },
                    success: function (data) {
                        $('#listaPermissao').html(data);
                    },
                    error: function () {
                        swal("", "Não foi possível carregar a lista de escolas selecionadas.", "error");
                    }
                });
            }
            @endif
        });

        function mudaVisualizacao(elemento) {
            valor = $(elemento).val();
            if(valor == 1){
                $('#btnEscolaTurma').hide();
                @if(auth()->user()->permissao == 'I')
                $('#listaPermissao').html('<li class="ui-state-default text-truncate"><input type="hidden" name="escola[]" value="0"><input type="hidden" name="turma[0][]" value="0"> <i class="fas fa-check"></i><span class="m-2"></span> Todas as escolas e turmas.</li>');
                @else
                $('#listaPermissao').html('<li class="ui-state-default text-truncate"><input type="hidden" name="escola[]" value="{{auth()->user()->escola_id}}"><input type="hidden" name="turma[{{auth()->user()->escola_id}}][]" value="0"> <i class="fas fa-check"></i><span class="m-2"></span> Todas as turmas de sua escola.</li>');
                @endif
            }
            else{
                $('#btnEscolaTurma').show();
                $('#listaPermissao').html('');
                //$('#listaPermissao').html('<li class="ui-state-default text-truncate"> Selecione as escolas e tumas.</li>');
            }
        }

        $('#formEscolasTrumas').on('show.bs.modal', function () {
            $('#buscaNome').val('');
            ehProfessor = 1;
            buscarEscolas('');
        });

        $(document).on('click', '.page-link', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            buscarEscolas(page);
        });

        function buscarEscolas(page){
            $.ajax({
                url: '{{url('gestao/agenda/canais-atendimento/getEscolas')}}?'+$('#formCadastroComunicado input[type=hidden]').serialize(),
                type: 'post',
                dataType: 'json',
                data: {
                    nome: $('#buscaNome').val(),
                    page: page,
                    comTurma:1,
                    status:1,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    $('#tabelaEscolasTurmas').html(data);
                },
                error: function() {
                    swal("", "Não foi possível carregar a lista de escolas.", "error");
                }
            });
        }

        function adicionaEscola(escolaId){

            if($('#selecionadaEscola'+escolaId).length == 0) {
                nomeEscola= $('#nomeEscola'+escolaId).html();
                var add = '<li class="ui-state-default text-truncate" id="selecionadaEscola' + escolaId + '">';
                add += '<input type="hidden" name="escola[]" value="'+escolaId+'">';
                add += '<b>' + nomeEscola + '</b></li>';
                $('#listaPermissao').append(add);
            }

        }
    </script>
@stop



