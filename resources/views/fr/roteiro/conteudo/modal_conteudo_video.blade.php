<div class="col-12 elementosConteudo" id="elementoConteudoVideo" style="display: none">
    <fieldset class="border p-2 mb-3">
        <legend  class="float-none w-auto" style="font-size: 14px">* Arquivo de vídeo ou Link</legend>
        <div class="form-group" id="FormGroupVideoFile">
            <label for="">Arquivo  (tamanho máximo 10mb): </label>
            <input type="file" name="conteudo_3" id="videoConteudo" class="form-control rounded {{ $errors->has('conteudo_3') ? 'is-invalid' : '' }}" accept="video/*"/>
            <div id="msgErroConteudoFile_3" class="invalid-feedback">{{ $errors->first('conteudo_3') }}</div>
        </div>
        <div class="form-group" id="FormGroupVideoDownload">
            <label for="">Arquivo  (tamanho máximo 10mb): </label>
            <b id="nomeArquivoVideo">arquivo de vídeo</b>  <a id="linkDownloadVideo" href="#" class="text-info ml-2"><i class="fas fa-download"></i></a> <a href="javascript:void(0)" onclick="excluirArquivo('FormGroupVideoDownload', 'FormGroupVideoFile')" class="text-info ml-2"><i class="fas fa-trash fa-fw"></i></a>
            <input type="hidden" id="conteudo_download_3" name="conteudo_download_3" value="{{old('conteudo_download_3')}}" />
        </div>
        <div class="form-group">
            <label for="">Link: </label>
            <input type="text" name="link_3" id="videoLinkConteudo" class="form-control rounded {{ $errors->has('link_3') ? 'is-invalid' : '' }}" value="{{old('link_3')}}" />
            <div id="msgErroConteudoLink_3" class="invalid-feedback">{{ $errors->first('link_3') }}</div>
        </div>
    </fieldset>
</div>
