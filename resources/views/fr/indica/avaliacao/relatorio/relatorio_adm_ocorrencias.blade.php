@extends('fr/master')
@section('content')

    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.css">
    <script src="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.js"></script>

    <section class="section section-interna">
        <div class="container">
            <div class="row pt-0 pb-4">
                <div class="col-md-6">
                    <h3>
                        <a href="{{url('indica/gestao/avaliacao/relatorio/'.$avaliacao->id)}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        INdica - Ocorrências em {{$avaliacao->titulo}}</h3>
                </div>

            </div>
            <div class="row">
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Estudante</th>
                                <th scope="col">Instituição</th>
                                <th scope="col">Escola</th>
                                <th scope="col">Quantidade de tentativas</th>
                                <th scope="col">Tempo avaliação fechada</th>
                                <th scope="col">Tempo avaliação aberta</th>
                                <th scope="col">Tempo total</th>
                                <th scope="col">Token</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dados as $d)
                              <tr onclick="window.location.href='{{url('indica/gestao/avaliacao/relatorio/ocorrencias/detalhes/'.$avaliacao->id.'/'.$d->id)}}'">
                                <td>{{$d->nome_completo}}</td>
                                @if($d->instituicaoObj->instituicao_tipo_id==2)
                                    <td>{{$d->escola->cidade}}</td>
                                @else
                                    <td>{{$d->instituicaoObj->titulo}}</td>
                                @endif
                                <td>{{$d->escola->titulo}}</td>
                                <td>{{$d->placarIndica->qtd_tentativas}}</td>
                                <td>{{$d->placarIndica->tempo_janela_fechada}}</td>
                                <td>{{$d->placarIndica->tempo_janela_aberta}}</td>
                                <td>{{$d->placarIndica->tempo_total_tentativas}}</td>
                                <td>{{$d->placarIndica->token}}</td>
                              </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <nav class="mt-4" aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                </ul>
            </nav>
        </div>
    </section>

@stop
