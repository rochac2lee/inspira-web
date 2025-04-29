@extends('fr/master')
@section('content')
    <style type="text/css">
        .jfk-button {
            border-radius: 2px;
            cursor: default;
            font-size: 11px;
            text-align: center;
            white-space: nowrap;
            line-height: 27px;
            min-width: 54px;
            outline: 0;
            float: left;
            cursor: pointer;
        }

        .save-to-drive-button.jfk-button {
            font-weight: normal;
            color: #444;
            border: 1px solid rgba(0,0,0,0.1);
            height: 29px;
            background-color: #ededed;
            background-image: -webkit-linear-gradient(top,#f5f5f5,#e6e6e6);
            background-image: -moz-linear-gradient(top,#f5f5f5,#e6e6e6);
            background-image: -ms-linear-gradient(top,#f5f5f5,#e6e6e6);
            background-image: -o-linear-gradient(top,#f5f5f5,#e6e6e6);
            background-image: linear-gradient(top,#f5f5f5,#e6e6e6);
            margin: 0;
            padding: 0;
            font-family: arial,sans-serif;
        }

        .save-to-drive-button:hover {
            background-color: #e8e8e8;
            background-image: -webkit-linear-gradient(top,#f0f0f0,#e1e1e1);
            background-image: -moz-linear-gradient(top,#f0f0f0,#e1e1e1);
            background-image: -ms-linear-gradient(top,#f0f0f0,#e1e1e1);
            background-image: -o-linear-gradient(top,#f0f0f0,#e1e1e1);
            background-image: linear-gradient(top,#f0f0f0,#e1e1e1);
        }
        .save-to-drive-image {
            display: inline-block;
            float: left;
            margin-left: 3px;
            margin-right: 5px;
            margin-top: 5px;
            position: relative;
            background-size: 21px 121px;
            width: 16px;
            height: 16px;
        }

        .save-to-drive-text {
            display: inline-block;
            margin-left: -3px;
            margin-right: 6px;
            position: relative;
            vertical-align: bottom;
        }

    </style>
	<script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.jquery.min.js"></script>
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.css">
	<script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet">
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/formUtilities.js"></script>

	<script type="text/javascript">
        $(document).ready(function(){
            /* configuracoes basicas do plugin de recortar imagem */
            var configuracao = {
                ratio: '5:2',
                crop: {
                    x: 1024,
                    y: 500,
                    width: 1024,
                    height: 500
                },
                download: false,
                label: '<label for="exampleFormControlFile1">Insira uma Imagem</label> <i class="fas fa-file h5"></i> <br>Tamanho da imagem: 1024px X 500px ',
                buttonConfirmLabel: 'Ok',
            }
            /* carrega o plugin de recortar imagem */
            $(".myCropper").slim(configuracao);
        });
    </script>
	<section class="section section-interna">
		<div class="container">
			<div class="row mb-3">
				<div class="col-md-12">
					<div class="filter">
						<form method="get" id="formPesquisa" class="form-inline d-flex justify-content-end">
                            <input type="hidden" name="biblioteca" value="{{Request::input('biblioteca')}}" id="biblioteca">
							<div class="input-group ml-1">
								<select class="form-control" name="componente" onchange="mudaPesquisa()" style="width:250px;">
									<option value="">Componente Curricular</option>
                                    @foreach($disciplinaFiltro as $d)
                                        <option @if(Request::input('componente') == $d->id) selected @endif value="{{$d->id}}">{{$d->titulo}}</option>
                                    @endforeach
								</select>
							</div>
							<div class="input-group ml-1">
								<select class="form-control" name="ciclo_etapa" onchange="mudaPesquisa()" style="width:300px;">
									<option value="">Etapa / Ano</option>
                                    @foreach($cicloEtapa as $c)
                                        <option @if( Request::input('ciclo_etapa') == $c->id) selected @endif value="{{$c->id}}">{{$c->ciclo}} - {{$c->ciclo_etapa}}</option>
                                    @endforeach
								</select>
							</div>
							<div class="input-group ml-1">
								<select class="form-control" name="exibicao" onchange="mudaPesquisa()">
                                    <option value="">Exibição</option>
                                    <option @if( Request::input('exibicao') == 1) selected @endif  value="1">Publicado</option>
                                    <option @if( Request::input('exibicao') == 0 && Request::input('exibicao') != '') selected @endif  value="0">Rascunho</option>
								</select>
							</div>
							<div class="input-group ml-1">
							    <div class="input-group-prepend">
							      	<div class="input-group-text">
							      		<i class="fas fa-search"></i>
							      	</div>
							    </div>
								<input name="texto" type="text" value="{{Request::input('texto')}}" placeholder="Procurar Conteúdo" class="form-control" />
							</div>
							<div class="input-group ml-1">
								<a href="{{url('/gestao/quiz')}}" class="btn btn-secondary btn-sm">Limpar Filtros</a>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="row justify-content-center border-top  p-3">
				<div class="col-md-3">
					<button class="btn btn-success w-100" data-toggle="modal" data-target="#formIncluir" onclick="tipoOperacao = 'add'">
						<i class="fas fa-plus"></i>
						Novo Quiz
					</button>
				</div>
			</div>

            <h2 class="title-page">
                <a @if(Request::input('id')!='') href="{{url()->previous()}}" @else href="{{url('/quiz/colecao')}}" @endif  class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                Quiz
            </h2>
            @if(auth()->user()->permissao != 'Z')
                <h4 style="margin-bottom: 18px;">

                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('biblioteca') == '' ) active @endif" href="javascript:$('#biblioteca').val('');mudaPesquisa()">Meus quizzes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('biblioteca') == 1 ) active @endif" href="javascript:$('#biblioteca').val(1);mudaPesquisa()">Quizzes da biblioteca</a>
                        </li>
                    </ul>
                </h4>
            @else

            @endif

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
				@if(count($dados)>0)
                    @foreach($dados as $d)
                    <div class="col-md-4 grid-item">
                        <a href="javascript:void(0)" class="wrap" onclick="mostraVisao({{$d->id}}, '{{addslashes($d->titulo)}}')">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="img">
                                        @if($d->capa != '')
                                            <img src="{{ config('app.cdn').'/storage/quiz/'.$d->id.'/capa/'.$d->capa}}" />
                                        @else
                                            <img src="fr/imagens/colecao.png" />
                                        @endif
                                        <button class="btn detalhes" onclick="mostraVisao({{$d->id}}, '{{addslashes($d->titulo)}}')">Visão do aluno</button>
                                    </div>
                                    <div class="text mb-3 mt-3">
                                        <h6 class="title font-weight-bold">{{$d->titulo}}</h6>
                                        <p>
                                            {{$d->disciplina->titulo}} - @if(isset($d->qtdPerguntas[0]->qtd)){{$d->qtdPerguntas[0]->qtd}} @else 0 @endif perguntas
                                            <br>
                                            @if($d->instituicao_id==1 && $d->publicado==1)
                                                <i>Editora Opet</i>
                                            @else
                                                <i>{{$d->usuario}}</i>
                                            @endif
                                        </p>
                                        @if(Request::input('biblioteca')!=1)
                                            @if($d->publicado!=1)
                                                <p><span id="badgeParcial" class="badge badge-danger" >Rascunho</span></p>
                                            @else
                                                <p><span id="badgeParcial" class="badge badge-success" >Publicado</span></p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                @if(Request::input('biblioteca')!=1 && auth()->user()->id == $d->user_id && $d->publicado == 1 && auth()->user()->instituicao_id != 1)
                                <div class="card-footer" >
                                    <a href="https://classroom.google.com/u/0/share?url={{url('/quiz/exibir')}}?q={{$d->id}}" target="blank" alt="Compartilhar no Google Sala de Aula" title="Compartilhar no Google Sala de Aula"  class="">
                                        <div class="save-to-drive-button jfk-button ml-1" role="button" tabindex="0" style="user-select: none;"><div class="save-to-drive-image drive-sprite-classroom" style="background: no-repeat url({{config('app.cdn')}}/fr/imagens/icones/ico_google_buttons1.png) 0 -21px;"></div><div class="save-to-drive-text">Compartilhar</div></div>
                                    </a>
                                    <a href="javascript:void(0)" data-trigger= "click" data-toggle="tooltip" data-placement="top" title="Link copiado!" onclick="copiarLink(this,'{{url('/quiz/exibir')}}?q={{$d->id}}')">
                                        <div class="save-to-drive-button jfk-button ml-1" role="button" tabindex="0" style="user-select: none;"><div class="save-to-drive-image drive-sprite-classroom" style="background: no-repeat url({{config('app.cdn')}}/fr/imagens/icones/ico_google_buttons1.png) 0 -42px;"></div><div class="save-to-drive-text">Link</div></div>
                                    </a>
                                </div>
                                @endif

                                @if(auth()->user()->permissao == 'Z')
                                <div class="card-footer" >
                                    @php
                                    $linkQuiz = ''.url('quiz/publico/'.$d->public_id).'';
                                    @endphp
                                    <a href="javascript:void(0)" data-trigger= "click" data-toggle="tooltip" data-placement="top" title="Link copiado!" onclick="copiarLink(this,'{{$linkQuiz}}')">
                                        <div class="save-to-drive-button jfk-button ml-1" role="button" tabindex="0" style="user-select: none;"><div class="save-to-drive-image drive-sprite-classroom" style="background: no-repeat url({{config('app.cdn')}}/fr/imagens/icones/ico_google_buttons1.png) 0 -42px;"></div><div class="save-to-drive-text">Link</div></div>
                                    </a>
                                </div>
                                @endif

                                <div class="card-footer">
                                    <a href="{{url('gestao/quiz/'.$d->id.'/perguntas')}}" class="btn btn-secondary btn-sm mt-1" title="Perguntas" data-toggle="tooltip" data-placement="top"><i class="fas fa-question"></i></a>

                                    @if(auth()->user()->id == $d->user_id || ($d->instituicao_id == 1 && auth()->user()->instituicao_id==1) )
                                        @php
                                            $url = url('/gestao/quiz/publicar/'.$d->id).'/'.$d->publicado;
                                            $click = '';
                                            if(auth()->user()->permissao == 'P')
                                            {
                                                $url = 'javascript:void(0)';
                                                $click ="modalPublicar('$d->id','".addslashes($d->titulo)."')" ;
                                            }
                                        @endphp
                                        <a href="{{$url}}" onclick="{!!$click!!}" class="btn btn-secondary btn-sm mt-1 @if(auth()->user()->instituicao_id!=1 && $d->publicado == 1) disabled @endif" data-toggle="tooltip" data-placement="top" title="@if($d->publicado==1) Despublicar @else Publicar @endif"><i class="fas fa-bullhorn"></i> </a>
                                    @endif

                                    <a href="{{url('/gestao/quiz/duplicar/'.$d->id)}}" class="btn btn-secondary btn-sm mt-1" data-toggle="tooltip" data-placement="top" title="@if(Request::input('biblioteca') == 1 ) Adicionar aos meus quizzes @else Duplicar @endif">
                                        @if(Request::input('biblioteca') == 1 )
                                            <i class="fas fa-heart"></i>
                                        @else
                                            <i class="fas fa-clone"></i>
                                        @endif
                                    </a>

                                    @if(auth()->user()->permissao == 'Z')
                                        @php
                                            $linkLivroDigital = '<div style="text-align: center;"><a href="'.url('quiz/publico/'.$d->public_id).'" target="_blank"><span class="opet_f1_t3">QUIZ - '.$d->titulo.'</span><br><span class="opet_inf_t1"><img src="'.config('app.cdn').'/storage/quiz/'.$d->id.'/capa/'.$d->capa.'" style="vertical-align: top; margin: 5px auto; text-align: center; max-width: calc(100% - 10px);"></span></a></div>';
                                        @endphp
                                        <a href="javascript:void(0)" class="btn btn-secondary btn-sm mt-1" data-trigger= "click" data-toggle="tooltip" data-placement="top" title="Link copiado!" onclick="copiarLink(this,'{{$linkLivroDigital}}')" alt="Copiar Link">
                                            <i class="fas fa-book"></i>
                                        </a>
                                    @endif

                                    @if(auth()->user()->id == $d->user_id || ($d->instituicao_id == 1 && auth()->user()->instituicao_id==1))
                                        <a href="javascript:void(0)" class="btn btn-secondary btn-sm mt-1 @if(auth()->user()->instituicao_id!=1 && $d->publicado == 1) disabled @endif" onclick="tipoOperacao = 'editar'; idQuiz= {{$d->id}}; $('#formIncluir').modal('show');" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0)" class="btn btn-secondary btn-sm mt-1" onclick="modalExcluir('{{$d->id}}', '{{addslashes($d->titulo)}}')" data-toggle="tooltip" data-placement="top" title="Apagar"><i class="fas fa-trash" ></i></a>
                                    @endif

                                </div>
                                @if(auth()->user()->permissao == 'P' && auth()->user()->id == $d->user_id)
                                    <div class="card-footer">
                                        @if($d->publicado == 1)
                                            <span class="badge badge-ligth">{{count($d->respondido)}} iniciados</span>
                                            <span class="badge badge-ligth">@if(count($d->finalizado)>0) {{$d->finalizado[0]->qtd}} @else 0 @endif finalizados</span>
                                            <span class="badge badge-ligth">@if(count($d->finalizado)>0) {{number_format($d->finalizado[0]->media_acerto,0,',','.')}}% @else 0% @endif média de acertos</span>
                                            <br><br>
                                            <a href="{{url('/gestao/quiz/relatorio/'.$d->id)}}" class="btn btn-secondary btn-sm mt-1 btn-block @if(count($d->finalizado)==0) disabled @endif"  ><i class="fas fa-file-invoice" ></i> Ver relatório completo</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                    @endforeach
                @elseif(Request::input('biblioteca') == '')
                    <div class="col">
                        <div class="card text-center">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado.</h5>
                                <p class="card-text mb-2">
                                    Para visualizar Quizzes já prontos da Biblioteca da Opet INspira clique em <b>Quizzes da biblioteca</b>.
                                    <br>Para criar seus Quizzes autorais, clique no botão <b>Novo Quiz</b>.
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
                                <p class="card-text ">Não foi encontrado resultado contendo todos os seus termos de pesquisa, clique no botão abaixo para reiniciar a pesquisa</p>
                                <a class="btn btn-danger fs-13 mb-2" href="{{url('/gestao/quiz?biblioteca=1')}}" title="Excluir"><i class="fas fa-undo-alt"></i> Limpar Filtro</a>
                            </div>
                            <div class="card-footer text-muted"></div>
                        </div>
                    </div>
                @endif

			</div>
			<nav class="mt-4" aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    {{ $dados->appends(Request::all())->links() }}
                </ul>
            </nav>
		</div>

		 @include('fr/quiz/form_quiz')

	</section>
    <script>
        function  copiarLink(el,link){
            navigator.clipboard.writeText(link);
            var elemento = $(el);
            setTimeout(function(){
                elemento.tooltip("hide");
            }, 2000)
        }

        $('.list-grid-btn').click(function() {
            $('.grid-item').addClass('list-grid');
        });

        $('.no-list-grid-btn').click(function() {
            $('.grid-item').removeClass('list-grid');
        });

        function  copiarLink(el,link){
            navigator.clipboard.writeText(link);
            var elemento = $(el);
            setTimeout(function(){
                elemento.tooltip("hide");
            }, 2000)
        }
    </script>
@stop
