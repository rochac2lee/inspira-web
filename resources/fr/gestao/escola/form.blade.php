<div class="modal fade" id="formIncluir" tabindex="-1" role="dialog" aria-labelledby="formIncluir" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title" id="tituloForm">Cadastro de Escola</h5>
            </div>
            <div class="modal-body">
                <form id="formFormulario" action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="idEscola" value="{{old('id')}}">
                    <input type="hidden" name="idUserAdm" id="idUserAdm" value="{{old('idUserAdm')}}">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Instituição *</label>
                                <select class="multipleInst {{ $errors->has('instituicao_id') ? 'is-invalid' : '' }}" name="instituicao_id" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
                                    <option value="">Selecione</option>
                                    @foreach($inst as $i)
                                        <option @if($i->id == old('instituicao_id')) selected @endif value="{{$i->id}}">{{$i->titulo}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">{{ $errors->first('instituicao_id') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Nome da Escola *</label>
                                <input type="text" name="titulo" placeholder="" value="{{old('titulo')}}" class="form-control rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Contrato</label>
                                <input type="text" name="numero_contrato" value="{{old('numero_contrato')}}" placeholder="Número do contrato" class="form-control rounded {{ $errors->has('numero_contrato') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('numero_contrato') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>CNPJ</label>
                                <input type="text" name="cnpj" id="cnpj" value="{{old('cnpj')}}" placeholder="" class="form-control rounded {{ $errors->has('cnpj') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('cnpj') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Logo da Escola</label>
                                <div id="logoCadastro" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                    <input type="hidden" name="existeImg" id="existeImg" value="{{old('existeImg')}}">
                                    <img id="imgLogo" width="328px" src="@if(old('existeImg')!= ''){{ config('app.cdn').'/storage/logo_escola/'.old('existeImg')}}@endif">
                                    <br>
                                    <a class="btn btn-secondary" onclick="excluirLogo()">Excluir Logo</a>
                                </div>
                                <div id="novaLogo" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                    <input type="file" name="imagem" class="myCropper">
                                </div>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Etapa / Ano *</label>
                                        <select class="multipleEtapa {{ $errors->has('etapa_ano') ? 'is-invalid' : '' }}" name="etapa_ano[]" multiple style="border: 1px solid #ffb100; border-radius: 0.4rem;">
                                            @foreach($cicloEtapa as $c)
                                                <option @if(is_array(old('etapa_ano')) && in_array($c->id, old('etapa_ano'))) selected @endif value="{{$c->id}}">{{$c->ciclo}} - {{$c->ciclo_etapa}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback @if($errors->first('etapa_ano'))d-block @endif">{{ $errors->first('etapa_ano') }}</div>
                                    </div>
                                </div>


                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Cor da Plataforma</label>
                                        <div class="input-group input-colorpicker" title="Using input value">
                                            <input type="text" name="cor_primaria" class="form-control input-lg {{ $errors->has('cor_primaria') ? 'is-invalid' : '' }}" value="#ff8040" required/>
                                            <span class="input-group-append">
												<span class="input-group-text colorpicker-input-addon"><i></i></span>
											</span>
                                            <div class="invalid-feedback">{{ $errors->first('cor_primaria') }}</div>

                                        </div>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Personalização da URL</label>
                                        <div class="input-group">
                                            <input type="text" name="url" value="{{old('url')}}" placeholder="" class="form-control rounded {{ $errors->has('url') ? 'is-invalid' : '' }}">
                                            <div class="invalid-feedback">{{ $errors->first('url') }}</div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Facebook</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fab fa-facebook-square"></i></div>
                                            </div>
                                            <input type="text" name="facebook" value="{{old('facebook')}}" placeholder="" class="form-control rounded {{ $errors->has('facebook') ? 'is-invalid' : '' }}">
                                            <div class="invalid-feedback">{{ $errors->first('facebook') }}</div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Instagram</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fab fa-instagram"></i></div>
                                            </div>
                                            <input type="text" name="instagram" value="{{old('instagram')}}" placeholder="" class="form-control rounded {{ $errors->has('instagram') ? 'is-invalid' : '' }}">
                                            <div class="invalid-feedback">{{ $errors->first('instagram') }}</div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Youtube</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fab fa-youtube-square"></i></div>
                                            </div>
                                            <input type="text" name="youtube" value="{{old('youtube')}}" placeholder="" class="form-control rounded {{ $errors->has('youtube') ? 'is-invalid' : '' }}">
                                            <div class="invalid-feedback">{{ $errors->first('youtube') }}</div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3 mb-4 border-bottom "><label class="font-weight-bold">ENDEREÇO</label></div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>CEP</label>
                                <input type="text" name="cep" id="cep" value="{{old('cep')}}" class="form-control rounded {{ $errors->has('cep') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('cep') }}</div>

                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>UF</label>
                                <input type="text" name="uf" id="uf" value="{{old('uf')}}" placeholder="" class="form-control rounded {{ $errors->has('uf') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('uf') }}</div>

                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Cidade</label>
                                <input type="text" name="cidade" value="{{old('cidade')}}" id="cidade" placeholder="" class="form-control rounded {{ $errors->has('cidade') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('cidade') }}</div>

                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Bairro</label>
                                <input type="text" name="bairro" value="{{old('bairro')}}" id="bairro" placeholder="" class="form-control rounded {{ $errors->has('bairro') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('bairro') }}</div>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Endereço</label>
                                <input type="text" name="logradouro" value="{{old('logradouro')}}" id="logradouro" placeholder="" class="form-control rounded {{ $errors->has('logradouro') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('logradouro') }}</div>

                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Número</label>
                                <input type="text" name="numero" value="{{old('numero')}}" id="numero" placeholder="" class="form-control rounded {{ $errors->has('numero') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('numero') }}</div>

                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Complemento</label>
                                <input type="text" name="complemento" value="{{old('complemento')}}" placeholder="" class="form-control rounded {{ $errors->has('complemento') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('complemento') }}</div>

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
                        <div class="col-6">
                            <div class="form-group">
                                <label>Nome do Responsável *</label>
                                <input type="text" id="adm_escola" name="adm_escola" value="{{old('adm_escola')}}" placeholder="" class="form-control rounded {{ $errors->has('adm_escola') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('adm_escola') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>E-mail *</label>
                                <input type="text" id="adm_email" name="adm_email" value="{{old('adm_email')}}" placeholder="" class="form-control rounded {{ $errors->has('adm_email') ? 'is-invalid' : '' }}">
                                <div class="invalid-feedback">{{ $errors->first('adm_email') }}</div>
                            </div>
                        </div>
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
