@extends('layouts.master')

@section('title', 'Cursos livres')

@section('headend')

<!-- Custom styles for this template -->
<style>
    header {
        padding: 154px 0 100px;
    }

    @media (min-width: 992px) {
        header {
            padding: 156px 0 100px;
        }
    }

    .capa-curso {
        min-height: 160px;
        border-radius: 10px 10px 0px 0px;
        background-image: url('{{ config('app.cdn') }}/images/default-cover.jpg');
        background-size: cover;
        background-position: 50% 50%;
        background-repeat: no-repeat;

            {
                {
                -- margin-left: -15px;
                --
            }
        }

            {
                {
                -- width: calc(100% + 30px) !important;
                --
            }
        }
    }

    .card-footer {
        border-radius: 0px 0px 10px 10px;
    }

    .input-group input.text-secondary::placeholder {
        color: #989EB4;
    }
</style>

@endsection

@section('content')

<main role="main">

    <div class="container">

        <div class="row" style="height: 100%; min-height: calc(100vh - 114px);">

            {{-- @include('gestao.side-menu')  --}}

            <div id="divMainMenu" class="col-12 p-0" style="width: calc(100% - 1px); flex: inherit; transition: 0.3s all ease-in-out;">

                <div class="col-12 col-md-11 mb-4 mx-auto pt-4">

                    <div class="col-12 mb-3 title pl-0">
                        <h2>Cursos Livres</h2>
                    </div>

                    <div class="row d-flex align-items-center mb-4">
                        @if(count($cursos) > 0)
                        <div class="row w-100 mx-0">
                            <div class="col-md-12 col-lg-6 mb-2 mb-lg-0">
                                @include('components.search-input', ['placeholder' => 'Procurar curso...'])
                            </div>
                        </div>
                        @endif
                    </div>

                    @if(Request::has('pesquisa'))
                    @include('components.search-response', ['pesquisa' => Request::get('pesquisa')])
                    @endif

                </div>

                <div class="col-12 col-md-11 mx-auto">

                    <div class="card-deck">
                        @foreach ($cursos as $curso)

                        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4 px-0 px-md-3">

                            <div class="card rounded-10 shadow-sm border-0 h-100 px-0 mx-0">
                                <div class="w-100 py-5 capa-curso" style="{{ $curso->capa != '' ? 'background-image: url('. config('app.local') .'/uploads/cursos/capas/'. $curso->capa .');' : '' }}">
                                </div>

                                <div class="card-body pb-0">
                                    <h6 class="card-title text-dark text-truncate w-100" style="width: 70%; overflow: hidden;	text-overflow: ellipsis; white-space: wrap;	display: block;" data-toggle="tooltip" data-placement="right" title="{{$curso->titulo}}">
                                        {{ $curso->titulo }} -
                                        {{ $curso->preco > 0 ? 'R$ ' . number_format($curso->preco, 2, ',', '.') : 'Gratuito' }}
                                    </h6>

                                    <p class="small text-lightgray my-2">
                                        <i class="fas fa-circle {{ $curso->status == 0 ? 'text-lightgray' : 'text-accepted' }} mr-1"></i>
                                        {{ $curso->status_name }}
                                        {{--•
                                        <i class="fas fa-eye{{ $curso->visibilidade == 0 ? '-slash' : '' }} mr-1"></i>
                                        {{ $curso->visibilidade == 1 ? 'Listado' : 'Não listado' }}
                                        •
                                        {{ $curso->matriculados }} Matriculado{{ $curso->matriculados != 1 ? 's' : '' }} --}}
                                    </p>
                                    <p class="text-secondary font-weight-bold">
                                        {{-- <span class="text-truncate w-75 d-block font-weight-normal">{{ ucfirst($curso->descricao_curta) }}</span> --}}
                                        {{-- @if(Auth::user()->id != $curso->user->id) --}}
                                        <small class="d-block">
                                            {{ ucfirst($curso->user->name) }}
                                        </small>
                                        {{-- @endif --}}
                                    </p>
                                    @if(Auth::user()->permissao == "Z")
                                    <p class="text-secondary font-weight-bold">
                                        Consumo: {{ number_format( $curso->consumo , 2, ",", ".") }} GB
                                    </p>
                                    @endif
                                </div>

                                <div class="card-footer bg-white">
                                    <div class="text-center">
                                        <a href="{{ route('curso', ['idCurso' => $curso->id]) }}" data-toggle="tooltip" data-placement="top" title="Visualizar curso livre" class="btn btn-cards">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>

                        @endforeach

                    </div>
                    {{ $cursos->links() }}

                </div>

                <div class="col-12 col-md-11 mx-auto">
                    @if(count($cursos) == 0)
                    <div class="col-12 px-0 mb-4">
                        <div class="card-curso box-shadow rounded-10 p-3">
                            <h5>
                                Nenhum curso livre para exibir.
                            </h5>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

</main>

@endsection

@section('bodyend')

<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.pt-BR.min.js">
</script>

@endsection