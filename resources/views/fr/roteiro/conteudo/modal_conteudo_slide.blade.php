<div class="col-12 elementosConteudo" id="elementoConteudoSlide" style="display: none">
    <fieldset class="border p-2 mb-3">
        <legend  class="float-none w-auto" style="font-size: 14px">* Arquivo de slide ou Link</legend>
        <div class="form-group" id="FormGroupSlideFile">
            <label for="">Arquivo (tamanho máximo 10mb): </label>
            <input type="file" name="conteudo_4" id="slideConteudo" class="form-control rounded {{ $errors->has('conteudo_4') ? 'is-invalid' : '' }}" accept="slide/*"/>
            <div id="msgErroConteudoFile_4" class="invalid-feedback">{{ $errors->first('conteudo_4') }}</div>
        </div>
        <div class="form-group" id="FormGroupSlideDownload">
            <label for="">Arquivo (tamanho máximo 10mb): </label>
            <b id="nomeArquivoSlide">arquivo de vídeo</b>  <a id="linkDownloadSlide" href="#" class="text-info ml-2"><i class="fas fa-download"></i></a> <a href="javascript:void(0)" onclick="excluirArquivo('FormGroupSlideDownload', 'FormGroupSlideFile')" class="text-info ml-2"><i class="fas fa-trash fa-fw"></i></a>
            <input type="hidden" id="conteudo_download_4" name="conteudo_download_4" value="{{old('conteudo_download_4')}}" />
        </div>
        <div class="form-group">
            <label for="">Link: </label>
            <input type="text" name="link_4" id="slideLinkConteudo" class="form-control rounded {{ $errors->has('link_4') ? 'is-invalid' : '' }}" value="{{old('link_4')}}" />
            <div id="msgErroConteudoLink_4" class="invalid-feedback">{{ $errors->first('link_4') }}</div>
        </div>
    </fieldset>
</div>
