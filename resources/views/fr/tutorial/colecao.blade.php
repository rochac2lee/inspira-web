@extends(Auth::user() ? 'fr/master' : 'fr/masterFora')
@section('content')
    <section class="section section-interna">
        <div class="container">
            <h2 class="title-page">
                <a href="{{url('')}}" title="Voltar" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> </a>
                Biblioteca de tutoriais
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
            <div class="subtitle-page" style="margin-top: 10px">Clique na imagem para acessar os tutoriais</div>
            <div class="row section-grid colecoes">
                @foreach($dados as $d)
                    @if($d->nome!="INspira Agenda" || auth()->user()->permissao  == 'Z')
                    <div class="col-md-6 grid-item">
                        <a href="{{url('/tutorial/'.$d->id)}}">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="img">
                                        <img class="img-fluid" src="{{ config('app.cdn').'/storage/colecaotutorial/'.$d->img}}" />
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <strong class="title h6 font-weight-bold d-block">{{$d->nome}}</strong>
                                    <span class="text">{{$d->selo}}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                @endforeach

            </div>

            <nav class="mt-5" aria-label="Page navigation example">
                {{ $dados->links() }}
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
