<!--<div class="col-6">
    <div class="custom-control custom-switch">
        <input name="obrigatorio" id="conteudoObrigatorio" @if(old('obrigatorio') == '1') checked @endif  value="1" type="checkbox" class="custom-control-input" >
        <label class="custom-control-label pt-1" for="conteudoObrigatorio">Obrigatório</label>
        <small class="form-text w-100 text-muted">
            explicar o obrigatório.
        </small>
    </div>
</div>
--->
<div class="col-12">
    <div class="form-group">
        <label>* Título:</label>
        <input type="text" name="titulo" id="conteudoTitulo" value="{{old('titulo')}}" class="form-control rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
        <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
    </div>
</div>
<div class="col-12">
    <div class="form-group">
        <label>Descrição:</label>
        <textarea name="descricao" id="conteudoDescricao" class="form-control rounded {{ $errors->has('descricao') ? 'is-invalid' : '' }}" rows="4">{{old('descricao')}}</textarea>
        <div class="invalid-feedback">{{ $errors->first('descricao') }}</div>
    </div>
</div>
<div class="col-6">
    <div class="form-group">
        <label>Etapa / Ano:</label>
        <select name="ciclo_etapa_id" class="multipleEtapa" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
            <option value="">Selecione</option>
            @foreach($ciclo as $c)
                <option @if( $c->ciclo_id.';'.$c->id == old('ciclo_etapa_id',@$dados->ciclo_id.';'.@$dados->cicloetapa_id)) selected @endif value="{{$c->ciclo_id.';'.$c->id}}">{{$c->ciclo}} - {{$c->ciclo_etapa}}</option>
            @endforeach
        </select>
        <div id="msgErroCicloEtapa" class="invalid-feedback @if($errors->first('ciclo_etapa_id'))d-block @endif">{{ $errors->first('ciclo_etapa_id') }}</div>
    </div>
</div>
<div class="col-6">
    <div class="form-group">
        <label>Componente curricular:</label>
        <select name="disciplina_id" class="multipleComponente" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
            <option value="">Selecione</option>
            @foreach($disciplinas as $d)
                <option @if( $d->id == old('disciplina_id',@$dados->disciplina_id)) selected @endif value="{{$d->id}}">{{$d->titulo}}</option>
            @endforeach
        </select>
        <div id="msgErroDisciplina" class="invalid-feedback @if($errors->first('disciplina_id'))d-block @endif">{{ $errors->first('disciplina_id') }}</div>
    </div>
</div>
