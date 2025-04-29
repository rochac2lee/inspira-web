<!-- PEGUNTA --->
<div class="pergunta text-center">
    <span style="color: #757575; font-size: 24px">{{$i+1}}- {{$p->titulo}}</span>
    @if($p->audio_titulo!='')
        <div class="audioplay" style="font-size: 20px; cursor:pointer;" onclick="$('#audioP{{$p->id}}')[0].play()"><span class="escreva"><i class="fas fa-volume-up mr-2"></i></span>{!!$p->sub_titulo!!}
            <audio id="audioP{{$p->id}}" class="audio" src="{{config('app.cdn')}}/storage/quiz/{{$quiz->id}}/pergunta/{{$p->id}}/{{$p->audio_titulo}}" autostart="false" ></audio>
        </div>
    @else
        <div style="font-size: 20px">
            {!!$p->sub_titulo!!}
        </div>
    @endif
</div>

<!-- RESPOSTAS --->
<div class="container mt-4">
    <div class="row justify-content-md-center">
        @foreach($p->respostas as $r)
            <label class="col-md-auto grid-item" @if($r->audio) onclick="$('#audio{{$r->id}}')[0].play()" @endif>
                <div class="card-option card-optionP{{$p->id}} @if(isset($relatorio)) validado @else @if($p->log != null) validado @if($p->log->resposta == $r->id && $r->correta != 1) selected @endif @if($r->correta == 1) selected-correto @endif  @endif @endif " resposta="{{$r->id}}" style="cursor: pointer" onclick="selecionaCard({{$p->id}}, this)">
                    <div class="card-body">
                        <div class="img mb-1">
                            <img src="{{config('app.cdn')}}/storage/quiz/{{$quiz->id}}/pergunta/{{$p->id}}/respostas/{{$r->imagem}}" />
                        </div>
                    </div>
                    <div class="resposta card-footer">
                        {{$r->titulo}}
                    </div>
                    @if($r->audio)
                        <audio id="audio{{$r->id}}" src="{{config('app.cdn')}}/storage/quiz/{{$quiz->id}}/pergunta/{{$p->id}}/respostas/{{$r->audio}}" autostart="false" ></audio>
                    @endif
                    @if(isset($relatorio) && auth()->user()->permissao != 'A' && $p->tipo == $relatorio['tipo'])
                        <div class="resposta card-footer @if(isset($relatorio['resposta'][$r->id]) && $relatorio['resposta'][$r->id]['correta']==1)certo @else errado @endif">

                            <h3 class="mb-0">
                                @if(isset($relatorio['resposta'][$r->id]))
                                    <b>{{$relatorio['resposta'][$r->id]['marcado']}}</b>
                                    <small>({{number_format(($relatorio['resposta'][$r->id]['marcado']*100)/$relatorio['total'],0,',','.')}}%)</small>
                                @else
                                    <b>0</b> <small>(0%)</small>
                                @endif
                            </h3>
                            estudante(s) escolheram essa alternativa.
                            <br>
                            <small>
                                <br>
                                @if(isset($relatorio['resposta'][$r->id]) && $relatorio['resposta'][$r->id]['marcado']>0)
                                    <i class="far fa-clock"></i> {{gmdate('H:i:s', (int)($relatorio['resposta'][$r->id]['tempo'] / $relatorio['resposta'][$r->id]['marcado']))}}
                                @endif
                            </small>
                        </div>
                    @endif
                </div>
            </label>
        @endforeach
    </div>
</div>
