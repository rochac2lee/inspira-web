<style>
    .card-header.errado {
        background-color: #fad7d7!important;
        color: #a72828;
    }
    .card-header.certo {
        background-color: #d7fad8!important;
        color: #1b692b;
    }
</style>

    <section class="section section-interna">
@if($frame!=1)
        <div class="container">
            <h2 class="title-page">
                <a href="{{url('quiz/listar?componente='.$quiz->disciplina_id)}}" title="Voltar" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                Quiz - {{$quiz->titulo}}
            </h2>
        </div>
@endif
	<div class="container">
		<div class="avaliacao-online mt-0">
			<div class="pergunta text-center">
				<p><img src="{{config('app.cdn')}}/fr/imagens/fim.png" style="width: 50px;"></p>
				<div class="mt-4">Parabéns, você finalizou o Quiz!</div>
				<div class="mt-3 fs-14">Que tal conferir como você se saiu?</div>
				<div class="mb-4"></div>
			</div>
		</div>

		<div class="container-fluid">
            <div class="row">
                <div class="col-md-6 bg-light border-right pt-4 pb-4 pl-4 pr-5">
                    <h4 class="pb-3 border-bottom mb-4">Placar final</h4>
                    <div class="mb-4 w-100">
						<div class="progress" style="height: 25px;">
							<div class="progress-bar bg-success" role="progressbar" style="width: {{$dados->porcentagem_acerto}}%;" aria-valuenow="{{$dados->porcentagem_acerto}}" aria-valuemin="0" aria-valuemax="100">{{(int)$dados->porcentagem_acerto}}%</div>
							<div class="progress-bar bg-danger" role="progressbar" style="width: {{$dados->porcentagem_erro}}%;" aria-valuenow="{{$dados->porcentagem_erro}}" aria-valuemin="0" aria-valuemax="100">{{(int)$dados->porcentagem_erro}}%</div>
						</div>
						<div class="fs-13 mt-2 text-center"><strong>{{$dados->qtd_questoes_respondida}}</strong> de <strong>{{$dados->qtd_questoes_total}}</strong> questões respondidas</div>
					</div>

					<div class="col border p-2 text-center statsPerformace">
					 	<label class="font-weight-bold mt-2">Status de Performance</label>
					 	<div class="row">
                            <div class="col col-md-6"><div class="a a1"><label>{{$dados->qtd_acerto}}</label>Respostas<br>adequadas</div></div>
                            <div class="col col-md-6"><div class="a a2"><label>{{$dados->qtd_erro}}</label>Respostas<br>não adequadas</div></div>
                            <div class="col col-md-6"><div class="a a3"><label>{{$dados->pontuacao}}</label>Pontuação<br>neste Quiz</div></div>
                            <div class="col col-md-6"><div class="a a3"><label>{{$total->pontuacao}}</label>Pontuação<br>total nos Quizzes</div></div>
                            <div class="col col-md-12"><div class="a a4"><label>{{$dados->tempo}}</label> Tempo total neste quiz (h:m:s)</div></div>
                            <!--
                            <div class="col col-md-6"><div class="a a5"><label>3</label> Longest Streak</div></div>-->
                    	</div>
                    </div>
                    @if($frame!=1)
                        <a href="{{url('quiz/colecao')}}" class="btn btn-default mt-0 float-right ml-2 mt-4">Voltar para o início</a>
                    @endif
                </div>
                <div class="col-md-6 pl-5">
                    <div class="pt-4">
                        <h4 class="pb-3 border-bottom mb-4">Perguntas Respondidas</h4>
                    </div>
                    <div class="col-md-12 p-0">
                    	@php
                    		$i=1;
                    	@endphp
                    	@foreach($quiz->perguntas as $p)
							<div class="card mb-3 p-0">
								<div class="card-header font-weight-bold @if(isset($p->log)) @if($p->log->eh_correta == 1) certo @else errado  @endif  @endif ">{{$i}}. {{strip_tags($p->titulo)}}</div>
								<button class="btn btn-link btn-sm mt-1 mb-1" onclick="exibirPerguntaFinalizado({{$p->id}})"><i class="fas fa-eye"></i> Veja a pergunta</button>
							</div>
							@php
                    			$i++;
                    		@endphp
						@endforeach
					</div>
                </div>
            </div>
        </div>
	</div>
</section>

<!-- Exibir quiz -->
	<div class="modal fade" id="modalExibirPerguntaFinalizado" tabindex="-1" role="dialog" aria-labelledby="modalExibirPerguntaFinalizado" aria-hidden="true">
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
		                <div class="col-12" id="conteudoExibirPerguntaFinalizado">

		                </div>
		            </div>
		        </div>
		    </div>
	    </div>
	</div>
	<!-- FIM Exibir quiz -->

<script>
	function exibirPerguntaFinalizado(id)
    {
        url = '{{url('/quiz/exibir')}}?f=1&frame=1&q={{$quiz->id}}&p='+id;
        $('#conteudoExibirPerguntaFinalizado').html('<iframe style="border:none;" width="100%" height="600px" src="'+url+'"></iframe>')
        $('#modalExibirPerguntaFinalizado').modal('show');
    }

    $('#modalExibirPerguntaFinalizado').on('hidden.bs.modal', function () {
        $('#conteudoExibirPerguntaFinalizado').html('');
    });
</script>
