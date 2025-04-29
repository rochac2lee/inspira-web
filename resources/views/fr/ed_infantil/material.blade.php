@extends('fr/ed_infantil/master_interna')
@section('content')
<section class="section conteudo-interna">
	<div class="itens slide-banner">
		@foreach($dados as $d)
			@include('fr/ed_infantil/item_material')
		@endforeach
	</div>
</section>
<div class="modal fade divModalVisualizarConteudo" id="divModalVisualizarConteudo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl dialog-fixed px-1 px-md-5" role="document">
        <div class="modal-content content-fixed">
            <div class="modal-body body-fixed">

                <button style="z-index:1;" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
				</button>

                <div class="row mb-2">
                    <div class="col align-middle my-auto">
                        <div class="col-12 title conteudo-titulo mb-0">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mx-auto conteudo-tipo">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel='stylesheet' type="text/css" href="{{ config('app.cdn') }}/fr/includes/audioplayer/audioplayer.css"/>
<script src="{{ config('app.cdn') }}/fr/includes/audioplayer/audioplayer.js" type="text/javascript"></script>
<script>
    var settings1 = {
        disable_volume: "off"
        ,autoplay: "off"
        ,cue: "on"
        ,disable_scrub: "default"
        ,design_skin: "skin-wave"
        ,skinwave_dynamicwaves:"on"
        ,skinwave_enableSpectrum: "off"
        ,settings_backup_type:"full"
        ,settings_useflashplayer:"auto"
        ,skinwave_spectrummultiplier: "4"
        ,skinwave_comments_enable:"on"
        ,skinwave_mode: "small"
        ,skinwave_comments_retrievefromajax:"on"
        ,pcm_data_try_to_generate: "on"
        };
</script>
<script type="text/javascript">

	var porPagina = {{$dados->perPage()}};
	var metadePagina = parseInt(porPagina/2);
	var nextPagina = 2;

	$('.slide-banner').on('init', function(event, slick){
	    $('.home.interna .itens').css("overflow", "initial");
	});

	$('.slide-banner').on('beforeChange', function(event, slick, currentSlide, nextSlide){
	  if(nextSlide+4 >= metadePagina){
	  	getConteudoAjax();
	  }
	});

	$('.slide-banner').slick({
	  	dots: false,
	  	slidesToShow: 4,
		slidesToScroll: 4,
	  	infinite: false,
	  	cssEase: 'linear',
	  	arrows: true,
	  	autoplay: false,
	  	responsive: [
            {
                breakpoint: 940,
                settings: "unslick"
            }
        ]
	});

	function getConteudoAjax()
	{
		metadePagina = porPagina+metadePagina;

		$.ajax({
            url: '{{ config('app.url') }}' + '/infantil/colecao/ajaxMaterial/{{$colecaoId}}?busca={{request()->get('busca')}}&page=' + nextPagina,
            type: 'get',
            dataType: 'json',
            success: function( _response )
            {
            	nextPagina++;
                if(_response)
                {
                    $('.slide-banner').slick('slickAdd', _response);
                }

            },
            error: function( _response )
            {

            }
        });
	}

	function visualizarConteudo(idConteudo)
    {
        $.ajax({
            url: '{{ config('app.url') }}' + '/gestao/biblioteca/' + idConteudo + '/visualizar',
            type: 'get',
            dataType: 'json',
            success: function( _response )
            {


                if(_response)
                {

                    $(".conteudo-titulo").empty();
                    $(".conteudo-tipo").empty();

                    $(".conteudo-titulo").append(_response.titulo);
                    $(".conteudo-tipo").append(_response.descricao);
                    $("#player1").audioplayer(settings1);
                    $('#divModalVisualizarConteudo').modal('show');
                }

            },
            error: function( _response )
            {

            }
        });
    }

    $('#divModalVisualizarConteudo').on('hidden.bs.modal', function () {
            $(".conteudo-titulo").empty();
            $(".conteudo-tipo").empty();
        });
</script>
@stop
