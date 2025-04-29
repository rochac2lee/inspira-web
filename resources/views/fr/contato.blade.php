@extends(Auth::user() ? 'fr/master' : 'fr/masterFora')
@section('content')
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/jquery.inputmask.bundle.min.js"></script>

    <section class="section section-interna">
        <div class="container-fluid">
            <h2 class="title-page mr-3">
                <a href="#" onclick="window.history.go(-1);" title="Voltar" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> </a>
                Central de Atendimento
            </h2>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 bg-light border-right p-4">
                    <h4 class="pb-3 border-bottom mb-4">FAQ</h4>
                    <div class="accordion faq" id="accordionExample">
                        <div class="card m-0">
                            <div class="card-header" id="heading1">
                                <h2 class="mb-0">
                                    <button class="btn btn-link w-100 text-left" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                                        Como obter um usuário e senha de acesso?
                                    </button>
                                </h2>
                            </div>

                            <div id="collapse1" class="collapse" aria-labelledby="heading1" data-parent="#accordionExample">
                                <div class="card-body">
                                    Entre em contato com a equipe pedagógica/diretiva de sua escola e solicite um usuário e senha de acesso a plataforma educacional Opet INspira.
                                </div>
                            </div>
                        </div>

                        <div class="card m-0">
                            <div class="card-header" id="heading2">
                                <h2 class="mb-0">
                                    <button class="btn btn-link w-100 text-left" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                        Esqueci minha senha de acesso, o que devo fazer?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordionExample">
                                <div class="card-body">
                                    <!-- Acesse <a href="{{url('')}}">www.opetinspira.com.br</a> e na tela Seja Bem-vindo localize e clique em &quot;Esqueci a Senha&quot;. Leia atentamente e siga as orientações da plataforma para criar uma nova senha de acesso. -->
                                    Preencha todas as informações do Formulário de Contato e fique atento à sua Caixa de Mensagens porque as informações para a recuperação de senha serão enviadas para o seu e-mail.
                                </div>
                            </div>
                        </div>

                        <div class="card m-0">
                            <div class="card-header" id="heading3">
                                <h2 class="mb-0">
                                    <button class="btn btn-link w-100 text-left" type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                        <!-- Digitei meu usuário e senha porém aparece a mensagem &quot;Usuário ou senha incorreto!&quot; Como proceder? -->
                                        Como acessar a plataforma educacional Opet INspira?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordionExample">
                                <div class="card-body">
                                    <!-- Verifique novamente se o usuário e senha digitado foram digitados corretamente.<br>
                                    Caso tenha esquecido sua senha, clique em &quot;Esqueci a Senha&quot; e siga as orientações da plataforma.<br>
                                    O login (e-mail) informado pode não pertencer a nenhum usuário cadastrado na plataforma.<br>
                                    Neste caso, busque orientação diretamente com equipe pedagógica/diretiva de sua escola. -->
                                    Recomendamos acessar os Tutoriais da Opet INspira disponíveis na própria plataforma e no <a href="{{url('tutorial/1')}}">LINK</a>.<br>
                                    <br>
                                    Acesse e INspire-se!
                                </div>
                            </div>
                        </div>

                        <div class="card m-0">
                            <div class="card-header" id="heading4">
                                <h2 class="mb-0">
                                    <button class="btn btn-link w-100 text-left" type="button" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                        Como entrar em contato com o Fale Conosco?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordionExample">
                                <div class="card-body">
                                    Em caso de dúvidas, fale conosco: atendimentote@opeteducation.com.br ou 0800-41-00034 (segunda a sexta-feira, das 08:00 às 17:30).
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-5 p-4">
                    <form id="form" method="post" action="{{url('contato')}}">
                        @csrf
                        <h4 class="pb-3 border-bottom mb-4">Formulário de Contato </h4>
                        <p><small>* Campos obrigatórios</small></p>
                        @if($errors->first('g-recaptcha-response'))
                            <p style="color: red">{{$errors->first('g-recaptcha-response')}}</p>
                        @endif
                        <div class="form-group">
                            <label>* Nome Completo</label>
                            <input type="text" placeholder="" name="nome" class="form-control rounded {{ $errors->has('nome') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->first('nome') }}</div>
                        </div>
                        <div class="form-group">
                            <label>* Escola</label>
                            <input type="text" placeholder="" name="escola" class="form-control rounded {{ $errors->has('escola') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->first('escola') }}</div>
                        </div>
                        <div class="form-group">
                            <label>* Cidade</label>
                            <input type="text" placeholder="" name="cidade" class="form-control rounded {{ $errors->has('cidade') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->first('cidade') }}</div>
                        </div>
                        <div class="form-group">
                            <label>* Telefone</label>
                            <input id="telefone" type="text" placeholder="" name="telefone" class="form-control rounded {{ $errors->has('telefone') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->first('telefone') }}</div>
                        </div>
                        <div class="form-group">
                            <label>* E-mail para contato</label>
                            <p style="color: red"><font size="-1">- Se @opeteducation.com.br/@souopet.com.br estiverem com problema, utilizar outro e-mail.</font></p>
                            <input type="text" placeholder="" name="email" class="form-control rounded {{ $errors->has('email') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        </div>
                        <div class="form-group">
                            <label>* Assunto</label>
                            <input type="text" placeholder="" name="assunto" class="form-control rounded {{ $errors->has('assunto') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->first('assunto') }}</div>
                        </div>
                        <div class="form-group">
                            <label>* Mensagem</label>
                            <textarea class="form-control rounded {{ $errors->has('msg') ? 'is-invalid' : '' }}" name="msg" rows="4"></textarea>
                            <div class="invalid-feedback">{{ $errors->first('msg') }}</div>
                        </div>
                        <button @if( env('APP_ENV') != 'local') type="button" @else type="submit" @endif  data-sitekey="{{config('app.GOOGLE_RECAPTCHA_KEY')}}" data-callback='onSubmit' class="g-recaptcha btn btn-default mt-0 float-right ml-2">Enviar Contato</button>
                    </form>
                </div>
                <div class="col-md-3 p-0 bg-light border-right p-4">
                    <h4 class="pb-3 border-bottom mb-4">Atendimento</h4>
                    <div class="form-group">
                        <div class="pt-2">
                            <p>Caso tenha alguma dúvida, entre em contato conosco.</p>

                            <p>E-mail:&nbsp;<strong>atendimentote@opeteducation.com.br</strong>&nbsp;</p>

                            <p>Fone: <strong>0800 41 00034</strong></p>

                            <p>Horário de atendimento:<br>
                                Segunda a Sexta – 08h00 às 17h30, exceto feriados.</p>

                            <p>Estamos à disposição para esclarecer as suas questões.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if(env('APP_ENV')!= 'local')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <script>

        var error = '{{session('erro')}}';
        var certo = '{{session('certo')}}'
        if(error != '')
            swal("Erro ao tentar enviar contato.", error, "error");
        if(certo != '')
            swal("Sua mensagem foi enviada!", certo, "success");

        function onSubmit(token) {
            document.getElementById("form").submit();
        }

        $("#telefone").inputmask({
            mask: ["(99) 9999-9999","(99) 99999-9999" ],
            keepStatic: true
        });

    </script>
@stop
