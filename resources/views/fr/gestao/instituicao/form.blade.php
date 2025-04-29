<!-- INCLIUR -->
<div class="modal fade" id="formIncluir" tabindex="-1" role="dialog" aria-labelledby="formIncluir" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-header">
            <h5 id="tituloForm" class="modal-title"></h5>
        </div>
        <div class="modal-body">
            <form id="formFormulario" action="xxxxxx" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="idInstituicao" value="{{old('id')}}">
                <input type="hidden" name="idUserAdm" id="idUserAdm" value="{{old('idUserAdm')}}">
                <div class="form-group">
                    <label class="font-weight-bold">Nome da Instituição</label>
                    <input type="text" name="titulo" id="tituloInstituicao" placeholder="Nome da Instituição" class="form-control p-4 rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}" value="{{old('titulo')}}">
                    <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Tipo da Instituição</label>
                    <select name="instituicao_tipo_id" id="instituicaoTipoId" class="form-control rounded {{ $errors->has('instituicao_tipo_id') ? 'is-invalid' : '' }}" value="{{old('instituicao_tipo_id')}}">
                            <option value="">Selecione</option>
                        @foreach($tipo as $t){
                            <option @if($t->id==old('instituicao_tipo_id'))selected @endif value="{{$t->id}}">{{$t->titulo}}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">{{ $errors->first('instituicao_tipo_id') }}</div>

                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Descrição da Instituição</label>
                    <textarea name="descricao" id="descricaoInstituicao" class="form-control {{ $errors->has('descricao') ? 'is-invalid' : '' }}" rows="2">{{old('descricao')}}</textarea>
                    <div class="invalid-feedback">{{ $errors->first('descricao') }}</div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Logo da Instituição</label>
                            <div id="logoCadastro" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                <input type="hidden" name="existeImg" id="existeImg" value="{{old('existeImg')}}">
                                <img id="imgLogo" width="214px" src="{{ config('app.cdn').'/storage/logo_instituicao/'.old('existeImg')}}">
                                <br>
                                <a class="btn btn-secondary" onclick="excluirLogo()">Excluir Logo</a>
                            </div>
                            <div id="novaLogo" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                <input type="file" name="arquivo" class="myCropper">
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Cor Primária</label>
                            <div class="input-group input-colorpicker" title="Using input value">
                                <input type="text" name="cor_primaria" id="cor_primaria" class="form-control input-lg {{ $errors->has('cor_primaria') ? 'is-invalid' : '' }}" value="{{old('cor_primaria','#ff8040')}}"/>
                                <span class="input-group-append">
                                    <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                </span>
                            </div>
                            <div class="invalid-feedback">{{ $errors->first('cor_primaria') }}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>Cor Secundária</label>
                            <div class="input-group input-colorpicker" title="Using input value">
                                <input type="text" name="cor_secundaria" id="cor_secundaria" class="form-control input-lg {{ $errors->has('cor_secundaria') ? 'is-invalid' : '' }}" value="{{old('cor_secundaria','#c25022')}}"/>
                                <span class="input-group-append">
                                    <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                </span>
                            </div>
                            <div class="invalid-feedback">{{ $errors->first('cor_secundaria') }}</div>
                        </div>
                    </div>

                </div>
                <div class="col-12 mt-3 mb-4 border-bottom "><label class="font-weight-bold">ADMINISTRADOR</label></div>
                <div id="editarADM" class="col-12">
                    <div class="form-group">
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="radio_adm" value="editar" onclick="editarADM()">Editar administrador
                          </label>
                        </div>

                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="radio_adm" value="novo" onclick="novoADM()">Novo administrador
                          </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Nome do administrador da instituição</label>
                    <input type="text" name="adm_inst" id="adm_inst" placeholder="Nome do administrador da instituição" class="form-control p-4 rounded {{ $errors->has('adm_inst') ? 'is-invalid' : '' }}" value="{{old('adm_inst')}}">
                    <div class="invalid-feedback">{{ $errors->first('adm_inst') }}</div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">E-mail do administrador da instituição</label>
                    <input type="text" name="adm_email" id="adm_email" placeholder="E-mail do administrador da instituição" class="form-control p-4 rounded {{ $errors->has('adm_email') ? 'is-invalid' : '' }}" value="{{old('adm_email')}}">
                    <div class="invalid-feedback">{{ $errors->first('adm_email') }}</div>
                </div>
                
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button id="btnForm" type="button" class="btn btn-success" onclick="enviaFormulario()">Cadastrar</button>
        </div>
        </div>
    </div>
</div>
<!-- FIM INCLUIR -->