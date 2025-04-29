@extends('fr/master')
@section('content')
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.jquery.min.js"></script>
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.css">
    <!--  Exclusivo Froala Editor  -->

    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/froala_editor.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/froala_style.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/code_view.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/draggable.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/colors.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/emoticons.css">
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

    <!-- audio record -->
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/audio_recorder.js"></script>
    <!-- Fim audio record -->

    <script type="text/javascript">
        var parametrosFroala = {
            key: "{{config('app.froala')}}",
            attribution: false, // to hide "Powered by Froala"
            heightMin: 132,
            buttonsVisible: 4,
            placeholderText: '',
            language: 'pt_br',
            linkAlwaysBlank: true
        }

        /* configuracoes basicas do plugin de recortar imagem */
        var configuracao = {
            ratio: '3:3',
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

    </script>
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

    </style>
    <section class="section section-interna">
        <div class="container" >
            <h3 class="pb-3 border-bottom mb-4">
                <a href="{{url('/gestao/quiz/'.$quiz->id.'/perguntas')}}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                Pergunta do quiz "{{$quiz->titulo}}"
                <small>
                    <br>@if ( strpos(Request::path(),'editar')===false )Nova pergunta de correlação de imagens @else Editar pergunta de correlação de imagens @endif
                </small>
            </h3>


            <form id="formFormularioPergunta1" action="@if ( strpos(Request::path(),'editar')===false ) {{url('/gestao/quiz/add_pergunta/tipo1')}} @else {{url('/gestao/quiz/editar_pergunta/tipo1')}} @endif" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="idPerguntaTipo1" value="{{old('id',@$dados->id)}}">
                <input type="hidden" class="quizId" name="quiz_id" value="{{$quiz->id}}">
                <input type="hidden" name="tipo" value="1">

                <div class="row">

                    <div class="col-12">
                        <div class="form-group">
                            <label>* Título da pergunta</label>
                            <input type="text" name="titulo" placeholder="" value="{{old('titulo', @$dados->titulo)}}" class="form-control rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>* Pergunta</label>
                            <span id="spanSubtitulo1">
                                <textarea id="froalaSubtitulo1" name="sub_titulo" >{{old('sub_titulo',@$dados->sub_titulo)}}</textarea>
                            </span>
                            <div class="invalid-feedback" style="display: block">{{ $errors->first('sub_titulo') }}</div>
                        </div>
                    </div>
                    <div class="col-6">

                        <div class="form-group">
                            <label>Áudio da pergunta</label>
                            <div id="playerAudioPergunta" @if(old('existeAudioPergunta',@$dados->audio_titulo) == '') style="display: none" @endif>
                                <audio src=" @if(old('existeAudioPergunta',@$dados->audio_titulo) != ''){{config('app.cdn')}}/storage/quiz/{{$quiz->id}}/pergunta/{{$dados->id}}/{{$dados->audio_titulo}}@endif">
                                    Seu browser não suporta esse formato de áudio.
                                </audio>
                                <a href="javascript:void(0);" onclick="addAudioPergunta('','','')" title="Excluir áudio"><i class="fas fa-trash-alt" style="color:red; font-weight: normal"></i></a>
                                <button type="button" style="margin-left: 15px" class="btn btn-secondary btn-small" onclick="playAudio(this)"><i class="fas fa-play" aria-hidden="true"></i></button>
                                <input id="existeAudioPergunta" type="hidden" name="existeAudioPergunta" value="{{old('existeAudioPergunta',@$dados->audio_titulo)}}">
                            </div>
                            <div id="audioPergunta" @if(old('existeAudioPergunta',@$dados->audio_titulo) != '') style="display: none" @endif>
                                <div class="form-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                      <input type="radio" id="PerguntaTipoAudioArquivo1" name="tipoAudio" class="custom-control-input" value="arquivo" @if(old('tipoAudio')=='' || old('tipoAudio')=='arquivo') checked @endif >
                                      <label class="custom-control-label pt-1" for="PerguntaTipoAudioArquivo1" onclick ="mudaGravarAudioPergunta(1,'arquivo')">Enviar arquivo</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                      <input type="radio" id="PerguntaTipoAudioGravar1" name="tipoAudio" class=" custom-control-input" value="gravado" @if(old('tipoAudio')=='gravado') checked @endif>
                                      <label class="custom-control-label pt-1" for="PerguntaTipoAudioGravar1" onclick="mudaGravarAudioPergunta(1,'gravado')">Gravar meu áudio</label>
                                    </div>
                                </div>
                                <input id="enviaAudioPergunta1" type="file" accept=".mp3" name="audio_pergunta" class="form-control rounded {{ $errors->has('audio_titulo') ? 'is-invalid' : '' }}">
                                <span id="gravaAudioPergunta1">
                                    <input id="enviaAudioPerguntaGravado1" type="hidden" name="audio_pergunta_gravado">
                                    <button type="button" class="btn btn-secondary btnGravar" onclick="startRecording(this,1);"><i class="fas fa-microphone" aria-hidden="true"></i> Gravar</button>
                                    <button type="button" class="btn btn-secondary btnParar" onclick="stopRecording(this,1)" disabled><i class="fas fa-stop" aria-hidden="true"></i> Parar</button>
                                    <button type="button" class="btn btn-secondary btnOuvir" onclick="$('#audioPergunta1')[0].play()" disabled><i class="fas fa-play" aria-hidden="true"></i> Ouvir</button>
                                    <audio id="audioPergunta1">Seu browser não suporta esse formato de áudio.</audio>
                                    <p class="mt-1"><b id="statusGravacao1" class="statusGravacao"></b></p>
                                </span>
                            </div>
                            <div class="invalid-feedback">{{ $errors->first('audio_titulo') }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Ordem das alternativas</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitch2" @if(old('aleatorizar_respostas',@$dados->aleatorizar_respostas) == 1) checked @endif name="aleatorizar_respostas" value="1">
                                <label class="custom-control-label pt-1" for="customSwitch2">Aleatorizar alternativas?</label>
                                <small class="form-text w-100 text-muted">
                                Se ativado, deixa as alternativas da pergunta ordenadas de forma aleatória.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="divAlternativas">
                    <div class="col-9 mt-3 mb-4 border-bottom "><label class="font-weight-bold">Alternativas </label></div>
                    <div class="col-3 mt-3 mb-4 border-bottom text-right link"><a href="javascript:void(0)" class="link_personalido" onclick="addAlternativa()"><i class="fas fa-plus"></i> Adicionar Alternativa </a></div>
                    @php
                        $count = 2;
                        if(is_array(old('titulo_alternativa')) || @$dados->respostas!=''){
                            $count = count(old('titulo_alternativa',@$dados->respostas));
                        }
                    @endphp
                    @for($i=0; $i<$count; $i++)

                        <div class="col-3 border">
                            <p class="font-weight-bold text-center" style="color:#5b5959; margin-top: 5px;margin-bottom: 2px">Alternativa @if($i>=2)<a href="javascript:void(0);" onclick="$(this).parent().parent().remove(); numeroAlternativa--;"><i class="fas fa-trash-alt" style="color:red; font-weight: normal"></i></a> @endif</p>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="radio" class="custom-control-input correta{{$i}}" id="correta{{$i}}" @if( (old('correta') == $i && old('correta') != null) || @$dados->respostas[$i]->correta == 1) checked @endif name="correta" value="{{$i}}">
                                    <label class="custom-control-label pt-1" for="correta{{$i}}">Correta</label>
                                </div>
                                <div class="invalid-feedback @if($errors->first('correta'))d-block @endif">{{ $errors->first('correta') }}</div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="idResposta[]" id="idResposta{{$i}}" value="{{old('idResposta.'.$i, @$dados->respostas[$i]->id)}}">
                                <label>* Imagem da alternativa</label>
                                <div id="logoCadastro{{$i}}" class=" logoCadastro form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center" @if(old('existeImg.'.$i, @$dados->respostas[$i]->imagem) == '') style="display: none" @endif>
                                    <input type="hidden" name="existeImg[]" id="existeImg{{$i}}" value="{{old('existeImg.'.$i, @$dados->respostas[$i]->imagem)}}">
                                    <img id="imgLogo0" width="200px" src="@if(old('existeImg.'.$i, @$dados->respostas[$i]->imagem) != ''){{config('app.cdn')}}/storage/quiz/{{$quiz->id}}/pergunta/{{$dados->id}}/respostas/{{old('existeImg.'.$i, @$dados->respostas[$i]->imagem)}}@endif">
                                    <br>
                                    <a class="btn btn-secondary" onclick="excluirLogo({{$i}})">Excluir Imagem</a>
                                </div>
                                <div id="novaLogo{{$i}}" class="novaLogo form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center" @if(old('existeImg.'.$i, @$dados->respostas[$i]->imagem) != '') style="display: none" @endif>
                                    <input type="file" accept="image/*" name="imagem[]" class="myCropper">
                                </div>
                                <div class="invalid-feedback @if($errors->first('imagem.'.$i))d-block @endif">{{ $errors->first('imagem.0') }}</div>
                            </div>
                            <div class="form-group">
                                <label>Texto da alternativa</label>
                                <input type="text" name="titulo_alternativa[]" id="titulo_alternativa{{$i}}" placeholder="" value="{{old('titulo_alternativa.'.$i, @$dados->respostas[$i]->titulo)}}" class="form-control rounded {{ $errors->has('titulo_alternativa.0') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('titulo_alternativa.0') }}</div>
                            </div>
                            <div class="form-group">
                                <label>Áudio da alternativa</label>
                                <div id="playerAudioAlternativa{{$i}}" @if(old('existe_audio_alternativa.'.$i, @$dados->respostas[$i]->audio) == '') style="display: none" @endif>
                                    <audio src="@if(old('existe_audio_alternativa.'.$i, @$dados->respostas[$i]->audio) != ''){{config('app.cdn')}}/storage/quiz/{{$quiz->id}}/pergunta/{{$dados->id}}/respostas/{{$dados->respostas[$i]->audio}}@endif">
                                        Seu browser não suporta esse formato de áudio.
                                    </audio>
                                    <a href="javascript:void(0);" onclick="addAudioAlternativa('','','',{{$i}})" title="Excluir áudio"><i class="fas fa-trash-alt" style="color:red; font-weight: normal"></i></a>
                                    <button type="button" style="margin-left: 15px" class="btn btn-secondary btn-small" onclick="playAudio(this)"><i class="fas fa-play" aria-hidden="true"></i></button>
                                    <input id="existeAudioAlternativa{{$i}}" type="hidden" name="existe_audio_alternativa[]" value="{{old('existe_audio_alternativa.'.$i, @$dados->respostas[$i]->audio)}}">
                                </div>
                                <div id="audioAlternativa{{$i}}" @if(old('existe_audio_alternativa.'.$i, @$dados->respostas[$i]->audio) != '') style="display: none" @endif>
                                    <div class="form-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                          <input type="radio" id="AlternativaTipoAudioArquivo{{$i}}" name="tipoAudioAlternativa{{$i}}" class="custom-control-input" value="arquivo" @if(old('tipoAudioAlternativa'.$i)=='' || old('tipoAudioAlternativa'.$i)=='arquivo') checked @endif >
                                          <label class="custom-control-label pt-1" for="AlternativaTipoAudioArquivo{{$i}}" onclick ="mudaGravarAudioAlternativa({{$i}},'arquivo')">Enviar arquivo</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                          <input type="radio" id="AlternativaTipoAudioGravar{{$i}}" name="tipoAudioAlternativa{{$i}}" class=" custom-control-input" value="gravado" @if(old('tipoAudioAlternativa'.$i)=='gravado') checked @endif>
                                          <label class="custom-control-label pt-1" for="AlternativaTipoAudioGravar{{$i}}" onclick="mudaGravarAudioAlternativa({{$i}},'gravado')">Gravar meu áudio</label>
                                        </div>
                                    </div>
                                    <input id="enviaAudioAlternativa{{$i}}" type="file" accept=".mp3" name="audio_alternativa[]" class="form-control rounded {{ $errors->has('audio_titulo') ? 'is-invalid' : '' }}">
                                    <span id="gravaAudioAlternativa{{$i}}">
                                        <input id="enviaAudioAlternativaGravado{{$i}}" type="hidden" name="audio_alternativa_gravado[]">
                                        <button type="button" class="btn btn-secondary btn-sm " onclick="startRecordingAlternativa(this,{{$i}})"><i class="fas fa-microphone" aria-hidden="true"></i> Gravar</button>
                                        <button type="button" class="btn btn-secondary btn-sm " onclick="stopRecordingAlternativa(this,{{$i}})" disabled><i class="fas fa-stop" aria-hidden="true"></i> Parar</button>
                                        <button type="button" class="btn btn-secondary btn-sm " onclick="$('#audioGravado{{$i}}')[0].play()" disabled><i class="fas fa-play" aria-hidden="true"></i> Ouvir</button>
                                        <audio id="audioGravado{{$i}}">Seu browser não suporta esse formato de áudio.</audio>
                                        <p class="mt-1"><b id="statusGravacaoAlternativa{{$i}}" class="statusGravacao"></b></p>
                                    </span>
                                </div>
                                <div class="invalid-feedback">{{ $errors->first('audio_alternativa.0') }}</div>
                            </div>
                            <div class="form-group">
                                <label>Feedback da alternativa</label>
                                <input type="text" name="feedback[]" id="feedback{{$i}}" value="{{old('feedback.'.$i, @$dados->respostas[$i]->feedback)}}" class="form-control rounded {{ $errors->has('feedback.'.$i) ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('feedback.'.$i) }}</div>
                            </div>
                        </div>
                    @endfor
                </div>
                <div class="row ">
                    <div class="col-12 text-center mt-3 mb-5">
                        <a href="{{url('gestao/quiz/'.$quiz->id.'/perguntas')}}" class="btn btn-secondary">Cancelar</a>
                        <button id="btnFormPergunta4" type="submit" class="btn btn-success">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

<div id="modeloAlternativa" style="display: none;">
	<div class="col-3 border alternativaInativa">
		<input type="hidden" name="pergunta_dinamica[]" value ="1">
		<p class="font-weight-bold text-center" style="color:#5b5959; margin-top: 5px;margin-bottom: 2px">Alternativa <a href="javascript:void(0);" onclick="$(this).parent().parent().remove(); numeroAlternativa--;"><i class="fas fa-trash-alt" style="color:red; font-weight: normal"></i></a></p>
		<div class="form-group">
			<div class="custom-control custom-switch">
                <input type="radio" class="custom-control-input calsseModeloCorreta" id="modeloCorreta" name="correta" value="valorCorreta">
                <label class="custom-control-label pt-1" for="modeloCorreta" >Correta</label>
            </div>
		</div>
		<div class="form-group">
			<label>* Imagem da alternativa</label>
            <input type="hidden" name="idResposta[]" id="idRespostaX" value="">
            <div id="logoCadastroX" class="d-none logoCadastro form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                <input type="hidden" name="existeImg[]" id="existeImgX" value="">
                <img id="imgLogoX" width="200px" src="">
                <br>
                <a class="btn btn-secondary" onclick="excluirLogo(excluirLogoX)">Excluir Imagem</a>
            </div>
        	<div id="novaLogoX" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
    			<input type="file" accept="image/*" name="imagem[]" class="cropperDinamico">
			</div>
    	</div>
		<div class="form-group">
			<label>Texto da alternativa </label>
			<input type="text" id="modeloTituloAlternativa" name="titulo_alternativa[]" placeholder="" value="" class="form-control rounded ">
		</div>
		<div class="form-group">
			<label>Áudio da alternativa </label>
            <div id="playerAudioAlternativaXXX" class="d-none">
                <audio>
                    Seu browser não suporta esse formato de áudio.
                </audio>
                <a href="javascript:void(0);" onclick="addAudioAlternativa('','','',excluirAudioXXX)" title="Excluir áudio"><i class="fas fa-trash-alt" style="color:red; font-weight: normal"></i></a>
                <button type="button" style="margin-left: 15px" class="btn btn-secondary btn-small" onclick="playAudio(this)"><i class="fas fa-play" aria-hidden="true"></i></button>
                <input id="existeAudioAlternativaXXX" type="hidden" name="existe_audio_alternativa[]" value="">
            </div>
			<div id="audioAlternativaXXX">
                <div class="form-group">
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="AlternativaTipoAudioArquivoXXX" name="tipoAudioAlternativaXXX" class="custom-control-input" value="arquivo" checked >
                      <label class="custom-control-label pt-1" for="AlternativaTipoAudioArquivoXXX" onclick ="mudaGravarAudioAlternativa(XXX,'arquivo')">Enviar arquivo</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="AlternativaTipoAudioGravarXXX" name="tipoAudioAlternativaXXX" class=" custom-control-input" value="gravado">
                      <label class="custom-control-label pt-1" for="AlternativaTipoAudioGravarXXX" onclick="mudaGravarAudioAlternativa(XXX,'gravado')">Gravar meu áudio</label>
                    </div>
                </div>
                <input id="enviaAudioAlternativaXXX" type="file" name="audio_alternativa[]" accept=".mp3" class="form-control rounded">
                <span id="gravaAudioAlternativaXXX" style="display: none;">
                    <input id="enviaAudioAlternativaGravadoXXX" type="hidden" name="audio_alternativa_gravado[]">
                    <button type="button" class="btn btn-secondary btn-sm " onclick="startRecordingAlternativa(this,XXX)"><i class="fas fa-microphone" aria-hidden="true"></i> Gravar</button>
                    <button type="button" class="btn btn-secondary btn-sm " onclick="stopRecordingAlternativa(this,XXX)" disabled><i class="fas fa-stop" aria-hidden="true"></i> Parar</button>
                    <button type="button" class="btn btn-secondary btn-sm " onclick="$('#audioGravadoXXX')[0].play()" disabled><i class="fas fa-play" aria-hidden="true"></i> Ouvir</button>
                    <audio id="audioGravadoXXX">Seu browser não suporta esse formato de áudio.</audio>
                    <p class="mt-1"><b id="statusGravacaoAlternativaXXX" class="statusGravacao"></b></p>
                </span>
            </div>
		</div>
        <div class="form-group">
            <label>Feedback da alternativa</label>
            <input type="text" id="modeloFeedback" name="feedback[]" class="form-control rounded">
        </div>
	</div>
</div>
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

    <script type="text/javascript">

	var numeroAlternativa={{$i}};
	var countGeral={{$i}};

    $(document).ready(function(){
        new FroalaEditor('#froalaSubtitulo1', parametrosFroala);
        /* carrega o plugin de recortar imagem */
        $(".myCropper").slim(configuracao);
        mudaGravarAudioPergunta(1,'arquivo')
        for(i=0; i<numeroAlternativa; i++){
            mudaGravarAudioAlternativa(i,'arquivo');
        }
    });


    function excluirLogo(i)
    {
        $('#novaLogo'+i).show();
        $('#logoCadastro'+i).hide();
        $('#existeImg'+i).val('');
    }
    function addAlternativa()
    {
    	if(numeroAlternativa<8)
    	{
    		numeroAlternativa++;
    		countGeral++;
    		alternativa = $('#modeloAlternativa').html();
    		alternativa = alternativa.replace('alternativaInativa','alternativaAtiva');
            alternativa = alternativa.replace('modeloCorreta','correta'+countGeral);
            alternativa = alternativa.replace('modeloCorreta','correta'+countGeral);
    		alternativa = alternativa.replace('calsseModeloCorreta','correta'+(numeroAlternativa-1));

            alternativa = alternativa.replace('cropperDinamico','cropperDinamico'+countGeral);
            alternativa = alternativa.replace('valorCorreta',(numeroAlternativa-1));
            alternativa = alternativa.replace('modeloTituloAlternativa','titulo_alternativa'+(numeroAlternativa-1));
            alternativa = alternativa.replace('modeloFeedback','feedback'+(numeroAlternativa-1));
            alternativa = alternativa.replace('logoCadastroX','logoCadastro'+(numeroAlternativa-1));
            alternativa = alternativa.replace('imgLogoX','imgLogo'+(numeroAlternativa-1));
            alternativa = alternativa.replace('excluirLogoX',(numeroAlternativa-1));
            alternativa = alternativa.replace('novaLogoX','novaLogo'+(numeroAlternativa-1));
            alternativa = alternativa.replace('existeImgX','existeImg'+(numeroAlternativa-1));
            alternativa = alternativa.replace('playerAudioAlternativaXXX','playerAudioAlternativa'+(numeroAlternativa-1));
            alternativa = alternativa.replace('existeAudioAlternativaXXX','existeAudioAlternativa'+(numeroAlternativa-1));
            alternativa = alternativa.replace('audioAlternativaXXX','audioAlternativa'+(numeroAlternativa-1));
            alternativa = alternativa.replace('excluirAudioXXX',(numeroAlternativa-1));
            alternativa = alternativa.replace('idRespostaX','idResposta'+(numeroAlternativa-1));

            for (k=0; k<20; k++)
            {
                alternativa = alternativa.replace('XXX',(numeroAlternativa-1));
            }

    		$('#divAlternativas').append(alternativa);
			$(".cropperDinamico"+countGeral).slim(configuracao);
    	}
    	else
    	{
    		swal("", "Número de alternativas está no máximo.", "error");
    	}

    }





    function addAudioPergunta(audio,quizId,perguntaId)
    {

        if(audio!=''&& audio != null)
        {
            var urlAudio = '{{config('app.cdn')}}/storage/quiz/'+quizId+'/pergunta/'+perguntaId+'/'+audio;
            $("#playerAudioPergunta").removeClass('d-none');
            $("#playerAudioPergunta").show();
            $("#playerAudioPergunta").children().attr('src',urlAudio);
            $("#audioPergunta").hide();
            $("#existeAudioPergunta").val(audio);
        }
        else
        {
            $("#playerAudioPergunta").hide();
            $("#audioPergunta").show();
            $("#existeAudioPergunta").val('');
        }
    }

    function addAudioAlternativa(audio,quizId,perguntaId,i)
    {
        if(audio!='' && audio != null)
        {
            var urlAudio = '{{config('app.cdn')}}/storage/quiz/'+quizId+'/pergunta/'+perguntaId+'/respostas/'+audio;
            $("#playerAudioAlternativa"+i).removeClass('d-none');
            $("#playerAudioAlternativa"+i).show();
            $("#playerAudioAlternativa"+i).children().attr('src',urlAudio);
            $("#audioAlternativa"+i).hide();
            $("#existeAudioAlternativa"+i).val(audio);
        }
        else
        {
            $("#playerAudioAlternativa"+i).hide();
            $("#audioAlternativa"+i).show();
            $("#existeAudioAlternativa"+i).val('');
            mudaGravarAudioAlternativa(i,'arquivo');
        }
    }

    function mudaGravarAudioPergunta(n,tipo)
    {
        if (tipo == 'gravado')
        {
            $('#enviaAudioPergunta'+n).hide()
            $('#gravaAudioPergunta'+n).show()
        }
        else
        {
            $('#enviaAudioPergunta'+n).show()
            $('#gravaAudioPergunta'+n).hide()
        }
    }

    function mudaGravarAudioAlternativa(n,tipo)
    {
        if (tipo == 'gravado')
        {
            $('#enviaAudioAlternativa'+n).hide()
            $('#gravaAudioAlternativa'+n).show()
        }
        else
        {
            $('#enviaAudioAlternativa'+n).show()
            $('#gravaAudioAlternativa'+n).hide()
        }
    }

    // GRAVAR AUDIO

    URL = window.URL || window.webkitURL;

    var gumStream;
    var rec;
    var input;
    var pergutaGravacao;
    var alternativaGravacao;

    var AudioContext = window.AudioContext || window.webkitAudioContext;
    var audioContext
    function playAudio(elemento)
    {
        p = $(elemento).prev().prev();

        if (p[0].paused || p[0].ended) {
            p[0].play();
        }
        else{
            p[0].pause();
        }

        //$(elemento).children().toggleClass("fa fa-play fa fa-pause");
    }

        function startRecordingAlternativa(elemento,n) {
            $(elemento).next().prop('disabled',false);
            $(elemento).prop('disabled',true);
            $(elemento).next().next().prop('disabled',false);

            var constraints = { audio: true, video:false }

            navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {

                audioContext = new AudioContext();

                gumStream = stream;

                input = audioContext.createMediaStreamSource(stream);

                rec = new Recorder(input,{numChannels:1})

                rec.record()
                $('#statusGravacaoAlternativa'+n).html('Gravando ...');

            }).catch(function(err) {
                $(elemento).next().prop('disabled',true);
                $(elemento).prop('disabled',false);

            });
        }

    function startRecording(elemento,n) {
        $(elemento).next().prop('disabled',false);
        $(elemento).prop('disabled',true);
        $(elemento).next().next().prop('disabled',false);

        var constraints = { audio: true, video:false }

        navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {

            audioContext = new AudioContext();

            gumStream = stream;

            input = audioContext.createMediaStreamSource(stream);

            rec = new Recorder(input,{numChannels:1})

            rec.record()
            $('#statusGravacao'+n).html('Gravando ...');

        }).catch(function(err) {
            $(elemento).next().prop('disabled',true);
            $(elemento).prop('disabled',false);

        });
    }

    function stopRecordingGeral(n) {
        try {
            $('#statusGravacao'+n).html('');
            $('#enviaAudioPerguntaGravado'+n).val('');
            $('.btnGravar').prop('disabled',false);
            $('.btnParar').prop('disabled',true);
            $('.btnOuvir').prop('disabled',true);
            rec.stop();

            gumStream.getAudioTracks()[0].stop();

            rec.exportWAV(createDownloadLink);
        }
        catch (e) {

        }
    }

    function stopRecording(elemento,n) {
        $(elemento).prop('disabled',true);
        $(elemento).next().prop('disabled',false);
        $(elemento).prev().prop('disabled',false);
        pergutaGravacao = n;

        rec.stop();

        $('#statusGravacao'+n).html('Áudio gravado.');

        gumStream.getAudioTracks()[0].stop();

        rec.exportWAV(createDownloadLink);
    }

    function createDownloadLink(blob) {
        var url = URL.createObjectURL(blob);

        var filename = new Date().toISOString();

        $('#audioPergunta'+pergutaGravacao).prop('src',url);

        var fd=new FormData();
        fd.append("audio",blob, filename);
        fd.append("_token",'{{csrf_token()}}');

        var xhr=new XMLHttpRequest();
        xhr.onload=function(e) {
            if(this.readyState === 4) {
                $('#enviaAudioPerguntaGravado'+pergutaGravacao).val(JSON.parse(e.target.responseText));
            }
        };
        xhr.open("POST","{{url('/gestao/quiz/gravarAudioTemporario')}}",true);
        xhr.send(fd);
    }
        function stopRecordingAlternativa(elemento,n) {
            $(elemento).prop('disabled',true);
            $(elemento).next().prop('disabled',false);
            $(elemento).prev().prop('disabled',false);
            alternativaGravacao = n;

            rec.stop();

            $('#statusGravacaoAlternativa'+n).html('Áudio gravado.');

            gumStream.getAudioTracks()[0].stop();

            rec.exportWAV(createDownloadLinkAlternativa);
        }

        function createDownloadLinkAlternativa(blob) {
            var url = URL.createObjectURL(blob);

            var filename = new Date().toISOString();

            $('#audioGravado'+alternativaGravacao).prop('src',url);

            var fd=new FormData();
            fd.append("audio",blob, filename);
            fd.append("_token",'{{csrf_token()}}');

            var xhr=new XMLHttpRequest();
                  xhr.onload=function(e) {
                      if(this.readyState === 4) {
                          $('#enviaAudioAlternativaGravado'+alternativaGravacao).val(JSON.parse(e.target.responseText));
                      }
                  };
                  xhr.open("POST","{{url('/gestao/quiz/gravarAudioTemporario')}}",true);
                  xhr.send(fd);
        }

</script>
@stop
