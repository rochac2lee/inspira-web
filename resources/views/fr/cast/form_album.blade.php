@extends('fr/master')
@section('content')
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.jquery.min.js"></script>
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.css">
    <script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet"/>
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery/jquery-ui.js"></script>
    <script type="text/javascript">
        var buscaDisciplina;
        var buscaEtapa;
        var buscaCategoria
        $(document).ready(function(){
            /* configuracoes basicas do plugin de recortar imagem */
            var configuracao = {
                ratio: '1:1',
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
            /* carrega o plugin de recortar imagem */
            $(".myCropper").slim(configuracao);

            $('body').addClass('interna');
            $('body').addClass('avaliacao');
            $('#buscarQuestoes').children().unwrap().wrapAll("<form id='buscarQuestoes'></form>");

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

             buscaDisciplina = new SlimSelect({
                select: '.buscaDisciplina',
                placeholder: 'Buscar',
                searchPlaceholder: 'Buscar',
                closeOnSelect: true,
                allowDeselectOption: true,
                selectByGroup: true,
            });

            buscaEtapa = new SlimSelect({
                select: '.buscaEtapa',
                placeholder: 'Buscar',
                searchPlaceholder: 'Buscar',
                closeOnSelect: true,
                allowDeselectOption: true,
                selectByGroup: true,
            });

            buscaCategoria = new SlimSelect({
                select: '.buscaCategoria',
                placeholder: 'Buscar',
                searchPlaceholder: 'Buscar',
                closeOnSelect: true,
                allowDeselectOption: true,
                selectByGroup: true,
            });

        });
    </script>

    <style type="text/css">
        .modal-full {
            min-width: 90%;
            margin-left: 80;
        }
        .form-control{
            border: 1px solid #dcdee2;
        }

        #resultado-questoes, #avaliacao{
            height: 800px;
            overflow-y: scroll;
        }

        #resultado-questoes .adicionar, #avaliacao .remover{
            display: initial;
        }
        #resultado-questoes .remover, #avaliacao .adicionar{
            display: none;
        }

        .list-group-sortable-connected{
            height: 100%;
        }

        .list-group-sortable-connected li{
            max-width: 98%;
            padding: 1em;
            background-color: #ffffff;
            margin-bottom: 0.5em;
            height: 160px;
        }

        .list-group-sortable-connected li .questao{
            height: 76px;
            overflow: hidden;
        }

      .card:hover img {
          transition: filter 0s ease-in-out;
          -webkit-filter: unset;
          filter: unset;
      }
        .list-group-sortable-connected li .questao {
            height: unset;
            overflow: hidden;
        }

        .list-group-sortable-connected li {
            height: unset;
        }
    </style>

    <section class="section section-interna">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mt-1">
                    <h4 class="pb-1 border-bottom mb-4">
                        <a href="{{url('gestao/cast?tipo=1')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        @if ( strpos(Request::path(),'editar')===false )Novo álbum @else Editar álbum @endif
                    </h4>
                    <div class="card">
                        <div class="card-header">Dados do álbum</div>
                        <div class="card-body">
                            <div class="filter">
                                <form id="formCadastro" action="@if ( strpos(Request::path(),'editar') ){{url('/gestao/cast/album/editar/')}}@else{{url('/gestao/cast/album/add/')}}@endif" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{old('id',@$dados->id)}}">
                                    <div class="form-row">
                                        <div class="form-group col-2">
                                            <label>* Capa do álbum</label>
                                            <div id="logoCadastro" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                                <input type="hidden" name="existeImg" id="existeImg" value="{{old('existeImg',@$dados->capa_album)}}">
                                                <img id="imgLogo" width="100%" src="{{old('existeImg',@$dados->capa_album)}}" >
                                                <br>
                                                <a class="btn btn-secondary" onclick="excluirLogo()">Excluir Capa</a>
                                            </div>
                                            <div id="novaLogo" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                                <input type="file" data-min-size="100,100" accept="image/*" name="imagem" class="myCropper">
                                            </div>
                                            <div class="invalid-feedback @if($errors->first('imagem'))d-block @endif">{{ $errors->first('imagem') }}</div>

                                        </div>
                                        <div class="form-group col-5">
                                            <div class="row">
                                                <div class="form-group col-12">
                                                        <label>* Título</label>
                                                        <input type="text" name="titulo" placeholder="" value="{{old('titulo',@$dados->titulo)}}" class="form-control rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
                                                        <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label>* Componente Curricular</label>
                                                        <select class="multipleDisciplina "  @if($errors->has('disciplina_id') ) style="border: 1px solid #E25A66; border-radius: 0.4rem;" @endif name="disciplina_id" >
                                                            <option value="">Selecione</option>
                                                            @foreach($disciplina as $d)
                                                                <option @if( $d->id == old('disciplina_id',@$dados->disciplina_id)) selected @endif value="{{$d->id}}">{{$d->titulo}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback @if($errors->first('disciplina_id'))d-block @endif">{{ $errors->first('disciplina_id') }}</div>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label>* Etapa / Ano</label>
                                                    <select class="multipleEtapa"  @if($errors->has('cicloetapa_id') ) style="border: 1px solid #E25A66; border-radius: 0.4rem;" @endif name="cicloetapa_id" >
                                                        <option value="">Selecione</option>
                                                        @foreach($cicloEtapa as $c)
                                                            <option @if( $c->id == old('cicloetapa_id',@$dados->cicloetapa_id)) selected @endif value="{{$c->id}}">{{$c->ciclo}} - {{$c->ciclo_etapa}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback @if($errors->first('cicloetapa_id'))d-block @endif">{{ $errors->first('cicloetapa_id') }}</div>
                                                </div>
                                                <div class="form-group col-12">
                                                    <label>* Categoria</label>
                                                    <select class="multipleCategoria" @if($errors->has('categoria_id') ) style="border: 1px solid #E25A66; border-radius: 0.4rem;" @endif name="categoria_id" >
                                                        <option value="">Selecione</option>
                                                        @foreach($categoria as $c)
                                                            <option @if( $c->id == old('categoria_id',@$dados->categoria_id)) selected @endif value="{{$c->id}}">{{$c->titulo}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback @if($errors->first('categoria_id'))d-block @endif">{{ $errors->first('categoria_id') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-5">
                                            <div class="row">
                                                <div class="form-group col-12">
                                                    <label>Descrição</label>
                                                    <textarea name="descricao" class="form-control rounded {{ $errors->has('descricao') ? 'is-invalid' : '' }}">{{old('descricao',@$dados->descricao)}}</textarea>

                                                    <div class="invalid-feedback @if($errors->first('descricao'))d-block @endif">{{ $errors->first('descricao') }}</div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-12">
                                                    <label>Palavras-chave</label>
                                                    <textarea name="palavras_chave" class="form-control rounded {{ $errors->has('palavras_chave') ? 'is-invalid' : '' }}">{{old('palavras_chave',@$dados->palavras_chave)}}</textarea>
                                                    <small class="form-text w-100 text-muted">
                                                        As palavras-chave devem ser separadas por hífen " - ".
                                                    </small>
                                                    <div class="invalid-feedback @if($errors->first('palavras_chave'))d-block @endif">{{ $errors->first('palavras_chave') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 border-right pl-4 pr-4">
                    <div class="card mb-3" >
                        <div id="buscarQuestoes">
                            <div class="card-header">Buscar áudios</div>
                            <div class="card-body">
                                <input id="selecionados" type="hidden" name="selecionados" value="">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input id="buscaPalavraChave" name="palavra_chave" type="text" placeholder="Palavra chave" class="form-control form-control-sm rounded" />
                                    </div>
                                    <div class="form-group col-md-6">
                                            <select class="buscaDisciplina" name="disciplina" >
                                                <option value="">Componente Curricular</option>
                                                @foreach($disciplina as $d)
                                                    <option value="{{$d->id}}">{{$d->titulo}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <select class="buscaEtapa" name="etapa" >
                                            <option value="">Etapa / Ano</option>
                                            @foreach($cicloEtapa as $c)
                                                <option value="{{$c->id}}">{{$c->ciclo}} - {{$c->ciclo_etapa}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select class="buscaCategoria"  name="categoria">
                                            <option value="">Categoria</option>
                                            @foreach($categoria as $c)
                                                <option value="{{$c->id}}">{{$c->titulo}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12 mb-0 text-right">
                                        <button type="button" class="btn btn-sm btn-info fs-10" onclick="filtrarQuestoes()"><i class="fas fa-search"></i> Filtrar</button>
                                        <button type="button" class="btn btn-sm btn-danger fs-10" title="Limpar" onclick="limparQuestoes()"><i class="fas fa-undo-alt"></i> Limpar todos os filtros</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-2 fs-13">Exibindo <span class="exibindoQuestoes">30</span> de <b class="totalQuestao"></b> áudios encontrados</div>
                    <div class="shadow-none p-3 bg-light rounded">

                        <div id="resultado-questoes" class="box-container">
                            <ul id="audioBiblioteca" class="list-group list-group-sortable-connected">
                            </ul>
                        </div>
                        <br>
                        <div class="pb-2 fs-13">Exibindo <span class="exibindoQuestoes">30</span> de <b class="totalQuestao"></b> áudios encontrados</div>

                        <nav class="mt-4" aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item">
                                    <button type="button" class="page-link" onclick="maisQuestoes()" tabindex="-1">Carregar mais audios</button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-md-7 pl-4">
                    <div class="row">
                        <div class="pb-2 fs-13 pt-3 col-5">Arraste os áudios para esse quadro.</div>
                    </div>
                    <div class="invalid-feedback @if($errors->first('lista_audio'))d-block @endif" style="font-size: 12px">{{ $errors->first('lista_audio') }}</div>
                    <div class="shadow-none p-3 mb-3 bg-light rounded" id="avaliacao">
                        <ul class="list-group list-group-sortable-connected" id="ListaAudioSelecionado">

                        </ul>

                    </div>
                    <a href="{{url('/gestao/cast?tipo=1')}}" class="btn btn-secondary float-left">Cancelar</a>
                    <button type="button" class="btn btn-default mt-0 float-right ml-2" onclick="enviar()">Salvar</button>
                </div>

                <script src="{{config('app.cdn')}}/fr/includes/js/jquery-sortable/jquery.sortable.js"></script>
                <script>
                    $(function() {
                        $('.list-group-sortable-connected').sortable({
                            placeholderClass: 'list-group-item',
                            connectWith: '.connected'
                        });
                    });
                </script>
            </div>
        </div>
    </section>

    <link rel='stylesheet' type="text/css" href="{{ config('app.cdn') }}/fr/includes/audioplayer/audioplayer.css"/>
    <script src="{{ config('app.cdn') }}/fr/includes/audioplayer/audioplayer.js" type="text/javascript"></script>
    <script>
        var settings1 = {
            disable_volume: "off"
            ,autoplay: "off"
            ,cue: "on"
            ,disable_scrub: "default"
            ,design_skin: "skin-wave"
            ,skinwave_dynamicwaves:"on"
            ,skinwave_enableSpectrum: "off"
            ,settings_backup_type:"full"
            ,settings_useflashplayer:"auto"
            ,skinwave_spectrummultiplier: "4"
            ,skinwave_comments_enable:"on"
            ,skinwave_mode: "small"
            ,skinwave_comments_retrievefromajax:"on"
            ,pcm_data_try_to_generate: "on"
        };
    </script>
    <div class="modal fade divModalVisualizarConteudo" id="divModalVisualizarConteudo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl dialog-fixed px-1 px-md-5" role="document">
            <div class="modal-content content-fixed">
                <div class="modal-body body-fixed">
                    <button style="z-index:1;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                    </button>

                    <div class="row mb-2">
                        <div class="col align-middle my-auto">
                            <div class="col-12 title conteudo-titulo mb-0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mx-auto conteudo-tipo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#divModalVisualizarConteudo').on('hidden.bs.modal', function () {
            $(".conteudo-tipo").empty();
        });

        function visualizarConteudo(idConteudo)
        {
            $.ajax({
                url: '{{ config('app.url') }}' + '/gestao/biblioteca/' + idConteudo + '/visualizar',
                type: 'get',
                dataType: 'json',
                success: function( _response )
                {
                    if(_response)
                    {
                        $(".conteudo-titulo").empty();
                        $(".conteudo-tipo").empty();

                        $(".conteudo-titulo").append(_response.titulo);
                        $(".conteudo-tipo").append(_response.descricao);
                        $("#player1").audioplayer(settings1);
                        $('#divModalVisualizarConteudo').modal('show');
                    }
                },
                error: function( _response )
                {

                }
            });
        }

        function excluirLogo()
        {
            $('#logoCadastro').hide();
            $('#novaLogo').show();
            $('#existeImg').val('');
        }

        function atualizadaSortable()
        {
            $('.list-group-sortable-connected').sortable({
                placeholderClass: 'list-group-item',
                connectWith: '.connected'
            });
        }

        function adicionarAudio(id)
        {
            $('#ListaAudioSelecionado').append($('#audio'+id));
            defineSelecionados();
        }

        function removerAudio(id,elemento)
        {
            elemento = $(elemento).parent().parent();
            $('#audioBiblioteca').append($(elemento));
            defineSelecionados();
        }

        var pagina = 1;
        var formulario = '';
        var vetSelecionados = new Array;

        function defineSelecionados()
        {
            vetSelecionados = new Array;
            $('#ListaAudioSelecionado li input').each(function(){
                vetSelecionados.push($(this).val());
            })
        }

        function filtrarQuestoes()
        {
            $('#selecionados').val(vetSelecionados);
            pagina =1;
            $('.exibindoQuestoes').html(30);
            formulario = $('#buscarQuestoes').serialize();
            ajaxQuestoes(formulario, 'limpar');

        }

        function limparQuestoes()
        {
            buscaDisciplina.set('');
            buscaEtapa.set('');
            buscaCategoria.set('');

            filtrarQuestoes();
        }

        function maisQuestoes()
        {
            pagina ++;
            exibindo = 30+parseInt($('.exibindoQuestoes').html());
            total = parseInt($('.totalQuestao').html());
            if(exibindo > total)
                exibindo = total;
            $('.exibindoQuestoes').html(exibindo);
            ajaxQuestoes(formulario,'adicionar');
        }

        function ajaxQuestoes(form, tipo)
        {
            url = '{{url('/gestao/cast/getAudiosAjax')}}/?'+form+'&page='+pagina;
            $.get(url,function(retorno){
                if(tipo=='limpar')
                {
                    $('#audioBiblioteca').html(retorno.questao);
                    $('.exibindoQuestoes').html(retorno.exibindo);

                }
                else
                {
                    $('#audioBiblioteca').append(retorno.questao);
                }
                $('.totalQuestao').html(retorno.total);
                atualizadaSortable();
            })
        }

        function enviar()
        {
            $('#audioBiblioteca').empty();
            $('#ListaAudioSelecionado input').each(function(){
                $(this).clone().appendTo('#formCadastro');
            });
            $('#formCadastro').submit();
        }



        $(document).ready(function(){

            @if(old('existeImg',@$dados->capa_album))
                $('#logoCadastro').show();
                $('#novaLogo').hide();
            @else
                $('#logoCadastro').hide();
                $('#novaLogo').show();
            @endif

            @if(old('lista_audio',@$dados->audios))
                vetSelecionados = new Array;
                @if(old('lista_audio'))
                    @foreach (old('lista_audio') as $d)
                        vetSelecionados.push({{$d}});
                    @endforeach
                @else
                    @foreach ($dados->audios as $d)
                        vetSelecionados.push({{$d->conteudo_id}});
                    @endforeach
                @endif
                filtrarQuestoes()
                $.ajax({
                    url: '{{url('/gestao/cast/getAudiosAjax')}}',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        com_selecionados: vetSelecionados,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data) {
                        $('#ListaAudioSelecionado').append(data);
                        atualizadaSortable();
                    },
                    error: function(data) {
                        swal("", "Erro ao carregar audios", "error");
                    }
                });
            @else
                filtrarQuestoes();
            @endif
        });
    </script>
@stop
