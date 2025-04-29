@extends('fr/master')
@section('content')
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery/jquery-ui.js"></script>
    <section class="section section-interna" style="padding-top: 50px">
        <div class="container">
            @include('fr.agenda.menu')
            <div class="row mb-3 border-top pt-4">
                <div class="col-md-12">
                    <div class="filter">
                        <form class="form-inline d-flex justify-content-end">
                            <div class="input-group ml-1">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </div>
                                <input size="40" type="text" name="nome" value="{{Request::input('nome')}}" placeholder="Digite o título ou código para buscar" class="form-control" />
                            </div>
                            @if(auth()->user()->permissao == 'I' && count($escolas)>1)
                            <div class="input-group ml-1">
                                <select class="form-control" name="escola">
                                    <option value="">Escola</option>
                                    @foreach($escolas as $e)
                                        <option @if(Request::input('escola') == $e->id) selected @endif value="{{$e->id}}">{{$e->titulo}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="input-group ml-1">
                                <select class="form-control" name="publicado">
                                    <option value="">Status</option>
                                    <option @if(Request::input('publicado') == 1) selected @endif value="1">Publicado</option>
                                    <option @if(Request::input('publicado') == 0 && Request::input('publicado') == '0') selected @endif value="0">Rascunho</option>
                                </select>
                            </div>
                            <div class="input-group ml-1">
                                <button type="submit" class="btn btn-secondary">Localizar</button>
                            </div>
                            <div class="input-group ml-1">
                                <a type="button" href="{{url()->current()}}" class="btn btn-secondary">Limpar Filtros</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row pb-4">
                <div class="col-md-4">
                    <h3>
                        Canais de atendimento
                    </h3>
                </div>
                    <div class="col-md-8 text-right">
                        <a href="{{url('gestao/agenda/canais-atendimento/novo')}}" class="btn btn-success" >
                            <i class="fas fa-plus"></i>
                            Novo Canal
                        </a>
                    </div>
            </div>
            <div class="row">
                @if(count($dados)>0)
                    @if(Request::input('escola') == '' && auth()->user()->permissao == 'I' && count($escolas)>1)
                        <h6>Você pode ordenar os canais de atendimento especificando uma escola.</h6>
                    @else
                        <h6>Caso deseje alterar a ordem, clique sobre o nome ou imagem e arraste para cima ou para baixo.</h6>
                    @endif
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Atendente</th>
                                <th scope="col">Contatos</th>
                                <th scope="col">Cargo</th>
                                <th scope="col"></th>
                                <th scope="col">Ação</th>
                            </tr>
                            </thead>
                            <tbody id="sortable">
                                @foreach($dados as $d)
                                    <tr id="{{$d->id}}">
                                        <td>#{{$d->id}}</td>
                                        <td>
                                            <div style="">
                                                <img class="img-fluid float-left mr-3" src="{{$d->linkImagem}}" width="50px">
                                                {{$d->nome}}
                                            </div>
                                            <br><br><br>
                                            <div>
                                                <span class="badge badge-light" style="font-weight: normal"><i class="fas fa-user"></i> {{$d->usuario->nome}}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <i class="fas fa-envelope"></i> {{$d->email}}<br>
                                            @if($d->telefone_eh_zap==1)<i class="fab fa-whatsapp" aria-hidden="true"></i> @else <i class="fas fa-phone"></i> @endif {{$d->telefone}}
                                        </td>
                                        <td>
                                            {{$d->cargo}}<br>
                                        </td>
                                        <td>
                                            @if($d->publicado == 1)
                                                <span class="badge badge-success">Publicado<br>{{$d->updated_at->format('d/m/Y H:i:s')}}</span>
                                            @else
                                                <span class="badge badge-danger">Rascunho<br>{{$d->updated_at->format('d/m/Y H:i:s')}}</span>
                                            @endif
                                        </td>
                                        <td class="text-right text-nowrap">
                                            @if( $d->user_id == auth()->user()->id)
                                                <span><a href="{{url('/gestao/agenda/canais-atendimento/publicar/'.$d->id.'/'.$d->publicado)}}" class="btn btn-success btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="@if($d->publicado==1) Despublicar @else Publicar @endif"><i class="fas fa-bullhorn"></i></a></span>
                                                <span><a href="{{url('/gestao/agenda/canais-atendimento/editar/'.$d->id)}}" class="btn btn-success btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pencil-alt"></i></a></span>
                                                <span><button class="btn btn-danger btn-sm fs-13" data-toggle="tooltip" data-placement="top" title="Excluir" onclick="modalExcluir({{$d->id}}, '{{$d->nome}}')"><i class="fas fa-trash-alt"></i></button></span>
                                            @endif
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
            <nav class="mt-4" aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    {{ $dados->appends(Request::all())->links() }}
                </ul>
            </nav>
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
                    <h5 class="modal-title" id="exampleModalLabel">Exclusão de Canal de Atendimento</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                Você deseja mesmo excluir este canal de atendimento?<br><br>
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
        window.location.href = '{{url("/gestao/agenda/canais-atendimento/excluir")}}/'+idExcluir;
    }

    var idPublicar =0;
    function modalPublicar(id, nome)
    {
        idPublicar = id;
        $('#nomePerguntaPublicar').html(nome);
        $('#formPublicar').modal('show');
    }
    function publicar()
    {
        window.location.href = '{{url("/gestao/agenda/canais-atendimento/publicar")}}/'+idPublicar;
    }

    @if( (auth()->user()->permissao == 'I' && Request::input('escola')!='') || auth()->user()->permissao == 'C' || count($escolas)==1)
        @if(Request::input('escola')!='')
            escola = {{Request::input('escola')}};
        @else
            escola = {{$escolas[0]->id}};
        @endif
    $(function() {
        $("#sortable").sortable({
            update: function() {
                var sort = $(this).sortable("toArray");
                $.ajax({
                    url: '{{url('/gestao/agenda/canais-atendimento/ordem/')}}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        ordem: sort,
                        escola: escola,
                        _token: '{{csrf_token()}}'
                    }
                });
            }
        });
        $("#sortable").disableSelection();
    });
    @endif

</script>
@stop
