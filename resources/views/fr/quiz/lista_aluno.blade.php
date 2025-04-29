@extends('fr/master')
@section('content')
	<section class="section section-interna">
		<div class="container">
			<div class="row mb-3" style="margin-top: -40px">
				<div class="col-md-12">
					<div class="filter">
						<form method="get" id="formPesquisa" class="form-inline d-flex justify-content-end">
                            <input type="hidden" name="componente" value="{{Request::input('componente')}}" id="componente">
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

            <h2 class="title_page  border-bottom mb-4">
                <a href="{{url('/quiz/colecao')}}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                Quizzes - {{$dados[0]->disciplina->titulo}}
            </h2>

			<div class="list-grid-menu">
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
			<div class="row section-grid colecoes">
				@foreach($dados as $d)
				<div class="col-md-4 grid-item">
                    @if(auth()->user()->permissao == 'A')
                        @php $btn = 'Iniciar quiz';  $url = url('/quiz/exibir/?q='.$d->id); $classBtn = 'btn-warning'; @endphp
                        @if(count($d->finalizado) >0)
                            @php $btn = 'Quiz finalizado!<br> Confira seu desempenho.'; $url = url('quiz/finalizado?&q='.$d->id); $classBtn = 'btn-secondary';  @endphp
                        @elseif(count($d->respondido) >0)
                            @php $btn = 'Continuar quiz'; $classBtn = 'btn-info'; @endphp
                        @endif
                    @else
                        @if(count($d->finalizado) >0)
                            @php $btn = 'Quiz finalizado!<br> Confira seu desempenho.'; $url = url('quiz/finalizado?&q='.$d->id); $classBtn = 'btn-warning';  @endphp
                        @else
                            @php $btn = '<b>Quiz ainda não iniciado!</b><br>Você está no perfil familiar ou responsável e não pode iniciar o quiz.';  $url = 'javascript:void(0)'; $classBtn = 'btn-secondary'; @endphp
                        @endif
                    @endif
                        <a href="{{$url}}" class="wrap" >
						<div class="card text-center">
							<div class="card-body">
								<div class="img">
									@if($d->capa != '')
										<img src="{{ config('app.cdn').'/storage/quiz/'.$d->id.'/capa/'.$d->capa}}" />
									@else
										<img src="fr/imagens/colecao.png" />
									@endif
									<button class="btn detalhes">{!! $btn !!}</button>
								</div>
								<div class="text mb-3 mt-3">
                                    <h6 class="title font-weight-bold">{{$d->titulo}}</h6>
                                    <p>{{$d->disciplina->titulo}}</p>
                                    <p>{{$d->usuario}}</p>

                                </div>
							</div>
							<div class="card-footer">
                                <a href="{{$url}}" class="btn {{$classBtn}} btn-sm mt-1 btn-block" >{!! $btn !!}</a>
                            </div>
						</div>
					</a>
				</div>
				@endforeach
			</div>
			<nav class="mt-4" aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    {{ $dados->appends(Request::all())->links() }}
                </ul>
            </nav>
		</div>
	</section>
    <script>
        $('.list-grid-btn').click(function() {
            $('.grid-item').addClass('list-grid');
        });

        $('.no-list-grid-btn').click(function() {
            $('.grid-item').removeClass('list-grid');
        });
    </script>
@stop
