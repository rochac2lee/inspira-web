<!-- Modal Novo Conteudo -->
       <div class="modal fade" id="divModalNovoConteudoCards" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl  px-1 px-md-5" role="document">
                    <div class="modal-content">
                        <div class="modal-body carreira mainCard">
                          
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <form id="formNovoConteudoCards" method="POST" action="#" onsubmit="return salvarConteudoCards();" enctype="multipart/form-data" class="text-center px-3 shadow-none border-0">
                                @csrf
                                <input name="idAula"  required hidden> 
                                <div id="divLoading" class="text-center">
                                    <i class="fas fa-spinner fa-pulse fa-3x text-primary"></i>
                                </div>

                                <div id="divEnviando" class="text-center d-none">
                                    <i class="fas fa-spinner fa-pulse fa-3x text-primary mb-3"></i>
                                    <h4>Enviando</h4>
                                    <div id="divUploading" class="d-none">
                                        <progress id="progressBar" value="0.5"></progress>
                                        <span id="lblProgress" class="d-block align-middle">(0MB de 0MB)</span>
                                    </div>
                                </div>

                            <div id="divEditar" class="form-page d-none">

                                <div id="page1" class="form-page carreiras">
                                  <!-- cronometro de tempo para cada card -->  
                                    <div class="row" id="cronometrocards">
                                            <div class="col-0">
                                                <input type="checkbox" name="cronometro" id="cronometro" checked >&nbsp;&nbsp;
                                            </div>
                                            <div class="col-0">
                                                <p>Tempo Para Responder:</p>
                                           </div>
                                            <div class="col-1">
                                                <div class="">
                                                    <label class="cards" id="">
                                                        <input type="datetime" name="duracao" class="tempo duracaoAdded" id="endTime"> 
                                     
                                                    </label>
                                                </div>
                                           </div>
                                           <div class="col-sm">    
                                            <button type="button"
                                                    style="margin-left:775px; width:180px;"
                                                   id="btncorrelacaopalavra"
                                                    class="btn-master box-shadow round-10 py-3 btnAddquestao2 "
                                                    <i class="fas fa-plus mr-1"></i>
                                                    Nova Pergunta 
                                            </button>
                                            <button type="button"
                                                    style="margin-left:775px; width:180px;"
                                                   id="btncorrelacaopalavra1"
                                                    class="btn-master box-shadow round-10 py-3 btnAddquestao2 "
                                                    <i class="fas fa-plus mr-1"></i>
                                                    Nova Pergunta 
                                            </button>  
                                                
                                        </div>
                                      
                                    </div>
                       
                                        <h4 id="lblTipoNovoConteudoCards">Tipo de conteudo</h4>
 
                                        <input id="tipo" name="tipo" required hidden>
                                        <input name="idCurso"  value="{{$curso->id}}" required hidden>
                                   
                                        <input name="tipo" required hidden>
                                        <div class="container">
                                            <div class="row">
                                            <div class="col-md-6">
                                            <div class="form-group mb-3 text-left ">
                                            <label class="" for="txtArquivoNovoConteudo">1Título</label>
                                                    <input type="text" class="form-control"  name="titulo" id="titulo" maxlength="30" required><br>
                                            </div>      
                                            </div>
                                  
                                        <div class="col-md-6">    
                                            <button type="button"
                                                    style="margin-left:275px; width:180px;"
                                                    id="addDiv1"
                                                    class="btn-master box-shadow round-10 py-3 btnAddquestao "
                                                    <i class="fas fa-plus mr-1"></i>
                                                    Nova Pergunta 
                                            </button> 
                                                
                                        </div>
                                    </div>
      
                                        <!--
                                        <div class="form-group mb-3 text-left">
                                            <label class="" for="txtDescricaoNovoConteudo">Descrição do conteúdo <small>(opcional)</small></label>
                                            <textarea class="form-control" name="descricao" id="txtDescricaoNovoConteudo" rows="3" placeholder="Clique para digitar..."></textarea>
                                        </div>

                                        <div class="custom-control custom-checkbox form-group mb-3 text-left">
                                            <input type="checkbox" class="custom-control-input" name="obrigatorio" id="ckbObrigatorioNovoConteudo" checked>
                                            <label class="custom-control-label" for="ckbObrigatorioNovoConteudo">Conteúdo obrigatório</label>
                                        </div>> -->

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
                                            <script>
                                               
                                            </script>
                                                <div class="form-group mb-3 text-left">
                                                    <label class="" for="inputAudioNovoConteudo">Clique para fazer upload do áudio</label>
                                                    <br>
                                                    <div class="upload-btn-wrapper">
                                                        <button class="btn btn-lg btn-primary file-name">Selecionar arquivo</button>
                                                        <input type="file" name="arquivoAudio" id="inputAudioNovoConteudo" onchange="mudouArquivoInput(this);"  accept="audio/*" />
                                                    </div>
                                                </div>
                                           
                                                <div class="form-group mb-3 text-left">
                                                    <label class="" for="txtAudioNovoConteudo">Ou digite o link</label>
                                                    <input type="text" class="form-control" name="conteudoAudio" id="txtAudioNovoConteudo" placeholder="Clique para digitar...">
                                                </div>
                                            </div>
                                            <div id="conteudoTipo3" class="tipo">
                                                <div class="form-group mb-3 text-left">
                                                    <label class="" for="inputVideoNovoConteudo">Clique para fazer upload do vídeo</label>
                                                    <br>
                                                    <div class="upload-btn-wrapper">
                                                        <button class="btn btn-lg btn-primary file-name">Selecionar arquivo</button>
                                                        <input type="file" name="arquivoVideo" id="inputVideoNovoConteudo" onchange="mudouArquivoInput(this);"  accept="video/*" />
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3 text-left">
                                                    <label class="" for="txtVideoNovoConteudo">Ou digite o link</label>
                                                    <input type="text" class="form-control" name="conteudoVideo" id="txtVideoNovoConteudo" placeholder="Clique para digitar...">
                                                </div>
                                                <div class="form-group mb-3 text-left" hidden>
                                                    <label class="">Preview:</label>
                                                    <iframe class="d-block" src="https://www.youtube.com/embed/NpEaa2P7qZI" frameborder="0" allow="encrypted-media" style="width: 40vw;height: 25vw;max-width: 1040px;max-height: 586px;"></iframe>
                                                </div>
                                            </div>
                                            <div id="conteudoTipo4" class="tipo">
                                                <div class="form-group mb-3 text-left">
                                                    <label class="" for="inputSlideNovoConteudo">Clique para fazer upload do slide (Powerpoint, PDF, HTML)</label>
                                                    <br>
                                                    <div class="upload-btn-wrapper">
                                                        <button class="btn btn-lg btn-primary file-name">Selecionar arquivo</button>
                                                        <input type="file" name="arquivoSlide" id="inputSlideNovoConteudo" onchange="mudouArquivoInput(this);"  accept="application/pdf, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.slideshow, application/vnd.openxmlformats-officedocument.presentationml.presentation, .pps, .pptx" />
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3 text-left">
                                                    <label class="" for="txtSlideNovoConteudo">Ou digite o link</label>
                                                    <input type="text" class="form-control" name="conteudoSlide" id="txtSlideNovoConteudo" placeholder="Clique para digitar...">
                                                </div>
                                            </div>
                                            <div id="conteudoTipo20" class="tipo">
                                            {{ csrf_field() }}
                                             <div class="scrolly">
                                                <div class="conteudoquiz ">
                                              
                                                     <!-- <div class="divscrol"> 
                                                            <div class="form-group mb-3 text-left">
                                                                <label class="" for="txtQuizNovoConteudo">Pergunta 1:</label>
                                                                  <textarea id="txtQuizNovoConteudo" name="conteudoQuiz[]" class="md-textarea form-control " rows="2"></textarea>   
                                                            </div>

                                                            <div class="custom-control custom-radio mb-3 text-left">
                                                                 <input type="radio" id="rdoAlternativa1" name="conteudoQuizAlternativaCorreta[]" value="1" class="custom-control-input"> 
                                                                <label class="" for="rdoAlternativa1">Imagem 1:</label><br>
                                                                <label class="labelInput">
                                                                    <input id="file" name="imagem" type="file" />
                                                                   
                                                                </label>
                                                               
                                                            </div>

                                                            <div class="custom-control custom-radio mb-3 text-left">
                                                                 <input type="radio" id="rdoAlternativa1" name="conteudoQuizAlternativaCorreta[]" value="1" class="custom-control-input"> 
                                                                <label class="" for="rdoAlternativa1">Imagem 2:</label><br>
                                                                <label class="LabelInputFile">
                                                                    <input id="file" name="imagem" type="file"/>
                                                                    
                                                                </label>
                                                               
                                                            </div>

                                                            <br>
                                                            <div class="form-group">
                                                                <button type="button" class=" verdFals btn btn-outline-success">
                                                                    <input type="checkbox" name= "conteudoQuizAlternativaVerdadeira[]" id="checkverd" value="1" autocomplete="off" />Verdadeiro</button>
                                                                <button type="button" class=" verdFals btn btn-outline-danger">
                                                                    <input type="checkbox" class="conteudoQuizAlternativaFalsa[]" id="checkverd" value="2" >Falso</button>
                                                            </div>
                                                           
                                                    </div>   -->
                                                    <div class="divscrol"> 
                                                    <div class="newquestion1"></div>
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

                       
                                            <!-- <div id="conteudoTipo6" class="tipo">
                                                <div class="form-group mb-3 text-left">
                                                    <label class="" for="inputArquivoNovoConteudo">Clique para fazer upload do arquivo</label>
                                                    <br>
                                                    <div class="upload-btn-wrapper">
                                                        <button class="btn btn-lg btn-primary file-name">Selecionar arquivo</button>
                                                        <input type="file" name="arquivoArquivo" id="inputArquivoNovoConteudo" onchange="mudouArquivoInput(this);" />
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3 text-left">
                                                    <label class="" for="txtArquivoNovoConteudo">Ou digite o link</label>
                                                    <input type="text" class="form-control" name="conteudoArquivo" id="txtArquivoNovoConteudo" placeholder="Clique para digitar...">
                                                </div>
                                            </div> -->
                                            <div id="conteudoTipo19" class="tipo">
                                            <div class="scrolly">
                                                <div class="conteudoquiz ">
                                              
                                                        <div class="divscrol">
                                                            <div class="newquestion2"></div>
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
                                                                <input type="radio" id="rdoAlternativa1" name="conteudoQuizAlternativaCorreta[]" value="1" class="custom-control-input">
                                                                <label class="custom-control-label" for="rdoAlternativa1">{{ucfirst($langResposta)}} 1:</label>
                                                                <input type="text" class="form-control d-inline-block mx-2" name="conteudoQuizAlternativa[]" id="txtQuizAlternativa1NovoConteudo" maxlength="150" placeholder="Digite aqui a 1ª {{$langResposta}}..." style="width: calc(95% - 80px);">
                                                            </div>

                                                            <div class="custom-control custom-radio mb-3 text-left">
                                                                <input type="radio" id="rdoAlternativa2" name="conteudoQuizAlternativaCorreta[]" value="2" class="custom-control-input">
                                                                <label class="custom-control-label" for="rdoAlternativa2">{{ucfirst($langResposta)}} 2:</label>
                                                                <input type="text" class="form-control d-inline-block mx-2" name="conteudoQuizAlternativa[]" id="txtQuizAlternativa2NovoConteudo" maxlength="150" placeholder="Digite aqui a 2ª {{$langResposta}}..." style="width: calc(95% - 80px);">
                                                            </div>

                                                            <div class="custom-control custom-radio mb-6 text-left">
                                                                <input type="radio" id="rdoAlternativa3" name="conteudoQuizAlternativaCorreta[]" value="3" class="custom-control-input">
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
                                            <div id="conteudoTipo9" class="tipo">
                                                <div class="form-group mb-3">
                                                    <select class="custom-select form-control d-inline-block mr-2" id="cmbQuestaoAtual" onchange="mudouPerguntaProvaAtual();" style="width: auto; min-width: 200px;">
                                                        <option value="1" selected>{{ucfirst($langQuestao)}} 1</option>
                                                    </select>
                                                    <input type="text" class="form-control d-inline-block" id="txtPesoPergunta" placeholder="Peso da pergunta" style="width: auto; min-width: 200px;">
                                                </div>
                                                <div class="form-group mb-3 text-left">
                                                    <label class="" for="txtDissertativaNovoConteudo" style="color: #999FB4;">Tipo de {{$langResposta}}:</label>
                                                    <div class="btn-group btn-group-toggle d-inline-block" data-toggle="buttons">
                                                        <label id="btnTipoResposta1" class="btn active">
                                                            <input type="radio" name="options" id="rdoTipoResposta1" autocomplete="off" onchange="mudouTipoRespostaProva(this.id);" checked> Dissertativa
                                                        </label>
                                                        <label id="btnTipoResposta2" class="btn">
                                                            <input type="radio" name="options" id="rdoTipoResposta2" autocomplete="off" onchange="mudouTipoRespostaProva(this.id);"> Multipla escolha
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
                                                        <input type="text" class="form-control" id="txtPerguntaQuiz" maxlength="150" placeholder="Digite aqui sua pergunta...">
                                                    </div>
                                                    <div class="custom-control custom-radio mb-3 text-left">
                                                        <input type="radio" id="rdoAlternativaMultiplaEscolha1" name="alternativaCorretaMultiplaEscolha" value="1" class="custom-control-input">
                                                        <label class="custom-control-label" for="rdoAlternativaMultiplaEscolha1">{{ucfirst($langResposta)}} 1:</label>
                                                        <input type="text" class="form-control d-inline-block mx-2" id="txtQuizAlternativa1" maxlength="150" placeholder="Digite aqui a 1ª {{$langResposta}}..." style="width: calc(95% - 80px);">
                                                    </div>
                                                    <div class="custom-control custom-radio mb-3 text-left">
                                                        <input type="radio" id="rdoAlternativaMultiplaEscolha2" name="alternativaCorretaMultiplaEscolha" value="2" class="custom-control-input">
                                                        <label class="custom-control-label" for="rdoAlternativaMultiplaEscolha2">{{ucfirst($langResposta)}} 2:</label>
                                                        <input type="text" class="form-control d-inline-block mx-2" id="txtQuizAlternativa2" maxlength="150" placeholder="Digite aqui a 2ª {{$langResposta}}..." style="width: calc(95% - 80px);">
                                                    </div>
                                                    <div class="custom-control custom-radio mb-3 text-left">
                                                        <input type="radio" id="rdoAlternativaMultiplaEscolha3" name="alternativaCorretaMultiplaEscolha" value="3" class="custom-control-input">
                                                        <label class="custom-control-label" for="rdoAlternativaMultiplaEscolha3">{{ucfirst($langResposta)}} 3:</label>
                                                        <input type="text" class="form-control d-inline-block mx-2" id="txtQuizAlternativa3" maxlength="150" placeholder="Digite aqui a 3ª {{$langResposta}}..." style="width: calc(95% - 80px);">
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3 text-left">
                                                    <button id="btnAdicionarPergunta" type="button" onclick="adicionarNovaPerguntaProva();" class="btn btn-primary btn-block signin-button mt-4 mb-0 col-4 font-weight-bold">
                                                        Adicionar nova pergunta
                                                    </button>
                                                </div>
                                                <input name="conteudoProva" id="txtPerguntas" hidden>
                                            </div>
                                            <div id="conteudoTipo16" class="tipo" >
                                                <div class="col-6">
                                                    <div class="adicionacampo">
                                                        <label class="" for="txtConteudoEntregavel">Palavra</label>
                                                       <input type="text" name="cacapalavras[]" class="form-control" id="cacapalavra">
                                                    
                                                    <div class="col-topicos mt-3 col-lg-12 col-12">
                                                        <div class="container-input-topico form-group">
                                                    </div>
                                                    </div>
                                                </div>                                                
                                                    <div class="col-lg-12 col-12 mt-3">
                                                        <div class="row btnAdd1 d-flex flex-nowrap">
                                                            <div class="col-lg-4 col-12">
                                                                <button type="button" class="btn addDiv btnAdd1 btn-secondary btn-block" id="1">
                                                                    <i class="fas fa-plus"></i> ADICIONAR PALAVRA
                                                                </button>                           
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="conteudoTipo12" class="tipo">
                                                <div class="form-group mb-3 text-left">
                                                   
                                                </div>
                                                <div class="form-group mb-3 text-left">
                                                  
                                                    <input type="text" class="form-control" name="palavra" id="txtPalavraNovoConteudo" placeholder="Escreva a palavra a ser adivinhada">
                                                </div>
                                                
                                            </div>
                                            <div id="conteudoTipo13" class="tipo" >
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 text-left ">
                                                                <input type="text" class=" form-control"  name="enunciado" id="enunciadoverfalNovoConteudo"  placeholder="Espaço para o enunciado" maxlength="100"required>
                                                            </div>      
                                                        </div> 
                                                        <div class="col-md-6"> 
                                                        </div>
                                                    </div>
                                                    <br>
                                                            <div class="form-group mb-3 text-left vf">
                                                                <div class="md-form " >
                                                                {{ csrf_field() }}
                                                                    <div class="col-md-12  " id="verdadeirofalso">
                                                                        <input type="checkbox" data-width="150" data-height="-20" value="1" name="verdadeirofalso[]"  data-toggle="toggle" data-on="Verdadeira" data-off="Falso" data-onstyle="success" data-offstyle="danger">
                                                                    </div>
                                                                    <div class="md-form mb-4 blue-textarea active-blue-textarea ">
                                                                        <h12> Afirmação</h12>                                               
                                                                        <textarea id="afirmacao1" name="afirmacao[]" class="md-textarea form-control " rows="2" style="resize: none"></textarea>                                   
                                                                    </div>
                                                                </div>
                                                                <div class="md-form">
                                                                    <div class="col-md-12 " id="verdadeirofalso">
                                                                        <input type="checkbox" class="campoDefault" data-width="150" value="2" data-height="-20"  name="verdadeirofalso2" data-toggle="toggle" data-on="Verdadeiro" data-off="Falsa" data-onstyle="success" data-offstyle="danger">    
                                                                    </div>
                                                                    <div class="md-form mb-4 blue-textarea active-blue-textarea">
                                                                        <h12>Afirmação</h12>
                                                                        <textarea id="afirmacao2" name="afirmacao2" class="md-textarea form-control campoDefault" rows="2" style="resize: none"></textarea>  </div> 
                                                                    </div>
                                                                </div> 
                                                        </div>
                                                     </div> 
                                                     <input type="hidden" name="status" value="1">
                                                           
                                                </div>
                                            </div>
                        <!-- <div class="form-group mb-3 text-left">
                                                <label class="" for="txtApoioNovoConteudo"><small>(opcional)</small></label>
                                                <textarea class="form-control" name="apoio" id="txtApoioNovoConteudo" rows="2" placeholder="Clique para digitar..."></textarea>
                                            </div>

                                            <div class="form-group mb-3 text-left">
                                                <label class="" for="txtFonteNovoConteudo">Fonte do conteúdo <small>(opcional)</small></label>
                                                <textarea class="form-control" name="fonte" id="txtFonteNovoConteudo" rows="2" placeholder="Clique para digitar..."></textarea>
                                            </div>

                                            <div class="form-group mb-3 text-left">
                                                <label class="" for="txtAutoresNovoConteudo">Autores do conteúdo <small>(opcional)</small></label>
                                                <textarea class="form-control" name="autores" id="txtAutoresNovoConteudo" rows="2" placeholder="Clique para digitar..."></textarea>
                                            </div> -->
                                            <hr>
                                        <div class="row ">
                                       
                                        <button type="button" data-dismiss="modal" class="btn btn-primary btn-block outline-button mt-4 mb-0 col-1 ml-auto mr-4 font-weight-bold" >Excluir   
                                            </button>
                                            <button type="button" onclick="salvarConteudoCards();" class="btn btn-primary btn-block outline-button  mt-4 mb-0 col-1 ml-4 mr-auto text-dark-right font-weight-bold">Salvar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
             </div> 
            


