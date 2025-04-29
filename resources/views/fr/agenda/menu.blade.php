<div class="row">
    <div class="col-md-12 text-center">
        <h3>Administração da INspira Agenda</h3>
    </div>
    <div class="col-md-12 text-center mb-2 pl-0 pr-0">

        <div class="d-inline-block p-1 @if ( strpos(Request::path(),'/agenda/familia')!==false ) border rounded-lg @endif ">
            <a href="{{url('gestao/agenda/familia')}}">
               <img  class="img-fluid" src="{{config('app.cdn')}}/fr/imagens/icones/agenda/familia.webp">
                <p class="mt-1" ><small><b>Espaço da Família</b></small></p>
            </a>
        </div>
        <div class="d-inline-block p-1 @if ( strpos(Request::path(),'/agenda/comunicados')!==false ) border rounded-lg @endif" >
            <a href="{{url('gestao/agenda/comunicados')}}">
                <img  class="img-fluid" src="{{config('app.cdn')}}/fr/imagens/icones/agenda/comunicados.webp">
                <p class="mt-1"><small><b>Comunicados</b></small></p>
            </a>
        </div>
        <div class="d-inline-block p-1 @if ( strpos(Request::path(),'/agenda/tarefas')!==false ) border rounded-lg @endif">
            <a href="{{url('gestao/agenda/tarefas')}}">
                <img  class="img-fluid" src="{{config('app.cdn')}}/fr/imagens/icones/agenda/tarefas.webp">
                <p class="mt-1"><small><b>Tarefas e Atividades</b></small></p>
            </a>
        </div>
        <div class="d-inline-block p-1 @if ( strpos(Request::path(),'/agenda/noticias')!==false ) border rounded-lg @endif">
            <a href="{{url('gestao/agenda/noticias')}}">
                <img  class="img-fluid" src="{{config('app.cdn')}}/fr/imagens/icones/agenda/noticias.webp">
                <p class="mt-1"><small><b>Fotos e Notícias</b></small></p>
            </a>
        </div>
        <div class="d-inline-block p-1 @if ( strpos(Request::path(),'/agenda/registro')!==false ) border rounded-lg @endif">
            <a href="{{url('gestao/agenda/registros')}}">
                <img  class="img-fluid" src="{{config('app.cdn')}}/fr/imagens/icones/agenda/agenda.webp">
                <p class="mt-1"><small><b>Agenda e Registros</b></small></p>
            </a>
        </div>
        <div class="d-inline-block p-1 @if ( strpos(Request::path(),'/agenda/calendario')!==false ) border rounded-lg @endif">
            <a href="{{url('gestao/agenda/calendario')}}">
                <img  class="img-fluid" src="{{config('app.cdn')}}/fr/imagens/icones/agenda/calendario.webp">
                <p class="mt-1"><small><b>Calendário de Eventos</b></small></p>
            </a>
        </div>
        @if(auth()->user()->permissao != 'P')
            <div class="d-inline-block p-1 @if ( strpos(Request::path(),'/agenda/documentos')!==false ) border rounded-lg @endif">
                <a href="{{url('gestao/agenda/documentos')}}">
                    <img  class="img-fluid" src="{{config('app.cdn')}}/fr/imagens/icones/agenda/documentos.webp">
                    <p class="mt-1"><small><b>Documentos</b></small></p>
                </a>
            </div>
        @endif
        <div class="d-inline-block p-1 @if ( strpos(Request::path(),'/agenda/autorizacoes')!==false ) border rounded-lg @endif">
            <a href="{{url('gestao/agenda/autorizacoes')}}">
                <img  class="img-fluid" src="{{config('app.cdn')}}/fr/imagens/icones/agenda/autorizacoes.webp">
                <p class="mt-1"><small><b>Autorizações</b></small></p>
            </a>
        </div>
        @if(auth()->user()->permissao != 'P')
        <div class="d-inline-block p-1 @if ( strpos(Request::path(),'/agenda/enquetes')!==false ) border rounded-lg @endif">
            <a href="{{url('gestao/agenda/enquetes')}}">
                <img  class="img-fluid" src="{{config('app.cdn')}}/fr/imagens/icones/agenda/enquetes.webp">
                <p class="mt-1"><small><b>Enquetes e Pesquisas</b></small></p>
            </a>
        </div>

        <div class="d-inline-block p-1 @if ( strpos(Request::path(),'/agenda/canais-atendimento')!==false ) border rounded-lg @endif">
            <a href="{{url('gestao/agenda/canais-atendimento')}}">
                <img  class="img-fluid" src="{{config('app.cdn')}}/fr/imagens/icones/agenda/atendimento.webp">
                <p class="mt-1"><small><b>Canais de Atendimento</b></small></p>
            </a>
        </div>
        @endif
        @if(auth()->user()->permissao == 'I')
            <div class="d-inline-block p-1 @if ( strpos(Request::path(),'/agenda/configuracoes')!==false ) border rounded-lg @endif">
                <a href="{{url('gestao/agenda/configuracoes')}}">
                    <img  class="img-fluid" src="{{config('app.cdn')}}/fr/imagens/icones/agenda/configuracoes.webp">
                    <p class="mt-1"><small><b>Configurações</b></small></p>
                </a>
            </div>
        <!--
            <div class="d-inline-block p-1 @if ( strpos(Request::path(),'/agenda/configuracoes')!==false ) border rounded-lg @endif">
                <a href="{{url('gestao/agenda/relatorio/acesso')}}">
                    <img  class="img-fluid" src="{{config('app.cdn')}}/fr/imagens/icones/agenda/configuracoes.webp">
                    <p class="mt-1"><small><b>Acessos</b></small></p>
                </a>
            </div>
            -->
        @endif
    </div>
</div>
