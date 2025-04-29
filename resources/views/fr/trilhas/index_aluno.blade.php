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
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                    <input name="texto" type="text" value="{{Request::input('texto')}}" placeholder="Procurar Conteúdo" class="form-control" />
                                </div>
                                <div class="input-group ml-1">
                                    <a href="@isset($ead){{url('/ead/listar')}}@else{{url('trilhas/listar')}}@endisset" class="btn btn-secondary btn-sm">Limpar Filtros</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <h2 class="title-page">
                    @isset($ead)
                        <a href="{{ url('/')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Trilhas EAD
                    @else
                        <a href="{{ url('/')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Trilhas
                    @endisset
                </h2>
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
                            <div class="col-md-3 grid-item mb-4">

                                    <div class="card h-100 text-center">
                                        <div class="card-body">
                                            @isset($ead)
                                                @if(strpos($trilha->perfil_permissao_realizar,'-'.auth()->user()->permissao.'-')!==false)
                                                    @if(count($trilha->matriculados)==0)
                                                        <a href="{{url('ead/detalhes/'.$trilha->id)}}" class="wrap">
                                                    @else
                                                        <a href="{{url('ead/matriculado/'.$trilha->id.'/roteiro')}}"  class="wrap">
                                                    @endif
                                                @endif
                                            @else
                                                @if(count($trilha->matriculados)==0)
                                                    <a href="{{url('trilhas/detalhes/'.$trilha->id)}}" class="wrap">
                                                @else
                                                    <a href="{{url('trilhas/matriculado/'.$trilha->id.'/roteiro')}}"  class="wrap">
                                                @endif
                                            @endisset
                                                <div class="img mb-1">
                                                    <img src="{{  $trilha->url_capa }}" />
                                                    @isset($ead)
                                                        @if(strpos($trilha->perfil_permissao_realizar,'-'.auth()->user()->permissao.'-')!==false)
                                                            @if(count($trilha->matriculados)==0)
                                                                <button class="btn detalhes">Detalhes</button>
                                                            @else
                                                                @if(auth()->user()->permissao != 'A')
                                                                    <button class="btn detalhes">Continuar Curso </button>
                                                                @else
                                                                    <button class="btn detalhes">Continuar </button>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if(count($trilha->matriculados)==0)
                                                            <button class="btn detalhes">Detalhes</button>
                                                        @else
                                                            @if(auth()->user()->permissao != 'A')
                                                                <button class="btn detalhes">Continuar Curso </button>
                                                            @else
                                                                <button class="btn detalhes">Continuar </button>
                                                            @endif
                                                        @endif
                                                    @endisset
                                                </div>
                                                <div class="text fs-13">
                                                    <h6 class="title font-weight-bold mt-2">{{ $trilha->titulo }}</h6>
                                                    @isset($ead)
                                                        @if(strpos($trilha->perfil_permissao_realizar,'-'.auth()->user()->permissao.'-')!==false)
                                                            @if(count($trilha->matriculados)==0)
                                                                <p><span id="badgeParcial" class="badge badge-warning" >Não Matriculado</span></p>
                                                                @if($trilha->ead_matricula_inicio == '' || $trilha->ead_matricula_fim == '')
                                                                    <p>Período de matrícula não definido</p>
                                                                @else
                                                                    <p>Matrícula disponível <br>de @php echo dataBr($trilha->ead_matricula_inicio) @endphp à @php echo dataBr($trilha->ead_matricula_fim) @endphp</p>
                                                                @endif
                                                            @elseif(isset($estatistica[$trilha->id]) && $estatistica[$trilha->id]['perc']>=100)
                                                                <p><span id="badgeParcial" class="badge badge-success" >Concluído</span></p>
                                                            @else
                                                                <p><span id="badgeParcial" class="badge badge-info" >Em andamento</span></p>
                                                            @endif
                                                        @else
                                                            <p><span id="badgeParcial" class="badge badge-danger" >Não liberada para seu perfil</span></p>
                                                        @endif
                                                    @else
                                                        @if(count($trilha->matriculados)==0)
                                                            <p><span id="badgeParcial" class="badge badge-warning" >Não Iniciado</span></p>
                                                        @elseif(isset($estatistica[$trilha->id]) && $estatistica[$trilha->id]['perc']>=100)
                                                            <p><span id="badgeParcial" class="badge badge-success" >Concluído</span></p>
                                                        @else
                                                            <p><span id="badgeParcial" class="badge badge-info" >Em andamento</span></p>
                                                        @endif
                                                    @endisset

                                                    <strong>Autor:</strong><br> {{ ($trilha->autor != '') ? $trilha->autor: $trilha->user->name }}<br>
                                                    <br>{{ $trilha->disciplina }}
                                                </div>
                                            @isset($ead)
                                                @if(strpos($trilha->perfil_permissao_realizar,'-'.auth()->user()->permissao.'-')!==false)
                                                    </a>
                                                @endif
                                            @else
                                                </a>
                                            @endisset

                                        </div>
                                        @if(count($trilha->matriculados)!=0 && isset($estatistica[$trilha->id]))
                                            <div class="card-footer">
                                               <div class="fs-13 mb-2 text-left font-weight-bold">Progresso</div>
                                               <div class="progress" style="height: 20px;">
                                                   <div class="progress-bar" role="progressbar" style="width: {{$estatistica[$trilha->id]['perc']}}%;" aria-valuenow="{{@$estatistica[$trilha->id]['perc']}}" aria-valuemin="0" aria-valuemax="100">{{@$estatistica[$trilha->id]['perc']}}%</div>
                                               </div>
                                               <div class="fs-13 mt-2"><strong>{{@$estatistica[$trilha->id]['feito']}}</strong> de <strong>{{@$estatistica[$trilha->id]['total']}}</strong> aulas concluídos</div>
                                           </div>
                                        @endif
                                        @if(isset($ead) && (auth()->user()->permissao == 'Z' || auth()->user()->permissao == 'I') )
                                            <div class="card-footer">
                                                <p class="text-center" style="margin-bottom: 0px;"><a href="{{url('/ead/trilhas/'.$trilha->id.'/relatorio')}}"><i class="fas fa-file-invoice"></i> Relatório</a></p>
                                            </div>
                                        @endif
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

</script>
@stop
