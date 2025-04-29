@extends('fr/ed_infantil/master')
@section('content')
<section class="section conteudo">
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="menu menu-1">
				<div class="hover no-mobile">
					<i class="fas fa-volume-up mt-4"></i>
					<audio class="audio" src="{{ config('app.cdn') }}/fr/ed_infantil/audio/1_para_ficar_mais_sabido.mp3" autostart="false" ></audio>
				</div>
				<a href="{{url('infantil/colecao/1')}}"><div class="menu_chamada"></div></a>
			</div>
			<div class="menu menu-2">
				<div class="hover no-mobile">
					<i class="fas fa-volume-up mt-4"></i>
					<audio class="audio" src="{{ config('app.cdn') }}/fr/ed_infantil/audio/2_uma_historia_por_favor.mp3" autostart="false" ></audio>
				</div>
				<a href="{{url('infantil/colecao/2')}}"><div class="menu_chamada"></div></a>
			</div>
			<div class="menu menu-3">
				<div class="hover no-mobile">
					<i class="fas fa-volume-up mt-4"></i>
					<audio class="audio" src="{{ config('app.cdn') }}/fr/ed_infantil/audio/3_canta_canta_minha_gente.mp3" autostart="false" ></audio>
				</div>
				<a href="{{url('infantil/colecao/3')}}"><div class="menu_chamada"></div></a>
			</div>
			<div class="menu menu-4">
				<div class="hover no-mobile">
					<i class="fas fa-volume-up mt-4"></i>
					<audio class="audio" src="{{ config('app.cdn') }}/fr/ed_infantil/audio/4_brincadeira_divertida.mp3" autostart="false" ></audio>
				</div>
				<a href="{{url('infantil/colecao/4')}}"><div class="menu_chamada"></div></a>
			</div>
			<div class="menu menu-5">
				<div class="hover no-mobile">
					<i class="fas fa-volume-up mt-4"></i>
					<audio class="audio" src="{{ config('app.cdn') }}/fr/ed_infantil/audio/5_maos_na_massa.mp3" autostart="false" ></audio>
				</div>
				<a href="{{url('infantil/colecao/5')}}"><div class="menu_chamada"></div></a>
			</div>
			@if(auth()->user()->permissao != 'A' && 1 == 2)
				<div class="menu menu-6">
					<div class="hover no-mobile">
						<i class="fas fa-volume-up mt-4"></i>
						<audio class="audio" src="{{ config('app.cdn') }}/fr/ed_infantil/audio/6_docente.mp3" autostart="false" ></audio>
					</div>
					<a href="{{url('infantil/colecao_professor')}}"><div class="menu_chamada"></div></a>
				</div>
			@endif
		</div>
	</div>
</section>
@stop
