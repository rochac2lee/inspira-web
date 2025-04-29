<div class="row justify-content-center">
    <div class="col-8 ">
        <div class="row roteiro_edita_menu">
            <a href="#" class="col text-center border p-1 rounded my-2 ml-0 bg-white item-menu">
                <div class="icon rounded bg-white d-inline-block text-secondary p-1 h4 mr-1 mb-0">
                    <i class="fas fa-book"></i>
                </div>
                <div class="title text-dark w-100">Conteúdo</div>
                <div class="dropdown-menu-roteiro">
                    <button class="dropdown-item py-2 border-bottom border-top" type="button" onclick="novoConteudoTexto(1)"><i class="fas fa-font"></i> Texto</button>
                    <button class="dropdown-item py-2 border-bottom" type="button" onclick="novoConteudoAudio()"><i class="fas fa-podcast"></i> Áudio</button>
                    <button class="dropdown-item py-2 border-bottom" type="button" onclick="novoConteudoVideo()"><i class="fas fa-video"></i> Vídeo</button>
                    <button class="dropdown-item py-2 border-bottom" type="button" onclick="novoConteudoSlide()"><i class="fas fa-file-powerpoint"></i> Slide</button>
                    <button class="dropdown-item py-2 border-bottom" type="button" onclick="novoConteudoPdf()"><i class="fas fa-file-pdf"></i> PDF</button>
                    <button class="dropdown-item py-2 border-bottom" type="button" onclick="novoConteudoArquivo()"><i class="fas fa-upload"></i> Arquivos</button>
                </div>
            </a>
            <a href="#" class="col text-center border p-1 rounded my-2 ml-2 bg-white item-menu">
                <div class="icon rounded bg-white d-inline-block text-secondary p-1 h4 mr-1 mb-0">
                    <i class="fas fa-gamepad"></i>
                </div>
                <div class="title text-dark w-100">Atividades</div>
                <div class="dropdown-menu-roteiro">
                    <button class="dropdown-item py-2 border-bottom border-top" type="button" onclick="novoConteudoTexto(7)"><i class="fas fa-comment-alt"></i> Dissertativa</button>
                    <button class="dropdown-item py-2 border-bottom" type="button" onclick="novoConteudoQuiz(8)"><i class="fas fa-list-ul"></i> Quiz/Roteiro</button>
                </div>
            </a>
            @if(/*auth()->user()->permissao  != 'P'*/false)
            <a href="javascript:void(0);" onclick="novoConteudoTexto(10);" class="col text-center border p-1 rounded my-2 ml-2 bg-white item-menu">
                <div class="icon rounded bg-white d-inline-block text-secondary p-1 h4 mr-1 mb-0">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="title text-dark w-100 " >Entregável</div>
            </a>
            @endif
            <a href="javascript:void(0)" onclick="novoConteudoBiblioteca()" class="col text-center border p-1 rounded my-2 ml-2 bg-white item-menu">
                <div class="icon rounded bg-white d-inline-block text-secondary p-1 h4 mr-1 mb-0">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="title text-dark w-100">Biblioteca</div>
            </a>
        </div>
    </div>
</div>
<h4 class="my-4 py-3 border-bottom">
    {{$tema->titulo}}
    <div class="btns_gestao_da_aula float-right">
        <button type="button" onclick="editarTema({{$tema->id}})" class="btn btn-nova-aula " data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar tema"><i class="fas fa-pencil-alt fa-fw"></i></button>
        <!--<button disabled type="button" onclick="importarAulaConteudo()" class="btn btn-nova-aula" data-toggle="tooltip" data-placement="top" title="" data-original-title="Importar conteúdo para tema"><i class="fas fa-upload"></i></button>-->
        <a href="{{url('/gestao/roteiros/duplicarTema/'.$curso_id.'/'.$tema->id)}}"  type="button"  class="btn btn-nova-aula" data-toggle="tooltip" data-placement="top" title="" data-original-title="Duplicar tema"><i class="fas fa-copy fa-fw"></i></a>
        <!-- <button disabled  class="targetExportIdAula btn btn-nova-aula" title="" data-toggle="tooltip" data-placement="top" data-original-title="Exportar tema"><i class="fas fa-download"></i></button> -->
        <button type="button" onclick="excluirTema('{{$tema->titulo}}', {{$tema->id}})" class="btn btn-nova-aula" data-toggle="tooltip" data-placement="top" title="" data-original-title="Excluír tema"><i class="fas fa-trash fa-fw"></i></button>
    </div>
</h4>
<div class="row mb-4">
    <div class="col roteiro-itens">
        <ul id="sortableConteudo" class="list-style">
            @foreach($tema->conteudo as $c)
                <li id="{{$c->id}}" class="ui-state-default d-flex">
                    <div class="p-2 flex-grow-1"><i class="fas fa-bars"></i> {{$c->titulo}}</div>
                    <div class="p-2 mr-2"><i class="fa {{$c->tipo_icon}}"></i> {{$c->tipo_nome}}</div>
                    <div class="p-2 mr-2">{{$c->updated_at->format('d \d\e M \d\e Y \à\s H:i')}}</div>
                    <div class="p-2">
                        <button class="notification dropdown-toggle-split mt-0 border-0 bg-white p-0" id="dropdownMenuNot" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button>
                        <div class="notifications dropdown-menu dropdown-menu-right bg-dark text-white">
                            @if($c->criado_em_roteiros == 1)
                                <a href="javascript:void(0)" onclick="editarConteudo({{$c->pivot->curso_id}}, {{$tema->id}}, {{$c->id}})">Editar Conteúdo</a>
                                <!-- <a href="#">Exportar Conteúdo</a> -->
                            @endif
                            <a href="{{url('/gestao/roteiros/duplicarConteudo/'.$c->pivot->curso_id.'/'.$tema->id.'/'.$c->id)}}">Duplicar Conteúdo</a>
                            <a href="javascript:void(0)" onclick="excluirConteudo('{{$c->titulo}}', {{$c->pivot->curso_id}}, {{$tema->id}}, {{$c->id}})">Excluir Conteúdo</a>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
<script>

    function excluirArquivo(esconder, mostrar){
        $('#'+esconder).hide();
        $('#'+mostrar).show();
        $('#existeArquivo').val('');
        $('#inputNomeArquivo').val('');
    }

    function limpaConteudoBase(){
        $('#conteudoConteudoId').val('');
        $('#existeArquivo').val('');
        $('#inputNomeArquivo').val('');
        $('#conteudoAulaId').val('');
        $('#conteudoObrigatorio').prop('checked',false);
        $('#conteudoTitulo').val('');
        $('#conteudoTitulo').removeClass('is-invalid');
        $('#conteudoDescricao').val('');
        $('#conteudoDescricao').removeClass('is-invalid');
        $('#conteudoApoio').val('');
        $('#conteudoFonte').val('');
        $('#conteudoAutores').val('');
        selectEtapaAno.set('');
        $('#msgErroCicloEtapa').removeClass('d-block');
        selectComponente.set('');
        $('#msgErroDisciplina').removeClass('d-block');
    }

    function novoConteudoBiblioteca(){
        $('#conteudoAulaIdBiblioteca').val(temaIdGlobal);
        $('#modalConteudoBiblioteca').modal('show')
    }


    function novoConteudoTexto(tipo = 1){
        limpaConteudoBase();

        $('#tituloModalConteudo').html('Cadastrar conteúdo de texto');
        if(tipo == 7){
            $('#tituloModalConteudo').html('Cadastrar atividade dissertativa');
        }else if(tipo == 10){
            $('#tituloModalConteudo').html('Cadastrar conteudo entregável');
        }
        $('#conteudoAulaTipo').val(tipo);
        $('#conteudoAulaId').val(temaIdGlobal);
        $('#conteudoOp').val('inserir');

        froalaConteudo.html.set('');
        $('#msgErroConteudo_1').removeClass('d-block');

        $('.elementosConteudo').hide();
        $('#elementoConteudoTexto').show();

        $('#modalConteudo').modal('show')
    }

    function novoConteudoAudio(){
        limpaConteudoBase();
        $('#tituloModalConteudo').html('Cadastrar conteúdo de áudio');
        $('#conteudoAulaTipo').val(2);
        $('#conteudoAulaId').val(temaIdGlobal);
        $('#conteudoOp').val('inserir');

        $('#FormGroupAudioDownload').hide();
        $('#FormGroupAudioFile').show();

        $('#audioConteudo').val('')
        $('#msgErroConteudoFile_2').removeClass('d-block');
        $('#audioLinkConteudo').val('')
        $('#msgErroConteudoLink_2').removeClass('d-block');

        $('.elementosConteudo').hide();
        $('#elementoConteudoAudio').show();

        $('#modalConteudo').modal('show')
    }

    function novoConteudoVideo(){
        limpaConteudoBase();
        $('#tituloModalConteudo').html('Cadastrar conteúdo de vídeo');
        $('#conteudoAulaTipo').val(3);
        $('#conteudoAulaId').val(temaIdGlobal);
        $('#conteudoOp').val('inserir');

        $('#FormGroupVideoDownload').hide();
        $('#FormGroupVideoFile').show();

        $('#videoConteudo').val('')
        $('#msgErroConteudoFile_3').removeClass('d-block');
        $('#videoLinkConteudo').val('')
        $('#msgErroConteudoLink_3').removeClass('d-block');

        $('.elementosConteudo').hide();
        $('#elementoConteudoVideo').show();

        $('#modalConteudo').modal('show')
    }

    function novoConteudoSlide(){
        limpaConteudoBase();
        $('#tituloModalConteudo').html('Cadastrar conteúdo de slide');
        $('#conteudoAulaTipo').val(4);
        $('#conteudoAulaId').val(temaIdGlobal);
        $('#conteudoOp').val('inserir');

        $('#FormGroupSlideDownload').hide();
        $('#FormGroupSlideFile').show();

        $('#slideConteudo').val('')
        $('#msgErroConteudoFile_4').removeClass('d-block');
        $('#slideLinkConteudo').val('')
        $('#msgErroConteudoLink_4').removeClass('d-block');

        $('.elementosConteudo').hide();
        $('#elementoConteudoSlide').show();

        $('#modalConteudo').modal('show')
    }

    function novoConteudoPdf(){
        limpaConteudoBase();
        $('#tituloModalConteudo').html('Cadastrar conteúdo de PDF');
        $('#conteudoAulaTipo').val(15);
        $('#conteudoAulaId').val(temaIdGlobal);
        $('#conteudoOp').val('inserir');

        $('#FormGroupPdfDownload').hide();
        $('#FormGroupPdfFile').show();

        $('#pdfConteudo').val('')
        $('#msgErroConteudoFile_15').removeClass('d-block');
        $('#pdfLinkConteudo').val('')
        $('#msgErroConteudoLink_15').removeClass('d-block');

        $('.elementosConteudo').hide();
        $('#elementoConteudoPdf').show();

        $('#modalConteudo').modal('show')
    }

    function novoConteudoArquivo(){
        limpaConteudoBase();
        $('#tituloModalConteudo').html('Cadastrar conteúdo de arquivo');
        $('#conteudoAulaTipo').val(6);
        $('#conteudoAulaId').val(temaIdGlobal);
        $('#conteudoOp').val('inserir');

        $('#FormGroupArquivoDownload').hide();
        $('#FormGroupArquivoFile').show();

        $('#arquivoConteudo').val('')
        $('#msgErroConteudoFile_6').removeClass('d-block');
        $('#arquivoLinkConteudo').val('')
        $('#msgErroConteudoLink_6').removeClass('d-block');

        $('.elementosConteudo').hide();
        $('#elementoConteudoArquivo').show();

        $('#modalConteudo').modal('show')
    }

    function novoConteudoQuiz(){
        limpaConteudoBase();

        $('#tituloModalConteudo').html('Cadastrar atividade Quiz');

        $('#conteudoAulaTipo').val(8);
        $('#conteudoAulaId').val(temaIdGlobal);
        $('#conteudoOp').val('inserir');

        froalaQuiz.html.set('');
        $('#msgErroConteudo_8').removeClass('d-block');

        froalaAlternativa1.html.set('');
        $('#msgErroAlternativa_1').removeClass('d-block');
        froalaAlternativa2.html.set('');
        $('#msgErroAlternativa_2').removeClass('d-block');
        froalaAlternativa3.html.set('');
        $('#msgErroAlternativa_3').removeClass('d-block');

        $('.alternativaCorreta').prop('checked',false);
        $('#msgErroCorreta').removeClass('d-block');


        $('.elementosConteudo').hide();
        $('#elementoConteudoQuiz').show();

        $('#modalConteudo').modal('show')
    }

    function popularConteudoBase(dados){

        if(dados.obrigatorio == '1')
            $('#conteudoObrigatorio').prop('checked',true);
        else
            $('#conteudoObrigatorio').prop('checked',false);
        $('#conteudoTitulo').val(dados.titulo);
        $('#conteudoTitulo').removeClass('is-invalid');
        $('#conteudoDescricao').val(dados.descricao);
        $('#conteudoDescricao').removeClass('is-invalid');
        $('#conteudoApoio').val(dados.apoio);
        $('#conteudoFonte').val(dados.fonte);
        $('#conteudoAutores').val(dados.autores);
        selectEtapaAno.set(dados.ciclo_id+';'+dados.cicloetapa_id);
        $('#msgErroCicloEtapa').removeClass('d-block');
        selectComponente.set(dados.disciplina_id);
        $('#msgErroDisciplina').removeClass('d-block');
    }

    function editarConteudoTexto(dados, tipo = 1){
        $('#tituloModalConteudo').html('Editar conteúdo de texto');
        if(tipo == 7){
            $('#tituloModalConteudo').html('Editar atividade dissertativa');
        } else if(tipo == 10){
            $('#tituloModalConteudo').html('Editar conteúdo entregável');
        }
        $('#conteudoAulaTipo').val(tipo);
        popularConteudoBase(dados);
        if(tipo == 1 || tipo == 10) {
            froalaConteudo.html.set(dados.conteudo);
        }else{
            froalaConteudo.html.set(dados.pergunta);
        }

        $('#msgErroConteudo_1').removeClass('d-block');

        $('.elementosConteudo').hide();
        $('#elementoConteudoTexto').show();

        $('#modalConteudo').modal('show')
    }

    function editarConteudoAudio(dados){
        $('#tituloModalConteudo').html('Editar conteúdo de audio');
        $('#conteudoAulaTipo').val(2);
        popularConteudoBase(dados);

        $('#audioConteudo').val('')
        $('#msgErroConteudoFile_2').removeClass('d-block');
        $('#audioLinkConteudo').val('')
        $('#msgErroConteudoLink_2').removeClass('d-block');

        if(dados.eh_link == '1'){
            $('#audioLinkConteudo').val(dados.conteudo)
            $('#FormGroupAudioDownload').hide();
            $('#FormGroupAudioFile').show();
        }else{
            $('#existeArquivo').val(1);
            $('#FormGroupAudioDownload').show();
            $('#FormGroupAudioFile').hide();
            $('#nomeArquivoAudio').html(dados.nome_arquivo);
            $('#inputNomeArquivo').val(dados.nome_arquivo);
            $('#linkDownloadAudio').prop('href','{{url('/gestao/roteiros/download')}}/'+dados.id+'/'+replaceArquivo(dados.conteudo));
            $('#conteudo_download_2').val(dados.conteudo);
        }

        $('.elementosConteudo').hide();
        $('#elementoConteudoAudio').show();

        $('#modalConteudo').modal('show')
    }

    function editarConteudoVideo(dados){
        $('#tituloModalConteudo').html('Editar conteúdo de vídeo');
        $('#conteudoAulaTipo').val(3);
        popularConteudoBase(dados);

        $('#audioConteudo').val('')
        $('#msgErroConteudoFile_3').removeClass('d-block');
        $('#audioLinkConteudo').val('')
        $('#msgErroConteudoLink_3').removeClass('d-block');

        if(dados.eh_link == '1'){
            $('#videoLinkConteudo').val(dados.conteudo)
            $('#FormGroupVideoDownload').hide();
            $('#FormGroupVideoFile').show();
        }else{
            $('#existeArquivo').val(1);
            $('#FormGroupVideoDownload').show();
            $('#FormGroupVideoFile').hide();
            $('#nomeArquivoVideo').html(dados.nome_arquivo);
            $('#inputNomeArquivo').val(dados.nome_arquivo);
            $('#linkDownloadVideo').prop('href','{{url('/gestao/roteiros/download')}}/'+dados.id+'/'+replaceArquivo(dados.conteudo));
            $('#conteudo_download_3').val(dados.conteudo);
        }

        $('.elementosConteudo').hide();
        $('#elementoConteudoVideo').show();

        $('#modalConteudo').modal('show')
    }

    function editarConteudoSlide(dados){
        $('#tituloModalConteudo').html('Editar conteúdo de vídeo');
        $('#conteudoAulaTipo').val(4);
        popularConteudoBase(dados);

        $('#slideConteudo').val('')
        $('#msgErroConteudoFile_4').removeClass('d-block');
        $('#slideLinkConteudo').val('')
        $('#msgErroConteudoLink_4').removeClass('d-block');

        if(dados.eh_link == '1'){
            $('#slideLinkConteudo').val(dados.conteudo)
            $('#FormGroupSlideDownload').hide();
            $('#FormGroupSlideFile').show();
        }else{
            $('#existeArquivo').val(1);
            $('#FormGroupSlideDownload').show();
            $('#FormGroupSlideFile').hide();
            $('#nomeArquivoSlide').html(dados.nome_arquivo);
            $('#inputNomeArquivo').val(dados.nome_arquivo);
            $('#linkDownloadSlide').prop('href','{{url('/gestao/roteiros/download')}}/'+dados.id+'/'+replaceArquivo(dados.conteudo));
            $('#conteudo_download_4').val(dados.conteudo);
        }

        $('.elementosConteudo').hide();
        $('#elementoConteudoSlide').show();

        $('#modalConteudo').modal('show')
    }

    function editarConteudoPdf(dados){
        $('#tituloModalConteudo').html('Editar conteúdo de PDF');
        $('#conteudoAulaTipo').val(15);
        popularConteudoBase(dados);

        $('#pdfConteudo').val('')
        $('#msgErroConteudoFile_15').removeClass('d-block');
        $('#pdfLinkConteudo').val('')
        $('#msgErroConteudoLink_15').removeClass('d-block');

        if(dados.eh_link == '1'){
            $('#pdfLinkConteudo').val(dados.conteudo)
            $('#FormGroupPdfDownload').hide();
            $('#FormGroupPdfFile').show();
        }else{
            $('#existeArquivo').val(1);
            $('#FormGroupPdfDownload').show();
            $('#FormGroupPdfFile').hide();
            $('#nomeArquivoPdf').html(dados.nome_arquivo);
            $('#inputNomeArquivo').val(dados.nome_arquivo);
            $('#linkDownloadPdf').prop('href','{{url('/gestao/roteiros/download')}}/'+dados.id+'/'+replaceArquivo(dados.conteudo));
            $('#conteudo_download_15').val(dados.conteudo);
        }

        $('.elementosConteudo').hide();
        $('#elementoConteudoPdf').show();

        $('#modalConteudo').modal('show')
    }

    function editarConteudoArquivo(dados){
        $('#tituloModalConteudo').html('Editar conteúdo de arquivo');
        $('#conteudoAulaTipo').val(6);
        popularConteudoBase(dados);

        $('#arquivoConteudo').val('')
        $('#msgErroConteudoFile_6').removeClass('d-block');
        $('#arquivoLinkConteudo').val('')
        $('#msgErroConteudoLink_6').removeClass('d-block');

        if(dados.eh_link == '1'){
            $('#arquivoLinkConteudo').val(dados.conteudo)
            $('#FormGroupArquivoDownload').hide();
            $('#FormGroupArquivoFile').show();
        }else{
            $('#existeArquivo').val(1);
            $('#FormGroupArquivoDownload').show();
            $('#FormGroupArquivoFile').hide();
            $('#nomeArquivoArquivo').html(dados.nome_arquivo);
            $('#inputNomeArquivo').val(dados.nome_arquivo);
            $('#inputNomeArquivo').val(dados.nome_arquivo);
            $('#conteudo_download_6').val(dados.conteudo);
            $('#linkDownloadArquivo').prop('href','{{url('/gestao/roteiros/download')}}/'+dados.id+'/'+replaceArquivo(dados.conteudo));
        }

        $('.elementosConteudo').hide();
        $('#elementoConteudoArquivo').show();

        $('#modalConteudo').modal('show')
    }

    function editarConteudoQuiz(dados){

        $('#tituloModalConteudo').html('Editar atividade Quiz');

        $('#conteudoAulaTipo').val(8);
        popularConteudoBase(dados);

        froalaQuiz.html.set(dados.pergunta);
        $('#msgErroConteudo_8').removeClass('d-block');

        froalaAlternativa1.html.set(dados.alternativas[0]);
        $('#msgErroAlternativa_1').removeClass('d-block');
        froalaAlternativa2.html.set(dados.alternativas[1]);
        $('#msgErroAlternativa_2').removeClass('d-block');
        froalaAlternativa3.html.set(dados.alternativas[2]);
        $('#msgErroAlternativa_3').removeClass('d-block');

        $('.alternativaCorreta').prop('checked',false);
        $("input[name=correta][value=" + dados.correta + "]").prop('checked', true);
        $('#msgErroCorreta').removeClass('d-block');


        $('.elementosConteudo').hide();
        $('#elementoConteudoQuiz').show();

        $('#modalConteudo').modal('show')
    }

    function editarConteudo(cursoID, temaID, conteudoID){
        limpaConteudoBase();
        $.ajax({
            url: '{{url('/gestao/roteiros/getConteudoAjax')}}',
            type: 'post',
            dataType: 'json',
            data: {
                aula_id: temaID,
                curso_id: cursoID,
                conteudo_id: conteudoID,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $('#conteudoOp').val('editar');
                $('#conteudoAulaId').val(temaID);
                $('#conteudoConteudoId').val(data.id);
                if(data.tipo == '1') {
                    editarConteudoTexto(data, 1);
                }else if(data.tipo == '2'){
                    editarConteudoAudio(data);
                }else if(data.tipo == '3'){
                    editarConteudoVideo(data);
                }else if(data.tipo == '4'){
                    editarConteudoSlide(data);
                }else if(data.tipo == '15'){
                    editarConteudoPdf(data);
                }else if(data.tipo == '6'){
                    editarConteudoArquivo(data);
                }else if(data.tipo == '7'){
                    editarConteudoTexto(data, 7);
                }else if(data.tipo == '8'){
                    editarConteudoQuiz(data);
                }else if(data.tipo == '10'){
                    editarConteudoTexto(data, 10);
                }
            },
            error: function() {
                swal("", "Não foi possível carregar o conteudo.", "error");
            }
        });
    }

    function replaceArquivo(link){
        const lastIndex = link.lastIndexOf('.');
        const replacement = '_';
        return link.substring(0, lastIndex) + replacement + link.substring(lastIndex + 1);
    }
</script>
