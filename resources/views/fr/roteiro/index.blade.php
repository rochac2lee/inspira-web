@extends('fr/master')
@section('content')
<section class="section section-interna">
            <div class="container">
                <div class="row mb-3" style="margin-top: -40px">
                    <div class="col-md-12">
                        <div class="filter">
                            <form method="get" id="formPesquisa" class="form-inline d-flex justify-content-end">
                                <input type="hidden" name="biblioteca" value="{{Request::input('biblioteca')}}" id="biblioteca">
                                <div class="input-group ml-1">
                                    <select class="form-control" name="componente" onchange="mudaPesquisa()" style="width:250px;">
                                        <option value="">Componente Curricular</option>
                                        @foreach($disciplina as $d)
                                            <option @if(Request::input('componente') == $d->id) selected @endif value="{{$d->id}}">{{$d->titulo}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group ml-1">
                                    <select class="form-control" name="ciclo_etapa" onchange="mudaPesquisa()" style="width:300px;">
                                        <option value="">Etapa / Ano</option>
                                        @foreach($cicloEtapa as $c)
                                            <option @if( Request::input('ciclo_etapa') == $c->id) selected @endif value="{{$c->id}}">{{$c->ciclo}} - {{$c->ciclo_etapa}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group ml-1">
                                    <select class="form-control" name="exibicao" onchange="mudaPesquisa()">
                                        <option value="">Exibição</option>
                                        <option @if( Request::input('exibicao') == 1) selected @endif  value="1">Publicado</option>
                                        <option @if( Request::input('exibicao') == 0) selected @endif  value="0">Rascunho</option>
                                    </select>
                                </div>

                                <div class="input-group ml-1">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                    <input name="texto" type="text" value="{{Request::input('texto')}}" placeholder="Procurar Conteúdo" class="form-control" />
                                </div>
                                <div class="input-group ml-1">
                                    <a href="{{url('/gestao/roteiros')}}" class="btn btn-secondary btn-sm">Limpar Filtros</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center border-top  p-3 ">
                    <div class="col-md-3">
                        <a href="{{url('/gestao/roteiros/add')}}" class="btn btn-success w-100">
                            <i class="fas fa-plus"></i>
                            Novo Roteiro
                        </a>
                    </div>
                    <!--
                    <div class="col-md-3">
                        <a href="{{url('/gestao/trilhas/add')}}" class="btn btn-success w-100">
                            <i class="fas fa-upload"></i>
                            Importar Roteiro
                        </a>
                    </div>
                    -->
                </div>
                <h2 class="title-page">
                    <a @if(Request::input('id')!='') href="{{url()->previous()}}" @else href="{{url('')}}" @endif  class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    Roteiros
                </h2>
                @if(auth()->user()->permissao != 'Z')
                    <h4 style="margin-bottom: 18px;">

                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link @if(Request::input('biblioteca') == '' ) active @endif" href="javascript:$('#biblioteca').val('');mudaPesquisa()">Meus roteiros</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(Request::input('biblioteca') == 1 ) active @endif" href="javascript:$('#biblioteca').val(1);mudaPesquisa()">Roteiros da biblioteca</a>
                            </li>
                        </ul>
                    </h4>
                @endif
                <div class="list-grid-menu">
                    <form class="form-inline">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-secondary p-2 no-list-grid-btn active">
                                <input type="radio" name="options" id="option1" autocomplete="off" checked>
                                <i class="fas fa-th-large"></i>
                            </label>
                            <label class="btn btn-secondary list-grid-btn">
                                <input type="radio" name="options" id="option2" autocomplete="off">
                                <i class="fas fa-list"></i>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="row section-grid">
                    @if(count($dados)>0)
                        @foreach ($dados as $d)

                            <div class="col-md-6 grid-item">
                                @if(Request::input('biblioteca')!=1)
                                    <a href="{{url('/gestao/roteiros/'.$d->id.'/editar_conteudo')}}" class="wrap">
                                @else
                                    <a href="{{url('/gestao/roteiros/iniciarRoteiro/'.$d->id.'?biblioteca=1')}}" class="wrap">
                                @endif
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <div class="img">
                                                <img src="{{ $d->url_capa }}" />
                                                <button class="btn detalhes">@if(Request::input('biblioteca')!=1)Mais Detalhes @else Visão do Aluno @endif</button>
                                            </div>
                                            <div class="text mb-2">
                                                <p><h6 class="title font-weight-bold mt-2">{{ $d->titulo }}</h6> </p>
                                                <p>
                                                    {{ $d->disciplina }}
                                                </p>
                                                @if(Request::input('biblioteca')!=1)
                                                    @if($d->publicado==0)
                                                        <p><span id="badgeParcial" class="badge badge-danger" >Rascunho</span></p>
                                                    @else
                                                        <p><span id="badgeParcial" class="badge badge-success" >Publicado</span></p>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                            <div class="card-footer">
                                                @if(Request::input('biblioteca') == 1 )
                                                    <a href="{{url('/gestao/roteiros/duplicar/'.$d->id.'?biblioteca=1')}}" class="btn btn-secondary btn-sm " data-toggle="tooltip" data-placement="top" title="Adicionar aos meus roteiros">
                                                        <i class="fas fa-heart"></i>
                                                    </a>
                                                @else
                                                    <a href="{{url('/gestao/roteiros/duplicar/'.$d->id)}}" class="btn btn-secondary btn-sm " data-toggle="tooltip" data-placement="top" title="Duplicar">
                                                        <i class="fas fa-clone"></i>
                                                    </a>
                                                @endif
                                                @if(Request::input('biblioteca')!=1)
                                                    <a href="{{url('/gestao/roteiros/publicar/'.$d->id.'/?publicado='.$d->publicado)}}" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="@if($d->publicado == 1) Despublicar @else Publicar @endif"><i class="fas fa-bullhorn"></i></a>
                                                @endif
                                                @if(auth()->user()->permissao == 'Z' || $d->user_id == auth()->user()->id)
                                                    <a href="{{url('/gestao/roteiros/'.$d->id.'/editar')}}" class="btn btn-secondary btn-sm " data-toggle="tooltip" data-placement="top" title="Editar roteiro">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{url('/gestao/roteiros/'.$d->id.'/editar_conteudo')}}" class="btn btn-secondary btn-sm " data-toggle="tooltip" data-placement="top" title="Editar conteúdo">
                                                        <i class="fas fa-comments"></i>
                                                    </a>
                                                    <a href="javascript:" onclick="modalExcluir({{ $d->id }}, '{{ $d->titulo }}')" class="btn btn-secondary btn-sm " data-toggle="tooltip" data-placement="top" title="Excluir roteiro">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                @endif
                                            </div>

                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @elseif(Request::input('biblioteca') == '')
                        <div class="col">
                            <div class="card text-center">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado.</h5>
                                    <p class="card-text mb-2">
                                        Para visualizar Roteiros já prontos da Biblioteca da Opet INspira clique em <b>Roteiros da biblioteca</b>.
                                        <br>Para criar seus Roteiros autorais, clique no botão <b>Novo Roteiro</b>.
                                    </p>
                                </div>
                                <div class="card-footer text-muted"></div>
                            </div>
                        </div>
                    @elseif(Request::input('biblioteca') == '2')
                        <div class="col">
                            <div class="card text-center">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado.</h5>
                                    <p class="card-text mb-2">
                                        Para visualizar Roteiros já prontos da Biblioteca da Opet INspira clique em <b>Roteiros da biblioteca</b>.
                                        <br>Para criar seus Roteiros autorais, clique no botão <b>Novo Roteiro</b>.
                                    </p>
                                </div>
                                <div class="card-footer text-muted"></div>
                            </div>
                        </div>
                    @else
                        <div class="col">
                            <div class="card text-center">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado</h5>
                                    <p class="card-text ">Não foi encontrado resultado contendo todos os seus termos de pesquisa, clique no botão abaixo para reiniciar a pesquisa</p>
                                    <a class="btn btn-danger fs-13 mb-2" href="{{url('/gestao/trilhas/novo?biblioteca=1')}}" title="Excluir"><i class="fas fa-undo-alt"></i> Limpar Filtro</a>
                                </div>
                                <div class="card-footer text-muted"></div>
                            </div>
                        </div>
                    @endif
                </div>
                <nav class="mt-5" aria-label="Page navigation example">
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
                <h5 class="modal-title" id="exampleModalLabel">Exclusão de roteiro</h5>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="row">
                        <div class="col-12">
                            Você deseja mesmo excluir esse roteiro?<br><br>
                            <b id="titulo"></b>
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

<script type="text/javascript">

    $('.list-grid-btn').click(function() {
        $('.grid-item').addClass('list-grid');
    });

    $('.no-list-grid-btn').click(function() {
        $('.grid-item').removeClass('list-grid');
    });
    function mudaPesquisa()
    {
        $('#formPesquisa').submit();
    }
    var idExcluir = 0;

    function modalExcluir(id, nome)
    {
        idExcluir = id;
        $('#titulo').html(nome);
        $('#formExcluir').modal('show');
    }

    function excluir()
    {
        window.location.href = '{{url("/gestao/roteiros/excluir/")}}/'+idExcluir;
    }

</script>
@stop
