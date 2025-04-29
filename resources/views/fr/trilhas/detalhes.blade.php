@extends('fr/master')
@section('content')
    <script type="text/javascript" src="{{url('fr/includes/froala_editor/js/plugins/froala_wiris/integration/WIRISplugins.js?viewer=image')}}"></script>

    <section class="section section-interna">
        <div class="container-fluid">
            <h3 class="pb-3 border-bottom mb-4">
                @isset($ead)
                    <a href="{{ url('/ead/listar')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    Trilha EAD - {{$dados->titulo}}
                @else
                    <a href="{{ url('/trilhas/listar')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    Trilha - {{$dados->titulo}}
                @endif

            </h3>
<!--
            <div class="row">
                <div class="col-md-12 text-center">
                    <img src="{{$dados->url_capa}}" class=" rounded"  width="80%"/>
                </div>
            </div>
-->
            <div class="row mt-5">
                <div class="col-md-4">
                    <img src="{{$dados->url_capa}}" class=" w-100 rounded p-2"/>
                </div>
                <div class="col-md-8">
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h5 class="mb-3 d-block">Autor</h5>
                        </div>
                        <div class="col-md-12">
                            <div class="p-3 rounded bg-light row">
                                <strong class="col-md-9 p-3">{{ ($dados->autor != '') ? $dados->autor: $dados->user->name }}</strong>
                                @isset($ead)
                                    @if( \Carbon\Carbon::now()->between($dados->ead_matricula_inicio,$dados->ead_matricula_fim.' 23:59:59'))
                                        @if($qtdMatriculasEADSemestre < 2)
                                        <a href="javascript:void(0)" onclick="modalMatricular({{ $dados->id }}, '{{ $dados->titulo }}')" class="btn btn-success col-md-3">
                                            <i class="fas fa-play"></i>
                                            Matricular-se<br> <small> o período de matrícula se encerra {{\Carbon\Carbon::parse($dados->ead_matricula_fim.' 23:59:59')->diffForHumans()}} </small>
                                        </a>
                                        @else
                                            <button class="btn btn-success col-md-3" disabled>Você já atingiu a quantidade de Trilhas matrículadas nesse semestre</button>
                                        @endif
                                    @else
                                        <button class="btn btn-success col-md-3" disabled>EAD fora do período de matrícula</button>
                                    @endif
                                @else
                                    <a href="javascript:void(0)" onclick="modalMatricular({{ $dados->id }}, '{{ $dados->titulo }}')" class="btn btn-success col-md-3">
                                        <i class="fas fa-play"></i>
                                        Matricular-se</small>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <h5 class="mb-3 d-block border-bottom pb-3">Descrição</h5>
                    {!! $dados->descricao !!}
                    <h5 class="mb-3 d-block">Roteiros desta trilha</h5>
                    <div class="accordion visualizar-list" id="accordionTema">

                        @foreach($dados->cursos as $c)
                            <div class="card mb-0">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link text-dark d-block w-100 text-left" type="button" data-toggle="collapse" data-target="#collapse{{$c->id}}" aria-expanded="true" aria-controls="collapse{{$c->id}}">
                                        <img src="{{$c->url_capa}}" width="200px" />
                                        <!--<i class="fas fa-plus"></i> -->
                                        {{$c->titulo}}
                                        <small class=""><br>{!!$c->titulo!!}</small>
                                    </button>
                                </h2>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                </div>
            </div>
        </div>
    </section>
    <!-- MATRICULAR -->
    <div class="modal fade" id="formMatricular" tabindex="-1" role="dialog" aria-labelledby="formMatricular" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Realizar Matrícula</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                Você deseja mesmo matricular-se nessa trilha?<br><br>
                                <b id="titulo"></b>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <button type="button" onclick="matricular()" class="btn btn-success">Sim, matricular</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM MATRICULAR -->


    <script type="text/javascript">

        var idMatricular;

        function modalMatricular(id, nome)
        {
            idMatricular = id;
            $('#titulo').html(nome);
            $('#formMatricular').modal('show');
        }

        function matricular()
        {
            @isset($ead)
                window.location.href = '{{url("/ead/matricular/")}}/'+idMatricular;
            @else
                window.location.href = '{{url("/trilhas/matricular/")}}/'+idMatricular;
            @endif
        }

    </script>

@stop
