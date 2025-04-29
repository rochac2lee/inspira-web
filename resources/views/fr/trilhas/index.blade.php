@extends('fr/master')
@section('content')
<section class="section section-interna">
            <div class="container">
                <div class="row mb-3" style="margin-top: -40px">
                    <div class="col-md-12">
                        <div class="filter">
                            <form method="get" id="formPesquisa" class="form-inline d-flex justify-content-end">
                                @if(auth()->user()->permissao == 'Z')
                                    <input type="hidden" name="ead" value="{{Request::input('ead')}}" id="ead">
                                @else
                                    <input type="hidden" name="biblioteca" value="{{Request::input('biblioteca')}}" id="biblioteca">
                                @endif
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
                                        <option @if( Request::input('exibicao') == 0 && Request::input('exibicao') != '') selected @endif  value="0">Rascunho</option>
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
                                    <a href="{{url('/gestao/trilhass')}}" class="btn btn-secondary btn-sm">Limpar Filtros</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center border-top  p-3 ">
                    <div class="col-md-3">
                        <a href="{{url('/gestao/trilhass/add')}}" class="btn btn-success w-100">
                            <i class="fas fa-plus"></i>
                            Nova Trilha
                        </a>
                    </div>
                </div>
                <h2 class="title-page">
                    <a @if(Request::input('id')!='') href="{{url()->previous()}}" @else href="{{url('')}}" @endif  class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    Trilhas
                </h2>
                @if(auth()->user()->permissao != 'Z')
                    <h4 style="margin-bottom: 18px;">

                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link @if(Request::input('biblioteca') == '' ) active @endif" href="javascript:$('#biblioteca').val('');mudaPesquisa()">Minhas trilhas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(Request::input('biblioteca') == 1 ) active @endif" href="javascript:$('#biblioteca').val(1);mudaPesquisa()">Trilhas da biblioteca</a>
                            </li>
                        </ul>
                    </h4>
                @else
                    <h4 style="margin-bottom: 18px;">

                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link @if(Request::input('ead') == '' ) active @endif" href="javascript:$('#ead').val('');mudaPesquisa()">Trilhas Regulares</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(Request::input('ead') == 1 ) active @endif" href="javascript:$('#ead').val(1);mudaPesquisa()">Trilhas EAD</a>
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
                    @if(count($trilhas)>0)
                        @foreach ($trilhas as $trilha)
                            <form id="formExcluirTrilha{{ $trilha->id }}"
                                action="{{ route('gestao.trilhas.destroy', ['idTrilha' => $trilha->id]) }}"
                                method="post">@csrf
                            </form>
                            <div class="col-md-3 grid-item pb-3">
                                    <div class="card h-100 text-center">
                                        <div class="card-body">
                                            <div class="img">
                                                <img src="{{$trilha->url_capa}}" />

                                            </div>
                                            <div class="text mb-2">
                                                <p><h6 class="title font-weight-bold mt-2">{{ $trilha->titulo }}</h6> </p>
                                                <p>
                                                    {{ $trilha->disciplina }}
                                                </p>
                                                @if(Request::input('biblioteca')!=1)
                                                    @if($trilha->publicado!=1)
                                                        <p><span id="badgeParcial" class="badge badge-danger" >Rascunho</span></p>
                                                    @else
                                                        <p><span id="badgeParcial" class="badge badge-success" >Publicado</span></p>
                                                        @if(auth()->user()->permissao == 'Z')
                                                            @if($trilha->ead == 1)
                                                                @if($trilha->ead_matricula_inicio == '' || $trilha->ead_matricula_fim == '')
                                                                    <p><span class="badge badge-warning" >Sem período de matrícula definido</span></p>
                                                                @else
                                                                    <p>Matrícula entre <br>{{dataBR($trilha->ead_matricula_inicio)}} - {{dataBR($trilha->ead_matricula_fim)}}</p>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                            <div class="card-footer">
                                                @if(Request::input('biblioteca') == 1 )
                                                    <a href="{{url('/gestao/trilhass/duplicar/'.$trilha->id.'?biblioteca=1')}}" class="btn btn-secondary btn-sm " data-toggle="tooltip" data-placement="top" title="Adicionar as minhas trilhas ">
                                                        <i class="fas fa-heart"></i>
                                                    </a>
                                                @else
                                                   @if(auth()->user()->escola_id == 958)
                                                    <a href="{{url('/gestao/trilhass/duplicar/'.$trilha->id)}}" class="btn btn-secondary btn-sm " data-toggle="tooltip" data-placement="top" title="Duplicar">
                                                        <i class="fas fa-clone"></i>
                                                    </a>
                                                   @endif
                                                @endif

                                                @if(Request::input('biblioteca')!=1)
                                                    <a href="{{url('/gestao/trilhass/publicar/'.$trilha->id.'/?publicado='.$trilha->publicado)}}" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="@if($trilha->publicado==1) Despublicar @else Publicar @endif"><i class="fas fa-bullhorn"></i></a>
                                                    @if(auth()->user()->permissao == 'Z')
                                                        @if(Request::input('ead')==1 && $trilha->publicado==1)
                                                            <a href="javascript:void(0)" onclick="matricular('{{$trilha->titulo}}', {{$trilha->id}})" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Período de matrícula"><i class="fas fa-bolt"></i></a>
                                                        @endif
                                                    @endif
                                                @endif
                                                
                                                @if( (auth()->user()->permissao == 'Z' || $trilha->user_id == auth()->user()->id)  && Request::input('biblioteca')!=1)
                                                <a href="{{url('/gestao/trilhass/'.$trilha->id.'/relatorio')}}" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Relatório"><i class="fas fa-file-invoice"></i></a>
                                                    <a href="{{url('/gestao/trilhass/'.$trilha->id.'/editar')}}" class="btn btn-secondary btn-sm " data-toggle="tooltip" data-placement="top" title="Editar trilha">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" onclick="modalExcluir({{ $trilha->id }}, '{{ $trilha->titulo }}')" class="btn btn-secondary btn-sm " data-toggle="tooltip" data-placement="top" title="Excluir trilha">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                @endif
                                            </div>

                                    </div>
                            </div>
                        @endforeach
                    @elseif(Request::input('biblioteca') == '')
                        <div class="col">
                            <div class="card text-center">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado.</h5>
                                    <p class="card-text mb-2">
                                        Para visualizar Trilhas já prontas da Biblioteca da Opet INspira clique em <b>Trilhas da biblioteca</b>.
                                        <br>Para criar suas Trilhas autorais, clique no botão <b>Nova Trilha</b>.
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
                                        Para visualizar Trilhas já prontas da Biblioteca da Opet INspira clique em <b>Trilhas da biblioteca</b>.
                                        <br>Para criar suas Trilhas autorais, clique no botão <b>Nova Trilha</b>.
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
                        {{ $trilhas->appends(Request::all())->links() }}
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
                <h5 class="modal-title" id="exampleModalLabel">Exclusão de Trilha</h5>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="row">
                        <div class="col-12">
                            Você deseja mesmo excluir essa trilha?<br><br>
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
@if(auth()->user()->permissao == 'Z')
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/jquery-datetimepicker/jquery.datetimepicker.min.css" type="text/css" charset="utf-8" />
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <!-- Modal MAtricula -->
    <div class="modal fade" id="modalMatricula" tabindex="-1" role="dialog" aria-labelledby="modalMatricula" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Definir período de matrícula</h5>
                </div>
                <form id="formMatricula" action="" method="post">
                    @csrf
                    <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <h6 id="tituloMatricula" class="text-center"></h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="font-weight-bold fs-12">* Data inicial da matrícula</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                            <input name="ead_matricula_inicial" readonly autocomplete="off" value="{{date('d/m/Y')}}" type="text" id="datetimepicker" placeholder="00/00/0000" class="form-control form-control-sm rounded" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="font-weight-bold fs-12">* Data final da matrícula</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <input name="ead_matricula_final" readonly autocomplete="off" value="{{date('d/m/Y')}}" type="text" id="datetimepicker2" placeholder="00/00/0000" class="form-control form-control-sm rounded" />
                                    </div>
                                </div>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit"  class="btn btn-success">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- FIM Matricula -->
    <script>
        $(document).ready(function(){
            jQuery.datetimepicker.setLocale('pt-BR');
            jQuery('#datetimepicker').datetimepicker({
                timepicker:false,
                format:'d/m/Y'
            });
            jQuery('#datetimepicker2').datetimepicker({
                timepicker:false,
                format:'d/m/Y'
            });
        })

        function matricular(trilhaTitulo, trilhaId){
            $.ajax({
                url: '{{url('/gestao/trilhass/get_periodo_matricula/')}}/' + trilhaId,
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    inicial = '{{date('d/m/Y')}}';
                    final = '{{date('d/m/Y')}}';
                    $('#datetimepicker').val(inicial);
                    $('#datetimepicker2').val(final);

                    if(data.ead_matricula_inicio != null)
                    {
                        $('#datetimepicker').val(data.ead_matricula_inicio)
                    }
                    if(data.ead_matricula_fim != null)
                    {
                        $('#datetimepicker2').val(data.ead_matricula_fim)
                    }
                    $('#tituloMatricula').html('<b>'+trilhaTitulo+'</b>')
                    url = '{{url('/gestao/trilhass/periodo_matricula')}}/'+trilhaId;
                    $('#formMatricula').prop('action',url)
                    $('#modalMatricula').modal('show');
                },
                error: function () {
                    swal("", "Não foi possível carregar o período de matrícula", "error");
                }
            });
        }
    </script>

@endif

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
    function modalExcluir(id, nome)
    {
        idExcluir = id;
        $('#titulo').html(nome);
        $('#formExcluir').modal('show');
    }

    function excluir()
    {
        window.location.href = '{{url("/gestao/trilhass/excluir/")}}/'+idExcluir;
    }

</script>
@stop
