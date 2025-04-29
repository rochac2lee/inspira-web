<div class="modal fade "  id="modalTema" tabindex="-1" role="dialog" aria-labelledby="modalTema" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
            </div>
            <form action="{{url('/gestao/roteiros/add_update_conteudos')}}" method="post">
            <div class="modal-body">
                    @csrf
                    <input type="hidden" name="curso_id" value="{{$roteiro->id}}">
                    <input type="hidden" name="aula_id" id="aulaId" value="">
                    <input type="hidden" name="op" id="temaOp" value="{{old('op')}}">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>* Título:</label>
                                <input type="text" name="titulo" id="temaTitulo" value="{{old('titulo',@$dados->titulo)}}" class="form-control rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Descrição:</label>
                                <textarea name="descricao" id="temaDescricao" class="form-control rounded {{ $errors->has('descricao') ? 'is-invalid' : '' }}" rows="6">{{old('descricao',@$dados->descricao)}}</textarea>
                                <div class="invalid-feedback">{{ $errors->first('descricao') }}</div>
                            </div>
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
