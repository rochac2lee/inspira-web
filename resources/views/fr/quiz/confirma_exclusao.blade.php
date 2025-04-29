@extends('fr/master')
@section('content')
<section class="section section-interna">
    <div class="container">
        <h2 class="title_page">
            <a href="{{url('/quiz/colecao')}}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            Exclusão de Quiz
        </h2>
        <div class="col">
            <div class="card text-center">
                <div class="card-header"></div>
                <div class="card-body">
                    <h5 class="card-title mt-2" style="color:red"><i class="fas fa-exclamation-circle"></i> ATENÇÃO</h5>
                    <p class="card-text " style="font-size: 15px">
                        <b>Já existem respostas nesse quiz.</b>
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <img id="imgLogo" width="328px" src="{{ config('app.cdn')}}/storage/quiz/{{$dados->id}}/capa/{{$dados->capa}}" >
                            </div>
                            <div class="col-12 mt-3">
                                <p style="font-size: 20px">{{$dados->titulo}}</p>
                            </div>
                        </div>
                        <br>
                        Se você excluí-lo esses dados serão perdidos definitivamente.<br><br><b>Tem certeza que deseja excluir esse quiz?</b></p>
                    <form action="" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$dados->id}}">
                        <a href="{{session('UrlPreviousExcluir')}}" class="btn btn-secondary" data-dismiss="modal">Não</a>

                        <button type="submit" class="btn btn-danger">Sim, excluir</button>
                    </form>
                </div>
                <div class="card-footer text-muted"></div>
            </div>
        </div>
    </div>
</section>
@stop
