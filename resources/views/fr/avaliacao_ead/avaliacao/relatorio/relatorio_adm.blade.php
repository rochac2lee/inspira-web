@extends('fr/master')
@section('content')

    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.css">
    <script src="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.js"></script>

    <section class="section section-interna">
        <div class="container">
            <div class="row pt-0 pb-4">
                <div class="col-md-6">
                    <h3>
                        <a href="{{url('indica/gestao/avaliacao')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        INdica - Avaliações</h3>
                </div>
                <div class="col-md-6 p-0 text-right">
                    <span><a href="{{url('indica/gestao/avaliacao/relatorio/download/'.$avaliacao->id)}}" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="download"><i class="fas fa-download"></i> Download</a></span>
                </div>
            </div>
            <div class="row">
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">form_id</th>
                                <th scope="col">avaliacao</th>
                                <th scope="col">disciplina</th>
                                <th scope="col">instituicao/município</th>
                                <th scope="col">escola</th>
                                <th scope="col">ano</th>
                                <th scope="col">turma</th>
                                <th scope="col">turno</th>
                                <th scope="col">matricula</th>
                                <th scope="col">nome</th>
                                <th scope="col">d_nasc</th>
                                <th scope="col">idade</th>
                                <th scope="col">sexo</th>
                                @foreach($ordemPerguntas as $p)
                                    <th scope="col">item{{$loop->iteration}}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($relatorio as $d)
                               <tr>
                                <td>{{$d->usuario->id}}</td>
                                <td>{{$d->indica_avaliacao_id}}</td>
                                <td>{{$disciplina}}</td>
                                @if($d->instituicao->instituicao_tipo_id==2)
                                <td>{{$d->escola->cidade}}</td>
                                @else
                                <td>{{$d->instituicao->titulo}}</td>
                                @endif
                                <td>{{$d->escola->titulo}}</td>
                                <td>{{$turmaAlunos[$d->usuario->id]['ciclo_etapa_id']}}</td>
                                <td>{{$turmaAlunos[$d->usuario->id]['turma']}}</td>
                                <td>{{$turmaAlunos[$d->usuario->id]['turno']}}</td>
                                <td>{{$d->matricula}}</td>
                                <td>{{$d->usuario->nome_completo}}</td>
                                <td>@if($d->usuario->data_nascimento!=''){{$d->usuario->data_nascimento->format('d/m/Y')}}@endif</td>
                                <td>@if($d->usuario->data_nascimento!=''){{\Carbon\Carbon::parse($d->usuario->data_nascimento)->age}}@endif</td>
                                <td>{{$d->usuario->genero}}</td>
                                @foreach($questoesAlunos[$d->usuario->id] as $p)
                                    <td>{{$p}}</td>
                                @endforeach
                               </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <nav class="mt-4" aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    {{ $relatorio->appends(Request::all())->links() }}
                </ul>
            </nav>
        </div>
    </section>

@stop
