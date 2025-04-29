@extends('fr/master')
@section('content')
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery/jquery-ui.js"></script>
    <section class="section section-interna" style="padding-top: 50px">
        <div class="container">
            <h3 class="title-page mt-5">
                Rotinas da Agenda e Registro
            </h3>
            @if($vetRotinas[1]['qtd'] < 20 || $vetRotinas[2]['qtd'] < 20 || $vetRotinas[3]['qtd'] < 20)
                <div class="col-md-12 text-right mb-2">
                    <a href="{{url('/gestao/agenda/registros/rotinas/opet/novo')}}" class="btn btn-success" >
                        <i class="fas fa-plus"></i>
                        Nova atividade
                    </a>
                </div>
            @endif
            <div class="row">
                <div class="col-12">
                <h4 style="margin-bottom: 18px;">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == '1' ||  Request::input('tipo') == '') active @endif" href="{{url('/gestao/agenda/registros/rotinas/opet?tipo=1')}}">
                                Rotina 1
                                <p class="text-center" style="font-size: 10px; margin-bottom: 0px">{{$vetRotinas[1]['qtd']}} de 20</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == 2 ) active @endif" href="{{url('/gestao/agenda/registros/rotinas/opet?tipo=2')}}">
                                Rotina 2
                                <p class="text-center" style="font-size: 10px; margin-bottom: 0px">{{$vetRotinas[2]['qtd']}} de 20</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == 3 ) active @endif" href="{{url('/gestao/agenda/registros/rotinas/opet?tipo=3')}}">
                                Rotina 3
                                <p class="text-center" style="font-size: 10px; margin-bottom: 0px">{{$vetRotinas[3]['qtd']}} de 20</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == 4 ) active @endif" href="{{url('/gestao/agenda/registros/rotinas/opet?tipo=4')}}">
                                Rotina 4
                                <p class="text-center" style="font-size: 10px; margin-bottom: 0px">{{$vetRotinas[4]['qtd']}} de 20</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == 5 ) active @endif" href="{{url('/gestao/agenda/registros/rotinas/opet?tipo=5')}}">
                                Rotina 5
                                <p class="text-center" style="font-size: 10px; margin-bottom: 0px">{{$vetRotinas[5]['qtd']}} de 20</p>
                            </a>
                        </li>
                    </ul>
                </h4>
                </div>
            </div>
            @if(count($dados)>0)
            <div class="row">
                <h6>Arraste as linhas para ordenar as rotinas.</h6>
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Ícone</th>
                                <th scope="col">Título</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody id="sortable">
                                @foreach($dados as $d)
                                    <tr id="{{$d->id}}">
                                        <td>#{{$d->id}}</td>
                                        <td>
                                            <img class="img-fluid" src="{{$d->linkImagem}}" width="50px">
                                        </td>
                                        <td>
                                            {{$d->titulo}}
                                        </td>
                                        <td class="text-right text-nowrap">
                                            <span><a href="{{url('/gestao/agenda/registros/rotinas/opet/editar/'.$d->id)}}" class="btn btn-success btn-sm fs-13"  title="Editar"><i class="fas fa-pencil-alt"></i></a></span>
                                            <span><button class="btn btn-danger btn-sm fs-13" title="Excluir" onclick="modalExcluir({{$d->id}}, '{{$d->titulo}}')"><i class="fas fa-trash-alt"></i></button></span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
                @else
                    <div class="col">
                        <div class="card text-center">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado</h5>
                                <p class="card-text ">Não foi encontrado resultado contendo todos os seus termos de pesquisa, clique no botão abaixo para reiniciar a pesquisa</p>
                                <a class="btn btn-danger fs-13 mb-2" href="{{Request::url()}}" title="Excluir"><i class="fas fa-undo-alt"></i> Limpar Filtro</a>
                            </div>
                            <div class="card-footer text-muted"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- EXCLUIR -->
    <div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Exclusão de Rotinas</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                Você deseja mesmo excluir esse registro?<br><br>
                                <b id="nomePergunta"></b><br>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <button type="button" onclick="excluir()" class="btn btn-danger">Sim, excluir</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM EXCLUIR -->

<script>
    var idExcluir =0;
    function modalExcluir(id, nome)
    {
        idExcluir = id;
        $('#nomePergunta').html(nome);
        $('#formExcluir').modal('show');
    }
    function excluir()
    {
        window.location.href = '{{url("/gestao/agenda/registros/rotinas/opet/excluir")}}/'+idExcluir;
    }

    $(function() {
        $("#sortable").sortable({
            update: function() {
                var sort = $(this).sortable("toArray");
                $.ajax({
                    url: '{{url('/gestao/agenda/registros/rotinas/opet/ordem/')}}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        ordem: sort,
                        _token: '{{csrf_token()}}'
                    }
                });
            }
        });
        $("#sortable").disableSelection();
    });

</script>
@stop
