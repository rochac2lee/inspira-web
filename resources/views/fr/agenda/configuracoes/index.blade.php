@extends('fr/master')
@section('content')
    <section class="section section-interna" style="padding-top: 50px">
        <div class="container">
            @include('fr.agenda.menu')

            <div class="row pb-4 border-top pt-4">
                <div class="col-md-4">
                    <h3>
                        Configurações
                    </h3>
                </div>
            </div>
            <div class="row">
                <a href="{{url('gestao/agenda/configuracoes/estilo/editar')}}" class="btn btn-success mr-3" >
                    <i class="fas fa-palette"></i>
                    Estilos
                </a>
                <!--
                <a href="{{url('')}}" class="btn btn-success mr-3" >
                    <i class="fas fa-comments"></i>
                    Grupos de mensagens
                </a>
                -->
                <a href="{{url('gestao/agenda/configuracoes/registros/rotinas/editar')}}" class="btn btn-success mr-3" >
                    <i class="fas fa-clipboard-list"></i>
                    Tarefas e atividades - Rotinas
                </a>
                <a href="{{url('gestao/agenda/configuracoes/etiquetas/editar')}}" class="btn btn-success mr-3" >
                    <i class="fas fa-tags"></i>
                    Etiquetas do calendário
                </a>


            </div>

        </div>
    </section>

@stop
