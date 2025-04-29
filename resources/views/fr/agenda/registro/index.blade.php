@extends('fr/master')
@section('content')
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/jquery-datetimepicker/jquery.datetimepicker.min.css" type="text/css" charset="utf-8" />
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet">
    <style>

        .ss-main .ss-single-selected {
            border: 1px solid #ffb100;
        }

        #tableRegistro .custom-control-input:disabled~.custom-control-label, .custom-control-label{
            font-size: 10px;
            padding: 7px 0 0 0;
        }

        #tableRegistro .form-control{
            border-radius: .25rem;
            border: 1px solid #ced4da;
            font-size: 12px;
        }

        #tableRegistro .input-group-text{
            padding: 0px 0px 0px 8px;
        }

        #tableRegistro p{
            margin-bottom: 0.5rem;
        }

        #tableRegistro td{
            min-width: 200px;
        }

        #tableRegistro th:first-child, td:first-child
        {
            position: sticky;
            left: -1px;
            z-index: 300;
            background-image: linear-gradient(to right, #f2f2f2 98.8%, #dee2e6);
        }
    </style>
    <script>
        var selTurma = null;
        $(document).ready(function(){
            jQuery.datetimepicker.setLocale('pt-BR');
            config ={
                format:'d/m/Y',
                timepicker:false,
            };
            jQuery('#datetimepicker').datetimepicker(config);
             selTurma = new SlimSelect({
                select: '.selTurma',
                placeholder: 'Buscar',
                searchPlaceholder: 'Buscar',
                closeOnSelect: true,
                allowDeselectOption: true,
                selectByGroup: true,
            });
        });
    </script>
    <section class="section section-interna" style="padding-top: 50px">
        <div class="container">
            @include('fr.agenda.menu')
            <div class="row">
                <div class="col-12">
                    <h3>
                        Agenda e Registro
                    </h3>
                </div>
            </div>
            <div class="row mb-3  border-top pt-4">
                <div class="col-md-12">
                    @if(auth()->user()->permissao == 'P')
                        @include('fr.agenda.registro.buscaMenuProfessor')
                    @elseif(auth()->user()->permissao == 'C')
                        @include('fr.agenda.registro.buscaMenuCoordenador')
                    @elseif(auth()->user()->permissao == 'I')
                        @include('fr.agenda.registro.buscaMenuInstitucional')
                    @endif
                </div>
            </div>

        </div>
        @if(isset($registro['turma']) && isset($registro['rotina']))
            @if($registro['turma'] && count($registro['rotina']) >0)
                @if(auth()->user()->permissao != 'I')
                    @include('fr.agenda.registro.indexCadastro')
                @else
                    @include('fr.agenda.registro.indexRelatorio')
                @endif
            @else
                <div class="container">
                    <div class="col">
                        <div class="card text-center">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Nenhum Resultado Encontrado</h5>
                                <p class="card-text ">A turma selecionada não está habilitada com rotinas.</p>
                            </div>
                            <div class="card-footer text-muted"></div>
                        </div>
                    </div>

                </div>
            @endif
        @else
            <div class="container">
                <div class="col">
                    <div class="card text-center">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <h5 class="card-title mt-2"><i class="fas fa-exclamation-circle"></i> Faça a busca pela sua turma</h5>
                            <p class="card-text "> </p>
                        </div>
                        <div class="card-footer text-muted"></div>
                    </div>
                </div>

            </div>
        @endif
    </section>


    <script>
        $(document).ready(function(){
            @if(isset($registro['turma']) && isset($registro['rotina']))
                @foreach($registro['rotina'] as $r)
                    @if( !isset($registro['dadosRegistros'][$r->id]))
                        ativaRotina({{$r->id}});
                    @endif
                @endforeach
            @endif
        });

        function ativaRotina(id){
            if(!$('#rotinaSwitch'+id).prop('checked')){
                $('.rotinaAlunoSwitch'+id).prop('checked',false);
                $('.rotinaAlunoSwitch'+id).prop('disabled',true);
                $('.rotinaAlunoText'+id).prop('disabled',true);
                $('.rotinaAlunoText'+id).val('');
                $('#checkTodosRotina'+id).prop('disabled',true);
                $('#checkTodosRotina'+id).prop('checked',false);
            }
            else{
                $('.rotinaAlunoSwitch'+id).prop('disabled',false);
                $('.rotinaAlunoText'+id).prop('disabled',false);
                $('#checkTodosRotina'+id).prop('disabled',false);
                $('.rotinaAlunoSwitch'+id).prop('checked',true);
                $('#checkTodosRotina'+id).prop('checked',true);
            }
        }

        function marcarTodosRotina(id){
            marcar = $('#checkTodosRotina'+id).prop('checked');
            $('.rotinaAlunoSwitch'+id).prop('checked',marcar);
        }
    </script>
@stop
