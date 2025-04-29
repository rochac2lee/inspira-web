<div class="row h-100 d-none animated fadeIn shadow-sm rounded pt-3" id="divEditarCurso" style="background-color: #FBFBFB;">

    <div id="divEnviando" class="w-100 text-center d-none">
        <div style="position: absolute;left 50%;top: 50%;left: 50%;transform: translate(-50%, -50%);">
            <i class="fas fa-spinner fa-pulse fa-3x text-primary mb-3"></i>
            <h4>Enviando</h4>
        </div>
    </div>
    <!-- Form novo, muitos codigos comentados a definir -->
    <div id="divEditar" class="w-100 d-none">
        <form class="w-100" id="formEditarCurso" action="{{ route('gestao.curso-salvar', ['idCurso' => $curso->id]) }}" method="post" enctype="multipart/form-data">

            @csrf
            <div class="col-12 col-lg-12 mb-5">
                <div class="form-group m-0">
                    <span class="d-block px-4 py-2" id="info-alterar-titulo">Título</span>
                    <input type="text" class="px-4 py-3 form-control rounded-0 text-break shadow-none border-bottom" maxlength="80" name="titulo" id="titulo" placeholder="Clique para alterar o título do {{$langCurso}}." style="font-size:1.5rem;" required="" value="{{ $curso->titulo }}">
                </div>

                <label for="capa" id="divFileInputCapa" class="file-input-area input-capa mt-3 mb-5 w-100 text-center text-nowrap" style="{{ $curso->capa != '' ? 'background-image: url('. config('app.cdn') .'/storage/uploads/cursos/capas/'. $curso->capa .');' : '' }}background-size: 50% 100%; background-repeat: no-repeat; background-position: 50% 100%;">
                    <input type="file" class="custom-file-input" value="{{$curso->capa}}" id="capa" name="capa" required style="height:  100%;position:  absolute;left:  0px;" accept="image/jpg, image/jpeg, image/png" oninput="mudouArquivoCapa(this);">
                    <h5 id="placeholder" class="text-white">
                        <i class="far fa-image fa-2x d-block text-white mb-2 w-100 w-100" style="vertical-align: sub;"></i>
                        CAPA DO {{strtoupper($langCurso)}}
                        <small class="text-uppercase d-block text-white small mt-2 mx-auto w-50" style="font-size:  70%;">
                            (Arraste o arquivo para esta área)
                            <br>
                            JPG ou PNG
                        </small>
                    </h5>
                    </h5>
                </label>

                <div class="form-group mb-3">
                    <label class="" for="descricao">Descrição</label>
                    <textarea name="descricao" class="form-control" id="descricao" maxlength="1000" placeholder="Clique para digitar." style="resize: none">{{$curso->descricao}}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label class="" for="descricao">Palavras-chave</label>
                    <input name="tag" id="tag" class="form-control" maxlength="255" value="{{$curso->tag}}" placeholder="Clique para digitar.">
                </div>

                @if(Auth::user()->permissao == "G" || Auth::user()->permissao == "Z" ||
                Auth::user()->privilegio_id == 2 || Auth::user()->privilegio_id == 1)
                <div class="form-group mb-3 mt-3">
                    <label for="tipo">Instituição</label>
                    <select class="custom-select form-control" name="instituicao_id" id="instituicao_id" required>
                        @foreach($instituicoes as $instituicao)
                        <option value="{{ $instituicao->id }}" {{ $curso->instituicao_id == $instituicao->id ? 'selected' : ''}}>
                            {{ ucfirst($instituicao->titulo) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="row">
                    <div class="col-6 col-lg-6">
                        @if(Auth::user()->permissao == "G" || Auth::user()->permissao == "Z" ||
                        Auth::user()->privilegio_id == 2 || Auth::user()->privilegio_id == 1)
                        <div class="form-group mb-3 mt-3">
                            <label for="tipo">Unidade Escolar</label>
                            <select class="custom-select form-control" name="escola_id" id="escola_id" required>
                                <option disabled="disabled" value="" selected>Selecione um tipo</option>
                                @foreach ($escolas as $escola)
                                <option value="{{ $escola->id }}" {{ $curso->escola_id == $escola->id ? 'selected' : ''}}>
                                    {{ ucfirst($escola->titulo) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
<!--
                    <div class="col-6 col-lg-6">
                        <div class="form-group my-3">
                            <label for="categoria">Visibilidade</label>
                            <select class="custom-select form-control" name="visibilidade" id="visibilidade" required>
                                <option dis abled="disabled" value="" selected>Selecione uma visibilidade</option>
                                @foreach($visibilidades as $visibilidade)
                                <option value="{{ $visibilidade->id }}" {{ $curso->visibilidade == $visibilidade->id ? 'selected' : ''}}>
                                    {{ ucfirst($visibilidade->titulo) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
-->
                </div>

                {{-- @if((strtoupper(Auth::user()->permissao) == "E" || strtoupper(Auth::user()->permissao) == "Z"))
                    <div class="form-group mb-3">
                        <label class="" for="senha">Senha do {{$langCurso}}<small>(opcional)</small></label>
                <input type="text" class="form-control" name="senha" id="senha" maxlength="50" aria-describedby="helpId" placeholder="Clique para digitar.">
            </div>
            @endif

            <div class="form-group mb-3">
                @if((strtoupper(Auth::user()->permissao) == "E" || strtoupper(Auth::user()->permissao) == "Z"))
                <label class="" for="preco">Preço do {{$langCurso}}(Opcional)</label>
                @else
                <label class="" for="preco">Preço do {{$langCurso}}(Opcional)</label>
                @endif
                <input type="text" class="form-control money" name="preco" id="preco" maxlength="12" aria-describedby="helpId" placeholder="Clique para digitar.">
            </div>

            <div class="form-group mb-3">
                <label class="" for="preco">Link para checkout (Opcional)</label>
                <input type="text" class="form-control" maxlength="150" name="link_checkout" id="link_checkout" aria-describedby="helpId" placeholder="Clique para digitar.">
            </div> --}}

            <div class="row">
                <div class="col-6 col-lg-6">
                    <div class="form-group mb-3">
                        <label class="" for="autor">Autor</label>
                        <input type="text" class="form-control" name="autor" id="autor" value="{{$curso->instituicao_id ==1 ? "Editora $appName" : strtoupper(Auth::user()->nome_completo)}}" readonly> </div>
                </div>

                {{-- <div class="form-group mb-3">
                    <label class="" for="periodo">Período do {{$langCurso}} (dias)</label>
                <label class="float-right" id="lblPeriodo" for="periodo">1</label>
                <input type="range" class="custom-range" min="1" max="366" value="0" name="periodo" id="periodo" oninput="mudouPeriodo(this);">
            </div> --}}

            {{-- <div class="form-group mb-3">
                    <label class="" for="vagas">Vagas do {{$langCurso}}</label>
            <label class="float-right" id="lblVagas" for="vagas">1</label>
            <input type="range" class="custom-range" min="1" max="101" value="0" name="vagas" id="vagas" oninput="mudouVagas(this);">
    </div> --}}

    <div class="col-6 col-lg-6">
        <div class="form-group mb-3">
            <label for="tipo">Etapa</label>
            <select class="custom-select form-control" name="ciclo_id" id="ciclo_id" required>
                <option disabled="disabled" value="" selected>Selecione uma etapa</option>
                @foreach($etapas as $etapa)
                <option value="{{ $etapa->id }}" {{ $curso->ciclo_id == $etapa->id ? 'selected' : ''}}>
                    {{ ucfirst($etapa->titulo) }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
</div> <!-- Fim row -->

<div class="row">
    <div class="col-6 col-lg-6">
        <div class="form-group mb-3 mt-3">
            <label for="tipo">Ano</label>
            <select class="custom-select form-control" name="cicloetapa_id" id="cicloetapa_id" required>
                <option disabled="disabled" value="" selected>Selecione um ano</option>
                @foreach($cicloEtapas as $cicloEtapa)
                <option value="{{ $cicloEtapa->id }}" {{ $curso->cicloetapa_id == $cicloEtapa->id ? 'selected' : ''}}>
                    {{ ucfirst($cicloEtapa->titulo) }}
                </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-6 col-lg-6">
        <div class="form-group mb-3 mt-3">
            <label for="tipo">Componente Curricular</label>
            <select class="custom-select form-control" name="disciplina_id" id="disciplina_id" required>
                <option disabled="disabled" value="" selected>Componente Curricular</option>
                @foreach($disciplinas as $disciplina)
                <option value="{{ $disciplina->id }}" {{ $curso->disciplina_id == $disciplina->id ? 'selected' : ''}}>
                    {{ ucfirst($disciplina->titulo) }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
</div><!-- Fim row -->

<input id="rasunho" value="false" type="text" hidden="">

<div class="container-fluid my-2">
    <div class="row">
        <div class="col-12 px-0">
            <button type="button" onclick="salvarCurso();" class="btn btn-primary font-weight-bold px-4">
                Salvar
            </button>
        </div>
    </div>
</div>

</form>
</div>
</div>
{{--


        <div class="p-3" style="background-color: #FBFBFB;min-height: calc(100vh - 284px);">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-lg-6">

                        <label for="capa" id="divFileInputCapa" class="file-input-area input-capa mt-3 mb-5 w-100 text-center text-nowrap" style="{{ $curso->capa != '' ? 'background-image: url('. config('app.local') .'/uploads/cursos/capas/'. $curso->capa .');' : '' }}background-size: contain;background-position: 50% 50%;background-repeat: no-repeat;">
<input type="file" class="custom-file-input" id="capa" name="capa" style="top: 0px;height:  100%;position:  absolute;left:  0px;" accept="image/jpg, image/jpeg, image/png" oninput="mudouArquivoCapa(this);">

<h5 id="placeholder" class="text-white d-none">
    <i class="far fa-image fa-2x d-block text-white mb-2 w-100" style="vertical-align: sub;"></i>
    CAPA DO CURSO
    <small class="text-uppercase d-block text-white small mt-2 mx-auto" style="font-size:  70%;">
        (Arraste o arquivo para esta área)
        <br>
        JPG ou PNG
    </small>
</h5>
<h5 id="file-name" class="float-left text-darker font-weight-bold text-break overflow-h" style="margin-top:152px;margin-bottom:20px; max-width:100%;">
</h5>
</label>

<div class="form-group mb-3 mt-2">
    <label class="" for="descricao_curta">Descrição curta do {{$langCurso}}</label>
    <textarea class="form-control" name="descricao_curta" id="descricao_curta" maxlength="1000" rows="3" placeholder="Clique para digitar." required="">{{ $curso->descricao_curta }}</textarea>
</div>

<div class="form-group mb-3">
    <label class="" for="descricao">Descrição do {{$langCurso}}</label>
    --}} {{-- <textarea class="form-control" maxlength="1000" name="descricao" id="descricao" rows="3" placeholder="Clique para digitar." required="">{{ $curso->descricao }}</textarea> --}}
    {{-- <textarea name="descricao" id="descricao" maxlength="1000" class="summernote">{{ $curso->descricao }}</textarea>
</div>

<div class="form-group mb-3">
    <label for="categoria">Categoria do {{$langCurso}}</label>
    <select class="custom-select form-control" name="categoria" id="categoria" required="">
        <option disabled="disabled" value="">Selecione uma categoria</option>
        @foreach ($categorias as $categoria)
        <option value="{{ $categoria->id }}" {{ $curso->categoria == $categoria->id ? 'selected=true' : '' }}>{{ ucfirst($categoria->titulo) }}</option>
        @endforeach
    </select>
</div>
</div>

<div class="col-12 col-lg-6">

    @if(Auth::user()->permissao == "G" || Auth::user()->permissao == "Z")
    <div class="form-group mb-3">
        <label for="tipo">Tipo de {{$langCurso}}</label>
        <select class="custom-select form-control" name="tipo" id="tipo" required>
            <option disabled="disabled">Selecione um tipo</option>
            <option value="1" {{ $curso->tipo == 1 ? 'selected=true' : '' }}>{{ucfirst($langCurso)}} padrão / Para alunos</option>
            <option value="2" {{ $curso->tipo == 2 ? 'selected=true' : '' }}>{{ucfirst($langCurso)}} para Professores / Gestores</option>
        </select>
    </div>
    @endif

    <div class="form-group mb-3">
        <label for="categoria">Visibilidade do {{$langCurso}}</label>
        <select class="custom-select form-control" name="visibilidade" id="visibilidade" required>
            @foreach($visibilidades as $visibilidade)
            <option value="{{ $visibilidade->id }}" {{ $curso->visibilidade == $visibilidade->id ? 'selected' : ''}}>
                {{ ucfirst($visibilidade->titulo) }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3">
        <label class="" for="senha">Senha do {{$langCurso}} <small>(opcional)</small></label>
        <input type="text" class="form-control" name="senha" id="senha" aria-describedby="helpId" placeholder="Clique para digitar." value="{{ $curso->senha }}">
    </div>

    <div class="form-group mb-3">
        <label class="" for="preco">Preço do {{$langCurso}} (Opcional)</label>
        <input type="text" class="form-control" name="preco" id="preco" aria-describedby="helpId" placeholder="Clique para digitar." value="{{ $curso->preco }}">
    </div>

    <div class="form-group mb-3">
        <label class="" for="link_checkout">Link para checkout (Opcional)</label>
        <input type="text" class="form-control" name="link_checkout" id="link_checkout" aria-describedby="helpId" placeholder="Clique para digitar." value="{{ $curso->link_checkout }}">
    </div>

    <div class="form-group mb-3">
        <label class="" for="identificador">Identificador externo (Opcional)</label>
        <input type="text" class="form-control" name="identificador" id="identificador" aria-describedby="helpId" placeholder="Clique para digitar." value="{{ $curso->identificador }}">
    </div>

    <div class="form-group mb-3">
        <label class="" for="periodo">Período do {{$langCurso}} (dias)</label>
        <label class="float-right" id="lblPeriodo" for="periodo">{{ $curso->periodo > 0 ? $curso->periodo : "Ilimitado"  }}</label>
        <input type="range" class="custom-range" min="0" max="365" value="{{ $curso->periodo }}" name="periodo" id="periodo" required="" oninput="mudouPeriodo(this);">
    </div>

    <div class="form-group mb-3">
        <label class="" for="vagas">Vagas do curso</label>
        <label class="float-right" id="lblVagas" for="vagas">{{ $curso->vagas > 0 ? $curso->vagas : "Ilimitado"  }}</label>
        <input type="range" class="custom-range" min="0" max="100" value="{{ $curso->vagas }}" name="vagas" id="vagas" required="" oninput="mudouVagas(this);">
    </div>

    --}}



</div>

{{--</div>

            </div>

        </div>--}}
