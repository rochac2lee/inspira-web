@extends('fr/master')
@section('content')

    <section class="section section-interna">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="pb-1 border-bottom mb-4">
                        <a href="{{url('/indica/gestao/questao/importa')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        INdica - Detalhes Importar Questões
                    </h4>
                </div>
            </div>
            <div class="row  pb-4" >
                <div class="col-md-6">
                    <h6>{{$arquivo->created_at->format('d/m/Y H:i:s')}} - {{$arquivo->nome_arquivo}} / {{$arquivo->usuario->name}}</h6>
                </div>
            </div>

            <div class="row">

                <div class="col-12">
                    <section class="table-page w-100">
                        <div class="table-responsive table-hover">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                <tr>
                                    <th >Linha</th>
                                    <th scope="col">Erro de</th>
                                    <th scope="col">Mensagem</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dados as $d)
                                    <tr>
                                        <td>{{$d->linha}}</td>
                                        <td>
                                            @if($d->erro_banco=='')
                                                Validação
                                            @else
                                                Banco de dados
                                            @endif
                                        </td>
                                        <td>
                                            @if($d->erro_banco=='' )
                                                @if($d->erro_validacao != '')
                                                    @php
                                                        $errors = unserialize($d->erro_validacao);
                                                    @endphp
                                                    @if($errors->any())
                                                        <ul>
                                                            {!!implode('', $errors->all('<li>:message</li>'))!!}
                                                        </ul>
                                                    @endif
                                                @endif
                                            @else
                                                {{$d->erro_banco}}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach()

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
        </div>
    </section>

@stop
