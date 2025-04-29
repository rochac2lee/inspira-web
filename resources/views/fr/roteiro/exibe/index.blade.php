@extends('fr/master')
@section('content')
    <script type="text/javascript" src="{{url('fr/includes/froala_editor/js/plugins/froala_wiris/integration/WIRISplugins.js?viewer=image')}}"></script>

    <section class="section section-interna">
        <div class="container-fluid">
            <h3 class="pb-3 border-bottom mb-4">
                @isset($ead)
                    <a href="{{ url('ead/matriculado/'.$trilhaId.'/roteiro')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    Roteiro EAD - {{$roteiro->titulo}}
                @elseif(session('RoteiroBiblioteca'.$roteiro->id) == 1)
                    <a href="{{ url('/gestao/roteiros?biblioteca=1')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    Roteiro - {{$roteiro->titulo}}
                @elseif(isset($executar) && $executar)
                    <a href="{{ url('/trilhas/matriculado/'.$trilhaId.'/roteiro')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    Roteiro - {{$roteiro->titulo}}
                @else
                    <a href="{{ url('/gestao/roteiros/'.$roteiro->id.'/editar_conteudo')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    Roteiro - {{$roteiro->titulo}}
                @endisset

            </h3>
            <div class="row">
                <div class="col-md-12">
                    <img src="{{$roteiro->url_capa}}" class="w-100 rounded" />
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h5 class="mb-3 d-block">Docente</h5>
                        </div>
                        <div class="col-md-12">
                            <div class="p-3 rounded bg-light row">
                                <strong class="col-md-9 p-3">{{$roteiro->usuario->nome_completo}} </strong>
                                @if(isset($executar) && $executar)
                                    @isset($ead)
                                        <a href="{{url('ead/roteiros/realizarRoteiro/'.$roteiro->id.'/'.$trilhaId)}}" class="btn btn-success col-md-3">
                                    @else
                                        <a href="{{url('/roteiros/realizarRoteiro/'.$roteiro->id.'/'.$trilhaId)}}" class="btn btn-success col-md-3">
                                    @endisset
                                @else
                                    <a href="{{url('/gestao/roteiros/realizarRoteiro/'.$roteiro->id)}}" class="btn btn-success col-md-3">
                                @endif
                                    <i class="fas fa-play"></i>
                                    Iniciar
                                </a>
                            </div>
                        </div>
                    </div>
                    <h5 class="mb-3 d-block border-bottom pb-3">Descrição</h5>
                    {!! $roteiro->descricao !!}
                </div>
                <div class="col-md-6">

                    <h5 class="mb-3 d-block">Grade Curricular deste roteiro</h5>
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
                                                <a href="" class="d-block">
                                                    <i class="fas {{$c->tipo_icon}}"></i>
                                                    {{$c->titulo}}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
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
            <div class="row mt-5">
                <div class="col-md-12">
                </div>
            </div>
        </div>
    </section>



@stop
