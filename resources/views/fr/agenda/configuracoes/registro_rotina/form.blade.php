@extends('fr/master')
@section('content')
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.jquery.min.js"></script>
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.css">
<script>
    $(document).ready(function(){
        /* configuracoes basicas do plugin de recortar imagem */
        var configuracao = {
            ratio: '1:1',
            crop: {
                x: 150,
                y: 150,
                width: 150,
                height: 150
            },
            download: false,
            label: '<label for="exampleFormControlFile1">Insira uma Imagem</label> <i class="fas fa-file h5"></i> <br>Tamanho da imagem: 150px X 150px ',
            buttonConfirmLabel: 'Ok',
        }

        /* carrega o plugin de recortar imagem */
        $(".myCropper").slim(configuracao);
    });
</script>
    <section class="section section-interna mb-5" style="padding-top: 50px">
        <div class="container">
            @include('fr.agenda.menu')
            <div class=" row border-top pt-4">
                <div class="col-12">
                    <div class="pt-4">
                        <h4 class="pb-3 border-bottom mb-4">
                            <a href="{{ url('/gestao/agenda/configuracoes/registros/rotinas/editar')}}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            Editar Rotina de Agendamento e Registro
                        </h4>
                    </div>
                </div>
                <div class="col-12">
                    <form calss="" id="formCadastroComunicado" action="{{url('/gestao/agenda/configuracoes/registros/rotinas/editar/form/')}}" method="post" enctype="multipart/form-data">
                        <div class="row">
                            @csrf
                            <input type="hidden" name="id" value="{{$dados->id}}">
                            <input type="hidden" name="rotina" value="{{$dados->rotina}}">
                            <div class="col-md-6 pl-5">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>* Imagem</label>
                                            <div id="logoCadastro" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                                <input type="hidden" name="existeImg" id="existeImg" value="{{old('existeImg',@$dados->imagem)}}">
                                                <img id="imgLogo" class="img-fluid" width="100px" src="@if(@$dados->imagem != ''){{$dados->linkImagem}}@endif">
                                                <br>
                                                <a class="btn btn-secondary" onclick="excluirLogo()">Excluir Imagem</a>
                                            </div>
                                            <div id="novaLogo" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                                <input type="file" name="imagem" class="myCropper">
                                            </div>
                                            <div class="invalid-feedback" style="display: block">{{ $errors->first('imagem') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>* Título</label>
                                            <input name="titulo" type="text" placeholder="" value="{{old('titulo',@$dados->titulo)}}" class="form-control rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
                                            <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{url('/gestao/agenda/configuracoes/registros/rotinas/editar')}}" class="btn btn-secondary float-left">Cancelar</a>
                                <button class="btn btn-default mt-0 float-right ml-2">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            </div>


        </div>
    </section>

    <script>
        function excluirLogo()
        {
            $('#logoCadastro').hide();
            $('#novaLogo').show();
            $('#existeImg').val('');
        }

        $(document).ready(function() {
            @if(@$dados->imagem != '')
            $('#logoCadastro').show();
            $('#novaLogo').hide();
            @else
            $('#logoCadastro').hide();
            $('#novaLogo').show();
            @endif

        })
    </script>
@stop



