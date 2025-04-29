@extends('fr/master')
@section('content')

<link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.css">
<script src="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.js"></script>

<style type="text/css">
	.modal-full {
	    min-width: 90%;
	    margin-left: 80;
	}
    .custom-control-input-laranja:checked~.custom-control-label::before {
        color: #fff;
        background-color: red;
        border-color:red;
        outline: 0;
        -webkit-box-shadow: none;
        box-shadow: none;
    }
</style>

<section class="section section-interna">
	<div class="container">

		<div class="row  pb-4" >
			<div class="col-md-6">
				<h4>
                    <a href="{{url('/avaliacao_ead/gestao/avaliacao')}}" class="btn btn-secondary ml-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    EAD - Revisar Questões
                </h4>
			</div>

		</div>

		<div class="row">

            <div class="col-12">
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Questão</th>
                                    {{--<th scope="col">Habilidade</th> --}}
                                    <th scope="col" class="text-right">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dados as $d)
                                    @php
                                        $pergunta =  strip_tags($d->pergunta);
                                    @endphp
                                <tr>
                                    <td>{{substr($pergunta,0,60)}}@if(strlen($pergunta)>60)... @endif
                                        {{--
                                        <br>
                                        <div class="mt-2 fs-11" style="font-size: 11px; color: #E65A24"><i class="fas fa-layer-group"></i> {{@$d->cicloEtapa->ciclo}} / {{@$d->cicloEtapa->ciclo_etapa}} &nbsp;&nbsp;&nbsp; <i class="fas fa-book"></i> {{$d->disciplina->titulo}} &nbsp;&nbsp;&nbsp; <i class="fas fa-ruler-vertical"></i> {{$dificuldade[$d->dificuldade]}} &nbsp;&nbsp;&nbsp; <i class="fas fa-user"></i> {{$d->usuario->name}}</div>
                                        --}}
                                    </td>
                                    {{--<td>@if(isset($d->bncc->codigo_habilidade))  {{$d->bncc->codigo_habilidade}} @endif</td> --}}

                                    <td class="text-right text-nowrap">
                                        <span><a href="{{url('/avaliacao_ead/gestao/avaliacao/revisao/questao/editar/'.$avaliacao_id.'/'.$d->id)}}" class="btn btn-secondary btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Editar Questão"><i class="fas fa-pencil-alt"></i></a></span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
		</div>
	</div>
</section>


@stop
