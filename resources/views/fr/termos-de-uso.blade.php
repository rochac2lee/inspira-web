@extends(Auth::user() ? 'fr/master' : 'fr/masterFora')
@section('content')
<section class="section section-interna">
            
        <div class="container">
            <h2 class="title-page">
                <a href="#" onclick="window.history.go(-1);" title="Voltar" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> </a>
                TERMOS DE USO DA PLATAFORMA INSPIRA
            </h2>
            <div class="row section-grid colecoes text-justify" style="line-height:1.5">

            	{!!$plataforma->termo!!}

        	</div>
        </div>

  

</section>
@stop
