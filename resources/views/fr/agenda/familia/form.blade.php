@extends('fr/master')
@section('content')
    <section class="section section-interna mb-5" style="padding-top: 50px">
        <div class="container">
            @if(auth()->user()->permissao != 'Z')
                @include('fr.agenda.menu')
            @endif
            <div class="row border-bottom @if(auth()->user()->permissao != 'Z') border-top @endif pt-4">
                <div class="col-md-12 pb-2">
                    <h3>
                        <a href="{{ url('/gestao/agenda/familia')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        @if ( strpos(Request::path(),'editar')===false )
                            Novo Comunicado em Espaço da Família
                        @else
                            Editar Comunicado em Espaço da Família
                        @endif
                    </h3>
                </div>

            </div>
            <form id="formCadastroComunicado" action="@if ( strpos(Request::path(),'editar')===false ) {{url('/gestao/agenda/familia/add')}} @else {{url('/gestao/agenda/familia/editar')}} @endif " method="post" enctype="multipart/form-data">
            <div class="row">
                @csrf
                @if ( strpos(Request::path(),'editar')!==false )
                    <input type="hidden" name="id" value="{{old('id',@$dados->id)}}">
                @endif

                <div class="col-md-6 bg-light border-right pt-4 pb-4 pl-4 pr-5">
                    <h4 class="pb-3 border-bottom mb-4">Dados de visualização</h4>

                    <div class="form-group">
                        <label>Este comunicado será visualizado por: *</label>
                        <select class="form-control" name="vizualizacao" id="vizualizacao" onchange="mudaVisualizacao(this)">
                            <option @if(old('vizualizacao', @$dados->vizualizacao) == 1) selected @endif value="1"> Todas as instituições e escolas da rede </option>
                            <option @if(old('vizualizacao', @$dados->vizualizacao) == 2) selected @endif value="2"> Todas as instituições e escolas PÚBLICAS  </option>
                            <option @if(old('vizualizacao', @$dados->vizualizacao) == 3) selected @endif value="3"> Todas as instituições e escolas PRIVADAS </option>
                            <option @if(old('vizualizacao', @$dados->vizualizacao) == 4) selected @endif value="4">
                                    Adicionar e selecionar instituicaoes e escolas específicas
                            </option>
                        </select>
                        <div class="invalid-feedback">{{ $errors->first('vizualizacao') }}</div>
                    </div>
                    <div class="form-group" id="btnEscolaTurma">
                        <button type="button" class="btn btn-success" onclick="$('#formEscolasTrumas').modal('show')"><i class="fas fa-plus"></i> Adicione instituições e escolas</button>
                    </div>
                    <hr>
                    <h6 class="text-center">Instituições e escolas selecionadas *</h6>
                    <div class="row mb-4">
                        <div class="col">
                            <ul id="listaPermissao" class="list-style">

                            </ul>
                            <div class="invalid-feedback" style="display: block">{{ $errors->first('instituicao') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 pl-5">
                    <div class="pt-4">
                        <h4 class="pb-3 border-bottom mb-4">Comunicado</h4>
                    </div>

                    <div class="form-group">
                        <label>*Título</label>
                        <input name="titulo" type="text" placeholder="" value="{{old('titulo',@$dados->titulo)}}" class="form-control rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
                        <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                    </div>
                    <div class="form-group">
                        <label>*Descrição</label>
                        <textarea rows="9" name="descricao" class="form-control rounded {{ $errors->has('descricao') ? 'is-invalid' : '' }}">{{old('descricao',@$dados->descricao)}}</textarea>
                        <div class="invalid-feedback">{{ $errors->first('descricao') }}</div>
                    </div>

                    <div class="form-group">
                        <label>Imagem</label>
                        @if(strpos(Request::path(),'editar')===false)
                        <input name="imagem" type="file" accept="image/*"  class="form-control rounded {{ $errors->has('imagem') ? 'is-invalid' : '' }}">
                        <small class="form-text w-100 text-muted">Caso deseje publicar mais de uma imagem, clique no botão Salvar e clique na opção Gerenciar Imagens.</small>
                        <div class="invalid-feedback">{{ $errors->first('imagem') }}</div>
                        @else
                            <input class="form-control" type="text" disabled  value="Você deverá editar as imagens na opção Gerenciar Imagens na tela anterior.">
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Link Vídeo</label>
                        <input name="link_video" type="text" placeholder="" value="{{old('link_video',@$dados->link_video)}}" class="form-control rounded {{ $errors->has('link_video') ? 'is-invalid' : '' }}">
                        <div class="invalid-feedback">{{ $errors->first('link_video') }}</div>
                    </div>
                    <div class="form-group ">
                        <div class="custom-control custom-switch">
                            <input name="publicado" @if(old('publicado',@$dados->pubicad0) == '1') checked @endif  value="1" type="checkbox" class="custom-control-input" id="customSwitch2">
                            <label class="custom-control-label pt-1" for="customSwitch2">Publicar Comunicado?</label>
                            <small class="form-text w-100 text-muted">
                                <span class="text-danger">Atenção:</span> ação irreversível! Após publicado <b>não será possível desfazer a publicação</b> da Família.
                            </small>
                        </div>
                    </div>
                    <a href="{{url('/gestao/agenda/familia/')}}" class="btn btn-secondary float-left">Cancelar</a>
                    <button type="button" class="btn btn-default mt-0 float-right ml-2" onclick="salvar()">Salvar</button>
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
                                        <input type="text" id="buscaNome" value="" placeholder="Nome da instituição" class="form-control" />
                                    </div>
                                    <div class="input-group ml-1">
                                        <input type="text" id="buscaEscola" value="" placeholder="Nome da escola" class="form-control" />
                                    </div>
                                    <div class="input-group ml-1">
                                        <select id="buscaTipo" class="form-control">
                                            <option value="-1">Tipo</option>
                                            @foreach($tipoInstituicao as $t)
                                                <option value="{{$t->id}}">{{$t->titulo}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-group ml-1">
                                        <button type="button" onclick="buscarEscolas('')" class="btn btn-secondary btn-sm">Localizar</button>
                                    </div>
                                    <div class="input-group ml-1">
                                        <button type="button" onclick="$('#buscaNome').val(''); $('#buscaEscola').val(''); $('#buscaTipo').val('-1'); buscarEscolas(''); " class="btn btn-secondary btn-sm">Limpar Filtros</button>
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

    @include('fr.agenda.familia.modalPublicar')

    <form id="formDadosEditar">
        @if(old('instituicao')!= '')
            @foreach(old('instituicao') as $e)
                <input type="hidden" name="instituicao[]" value="{{$e}}">
                @if(old('escola.'.$e) != '')
                    @foreach(old('escola.'.$e) as $t)
                        <input type="hidden" name="escola[{{$e}}][]" value="{{$t}}">
                    @endforeach
                @endif
            @endforeach
        @elseif( isset($dados->vizualizacao) && $dados->vizualizacao==4)
            @foreach($dados->escolas as $t)
                <input type="hidden" name="instituicao[]" value="{{$t->instituicao_id}}">
                <input type="hidden" name="escola[{{$t->instituicao_id}}][]" value="{{$t->escola_id}}">
            @endforeach

        @endif
    </form>
<script>
    $(document).ready(function(){
        $('#vizualizacao').change();
        @if( ( (isset($dados->escolas) && $dados->escolas) || old('instituicao') ))
            if($('#vizualizacao').val() ==4) {
                $.ajax({
                    url: '{{url('gestao/agenda/familia/getInstituicaoSelecionadas')}}?' + $('#formDadosEditar').serialize(),
                    type: 'post',
                    dataType: 'json',
                    data: {
                        _token: '{{csrf_token()}}'
                    },
                    success: function (data) {
                        $('#listaPermissao').html(data);
                    },
                    error: function () {
                        swal("", "Não foi possível carregar a lista de instituicoes selecionadas.", "error");
                    }
                });
            }
        @endif
    });

    function mudaVisualizacao(elemento) {
        valor = $(elemento).val();
        $('#btnEscolaTurma').hide();
        if(valor == 1){
            $('#listaPermissao').html('<li class="ui-state-default "><input type="hidden" name="instituicao[]" value="0"><input type="hidden" name="tipo" value=""> <i class="fas fa-check"></i><span class="m-2"></span> Todas as instituições e escolas da rede.</li>');
        }
        else if(valor == 2){
            $('#listaPermissao').html('<li class="ui-state-default "><input type="hidden" name="instituicao[]" value="0"><input type="hidden" name="tipo" value="publica"> <i class="fas fa-check"></i><span class="m-2"></span> Todas as instituições e escolas PÚBLICAS.</li>');
        }
        else if(valor == 3){
            $('#listaPermissao').html('<li class="ui-state-default "><input type="hidden" name="instituicao[]" value="0"><input type="hidden" name="tipo" value="privada"> <i class="fas fa-check"></i><span class="m-2"></span> Todas as instituições e escolas PRIVADAS.</li>');
        }
        else{
            $('#btnEscolaTurma').show();
            $('#listaPermissao').html('');
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
            url: '{{url('gestao/agenda/familia/getInstituicoesEscolas')}}?'+$('#formCadastroComunicado input[type=hidden]').serialize(),
            type: 'post',
            dataType: 'json',
            data: {
                nome: $('#buscaNome').val(),
                nomeEscola: $('#buscaEscola').val(),
                tipo: $('#buscaTipo').val(),
                page: page,
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

    function adicionaInstituicao(instituicaoId, escolaId){

        if($('#selecionadaInstituicao'+instituicaoId).length == 0) {
            nomeInst= $('#nomeInstituicao'+instituicaoId).html();
            var add = '<li class="ui-state-default" id="selecionadaInstituicao' + instituicaoId + '">';
            add += '<input type="hidden" name="instituicao[]" value="'+instituicaoId+'">';
            add += '<b>' + nomeInst + '</b>';
            add += '<p id="listaEscolasSelecionadasInstituicao' + instituicaoId + '"></p></li>';
            $('#listaPermissao').append(add);
        }
        if(escolaId == -1) {
            $('#listaEscolasSelecionadasInstituicao' + instituicaoId).html('<input type="hidden" name="escola['+instituicaoId+'][]" value="0"><span id="todasEscolasInstituicao' + instituicaoId + '" class="badge badge-secondary">Todas as escolas selecionadas.</span>');
        }
        else if($('#selecionadaInstituicao'+instituicaoId).length != 0){
            nomeEscola= $('#nomeEscola'+escolaId).html();
            $('#todasEscolasInstituicao'+ instituicaoId).remove();
            $('#listaEscolasSelecionadasInstituicao' + instituicaoId).append('<input type="hidden" name="escola['+instituicaoId+'][]" value="'+escolaId+'"><span class="badge badge-light ml-2" id="selecionadaEscola'+escolaId+'">'+nomeEscola+'</span>');
        }
    }

    function salvar(){
        if($('#customSwitch2').prop('checked')){
            $('#formPublicar').modal('show');
        }else{
            $('#formCadastroComunicado').submit()
        }
    }
    function publicar(){
        $('#formCadastroComunicado').submit()
    }

</script>
@stop
