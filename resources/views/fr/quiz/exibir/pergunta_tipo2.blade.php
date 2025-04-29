<!-- PEGUNTA --->
<div class="pergunta text-center">
    <span style="color: #757575; font-size: 24px">{{$i+1}}- {{$p->titulo}}</span>
</div>

<!-- RESPOSTAS --->
<div class="container mt-4">
    <div class="row justify-content-md-center">
        <label class="col-md-auto grid-item">
            <div class="card-option">
                <div class="card-body">
                    <div class="img mb-1">
                        <img src="{{config('app.cdn')}}/storage/quiz/{{$quiz->id}}/pergunta/{{$p->id}}/{{$p->imagem}}" />
                    </div>
                </div>
            </div>
        </label>
        <div class="col-md-6 grid-item">
            @if($p->audio_titulo!='')
                <div class=" audioplay" onclick="$('#audioP{{$p->id}}')[0].play()" style="font-size: 20px; cursor: pointer; font-weight: 500"><span class="escreva"><i class="fas fa-volume-up mr-2" ></i></span>{!!$p->sub_titulo!!}
                    <audio id="audioP{{$p->id}}" class="audio" src="{{config('app.cdn')}}/storage/quiz/{{$quiz->id}}/pergunta/{{$p->id}}/{{$p->audio_titulo}}" autostart="false" ></audio>
                </div>
            @else
                <div style="font-size: 20px; font-weight: 500">
                    {!!$p->sub_titulo!!}
                </div>
            @endif

            <div class="discursiva mt-3">
                <div id="discursiva-respostaP{{$p->id}}" class="discursiva-resposta">

                </div>
                <div class="linhas"></div>
                <div class="linhas"></div>
            </div>

            <div id="autocompletarP{{$p->id}}" class="autocompletar mt-3">
                @foreach($p->respostas as $r)
                    <button id="btnTipo2{{$r->id}}" type="button" idResposta="{{$r->id}}" class="btn btn-outline-secondary">{{$r->titulo}}</button>
                @endforeach
            </div>
        </div>
        @if($p->log != null && auth()->user()->permissao == 'A' && $p->log->eh_correta != 1)
            <div class="col-md-12 text-center mt-4 text-success">
                <p style="font-size: 18px">Respostas adequada:</p>
                <p style="font-size: 14px; margin-bottom: 4px" ><b>
                    @foreach($p->respostas as $pr)
                        {{$pr->titulo}}
                    @endforeach
                </b></p>
            </div>
        @endif
    </div>
    @if(isset($relatorio) && auth()->user()->permissao != 'A' && $p->tipo == $relatorio['tipo'])
        <div class="row justify-content-md-center">
            <br>
        </div>
        @foreach($relatorio['resposta'] as $d)
            <div class="row m-2 justify-content-md-center @if($d['correta']==1) certo @else errado @endif">
                <div class="col-md-6 grid-item text-right align-middle" style="width: 100%">
                    <br>
                    <b> {{$d['titulo']}} </b>
                </div>
                <div class="col-md-6 grid-item ">
                    <h3 class="mb-0">
                        @if($d['marcado']>0)
                            <b>{{$d['marcado']}}</b>
                            <small>({{number_format(($d['marcado']*100)/$relatorio['total'],0,',','.')}}%)</small>
                        @else
                            <b>0</b> <small>(0%)</small>
                        @endif
                    </h3>
                    estudante(s) escolheram essa alternativa.
                    <br>
                    <small>
                        <br>
                        @if($d['marcado']>0)
                            <i class="far fa-clock"></i> {{gmdate('H:i:s', (int)($d['tempo'] / $d['marcado']))}} <br>
                        @endif
                    </small>
                </div>

            </div>
        @endforeach
    @endif
</div>

<script>
    $(document).ready(function(){
        @if($p->log != null)
            @php
                $vet = json_decode($p->log->resposta);
            @endphp
            @foreach( $vet as $v )
                $('#btnTipo2{{$v}}').appendTo( "#discursiva-respostaP{{$p->id}}" );
            @endforeach

        @else
            $(document).delegate('#autocompletarP{{$p->id}} button','click',function(){
                if(!$('#discursiva-respostaP{{$p->id}}').hasClass('validado'))
                {
                    $(this).appendTo( "#discursiva-respostaP{{$p->id}}" );
                }
            });

            $(document).delegate('#discursiva-respostaP{{$p->id}} button','click',function(){
                if(!$('#discursiva-respostaP{{$p->id}}').hasClass('validado'))
                {
                    $(this).appendTo( "#autocompletarP{{$p->id}}" );
                }
            });
        @endif
    })
</script>
