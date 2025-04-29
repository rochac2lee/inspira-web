<div class="col-12 elementosConteudo" id="elementoConteudoTexto" style="display: none">
    <div class="form-group">
        <label for="">* Conteúdo: </label>
        <textarea rows="6" name="conteudo_1" id="froalaConteudo" class="form-control rounded">{{old('conteudo_1')}}</textarea>
        <div id="msgErroConteudo_1" class="invalid-feedback @if($errors->first('conteudo_1'))d-block @endif">{{ $errors->first('conteudo_1') }}</div>
    </div>
</div>
