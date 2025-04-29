@extends('fr/master')
@section('content')
	<section class="section section-interna">
		<div class="container">
			<div class="row mb-3" style="margin-top: -40px">
				<div class="col-md-12">
					<div class="filter">
						<form method="get" id="formPesquisa" class="form-inline d-flex justify-content-end">
                            <input type="hidden" id="finalizado" name="finalizado" value="">
							<div class="input-group ml-1">
							    <div class="input-group-prepend">
							      	<div class="input-group-text">
							      		<i class="fas fa-search"></i>
							      	</div>
							    </div>
								<input name="texto" type="text" value="{{Request::input('texto')}}" placeholder="Procurar Conteúdo" class="form-control" />
							</div>
							<div class="input-group ml-1">
								<a href="{{url('/quiz/listar?componente='.Request::input('componente'))}}" class="btn btn-secondary btn-sm">Limpar Filtros</a>
							</div>
						</form>
					</div>
				</div>
			</div>

            <h2 class="title_page mb-4">
                <a href="{{url('/')}}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                INdica Avaliações
            </h2>

			<div class="list-grid-menu pl-0">
				<form class="form-inline">
					<div class="btn-group btn-group-toggle" data-toggle="buttons">
						<label class="btn btn-secondary p-2 no-list-grid-btn active">
							<input type="radio" name="options" id="option1" autocomplete="off" checked>
							<i class="fas fa-th-large"></i>
						</label>
						<label class="btn btn-secondary list-grid-btn">
							<input type="radio" name="options" id="option2" autocomplete="off">
							<i class="fas fa-list"></i>
						</label>
					</div>
				</form>
			</div>
            <div class="col-12 pl-0 pr-0">
                <h4>
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('finalizado') == '' ) active @endif" href="javascript:$('#finalizado').val('');$('#formPesquisa').submit()">Avaliações abertas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('finalizado') == 1 ) active @endif" href="javascript:$('#finalizado').val(1);$('#formPesquisa').submit()">Avaliações realizadas</a>
                        </li>
                    </ul>
                </h4>
            </div>
			<div class="row section-grid colecoes">
                @if(count($dados)>0)
                    @foreach($dados as $d)
                    <div class="col-md-4 grid-item">
                        @if(Request::input('finalizado') != 1)
                            <a href="javascript:void(0)" onclick="modalIniciarAvaliacao({{$d->id}}, '{{$d->titulo}}')" class="wrap" >
                        @else
                            @if( ( count($d->placar)>0 || count($d->logAtividade)>0 || count($d->logGeral)>0))
                                <a href="{{url('/indica/avaliacao/resultado/'.$d->id)}}" class="wrap" >
                            @endif
                        @endif
                            <div class="card text-center">
                                <div class="card-body">
                                        <img width="100px" src="{{config('app.cdn')}}/fr/imagens/provas_bimestrais1.png" />
                                        @if(Request::input('finalizado') != 1)
                                            <button class="btn detalhes">
                                                @if(count($d->logGeral) > 0)
                                                    Voltar para a avaliação
                                                @else
                                                    Iniciar avaliação
                                                @endif
                                            </button>
                                        @elseif( ( count($d->placar)>0 || count($d->logAtividade)>0 || count($d->logGeral)>0) )
                                            <button class="btn detalhes">Avaliação Realizada</button>
                                        @elseif( count($d->placar)==0 && count($d->logAtividade)==0 && count($d->logGeral)==0)
                                            <button class="btn detalhes" style="cursor:default">Você não realizou essa avaliação dentro do prazo.</button>
                                        @endif

                                    <div class="text mb-3 mt-3">
                                        <h6 class="title font-weight-bold">#{{$d->id}} {{$d->titulo}}</h6>
                                        <p style="margin-bottom: 8px">{{$d->disciplina->titulo}}</p>
                                        <p style="margin-bottom: 8px">{{$d->usuario->nome}}</p>
                                        @if(Request::input('finalizado') != 1)
                                            <p style="color: darkred">Você pode realizar a avaliação até:<br><b>{{$d->data_hora_final->format('d/m/Y \à\s H:i')}}</b></p>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer">
                                    @if(Request::input('finalizado') != 1)
                                        <a href="javascript:void(0)" onclick="modalIniciarAvaliacao({{$d->id}}, '{{$d->titulo}}', '{{$d->qtd_questao}}', '{{$d->tempo_maximo}}')" class="btn btn-warning btn-sm mt-1 btn-block" >
                                            @if(count($d->logGeral) > 0)
                                                Voltar para a avaliação
                                            @else
                                                Iniciar avaliação
                                            @endif
                                        </a>
                                    @elseif(( count($d->placar)>0 || count($d->logAtividade)>0 || count($d->logGeral)>0) )
                                        <a href="{{url('/indica/avaliacao/resultado/'.$d->id)}}" class="btn btn-warning btn-sm mt-1 btn-block" >Avaliação Realizada</a>
                                    @elseif( count($d->placar)==0 && count($d->logAtividade)==0 && count($d->logGeral)==0)
                                        <a href="javascript:void(0)"  class="btn btn-secondary btn-sm mt-1 btn-block" style="cursor: default" >Você não realizou essa avaliação dentro do prazo.</a>
                                    @endif

                                </div>
                            </div>
                    @if(Request::input('finalizado') != 1)
                        </a>
                    @endif
                    </div>
                    @endforeach
                    <nav class="mt-4" aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            {{ $dados->appends(Request::all())->links() }}
                        </ul>
                    </nav>
                @elseif(Request::input('finalizado') == '')
                    <div class="col">
                        <div class="card text-center">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado.</h5>
                                <p class="card-text mb-2">
                                    Você ainda não possui avaliações com possibilidade de realização.
                                </p>
                            </div>
                            <div class="card-footer text-muted"></div>
                        </div>
                    </div>
                @else
                    <div class="col">
                        <div class="card text-center">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado</h5>
                                <p class="card-text ">Você não realizou nenhuma avaliação on-line.</p>
                            </div>
                            <div class="card-footer text-muted"></div>
                        </div>
                    </div>
                @endif
			</div>
		</div>
	</section>
    <!-- Iniciar avaliacao -->
    <div class="modal fade" id="formPublicar" tabindex="-1" role="dialog" aria-labelledby="formPublicar" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Iniciar avaliação</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                <P align="justify">Você está prestes a iniciar a avaliação <b id="tituloAvaliacao"></b>. Leia com atenção as orientações a seguir:</P>
                                <p><b class="text-danger">
                                <ul>
                                <P align="justify"><li> A sua avaliação é composta por <b id="qtdQuestao"></b> questões.</li></P>
                                <P align="justify"><li> Você terá apenas uma tentativa para a resolução da prova.</li></P>
                                <P align="justify"><li> Leia com atenção o enunciado e as alternativas.</li></P>
                                <P align="justify"><li> Cada questão tem somente uma alternativa correta.</li></P>
                                <P align="justify"><li> Não deixe questões sem resposta.</li></P>
                                <P align="justify"><li> O tempo de resolução da prova é de <b id="tempoMax"></b> minutos, contados a partir do clique INICIAR.</li></P>
                                <P align="justify"><li> Verifique a qualidade da sua conexão. Se ela cair, você poderá voltar e continuar sua prova a partir da última questão respondida. As questões já respondidas não poderão ser alteradas.</li></P>
                                <P align="justify"><li> Antes de encerrar a prova, revise-a.</li></P>
                                </ul>
                            </b> </p>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <button type="button" onclick="iniciarAvaliacao()" class="btn btn-success">Sim, iniciar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Iniciar avaliacao -->

<script>
    $('.list-grid-btn').click(function() {
        $('.grid-item').addClass('list-grid');
    });

    $('.no-list-grid-btn').click(function() {
        $('.grid-item').removeClass('list-grid');
    });
    var idAvaliacao =0;
    function modalIniciarAvaliacao(id, nome, qtd, tempo)
    {
        idAvaliacao = id;
        $('#tituloAvaliacao').html(nome);
        $('#qtdQuestao').html(qtd);
        $('#tempoMax').html(tempo);
        $('#formPublicar').modal('show');
    }

    function iniciarAvaliacao()
    {
        window.location.href = '{{url("/indica/avaliacao/exibir")}}?a='+idAvaliacao;
    }
</script>
@stop
