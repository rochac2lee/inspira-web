<!-- EXCLUIR -->
<div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalExcluir"></h5>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="row">
                        <div class="col-12">
                            Você deseja mesmo excluir esse registro?<br><br>
                            <b id="elementoModalExcluir"></b><br>
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
