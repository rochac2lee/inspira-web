<div class="col-12">
    <div class="form-group">
        <label>Palavra-chave:</label>
        <input type="text" name="apoio" id="conteudoApoio" value="{{old('apoio')}}" class="form-control rounded {{ $errors->has('apoio') ? 'is-invalid' : '' }}">
        <div class="invalid-feedback">{{ $errors->first('apoio') }}</div>
        <small class="form-text w-100 text-muted">
            As palavras-chave devem ser separadas por hífen " - ".
        </small>
    </div>
</div>
<div class="col-12">
    <div class="form-group">
        <label>Fonte do conteúdo:</label>
        <input type="text" name="fonte" id="conteudoFonte" value="{{old('fonte')}}" class="form-control rounded {{ $errors->has('fonte') ? 'is-invalid' : '' }}">
        <div class="invalid-feedback">{{ $errors->first('fonte') }}</div>
    </div>
</div>
<div class="col-12">
    <div class="form-group">
        <label>Autores do conteúdo:</label>
        <input type="text" name="autores" id="conteudoAutores" value="{{old('autores')}}" class="form-control rounded {{ $errors->has('autores') ? 'is-invalid' : '' }}">
        <div class="invalid-feedback">{{ $errors->first('autores') }}</div>
    </div>
</div>
