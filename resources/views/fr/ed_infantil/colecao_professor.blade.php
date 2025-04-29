@extends('fr/ed_infantil/master_interna')
@section('content')
<section class="section conteudo-interna">
	<div class="itens slide-banner">
		<div class="item" onclick="abreColecao(6)">
			<div class="banner">
				<i style="background-image: url({{ config('app.cdn') }}/fr/infantil/imagens/itens/001-foto.jpg)"></i>MATERIAIS DE ESTUDO
				<div class="banner-icon"><img src="{{ config('app.cdn') }}/fr/infantil/imagens/itens/001-icon.png"></div>
			</div>
		</div>
		<div class="item" onclick="abreColecao(7)">
			<div class="banner">
				<i style="background-image: url({{ config('app.cdn') }}/fr/infantil/imagens/itens/002-foto.jpg)"></i>PUBLICAÇÕES
				<div class="banner-icon"><img src="{{ config('app.cdn') }}/fr/infantil/imagens/itens/001-icon.png"></div>
			</div>
		</div>
		<div class="item" onclick="abreColecao(8)">
			<div class="banner">
				<i style="background-image: url({{ config('app.cdn') }}/fr/infantil/imagens/itens/003-foto.jpg)"></i>MALETA CRIATIVA
				<div class="banner-icon"><img src="{{ config('app.cdn') }}/fr/infantil/imagens/itens/001-icon.png"></div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">

	function abreColecao(id)
	{
		window.location = '{{url('infantil/colecao/professor')}}/'+id;
	}

	$('.slide-banner').on('init', function(event, slick){
	    $('.home.interna .itens').css("overflow", "initial");
	});

	$('.slide-banner').slick({
	  	dots: false,
	  	slidesToShow: 4,
		slidesToScroll: 1,
	  	infinite: true,
	  	cssEase: 'linear',
	  	arrows: true,
	  	autoplay: true,
	  	autoplaySpeed: 4500,
	  	responsive: [
            {
                breakpoint: 940,
                settings: "unslick"
            }
        ]
	});
</script>
@stop
