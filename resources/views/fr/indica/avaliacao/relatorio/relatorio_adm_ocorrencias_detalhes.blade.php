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
                        INdica - Ocorrências em {{$avaliacao->titulo}}
                        <small>{{$dados->name}}</small>
                    </h3>
                </div>

            </div>
            <div class="row">
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Ocorrências</th>
                                <th scope="col">Data</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dados->tentativasIndica as $d)
                              <tr>
                                <td>
                                    @if($d->iniciou == 1)
                                        Entrou
                                    @elseif($d->iniciou == 0)
                                        Saiu
                                    @elseif($d->iniciou == 2)
                                        Finalizou
                                    @endif
                                </td>
                                <td>{{$d->created_at->format('d/m/Y H:i:s')}}</td>
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
