@extends('fr/master')
@section('content')
    <script type="text/javascript" src="{{url('fr/includes/froala_editor/js/plugins/froala_wiris/integration/WIRISplugins.js?viewer=image')}}"></script>

    <section class="section section-interna">
        <div class="container">
            <h2 class="title-page">
                <a href="{{url('gestao/avaliacao')}}" title="Voltar" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
                Relatório da Avaliação INterativa  <i>#{{$avaliacao->id}} {{$avaliacao->titulo}}</i>
            </h2>
            @if( $avaliacao->data_hora_inicial->gte(date('Y-m-d H:i:s')) )
                <h6 class="text-center text-danger"> Essa avaliação ainda não foi iniciada. A data de início é <b>{{$avaliacao->data_hora_inicial->format('d/m/Y H:i:s')}}</b></h6>
            @elseif( $avaliacao->data_hora_final->gte(date('Y-m-d H:i:s')) )
                <h6 class="text-center text-warning"> Essa avaliação ainda não foi encerrada. A data de encerramento é <b>{{$avaliacao->data_hora_final->format('d/m/Y H:i:s')}}</b></h6>
            @endif
            <h4 style="margin-bottom: 18px;">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="{{url('/gestao/avaliacao/relatorio/online/'.$avaliacao->id.'?tipo=a')}}" class="nav-link @if(Request::input('tipo')!= 'g' && Request::input('tipo')!= 'm') active @endif">Alunos</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('/gestao/avaliacao/relatorio/online/'.$avaliacao->id.'?tipo=g')}}" class="nav-link @if(Request::input('tipo')== 'g' ) active @endif">Visão geral</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{url('/gestao/avaliacao/relatorio/online/'.$avaliacao->id.'?tipo=m')}}" class="nav-link @if(Request::input('tipo')== 'm' ) active @endif">Questões a corrigir manualmente</a>
                    </li>
                </ul>
            </h4>
            <div class="row">
                @if(Request::input('tipo')== 'g' )
                    @include('fr/avaliacao/relatorio/gestao/relatorio_visao_geral')
                @elseif(Request::input('tipo')== 'm' )
                    @include('fr/avaliacao/relatorio/gestao/relatorio_correcao_manual')
                @else
                    @include('fr/avaliacao/relatorio/gestao/relatorio_alunos')
                @endif
            </div>

        </div>
    </section>
    <div class="modal fade" id="modalCorrigir" tabindex="-1" role="dialog" aria-labelledby="modalCorrigir" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" >{{$avaliacao->titulo}}</h5>
                </div>
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-12 text-center">
                            <span style="color: #757575; font-size: 24px" id="tituloCorrecao"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" id="corpoModalCorrigir">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button id="btnForm" type="button" class="btn btn-success" onclick="enviaFormularioCorrecao()">Avaliar</button>
                </div>
            </div>
        </div>
    </div>
    <script>

        function corrigir(numeroQuestao,idQuestao,idAluno){
            $('#tituloCorrecao').html('Correção da questão '+numeroQuestao);
            $('#corpoModalCorrigir').html('');
            $('#modalCorrigir').modal('show');
            $.ajax({
                url: '{{url('/gestao/avaliacao/correcaoPergunta')}}',
                type: 'post',
                dataType: 'json',
                data: {
                    avaliacao_id: {{$avaliacao->id}},
                    questao_id: idQuestao,
                    user_id: idAluno,
                    numero_questao: numeroQuestao,
                    _token: '{{csrf_token()}}'
                },
                success:function(data){
                    $('#corpoModalCorrigir').html(data)
                },
                error: function() {
                    swal("Erro.", "Impossível localizar a questão.", "error");
                }
            });
        }

        function enviaFormularioCorrecao(){
            erro = 0;
            correcao = $('#corrigirAvaliacao').val();
            correcaoPesoParcial = $('#correcaoPesoParcial').val();
            correcaoPeso = parseFloat($('#correcaoPeso').val());
            if(correcao == '')
            {
                erro = 1;
                $('#corrigirAvaliacao').addClass('is-invalid');
                $('#FeedCorrigirAvaliacao').html('O campo correção é obrigatório');
            }
            if(correcao == 2){
                if(correcaoPesoParcial == '') {
                    erro = 1;
                    $('#correcaoPesoParcial').addClass('is-invalid');
                    $('#FeedCorrecaoPesoParcial').html('O campo peso parcial é obrigatório');
                }
                else{
                    correcaoPesoParcial = parseFloat(correcaoPesoParcial);
                    if(correcaoPesoParcial <= 0){
                        erro = 1;
                        $('#correcaoPesoParcial').addClass('is-invalid');
                        $('#FeedCorrecaoPesoParcial').html('O campo peso parcial precisa ser maior que zero.');
                    }

                    if(correcaoPesoParcial > correcaoPeso){
                        erro = 1;
                        $('#correcaoPesoParcial').addClass('is-invalid');
                        $('#FeedCorrecaoPesoParcial').html('O campo peso parcial não pode ser maior que '+correcaoPeso);
                    }
                }
            }
            if(erro == 0) {
                $('#formCorrecao').submit();
            }
        }
    </script>
@stop
