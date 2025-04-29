@extends('fr/master')
@section('content')
@php
    $vetAno =[
                4 => 1,
                5 => 2,
                6 => 3,
                7 => 4,
                8 => 5,
                9 => 1,
                10 => 2,
                11 => 3,
                12 => 4,
                13 => 5,
                14 => 6,
                15 => 7,
                16 => 8,
                17 => 9,
                18 => 1,
                19 => 2,
                20 => 3,
            ];
@endphp
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
                    @if(count($relatorio)>0)
                    <span><a href="{{url('indica/gestao/avaliacao/relatorio/download/'.$avaliacao->id)}}" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="download"><i class="fas fa-download"></i> Download</a></span>
                    @endif
                    {{-- <span><a href="{{url('indica/gestao/avaliacao/relatorio/ocorrencias/'.$avaliacao->id)}}" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="ocorrências"><i class="fas fa-user-ninja"></i> Ocorrências ({{$ocorrencias}})</a></span>--}}
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
                                <th scope="col">aplicado em</th>
                                <th scope="col">caderno</th>
                                 <th scope="col">email</th>
                                 @foreach($ordemPerguntas as $p)
                                     <th scope="col">item{{$loop->iteration}}</th>
                                 @endforeach
                                <th scope="col">status</th>
                                <th scope="col">iniciado em</th>
                                <th scope="col">tentativas</th>
                                <th scope="col">tempo fechada</th>
                                <th scope="col">tempo aberta</th>
                                <th scope="col">tempo total</th>
                                <th scope="col">token</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($relatorio as $d)
                                @foreach($d->turmaDeAlunos as $t)

                                      <tr
                                          @if(count($d->tentativasIndica)>0)
                                            onclick="window.location.href='{{url('indica/gestao/avaliacao/relatorio/ocorrencias/detalhes/'.$avaliacao->id.'/'.$d->id)}}'"
                                          @endif
                                      >
                                        <td>{{$d->id}}</td>
                                         <td>{{$avaliacao->id}}</td>
                                        <td>{{$disciplina}}</td>
                                        @if($t->instituicao_tipo_id==2)
                                        <td>{{$t->cidade}}</td>
                                        @else
                                        <td>{{$t->instituicao}}</td>
                                        @endif
                                        <td>{{$t->escola}}</td>
                                        <td>{{$vetAno[$t->ciclo_etapa_id]}}</td>
                                        <td>{{$t->titulo}}</td>
                                        <td>{{$t->turno}}</td>
                                        <td>
                                            @if($t->matricula == '')
                                                {{$d->matricula}}
                                            @else
                                                {{$t->matricula}}
                                            @endif
                                        </td>
                                        <td>{{$d->nome_completo}}</td>
                                        <td>@if($d->data_nascimento!=''){{$d->data_nascimento->format('d/m/Y')}}@endif</td>
                                        <td>@if($d->data_nascimento!=''){{\Carbon\Carbon::parse($d->data_nascimento)->age}}@endif</td>
                                        <td>{{$d->genero}}</td>

                                            <td>{{Str::of($avaliacao->data_hora_inicial)->explode('-')[0]}}</td>
                                          <td>{{$avaliacao->caderno}}</td>
                                          <td>{{$d->email}}</td>
                                          @if(isset($questoesAlunos[$d->id]))
                                              @foreach($questoesAlunos[$d->id] as $p)
                                                  <td>{{$p}}</td>
                                              @endforeach
                                          @else
                                              @foreach($ordemPerguntas as $p)
                                                  <td></td>
                                              @endforeach
                                          @endif
                                          <td>
                                              @if($d->placarIndica)
                                                  Finalizada
                                              @elseif(count($d->logAtividadeIndica)>0 || count($d->logGeralIndica)>0)
                                                  Iniciada
                                              @else
                                                  Não iniciada
                                              @endif
                                          </td>
                                          <td>
                                              @if(count($d->tentativasIndica)>0)
                                                  {{$d->tentativasIndica[0]->created_at->format('d/m/Y')}}
                                              @endif
                                          </td>
                                          <td>{{count($d->tentativasIndica)}}</td>
                                          <td>
                                                {{gmdate("H:i:s", $d->logGeralIndica->sum('tempo_inativo'))}}
                                          </td>
                                          <td>
                                                  {{gmdate("H:i:s",$d->logGeralIndica->sum('tempo_ativo'))}}
                                          </td>
                                          <td>
                                                  {{gmdate("H:i:s",$d->logGeralIndica->sum('tempo_inativo') + $d->logGeralIndica->sum('tempo_ativo'))}}
                                          </td>
                                          <td>
                                              @if($d->placarIndica)
                                                  {{$d->placarIndica->token}}
                                              @elseif(count($d->logGeralIndica)>0)
                                                  {{$d->logGeralIndica[0]->token}}
                                              @endif
                                          </td>
                                      </tr>
                                @endforeach

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
