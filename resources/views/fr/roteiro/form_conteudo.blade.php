@extends('fr/master')
@section('content')
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery/jquery-ui.js"></script>

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
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/special_characters.min.js"></script>    
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/link.min.js"></script>  <!--  FIM Froala Editor  -->

    <!-- Codigo para adicionar a matemática-->
    <script type="text/javascript" src="{{url('/fr/includes/froala_editor_v4/node_modules/wiris/mathtype-froala3/wiris.js')}}"></script>

    <!-- Codigo para mostrar o resultado --->
    <script type="text/javascript" src="{{url('/fr/includes/froala_editor_v4/js/plugins/froala_wiris/integration/WIRISplugins.js?viewer=image')}}"></script>
    <!-- Style exclusiva desta página --->
    <style type="text/css">
        .dropdown-menu-roteiro{
            margin-top: 3px;
            margin-left: -5px;
            position: absolute;
            background-color: #ffffff;
            width: 101.3%;
            z-index: 20;
            border: 1px solid #dee2e6;
            border-top: none;
            text-align: left;
            display: none;
        }
    </style>

    <section class="section section-interna">
        <div class="container-fluid">
            <h3 class="pb-3 border-bottom mb-4">
                <a href="{{url('/gestao/roteiros')}}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                Editar Conteúdo do Roteiro
                <span class="float-right">
                    <a href="{{url('/gestao/roteiros/iniciarRoteiro/'.$roteiro->id)}}" class="btn btn-default mt-0 ml-1"><i class="fas fa-search"></i> Visão do aluno</a>
                </span>
                <small>
                    <br>{{$roteiro->titulo}}
                </small>
            </h3>

            <div class="row">
                <div class="col-md-3 p-0 bg-light border-right p-4">
                    <div class="row">
                        <h5 class="w-100 text-center">Temas</h5>
                        <button onclick="novoTema()" class="btn btn-default w-100 mb-4">
                            <i class="fas fa-plus"></i>
                            Novo Tema
                        </button>
                        <!--
                        <button class="btn btn-default w-100 mb-4">
                            <i class="fas fa-import"></i>
                            Importar Tema
                        </button>
                        -->
                    </div>
                    <div class="row mb-4">
                        <div class="col">
                            <ul id="sortableTema" class="list-style">
                                @forelse($roteiro->temas as $t)
                                    <li id="{{$t->id}}" class="ui-state-default text-truncate">
                                        <a href="javascript:void(0)" style="cursor: move"><i class="fas fa-bars pr-1"></i></a>
                                        <a href="javascript:void(0)" onclick="buscarTema({{$t->id}})" > Tema <span class="numeroOrdemTema">{{$loop->iteration}}</span> - {{$t->titulo}}</a>
                                    </li>
                                @empty
                                    <p class="text-center">Nenhum tema cadastrado.</p>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <!--
                        <div class="col">
                            <div class="rounded border p-4 bg-white">
                                <b>Data de criação:</b> 19/12/2019 às 08:46<br />
                                <b>Data de publicação:</b> 19/12/2019 às 13:12<br />
                                <b>Período de validade:</b> Ilimitado<br />
                                <b>Período restante:</b> Ilimitado<br />
                                <b>Data de expiração:</b> -<br />
                                <b>Status:</b> Publicado<br />
                                <b>Visibilidade:</b> Visível para todos<br />
                                <b>Vagas totais:</b> Ilimitadas<br />
                                <b>Matriculados:</b> 3 alunos<br />
                                <b>Vagas restantes:</b> Ilimitadas
                                <b>Preço:</b> Gratuito
                            </div>
                        </div>
                        -->
                    </div>
                </div>
                <div class="col-md-9" id="divConteudo">
                    <h6 class="text-center ">Selecione um tema para exibir seu conteúdo.</h6>

                </div>
            </div>
        </div>
    </section>

    @include('fr.roteiro.conteudo.modal_tema')
    @include('fr.roteiro.conteudo.modal_excluir')
    @include('fr.roteiro.conteudo.modal_conteudo')
    @include('fr.roteiro.conteudo.modal_biblioteca')

    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.jquery.min.js"></script>
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.css">

    <script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.css">
    <script src="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.js"></script>
<script type="text/javascript">

    var froalaConteudo;
    var temaIdGlobal = 0;
    var selectEtapaAno;
    var selectComponente;

    var parametrosFroala = {
        key: "{{config('app.froala')}}",
        attribution: false, // to hide "Powered by Froala"
        heightMin: 132,
        buttonsVisible: 4,
        placeholderText: '',
        language: 'pt_br',
        linkAlwaysBlank: true
    }

    $(document).ready(function(){

        froalaConteudo = new FroalaEditor('#froalaConteudo', parametrosFroala);
        froalaQuiz = new FroalaEditor('#froalaQuiz', parametrosFroala);
        froalaAlternativa1 = new FroalaEditor('#froalaAlternativa1', parametrosFroala);
        froalaAlternativa2 = new FroalaEditor('#froalaAlternativa2', parametrosFroala);
        froalaAlternativa3 = new FroalaEditor('#froalaAlternativa3', parametrosFroala);

        selectEtapaAno = new SlimSelect({
            select: '.multipleEtapa',
            placeholder: 'Buscar',
            searchPlaceholder: 'Buscar',
            closeOnSelect: true,
            allowDeselectOption: true,
            selectByGroup: true,
        });

        selectComponente = new SlimSelect({
            select: '.multipleComponente',
            placeholder: 'Buscar',
            searchPlaceholder: 'Buscar',
            closeOnSelect: true,
            allowDeselectOption: true,
            selectByGroup: true,
        });

        @if ($errors->any())
            @if(old('op') == 'novo_tema' || old('op') == 'editar_tema')
                $('#modalTema').modal('show');
            @else
                $('#tituloModalConteudo').html('Inserir conteúdo ');
                @if(old('op') == 'editar')
                    $('#tituloModalConteudo').html('Editar conteúdo ');
                @endif
                @if(old('tipo') == 1)
                    $('#tituloModalConteudo').append('de texto');
                    $('.elementosConteudo').hide();
                    $('#elementoConteudoTexto').show();
                @elseif(old('tipo') == 2)
                    $('#tituloModalConteudo').append('de áudio');
                    $('.elementosConteudo').hide();
                    @if(old('nome_arquivo') != '')
                        $('#FormGroupAudioDownload').show();
                        $('#FormGroupAudioFile').hide();
                        $('#nomeArquivoAudio').html('{{old('nome_arquivo')}}');
                        $('#inputNomeArquivo').val('{{old('nome_arquivo')}}');
                        $('#linkDownloadAudio').prop('href','{{url('/gestao/roteiros/download/'.old('conteudo_id').'/')}}'+'/'+replaceArquivo('{{old('conteudo_download_2')}}'));
                    @else
                        $('#FormGroupAudioDownload').hide();
                        $('#FormGroupAudioFile').show();
                    @endif
                    $('#elementoConteudoAudio').show();
                @elseif(old('tipo') == 3)
                    $('#tituloModalConteudo').append('de vídeo');
                    $('.elementosConteudo').hide();
                    @if(old('nome_arquivo') != '')
                        $('#FormGroupVideoDownload').show();
                        $('#FormGroupVideoFile').hide();
                        $('#nomeArquivoVideo').html('{{old('nome_arquivo')}}');
                        $('#inputNomeArquivo').val('{{old('nome_arquivo')}}');
                        $('#linkDownloadVideo').prop('href','{{url('/gestao/roteiros/download/'.old('conteudo_id').'/')}}'+'/'+replaceArquivo('{{old('conteudo_download_3')}}'));
                    @else
                        $('#FormGroupVideoDownload').hide();
                        $('#FormGroupVideoFile').show();
                    @endif
                    $('#elementoConteudoVideo').show();
                @elseif(old('tipo') == 4)
                    $('#tituloModalConteudo').append('de slide');
                    $('.elementosConteudo').hide();
                    @if(old('nome_arquivo') != '')
                        $('#FormGroupSlideDownload').show();
                        $('#FormGroupSlideFile').hide();
                        $('#nomeArquivoSlide').html('{{old('nome_arquivo')}}');
                        $('#inputNomeArquivo').val('{{old('nome_arquivo')}}');
                        $('#linkDownloadSlide').prop('href','{{url('/gestao/roteiros/download/'.old('conteudo_id').'/')}}'+'/'+replaceArquivo('{{old('conteudo_download_4')}}'));
                    @else
                        $('#FormGroupSlideDownload').hide();
                        $('#FormGroupSlideFile').show();
                    @endif
                        $('#elementoConteudoSlide').show();
                @elseif(old('tipo') == 15)
                    $('#tituloModalConteudo').append('de PDF');
                    $('.elementosConteudo').hide();
                    @if(old('nome_arquivo') != '')
                        $('#FormGroupPdfDownload').show();
                        $('#FormGroupPdfFile').hide();
                        $('#nomeArquivoPdf').html('{{old('nome_arquivo')}}');
                        $('#inputNomeArquivo').val('{{old('nome_arquivo')}}');
                        $('#linkDownloadPdf').prop('href','{{url('/gestao/roteiros/download/'.old('conteudo_id').'/')}}'+'/'+replaceArquivo('{{old('conteudo_download_15')}}'));
                    @else
                        $('#FormGroupPdfDownload').hide();
                        $('#FormGroupPdfFile').show();
                    @endif
                    $('#elementoConteudoPdf').show();
                @elseif(old('tipo') == 6)
                    $('#tituloModalConteudo').append('de arquivo');
                    $('.elementosConteudo').hide();
                    @if(old('nome_arquivo') != '')
                        $('#FormGroupArquivoDownload').show();
                        $('#FormGroupArquivoFile').hide();
                        $('#nomeArquivoArquivo').html('{{old('nome_arquivo')}}');
                        $('#inputNomeArquivo').val('{{old('nome_arquivo')}}');
                        $('#linkDownloadArquivo').prop('href','{{url('/gestao/roteiros/download/'.old('conteudo_id').'/')}}'+'/'+replaceArquivo('{{old('conteudo_download_6')}}'));
                    @else
                        $('#FormGroupArquivoDownload').hide();
                        $('#FormGroupArquivoFile').show();
                    @endif
                    $('#elementoConteudoArquivo').show();
                @elseif(old('tipo') == 7)
                    $('#tituloModalConteudo').html('Cadastro de atividade dissertativa');
                    @if(old('op') == 'editar')
                        $('#tituloModalConteudo').html('Editar atividade dissertativa ');
                    @endif
                    $('.elementosConteudo').hide();
                    $('#elementoConteudoTexto').show();
                @elseif(old('tipo') == 8)
                    $('#tituloModalConteudo').html('Cadastro de atividade Quiz');
                    @if(old('op') == 'editar')
                        $('#tituloModalConteudo').html('Editar atividade Quiz ');
                    @endif
                    $('.elementosConteudo').hide();
                    $('#elementoConteudoQuiz').show();
                @elseif(old('tipo') == 10)
                    $('#tituloModalConteudo').html('Cadastro de conteúdo entregável');
                    @if(old('op') == 'editar')
                        $('#tituloModalConteudo').html('Editar conteúdo entregável ');
                    @endif
                    $('.elementosConteudo').hide();
                    $('#elementoConteudoTexto').show();
                @endif

                @if(old('tipo') == 'B')
                    $('#modalConteudoBiblioteca').modal('show')
                @else
                    $('#modalConteudo').modal('show')
                @endif

            @endif
        @endif

        $("#sortableTema").sortable({
            update: function() {
                ajustaNumeroOrdemTema();
                var sort = $(this).sortable("toArray");
                $.ajax({
                    url: '{{url('/gestao/roteiros/ordemTema/')}}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        ordem: sort,
                        curso_id: '{{$roteiro->id}}',
                        _token: '{{csrf_token()}}'
                    }
                });
            }
        });

        function ajustaNumeroOrdemTema(){

            $(".numeroOrdemTema").each(function(index){
                $(this).html(index+1);
            });
        }

        @if( Request::input('tema') != '')
            buscarTema({{ (int)Request::input('tema')}})
        @endif

    });

    var urlExcluir = '';
    function excluirTema(titulo, id){
        $('#tituloModalExcluir').html('Exclusão de Tema');
        $('#elementoModalExcluir').html(titulo);
        urlExcluir = '{{url('/gestao/roteiros/excluirTema/'.$roteiro->id)}}/'+id;
        $('#formExcluir').modal('show');
    }

    function excluirConteudo(titulo, idCurso, idTema, idConteudo){
        $('#tituloModalExcluir').html('Exclusão de Conteúdo');
        $('#elementoModalExcluir').html(titulo);
        urlExcluir = '{{url('/gestao/roteiros/excluirConteudo')}}/'+idCurso+'/'+idTema+'/'+idConteudo;
        $('#formExcluir').modal('show');
    }

    function excluir(){
        window.location.href = urlExcluir;
    }

    function limpaFormTema(){
        $('#temaTitulo').val('');
        $('#temaTitulo').removeClass('is-invalid');
        $('#temaDescricao').val('');
        $('#temaDescricao').removeClass('is-invalid');
        $('#aulaId').val('');
        $('#temaOp').val('');
    }

    function novoTema()
    {
        limpaFormTema();
        $('#temaOp').val('novo_tema');
        $('#modalTema').modal('show');
    }

    function editarTema(temaID){
        limpaFormTema();
        $.ajax({
            url: '{{url('/gestao/roteiros/getTemaAjax')}}',
            type: 'post',
            dataType: 'json',
            data: {
                aula_id: temaID,
                curso_id: {{$roteiro->id}},
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $('#temaTitulo').val(data.titulo);
                $('#temaDescricao').val(data.descricao);
                $('#aulaId').val(data.id);
                $('#temaOp').val('editar_tema');
                $('#modalTema').modal('show');
            },
            error: function() {
                swal("", "Não foi possível carregar o tema.", "error");
            }
        });
    }

    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        }
        else {
            return uri + separator + key + "=" + value;
        }
    }

    function buscarTema(temaID){
        var newUrl=updateQueryStringParameter(window.location.href,"tema",temaID);
        window.history.pushState("", "", newUrl);
        temaIdGlobal = temaID;
        $.ajax({
            url: '{{url('/gestao/roteiros/getTemaConteudoAjax')}}',
            type: 'post',
            dataType: 'json',
            data: {
                aula_id: temaID,
                curso_id: {{$roteiro->id}},
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $('#divConteudo').html(data);
                ordernarConteudo(temaID);
            },
            error: function() {
                swal("", "Não foi possível carregar o tema.", "error");
            }
        });
    }

    function ordernarConteudo(temaId){
        $("#sortableConteudo").sortable({
            update: function() {
                var sort = $(this).sortable("toArray");
                $.ajax({
                    url: '{{url('/gestao/roteiros/ordenarConteudo/')}}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        ordem: sort,
                        curso_id: '{{$roteiro->id}}',
                        tema_id: temaId,
                        _token: '{{csrf_token()}}'
                    }
                });
            }
        });
    }

    ///menu
    $(document).ready(function(){
        $('div.dropdown').each(function() {
            var $dropdown = $(this);

            $("a.dropdown-link", $dropdown).click(function(e) {
                e.preventDefault();
                $div = $("div.dropdown-container", $dropdown);
                $div.toggle();
                $("div.dropdown-container").not($div).hide();
                return false;
            });

        });

        $('html').click(function(){
            $("div.dropdown-container").hide();
        });


        /* abre e fecha submenu */
        $(document).delegate('.item-menu','mouseenter mouseleave',function(){
            $(this).find(".dropdown-menu-roteiro").toggle();
        });
    });
    function replaceArquivo(link){
        const lastIndex = link.lastIndexOf('.');
        const replacement = '_';
        return link.substring(0, lastIndex) + replacement + link.substring(lastIndex + 1);
    }
</script>


@stop
