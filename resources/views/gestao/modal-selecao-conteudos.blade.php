<div class="modal fade" id="modalSelecaoConteudos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md px-1 px-md-5" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="">
                    <h5 class="text-primary text-center mb-4 title-modal">Biblioteca</h5>

                    <form id="formSelecaoConteudos" method="POST" action="{{ route('gestao.curso.aula-selecao-conteudos', ['idCurso' => $curso->id]) }}" enctype="multipart/form-data" class="text-center px-3 shadow-none border-0">

                        @csrf

                        @if(\Request::is('gestao/curso/*'))
                        <input name="idAulaB" value="" required hidden>
                        @endif

                        @if(\Request::is('gestao/cursoslivres/*'))
                        <input name="idAulaB" value="" required hidden>
                        @endif

                        <div class="row">
                            <div class="col-pesquisa col-12 col-sm-12 col-md-8">
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control" name="pesquisa" id="txtPesquisa" placeholder="Digite o título ou autor do conteúdo">
                                    <span class="btn-search fa-stack fa-1x mr-3 search-icon-background">
                                        <!--<i class="fas fa-circle fa-stack-2x"></i>-->
                                        <i class="fas fa-search fa-stack-1x search-icon"></i>
                                    </span>                                    
                                </div>                                
                            </div>
                            <div class="col-tipo col-12 col-sm-12 col-md-4">
                                <select name="tipo" id="txtTipo" class="custom-select form-control">
                                    <option value="">Selecione um tipo</option>
                                    <option value="1">Texto</option>
                                    <option value="2">Áudio</option>
                                    <option value="3">Vídeo</option>
                                    <option value="4">Apresentação</option>
                                    <!--<option value="5">Transmissão</option>-->ß
                                    <option value="6">Arquivo</option>
                                    <option value="7">Dissertativa</option>
                                    <option value="8">Quiz</option>
                                    <!--<option value="9">Avaliação</option>-->
                                    <!--<option value="10">Entregável</option>-->
                                    <!--<option value="11">Apostila</option>-->
                                    <!--<option value="12">Descubra a Palavra</option>
                                    <option value="13">Verdadeiro Falso</option>
                                    <option value="15">PDF</option>-->
                                    <option value="21">Livro Digital</option>
                                </select>
                            </div>
                            <div class="col-tipo col-12">
                                <select name="txtColecaoLivro" style="display: none" id="txtColecaoLivro" class="custom-select form-control">
                                    <option>Selecione uma coleção de livro</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="container-conteudos col-12 col-sm-12 col-md-12">

                                <div class="item row not-found">
                                    <div class="col col-md-12 d-flex align-items-center">
                                        <div class="icon">
                                            <i class="fas fa-exclamation-triangle"></i>                                        
                                        </div>
                                        <div class="title-author">
                                            <h6>Selecione um tipo de conteúdo</h6>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row mt-4">
                            <button type="button" data-dismiss="modal"
                                    class="btn btn-cancelar mb-0 col-4 ml-auto mr-4">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="btn btn-primary mb-0 col-4 ml-4 mr-auto">
                                Salvar
                            </button>
                        </div>                        

                    </form>
                </div>    

            </div>
        </div>
    </div>
</div>