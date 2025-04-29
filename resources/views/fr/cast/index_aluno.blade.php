@extends('fr/master')
@section('content')

	<section class="section section-interna">
		<div class="container">
			<div class="row mb-3" >
				<div class="col-12">
					<div class="filter">
						<form method="get" id="formPesquisa" class="form-inline d-flex justify-content-end">
                            <input type="hidden" name="tipo" value="{{Request::input('tipo')}}" id="tipo">
							@if(Request::input('tipo')!=2)
                                <div class="input-group ml-1">
                                    <select class="form-control" name="componente" onchange="mudaPesquisa()" style="width:250px;">
                                        <option value="">Componente Curricular</option>
                                        @foreach($disciplina as $d)
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
                                    <select class="form-control" name="categoria" onchange="mudaPesquisa()" style="width:300px;">
                                        <option value="">Categoria</option>
                                        @foreach($categoria as $c)
                                            <option @if( Request::input('categoria') == $c->id) selected @endif value="{{$c->id}}">{{$c->titulo}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
							<div class="input-group ml-1">
							    <div class="input-group-prepend">
							      	<div class="input-group-text">
							      		<i class="fas fa-search"></i>
							      	</div>
							    </div>
								<input name="texto" type="text" value="{{Request::input('texto')}}" placeholder="Procurar Conteúdo" class="form-control" />
							</div>

							<div class="input-group ml-1">
								<a href="{{url('/gestao/cast')}}" class="btn btn-secondary btn-sm">Limpar Filtros</a>
							</div>
						</form>
					</div>
				</div>
			</div>


            <h2 class="title-page">
                <a @if(Request::input('id')!='') href="{{url()->previous()}}" @else href="{{url('/')}}" @endif  class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                Cast
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
                <h4 style="margin-bottom: 18px;">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == '' ) active @endif" href="javascript:$('#tipo').val('');mudaPesquisa()">Áudios </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == 1 ) active @endif" href="javascript:$('#tipo').val(1);mudaPesquisa()">Álbuns </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == 2 ) active @endif" href="javascript:$('#tipo').val(2);mudaPesquisa()">Playlist </a>
                        </li>
                    </ul>
                </h4>

			<div class="row section-grid colecoes">
				@if(count($dados)>0)
                    @if(Request::input('tipo') == '')
                        @include('fr/cast/lista_audio')
                    @elseif(Request::input('tipo') == '1')
                        @include('fr/cast/lista_album')
                    @else
                        @include('fr/cast/lista_playlist')
                    @endif
                @else
                    <div class="col">
                        <div class="card text-center">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado</h5>
                                <p class="card-text ">Não foi encontrado resultado contendo todos os seus termos de pesquisa, clique no botão abaixo para reiniciar a pesquisa</p>
                                <a class="btn btn-danger fs-13 mb-2" href="{{url('/cast?tipo='.Request::input('tipo'))}}" title="Limpar Filtro"><i class="fas fa-undo-alt"></i> Limpar Filtro</a>
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

	</section>
    <link rel='stylesheet' type="text/css" href="{{ config('app.cdn') }}/fr/includes/audioplayer/audioplayer.css"/>
    <script src="{{ config('app.cdn') }}/fr/includes/audioplayer/audioplayer.js" type="text/javascript"></script>
    <script>
        var settings1 = {
            disable_volume: "off"
            ,autoplay: "off"
            ,cue: "on"
            ,disable_scrub: "default"
            ,design_skin: "skin-wave"
            ,skinwave_dynamicwaves:"on"
            ,skinwave_enableSpectrum: "off"
            ,settings_backup_type:"full"
            ,settings_useflashplayer:"auto"
            ,skinwave_spectrummultiplier: "4"
            ,skinwave_comments_enable:"on"
            ,skinwave_mode: "small"
            ,skinwave_comments_retrievefromajax:"on"
            ,pcm_data_try_to_generate: "on"
        };
    </script>
    <!-- INICIO EXIBIR LISTA -->
    <div class="modal fade" id="formExibirLista" tabindex="-1" role="dialog" aria-labelledby="formExibirLista" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloLista"></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" id="conteudoLista">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM EXIBIR LISTA -->

    <div class="modal fade divModalVisualizarConteudo" id="divModalVisualizarConteudo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl dialog-fixed px-1 px-md-5" role="document">
            <div class="modal-content content-fixed">
                <div class="modal-body body-fixed">
                    <button style="z-index:1;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                    </button>

                    <div class="row mb-2">
                        <div class="col align-middle my-auto">
                            <div class="col-12 title conteudo-titulo mb-0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mx-auto conteudo-tipo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function mudaPesquisa()
        {
            $('#formPesquisa').submit();
        }

        $('#divModalVisualizarConteudo').on('hidden.bs.modal', function () {
            $(".conteudo-tipo").empty();
        });

        function visualizarConteudo(idConteudo)
        {
            $.ajax({
                url: '{{ config('app.url') }}' + '/gestao/biblioteca/' + idConteudo + '/visualizar',
                type: 'get',
                dataType: 'json',
                success: function( _response )
                {
                    if(_response)
                    {
                        $(".conteudo-titulo").empty();
                        $(".conteudo-tipo").empty();

                        $(".conteudo-titulo").append(_response.titulo);
                        $(".conteudo-tipo").append(_response.descricao);
                        $("#player1").audioplayer(settings1);
                        $('#divModalVisualizarConteudo').modal('show');
                    }
                },
                error: function( _response )
                {

                }
            });
        }

        function visualizarLista(idConteudo,tipo, titulo)
        {
            url = '{{url('/cast/exibirPlayList')}}?tipo='+tipo+'&c='+idConteudo;
            if(tipo == 1){
                titulo = 'Albúm - '+titulo;
            }else if(tipo == 2){
                titulo = 'Playlist - '+titulo;
            }
            $("#tituloLista").html('<h5 class="modal-title" id="tituloVisao">'+titulo+'</h5>');
            $("#conteudoLista").html('<iframe style="border:none;" width="100%" height="600px" src="'+url+'"></iframe>');
            $('#formExibirLista').modal('show');
        }

        $('#formExibirLista').on('hidden.bs.modal', function () {
            $("#conteudoLista").empty();
        });
    </script>
@stop
