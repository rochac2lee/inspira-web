@extends('fr/master')
@section('content')
<section class="section section-interna">
    <div class="container-fluid">

        <h2 class="title-page">
            <a href="{{url('/')}}" class="btn btn-secondary" style="float: left; margin-right: 10px">
                <i class="fas fa-arrow-left"></i>
            </a>
            Busca INteligente<p class="mb-0" style="font-size: 13px">Buscando por: <i>{{Request::input('busca')}}</i></p>
        </h2>
        <h6 style="margin-bottom: 14px;">
            <ul class="nav nav-tabs">
                <li class="nav-item" >
                    <a @if($dados['livros']==0 )style="color: #CCCCCC;" @endif href="{{url('busca_geral?busca='.Request::input('busca'))}}" class="nav-link  @if(Request::input('tipo') == 'livros'|| Request::input('tipo') == '') active @endif ">Livros Digitais <small>({{$dados['livros']}})</small></a>
                </li>
                <li class="nav-item">
                    <a @if($dados['jogos']==0 )style="color: #CCCCCC;" @endif href="{{url('busca_geral?busca='.Request::input('busca'))}}&tipo=jogos" class="nav-link @if(Request::input('tipo') == 'jogos') active @endif">Jogos <small>({{$dados['jogos']}})</small></a>
                </li>
                <li class="nav-item">
                    <a @if($dados['simuladores']==0 )style="color: #CCCCCC;" @endif href="{{url('busca_geral?busca='.Request::input('busca'))}}&tipo=simuladores" class="nav-link @if(Request::input('tipo') == 'simuladores') active @endif">Simuladores <small>({{$dados['simuladores']}})</small></a>
                </li>
                <li class="nav-item">
                    <a @if($dados['videos']==0 )style="color: #CCCCCC;" @endif href="{{url('busca_geral?busca='.Request::input('busca'))}}&tipo=videos" class="nav-link @if(Request::input('tipo') == 'videos') active @endif">Vídeos <small>({{$dados['videos']}})</small></a>
                </li>
                <li class="nav-item">
                    <a @if($dados['apresentacoes']==0 )style="color: #CCCCCC;" @endif href="{{url('busca_geral?busca='.Request::input('busca'))}}&tipo=apresentacoes" class="nav-link @if(Request::input('tipo') == 'apresentacoes') active @endif">Apresentações <small>({{$dados['apresentacoes']}})</small></a>
                </li>
                <li class="nav-item">
                    <a @if($dados['audios']==0 )style="color: #CCCCCC;" @endif href="{{url('busca_geral?busca='.Request::input('busca'))}}&tipo=audios" class="nav-link @if(Request::input('tipo') == 'audios') active @endif">Áudios <small>({{$dados['audios']}})</small></a>
                </li>
                <li class="nav-item">
                    <a @if($dados['imagens']==0 )style="color: #CCCCCC;" @endif href="{{url('busca_geral?busca='.Request::input('busca'))}}&tipo=imagens" class="nav-link @if(Request::input('tipo') == 'imagens') active @endif">Imagens <small>({{$dados['imagens']}})</small></a>
                </li>
                @if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')
                    <li class="nav-item">
                        <a @if($dados['provas']==0 )style="color: #CCCCCC;" @endif href="{{url('busca_geral?busca='.Request::input('busca'))}}&tipo=provas" class="nav-link @if(Request::input('tipo') == 'provas') active @endif">Provas <small>({{$dados['provas']}})</small></a>
                    </li>
                    <li class="nav-item">
                        <a @if($dados['questoes']==0 )style="color: #CCCCCC;" @endif href="{{url('busca_geral?busca='.Request::input('busca'))}}&tipo=questoes" class="nav-link @if(Request::input('tipo') == 'questoes') active @endif">Questões <small>({{$dados['questoes']}})</small></a>
                    </li>
                @endif
                <li class="nav-item">
                    <a @if($dados['quizzes']==0 )style="color: #CCCCCC;" @endif href="{{url('busca_geral?busca='.Request::input('busca'))}}&tipo=quizzes" class="nav-link @if(Request::input('tipo') == 'quizzes') active @endif">Quizzes <small>({{$dados['quizzes']}})</small></a>
                </li>
            </ul>
        </h6>
        <div class="row">
            @if(count($dados['dados'])>0)
                <section class=" w-100">
                    <div class="table-responsive table-hover">
                        <table class="table table-striped">
                            <tbody>
                            @foreach($dados['dados'] as $d)
                                @php
                                    if(Request::input('tipo') == 'questoes')
                                        $url = url('/gestao/avaliacao/minhas_questoes?id='.$d->id);
                                    elseif(Request::input('tipo') == 'livros'|| Request::input('tipo') == '')
                                        $url = url('colecao_livro/'.$d->colecao_livro_id.'/livros?id='.$d->id);
                                    elseif(Request::input('tipo') == 'quizzes')
                                        $url = url('/gestao/quiz?biblioteca=1&id='.$d->id);
                                    else
                                        $url = url('/editora/conteudos?conteudo='.$d->tipo.'&id='.$d->id);
                                @endphp

                                <tr onclick="exibir('{{$url}}')">
                                    <td>
                                        @if(Request::input('tipo') != 'questoes')
                                            {{$d->titulo}}
                                        @else
                                            {{substr(trim(html_entity_decode(strip_tags($d->pergunta))),0,60)}}...
                                        @endif

                                    </td>
                                    <td>{{$d->ciclo}} / {{$d->etapa}}</td>
                                    <td>{{$d->disciplina}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @else
                @php
                    switch (Request::input('tipo')) {
                        case 'jogos':
                            $rotulo = 'Jogos';
                            break;
                        case 'videos':
                            $rotulo = 'Vídeos';
                            break;
                        case 'apresentacoes':
                            $rotulo = 'Apresentações';
                            break;
                        case 'audios':
                            $rotulo = 'Áudios';
                            break;
                        case 'imagens':
                            $rotulo = 'Imagens';
                            break;
                        case 'provas':
                            $rotulo = 'Provas';
                            break;
                        case 'simuladores':
                            $rotulo = 'Simuladores';
                            break;
                        case 'quizzes':
                            $rotulo = 'Quizzes';
                            break;
                        default:
                            $rotulo = 'Livros Digitais';
                    }
                @endphp
                <div class="col">
                    <div class="card text-center">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-exclamation-circle"></i>
                                Objetos educacionais digitais não localizados na seção {{$rotulo}}.
                            </h5>
                            <p class="card-text">Sugerimos que faça uma nova combinação de palavras-chave para localizar conteúdos digitais INspiradores!</p>
                        </div>
                        <div class="card-footer text-muted"></div>
                    </div>
                </div>
            @endif
        </div>
        <nav class="mt-4" aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                {{ $dados['dados']->appends(Request::all())->links() }}
            </ul>
        </nav>
    </div>
</section>
    <script>
        function exibir(url){
            window.location.href = url;
        }
    </script>
@stop
