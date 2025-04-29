@extends('fr/master')
@section('content')
	<script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.jquery.min.js"></script>
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.css">
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/formUtilities.js"></script>
    <script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet">

	<script type="text/javascript">
        $(document).ready(function(){
            /* configuracoes basicas do plugin de recortar imagem */
            var configuracao = {
                ratio: '1:1',
                crop: {
                    x: 200,
                    y: 200,
                    width: 200,
                    height: 200
                },
                download: false,
                label: '<label for="exampleFormControlFile1">Insira uma Imagem</label> <i class="fas fa-file h5"></i> <br>Tamanho da imagem: 200px X 200px ',
                buttonConfirmLabel: 'Ok',
            }
            /* carrega o plugin de recortar imagem */
            $(".myCropper").slim(configuracao);
        });
    </script>
	<section class="section section-interna">
		<div class="container">
			<div class="row mb-3" >
				<div class="col-12">
					<div class="filter">
						<form method="get" id="formPesquisa" class="form-inline d-flex justify-content-end">
                            <input type="hidden" name="tipo" value="{{Request::input('tipo')}}" id="tipo">
                            <input type="hidden" name="biblioteca" value="{{Request::input('biblioteca')}}" id="biblioteca">
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
								<a href="{{url('/gestao/cast')}}" class="btn btn-secondary btn-sm">Limpar Filtros</a>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="row justify-content-center border-top  p-3">
				<div class="col-md-3">
					<button class="btn btn-success w-100" data-toggle="modal" data-target="#formIncluir" onclick="tipoOperacao = 'add'">
						<i class="fas fa-plus"></i>
						Novo Áudio
					</button>
				</div>
                <div class="col-md-3">
                    <a href="{{url('/gestao/cast/album/add')}}" class="btn btn-success w-100">
                        <i class="fas fa-plus"></i>
                        Novo Álbum
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{url('/gestao/cast/playlist/add')}}" class="btn btn-success w-100">
                        <i class="fas fa-plus"></i>
                        Nova Playlist
                    </a>
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
            @if(auth()->user()->permissao == 'Z')
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
            @else
                <h6 style="margin-bottom: 18px;">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == '' && Request::input('biblioteca') == '') active @endif" href="javascript:$('#tipo').val('');$('#biblioteca').val('');mudaPesquisa()">Meus áudios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == '' && Request::input('biblioteca') == 1) active @endif" href="javascript:$('#tipo').val('');$('#biblioteca').val(1);mudaPesquisa()">Áudios da biblioteca</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == 1 && Request::input('biblioteca') == '') active @endif" href="javascript:$('#tipo').val(1);$('#biblioteca').val('');mudaPesquisa()">Meus álbuns</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == 1 && Request::input('biblioteca') == 1) active @endif" href="javascript:$('#tipo').val(1);$('#biblioteca').val(1);mudaPesquisa()">Álbuns da biblioteca </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == 2 && Request::input('biblioteca') == '') active @endif" href="javascript:$('#tipo').val(2);$('#biblioteca').val('');mudaPesquisa()">Minhas playlist</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == 2 && Request::input('biblioteca') == 1) active @endif" href="javascript:$('#tipo').val(2);$('#biblioteca').val(1);mudaPesquisa()">Playlist da biblioteca</a>
                        </li>
                    </ul>
                </h6>
            @endif


			<div class="row section-grid colecoes">
				@if(count($dados)>0)
                    @if(Request::input('tipo') == '')
                        @include('fr/cast/lista_audio')
                    @elseif(Request::input('tipo') == '1')
                        @include('fr/cast/lista_album')
                    @else
                        @include('fr/cast/lista_playlist')
                    @endif
                @elseif(Request::input('biblioteca') == '')
                    <div class="col">
                        <div class="card text-center">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado.</h5>
                                <p class="card-text mb-2">
                                    @if(Request::input('tipo')=='')
                                        Para visualizar Áudios já prontos da Biblioteca da Opet INspira clique em <b>Áudios da biblioteca</b>.
                                        <br>Para criar seus Áudios autorais, clique no botão <b>Novo Áudio</b>.
                                    @elseif(Request::input('tipo')==1)
                                        Para visualizar Álbuns já prontos da Biblioteca da Opet INspira clique em <b>Álbuns da biblioteca</b>.
                                        <br>Para criar seus Áudios autorais, clique no botão <b>Novo Álbum</b>.
                                    @elseif(Request::input('tipo')==2)
                                        Para visualizar Playlist já prontas da Biblioteca da Opet INspira clique em <b>Playlist da biblioteca</b>.
                                        <br>Para criar suas Playlist autorais, clique no botão <b>Nova Playlist</b>.
                                    @endif
                                        <br><a class="btn btn-danger fs-13 mb-2 mt-2" href="{{url('/gestao/cast?biblioteca=&tipo='.Request::input('tipo'))}}" title="Limpar Filtro"><i class="fas fa-undo-alt"></i> Limpar Filtro</a>
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
                                <a class="btn btn-danger fs-13 mb-2" href="{{url('/gestao/cast?biblioteca=1&tipo='.Request::input('tipo'))}}" title="Limpar Filtro"><i class="fas fa-undo-alt"></i> Limpar Filtro</a>
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


        <!-- EXCLUIR -->
        <div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                    </button>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Exclusão de áudio do cast</h5>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <div class="row">
                                <div class="col-12">
                                    Você deseja mesmo excluir esse áudio?<br><br>
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

        <!-- EXCLUIR álbum-->
        <div class="modal fade" id="formExcluirAlbum" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                    </button>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Exclusão de álbum do cast</h5>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <div class="row">
                                <div class="col-12">
                                    Você deseja mesmo excluir esse álbum?<br><br>
                                    <b id="tituloAlbum"></b>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                        <button type="button" onclick="excluirAlbum()" class="btn btn-danger">Sim, excluir</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIM EXCLUIR -->

        <!-- EXCLUIR playlist-->
        <div class="modal fade" id="formExcluirPlaylist" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                    </button>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Exclusão de playlist do cast</h5>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <div class="row">
                                <div class="col-12">
                                    Você deseja mesmo excluir essa playlist?<br><br>
                                    <b id="tituloPlaylist"></b>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                        <button type="button" onclick="excluirPlaylist()" class="btn btn-danger">Sim, excluir</button>
                    </div>
                </div>
            </div>
        </div>


        @include('fr/cast/form')

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
        var idExcluir = 0;

        function modalExcluir(id, nome)
        {
            idExcluir = id;
            $('#tituloQuiz').html(nome);
            $('#formExcluir').modal('show');
        }

        function excluir()
        {
            window.location.href = '{{url("/gestao/cast/excluir/")}}/'+idExcluir;
        }

        function modalExcluirAlbum(id, nome)
        {
            idExcluir = id;
            $('#tituloAlbum').html(nome);
            $('#formExcluirAlbum').modal('show');
        }

        function excluirAlbum()
        {
            window.location.href = '{{url("/gestao/cast/album/excluir/")}}/'+idExcluir;
        }

        function modalExcluirPlaylist(id, nome)
        {
            idExcluir = id;
            $('#tituloPlaylist').html(nome);
            $('#formExcluirPlaylist').modal('show');
        }

        function excluirPlaylist()
        {
            window.location.href = '{{url("/gestao/cast/playlist/excluir/")}}/'+idExcluir;
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
