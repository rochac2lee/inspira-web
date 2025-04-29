@extends('fr/master')
@section('content')
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/jquery-datetimepicker/jquery.datetimepicker.min.css" type="text/css" charset="utf-8" />
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet">
    <section class="section section-interna mb-5" style="padding-top: 50px">
        <div class="container">
            @include('fr.agenda.menu')
            <div class="row border-bottom border-top pt-4">
                <div class="col-md-4 pb-2">
                    <h3>
                        <a href="{{ url('/gestao/agenda/tarefas')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        @if ( strpos(Request::path(),'editar')===false )
                            Nova Tarefa
                        @else
                            Editar Tarefa
                        @endif
                    </h3>
                </div>

            </div>
            <form id="formCadastroTarefa" action="@if ( strpos(Request::path(),'editar')===false ) {{url('/gestao/agenda/tarefas/add')}} @else {{url('/gestao/agenda/tarefas/editar')}} @endif " method="post" enctype="multipart/form-data">
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
                        <h4 class="pb-3 border-bottom mb-4">Tarefa</h4>
                    </div>

                    <div class="form-group">
                        <label>* Componente curricular:</label>
                        <select class="multipleDisciplina {{ $errors->has('disciplina_id') ? 'is-invalid' : '' }}" name="disciplina_id" style="border: 1px solid {{ $errors->has('disciplina_id') ? 'red' : '#ffb100' }}; border-radius: 0.4rem;">
                            <option value="">Selecione</option>
                            @foreach($disciplina as $d)
                                <option @if( $d->id == old('disciplina_id',@$dados->disciplina_id)) selected @endif value="{{$d->id}}">{{$d->titulo}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" style="display: block" >{{ $errors->first('disciplina_id') }}</div>
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
                        <label>* Data de entrega:</label>
                        @php
                            $dataEntrega = null;
                            if(isset($dados) && $dados->data_entrega!=''){
                                $dataEntrega = $dados->data_entrega->format('d/m/Y');
                            }
                        @endphp
                        <input name="data_entrega" autocomplete="off" value="{{old('data_entrega',$dataEntrega)}}" type="text" id="datetimepicker" placeholder="dd/mm/aaaa" class="form-control form-control-sm rounded {{ $errors->has('data_entrega') ? 'is-invalid' : '' }}" />
                        <div class="invalid-feedback">{{ $errors->first('data_entrega') }}</div>
                    </div>
                    <div class="form-group">
                        <label>Arquivo:</label>
                            <span id="arquivoNovo" @if(@$dados->arquivo != '' && old('arquivo_novo') != 1)style="display: none" @endif>
                                <input name="arquivo" type="file" class="form-control rounded {{ $errors->has('arquivo') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('arquivo') }}</div>
                                <input type="hidden" id="arquivo_novo" name="arquivo_novo" value="{{old('arquivo_novo')}}">
                            </span>
                            <span id="jaTemArquivo" @if(@$dados->arquivo == ''  || old('arquivo_novo') == 1)style="display: none" @endif>
                                <input type="text" disabled value="{{@$dados->nome_arquivo_original}}" class="form-control ">
                                <p class="text-right mt-2">
                                    <a href="{{url('gestao/agenda/tarefas/arquivo/donwload/'.@$dados->id)}}" class="text-info pr-4"><i class="fas fa-download"></i> Download arquivo</a>
                                    <a href="javascript:void(0)" onclick="$('#jaTemArquivo').hide(); $('#arquivoNovo').show(); $('#arquivo_novo').val(1)" class="text-danger "><i class="fas fa-trash-alt"></i> Excluir arquivo</a>
                                </p>
                            </span>
                    </div>
                    <div class="form-group ">
                        <div class="custom-control custom-switch">
                            <input name="publicado" @if(old('publicado',@$dados->pubicad0) == '1') checked @endif  value="1" type="checkbox" class="custom-control-input" id="customSwitch2">
                            <label class="custom-control-label pt-1" for="customSwitch2">Publicar Tarefa?</label>
                            <small class="form-text w-100 text-muted">
                                <span class="text-danger">Atenção:</span> ação irreversível! Após publicado <b>não será possível desfazer a publicação</b> da Tarefa.
                            </small>
                        </div>
                    </div>
                    <a href="{{url('/gestao/agenda/tarefas/')}}" class="btn btn-secondary float-left">Cancelar</a>
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
                    <h5 class="modal-title" id="tituloForm">Seleção de Turmas e Estudantes</h5>
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

    @include('fr.agenda.tarefa.modalPublicar')

    <form id="formDadosEditar">
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

        @if( ( (isset($dados->alunos) && $dados->alunos) || old('turma') ))
            $.ajax({
                url: '{{url('gestao/agenda/tarefas/getTurmasSelecionadas')}}?' + $('#formDadosEditar').serialize(),
                type: 'post',
                dataType: 'json',
                data: {
                    _token: '{{csrf_token()}}',
                    @if ( strpos(Request::path(),'editar')!==false )
                        tarefa: '{{@$dados->id}}',
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

        var multipleDisciplina = new SlimSelect({
            select: '.multipleDisciplina',
            placeholder: 'Buscar',
            searchPlaceholder: 'Buscar',
            closeOnSelect: true,
            allowDeselectOption: true,
            selectByGroup: true,
        });
        jQuery.datetimepicker.setLocale('pt-BR');
        jQuery('#datetimepicker').datetimepicker({
            format:'d/m/Y',
            timepicker:false,
        });
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
            url: '{{url('gestao/agenda/tarefas/getTurmas')}}?'+$('#formCadastroTarefa input[type=hidden]').serialize(),
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
