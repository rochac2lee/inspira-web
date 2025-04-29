@extends('fr/master')
@section('content')
    <script type="text/javascript" src="{{url('fr/includes/froala_editor/js/plugins/froala_wiris/integration/WIRISplugins.js?viewer=image')}}"></script>

    <section class="section section-interna">
        <div class="container-fluid">
            <h3 class="pb-3 border-bottom mb-4">
                @isset($ead)
                    <a href="{{ url('ead/roteiros/iniciarRoteiro/'.$roteiro->id.'/'.$trilhaId)}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    Roteiro EAD - {{$roteiro->titulo}}
                @elseif(isset($executar))
                    <a href="{{ url('/roteiros/iniciarRoteiro/'.$roteiro->id.'/'.$trilhaId)}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    Roteiro - {{$roteiro->titulo}}
                @else
                    <a href="{{ url('/gestao/roteiros/iniciarRoteiro/'.$roteiro->id)}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    Roteiro - {{$roteiro->titulo}}
                @endisset

            </h3>
            <script>
                var itens = [];
                var quantidadeItens = 0;
            </script>
            @php $qtdItens=0; @endphp
            <div class="row">
                <div class="col-md-3 p-0 bg-light">
                    <div class="accordion visualizar-list" id="accordionTema">
                        @foreach($roteiro->temas as $t)
                            <div class="card mb-0">
                                <div class="card-header" id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link text-dark d-block w-100 text-left" type="button" data-toggle="collapse" data-target="#collapse{{$t->id}}" aria-expanded="true" aria-controls="collapse{{$t->id}}">
                                            <i class="fas fa-plus"></i>
                                            {{$t->titulo}}
                                            <!--<small class="time float-right">00:00</small>-->
                                        </button>
                                    </h2>
                                </div>
                                @foreach($t->conteudo as $c)
                                    <div id="collapse{{$t->id}}" class="collapse @if ($loop->parent->first) show @endif" aria-labelledby="headingOne" data-parent="#accordionTema">
                                        <div class="card-body">
                                            <ul class="d-block">
                                                <li class="border-bottom">
                                                    <a href="javascript:void(0)" onclick="getConteudo({{$roteiro->id}}, {{$t->id}}, {{$c->id}}, {{$qtdItens+1}}); atual = {{$qtdItens+1}}; mudaBtnProximo({{$qtdItens+1}})" class="d-block">
                                                        <i class="fas {{$c->tipo_icon}}"></i>
                                                        {{$c->titulo}}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <script>
                                        itens.push([ {{$roteiro->id}}, {{$t->id}}, {{$c->id}}]);
                                        quantidadeItens++;
                                        @php $qtdItens++; @endphp
                                    </script>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-9 p-0">
                    <div class="row pr-2 pl-2">
                        <div class="col-2">
                            <a href="javascript:void(0)" class="btn btn-default" style="margin-top: 0px; float: none" onclick="anterior()"> Anterior </a>
                        </div>
                        <div class="col-8">
                            <h4 class="text-center" id="conteudoTitulo">

                            </h4>
                        </div>
                        <div class="col-2 text-right">
                            <a id="btnProximo" href="javascript:void(0)" class="btn btn-default" style="margin-top: 0px; float: none" onclick="proximo()"> Próximo </a>
                        </div>
                    </div>

                    <div class="p-4" id="conteudoRoteiro">

                    </div>
                    <div class="p-3 bg-light row justify-content-end">
                        <div class="col-md-2">
                            @php /*
                            <a href="#" class="mr-3">
                                <i class="far fa-thumbs-down text-danger"></i>
                                0
                            </a>
                            <a href="#">
                                <i class="far fa-thumbs-up text-success"></i>
                                0
                            </a>
                            */
                            @endphp
                        </div>
                    </div>
                    <div class="p-5 mt-3">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#instrutor" role="tab" aria-controls="home" aria-selected="true">Docente</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#fontes" role="tab" aria-controls="contact" aria-selected="false">Fontes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#autores" role="tab" aria-controls="contact" aria-selected="false">Autores</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-5" id="myTabContent">
                            <div class="tab-pane fade show active" id="instrutor" role="tabpanel" aria-labelledby="home-tab">
                                <h4 class="border-bottom pb-3 mb-4">{{$roteiro->usuario->nome_completo}}</h4>
                               <!-- <p><b>Roteiro:</b> {!! $roteiro->descricao !!}</p> -->
                                @foreach($roteiro->temas as $t)
                                    <p class="descTema descTema{{$t->id}}" style="display: none; text-align: justify; text-justify: inter-word;">{!! $t->descricao !!}</p>
                                @endforeach

                            </div>
                            <div class="tab-pane fade" id="fontes" role="tabpanel" aria-labelledby="profile-tab">
                                <p id="fonteConteudo"></p>
                            </div>
                            <div class="tab-pane fade" id="autores" role="tabpanel" aria-labelledby="contact-tab">
                                <p id="autorConteudo">{{$roteiro->autores}}</p>
                            </div>
                        </div>
                        @php /*
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <h4 class="border-bottom pb-3 mb-4">Avaliações</h4>
                            </div>
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-body h-auto">
                                        <strong class="mb-3 d-block">Lorem Ipsum Dolor</strong>
                                        <p>
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem velit similique repellendus officia aliquid, hic quasi minus reiciendis iusto ipsum voluptate nihil fugiat quod corrupti, nesciunt enim eveniet ipsa voluptatum!
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stars">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <span>(3,0)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body h-auto">
                                        <strong class="mb-3 d-block">Lorem Ipsum Dolor</strong>
                                        <p>
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem velit similique repellendus officia aliquid, hic quasi minus reiciendis iusto ipsum voluptate nihil fugiat quod corrupti, nesciunt enim eveniet ipsa voluptatum!
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stars">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <span>(3,0)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body h-auto">
                                        <strong class="mb-3 d-block">Lorem Ipsum Dolor</strong>
                                        <p>
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem velit similique repellendus officia aliquid, hic quasi minus reiciendis iusto ipsum voluptate nihil fugiat quod corrupti, nesciunt enim eveniet ipsa voluptatum!
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stars">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <span>(3,0)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body h-auto">
                                        <strong class="mb-3 d-block">Lorem Ipsum Dolor</strong>
                                        <p>
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem velit similique repellendus officia aliquid, hic quasi minus reiciendis iusto ipsum voluptate nihil fugiat quod corrupti, nesciunt enim eveniet ipsa voluptatum!
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stars">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <span>(3,0)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-5">
                                <button class="btn btn-success w-100" data-toggle="modal" data-target="#novo-topico">
                                    <i class="fas fa-plus"></i>
                                    Novo Tópico
                                </button>
                            </div>
                        </div>
                        */ @endphp
                    </div>
                </div>
            </div>
        </div>
    </section>
    <link rel="stylesheet" type="text/css" href="{{config('app.cdn')}}/fr/includes/audioplayer/audioplayer.css">
    <script src="{{config('app.cdn')}}/fr/includes/audioplayer/audioplayer.js" type="text/javascript"></script>
    <script>
        var audio = {
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
        var atual = 1;
        function getConteudo(cursoId, temaId, conteudoId, posicaoVetor){
            $('.descTema').hide();
            $('.descTema'+temaId).show();

            atual = posicaoVetor;
            $.ajax({
                @if( isset($executar) && $executar)
                    @isset($ead)
                        url: '{{url('ead/roteiros/getConteudoAjax/')}}',
                    @else
                        url: '{{url('/roteiros/getConteudoAjax/')}}',
                    @endif
                @else
                    url: '{{url('/gestao/roteiros/getConteudoAjax/')}}',
                @endif
                type: 'post',
                dataType: 'json',
                data: {
                    curso_id:    cursoId,
                    aula_id:     temaId,
                    conteudo_id: conteudoId,
                    @if( isset($executar) && $executar)
                        trilha_id: {{$trilhaId}},
                    @endif
                    _token:      '{{csrf_token()}}'
                },
                success: function(data) {

                    $('#conteudoTitulo').html(data.titulo)
                    $('#conteudoRoteiro').html(data.iframe)
                    $('#fonteConteudo').html(data.fonte)
                    $('#autorConteudo').html(data.autores)

                    if(data.tipo == 2){
                        $("#player1").audioplayer(audio);
                    }
                    @if( (isset($executar) && $executar ) )
                        if(data.tipo == 10){
                            $("#entregavel").show()
                            $("#trilhaIdEntregavel").val({{$trilhaId}})
                            $("#cursoIdEntregavel").val(cursoId)
                            $("#aulaIdEntregavel").val(temaId)
                            $("#conteudoIdEntregavel").val(data.id)
                            $("#tokenEntregavel").val('{{csrf_token()}}')
                            submitFormEntregavel();
                            getListaEntregavel(cursoId,temaId,data.id, {{$trilhaId}} )
                        }
                        if(data.tipo == 7) {
                            $("#trilhaIdEntregavel").val({{$trilhaId}})
                            $("#cursoIdEntregavel").val(cursoId)
                            $("#aulaIdEntregavel").val(temaId)
                            $("#conteudoIdEntregavel").val(data.id)
                            $("#tokenEntregavel").val('{{csrf_token()}}')
                            getDiscursiva(cursoId,temaId,data.id, {{$trilhaId}} )
                            submitformDiscursiva()
                        }
                    @else
                        if(data.tipo == 7) {
                            $('#btnDiscursiva').hide();
                        }
                    @endif
                },
                error: function() {
                    swal("Erro", "Não foi possível localizar o conteúdo.", "error");
                }
            });
        }

        function getListaEntregavel(cursoId,temaId,conteudoId, trilhaId ){
            $.ajax({
                @isset($ead)
                    url: "{{url('ead/roteiros/listaEntregavel')}}",
                @else
                    url: "{{url('/roteiros/listaEntregavel')}}",
                @endif

                type: "GET",
                dataType: 'json',
                data: {
                    curso_id:    cursoId,
                    aula_id:     temaId,
                    conteudo_id: conteudoId,
                    trilha_id: trilhaId,
                },
                success: function (data) {
                    $('#listaEntregavel').html(data);
                },
                error: function (e) {
                    swal("Erro", "Não foi recuperar entregáveis.", "error");
                }
            });
        }



        function getDiscursiva(cursoId,temaId,conteudoId, trilhaId ){
            $.ajax({
                @isset($ead)
                url: "{{url('ead/roteiros/getDiscursiva')}}",
                @else
                url: "{{url('/roteiros/getDiscursiva')}}",
                @endif

                type: "GET",
                dataType: 'json',
                data: {
                    curso_id:    cursoId,
                    aula_id:     temaId,
                    conteudo_id: conteudoId,
                    trilha_id: trilhaId,
                },
                success: function (data) {
                    $('#respostaDiscursiva').html(data);
                    if(data != ''){
                        $('#respostaDiscursiva').prop('disabled',true);
                        $('#discursivaEstaSalva').show();
                        $('#btnDiscursiva').hide();
                    }
                },
                error: function (e) {
                    swal("Erro", "Não foi recuperar discursiva.", "error");
                }
            });
        }

        function submitFormEntregavel() {
            $("#formEntregavel").on('submit', (function (e) {
                e.preventDefault();
                $.ajax({
                    @isset($ead)
                        url: "{{url('ead/roteiros/salvaEntregavel')}}",
                    @else
                        url: "{{url('/roteiros/salvaEntregavel')}}",
                    @endif
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $('#listaEntregavel').html(data);
                    },
                    error: function (e) {
                        swal("Erro", "Não foi possível enviar seu arquivo.", "error");
                    }
                });
            }));
        }

        function submitformDiscursiva() {
            $("#formDiscursiva").on('submit', (function (e) {
                e.preventDefault();
                if(!confirm('Você tem certeza que deseja salvar essa resposta? Após salva a reposta não poderá ser alterada.'))
                {
                    return false;
                }
                $.ajax({
                    @isset($ead)
                    url: "{{url('ead/roteiros/salvaDiscursiva')}}",
                    @else
                    url: "{{url('/roteiros/salvaDiscursiva')}}",
                    @endif
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data){
                        $('#respostaDiscursiva').prop('disabled',true);
                        $('#discursivaEstaSalva').show();
                        $('#btnDiscursiva').hide();
                    },
                    error: function (e) {
                        swal("Erro", "Não foi possível enviar seu arquivo.", "error");
                    }
                });
            }));
        }

        function mudaBtnProximo(qtd){
            if(qtd == quantidadeItens){
                $('#btnProximo').html('Finalizar');
            }else{
                $('#btnProximo').html('Próximo');
            }
        }
        function proximo(){
            if(atual < quantidadeItens){
                atual++;
                getConteudo(itens[atual-1][0], itens[atual-1][1], itens[atual-1][2], atual)
                mudaBtnProximo(atual)
            }
            else{
                @isset($ead)
                    window.location.href="{{url('ead/matriculado/'.$trilhaId.'/roteiro')}}";
                @elseif(isset($executar))
                    window.location.href="{{url('trilhas/matriculado/'.$trilhaId.'/roteiro')}}";
                @endif
            }
        }

        function anterior(){
            if(atual > 0) {
                atual--;
                getConteudo(itens[atual-1][0], itens[atual-1][1], itens[atual-1][2], atual)
            }
        }

        $(document).ready(function(){
            getConteudo(itens[0][0], itens[0][1], itens[0][2], 1)
        });

    </script>

@stop
