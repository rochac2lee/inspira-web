<!--  Exclusivo Froala Editor  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/froala_editor.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/froala_style.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/code_view.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/draggable.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/colors.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/emoticons.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/image_manager.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/image.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/line_breaker.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/table.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/char_counter.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/video.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/fullscreen.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/file.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/quick_insert.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/help.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/third_party/spell_checker.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor/css/plugins/special_characters.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">
<!--  FIM Froala Editor  -->
<div class="modal fade" id="divModalNovoConteudo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl px-1 px-md-5" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                @if(\Request::is('gestao/curso/*'))
                    <form id="formNovoConteudo" method="POST" action="{{ route('gestao.curso.aula-conteudos-novo', ['idCurso' => $curso->id]) }}" enctype="multipart/form-data" class="text-center px-3 shadow-none border-0">
                @elseif(\Request::is('gestao/cursoslivres/*'))
                    <form id="formNovoConteudo" method="POST" action="{{ route('gestao.curso.aula-conteudos-novo', ['idCurso' => $curso->id]) }}" enctype="multipart/form-data" class="text-center px-3 shadow-none border-0">
                @else
                    <form id="formNovoConteudo" method="POST" action="{{ route('gestao.conteudo-novo') }}" enctype="multipart/form-data" class="text-center px-3 shadow-none border-0">
                @endif


                    @csrf

                    @if(\Request::is('gestao/curso/*'))
                        <input name="idAula" required hidden>
                    @elseif(\Request::is('gestao/cursoslivres/*'))
                       <input name="idAula" required hidden>
                    @endif

                    <input id="tipo" name="tipo" required hidden>

                    <div id="divLoading" class="text-center">
                        <i class="fas fa-spinner fa-pulse fa-3x text-primary"></i>
                    </div>

                    <div id="divEnviando" class="text-center d-none">
                        <i class="fas fa-spinner fa-pulse fa-3x text-primary mb-3"></i>
                        <h4>Enviando</h4>
                    </div>

                    <div id="divEditar" class="form-page d-none">

                        <div id="page1" class="form-page">

                            <h4 id="lblTipoNovoConteudo">Tipo de conteudo</h4>

                            <div class="form-group mb-3 text-left">
                                <label class="" for="txtTituloNovoConteudo">Título do conteúdo</label>
                                <input type="text" class="form-control" name="titulo" id="txtTituloNovoConteudo" placeholder="Clique para digitar." required maxlength="255">
                            </div>


                            <div class="form-group mb-3 text-left">
                                <label class="" for="txtDescricaoNovoConteudo">Descrição do conteúdo</label>
                                <textarea class="form-control" name="descricao" id="txtDescricaoNovoConteudo" rows="3" placeholder="Clique para digitar." maxlength="1000" style="resize: none" required></textarea>
                            </div>
                            @php $instituicao = session('instituicao'); @endphp
                            @if($instituicao['id'] == 1)
                                <div class="form-group mb-3 text-left">
                                    <label  for="">Permissão:</label>
                                    <select class="custom-select form-control" name="tipo_livro">
                                        <option value="">Todos</option>
                                        <option value="P">Professor</option>
                                        <option value="A">Estudante</option>
                                    </select>
                                </div>
                            @endif
                            <div class="custom-control custom-checkbox form-group mb-3 text-left">
                                <input type="checkbox" class="custom-control-input" value="1" name="permissao_download" maxlength="1000" id="ckbObrigatorioEditarConteudo">
                                <label class="custom-control-label" for="ckbObrigatorioEditarConteudo">Realizar download</label>
                            </div>

                            <div class="custom-control custom-checkbox form-group mb-3 text-left">
                                <input type="checkbox" class="custom-control-input" name="obrigatorio" maxlength="1000" id="ckbObrigatorioEditarConteudo" checked>
                                <label class="custom-control-label" for="ckbObrigatorioEditarConteudo">Conteúdo obrigatório</label>
                            </div>

                            @if(\Request::is('gestao/livro') || \Request::is('gestao/curso/*') || \Request::is('gestao/cursoslivres/*' || cursoslivres))
                            <div class="form-group mb-3 text-left">

                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="upload-btn-wrapper w-100">
                                            <div class="form-group my-1">
                                                <label for="tipo">Etapa</label>
                                                <select class="custom-select form-control ciclo_id" name="ciclo_id" id="ciclo_id" required>
                                                    <option disabled="disabled" value="" selected>Selecione uma etapa</option>
                                                    @foreach($etapas as $etapa)
                                                        <option value="{{$etapa->id}}">{{$etapa->titulo}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="upload-btn-wrapper w-100">
                                            <div class="form-group my-1">
                                                <label for="tipo">Ano</label>
                                                <select class="custom-select form-control cicloetapa_id" name="cicloetapa_id" id="cicloetapa_id" required>
                                                    <option value="">Selecione um ano</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="upload-btn-wrapper w-100">
                                            <div class="form-group my-1">
                                                <label for="disciplina_id">Componente Curricular</label>
                                                <select class="custom-select form-control disciplina_id" name="disciplina_id" id="disciplina_id" required>
                                                    <option disabled="disabled" value="" selected>Componente Curricular</option>
                                                    @foreach($disciplinas as $disciplina)
                                                        <option value="{{$disciplina->id}}">{{$disciplina->titulo}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(isset($colecoesAcaoDestaque ))
                                <div id="colecaoDestaque" class="row d-none" >
                                    <div class="col-12">
                                        <div class="upload-btn-wrapper w-100">
                                            <div class="form-group my-1">
                                                <label for="tipo">Coleção Ação Destaque</label>
                                                <select class="custom-select form-control " name="colecao_destaque_livro_id" id="colecao_destaque_livro_id" required>
                                                    @foreach($colecoesAcaoDestaque as $c)
                                                        <option value="{{$c->id}}">{{$c->nome}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>
                            @endif

                            <!-- <div class="form-group mb-3 text-left">
                                <label class="" for="txtTempoLimiteNovoConteudo">Tempo limite <small>(opcional - em minutos)</small></label>
                                <input type="number" step="1" min="0" max="60" class="form-control w-auto" name="tempo" id="txtTempoLimiteNovoConteudo" placeholder="min.">
                            </div> -->

                            <div class="tipos-conteudo text-left">

                                <div id="conteudoTipo1" class="tipo">
                                    <div class="form-group mb-3">
                                        <label class="" for="txtConteudo">Conteúdo</label>
                                        <div class="summernote-holder">
                                            <div id='edit'></div>
                                            <textarea name="conteudo" id="txtConteudo" style="resize: none"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="conteudoTipo2" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="inputAudioNovoConteudo">Clique para fazer upload do áudio</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-primary file-name">Selecionar arquivo</button>
                                            <input type="file" name="arquivoAudio" id="inputAudioNovoConteudo" onchange="mudouArquivoInput(this);"  accept="audio/*" />
                                        </div>

                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtAudioNovoConteudo">Ou digite o link</label>
                                        <input type="text" class="form-control" name="conteudoAudio" maxlength="150" id="txtAudioNovoConteudo" placeholder="Clique para digitar.">
                                    </div>
                                </div>
                                <div id="conteudoTipo3" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="inputVideoNovoConteudo">Clique para fazer upload do vídeo</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-primary file-name">Selecionar arquivo</button>
                                            <input type="file" name="arquivoVideo" id="inputVideoNovoConteudo" onchange="mudouArquivoInput(this);"  accept="video/*" />
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtVideoNovoConteudo">Ou digite o link</label>
                                        <input type="text" class="form-control" name="conteudoVideo" maxlength="150" id="txtVideoNovoConteudo" placeholder="Clique para digitar.">
                                    </div>
                                    @if($instituicao['id'] == 1)
                                        <div class="form-group mb-3 text-left">
                                            <label class="" for="">Capa do vídeo:</label>
                                            <input type="file" class="form-control" name="capaVideo">
                                        </div>
                                    @endif
                                    <div class="form-group mb-3 text-left" hidden>
                                        <label class="">Preview:</label>
                                        <iframe class="d-block" src="https://www.youtube.com/embed/NpEaa2P7qZI" frameborder="0" allow="encrypted-media" style="width: 40vw;height: 25vw;max-width: 1040px;max-height: 586px;"></iframe>
                                    </div>
                                </div>
                                <div id="conteudoTipo4" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="inputSlideNovoConteudo">Clique para fazer upload do slide (Powerpoint)</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-primary file-name">Selecionar arquivo</button>
                                            <input type="file" name="arquivoSlide" id="inputSlideNovoConteudo" onchange="mudouArquivoInput(this);"  accept="application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.slideshow, application/vnd.openxmlformats-officedocument.presentationml.presentation, .pps, .pptx" />
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtSlideNovoConteudo">Ou digite o link</label>
                                        <input type="text" class="form-control" name="conteudoSlide" maxlength="150" id="txtSlideNovoConteudo" placeholder="Clique para digitar.">
                                    </div>
                                </div>
                                <div id="conteudoTipo6" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="inputArquivoNovoConteudo">Clique para fazer upload do arquivo</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-primary file-name">Selecionar arquivo</button>
                                            <input type="file" name="arquivoArquivo" id="inputArquivoNovoConteudo" onchange="mudouArquivoInput(this);" />
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtArquivoNovoConteudo">Ou digite o link</label>
                                        <input type="text" class="form-control" name="conteudoArquivo" maxlength="150" id="txtArquivoNovoConteudo" placeholder="Clique para digitar.">
                                    </div>
                                </div>
                                <div id="conteudoTipo7" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtDissertativaNovoConteudo">Sua pergunta dissertativa:</label>
                                        <textarea class="form-control" name="conteudoDissertativa" id="txtDissertativaNovoConteudo" rows="3" placeholder="Digite aqui sua pergunta." maxlength="1000" style="resize: none"></textarea>
                                    </div>
                                        <input type="hidden"  name="conteudoDissertativaDica" >

<!--
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtDissertativaDicaNovoConteudo">Dica <small>(opcional)</small></label>
                                        <input type="text" class="form-control" name="conteudoDissertativaDica" id="txtDissertativaDicaNovoConteudo" maxlength="1000" placeholder="Digite aqui uma mensagem de dica que será exibida caso seu aluno erre 3x.">
                                    </div>
-->
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtDissertativaExplicacaoNovoConteudo">Explicação <small>(opcional)</small></label>
                                        <input type="text" class="form-control" name="conteudoDissertativaExplicacao" id="txtDissertativaExplicacaoNovoConteudo" maxlength="1000" placeholder="Digite aqui uma mensagem explicativa a ser exibida quando o usuário acertar.">
                                    </div>

                                </div>
                                <div id="conteudoTipo8" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtQuizNovoConteudo">Sua pergunta:</label>
                                        <textarea name="conteudoQuizPergunta" id="txtQuizNovoConteudo" style="resize: none"></textarea>
                                    </div>
                                    <div class="custom-control custom-radio mb-3 text-left">
                                        <input type="radio" id="rdoAlternativa1" name="conteudoQuizAlternativaCorreta" value="1" class="custom-control-input">
                                        <label class="custom-control-label" for="rdoAlternativa1">{{ucfirst($langResposta)}} 1:</label>
                                        <textarea name="conteudoQuizAlternativa1" maxlength="150" id="txtQuizAlternativa1NovoConteudo" placeholder="Digite aqui a 1ª {{$langResposta}}." style="width: calc(95% - 80px);resize:none"></textarea>
                                    </div>
                                    <div class="custom-control custom-radio mb-3 text-left">
                                        <input type="radio" id="rdoAlternativa2" name="conteudoQuizAlternativaCorreta" value="2" class="custom-control-input">
                                        <label class="custom-control-label" for="rdoAlternativa2">{{ucfirst($langResposta)}} 2:</label>
                                        <textarea name="conteudoQuizAlternativa2" maxlength="150" id="txtQuizAlternativa2NovoConteudo" placeholder="Digite aqui a 2ª {{$langResposta}}." style="width: calc(95% - 80px);resize:none"></textarea>
                                    </div>
                                    <div class="custom-control custom-radio mb-3 text-left">
                                        <input type="radio" id="rdoAlternativa3" name="conteudoQuizAlternativaCorreta" value="3" class="custom-control-input">
                                        <label class="custom-control-label" for="rdoAlternativa3">{{ucfirst($langResposta)}} 3:</label>
                                        <textarea name="conteudoQuizAlternativa3" id="txtQuizAlternativa3NovoConteudo" maxlength="150" placeholder="Digite aqui a 3ª {{$langResposta}}." style="width: calc(95% - 80px);resize:none"></textarea>
                                    </div>
                                        <input type="hidden"  name="conteudoQuizDica" >

<!--
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtQuizDicaNovoConteudo">Dica <small>(opcional)</small></label>
                                        <input type="text" class="form-control" name="conteudoQuizDica" id="txtQuizDicaNovoConteudo" maxlength="1000" placeholder="Digite aqui uma mensagem de dica que será exibida caso seu aluno erre 3x.">
                                    </div>
-->
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtQuizExplicacaoNovoConteudo">Explicação <small>(opcional)</small></label>
                                        <input type="text" class="form-control" name="conteudoQuizExplicacao" id="txtQuizExplicacaoNovoConteudo" maxlength="1000" placeholder="Digite aqui uma mensagem explicativa a ser exibida quando o usuário acertar.">
                                    </div>
                                </div>
                                <div id="conteudoTipo9" class="tipo">
                                    <div class="form-group mb-3">
                                        <select class="custom-select form-control d-inline-block mr-2" id="cmbQuestaoAtual" onchange="mudouPerguntaProvaAtual('divModalNovoConteudo');" style="width: auto; min-width: 200px;">
                                            <option value="1" selected>Pergunta 1</option>
                                        </select>
                                        <input type="number" class="form-control d-inline-block" id="txtPesoPergunta" placeholder="Peso da pergunta" style="width: auto; min-width: 200px;">
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtDissertativaNovoConteudo" style="color: #999FB4;">Tipo de {{$langResposta}}:</label>
                                        <div class="btn-group btn-group-toggle d-inline-block" data-toggle="buttons">
                                            <label id="btnTipoResposta1" class="btn active">
                                                <input type="radio" name="options" id="rdoTipoResposta1" autocomplete="off" onchange="mudouTipoRespostaProva(this.id, 'divModalNovoConteudo');" checked> Dissertativa
                                            </label>
                                            <label id="btnTipoResposta2" class="btn">
                                                <input type="radio" name="options" id="rdoTipoResposta2" autocomplete="off" onchange="mudouTipoRespostaProva(this.id, 'divModalNovoConteudo');"> Multipla escolha
                                            </label>
                                        </div>
                                    </div>
                                    <div id="divDissertativa" class="">
                                        <div class="form-group mb-3 text-left">
                                            <label class="" for="txtPerguntaDissertativa">Sua pergunta dissertativa:</label>
                                            <textarea id="txtPerguntaDissertativa" style="resize: none"></textarea>
                                        </div>
                                    </div>
                                    <div id="divMultiplaEscolha" class="d-none">
                                        <div class="form-group mb-3 text-left">
                                            <label class="" for="txtPerguntaQuiz">Sua pergunta:</label>
                                            <textarea id="txtPerguntaQuiz" style="resize: none"> </textarea>
                                        </div>
                                        <div class="custom-control custom-radio mb-3 text-left">
                                            <input type="radio" id="rdoAlternativaMultiplaEscolha1" name="alternativaCorretaMultiplaEscolha" value="1" class="custom-control-input">
                                            <label class="custom-control-label" for="rdoAlternativaMultiplaEscolha1">{{ucfirst($langResposta)}} 1:</label>
                                            <textarea id="txtQuizAlternativa1" style="resize: none"> </textarea>
                                        </div>
                                        <div class="custom-control custom-radio mb-3 text-left">
                                            <input type="radio" id="rdoAlternativaMultiplaEscolha2" name="alternativaCorretaMultiplaEscolha" value="2" class="custom-control-input">
                                            <label class="custom-control-label" for="rdoAlternativaMultiplaEscolha2">{{ucfirst($langResposta)}} 2:</label>
                                            <textarea id="txtQuizAlternativa2" style="resize: none"></textarea>
                                        </div>
                                        <div class="custom-control custom-radio mb-3 text-left">
                                            <input type="radio" id="rdoAlternativaMultiplaEscolha3" name="alternativaCorretaMultiplaEscolha" value="3" class="custom-control-input">
                                            <label class="custom-control-label" for="rdoAlternativaMultiplaEscolha3">{{ucfirst($langResposta)}} 3:</label>
                                            <textarea id="txtQuizAlternativa3" style="resize: none"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <button id="btnAdicionarPergunta" type="button" onclick="adicionarNovaPerguntaProva('divModalNovoConteudo');" class="btn btn-primary btn-block mt-4 mb-0 col-sm-4 col-12 font-weight-bold">
                                            Adicionar pergunta
                                        </button>
                                    </div>
                                    <input name="conteudoProva" id="txtPerguntas" hidden>
                                </div>
                                <div id="conteudoTipo10" class="tipo">
                                    <div class="form-group mb-3">
                                        <label class="" for="txtConteudoEntregavel">Instruções para o conteúdo entregável</label>
                                        <textarea name="conteudoEntregavel" id="txtConteudoEntregavel" style="resize: none">
                                        </textarea>
                                    </div>
                                </div>
                                <div id="conteudoTipo11" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="inputApostilaNovoConteudo">Clique para fazer upload do {{$langDigital}} digital (.zip)</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-primary file-name">Selecionar arquivo</button>
                                            <input type="file" name="arquivoApostila" id="inputApostilaNovoConteudo" onchange="mudouArquivoInput(this);"  accept=".zip" />
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtApostilaNovoConteudo">Ou digite o link</label>
                                        <input type="text" class="form-control" name="conteudoApostila" maxlength="150" id="txtApostilaNovoConteudo" placeholder="Clique para digitar.">
                                    </div>
                                </div>
                                <div id="conteudoTipo12" class="tipo">
                                                <div class="form-group mb-3 text-left">

                                                </div>
                                                <div class="form-group mb-3 text-left">

                                                    <input type="text" class="form-control" name="palavra" id="txtPalavraNovoConteudo" placeholder="Escreva a palavra a ser adivinhada">
                                                </div>

                                            </div>
                                <div id="conteudoTipo15" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="inputPDFNovoConteudo">Clique para fazer upload do PDF</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-primary file-name">Selecionar arquivo</button>
                                            <input type="file" name="arquivoPDF" id="inputPDFNovoConteudo" onchange="mudouArquivoInput(this);"  accept="application/pdf" />
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtPDFNovoConteudo">Ou digite o link</label>
                                        <input type="text" class="form-control" name="conteudoPDF" maxlength="150" id="txtPDFNovoConteudo" placeholder="Clique para digitar.">
                                    </div>
                                </div>
                                <div id="conteudoTipo12" class="tipo">
                                    <div class="form-group mb-3 text-left">

                                    </div>
                                    <div class="form-group mb-3 text-left">

                                    <input type="text" class="form-control" name="palavra" id="txtPalavraNovoConteudo" placeholder="Escreva a palavra a ser adivinhada">
                                    </div>

                                </div>

                                <!-- Inicio de livro digital -->
                                <div id="conteudoTipo21" class="tipo">
                                    <div class="row">
                                        <div class="col-12 col-md-4">
                                            <div class="upload-btn-wrapper w-100">
                                                <div class="form-group my-1">
                                                    <label for="disciplina_id">Coleção do Livro</label>
                                                    <select class="custom-select form-control" name="colecao_livro_id" id="colecao_livro_id" required>
                                                        <option disabled="disabled" value="" selected>Coleção do Livro</option>
                                                        @if(isset($colecaoLivro))
                                                            @foreach($colecaoLivro as $c)
                                                                <option value="{{$c->id}}">{{$c->nome}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="upload-btn-wrapper w-100">
                                                <div class="form-group my-1">
                                                    <label for="disciplina_id">Tipo do Livro</label>
                                                    <select class="custom-select form-control" name="tipo_livro" id="tipo_livro" required>
                                                        <option disabled="disabled" value="" selected>Tipo do Livro</option>
                                                            <option value="P">Material (Docente)</option>
                                                            <option value="A">Material (Estudante)</option>
                                                            <option value="AP">Apoio (Docente)</option>
                                                            <option value="AA">Apoio (Estudante)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-1 text-left">
                                        <label class="" for="inputRevistaNovoConteudo">Clique para fazer upload do arquivo (.zip)</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-primary file-name">Selecionar arquivo</button>
                                            <input type="file" name="arquivoRevista" id="inputRevistaNovoConteudo" onchange="mudouArquivoInput(this);"  accept=".zip" />
                                        </div>
                                    </div>
                                </div>
                                <!-- Fim de livro digital -->

                                <div id="conteudoTipo22" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="inputPDFNovoConteudo">Clique para fazer upload do Documento Oficial</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-primary file-name">Selecionar arquivo</button>
                                            <input type="file" name="arquivoPDF2" id="inputPDFNovoConteudo" onchange="mudouArquivoInput(this);"  accept="application/pdf" />
                                        </div>
                                    </div>

                                </div>
                                 <!-- aqui começa o conteudo quiz -->
                                 <div id="conteudoTipo17" class="tipo">
                                            <div class="scrolly">
                                                <div class="conteudoquiz">

                                                     <div class="divscrol">
                                                            <div class="form-group mb-3 text-left">
                                                                <label class="" for="txtQuizNovoConteudo">Pergunta 1:</label>
                                                                <textarea id="txtQuizNovoConteudo" name="conteudoQuiz[]" class="md-textarea form-control campoDefault" rows="2" style="resize: none"></textarea>
                                                            </div>

                                                            <div class="custom-control custom-radio mb-3 text-left">
                                                                <input type="checkbox" id="rdoAlternativa1" name="conteudoQuizAlternativaCorreta[]" value="1" class="custom-control-input">
                                                                <label class="custom-control-label" for="rdoAlternativa1">{{ucfirst($langResposta)}} 1:</label>
                                                                <input type="text" class="form-control d-inline-block mx-2" name="conteudoQuizAlternativa[]" id="txtQuizAlternativa1NovoConteudo" maxlength="150" placeholder="Digite aqui a 1ª {{$langResposta}}..." style="width: calc(95% - 80px);">
                                                            </div>

                                                            <div class="custom-control custom-radio mb-3 text-left">
                                                                <input type="checkbox" id="rdoAlternativa2" name="conteudoQuizAlternativaCorreta[]" value="2" class="custom-control-input">
                                                                <label class="custom-control-label" for="rdoAlternativa2">{{ucfirst($langResposta)}} 2:</label>
                                                                <input type="text" class="form-control d-inline-block mx-2" name="conteudoQuizAlternativa[]" id="txtQuizAlternativa2NovoConteudo" maxlength="150" placeholder="Digite aqui a 2ª {{$langResposta}}..." style="width: calc(95% - 80px);">
                                                            </div>

                                                            <div class="custom-control custom-radio mb-6 text-left">
                                                                <input type="checkbox" id="rdoAlternativa3" name="conteudoQuizAlternativaCorreta[]" value="3" class="custom-control-input">
                                                                <label class="custom-control-label" for="rdoAlternativa3">{{ucfirst($langResposta)}} 3:</label>
                                                                <input type="text" class="form-control d-inline-block mx-2" name="conteudoQuizAlternativa[]" id="txtQuizAlternativa3NovoConteudo" maxlength="150" placeholder="Digite aqui a 3ª {{$langResposta}}..." style="width: calc(95% - 80px);">
                                                            </div>

                                                             <div class="showNewInput"></div>
                                                            <br>
                                                            <div class="form-group">
                                                                <button type="button" class="btn btnAdd btn-secondary btn-block" id="1">
                                                                    <i class="fas fa-plus"></i> ADICIONAR NOVA {{strtoupper($langResposta)}}
                                                                </button>
                                                            </div>
                                                        </div>
                                                         <div class="divscrol">
                                                                <div class="newquestion" ></div>
                                                    </div>

                                                        <!-- <div class="divscrol">
                                                            <div class="form-group mb-3 text-left">
                                                                <label class="" for="txtQuizNovoConteudo">Pergunta 2:</label>
                                                                <textarea id="txtQuizNovoConteudo" name="conteudoQuiz1" class="md-textarea form-control campoDefault" rows="2"></textarea>
                                                            </div>
                                                            <div class="custom-control custom-radio mb-3 text-left">
                                                                <input type="radio" id="rdoAlternativa4" name="conteudoQuizAlternativaCorreta1" value="1" class=" custom-control-input">
                                                                <label class="custom-control-label" for="rdoAlternativa4">Resposta 1:</label>
                                                                <input type="text" class="form-control d-inline-block mx-2" name="conteudoQuizAlternativa4" id="txtQuizAlternativa1NovoConteudo" maxlength="150" placeholder="Digite aqui a 1ª alternativa..." style="width: calc(95% - 80px);">
                                                            </div>
                                                            <div class="custom-control custom-radio mb-3 text-left">
                                                                <input type="radio" id="rdoAlternativa5" name="conteudoQuizAlternativaCorreta1" value="2" class="custom-control-input">
                                                                <label class="custom-control-label" for="rdoAlternativa5">Resposta 2:</label>
                                                                <input type="text" class="form-control d-inline-block mx-2" name="conteudoQuizAlternativa5" id="txtQuizAlternativa2NovoConteudo" maxlength="150" placeholder="Digite aqui a 2ª alternativa..." style="width: calc(95% - 80px);">
                                                            </div>

                                                            <div class="custom-control custom-radio mb-6 text-left">
                                                                <input type="radio" id="rdoAlternativa6" name="conteudoQuizAlternativaCorreta1" value="3" class="custom-control-input">
                                                                <label class="custom-control-label" for="rdoAlternativa6">Resposta 3:</label>
                                                                <input type="text" class="form-control d-inline-block mx-2" name="conteudoQuizAlternativa6" id="txtQuizAlternativaNovoConteudo" maxlength="150" placeholder="Digite aqui a 3ª alternativa..." style="width: calc(95% - 80px);">
                                                            </div>

                                                            <div class="showNewInput"></div>
                                                            <br>
                                                            <div class="form-group">
                                                                <button type="button" class="btn btnAdd btn-secondary btn-block" id="2">
                                                                    <i class="fas fa-plus"></i> ADICIONAR NOVA RESPOSTA
                                                                </button>
                                                            </div>
                                                        </div> -->
<!--
                                                        <div class="divscrol">
                                                            <div class="form-group mb-3 text-left">
                                                                <label class="" for="txtQuizNovoConteudo">Pergunta 3:</label>
                                                                <textarea id="txtQuizNovoConteudo" name="conteudoQuiz2" class="md-textarea form-control " rows="2"></textarea>
                                                            </div>
                                                            <div class="custom-control custom-radio mb-6 text-left">
                                                                <input type="radio" id="rdoAlternativa7" name="conteudoQuizAlternativaCorreta2" value="1" class="custom-control-input">
                                                                <label class="custom-control-label" for="rdoAlternativa7">Resposta 1:</label>
                                                                <input type="text" class="form-control d-inline-block mx-2" name="conteudoQuizAlternativa7" id="txtQuizAlternativa1NovoConteudo" maxlength="150" placeholder="Digite aqui a 1ª alternativa..." style="width: calc(95% - 80px);">
                                                            </div><br>
                                                            <div class="custom-control custom-radio mb-3 text-left">
                                                                <input type="radio" id="rdoAlternativa8" name="conteudoQuizAlternativaCorreta2" value="2" class="custom-control-input">
                                                                <label class="custom-control-label" for="rdoAlternativa8">Resposta 2:</label>
                                                                <input type="text" class="form-control d-inline-block mx-2" name="conteudoQuizAlternativa8" id="txtQuizAlternativa2NovoConteudo" maxlength="150" placeholder="Digite aqui a 2ª alternativa..." style="width: calc(95% - 80px);">
                                                            </div>
                                                            <div class="custom-control custom-radio mb-3 text-left">
                                                                <input type="radio" id="rdoAlternativa9" name="conteudoQuizAlternativaCorreta2" value="3" class="custom-control-input">
                                                                <label class="custom-control-label" for="rdoAlternativa9">Resposta 3:</label>
                                                                <input type="text" class="form-control d-inline-block mx-2" name="conteudoQuizAlternativa9" id="txtQuizAlternativa3NovoConteudo" maxlength="150" placeholder="Digite aqui a 3ª alternativa..." style="width: calc(95% - 80px);">
                                                            </div>

                                                        <div class="showNewInput"></div>

                                                            <div class="form-group">
                                                                <button type="button" class="btn btnAdd btn-secondary btn-block" id="3">
                                                                    <i class="fas fa-plus"></i> ADICIONAR NOVA RESPOSTA
                                                                </button>
                                                            </div>

                                                        </div> -->

                                                    </div>

                                                </div>

                                                <!-- <div class="form-group mb-3 text-left">
                                                    <label class="" for="txtQuizDicaNovoConteudo">Dica <small>(opcional)</small></label>
                                                    <input type="text" class="form-control" name="conteudoQuizDica" id="txtQuizDicaNovoConteudo" maxlength="150" placeholder="Digite aqui uma mensagem de dica que será exibida caso seu aluno erre 3x...">
                                                </div> -->
                                                <!-- <div class="form-group mb-3 text-left">
                                                    <label class="" for="txtQuizExplicacaoNovoConteudo">Explicação <small>(opcional)</small></label>
                                                    <input type="text" class="form-control" name="conteudoQuizExplicacao" id="txtQuizExplicacaoNovoConteudo" maxlength="150" placeholder="Digite aqui uma mensagem explicativa a ser exibida quando o usuário acertar...">
                                                </div> -->
                                            </div>

                            </div>

                              <div class="form-group mb-3 text-left d-none">
                                <label class="" for="cmbStatusNovoConteudo">Status do conteúdo</label>
                                <select id="cmbStatusNovoConteudo" name="status" required class="custom-select rounded">
                                    <option disabled selected>Selecione um status</option>
                                    <option value="0">Não publicado</option>
                                    <option value="1">Publicado</option>
                                    <option value="2">Não listado</option>
                                </select>
                            </div>
                            <input type="hidden" name="status" value="1">

                            <div class="form-group mb-3 text-left">
                                <label class="" for="txtApoioNovoConteudo">Palavra-chave <small>(opcional)</small></label>
                                <textarea class="form-control" name="apoio" id="txtApoioNovoConteudo" rows="2" placeholder="Clique para digitar." maxlength="1000" style="resize: none"></textarea>
                            </div>

                            <div class="form-group mb-3 text-left">
                                <label class="" for="txtFonteNovoConteudo">Fonte do conteúdo <small>(opcional)</small></label>
                                <textarea class="form-control" name="fonte" id="txtFonteNovoConteudo" rows="2" placeholder="Clique para digitar." maxlength="1000" style="resize: none"></textarea>
                            </div>

                            <div class="form-group mb-3 text-left">
                                <label class="" for="txtAutoresNovoConteudo">Autores do conteúdo <small>(opcional)</small></label>
                                <textarea class="form-control" name="autores" id="txtAutoresNovoConteudo" rows="2" placeholder="Clique para digitar." maxlength="1000" style="resize: none"></textarea>
                            </div>

                            <div class="row">
                                <button type="button" data-dismiss="modal" class="btn btn-lg btn-block btn-cancelar mt-4 mb-0 col-4 ml-auto mr-4 font-weight-bold">Cancelar</button>
                                <button type="button" onclick="salvarConteudo();" class="btn btn-lg btn-block btn-primary mt-4 mb-0 col-4 ml-4 mr-auto text-white font-weight-bold">Salvar</button>
                            </div>

                        </div>



                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

<!--  Exclusivo Froala Editor  -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>

  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/froala_kg.js"></script>

  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/froala_editor.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/align.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/char_counter.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/code_beautifier.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/code_view.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/colors.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/draggable.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/entities.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/font_size.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/font_family.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/fullscreen.min.js"></script>

  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/image.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/image_manager.min.js"></script>

  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/line_breaker.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/inline_style.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/link.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/lists.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/paragraph_format.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/paragraph_style.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/quote.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/table.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/save.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/url.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/help.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/print.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/third_party/spell_checker.min.js"></script>
  <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor/js/plugins/word_paste.min.js"></script>
  <script type="text/javascript" src='{{config('app.cdn')}}/fr/includes/froala_editor/js/languages/pt_br.js'></script>
  <script>


    new FroalaEditor('#txtQuizAlternativa1NovoConteudo, #txtQuizAlternativa2NovoConteudo, #txtQuizAlternativa3NovoConteudo, #txtQuizNovoConteudo, #txtConteudo, #txtConteudoEntregavel, #txtDissertativaNovoConteudo, #txtQuizAlternativa1, #txtQuizAlternativa2, #txtQuizAlternativa3', {
      key: "1C%kZV[IX)_SL}UJHAEFZMUJOYGYQE[\\ZJ]RAe(+%$==",
      attribution: false, // to hide "Powered by Froala"
      heightMin: 132,
      language: 'pt_br',
      linkAlwaysBlank: true,
      imageUploadURL: '{{url('/upload/froala/')}}',

      imageUploadParams: {
        id: 'my_editor',
        _token: '{{ csrf_token() }}'
      },
      imageEditButtons: ['imageReplace', 'imageAlign', 'imageRemove', '|', 'imageLink', 'linkOpen', 'linkEdit', 'linkRemove', '-', 'imageDisplay', 'imageStyle', 'imageAlt', 'imageSize']
    });




  </script>
<!--  FIM Froala Editor  -->

<script>
    // Função para mostrar apenas os anos de etapa
    $('.ciclo_id').change(function(){
        ciclo = $(this).val();
        const newLocal = '{{ route('gestao.searchcicloetapa')}}';
        $.ajax({
            url: newLocal,
            type: 'GET',
            dataType: 'json',
            data: {ciclo: ciclo},
            success: function (data) {
                $('.cicloetapa_id').html(data);
            },
                error: function (data) {
                    console.log(data);
            }
        });
    });
</script>
