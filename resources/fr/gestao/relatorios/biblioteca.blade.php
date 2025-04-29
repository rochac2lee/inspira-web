@extends('fr/master')
@section('content')
<link rel="stylesheet" href="https://cf.opetinspira.com.br/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.css">
<script src="https://cf.opetinspira.com.br/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.js"></script>
<link rel="stylesheet" href="{{ asset('fr/includes/relatorios/css/style_relatorios.css?v=001') }}">
<section class="section section-interna relatorios">
    <div class="container">
        <div class="row mb-3" style="margin-top: -40px">
            <a href="{{ url('/gestao/relatorios')}}" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i></a>
            <div class="col-md-12 text-center">
                <h3>Relatório de Biblioteca</h3>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Filtros do Relatório</div>
                    <div class="card-body">
                        <div class="filter">
                            <form class="">
                                 <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                  <div class="input-group-text">
                                                      <i class="fas fa-search"></i>
                                                  </div>
                                            </div>
                                            <input type="text" placeholder="Nome ou E-mail" class="form-control fs-13" />
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <div class="input-group">
                                            <select class="form-control fs-13">
                                                <option>Normal</option>
                                                <option>Agrupado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <div class="input-group">
                                            <input class="form-control fs-13" name="data_inicial" value="" type="text" id="datetimepicker1" placeholder="Data inicial" aria-label="data inicial">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <div class="input-group">
                                            <input class="form-control fs-13" name="data_final" value="" type="text" id="datetimepicker2" placeholder="Data final" aria-label="data final" data-uw-rm-form="fx">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 mb-0">
                                        <div class="input-group">
                                            <select class="form-control fs-13" name="permissao" aria-label="select" data-uw-rm-form="fx">
                                                <option value="">Permissão</option>
                                                <option value="A">Aluno</option>
                                                <option value="P">Docente</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <div class="input-group ml-1">
                                            <select id="instituicaoTipo" name="instituicaoTipo" multiple="" size="1">
                                                <option value="1">Privado</option>
                                                <option value="2">Público</option>
                                                <option value="3">Licitação</option>
                                                <option value="4">E-Commerce</option>
                                                <option value="5">Tutorial</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2 mb-0">
                                        <div class="input-group">
                                            <select id="acessoPor" name="acessoPor" multiple="" size="1">
                                                <option value="t">Todos</option>
                                                <option value="w">WEB</option>
                                                <option value="opet">App Opet</option>
                                                <option value="agenda">App Agenda</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 mb-0">
                                        <div class="input-group">
                                            <select class="form-control fs-13" name="ordenacao" aria-label="select" data-uw-rm-form="fx">
                                                <option value="">Ordenação</option>
                                                <option value="1">Instituição</option>
                                                <option value="2">Escola</option>
                                                <option value="3">Tipo de usuário</option>
                                                <option value="4">Nome de usuário</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <button type="submit" class="btn btn-info fs-13">Filtrar</button>
                                        <button class="btn btn-danger fs-13" title="Limpar Filtro"><i class="fas fa-undo-alt"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="subtitle-page mt-0">Resultados</div>

        <div class="row">
            <section class="table-page w-100">


                <!-- Inicio Sem Resultado -->
                <div class="col">
                    <div class="card text-center">
                      <div class="card-header"></div>
                      <div class="card-body">
                          <h5 class="card-title"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado</h5>
                        <p class="card-text">Não foi encontrado resultado contendo todos os seus termos de pesquisa, clique no botão abaixo para reiniciar a pesquisa</p>
                        <a class="btn btn-danger fs-13" href="#" title="Limpar Filtro"><i class="fas fa-undo-alt"></i> Limpar Filtro</a>
                      </div>
                      <div class="card-footer text-muted"></div>
                    </div>
                </div>
                <!-- Fim Sem Resultado -->
            </section>
        </div>
    </div>
</section>
<!-- Data Picker -->
<link rel="stylesheet" href="https://cf.opetinspira.com.br/fr/includes/js/jquery-datetimepicker/jquery.datetimepicker.min.css" type="text/css" charset="utf-8" />
<script src="https://cf.opetinspira.com.br/fr/includes/js/jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
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

<!-- MultiSelect Vanilla -->
<script type="text/javascript">
    selectBoxTest = new vanillaSelectBox("#instituicaoTipo", {
            "maxHeight": 250,
            "search": false ,
            "placeHolder": "Tipo de Instituição",
            "borda":"#ffb100",
        });

    selectBoxTest2 = new vanillaSelectBox("#acessoPor", {
            "maxHeight": 250,
            "search": false ,
            "placeHolder": "Acesso por",
            "borda":"#ffb100",
        });
</script>
@stop
