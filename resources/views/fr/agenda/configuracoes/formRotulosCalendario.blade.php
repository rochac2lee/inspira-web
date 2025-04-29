@extends('fr/master')
@section('content')
    <link href="{{config('app.cdn')}}/fr/includes/js/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css" rel="stylesheet">
    <script src="{{config('app.cdn')}}/fr/includes/js/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>

<script>
    $(document).ready(function(){
        $('.input-colorpicker').colorpicker();
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
            <form action="{{url('/gestao/agenda/configuracoes/etiquetas/editar')}}" method="post">
                <div class="row">
                    <div class="col-md-12 pl-5">
                        @csrf
                        <div class="pt-4">
                            <h4 class="pb-3 border-bottom mb-4">Etiquetas do calendário</h4>
                        </div>

                        @for($i=0; $i<5; $i++)
                            @php
                                $titulo = '';
                                $cor = '';
                                $id = '';
                                if(isset($dados[$i])){
                                    $titulo = $dados[$i]->titulo;
                                    $cor = $dados[$i]->cor;
                                    $id = $dados[$i]->id;
                                }
                            @endphp
                            <div class=" form-row align-items-center mb-3">
                                <div class="col-3">
                                    <label>* Cor etiqueta {{$i+1}}</label>
                                    <div class="input-group input-colorpicker">
                                        <input type="text" name="cor[]" class="form-control {{ $errors->has('cor.'.$i) ? 'is-invalid' : '' }}" value="{{old('cor.'.$i, $cor)}}"/>
                                        <span class="input-group-append">
                                        <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                    </span>
                                        <div class="invalid-feedback">{{ $errors->first('cor.'.$i) }}</div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <label>* Título etiqueta  {{$i+1}}</label>
                                    <input class="form-control {{ $errors->has('titulo.'.$i) ? 'is-invalid' : '' }}" type="text" name="titulo[]" value="{{old('titulo.'.$i, $titulo)}}" maxlength="50">
                                    <div class="invalid-feedback">{{ $errors->first('titulo.'.$i) }}</div>
                                </div>
                                <div class="col-1">
                                    @if($id!='')
                                        <label class="text-white">Operação</label>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Limpar etiqueta" onclick="modalExcluir('{{$id}}', '{{$titulo}}', '{{$cor}}')"><i class="fas fa-recycle"></i></button>
                                    @endif
                                </div>
                            </div>
                        @endfor
                        <div class="col-12 mt-3">
                            <a href="{{url('/gestao/agenda/comunicados/')}}" class="btn btn-secondary float-left">Cancelar</a>
                            <button class="btn btn-default mt-0 float-right ml-2">Salvar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- EXCLUIR -->
    <div class="modal fade" id="formExcluir" tabindex="-1" role="dialog" aria-labelledby="formExcluir" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Limpar Etiqueta</h5>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-12">
                                Você deseja mesmo remover essa etiqueta?<br><br>
                            </div>
                            <div id="etiquetaCor" class="col-12 text-white text-center" style="background-color: red; font-size: 15px;">
                            <p id="etiquetaTitulo" class="m-3"></p>
                            </div><br>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <button type="button" onclick="excluir()" class="btn btn-danger">Sim, remover</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM EXCLUIR -->
<script>
    var idExcluir =0;
    function modalExcluir(id, titulo, cor)
    {
        idExcluir = id;
        $('#etiquetaTitulo').html(titulo);
        $('#etiquetaCor').css('background-color',cor);
        $('#formExcluir').modal('show');
    }
    function excluir()
    {
        window.location.href = '{{url("/gestao/agenda/configuracoes/etiquetas/excluir")}}?id='+idExcluir;
    }
</script>
@stop



