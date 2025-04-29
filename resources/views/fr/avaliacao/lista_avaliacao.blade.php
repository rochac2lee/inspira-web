@extends('fr/master')
@section('content')

    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.css">
    <script src="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.js"></script>

<section class="section section-interna">
	<div class="container">
		<div class="row mb-3" >
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">Localizar Avaliações</div>
					<div class="card-body">
						<div class="filter">
							<form id="formPesquisa" method="get" action="{{url('/gestao/avaliacao')}}">
                                <input type="hidden" name="biblioteca" id="biblioteca" value="" >
                                @if(auth()->user()->permissao == 'Z')
                                    <input type="hidden" value="{{Request::input('ead')}}" name="ead" id="ead">
                                @endif
								 <div class="form-row">
									<div class="form-group col-md-3">
										<div class="input-group">
									    	<div class="input-group-prepend">
									      		<div class="input-group-text">
									      			<i class="fas fa-search"></i>
									    	  	</div>
									    	</div>
											<input name="titulo" value="{{Request::input('titulo')}}" type="text" placeholder="O que está procurando?" class="form-control fs-13" />
										</div>
									</div>
									<div class="form-group col-md-4">
										<div class="input-group">
											<select name="disciplina" class="form-control fs-13">
												<option value="">Componente curricular</option>
												@foreach($disciplina as $d)
                                                	@if($d->titulo!='EAD')
                                                        <option @if(Request::input('disciplina') == $d->id) selected @endif value='{{$d->id}}'>{{$d->titulo}}</option>
                                                    @endif
                                                @endforeach
											</select>
										</div>
									</div>
									<div class="form-group col-md-2">
										<div class="input-group">
											<select name="tipo" class="form-control fs-13">
												<option @if(Request::input('tipo') == '') selected @endif value="">Tipo de prova</option>
												<option @if(Request::input('tipo') == 'p') selected @endif value="p">Prova</option>
                                                <option @if(Request::input('tipo') == 's') selected @endif value="s">Simulado</option>
                                                <option @if(Request::input('tipo') == 'l') selected @endif value="l">Lista</option>
											</select>
										</div>
									</div>
									<div class="form-group col-md-2">
										<div class="input-group">
											<select name="aplicacao" class="form-control fs-13">
												<option @if(Request::input('aplicacao') == '') selected @endif value="">Tipo de aplicação</option>
												<option @if(Request::input('aplicacao') == 'o') selected @endif  value="o">Online</option>
                                                <option @if(Request::input('aplicacao') == 'i') selected @endif  value="i">Impressa</option>
											</select>
										</div>
									</div>

								</div>
								<div class="form-row">


									<div class="col text-right">
										<button type="submit" class="btn btn-info fs-13">Filtrar</button>
										<a href="{{url('/gestao/avaliacao')}}" class="btn btn-danger fs-13" title="Limpar todos os filtros"><i class="fas fa-undo-alt"></i> Limpar todos os filtros</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row pt-0 pb-4">
			<div class="col-md-6">
				<h3>Avaliações INterativas</h3>
			</div>
			<div class="col-md-6 p-0 text-right">
                <span class="mr-5 "><a href="{{url('/gestao/avaliacao/minhas_questoes')}}" class="btn btn-warning text-white"><i class="fas fa-tasks"></i> Minhas questões</a></span>
				<span><a href="{{url('/gestao/avaliacao/nova')}}" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Criar Nova Avaliação"><i class="fas fa-plus"></i> Criar Nova Avaliação</a></span>
			</div>
		</div>
		<div class="row">
            @if(auth()->user()->permissao != 'Z')
                <div class="col-12">
                    <h4>
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link @if(Request::input('biblioteca') == '' ) active @endif" href="javascript:$('#biblioteca').val('');$('#formPesquisa').submit()">Minhas avaliações</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(Request::input('biblioteca') == 1 ) active @endif" href="javascript:$('#biblioteca').val(1);$('#formPesquisa').submit()">Avaliações da biblioteca</a>
                            </li>
                        </ul>
                    </h4>
                </div>
            @endif
			<section class="table-page w-100">
				<div class="table-responsive table-hover">
					<table class="table table-striped">
						<thead class="thead-dark">
							<tr>
								<th scope="col">Avaliação</th>
                                @if(Request::input('biblioteca') != 1 && (auth()->user()->permissao == 'P' || auth()->user()->permissao == 'C'))
								    <th scope="col">Turmas</th>
                                @endif
								<th scope="col">Status</th>
								<th scope="col" class="text-right" style="min-width: 220px">Ação</th>
							</tr>
						</thead>
						<tbody>
							@foreach($dados as $d)
							<tr>
								<td data-toggle="modal" data-target="#formAlterar"><b>#{{$d->id}}</b> {{$d->titulo}}
									<br>
									<div class="mt-2 fs-11">
										<span data-toggle="tooltip" data-placement="top" title="{{dataBR($d->data_hora_inicial)}} @if($d->data_hora_final!='')à {{dataBR($d->data_hora_final)}}@endif">
                                            <i class="fas fa-calendar-alt"></i> {{$d->data_hora_inicial->format('d/m/y')}} @if($d->data_hora_final!='') à {{$d->data_hora_final->format('d/m/y')}} @endif</span> &nbsp;&nbsp;&nbsp;
                                            {{$d->qtd_questao}} questões  {{$d->disciplina->titulo}}&nbsp;&nbsp; @if($d->tipo == 'p') Prova @elseif($d->tipo == 's') Simulado @else Lista @endif &nbsp;&nbsp;&nbsp;@if($d->aplicacao == 'o') Online @else Impressa @endif &nbsp;&nbsp;&nbsp;
                                        <br> <div class="pt-1">@if(isset($d->usuario)&&$d->usuario->name)<i class="fas fa-user"></i> {{$d->usuario->name}}@endif</div>
									</div>
								</td>
                                @if(Request::input('biblioteca') != 1 && (auth()->user()->permissao == 'P' || auth()->user()->permissao == 'C'))
                                    <td class="fs-11">
                                        @foreach($d->turmas as $t)
                                            {{$t->ciclo}} / {{$t->ciclo_etapa}} - {{$t->titulo}}<br>
                                        @endforeach
                                    </td>
                                @endif
                                <td>
                                    @if($d->publicado!=1)
                                        <span  class="badge badge-danger" >Rascunho</span>
                                    @else
                                        <span  class="badge badge-success" >Publicado</span>
                                    @endif
                                </td>
								<td class="text-right text-nowrap">

                                    @if ( ( (auth()->user()->permissao == 'Z' && $d->instituicao_id==1) || (auth()->user()->id == $d->user_id && auth()->user()->escola_id == $d->escola_id)) && $d->publicado != 1 )

                                        @if( (auth()->user()->permissao == 'Z' && $d->instituicao_id==1) || ($d->data_hora_inicial->gte(date('Y-m-d H:i:s'))) )
                                            <span><button onclick="modalPublicar({{$d->id}}, '{{$d->titulo}}')" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Publicar"><i class="fas fa-bullhorn"></i> </button></span>
                                        @else
                                            <span><button onclick="modalNaoPublicar({{$d->id}}, '{{$d->titulo}}')" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Publicar"><i class="fas fa-bullhorn"></i> </button></span>
                                        @endif

                                    @endif
                                    <span>
                                        <a href="{{url('/gestao/avaliacao/duplicar/'.$d->id)}}" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="@if(Request::input('biblioteca') == 1 ) Adicionar as minhas avaliações @else Duplicar @endif">
                                            @if(Request::input('biblioteca') == 1 )
                                                <i class="fas fa-heart"></i>
                                            @else
                                                <i class="fas fa-clone"></i>
                                            @endif
                                        </a>
                                    </span>
                                    @if($d->instituicao_id != 1 && $d->publicado == 1 && $d->aplicacao == 'o')
                                        <span><a href="{{url('/gestao/avaliacao/relatorio/online/'.$d->id)}}" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Visualizar, Relatórios e Notas"><i class="fas fa-file-invoice"></i></a></span>
                                    @endif
                                    @if($d->instituicao_id != 1 && $d->publicado == 1)
                                        <span><a href="{{url('/gestao/avaliacao/relatorio/detalhes/'.$d->id)}}" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Detalhes e Impressão"><i class="fas fa-print"></i></a></span>
                                    @endif
                                    @if($d->publicado != 1)
                                        <span><a href="{{url('/gestao/avaliacao/editar/'.$d->id)}}" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Editar Avaliação"><i class="fas fa-pencil-alt"></i></a></span>
                                        <span data-toggle="modal" data-target="#formExcluir"><button class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Excluir" onclick="modalExcluir({{$d->id}},'#{{$d->id}} {{$d->titulo}}')"><i class="fas fa-trash-alt"></i></button></span>
                                    @endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</section>
		</div>
		<nav class="mt-4" aria-label="Page navigation example">
			<ul class="pagination justify-content-center">
				{{ $dados->appends(Request::all())->links() }}
			</ul>
		</nav>
	</div>
</section>
<!-- EXCLUIR -->
<div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
			</button>
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Exclusão de Avaliações</h5>
			</div>
			<div class="modal-body">
				<form action="">
	        		<div class="row">
	            		<div class="col-12">
	            			Você deseja mesmo excluir esse registro?<br><br>
	            			<b id="codigoExcluir"></b>
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

<!-- PUBLICAR -->
<div class="modal fade " id="formPublicar" tabindex="-1" role="dialog" aria-labelledby="formPublicar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Publicação de Avaliação</h5>
            </div>
            <div class="modal-body">
                <form id="FormPublicarPost" action="{{url('/gestao/avaliacao/publicar')}}" method="post">
                    @csrf
                    <input type="hidden" name="tituloAvaliacao" id="tituloAvaliacao" value="">
                    <input type="hidden" name="id" id="idAvaliacao" value="">
                    <div class="row">
                        <div class="col-12">
                            Você deseja mesmo publicar esse registro?<br><br>
                            <b id="nomeAvaliacao" style="font-size: 18px"></b><br><br>
                        </div>
                        @if(auth()->user()->permissao == 'P' || auth()->user()->permissao == 'C')
                        <div class="col-12" style="background-color: rgba(219,219,219,0.76) ">
                            <br>
                            <p><b>Para publicar é necessário selecionar as turmas que farão essa avaliação.</b></p>
                            @if(auth()->user()->permissao == 'C')
                                <select name="tipo" id="tipoPublicar" onchange="trocaTipoPublicar()">
                                    <option value="1" @if(old('tipo') == 1 || old('tipo') == '') selected @endif>Publicar apenas na biblioteca visível para os professores das escolas.</option>
                                    <option value="2" @if(old('tipo') == 2) selected @endif>Publicar para realização de avaliação por turmas específicas.</option>
                                </select>
                                <br>
                                <br>
                            @endif
                            <span id="selTurma">
                                <select name="turmas[]" id="turma" multiple>
                                    @foreach($turmas as $t)
                                        <option value="{{$t->id}}">{{$t->ciclo}} / {{$t->ciclo_etapa}} - {{$t->titulo}}</option>
                                    @endforeach
                                </select>
                            </span>
                            <div class="invalid-feedback @if($errors->first('turmas')) d-block @endif" style="font-size: 12px">{{ $errors->first('turmas') }}</div>
                            <br><br>
                        </div>
                        @elseif(auth()->user()->permissao != 'Z')
                            <div class="col-12" style="background-color: rgba(219,219,219,0.76) ">
                                <br>
                                <p>
                                    @if(auth()->user()->permissao == 'Z')
                                        <b>A sua avaliação ficará disponível na biblioteca da rede. </b>
                                    @else
                                        <b>A sua avaliação ficará disponível apenas na biblioteca da sua instituição. </b>
                                    @endif
                                </p>
                            </div>
                        @endif
                        <div class="col-12">
                            <br><br>
                            <p class="text-justify"><b >ATENÇÃO:</b> A publicação da avaliação NÃO poderá ser desfeita. </p>
                            <p><b >Tem certeza que deseja publicar?</b></p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                <button type="button" onclick="$('#FormPublicarPost').submit();" class="btn btn-success">Sim, publicar</button>
            </div>
        </div>
    </div>
</div>
<!-- FIM PUBLICAR -->

    <!-- DataAvaliacao -->
    <div class="modal fade" id="formDataAvaliacao" tabindex="-1" role="dialog" aria-labelledby="formDataAvaliacao" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Publicação de Avaliações</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                Você deseja mesmo publicar esse registro?<br><br>
                                <b id="nomeAvaliacaoNaoPublicar" style="font-size: 18px"></b><br><br>
                            </div>
                            <div class="col-12" style="background-color: rgba(252,186,186,0.76) ">
                                <br>
                                <p class="text-center" style="color: red"><b>Não é possível publicar essa avaliação. <br>A data inicial da avaliação já passou!</b></p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" onclick="editar()" class="btn btn-success">Editar Avaliação</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM DataAvaliacao -->
<script>
    $(document).ready(function(){
        @if(auth()->user()->permissao == 'P' || auth()->user()->permissao == 'C')
            var selTurmaMulti = new vanillaSelectBox("#turma", {
                "maxHeight": 250,
                "search": true ,
                "placeHolder": "Etapa / Ano - Turma",
            });
            @if(auth()->user()->permissao == 'C')
            var selTipo = new vanillaSelectBox("#tipoPublicar", {
                "maxHeight": 250,
                "search": false ,
                "placeHolder": "",
            });
            @endif
        @endif

        @error('turmas')
            modalPublicar({{old('id')}}, '{{old('tituloAvaliacao')}}');
        @enderror


    });

	var idExcluir = 0;
	var idPublicar = 0;
	var idEditar = 0;

	function modalExcluir(id, titulo)
	{
		idExcluir = id;
		$('#codigoExcluir').html(titulo);
		$('#formExcluir').modal('show');
	}
	function excluir()
    {
        window.location.href = '{{url("/gestao/avaliacao/excluir")}}/'+idExcluir;
    }

    function trocaTipoPublicar(){
        tipo = $('#tipoPublicar').val();
        if(tipo == '1'){
            $('#selTurma').hide();
        }else{
            $('#selTurma').show();
        }
    }

    function modalPublicar(id, titulo)
    {
        @if(auth()->user()->permissao == 'C')
            tipo = $('#tipoPublicar').val();
            if(tipo != '2'){
                $('#selTurma').hide();
            }
        @endif
        idPublicar = id;
        $('#nomeAvaliacao').html(titulo);
        $('#tituloAvaliacao').val(titulo);
        $('#idAvaliacao').val(id);
        $('#formPublicar').modal('show');
    }

    function publicar()
    {
        window.location.href = '{{url('/gestao/avaliacao/publicar')}}/'+idPublicar;
    }

    function modalNaoPublicar(id, titulo)
    {
        idEditar = id;
        $('#nomeAvaliacaoNaoPublicar').html(titulo);
        $('#formDataAvaliacao').modal('show');
    }

    function editar()
    {
        window.location.href = '{{url('/gestao/avaliacao/editar/')}}/'+idEditar;
    }
</script>
@stop
