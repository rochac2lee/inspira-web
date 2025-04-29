<link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/js/slick/slick.css" type="text/css" charset="utf-8" />
<script src="{{ config('app.cdn') }}/fr/includes/js/slick/slick.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="{{ config('app.cdn') }}/fr/includes/css/style_avaliacao_online_v3.css">

<style type="text/css">


    .errado {
        background-color: #fad7d7!important;
        color: #a72828;
    }
    .certo {
        background-color: #d7fad8!important;
        color: #1b692b;
    }
</style>
<!-- Codigo para mostrar o resultado --->
<script type="text/javascript" src="{{url('fr/includes/froala_editor/js/plugins/froala_wiris/integration/WIRISplugins.js?viewer=image')}}"></script>

<script>
    var perguntasTotal = {{count($quiz->perguntas)}};
    var perguntasConcluidas = 0;
</script>
<section class="section section-interna" @if($frame==1) style="padding-top: 0px" @endif>
    @if($frame!=1)
        <div class="container">
            <h2 class="title-page">
                <a href="{{ redirect()->back()->getTargetUrl() }}" title="Voltar" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                Quiz - {{$quiz->titulo}}
            </h2>
        </div>
    @endif

    <div class="container">
        @if(Request::input('r')!=1)
        <div class="mb-4 w-100">
            <div class="fs-13 mb-2 text-right font-weight-bold">Meu Progresso</div>
            <div class="progress" style="height: 20px;">
                <div id="barraProgresso" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
            <div class="fs-13 mt-2 text-center"><strong id="concluidas">0</strong> de <strong>{{count($quiz->perguntas)}}</strong> questões concluídas</div>
        </div>
        @endif
        @if(isset($relatorio) && auth()->user()->permissao != 'A')
            <div class="mb-4 w-100">
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{$relatorio['porcentagem_certo']}}%" aria-valuenow="{{$relatorio['porcentagem_certo']}}" aria-valuemin="0" aria-valuemax="100">{{$relatorio['porcentagem_certo']}}%</div>
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{$relatorio['porcentagem_errado']}}%" aria-valuenow="{{$relatorio['porcentagem_errado']}}" aria-valuemin="0" aria-valuemax="100">{{$relatorio['porcentagem_errado']}}%</div>
                </div>
                <div class="fs-13 mt-2 text-center"><b>{{$relatorio['certo']}}</b> estudante(s) marcaram a resposta adequada / <b>{{$relatorio['errado']}}</b> estudante(s) marcaram respostas não adequadas</div>
                <div class="fs-13 mt-2 text-center">Tempo médio para responder essa pergunta <b>{{$relatorio['tempo_medio']}}</b> (H:m:s)</div>
            </div>
        @endif
        <div class="avaliacao-online" style="margin-top: 0px">
            @php $i=0; @endphp
            @foreach($quiz->perguntas as $p)
                <input type="hidden" id="tentativasP{{$p->id}}" value="{{$quiz->qtd_tentativas}}">
                <div id="pergunta{{$i}}" @if( ($i>0 && $perguntaId=='') || ($perguntaId!='' && $perguntaId!= $p->id)) class="d-none" @endif>
                    @include('fr/quiz/exibir/pergunta_tipo'.$p->tipo)
                    @if(Request::input('r')!=1)
                    <div class="avaliacao-footer-verificar @if($p->log != null) @if($p->log->eh_correta == 1) success @else error @endif @endif" style="margin-top: 15px" id="resposta{{$p->id}}">
                        <div class="row">
                            <div class="col-sm-12">
                                        <span class="success">
                                            <i class="far fa-check-circle ml-4 mr-2" style="font-size: 70px; margin-top: 25px;"></i>
                                            <label>
                                                <p id="feedback_correta{{$p->id}}" style="color: black; font-size: 18px;">
                                                    @if($p->log != null)
                                                        @if($p->log->feedback!='')
                                                            {{$p->log->feedback}}
                                                        @else
                                                            Resposta adequada
                                                        @endif
                                                    @endif
                                                </p>
                                            </label>
                                        </span>
                                <span class="error">
                                            <i class="far fa-times-circle ml-4 mr-2" style="font-size: 70px; margin-top: 25px;"></i>
                                            <label style="position:20px">
                                                <p id="feedback{{$p->id}}" style="color: black; font-size: 18px;">
                                                    @if($p->log != null)
                                                        {{$p->log->feedback}}
                                                    @else
                                                        Resposta inadequada
                                                    @endif
                                                </p>
                                            </label>

                                        </span>
                            </div>
                        </div>
                    </div>

                    <div class="avaliacao-footer" style="margin-top: 15px">
                        <div class="row">
                            <div class="col-4 text-left" >
                                @if($i>0 && $f== '')
                                    <button class="btn btn-outline-secondary" onclick="anterior({{$i}})">Anterior</button>
                                @endif
                            </div>
                            <div class="col-4 text-center divBtnVerificar">
                                @if($p->log == null || (isset($p->log->tentativa) && $p->log->tentativa < $quiz->qtd_tentativas && $p->log->eh_correta !=1) )
                                    @php
                                        $tentativa = $quiz->qtd_tentativas;
                                        if(isset($p->log->tentativa))
                                        {
                                            $tentativa = $quiz->qtd_tentativas-$p->log->tentativa;
                                        }
                                    @endphp
                                    <span id="btnVerificar{{$p->id}}">
                                        <button  class="btn btn-warning" onclick="verificarTipo{{$p->tipo}}({{$p->id}})">Verificar</button>
                                        <br><br><small>Você tem <b><span id="tentativasSobrando{{$p->id}}">{{$tentativa}}</span></b> tentativa(s)</small>
                                    </span>
                                @else
                                    <script> perguntasConcluidas++; </script>
                                @endif

                            </div>
                            <div class="col-4 text-right">
                                @if($i < count($quiz->perguntas)-1 && $f=='')
                                    <button id="btnProximo{{$p->id}}" class="btn btn-secondary" @if(isset($tentativa) && $tentativa>1) disabled @endif onclick="proximo({{$i}})">Próximo</button>
                                    <small id="msgTentativa{{$p->id}}" @if(!isset($tentativa) ||$tentativa<=1)style="display: none"@endif><br>Para avançar, você precisa usar<br> todas as suas tentativas.</small>
                                @elseif(!isset($quiz->respondido) && $f=='')
                                    <a href="{{url('/quiz/finalizado?frame='.$frame.'&q='.$quiz->id)}}" class="btn btn-outline-secondary">Finalizar</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @php $i++; @endphp
            @endforeach
        </div>
    </div>
</section>

@if(Request::input('r')!=1)
<script type="text/javascript">

    var start = Date.now();

    function mudaBarraProgresso()
    {
        porcentagem = parseInt((100*perguntasConcluidas)/perguntasTotal);
        if(porcentagem>100)
            porcentagem = 100;
        $('#barraProgresso').css('width',porcentagem+'%');
        $('#barraProgresso').attr('aria-valuenow',porcentagem);
        $('#barraProgresso').html(porcentagem+'%');
        $('#concluidas').html(perguntasConcluidas);

        if(perguntasConcluidas>=perguntasTotal && '{{$f}}'!= '1')
        {
            location.href = '{{url('quiz/finalizado')}}'+'?&q='+'{{$quiz->id}}'+'&frame='+'{{$frame}}';
        }
    }

    function verificarCorreta(envio)
    {
        varTemTentativa = temTentativa(envio.pergunta);
        varUltima = 0;
        varTempo = Math.floor((Date.now()-start)/1000);
        start = Date.now();
        if(!varTemTentativa)
        {
            varUltima = 1;
        }
        $.ajax({
            url: '{{url('/gestao/quiz/verificarCorreta')}}',
            type: 'post',
            dataType: 'json',
            data: {
                envio: envio,
                ultima: varUltima,
                tempo: varTempo,
                quiz_id: {{$quiz->id}},
                _token: '{{csrf_token()}}'
            },
            success: function(data) {


                $('#resposta'+envio.pergunta).show();
                if(data.correta)
                {
                    $('#msgTentativa'+envio.pergunta).hide();
                    $('#btnProximo'+envio.pergunta).prop("disabled",false);;
                    $('#resposta'+envio.pergunta).addClass('success');
                    $('#resposta'+envio.pergunta).removeClass('error');
                    feedback = 'Resposta adequada';
                    if(data.feedback_correta != null)
                    {
                        feedback = data.feedback_correta;
                    }
                    $('#feedback_correta'+envio.pergunta).html(feedback);
                }
                else
                {
                    $('#resposta'+envio.pergunta).addClass('error');
                    $('#resposta'+envio.pergunta).removeClass('success');
                    feedback = 'Resposta inadequada';
                    if(data.feedback != null)
                    {
                        feedback = data.feedback;
                    }
                    $('#feedback'+envio.pergunta).html(feedback);
                }

                if(!varTemTentativa || data.correta)
                {
                    perguntasConcluidas++;

                    fimDasTentativas(envio.tipo,envio.pergunta);
                    mudaBarraProgresso();
                    $('#btnVerificar'+envio.pergunta).hide();
                }
            },
            error: function(data) {
                alert('Não foi possível encontrar a pergunta.');
            }
        });
    }

    function fimDasTentativas(tipo,perguntaId)
    {
        if(tipo == 1)
        {
            $('.card-optionP'+perguntaId).addClass('validado');
        }

        if(tipo == 2)
        {
            $('#discursiva-respostaP'+perguntaId).addClass('validado');
        }

        if(tipo == 3)
        {
            $('#abertaP'+perguntaId).prop('disabled',true);
        }

        if(tipo == 4)
        {
            $('.questaoAbertaP'+perguntaId).addClass('validado');
        }
    }

    function temTentativa(perguntaId)
    {
        tentativa = parseInt($('#tentativasP'+perguntaId).val());
        tentativaNova = tentativa-1;
        $('#tentativasP'+perguntaId).val(tentativaNova);
        $('#tentativasSobrando'+perguntaId).html(tentativaNova);
        if(tentativa>1)
        {
            $('#msgTentativa'+perguntaId).show();
            $('#btnProximo'+perguntaId).prop("disabled",true);
            return true;
        }
        else
        {
            $('#msgTentativa'+perguntaId).hide();
            $('#btnProximo'+perguntaId).prop("disabled",false);
            return false;
        }
    }

    function verificarTipo1(perguntaId)
    {
        var tem = false;
        $('.card-optionP'+perguntaId).each(function(){
            if ($(this).hasClass('selected'))
            {
                tem = true;
                envio = new Object;
                envio.tipo = 1;
                envio.pergunta = perguntaId;
                envio.correta = $(this).attr('resposta');

                verificarCorreta(envio);
            }
        })
        if(!tem)
        {
            alert('Você precisa escolher uma imagem.');
        }
    }

    function verificarTipo2(perguntaId)
    {
        vet = new Array;
        $('#discursiva-respostaP'+perguntaId+' button').each(function(){

            vet.push($(this).attr('idResposta'));
        })
        if(vet.length > 0)
        {
            envio = new Object;
            envio.tipo = 2;
            envio.pergunta = perguntaId;
            envio.correta = vet;

            verificarCorreta(envio);
        }
        else
        {
            alert('Você precisa montar a frase.');
        }
    }

    function verificarTipo3(perguntaId)
    {
        if($('#abertaP'+perguntaId).val()!='')
        {
            envio = new Object;
            envio.tipo = 3;
            envio.pergunta = perguntaId;
            envio.correta = $('#abertaP'+perguntaId).val();

            verificarCorreta(envio);
        }
        else
        {
            alert('Você precisa escrever sua resposta.');
        }

    }

    function verificarTipo4(perguntaId)
    {
        var tem = false;
        $('.questaoAbertaP'+perguntaId).each(function(){
            if ($(this).hasClass('corretaAlternativa'))
            {
                tem = true;
                envio = new Object;
                envio.tipo = 4;
                envio.pergunta = perguntaId;
                envio.correta = $(this).attr('resposta');

                verificarCorreta(envio);
            }
        })

        if(!tem)
        {
            alert('Você precisa escolher uma alternativa.');
        }
    }


    function marcaAlternativa(perguntaId, elemento)
    {
        if(!$(elemento).hasClass('validado'))
        {
            $('.questaoAbertaP'+perguntaId).removeClass('corretaAlternativa');
            $(elemento).addClass('corretaAlternativa');
        }
    }

    function selecionaCard(perguntaId, elemento)
    {
        if(!$('.card-optionP'+perguntaId).hasClass('validado'))
        {
            $('.card-optionP'+perguntaId).removeClass('selected');
            $(elemento).addClass('selected');
        }

    }

    function anterior(i)
    {
        stopAudioGeral();
        $('#pergunta'+i).hide();
        $('#pergunta'+(i-1)).show();
    }

    function proximo(i)
    {
        stopAudioGeral();
        $('#pergunta'+i).hide();
        $('#pergunta'+(i+1)).removeClass('d-none');
        $('#pergunta'+(i+1)).show();
    }

    function stopAudioGeral(){
        var media = document.getElementsByTagName('audio');

        i = media.length;
        while (i--) {
            media[i].pause();
            media[i].currentTime = 0;
        }
    }

    $(document).ready(function(){
        if(perguntasConcluidas>0)
        {
            mudaBarraProgresso();
        }
    });


</script>
@endif
