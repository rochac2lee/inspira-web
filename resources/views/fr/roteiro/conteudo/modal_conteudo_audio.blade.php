<div class="col-12 elementosConteudo" id="elementoConteudoAudio" style="display: none">
    <fieldset class="border p-2 mb-3">
        <legend  class="float-none w-auto" style="font-size: 14px">* Arquivo de áudio ou Link</legend>
        <div class="form-group" id="FormGroupAudioFile">
            <label for="">Arquivo (tamanho máximo 10mb):</label>
            <input type="file" name="conteudo_2" id="audioConteudo" class="form-control rounded {{ $errors->has('conteudo_2') ? 'is-invalid' : '' }}" accept=".mp3,audio/*"/>
            <div id="msgErroConteudoFile_2" class="invalid-feedback">{{ $errors->first('conteudo_2') }}</div>
        </div>
        <div class="form-group" id="FormGroupAudioDownload">
            <label for="">Arquivo (tamanho máximo 10mb): </label>
            <b id="nomeArquivoAudio">arquivo de audio</b>  <a id="linkDownloadAudio" href="#" class="text-info ml-2"><i class="fas fa-download"></i></a> <a href="javascript:void(0)" onclick="excluirArquivo('FormGroupAudioDownload', 'FormGroupAudioFile')" class="text-info ml-2"><i class="fas fa-trash fa-fw"></i></a>
            <input type="hidden" id="conteudo_download_2" name="conteudo_download_2" value="{{old('conteudo_download_2')}}" />
        </div>
        <div class="form-group">
            <label for="">Link: </label>
            <input type="text" name="link_2" id="audioLinkConteudo" class="form-control rounded {{ $errors->has('link_2') ? 'is-invalid' : '' }}" value="{{old('link_2')}}" />
            <div id="msgErroConteudoLink_2" class="invalid-feedback">{{ $errors->first('link_2') }}</div>
        </div>
    </fieldset>
</div>
