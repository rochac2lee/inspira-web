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
							<form id="formPesquisa" method="get" action="{{url('indica/gestao/avaliacao')}}">
                                <input type="hidden" name="publicado" id="publicado" value="" >
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
									<div class="form-group col-md-3">
										<div class="input-group">
											<select name="disciplina" class="form-control fs-13">
												<option value="">Componente curricular</option>
												@foreach($disciplina as $d)
                                                    @if($d->titulo=="Língua Portuguesa" || $d->titulo=="Matemática")
                                                        <option @if(Request::input('disciplina') == $d->id) selected @endif value='{{$d->id}}'>{{$d->titulo}}</option>
                                                    @endif
                                                @endforeach
											</select>
										</div>
									</div>
                                     <div class="form-group col-md-3">
                                         <div class="input-group">
                                             <select name="cicloetapa_id" class="form-control fs-13">
                                                 <option value="">Etapa / Ano</option>
                                                 @foreach($cicloEtapa as $c)
                                                 @if($c->ciclo!="Ensino Médio" && $c->ciclo_etapa!="Todos" && $c->ciclo_etapa!="*" && $c->ciclo_etapa!="NEM" && $c->ciclo_etapa!="Especial")
                                                        <option @if(Request::input('cicloetapa_id') == $c->id) selected @endif value="{{$c->id}}">{{$c->ciclo}} / {{$c->ciclo_etapa}}</option>
                                                    @endif
                                                 @endforeach
                                             </select>
                                         </div>
                                     </div>
                                     <div class="form-group col-md-3">
                                         <div class="input-group">
                                             <select name="caderno" class="form-control fs-13">
                                                 <option value="">Caderno</option>
                                                 <option @if(Request::input('caderno') == 1) selected @endif value="1">Caderno 1</option>
                                                 <option @if(Request::input('caderno') == 2) selected @endif value="2">Caderno 2</option>
                                                 <option @if(Request::input('caderno') == 3) selected @endif value="3">Caderno 3</option>
                                                 <option @if(Request::input('caderno') == 4) selected @endif value="4">Caderno 4</option>
                                                 <option @if(Request::input('caderno') == 5) selected @endif value="5">Caderno 5</option>
                                                 <option @if(Request::input('caderno') == 6) selected @endif value="6">Caderno 6</option>
                                             </select>
                                         </div>
                                     </div>

								</div>
								<div class="form-row">


									<div class="col text-right">
										<button type="submit" class="btn btn-info fs-13">Filtrar</button>
										<a href="{{url('/indica/gestao/avaliacao')}}" class="btn btn-danger fs-13" title="Limpar todos os filtros"><i class="fas fa-undo-alt"></i> Limpar todos os filtros</a>
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
				<h3>INdica - Avaliações</h3>
			</div>
			<div class="col-md-6 p-0 text-right">
                <span class="mr-5 "><a href="{{url('/indica/gestao/questao')}}" class="btn btn-warning text-white"><i class="fas fa-tasks"></i> Minhas questões</a></span>
				<span><a href="{{url('/indica/gestao/avaliacao/nova')}}" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Criar Nova Avaliação"><i class="fas fa-plus"></i> Criar Nova Avaliação</a></span>
			</div>
		</div>
		<div class="row">
            <div class="col-12">
                <h4>
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('publicado') == 1 || Request::input('publicado') == '' ) active @endif" href="javascript:$('#publicado').val(1);$('#formPesquisa').submit()">Publicado</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('publicado') == 0 && Request::input('publicado') != '') active @endif" href="javascript:$('#publicado').val(0);$('#formPesquisa').submit()">Rascunho</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('publicado') == 2 ) active @endif" href="javascript:$('#publicado').val(2);$('#formPesquisa').submit()">Cancelado</a>
                        </li>
                    </ul>
                </h4>
            </div>
			<section class="table-page w-100">
				<div class="table-responsive table-hover">
					<table class="table table-striped">
						<thead class="thead-dark">
							<tr>
								<th scope="col">Avaliação</th>
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
                                            {{$d->qtd_questao}} questões  &nbsp;&nbsp;{{$d->disciplina->titulo}}&nbsp;&nbsp; Caderno {{$d->caderno}}
                                        <br>
                                        @if(isset($d->cicloEtapa->ciclo)  && isset($d->cicloEtapa->ciclo_etapa)) {{$d->cicloEtapa->ciclo}} / {{$d->cicloEtapa->ciclo_etapa}}@endif
                                        <br> <div class="pt-1"><i class="fas fa-user"></i> {{$d->usuario->name}}</div>
									</div>
								</td>
                                <td>
                                    @if($d->deleted_at == '')
                                        @if($d->publicado!=1)
                                            <span  class="badge badge-info" >Rascunho</span>
                                        @else
                                            <span  class="badge badge-success" >Publicado</span>
                                        @endif
                                    @else
                                        <span  class="badge badge-danger" >Cancelado</span>
                                    @endif
                                </td>
								<td class="text-right text-nowrap">
                                    @if($d->publicado != 1)
                                        <span><button onclick="modalPublicar({{$d->id}}, '{{$d->titulo}}')" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Publicar"><i class="fas fa-bullhorn"></i> </button></span>
                                    @endif
                                    <span>
                                        <a href="{{url('/indica/gestao/avaliacao/duplicar/'.$d->id)}}" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title=" Duplicar">
                                                <i class="fas fa-clone"></i>
                                        </a>
                                    </span>
                                    @if($d->deleted_at == '')
                                        <span>
                                            <a href="javascript:void(0)" onclick="modalDetalhes({{$d->id}}, '{{$d->titulo}}')" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title=" Instituições selecionadas">
                                                    <i class="fas fa-eye"></i>
                                            </a>
                                        </span>
                                    @endif
                                    @if($d->publicado != 1)
                                        <span><a href="{{url('/indica/gestao/avaliacao/editar/'.$d->id)}}" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Editar Avaliação"><i class="fas fa-pencil-alt"></i></a></span>
                                        <span data-toggle="modal" data-target="#formExcluir"><button class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Excluir" onclick="modalExcluir({{$d->id}},'#{{$d->id}} {{$d->titulo}}')"><i class="fas fa-trash-alt"></i></button></span>
                                    @elseif($d->deleted_at == '')
                                        <span>
                                            <a href="{{url('/indica/gestao/avaliacao/relatorio/'.$d->id)}}" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title=" Relatorio">
                                                    <i class="fas fa-print"></i>
                                            </a>
                                        </span>
                                            <span>
                                            <a href="javascript:void(0)" onclick="modalCancelar({{$d->id}}, '{{$d->titulo}}')" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title=" Cancelar Avaliação">
                                                    <i class="fas fa-ban"></i>
                                            </a>
                                        </span>
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

    <!-- DETALHES -->
    <div class="modal fade" id="formDetalhes" tabindex="-1" role="dialog" aria-labelledby="formDetalhes" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Permissões da avaliação</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                <h5 id="codigoDetalhes" class="text-center"></h5>
                            </div>
                            <div class="col-12" >
                                <ul id="permissionamento" id="listaPermissao" class="list-style">

                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM DETALHES -->

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

    <!-- CANCELAR -->
    <div class="modal fade" id="formCancelar" tabindex="-1" role="dialog" aria-labelledby="formCancelar" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" >Cancelar de Avaliação</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                Você deseja mesmo cancelar esse registro?<br><br>
                                <b id="codigoCancelar"></b>
                                <br>
                                <br>
                                <p>Essa ação não poderá ser desfeita, todos os relatórios e participantes dessa avaliação serão perdidos</p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <button type="button" onclick="cancelar()" class="btn btn-danger">Sim, cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM CANCELAR -->

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
                <form id="FormPublicarPost" action="{{url('indica/gestao/avaliacao/publicar')}}" method="post">
                    @csrf
                    <input type="hidden" name="tituloAvaliacao" id="tituloAvaliacao" value="">
                    <input type="hidden" name="id" id="idAvaliacao" value="">
                    <div class="row">
                        <div class="col-12">
                            Você deseja mesmo publicar esse registro?<br><br>
                            <b id="nomeAvaliacao" style="font-size: 18px"></b><br><br>
                        </div>
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

    function modalDetalhes(id, titulo)
    {
        $.ajax({
            url: '{{url('/indica/gestao/avaliacao/permissoes')}}',
            type: 'post',
            dataType: 'json',
            data: {
                id: id,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $('#codigoDetalhes').html(titulo);
                $('#permissionamento').html(data);
                $('#formDetalhes').modal('show');
            },
            error: function(data) {
                swal("", "Erro ao carregar permissões", "error");
            }
        });

    }

    function modalCancelar(id, titulo)
    {
        idExcluir = id;
        $('#codigoCancelar').html(titulo);
        $('#formCancelar').modal('show');
    }
    function cancelar()
    {
        window.location.href = '{{url("/indica/gestao/avaliacao/cancelar")}}/'+idExcluir;
    }

	function modalExcluir(id, titulo)
	{
		idExcluir = id;
		$('#codigoExcluir').html(titulo);
		$('#formExcluir').modal('show');
	}
	function excluir()
    {
        window.location.href = '{{url("/indica/gestao/avaliacao/excluir")}}/'+idExcluir;
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
        window.location.href = '{{url('/indica/gestao/avaliacao/publicar')}}/'+idPublicar;
    }

    function modalNaoPublicar(id, titulo)
    {
        idEditar = id;
        $('#nomeAvaliacaoNaoPublicar').html(titulo);
        $('#formDataAvaliacao').modal('show');
    }

    function editar()
    {
        window.location.href = '{{url('/indica/gestao/avaliacao/editar/')}}/'+idEditar;
    }
</script>
@stop
