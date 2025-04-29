@extends('fr/master')
@section('content')
<style>
    table.table-bordered > thead > tr > th{
        border:1px solid black;
    }
    table.table-bordered > tbody > tr > td{
        border:1px solid black;
    }
</style>
    <section class="section section-interna">
        <div class="container-fluid" >
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="title-page">
                            @isset($ead)
                                <a  href="{{url('ead/listar')}}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                Relatório EAD - {{$dados->titulo}}
                            @else
                                <a  href="{{url('/gestao/trilhass/')}}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                Relatório - {{$dados->titulo}}
                            @endisset

                        </h3>
                    </div>
                </div>
            @if(count($relatorio->matriculas)>0)
                <div class="row" style="overflow-x: auto; overflow-y: hidden;">
                    <div class="col-12">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Estudantes</th>
                                @php $i=1; @endphp
                                @foreach($relatorio->cursos as $c)
                                    @php $j=1; @endphp
                                    @foreach($c->temas as $t)
                                        @php $k=1; @endphp
                                        @foreach($t->conteudo as $con)
                                            <th data-toggle="tooltip" data-placement="top" title="{{$c->titulo}} - {{$t->titulo}} - {{$con->titulo}}">R{{$i}}-T{{$j}}-C{{$k}}</th>
                                            @php $k++; @endphp
                                        @endforeach
                                        @php $j++; @endphp
                                    @endforeach
                                    @php $i++; @endphp
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($relatorio->matriculas as $m)
                                <tr>
                                    <td>
                                        <p style=" margin-bottom: 5px;"><b>{{$m->name}}</b></p>
                                        {{$percCursados[$m->id]['perc']}}% ({{$percCursados[$m->id]['feito']}} de {{$percCursados[$m->id]['total']}}) <br>
                                        @if($m->pivot->tentativas_avaliacao>0)
                                            {{$m->pivot->tentativas_avaliacao}} tentativa(s) na avaliação -  maior conceito {{number_format($m->pivot->porcentagem_acerto,2,',','.')}}
                                            @if($m->pivot->porcentagem_acerto >=7)
                                                <a target="blank" href="{{url('/ead/certificado/'.$m->pivot->chave_validacao)}}"><i class="fas fa-print"></i></a>
                                            @endif
                                        @endif
                                    </td>
                                    @foreach($relatorio->cursos as $c)
                                        @foreach($c->temas as $t)
                                            @foreach($t->conteudo as $con)
                                                <td class="text-center" style="background-color: @if(isset($cursados[$m->id][$c->id][$t->id][$con->id])) #d7fad8 @else #fad7d7 @endif">
                                                    @if( ($con->tipo == 7 || $con->tipo == 10) && isset($cursados[$m->id][$c->id][$t->id][$con->id]))
                                                        <a href="javascript:void(0)" onclick="verInteracao({{$con->tipo}}, {{$m->id}}, {{$c->id}}, {{$t->id}}, {{$con->id}})">Ver @if($con->tipo == 7) dissertativa @else entregável @endif</a>
                                                    @endif
                                                </td>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            @else
                <div class="row">
                    <div class="col-12">
                        Nenhum estudante matriculado.
                    </div>
                </div>
            @endif

        </div>
    </section>

<!-- Modal Interacao -->
<div class="modal fade "  id="modalInteracao" tabindex="-1" role="dialog" aria-labelledby="modalInteracao" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <span id="corpoModal">

            </span>
        </div>
    </div>
</div>


    <script>
        function verInteracao(tipo, user, curso, tema, conteudo){

            $.ajax({
                url: '{{url('/gestao/trilhass/getInteracao')}}',
                type: 'post',
                dataType: 'json',
                data: {
                    tipo    : tipo,
                    user_id : user,
                    curso_id: curso,
                    aula_id : tema,
                    conteudo_id: conteudo,
                    trilha_id: {{$dados->id}},
                    _token  : '{{csrf_token()}}'
                },
                success: function(data) {
                    $('#corpoModal').html(data);
                    $('#modalInteracao').modal('show');
                },
                error: function() {
                    swal("", "Não foi possível carregar a interação.", "error");
                }
            });
        }
    </script>
@stop
