@extends('fr/master')
@section('content')
    <link href="{{config('app.cdn')}}/fr/includes/js/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css" rel="stylesheet">
    <script src="{{config('app.cdn')}}/fr/includes/js/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>

    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.jquery.min.js"></script>
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.css">

<script>
    $(document).ready(function(){
        $('.input-colorpicker').colorpicker();

        /* configuracoes basicas do plugin de recortar imagem */
        var configuracao = {
            ratio: '1:1',
            crop: {
                x: 250,
                y: 250,
                width: 250,
                height: 250
            },
            download: false,
            label: '<label for="exampleFormControlFile1">Insira uma Imagem</label> <i class="fas fa-file h5"></i> <br>Tamanho da imagem: 250px X 250px ',
            buttonConfirmLabel: 'Ok',
        }

        /* carrega o plugin de recortar imagem */
        $(".myCropper").slim(configuracao);
    });
</script>
    <section class="section section-interna mb-5" style="padding-top: 50px">
        <div class="container">
            @include('fr.agenda.menu')
            <div class="row border-top pt-4">
                <div class="col-md-4 pb-2">
                    <h3>
                        <a href="{{ url('/gestao/agenda/configuracoes')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Configurações
                    </h3>
                </div>

            </div>
            <form id="formCadastroComunicado" action="{{url('/gestao/agenda/configuracoes/estilo/editar')}}" method="post" enctype="multipart/form-data">
                <div  class="row pt-1 pb-1">
                    <div class="col-12">
                        <small>* campos obrigatórios.</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ">
                        @csrf
                        @if ( strpos(Request::path(),'editar')!==false )
                            <input type="hidden" name="id" value="{{old('id',@$dados->id)}}">
                        @endif
                        <div class="pt-4">
                            <h4 class="pb-3 border-bottom mb-4">Estilos</h4>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                @if(@$dados->imagem == '')
                                    <input type="hidden" value="1" name="imagemOriginal">
                                @endif
                                @php
                                    $img = config('app.cdn').'/fr/imagens/agenda/icone_tela_principal.png';
                                    if(@$dados->imagem != ''){
                                        $img = $dados->linkImagem;
                                    }

                                    $cor = '#ffae00';
                                    if(@$dados->cor_primaria != ''){
                                        $cor = $dados->cor_primaria;
                                    }
                                @endphp
                                <label>* Imagem:</label>
                                <div id="logoCadastro" class="form-group imagem-file-roteiro text-white rounded p-1 text-center" style="background-color: #ffffff">
                                    <input type="hidden" name="existeImg" id="existeImg" value="{{$img}}">
                                    <img id="imgLogo" class="img-fluid" width="250px" height="250px" src="{{$img}}">
                                    <br>
                                    <a class="btn btn-secondary" onclick="excluirLogo()">Excluir Imagem</a>
                                </div>
                                <!--<div id="logoCadastro" class="form-group imagem-file-roteiro text-white rounded p-1 text-center" style="background-color: {{$cor}}">
                                    <input type="hidden" name="existeImg" id="existeImg" value="{{$img}}">
                                    <img id="imgLogo" class="img-fluid rounded-circle mt-2" src="{{$img}}">
                                    <br>
                                    <a class="btn btn-secondary mt-2" onclick="excluirLogo()">Excluir Imagem</a>
                                </div>-->
                                <div id="novaLogo" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                    <input type="file" name="imagem" class="myCropper">
                                </div>
                                    <p class="text-center text-secondary">Recomendamos imagens nos formatos .PNG, fundo transparente e com tamanho 250px por 250px.</p>
                                <div class="invalid-feedback" style="display: block">{{ $errors->first('imagem') }}</div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    @php
                                        $titulo = 'INspira Agenda';
                                        if(@$dados->titulo_inicial != ''){
                                            $titulo = $dados->titulo_inicial;
                                        }
                                    @endphp
                                    <label>* Título inicial:</label>
                                    <input name="titulo_inicial" id="titulo_inicial" type="text" placeholder="" value="{{old('titulo_inicial',$titulo)}}" class="form-control rounded {{ $errors->has('titulo_inicial') ? 'is-invalid' : '' }}" maxlength="15" onkeyup="contaCaracteres()">
                                    <small class="form-text w-100 text-muted">
                                        O título inicial pode conter no máximo 15 caracteres. (<span id="quantidade">0</span> de 15)
                                    </small>
                                    <div class="invalid-feedback">{{ $errors->first('titulo_inicial') }}</div>
                                </div>
                                <div class="form-group">

                                    <label>* Cor primária:</label>
                                    <div class="input-group input-colorpicker" title="Using input value">
                                        <input type="text" name="cor_primaria" class="form-control {{ $errors->has('cor_primaria') ? 'is-invalid' : '' }}" value="{{old('cor_primaria',$cor)}}"/>
                                        <span class="input-group-append">
                                        <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                    </span>
                                        <div class="invalid-feedback">{{ $errors->first('cor_primaria') }}</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Preview (Imagem / Cor primária):</label>
                                    <div id="" class="form-group imagem-file-roteiro text-white rounded p-1 text-center position-relative" style="background-color: {{$cor}}">
                                        <img id="" class="img-fluid rounded-circle mt-1" width="150px" height="150px" style="background-color:#ffffff">
                                        <div id="" class="position-absolute fixed-top" >
                                            <br><br>
                                            <img id="" class="img-fluid" width="105px" height="105px" src="{{$img}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(isset($dados->titulo_inicial) && $dados->titulo_inicial!='')
                            <p class="text-center">
                                <a href="{{url('/gestao/agenda/configuracoes/estilo/limpar')}}" ><u><i class="fas fa-palette"></i> Voltar estilo para o original</u></a>
                            </p>
                        @endif
                        <a href="{{ url('/gestao/agenda/configuracoes')}}" class="btn btn-secondary float-left">Cancelar</a>

                        <button class="btn btn-default mt-0 float-right ml-2">Salvar</button>

                    </div>
                </div>
            </form>
        </div>
    </section>
<script>
    function contaCaracteres(){
        $('#quantidade').html($('#titulo_inicial').val().length);
    }
    function excluirLogo()
    {
        $('#logoCadastro').hide();
        $('#novaLogo').show();
        $('#existeImg').val('');
    }
    $(document).ready(function() {
        $('#logoCadastro').show();
        $('#novaLogo').hide();
        contaCaracteres();
    });
</script>
@stop



