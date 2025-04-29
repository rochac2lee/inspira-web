@extends('layouts.master')

@section('title', 'Configurações de conta')

@section('headend')

    <!-- Custom styles for this template -->
    <style>
         body{
            padding-top:40px!important;
        }
        main{
            padding-top:0!important;
        }
        header {
            padding: 154px 0 100px;
        }

        @media (min-width: 992px) {
            header {
                padding: 156px 0 100px;
            }

            main > .row > .side-menu {
                height: 100%;
            }
        }

        main > .container-fluid {
            min-height: calc(100vh - 146px);
        }

        .option-menu .btn {
            color: var(--primary-color) !important;
            font-size: 18px;
            font-weight: bold;
            text-align: left;
            display: block;
        }

        .option-menu .btn, .option-menu .btn:hover {
            text-decoration: none;
        }

        .option-menu .btn.active {
            color: #525870 !important;
        }

        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: transparent;
            color: var(--primary-color);
        }

        .nav-tabs .nav-item {
            margin-bottom: 0;
        }

        .nav-tabs .nav-link {
            border: 0;
            font-size: 20px;
            color: var(--primary-color);
            font-weight: bold;
            padding-bottom: 20px;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom: 4px solid var(--primary-color);
            background: transparent;
        }

        .input-group > .form-control::placeholder {
            color: #ABABAB;
        }

        .input-group > .form-control {
            z-index: 3;
            color: #525870;
            background: #F3F3F3;
            border-top: 4px solid transparent !important;
            border-bottom: 4px solid transparent !important;
            box-shadow: none;
            font-weight: bold;
            font-size: 18px;
            width: auto;
        }

        .input-group > .custom-file:focus, .input-group > .custom-select:focus, .input-group > .form-control:focus {
            z-index: 3;
            background: #F3F3F3;
            border-bottom: 4px solid var(--primary-color) !important;
            box-shadow: none;
            color: var(--primary-color);
            font-weight: bold;
        }

        .input-group-text {
            color: #ABABAB;
        }

        .nav-pills > a.nav-link {
            font-size: 20px;
            color: black;
            font-weight: normal;
        }

        .nav-pills > a.nav-link.active {
            color: var(--primary-color) !important;
            font-weight: bold;
        }

        .input-file-custom-avatar {
            margin: 40px auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: transparent !important;
            background: #DEE2F0 url({{ asset('storage/uploads/usuarios/perfil/'.$user->img_perfil) }}) 50% 50% no-repeat;
            background-size: cover;
            background-blend-mode: multiply;
            width: 160px;
            height: 160px;
            -webkit-border-radius: 80px;
            -moz-border-radius: 80px;
            border-radius: 80px;
            cursor: pointer;
            transition: 0.2s all ease-in-out;
        }

        .input-file-custom-avatar:hover {
            color: #fff !important;
            background: rgba(333, 333, 333, 0.7) url({{ asset('storage/uploads/usuarios/perfil/'.$user->img_perfil) }}) 50% 50% no-repeat;
            background-size: cover;
            background-blend-mode: multiply;
        }

        label {cursor:pointer}


    </style>

@endsection

@section('content')

    <main role="main">

        <div class="container-fluid">

            <div class="row" style="min-height: calc(100vh - 114px);">

                <div class="col-12 col-md-3 pt-5 p-2 text-center" style="background: #F9F9F9;">

                    <h4 class="font-weight-bold">Configurações da Conta </h4>

                    <div class="mx-auto">

                        <form class="" id="formTrocarFotoPerfil" method="POST"
                                action="{{ route('configuracao.trocar-foto') }}" enctype="multipart/form-data" hidden>
                            @csrf
                            <input type="file" class="custom-file-input" id="input_foto" name="foto" required
                                style="top: 0;height:  100%; position: absolute; left: 0;"
                                accept="image/jpg, image/jpeg, image/png" oninput="trocarFotoPerfil(this);">
                        </form>

                        <div class="d-flex justify-content-center">
                            <label for="input_foto" class="input-file-custom-avatar avatar-img">
                                <div><i class="fas fa-camera fa-lg"></i></div>
                                <div>ALTERAR FOTO</div>
                            </label>
                        </div>

                        <button id="btnSalvarFoto" type="button" onclick="salvarFotoPerfil();"
                            class="btn btn-primary border-0 font-weight-bold py-2 mx-auto mb-5 d-none animated fadeIn">
                            Salvar foto
                        </button>

                        <h5 class="text-primary font-weight-bold mb-5">{{ ucwords( mb_strtolower( Auth::user()->name, 'UTF-8') ) }}</h5>
                    </div>

                    <hr class="mb-4">

                    <div class="nav flex-column nav-pills py-2 mt-3 px-0 px-md-2 mx-0 mx-md-2 text-left font-weight-bold" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="font-size: 18px;">
                        @if(Auth::user()->habilitar_troca_senha == 1)
                            <a class="nav-link py-1 px-1 mb-3 active" id="v-pills-home-tab" data-toggle="pill" href="#conta"
                               role="tab" aria-controls="v-pills-home" aria-selected="false">
                               Alterar e-mail ou senha
                            </a>
                        @endif
                        <a class="nav-link py-1 px-1 mb-3" id="v-pills-profile-tab" data-toggle="pill" href="#dados"
                           role="tab" aria-controls="v-pills-profile" aria-selected="true">
                           Alterar dados cadastrais
                        </a>
                    </div>
                </div>

                <div class="col-12 col-md-9 p-5 px-lg-0" style="min-height: 100%; background-color: #fff;">

                    <div class="col-12 col-md-11 mx-auto">

                        <div class="tab-content" id="v-pills-tabContent">

                            @if(Auth::user()->habilitar_troca_senha == 1)
                                <!-- alterar email ou senha -->
                                @include('pages.configuracoes-conta._email-senha')
                            @endif
                            <!-- dados cadastrais -->
                            @include('pages.configuracoes-conta._dados-cadastrais')


                        </div>
                    </div>

                </div>

            </div>
        </div>
    </main>

@endsection

@section('bodyend')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

    <!-- Sweet Alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Moment JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    <!-- Password strength  detection -->
    <script type="text/javascript" src="{{config('app.cdn')}}/old/assets/js/password-strength2.js"></script>

    <!-- ZXCVBN for Password strength  detection -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>

    <!-- Password Strength -->
    <script type="text/javascript" src="{{config('app.cdn')}}/old/assets/js/jquery.password-validation.js"></script>

    <!-- Summernote css/js -->
    {{-- <link href="{{ config('app.local') }}/assets/css/summernote-lite-cerulean.min.css" rel="stylesheet"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/lang/summernote-pt-BR.min.js" crossorigin="anonymous"></script>


    <script type="text/javascript">

        var estados = null;

        $(document).ready(function() {
            $(".date").mask('00/00/0000');
            $(".cpf").mask('000.000.000-00');
            $(".rg").mask('00.000.000-A');
            $('.telefone').mask('(00) 0 0000-0000');
            $(".cep").mask('00000-000');

            $('.datepicker').mask('00/00/0000');

            $('.datepicker').datepicker({
                autoclose: true,
                language: 'pt-BR',
                startDate: '01-01-' + {{ (date("Y") - 150) }},
                endDate: '{{ date("d-m-Y", strtotime('-1 year')) }}'
            });

            // integraçoes
            $('.numeric').keyup(function() {
                $(this).val(this.value.replace(/\D/g, ''));
            });

            $('#conta').keyup(function() {
                if($(this).val().lenght);
            });

            $("#document_number").keydown(function(){
                try {
                    $("#document_number").unmask();
                } catch (e) {}

                var tamanho = $("#document_number").val().length;

                if(tamanho < 11){
                    $("#document_number").mask("999.999.999-99");
                } else if(tamanho >= 11){
                    $("#document_number").mask("99.999.999/9999-99");
                }

                // ajustando foco
                var elem = this;
                setTimeout(function(){
                    // mudo a posição do seletor
                    elem.selectionStart = elem.selectionEnd = 10000;
                }, 0);
                // reaplico o valor para mudar o foco
                var currentValue = $(this).val();
                $(this).val('');
                $(this).val(currentValue);
            });


            $("#txtNovaSenha").passwordValidation({
                "confirmField": "#txtConfirmarSenha"},
                function(element, valid, match, failedCases) {
                $("#errors").html('<span">' + failedCases.join ('<br>') + '</span>');
                if(valid) $(element).css("color","#989eb4");
                if(!valid) $(element).css("color","#A41B0B");
                if(valid && match) $("#txtConfirmarSenha").css("color","#989eb4");
                if(!valid || !match) $("#txtConfirmarSenha").css("color","#A41B0B");

                if(valid && match){
                    $('#btn_salvar_senha').prop('disabled', false);
                }else{
                    $('#btn_salvar_senha').prop('disabled', true);
                }
                // #Zl:U8fB2wh|
            });


            $("#cep").focusout(function(){
                //Início do Comando AJAX
                cep = $(this).val();
                $.ajax({
                    //O campo URL diz o caminho de onde virá os dados
                    //É importante concatenar o valor digitado no CEP
                    url: 'https://viacep.com.br/ws/'+ cep +'/json/unicode/',
                    //Aqui você deve preencher o tipo de dados que será lido,
                    //no caso, estamos lendo JSON.
                    dataType: 'json',
                    //SUCESS é referente a função que será executada caso
                    //ele consiga ler a fonte de dados com sucesso.
                    //O parâmetro dentro da função se refere ao nome da variável
                    //que você vai dar para ler esse objeto.
                    success: function(resposta){
                        //Agora basta definir os valores que você deseja preencher
                        //automaticamente nos campos acima.

                        if(resposta.localidade == undefined){
                        }

                        $("#logradouro").val(resposta.logradouro);
                        $("#bairro").val(resposta.bairro);
                        $("#uf").val(resposta.uf);
                        selectEstado($("#uf")[0]);
                        $("#cmbCidade").val(resposta.localidade);
                        //Vamos incluir para que o Número seja focado automaticamente
                        //melhorando a experiência do usuário
                        $("#numero_complemento").focus();
                    }
                });
            });

            var url = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados';

            $.get(url, function (data) {

                window.estados = data;

                @if(isset($endereco))
                $("select#uf").val("{{ $endereco->uf }}");

                if (findValueWhere(estados, 'sigla', $("select#uf").val()) != null) {
                    var url = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + findValueWhere(estados, 'sigla', $("select#uf").val()).id + '/municipios';

                    $.get(url, function (data) {

                        $("#cmbCidade").html("");

                        $("#cmbCidade").append("<option value=''>Selecione sua cidade</option>");

                        data.forEach(function (cidade) {

                            $("#cmbCidade").append("<option value='" + cidade.nome + "'>" + cidade.nome + "</option>");

                        });

                        @if(isset($endereco))
                        $("select#cmbCidade").val("{{ $endereco->cidade }}");
                        @endif
                    });
                } else {
                }
                @endif
            });

            if (window.location.hash) {
                $(".nav-link[href='" + window.location.hash + "']").tab('show');
            }

            // Inserir nome do aluno
            var nomeAluno = function (context) {
                var ui = $.summernote.ui;
                // create button
                var button = ui.button({
                    contents: 'Aluno',
                    container: false,
                    tooltip: 'Nome do aluno',
                    click: function () {
                        // invoke insertText method with 'hello' on editor module.
                        context.invoke('editor.insertText', '[NOME_ALUNO]');
                    }
                });

                return button.render();   // return button as jquery object
            }

            // Inserir nome do curso
            var nomeCurso = function (context) {
                var ui = $.summernote.ui;
                // create button
                var button = ui.button({
                    contents: 'Curso',
                    container: false,
                    tooltip: "Descrição do ",
                    click: function () {
                        // invoke insertText method with 'hello' on editor module.
                        context.invoke('editor.insertText', '[DESC_CURSO]');
                    }
                });

                return button.render();   // return button as jquery object
            }

            // Inserir nome do professor
            var nomeProfessor = function (context) {
                var ui = $.summernote.ui;
                // create button
                var button = ui.button({
                    contents: 'Professor',
                    container: false,
                    tooltip: 'Nome do professor',
                    click: function () {
                        // invoke insertText method with 'hello' on editor module.
                        context.invoke('editor.insertText', '[NOME_PROFESSOR]');
                    }
                });

                return button.render();   // return button as jquery object
            }

            // Inserir nome do professor
            var horaCurso = function (context) {
                var ui = $.summernote.ui;
                // create button
                var button = ui.button({
                    contents: 'Hora',
                    container: false,
                    tooltip: "Quantidade de horas do ",
                    click: function () {
                        // invoke insertText method with 'hello' on editor module.
                        context.invoke('editor.insertText', '[HORA_CURSO]');
                    }
                });

                return button.render();   // return button as jquery object
            }
            // nome curso, professor, hora, skill

            $('.summernote').summernote({
                placeholder: "Clique para digitar.",
                lang: 'pt-BR',
                airMode: false,
                height: 300,
                toolbar: [
                    ['font', ['bold', 'italic']],
                    ['buttons', ['aluno', 'curso', 'professsor', 'hora']],
                ],
                buttons: {
                    aluno: nomeAluno,
                    curso: nomeCurso,
                    professsor: nomeProfessor,
                    hora: horaCurso
                }
            });

        });



        function selectEstado(element) {
            if (findValueWhere(estados, 'sigla', element.value) != null) {
                var url = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + findValueWhere(estados, 'sigla', element.value).id + '/municipios';

                $.get(url, function (data) {

                    $("#cmbCidade").html("");

                    $("#cmbCidade").append("<option value=''>Selecione sua cidade</option>");

                    data.forEach(function (cidade) {

                        $("#cmbCidade").append("<option value='" + cidade.nome + "'>" + cidade.nome + "</option>");

                    });

                    @if(isset($endereco))
                    $("select#cmbCidade").val("{{ $endereco->cidade }}");
                    @endif

                });
            } else {
            }

            alterouDadosUsuario();
        }

        function painelShowTrocarSenha() {
            $('#formTrocarSenha').removeClass('d-none');
            $('#btnTrocarSenha').addClass('d-none');
        }

        function painelCloseTrocarSenha() {
            $('#formTrocarSenha').addClass('d-none');
            $('#btnTrocarSenha').removeClass('d-none');
            $("#formTrocarSenha > input").val("");
        }

        function alterouEmail() {
            $('#formTrocarEmail #btnSalvarAlteracoesEmail').removeClass('d-none');
            /*
            window.onbeforeunload = function () {
                return true;
            };
            */
        }

        function alterouDadosUsuario() {
            $('#formTrocarDados #btnSalvarAlteracoesDados').removeClass('d-none');
            /*
            window.onbeforeunload = function () {
                return true;
            };
            */
        }

        function salvarSenha() {

            //Recupera os dados do input senha
            inputSenha = $('#txtNovaSenha').val();
            //Regex para verificar se tem caracter especia, letra maiuscula e minuscula, e numero
            let regex = /^(?=.*[@!#$%^&*()/\\])(?=.*[0-9])(?=.*[a-zA-Z])[@!#$%^&*()/\\a-zA-Z0-9]{6,20}$/;

            window.checarPreenchimento("#formTrocarSenha");

            /* if ($("#txtSenhaAntiga").val() == "" || $("#txtSenhaAntiga").val() == null) {
                $("#txtSenhaAntiga").focus();
                return;
            }

            if ($("#txtNovaSenha").val() == "" || $("#txtNovaSenha").val() == null) {
                $("#txtNovaSenha").focus();
                return;
            }

            if ($("#txtConfirmarSenha").val() == "" || $("#txtConfirmarSenha").val() == null) {
                $("#txtConfirmarSenha").focus();
                return;
            } */

            /* if ($("#txtSenhaAntiga").val().toString().length < 6 || $("#txtNovaSenha").val().toString().length < 6) {
                $("#txtNovaSenha").focus();
                swal("Ops!", "Sua senha deve conter 6 ou mais caracteres!", "warning");
                return;
            } */

            /* if (regex.test(inputSenha) == false) {
                $("#txtNovaSenha").focus();
                swal("Ops!", "Sua senha deve conter letras maiúsculas e minúsculas \n" +
                             "Pelo menos um número \n" +
                             "Pelo menos um símbolo \n", "warning");
                return;
            } */

             if ($("#txtSenhaAntiga").val() == $("#txtNovaSenha").val()) {
                $("#txtNovaSenha").focus();
                swal("", "Sua nova senha deve ser diferente da antiga!", "warning");
                return;
            }

            /* if ($("#txtNovaSenha").val() != $("#txtConfirmarSenha").val()) {
                $("#txtConfirmarSenha").focus();
                swal("Ops!", "A senha de confirmação deve ser igual a sua nova senha!", "warning");
                return;
            } */

            document.getElementById("formTrocarSenha").submit();
        }

        function salvarAlteracoesDados() {
            var preenchido = true;

            $("#formTrocarDados").find('#pessoal :input[required]').each(function () {
                if ($(this).val() == "") {
                    $(this).focus();
                    preenchido = false;
                    $('#formTrocarDados a[href="#pessoal"]').tab('show');
                    return false;
                }
            });

            $("#formTrocarDados").find('#endereco :input[required]').each(function () {
                if ($(this).val() == "") {
                    $(this).focus();
                    preenchido = false;
                    $('#formTrocarDados a[href="#endereco"]').tab('show');
                    return false;
                }
            });

            campo = $('#data_nascimento').val();
            //Verifica se é uma data valida
            const dataNascimento = moment(campo, "DD/MM/YYYY", true);
                data =  moment(dataNascimento).isValid();
            //Formata a data para yyyy-mm-dd
            campoInput = moment(dataNascimento).format("YYYY-MM-DD");
            //Formata e recupera a data atual - 6 anos a data para yyyy-mm-dd
            before = moment().subtract(1, 'year').format("YYYY-MM-DD");
            //Formata e recupera a data atual -130
            after = moment().subtract(130, 'year').format("YYYY-MM-DD");
            //Verifica se a idade é menor que 1 anos
            idadeMinima = moment(before).isBefore(campoInput, 'year');
            //Verifica se a idade é maior que 130 anos
            idadeMaxima = moment(after).isAfter(campoInput, 'year');
            let textError = "";
            if(!data){
                textError = "Data inválida";
            }

            if(idadeMaxima){
                textError = "Idade máxima de 130 anos";
            }

            if(idadeMinima){
                textError = "Idade mínima de 1 ano";
            }

            if(data == false || idadeMaxima == true || idadeMinima == true){
                $("#data_nascimento").focus();
                Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: textError
                        });
            }

            if (!preenchido || !data || idadeMaxima || idadeMinima) {
                return;
            } else {
                document.getElementById("formTrocarDados").submit();
            }
            /*window.onbeforeunload = null;*/
        }

        function trocarFotoPerfil(input) {
            if (input.files && input.files[0]) {
                $("#btnSalvarFoto").addClass("d-block");

                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.avatar-img').attr('style', 'background: url(\'' + e.target.result + '\');background-size: cover;background-position: 50% 50%;background-repeat: no-repeat;');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function salvarFotoPerfil() {
            if ($("#formTrocarFotoPerfil input").val() == null) {
                $("#formTrocarFotoPerfil input").focus();

                return;
            }

            document.getElementById("formTrocarFotoPerfil").submit();
        }

        function mudouFormaPagamento(el)
        {
           if($(el).attr('id') == 'rdoWirecard'){
                $('#btnWirecard').removeClass('d-none');
                $('#btnPagarme').addClass('d-none');
           } else {
                $('#btnPagarme').removeClass('d-none');
                $('#btnWirecard').addClass('d-none');
           }
        }

        async function consultarCEP(cep) {
            if (cep.length != 9) {
                return {'error': 'Cep inválido!'};
            }

            let response = await fetch("https://viacep.com.br/ws/" + cep + "/json/");

            let data = await response.json();


            return data;
        }

        function setarCor(el) {
            let codigo = $(el).attr('codigo');
            if ($(el).val().match(/^#[0-9A-F]{6}$/i)) {

                $('[codigo='+codigo+']').val($(el).val());
            }else{
                $('[codigo='+codigo+']').val('#FFFFFF');
            }
        }

        function mudouArquivoImagem(input) {
            if(input.accept.toString().indexOf(input.value.substring(input.value.length - 3).toLowerCase()) < 0)
            {
                input.value = null;
                swal('Atenção!', "Selecione um arquivo do tipo correto!", "warning");
            }

            imgIcon = '<i class="far fa-image fa-2x text-darkmode mr-2" style="vertical-align:sub;"></i>';

            if(input.value != null && input.value != "")
            {
                $("#" + input.parentNode.id + " #placeholder").addClass('d-none');
                $("#" + input.parentNode.id + " #file-name").html( imgIcon + input.value.split(/(\\|\/)/g).pop() );
                $("#" + input.parentNode.id + " #file-name").removeClass('d-none');
            }
            else
            {
                $(input.parentNode).css('background', 'var(--primary-color)');
                $("#" + input.parentNode.id + " #placeholder").removeClass('d-none');
                $("#" + input.parentNode.id + " #file-name").addClass('d-none');
            }

            let altura = '';
            if(input.id != 'imagem') {
                altura = 'height:  70px;';
            }

            if (input.files &&input.files[0])
            {
                var reader = new FileReader();

                reader.onload = function(e)
                {
                    $(input.parentNode).attr('style', 'color:white; background: url(\'' + e.target.result + '\');background-size: contain;background-position: 50% 50%;background-repeat: no-repeat;'+altura);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function alterouTipoAssinatura(input) {
            if (input.value == 3) {
                if (input.id == 'tipo_assinatura1') {
                    $('#divImagem1').removeClass('d-none');
                } else {
                    $('#divImagem2').removeClass('d-none');
                }
            } else {
                if (input.id == 'tipo_assinatura1') {
                    $('#divImagem1').addClass('d-none');
                } else {
                    $('#divImagem2').addClass('d-none');
                }
            }
        }

    </script>

@endsection
