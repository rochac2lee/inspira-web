@extends('fr/master')
@section('content')
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.jquery.min.js"></script>
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.css">
    <script>
        $(document).ready(function(){
            /* configuracoes basicas do plugin de recortar imagem */
            var configuracao = {
                ratio: '1:1',
                crop: {
                    x: 600,
                    y: 600,
                    width: 600,
                    height: 600
                },
                download: false,
                label: '<label for="exampleFormControlFile1">Insira uma Imagem</label> <i class="fas fa-file h5"></i> <br>Tamanho da imagem: 600px X 600px ',
                buttonConfirmLabel: 'Ok',
            }

            /* carrega o plugin de recortar imagem */
            $(".myCropper").slim(configuracao);
        });
    </script>

    <section class="section section-interna mb-5" style="padding-top: 50px">
        <div class="container">
            @include('fr.agenda.menu')
            <div class="row border-bottom border-top pt-4">
                <div class="col-md-4 pb-2">
                    <h3>
                        <a href="{{ url('/gestao/agenda/autorizacoes')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        @if ( strpos(Request::path(),'editar')===false )
                            Nova Autorização
                        @else
                            Editar Autorização
                        @endif
                    </h3>
                </div>

            </div>
            <form id="formCadastroTarefa" action="@if ( strpos(Request::path(),'editar')===false ) {{url('/gestao/agenda/autorizacoes/add')}} @else {{url('/gestao/agenda/autorizacoes/editar')}} @endif " method="post" enctype="multipart/form-data">
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

                    <div class="form-group" id="btnEscolaTurma">
                        <button type="button" class="btn btn-success" onclick="$('#formEscolasTrumas').modal('show')"><i class="fas fa-plus"></i> Adicione turmas e estudantes</button>
                    </div>
                    <hr>
                    <h6 class="text-center">* Turmas e estudantes selecionados</h6>
                    <div class="row mb-4">
                        <div class="col">
                            <ul id="listaPermissao" class="list-style">

                            </ul>
                            <div class="invalid-feedback" style="display: block">{{ $errors->first('aluno') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 pl-5">
                    <div class="row">
                        <div class="pt-4 col-12">
                            <h4 class="pb-3 border-bottom mb-4">Informações sobre a autorização</h4>

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

                            <div class="form-group col-6 pl-0">
                                <label>Imagem</label>
                                <div id="logoCadastro" class="form-group imagem-file-roteiro text-white rounded p-1 text-center">
                                    <input type="hidden" name="existeImg" id="existeImg" value="{{@$dados->imagem}}">
                                    <img id="imgLogo" class="img-fluid mt-2" src="{{config('app.cdn').'/storage/agenda/autorizacao/' .@$dados->user_id.'/'.@$dados->id.'/'.@$dados->imagem}}">
                                    <br>
                                    <a class="btn btn-secondary mt-2" onclick="excluirLogo()">Excluir Imagem</a>
                                </div>
                                <div id="novaLogo" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                    <input type="file" name="imagem" class="myCropper">
                                </div>
                                <div class="invalid-feedback" style="display: block">{{ $errors->first('imagem') }}</div>
                            </div>
                            <div class="form-group ">
                                <div class="custom-control custom-switch">
                                    <input name="publicado" @if(old('publicado',@$dados->pubicad0) == '1') checked @endif  value="1" type="checkbox" class="custom-control-input" id="customSwitch2">
                                    <label class="custom-control-label pt-1" for="customSwitch2">Publicar Autorização?</label>
                                    <small class="form-text w-100 text-muted">
                                        <span class="text-danger">Atenção:</span> ação irreversível! Após publicada, <b>não será possível desfazer a publicação</b> da Autorização.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="{{url('/gestao/agenda/autorizacoes/')}}" class="btn btn-secondary float-left">Cancelar</a>
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
                    <h5 class="modal-title" id="tituloForm">Seleção de Turmas e Estudantes
                        <br><small class="form-text w-100 text-muted">As autorizações serão encaminhadas apenas aos RESPONSÁVEIS dos estudantes selecionados.</small>
                    </h5>
                </div>
                <div class="modal-body pb-0">
                    <div class="row mb-3">
                        <div class="col-md-12" id="tabelaTurmas">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Finalizar</button>
                </div>
            </div>
        </div>
    </div>

    @include('fr.agenda.autorizacao.modalPublicar')

    <form id="formDadosEditar">
        @if( isset($dados->id) )
            <input type="hidden" name="autorizacao" value="{{$dados->id}}">
        @endif
        @if(old('turma')!= '')
            @foreach(old('turma') as $e)
                <input type="hidden" name="turma[]" value="{{$e}}">
                @foreach(old('aluno.'.$e) as $t)
                    <input type="hidden" name="aluno[{{$e}}][]" value="{{$t}}">
                @endforeach
            @endforeach
        @elseif( isset($dados->alunos) && count($dados->alunos)>0)
            @foreach($dados->alunos as $t)
                <input type="hidden" name="turma[]" value="{{$t->pivot->turma_id}}">
                <input type="hidden" name="aluno[{{$t->pivot->turma_id}}][]" value="{{$t->pivot->aluno_id}}">
            @endforeach
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
        @if(@$dados->imagem == '')
            $('#logoCadastro').hide();
            $('#novaLogo').show();
        @else
            $('#logoCadastro').show();
            $('#novaLogo').hide();
        @endif

        @if( ( (isset($dados->alunos) && $dados->alunos) || old('turma') ))
            $.ajax({
                url: '{{url('gestao/agenda/autorizacoes/getTurmasSelecionadas')}}?' + $('#formDadosEditar').serialize(),
                type: 'post',
                dataType: 'json',
                data: {
                    _token: '{{csrf_token()}}',
                    @if ( strpos(Request::path(),'editar')!==false )
                        documento: '{{@$dados->id}}',
                    @endif
                },
                success: function (data) {
                    $('#listaPermissao').html(data);
                },
                error: function () {
                    swal("", "Não foi possível carregar a lista de turmas selecionadas.", "error");
                }
            });
        @endif
    });

    $('#formEscolasTrumas').on('show.bs.modal', function () {
        $('#buscaNome').val('');
        buscarTurmas('');
    });

    $(document).on('click', '.page-link', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        buscarTurmas(page);
    });

    function buscarTurmas(page){
        $.ajax({
            url: '{{url('gestao/agenda/autorizacoes/getTurmas')}}?'+$('#formCadastroTarefa input[type=hidden]').serialize(),
            type: 'post',
            dataType: 'json',
            data: {
                nome: $('#buscaNome').val(),
                page: page,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $('#tabelaTurmas').html(data);
            },
            error: function() {
                swal("", "Não foi possível carregar a lista de turmas.", "error");
            }
        });
    }

    function adicionaTurma(turmaId, alunoId){

        if($('#listaAlunosSelecionadosTurma'+turmaId).length == 0) {
            nomeTurma= $('#nomeTurma'+turmaId).html();
            var add = '<li class="ui-state-default" id="selecionadaTurma' + turmaId + '">';
            add += '<input type="hidden" name="turma[]" value="'+turmaId+'">';
            add += '<b>' + nomeTurma + '</b>';
            add += '<p id="listaAlunosSelecionadosTurma' + turmaId + '"></p></li>';
            $('#listaPermissao').append(add);
        }
        if(alunoId == 0) {
            html = '<span id="todosAlunosTurmas' + turmaId + '" class="badge badge-secondary">Todos os estudantes selecionados.</span>';

            $('.ckTurma' + turmaId).each(function(index){
                html += '<input type="hidden" name="aluno['+turmaId+'][]" value="'+$(this).attr('aluno_id')+'">';
            })
            $('#listaAlunosSelecionadosTurma' + turmaId).html(html);
        }
        else if($('#selecionadaTurma'+turmaId).length != 0){
            nomeAluno= $('#nomeAluno'+alunoId+'-'+turmaId).html();
            $('#todosAlunosTurmas'+ turmaId).remove();
            $('#listaAlunosSelecionadosTurma' + turmaId).append('<input type="hidden" name="aluno['+turmaId+'][]" value="'+alunoId+'"><span class="badge badge-light ml-2" id="selecionadaAluno'+alunoId+'">'+nomeAluno+'</span>');
        }
    }

    function salvar(){
        if($('#customSwitch2').prop('checked')){
            $('#formPublicar').modal('show');
        }else{
            $('#formCadastroTarefa').submit()
        }
    }
    function publicar(){
        $('#formCadastroTarefa').submit()
    }
</script>
@stop
