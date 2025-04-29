<div class="tab-pane fade " id="dados" role="tabpanel" aria-labelledby="v-pills-profile-tab">

    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
        <li class="nav-item mx-auto">
            <a class="nav-link active" id="dados_pessoais-tab" data-toggle="tab"
               href="#dados_pessoais"
               role="tab" aria-controls="dados_pessoais" aria-selected="true">Dados
                Pessoais</a>
        </li>
        <li class="nav-item mx-auto">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#endereco"
               role="tab" aria-controls="profile" aria-selected="false">Endereço</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">

        <div class="tab-pane fade show active" id="dados_pessoais" role="tabpanel"
             aria-labelledby="dados_pessoais-tab">
            <form id="formTrocarDados" action="{{ route('configuracao.salvar-dados') }}"
                  method="post">
                @csrf

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                        <span
                            class="input-group-text bg-lightgray border-0 rounded-0"
                            id="basic-addon1">
                            Nome
                        </span>
                    </div>
                    <input type="name" name="name" value="{{ $user->name }}"
                           class="form-control bg-lightgray border-0 rounded-0 px-5 py-3"
                           placeholder="Nome" required
                           onchange="alterouDadosUsuario()">
                </div>

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-lightgray border-0 rounded-0" id="basic-addon1">
                            Nome Completo
                        </span>
                    </div>
                    <input type="name" name="nome_completo" value="{{ $user->nome_completo }}" class="form-control bg-lightgray border-0 rounded-0" placeholder="Clique aqui para digitar..." required onchange="alterouDadosUsuario()">
                </div>

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-lightgray border-0 rounded-0" id="basic-addon1">
                            CPF
                        </span>
                    </div>
                    {{-- <input type="text" name="cpf" value="{{ $user->cpf }}" class="form-control bg-lightgray border-0 rounded-0" placeholder="Clique aqui para digitar..." required onchange="alterouDadosUsuario()"> --}}
                    <div class="form-control bg-lightgray border-0 rounded-0">
                        {{ ucfirst($user->cpf) }}
                    </div>
                </div>

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-lightgray border-0 rounded-0" id="basic-addon1">
                            RG
                        </span>
                    </div>
                    {{-- <input type="text" name="rg" value="{{ $user->rg }}" class="form-control bg-lightgray border-0 rounded-0" placeholder="Clique aqui para digitar..." required onchange="alterouDadosUsuario()"> --}}
                    <div class="form-control bg-lightgray border-0 rounded-0">
                        {{ ucfirst($user->rg) }}
                    </div>
                </div>

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-lightgray border-0 rounded-0" id="basic-addon1">
                            Telefone
                        </span>
                    </div>
                    <input type="text" name="telefone" value="{{ $user->telefone }}" maxlength="16" class="form-control bg-lightgray border-0 rounded-0 telefone" placeholder="Clique aqui para digitar..." required onchange="alterouDadosUsuario()">
                    {{--  <div class="form-control bg-lightgray border-0 rounded-0">
                        {{ ucfirst($user->rg) }}
                    </div>  --}}
                </div>

                <div class="row">

                    <div class="col-lg-6 col-md-6 col-xs-12" style="display:none">
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-lightgray border-0 rounded-0"
                                      id="basic-addon1">
                                    Sexo
                                </span>
                            </div>
                            <select readonly name="genero" id="cmbGenero"
                                    onchange="alterouDadosUsuario();"
                                    class="form-control custom-select false-input border-0 bg-green-darker2 d-inline-block text-green-lighter px-5 py-3"
                                    style="width: auto;">
                                <option
                                    value="Feminino" {{ strtolower($user->genero) == "feminino" ? 'selected=true' : '' }}>
                                    Feminino
                                </option>
                                <option
                                    value="Masculino" {{ strtolower($user->genero) == "masculino" ? 'selected=true' : '' }}>
                                    Masculino
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <span
                                    class="input-group-text bg-lightgray border-0 rounded-0"
                                    id="basic-addon1">
                                    Data de Nasc.
                                </span>
                            </div>
                            <input type="text" name="data_nascimento" id="data_nascimento"
                                   onchange="alterouDadosUsuario();"
                                   value="{{ date('d/m/Y', strtotime(ucfirst($user->data_nascimento))) }}"
                                   class="datepicker form-control bg-lightgray border-0 rounded-0 px-5 py-3 date"
                                   placeholder="Data de Nascimento">
                        </div>
                    </div>

                </div>

                {{--<div class="input-group mb-4">--}}
                {{--<div class="input-group-prepend">--}}
                {{--<span class="input-group-text bg-lightgray border-0 rounded-0"--}}
                {{--id="basic-addon1">--}}
                {{--Permissao--}}
                {{--</span>--}}
                {{--</div>--}}
                {{--<div class="form-control bg-lightgray border-0 rounded-0">--}}
                {{--{{ \App\User::PermissaoName($user->permissao) }}--}}
                {{--</div>--}}
                {{--</div>--}}


                {{--<div class="input-group mb-4">--}}
                {{--<div class="input-group-prepend">--}}
                {{--<span class="input-group-text bg-lightgray border-0 rounded-0"--}}
                {{--id="basic-addon1">--}}
                {{--Escola--}}
                {{--</span>--}}
                {{--</div>--}}
                {{--<div class="form-control bg-lightgray border-0 rounded-0">--}}
                {{--{{ ucfirst($user->escola->titulo) }}--}}
                {{--</div>--}}
                {{--</div>--}}


                {{--<div class="input-group mb-4">--}}
                {{--<div class="input-group-prepend">--}}
                {{--<span class="input-group-text bg-lightgray border-0 rounded-0"--}}
                {{--id="basic-addon1">--}}
                {{--Biografia--}}
                {{--</span>--}}
                {{--</div>--}}
                {{--<textarea name="descricao" id="" rows="10"--}}
                {{--class="form-control bg-lightgray border-0 rounded-0"--}}
                {{--placeholder="Clique aqui para digitar."--}}
                {{--onchange="alterouDadosUsuario()">{{ $user->descricao }}</textarea>--}}
                {{--</div>--}}

                <button type="button" onclick="salvarAlteracoesDados();"
                        id="btnSalvarAlteracoesDados"
                        class="btn bg-primary border-0  text-white font-weight-bold btn-block px-5 py-3 d-none">
                    Salvar alterações
                </button>

            </form>
        </div>

        <div class="tab-pane fade" id="endereco" role="tabpanel"
             aria-labelledby="endereco-tab">

            <form id="formTrocarDados" action="{{ route('configuracao.salvar-dados') }}"
                  method="post">
                @csrf


                <input type="hidden" name="has_endereco_user" value="1">

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                                                    <span
                                                        class="input-group-text bg-lightgray border-0 rounded-0"
                                                        id="basic-addon1">
                                                        CEP
                                                    </span>
                    </div>
                    <input type="text" name="cep" id="cep" value="{{ $user->endereco->cep }}"
                           class="form-control bg-lightgray border-0 rounded-0 px-5 py-3 cep"
                           placeholder="CEP" required
                           onchange="alterouDadosUsuario()">
                </div>

                <div class="row">

                    <div class="col-12 col-md-4 col-lg-6">
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                                        <span class="input-group-text bg-lightgray border-0 rounded-0"
                                                                id="basic-addon1">
                                                            UF
                                                        </span>
                            </div>
                            <input type="text" name="uf" id="uf" value="{{ $user->endereco->uf }}"
                                    class="form-control bg-lightgray border-0 rounded-0 px-5 py-3 w-75"
                                    placeholder="UF" readonly required
                                    onchange="alterouDadosUsuario()">
                            <!-- <select readonly name="uf" id="uf" required
                                    onchange="alterouDadosUsuario();"
                                    class="form-control custom-select false-input border-0 bg-green-darker2 d-inline-block text-green-lighter px-5 py-3 w-75"
                                    style="width: auto;">
                                    <option value="">UF</option>
                                    <option value="AC" {{ $user->endereco->uf == "AC" ? 'selected' : '' }}>Acre</option>
                                    <option value="AL" {{ $user->endereco->uf == "AL" ? 'selected' : '' }}>Alagoas</option>
                                    <option value="AP" {{ $user->endereco->uf == "AP" ? 'selected' : '' }}>Amapá</option>
                                    <option value="AM" {{ $user->endereco->uf == "AM" ? 'selected' : '' }}>Amazonas</option>
                                    <option value="BA" {{ $user->endereco->uf == "BA" ? 'selected' : '' }}>Bahia</option>
                                    <option value="CE" {{ $user->endereco->uf == "CE" ? 'selected' : '' }}>Ceará</option>
                                    <option value="DF" {{ $user->endereco->uf == "DF" ? 'selected' : '' }}>Distrito Federal</option>
                                    <option value="ES" {{ $user->endereco->uf == "ES" ? 'selected' : '' }}>Espírito Santo</option>
                                    <option value="GO" {{ $user->endereco->uf == "GO" ? 'selected' : '' }}>Goiás</option>
                                    <option value="MA" {{ $user->endereco->uf == "MA" ? 'selected' : '' }}>Maranhão</option>
                                    <option value="MT" {{ $user->endereco->uf == "MT" ? 'selected' : '' }}>Mato Grosso</option>
                                    <option value="MS" {{ $user->endereco->uf == "MS" ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                    <option value="MG" {{ $user->endereco->uf == "MG" ? 'selected' : '' }}>Minas Gerais</option>
                                    <option value="PA" {{ $user->endereco->uf == "PA" ? 'selected' : '' }}>Pará</option>
                                    <option value="PB" {{ $user->endereco->uf == "PB" ? 'selected' : '' }}>Paraíba</option>
                                    <option value="PR" {{ $user->endereco->uf == "PR" ? 'selected' : '' }}>Paraná</option>
                                    <option value="PE" {{ $user->endereco->uf == "PE" ? 'selected' : '' }}>Pernambuco</option>
                                    <option value="PI" {{ $user->endereco->uf == "PI" ? 'selected' : '' }}>Piauí</option>
                                    <option value="RJ" {{ $user->endereco->uf == "RJ" ? 'selected' : '' }}>Rio de Janeiro</option>
                                    <option value="RN" {{ $user->endereco->uf == "RN" ? 'selected' : '' }}>Rio Grande do Norte</option>
                                    <option value="RS" {{ $user->endereco->uf == "RS" ? 'selected' : '' }}>Rio Grande do Sul</option>
                                    <option value="RO" {{ $user->endereco->uf == "RO" ? 'selected' : '' }}>Rondônia</option>
                                    <option value="RR" {{ $user->endereco->uf == "RR" ? 'selected' : '' }}>Roraima</option>
                                    <option value="SC" {{ $user->endereco->uf == "SC" ? 'selected' : '' }}>Santa Catarina</option>
                                    <option value="SP" {{ $user->endereco->uf == "SP" ? 'selected' : '' }}>São Paulo</option>
                                    <option value="SE" {{ $user->endereco->uf == "SE" ? 'selected' : '' }}>Sergipe</option>
                                    <option value="TO" {{ $user->endereco->uf == "TO" ? 'selected' : '' }}>Tocantins</option>
                                    <option value="EX" {{ $user->endereco->uf == "EX" ? 'selected' : '' }}>Estrangeiro</option>
                            </select> -->
                        </div>
                    </div>

                    <div class="col-12 col-md-4 col-lg-6">
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                                    <span
                                                        class="input-group-text bg-lightgray border-0 rounded-0"
                                                        id="basic-addon1">
                                                        Cidade
                                                    </span>
                            </div>
                            <input type="text" name="cidade" id="cmbCidade" value="{{ $user->endereco->cidade }}"
                                    class="form-control bg-lightgray border-0 rounded-0 px-5 py-3 w-75"
                                    placeholder="cidade" readonly required
                                    onchange="alterouDadosUsuario()">
                                    <!-- <select name="cidade" id="cmbCidade" onchange="alterouDadosUsuario();" class="form-control custom-select false-input border-0 bg-green-darker2 d-inline-block text-green-lighter" name="" id="" style="width: auto;">
                                                    <option value="" >Selecione sua cidade</option>
                                                </select> -->
                        </div>
                    </div>

                </div>

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                                                    <span
                                                        class="input-group-text bg-lightgray border-0 rounded-0"
                                                        id="basic-addon1">
                                                        Bairro
                                                    </span>
                    </div>
                    <input type="text" name="bairro" id="bairro" value="{{ $user->endereco->bairro }}"
                            class="form-control bg-lightgray border-0 rounded-0 px-5 py-3"
                            placeholder="Bairro" readonly required
                            onchange="alterouDadosUsuario()">
                </div>

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                                                    <span
                                                        class="input-group-text bg-lightgray border-0 rounded-0"
                                                        id="basic-addon1">
                                                        Logradouro
                                                    </span>
                    </div>
                    <input type="text" name="logradouro" id="logradouro" maxlength="255" value="{{ $user->endereco->logradouro }}"
                           class="form-control bg-lightgray border-0 rounded-0 px-5 py-3"
                           placeholder="Logradouro" required
                           onchange="alterouDadosUsuario()">
                </div>

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                                                    <span
                                                        class="input-group-text bg-lightgray border-0 rounded-0"
                                                        id="basic-addon1">
                                                        Número
                                                    </span>
                    </div>
                    <input type="text" name="numero" maxlength="20" value="{{ $user->endereco->numero }}"
                           class="form-control bg-lightgray border-0 rounded-0 px-5 py-3"
                           placeholder="Número" required
                           onchange="alterouDadosUsuario()">
                </div>

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                                                    <span
                                                        class="input-group-text bg-lightgray border-0 rounded-0"
                                                        id="basic-addon1">
                                                        Complemento
                                                    </span>
                    </div>
                    <input type="text" name="complemento" maxlength="255" value="{{ $user->endereco->complemento }}"
                           class="form-control bg-lightgray border-0 rounded-0 px-5 py-3"
                           placeholder="Complemento"
                           onchange="alterouDadosUsuario()">
                </div>

                {{--<div class="input-group mb-4">--}}
                {{--<div class="input-group-prepend">--}}
                {{--<span class="input-group-text bg-lightgray border-0 rounded-0"--}}
                {{--id="basic-addon1">--}}
                {{--Permissao--}}
                {{--</span>--}}
                {{--</div>--}}
                {{--<div class="form-control bg-lightgray border-0 rounded-0">--}}
                {{--{{ \App\User::PermissaoName($user->permissao) }}--}}
                {{--</div>--}}
                {{--</div>--}}


                {{--<div class="input-group mb-4">--}}
                {{--<div class="input-group-prepend">--}}
                {{--<span class="input-group-text bg-lightgray border-0 rounded-0"--}}
                {{--id="basic-addon1">--}}
                {{--Escola--}}
                {{--</span>--}}
                {{--</div>--}}
                {{--<div class="form-control bg-lightgray border-0 rounded-0">--}}
                {{--{{ ucfirst($user->escola->titulo) }}--}}
                {{--</div>--}}
                {{--</div>--}}


                {{--<div class="input-group mb-4">--}}
                {{--<div class="input-group-prepend">--}}
                {{--<span class="input-group-text bg-lightgray border-0 rounded-0"--}}
                {{--id="basic-addon1">--}}
                {{--Biografia--}}
                {{--</span>--}}
                {{--</div>--}}
                {{--<textarea name="descricao" id="" rows="10"--}}
                {{--class="form-control bg-lightgray border-0 rounded-0"--}}
                {{--placeholder="Clique aqui para digitar."--}}
                {{--onchange="alterouDadosUsuario()">{{ $user->descricao }}</textarea>--}}
                {{--</div>--}}

                <button type="submit"
                        id="btnSalvarAlteracoesDados"
                        class="btn bg-primary border-0  text-white font-weight-bold btn-block px-5 py-3 d-none">
                    Salvar alterações
                </button>

            </form>

        </div>
    </div>


</div>
