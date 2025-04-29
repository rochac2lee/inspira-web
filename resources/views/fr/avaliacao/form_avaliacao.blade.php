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

    <section class="section section-interna">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mt-4">
                    <h4 class="pb-3 border-bottom mb-4">
                        <a href="{{url('/gestao/avaliacao')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
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
                                        <div class="form-group col-md-2">
                                            <label><b>* Componente Curricular</b></label>
                                            <select name="disciplina_id" class="multiple1 {{ $errors->has('disciplina_id') ? 'is-invalid' : '' }}" single>
                                                <option value=''></option>
                                                @foreach($disciplina as $d)
                                                    @if($d->titulo!='EAD')
                                                    <option value='{{$d->id}}' @if(old('disciplina_id',@$dados->disciplina_id) == $d->id) selected @endif>{{$d->titulo}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback @if($errors->first('disciplina_id'))d-block @endif" style="font-size: 12px">{{ $errors->first('disciplina_id') }}</div>

                                        </div>
                                        <div class="form-group col-md-2">
                                            <label><b>Tipo</b></label>
                                            <select id="tipoProva" name="tipo" class="form-control form-control-sm rounded {{ $errors->has('tipo') ? 'is-invalid' : '' }}">
                                                <optio value="">Selecione</optio>
                                                <option @if(old('tipo',@$dados->tipo) == 'p') selected @endif value="p">Prova</option>
                                                <option @if(old('tipo',@$dados->tipo) == 's') selected @endif value="s">Simulado</option>
                                                <option @if(old('tipo',@$dados->tipo) == 'l') selected @endif value="l">Lista de Exercícios</option>
                                            </select>
                                            <div class="invalid-feedback @if($errors->first('tipo'))d-block @endif" style="font-size: 12px">{{ $errors->first('tipo') }}</div>

                                        </div>
                                        <div class="form-group col-md-2">
                                            <label><b>* Aplicação</b></label>
                                            <select name="aplicacao" id="aplicacao" class="form-control form-control-sm rounded {{ $errors->has('aplicacao') ? 'is-invalid' : '' }}" onchange="mudouAplicacao()">
                                                <option value="">Selecione</option>
                                                <option @if(old('aplicacao',@$dados->aplicacao) == 'o') selected @endif value="o">Online</option>
                                                <option @if(old('aplicacao',@$dados->aplicacao) == 'i') selected @endif value="i">Impressa</option>
                                            </select>
                                            <div class="invalid-feedback @if($errors->first('aplicacao'))d-block @endif" style="font-size: 12px">{{ $errors->first('aplicacao') }}</div>
                                        </div>
                                        <div class="form-group mb-0 col-md-2">
                                            <label><b>Ordenação das Questões</b></label>
                                            <select name="ordenacao" id="ordenacao" class="form-control form-control-sm rounded {{ $errors->has('ordenacao') ? 'is-invalid' : '' }}">
                                                <option value="">Selecione a aplicação</option>
                                            </select>
                                            <div class="invalid-feedback @if($errors->first('ordenacao'))d-block @endif" style="font-size: 12px">{{ $errors->first('ordenacao') }}</div>
                                        </div>
                                        <div class="form-group mb-0 col-md-2">
                                            <label><b>Tipo do peso</b></label>
                                            <div class="input-group">
                                                <select name="tipo_peso" id="tipo_peso" class="form-control form-control-sm rounded {{ $errors->has('tipo_peso') ? 'is-invalid' : '' }}" onchange="mudaTipoPesoAvaliacao()">
                                                    <option value="">Não definir</option>
                                                    <option @if(old('tipo_peso',@$dados->tipo_peso) == '1') selected @endif value="1">Peso por questão</option>
                                                    <option @if(old('tipo_peso',@$dados->tipo_peso) == '2') selected @endif value="2">Peso da avaliação</option>
                                                </select>
                                                <div class="invalid-feedback @if($errors->first('tipo_peso'))d-block @endif" style="font-size: 12px">{{ $errors->first('tipo_peso') }}</div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0 col-md-2">
                                            <label><b>Pesos nas Questões</b></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" onclick="if(!$('#peso').is('[disabled]')){document.getElementById('peso').stepDown(); mudaPeso();}" class="btn btn-sm btn-secondary "><i class="fas fa-minus"></i></button>
                                                </div>
                                                <input type="number" id="peso" name="peso" value="{{old('peso',@$dados->peso)}}" class="inputNumberReadOnly form-control form-control-sm rounded {{ $errors->has('peso') ? 'is-invalid' : '' }}" placeholder="Peso" min="0" style="text-align:center;">
                                                <div class="input-group-append">
                                                    <button type="button" onclick="if(!$('#peso').is('[disabled]')){document.getElementById('peso').stepUp(); mudaPeso();}" class="btn btn-sm btn-secondary "><i class="fas fa-plus"></i></button>
                                                </div>
                                                <div class="invalid-feedback @if($errors->first('peso'))d-block @endif" style="font-size: 12px">{{ $errors->first('peso') }}</div>
                                            </div>
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
                                                <option value="{{$c->id}}">{{$c->ciclo}} / {{$c->ciclo_etapa}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <select id="buscaDisciplina" name="disciplina[]" class=" multiple4" multiple onchange="defineTemaBusca($('#buscaTema').val()); defineUnidadeBusca($('#buscaUnidade').val()); defineHabilidadeBusca($('#buscaHabilidade').val())">
                                            @foreach($disciplina as $d)
                                                @if($d->titulo!='EAD')
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
                                    @if(auth()->user()->permissao != 'Z')
                                    <div class="form-group col-md-6">
                                        <select id="buscaBiblioteca" name="biblioteca" class=" multiple12">
                                                <option value="todas">Todas</option>
                                                <option value="">Minhas questões</option>
                                                <option value="1">Questões da biblioteca</option>
                                        </select>
                                    </div>
                                    @endif
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
                            <ul id="questaoBiblioteca" class="list-group list-group-sortable-connected">
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
                        <div class="card-header">Montar Avaliação</div>
                        <div class="card-body">

                                <div class="form-row">
                                    <label class="font-weight-bold fs-12">* Título</label>
                                    <div class="form-group col-md-12">
                                        <input name="titulo" value="{{old('titulo',@$dados->titulo)}}" type="text" placeholder="Título" class="form-control form-control-sm rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}" />
                                        <div class="invalid-feedback @if($errors->first('titulo'))d-block @endif" style="font-size: 12px">{{ $errors->first('titulo') }}</div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
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

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold fs-12"> Fim da Avaliação</label>
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

                                    <div class="form-group mb-0 col-md-6" id="liberarResultado" @if(old('eh_ead',@$dados->eh_ead) == 1) style="display: none" @endif>
                                        <label class="font-weight-bold fs-12">* Liberação do Resultado</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </div>
                                            </div>
                                            @php
                                                $dataLiberado = '';
                                                if(@$dados->data_hora_final && @$dados->data_hora_liberacao_resultado)
                                                {
                                                    $dataLiberado = $dados->data_hora_liberacao_resultado->format('d/m/Y H:i:s');
                                                }
                                            @endphp
                                            <input name="data_hora_liberacao_resultado" autocomplete="off" value="{{old('data_hora_liberacao_resultado',$dataLiberado)}}" type="text" id="datetimepicker3" placeholder="00/00/0000 00:00" class="form-control form-control-sm rounded {{ $errors->has('data_hora_liberacao_resultado') ? 'is-invalid' : '' }}" />
                                        </div>
                                        <div class="invalid-feedback @if($errors->first('data_hora_liberacao_resultado'))d-block @endif" style="font-size: 12px">{{ $errors->first('data_hora_liberacao_resultado') }}</div>
                                    </div>

                                    <div class="form-group mb-0 col-md-6">
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
                                </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="pb-2 fs-13 pt-3 col-5">* Arraste as questões para esse quadro.</div>
                        <div class="pb-2 pt-3 col-7 text-right" style="font-size: 18px; padding-right: 60px; ">
                            <span class="badge badge-light" style="font-weight: normal !important;"><span id="qtdQuestaoAlocada"></span> questões</span>
                            <span class="badge badge-light" style="font-weight: normal !important;">peso total <span id="pesoTotal">0</span></span>
                        </div>
                    </div>
                    <div class="invalid-feedback @if($errors->first('questao'))d-block @endif" style="font-size: 12px">{{ $errors->first('questao') }}</div>
                    <div class="shadow-none p-3 mb-0 bg-light rounded" id="avaliacao">
                        <ul class="list-group list-group-sortable-connected" id="questaoAvaliacao">

                        </ul>

                    </div>
                    <div class="col border bg-light p-2 mt-2 mb-3 ">
                        <div class="form-group mb-0">
                            <div class="custom-control custom-switch">
                                <input name="ativo" @if(old('ativo',@$dados->ativo) == '1') checked @endif  value="1" type="checkbox" class="custom-control-input" id="customSwitch2">
                                <label class="custom-control-label pt-1" for="customSwitch2">Publicar Avaliação?</label>
                                <small class="form-text w-100 text-muted">
                                Se publicado, a avaliação ficará disponível para realização.
                                </small>
                            </div>
                        </div>
                    </div>
                    <a href="{{url('/gestao/avaliacao')}}" class="btn btn-secondary float-left">Cancelar</a>
                    <button type="button" class="btn btn-default mt-0 float-right ml-2" onclick="verificaPublicar()">Salvar</button>
                </div>
                    <input type="hidden" value="" name="publicar" id="publicar">

            </div>
        </div>
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


<!-- PUBLICAR -->
<div class="modal fade " id="formPublicar" tabindex="-1" role="dialog" aria-labelledby="formPublicar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Publicação de Avaliação</h5>
            </div>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            Você deseja mesmo publicar esse registro?<br><br>
                            <b id="nomeAvaliacao" style="font-size: 18px"></b><br><br>
                        </div>
                        @if(auth()->user()->permissao == 'P' || auth()->user()->permissao == 'C')
                            <div class="col-12" style="background-color: rgba(219,219,219,0.76) ">
                                <br>
                                <p><b>Para publicar é necessário selecionar as turmas que farão essa avaliação.</b></p>
                                @if(auth()->user()->permissao == 'C')
                                    <select name="tipoPublicar" id="tipoPublicar" onchange="trocaTipoPublicar()">
                                        <option value="1" @if(old('tipo') == 1 || old('tipo') == '') selected @endif>Publicar apenas na biblioteca visível para os professores das escolas.</option>
                                        <option value="2" @if(old('tipo') == 2) selected @endif>Publicar para realização de avaliação por turmas específicas.</option>
                                    </select>
                                    <br>
                                    <br>
                                @endif
                                <span id="selTurma">
                                <select name="turmas[]" id="turma" multiple>
                                    @foreach($turmas as $t)
                                        <option value="{{$t->id}}">{{$t->ciclo}} / {{$t->ciclo_etapa}} - {{$t->titulo}}</option>
                                    @endforeach
                                </select>
                            </span>
                                <div class="invalid-feedback @if($errors->first('turmas')) d-block @endif" style="font-size: 12px">{{ $errors->first('turmas') }}</div>
                                <br><br>
                            </div>
                        @elseif(auth()->user()->permissao != 'Z')
                            <div class="col-12" style="background-color: rgba(219,219,219,0.76) ">
                                <br>
                                <p>
                                    @if(auth()->user()->permissao == 'Z')
                                       <!-- <b>A sua avaliação ficará disponível na biblioteca da rede. </b> -->
                                    @else
                                        <b>A sua avaliação ficará disponível apenas na biblioteca da sua instituição. </b>
                                    @endif
                                </p>
                            </div>
                        @endif
                        <div class="col-12">
                            <br><br>
                            <p class="text-justify"><b >ATENÇÃO:</b> A publicação da avaliação NÃO poderá ser desfeita. </p>
                            <p><b >Tem certeza que deseja publicar?</b></p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                <button type="button" onclick="enviar();" class="btn btn-success">Sim, salvar e publicar</button>
            </div>
        </div>
    </div>
</div>
<!-- FIM PUBLICAR -->
<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="{{config('app.cdn')}}/fr/includes/js/jquery-sortable/jquery.sortable.js"></script>
<script>
    var ead =0;
    @if(@$dados->eh_ead == 1)
        ead =1;
    @endif

    function mudaEAD(elemento){
        if($(elemento).prop('checked')) {
            ead = 1;
            $('#liberarResultado').hide()
            $("#tipoProva").val("p").change();
            $("#aplicacao").val("o").change();
            $("#ordenacao").val("1").change();
            $("#tipo_peso").val("2").change();
        }
        else {
            ead = 0;
            $('#liberarResultado').show();
        }
        formulario = $('#buscarQuestoes').serialize();
        ajaxQuestoes(formulario, 'limpar');
    }
    function mudouAplicacao(){
        aplicacao = $('#aplicacao').val();
        if(aplicacao == 'o'){
            ordem = '<option value="" >Normal - Padrão</option>';
            ordem += '<option value="1" @if(old('ordenacao',@$dados->ordenacao) == '1') selected @endif>Aleatorizar questões</option>';

        }else{
            ordem = '<option value="">Normal - Padrão</option>';
            //ordem += '<option value="2" @if(old('ordenacao',@$dados->ordenacao) == '2') selected @endif>2 tipos de provas.</option>';
        }

        $('#ordenacao').html(ordem);

    }
    $(document).ready(function(){
        @if(auth()->user()->permissao == 'P' || auth()->user()->permissao == 'C')
            var selTurmaMulti = new vanillaSelectBox("#turma", {
                "maxHeight": 250,
                "search": true ,
                "placeHolder": "Etapa / Ano - Turma",
            });
            @if(auth()->user()->permissao == 'C')
                var selTipo = new vanillaSelectBox("#tipoPublicar", {
                    "maxHeight": 250,
                    "search": false ,
                    "placeHolder": "",
                });
            @endif
        @endif
    })

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
    @if(auth()->user()->permissao != 'Z')
    var selBiblioteca = new vanillaSelectBox(".multiple12", {
        "maxHeight": 250,
        "placeHolder": "Banco de questões",
        "borda":"#dcdee2",
    });
    @endif

    function trocaTipoPublicar(){
        tipo = $('#tipoPublicar').val();
        if(tipo == '1'){
            $('#selTurma').hide();
        }else{
            $('#selTurma').show();
        }
    }

    function modalPublicar(id, titulo)
    {
        @if(auth()->user()->permissao == 'C')
            tipo = $('#tipoPublicar').val();
        if(tipo != '2'){
            $('#selTurma').hide();
        }
        @endif
            idPublicar = id;
        $('#nomeAvaliacao').html(titulo);
        $('#tituloAvaliacao').val(titulo);
        $('#idAvaliacao').val(id);
        $('#formPublicar').modal('show');
    }

    function exibir(id)
    {
        url = '{{url('/gestao/avaliacao/minhas_questoes/ver')}}/'+id;
        $('#modalLabel').html('Questão '+id);
        $('#conteudoVer').html('<iframe style="border:none;" width="100%" height="600px" src="'+url+'"></iframe>')
        $('#formVerQuestao').modal('show');
    }


    {{--
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
        tipo = $('#tipo_peso').val();
        if(tipo == 2) {
            mudaTipoPesoAvaliacao();
        }else{
            $('.pesoQuestaoAdicionada').prop('readonly',false);
            $('.spanPeso'+id).show();
            peso= parseFloat($('#peso').val()).toFixed(2);
            $('#pesoQuestao'+id).val(peso);
            totalizaPeso();
        }

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
       $('#questaoBiblioteca').append($(elemento));
        $('.spanPeso'+id).hide();
        $('pesoQuestao'+id).val('');
        tipo = $('#tipo_peso').val();
        if(tipo == 2) {
            mudaTipoPesoAvaliacao();
        }
        totalizaPeso();
       defineSelecionados();
    }
    --}}

    function atualizadaSortable()
    {
        $('.list-group-sortable-connected').sortable({
            placeholderClass: 'list-group-item',
            connectWith: '.connected',
        });

        $('.list-group-sortable-connected').sortable().bind('sortconnect', function(e, ui) {
            var itemAvaliacao = ui.item;
            var quadrante = itemAvaliacao.parent().attr('id');
            var questao = $(itemAvaliacao).find('input[name="questao[]"]').val();

            if(quadrante == 'questaoBiblioteca'){
                removerQuestao(questao,$(itemAvaliacao).find('.remover'), true);
            }else if(quadrante == 'questaoAvaliacao'){
                adicionarQuestao(questao, true);
            }
        });
    }

    function adicionarQuestao(id, AutoAppend = false) //AutoAppend informa se a qustão já esta incluida
    {
        aux = parseInt($('#qtdQuestaoAlocada').html()) +1;
        $('#qtdQuestaoAlocada').html(aux);

        if(AutoAppend == false){
            $('#questaoAvaliacao').append($('#questao'+id));
        }

        tipo = $('#tipo_peso').val();
        if(tipo == 2) {
            mudaTipoPesoAvaliacao();
        }else{
            $('.pesoQuestaoAdicionada').prop('readonly',false);
            $('.spanPeso'+id).show();
            peso= parseFloat($('#peso').val()).toFixed(2);
            $('#pesoQuestao'+id).val(peso);
            totalizaPeso();
        }

        defineSelecionados();
    }

    function removerQuestao(id,elemento, AutoAppend = false)
    {
        aux = parseInt($('#qtdQuestaoAlocada').html()) -1;
        if(aux > 0){
            $('#qtdQuestaoAlocada').html(aux);
        }
        else{
            $('#qtdQuestaoAlocada').html(0);
        }

        elemento = $(elemento).parent().parent();
        if(AutoAppend == false){
            $('#questaoBiblioteca').append($(elemento));
        }
        $('.spanPeso'+id).hide();
        $('pesoQuestao'+id).val('');
        tipo = $('#tipo_peso').val();
        if(tipo == 2) {
            mudaTipoPesoAvaliacao();
        }
        totalizaPeso();
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
        selBiblioteca.empty();
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

        url = '{{url('/gestao/avaliacao/getQuestaoAjax')}}/?'+form+'&page='+pagina+'&full=1'+'&ead='+ead;
        $.get(url,function(retorno){
            if(tipo=='limpar')
            {
                $('#questaoBiblioteca').html(retorno.questao);
                $('.exibindoQuestoes').html(retorno.exibindo);

            }
            else
            {
                $('#questaoBiblioteca').append(retorno.questao);
            }
            $('.totalQuestao').html(retorno.total);
            atualizadaSortable();
            eventosPosAjax();
        })
    }

    function verificaPublicar(){
        if($('#customSwitch2').prop('checked')){
            $('#formPublicar').modal('show');
        }else{
            enviar();
        }
    }

    function enviar()
    {
        $('#questaoBiblioteca').empty();

        $('#FormPublicarPost select').each(function(){
            $(this).clone().appendTo('#formCadastro');
        });

        $('#questaoAvaliacao input').each(function(){
            $(this).clone().appendTo('#formCadastro');
        });
        $('#formCadastro').submit();
    }

    function mudaTipoPesoAvaliacao(){

        tipo = $('#tipo_peso').val();
        showPesoQuestao = false;
        if(tipo != ''){
            showPesoQuestao = true;
            $('#peso').prop('disabled',false);
            if(tipo == 2){
                $('.pesoQuestaoAdicionada').prop('readonly',true);
            }
            else{
                $('.pesoQuestaoAdicionada').prop('readonly',false);
            }
        }else{
            $('.pesoQuestaoAdicionada').val('');
            $('#peso').val('');
            $('#peso').prop('disabled',true);
        }
        mudaPeso();
        $( "#questaoAvaliacao li .divPesoQuestaoAdicionada" ).each(function() {
            if(showPesoQuestao){
                $(this).show();
            }else{
                $(this).hide();
            }

        });
    }

    function mudaPeso(){
        if($('#tipo_peso').val()!='') {
            tipo = $('#tipo_peso').val();
            if($('#peso').val()!='') {
                peso = parseFloat($('#peso').val());
            }else{
                peso = 10;
                $('#peso').val(10);
            }
            count = 0;

            if (tipo == 2) {
                count = 0;
                totalRedondo = 0;
                $("#questaoAvaliacao li ").each(function () {
                    count++;
                    valor = peso / count;
                    totalRedondo = parseFloat(valor.toFixed(2))*count;
                    $('.pesoQuestaoAdicionada').val(valor.toFixed(2));
                    $('#qtdQuestaoAlocada').html(count);
                });
                i = 0;
                $( "#questaoAvaliacao li .divPesoQuestaoAdicionada .pesoQuestaoAdicionada" ).each(function() {
                    if(i == 0 ) {
                        valor = parseFloat($(this).val()) + (peso - totalRedondo);
                        $(this).val(valor.toFixed(2));
                    }
                   i++;
                });
                $('#pesoTotal').html(peso);
            } else {
                valorCadaQuestao = peso;
                count = 0;
                $( "#questaoAvaliacao li .divPesoQuestaoAdicionada .pesoQuestaoAdicionada" ).each(function() {
                    valorCadaQuestao = parseFloat(valorCadaQuestao).toFixed(2);
                    $(this).val(valorCadaQuestao);
                    count++;
                    txt = parseFloat(valorCadaQuestao*count).toFixed(2).replace('.',',');
                    $('#pesoTotal').html(txt);
                    $('#qtdQuestaoAlocada').html(count);
                });
            }
        }
        else{
            count = 0;
            $( "#questaoAvaliacao li .divPesoQuestaoAdicionada .pesoQuestaoAdicionada" ).each(function() {
                count++;
            });
            $('#qtdQuestaoAlocada').html(count);
        }
    }

    function totalizaPeso()
    {
        total = 0;
        $( "#questaoAvaliacao li .divPesoQuestaoAdicionada .pesoQuestaoAdicionada" ).each(function() {
            total += parseFloat($(this).val());
            txt = parseFloat(total).toFixed(2).replace('.',',');
            $('#pesoTotal').html(txt);
        });

    }

    function diminuiPesoQuestao(el)
    {
        elemento = $(el).parent().next();
        if(!$(elemento).is('[readonly]')){
            elemento[0].stepDown();
            totalizaPeso();
        }
    }

    function aumentaPesoQuestao(el)
    {
        elemento = $(el).parent().prev();
        if(!$(elemento).is('[readonly]')){
            elemento[0].stepUp();
            totalizaPeso();
        }
    }

    $(document).ready(function(){


        @if(old('questao',@$dados->questao))
            sel = new Array;
            @foreach (old('questao',@$dados->questao) as $d)
                sel.push({{$d}});
            @endforeach
            $.ajax({
                url: '{{url('/gestao/avaliacao/getQuestaoSelecionadas')}}',
                type: 'post',
                dataType: 'json',
                data: {
                    selecionado: sel,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    $('#questaoAvaliacao').append(data);
                    eventosPosAjax();
                    mudaTipoPesoAvaliacao();
                    atualizadaSortable();
                    defineSelecionados();
                    limparQuestoes();
                    @foreach (old('peso_questao',@$dados->peso_questao) as $key => $d)
                        $('#pesoQuestao'+{{$key}}).val('{{$d}}');
                    @endforeach


                },
                error: function(data) {
                    swal("", "Erro ao carregar questões", "error");
                }
            });
        @else
            mudaTipoPesoAvaliacao();
        @endif
        setTimeout(function(){
            defineSelecionados();
            limparQuestoes();
        }, 300);

        /// para os input de peso de cada questao
        eventosPosAjax();
        $("#peso").change( function () {
            mudaPeso();
        });

        $("#peso").change( function () {
            mudaPeso();
        });
    });

    function eventosPosAjax(){
        $(".inputNumberReadOnly").keypress(function (evt) {
            evt.preventDefault();
        });
        $(".pesoQuestaoAdicionada").change( function () {
            totalizaPeso();
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
