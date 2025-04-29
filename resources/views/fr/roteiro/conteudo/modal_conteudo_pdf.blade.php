<div class="col-12 elementosConteudo" id="elementoConteudoPdf" style="display: none">
    <fieldset class="border p-2 mb-3">
        <legend  class="float-none w-auto" style="font-size: 14px">* Arquivo de PDF ou Link</legend>
        <div class="form-group" id="FormGroupPdfFile">
            <label for="">Arquivo: </label>
            <input type="file" name="conteudo_15" id="pdfConteudo" class="form-control rounded {{ $errors->has('conteudo_15') ? 'is-invalid' : '' }}" accept=".pdf"/>
            <div id="msgErroConteudoFile_15" class="invalid-feedback">{{ $errors->first('conteudo_15') }}</div>
        </div>
        <div class="form-group" id="FormGroupPdfDownload">
            <label for="">Arquivo: </label>
            <b id="nomeArquivoPdf">arquivo de vídeo</b>  <a id="linkDownloadPdf" href="#" class="text-info ml-2"><i class="fas fa-download"></i></a> <a href="javascript:void(0)" onclick="excluirArquivo('FormGroupPdfDownload', 'FormGroupPdfFile')" class="text-info ml-2"><i class="fas fa-trash fa-fw"></i></a>
            <input type="hidden" id="conteudo_download_15" name="conteudo_download_15" value="{{old('conteudo_download_15')}}" />
        </div>
        <div class="form-group">
            <label for="">Link: </label>
            <input type="text" name="link_15" id="pdfLinkConteudo" class="form-control rounded {{ $errors->has('link_15') ? 'is-invalid' : '' }}" value="{{old('link_15')}}" />
            <div id="msgErroConteudoLink_15" class="invalid-feedback">{{ $errors->first('link_15') }}</div>
        </div>
    </fieldset>
</div>
