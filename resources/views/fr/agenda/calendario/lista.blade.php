@extends('fr/master')
@section('content')
    <!--
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/fullcalendar/main.css" type="text/css"/>
    -->
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/fullcalendar_v2/main.css?v-1.2" type="text/css" data-uw-styling-context="true"/>
    <!--
    <script src="{{config('app.cdn')}}/fr/includes/fullcalendar/main.js"></script>
    -->
    <script src="{{config('app.cdn')}}/fr/includes/fullcalendar_v2/main.js" data-uw-styling-context="true"></script>
    <script src="{{config('app.cdn')}}/fr/includes/fullcalendar_v2/locales/pt-br.js"></script>
    <!--
     Alteracao estilo calendario 29/03/2022   
    -->
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/jquery-datetimepicker/jquery.datetimepicker.min.css" type="text/css" charset="utf-8" />
    <script src="{{config('app.cdn')}}/fr/includes/js/jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.css">
    <script src="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/formUtilities.js"></script>
    <section class="section section-interna" style="padding-top: 50px; padding-bottom: 50px">
        <div class="container">
            @include('fr.agenda.menu')
            <div class="row  pb-4">
                <div class="col-md-4">
                    <h3>
                        Calendário de Eventos
                    </h3>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id='calendar'>
                </div>
            </div>
        </div>
    </section>
    @include('fr.agenda.calendario.form')
    <script>
        $(document).ready(function(){
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                nowIndicator: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                ///initialDate: '2021-11-01',
                navLinks: true,
                editable: true,
                selectable: true,
                selectMirror: true,
                eventLimit: true,
                displayEventEnd: true,
                themeSystem: 'bootstrap',
                locale: 'pt-br',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                },
                select: function (start, end, allDay) {
                    data = dataBR(start.startStr);
                    $('#datetimepicker').val(data);
                    $('#datetimepicker1').val(data);
                    $('#tema1').prop('checked',true);
                    $('#ckDiaInteiro').prop('checked',true);
                    mudaTipoEvento();
                    $('#formAddEvento').modal('show');
                },

                eventDrop: function(info) {
                    if (info.event.extendedProps.editavel != '1') {
                        info.revert();
                    }else{
                        inicio = info.event.start.toISOString();
                        fim = info.event.end.toISOString();
                        id = info.event.id;
                        trocouData(inicio, fim, id)
                    }
                },
                eventOverlap: function(stillEvent, movingEvent) {
                    return false;
                },
                eventClick: function(info) {
                    $.ajax({
                        url: '{{url('gestao/agenda/calendario/get')}}',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            id: info.event.id,
                            _token: '{{csrf_token()}}'
                        },
                        success(data){
                            popularForm($('#formAddEvento'), data);
                            $('#tituloForm').html('Editar Evento');
                            if(info.event.extendedProps.editavel != '1'){
                                tornaReadyOnly();
                                $('#tituloForm').html('Evento');
                            }
                            $('#btnExcluir').show()
                            idExcluir = data.id;
                            tipoOperacao = 'editar';

                            $('#eventoId').val(data.id);
                            $('#avatarUsuario').prop('src',data.usuario.avatar);
                            $('#nomeUsuario').html(data.usuario.nome);
                            if(data.dia_inteiro == '1'){
                                $('#ckDiaInteiro').prop('checked',true);
                            }else{
                                $('#ckDiaInteiro').prop('checked',false);
                            }
                            if(data.escolas.length > 0){
                                $('#visualizacao option[value="2"]').prop('selected', true);
                                for(i=0; i< data.escolas.length; i++ ){
                                    $('#ckEscola option[value="'+data.escolas[i].id+'"]').prop('selected', true);
                                }
                            }else{
                                $('#visualizacao option[value="1"]').prop('selected', true);
                            }
                            $('#visualizacao').change();
                            mudaTipoEvento();
                            $('#formAddEvento').modal('show');
                        },
                        error: function(data) {
                            swal("", "Não foi possível recuperar o evento", "error");
                        }
                    });
                },
                events:
                {
                    url: '{{url('gestao/agenda/calendario/lista')}}',
                    method: 'post',
                    extraParams: {
                        _token: '{{csrf_token()}}',
                    },
                    failure: function() {
                        swal("", "Não foi possível carregar a lista de eventos.", "error");
                    },
                }
            });
            calendar.render();
        });

        function trocouData(inicio, fim, id){
            $.ajax({
                url: '{{url('gestao/agenda/calendario/setNovaData')}}',
                type: 'post',
                dataType: 'json',
                data: {
                    id: id,
                    inicio: inicio,
                    fim: fim,
                    _token: '{{csrf_token()}}'
                },
                success(data){

                },
                error: function(data) {
                    swal("", "Não foi possível recuperar o evento", "error");
                }
            });
        }

    </script>
@stop
