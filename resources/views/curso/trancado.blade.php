@extends('layouts.master')

{{--  @section('title', ucfirst($langCurso))  --}}
@section('title', ucfirst($curso->titulo))

@section('headend')

    <!-- Custom styles for this template -->
    <style>
        body{
            background:  linear-gradient(to bottom, rgba(246, 247, 249, 0.98)  90%, rgba(246, 247, 249, 0.98)  90%), url('{{ config('app.cdn') }}/images/education-pattern.jpg') repeat;
        }

        header
        {
            padding: 154px 0 100px;
        }

        @media (min-width: 992px)
        {
            header
            {
                padding: 156px 0 100px;
            }
        }

        .list-group
        {
            font-size: 16px;
        }

        .list-group-item
        {
            border: 0px;
        }

        #listAulas .list-group-item
        {
            border-left: 8px solid #ddd;
        }

        #listAulas .list-group-item:first-child
        {
            border-radius: 0px;
            border-left: 8px solid var(--primary-color);
        }

        .list-group-item:first-child, .list-group-item:last-child
        {
            border-radius: 0px;
        }

    </style>

@endsection

@section('content')


<main role="main">

    @if(Session::has('previewMode'))
        <div class="text-center p-3 text-white" style="background-color: #f3aa3d;position: sticky;left: 0px;top: 0px;z-index: 1;">
            <a href="{{ route('gestao.curso-conteudo', ['curso_id' => $curso->id]) }}" class="ml-2 text-white small position-absolute" style="left:0;top:6px;"> <i class="fas fa-chevron-left"></i> Voltar </a>
            <h6 class="d-inline-block align-middle my-auto">
                <small>{{ucfirst($langCurso)}} não publicado <strong>(Visão do aluno)</strong></small>
            </h6>
            <button type="button" class="btn btn-light bg-transparent border-0 p-1 float-right mr-5 my-auto align-middle" onclick="$(this).parent().remove();">
                <i class="far fa-times-circle fa-2x text-white"></i>
            </button>
        </div>
    @endif

    <div class="container-fluid">

        <div class="row">

            <div class="col-10 col-md-10 col-lg-8 mx-auto">

                <div class="row my-4">

                    <div class="col-12 col-md-8 text-center mx-auto">
                        <h4 class="my-3">
                            <span class="text-light font-weight-bold">{{ ucfirst($curso->titulo) }}</span>
                        </h4>

                        <div style="background-image: url('{{ config('app.local') . '/storage/uploads/cursos/capas/' .  $curso->capa }}');background-size: cover;background-position: 50% 50%;background-repeat: no-repeat;border:0px;width:100%;height:20vh;position:relative;border-radius:8px;box-shadow: 0px 2px 6px rgba(0,0,0,0.16);">
                        </div>

                        <div class="d-inline-block my-3 align-middle">
                            <i class="fas fa-lock fa-fw fa-2x mb-3 text-danger"></i>
                            <p class="font-weight-bold text-dark">{{ucfirst($langCurso)}} trancado</p>

                            <form action="{{ route('curso.trancado-acessar', ['idCurso' => $curso->id]) }}" method="post">

                                @csrf

                                <div class="form-group">
                                    <input type="password" name="senha" id="txtSenha" class="form-control" placeholder="Senha do curso.">
                                </div>

                                <button type="submit" name="" id="" class="btn btn-primary btn-block">Acessar</button>

                            </form>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</main>

@endsection

@section('bodyend')

    <script>

        $( document ).ready(function()
        {

        });

    </script>

@endsection
