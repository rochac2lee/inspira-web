@extends('fr/master')
@section('content')
    <section class="section section-interna">
        <div class="container">
            <div class="row pb-4">
                <div class="col-md-12">
                    <h3>
                        <a href="{{ url('/gestao/escola/relatorio/importacao/usuarios')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Detalhes da importação de usuários <small><br>{{dataBR($dados['created_at'])}} - {{$dados['name']}}</small>
                    </h3>
                    <h4 style="margin-bottom: 14px;">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="{{url('/gestao/escola/relatorio/importacao/usuarios/detalhes/'.$dados['id'])}}" class="nav-link @if(Request::input('tipo')!= 'c') active @endif">Erros ({{number_format($dados['qtd_errado'],0,',','.')}})</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/gestao/escola/relatorio/importacao/usuarios/detalhes/'.$dados['id'])}}?tipo=c" class="nav-link @if(Request::input('tipo')== 'c' ) active @endif">Corretos ({{number_format($dados['qtd_certo'],0,',','.')}})</a>
                            </li>
                        </ul>
                    </h4>
                </div>

            </div>
            <div class="row">
                @if(Request::input('tipo') != 'c')
                    @include('fr/gestao/escola/importacao_usuarios/detalhes_erro')
                @else
                    @include('fr/gestao/escola/importacao_usuarios/detalhes_certo')
                @endif
            </div>

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
