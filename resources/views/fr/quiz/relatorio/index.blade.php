@extends('fr/master')
@section('content')
	<section class="section section-interna">
		<div class="container">
                <h2 class="title-page">
                    <a href="{{ url('/gestao/quiz?componente='.$quiz->disciplina_id) }}" title="Voltar" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                   Relatório do quiz <i>{{$quiz->titulo}}</i>
                </h2>
                <h4 style="margin-bottom: 18px;">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="{{url('/gestao/quiz/relatorio/'.$quiz->id.'?tipo=a')}}" class="nav-link @if(Request::input('tipo')!= 'g' && Request::input('tipo')!= 'q') active @endif">Estudantes</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('/gestao/quiz/relatorio/'.$quiz->id.'?tipo=g')}}" class="nav-link @if(Request::input('tipo')== 'g' ) active @endif">Visão geral</a>
                        </li>
                    </ul>
                </h4>
			<div class="row">
                @if(Request::input('tipo')== 'g' )
                    @include('fr/quiz/relatorio/relatorio_visao_geral')
                @else
                    @include('fr/quiz/relatorio/relatorio_alunos')
                @endif
			</div>

		</div>
	</section>

@stop
