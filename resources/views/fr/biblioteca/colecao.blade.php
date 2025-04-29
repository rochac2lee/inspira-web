@extends('fr/master')
@section('content')
    <section class="section section-interna">
        <div class="container">
            <h2 class="title-page">
                <a href="{{url('/catalogo')}}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                @if($titulo != 'Tabelas Trimestrais')
                    Biblioteca de {{$titulo}}
                @else
                    Conteúdo Programático - Estrutura Trimestral
                @endif
            </h2>
            <div class="list-grid-menu">
                <form class="form-inline">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary p-2 no-list-grid-btn active">
                            <input type="radio" name="options" id="option1" autocomplete="off" checked>
                            <i class="fas fa-th-large"></i>
                        </label>
                        <label class="btn btn-secondary list-grid-btn">
                            <input type="radio" name="options" id="option2" autocomplete="off">
                            <i class="fas fa-list"></i>
                        </label>
                    </div>
                </form>
            </div>

            <div class="row section-grid colecoes">

                @foreach($dados as $d)
                @if($d->tipo_conteudo == 100)
                    @if(($d->nome!='Bebês' && $d->nome!='Crianças' && $d->nome!='Etnias') || (auth()->user()->permissao=='Z'))
                    <div class="col-md-6 grid-item">

                        @if($d->tipo==3)
                            <a href="{{url('/editora/conteudos?conteudo='.$d->tipo.'&componente='.$d->id)}}">
                        @else
                            @if($d->tipo_conteudo == 104 || $d->tipo_conteudo == 105)
                                <a href="{{url('/editora/conteudos?conteudo='.$d->tipo_conteudo.'&colecao='.$d->id)}}">
                            @else
                                <a href="{{url('/editora/conteudos?conteudo='.$d->tipo.'&colecao='.$d->id)}}">
                            @endif
                        @endif
                            <div class="card text-center">
                                
                                <div class="card-body">
                                    <div class="img">
                                        <img class="img-fluid" src="{{ config('app.cdn').$capa.$d->img}}" />
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <strong class="title h6 font-weight-bold d-block">{{$d->nome}}</strong>
                                    <!--<span class="text">{{$d->selo}}</span>-->
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                @else
                <div class="col-md-6 grid-item">

                    @if($d->tipo==3)
                        <a href="{{url('/editora/conteudos?conteudo='.$d->tipo.'&componente='.$d->id)}}">
                    @else
                        @if($d->tipo_conteudo == 104 || $d->tipo_conteudo == 105)
                            <a href="{{url('/editora/conteudos?conteudo='.$d->tipo_conteudo.'&colecao='.$d->id)}}">
                        @else
                            <a href="{{url('/editora/conteudos?conteudo='.$d->tipo.'&colecao='.$d->id)}}">
                        @endif
                    @endif
                        <div class="card text-center">
                            
                            <div class="card-body">
                                <div class="img">
                                    <img class="img-fluid" src="{{ config('app.cdn').$capa.$d->img}}" />
                                </div>
                            </div>
                            <div class="card-footer">
                                <strong class="title h6 font-weight-bold d-block">{{$d->nome}}</strong>
                                <!--<span class="text">{{$d->selo}}</span>-->
                            </div>
                            
                        </div>
                    </a>
                </div>
                @endif
                @endforeach

            </div>

            <nav class="mt-5" aria-label="Page navigation example">
                {{ $dados->appends(Request::all())->links() }}
            </nav>
        </div>
    </section>
    <script type="text/javascript">
    $('.list-grid-btn').click(function() {
        $('.grid-item').addClass('list-grid');
    });

    $('.no-list-grid-btn').click(function() {
        $('.grid-item').removeClass('list-grid');
    });
</script>
@stop
