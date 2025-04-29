@extends('fr/master')
@section('content')
    <script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet">
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery/jquery-ui.js"></script>
    <section class="section section-interna" style="padding-top: 50px">
        <div class="container">
            @include('fr.agenda.menu')
            <h3 class="title-page mb-3 border-top pt-4">
                <a href="{{ url('/gestao/agenda/configuracoes')}}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                Rotinas da Agenda e Registro
            </h3>

            <div class="row">
                <div class="col-12">
                <h4 style="margin-bottom: 18px;">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == '1' ||  Request::input('tipo') == '') active @endif" href="{{url('/gestao/agenda/configuracoes/registros/rotinas/editar?tipo=1')}}">
                                Rotina 1
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == 2 ) active @endif" href="{{url('/gestao/agenda/configuracoes/registros/rotinas/editar?tipo=2')}}">
                                Rotina 2
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == 3 ) active @endif" href="{{url('/gestao/agenda/configuracoes/registros/rotinas/editar?tipo=3')}}">
                                Rotina 3
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == 4 ) active @endif" href="{{url('/gestao/agenda/configuracoes/registros/rotinas/editar?tipo=4')}}">
                                Rotina 4
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Request::input('tipo') == 5 ) active @endif" href="{{url('/gestao/agenda/configuracoes/registros/rotinas/editar?tipo=5')}}">
                                Rotina 5
                            </a>
                        </li>
                    </ul>
                </h4>
                </div>
            </div>
            <div class="row">
            @if(count($dados)>0)
                <div class="col-6 border-right">
                    <h4 class="pb-3 border-bottom mb-4">Rotinas</h4>
                    <h6 >Arraste as linhas para ordenar as rotinas.</h6>
                    <section class="table-page w-100">
                        <div class="table-responsive table-hover">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col" width="5%"></th>
                                    <th scope="col">Ícone</th>
                                    <th scope="col">Título</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody id="sortable">
                                    @foreach($dados as $d)
                                        <tr id="{{$d->id}}">
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input name="publicado" @if($d->ativo == 1) checked @endif  value="1" type="checkbox" class="custom-control-input" id="ativoSwitch{{$d->id}}" onchange="ativar({{$d->id}})">
                                                    <label class="custom-control-label pt-1" for="ativoSwitch{{$d->id}}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <img class="img-fluid" src="{{$d->linkImagem}}" width="50px">
                                            </td>
                                            <td>
                                                {{$d->titulo}}
                                            </td>
                                            <td class="text-right text-nowrap">
                                                <span><a href="{{url('/gestao/agenda/configuracoes/registros/rotinas/editar/form/'.$d->id)}}" class="btn btn-success btn-sm fs-13"  title="Editar"><i class="fas fa-pencil-alt"></i></a></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
                <div class="col-6">
                    <h4 class="pb-3 border-bottom mb-4">Turmas Alocadas</h4>

                    <form action="{{url('/gestao/agenda/configuracoes/registros/rotinas/addTurmas')}}" method="post">
                        @csrf
                        <input type="hidden" value="{{Request::input('tipo')}}" name="rotina_id">
                        <div class="form-row">
                            <div class="col-5 mb-3">
                                <label >Escola: </label>
                                <select class="selEscola" id="selEscola" name="escola_id" style="border: 1px solid #ffb100; border-radius: 0.4rem;" onchange="mudaEscola(this)">
                                        <option value="">Selecione</option>
                                    @foreach($escolas as $e)
                                        <option @if(old('escola_id') == $e->id) selected @endif value="{{$e->id}}">{{$e->titulo}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" style="display: block">{{ $errors->first('escola_id') }}</div>
                            </div>
                            <div class="col-5 mb-3">
                                <label >Turma: </label>
                                <select multiple class=" selTurma" id="selTurma" name="turma_id[]" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
                                    <option value="">Selecione uma escola</option>
                                </select>
                                <div class="invalid-feedback" style="display: block">{{ $errors->first('turma_id') }}</div>
                            </div>
                            <div class="col-2 mb-3 text-right">
                                <button type="submit" class="btn btn-success btn-sm mt-4"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </form>
                    @if(count($turmas) > 0)
                    <section class="table-page w-100">
                        <div class="table-responsive table-hover">
                            <table class="table table-striped">
                                <tbody id="sortable">
                                @foreach($turmas as $d)
                                    <tr>
                                        <td>
                                            {{$d->ciclo}} / {{ $d->ciclo_etapa}}
                                        </td>
                                        <td>
                                            {{$d->titulo}} / {{$d->turno}}
                                        </td>
                                        <td class="text-right text-nowrap">
                                            <span><a href="{{url('/gestao/agenda/configuracoes/registros/rotinas/removerTurma/'.$d->id)}}" class="btn btn-danger btn-sm fs-13"  title="Remover"><i class="fas fa-trash-alt"></i></a></span>
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
                                    <p class="card-text ">É necessário alocar turmas nas rotinas.</p>
                                </div>
                                <div class="card-footer text-muted"></div>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="col">
                    <div class="card text-center">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado</h5>
                            <p class="card-text ">Você não possui rotinas liberadas para uso.</p>
                        </div>
                        <div class="card-footer text-muted"></div>
                    </div>
                </div>
            @endif
            </div>
        </div>
    </section>

<script>
    var selectTurma = null;
    $(document).ready(function(){
        var selectEscola = new SlimSelect({
            select: '.selEscola',
            placeholder: 'Buscar',
            searchPlaceholder: 'Buscar',
            closeOnSelect: true,
            allowDeselectOption: true,
            selectByGroup: true,
        });

        selectTurma = new SlimSelect({
            select: '.selTurma',
            placeholder: 'Selecione',
            searchPlaceholder: 'Buscar',
            closeOnSelect: true,
            allowDeselectOption: true,
            selectByGroup: true,
        });

        @if(old('escola_id') != '')
            mudaEscola($('#selEscola'));
        @endif

    })

    function mudaEscola(elemento){
        escola = $(elemento).val();
        $.ajax({
            url: '{{url('/gestao/agenda/configuracoes/registros/rotinas/getTurmas')}}',
            type: 'post',
            dataType: 'json',
            data: {
                escola_id: escola,
                _token: '{{csrf_token()}}'
            },
            success: function(data) {
                selectTurma.setData(data);
                //selectTurma.placeh
            }
        });
    }

    function ativar(rotinaId){
        ativo = 0;
        if($('#ativoSwitch'+rotinaId).prop('checked')){
            ativo = 1;
        }
        $.ajax({
            url: '{{url('/gestao/agenda/configuracoes/registros/rotinas/ativar')}}',
            type: 'post',
            dataType: 'json',
            data: {
                id: rotinaId,
                ativo: ativo,
                _token: '{{csrf_token()}}'
            },

        });
    }

    $(function() {
        $("#sortable").sortable({
            update: function() {
                var sort = $(this).sortable("toArray");
                $.ajax({
                    url: '{{url('/gestao/agenda/configuracoes/registros/rotinas/ordem')}}',
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
