@extends('fr/master')
@section('content')
    <style>
        .letra{
            border: 1px solid #cccccc;
            border-radius: 100%;
            width: 30px;
            height: 30px;
            text-align: center;
            font-size: 20px;
        }
    </style>

    <section class="section section-interna mb-5" style="padding-top: 50px">
        <div class="container">
            @include('fr.agenda.menu')
            <div class="row border-bottom border-top pt-4">
                <div class="col-md-12 pb-2">
                    <h3>
                        <a href="{{ url('/gestao/agenda/enquetes')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        @if ( strpos(Request::path(),'editar')===false )
                            Nova Enquete e Pesquisa
                        @else
                            Editar Enquete e Pesquisa
                        @endif
                    </h3>
                </div>

            </div>
            <form id="formCadastroTarefa" action="@if ( strpos(Request::path(),'editar')===false ) {{url('/gestao/agenda/enquetes/add')}} @else {{url('/gestao/agenda/enquetes/editar')}} @endif " method="post" enctype="multipart/form-data">
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
                    <div class="pt-4">
                        <h4 class="pb-3 border-bottom mb-4">Enquete</h4>
                    </div>
                    <div class="form-group" id="pergunta">
                        <label class="">* Pergunta:</label>
                            <textarea class="form-control" id='fr_1' name="pergunta">{{old('pergunta',@$dados->pergunta)}}</textarea>
                            <div class="invalid-feedback @if($errors->first('pergunta')) d-block @endif" style="font-size: 12px">{{ $errors->first('pergunta') }}</div>
                    </div>

                    <div class="form-group">
                        <label>* Quantidade de Alternativas:</label>
                        <div class="input-group input-group-sm mb-3">
                            @php
                                $qtd= 4;
                                if(isset($dados->qtd_alternativa))
                                {
                                    $qtd = $dados->qtd_alternativa;
                                }
                            @endphp
                            <input type="number" onchange="mudaQtd()" name="qtd_alternativa" id="qtda_alternativa" value="{{old('qtda_alternativa',$qtd)}}" class="form-control form-control-sm {{ $errors->has('qtd_alternativa') ? 'is-invalid' : '' }}" placeholder="Número" min="2" max="7">
                            <div class="input-group-append">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Alternativas</span>
                            </div>
                            <small class="form-text w-100 text-muted">
                                Define a quantidade de alternativas para a questão.
                            </small>
                            <div class="invalid-feedback @if($errors->first('qtd_alternativa')) d-block @endif">{{ $errors->first('qtd_alternativa') }}</div>
                        </div>
                    </div>
                    <div class="form-group " id="alternativa_1">
                        <label>* Alternativas:</label>
                        <div class="row">
                            <div class="col-md-1">
                                <div class="letra">A</div>
                            </div>
                            <div class="col-md-11">
                                <div>
                                    <textarea class="form-control" name="alternativa_1">{{old('alternativa_1',@$dados->alternativa_1)}}</textarea>
                                </div>
                                <div class="invalid-feedback @if($errors->first('alternativa_1')) d-block @endif" style="font-size: 12px">{{ $errors->first('alternativa_1') }}</div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group alternativa" id="alternativa_2">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="letra">B</div>
                            </div>
                            <div class="col-md-11">
                                <div id="editor">
                                    <textarea class='form-control' name="alternativa_2">{{old('alternativa_2',@$dados->alternativa_2)}}</textarea>
                                </div>
                                <div class="invalid-feedback @if($errors->first('alternativa_2')) d-block @endif" style="font-size: 12px">{{ $errors->first('alternativa_2') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group alternativa" id="alternativa_3">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="letra">C</div>
                            </div>
                            <div class="col-md-11">
                                <div id="editor">
                                    <textarea class='form-control' name="alternativa_3">{{old('alternativa_3',@$dados->alternativa_3)}}</textarea>
                                </div>
                                <div class="invalid-feedback @if($errors->first('alternativa_3')) d-block @endif" style="font-size: 12px">{{ $errors->first('alternativa_3') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group alternativa" id="alternativa_4">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="letra">D</div>
                            </div>
                            <div class="col-md-11">
                                <div id="editor">
                                    <textarea class='form-control' name="alternativa_4">{{old('alternativa_4',@$dados->alternativa_4)}}</textarea>
                                </div>
                                <div class="invalid-feedback @if($errors->first('alternativa_4')) d-block @endif" style="font-size: 12px">{{ $errors->first('alternativa_4') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group alternativa" id="alternativa_5">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="letra">E</div>
                            </div>
                            <div class="col-md-11">
                                <div id="editor">
                                    <textarea class='form-control' name="alternativa_5">{{old('alternativa_5',@$dados->alternativa_5)}}</textarea>
                                </div>
                                <div class="invalid-feedback @if($errors->first('alternativa_5')) d-block @endif" style="font-size: 12px">{{ $errors->first('alternativa_5') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group alternativa" id="alternativa_6">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="letra">F</div>
                            </div>
                            <div class="col-md-11">
                                <div id="editor">
                                    <textarea class='form-control' name="alternativa_6">{{old('alternativa_6',@$dados->alternativa_6)}}</textarea>
                                </div>
                                <div class="invalid-feedback @if($errors->first('alternativa_6')) d-block @endif" style="font-size: 12px">{{ $errors->first('alternativa_6') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group alternativa" id="alternativa_7">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="letra">G</div>
                            </div>
                            <div class="col-md-11">
                                <div id="editor">
                                    <textarea class='form-control' name="alternativa_7">{{old('alternativa_7',@$dados->alternativa_7)}}</textarea>
                                </div>
                                <div class="invalid-feedback @if($errors->first('alternativa_7')) d-block @endif" style="font-size: 12px">{{ $errors->first('alternativa_7') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="divArquivo">
                        <label>Imagem:</label>
                        <span id="arquivoNovo" @if(@$dados->imagem != '' && old('arquivo_novo') != 1)style="display: none" @endif>
                                <input name="arquivo" type="file" accept="image/*" class="form-control rounded {{ $errors->has('arquivo') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('arquivo') }}</div>
                                <input type="hidden" id="arquivo_novo" name="arquivo_novo" value="{{old('arquivo_novo')}}">
                            </span>
                        <span id="jaTemArquivo" @if(@$dados->imagem == ''  || old('arquivo_novo') == 1)style="display: none" @endif>
                            <div class="text-center" >
                                <img src="{{@$dados->link_imagem}}" class="img-fluid" width="250px">
                                <p class="text-center mt-2">
                                    <a href="javascript:void(0)" onclick="$('#jaTemArquivo').remove(); $('#arquivoNovo').show(); $('#arquivo_novo').val(1);" class="text-danger "><i class="fas fa-trash-alt"></i> Excluir imagem</a>
                                </p>
                            </div>
                        </span>
                    </div>
                    <div class="form-group ">
                        <div class="custom-control custom-switch">
                            <input name="publicado" @if(old('publicado',@$dados->pubicad0) == '1') checked @endif  value="1" type="checkbox" class="custom-control-input" id="customSwitch2">
                            <label class="custom-control-label pt-1" for="customSwitch2">Publicar Enquete?</label>
                            <small class="form-text w-100 text-muted">
                                <span class="text-danger">Atenção:</span> ação irreversível! Após publicada, <b>não será possível desfazer a publicação</b> da Enquete.
                            </small>
                        </div>
                    </div>
                    <a href="{{url('/gestao/agenda/enquetes/')}}" class="btn btn-secondary float-left">Cancelar</a>
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
                        <br><small class="form-text w-100 text-muted">As enquetes serão encaminhadas apenas aos RESPONSÁVEIS dos estudantes selecionados.</small>
                    </h5>
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

    @include('fr.agenda.enquetes.modalPublicar')

    <form id="formDadosEditar">
        @if( isset($dados->id) )
            <input type="hidden" name="enquete" value="{{$dados->id}}">
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
        $(document).ready(function(){
            mudaQtd();
            $('#visualizacao').change();
            @if( ( (isset($dados->turmas) && $dados->turmas) || old('escola') ))
            if($('#visualizacao').val() != 1) {
                $.ajax({
                    url: '{{url('gestao/agenda/enquetes/getTurmasSelecionadas')}}?' + $('#formDadosEditar').serialize(),
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
                url: '{{url('gestao/agenda/autorizacoes/getTurmas')}}?'+$('#formCadastroComunicado input[type=hidden]').serialize(),
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

        function montaQuestoes() {
            $('.alternativa').hide();
            var qtdaAlternativa = parseInt($('#qtda_alternativa').val());
            for (var i = 1; i <= qtdaAlternativa; i++) {
                $('#alternativa_'+i).show();
            }
        }

        function mudaQtd() {
            qtd = parseInt($('#qtda_alternativa').val());
            qtd = qtd || 0; /// se for NaN trasnforma para Zero
            if(qtd<2)
            {
                $('#qtda_alternativa').val(2);
            }
            if(qtd>7)
            {
                $('#qtda_alternativa').val(7);
            }
            montaQuestoes();
        };

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



