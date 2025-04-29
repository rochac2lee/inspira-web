
<div class="modal fade" id="divModalEditarConteudo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl px-1 px-md-5" role="document">
        <div class="modal-content">
            <div class="modal-body mt-4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                @if(\Request::is('gestao/curso/*'))
                    <form id="formEditarConteudo" method="POST" action="{{ route('gestao.curso.aula-conteudos-salvar', ['idCurso' => $curso->id]) }}" enctype="multipart/form-data" class="text-center px-3 shadow-none border-0">
                @elseif(\Request::is('gestao/cursoslivres/*'))
                    <form id="formEditarConteudo" method="POST" action="{{ route('gestao.curso.aula-conteudos-salvar', ['idCurso' => $curso->id]) }}" enctype="multipart/form-data" class="text-center px-3 shadow-none border-0">
                @else
                    <form id="formEditarConteudo" method="POST" action="{{ route('gestao.conteudos-salvar') }}" enctype="multipart/form-data" class="text-center px-3 shadow-none border-0">
                @endif

                    @csrf

                    <input name="idConteudo" required hidden>

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

                            <h4 id="lblTipoConteudo">Tipo de conteudo</h4>

                            <div class="form-group mb-3 text-left">
                                <label class="" for="txtTituloNovoConteudo">Título do conteúdo</label>
                                <input type="text" class="form-control" name="titulo" id="txtTituloNovoConteudo" maxlength="80" placeholder="Clique para digitar." required>
                            </div>

                            <div class="form-group mb-3 text-left">
                                <label class="" for="txtDescricaoNovoConteudo">Descrição do conteúdo <small>(opcional)</small></label>
                                <textarea class="form-control" name="descricao" id="txtDescricaoNovoConteudo" rows="3" maxlength="1000" placeholder="Clique para digitar." style="resize: none"></textarea>
                            </div>

                            <div class="custom-control custom-checkbox form-group mb-3 text-left">
                                <input type="checkbox" class="custom-control-input" value="1" name="permissao_download" id="ckDownload" maxlength="1000">
                                <label class="custom-control-label" for="ckDownload">Realizar download</label>
                            </div>

                            <div class="custom-control custom-checkbox form-group mb-3 text-left">
                                <input type="checkbox" class="custom-control-input" name="obrigatorio" id="ckbObrigatorioNovoConteudo" maxlength="1000" checked>
                                <label class="custom-control-label" for="ckbObrigatorioNovoConteudo">Conteúdo obrigatório</label>
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
                                                <select class="custom-select form-control cicloetapa_id" name="cicloetapa_id" required>
                                                    <option value="" disabled="" selected="">Selecione um ano</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="upload-btn-wrapper w-100">
                                            <div class="form-group my-1">
                                                <label for="disciplina_id">Componente Curricular</label>
                                                <select class="custom-select form-control componente_curricular" name="disciplina_id" id="disciplina_id" required>
                                                    <option disabled="disabled" value="" selected>Componente Curricular</option>
                                                    @foreach($disciplinas as $disciplina)
                                                        <option value="{{$disciplina->id}}">{{$disciplina->titulo}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            @endif

                            <!-- <div class="form-group mb-3 text-left">
                                <label class="" for="txtTempoLimiteNovoConteudo">Tempo limite <small>(opcional - em minutos)</small></label>
                                <input type="number" step="1" min="0" max="60" class="form-control w-auto" name="tempo" id="txtTempoLimiteNovoConteudo" placeholder="min.">
                            </div> -->

                            <div id="divVerArquivoAtual" class="form-group mb-3 text-left d-none">
                                <a id="btnVerArquivoAtual" target="_blank" class="btn btn-lg btn-primary">Ver arquivo atual</a>
                            </div>

                            <div class="tipos-conteudo text-left">

                                <div id="conteudoTipo1" class="tipo">
                                    <div class="form-group mb-3">
                                        <label class="" for="txtConteudo">Conteúdo</label>
                                        <div class="summernote-holder">
                                            <textarea name="conteudo" id="txtConteudo" style="resize: none">

                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="conteudoTipo2" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="inputAudioNovoConteudo">Clique para fazer upload de um novo áudio</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-lg bg-blue text-white file-name">Selecionar arquivo</button>
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
                                        <label class="" for="inputVideoNovoConteudo">Clique para fazer upload de um novo vídeo</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-lg bg-blue text-white file-name">Selecionar arquivo</button>
                                            <input type="file" name="arquivoVideo" id="inputVideoNovoConteudo" onchange="mudouArquivoInput(this);"  accept="video/*" />
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtVideoNovoConteudo">Ou digite o link</label>
                                        <input type="text" class="form-control" name="conteudoVideo" maxlength="150" id="txtVideoNovoConteudo" placeholder="Clique para digitar.">
                                    </div>
                                    <div class="form-group mb-3 text-left" hidden>
                                        <label class="">Preview:</label>
                                        <iframe class="d-block" src="https://www.youtube.com/embed/NpEaa2P7qZI" frameborder="0" allow="encrypted-media" style="width: 40vw;height: 25vw;max-width: 1040px;max-height: 586px;"></iframe>
                                    </div>
                                </div>
                                <div id="conteudoTipo4" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="inputSlideNovoConteudo">Clique para fazer upload de um novo slide (Powerpoint)</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-lg bg-blue text-white file-name">Selecionar arquivo</button>
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
                                        <label class="" for="inputArquivoNovoConteudo">Clique para fazer upload de um novo arquivo</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-lg bg-blue text-white file-name">Selecionar arquivo</button>
                                            <input type="file" name="arquivoArquivo" id="inputArquivoNovoConteudo" onchange="mudouArquivoInput(this);" />
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtArquivoNovoConteudo">Ou digite o link</label>
                                        <input type="text" class="form-control" name="conteudoArquivo" maxlength="150" id="txtArquivoNovoConteudo" placeholder="Clique para digitar.">
                                    </div>
                                </div>
                                <div id="conteudoTipo7" class="tipo">
                                    <input type="hidden" id="questaoTipo7Id" name="questaoTipo7Id">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtDissertativaNovoConteudo">Sua pergunta dissertativa:</label>
                                        <textarea name="conteudoDissertativa" id="txtDissertativaNovoConteudo" style="resize: none"></textarea>
                                    </div>
                                    <input type="hidden" name="conteudoDissertativaDica" >
                                    <!--<div class="form-group mb-3 text-left">
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
                                    <input type="hidden" id="questaoTipo8Id" name="questaoTipo8Id">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtQuizNovoConteudo">Sua pergunta:</label>
                                        <textarea name="conteudoQuizPergunta" id="txtQuizNovoConteudo" style="resize: none">  </textarea>
                                    </div>
                                    <div class="custom-control custom-radio mb-3 text-left">
                                        <input type="radio" id="rdoEditAlternativa1" name="conteudoQuizAlternativaCorreta" value="1" class="custom-control-input">
                                        <label class="custom-control-label" for="rdoEditAlternativa1">{{ucfirst($langResposta)}} 1:</label>
                                        <textarea name="conteudoQuizAlternativa1" maxlength="150" id="txtQuizAlternativa1NovoConteudo" placeholder="Digite aqui a 1ª {{$langResposta}}." style="width: calc(95% - 80px);resize:none;"></textarea>
                                    </div>
                                    <div class="custom-control custom-radio mb-3 text-left">
                                        <input type="radio" id="rdoEditAlternativa2" name="conteudoQuizAlternativaCorreta" value="2" class="custom-control-input">
                                        <label class="custom-control-label" for="rdoEditAlternativa2">{{ucfirst($langResposta)}} 2:</label>
                                        <textarea name="conteudoQuizAlternativa2" maxlength="1000" id="txtQuizAlternativa2NovoConteudo" placeholder="Digite aqui a 2ª {{$langResposta}}." style="width: calc(95% - 80px);resize:none"></textarea>
                                    </div>
                                    <div class="custom-control custom-radio mb-3 text-left">
                                        <input type="radio" id="rdoEditAlternativa3" name="conteudoQuizAlternativaCorreta" value="3" class="custom-control-input">
                                        <label class="custom-control-label" for="rdoEditAlternativa3">{{ucfirst($langResposta)}} 3:</label>
                                        <textarea name="conteudoQuizAlternativa3" maxlength="150" id="txtQuizAlternativa3NovoConteudo" placeholder="Digite aqui a 3ª {{$langResposta}}." style="width: calc(95% - 80px);resize:none"></textarea>
                                    </div>
                                    <input type="hidden" name="conteudoQuizDica" >
                                    <!--
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtQuizDicaNovoConteudo">Dica <small>(opcional)</small></label>
                                        <input type="text" class="form-control" name="conteudoQuizDica" id="txtQuizDicaNovoConteudo" maxlength="1000"  placeholder="Digite aqui uma mensagem de dica que será exibida caso seu aluno erre 3x.">
                                    </div>
                                -->
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtQuizExplicacaoNovoConteudo">Explicação <small>(opcional)</small></label>
                                        <input type="text" class="form-control" name="conteudoQuizExplicacao" id="txtQuizExplicacaoNovoConteudo" maxlength="1000" placeholder="Digite aqui uma mensagem explicativa a ser exibida quando o usuário acertar.">
                                    </div>
                                </div>
                                <div id="conteudoTipo10" class="tipo">
                                    <div class="form-group mb-3">
                                        <label class="" for="txtConteudoEntregavel">Instruções para o conteúdo entregável</label>
                                        <div class="summernote-holder">
                                            <textarea name="conteudoEntregavel" id="txtConteudoEntregavel"  class="summernote-airmode" style="resize: none">
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="conteudoTipo9" class="tipo">
                                    <input type="hidden" id="questaoTipo9Id" name="questaoTipo9Id">
                                    <div class="form-group mb-3">
                                        <select class="custom-select form-control d-inline-block mr-2" id="cmbQuestaoAtual" onchange="mudouPerguntaProvaAtual('divModalEditarConteudo');" style="width: auto; min-width: 200px;">
                                            <option value="1" selected>Pergunta 1</option>
                                        </select>
                                        <input type="number" class="form-control d-inline-block" id="txtPesoPergunta" placeholder="Peso da pergunta" style="width: auto; min-width: 200px;">
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtDissertativaNovoConteudo" style="color: #999FB4;">Tipo de {{$langResposta}}:</label>
                                        <div class="btn-group btn-group-toggle d-inline-block" data-toggle="buttons">
                                            <label id="btnTipoResposta1" class="btn">
                                                <input type="radio" name="options" id="rdoTipoResposta1" autocomplete="off" onchange="mudouTipoRespostaProva(this.id, 'divModalEditarConteudo');" checked> Dissertativa
                                            </label>
                                            <label id="btnTipoResposta2" class="btn">
                                                <input type="radio" name="options" id="rdoTipoResposta2" autocomplete="off" onchange="mudouTipoRespostaProva(this.id, 'divModalEditarConteudo');"> Multipla escolha
                                            </label>
                                        </div>
                                    </div>
                                    <div id="divDissertativa" class="">
                                        <div class="form-group mb-3 text-left">
                                            <label class="" for="txtPerguntaDissertativa">Sua pergunta dissertativa:</label>
                                            <textarea id="txtPerguntaDissertativa" style="resize: none"> <textarea>
                                        </div>
                                    </div>
                                    <div id="divMultiplaEscolha" class="">
                                        <div class="form-group mb-3 text-left">
                                            <label class="" for="txtPerguntaQuiz">Sua pergunta:</label>
                                            <input type="text" class="form-control" id="txtPerguntaQuiz" maxlength="150" placeholder="Digite aqui sua pergunta.">
                                        </div>
                                        <div class="custom-control custom-radio mb-3 text-left">
                                            <input type="radio" id="rdoEditAlternativaMultiplaEscolha1" name="alternativaCorretaMultiplaEscolha" value="1" class="custom-control-input">
                                            <label class="custom-control-label" for="rdoEditAlternativaMultiplaEscolha1">{{ucfirst($langResposta)}} 1:</label>
                                            <input type="text" class="form-control d-inline-block mx-2" id="txtQuizAlternativa1" maxlength="150" placeholder="Digite aqui a 1ª {{$langResposta}}." style="width: calc(95% - 80px);">
                                        </div>
                                        <div class="custom-control custom-radio mb-3 text-left">
                                            <input type="radio" id="rdoEditAlternativaMultiplaEscolha2" name="alternativaCorretaMultiplaEscolha" value="2" class="custom-control-input">
                                            <label class="custom-control-label" for="rdoEditAlternativaMultiplaEscolha2">{{ucfirst($langResposta)}} 2:</label>
                                            <input type="text" class="form-control d-inline-block mx-2" id="txtQuizAlternativa2" maxlength="150" placeholder="Digite aqui a 2ª {{$langResposta}}." style="width: calc(95% - 80px);">
                                        </div>
                                        <div class="custom-control custom-radio mb-3 text-left">
                                            <input type="radio" id="rdoEditAlternativaMultiplaEscolha3" name="alternativaCorretaMultiplaEscolha" value="3" class="custom-control-input">
                                            <label class="custom-control-label" for="rdoEditAlternativaMultiplaEscolha3">{{ucfirst($langResposta)}} 3:</label>
                                            <input type="text" class="form-control d-inline-block mx-2" id="txtQuizAlternativa3" maxlength="150" placeholder="Digite aqui a 3ª {{$langResposta}}." style="width: calc(95% - 80px);">
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <button id="btnAdicionarPergunta" type="button" onclick="adicionarNovaPerguntaProva('divModalEditarConteudo');" class="btn btn-primary btn-block mt-4 mb-0 col-sm-4 col-12 font-weight-bold">
                                            Adicionar pergunta
                                        </button>
                                    </div>
                                    <input name="conteudoProva" id="txtPerguntas" hidden>
                                </div>
                                <div id="conteudoTipo10" class="tipo">
                                    <div class="form-group mb-3">
                                        <label class="" for="txtConteudoEntregavel">Instruções para o conteúdo entregável</label>
                                        <div class="summernote-holder">
                                            <textarea name="conteudoEntregavel" id="txtConteudoEntregavel"  class="summernote-airmode" style="resize: none">
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="conteudoTipo11" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="inputApostilaNovoConteudo">Clique para fazer upload do PDF (.zip)</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-lg btn-primary file-name">Selecionar arquivo</button>
                                            <input type="file" name="arquivoApostila" id="inputApostilaNovoConteudo" onchange="mudouArquivoInput(this);"  accept=".zip" />
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtApostilaNovoConteudo">Ou digite o link</label>
                                        <input type="text" class="form-control" name="conteudoApostila" maxlength="150" id="txtApostilaNovoConteudo" placeholder="Clique para digitar.">
                                    </div>
                                </div>

                                <div id="conteudoTipo21" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="inputRevistaNovoConteudo">Clique para fazer upload do {{ucfirst($langDigital)}} Digital (.zip)</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-lg btn-primary file-name">Selecionar arquivo</button>
                                            <input type="file" name="arquivoRevista" id="inputRevistaNovoConteudo" onchange="mudouArquivoInput(this);"  accept=".zip" />
                                        </div>
                                    </div>

                                    @if(\Request::is('gestao/livro') || \Request::is('gestao/curso/*'))
                                    <!-- <div class="form-group mb-1 text-left">

                                        <div class="upload-btn-wrapper">
                                            <div class="form-group my-1 mx-2">
                                                <label for="tipo">Etapa</label>
                                                <select class="custom-select form-control ciclo_id" name="ciclo_id" id="ciclo_id" required>
                                                    <option disabled="disabled" value="" selected>Selecione uma etapa</option>
                                                    @foreach($etapas as $etapa)
                                                        <option value="{{$etapa->id}}">{{$etapa->titulo}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="upload-btn-wrapper">
                                            <div class="form-group my-1 mx-2">
                                                <label for="tipo">Ano</label>
                                                <select class="custom-select form-control" name="cicloetapa_id" id="cicloetapa_id" required>
                                                    <option value="" disabled="disabled" selected="selected">Selecione um ano</option>
                                                    @foreach($cicloEtapas as $cicloEtapa)
                                                        <option value="{{$cicloEtapa->id}}">{{$cicloEtapa->titulo}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="upload-btn-wrapper">
                                            <div class="form-group my-1 mx-2">
                                                <label for="disciplina_id">Componente Curricular</label>
                                                <select class="custom-select form-control" name="disciplina_id" id="disciplina_id" required>
                                                    <option disabled="disabled" value="" selected>Componente Curricular</option>
                                                    @foreach($disciplinas as $disciplina)
                                                        <option value="{{$disciplina->id}}">{{$disciplina->titulo}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div> -->
                                    @endif
                                </div>

                                <div id="conteudoTipo22" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="inputPDFNovoConteudo">Clique para fazer upload do Documento Oficial</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-lg btn-primary file-name">Selecionar arquivo</button>
                                            <input type="file" name="arquivoPDF2" id="inputPDFNovoConteudo" onchange="mudouArquivoInput(this);"  accept="application/pdf" />
                                        </div>
                                    </div>

                                </div>

                                <div id="conteudoTipo15" class="tipo">
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="inputPDFNovoConteudo">Clique para fazer upload do PDF</label>
                                        <br>
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-lg btn-primary file-name">Selecionar arquivo</button>
                                            <input type="file" name="arquivoPDF" id="inputPDFNovoConteudo" onchange="mudouArquivoInput(this);"  accept="application/pdf" />
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label class="" for="txtPDFNovoConteudo">Ou digite o link</label>
                                        <input type="text" class="form-control" name="conteudoPDF" maxlength="150" id="txtPDFNovoConteudo" placeholder="Clique para digitar.">
                                    </div>
                                </div>
                            </div>



                            {{--  <div class="form-group mb-3 text-left">
                                <label class="" for="cmbStatusNovoConteudo">Status do conteúdo</label>
                                <select id="cmbStatusNovoConteudo" name="status" required class="custom-select rounded">
                                    <option disabled selected>Selecione um status</option>
                                    <option value="0">Não publicado</option>
                                    <option value="1">Publicado</option>
                                    <option value="2">Não listado</option>
                                </select>
                            </div>  --}}
                            <input type="hidden" name="status" value="1">

                            <div class="form-group mb-3 text-left">
                                <label class="" for="txtApoioNovoConteudo">Palavra Chave <small>(opcional)</small></label>
                                <textarea class="form-control" name="apoio" id="txtApoioNovoConteudo" rows="2" maxlength="1000" placeholder="Clique para digitar." style="resize: none"> </textarea>
                            </div>

                            <div class="form-group mb-3 text-left">
                                <label class="" for="txtFonteNovoConteudo">Fonte do conteúdo <small>(opcional)</small></label>
                                <textarea class="form-control" name="fonte" id="txtFonteNovoConteudo" rows="2" maxlength="1000" placeholder="Clique para digitar." style="resize: none"></textarea>
                            </div>

                            <div class="form-group mb-3 text-left">
                                <label class="" for="txtAutoresNovoConteudo">Autores do conteúdo <small>(opcional)</small></label>
                                <textarea class="form-control" name="autores" id="txtAutoresNovoConteudo" rows="2" maxlength="1000" placeholder="Clique para digitar." style="resize: none"></textarea>
                            </div>

                            <div class="row mb-3">
                                <button type="button" data-dismiss="modal" class="btn btn-cancelar mt-4 mb-0 col-4 ml-auto mr-4 font-weight-bold">Cancelar</button>
                                <button type="button" onclick="salvarEdicaoConteudo();" class="btn btn-primary mt-4 mb-0 col-4 ml-4 mr-auto font-weight-bold">Salvar</button>
                            </div>

                        </div>



                    </div>

                </form>

            </div>

        </div>
    </div>
</div>
<!--  Exclusivo Froala Editor  -->
<script>


    new FroalaEditor('txtDescricaoNovoConteudo', {
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
