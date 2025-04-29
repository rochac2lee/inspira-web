@extends('fr/master')
@section('content')
    <script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet">

    <script>
        $(document).ready(function(){

            var selectEtapaAno = new SlimSelect({
                select: '.etapaAno',
                placeholder: 'Buscar',
                searchPlaceholder: 'Buscar',
                closeOnSelect: true,
                allowDeselectOption: true,
                selectByGroup: true,
            });
        });

    </script>

    <section class="section section-interna">
        <div class="container-fluid">
            <div class="row border-bottom">
                <div class="col-md-4 pb-2">
                    <h3>
                        <a href="{{ url('/gestao/escola/'.$escola->id.'/turmas')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Nova Turma
                        <small><br>{{$escola->titulo}}</small>

                    </h3>
                </div>

            </div>
            <form action="@if ( strpos(Request::path(),'editar')===false ) {{url('/gestao/escola/turma/add')}} @else {{url('/gestao/escola/turma/editar')}} @endif " method="post">
            <div class="row">
                @csrf
                @if ( strpos(Request::path(),'editar')!==false )
                    <input type="hidden" name="turma_id" value="{{$dados->id}}">
                @endif
                <input type="hidden" name="escola_id" value="{{$escola->id}}">
                <div class="col-md-6 bg-light border-right pt-4 pb-4 pl-4 pr-5">
                    <h4 class="pb-3 border-bottom mb-4">Dados da Turma</h4>
                    <div class="form-group">
                        <label>Etapa / Ano</label>
                        <select name="ciclo_etapa_id" class="etapaAno {{ $errors->has('ciclo_etapa_id') ? 'is-invalid' : '' }}" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
                            @foreach($cicloEtapa as $c)
                                <option @if( old('etapa_ano',@$dados->ciclo_etapa_id) == $c->id) selected @endif value="{{$c->id}}">{{$c->ciclo}} - {{$c->ciclo_etapa}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" style="display: block">{{ $errors->first('ciclo_etapa_id') }}</div>
                    </div>
                    <div class="form-group">
                        <label>* Nome da Turma</label>
                        <input name="titulo" type="text" placeholder="" value="{{old('titulo',@$dados->titulo)}}" class="form-control rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
                        <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                    </div>
                    <div class="form-group">
                        <label>* Turno da Turma</label>
                        <select name="turno" class="form-control rounded {{ $errors->has('turno') ? 'is-invalid' : '' }}">
                            <option @if(old('turno',@$dados->turno) == 'Manhã') selected @endif value="Manhã">Manhã</option>
                            <option @if(old('turno',@$dados->turno) == 'Tarde') selected @endif value="Tarde">Tarde</option>
                            <option @if(old('turno',@$dados->turno) == 'Noite') selected @endif value="Noite">Noite</option>
                            <option @if(old('turno',@$dados->turno) == 'Integral') selected @endif value="Integral">Integral</option>
                        </select>
                        <div class="invalid-feedback">{{ $errors->first('turno') }}</div>
                    </div>
                    <div class="form-group mt-3">
                        <h4 class="pb-3 border-bottom mb-4">Docentes da Turma <button type="button" class="btn btn-success btn-sm ml-2" onclick="$('#formIncluirProfessor').modal('show')" data-toggle="tooltip" data-placement="top" title="Adicionar docente"><i class="fas fa-plus"></i></button></h4>
                    </div>
                    <h6 class="text-center">Docentes pertencente a turma</h6>
                    <div class="row mb-4">
                        <div class="col">
                            <ul id="listaProfessores" class="list-style">

                            </ul>
                            <div class="invalid-feedback" style="display: block">{{ $errors->first('professor') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 pl-5">
                    <div class="pt-4">
                        <h4 class="pb-3 border-bottom mb-4">Alunos da Turma <button type="button" class="btn btn-success btn-sm ml-2" onclick="$('#formIncluirAluno').modal('show')" data-toggle="tooltip" data-placement="top" title="Adicionar aluno"><i class="fas fa-plus"></i></button></h4>
                    </div>

                    <h6 class="text-center">Alunos pertencente a turma</h6>
                    <div class="row mb-4">
                        <div class="col">
                            <ul id="listaAlunos" class="list-style">

                            </ul>
                            <div class="invalid-feedback" style="display: block">{{ $errors->first('aluno') }}</div>
                        </div>
                    </div>
                    <a href="{{url()->previous()}}" class="btn btn-secondary float-left">Cancelar</a>
                    <button class="btn btn-default mt-0 float-right ml-2">Salvar</button>
                </div>

            </div>
            </form>

        </div>
    </section>
@include('fr.gestao.turma.formAddProfessor')
@include('fr.gestao.turma.formAddAluno')
<script>
    var vetProfessores = new Array();
    var vetAlunos = new Array();
    var ehProfessor = 1;

    @if(old('escola_id')!= '')
        @if(is_array(old('professor')))
            @php $prof = old('professor'); @endphp
            @foreach( $prof as $p)
                vetProfessores.push({{$p}});
            @endforeach
        @endif
    @elseif(isset($dados->professores))
        @foreach( $dados->professores as $p)
            vetProfessores.push({{$p->id}});
        @endforeach
    @endif

    @if(old('escola_id')!= '')
        @if(is_array(old('aluno')))
            @php $aluno = old('aluno'); @endphp
            @foreach( $aluno as $p)
                vetAlunos.push({{$p}});
            @endforeach
        @endif
   @elseif(isset($dados->alunos))
        @foreach( $dados->alunos as $p)
            vetAlunos.push({{$p->id}});
        @endforeach
    @endif

    function adicionarProfessor(professorId,avatar, nome){
        $('#btnExcluirProfessor'+professorId).show();
        $('#btnAdicionarProfessor'+professorId).hide();
        vetProfessores.push(professorId);
        elemento = '<li class="ui-state-default text-truncate" id="professorAdicionado'+professorId+'"><input type="hidden" name="professor[]" value="'+professorId+'"> <span class="m-2"><img src="'+avatar+'" style="height:35px;"></span> '+nome+' <button type="button" class="btn btn-sm btn-danger" onclick="excluirProfessor('+professorId+')" style="float: right"><i class="fas fa-trash-alt" style="color: white"></i></button></li>';
        $('#listaProfessores').append(elemento);
    }

    function excluirProfessor(professorId){
        $('#professorAdicionado'+professorId).remove();
        index = vetProfessores.indexOf(professorId);
        vetProfessores.splice(index, 1);
        $('#btnExcluirProfessor'+professorId).hide();
        $('#btnAdicionarProfessor'+professorId).show();
    }

    $('#formIncluirProfessor').on('show.bs.modal', function () {
        $('#buscaNome').val('');
        ehProfessor = 1;
        buscarProfessor({{$escola->id}}, '')
    });

    $(document).on('click', '.page-link', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        if(ehProfessor == 1) {
            buscarProfessor({{$escola->id}}, page)
        }
        else
        {
            buscarAluno({{$escola->id}}, page)
        }
    });

    function buscarProfessor(escolaId, page){
        $.ajax({
            url: '{{url('/gestao/escola/turmas/getProfessoresTabela')}}',
            type: 'post',
            dataType: 'json',
            data: {
                escola_id: escolaId,
                nome: $('#buscaNome').val(),
                page: page,
                professores: vetProfessores,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $('#tabelaProfessores').html(data);
            },
            error: function() {
                swal("", "Não foi possível carregar a lista de docentes.", "error");
            }
        });
    }

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

    function buscarSelecionados(dados,tipo){
        $.ajax({
            url: '{{url('/gestao/escola/turmas/getProfessorAluno')}}',
            type: 'post',
            dataType: 'json',
            data: {
                tipo: tipo,
                selecionados: dados,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                if(tipo == 'P'){
                    $('#listaProfessores').html(data);
                }else{
                    $('#listaAlunos').html(data);
                }

            },
            error: function() {
                swal("", "Não foi possível carregar a lista de selecionados.", "error");
            }
        });
    }

    $(document).ready(function(){
        if(vetAlunos.length>0){
            buscarSelecionados(vetAlunos,'A');
        }
        if(vetProfessores.length>0){
            buscarSelecionados(vetProfessores,'P');
        }
    });
</script>
@stop
