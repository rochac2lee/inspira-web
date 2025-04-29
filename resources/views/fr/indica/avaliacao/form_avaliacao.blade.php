@extends('fr/master')
@section('content')

<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.css">
<script src="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.js"></script>

<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/jquery-datetimepicker/jquery.datetimepicker.min.css" type="text/css" charset="utf-8" />
<script src="{{config('app.cdn')}}/fr/includes/js/jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
    $('body').addClass('interna');
    $('body').addClass('avaliacao');
    $('#buscarQuestoes').children().unwrap().wrapAll("<form id='buscarQuestoes'></form>");

    jQuery.datetimepicker.setLocale('pt-BR');
    jQuery('#datetimepicker').datetimepicker({
      format:'d/m/Y H:i:s'
    });
    jQuery('#datetimepicker2').datetimepicker({
      format:'d/m/Y H:i:s'
    });
    jQuery('#datetimepicker3').datetimepicker({
      format:'d/m/Y H:i:s'
    });

    defineTemaBusca($('#buscaTema').val());
    defineUnidadeBusca($('#buscaUnidade').val());
    defineHabilidadeBusca($('#buscaHabilidade').val());
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
            padding-bottom: 0.4em;
        }

        .list-group-sortable-connected li .questao{
            height: 47px;
            overflow: hidden;
            position: relative;
        }

        .btn_degrade {
            position: relative;
        }

        .avaliacao_degrade {
            background: url({{config('app.cdn')}}/fr/imagens/avaliacao_degrade.png) top;
            height: 16px;
            width: 100%;
            position: absolute;
            top: -17px;
        }

        .list-group-sortable-connected li .questao:hover {
            height: 120px;
            overflow: auto;
            position: relative;
        }
    </style>
    <script type="text/javascript" src="{{url('')}}/fr/includes/froala_editor/js/plugins/froala_wiris/integration/WIRISplugins.js?viewer=image"></script>
    <form id="formDados">
        @if(old('instituicao')!= '')
            @foreach(old('instituicao') as $e)
                <input type="hidden" name="instituicao[]" value="{{$e}}">
                @if(old('escola.'.$e) != '')
                    @foreach(old('escola.'.$e) as $t)
                        <input type="hidden" name="escola[{{$e}}][]" value="{{$t}}">
                    @endforeach
                @else
                    <input type="hidden" name="escola[{{$e}}][]" value="0">
                @endif
            @endforeach
        @else
            @if(isset($dados) && isset($dados->escolas))
                @foreach(@$dados->escolas as $t)
                    <input type="hidden" name="instituicao[]" value="{{$t->instituicao_id}}">
                    <input type="hidden" name="escola[{{$t->instituicao_id}}][]" value="{{$t->escola_id}}">
                @endforeach
            @endif
        @endif
    </form>
    <section class="section section-interna">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mt-4">
                    <h4 class="pb-3 border-bottom mb-4">
                        <a href="{{url('indica/gestao/avaliacao')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        INdica -
                        @if ( strpos(Request::path(),'editar')===false )
                            Nova Avaliação
                        @else
                            Avaliação #{{$dados->id}} - {{$dados->titulo}}
                        @endif
                    </h4>
                    <p style="font-size: 10px">* Campos obrigatórios</p>
                    <div class="card">
                        <div class="card-header">Dados da Avaliação</div>
                        <div class="card-body">
                            <div class="filter">
                                <form id="formCadastro" action="" method="post">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold fs-12">* Título</label>
                                                <input name="titulo" value="{{old('titulo',@$dados->titulo)}}" type="text" placeholder="Título" class="form-control form-control-sm rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}" value="" />
                                                <div class="invalid-feedback @if($errors->first('titulo'))d-block @endif" style="font-size: 12px">{{ $errors->first('titulo') }}</div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="font-weight-bold fs-12">* Componente Curricular</label>
                                            <select name="disciplina_id" class="multiple1 {{ $errors->has('disciplina_id') ? 'is-invalid' : '' }}" single>
                                                <option value=''></option>
                                                @foreach($disciplina as $d)
                                                    @if($d->titulo=="Língua Portuguesa" || $d->titulo=="Matemática")
                                                        <option value='{{$d->id}}' @if(old('disciplina_id',@$dados->disciplina_id) == $d->id) selected @endif>{{$d->titulo}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback @if($errors->first('disciplina_id'))d-block @endif" style="font-size: 12px">{{ $errors->first('disciplina_id') }}</div>

                                        </div>
                                        <div class="form-group mb-0 col-md-3">
                                            <label class="font-weight-bold fs-12">* Etapa / Ano</label>
                                            <select name="cicloetapa_id" id="ordenacao" class="form-control form-control-sm rounded {{ $errors->has('cicloetapa_id') ? 'is-invalid' : '' }}">
                                                <option value="">Selecione </option>
                                                @foreach($cicloEtapa as $c)
                                                @if($c->ciclo!="Ensino Médio" && $c->ciclo_etapa!="Todos" && $c->ciclo_etapa!="*" && $c->ciclo_etapa!="NEM" && $c->ciclo_etapa!="Especial")
                                                        <option @if(old('cicloetapa_id',@$dados->cicloetapa_id) == $c->id) selected @endif value="{{$c->id}}">{{$c->ciclo}} / {{$c->ciclo_etapa}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback @if($errors->first('cicloetapa_id'))d-block @endif" style="font-size: 12px">{{ $errors->first('cicloetapa_id') }}</div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="font-weight-bold fs-12">* Início da Avaliação</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </div>
                                                </div>
                                                @php
                                                    $dataInicial = '';
                                                    if(@$dados->data_hora_inicial)
                                                    {
                                                        $dataInicial = $dados->data_hora_inicial->format('d/m/Y H:i:s');
                                                    }
                                                @endphp
                                                <input name="data_hora_inicial" autocomplete="off" value="{{old('data_hora_inicial',$dataInicial)}}" type="text" id="datetimepicker" placeholder="00/00/0000 00:00" class="form-control form-control-sm rounded {{ $errors->has('data_hora_inicial') ? 'is-invalid' : '' }}" />
                                            </div>
                                            <div class="invalid-feedback @if($errors->first('data_hora_inicial'))d-block @endif" style="font-size: 12px">{{ $errors->first('data_hora_inicial') }}</div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="font-weight-bold fs-12">* Fim da Avaliação</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </div>
                                                </div>
                                                @php
                                                    $dataFinal = '';
                                                    if(@$dados->data_hora_final)
                                                    {
                                                        $dataFinal = $dados->data_hora_final->format('d/m/Y H:i:s');
                                                    }
                                                @endphp
                                                <input name="data_hora_final" autocomplete="off" value="{{old('data_hora_final',$dataFinal)}}" type="text" id="datetimepicker2" placeholder="00/00/0000 00:00" class="form-control form-control-sm rounded {{ $errors->has('data_hora_final') ? 'is-invalid' : '' }}" />
                                            </div>
                                            <div class="invalid-feedback @if($errors->first('data_hora_final'))d-block @endif" style="font-size: 12px">{{ $errors->first('data_hora_final') }}</div>
                                        </div>



                                        <div class="form-group mb-0 col-md-3">
                                            <label class="font-weight-bold fs-12">* Tempo máximo de execução da prova</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="far fa-clock"></i>
                                                    </div>
                                                </div>
                                                <input name="tempo_maximo" type="number" value="@if(old('tempo_maximo',@$dados->tempo_maximo)==''){{'90'}}@else{{old('tempo_maximo',@$dados->tempo_maximo)}}@endif" step="5" class="form-control form-control-sm rounded {{ $errors->has('tempo_maximo') ? 'is-invalid' : '' }}" />
                                                <div class="input-group-append">
                                                    <div class="input-group-text" style="font-size: 11px">minutos</div>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback @if($errors->first('tempo_maximo'))d-block @endif" style="font-size: 12px">{{ $errors->first('tempo_maximo') }}</div>
                                        </div>
                                        <div class="form-group mb-0 col-md-3">
                                            <label class="font-weight-bold fs-12">* Caderno</label>
                                            <select name="caderno" id="caderno" class="form-control form-control-sm rounded {{ $errors->has('caderno') ? 'is-invalid' : '' }}">
                                                <option  value="">Selecione </option>
                                                <option @if(old('caderno',@$dados['caderno']) == 1) selected @endif value="1">Caderno 1 </option>
                                                <option @if(old('caderno',@$dados['caderno']) == 2) selected @endif value="2">Caderno 2 </option>
                                                <option @if(old('caderno',@$dados['caderno']) == 3) selected @endif value="3">Caderno 3 </option>
                                                <option @if(old('caderno',@$dados['caderno']) == 4) selected @endif value="4">Caderno 4 </option>
                                                <option @if(old('caderno',@$dados['caderno']) == 5) selected @endif value="5">Caderno 5 </option>
                                                <option @if(old('caderno',@$dados['caderno']) == 6) selected @endif value="6">Caderno 6 </option>
                                            </select>
                                            <div class="invalid-feedback @if($errors->first('cicloetapa_id'))d-block @endif" style="font-size: 12px">{{ $errors->first('cicloetapa_id') }}</div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 border-right pb-4 pl-4 pr-4">
                    <div class="card mb-3" >
                        <div id="buscarQuestoes">
                        <div class="card-header">Buscar Questões</div>
                        <div class="card-body">
                                <input id="selecionados" type="hidden" name="selecionados" value="">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input id="buscaPalavraChave" name="palavra_chave" type="text" placeholder="Código ou palavra-chave" class="form-control form-control-sm rounded" />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select id="buscaCicloEtapa" name="ciclo_etapa[]" class=" multiple3 " multiple onchange="defineUnidadeBusca($('#buscaUnidade').val()); defineHabilidadeBusca($('#buscaHabilidade').val())">
                                            @foreach($cicloEtapa as $c)
                                                @if($c->ciclo!="Ensino Médio" && $c->ciclo_etapa!="Todos")
                                                    <option value="{{$c->id}}">{{$c->ciclo}} / {{$c->ciclo_etapa}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <select id="buscaDisciplina" name="disciplina[]" class=" multiple4" multiple onchange="defineTemaBusca($('#buscaTema').val()); defineUnidadeBusca($('#buscaUnidade').val()); defineHabilidadeBusca($('#buscaHabilidade').val())">
                                            @foreach($disciplina as $d)
                                                @if($d->titulo=="Língua Portuguesa" || $d->titulo=="Matemática")
                                                    <option value="{{$d->id}}">{{$d->titulo}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <select id="buscaTema" name="tema[]" class=" multiple6" multiple>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select id="buscaUnidade" name="unidade_tematica[]" class="multiple7" multiple onchange="defineHabilidadeBusca($('#buscaHabilidade').val())">

                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select id="buscaHabilidade" name="habilidade[]" class="multiple8" multiple >

                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select id="buscaDificuldade" name="dificuldade[]" class="multiple9" multiple>
                                            <option value="0">Fácil</option>
                                            <option value="1">Médio</option>
                                            <option value="2">Difícil</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select id="buscaFormato" name="formato[]" class="multiple10" multiple>
                                            @foreach($formato as $d)
                                                <option value="{{$d->id}}">{{$d->titulo}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select id="buscaSuporte" name="suporte[]" class="multiple11" multiple>
                                            @foreach($suporte as $d)
                                                <option value="{{$d->id}}">{{$d->titulo}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select id="buscaFonte" name="fonte[]" class=" multiple5" multiple>
                                            @foreach($fonte as $d)
                                                <option value="{{$d->fonte}}">{{$d->fonte}}</option>
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
                    <div class="pb-2 fs-13">Exibindo <span class="exibindoQuestoes">30</span> de <b class="totalQuestao"></b> questões encontradas</div>
                    <div class="shadow-none p-3 mb-5 bg-light rounded">

                        <div id="resultado-questoes" class="box-container">
                            <ul id="questao" class="list-group list-group-sortable-connected">
                                Aguarde Carregando ...
                            </ul>
                        </div>
                        <br>
                        <div class="pb-2 fs-13">Exibindo <span class="exibindoQuestoes">30</span> de <b class="totalQuestao"></b> questões encontradas</div>

                        <nav class="mt-4" aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item">
                                    <button type="button" class="page-link" onclick="maisQuestoes()" tabindex="-1">Carregar mais questões</button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-md-7 pl-4">
                    <div class="card mb-3">
                        <div class="card-header">Permissionamento</div>
                        <div class="card-body">
                            <div class="form-group">
                                <button type="button" class="btn btn-sm btn-success" onclick=" $('#formEscolasTrumas').modal('show'); "><i class="fas fa-plus"></i> Adicione/Remova instituições e escolas</button>
                            </div>
                            <h6 class="text-center">Instituições e escolas selecionadas *</h6>
                            <div class="row mb-4">
                                    <div class="col">
                                        <ul id="listaPermissao" class="list-style">

                                        </ul>
                                        <div class="invalid-feedback" style="display: block">{{ $errors->first('escola') }}</div>
                                    </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="pb-2 fs-13 pt-3 col-5">* Arraste as questões para esse quadro.</div>
                        <div class="pb-2 pt-3 col-7 text-right" style="font-size: 18px; padding-right: 60px; ">
                            <span class="badge badge-light" style="font-weight: normal !important;"><span id="qtdQuestaoAlocada">0</span> questões</span>
                        </div>
                    </div>
                    <div class="invalid-feedback @if($errors->first('questao'))d-block @endif" style="font-size: 12px">{{ $errors->first('questao') }}</div>
                    <div class="shadow-none p-3 mb-0 bg-light rounded" id="avaliacao">
                        <ul class="list-group list-group-sortable-connected" id="questaoAvaliacao">

                        </ul>

                    </div>
                    <p class="text-center">
                        <br>
                        <a href="{{url('indica/gestao/avaliacao')}}" class="btn btn-secondary float-left">Cancelar</a>
                        <button type="button" class="btn btn-default mt-0 float-right ml-2" onclick="enviar()">Salvar</button>
                    </p>
                </div>

            </div>
        </div>
        <br>
    </section>

<!-- VER QUESTÃO -->
<div class="modal fade" id="formVerQuestao" tabindex="-1"  role="dialog" aria-labelledby="formIncluir" aria-hidden="true">
    <div class="modal-dialog  px-1 px-md-5 modal-full "  role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"></h5>
            </div>
            <div class="modal-body" id="conteudoVer">

            </div>
        </div>
    </div>
</div>
<!-- FIM VERQUESTAO -->

<div class="modal fade" id="formEscolasTrumas" tabindex="0" role="dialog" aria-labelledby="formEscolasTrumas" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="tituloForm">Seleção de Instituições e Escolas</h5>
            </div>
            <div class="modal-body pb-0">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="filter">
                                <div class="input-group ml-1">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                    <input type="text" id="buscaNome" value="" placeholder="Nome da instituição" class="form-control" />
                                </div>
                                <div class="input-group ml-1">
                                    <input type="text" id="buscaEscola" value="" placeholder="Nome da escola" class="form-control" />
                                </div>
                                <div class="input-group ml-1">
                                    <select id="buscaTipo" class="form-control">
                                        <option value="-1">Tipo</option>
                                        @foreach($tipoInstituicao as $t)
                                            <option value="{{$t->id}}">{{$t->titulo}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group ml-1">
                                    <button type="button" onclick="buscarEscolas('')" class="btn btn-secondary btn-sm">Localizar</button>
                                </div>
                                <div class="input-group ml-1">
                                    <button type="button" onclick="$('#buscaNome').val(''); $('#buscaEscola').val(''); $('#buscaTipo').val('-1'); buscarEscolas(''); " class="btn btn-secondary btn-sm">Limpar Filtros</button>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12" id="tabelaEscolasTurmas">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Finalizar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="{{config('app.cdn')}}/fr/includes/js/jquery-sortable/jquery.sortable.js"></script>

<script>
    $(document).ready(function() {
        @if( ( (isset($dados->escolas) && $dados->escolas) || old('instituicao') ))
            $.ajax({
                url: '{{url('gestao/agenda/familia/getInstituicaoSelecionadas')}}?' + $('#formDados').serialize(),
                type: 'post',
                dataType: 'json',
                data: {
                    _token: '{{csrf_token()}}'
                },
                success: function (data) {
                    $('#listaPermissao').html(data);
                },
                error: function () {
                    swal("", "Não foi possível carregar a lista de instituicoes selecionadas.", "error");
                }
            });
        @endif
    });

    $('#formEscolasTrumas').on('show.bs.modal', function () {
        $('#buscaNome').val('');
        buscarEscolas('');
    });

    $(document).on('click', '.page-link', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        buscarEscolas(page);
    });

    function buscarEscolas(page){
        var form = '';
        form = $('#listaPermissao input[type=hidden]').serialize();
        $.ajax({
            url: '{{url('gestao/agenda/familia/getInstituicoesEscolas')}}?'+form,
            type: 'post',
            dataType: 'json',
            data: {
                nome: $('#buscaNome').val(),
                nomeEscola: $('#buscaEscola').val(),
                tipo: $('#buscaTipo').val(),
                page: page,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                $('#tabelaEscolasTurmas').html(data);
            },
            error: function() {
                swal("", "Não foi possível carregar a lista de escolas.", "error");
            }
        });
    }

    function adicionaInstituicao(instituicaoId, escolaId){
        var quantidade = 0;
            quantidade = $('#selecionadaInstituicao'+instituicaoId).length;

        if(quantidade == 0) {
                nomeInst = $('#nomeInstituicao' + instituicaoId).html();
                var add = '<li class="ui-state-default" id="selecionadaInstituicao' + instituicaoId + '">';
                add += '<input type="hidden" name="instituicao[]" value="' + instituicaoId + '">';
                add += '<b>' + nomeInst + '</b>';
                add += '<p id="listaEscolasSelecionadasInstituicao' + instituicaoId + '"></p></li>';
                $('#listaPermissao').append(add);

        }
        if(escolaId == -1) {
                $('#listaEscolasSelecionadasInstituicao' + instituicaoId).html('<input type="hidden" name="escola[' + instituicaoId + '][]" value="0"><span id="todasEscolasInstituicao' + instituicaoId + '" class="badge badge-secondary">Todas as escolas selecionadas.</span>');
        }
        else{
            if(quantidade != 0) {
                $('#todasEscolasInstituicao' + instituicaoId).remove();
            }
            nomeEscola = $('#nomeEscola' + escolaId).html();
                $('#listaEscolasSelecionadasInstituicao' + instituicaoId).append('<input type="hidden" name="escola[' + instituicaoId + '][]" value="' + escolaId + '"><span class="badge badge-light ml-2" id="selecionadaEscola' + escolaId + '">' + nomeEscola + '</span>');
        }

    }
</script>



<script>

    var selComponenteDados = new vanillaSelectBox(".multiple1", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Componente curricular",
        "borda":"#dcdee2",
    });

    var selEtapa = new vanillaSelectBox(".multiple3", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Etapa / Ano",
        "borda":"#dcdee2",
    });

    var selComponente = new vanillaSelectBox(".multiple4", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Componente curricular",
        "borda":"#dcdee2",
    });

    var selFonte = new vanillaSelectBox(".multiple5", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Fonte",
        "borda":"#dcdee2",
    });

    var selTema = new vanillaSelectBox(".multiple6", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Assunto / Tema",
        "borda":"#dcdee2",
    });

    var selUnidade = new vanillaSelectBox(".multiple7", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Unidade Temática",
        "borda":"#dcdee2",
    });

    var selHabilidade = new vanillaSelectBox(".multiple8", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Habilidade",
        "borda":"#dcdee2",
    });

    var selDificuldade = new vanillaSelectBox(".multiple9", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Dificuldade",
        "borda":"#dcdee2",
    });

    var selFormato = new vanillaSelectBox(".multiple10", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Formato",
        "borda":"#dcdee2",
    });

    var selSuporte = new vanillaSelectBox(".multiple11", {
        "maxHeight": 250,
        "search": true ,
        "placeHolder": "Suporte",
        "borda":"#dcdee2",
    });
    /*var sel = new vanillaSelectBox(".multiple12", {
        "maxHeight": 250,
        "placeHolder": "Banco de questões",
        "borda":"#dcdee2",
    });*/


    function exibir(id)
    {
        url = '{{url('indica/gestao/avaliacao/minhas_questoes/ver')}}/'+id;
        $('#modalLabel').html('Questão '+id);
        $('#conteudoVer').html('<iframe style="border:none;" width="100%" height="600px" src="'+url+'"></iframe>')
        $('#formVerQuestao').modal('show');
    }
    function atualizadaSortable()
    {
        {{--
        $('.list-group-sortable-connected').sortable({
            placeholderClass: 'list-group-item',
            connectWith: '.connected',
        });

        $('.list-group-sortable-connected').sortable().bind('sortconnect', function(e, ui) {
            var itemAvaliacao = ui.item;
            var quadrante = itemAvaliacao.parent().attr('id');
            var questao = $(itemAvaliacao).find('input[name="questao[]"]').val();

            if(quadrante == 'questao'){
                removerQuestao(questao,$(itemAvaliacao).find('.remover'), true);
            }else if(quadrante == 'questaoAvaliacao'){
                adicionarQuestao(questao, true);
            }
        });
        --}}
    }



    function adicionarQuestao(id, AutoAppend = false) //AutoAppend informa se a qustão já esta incluida
    {
        aux = parseInt($('#qtdQuestaoAlocada').html()) +1;
        $('#qtdQuestaoAlocada').html(aux);

        if(AutoAppend == false){
            $('#questaoAvaliacao').append($('#questao'+id));
        }

        defineSelecionados();
    }



    function removerQuestao(id,elemento, AutoAppend = false)
    {
        aux = parseInt($('#qtdQuestaoAlocada').html()) -1;
        console.log(aux)
        if(aux > 0){
            $('#qtdQuestaoAlocada').html(aux);
        }
        else{
            $('#qtdQuestaoAlocada').html(0);
        }
        elemento = $(elemento).parent().parent();
        if(AutoAppend == false){
            $('#questao').append($(elemento));
        }
        defineSelecionados();
    }



    function atualizadaSortable()
    {
        $('.list-group-sortable-connected').sortable({
            placeholderClass: 'list-group-item',
            connectWith: '.connected',
        });
    }
    function adicionarQuestao(id)
    {
        aux = parseInt($('#qtdQuestaoAlocada').html()) +1;
        $('#qtdQuestaoAlocada').html(aux);
        $('#questaoAvaliacao').append($('#questao'+id));
        $('.pesoQuestaoAdicionada').prop('readonly',false);
        defineSelecionados();
    }

    function removerQuestao(id,elemento)
    {
        aux = parseInt($('#qtdQuestaoAlocada').html()) -1;
        if(aux > 0){
            $('#qtdQuestaoAlocada').html(aux);
        }
        else{
            $('#qtdQuestaoAlocada').html(0);
        }

        elemento = $(elemento).parent().parent();
       $('#questao').append($(elemento));
       defineSelecionados();
    }

    var pagina = 1;
    var formulario = '';
    var vetSelecionados = new Array;

    function defineSelecionados()
    {
        vetSelecionados = new Array;
        $('#questaoAvaliacao li input').each(function(){
            if($(this).attr('type')=='hidden') {
                vetSelecionados.push($(this).val());
            }
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
        //sel.empty();
        selHabilidade.empty();
        selUnidade.empty();
        selTema.empty();
        selEtapa.empty();
        selComponente.empty();
        selDificuldade.empty();
        selFormato.empty();
        selSuporte.empty();
        selFonte.empty();
        $('#buscaPalavraChave').val('');
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
        url = '{{url('indica/gestao/avaliacao/getQuestaoAjax')}}/?'+form+'&page='+pagina+'&full=1';
        $.get(url,function(retorno){
            if(tipo=='limpar')
            {
                $('#questao').html(retorno.questao);
                $('.exibindoQuestoes').html(retorno.exibindo);

            }
            else
            {
                $('#questao').append(retorno.questao);
            }
            $('.totalQuestao').html(retorno.total);
            atualizadaSortable();
            eventosPosAjax();
        })
    }


    function enviar()
    {
        $('#questao').empty();

        $('#FormPublicarPost select').each(function(){
            $(this).clone().appendTo('#formCadastro');
        });

        $('#questaoAvaliacao input').each(function(){
            $(this).clone().appendTo('#formCadastro');
        });

        $('#listaPermissao input').each(function(){
            console.log(1);
            $(this).clone().appendTo('#formCadastro');
        });

        $('#formCadastro').submit();
    }



    $(document).ready(function(){


        @if(old('questao',@$dados->questao))
            sel = new Array;
            @foreach (old('questao',@$dados->questao) as $d)
                sel.push({{$d}});
            @endforeach
            $.ajax({
                url: '{{url('indica/gestao/avaliacao/getQuestaoSelecionadas')}}',
                type: 'post',
                dataType: 'json',
                data: {
                    selecionado: sel,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    $('#questaoAvaliacao').append(data);
                    eventosPosAjax();
                    atualizadaSortable();
                    defineSelecionados();
                    limparQuestoes();
                    aux = 0;
                    $('#questaoAvaliacao li').each(function(){
                        aux++;
                    })
                    $('#qtdQuestaoAlocada').html(aux);
                },
                error: function(data) {
                    swal("", "Erro ao carregar questões", "error");
                }
            });
        @else
            defineSelecionados();
            limparQuestoes();
            eventosPosAjax();
        @endif


    });

    function eventosPosAjax(){
        $(".inputNumberReadOnly").keypress(function (evt) {
            evt.preventDefault();
        });
    }

    function defineTemaBusca(sel)
    {
        disciplina = $('#buscaDisciplina').val();
        $.ajax({
            url: '{{url('/gestao/avaliacao/getTemaAjaxLista')}}',
            type: 'post',
            dataType: 'json',
            data: {
                disciplina_id: disciplina,
                selecionado: sel,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                selTema.destroy();
                $('#buscaTema').html(data);
                selTema = new vanillaSelectBox(".multiple6", {
                    "maxHeight": 250,
                    "search": true ,
                    "placeHolder": "Assunto / Tema",
                    "borda":"#dcdee2",
                });
            },
            error: function(data) {
                swal("", "Erro ao carregar Assunto / Tema", "error");
            }
        });
    }

    function defineUnidadeBusca(sel)
    {
        disciplina = $('#buscaDisciplina').val();
        cicloetapa = $('#buscaCicloEtapa').val();
        $.ajax({
            url: '{{url('/gestao/avaliacao/getUnidadeTematicaAjaxLista')}}',
            type: 'post',
            dataType: 'json',
            data: {
                disciplina_id: disciplina,
                cicloetapa_id: cicloetapa,
                selecionado: sel,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                selUnidade.destroy();
                $('#buscaUnidade').html(data);
                selUnidade = new vanillaSelectBox(".multiple7", {
                    "maxHeight": 250,
                    "search": true ,
                    "placeHolder": "Unidade temática",
                    "borda":"#dcdee2",
                });
            },
            error: function(data) {
                swal("", "Erro ao carregar Assunto / Tema", "error");
            }
        });
    }

    function defineHabilidadeBusca(sel)
    {
        disciplina = $('#buscaDisciplina').val();
        cicloetapa = $('#buscaCicloEtapa').val();
        unidade = $('#buscaUnidade').val();
        $.ajax({
            url: '{{url('/gestao/avaliacao/getBnccAjaxLista')}}',
            type: 'post',
            dataType: 'json',
            data: {
                disciplina_id: disciplina,
                cicloetapa_id: cicloetapa,
                unidade_tematica_id: unidade,
                selecionado: sel,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                selHabilidade.destroy();
                $('#buscaHabilidade').html(data);
                selHabilidade = new vanillaSelectBox(".multiple8", {
                    "maxHeight": 250,
                    "search": true ,
                    "placeHolder": "Habilidade",
                    "borda":"#dcdee2",
                });
            },
            error: function(data) {
                swal("", "Erro ao carregar Assunto / Tema", "error");
            }
        });
    }



</script>
@stop
