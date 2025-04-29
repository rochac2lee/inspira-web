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
                        <a href="{{url('/avaliacao_ead/gestao/avaliacao')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        EAD -
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

                                        <div class="form-group mb-0 col-md-2">
                                            <label><b>* Tipo do peso</b></label>
                                            <div class="input-group">
                                                <select name="tipo_peso" id="tipo_peso" class="form-control form-control-sm rounded {{ $errors->has('tipo_peso') ? 'is-invalid' : '' }}" onchange="mudaTipoPesoAvaliacao()">
                                                    <option value="">Selecione</option>
                                                    <option @if(old('tipo_peso',@$dados->tipo_peso) == '1') selected @endif value="1">Peso por questão</option>
                                                    <option @if(old('tipo_peso',@$dados->tipo_peso) == '2') selected @endif value="2">Peso da avaliação</option>
                                                </select>
                                                <div class="invalid-feedback @if($errors->first('tipo_peso'))d-block @endif" style="font-size: 12px">{{ $errors->first('tipo_peso') }}</div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0 col-md-2">
                                            <label><b>* Pesos nas Questões</b></label>
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
                                        <div class="form-group mb-0 col-md-2">
                                            <label><b>* Quantidade de Questões</b></label>
                                            <div class="input-group">
                                                <input type="number" step="1" min="1"  name="quantidade_minima_questoes" value="{{old('quantidade_minima_questoes',@$dados->quantidade_minima_questoes)}}" class="form-control form-control-sm rounded {{ $errors->has('peso') ? 'is-invalid' : '' }}">
                                                <div class="invalid-feedback @if($errors->first('quantidade_minima_questoes'))d-block @endif" style="font-size: 12px">{{ $errors->first('quantidade_minima_questoes') }}</div>
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
                                                <option value="{{$d->id}}">{{$d->titulo}}</option>
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
                            <!-- <span class="badge badge-light" style="font-weight: normal !important;">peso total <span id="pesoTotal">0</span></span> -->
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
                    <a href="{{url('/avaliacao_ead/gestao/avaliacao')}}" class="btn btn-secondary float-left">Cancelar</a>
                    <button type="button" class="btn btn-default mt-0 float-right ml-2" onclick="enviar()">Salvar</button>
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


<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="{{config('app.cdn')}}/fr/includes/js/jquery-sortable/jquery.sortable.js"></script>
<script>

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
        url = '{{url('/avaliacao_ead/gestao/avaliacao/minhas_questoes/ver')}}/'+id;
        $('#modalLabel').html('Questão '+id);
        $('#conteudoVer').html('<iframe style="border:none;" width="100%" height="600px" src="'+url+'"></iframe>')
        $('#formVerQuestao').modal('show');
    }

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

        url = '{{url('/avaliacao_ead/gestao/avaliacao/getQuestaoAjax')}}/?'+form+'&page='+pagina+'&full=1';
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
                url: '{{url('/avaliacao_ead/gestao/avaliacao/getQuestaoSelecionadas')}}',
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
