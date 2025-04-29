<div class="tab-pane fade show active" id="conta" role="tabpanel"
     aria-labelledby="v-pills-home-tab">

    <h5 class="mb-4 font-weight-bold">
        Trocar endereço de e-mail
    </h5>

    <form id="formTrocarEmail" action="{{ route('configuracao.trocar-email') }}"
        method="post" class="mb-5">
        @csrf

        <div class="input-group mb-4">
            <input type="email" name="email" value="{{ $user->email }}"
            class="form-control bg-lightgray px-5 py-3 border-0"
            placeholder="E-mail"
            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="Exemplo: email@dominio.com" required onchange="alterouEmail()" maxlength="50">
        </div>
        <button type="submit" onclick="window.onbeforeunload = null;"
            id="btnSalvarAlteracoesEmail"
            class="btn bg-primary border-0 font-weight-bold text-white btn-block px-5 py-3 mb-2 d-none">
            Salvar alterações
        </button>

    </form>

    <form id="formTrocarSenha" action="{{ route('configuracao.trocar-senha') }}"
        method="post" class="mb-2">
        @csrf

        <h5 class="my-4 font-weight-bold">
            Alterar senha
        </h5>

        <div class="input-group mb-4">
            <input type="password" name="senha_atual" id="txtSenhaAntiga" value
            class="form-control border-0 bg-lightgray px-5 py-3"
            placeholder="Senha atual" required>
        </div>

        <div class="input-group mb-4">
            <input type="password" name="senha_nova" id="txtNovaSenha" value
            class="form-control border-0 bg-lightgray px-5 py-3"
            placeholder="Nova senha" required autocomplete="false">
        </div>

        <div id="divPasswordStrendthMeter" class="password-strength-meter d-none w-100 text-right pr-0 mt-2">
            <div class="progress">
                <meter max="4" role="progressbar" aria-valuemin="0" aria-valuemax="100" id="password-strength-meter" class="mb-0 progress-bar progress-bar-striped progress-bar w-100"></meter>
            </div>
            <small id="password-strength-text" class="mb-0"></small>
        </div>

        <div class="input-group mb-4">
            <input type="password" name="senha_confirmacao" id="txtConfirmarSenha" value
            class="form-control border-0 bg-lightgray px-5 py-3"
            placeholder="Confirmar nova senha" required autocomplete="false">
        </div>

        <div id="errors" class="well" style="font-size:14px;text-align:center;margin-bottom:20px;"></div>

        <div class="row justify-content-around">
        <div class="col-lg-6 mb-3  col-md-6 col-sm-6">

            <a href="/catalogo" class="btn bg-primary border-0 text-white font-weight-bold btn-block px-5 py-3">
                Voltar
            </a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <button type="button" id="btn_salvar_senha" onclick="salvarSenha();"
                class="btn bg-primary border-0 text-white font-weight-bold btn-block px-5 py-3">
                Salvar alterações
            </button>
        </div>

        </div>

    </form>
</div>
