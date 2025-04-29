<div class="modal fade "  id="modalConteudo" tabindex="-1" role="dialog" aria-labelledby="modalConteudo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalConteudo"></h5>
            </div>
            <form action="{{url('/gestao/roteiros/addConteudo')}}" method="post" enctype="multipart/form-data">
            <div class="modal-body">
                    @csrf
                    <input type="hidden" name="curso_id" value="{{$roteiro->id}}">
                    <input type="hidden" name="aula_id" id="conteudoAulaId" value="{{old('aula_id')}}">
                    <input type="hidden" name="conteudo_id" id="conteudoConteudoId" value="{{old('conteudo_id')}}">
                    <input type="hidden" name="tipo" id="conteudoAulaTipo" value="{{old('tipo')}}">
                    <input type="hidden" name="op" id="conteudoOp" value="{{old('op')}}">
                    <input type="hidden" value="" name="existe_arquivo" id="existeArquivo">
                    <input id="inputNomeArquivo" type="hidden" name="nome_arquivo" value="{{old('nome_arquivo')}}">
                    <div class="row">
                        @include('fr.roteiro.conteudo.modal_conteudo_header')
                            @include('fr.roteiro.conteudo.modal_conteudo_texto')
                            @include('fr.roteiro.conteudo.modal_conteudo_audio')
                            @include('fr.roteiro.conteudo.modal_conteudo_video')
                            @include('fr.roteiro.conteudo.modal_conteudo_slide')
                            @include('fr.roteiro.conteudo.modal_conteudo_pdf')
                            @include('fr.roteiro.conteudo.modal_conteudo_arquivo')
                            @include('fr.roteiro.conteudo.modal_conteudo_quiz')
                        @include('fr.roteiro.conteudo.modal_conteudo_footer')
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
            </form>
        </div>
    </div>
</div>
