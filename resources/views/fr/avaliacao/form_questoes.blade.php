@extends('fr/master')
@section('content')
<script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
<link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet">
<script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/formUtilities.js"></script>
<script src="{{config('app.cdn')}}/fr/includes/js/jquery/jquery-ui.js"></script>

<!--  Exclusivo Froala Editor  -->
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/froala_editor.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/froala_style.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/code_view.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/draggable.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/colors.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/emoticons.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/image_manager.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/image.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/line_breaker.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/table.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/char_counter.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/video.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/fullscreen.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/file.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/quick_insert.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/help.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/third_party/spell_checker.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/special_characters.css">
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/codemirror.min.css">


<!--  FIM Froala Editor  -->
<style type="text/css">
	.letra{
    border: 1px solid #cccccc;
    border-radius: 100%;
    width: 40px;
    height: 40px;
    text-align: center;
    font-size: 30px;
}
</style>

<section class="section section-interna pb-4">
    <div class="row">
        <div class="col-md-12">
            <h4 class="pb-1 border-bottom mb-4">
                <a href="{{url('/gestao/avaliacao/minhas_questoes/')}}" class="btn btn-secondary ml-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                @if ( strpos(Request::path(),'editar')===false )Nova questão @else Editar questão @endif
            </h4>
        </div>
    </div>
	<form action="" method="post">
		@csrf
	    <div class="container-fluid">
	    	<div class="row">
	    		<div class="col-12 bg-light ">
	    			<small style="font-size: 11px"><br>* campos obrigatórios</small>
	    		</div>
	    	</div>
	        <div class="row">
	            <div class="col-md-6 bg-light border-right pt-4 pb-4 pl-4 pr-5">
	                <h4 class="pb-3 border-bottom mb-4">Questão</h4>
	                <div class="form-group">
	                    <div class="form-group">
	                        <label class="font-weight-bold">* Pergunta</label>
	                        <div>
	                            <textarea class='editorFroala' name="pergunta">{{old('pergunta',@$dados->pergunta)}}</textarea>
	                        </div>
	                        <div class="invalid-feedback @if($errors->first('pergunta')) d-block @endif" style="font-size: 12px">{{ $errors->first('pergunta') }}</div>

	                    </div>
	                    <label class="font-weight-bold">Alternativas</label>
	                    <div class="container px-lg-5 fs-12">
	                        <div class="row mx-lg-n5">
	                            <div class="col border bg-light pt-3">
	                                <label class="font-weight-bold">* Tipo</label>
	                                <div class="form-group">
	                                    <div class="custom-control custom-radio custom-control-inline">
	                                      <input type="radio" id="tipoquestao1" name="tipo" class="tipoquestao custom-control-input" value="obj" @if( (old('tipo') == 'obj' || old('tipo') =='' ) && @$dados->tipo != 'd' ) checked @endif >
	                                      <label class="custom-control-label pt-1" for="tipoquestao1">Questão Objetiva</label>
	                                    </div>
	                                    <div class="custom-control custom-radio custom-control-inline">
	                                      <input type="radio" id="tipoquestao2" name="tipo" class="tipoquestao custom-control-input" value="dis" @if(old('tipo') == 'dis' ||  @$dados->tipo == 'd') checked @endif>
	                                      <label class="custom-control-label pt-1" for="tipoquestao2">Questão Discursiva</label>
	                                    </div>
	                                </div>

	                                <div id="avaliacaoObjetiva" class="d-none">
	                                    <div class="form-group">
	                                        <label class="font-weight-bold">* Quantidade de Alternativas</label>
	                                        <div class="input-group input-group-sm mb-3">
	                                        	@php
	                                        		$qtd= 4;
	                                        		if(isset($dados->qtd_alternativa))
	                                        		{
	                                        			$qtd = $dados->qtd_alternativa;
	                                        		}
	                                        	@endphp
	                                            <input type="number" name="qtd_alternativa" id="qtda_alternativa" value="{{old('qtda_alternativa',$qtd)}}" class="form-control form-control-sm {{ $errors->has('qtd_alternativa') ? 'is-invalid' : '' }}" placeholder="Número" min="2" max="7">
	                                            <div class="input-group-append">
	                                                <span class="input-group-text" id="inputGroup-sizing-sm">Alternativas</span>
	                                            </div>
	                                            <small class="form-text w-100 text-muted">
	                                                Define a quantidade de alternativas para a questão.
	                                            </small>
	                                            <div class="invalid-feedback @if($errors->first('qtd_alternativa')) d-block @endif">{{ $errors->first('qtd_alternativa') }}</div>
	                                        </div>
	                                    </div>
	                                    <div class="form-group alternativa" id="alternativa_1">
	                                        <label class="font-weight-bold">* Alternativas</label>
	                                        <div class="row">
	                                            <div class="col-md-1">
	                                                <div class="letra">A</div>
	                                            </div>
	                                            <div class="col-md-11">
	                                                <div>
	                                                    <textarea id='fr_1' name="alternativa_1">{{old('alternativa_1',@$dados->alternativa_1)}}</textarea>
	                                                </div>
	                                            	<div class="invalid-feedback @if($errors->first('alternativa_1')) d-block @endif" style="font-size: 12px">{{ $errors->first('alternativa_1') }}</div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="form-group alternativa" id="alternativa_2">
	                                        <div class="row">
	                                            <div class="col-md-1">
	                                                <div class="letra">B</div>
	                                            </div>
	                                            <div class="col-md-11">
	                                                <div id="editor">
	                                                    <textarea class='editorFroala' name="alternativa_2">{{old('alternativa_2',@$dados->alternativa_2)}}</textarea>
	                                                </div>
	                                                <div class="invalid-feedback @if($errors->first('alternativa_2')) d-block @endif" style="font-size: 12px">{{ $errors->first('alternativa_2') }}</div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="form-group alternativa" id="alternativa_3">
	                                        <div class="row">
	                                            <div class="col-md-1">
	                                                <div class="letra">C</div>
	                                            </div>
	                                            <div class="col-md-11">
	                                                <div id="editor">
	                                                    <textarea class='editorFroala' name="alternativa_3">{{old('alternativa_3',@$dados->alternativa_3)}}</textarea>
	                                                </div>
	                                                <div class="invalid-feedback @if($errors->first('alternativa_3')) d-block @endif" style="font-size: 12px">{{ $errors->first('alternativa_3') }}</div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="form-group alternativa" id="alternativa_4">
	                                        <div class="row">
	                                            <div class="col-md-1">
	                                                <div class="letra">D</div>
	                                            </div>
	                                            <div class="col-md-11">
	                                                <div id="editor">
	                                                    <textarea class='editorFroala' name="alternativa_4">{{old('alternativa_4',@$dados->alternativa_4)}}</textarea>
	                                                </div>
	                                                <div class="invalid-feedback @if($errors->first('alternativa_4')) d-block @endif" style="font-size: 12px">{{ $errors->first('alternativa_4') }}</div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="form-group alternativa" id="alternativa_5">
	                                        <div class="row">
	                                            <div class="col-md-1">
	                                                <div class="letra">E</div>
	                                            </div>
	                                            <div class="col-md-11">
	                                                <div id="editor">
	                                                    <textarea class='editorFroala' name="alternativa_5">{{old('alternativa_5',@$dados->alternativa_5)}}</textarea>
	                                                </div>
	                                                <div class="invalid-feedback @if($errors->first('alternativa_5')) d-block @endif" style="font-size: 12px">{{ $errors->first('alternativa_5') }}</div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="form-group alternativa" id="alternativa_6">
	                                        <div class="row">
	                                            <div class="col-md-1">
	                                                <div class="letra">F</div>
	                                            </div>
	                                            <div class="col-md-11">
	                                                <div id="editor">
	                                                    <textarea class='editorFroala' name="alternativa_6">{{old('alternativa_6',@$dados->alternativa_6)}}</textarea>
	                                                </div>
	                                                <div class="invalid-feedback @if($errors->first('alternativa_6')) d-block @endif" style="font-size: 12px">{{ $errors->first('alternativa_6') }}</div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="form-group alternativa" id="alternativa_7">
	                                        <div class="row">
	                                            <div class="col-md-1">
	                                                <div class="letra">G</div>
	                                            </div>
	                                            <div class="col-md-11">
	                                                <div id="editor">
	                                                    <textarea class='editorFroala' name="alternativa_7">{{old('alternativa_7',@$dados->alternativa_7)}}</textarea>
	                                                </div>
	                                                <div class="invalid-feedback @if($errors->first('alternativa_7')) d-block @endif" style="font-size: 12px">{{ $errors->first('alternativa_7') }}</div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                     <div class="form-group">
	                                        <label class="font-weight-bold">* Alternativa Correta</label>
	                                        <div class="input-group input-group-sm mb-3">
	                                            <div class="input-group-prepend">
	                                                <span class="input-group-text" id="inputGroup-sizing-sm">A alternativa</span>
	                                            </div>
	                                            <select id="correta" name="correta" class="form-control form-control-sm {{ $errors->has('correta') ? 'is-invalid' : '' }}">
	                                                <option></option>
	                                                <option @if(old('correta',@$dados->correta) == 1) selected @endif value="1">A</option>
	                                                <option @if(old('correta',@$dados->correta) == 2) selected @endif value="2">B</option>
	                                                <option @if(old('correta',@$dados->correta) == 3) selected @endif value="3">C</option>
	                                                <option @if(old('correta',@$dados->correta) == 4) selected @endif value="4">D</option>
	                                                <option @if(old('correta',@$dados->correta) == 5) selected @endif value="5">E</option>
	                                                <option @if(old('correta',@$dados->correta) == 6) selected @endif value="6">F</option>
	                                                <option @if(old('correta',@$dados->correta) == 7) selected @endif value="7">G</option>
	                                            </select>
	                                            <div class="input-group-append">
	                                                <span class="input-group-text" id="inputGroup-sizing-sm">é a correta</span>
	                                            </div>
	                                            <small class="form-text w-100 text-muted">
	                                                Define qual é a alternativa correta.
	                                            </small>
	                                            <div class="invalid-feedback @if($errors->first('correta'))d-block @endif" style="font-size: 12px">{{ $errors->first('correta') }}</div>
	                                        </div>
	                                    </div>
	                                </div>

	                                <div id="avaliacaoDiscursiva" class="d-none">
	                                    <label class="font-weight-bold">Formato da Questão Discursiva</label>
	                                    <div class="form-group">
	                                        <div class="custom-control custom-radio custom-control-inline">
	                                          <input type="radio" id="formatoQuestao1" name="com_linhas" class="formatoQuestao custom-control-input" value="1" @if( (old('com_linhas') == 1 || old('com_linhas')=='') && @$dados->com_linhas!='0') checked @endif>
	                                          <label class="custom-control-label pt-1" for="formatoQuestao1">Caixas com Linhas</label>
	                                        </div>
	                                        <div class="custom-control custom-radio custom-control-inline">
	                                          <input type="radio" id="formatoQuestao2" name="com_linhas" class="formatoQuestao custom-control-input" value="0" @if( (old('com_linhas') == 0 && old('com_linhas')!='') || @$dados->com_linhas=='0' ) checked @endif>
	                                          <label class="custom-control-label pt-1" for="formatoQuestao2">Caixas em Branco</label>
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
	                                        <label class="font-weight-bold">Linhas para Resposta</label>
	                                        <div class="input-group input-group-sm mb-3">
	                                        	@php
	                                        		$qtd= 4;
	                                        		if(isset($dados->qtd_linhas))
	                                        		{
	                                        			$qtd = $dados->qtd_linhas;
	                                        		}
	                                        	@endphp
	                                            <input type="number" name="qtd_linhas" id="qtda_discursiva_linhas" value="{{@old('qtd_linhas',$qtd)}}" class="form-control form-control-sm" placeholder="Número" min="0">
	                                            <div class="input-group-append">
	                                                <span class="input-group-text" id="inputGroup-sizing-sm">Linhas</span>
	                                            </div>
	                                            <small class="form-text w-100 text-muted">
	                                                Define a quantidade de espaços em linhas para a resposta da questão.
	                                            </small>
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
	                                        <label class="font-weight-bold">Preview de Linhas</label>
	                                        <div id="preview_discursiva" class="col bg-white border"></div>
	                                        <small class="form-text text-right w-100 text-muted" id="preview_discursiva_desc">
	                                        </small>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-6 pl-5">
	                <div class="pt-4">
	                    <h4 class="pb-3 border-bottom mb-4">Configurações</h4>
	                </div>
	                <div class="form-group">
	                    <label class="font-weight-bold">* Dificuldade da questão</label>
	                    <select class="form-control form-control-sm rounded" name="dificuldade">
                  		  	<option value="0" @if(old('dificuldade',@$dados->dificuldade)==0 && old('dificuldade',@$dados->dificuldade) != '') selected @endif>Fácil</option>
                  		  	<option value="1" @if(old('dificuldade',@$dados->dificuldade)==1 || old('dificuldade',@$dados->dificuldade) == '') selected @endif>Médio</option>
                  		  	<option value="2" @if(old('dificuldade',@$dados->dificuldade)==2) selected @endif>Difícil</option>
	                    </select>
	                    <div class="invalid-feedback @if($errors->first('dificuldade'))d-block @endif" style="font-size: 12px">{{ $errors->first('dificuldade') }}</div>

	                </div>
	                <div class="form-group mt-3">
	                    <label class="font-weight-bold">* Etapa / Ano</label>
	                    <select name="cicloetapa_id" id="cicloetapa_id" class="multiple2 {{ $errors->has('cicloetapa_id') ? 'is-invalid' : '' }}" single onchange="getUnidadeTematica()">
	                        <option value=''>Selecione</option>
	                        @foreach($cicloEtapa as $d)
	                        	<option value='{{$d->id}}'  @if(old('cicloetapa_id',@$dados->cicloetapa_id) == $d->id) selected @endif >{{$d->ciclo}} / {{$d->ciclo_etapa}}</option>
	                        @endforeach
	                    </select>
	                    <div class="invalid-feedback @if($errors->first('cicloetapa_id'))d-block @endif" style="font-size: 12px">{{ $errors->first('cicloetapa_id') }}</div>
	                </div>
	                <div class="form-group mt-3">
	                    <label class="font-weight-bold">* Componente curricular</label>
	                    <select name="disciplina_id" id="disciplina_id" class="multiple1 {{ $errors->has('disciplina_id') ? 'is-invalid' : '' }}" single onchange="getUnidadeTematica(); defineTema();">
	                        <option value=''>Selecione</option>
	                        @foreach($disciplina as $d)
	                        	@if($d->titulo!='EAD')
								<option value='{{$d->id}}' @if(old('disciplina_id',@$dados->disciplina_id) == $d->id) selected @endif>{{$d->titulo}}</option>
								@endif
							@endforeach
	                    </select>
	                    <div class="invalid-feedback @if($errors->first('disciplina_id'))d-block @endif" style="font-size: 12px">{{ $errors->first('disciplina_id') }}</div>
	                </div>
	                <br>
	                <p style="text-align: center;height: 0.5em;border-bottom: 2px solid #E9ECEF;margin-bottom: 0.5em;"><span style="background-color: white; padding-left: 6px;padding-right: 6px;"> Outras informações </span></p>
	                <br>
	                <div class="form-group mt-3">
	                    <label class="font-weight-bold">Unidade Temática / Prática de Linguagem</label>
	                    <select id="unidade_tematica_id" name="unidade_tematica" class="multiple3 {{ $errors->has('bncc_id') ? 'is-invalid' : '' }}" single onchange="getBncc()">
	                        <option value="">Não se aplica</option>
	                    </select>
	                    <div class="invalid-feedback @if($errors->first('unidade_tematica'))d-block @endif" style="font-size: 12px">{{ $errors->first('disciplina_id') }}</div>
	                </div>
	                <div class="form-group mt-3">
	                    <label class="font-weight-bold">Habilidade</label>
	                    <select name="bncc_id" id="bncc_id" class="multiple4 {{ $errors->has('bncc_id') ? 'is-invalid' : '' }}" single>
	                        <option value="">Não se aplica</option>
	                    </select>
	                    <div class="invalid-feedback @if($errors->first('bncc_id'))d-block @endif" style="font-size: 12px">{{ $errors->first('disciplina_id') }}</div>
	                </div>
	                <div class="form-group">
	                    <label class="font-weight-bold">Formato da questão</label>
	                    <select name="formato_id" class="multiple5 {{ $errors->has('formato_id') ? 'is-invalid' : '' }}" single>
	                        <option value=''>Não se aplica</option>
	                        @foreach($formato as $d)
	                        	<option value='{{$d->id}}' @if(old('formato_id',@$dados->formato_id) == $d->id) selected @endif>{{$d->titulo}}</option>
	                        @endforeach
	                    </select>
	                    <div class="invalid-feedback @if($errors->first('formato_id'))d-block @endif" style="font-size: 12px">{{ $errors->first('formato_id') }}</div>
	                </div>
	                <div class="form-group">
	                    <label class="font-weight-bold">Elementos de suporte</label>
	                    <select name="suporte_id" class="multiple6 {{ $errors->has('suporte_id') ? 'is-invalid' : '' }}" single>
	                        <option value=''>Nenhum</option>
	                        @foreach($suporte as $d)
	                        	<option value='{{$d->id}}' @if(old('suporte_id',@$dados->suporte_id) == $d->id) selected @endif>{{$d->titulo}}</option>
	                        @endforeach
	                    </select>
	                    <div class="invalid-feedback @if($errors->first('suporte_id'))d-block @endif" style="font-size: 12px">{{ $errors->first('suporte_id') }}</div>
	                </div>
	                <div class="form-group">
	                    <label class="font-weight-bold">Assunto / Tema</label>
	                    <select name="tema_id" id="tema" class="multiple7 {{ $errors->has('tema_id') ? 'is-invalid' : '' }}" single>
	                        <option value=''>Selecione um componente curricular</option>
	                    </select>
	                    <div class="invalid-feedback @if($errors->first('tema_id'))d-block @endif" style="font-size: 12px">{{ $errors->first('tema_id') }}</div>
	                </div>
					<div class="form-group">
                        <label class="font-weight-bold">Palavras-chave</label>
                        <input type="text" name="palavras_chave" value="{{old('palavras_chave',@$dados->palavras_chave)}}" class="form-control form-control-sm rounded">
                    	<small class="form-text w-100 text-muted">
                            As palavras-chave devem ser separadas por hífen " - ".
                        </small>
                    </div>

	                <div class="form-group">
                        <label class="font-weight-bold">Fonte</label>
                        <input type="text" name="fonte" value="{{old('fonte',@$dados->fonte)}}" class="form-control form-control-sm rounded">
                    </div>

	                <div class="container px-lg-5 fs-12 mb-4">
	                    <div class="row mx-lg-n5">
	                        <div class="col border bg-light p-2">
	                            <div class="form-group mb-0">
	                                <div class="custom-control custom-switch">
	                                    <input type="checkbox" name="disponibilizar_resolucao" class="custom-control-input" id="disponibilizarResposta" value="1" @if(old('disponibilizar_resolucao')==1 || @$dados->disponibilizar_resolucao==1) checked @endif>
	                                    <label class="custom-control-label pt-1" for="disponibilizarResposta">Disponibilizar resolução da questão</label>
	                                </div>
	                            </div>

	                            <div id="respotaQuestao" class="d-none">
	                                <div class="form-group mt-2">
	                                    <div id="editor">
	                                        <textarea name="resolucao" class='editorFroala'>{{old('resolucao',@$dados->resolucao)}}</textarea>
	                                    </div>
	                    				<div class="invalid-feedback @if($errors->first('resolucao'))d-block @endif"  style="font-size: 12px">{{ $errors->first('resolucao') }}</div>

	                                </div>
	                                <div class="form-group">
	                                    <label class="font-weight-bold">Link do Vídeo Resposta</label>
	                                    <input type="text" name="link_video_resolucao" value="{{old('link_video_resolucao',@$dados->link_video_resolucao)}}" placeholder="https://www.youtube.com/watch?v=codigo" class="form-control form-control-sm rounded">
	                                    <small class="form-text w-100 text-muted">
	                                        Você pode gravar um vídeo como resposta da questão usando o YouTube.
	                                    </small>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="row mx-lg-n5 mt-2">
	                        <div class="col border bg-light p-2">
	                            <div class="form-group mb-0">
	                                <div class="custom-control custom-switch">
	                                    <input type="checkbox" class="custom-control-input" id="customSwitch2" name="compartilhar" value="1" @if(old('compartilhar',@$dados->compartilhar) == 1) checked @endif>
	                                    <label class="custom-control-label pt-1" for="customSwitch2">Compartilhar questão?</label>
	                                    <small class="form-text w-100 text-muted">
	                                    Se ativado, sua questão poderá ser usada por outros docentes da sua escola.
	                                    </small>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <a href="{{url('/gestao/avaliacao/minhas_questoes/')}}" class="btn btn-secondary float-left" >Cancelar</a>
	                <button type="submit" class="btn btn-default mt-0 float-right ml-2">Salvar</button>
	            </div>
	        </div>
	    </div>
	</form>
</section>
       <!--  Exclusivo Froala Editor  -->
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/codemirror.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/xml.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/purify.min.js"></script>

  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/froala_editor.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/align.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/code_beautifier.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/code_view.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/colors.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/draggable.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/font_size.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/font_family.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/image.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/line_breaker.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/inline_style.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/lists.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/paragraph_format.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/paragraph_style.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/word_paste.min.js"></script>
  <script type="text/javascript" src='{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/languages/pt_br.js'></script>
<script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/special_characters.min.js"></script>    <!--  FIM Froala Editor  -->

<!-- Codigo para adicionar a matemática-->
<script type="text/javascript" src="{{url('/fr/includes/froala_editor_v4/node_modules/wiris/mathtype-froala3/wiris.js')}}"></script>

<!-- Codigo para mostrar o resultado --->
<script type="text/javascript" src="{{url('/fr/includes/froala_editor_v4/js/plugins/froala_wiris/integration/WIRISplugins.js?viewer=image')}}"></script>

  <script>

	var parametroFroala = {
  key: "{{config('app.froala')}}",
  attribution: false, // to hide "Powered by Froala"
  heightMin: 132,
  buttonsVisible: 4,
  language: 'pt_br',
  placeholderText: '',
  imageUploadRemoteUrls: false,
  imageUploadURL: '{{url('/upload/froala/')}}',
  imageUploadParams: {
    id: 'my_editor',
    tipo:'avaliacao',
    user_id: '{{auth()->user()->id}}',
    _token: '{{ csrf_token() }}'
  },
  //imageEditButtons: ['wirisEditor', 'wirisChemistry','imageReplace', 'imageAlign', 'imageRemove', '|', 'imageLink', 'linkOpen', 'linkEdit', 'linkRemove', '-', 'imageDisplay', 'imageStyle', 'imageAlt', 'imageSize'],
  imageEditButtons: ['imageReplace', 'imageAlign', 'imageRemove', '|', 'imageLink', 'linkOpen', 'linkEdit', 'linkRemove', '-', 'imageDisplay', 'imageStyle', 'imageAlt', 'imageSize'],
  /// wiris

   toolbarButtons: {
          'moreText': {
            'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting']
          },
          'moreParagraph': {
            'buttons': ['alignLeft', 'alignCenter', 'formatOLSimple', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'paragraphStyle', 'lineHeight', 'outdent', 'indent', 'quote']
          },
          'moreRich': {
            'buttons': ['insertLink', 'insertImage', 'insertVideo', 'insertTable', 'emoticons', 'fontAwesome', 'specialCharacters', 'embedly', 'insertFile', 'insertHR']
          },
          'more':{
          	'buttons':['wirisEditor', 'wirisChemistry']
          },
          'moreMisc': {
            'buttons': ['undo', 'redo', 'fullscreen', 'print', 'getPDF', 'spellChecker', 'selectAll', 'html', 'help']
          }

        },

		  htmlAllowedTags:   ['.*'],
		  htmlAllowedAttrs: ['.*'],
};

    vetAlternativas = new Array;
    vetAlternativas[1] = 'A';
    vetAlternativas[2] = 'B';
    vetAlternativas[3] = 'C';
    vetAlternativas[4] = 'D';
    vetAlternativas[5] = 'E';
    vetAlternativas[6] = 'F';
    vetAlternativas[7] = 'G';

new FroalaEditor('.editorFroala',parametroFroala );
new FroalaEditor('#fr_1',parametroFroala );
/* instancia o script do select das Disciplinas */
    var selDisciplina = new SlimSelect({
        select: '.multiple1',
        placeholder: 'Buscar',
        searchPlaceholder: 'Buscar',
        closeOnSelect: true,
        allowDeselectOption: true,
        selectByGroup: true,
    });

    var selCicloEtapa = new SlimSelect({
        select: '.multiple2',
        placeholder: 'Buscar',
        searchPlaceholder: 'Buscar',
        closeOnSelect: true,
        allowDeselectOption: true,
        selectByGroup: true,
    });

    var selUnidade = new SlimSelect({
        select: '.multiple3',
        placeholder: 'Buscar',
        searchPlaceholder: 'Buscar',
        closeOnSelect: true,
        allowDeselectOption: true,
        selectByGroup: true,
    });

    var selBncc = new SlimSelect({
        select: '.multiple4',
        placeholder: 'Buscar',
        searchPlaceholder: 'Buscar',
        closeOnSelect: true,
        allowDeselectOption: true,
        selectByGroup: true,
    });
    var selFormato = new SlimSelect({
        select: '.multiple5',
        placeholder: 'Buscar',
        searchPlaceholder: 'Buscar',
        closeOnSelect: true,
        allowDeselectOption: true,
        selectByGroup: true,
    });
    var selSuporte = new SlimSelect({
        select: '.multiple6',
        placeholder: 'Buscar',
        searchPlaceholder: 'Buscar',
        closeOnSelect: true,
        allowDeselectOption: true,
        selectByGroup: true,
    });
    var selTema = new SlimSelect({
        select: '.multiple7',
        placeholder: 'Buscar',
        searchPlaceholder: 'Buscar',
        closeOnSelect: true,
        allowDeselectOption: true,
        selectByGroup: true,
    });
$(document).ready(function(){

    /********** OBJETIVAS ***********/
    $('.tipoquestao').change(function() {

        if($(this).val() == 'obj'){
            montaObjetiva();
        }else{
            montaDiscursiva();
        }
    });

    function montaObjetiva(selecionado = null) {
        $('#avaliacaoObjetiva').removeClass("d-none");
        $('#avaliacaoDiscursiva').addClass("d-none");
        montaQuestoes(selecionado);
    }

    function montaDiscursiva() {
        $('#avaliacaoDiscursiva').removeClass("d-none");
        $('#avaliacaoObjetiva').addClass("d-none");
        montaPreview();
    }

    $('#qtda_alternativa').change(function() {
    	qtd = parseInt($('#qtda_alternativa').val());
    	qtd = qtd || 0; /// se for NaN trasnforma para Zero
    	if(qtd<2)
    	{
    		$('#qtda_alternativa').val(2);
    	}
    	if(qtd>7)
    	{
    		$('#qtda_alternativa').val(7);
    	}
        montaQuestoes();
    });

    function montaQuestoes( selecionado = null) {
        var qtdaAlternativa = parseInt($('#qtda_alternativa').val());
        var previewHtml = '';
        var previewDesc = '';

        $('.alternativa').hide();

        $('#correta').html('<option value="">Selecione</option>');
        for (var i = 1; i <= qtdaAlternativa; i++) {
            $('#alternativa_'+i).show();
            sel = '';
            if(selecionado == i)
            {
                sel = 'selected';
            }
            $('#correta').append('<option '+sel+' value="'+i+'">'+vetAlternativas[i]+'</option>');

        }

    }

    $('#disponibilizarResposta').change(function() {
        if(this.checked) {
            $('#respotaQuestao').removeClass("d-none");
        }else{
            $('#respotaQuestao').addClass("d-none");
        }
    });


    /********** DISCURSIVAS ***********/

    var linhas='<hr>';

    /* Informa o tipo de linha e monta exemplo */
    $('.formatoQuestao').change(function() {
        if($(this).val() == '1'){
            linhas='<hr>';
        }else{
            linhas='<p>&nbsp;</p>';
        }

        montaPreview();
    });

    $('#qtda_discursiva_linhas').change(function() {
        montaPreview();
    });

    function montaPreview() {
        var qtdaLinhas = parseInt($('#qtda_discursiva_linhas').val());
        	qtdaLinhas = qtdaLinhas || 1; /// trasnforma NaN para 1
        	if(qtdaLinhas<1)
        		qtdaLinhas = 1;
        	$('#qtda_discursiva_linhas').val(qtdaLinhas)
        var previewHtml = '';
        var previewDesc = '';
        for (var i = 1; i <= qtdaLinhas; i++) {
            previewHtml += linhas;
        }

        if(linhas == '<hr>'){
            previewDesc = '*'+qtdaLinhas+' linha(s) - Caixa com Linhas';
        }else{
            previewDesc = '*'+qtdaLinhas+' linha(s) - Caixas em Branco';
        }


        $("#preview_discursiva").html(previewHtml);
        $("#preview_discursiva_desc").html(previewDesc);

    }



    @if( (old('tipo') == 'obj' || old('tipo') =='' ) && @$dados->tipo != 'd' )
    	sel = '';
    	@if(old('correta',@$dados->correta) != '')
    		sel = {{old('correta',@$dados->correta)}};
    	@endif
    	montaObjetiva(sel);
    @else
    	montaDiscursiva();
    @endif

    @if( (old('com_linhas') == 1 || old('com_linhas')=='') && @$dados->com_linhas!='0')
    	linhas='<hr>';
    @else
    	linhas='<p>&nbsp;</p>';
    @endif
    	montaPreview();

    @if(old('disciplina_id',@$dados->disciplina_id) != '' && old('cicloetapa_id',@$dados->cicloetapa_id) != '')
    	getAjaxUnidadeTematica({{old('disciplina_id',@$dados->disciplina_id)}}, {{old('cicloetapa_id',@$dados->cicloetapa_id)}}, '{{old('unidade_tematica',@$dados->unidade_tematica)}}');
    	getAjaxBncc({{old('disciplina_id',@$dados->disciplina_id)}}, {{old('cicloetapa_id',@$dados->cicloetapa_id)}}, '{{old('unidade_tematica',@$dados->unidade_tematica)}}', '{{old('bncc_id',@$dados->bncc_id)}}');
    @endif

    @if(old('disponibilizar_resolucao')==1 || @$dados->disponibilizar_resolucao==1)
    	$('#respotaQuestao').removeClass("d-none");
    @endif

    @if(old('tema_id',@$dados->tema_id)!='')
    	defineTema({{old('tema_id',@$dados->tema_id)}})
    @else
        defineTema();
    @endif
});
	function defineTema(sel = '')
    {
    	disciplina = $('#disciplina_id').val();
    	$.ajax({
            url: '{{url('/gestao/avaliacao/getTemaAjax')}}',
            type: 'post',
            dataType: 'json',
            data: {
                disciplina_id: disciplina,
                selecionado: sel,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                selTema.setData(data);
            },
            error: function(data) {
                swal("", "Erro ao carregar Unidade Tematica", "error");
            }
        });
    }

	function getUnidadeTematica()
    {
    	disciplina = $('#disciplina_id').val();
    	cicloetapa = $('#cicloetapa_id').val();
    	if(disciplina != '' && cicloetapa != '')
    	{
    		getAjaxUnidadeTematica(disciplina, cicloetapa, '');
    	}
    	else
    	{
    		selUnidade.setData([
					  {text: 'Não se aplica', value:''},
					]);
    	}
    	selBncc.setData([
					  {text: 'Não se aplica', value:''},
					]);
    }

	function getBncc()
    {
    	disciplina = $('#disciplina_id').val();
    	cicloetapa = $('#cicloetapa_id').val();
    	unidadeTematica = $('#unidade_tematica_id').val();
    	if(disciplina != '' && cicloetapa != '' && unidadeTematica != '')
    	{
    		getAjaxBncc(disciplina, cicloetapa, unidadeTematica, '');
    	}
    	else
    	{
    		selBncc.setData([
					  {text: 'Não se aplica', value:''},
					]);
    	}
    }

    function getAjaxUnidadeTematica(disciplina, cicloetapa, sel)
    {
    	$.ajax({
            url: '{{url('/gestao/avaliacao/getUnidadeTematicaAjax')}}',
            type: 'post',
            dataType: 'json',
            data: {
                disciplina_id: disciplina,
                cicloetapa_id: cicloetapa,
                selecionado: sel,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                selUnidade.setData(data);
            },
            error: function(data) {
                swal("", "Erro ao carregar Unidade Tematica", "error");
            }
        });
    }

    function getAjaxBncc(disciplina, cicloetapa, unidade, sel)
    {
    	$.ajax({
            url: '{{url('/gestao/avaliacao/getBnccAjax')}}',
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
                selBncc.setData(data);
            },
            error: function(data) {
                swal("", "Erro ao carregar Bncc", "error");
            }
        });
    }

    </script>

@stop
