@extends('fr/master')
@section('content')
    <section class="section section-interna mb-5" style="padding-top: 50px">
        <div class="container">
            @include('fr.agenda.menu')
            <div class="row border-bottom border-top pt-4">
                <div class="col-md-4 pb-2">
                    <h3>
                        <a href="{{ url('/gestao/agenda/noticias')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        @if ( strpos(Request::path(),'editar')===false )
                            Nova Notícia
                        @else
                            Editar Notícia
                        @endif
                    </h3>
                </div>

            </div>
            <form id="formCadastroNoticia" action="@if ( strpos(Request::path(),'editar')===false ) {{url('/gestao/agenda/noticias/add')}} @else {{url('/gestao/agenda/noticias/editar')}} @endif " method="post" enctype="multipart/form-data">
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
                        <label>* Esta notícia será visualizada por: </label>
                        <select class="form-control" name="visualizacao" id="visualizacao" onchange="mudaVisualizacao(this)">
                            <option @if(old('visualizacao') == 1) selected @endif value="1">
                                @if(auth()->user()->permissao == 'I')
                                Todas as escolas e turmas de sua instituição
                                @else
                                Todas turmas de sua escola
                                @endif
                            </option>
                            <option @if(old('visualizacao') == 2 || (isset($dados->turmas) && count($dados->turmas)>0)) selected @endif value="2">
                                @if(auth()->user()->permissao == 'I')
                                    Selecionar escolas e turmas específicas de sua instituição
                                @else
                                    Selecionar turmas específicas da sua escola
                                @endif

                            </option>
                        </select>
                        <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                    </div>
                    <div class="form-group" id="btnEscolaTurma">
                        <button type="button" class="btn btn-success" onclick="$('#formEscolasTrumas').modal('show')"><i class="fas fa-plus"></i> Adicione escolas e turmas</button>
                    </div>
                    <hr>
                    <h6 class="text-center">* Escolas e turmas selecionadas</h6>
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
                        <h4 class="pb-3 border-bottom mb-4">Notícia</h4>
                    </div>

                    <div class="form-group">
                        <label>* Título:</label>
                        <input name="titulo" type="text" placeholder="" value="{{old('titulo',@$dados->titulo)}}" class="form-control rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
                        <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                    </div>
                    <div class="form-group">
                        <label>* Descrição:</label>
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
                            <input class="form-control" type="text" disabled  value="Você deverá editar as imagens na opção Gerenciar Imagens, na tela anterior.">
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
                            <label class="custom-control-label pt-1" for="customSwitch2">Publicar Notícia?</label>
                            <small class="form-text w-100 text-muted">
                                <span class="text-danger">Atenção:</span> ação irreversível! Após publicado <b>não será possível desfazer a publicação</b> da Notícia.
                            </small>
                        </div>
                    </div>
                    <a href="{{url('/gestao/agenda/noticias/')}}" class="btn btn-secondary float-left">Cancelar</a>
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

    @include('fr.agenda.noticias.modalPublicar')

    <form id="formDadosEditar">
        @if(old('escola')!= '')
            @foreach(old('escola') as $e)
                <input type="hidden" name="escola[]" value="{{$e}}">
                @foreach(old('turma.'.$e) as $t)
                    <input type="hidden" name="turma[{{$e}}][]" value="{{$t}}">
                @endforeach
            @endforeach
        @elseif( isset($dados->turmas) && count($dados->turmas)>0)
            @foreach($dados->turmas as $t)
                <input type="hidden" name="escola[]" value="{{$t->escola_id}}">
                <input type="hidden" name="turma[{{$t->escola_id}}][]" value="{{$t->turma_id}}">
            @endforeach
        @else
            <input type="hidden" name="escola[]" value="0">
            <input type="hidden" name="turma[0][]" value="0">
        @endif
    </form>
<script>
    $(document).ready(function(){
        $('#visualizacao').change();
        @if( ( (isset($dados->turmas) && $dados->turmas) || old('escola') ))
            if($('#visualizacao').val() != 1) {
                $.ajax({
                    url: '{{url('gestao/agenda/noticias/getEscolasTurmasSelecionados')}}?' + $('#formDadosEditar').serialize(),
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
                $('#listaPermissao').html('<li class="ui-state-default"><input type="hidden" name="escola[]" value="0"><input type="hidden" name="turma[0][]" value="0"> <i class="fas fa-check"></i><span class="m-2"></span> Todas as escolas e turmas.</li>');
            @else
                $('#listaPermissao').html('<li class="ui-state-default"><input type="hidden" name="escola[]" value="{{auth()->user()->escola_id}}"><input type="hidden" name="turma[{{auth()->user()->escola_id}}][]" value="0"> <i class="fas fa-check"></i><span class="m-2"></span> Todas as turmas de sua escola.</li>');
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
            url: '{{url('gestao/agenda/noticias/getEscolasTurmas')}}?'+$('#formCadastroNoticia input[type=hidden]').serialize(),
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

    function adicionaEscola(escolaId, turmaId){

        if($('#selecionadaEscola'+escolaId).length == 0) {
            nomeEscola= $('#nomeEscola'+escolaId).html();
            var add = '<li class="ui-state-default" id="selecionadaEscola' + escolaId + '">';
            add += '<input type="hidden" name="escola[]" value="'+escolaId+'">';
            add += '<b>' + nomeEscola + '</b>';
            add += '<p id="listaTurmasSelecionadasEscola' + escolaId + '"></p></li>';
            $('#listaPermissao').append(add);
        }
        if(turmaId == -1) {
            $('#listaTurmasSelecionadasEscola' + escolaId).html('<input type="hidden" name="turma['+escolaId+'][]" value="0"><span id="todasTurmasEscola' + escolaId + '" class="badge badge-secondary">Todas as turmas selecionadas.</span>');
        }
        else if($('#selecionadaEscola'+escolaId).length != 0){
            nomeTurma= $('#nomeTurma'+turmaId).html();
            $('#todasTurmasEscola'+ escolaId).remove();
            $('#listaTurmasSelecionadasEscola' + escolaId).append('<input type="hidden" name="turma['+escolaId+'][]" value="'+turmaId+'"><span class="badge badge-light ml-2" id="selecionadaTurma'+turmaId+'">'+nomeTurma+'</span>');
        }
    }

    function salvar(){
        if($('#customSwitch2').prop('checked')){
            $('#formPublicar').modal('show');
        }else{
            $('#formCadastroNoticia').submit()
        }
    }
    function publicar(){
        $('#formCadastroNoticia').submit()
    }
</script>
@stop
