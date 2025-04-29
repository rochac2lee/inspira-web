@extends('fr/master')
@section('content')


    <section class="section section-interna">
        <div class="container">
            <div class="row border-bottom">
                <div class="col-12 mb-3">
                    <h3>
                        <a href="{{url('/gestao/escola/'.$escola->id.'/responsaveis/')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Novo Responsável
                        <small><br>{{$escola->titulo}}</small>
                    </h3>
                </div>

            </div>
            <form action="@if ( strpos(Request::path(),'editar')===false ) {{url('/gestao/escola/responsaveis/add')}} @else {{url('/gestao/escola/responsaveis/editar')}} @endif " method="post">
                <div class="row">
                    @csrf
                    @if ( strpos(Request::path(),'editar')!==false )
                        <input type="hidden" name="id" value="{{$dados->id}}">
                    @endif
                    <input type="hidden" name="escola_id" value="{{$escola->id}}">
                    <div class="col-md-12 bg-light pt-4 pb-4 pl-4 pr-5">
                        <h4 class="pb-3 border-bottom mb-4">Dados do responsável</h4>

                        <div class="form-group">
                            <label>Nome do responsável</label>
                            <input name="nome" type="text" placeholder="" value="{{old('nome',@$dados->nome)}}" class="form-control rounded {{ $errors->has('nome') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->first('nome') }}</div>
                        </div>
                        <div class="form-group">
                            <label>E-mail do responsável</label>
                            <input name="email" type="text" placeholder="" value="{{old('email',@$dados->email)}}" class="form-control rounded {{ $errors->has('email') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        </div>
                        <div class="form-group mt-3">
                            <h4 class="pb-3 border-bottom mb-4">Vincular estudantes <button type="button" class="btn btn-success btn-sm ml-2" onclick="$('#formIncluirAluno').modal('show')" data-toggle="tooltip" data-placement="top" title="Vincular estudantes"><i class="fas fa-plus"></i></button></h4>
                        </div>
                        <h6 class="text-center">Estudantes vinculados</h6>
                        <div class="row mb-4">
                            <div class="col">
                                <ul id="listaAlunos" class="list-style">

                                </ul>
                                <div class="invalid-feedback" style="display: block">{{ $errors->first('aluno') }}</div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12 bg-light pt-4 pb-4 pl-4 pr-5 text-right">
                        <a href="{{url('/gestao/escola/'.$escola->id.'/responsaveis/')}}" class="btn btn-secondary ">Cancelar</a>
                        <button class="btn btn-success mt-0 ml-2">Salvar</button>
                    </div>
                </div>
            </form>

        </div>
    </section>
    @include('fr.gestao.escola.responsaveis.formAddAluno')
    <script>
        var vetProfessores = new Array();
        var vetAlunos = new Array();
        var ehProfessor = 1;



        @if(old('escola_id')!= '')
            @if(is_array(old('aluno')))
                @php $aluno = old('aluno'); @endphp
                @foreach( $aluno as $p)
                    vetAlunos.push({{$p}});
                @endforeach
            @endif
        @elseif(isset($dados->alunosDoResponsavel))
            @foreach( $dados->alunosDoResponsavel as $p)
                vetAlunos.push({{$p->id}});
            @endforeach
        @endif



        $(document).on('click', '.page-link', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
                buscarAluno({{$escola->id}}, page)
        });



        $('#formIncluirAluno').on('show.bs.modal', function () {
            $('#buscaNomeAluno').val('');
            ehProfessor = 0;
            buscarAluno({{$escola->id}}, '')
        });

        function adicionarAluno(alunoId,avatar, nome){
            $('#btnExcluirAluno'+alunoId).show();
            $('#btnAdicionarAluno'+alunoId).hide();
            vetAlunos.push(alunoId);
            elemento = '<li class="ui-state-default text-truncate" id="alunoAdicionado'+alunoId+'"><input type="hidden" name="aluno[]" value="'+alunoId+'"> <span class="m-2"><img src="'+avatar+'" style="height:35px;"></span> '+nome+' <button type="button" class="btn btn-sm btn-danger" onclick="excluirAluno('+alunoId+')" style="float: right"><i class="fas fa-trash-alt" style="color: white"></i></button></li>';
            $('#listaAlunos').append(elemento);
        }

        function excluirAluno(alunoId){
            $('#alunoAdicionado'+alunoId).remove();
            index = vetAlunos.indexOf(alunoId);
            vetAlunos.splice(index, 1);
            $('#btnExcluirAluno'+alunoId).hide();
            $('#btnAdicionarAluno'+alunoId).show();
        }

        function buscarAluno(escolaId, page){
            $.ajax({
                url: '{{url('/gestao/escola/turmas/getAlunosTabela')}}',
                type: 'post',
                dataType: 'json',
                data: {
                    escola_id: escolaId,
                    tipo: 'A',
                    nome: $('#buscaNomeAluno').val(),
                    page: page,
                    alunos: vetAlunos,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    $('#tabelaAlunos').html(data);
                },
                error: function() {
                    swal("", "Não foi possível carregar a lista de alunos.", "error");
                }
            });
        }

        function buscarSelecionados(dados){
            $.ajax({
                url: '{{url('/gestao/escola/turmas/getProfessorAluno')}}',
                type: 'post',
                dataType: 'json',
                data: {
                    tipo: 'A',
                    selecionados: dados,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    $('#listaAlunos').html(data);
                },
                error: function() {
                    swal("", "Não foi possível carregar a lista de selecionados.", "error");
                }
            });
        }

        $(document).ready(function(){
            if(vetAlunos.length>0){
                buscarSelecionados(vetAlunos);
            }
        });
    </script>
@stop
