@extends('fr/master')
@section('content')
<style type="text/css">
    .jfk-button {
        border-radius: 2px;
        cursor: default;
        font-size: 11px;
        text-align: center;
        white-space: nowrap;
        line-height: 27px;
        min-width: 54px;
        outline: 0;
        float: left;
        cursor: pointer;
    }

    .save-to-drive-button.jfk-button {
        font-weight: normal;
        color: #444;
        border: 1px solid rgba(0,0,0,0.1);
        height: 29px;
        background-color: #ededed;
        background-image: -webkit-linear-gradient(top,#f5f5f5,#e6e6e6);
        background-image: -moz-linear-gradient(top,#f5f5f5,#e6e6e6);
        background-image: -ms-linear-gradient(top,#f5f5f5,#e6e6e6);
        background-image: -o-linear-gradient(top,#f5f5f5,#e6e6e6);
        background-image: linear-gradient(top,#f5f5f5,#e6e6e6);
        margin: 0;
        padding: 0;
        font-family: arial,sans-serif;
    }

    .save-to-drive-button:hover {
        background-color: #e8e8e8;
        background-image: -webkit-linear-gradient(top,#f0f0f0,#e1e1e1);
        background-image: -moz-linear-gradient(top,#f0f0f0,#e1e1e1);
        background-image: -ms-linear-gradient(top,#f0f0f0,#e1e1e1);
        background-image: -o-linear-gradient(top,#f0f0f0,#e1e1e1);
        background-image: linear-gradient(top,#f0f0f0,#e1e1e1);
    }
    .save-to-drive-image {
        display: inline-block;
        float: left;
        margin-left: 3px;
        margin-right: 5px;
        margin-top: 5px;
        position: relative;
        background-size: 21px 121px;
        width: 16px;
        height: 16px;
    }

    .save-to-drive-text {
        display: inline-block;
        margin-left: -3px;
        margin-right: 6px;
        position: relative;
        vertical-align: bottom;
    }

</style>
<script src="https://apis.google.com/js/platform.js" async defer></script>

    <section class="section section-interna">
            <div class="container">
                @if(Request::input('conteudo') != 106)
                    <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="filter">
                            <form class="form-inline d-flex justify-content-end" method="get" id="formPesquisa">
                                <input type="hidden" name="conteudo" value="{{Request::input('conteudo')}}">
                                <input type="hidden" name="colecao" value="{{Request::input('colecao')}}">

                                @if(Request::input('conteudo') == 102)
                                    <input type="hidden" name="componente" value="{{Request::input('componente')}}">
                                    @if($pesquisa['periodo'] && count($pesquisa['periodo']) > 0)
                                        <div class="input-group ml-1 @if(Request::input('conteudo') == 100) d-none @endif">
                                            <select name="periodo" id="pesquisaPeriodo" class="form-control" onchange="$('#formPesquisa').submit();">
                                                @php
                                                    $periodos = $pesquisa['periodo']->pluck('periodo')->toArray();
                                                @endphp

                                                @if(array_intersect($periodos, [1, 2, 3, 4]))
                                                    <option value="">Período</option>
                                                @elseif(array_intersect($periodos, [5, 6]))
                                                    <option value="">Período</option>
                                                @elseif(array_intersect($periodos, [7, 8, 9]))
                                                    <option value="">Período</option>
                                                @elseif(array_intersect($periodos, [10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24]))
                                                    <option value="">Período</option>
                                                @endif

                                                @foreach($pesquisa['periodo'] as $d)
                                                    @php
                                                        $textoPeriodo = $d->periodo; // Valor padrão
                                                        if (in_array($d->periodo, [1, 2, 3, 4])) {
                                                            $textoPeriodo = $d->periodo . '° Bimestre';
                                                        } elseif (in_array($d->periodo, [7, 8, 9])) {
                                                            $textoPeriodo = ($d->periodo - 6) . '° Trimestre'; // 7 → 1° Trimestre, 8 → 2° Trimestre, etc.
                                                        } elseif (in_array($d->periodo, [5, 6])) {
                                                            $textoPeriodo = ($d->periodo - 4) . '° Semestre'; // 5 → 1° Semestre, 6 → 2° Semestre
                                                        } elseif (in_array($d->periodo, range(10, 24))) {
                                                            $textoPeriodo = 'Volume/Unidade ' . ($d->periodo - 9); // 10 → Volume/Unidade 1, 11 → Volume/Unidade 2, etc.
                                                        }
                                                    @endphp
                                                    <option @if(Request::input('periodo') == $d->periodo) selected @endif value="{{ $d->periodo }}">{{ $textoPeriodo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                @endif

                                <!-- Campo Etapa -->
                                @if($pesquisa['etapas'] && count($pesquisa['etapas']) > 0)
                                <div class="input-group ml-1 @if(Request::input('conteudo')==100) d-none @endif">
                                    <select name="etapa" id="pesquisaEtapa" class="form-control" onchange="alterarPesquisa(1)">
                                        <option value="">Etapa</option>
                                        @foreach($pesquisa['etapas'] as $d)
                                            <option @if(Request::input('etapa')==$d->id) selected @endif value="{{$d->id}}">{{$d->titulo}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <!-- Campo Ano -->
                                @if($pesquisa['anos'] && count($pesquisa['anos']) > 0)
                                <div class="input-group ml-1 @if(Request::input('conteudo')==100) d-none @endif">
                                    <select name="ano" id="pesquisaAno" class="form-control" onchange="alterarPesquisa(2)">
                                        <option value="">Ano</option>
                                        @foreach($pesquisa['anos'] as $d)
                                            <option @if(Request::input('ano')==$d->id) selected @endif value="{{$d->id}}">{{$d->titulo}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <!-- Campo Componente Curricular -->
                                @if($pesquisa['disciplinas'] && count($pesquisa['disciplinas']) > 0)
                                <div class="input-group ml-1 @if(Request::input('conteudo')==100) d-none @endif">
                                    <select name="componente" id="pesquisaDisciplina" class="form-control" onchange="alterarPesquisa(3)">
                                        <option value="">Componente Curricular</option>
                                        @foreach($pesquisa['disciplinas'] as $d)
                                            <option @if(Request::input('componente')==$d->id) selected @endif value="{{$d->id}}">{{$d->titulo}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <div class="input-group ml-1">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                    <input type="text" @if(Request::input('conteudo')==100) size="50px" @endif placeholder="Procurar Conteúdo" value="{{Request::input('texto')}}" name="texto" onclick="pesquisaTexto(event)" class="form-control" />
                                </div>
                                <div class="input-group ml-1">
                                    <button type="button" class="btn btn-secondary" onclick="window.location.href='{{url('/editora/conteudos?conteudo='.Request::input('conteudo').'&colecao='.Request::input('colecao'))}}'">Limpar Filtros</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                @endif
                <h2 class="title-page">
                    <a @if(Request::input('colecao') != '')
                        href="{{ url('editora/conteudos/colecao?conteudo='.Request::input('conteudo')) }}"
                    @elseif(Request::input('id') != '')
                        href="{{ url()->previous() }}"
                    @else
                        @if(Request::input('conteudo') == 2)
                            href="{{ url('editora/conteudos/colecao?conteudo='.Request::input('conteudo')) }}"
                        @elseif(Request::input('conteudo') == 3)
                            href="{{ url('editora/conteudos/colecao?conteudo='.Request::input('conteudo')) }}"
                        @else
                            href="{{ url('/catalogo') }}"
                        @endif
                    @endif
                    class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </h2>

                    @if($titulo != 'Tabelas Trimestrais')
                        {{$titulo}}
                    @else
                        Conteúdo Programático - Estrutura Trimestral
                    @endif

                </h2>
                @if(Request::input('conteudo') == 102)
                    @if($pesquisa['disciplinas']!=null)
                        <div class="row">
                            @foreach($pesquisa['disciplinas'] as $d)
                            <div class="col-md-1 text-center">
                                <a href="{{url('editora/conteudos?conteudo='.Request::input('conteudo').'&colecao='.Request::input('colecao').'&componente='.$d->id)}}">
                                    <img width="70px" height="70px" class="img-fluid" src="{{config('app.cdn')}}/storage/icones_disciplinas/{{@strtolower($d->sigla)}}.webp">
                                    <p><small>{{$d->titulo}}</small></p>
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @endif
                @endif
                @if($colecao!='')
                    <div class="subtitle-page">{{$colecao->nome}}</div>
                @endif

                <div class="list-grid-menu">
                    <form class="form-inline">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-secondary p-2 no-list-grid-btn active">
                                <input type="radio" name="options" id="option1" autocomplete="off" checked>
                                <i class="fas fa-th-large"></i>
                            </label>
                            <label class="btn btn-secondary list-grid-btn">
                                <input type="radio" name="options" id="option2" autocomplete="off">
                                <i class="fas fa-list"></i>
                            </label>
                        </div>
                    </form>
                </div>

                <div class="row section-grid">
                    @foreach($dados as $d)
                        @if($d->status == 1)
                        <div class="col-md-3 grid-item" id="divConteudo{{$d->id}}">
                            <a href="javascript:" onclick="visualizarConteudo({{$d->id}})" class="wrap">
                                <div class="card text-center">
                                    <div class="card-header" style="background-color:transparent;height:200px;border-bottom:transparent;display:inline-block;text-align:center;width:260px;max-width:100%;">

                                        @if($capa!='' && $d->capa != '')
                                            <img src="{{config('app.cdn').$capa.$d->capa}}" style="max-height: 100%;object-fit: fill;width: auto;height:auto;max-width:100%;" />
                                        @else
                                            <img src="{{config('app.cdn').$capaPadrao}}" style="max-height: 100%;object-fit: fill;width: auto;height:auto;max-width:100%;" />
                                        @endif
                                    </div>
                                    <div class="card-body" style="height:150px;display:inline-flex; ">
                                        <div class="text mb-2">
                                            <h6 class="title font-weight-bold" style="margin-top: 5px">@if($d->tipo == 101) <p style="color: #1E90FF   ">
                                                    {{mb_convert_case($d->disciplina, MB_CASE_UPPER, 'UTF-8')}}
                                                </p> @endif{{$d->titulo}}
                                            </h6>
                                        </div>
                                    </div>
                                    <div  class="card-footer" style=" padding-left: 20px; padding-right: 10px">
                                        @if((auth()->user()->permissao == 'Z' || strpos(auth()->user()->email,'@opeteducation.com.br') !== false || strpos(auth()->user()->email,'@souopet.com.br') !== false) && $d->compartilhado_google==1 && $d->id_google!='')
                                            @if($d->tipo == 100 || $d->tipo == 4 || $d->tipo == 102 || $d->tipo == 22)
                                                <div class="mb-1" style="float: left;">
                                                    <div class="g-savetodrive"
                                                    data-src="{{config('app.cdn').'/storage/'.$download[$d->tipo].$d->conteudo}}"
                                                    data-filename="{{$d->conteudo}}"
                                                    data-sitename="Opet Inspira">
                                                    </div>
                                                </div>
                                            @endif
                                            <a href="https://classroom.google.com/u/0/share?url={{url('/exibicao/google')}}?c={{$d->id_google}}" target="blank" alt="Compartilhar no Google Sala de Aula" title="Compartilhar no Google Sala de Aula"  class="">
                                                <div class="save-to-drive-button jfk-button ml-1" role="button" tabindex="0" style="user-select: none;"><div class="save-to-drive-image drive-sprite-classroom" style="background: no-repeat url({{config('app.cdn')}}/fr/imagens/icones/ico_google_buttons1.png) 0 -21px;"></div><div class="save-to-drive-text">Compartilhar</div></div>
                                            </a>
                                            @if(auth()->user()->permissao == 'Z')
                                            <a href="javascript:void(0)" data-trigger= "click" data-toggle="tooltip" data-placement="top" title="Link copiado!" onclick="copiarLink(this,'{{url('/exibicao/google')}}?c={{$d->id_google}}')">
                                                <div class="save-to-drive-button jfk-button ml-1" role="button" tabindex="0" style="user-select: none;"><div class="save-to-drive-image drive-sprite-classroom" style="background: no-repeat url({{config('app.cdn')}}/fr/imagens/icones/ico_google_buttons1.png) 0 -42px;"></div><div class="save-to-drive-text">Link</div></div>
                                            </a>
                                            @endif
                                        @endif
                                    </div>
                                    <div  class="card-footer">
                                            <a href="javascript:" alt="Ver conteúdo" title="Ver conteúdo" onclick="visualizarConteudo({{$d->id}})" data-toggle="tooltip" data-placement="top"  class="btn btn-secondary" >
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(Request::input('conteudo') == 104 || Request::input('conteudo') == 105)
                                                <a href="{{url('/editora/conteudos/bncc?conteudo='.$d->id)}}" target="_blank" alt="Exibir em nova aba" title="Exibir em nova aba" data-toggle="tooltip" data-placement="top" class="btn btn-secondary">
                                                    <i class="fas fa-arrows-alt"></i>
                                                </a>
                                            @endif
                                            @if(($d->permissao_download==1 || $d->tipo==100))
                                                <a href="{{ url('editora/conteudos/download/'.$d->id)}}" alt="Download" title="Download"  data-toggle="tooltip" data-placement="top" class="btn btn-secondary">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                            @if(Request::input('conteudo') == 103)
                                                <a href="{{$d->conteudo}}" target="_blank" alt="Exibir em nova aba" title="Exibir em nova aba" data-toggle="tooltip" data-placement="top" class="btn btn-secondary">
                                                    <i class="fas fa-arrows-alt"></i>
                                                </a>
                                            @endif

                                            @if(auth()->user()->permissao == 'Z')
                                                    <a href="javascript:void(0)" class="btn btn-secondary" data-trigger= "click" data-toggle="tooltip" data-placement="top" title="Link copiado!" onclick="copiarLink(this,'{{$d->iframe}}')" alt="">
                                                        <i class="fas fa-book"></i>
                                                    </a>
                                            @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endif
                    @endforeach
                </div>
                @if(count($dados)==0)
                    <div class="row">
                        <section class="table-page w-100">
                            <!-- Inicio Sem Resultado -->
                            <div class="col">
                                <div class="card text-center">
                                    <div class="card-header"></div>
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado</h5>
                                        <p class="card-text">Não foi encontrado resultado contendo todos os seus termos de pesquisa, clique no botão abaixo para reiniciar a pesquisa</p>
                                        <a class="btn btn-danger fs-13" href="{{url('/editora/conteudos?conteudo='.Request::input('conteudo'))}}" title="Excluir"><i class="fas fa-undo-alt"></i> Limpar Filtro</a>
                                    </div>
                                    <div class="card-footer text-muted"></div>
                                </div>
                            </div>
                            <!-- Fim Sem Resultado -->
                        </section>
                    </div>
                @endif
                <nav class="mt-5" aria-label="Page navigation example">
                    {{ $dados->appends(Request::all())->links() }}
                </nav>
            </div>
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

        </section>

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
    <script type="text/javascript">
      window.___gcfg = {
        lang: 'pt-BR'
      };

    $('.list-grid-btn').click(function() {
        $('.grid-item').addClass('list-grid');
    });

    $('.no-list-grid-btn').click(function() {
        $('.grid-item').removeClass('list-grid');
    });

    $('#divModalVisualizarConteudo').on('hidden.bs.modal', function () {
        $(".conteudo-tipo").empty();
    });


    function alterarPesquisa(id)
    {
        if(id ==1){
           // $('#pesquisaDisciplina').val('');
            $('#pesquisaAno').val('');
        }
       /* if(id==2){
            $('#pesquisaDisciplina').val('');
        }*/
        $('#formPesquisa').submit()
    }

    function pesquisaTexto(e) {
        if (e.keyCode == 13) {
            $('#formPesquisa').submit()
        }
    }

        function visualizarConteudo(idConteudo)
        {
            $.ajax({
                url: '{{ url('/gestao/biblioteca')}}' + '/' + idConteudo + '/visualizar',
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

        function  copiarLink(el,link){
            navigator.clipboard.writeText(link);
            try {
                messageHandler.postMessage(link);
            }catch (e) {

            }
            var elemento = $(el);
            setTimeout(function(){
                elemento.tooltip("hide");
            }, 2000)
        }
</script>
@stop
