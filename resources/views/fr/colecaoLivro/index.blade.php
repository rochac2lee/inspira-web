@extends('fr/master')
@section('content')
    <section class="section section-interna">
        <div class="container">
            <h2 class="title-page">
                <a href="{{url('/')}}" class="btn btn-secondary" style="float: left; margin-right: 10px">
                    <i class="fas fa-arrow-left"></i>
                </a>
                Coleções de Livros Didáticos Digitais</h2>
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
                    <div class="col-md-6 grid-item">
                        <a href="{{url('/colecao_livro/'.$d->id.'/livros')}}">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="img">
                                        <img class="img-fluid" src="{{ config('app.cdn').'/storage/colecaolivro/'.$d->img}}" />
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <strong class="title h6 font-weight-bold d-block">{{$d->nome}}</strong>
                                    <span class="text">{{$d->selo}}</span>
                                    <br>
                                    <!--Alteração momentânea para Rancharia, pois será chaveado duas coleções para o município-->
                                    @php
                                        $instituicao = session('instituicao');
                                    @endphp
                                    @if($instituicao['id'] == 485) <!-- SME DE RANCHARIA -->                                          
                                        @if($d->id == 217 && 251)  <!-- Coleções Ser & Viver 2023 e 2024 -->                                 
                                            <span class="text">{{ optional($d->created_at)->format('Y') }}</span>
                                        @endif
                                    @endif
                                    <!--Alteração momentânea para Rancharia, pois será chaveado duas coleções para o município-->
                                </div>
                            </div>
                        </a>
                    </div>
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
