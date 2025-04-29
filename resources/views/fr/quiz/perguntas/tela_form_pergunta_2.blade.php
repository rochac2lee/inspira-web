@extends('fr/master')
@section('content')
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/css/style_avaliacao_online_v2.css">
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
                    <br>@if ( strpos(Request::path(),'editar')===false )Nova pergunta de correlação de palavras @else Editar pergunta de correlação de palavras @endif
                </small>
            </h3>
            <form id="formFormularioPergunta2" action="@if ( strpos(Request::path(),'editar')===false ) {{url('/gestao/quiz/add_pergunta/tipo2')}} @else {{url('/gestao/quiz/editar_pergunta/tipo2')}} @endif" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="idPerguntaTipo2" value="{{@$dados->id}}">
                <input type="hidden" class="quizId" name="quiz_id" value="{{$quiz->id}}">
                <input type="hidden" id="tipo2" name="tipo2" value="2">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>* Título da pergunta</label>
                            <input type="text" name="titulo_tipo2" placeholder="" value="{{old('titulo_tipo2',@$dados->titulo)}}" class="form-control rounded {{ $errors->has('titulo_tipo2') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->first('titulo_tipo2') }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>* Pergunta</label>
                            <span id="spanSubtitulo2">
                                <textarea id="froalaSubtitulo2" name="sub_titulo_tipo2" class="form-control rounded {{ $errors->has('sub_titulo_tipo2') ? 'is-invalid' : '' }}">{{old('sub_titulo_tipo2',@$dados->sub_titulo)}}</textarea>
                            </span>
                            <div class="invalid-feedback" style="display: block">{{ $errors->first('sub_titulo_tipo2') }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Áudio da pergunta</label>

                            <div id="playerAudioTituloTipo2" @if(old('existeAudioTituloTipo2',@$dados->audio_titulo) =='') style="display: none" @endif>
                                <audio id="myAudio" src="@if(old('existeAudioTituloTipo2', @$dados->audio_titulo) != ''){{config('app.cdn')}}/storage/quiz/{{$quiz->id}}/pergunta/{{$dados->id}}/{{old('existeAudioTituloTipo2', @$dados->audio_titulo)}}@endif">
                                    Seu browser não suporta esse formato de áudio.
                                </audio>
                                <a href="javascript:void(0);" onclick="addAudioTituloTipo2('','','');" title="Excluir áudio"><i class="fas fa-trash-alt" style="color:red; font-weight: normal"></i></a>
                                <button type="button" style="margin-left: 15px" class="btn btn-secondary btn-small" onclick="playAudio(this)"><i class="fas fa-play" aria-hidden="true"></i></button>
                                <input id="existeAudioTituloTipo2" type="hidden" name="existeAudioTituloTipo2" value="{{old('existeAudioTituloTipo2',@$dados->audio_titulo)}}">
                            </div>
                            <div id="audioTituloTipo2" @if(old('existeAudioTituloTipo2',@$dados->audio_titulo) !='') style="display: none" @endif>
                                <div class="form-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                      <input type="radio" id="PerguntaTipoAudioArquivo2" name="tipoAudio" class="custom-control-input" value="arquivo" @if(old('tipoAudio')=='' || old('tipoAudio')=='arquivo') checked @endif >
                                      <label class="custom-control-label pt-1" for="PerguntaTipoAudioArquivo2" onclick ="mudaGravarAudioPergunta(2,'arquivo')">Enviar arquivo</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                      <input type="radio" id="PerguntaTipoAudioGravar2" name="tipoAudio" class=" custom-control-input" value="gravado" @if(old('tipoAudio')=='gravado') checked @endif>
                                      <label class="custom-control-label pt-1" for="PerguntaTipoAudioGravar2" onclick="mudaGravarAudioPergunta(2,'gravado')">Gravar meu áudio</label>
                                    </div>
                                </div>
                                <input id="enviaAudioPergunta2" type="file" accept=".mp3" name="audio_titulo_tipo2" class="form-control rounded {{ $errors->has('audio_titulo_tipo2') ? 'is-invalid' : '' }}">
                                <span id="gravaAudioPergunta2">
                                    <input id="enviaAudioPerguntaGravado2" type="hidden" name="audio_pergunta_gravado">
                                    <button type="button" class="btn btn-secondary btnGravar" onclick="startRecording(this,2)"><i class="fas fa-microphone" aria-hidden="true"></i> Gravar</button>
                                    <button type="button" class="btn btn-secondary btnParar" onclick="stopRecording(this,2)" disabled><i class="fas fa-stop" aria-hidden="true"></i> Parar</button>
                                    <button type="button" class="btn btn-secondary btnOuvir" onclick="$('#audioPergunta2')[0].play()" disabled><i class="fas fa-play" aria-hidden="true"></i> Ouvir</button>
                                    <audio id="audioPergunta2">Seu browser não suporta esse formato de áudio.</audio>
                                    <p class="mt-1"><b id="statusGravacao2" class="statusGravacao"></b></p>
                                </span>
                            </div>


                            <div class="invalid-feedback">{{ $errors->first('audio_titulo_tipo2') }}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label>* Imagem</label>
                            <div id="imagemCadastroTipo2" class=" form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center" @if(old('existeImgTipo2',@$dados->imagem) == '') style="display: none" @endif>
                                <input type="hidden" name="existeImgTipo2" id="existeImgTipo2" value="{{old('existeImgTipo2',@$dados->imagem)}}">
                                <img id="imgTipo2" width="328px" src="@if(old('existeImgTipo2', @$dados->imagem) != ''){{config('app.cdn')}}/storage/quiz/{{$quiz->id}}/pergunta/{{$dados->id}}/{{old('existeImgTipo2', @$dados->imagem)}}@endif">
                                <br>
                                <a class="btn btn-secondary" onclick="excluirImgTipo2()">Excluir Imagem</a>
                            </div>
                            <div id="novaImgTipo2" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center" @if(old('existeImgTipo2',@$dados->imagem) != '') style="display: none" @endif>
                                <input type="file" accept="image/*" name="imagem_tipo2" class="myCropper">
                            </div>
                            <div class="invalid-feedback @if($errors->first('imagem_tipo2'))d-block @endif">{{ $errors->first('imagem_tipo2') }}</div>

                        </div>
                    </div>
                    <div class="col-8">
                        <div class="row">

                            <div class="col-12">
                                <div class="form-group">
                                    <label style="width: 100%">* Palavras
                                        <!-- <small class="form-text w-100 text-muted">
                                        Colocar junto as palavras da <i>Frase correta</i>.
                                        </small> -->
                                        <p class="text-right link"><a href="javascript:void(0)" class="link_personalido" onclick="addPalavraTipo2()"><i class="fas fa-plus"></i> Adicionar palavra </a></p>

                                    </label>
                                    <div class="row" id="respostas" >
                                    @php
                                        $palavras =[];
                                        $listaTipo2= 3;
                                        if( is_array( old('resposta_tipo2')) || @$dados->respostas != null)
                                        {
                                            $listaTipo2 = count(old('resposta_tipo2',@$dados->respostas));
                                        }
                                    @endphp
                                    @for($i=0; $i<$listaTipo2; $i++)
                                        @php
                                            $palavras[$i] = old('resposta_tipo2.'.$i, @$dados->respostas[$i]->titulo);
                                        @endphp
                                        <div class="col-4">
                                            <div class="input-group input-group-sm" style="margin-bottom:10px" >
                                                <input type="text" id="palavra{{$i}}" value="{{old('resposta_tipo2.'.$i, @$dados->respostas[$i]->titulo)}}" name="resposta_tipo2[]" class="form-control rounded ">
                                                <div class="input-group-append operacaoTipo2">
                                                    <span class="input-group-text" id="inputGroup-sizing-sm">
                                                        <a href="javascript:void(0);" id="linkClick{{$i}}" title="Adicionar palavra na frase" onclick="addFraseTipo2(this,{{$i}})"><i class="fas fa-plus" style="color:green;"></i></a>
                                                    </span>
                                                    <span class="input-group-text" id="inputGroup-sizing-sm">
                                                        <a href="javascript:void(0);" title="Excluir palavra" onclick="excluirPalavraTipo2(this)"><i class="fas fa-trash-alt" style="color:red; font-weight: normal"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                    </div>

                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>* Frase correta
                                        <small class="form-text w-100 text-muted">
                                            Para excluir uma palavra da frase, clique nela.
                                        </small>
                                    </label>
                                    <div class="discursiva">
                                        <div id="discursivaRespostaTipo2" class="discursiva-resposta">

                                        </div>
                                        <div class="linhas"></div>
                                        <div class="linhas"></div>
                                    </div>
                                    <script>
                                        $(document).ready(function(){
                                        @if(is_array(old('frase_correta',@$dados->corretas)))
                                            @foreach(old('frase_correta',@$dados->corretas) as $r)

                                                @php
                                                    $key = array_search($r,old('resposta_tipo2',$palavras));
                                                @endphp
                                                 $('#linkClick{{$key}}').click();
                                            @endforeach
                                        @endif
                                            })
                                        </script>
                                    <div class="invalid-feedback @if($errors->first('frase_correta'))d-block @endif">{{ $errors->first('frase_correta') }}</div>

                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Ordem da frase embaralhada</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="aleatorizar_respostas_tipo2" @if(old('aleatorizar_respostas_tipo2', @$dados->aleatorizar_respostas) == 1) checked @endif name="aleatorizar_respostas_tipo2" value="1">
                                        <label class="custom-control-label pt-1" for="aleatorizar_respostas_tipo2">Aleatorizar alternativas?</label>
                                        <small class="form-text w-100 text-muted">
                                        Se ativado, deixa as palavras ordenadas de forma aleatória.
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Feedback quando incorreto</label>
                                    <input type="text" name="feedback_tipo2" placeholder="" value="{{old('feedback_tipo2', @$dados->feedback)}}" class="form-control rounded {{ $errors->has('feedback_tipo2') ? 'is-invalid' : '' }}">
                                    <div class="invalid-feedback">{{ $errors->first('feedback_tipo2') }}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Feedback quando correto</label>
                                    <input type="text" name="feedback_tipo2_correta" placeholder="" value="{{old('feedback_tipo2_correta', @$dados->feedback_correta)}}" class="form-control rounded {{ $errors->has('feedback_tipo2_correta') ? 'is-invalid' : '' }}">
                                    <div class="invalid-feedback">{{ $errors->first('feedback_tipo2_correta') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    <div class="d-none" id="modeloResposta">
        <div class="col-4 divModeloResposta">
            <div class="input-group input-group-sm" style="margin-bottom:10px" >
                <input id="palavraX" type="text"  value="conteudoPalavra" name="resposta_tipo2[]" class="form-control rounded ">
                <div class="input-group-append">
                    <span class="input-group-text" id="inputGroup-sizing-sm">
                        <a href="javascrip:void(0);" onclick="addFraseTipo2(this,palavraX)" title="Adicionar palavra na frase"><i class="fas fa-plus" style="color:green;"></i></a>
                    </span>
                    <span class="input-group-text" id="inputGroup-sizing-sm">
                        <a href="javascrip:void(0);" title="Excluir palavra" onclick="excluirPalavraTipo2(this)"><i class="fas fa-trash-alt" style="color:red; font-weight: normal"></i></a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="d-none" id="modeloFrase">
        <span class="divModeloResposta">
            <button type="button" class="btn btn-outline-secondary" onclick="excluirFraseTipo2(this,'contadorObjeto')">modeloPalavraFrase</button>
            <input type="hidden" name="frase_correta[]" value="modeloPalavraFrase" >
        </span>
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

    <script>

    var contGeralTipo2 = {{$listaTipo2+1}};

    $(document).ready(function(){
        new FroalaEditor('#froalaSubtitulo2', parametrosFroala);
        /* carrega o plugin de recortar imagem */
        $(".myCropper").slim(configuracao);
        mudaGravarAudioPergunta(2,'arquivo');
    });

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

    function excluirPalavraTipo2(elemento)
    {
        $(elemento).parent().parent().parent().parent().remove();
    }

    function addPalavraTipo2(palavra='')
    {
        elemento = $('#modeloResposta').html();
        elemento = elemento.replace('divModeloResposta','divResposta');
        elemento = elemento.replace('palavraX','palavra'+contGeralTipo2);
        elemento = elemento.replace('palavraX',contGeralTipo2);
        elemento = elemento.replace('conteudoPalavra',palavra);
        $('#respostas').append(elemento);
        contGeralTipo2++;
        return contGeralTipo2-1;
    }

    function addFraseTipo2(elemento, count)
    {
        input = $(elemento).parent().parent().prev();
        palavra = input.val();
        if(palavra!='')
        {
            input.prop('readonly',true);

            btn = $('#modeloFrase').html();
            btn = btn.replace('divModeloResposta','divResposta');
            btn = btn.replace('modeloPalavraFrase', palavra);
            btn = btn.replace('modeloPalavraFrase', palavra);
            btn = btn.replace('contadorObjeto', count);

            $('#discursivaRespostaTipo2').append(btn);
            $(elemento).parent().parent().hide();
        }
        else
        {
            alert('Preencha a palavra pra adicionar.');
        }

    }

    function excluirFraseTipo2(elemento, count)
    {
        $('#palavra'+count).attr('readonly',false);
        $('#palavra'+count).next().show();
        $(elemento).parent().remove();
    }

    function excluirImgTipo2()
    {
        $('#imagemCadastroTipo2').hide();
        $('#existeImgTipo2').val('');
        $('#novaImgTipo2').show();
    }

    function excluirAudioTituloTipo2()
    {
        $("#playerAudioTituloTipo2").hide();
        $("#playerAudioTituloTipo2").children().attr('src','');
        $("#audioTituloTipo2").show();
        $("#existeAudioTituloTipo2").val('');
    }



    function addAudioTituloTipo2(quizId,perguntaId,audio)
    {

        if(audio != '' && audio != null){
            var urlAudio = '{{config('app.cdn')}}/storage/quiz/'+quizId+'/pergunta/'+perguntaId+'/'+audio;
            $("#playerAudioTituloTipo2").removeClass('d-none');
            $("#playerAudioTituloTipo2").show();
            $("#playerAudioTituloTipo2").children().attr('src',urlAudio);
            $("#audioTituloTipo2").hide();
            $("#existeAudioTituloTipo2").val(audio);
        }
        else
        {
            $("#playerAudioTituloTipo2").hide();
            $("#audioTituloTipo2").show();
            $("#existeAudioTituloTipo2").val('');
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


</script>
@stop
