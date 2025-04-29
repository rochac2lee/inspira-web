@extends('fr/master')
@section('content')
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery/jquery-ui.js"></script>
    <link href="{{ config('app.cdn') }}/fr/includes/dropzone/dropzone.min.css" rel="stylesheet"/>
    <script src="{{ config('app.cdn') }}/fr/includes/dropzone/dropzone.min.js"></script>
    <section class="section section-interna">
        <div class="container">
            <div class="row border-bottom">
                <div class="col-12 pb-2">
                    <h3>
                        <a href="{{ url('/gestao/agenda/noticias')}}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Gerenciamento de imagens
                        <small><br>{{$dados->titulo}}</small>
                    </h3>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12 p-0 bg-light border-right p-4">
                    <div class="form-group mb-3">
                        <div id="envioImagensDZ" class="dropzone" style="cursor:pointer;">

                            <div class="dz-message needsclick">
                                <button type="button" class="dz-button mb-2" style="font-size: 20px"><span style="color: #888; font-size: 40px"><i class="fas fa-download"></i></span><br> Arraste as imagens aqui ou clique para enviar.</button><br>
                                <span class="note needsclick">É possivel enviar várias imagens de uma só vez, selecione as imagens segurando a tecla ctrl ou arraste para essa área.</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 mb-4">
                <div class="col-12">
                    <h6 class="text-center"><b>Imagens cadastradas</b> <br> <small>(Arraste as imagens para ordenar.)</small></h6>
                    <div class="row mb-4" id="listaImg">
                        @foreach($dados->imagens as $img)
                        <div class="col-3 text-center" id="{{$img->id}}">
                            <ul class="list-style">
                                <li >
                                    <img class="img-fluid" width="200px" src="{{ config('app.cdn').'/storage/agenda/noticias/'.$dados->user_id.'/'.$dados->id.'/'.$img->caminho}}">
                                    <br>
                                    <button  class="btn btn-secondary btn-sm fs-13 mt-2" title="Excluir imagem" onclick="modalExcluir({{$img->id}}, {{$dados->id}} , '{{$img->caminho}}')"><i style="color: white" class="fas fa-trash-alt"></i></button>
                                </li>
                            </ul>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-12 text-center">
                    <a href="{{url('/gestao/agenda/noticias/')}}" class="btn btn-default" style="float: none">Feito</a>
                </div>
            </div>
        </div>
    </section>

    <div id="modeloLista" style="display: none">
        <div class="col-3 text-center" id="WWW">
            <ul class="list-style">
                <li >
                    XXX
                    <br>
                    <button  class="btn btn-secondary btn-sm fs-13 mt-2" title="Excluir imagem" onclick="modalExcluir(WWW, {{$dados->id}},'ZZZ')"><i style="color: white" class="fas fa-trash-alt"></i></button>
                </li>
            </ul>
        </div>
    </div>

    <!-- EXCLUIR -->
    <div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Excluir imagem da notícia</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                Você deseja mesmo publicar esse registro?<br><br>
                                <br>
                                <p class="text-center" id="imgExcluir"></p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <button type="button" onclick="excluir()" class="btn btn-danger">Sim, excluir</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM EXCLUIR -->
<script>
    var urlImg = '{{ config('app.cdn').'/storage/agenda/noticias/'.$dados->user_id.'/'.$dados->id}}/';

    Dropzone.autoDiscover = false;
    $(document).ready(function(){
        $("#envioImagensDZ").dropzone({
            url: "{{ url('gestao/agenda/noticias/imagens/' .$dados->id. '/upload') }}",
            paramName: "file",
            acceptedFiles: 'image/*',
            params:{
                '_token' : '{{csrf_token()}}',
            },
            init: function() {
                this.on("success", function(file, response) {
                    img = '<img class="img-fluid" width="200px" src="'+urlImg+response.caminho+'">';
                    html = $('#modeloLista').html();
                    html = html.replace('XXX',img);
                    html = html.replace('WWW',response.id);
                    html = html.replace('WWW',response.id);
                    html = html.replace('ZZZ',response.caminho);
                    $('#listaImg').append(html);
                });
            }
        });

        $("#listaImg").sortable({
            update: function() {
                var sort = $(this).sortable("toArray");
                $.ajax({
                    url: '{{ url('gestao/agenda/noticias/imagens/' .$dados->id. '/ordem') }}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        ordem: sort,
                        _token: '{{csrf_token()}}'
                    }
                });
            }
        });
        $("#sortable").disableSelection();

    });

    var idExcluir =0;
    function modalExcluir(idImg,idNoticia,caminho)
    {
        idExcluir = idImg;
        img = '<img class="img-fluid" width="200px" src="'+urlImg+caminho+'">';
        $('#imgExcluir').html(img);
        $('#formExcluir').modal('show');
    }
    function excluir()
    {
        window.location.href = '{{url("/gestao/agenda/noticias/imagens/".$dados->id."/excluir/")}}/'+idExcluir;
    }


</script>
@stop
