
<!-- <h1 class="mb-2">{{$avaliacao->titulo}} @if($avaliacao->aplicacao == 'o')<span class="fClara text-right">{{$avaliacao->data_hora_final->format('d/m/Y')}}</span> @endif</h1> -->
<div class="cabecalho row align-items-center">
    <div class="col-auto">
        <!-- <img src="imagens/imagens_avaliacoes/logo_exemplo_escola.png" style="max-width: 110px; max-height: 96px;"> -->
    </div>
    @php
        $escola = session('escola');
    @endphp
    <div class="col">
        <div class="text-center">
            <!--<h6>{{$escola['titulo']}}</h6>-->
            <h6>{{$avaliacao->titulo}}</h6>
            <p class="text-muted"><i></i></p>
        </div>
        <div class="row">
            <div class="col-8 p-1">
                <div class="campo">
                    <label>Nome:</label><div class="linha"></div>
                </div>
            </div>
            <div class="col-4 p-1">
                <div class="campo">
                    <label>Data:</label><div class="linha"></div>
                </div>
            </div>
            <div class="col-6 p-1 pt-2">
                <div class="campo">
                    <label>Turma:</label><div class="linha"></div>
                </div>
            </div>
            <div class="col-6 p-1 pt-2">
                <div class="campo">
                    <label>Professor:</label><div class="linha"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-auto">
        <img src="{{config('app.cdn')}}/fr/imagens/logo_opet_avaliacao2.png" style="max-width: 110px; max-height: 110px;">
    </div>
</div>
<div class="conteudo">
    <div class="colunas_questoes">
        @foreach($perguntas as $p)
            <div class="panel" style="break-inside: avoid-page;">
                <div class="panel-body">
                    <h5>
                        <span>Questão {{$loop->index+1}}</span>
                        <span style="float: right;"></span>
                    </h5>
                    <div class="questao text-justify text-break">
                        <p style="page-break-inside:avoid">
                            @if($p->fonte!='')
                            <p>({{$p->fonte}})</p>
                            @endif
                            {!! $p->pergunta !!}
                        </p>
                        @if($p->tipo == 'o')
                            @include('fr.avaliacao.relatorio.impressaoQuestaoObjetiva')
                        @else
                            @include('fr.avaliacao.relatorio.impressaoQuestaoDiscursiva')
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
