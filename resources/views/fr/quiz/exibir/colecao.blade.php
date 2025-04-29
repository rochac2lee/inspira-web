@extends('fr/master')
@section('content')
    <section class="section section-interna">
        <div class="container">
            <h2 class="title-page">
                <a href="{{url('/catalogo')}}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                Biblioteca de quizzes
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
            @if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')
                <div class="row justify-content-center p-3 mb-4">
                    <div class="col-md-3">
                        <a href="{{url('gestao/quiz?n=1')}}" class="btn btn-success w-100" >
                            <i class="fas fa-plus"></i>
                            Novo Quiz
                        </a>
                    </div>
                </div>
            @endif
            <div class="row section-grid colecoes">
            @if(count($dados)>0)
                @foreach($dados as $d)
                    <div class="col-md-6 grid-item">
                        @if(auth()->user()->permissao == 'A' || auth()->user()->permissao == 'R')
                            <a href="{{url('/quiz/listar?componente='.$d->id)}}">
                        @else
                            <a href="{{url('/gestao/quiz?componente='.$d->id)}}">
                        @endif
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="img">
                                        <img class="img-fluid" src="{{ config('app.cdn').'/storage/colecaoquiz/'.$d->id}}.webp" />
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <strong class="title h6 font-weight-bold d-block">{{$d->titulo}}</strong>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @elseif(auth()->user()->permissao == 'A' || auth()->user()->permissao == 'R')
                <p class="text-center" style="width: 100%">Para que o Quiz esteja disponível é preciso que seu professor ou professora o tenha publicado!
                    <br>Sugerimos que <b>entre em contato com seu professor ou professora</b> em sua escola.</p>
            @endif
            </div>

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
