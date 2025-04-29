<div class="col-12 elementosConteudo" id="elementoConteudoArquivo" style="display: none">
    <fieldset class="border p-2 mb-3">
        <legend  class="float-none w-auto" style="font-size: 14px">* Arquivo ou Link</legend>
        <div class="form-group" id="FormGroupArquivoFile">
            <label for="">Arquivo (tamanho máximo 50mb): </label>
            <input type="file" name="conteudo_6" id="arquivoConteudo" class="form-control rounded {{ $errors->has('conteudo_6') ? 'is-invalid' : '' }}" />
            <div id="msgErroConteudoFile_6" class="invalid-feedback">{{ $errors->first('conteudo_6') }}</div>
        </div>
        <div class="form-group" id="FormGroupArquivoDownload">
            <label for="">Arquivo (tamanho máximo 50mb): </label>
            <b id="nomeArquivoArquivo">arquivo de vídeo</b>  <a id="linkDownloadArquivo" href="" class="text-info ml-2"><i class="fas fa-download"></i></a> <a href="javascript:void(0)" onclick="excluirArquivo('FormGroupArquivoDownload', 'FormGroupArquivoFile')" class="text-info ml-2"><i class="fas fa-trash fa-fw"></i></a>
            <input type="hidden" id="conteudo_download_6" name="conteudo_download_6" value="{{old('conteudo_download_6')}}" />
        </div>
        <div class="form-group">
            <label for="">Link: </label>
            <input type="text" name="link_6" id="arquivoLinkConteudo" class="form-control rounded {{ $errors->has('link_6') ? 'is-invalid' : '' }}" value="{{old('link_6')}}" />
            <div id="msgErroConteudoLink_6" class="invalid-feedback">{{ $errors->first('link_6') }}</div>
        </div>
    </fieldset>
</div>
