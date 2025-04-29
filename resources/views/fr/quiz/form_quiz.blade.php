<div class="modal fade" id="formIncluir" tabindex="-1" role="dialog" aria-labelledby="formIncluir" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
			<div class="modal-header">
				<h5 class="modal-title" id="tituloForm">Cadastro de Quiz</h5>
			</div>
			<div class="modal-body">
				<form id="formFormulario" action="" method="post" enctype="multipart/form-data">
					@csrf
	                <input type="hidden" name="id" id="idQuiz" value="{{old('id')}}">
					<div class="row">

                		<div class="col-12">
                			<div class="form-group">
                    			<label>* Título</label>
                    			<input type="text" name="titulo" placeholder="" value="{{old('titulo')}}" class="form-control rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
		                        <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                			</div>
                		</div>

                	</div>
            		<div class="row">
                		<div class="col-4">
                    		<div class="form-group">
	                        	<label>* Capa do quiz</label>
	                        	<div id="logoCadastro" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                	<input type="hidden" name="existeImg" id="existeImg" value="{{old('existeImg')}}">
	                                <img id="imgLogo" width="328px" src="{{ config('app.cdn')}}/storage/quiz/{{old('id')}}/capa/{{old('existeImg')}}" >
	                                <br>
	                                <a class="btn btn-secondary" onclick="excluirLogo()">Excluir Capa</a>
	                            </div>
	                        	<div id="novaLogo" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                        			<input type="file" accept="image/*" name="imagem" class="myCropper">
                    			</div>
		                       	<div class="invalid-feedback @if($errors->first('imagem'))d-block @endif">{{ $errors->first('imagem') }}</div>

	                    	</div>
                    	</div>
                    	<div class="col-8">
                    		<div class="row">

		                    	<div class="col-6">
	                				<div class="form-group">
                        				<label>* Componente Curricular</label>
                        				<div class="input-group">
											<select class="multipleDisciplina {{ $errors->has('disciplina_id') ? 'is-invalid' : '' }}" name="disciplina_id" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
				                      		  	<option value="">Selecione</option>
				                      		  	@foreach($disciplina as $d)
													<option @if( $d->id == old('disciplina_id')) selected @endif value="{{$d->id}}">{{$d->titulo}}</option>
				                      		  	@endforeach
				                      		</select>
		                       				<div class="invalid-feedback @if($errors->first('disciplina_id'))d-block @endif">{{ $errors->first('disciplina_id') }}</div>
										</div>
                    				</div>
                    			</div>
                    			@if(auth()->user()->permissao == 'Z' || auth()->user()->instituicao_id == 1)
                    			<!-- <div class="col-6">
		            				<div class="form-group">
                        				<label>* Coleção</label>
                        				<select class="multipleColecao {{ $errors->has('colecao_livro_id') ? 'is-invalid' : '' }}" name="colecao_livro_id" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
			                      		  	<option value="">Selecione</option>
			                      		  	@foreach($colecao as $c)
												<option @if( $c->id == old('colecao_livro_id')) selected @endif value="{{$c->id}}">{{$c->nome}}</option>
			                      		  	@endforeach
			                      		</select>
		                       			<div class="invalid-feedback @if($errors->first('colecao_livro_id'))d-block @endif">{{ $errors->first('colecao_livro_id') }}</div>
                    				</div>
		                    	</div> -->
		                    	@endif
                    			<div class="col-6">
		            				<div class="form-group">
                        				<label>* Etapa / Ano</label>
                        				<select class="multipleEtapa {{ $errors->has('ciclo_etapa_id') ? 'is-invalid' : '' }}" name="ciclo_etapa_id" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
				                      		<option value="">Selecione</option>
			                      		  	@foreach($cicloEtapa as $c)
												<option @if( $c->id == old('ciclo_etapa_id')) selected @endif value="{{$c->id}}">{{$c->ciclo}} - {{$c->ciclo_etapa}}</option>
			                      		  	@endforeach
			                      		</select>
		                       			<div class="invalid-feedback @if($errors->first('ciclo_etapa_id'))d-block @endif">{{ $errors->first('ciclo_etapa_id') }}</div>
                    				</div>
		                    	</div>
		                    	<div class="col-6">
		            				<div class="form-group">
                        				<label>Unidade Temática</label>
                    					<textarea name="unidade_tematica" class="form-control rounded {{ $errors->has('unidade_tematica') ? 'is-invalid' : '' }}">{{old('unidade_tematica')}}</textarea>

		                       			<div class="invalid-feedback @if($errors->first('unidade_tematica'))d-block @endif">{{ $errors->first('unidade_tematica') }}</div>
                    				</div>
		                    	</div>
		                    	<div class="col-6">
		            				<div class="form-group">
                        				<label>Habilidades</label>
                    					<textarea name="habilidades" class="form-control rounded {{ $errors->has('habilidades') ? 'is-invalid' : '' }}">{{old('habilidades')}}</textarea>
                        				<small class="form-text w-100 text-muted">
                        					As habilidades devem ser separadas por hífem " - ".
		                       			</small>
		                       			<div class="invalid-feedback @if($errors->first('habilidades'))d-block @endif">{{ $errors->first('habilidades') }}</div>
                    				</div>
		                    	</div>
		                    	<div class="col-6">
		            				<div class="form-group">
                        				<label>Palavras-chave</label>
                    					<textarea name="palavras_chave" class="form-control rounded {{ $errors->has('palavras_chave') ? 'is-invalid' : '' }}">{{old('palavras_chave')}}</textarea>
                        				<small class="form-text w-100 text-muted">
		                                    As palavras-chave devem ser separadas por hífen " - ".
		                                </small>
		                       			<div class="invalid-feedback @if($errors->first('palavras_chave'))d-block @endif">{{ $errors->first('palavras_chave') }}</div>
                    				</div>
		                    	</div>
		                    	<div class="col-6">
		            				<div class="form-group">
                        				<label>* Nível</label>
                        				<select class="multipleNivel {{ $errors->has('nivel') ? 'is-invalid' : '' }}" name="nivel" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
			                      		  	<option value="">Selecione</option>
			                      		  	<option value="0" @if(old('nivel')==0) selected @endif>Fácil</option>
			                      		  	<option value="1" @if(old('nivel')==1) selected @endif>Médio</option>
			                      		  	<option value="2" @if(old('nivel')==2) selected @endif>Difícil</option>
			                      		</select>
		                       			<div class="invalid-feedback @if($errors->first('nivel'))d-block @endif">{{ $errors->first('nivel') }}</div>
                    				</div>
		                    	</div>

								<div class="col-6">
		            				<div class="form-group">
                        				<label>* Quantidade de tentativas</label>
                        				<select class="form-control rounded {{ $errors->has('qtd_tentativas') ? 'is-invalid' : '' }}" name="qtd_tentativas" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
			                      		  	@for($k=1; $k<5; $k++)
			                      		  		<option value="{{$k}}" @if(old('qtd_tentativas')==$k || (old('qtd_tentativas')=='' && $k==0)) selected @endif>{{$k}}</option>
			                      		  	@endfor
			                      		</select>
			                      		<small class="form-text w-100 text-muted">
	                                    	Quantidade de tentativas que o aluno pode realizar em cada pergunta.
	                                    </small>
		                       			<div class="invalid-feedback @if($errors->first('qtd_tentativas'))d-block @endif">{{ $errors->first('qtd_tentativas') }}</div>
                    				</div>
		                    	</div>
								<div class="col-6">
		            				<div class="form-group">
                        				<label>* Pontuação total do Quiz</label>
                    					<input id="pontuacao" type="number" min="10" max="1000" step="10" name="pontuacao" value="{{old('pontuacao',1000)}}" class="form-control rounded {{ $errors->has('pontuacao') ? 'is-invalid' : '' }}">
                                        <small class="form-text w-100 text-muted">
                                            A soma de todas as questões dividida pelo número total de questões.
                                        </small>
                                        <div class="invalid-feedback @if($errors->first('pontuacao'))d-block @endif">{{ $errors->first('pontuacao') }}</div>
                    				</div>
		                    	</div>
		                    	<div class="col-6">
		            				<div class="form-group">
		                				<div class="custom-control custom-switch">
		                                    <input type="checkbox" class="custom-control-input" id="customSwitch2" @if(old('aleatorizar_questoes')== 1) checked @endif value="1" name="aleatorizar_questoes">
		                                    <label class="custom-control-label pt-1" for="customSwitch2">Aleatorizar perguntas?</label>
		                                    <small class="form-text w-100 text-muted">
		                                    Se ativado, deixa as perguntas do quiz ordenadas de forma aleatória.
		                                    </small>
		                                </div>
		            				</div>
		                    	</div>
                    		</div>
                		</div>
                    </div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button id="btnForm" type="button" class="btn btn-success" onclick="enviaFormulario()">Salvar</button>
			</div>
		</div>
	</div>
</div>

<!-- EXCLUIR -->
<div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Exclusão de Quiz</h5>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="row">
                        <div class="col-12">
                            Você deseja mesmo excluir esse quiz?<br><br>
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

<!-- Publicar -->
<div class="modal fade" id="formPublicar" tabindex="-1" role="dialog" aria-labelledby="formPublicar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Publicar Quiz</h5>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="row">
                        <div class="col-12">
                            Você deseja mesmo publicar esse quiz?<br><br>
                            <b id="tituloQuizPublicar"></b>
                            <br><br>
                            <p ><b class="text-danger">ATENÇÃO:</b> Após publicado o quiz não poderá ser editado! </p>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                <button type="button" onclick="publicar()" class="btn btn-success">Sim, publicar</button>
            </div>
        </div>
    </div>
</div>
<!-- FIM Publicar -->

<!-- Exibir quiz -->
<div class="modal fade" id="formExibir" tabindex="-1" role="dialog" aria-labelledby="formExibir" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="tituloVisao"></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" id="conteudoVisao">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FIM Exibir quiz -->
<script type="text/javascript">

    var quizExibir = 0;
    function mudaPesquisa()
    {
        $('#formPesquisa').submit();
    }

    function mostraVisao(id, titulo)
    {
        $('#tituloVisao').html(titulo)
        $('#conteudoVisao').html('<iframe style="border:none;" width="100%" height="600px" src="{{url('')}}/quiz/exibir?frame=1&q='+id+'"></iframe>')
        $('#formExibir').modal('show');
        quizExibir = id;
    }

    $('#formExibir').on('hidden.bs.modal', function () {
        url = '{{url('gestao/quiz/limparPlacar')}}'+'/'+quizExibir;
        $.get(url);
    });

    $('.list-grid-btn').click(function() {
        $('.grid-item').addClass('list-grid');
    });

    $('.no-list-grid-btn').click(function() {
        $('.grid-item').removeClass('list-grid');
    });
    var idExcluir = 0;
    var tipoOperacao = '';
    var idQuiz = '';
    var idPublicar = 0;

    function modalPublicar(id, nome)
    {
        idPublicar = id;
        $('#tituloQuizPublicar').html(nome);
        $('#formPublicar').modal('show');
    }

    function publicar()
    {
        window.location.href = '{{url("/gestao/quiz/publicar/")}}/'+idPublicar+'/0';
    }

    $('#formPublicar').on('hidden.bs.modal', function () {
        idPublicar = 0;
    });


    function modalExcluir(id, nome)
    {
        idExcluir = id;
        $('#tituloQuiz').html(nome);
        $('#formExcluir').modal('show');
    }

    function excluir()
    {
        window.location.href = '{{url("/gestao/quiz/excluir/")}}/'+idExcluir;
    }

    function excluirLogo()
    {
        $('#logoCadastro').hide();
        $('#novaLogo').show();
        $('#existeImg').val('');
    }

    $('#formExcluir').on('hidden.bs.modal', function () {
        idExcluir = 0;
    });

    $('#formIncluir').on('show.bs.modal', function () {

        if(tipoOperacao== 'editar')
        {
            $.ajax({
                url: '{{url('/gestao/quiz/getAjax/')}}',
                type: 'post',
                dataType: 'json',
                data: {
                    id: idQuiz,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    popularForm($('#formIncluir'), data);


                    selectEtapaAno.set(data['ciclo_etapa_id']);
                    selectDisciplina.set(data['disciplina_id']);
                    selectNivel.set(data['nivel']);
                    /*
                    @if(auth()->user()->permissao == 'Z' || auth()->user()->instituicao_id == 1)
                        selectColecao.set(data['colecao_livro_id']);
                    @endif
                    */
                    if(data['capa'] != '' && data['capa'] != null){
                        $('#imgLogo').attr('src','{{ config('app.cdn').'/storage/quiz/'}}'+'/'+data['id']+'/capa/'+data['capa']);
                        $('#existeImg').val(data['capa']);
                        $('#logoCadastro').show();
                        $('#novaLogo').hide();
                    }
                    else
                    {
                        $('#logoCadastro').hide();
                        $('#novaLogo').show();
                    }
                },
                error: function(data) {
                    swal("", "Quiz não encontrado", "error");
                }
            });

            $('#tituloForm').html('Edição de Quiz');

        }
        else
        {
            $('#tituloForm').html('Cadastro de Quiz');
            $('#logoCadastro').hide();
            $('#novaLogo').show();
        }
    });

    $('#formIncluir').on('hidden.bs.modal', function () {
        limpaForm('#formIncluir');
        tipoOperacao = '';
    });

    function enviaFormulario()
    {
        if(tipoOperacao == 'add')
        {
            $('#idQuiz').val('');
            $('#formFormulario').attr('action','{{url('/gestao/quiz/add/')}}');
            $('#formFormulario').submit();
        }
        else if(tipoOperacao == 'editar')
        {
            $('#formFormulario').attr('action','{{url('/gestao/quiz/editar/')}}');
            $('#formFormulario').submit();
        }
    }

    $(document).ready(function(){
        @if($errors->all() != null || Request::input('n')==1)
            $('#formIncluir').modal('show');
            @if(old('id')!='')
                tipoOperacao = 'editar';
                $('#tituloForm').html('Edição de Quiz');
                $('#btnForm').html('Editar');
                @if(old('existeImg'))
                    $('#logoCadastro').show();
                    $('#novaLogo').hide();
                @endif
            @else
                tipoOperacao = 'add';
            @endif
        @endif
    })

    var selectEtapaAno = new SlimSelect({
        select: '.multipleEtapa',
        placeholder: 'Buscar',
        searchPlaceholder: 'Buscar',
        closeOnSelect: true,
        allowDeselectOption: true,
        selectByGroup: true,
    });

    var selectDisciplina = new SlimSelect({
        select: '.multipleDisciplina',
        placeholder: 'Buscar',
        searchPlaceholder: 'Buscar',
        closeOnSelect: true,
        allowDeselectOption: true,
        selectByGroup: true,
    });

    var selectNivel = new SlimSelect({
        select: '.multipleNivel',
        placeholder: 'Buscar',
        searchPlaceholder: 'Buscar',
        closeOnSelect: true,
        allowDeselectOption: true,
        selectByGroup: true,
    });
    /*
    @if(auth()->user()->permissao == 'Z' || auth()->user()->instituicao_id == 1)
        var selectColecao = new SlimSelect({
            select: '.multipleColecao',
            placeholder: 'Buscar',
            searchPlaceholder: 'Buscar',
            closeOnSelect: true,
            allowDeselectOption: true,
            selectByGroup: true,
        });
    @endif
    */
</script>
