@extends('fr/master')
@section('content')

    <script src="{{config('app.cdn')}}/fr/includes/js/jquery/jquery-ui.js"></script>

	<section class="section section-interna" style="padding-bottom: 50px">
		<div class="container">
			@if( ( auth()->user()->id == $quiz->user_id || ($quiz->instituicao_id == 1 && auth()->user()->instituicao_id==1) )  && ($quiz->publicado != 1 || auth()->user()->instituicao_id == 1))
                    <div class="row justify-content-center p-3 mb-4">
                        <div class="col-md-3">
                            <button class="btn btn-success w-100" data-toggle="modal" data-target="#formEscolhaPergunta">
                                <i class="fas fa-plus"></i>
                                Nova Pergunta {{old('tipo')}}
                            </button>
                        </div>
                    </div>
            @endif
			<h2 class="title-page">
                @if(Auth::user()->id == $quiz->user_id || ($quiz->instituicao_id == 1 && Auth::user()->instituicao_id==1))
                    <a href="{{url('gestao/quiz?componente='.$quiz->disciplina_id)}}" title="Voltar" class="btn btn-secondary">
                @else
                    <a href="{{ redirect()->back()->getTargetUrl() }}" title="Voltar" class="btn btn-secondary">
                @endif

                    <i class="fas fa-arrow-left"></i>
                </a>
                Perguntas para o quiz "{{$quiz->titulo}}"
            </h2>
            @if($quiz->publicado == 1 && auth()->user()->instituicao_id!=1)
                <p class=" w-100 text-danger">
                    <b>O quiz <i>{{$quiz->titulo}}</i> está publicado. As perguntas não podem ser editadas.</b>
                </p>
            @endif
            @if($quiz->aleatorizar_questoes==1)
    			<small class="form-text w-100 text-muted">
                    Esse quiz está ativado para aleatorizar as perguntas, assim essa ordem não será a que o estudante verá.
                </small> <br>
            @endif
            <div class="row">
                <div class="col-12 roteiro-itens">
                    <ul id="sortable" class="list-style">
                        @foreach($perguntas as $p)
                        <div id="{{$p->id}}" class="row">
                            <li  class="ui-state-default d-flex">

                                <div class="col-10">
                                    <div class="p-2 flex-grow-1">
                                        <i class="fas fa-bars"></i>
                                        @if($p->tipo == 1)
                                            <b>Correlação de imagens</b>
                                        @elseif($p->tipo == 2)
                                            <b>Correlação de palavras</b>
                                        @elseif($p->tipo == 3)
                                            <b>Questão aberta</b>
                                        @elseif($p->tipo == 4)
                                            <b>Múltipla escolha</b>
                                        @endif
                                        - {{$p->titulo}}
                                    </div>
                                </div>
                                <div class="col-2 text-right mt-2">
                                        <a href="javascript:void(0)" title="Visualizar" onclick="exibirPergunta({{$p->id}})"><i class="fas fa-eye"></i></a>
                                        @if( ( auth()->user()->id == $quiz->user_id || ($quiz->instituicao_id == 1 && auth()->user()->instituicao_id==1) )  && ($quiz->publicado != 1 || auth()->user()->instituicao_id == 1))
                                            <a  title="Editar" href="/gestao/quiz/add_pergunta/editarFormTipo{{$p->tipo}}/{{$quiz->id}}/{{$p->id}}"><i class="fas fa-edit"></i></a>
                                            <a href="{{url('/gestao/quiz/duplicarPergunta/'.$p->id)}}" title="Duplicar"><i class="fas fa-clone"></i></a>
                                            <a href="javascript:void(0)" title="Excluir" onclick="modalExcluir('{{$p->id}}', '{{$p->titulo}}')"><i class="fas fa-trash-alt"></i></a>
                                        @endif
                                </div>
                            </li>
                        </div>
                        @endforeach
                    </ul>
                </div>
            </div>
		</div>

        <!-- ESCOLHA PERGUNTA -->
        <div class="modal fade" id="formEscolhaPergunta" tabindex="-1" role="dialog" aria-labelledby="formEscolhaPergunta" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                    </button>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Escolha o tipo da pergunta</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-5">
                                <img width="400px" src="{{url('/fr/imagens/quiz_tipo/quiz_tipo_1.png')}}">
                            </div>
                            <div class="col-7">
                                <b>Correlação de imagens</b>
                                <p>Questões que apresentam diferentes opções de resposta (alternativas), no formato de imagem, em que o estudante pode escolher apenas uma opção.</p>
                                <br>
                                <a href="{{url('/gestao/quiz/add_pergunta/formTipo1/').'/'.$quiz->id}}" id="btnForm" type="button" class="btn btn-success" >Adicionar Pergunta</a>
                            </div>
                            <div class="col-12 mt-3 mb-4 border-bottom "></div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <img width="400px" src="{{url('/fr/imagens/quiz_tipo/quiz_tipo_2.png')}}">
                            </div>
                            <div class="col-7">
                                <b>Correlação de palavras </b>
                                <p>Questões que apresentam diferentes opções de palavras ou imagens, em que o estudante seleciona a resposta clicando nas opções desejadas.</p>
                                <br>
                                <a href="{{url('/gestao/quiz/add_pergunta/formTipo2/').'/'.$quiz->id}}" id="btnForm" type="button" class="btn btn-success" >Adicionar Pergunta</a>
                            </div>
                            <div class="col-12 mt-3 mb-4 border-bottom "></div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <img width="400px" src="{{url('/fr/imagens/quiz_tipo/quiz_tipo_4.png')}}">
                            </div>
                            <div class="col-7">
                                <b>Questão aberta  </b>
                                <p>Questões abertas em que o estudante precisa digitar a resposta em poucas palavras.</p>
                                <br>
                                <a href="{{url('/gestao/quiz/add_pergunta/formTipo3/').'/'.$quiz->id}}" id="btnForm" type="button" class="btn btn-success" >Adicionar Pergunta</a>
                            </div>
                            <div class="col-12 mt-3 mb-4 border-bottom "></div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <img width="400px" src="{{url('/fr/imagens/quiz_tipo/quiz_tipo_3.png')}}">
                            </div>
                            <div class="col-7">
                                <b>Múltipla escolha</b>
                                <p>Questões que apresentam diferentes opções de resposta (alternativas), em que o estudante pode escolher apenas uma opção.</p>
                                <br>
                                <a href="{{url('/gestao/quiz/add_pergunta/formTipo4/').'/'.$quiz->id}}" id="btnForm" type="button" class="btn btn-success" >Adicionar Pergunta</a>
                            </div>
                            <div class="col-12 mt-3 mb-4 border-bottom "></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- ESCOLHA PERGUNTA -->

		<!-- EXCLUIR -->
	    <div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered" role="document">
	        <div class="modal-content">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
	            </button>
	            <div class="modal-header">
	                <h5 class="modal-title" id="exampleModalLabel">Exclusão de pergunta</h5>
	            </div>
	            <div class="modal-body">
	                <form action="">
	                    <div class="row">
	                        <div class="col-12">
	                            Você deseja mesmo excluir essa pergunta?<br><br>
	                            <b id="tituloQuiz"></b>
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

        <!-- Exibir quiz -->
        <div class="modal fade" id="formExibir" tabindex="-1" role="dialog" aria-labelledby="formExibir" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>

                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{$quiz->titulo}}</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" id="conteudoExibirPergunta">

                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>

	</section>

	<script type="text/javascript">

        function exibirPergunta(id)
        {
            url = '{{url('/quiz/exibir')}}?q={{$quiz->id}}&p='+id+'&f=1&frame=1';
            $('#conteudoExibirPergunta').html('<iframe style="border:none;" width="100%" height="600px" src="'+url+'"></iframe>')
            $('#formExibir').modal('show');
        }

        $('#formExibir').on('hidden.bs.modal', function () {
            url = '{{url('gestao/quiz/limparPlacar')}}'+'/'+'{{$quiz->id}}';
            $.get(url);
            $('#conteudoExibirPergunta').html('');
        });

        @if( ( auth()->user()->id == $quiz->user_id || ($quiz->instituicao_id == 1 && auth()->user()->instituicao_id==1) )  && ($quiz->publicado != 1 || auth()->user()->instituicao_id == 1))
            $(function() {
                $("#sortable").sortable({
                    update: function() {
                        var sort = $(this).sortable("toArray");
                        $.ajax({
                            url: '{{url('/gestao/quiz/ordemPergunta/')}}',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                ordem: sort,
                                quizId: '{{$quiz->id}}',
                                _token: '{{csrf_token()}}'
                            }
                        });
                    }
                });
                $("#sortable").disableSelection();
            });
        @endif
        function modalExcluir(id, nome)
        {
            idExcluir = id;
            $('#tituloQuiz').html(nome);
            $('#formExcluir').modal('show');
        }

        function excluir()
        {
            window.location.href = '{{url("/gestao/quiz/pergunta/excluir/")}}/'+idExcluir;
        }

        $('#formExcluir').on('hidden.bs.modal', function () {
            idExcluir = 0;
        });
	</script>
@stop
