@extends('fr/master')
@section('content')
<style type="text/css">
    .flipLivro{
        position: relative; display: block; height: 800px;
    }

</style>

    <section class="section section-interna">
        <div class="container-fluid">
            <h2 class="title-page">
            <a href="javascript: window.history.back();" title="Voltar" class="btn btn-secondary"><i class="fas fa-arrow-left"></i></a>
            Livro Didático Digital</h2>
            <h4></h4>
            <div class="subtitle-page ">{{$livro->titulo}}</div>
        </div>

        @include('fr/colecaoLivro/flip_livro')

    </section>
@stop
