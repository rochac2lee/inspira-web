<!-- PEGUNTA --->
<div class="pergunta text-center">
    <span style="color: #757575; font-size: 24px">{{$i+1}}- {{$p->titulo}}</span>
</div>
@php
    $alternativas = [
        '0' => 'A',
        '1' => 'B',
        '2' => 'C',
        '3' => 'D',
        '4' => 'E',
        '5' => 'F',
        '6' => 'G',
    ];
@endphp
<!-- RESPOSTAS --->
<div class="container mt-4">
    <div class="row justify-content-md-center">
        <div class="col-md-6 grid-item">
            <div class="questao text-justify text-break text-muted">
                <div class="row">
                    <div class="col-12 mb-1">
                        {!!$p->sub_titulo!!}
                    </div>
                @php $j=0; @endphp
                @foreach($p->respostas as $r)
                    <div class="col-12 resposta mb-3">
                        <div class="row">
                            <div class="col-1">
                                <div class="questaoAbertaP{{$p->id}} letraQuestaoAberta  @if(isset($relatorio)) validado @if(isset($relatorio['resposta'][$r->id]['correta']) && $relatorio['resposta'][$r->id]['correta']==1) corretaAlternativa @endif @else @if($p->log != null) validado @if($p->log->resposta == $r->id && $r->correta != 1) marcadaAlternativa @endif @if($r->correta == 1) corretaAlternativa @endif  @endif @endif" resposta="{{$r->id}}" style="cursor: pointer;" onclick="marcaAlternativa({{$p->id}}, this)">{{$alternativas[$j]}}</div>
                            </div>
                            <div class="col-11">
                                {!!$r->titulo!!}
                            </div>
                            @if(isset($relatorio) && auth()->user()->permissao != 'A' && $p->tipo == $relatorio['tipo'])
                                <div class="col-12 @if(isset($relatorio['resposta'][$r->id]) && $relatorio['resposta'][$r->id]['correta']==1)certo @else errado @endif">
                                    <h3 class="mb-0">
                                        @if(isset($relatorio['resposta'][$r->id]))
                                            <b>{{$relatorio['resposta'][$r->id]['marcado']}}</b>
                                            <small>({{number_format(($relatorio['resposta'][$r->id]['marcado']*100)/$relatorio['total'],0,',','.')}}%)</small>
                                        @else
                                            <b>0</b> <small>(0%)</small>
                                        @endif
                                    </h3>
                                    estudante(s) escolheram a alternativa <b>{{$alternativas[$j]}}</b>.
                                    <br>
                                    <small>
                                        <br>
                                        @if(isset($relatorio['resposta'][$r->id]) && $relatorio['resposta'][$r->id]['marcado']>0)
                                            <i class="far fa-clock"></i> {{gmdate('H:i:s', (int)($relatorio['resposta'][$r->id]['tempo'] / $relatorio['resposta'][$r->id]['marcado']))}}
                                        @endif
                                    </small>
                                </div>
                                <hr>
                            @endif
                        </div>
                    </div>
                    @php $j++; @endphp
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

    <script type="text/javascript">


    </script>
