@extends('fr/master')
@section('content')

<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.css">
<script src="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.js"></script>

<style type="text/css">
	.modal-full {
	    min-width: 90%;
	    margin-left: 80;
	}
    .custom-control-input-laranja:checked~.custom-control-label::before {
        color: #fff;
        background-color: red;
        border-color:red;
        outline: 0;
        -webkit-box-shadow: none;
        box-shadow: none;
    }
</style>

<section class="section section-interna">
	<div class="container">
		<div class="row mb-3" >
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">Localizar Questões</div>
					<div class="card-body" style="height: 100%">
						<div class="filter">
							<form id="formPesquisa" action="" method="get">
                                <input type="hidden" value="{{Request::input('biblioteca')}}" name="biblioteca" id="biblioteca">
								 <div class="form-row">
									<div class="form-group col-md-3">
										<div class="input-group">
									    	<div class="input-group-prepend">
									      		<div class="input-group-text">
									      			<i class="fas fa-search"></i>
									    	  	</div>
									    	</div>
											<input type="text" value="{{Request::input('palavra_chave')}}" name="palavra_chave" placeholder="Código ou palavra-chave" class="form-control fs-13" />
										</div>
									</div>
									<div class="form-group col-md-4">
										<div class="input-group">
											<select name="ciclo_etapa[]" id="cicloetapa" size="2" class="form-control fs-13 multiple1" multiple onchange="defineUnidade($('#unidade').val()); defineHabilidade($('#habilidade').val())">
												@foreach($cicloEtapa as $c)
                                                @if($c->ciclo!="Ensino Médio" && $c->ciclo_etapa!="Todos" && $c->ciclo_etapa!="*" && $c->ciclo_etapa!="NEM" && $c->ciclo_etapa!="Especial")
                                                        <option @if(is_array(Request::input('ciclo_etapa')) && in_array($c->id, Request::input('ciclo_etapa')) ) selected @endif value="{{$c->id}}">{{$c->ciclo}} / {{$c->ciclo_etapa}}</option>
                                                @endif
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group col-md-5">
										<div class="input-group">
											<select name="disciplina[]" id="disciplina"   size="2" class="form-control fs-13 multiple2" multiple onchange="defineTema($('#tema').val()); defineUnidade($('#unidade').val()); defineHabilidade($('#habilidade').val())">
												@foreach($disciplina as $d)
                                                    <option @if(is_array(Request::input('disciplina')) && in_array($d->id, Request::input('disciplina')) ) selected @endif value="{{$d->id}}">{{$d->titulo}}</option>
												@endforeach
											</select>
										</div>
									</div>

								</div>

								<div class="form-row">
									<div class="form-group col-md-4">
										<div class="input-group">
											<select name="tema[]" id="tema" size="2" class="form-control fs-13 multiple9" multiple>

											</select>
										</div>
									</div>
									<div class="form-group col-md-5 ">
										<div class="input-group">
											<select name="unidade_tematica[]" id="unidade" size="2" class="form-control fs-13 multiple3" multiple onchange="defineHabilidade($('#habilidade').val())">

											</select>
										</div>
									</div>
									<div class="form-group col-md-3 ">
										<div class="input-group">
											<select name="habilidade[]" id="habilidade" size="2" class="form-control fs-13 multiple4" multiple>

											</select>
										</div>
									</div>

									<div class="form-group col-md-2">
										<div class="input-group">
											<select name="dificuldade[]" size="2" class="form-control fs-13 multiple8" multiple>
												<option @if(is_array(Request::input('dificuldade')) && in_array(0, Request::input('dificuldade')) ) selected @endif value="0">Fácil</option>
												<option @if(is_array(Request::input('dificuldade')) && in_array(1, Request::input('dificuldade')) ) selected @endif  value="1">Médio</option>
												<option @if(is_array(Request::input('dificuldade')) && in_array(2, Request::input('dificuldade')) ) selected @endif  value="2">Difícil</option>
											</select>
										</div>
									</div>
									<div class="form-group col-md-3 ">
										<div class="input-group">
											<select name="formato[]" size="2" class="form-control fs-13 multiple5" multiple>
												@foreach($formato as $d)
													<option @if(is_array(Request::input('formato')) && in_array($d->id, Request::input('formato')) ) selected @endif  value="{{$d->id}}">{{$d->titulo}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group col-md-4 ">
										<div class="input-group">
											<select name="suporte[]" size="2" class="form-control fs-13 multiple6" multiple>
												@foreach($suporte as $d)
													<option @if(is_array(Request::input('suporte')) && in_array($d->id, Request::input('suporte')) ) selected @endif value="{{$d->id}}">{{$d->titulo}}</option>
												@endforeach
											</select>
										</div>
									</div>
                                    <div class="form-group col-md-3 ">
                                        <div class="input-group">
                                            <select name="fonte[]" id="fonte" size="2" class="form-control fs-13 multiple10" multiple>
                                                @foreach($fonte as $d)
                                                    <option @if(is_array(Request::input('fonte')) && in_array($d->fonte, Request::input('fonte')) ) selected @endif value="{{$d->fonte}}">{{$d->fonte}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-9 ">
                                        <div class="col text-right">
                                            <button type="submit" class="btn btn-info fs-13"><i class="fas fa-filter"></i> Filtrar</button>
                                            <a href="{{url('avaliacao_ead/gestao/questao')}}" class="btn btn-danger fs-13" title="Limpar Filtro"><i class="fas fa-undo-alt"></i> Limpar todos os filtros</a>
                                        </div>
                                    </div>
								</div>

								<!--<div class="row-md-3"> </div> -->


							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row  pb-4" >
			<div class="col-md-6">
				<h4>EAD - Questões
                    <small style="font-size: 18px">
                        <br>
                        {{number_format($dados->total(), 0,'','.')}} encontradas
                    </small>
                </h4>
			</div>
            <div class="col-md-6 text-right">
                <span class="mr-5 "><a href="{{url('avaliacao_ead/gestao/avaliacao')}}" class="btn btn-warning text-white"><i class="fas fa-list"></i> Avaliações</a></span>
				<span><a href="{{url('avaliacao_ead/gestao/questao/nova')}}" class="btn btn-success"><i class="fas fa-plus"></i> Criar Nova Questão</a></span>
			</div>
		</div>

		<div class="row">

            <div class="col-12">
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Questão</th>
                                    <th scope="col">Habilidade</th>
                                    <th scope="col" class="text-right">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dados as $d)
                                    @php
                                        $pergunta =  strip_tags($d->pergunta);
                                    @endphp
                                <tr>
                                    <td>{{$d->id}}</td>
                                    <td>{{substr($pergunta,0,60)}}@if(strlen($pergunta)>60)... @endif
                                        <br>
                                        <div class="mt-2 fs-11" style="font-size: 11px; color: #E65A24"><i class="fas fa-layer-group"></i> {{@$d->cicloEtapa->ciclo}} / {{@$d->cicloEtapa->ciclo_etapa}} &nbsp;&nbsp;&nbsp; <i class="fas fa-book"></i> {{$d->disciplina->titulo}} &nbsp;&nbsp;&nbsp; <i class="fas fa-ruler-vertical"></i> {{$dificuldade[$d->dificuldade]}} &nbsp;&nbsp;&nbsp; <i class="fas fa-user"></i> {{$d->usuario->name}}</div>
                                    </td>
                                    <td>@if(isset($d->bncc->codigo_habilidade))  {{$d->bncc->codigo_habilidade}} @endif</td>

                                    <td class="text-right text-nowrap">
                                        <span>
                                            <a href="{{url('/avaliacao_ead/gestao/questao/duplicar/'.$d->id)}}" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="@if(Request::input('biblioteca') == 1 ) Adicionar as minhas questões @else Duplicar @endif">
                                                    <i class="fas fa-clone"></i>
                                            </a>
                                        </span>
                                        <span><button onclick="exibir({{$d->id}})" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Ver Questão"><i class="fas fa-eye"></i></button></span>
                                            <span><a href="{{url('/avaliacao_ead/gestao/questao/editar/'.$d->id)}}" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Editar Questão"><i class="fas fa-pencil-alt"></i></a></span>
                                            <span><button class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Excluir" onclick="modalExcluir({{$d->id}})"><i class="fas fa-trash-alt"></i></button></span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
		</div>
		<nav class="mt-4" aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                {{ $dados->appends(Request::all())->links() }}
            </ul>
        </nav>
	</div>
</section>


<!-- VER QUESTÃO -->
<div class="modal fade" id="formVerQuestao" tabindex="-1"  role="dialog" aria-labelledby="formIncluir" aria-hidden="true">
	<div class="modal-dialog  px-1 px-md-5 modal-full "  role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
			</button>
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel"></h5>
			</div>
			<div class="modal-body" id="conteudoVer">

			</div>
		</div>
	</div>
</div>

<!-- FIM VERQUESTAO -->

<!-- EXCLUIR -->
<div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
			</button>
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Exclusão de Questão</h5>
			</div>
			<div class="modal-body">
				<form action="">
	        		<div class="row">
	            		<div class="col-12">
	            			Você deseja mesmo excluir esse registro?<br><br>
	            			<b>Questão Código:</b> <span id="codigoExcluir"></span>
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

<script type="text/javascript">

    $(document).ready(function(){
        defineTema($('#tema').val());
        defineUnidade($('#unidade').val());
        defineHabilidade($('#habilidade').val());
    });

    var selEtapa = new vanillaSelectBox(".multiple1", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Etapa/ Ano",
    });

    var selDisciplina = new vanillaSelectBox(".multiple2", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Componente Curricular",
    });

    var selUnidade = new vanillaSelectBox(".multiple3", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Unidade temática",
    });

    var selHabilidade = new vanillaSelectBox(".multiple4", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Habilidade",
    });

    var selFormato = new vanillaSelectBox(".multiple5", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Formato da questão",
    });

    var selSuporte = new vanillaSelectBox(".multiple6", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Suporte",
    });

    var selDificuldade = new vanillaSelectBox(".multiple8", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Dificuldade",
    });

    var selTema = new vanillaSelectBox(".multiple9", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Assunto / Tema",
    });

    var selFonte= new vanillaSelectBox(".multiple10", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Fonte",
    });


	var idExcluir = 0;
	function modalExcluir(id)
	{
		idExcluir = id;
		$('#codigoExcluir').html(id);
		$('#formExcluir').modal('show');
	}
	function excluir()
    {
        window.location.href = '{{url("/avaliacao_ead/gestao/questao/excluir")}}/'+idExcluir;
    }

    function exibir(id)
    {
        url = '{{url('/avaliacao_ead/gestao/questao/ver')}}/'+id;
        $('#modalLabel').html('Questão '+id);
        $('#conteudoVer').html('<iframe style="border:none;" width="100%" height="600px" src="'+url+'"></iframe>')
        $('#formVerQuestao').modal('show');
    }

    function mudaRevisao(idQuestao,elemento,tipo)
    {
        status = 0;
        if($(elemento).is(":checked"))
        {
            status = 1;
        }
        if(tipo == 'revisado') {
            dados = {
                id: idQuestao,
                revisado: status,
                _token: '{{csrf_token()}}'
            };
        }
        else{
            dados = {
                id: idQuestao,
                cadastro: status,
                _token: '{{csrf_token()}}'
            };
        }
        $.ajax({
                url: '{{url('/avaliacao_ead/gestao/questao/mudaStatus')}}',
                type: 'post',
                dataType: 'json',
                data: dados,
                error: function(data) {
                    swal("", "Não foi possível alterar o status", "error");
                }
            });
    }

    function defineTema(sel)
    {
        disciplina = $('#disciplina').val();
        $.ajax({
            url: '{{url('/gestao/avaliacao/getTemaAjaxLista')}}',
            type: 'post',
            dataType: 'json',
            data: {
                disciplina_id: disciplina,
                selecionado: sel,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                selTema.destroy();
                $('#tema').html(data);
                selTema = new vanillaSelectBox(".multiple9", {
                    "maxHeight": 250,
                    "search": true ,
                    "placeHolder": "Assunto / Tema",
                });
            },
            error: function(data) {
                swal("", "Erro ao carregar Assunto / Tema", "error");
            }
        });
    }

    function defineUnidade(sel)
    {
        disciplina = $('#disciplina').val();
        cicloetapa = $('#cicloetapa').val();
        $.ajax({
            url: '{{url('/gestao/avaliacao/getUnidadeTematicaAjaxLista')}}',
            type: 'post',
            dataType: 'json',
            data: {
                disciplina_id: disciplina,
                cicloetapa_id: cicloetapa,
                selecionado: sel,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                selUnidade.destroy();
                $('#unidade').html(data);
                selUnidade = new vanillaSelectBox(".multiple3", {
                    "maxHeight": 250,
                    "search": true ,
                    "placeHolder": "Unidade temática",
                });
            },
            error: function(data) {
                swal("", "Erro ao carregar Assunto / Tema", "error");
            }
        });
    }

    function defineHabilidade(sel)
    {
        disciplina = $('#disciplina').val();
        cicloetapa = $('#cicloetapa').val();
        unidade = $('#unidade').val();
        $.ajax({
            url: '{{url('/gestao/avaliacao/getBnccAjaxLista')}}',
            type: 'post',
            dataType: 'json',
            data: {
                disciplina_id: disciplina,
                cicloetapa_id: cicloetapa,
                unidade_tematica_id: unidade,
                selecionado: sel,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                selHabilidade.destroy();
                $('#habilidade').html(data);
                selHabilidade = new vanillaSelectBox(".multiple4", {
                    "maxHeight": 250,
                    "search": true ,
                    "placeHolder": "Habilidade",
                });
            },
            error: function(data) {
                swal("", "Erro ao carregar Assunto / Tema", "error");
            }
        });
    }
</script>

@stop
