@extends('fr/master')
@section('content')
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/jquery-datetimepicker/jquery.datetimepicker.min.css" type="text/css" charset="utf-8" />
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <script>
        $(document).ready(function() {
            jQuery.datetimepicker.setLocale('pt-BR');
            jQuery('#datetimepicker1').datetimepicker({
                format: 'd/m/Y'
            });
            jQuery('#datetimepicker2').datetimepicker({
                format: 'd/m/Y'
            });
        });

    </script>
    <section class="section section-interna">
        <div class="container">
            <div class="row mb-3" >
                <div class="col-md-12">
                    <div class="filter">
                        <form class="form-inline d-flex justify-content-end" autocomplete="off">
                            <div class="input-group ml-1">
                                <select class="form-control" name="tipo">
                                    <option value="" @if(Request::input('tipo')=='') selected @endif>Normal</option>
                                    <option value="1" @if(Request::input('tipo')=='1') selected @endif>Agrupado</option>
                                </select>
                            </div>
                            <div class="input-group ml-1">
                                <input class="form-control" name="data_inicial" value="{{Request::input('data_inicial')}}" type="text" id="datetimepicker1" placeholder="Data inicial" class="form-control form-control-sm rounded" />
                            </div>
                            <div class="input-group ml-1">
                                <input class="form-control" name="data_final" value="{{Request::input('data_final')}}" type="text" id="datetimepicker2" placeholder="Data final" class="form-control form-control-sm rounded" />
                            </div>
                            @if(auth()->user()->permissao == 'Z')
                                <div class="input-group ml-1">
                                    <select class="form-control" name="instituicaoTipo">
                                        <option value="">Tipo instituição</option>
                                        @foreach($instituicaoTipo as $i)
                                            <option value="{{$i->id}}" @if(Request::input('instituicaoTipo')==$i->id) selected @endif>{{$i->titulo}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="input-group ml-1">
                                <select class="form-control" name="permissao">
                                    <option value="">Permissão</option>
                                    <option value="A" @if(Request::input('permissao')== 'A') selected @endif>Aluno</option>
                                    <option value="P" @if(Request::input('permissao')== 'P') selected @endif>Docente</option>
                                </select>
                            </div>
                            <div class="input-group ml-1">
                                <select class="form-control" name="acessoPor">
                                    <option value="">Acesso por</option>
                                    <option value="t" @if(Request::input('acessoPor')== 't') selected @endif>Todos</option>
                                    <option value="w" @if(Request::input('acessoPor')== 'w') selected @endif>WEB</option>
                                    <option value="opet" @if(Request::input('acessoPor')== 'opet') selected @endif>App Opet</option>
                                    <option value="agenda" @if(Request::input('acessoPor')== 'agenda') selected @endif>App Agenda</option>
                                </select>
                            </div>
                            <div class="input-group ml-1">
                                <select class="form-control" name="ordenacao">
                                    <option value="">Ordenação</option>
                                    <option value="1" @if(Request::input('ordenacao')== '1') selected @endif >Instituição</option>
                                    <option value="2" @if(Request::input('ordenacao')== '2') selected @endif >Escola</option>
                                    <option value="3" @if(Request::input('ordenacao')== '3') selected @endif >Tipo de usuário</option>
                                    <option value="4" @if(Request::input('ordenacao')== '4') selected @endif >Nome de usuário</option>
                                </select>
                            </div>
                            <div class="input-group ml-1">
                                <button type="submit" class="btn btn-secondary">Localizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <h2 class="title-page">
                <!-- <a href="#" onclick="window.history.go(-1);" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> </a> -->
                <a href="{{ url('/gestao/relatorios')}}" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i></a>
                Relatório de acessos<br><small>@if(isset($escola)){{$escola->titulo}}@else Todas as escolas @endif</small>
            </h2>
            @if(count($dados )>0)
                @php
                    $idEscola='';
                        if(isset($escola)){
                            $idEscola = $escola->id;
                        }
                @endphp
                @if(auth()->user()->permissao == 'Z')

                    <div class="row mb-3">
                        <div class="col-12 text-right">
                            <a  href="{{url('/gestao/escola/acessos/download/a/'.str_replace(Request::url(), '', Request::fullUrl()).'&escola='.$idEscola)}}" class="btn btn-warning text-light mr-5 @if($dados->total()> 45000) disabled @endif" title="Relatório de acessos dos usuários" data-toggle="tooltip" data-placement="top">
                                <i class="fas fa-download"></i> Baixar arquivo de Log
                            </a>
                            @if($dados->total()> 45000)
                            <p class="text-danger text-right"><small> Reduza o intervalo de datas para realizar o download.</small></p>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
            <div class="row">
                <section class="table-page w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Usuário</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Permissão</th>
                                <th scope="col">Acesso por</th>
                                <th scope="col">Instituição</th>
                                <th scope="col">Escola</th>
                                @if(Request::input('tipo') !='1')
                                    <th scope="col">Data</th>
                                @else
                                    <th scope="col">Acessos</th>
                                @endif

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dados as $d)
                                <tr>
                                    <td>#{{$d->id}}</td>
                                    <td>{{$d->nome}}</td>
                                    <td>{{$d->email}}</td>
                                    <td>{{$d->permissaoOriginal}}</td>
                                    <td>
                                        @if($d->tipo_acesso == '')
                                            WEB
                                        @elseif($d->tipo_acesso == 'agenda')
                                            App Agenda
                                        @elseif($d->tipo_acesso == 'opet')
                                            App Opet
                                        @endif
                                    </td>
                                    <td>{{$d->inst}}</td>
                                    <td>{{$d->escola}}</td>
                                    @if(Request::input('tipo')!='1')
                                        <td>{{dataBR($d->data_logado)}}</td>
                                    @else
                                        <td>{{$d->qtd}}</td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(isset($dados) && count($dados)>0)
                            <nav class="mt-4" aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    {{$dados->withQueryString()->links()}}
                                </ul>
                            </nav>
                        @endif
                    </div>
                </section>
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
