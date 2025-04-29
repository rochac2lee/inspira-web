<div class="modal fade" id="formIncluirProfessor" tabindex="-1" role="dialog" aria-labelledby="formIncluirProfessor" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="tituloForm">Adicionar docente na turma</h5>
            </div>
            <div class="modal-body pb-0">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="filter">
                            <form class="form-inline d-flex justify-content-end">
                                <div class="input-group ml-1">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                    <input type="text" size="100%" id="buscaNome" value="" placeholder="Nome, e-mail ou código" class="form-control" />
                                </div>

                                <div class="input-group ml-1">
                                    <button type="button" onclick="buscarProfessor({{$escola->id}}, '')" class="btn btn-secondary btn-sm">Localizar</button>
                                </div>
                                <div class="input-group ml-1">
                                    <button type="button" onclick="buscarProfessor({{$escola->id}}, '', ''); $('#buscaNome').val('')" class="btn btn-secondary btn-sm">Limpar Filtros</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12" id="tabelaProfessores">

                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0" >
                <button type="button" class="btn btn-default" data-dismiss="modal">Finalizar</button>
            </div>
        </div>
    </div>
</div>
