@extends('fr/master')
@section('content')
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.jquery.min.js"></script>
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/slim_image_cropper/slim/slim.css">

    <script src="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.js"></script>
    <link href="{{config('app.cdn')}}/fr/includes/js/slim-select/1.23.0/slimselect.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/froala_editor.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/froala_style.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/code_view.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/draggable.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/colors.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/emoticons.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/line_breaker.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/table.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/char_counter.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/video.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/fullscreen.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/file.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/quick_insert.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/help.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/third_party/spell_checker.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/plugins/special_characters.css">
    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/froala_editor_v4/css/codemirror.min.css">
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/codemirror.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/xml.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/purify.min.js"></script>

    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/froala_editor.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/align.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/code_beautifier.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/code_view.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/colors.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/draggable.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/font_size.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/font_family.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/line_breaker.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/inline_style.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/lists.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/paragraph_format.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/paragraph_style.min.js"></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/word_paste.min.js"></script>
    <script type="text/javascript" src='{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/languages/pt_br.js'></script>
    <script type="text/javascript" src="{{config('app.cdn')}}/fr/includes/froala_editor_v4/js/plugins/special_characters.min.js"></script>    <!--  FIM Froala Editor  -->

    <!-- Codigo para adicionar a matemática-->
    <script type="text/javascript" src="{{url('/fr/includes/froala_editor_v4/node_modules/wiris/mathtype-froala3/wiris.js')}}"></script>

    <!-- Codigo para mostrar o resultado --->
    <script type="text/javascript" src="{{url('/fr/includes/froala_editor_v4/js/plugins/froala_wiris/integration/WIRISplugins.js?viewer=image')}}"></script>

    <script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

    <link rel="stylesheet" href="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.css">
    <script src="{{config('app.cdn')}}/fr/includes/js/vanillaSelectBox/vanillaSelectBox_v3.js"></script>

    <style>
        #resultado-questoes, #avaliacao{
            height: 250px;
            overflow-y: scroll;
        }

        #resultado-questoes .adicionar, #avaliacao .remover{
            display: initial;
        }
        #resultado-questoes .remover, #avaliacao .adicionar{
            display: none;
        }
        .esconde{
            display: none;
        }
    </style>
    <section class="section section-interna">
        <div class="container-fluid" >
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="title-page">
                            <a  href="{{url('/gestao/trilhass/')}}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            @if ( strpos(Request::path(),'editar')===false )Nova trilha @else Editar trilha @endif
                        </h3>
                    </div>
                </div>
                <div class="row">
                    <div class="@if(auth()->user()->permissao == 'Z') col-md-4 @else col-md-6 @endif p-0 bg-light border-right p-4">
                        <h4 class="pb-3 border-bottom mb-4">Roteiros</h4>
                        <h6 class="text-center">Biblioteca de Roteiros</h6>
                        <div class="shadow-none p-3 mb-5 bg-white rounded">
                            <div class="card mb-3 shadow-none rounded" >
                                <div id="buscarQuestoes">
                                    <div class="card-body">
                                        <form id="formBuscaRoteiro">
                                            <div id="roteirosParaBusca">
                                                @if(old('roteiros') != '')
                                                    @foreach(old('roteiros') as $r)
                                                        <input id="roteiroBusca{{$r}}" type="hidden" name="notId[]" value="{{$r}}">
                                                    @endforeach
                                                @else
                                                    @foreach($roteirosSelecionadosId as $r)
                                                        <input id="roteiroBusca{{$r}}" type="hidden" name="notId[]" value="{{$r}}">
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-9">
                                                    <input id="busca" name="texto" type="text" placeholder="Título" class="form-control form-control-sm rounded" />
                                                </div>
                                                <div class="form-group col-md-3 text-right">
                                                    <button type="button" class="btn btn-sm btn-secondary " onclick="buscarRoteiros()">Buscar</button>
                                                </div>
                                                <div class="form-group col-md-12 text-right">
                                                    <a href="javascript:void(0)" onclick="limparBusca()">Limpar Busca</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div id="resultado-questoes" class="box-container">
                                <ul id="roteiroBiblioteca" class="list-group list-group-sortable-connected" style="padding-right: 8px">
                                    Aguarde Carregando ...
                                </ul>
                            </div>
                            <br>
                            <div class="pb-2 fs-13">Exibindo <span class="exibindoRoteiros">30</span> de <b class="totalRoteiro"></b> roteiros encontrados</div>

                            <nav class="mt-1" aria-label="Page navigation example">
                                <ul  class="pagination justify-content-center">
                                    <li class="page-item">
                                        <button type="button" class="page-link" onclick="maisRoteiros()" tabindex="-1">Carregar mais roteiros</button>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        <h6 class="text-center"> Roteiros Selecionados</h6>
                        <div class="row mb-4">

                            <div class="col">
                                <div class="shadow-none p-3 mb-0 bg-white rounded" id="avaliacao">
                                    <ul class="list-group list-group-sortable-connected" id="questaoAvaliacao">
                                        {!! $roteirosSelecionados !!}
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <span class="fs-12">* Para ordenar clique no roteiro e raste para a aposição desejada.</span>
                    </div>
                    <div class="@if(auth()->user()->permissao == 'Z') col-md-8 @else col-md-6 @endif">
                        <form id="formRoteiro" action="" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{old('id',@$dados->id)}}">
                            <div id="roteirosParaSeremSalvos">
                                @foreach($roteirosSelecionadosId as $r)
                                    <input id="roteiroSalvar{{$r}}" type="hidden" name="roteiros[]" value="{{$r}}">
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="@if(auth()->user()->permissao == 'Z') col-md-7  @else col-md-12 @endif">
                                    <div class="pt-4">
                                        <h4 class="pb-3 border-bottom mb-4">Dados da Trilha</h4>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label  for="">* Título</label>
                                                <input type="text" name="titulo" placeholder="" value="{{old('titulo',@$dados->titulo)}}" class="form-control rounded {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
                                                <div class="invalid-feedback">{{ $errors->first('titulo') }}</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>* Componente Curricular</label>
                                                <select name="disciplina_id" class="multipleComponente" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
                                                    <option value="">Selecione</option>
                                                    @foreach($disciplinas as $d)
                                                        <option @if( $d->id == old('disciplina_id',@$dados->disciplina_id)) selected @endif value="{{$d->id}}">{{$d->titulo}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback @if($errors->first('disciplina_id'))d-block @endif">{{ $errors->first('disciplina_id') }}</div>
                                            </div>
                                            <div class="form-group">
                                                <label>* Etapa / Ano</label>
                                                <select name="ciclo_etapa_id" class="multipleEtapa" style="border: 1px solid #ffb100; border-radius: 0.4rem;">
                                                    <option value="">Selecione</option>
                                                    @foreach($ciclo as $c)
                                                        <option @if( $c->ciclo_id.';'.$c->id == old('ciclo_etapa_id',@$dados->ciclo_id.';'.@$dados->cicloetapa_id)) selected @endif value="{{$c->ciclo_id.';'.$c->id}}">{{$c->ciclo}} - {{$c->ciclo_etapa}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback @if($errors->first('ciclo_etapa_id'))d-block @endif">{{ $errors->first('ciclo_etapa_id') }}</div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <label for="">* Capa</label>
                                            <div class="form-group">
                                                <div id="logoCadastro" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                                    <input type="hidden" name="existeImg" id="existeImg" value="{{old('existeImg',@$dados->capa)}}">
                                                    <img class="img-fluid" id="imgLogo" src="{{@$dados->url_capa}}" >
                                                    <br>
                                                    <a class="btn btn-secondary" onclick="excluirLogo()">Excluir Capa</a>
                                                </div>
                                                <div id="novaLogo" class="form-group imagem-file-roteiro bg-secondary text-white rounded p-1 text-center">
                                                    <input type="file" accept="image/*" name="imagem" class="myCropper">
                                                </div>
                                                <div class="invalid-feedback @if($errors->first('imagem'))d-block @endif">{{ $errors->first('imagem') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="">Descrição</label>
                                        <textarea rows="6" name="descricao" id="froala" class="form-control rounded {{ $errors->has('descricao') ? 'is-invalid' : '' }}">{{old('descricao',@$dados->descricao)}}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Palavras-chave</label>
                                        <input type="text" name="tag" class="form-control rounded {{ $errors->has('tag') ? 'is-invalid' : '' }}" value="{{old('tag',@$dados->tag)}}" />
                                        <small class="form-text w-100 text-muted">
                                            As palavras-chave devem ser separadas por hífen " - ".
                                        </small>
                                        <div class="invalid-feedback @if($errors->first('tag'))d-block @endif">{{ $errors->first('tag') }}</div>
                                    </div>
                                    <div class="form-group">
                                        @php
                                            $autor = old('autor',@$dados->autor);
                                            if(auth()->user()->permissao == 'Z' && $autor == ''){
                                                $autor = 'Editora Opet';
                                            }
                                        @endphp
                                        <label>Autor</label>
                                        <input type="text" name="autor" class="form-control rounded {{ $errors->has('autor') ? 'is-invalid' : '' }}" value="{{$autor}}" />
                                        <div class="invalid-feedback @if($errors->first('autor'))d-block @endif">{{ $errors->first('autor') }}</div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <a href="{{url('gestao/trilhass')}}"  class="btn btn-secondary float-left">Cancelar</a>
                                        <button class="btn btn-default mt-0 float-right ml-2 mb-4">Salvar</button>
                                    </div>
                                </div>
                                @if(auth()->user()->permissao == 'Z')
                                    <div class="col-md-5">
                                    <div class="pt-4">
                                        <h4 class="pb-3 border-bottom mb-4">Permissionamento</h4>
                                    </div>
                                    <div class="form-group ">
                                        <div class="custom-control custom-switch">
                                            <input name="ead" @if(old('ead', @$dados->ead) == 1) checked @endif value="1" type="checkbox" class="custom-control-input" id="ead" onchange="$('#adicionarEad').toggleClass('d-none')">
                                            <label class="custom-control-label pt-1" for="ead">EAD</label>
                                        </div>
                                    </div>

                                    <span id="adicionarEad"  @if(old('ead', @$dados->ead) != 1) class="d-none" @endif>
                                        <div class="form-group">
                                            <label>* Avaliação final</label>
                                            <select name="avaliacao_id" class="form-control rounded {{ $errors->has('avaliacao_id') ? 'is-invalid' : '' }}">
                                                @if(count($avaliacao)>0)
                                                    <option value=""  selected >Selecione</option>
                                                    @foreach($avaliacao as $a)
                                                        <option value="{{$a->id}}" @if($a->id == old('avaliacao_id')) selected @endif>{{$a->titulo}}</option>
                                                    @endforeach
                                                @else
                                                    <option value=""  selected >Nenhuma avaliação encontrada</option>
                                                @endif
                                            </select>
                                            <div class="invalid-feedback @if($errors->first('avaliacao_id'))d-block @endif">{{ $errors->first('avaliacao_id') }}</div>
                                        </div>
                                        <div class="form-group" >
                                            <label>Carga horária</label>
                                            <input type="number" name="carga_horaria" class="form-control rounded {{ $errors->has('carga_horaria') ? 'is-invalid' : '' }}" value="{{old('carga_horaria',@$dados->carga_horaria)}}" />
                                            <div class="invalid-feedback @if($errors->first('carga_horaria'))d-block @endif">{{ $errors->first('carga_horaria') }}</div>
                                        </div>
                                        <div class="form-group">
                                            @php
                                                $perfilPermissao = '';
                                                if(isset($dados->perfil_permissao_realizar)){
                                                    $perfilPermissao = $dados->perfil_permissao_realizar;
                                                }
                                            @endphp
                                            <label>* Permissão para perfil</label>
                                            <select id="perfil" name="perfil_permissao_realizar[]" multiple class="form-control multiplePerfil" size="6">
                                                <option value="Z" @if(str_contains($perfilPermissao, 'Z')) selected @endif>Super usuário</option>
                                                <option value="I" @if(str_contains($perfilPermissao, 'I')) selected @endif>Gestor Institucional</option>
                                                <option value="C" @if(str_contains($perfilPermissao, 'C')) selected @endif>Coordenador</option>
                                                <option value="P" @if(str_contains($perfilPermissao, 'P')) selected @endif>Docente</option>
                                                <option value="A" @if(str_contains($perfilPermissao, 'A')) selected @endif>Estudante</option>
                                            </select>
                                            <div class="invalid-feedback @if($errors->first('perfil_permissao_realizar'))d-block @endif">{{ $errors->first('perfil_permissao_realizar') }}</div>
                                        </div>
                                        <div class="form-group">
                                            <label>Esta trilha será visualizado por: *</label>
                                            <select class="form-control" name="tipo_permissao_realizar" id="tipo_permissao_realizar" onchange="mudaVisualizacao(this);">
                                                <option @if(old('tipo_permissao_realizar', @$dados->tipo_permissao_realizar) == 1) selected @endif value="1"> Todas as instituições e escolas da rede </option>
                                                <option @if(old('tipo_permissao_realizar', @$dados->tipo_permissao_realizar) == 2) selected @endif value="2"> Todas as instituições e escolas PÚBLICAS  </option>
                                                <option @if(old('tipo_permissao_realizar', @$dados->tipo_permissao_realizar) == 3) selected @endif value="3"> Todas as instituições e escolas PRIVADAS </option>
                                                <option @if(old('tipo_permissao_realizar', @$dados->tipo_permissao_realizar) == 4) selected @endif value="4">
                                                        Adicionar e selecionar instituições e escolas específicas
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">{{ $errors->first('tipo_permissao_realizar') }}</div>
                                        </div>
                                        <div class="form-group" id="btnEscolaTurma">
                                            <button type="button" class="btn btn-sm btn-success" onclick="tipoModal=0; $('#formEscolasTrumas').modal('show')"><i class="fas fa-plus"></i> Adicione instituições e escolas</button>
                                        </div>
                                        <h6 class="text-center">Instituições e escolas selecionadas *</h6>
                                        <div class="row mb-4">
                                            <div class="col">
                                                <ul id="listaPermissao" class="list-style">

                                                </ul>
                                                <div class="invalid-feedback" style="display: block">{{ $errors->first('instituicao') }}</div>
                                            </div>
                                        </div>
                                    </span>
                                    <hr>
                                    <div class="form-group ">
                                        <div class="custom-control custom-switch">
                                            <input name="permite_biblioteca" @if(old('permite_biblioteca', @$dados->permite_biblioteca) == 1) checked @endif value="1" type="checkbox" class="custom-control-input" id="permite_biblioteca" onchange="$('#adicionarBiblioteca').toggleClass('d-none')">
                                            <label class="custom-control-label pt-1" for="permite_biblioteca">Adicionar na biblioteca</label>
                                        </div>
                                    </div>
                                    <span id="adicionarBiblioteca" @if(old('permite_biblioteca', @$dados->permite_biblioteca) != 1) class="d-none" @endif >

                                        <div class="form-group">
                                            <label>Esta trilha será disponibilizada na biblioteca de: *</label>
                                            <label>(mediante publicação)</label>
                                            <select class="form-control" name="tipo_permissao_biblioteca" id="tipo_permissao_biblioteca" onchange="mudaVisualizacaoBiblioteca(this); ">
                                                <option @if(old('tipo_permissao_biblioteca', @$dados->tipo_permissao_biblioteca) == 1) selected @endif value="1"> Todas as instituições e escolas da rede </option>
                                                <option @if(old('tipo_permissao_biblioteca', @$dados->tipo_permissao_biblioteca) == 2) selected @endif value="2"> Todas as instituições e escolas PÚBLICAS  </option>
                                                <option @if(old('tipo_permissao_biblioteca', @$dados->tipo_permissao_biblioteca) == 3) selected @endif value="3"> Todas as instituições e escolas PRIVADAS </option>
                                                <option @if(old('tipo_permissao_biblioteca', @$dados->tipo_permissao_biblioteca) == 4) selected @endif value="4">
                                                        Adicionar e selecionar instituições e escolas específicas
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">{{ $errors->first('tipo_permissao_biblioteca') }}</div>
                                        </div>
                                        <div class="form-group" id="btnEscolaTurmaBiblioteca">
                                            <button type="button" class="btn btn-sm btn-success" onclick="tipoModal = 1; $('#formEscolasTrumas').modal('show'); "><i class="fas fa-plus"></i> Adicione instituições e escolas</button>
                                        </div>
                                        <h6 class="text-center">Instituições e escolas selecionadas *</h6>
                                        <div class="row mb-4">
                                            <div class="col">
                                                <ul id="listaPermissaoBiblioteca" class="list-style">

                                                </ul>
                                                <div class="invalid-feedback" style="display: block">{{ $errors->first('instituicaoBiblioteca') }}</div>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                                @endif

                            </div>
                        </form>
                    </div>
                </div>

        </div>


        <div class="modal fade" id="formEscolasTrumas" tabindex="0" role="dialog" aria-labelledby="formEscolasTrumas" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                    </button>
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloForm">Seleção de Instituições e Escolas</h5>
                    </div>
                    <div class="modal-body pb-0">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="filter">
                                    <form class="form-inline d-flex justify-content-end">
                                        <div class="input-group ml-1">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-search"></i>
                                                </div>
                                            </div>
                                            <input type="text" id="buscaNome" value="" placeholder="Nome da instituição" class="form-control" />
                                        </div>
                                        <div class="input-group ml-1">
                                            <input type="text" id="buscaEscola" value="" placeholder="Nome da escola" class="form-control" />
                                        </div>
                                        <div class="input-group ml-1">
                                            <select id="buscaTipo" class="form-control">
                                                <option value="-1">Tipo</option>
                                                @foreach($tipoInstituicao as $t)
                                                    <option value="{{$t->id}}">{{$t->titulo}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group ml-1">
                                            <button type="button" onclick="buscarEscolas('')" class="btn btn-secondary btn-sm">Localizar</button>
                                        </div>
                                        <div class="input-group ml-1">
                                            <button type="button" onclick="$('#buscaNome').val(''); $('#buscaEscola').val(''); $('#buscaTipo').val('-1'); buscarEscolas(''); " class="btn btn-secondary btn-sm">Limpar Filtros</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12" id="tabelaEscolasTurmas">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Finalizar</button>
                    </div>
                </div>
            </div>
        </div>

        <form id="formDadosEditar">
            @if(old('instituicao')!= '')
                @foreach(old('instituicao') as $e)
                    <input type="hidden" name="instituicao[]" value="{{$e}}">
                    @if(old('escola.'.$e) != '')
                        @foreach(old('escola.'.$e) as $t)
                            <input type="hidden" name="escola[{{$e}}][]" value="{{$t}}">
                        @endforeach
                    @else
                        <input type="hidden" name="escola[{{$e}}][]" value="0">
                    @endif
                @endforeach
            @elseif( isset($dados->tipo_permissao_realizar) && $dados->tipo_permissao_realizar==4)
                @foreach($dados->escolas as $t)
                    <input type="hidden" name="instituicao[]" value="{{$t->instituicao_id}}">
                    <input type="hidden" name="escola[{{$t->instituicao_id}}][]" value="{{$t->escola_id}}">
                @endforeach

            @endif
        </form>

        <form id="formDadosBiblioteca">
            @if(old('instituicaoBiblioteca')!= '')
                @foreach(old('instituicaoBiblioteca') as $e)
                    <input type="hidden" name="instituicaoBiblioteca[]" value="{{$e}}">
                    @if(old('escolaBiblioteca.'.$e) != '')
                        @foreach(old('escolaBiblioteca.'.$e) as $t)
                            <input type="hidden" name="escolaBiblioteca[{{$e}}][]" value="{{$t}}">
                        @endforeach
                    @else
                        <input type="hidden" name="escolaBiblioteca[{{$e}}][]" value="0">
                    @endif
                @endforeach
            @elseif( isset($dados->tipo_permissao_biblioteca) && $dados->tipo_permissao_biblioteca==4)
                @foreach($dados->escolasBiblioteca as $t)
                    <input type="hidden" name="instituicaoBiblioteca[]" value="{{$t->instituicao_id}}">
                    <input type="hidden" name="escolaBiblioteca[{{$t->instituicao_id}}][]" value="{{$t->escola_id}}">
                @endforeach
            @endif
        </form>

    </section>
    <script>
        ///////// seleciona instituicao e escola
        var tipoModal = '';
        $(document).ready(function(){
            $('#tipo_permissao_realizar').change();
            $('#tipo_permissao_biblioteca').change();

            @if( ( (isset($dados->escolas) && $dados->escolas) || old('instituicao') ))
                if($('#tipo_permissao_realizar').val() ==4) {
                    $.ajax({
                        url: '{{url('gestao/agenda/familia/getInstituicaoSelecionadas')}}?' + $('#formDadosEditar').serialize(),
                        type: 'post',
                        dataType: 'json',
                        data: {
                            _token: '{{csrf_token()}}'
                        },
                        success: function (data) {
                            $('#listaPermissao').html(data);
                        },
                        error: function () {
                            swal("", "Não foi possível carregar a lista de instituicoes selecionadas.", "error");
                        }
                    });
                }
            @endif

            @if( ( (isset($dados->escolasBiblioteca) && $dados->escolasBiblioteca) || old('instituicaoBiblioteca') ))
                if($('#tipo_permissao_biblioteca').val() ==4) {
                    form = $('#formDadosBiblioteca input[type=hidden][name*="Biblioteca"]').serialize();
                    form = form.split('Biblioteca').join('') + '&biblioteca=1';
                    $.ajax({
                        url: '{{url('gestao/agenda/familia/getInstituicaoSelecionadas')}}?' + form,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            _token: '{{csrf_token()}}'
                        },
                        success: function (data) {
                            $('#listaPermissaoBiblioteca').html(data);
                        },
                        error: function () {
                            swal("", "Não foi possível carregar a lista de instituicoes selecionadas.", "error");
                        }
                    });
                }
            @endif

        });

        function mudaVisualizacao(elemento) {
            valor = $(elemento).val();
            $('#btnEscolaTurma').hide();
            if(valor == 1){
                $('#listaPermissao').html('<li class="ui-state-default "><input type="hidden" name="instituicao[]" value="0"><input type="hidden" name="tipo" value=""> <i class="fas fa-check"></i><span class="m-2"></span> Todas as instituições e escolas da rede.</li>');
            }
            else if(valor == 2){
                $('#listaPermissao').html('<li class="ui-state-default "><input type="hidden" name="instituicao[]" value="0"><input type="hidden" name="tipo" value="publica"> <i class="fas fa-check"></i><span class="m-2"></span> Todas as instituições e escolas PÚBLICAS.</li>');
            }
            else if(valor == 3){
                $('#listaPermissao').html('<li class="ui-state-default "><input type="hidden" name="instituicao[]" value="0"><input type="hidden" name="tipo" value="privada"> <i class="fas fa-check"></i><span class="m-2"></span> Todas as instituições e escolas PRIVADAS.</li>');
            }
            else{
                $('#btnEscolaTurma').show();
                $('#listaPermissao').html('');
            }
        }

        function mudaVisualizacaoBiblioteca(elemento) {
            valor = $(elemento).val();
            $('#btnEscolaTurmaBiblioteca').hide();
            if(valor == 1){
                $('#listaPermissaoBiblioteca').html('<li class="ui-state-default "><input type="hidden" name="instituicaoBiblioteca[]" value="0"><input type="hidden" name="tipo" value=""> <i class="fas fa-check"></i><span class="m-2"></span> Todas as instituições e escolas da rede.</li>');
            }
            else if(valor == 2){
                $('#listaPermissaoBiblioteca').html('<li class="ui-state-default "><input type="hidden" name="instituicaoBiblioteca[]" value="0"><input type="hidden" name="tipo" value="publica"> <i class="fas fa-check"></i><span class="m-2"></span> Todas as instituições e escolas PÚBLICAS.</li>');
            }
            else if(valor == 3){
                $('#listaPermissaoBiblioteca').html('<li class="ui-state-default "><input type="hidden" name="instituicaoBiblioteca[]" value="0"><input type="hidden" name="tipo" value="privada"> <i class="fas fa-check"></i><span class="m-2"></span> Todas as instituições e escolas PRIVADAS.</li>');
            }
            else{
                $('#btnEscolaTurmaBiblioteca').show();
                $('#listaPermissaoBiblioteca').html('');
            }
        }

        $('#formEscolasTrumas').on('show.bs.modal', function () {
            $('#buscaNome').val('');
            ehProfessor = 1;
            buscarEscolas('');
        });

        $(document).on('click', '.page-link', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            buscarEscolas(page);
        });

        function buscarEscolas(page){
            var form = '';
            if(tipoModal == 0){
                form = $('#formRoteiro input[type=hidden][name!="imagem"]').serialize();

            }else{
                form = $('#formRoteiro input[type=hidden][name*="Biblioteca"]').serialize();
                form = form.split('Biblioteca').join('') + '&biblioteca=1';
            }

            $.ajax({
                url: '{{url('gestao/agenda/familia/getInstituicoesEscolas')}}?'+form,
                type: 'post',
                dataType: 'json',
                data: {
                    nome: $('#buscaNome').val(),
                    nomeEscola: $('#buscaEscola').val(),
                    tipo: $('#buscaTipo').val(),
                    page: page,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    $('#tabelaEscolasTurmas').html(data);
                },
                error: function() {
                    swal("", "Não foi possível carregar a lista de escolas.", "error");
                }
            });
        }

        function adicionaInstituicao(instituicaoId, escolaId){
            var quantidade = 0;
            if(tipoModal == 0){
                quantidade = $('#selecionadaInstituicao'+instituicaoId).length;
            }else if(tipoModal == 1){
                quantidade = $('#selecionadaInstituicaoBiblioteca'+instituicaoId).length;
            }

            if(quantidade == 0) {
                if(tipoModal == 0) {
                    nomeInst = $('#nomeInstituicao' + instituicaoId).html();
                    var add = '<li class="ui-state-default" id="selecionadaInstituicao' + instituicaoId + '">';
                    add += '<input type="hidden" name="instituicao[]" value="' + instituicaoId + '">';
                    add += '<b>' + nomeInst + '</b>';
                    add += '<p id="listaEscolasSelecionadasInstituicao' + instituicaoId + '"></p></li>';
                    $('#listaPermissao').append(add);
                }
                if(tipoModal == 1) {
                    nomeInst = $('#nomeInstituicao' + instituicaoId).html();
                    var add = '<li class="ui-state-default" id="selecionadaInstituicaoBiblioteca' + instituicaoId + '">';
                    add += '<input type="hidden" name="instituicaoBiblioteca[]" value="' + instituicaoId + '">';
                    add += '<b>' + nomeInst + '</b>';
                    add += '<p id="listaEscolasSelecionadasInstituicaoBiblioteca' + instituicaoId + '"></p></li>';
                    $('#listaPermissaoBiblioteca').append(add);
                }
            }
            if(escolaId == -1) {
                if(tipoModal == 0) {
                    $('#listaEscolasSelecionadasInstituicao' + instituicaoId).html('<input type="hidden" name="escola[' + instituicaoId + '][]" value="0"><span id="todasEscolasInstituicao' + instituicaoId + '" class="badge badge-secondary">Todas as escolas selecionadas.</span>');
                }
                if(tipoModal == 1) {
                    $('#listaEscolasSelecionadasInstituicaoBiblioteca' + instituicaoId).html('<input type="hidden" name="escolaBiblioteca[' + instituicaoId + '][]" value="0"><span id="todasEscolasInstituicaoBiblioteca' + instituicaoId + '" class="badge badge-secondary">Todas as escolas selecionadas.</span>');
                }
            }
            else{
                if(quantidade != 0){
                    if(tipoModal == 0) {
                        $('#todasEscolasInstituicao' + instituicaoId).remove();
                    }
                    if(tipoModal == 1) {
                        $('#todasEscolasInstituicaoBiblioteca' + instituicaoId).remove();
                    }
                }
                nomeEscola = $('#nomeEscola' + escolaId).html();
                if(tipoModal == 0) {
                    $('#listaEscolasSelecionadasInstituicao' + instituicaoId).append('<input type="hidden" name="escola[' + instituicaoId + '][]" value="' + escolaId + '"><span class="badge badge-light ml-2" id="selecionadaEscola' + escolaId + '">' + nomeEscola + '</span>');
                }
                if(tipoModal == 1) {
                    $('#listaEscolasSelecionadasInstituicaoBiblioteca' + instituicaoId).append('<input type="hidden" name="escolaBiblioteca[' + instituicaoId + '][]" value="' + escolaId + '"><span class="badge badge-light ml-2" id="selecionadaEscolaBiblioteca' + escolaId + '">' + nomeEscola + '</span>');
                }
            }

        }

        /////////////
        var parametrosFroala = {
            key: "{{config('app.froala')}}",
            attribution: false, // to hide "Powered by Froala"
            heightMin: 132,
            buttonsVisible: 4,
            placeholderText: '',
            language: 'pt_br',
            linkAlwaysBlank: true
        }



        $(document).ready(function() {

            new FroalaEditor('#froala', parametrosFroala);

            var selectEtapaAno = new SlimSelect({
                select: '.multipleEtapa',
                placeholder: 'Buscar',
                searchPlaceholder: 'Buscar',
                closeOnSelect: true,
                allowDeselectOption: true,
                selectByGroup: true,
            });

            var selectComponente = new SlimSelect({
                select: '.multipleComponente',
                placeholder: 'Buscar',
                searchPlaceholder: 'Buscar',
                closeOnSelect: true,
                allowDeselectOption: true,
                selectByGroup: true,
            });
            @if(auth()->user()->permissao == 'Z')
                var selectPerfil = new vanillaSelectBox(".multiplePerfil", {
                    "search": true ,
                    "placeHolder": "Selecione",
                });
            @endif
            /* configuracoes basicas do plugin de recortar imagem */
            var configuracao = {
                ratio: '4:3',
                crop: {
                    x: 400,
                    y: 300,
                    width: 400,
                    height: 300
                },
                download: false,
                label: '<label>Insira uma Imagem</label> <i class="fas fa-file h5"></i> <br>Tamanho da imagem: 400px X 300px ',
                buttonConfirmLabel: 'Ok',
            }

            /* carrega o plugin de recortar imagem */
            $(".myCropper").slim(configuracao);

            @if(old('existeImg',@$dados->capa))
                $('#logoCadastro').show();
                $('#novaLogo').hide();
            @else
                $('#logoCadastro').hide();
                $('#novaLogo').show();
            @endif

            ajaxRoteiros($('#formBuscaRoteiro').serialize(), 'limpar');
            atualizadaSortable();

            @if(old('roteiros') != '')
                ajaxRoteirosSelecionados($('#formBuscaRoteiro').serialize());
            @endif

        });



        function excluirLogo(){
            $('#logoCadastro').remove();
            $('#novaLogo').show();
        }

        var pagina = 1;
        function ajaxRoteiros(form, tipo)
        {
            url = '{{url('/gestao/roteiros/getRoteiroAjax')}}/?'+form+'&page='+pagina+'&full=1';
            $.get(url,function(retorno){
                if(tipo=='limpar')
                {
                    $('#roteiroBiblioteca').html(retorno.roteiro);
                    $('.exibindoRoteiros').html(retorno.exibindo);

                }
                else
                {
                    $('#roteiroBiblioteca').append(retorno.questao);
                }
                $('.totalRoteiro').html(retorno.total);

            })
        }

        function ajaxRoteirosSelecionados(form)
        {
            url = '{{url('/gestao/roteiros/getRoteiroSelecionadosAjax')}}/?'+form;
            $.get(url,function(retorno){
                $('#questaoAvaliacao').html(retorno);
                atualizadaSortable();
            })
        }

        function maisRoteiros()
        {
            pagina ++;
            exibindo = 30+parseInt($('.exibindoRoteiros').html());
            total = parseInt($('.totalRoteiro').html());
            if(exibindo > total)
                exibindo = total;
            $('.exibindoRoteiros').html(exibindo);
            ajaxRoteiros(1,'adicionar');
        }

        function adicionarRoteiro(id){
            $('#questaoAvaliacao').append($('.roteiro'+id));
            $('#roteirosParaSeremSalvos').append('<input id="roteiroSalvar'+id+'" type="hidden" name="roteiros[]" value="'+id+'">');
            $('#roteirosParaBusca').append('<input id="roteiroBusca'+id+'" type="hidden" name="notId[]" value="'+id+'">');
            atualizadaSortable()

        }
        function removerRoteiro(id){
            $('#roteiroBiblioteca').append($('.roteiro'+id));
            $('#roteiroSalvar'+id).remove();
            $('#roteiroBusca'+id).remove();
            atualizadaSortable()
        }

        function buscarRoteiros(){
            ajaxRoteiros($('#formBuscaRoteiro').serialize(), 'limpar');
        }

        function limparBusca(){
            $('#busca').val('');
            ajaxRoteiros($('#formBuscaRoteiro').serialize(), 'limpar');
        }

        function atualizadaSortable()
        {
            $('#questaoAvaliacao').sortable({
                update: function() {
                    var sort = $(this).sortable("toArray");
                    $('#roteirosParaSeremSalvos').html('');
                    sort.forEach(reordenaSelecionados);
                },
            });
        }
        function reordenaSelecionados(value, index, array){
            $('#roteirosParaSeremSalvos').append('<input id="roteiroSalvar'+value+'" type="hidden" name="roteiros[]" value="'+value+'">');
        }
    </script>
@stop
