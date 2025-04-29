@extends(Auth::user() ? 'fr/master' : 'fr/masterFora')

@section('content')
    <section class="section section-interna" style="padding-top: 50px;">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-12 col-sm-12 grid-item ">
                    <img class="img-fluid" src="{{config('app.app')}}/fr/imagens/erro/419.webp" style="max-width:800px;" />
                    <br>
                    <br>
                    <div class="alert alert-info" role="alert">
                        <h6>Ops! <b>Parece que alguém demorou a acessar!</b> <br>Recomendamos que clique em uma das opções abaixo e continue a se INspirar.</h6>
                        <!--<span>O erro já foi enviado para equipe técnica para análise, obrigado pela compreensão.</span>-->
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
    <script>
        $(document).ready(function(){
            window.location.href = '{{url('/login')}}';
        })
    </script>
@stop
