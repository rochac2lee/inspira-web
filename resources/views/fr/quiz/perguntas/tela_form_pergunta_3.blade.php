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
                    <br>@if ( strpos(Request::path(),'editar')===false )Nova questão aberta @else Editar questão aberta @endif
                </small>
            </h3>
            <form id="formFormularioPergunta3" action="@if ( strpos(Request::path(),'editar')===false ) {{url('/gestao/quiz/add_pergunta/tipo3')}} @else {{url('/gestao/quiz/editar_pergunta/tipo3')}} @endif" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="idPerguntaTipo3" value="{{@$dados->id}}">
                <input type="hidden" class="quizId" name="quiz_id" value="{{$quiz->id}}">
                <input type="hidden" id="tipo3" name="tipo3" value="3">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>* Título da pergunta</label>
                            <input type="text" name="titulo_tipo3" placeholder="" value="{{old('titulo_tipo3', @$dados->titulo)}}" class="form-control rounded {{ $errors->has('titulo_tipo3') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->first('titulo_tipo3') }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>* Pergunta</label>
                            <span id="spanSubtitulo3">
                                <textarea id="froalaSubtitulo3" name="sub_titulo_tipo3" class="form-control rounded {{ $errors->has('sub_titulo_tipo3') ? 'is-invalid' : '' }}">{{old('sub_titulo_tipo3', @$dados->sub_titulo)}}</textarea>
                            </span>
                            <div class="invalid-feedback" style="display: block">{{ $errors->first('sub_titulo_tipo3') }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">

                            <label>Áudio da pergunta</label>
                            <div id="playerAudioTituloTipo3" @if(old('existeAudioTituloTipo3',@$dados->audio_titulo) =='') style="display: none" @endif>
                                <audio id="myAudio" src="@if(old('existeAudioTituloTipo3', @$dados->audio_titulo) != ''){{config('app.cdn')}}/storage/quiz/{{$quiz->id}}/pergunta/{{$dados->id}}/{{old('existeAudioTituloTipo3', @$dados->audio_titulo)}}@endif">
                                    Seu browser não suporta esse formato de áudio.
                                </audio>
                                <a href="javascript:void(0);" onclick="addAudioTituloTipo3('','','');" title="Excluir áudio"><i class="fas fa-trash-alt" style="color:red; font-weight: normal"></i></a>
                                <button type="button" style="margin-left: 15px" class="btn btn-secondary btn-small" onclick="playAudio(this)"><i class="fas fa-play" aria-hidden="true"></i></button>
                                <input id="existeAudioTituloTipo3" type="hidden" name="existeAudioTituloTipo3" value="{{old('existeAudioTituloTipo3',@$dados->audio_titulo)}}">
                            </div>
                            <div id="audioTituloTipo3" @if(old('existeAudioTituloTipo3',@$dados->audio_titulo) !='') style="display: none" @endif>
                                <div class="form-group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="PerguntaTipoAudioArquivo3" name="tipoAudio" class="custom-control-input" value="arquivo" @if(old('tipoAudio')=='' || old('tipoAudio')=='arquivo') checked @endif >
                                        <label class="custom-control-label pt-1" for="PerguntaTipoAudioArquivo3" onclick ="mudaGravarAudioPergunta(3,'arquivo')">Enviar arquivo</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="PerguntaTipoAudioGravar3" name="tipoAudio" class=" custom-control-input" value="gravado" @if(old('tipoAudio')=='gravado') checked @endif>
                                        <label class="custom-control-label pt-1" for="PerguntaTipoAudioGravar3" onclick="mudaGravarAudioPergunta(3,'gravado')">Gravar meu áudio</label>
                                    </div>
                                </div>
                                <input id="enviaAudioPergunta3" type="file" accept=".mp3" name="audio_titulo_tipo3" class="form-control rounded {{ $errors->has('audio_titulo_tipo3') ? 'is-invalid' : '' }}">
                                <span id="gravaAudioPergunta3">
                                    <input id="enviaAudioPerguntaGravado3" type="hidden" name="audio_pergunta_gravado">
                                    <button type="button" class="btn btn-secondary btnGravar" onclick="startRecording(this,3)"><i class="fas fa-microphone" aria-hidden="true"></i> Gravar</button>
                                    <button type="button" class="btn btn-secondary btnParar" onclick="stopRecording(this,3)" disabled><i class="fas fa-stop" aria-hidden="true"></i> Parar</button>
                                    <button type="button" class="btn btn-secondary btnOuvir" onclick="$('#audioPergunta3')[0].play()" disabled><i class="fas fa-play" aria-hidden="true"></i> Ouvir</button>
                                    <audio id="audioPergunta3">Seu browser não suporta esse formato de áudio.</audio>
                                    <p class="mt-1"><b id="statusGravacao3" class="statusGravacao"></b></p>
                                </span>
                                <div class="invalid-feedback" style="display: block">{{ $errors->first('audio_titulo_tipo3') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label>* Imagem</label>
                            <div id="imagemCadastroTipo3" class=" form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center" @if(old('existeImgTipo3',@$dados->imagem) == '') style="display: none" @endif>
                                <input type="hidden" name="existeImgTipo3" id="existeImgTipo3" value="{{old('existeImgTipo3',@$dados->imagem)}}">
                                <img id="imgTipo3" width="328px" src="@if(old('existeImgTipo3', @$dados->imagem) != ''){{config('app.cdn')}}/storage/quiz/{{$quiz->id}}/pergunta/{{$dados->id}}/{{old('existeImgTipo3', @$dados->imagem)}}@endif">
                                <br>
                                <a class="btn btn-secondary" onclick="excluirImgTipo3()">Excluir Imagem</a>
                            </div>
                            <div id="novaImgTipo3" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center" @if(old('existeImgTipo3',@$dados->imagem) != '') style="display: none" @endif>
                                <input type="file" accept="image/*" name="imagem_tipo3" class="myCropper">
                            </div>
                            <div class="invalid-feedback @if($errors->first('imagem_tipo3'))d-block @endif">{{ $errors->first('imagem_tipo3') }}</div>

                        </div>
                    </div>
                    <div class="col-8">
                        <div class="row">

                            <div class="col-12">
                                <div class="form-group">
                                    <label style="width: 100%">* Respostas corretas
                                        <p class="text-right link"><a href="javascript:void(0)" class="link_personalido" onclick="addRespostaTipo3('')"><i class="fas fa-plus"></i> Adicionar respostas </a></p>
                                    </label>

                                    <div class="row" id="respostasTipo3" >
                                    @php
                                        $listaTipo3= 1;
                                        if( is_array( old('resposta_tipo3')) || @$dados->respostas != '' )
                                        {
                                            $listaTipo3 = count(old('resposta_tipo3',@$dados->respostas));
                                        }
                                    @endphp
                                    @for($i=0; $i<$listaTipo3; $i++)
                                        <div class="col-12">
                                            <div class="input-group input-group-sm" style="margin-bottom:10px" >
                                                <input type="text" name="resposta_tipo3[]" class="form-control rounded" value="{{old('resposta_tipo3.'.$i,@$dados->respostas[$i]->titulo)}}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="inputGroup-sizing-sm">
                                                        <a href="javascript:void(0);" title="Excluir resposta" onclick="excluirRespostaTipo3(this)"><i class="fas fa-trash-alt" style="color:red; font-weight: normal"></i></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                    </div>
                                    @if($errors->first('resposta_tipo3.*') != '')
                                        <div class="invalid-feedback" style="display: block">Todos os campos de respostas corretas são obrigatórios</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>Feedback quando incorreto</label>
                                    <input type="text" name="feedback_tipo3" placeholder="" value="{{old('feedback_tipo3',@$dados->feedback)}}" class="form-control rounded {{ $errors->has('feedback_tipo3') ? 'is-invalid' : '' }}">
                                    <div class="invalid-feedback">{{ $errors->first('feedback_tipo3') }}</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label>Feedback quando correto</label>
                                    <input type="text" name="feedback_tipo3_correta" placeholder="" value="{{old('feedback_tipo3_correta',@$dados->feedback_correta)}}" class="form-control rounded {{ $errors->has('feedback_tipo3_correta') ? 'is-invalid' : '' }}">
                                    <div class="invalid-feedback">{{ $errors->first('feedback_tipo3_correta') }}</div>
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


    <div id="modeloResposta3" class="d-none">
    <div class="col-12 divModeloResposta">
        <div class="input-group input-group-sm" style="margin-bottom:10px" >
            <input type="text" name="resposta_tipo3[]" value="respostaX" class="form-control rounded">
            <div class="input-group-append">
                <span class="input-group-text" id="inputGroup-sizing-sm">
                    <a href="javascript:void(0);" title="Excluir resposta" onclick="excluirRespostaTipo3(this)"><i class="fas fa-trash-alt" style="color:red; font-weight: normal"></i></a>
                </span>
            </div>
        </div>
    </div>
</div>

<script>

    var contGeralTipo3 = {{$listaTipo3}};

    $(document).ready(function(){
        new FroalaEditor('#froalaSubtitulo3', parametrosFroala);
        /* carrega o plugin de recortar imagem */
        $(".myCropper").slim(configuracao);
        mudaGravarAudioPergunta(3,'arquivo');
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

    function addRespostaTipo3(palavra)
    {
        elemento = $('#modeloResposta3').html();
        elemento = elemento.replace('divModeloResposta','divResposta');
        elemento = elemento.replace('respostaX',palavra);
        $('#respostasTipo3').append(elemento);
        contGeralTipo3++;
    }

    function excluirRespostaTipo3(elemento)
    {
        if(contGeralTipo3 > 1) {
            contGeralTipo3--;
            $(elemento).parent().parent().parent().remove();
        }
    }

    function excluirImgTipo3()
    {
        $('#imagemCadastroTipo3').hide();
        $('#existeImgTipo3').val('');
        $('#novaImgTipo3').show();
    }

    function excluirAudioTituloTipo3()
    {
        $("#playerAudioTituloTipo3").hide();
        $("#playerAudioTituloTipo3").children().attr('src','');
        $("#audioTituloTipo3").show();
        $("#existeAudioTituloTipo3").val('');
    }

    function addImagemTipo3(quizId,perguntaId,imagem)
    {
        if(imagem != '' && imagem != null){
            $('#imagemCadastroTipo3').removeClass('d-none');
            $('#imagemCadastroTipo3').show();
            $('#existeImgTipo3').val(imagem);
            var urlImg = '{{config('app.cdn')}}/storage/quiz/'+quizId+'/pergunta/'+perguntaId+'/'+imagem;
            $('#imgTipo3').prop('src',urlImg);
            $('#novaImgTipo3').hide();
        }
    }

    function addAudioTituloTipo3(quizId,perguntaId,audio)
    {
        if(audio != '' && audio != null){
            var urlAudio = '{{config('app.cdn')}}/storage/quiz/'+quizId+'/pergunta/'+perguntaId+'/'+audio;
            $("#playerAudioTituloTipo3").removeClass('d-none');
            $("#playerAudioTituloTipo3").show();
            $("#playerAudioTituloTipo3").children().attr('src',urlAudio);
            $("#audioTituloTipo3").hide();
            $("#existeAudioTituloTipo3").val(audio);
        }
        else
        {
            $("#playerAudioTituloTipo3").hide();
            $("#audioTituloTipo3").show();
            $("#existeAudioTituloTipo3").val('');
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
