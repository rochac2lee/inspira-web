@extends('fr.master')
@section('content')



<section class="section section-interna" style=" margin-bottom:30px">
    <div class="container">
        <div class="row pt-4 pb-4">
            <div class="col-md-8">
                <h3>
                    <a href="{{ url('/gestao/instituicao')}}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    Gestão de Material <br><small>{{$instituicao->titulo}}</small>
                </h3>
            </div>
        </div>
        <div class="quadro-atividades">
            <div class="row d-flex">
                <div class="col-sm-6 col-md-2 my-4">
                    <a href="{{url('/gestao/instituicao/'.$instituicao->id.'/material/colecaoLivro')}}" class="item">
                        <img src="{{config('app.cdn')}}/fr/imagens/icones/livro_didático.png" />
                        <span class="title">Livro Digital</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-2 my-4">
                    <a href="{{url('/gestao/instituicao/'.$instituicao->id.'/material/colecaoAudio')}}" class="item">
                        <img src="{{config('app.cdn')}}/fr/imagens/icones/audio.png" />
                        <span class="title">Áudio</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-2 my-4">
                    <a href="{{url('/gestao/instituicao/'.$instituicao->id.'/material/colecaoProva')}}" class="item">
                        <img src="{{config('app.cdn')}}/fr/imagens/icones/provas_bimestrais.png" />
                        <span class="title">Provas</span>
                    </a>
                </div>
                <!--
                <div class="col-sm-6 col-md-2 my-4">
                    <a href="{{url('/gestao/instituicao/'.$instituicao->id.'/material/colecaoDocumento')}}" class="item">
                        <img src="{{config('app.cdn')}}/fr/imagens/icones/documentos_oficiais.png" />
                        <span class="title">Documentos Oficiais</span>
                    </a>
                </div>
                -->
            </div>
        </div>
    </div>
</section>
@stop
