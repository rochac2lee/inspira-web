<!-- PUBLICAR -->
<div class="modal fade" id="formPublicar" tabindex="-1" role="dialog" aria-labelledby="formPublicar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Publicar Autorização</h5>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="row">
                        <div class="col-12">
                            Você deseja mesmo publicar esse registro?<br><br>
                            <b id="nomeComuncadoPublicar"></b><br><br>
                            <p class="text-justify"><b >ATENÇÃO:</b> Ao publicar esse registro todos os usuários receberão imediatamente a comunicação e a ação NÃO poderá ser desfeita. </p>
                            <p><b >Tem certeza que deseja publicar?</b></p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                <button type="button" onclick="publicar()" class="btn btn-success">Sim, publicar</button>
            </div>
        </div>
    </div>
</div>
<!-- FIM PUBLICAR -->
