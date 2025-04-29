@extends(Auth::user() ? 'fr/master' : 'fr/masterFora')

@section('content')
    <section class="section section-interna" style="padding-top: 50px;">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-12 col-sm-12 grid-item ">
                    <img class="img-fluid" src="{{config('app.app')}}/fr/imagens/erro/404.webp" style="max-width:800px;" />
                    <br>
                    <br>
                    <div class="alert alert-info" role="alert">
                        <h6>Ops! <b>Não encontramos a página que você tentou acessar!</b><br> Recomendamos que clique em uma das opções abaixo e continue a se INspirar.</h6>
                    </div>
                    <div class="btn-group" role="group" aria-label="Podemos ajudar?">
                        @if(Auth::user())
                            <button onclick="toggleSidebar()" type="button" class="btn btn-warning"><i class="fas fa-bars"></i> Abrir o menu</button>
                        @endif
                        <a href="{{ url('/') }}" class="btn btn-warning"><i class="fas fa-home"></i> Ir ao início</a>
                        <a href="#" onclick="{{url()->previous()}}" class="btn btn-warning">Voltar à página anterior <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </section>
@stop
