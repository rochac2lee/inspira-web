<style>
     .container-conteudos{overflow:hidden;overflow-y:scroll;height:300px;padding-right:30px} .container-conteudos::-webkit-scrollbar-track{border-radius:10px;background-color:#f5f5f5} .container-conteudos::-webkit-scrollbar{width:8px;background-color:#fff} .container-conteudos::-webkit-scrollbar-thumb{border-radius:32px;background-color:#c5c5c5} .container-conteudos .row{padding:8px 16px;background:#f5f5f5;margin-top:12px;border-radius:4px;-webkit-border-radius:4px;-moz-border-radius:4px} .container-conteudos .row:nth-child(2n){background:#f5f5f5;border:2px solid #f5f5f5} .container-conteudos .row:nth-child(odd){background:#eee;border:2px solid #eee} .container-conteudos .row .col .icon, .container-conteudos .row .col .title-author{float:left;text-align:left;word-break:break-word} .container-conteudos .row .col .icon{color:#959595;margin-right:15px} .container-conteudos .row .col .title-author h6{float:left;color:#000;margin-bottom:0} .container-conteudos .row .col .title-author span{display:block;float:left;clear:both;color:#000} .container-conteudos .item.row{position:relative;cursor:pointer} .container-conteudos .item.row input[type=checkbox]{visibility:visible;position:absolute;width:100%;height:100%;background:red;top:0;left:0;z-index:999;opacity:0;cursor:pointer} .container-conteudos .item.row.selected, .container-conteudos .item.row:hover{border:2px solid #93bee8} .container-conteudos .item.row.selected .col .title-author h6, .container-conteudos .item.row.selected .col .title-author span, .container-conteudos .item.row.selected i{color:#1a77d4} .container-conteudos .item.row.not-found.selected, .container-conteudos .item.row.not-found:hover{border:2px solid #f5f5f5} .container-conteudos .item.row.not-found.selected .col .title-author h6, .container-conteudos .item.row.not-found.selected .col .title-author span, .container-conteudos .item.row.not-found.selected i{color:#000}
</style>
<div class="modal fade "  id="modalConteudoBiblioteca" tabindex="-1" role="dialog" aria-labelledby="modalConteudoBiblioteca" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalConteudo">Conteúdo da Biblioteca</h5>
            </div>
            <form action="{{url('/gestao/roteiros/addConteudoBiblioteca')}}" method="post" enctype="multipart/form-data">
            <div class="modal-body">
                @csrf
                <input type="hidden" name="curso_id" value="{{$roteiro->id}}">
                <input type="hidden" name="aula_id" id="conteudoAulaIdBiblioteca" value="{{old('aula_id')}}">
                <input type="hidden" name="tipo"  value="B">
                <div class="row">
                    <div class="col-pesquisa col-12 col-sm-12 col-md-8">
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" name="pesquisa" id="txtPesquisa" placeholder="Digite o título ou autor do conteúdo" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-tipo col-12 col-sm-12 col-md-4">
                        <select name="tipoBusca" id="txtTipo" class="custom-select form-control">
                            <option value="">Selecione um tipo</option>
                            <option value="2">Áudio</option>
                            <option value="3">Vídeo</option>
                            <option value="4">Apresentação</option>
                            <option value="8">Quiz/Roteiro</option>
                            <option value="21">Livro Digital</option>
                        </select>

                        <p class="text-secondary" style="padding-top: 2px">* campo obrigatótio</p>
                    </div>
                    <div class="col-tipo col-12">
                        <select name="txtColecaoLivro" style="display: none" id="txtColecaoLivro" class="custom-select form-control">
                            <option>Selecione uma coleção de livro</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="container-conteudos col-12 col-sm-12 col-md-12">
                        <h6 class="text-center"><i class="fas fa-exclamation-triangle"></i> Selecione um tipo de conteúdo</h6>
                        <div class="invalid-feedback @if($errors->first('conteudosIds'))d-block @endif">{{ $errors->first('conteudosIds') }}</div>
                    </div>
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

<script>

    $('.container-conteudos .item.row').on('click', function() {
        $(this).toggleClass('selected');
    });

    function clicaConteudoBiblioteca(elemento) {
        $(elemento).toggleClass('selected');
    };

    $('#txtColecaoLivro').change(function(){
        mudaColecao();
    });

    function mudaColecao(){
        colecao =  $('#txtColecaoLivro').val();
        idTipoConteudo = $('#txtTipo').val();
        url = '{{url('/biblioteca/conteudosAjax')}}';
        if(idTipoConteudo == 21 ){
            url = '{{url('/biblioteca/conteudosAjax')}}';
        }
        dados = {idTipo: idTipoConteudo, _token:'{{ csrf_token() }}', colecao:colecao};
        if(idTipoConteudo == 3)
        {
            dados = {idTipo: idTipoConteudo, _token:'{{ csrf_token() }}', componente:colecao};
        }
        $.ajax({
            url: url,
            type: 'post',
            data: dados,
            success: function (data) {
                $('.container-conteudos').html(data);
            }
        });
    }

    $('#txtTipo').change(function(){
        idTipoConteudo = $(this).val();
        if(idTipoConteudo == 21 || idTipoConteudo == 2 || idTipoConteudo == 3 || idTipoConteudo == 4)
        {
            url = '{{url('biblioteca/colecaoAjax')}}';
            if(idTipoConteudo == 21 ){
                url = '{{url('/colecao_livro/colecoesLivroAjaxOption')}}';
            }

            $('.container-conteudos').html('Nenhuma coleção de livro selecionada.');
            $.ajax({
                url: url,
                type: 'post',
                data: {_token:'{{ csrf_token() }}', idTipo:idTipoConteudo},
                success: function (data) {
                    $('#txtColecaoLivro').html(data);
                    mudaColecao();
                }
            });

            $('#txtColecaoLivro').show();
        }
        else
        {
            $('#txtColecaoLivro').hide();
        }

        if(idTipoConteudo != 21 && idTipoConteudo != 2 && idTipoConteudo != 3 && idTipoConteudo != 4){
            $.ajax({
                url: '{{url('/biblioteca/conteudosAjax')}}',
                type: 'post',
                data: {idTipo: $(this).val(), _token:'{{ csrf_token() }}'},
                success: function (data) {
                    $('.container-conteudos').html(data);
                }
            });
        }

    });

    $('#txtPesquisa').keyup(function(){
        var mySearch = $(this).val();
        var myTipoSelected = $('#txtTipo').val();

        $('.container-conteudos .item').hide();

        /*if(myTipoSelected == '') {
            $('.container-conteudos .item:icontains("'+mySearch+'")').not('.not-found').show();
        } else {*/
        $('.container-conteudos .item:icontains("'+mySearch+'")').not('.not-found').show();
        //}

        if($('.container-conteudos .item').is(':visible')) {
            $('.container-conteudos .item.row.not-found').hide();
        } else {
            $('.container-conteudos .item.row.not-found').show();
        }
    });

    $('#modalConteudoBiblioteca').on('hide.bs.modal', function () {
        $('#modalConteudoBiblioteca .container-conteudos input:checkbox').removeAttr('checked');
        $('#modalConteudoBiblioteca .container-conteudos .item').removeClass('selected');
        $('#txtColecaoLivro').hide();
        $("#txtTipo").val($("#txtTipo option:first").val());
        $('#txtPesquisa').val('');
        $('.container-conteudos').html('');
    });
    $(document).ready(function(){
        jQuery.expr[':'].icontains = function(a, i, m) {
        return jQuery(a).text().toUpperCase()
        .indexOf(m[3].toUpperCase()) >= 0;
    };
    });
</script>
