@extends('fr/master')
@section('content')
<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/css/style_avaliacao_online_v2.css">
	<script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.jquery.min.js"></script>
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.css">
	<script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet"></link>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/formUtilities.js"></script>
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery/jquery-ui.js"></script>

    <!--  Exclusivo Froala Editor  -->
    <!--<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/font-awesome.min.css">-->
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
    .link_personalido:link{
        color:#f8a502;
        text-decoration: underline;
    }
    .link_personalido:visited{
        color:#f8a502;
        text-decoration: underline;
    }
    .link_personalido:hover{
        color:#f8a502;
        text-decoration: underline;
    }
    .link_personalido:active{
        color:#f8a502; text-decoration: underline;
    }

    .link_excluirAudio:link{
        color:red;
        text-decoration: underline;
    }
    .link_excluirAudio:visited{
        color:red;
        text-decoration: underline;
    }
    .link_excluirAudio:hover{
        color:red;
        text-decoration: underline;
    }
    .link_excluirAudio:active{
        color:red; text-decoration: underline;
    }

    .letraPergunta4{
        border: 1px solid #cccccc;
        border-radius: 100%;
        width: 40px;
        height: 40px;
        text-align: center;
        font-size: 30px;
    }

</style>


<section class="section section-interna">
	<div class="container" >
        <h3 class="pb-3 border-bottom mb-4">
            <a href="{{url('/gestao/quiz/'.$quiz->id.'/perguntas')}}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            Pergunta do quiz "{{$quiz->titulo}}"
            <small>
                <br>
                @if ( strpos(Request::path(),'editar')===false )Nova pergunta de múltipla escolha @else Editar pergunta de múltipla escolha @endif
            </small>
        </h3>
				<form id="formFormularioPergunta4" action="" method="post" enctype="multipart/form-data">
					@csrf
	                <input type="hidden" name="id" id="idPerguntaTipo4" value="{{old('id')}}">
	                <input type="hidden" class="quizId" name="quiz_id" value="{{$quiz->id}}">
	                <input type="hidden" id="tipo4" name="tipo4" value="4">
					<div id="avaliacaoObjetiva">
                        <div class="form-group">
                            <label >* Título da pergunta</label>
                            <input type="text" class="form-control rounded {{ $errors->has('titulo_tipo4') ? 'is-invalid' : '' }}" name="titulo_tipo4" value="{{old('titulo_tipo4')}}">
                            <div class="invalid-feedback">{{ $errors->first('titulo_tipo4') }}</div>

                        </div>
                        <div class="form-group">
                            <label >* Pergunta</label>
                            <div id="editor1">
                                <textarea id='edit1' name="sub_titulo_tipo4">{{old('sub_titulo_tipo4')}}</textarea>
                            </div>
                            <div class="invalid-feedback @if($errors->first('sub_titulo_tipo4'))d-block @endif">{{ $errors->first('sub_titulo_tipo4') }}</div>
                        </div>
                        <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label >* Quantidade de Alternativas</label>
                                <div class="input-group input-group-sm mb-3">
                                    <input type="number" name="qtda_alternativa" id="qtda_alternativa" value="@if(old('qtda_alternativa')<1){{'4'}}@else{{old('qtda_alternativa')}}@endif" class="form-control form-control-sm {{ $errors->has('qtda_alternativa') ? 'is-invalid' : '' }}" placeholder="Número" min="2" max="7" onchange="montaQuestoes();">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">Alternativas</span>
                                    </div>
                                    <small class="form-text w-100 text-muted">
                                        Define a quantidade de alternativas para a questão.
                                    </small>
                                    <div class="invalid-feedback">{{ $errors->first('qtda_alternativa.0') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label >Alternativas embaralhadas</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="aleatorizar_respostas_tipo4" @if(old('aleatorizar_respostas_tipo4') == 1) checked @endif name="aleatorizar_respostas_tipo4" value="1">
                                    <label class="custom-control-label pt-1" for="aleatorizar_respostas_tipo4">Aleatorizar alternativas?</label>
                                    <small class="form-text w-100 text-muted">
                                    Se ativado, deixa as alternativas ordenadas de forma aleatória.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="form-group alternativa" id="alternativa_1">
                            <label >* Alternativas</label>
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="letraPergunta4">A</div>
                                </div>
                                <div class="col-md-11">
                                    <div id="editor2">
                                        <textarea id='edit2' name="resposta_tipo4[]">{{old('resposta_tipo4.0')}}</textarea>
                                    </div>
                                    <div class="invalid-feedback ">{{ $errors->first('resposta_tipo4.0') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group alternativa" id="alternativa_2">
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="letraPergunta4">B</div>
                                </div>
                                <div class="col-md-11">
                                    <div id="editor3">
                                        <textarea id='edit3' name="resposta_tipo4[]">{{old('resposta_tipo4.1')}}</textarea>
                                    </div>
                                    <div class="invalid-feedback">{{ $errors->first('resposta_tipo4.1') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group alternativa" id="alternativa_3">
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="letraPergunta4">C</div>
                                </div>
                                <div class="col-md-11">
                                    <div id="editor4">
                                        <textarea id='edit4' name="resposta_tipo4[]">{{old('resposta_tipo4.2')}}</textarea>
                                    </div>
                                    <div class="invalid-feedback">{{ $errors->first('resposta_tipo4.2') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group alternativa" id="alternativa_4">
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="letraPergunta4">D</div>
                                </div>
                                <div class="col-md-11">
                                    <div id="editor5">
                                        <textarea id='edit5' name="resposta_tipo4[]">{{old('resposta_tipo4.3')}}</textarea>
                                    </div>
                                    <div class="invalid-feedback">{{ $errors->first('resposta_tipo4.3') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group alternativa" id="alternativa_5">
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="letraPergunta4">E</div>
                                </div>
                                <div class="col-md-11">
                                    <div id="editor6">
                                        <textarea id='edit6' name="resposta_tipo4[]">{{old('resposta_tipo4.4')}}</textarea>
                                    </div>
                                    <div class="invalid-feedback">{{ $errors->first('resposta_tipo4.4') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group alternativa" id="alternativa_6">
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="letraPergunta4">F</div>
                                </div>
                                <div class="col-md-11">
                                    <div id="editor7">
                                        <textarea id='edit7' name="resposta_tipo4[]">{{old('resposta_tipo4.5')}}</textarea>
                                    </div>
                                    <div class="invalid-feedback">{{ $errors->first('resposta_tipo4.5') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group alternativa" id="alternativa_7">
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="letraPergunta4">G</div>
                                </div>
                                <div class="col-md-11">
                                    <div id="editor8">
                                        <textarea id='edit8' name="resposta_tipo4[]">{{old('resposta_tipo4.6')}}</textarea>
                                    </div>
                                    <div class="invalid-feedback">{{ $errors->first('resposta_tipo4.6') }}</div>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label >* Alternativa Correta</label>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">A alternativa</span>
                                </div>
                                <select class="form-control form-control-sm {{ $errors->has('corretaTipo4') ? 'is-invalid' : '' }}" id="alternativasCorretas4" name="corretaTipo4">
                                    <option value="">Selecione</option>
                                    <option value="1" @if(old('corretaTipo4')==1) selected @endif>A</option>
                                    <option value="2" @if(old('corretaTipo4')==2) selected @endif>B</option>
                                    <option value="3" @if(old('corretaTipo4')==3) selected @endif>C</option>
                                    <option value="4" @if(old('corretaTipo4')==4) selected @endif>D</option>
                                    <option value="5" @if(old('corretaTipo4')==5) selected @endif>E</option>
                                    <option value="6" @if(old('corretaTipo4')==6) selected @endif>F</option>
                                    <option value="7" @if(old('corretaTipo4')==7) selected @endif>G</option>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">é a correta</span>
                                </div>
                                <small class="form-text w-100 text-muted">
                                    Define qual é a alternativa correta.
                                </small>
                                <div class="invalid-feedback">{{ $errors->first('corretaTipo4') }}</div>

                            </div>
                        </div>
                        <div class="form-group">
                            <label >Feedback quando incorreto</label>
                            <input type="text" name="feedback_tipo4" placeholder="" value="{{old('feedback_tipo4')}}" class="form-control rounded {{ $errors->has('feedback_tipo4') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->first('feedback_tipo4') }}</div>
                        </div>
                        <div class="form-group">
                            <label >Feedback quando correto</label>
                            <input type="text" name="feedback_tipo4_correta" placeholder="" value="{{old('feedback_tipo4_correta')}}" class="form-control rounded {{ $errors->has('feedback_tipo4_correta') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->first('feedback_tipo4_correta') }}</div>
                        </div>
                        <div class="form-group text-center">
                        	<a href="{{url('gestao/quiz/'.$quiz->id.'/perguntas')}}" class="btn btn-secondary">Cancelar</a>
							<button id="btnFormPergunta4" type="button" class="btn btn-success" onclick="enviaFormularioTipo4()">Salvar</button>
                        </div>
                    </div>
				</form>
	</div>
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
<script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/special_characters.min.js"></script>

  <!-- Codigo para adicionar a matemática-->
  <script type="text/javascript" src="{{url('/fr/includes/froala_editor_v4/node_modules/wiris/mathtype-froala3/wiris.js')}}"></script>

  <!-- Codigo para mostrar o resultado --->
  <script type="text/javascript" src="{{url('/fr/includes/froala_editor_v4/js/plugins/froala_wiris/integration/WIRISplugins.js?viewer=image')}}"></script>

<script>
    var tipoOperacao = '';
    var tipoPergunta = 4;
    @if($idPergunta != '')
        var idPergunta = {{$idPergunta}};
    @else
        var idPergunta = '';
    @endif

    @if ( strpos(Request::path(),'editar')===false )
    	tipoOperacao = 'add';
    @else
    	tipoOperacao = 'editar';
    @endif

    var parametrosFroala = {
          key: "{{config('app.froala')}}",
          attribution: false, // to hide "Powered by Froala"
          heightMin: 132,
          buttonsVisible: 4,
          placeholderText: '',
          language: 'pt_br',
          linkAlwaysBlank: true,
          imageUploadURL: '{{url('/upload/froala/')}}',

          imageUploadParams: {
            id: 'my_editor',
            tipo:'quiz',
            quiz_id: '{{$quiz->id}}',
            _token: '{{ csrf_token() }}'
          },
          imageEditButtons: ['wirisEditor', 'wirisChemistry','imageReplace', 'imageAlign', 'imageRemove', '|', 'imageLink', 'linkOpen', 'linkEdit', 'linkRemove', '-', 'imageDisplay', 'imageStyle', 'imageAlt', 'imageSize'],
          //imageEditButtons: ['imageReplace', 'imageAlign', 'imageRemove', '|', 'imageLink', 'linkOpen', 'linkEdit', 'linkRemove', '-', 'imageDisplay', 'imageStyle', 'imageAlt', 'imageSize'],

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
    }

    function ativarFroala(){
        new FroalaEditor('#edit1, #edit2, #edit3, #edit4, #edit5, #edit6, #edit7, #edit8', parametrosFroala);
    }

    function limpaFroala()
    {
        $('#editor1').html('<textarea id="edit1" name="sub_titulo_tipo4"></textarea>')
        for(i=2; i<=8; i++)
        {
           $('#editor'+i).html('<textarea id="edit'+i+'" name="resposta_tipo4[]"></textarea>')
        }
    }

    vetAlternativas = new Array;
    vetAlternativas[1] = 'A';
    vetAlternativas[2] = 'B';
    vetAlternativas[3] = 'C';
    vetAlternativas[4] = 'D';
    vetAlternativas[5] = 'E';
    vetAlternativas[6] = 'F';
    vetAlternativas[7] = 'G';

    $(document).ready(function(){
        montaQuestoes(-1);
        ativarFroala();

        if(tipoOperacao== 'editar')
        {
            $.ajax({
                url: '{{url('/gestao/quiz/pergunta/tipo4/getAjax/')}}',
                type: 'post',
                dataType: 'json',
                data: {
                    id: idPergunta,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    correta = -1;
                    limpaFroala();
                    popularForm($('#formFormularioPergunta4'), data);
                    $('#edit1').html(data.sub_titulo_tipo4);
                    for(i=0; i<data.respostas.length; i++)
                    {
                        $('#edit'+(i+2)).html(data.respostas[i].titulo);
                        if(data.respostas[i].correta == '1')
                        {
                            correta = i+1;
                        }
                    }
                    $('#qtda_alternativa').val(data.respostas.length);

                    setTimeout(function () {
                            ativarFroala();
                            montaQuestoes(correta);
                    }, 200);

                },
                error: function(data) {
                    swal("", "Pergunta não encontrada", "error");
                }
            });

        }

    });

    function montaQuestoes(selecionado) {
        var qtdaAlternativa = parseInt($('#qtda_alternativa').val());
        if(qtdaAlternativa<2)
        {
            qtdaAlternativa = 2;
            $('#qtda_alternativa').val(2);
        }

        if(qtdaAlternativa>7)
        {
            qtdaAlternativa = 7;
            $('#qtda_alternativa').val(7);
        }
        var previewHtml = '';
        var previewDesc = '';

        $('.alternativa').hide();
        $('#alternativasCorretas4').html('<option value="">Selecione</option>');

        for (var i = 1; i <= qtdaAlternativa; i++) {
            sel = '';
            if(selecionado == i)
            {
                sel = 'selected';
            }
            $('#alternativa_'+i).show();
            $('#alternativasCorretas4').append('<option '+sel+' value="'+i+'">'+vetAlternativas[i]+'</option>');
        }

    }


    function enviaFormularioTipo4()
    {
        if(tipoOperacao == 'add')
        {
            $('#formFormularioPergunta4').attr('action','{{url('/gestao/quiz/add_pergunta/tipo4')}}');
            $('#formFormularioPergunta4').submit();
        }
        else if(tipoOperacao == 'editar')
        {
            $('#formFormularioPergunta4').attr('action','{{url('/gestao/quiz/editar_pergunta/tipo4')}}');
            $('#formFormularioPergunta4').submit();
        }
    }

</script>
@stop
