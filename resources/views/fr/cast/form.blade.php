<div class="modal fade" id="formIncluir" tabindex="-1" role="dialog" aria-labelledby="formIncluir" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
			<div class="modal-header">
				<h5 class="modal-title" id="tituloForm">Cadastro de Áudio</h5>
			</div>
			<div class="modal-body">
				<form id="formFormulario" action="" method="post" enctype="multipart/form-data">
					@csrf
	                <input type="hidden" name="id" value="{{old('id')}}">
					<div class="row">
                		<div class="col-6">
                			<div class="form-group">
                    			<label>* Título</label>
                    			<input type="text" name="titulo" placeholder="" value="{{old('titulo')}}" class="form-control rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
		                        <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                			</div>
                		</div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>* Áudio</label>
                                <div id="audioCadastro">
                                    <audio id="myAudio" src="{{old('existeAudio')}}">
                                        Seu browser não suporta esse formato de áudio.
                                    </audio>
                                    <button type="button" style="float: none;margin-top: 0px;"  class="btn btn-default " onclick="playAudio()"><i class="fa fa-play" aria-hidden="true"></i></button>
                                    <button type="button" style="float: none;margin-top: 0px;" class="btn btn-secondary"  onclick="excluirAudio()">Excluir áudio</button>
                                    <input id="existeAudio" type="hidden" name="existeAudio" value="{{old('existeAudio')}}">
                                </div>
                                <div id="novoAudio">
                                    <div class="form-group">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="audioArquivo" name="tipoAudio" class="custom-control-input" value="arquivo" @if(old('tipoAudio')=='' || old('tipoAudio')=='arquivo') checked @endif >
                                            <label class="custom-control-label pt-1" for="audioArquivo" onclick ="mudaGravarAudio('enviar')">Enviar arquivo</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="audioGravar" name="tipoAudio" class=" custom-control-input" value="gravado" @if(old('tipoAudio')=='gravado') checked @endif>
                                            <label class="custom-control-label pt-1" for="audioGravar" onclick="mudaGravarAudio('gravar')">Gravar meu áudio</label>
                                        </div>
                                    </div>
                                    <input id="arquivoDeAudio" type="file" name="audio" accept=".mp3" class="form-control rounded {{ $errors->has('audio') ? 'is-invalid' : '' }}" @if(old('tipoAudio')=='gravado') style="display: none" @endif >
                                    <span id="gravaAudio" @if(old('tipoAudio')=='' || old('tipoAudio')=='arquivo') style="display: none" @endif>
                                        <input id="enviaAudioGravado" type="hidden" name="audio_gravado">
                                        <button type="button" class="btn btn-secondary btnGravar" onclick="startRecording(this);"><i class="fa fa-microphone" aria-hidden="true"></i> Gravar</button>
                                        <button type="button" class="btn btn-secondary btnParar" onclick="stopRecording(this)" disabled><i class="fa fa-stop" aria-hidden="true"></i> Parar</button>
                                        <button type="button" class="btn btn-secondary btnOuvir" onclick="$('#audioPergunta')[0].play()" disabled><i class="fa fa-play" aria-hidden="true"></i> Ouvir</button>
                                        <audio id="audioPergunta">Seu browser não suporta esse formato de áudio.</audio>
                                        <p class="mt-1"><b id="statusGravacao" class="statusGravacao"></b></p>
                                    </span>
                                </div>
                                <div class="invalid-feedback @if($errors->first('audio'))d-block @endif">{{ $errors->first('audio') }}</div>
                            </div>
                        </div>
                	</div>
            		<div class="row">
                		<div class="col-6">
                    		<div class="form-group">
	                        	<label>* Capa do áudio</label>
	                        	<div id="logoCadastro" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                	<input type="hidden" name="existeImg" id="existeImg" value="{{old('existeImg')}}">
	                                <img id="imgLogo" width="328px" src="{{old('existeImg')}}" >
	                                <br>
	                                <a class="btn btn-secondary" onclick="excluirLogo()">Excluir Capa</a>
	                            </div>
	                        	<div id="novaLogo" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                        			<input type="file" accept="image/*" name="imagem" class="myCropper">
                    			</div>
		                       	<div class="invalid-feedback @if($errors->first('imagem'))d-block @endif">{{ $errors->first('imagem') }}</div>

	                    	</div>
                    	</div>
                    	<div class="col-6">
                    		<div class="row">

		                    	<div class="col-12">
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
                    			<div class="col-12">
		            				<div class="form-group">
                        				<label>* Etapa / Ano</label>
                        				<select class="multipleEtapa {{ $errors->has('ciclo_etapa_id') ? 'is-invalid' : '' }}" name="ciclo_etapa_id" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
				                      		<option value="">Selecione</option>
			                      		  	@foreach($cicloEtapa as $c)
												<option @if( $c->ciclo_id.';'.$c->id == old('ciclo_etapa_id')) selected @endif value="{{$c->ciclo_id.';'.$c->id}}">{{$c->ciclo}} - {{$c->ciclo_etapa}}</option>
			                      		  	@endforeach
			                      		</select>
		                       			<div class="invalid-feedback @if($errors->first('ciclo_etapa_id'))d-block @endif">{{ $errors->first('ciclo_etapa_id') }}</div>
                    				</div>
		                    	</div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>* Categoria</label>
                                        <select class="multipleCategoria {{ $errors->has('categoria_id') ? 'is-invalid' : '' }}" name="categoria_id" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
                                            <option value="">Selecione</option>
                                            @foreach($categoria as $c)
                                                <option @if( $c->id == old('categoria_id')) selected @endif value="{{$c->id}}">{{$c->titulo}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback @if($errors->first('categoria_id'))d-block @endif">{{ $errors->first('categoria_id') }}</div>
                                    </div>
                                </div>
		                    	<div class="col-12">
		            				<div class="form-group">
                        				<label>Descrição</label>
                    					<textarea name="descricao" class="form-control rounded {{ $errors->has('descricao') ? 'is-invalid' : '' }}">{{old('descricao')}}</textarea>

		                       			<div class="invalid-feedback @if($errors->first('descricao'))d-block @endif">{{ $errors->first('descricao') }}</div>
                    				</div>
		                    	</div>

		                    	<div class="col-12">
		            				<div class="form-group">
                        				<label>Palavras-chave</label>
                    					<textarea name="apoio" class="form-control rounded {{ $errors->has('apoio') ? 'is-invalid' : '' }}">{{old('apoio')}}</textarea>
                        				<small class="form-text w-100 text-muted">
		                                    As palavras-chave devem ser separadas por hífen " - ".
		                                </small>
		                       			<div class="invalid-feedback @if($errors->first('apoio'))d-block @endif">{{ $errors->first('apoio') }}</div>
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
<!-- audio record -->
<script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/audio_recorder.js"></script>
<!-- Fim audio record -->

<script type="text/javascript">

    var quizExibir = 0;
    // GRAVAR AUDIO

    URL = window.URL || window.webkitURL;

    var gumStream;
    var rec;
    var input;
    var pergutaGravacao;


    var AudioContext = window.AudioContext || window.webkitAudioContext;
    var audioContext

    function startRecording(elemento) {
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
            $('#statusGravacao').html('Gravando ...');

        }).catch(function(err) {
            $(elemento).next().prop('disabled',true);
            $(elemento).prop('disabled',false);

        });
    }

    function stopRecording(elemento) {
        $(elemento).prop('disabled',true);
        $(elemento).next().prop('disabled',false);
        $(elemento).prev().prop('disabled',false);
        if(rec != null){
            rec.stop();
            $('#statusGravacao').html('Áudio gravado.');
            gumStream.getAudioTracks()[0].stop();
            rec.exportWAV(createDownloadLink);
        }

    }

    function createDownloadLink(blob) {
        var url = URL.createObjectURL(blob);

        var filename = new Date().toISOString();

        $('#audioPergunta').prop('src',url);

        var fd=new FormData();
        fd.append("audio",blob, filename);
        fd.append("_token",'{{csrf_token()}}');

        var xhr=new XMLHttpRequest();
        xhr.onload=function(e) {
            if(this.readyState === 4) {
                $('#enviaAudioGravado').val(JSON.parse(e.target.responseText));
            }
        };
        xhr.open("POST","{{url('/gestao/quiz/gravarAudioTemporario')}}",true);
        xhr.send(fd);
    }

    function mudaGravarAudio(tipo)
    {
        if(tipo == 'enviar'){
            $('#arquivoDeAudio').show();
            $('#gravaAudio').hide();
        }else{
            $('#arquivoDeAudio').hide();
            $('#gravaAudio').show();
        }
    }

    function mudaPesquisa()
    {
        $('#formPesquisa').submit();
    }

    $('.list-grid-btn').click(function() {
        $('.grid-item').addClass('list-grid');
    });

    $('.no-list-grid-btn').click(function() {
        $('.grid-item').removeClass('list-grid');
    });
    var tipoOperacao = '';
    var idCast = '';

    function excluirLogo()
    {
        $('#logoCadastro').hide();
        $('#novaLogo').show();
        $('#existeImg').val('');
    }

    function excluirAudio()
    {
        $('#audioCadastro').hide();
        $('#novoAudio').show();
        $('#existeAudio').val('');
    }

    $('#formExcluir').on('hidden.bs.modal', function () {
        idExcluir = 0;
    });

    function playAudio()
    {
        p = $('#myAudio');
        if (p[0].paused || p[0].ended) {
            p[0].play();
        }
        else{
            p[0].pause();
        }
    }

    $('#formIncluir').on('show.bs.modal', function () {

        if(tipoOperacao== 'editar')
        {
            $.ajax({
                url: '{{url('/gestao/cast/getAjax/')}}',
                type: 'post',
                dataType: 'json',
                data: {
                    id: idCast,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    popularForm($('#formIncluir'), data);
                    selectEtapaAno.set(data['ciclo_id']+';'+data['cicloetapa_id']);
                    selectDisciplina.set(data['disciplina_id']);
                    selectCategoria.set(data['categoria_id']);

                    if(data['capa_audio'] != '' && data['capa_audio'] != null){
                        $('#imgLogo').attr('src',data['capa_audio']);
                        $('#existeImg').val(data['capa_audio']);
                        $('#logoCadastro').show();
                        $('#novaLogo').hide();
                    }
                    else
                    {
                        $('#logoCadastro').hide();
                        $('#novaLogo').show();
                    }

                    if(data['audio'] != '' && data['audio'] != null){
                        $('#myAudio').attr('src',data['audio']);
                        $('#existeAudio').val(data['audio']);
                        $('#audioCadastro').show();
                        $('#novoAudio').hide();
                    }
                    else
                    {
                        $('#audioCadastro').hide();
                        $('#novoAudio').show();
                    }
                },
                error: function(data) {
                    swal("", "Audio não encontrado", "error");
                }
            });

            $('#tituloForm').html('Edição de Áudio');

        }
        else
        {
            $('#tituloForm').html('Cadastro de Áudio');
            $('#logoCadastro').hide();
            $('#novaLogo').show();
            $('#audioCadastro').hide();
            $('#novoAudio').show();
        }
    });

    $('#formIncluir').on('hidden.bs.modal', function () {
        limpaForm('#formIncluir');
        tipoOperacao = '';
        p = $('#myAudio');
        p[0].pause();

        $('#novaLogo').show();
        $('#logoCadastro').hide();
        $('#existeImg').val('');

        $('#audioArquivo').click();
        $('#arquivoDeAudio').show();
        $('#gravaAudio').hide();

        stopRecording($('.btnParar'));

        tipoOperacao = '';

    });

    function enviaFormulario()
    {
        if(tipoOperacao == 'add')
        {
            $('#idCast').val('');
            $('#formFormulario').attr('action','{{url('/gestao/cast/add/')}}');
            $('#formFormulario').submit();
        }
        else if(tipoOperacao == 'editar')
        {
            $('#formFormulario').attr('action','{{url('/gestao/cast/editar/')}}');
            $('#formFormulario').submit();
        }
    }

    $(document).ready(function(){
        @if($errors->all() != null || Request::input('n')==1)
            $('#formIncluir').modal('show');
            @if(old('id')!='')
                tipoOperacao = 'editar';
                $('#tituloForm').html('Edição de Áudio');
                @if(old('existeImg'))
                    $('#logoCadastro').show();
                    $('#novaLogo').hide();
                @endif
                @if(old('existeAudio'))
                    $('#audioCadastro').show();
                    $('#novoAudio').hide();
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

    var selectCategoria = new SlimSelect({
        select: '.multipleCategoria',
        placeholder: 'Buscar',
        searchPlaceholder: 'Buscar',
        closeOnSelect: true,
        allowDeselectOption: true,
        selectByGroup: true,
    });


</script>
