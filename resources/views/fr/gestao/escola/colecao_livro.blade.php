@extends('fr/master')
@section('content')
    <script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet"></link>
    <section class="section section-interna">
        <div class="container">
            
            <h2 class="title-page">
                <a href="{{ url('gestao/escola/'.$escola->id.'/material')}}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> 
                </a>
                Coleções de Livros Digitais Permitidos<br><small>{{$escola->titulo}}</small>
            </h2>
                <form action="{{url('/gestao/escola/material/addColecaoLivro/')}}" method="post">
                    <div class="form-row">
                        <div class="col-md-2"></div>
                        @csrf
                        <input type="hidden" name="escola_id" value="{{$escola->id}}">
                        <div class="col-md-6">
                            <select name="colecao[]" class="selectColecao" style="border: 1px solid #ffb100; border-radius: 0.4rem;" multiple>
                                @foreach($colecaoParaAdd as $add)
                                    <option value="{{$add->id}}">{{$add->nome}} - {{$add->selo}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success">Adicionar coleção </button>
                        </div>
                    </div>
                </form>
            <br>
            @if(isset($dados) && count($dados)>0) 
                <div class="row section-grid colecoes">
                    @foreach($dados as $d)
                        <div class="col-md-6 grid-item">
                            <div class="card text-center">                          
                                <div class="card-body">
                                    <div class="img">
                                        <img class="img-fluid" src="{{ config('app.cdn').'/storage/colecaolivro/'.$d->img}}" />
                                    </div>                              
                                </div>
                                <div class="card-footer">  
                                    <strong class="title h6 font-weight-bold d-block">{{$d->nome}}</strong>
                                    <span class="text">{{$d->selo}}</span>
                                    
                                       <p> 
                                            <span id="badgeTodos" @if($d->todos != 1) style="display: none" @endif class="badge badge-success" > Todos Etapa / Ano</span>
                                            <span id="badgeParcial" @if($d->todos == 1) style="display: none" @endif class="badge badge-danger" >Parcial Etapa / Ano</span>
                                            <span id="badgeTodosPeriodos" @if($d->todos_periodos != 1) style="display: none" @endif class="badge badge-success" > Todos Bimestre / Semestre</span>
                                            <span id="badgeParcialPeriodos" @if($d->todos_periodos == 1) style="display: none" @endif class="badge badge-danger" >Parcial Bimestre / Semestre</span>
                                        </p>

                                    <a href="javascript:void(0)" onclick="$('#etapa{{$d->id}}').toggle()" alt="Gerenciar Etapa / Ano" title="Gerenciar Etapa / Ano" class="btn btn-secondary">
                                        <i class="fas fa-server"></i> Gerenciar Permissionamento
                                    </a> 
                                    <a href="javascript:void(0)" onclick="modalExcluir('{{$escola->id}}', '{{$d->id}}', '{{$d->nome}}')" alt="Remover Coleção da Escola" title="Remover Coleção da Escola" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Remover Coleção
                                    </a> 

                                    <div id="etapa{{$d->id}}" style="display: none;" class="text-left"> 
                                        <form action="{{url('/gestao/escola/material/permissaoColecaoLivro')}}" method="post" >
                                            <br><br>
                                            @csrf
                                            <input type="hidden" name="escola_id" value="{{$escola->id}}">
                                            <input type="hidden" name="colecao_id" value="{{$d->id}}">

                                            <div class="form-group">
                                                <p><b>Etapa / Ano</b></p>
                                                @foreach($d->cicloEtapaColecao as $c)
                                                    @php $checked = 'checked'; @endphp
                                                    @if($d->todos != 1)
                                                        @php $checked = ''; @endphp
                                                        @if($c->permissao != '')
                                                            @php $checked = 'checked'; @endphp
                                                        @endif
                                                    @endif
                                                    <div class="form-check">
                                                        <input class="form-check-input rounded" {{$checked}} name="cicloetapa[]" type="checkbox" value="{{$c->cicloetapa_id}}" id="ck{{$c->cicloetapa_id}}{{$d->id}}">
                                                        <label class="form-check-label" for="ck{{$c->cicloetapa_id}}{{$d->id}}">
                                                            {{$c->ciclo}} / {{$c->ciclo_etapa}}
                                                        </label>
                                                    </div>
                                                    <br>
                                                @endforeach
                                            </div>
                                            @if(count($d->periodoColecao)>0)
                                                <div class="form-group">
                                                    <p><b>Bimestre / Semestre</b></p>
                                                    @foreach($d->periodoColecao as $c)
                                                        @php $checked = 'checked'; @endphp
                                                        @if($d->todos_periodos != 1)
                                                            @php $checked = ''; @endphp
                                                            @if($c->permissao != '')
                                                                @php $checked = 'checked'; @endphp
                                                            @endif
                                                        @endif
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input rounded" {{$checked}} name="periodo[]" type="checkbox" value="{{$c->periodo}}" id="ckPeriodo{{$c->periodo}}{{$d->id}}">
                                                            <label class="form-check-label" for="ckPeriodo{{$c->periodo}}{{$d->id}}">
                                                                {{$c->periodo}}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <br>
                                            <a href="{{ url()->current() }}" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</a>
                                            <button id="btnForm" type="submit" class="btn btn-success">Salvar Permissões</button>
                                        </form>
                                    </div>
                                </div>    
                            </div>
                        </div>
                    @endforeach

                </div>
                    <nav class="mt-5" aria-label="Page navigation example">
                        {{ $dados->links() }}
                    </nav>
            @else
                <span style="color: orange"> <b>Nenhuma coleção adicionada.</b></span>
            @endif
        </div>
    </section>
    <!-- EXCLUIR -->
    <div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Remover Coleção</h5>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="row">
                        <div class="col-12">
                            Você deseja mesmo remover a coleção?<br><br>
                            <b class="nomeColecao"></b>
                            <p>{{$escola->titulo}}</p>
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
        var vescolaId = 0;
        var vcolecaoId = 0;

        function modalGerenciar(colecao)
        {
            $('.nomeColecao').html(colecao);
            $('#formGerenciar').modal('show');
        }

        function modalExcluir(escolaId, colecaoId, colecao)
        {
            vescolaId = escolaId;
            vcolecaoId = colecaoId;
            $('.nomeColecao').html(colecao);
            $('#formExcluir').modal('show');
        }

        function excluir()
        {
            window.location.href = '{{url('/gestao/escola')}}/' + vescolaId + '/material/removerColecaoLivro/' + vcolecaoId;
        }

        
        var selectEtapaAno = new SlimSelect({
                select: '.selectColecao',
                placeholder: 'Buscar',
                searchPlaceholder: 'Buscar',
                closeOnSelect: true,
                allowDeselectOption: true,
                selectByGroup: true,
            });

    </script>
@stop