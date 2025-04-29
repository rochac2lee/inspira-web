@extends('fr/master')
@section('content')
    <section class="section section-interna">
        <div class="container">
            <div class="row pb-4">
                <div class="col-md-12">
                    <h3>
                        <a href="{{ url('/gestao/escolas')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Importações de usuários realizadas
                    </h3>
                </div>

            </div>
            <div class="row">
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th >Código</th>
                                    <th scope="col">Data</th>
                                    <th scope="col">Usuário</th>
                                    <th scope="col">Nome arquivo original</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-right" style="min-width: 220px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dados as $d)
                                    <tr>
                                        <td>#{{$d->id}}</td>
                                        <td>{{$d->created_at->format('d/m/Y H:i:s')}}</td>
                                        <td>{{$d->name}}</td>
                                        <td>{{$d->nome_original}}</td>
                                        <td>
                                            @if($d->qtd_certo<=0 && $d->qtd_errado<=0)
                                                <span class="text-warning" ><b>Processando fila</b></span>
                                            @else
                                                @if($d->qtd_certo>0)
                                                    <span style="color: #00cc05" ><b>{{number_format($d->qtd_certo,0,',','.')}}</b> efetivados</span>
                                                @endif
                                                @if($d->qtd_certo>0 && $d->qtd_errado>0)
                                                    <br>
                                                @endif
                                                @if($d->qtd_errado>0)
                                                    <span style="color: #ff0000" ><b>{{number_format($d->qtd_errado,0,',','.')}}</b> não efetivados</span>
                                                @endif
                                            @endif

                                        </td>
                                        <td>
                                            <a href="{{url('/gestao/escola/relatorio/importacao/usuarios/detalhes/'.$d->id)}}" class="btn btn-secondary btn-sm"><i class="fas fa-search"></i> Detalhes</a>
                                            <a href="{{url('/gestao/escola/importacao/usuarios/download/'.$d->id)}}" class="btn btn-secondary btn-sm"><i class="fas fa-file-download"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <nav class="mt-4" aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    {{$dados->appends(Request::all())->links()}}
                </ul>
            </nav>
        </div>
    </section>

    <script>

    $('.table-responsive').on('show.bs.dropdown', function () {
         $('.table-responsive').css( "overflow", "inherit" );
    });

    $('.table-responsive').on('hide.bs.dropdown', function () {
         $('.table-responsive').css( "overflow", "auto" );
    })

    </script>
@stop
