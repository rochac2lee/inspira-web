@php
	$iconeTopo = '';
	$capa = '';
	if($d->tipo == 2)
	{
		$iconeTopo = 'audio.png';
		$capa = '/storage/capa_audios/';

	}elseif($d->tipo == 3)
	{
		$iconeTopo = 'video.png';
		$capa = '/storage/capa_videos/';
	}
	elseif($d->tipo == 103)
	{
		$iconeTopo = 'game.png';
		$capa = '/storage/capa_jogos/';
	}
	elseif($d->tipo == 100)
	{
		$iconeTopo = 'imagem.png';
		$capa = '/storage/banco_imagens/thumbs/';
	}
	elseif($d->tipo == 4)
	{
		$iconeTopo = 'ppt.png';
		$capa = '/storage/capa_ppt/';
	}
	elseif($d->tipo == 15)
	{
		$iconeTopo = 'pdf.png';
		$capa = '';
	}

@endphp
<div class="item" >
	<div class="banner" @if(auth()->user()->permissao == 'A') onclick="visualizarConteudo({{$d->id}})" @else style="cursor: default" @endif >
		<span @if(auth()->user()->permissao != 'A') style="cursor: pointer;" onclick="visualizarConteudo({{$d->id}})" @endif   >
			@if($iconeTopo!='')
				<div class="banner-icon-top"><img src="{{ config('app.cdn') }}/fr/ed_infantil/imagens/icon/{{$iconeTopo}}"></div>
			@endif
			<i style="height: 110px; background-size: 198px; background-image: url({{ config('app.cdn').$capa.$d->capa }})"></i>
			<p class="mr-3 ml-3">{{$d->titulo}}</p>
		</span>
		@if(auth()->user()->permissao != 'A' && $d->comentario_pedagogico!='')
			<div class="banner-icon"><img src="{{ config('app.cdn') }}/fr/ed_infantil/imagens/itens/001-icon.png"></div>
		@endif
	</div>
</div>
