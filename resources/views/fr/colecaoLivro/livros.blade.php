@extends('fr/master')
@section('content')
    <section class="section section-interna">
        <div class="container">            
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="filter">
                        <form class="form-inline d-flex justify-content-center" method="get" id="formPesquisa">
                            @if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')
                            <div class="input-group ml-1">
                                <select name="material" id="" class="form-control" onchange="alterarPesquisa(3)">
                                    <option value="">Tipo de Material</option>
                                    @foreach($tipo_livro as $tipo)
                                        @if(in_array($tipo, ['A', 'P', 'AA', 'AP']))
                                            <option @if(Request::input('material') == $tipo) selected @endif value="{{ $tipo }}">
                                                @if($tipo == 'A') Estudante
                                                @elseif($tipo == 'P') Docente
                                                @elseif($tipo == 'AA') Apoio Estudante
                                                @elseif($tipo == 'AP') Apoio Docente
                                                @endif
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            
                            <!-- Para adição de novos períodos necessário alterar a Services/Fr/LivroService.php na linha 204-->
                            <!-- 10 ao 21 = do 1 volume ao 12 volume  -->
                            @if(
                                (isset($pesquisa['periodo1']) && $pesquisa['periodo1'] == 1) 
                                || (isset($pesquisa['periodo2']) && $pesquisa['periodo2'] == 2) 
                                || (isset($pesquisa['periodo3']) && $pesquisa['periodo3'] == 3) 
                                || (isset($pesquisa['periodo4']) && $pesquisa['periodo4'] == 4)
                                || (isset($pesquisa['periodo5']) && $pesquisa['periodo5'] == 5)
                                || (isset($pesquisa['periodo6']) && $pesquisa['periodo6'] == 6)
                                || (isset($pesquisa['periodo7']) && $pesquisa['periodo7'] == 7)
                                || (isset($pesquisa['periodo8']) && $pesquisa['periodo8'] == 8)
                                || (isset($pesquisa['periodo9']) && $pesquisa['periodo9'] == 9)                        
                                || (isset($pesquisa['periodo10']) && $pesquisa['periodo10'] == 10)
                                || (isset($pesquisa['periodo11']) && $pesquisa['periodo11'] == 11)
                                || (isset($pesquisa['periodo12']) && $pesquisa['periodo12'] == 12)
                                || (isset($pesquisa['periodo13']) && $pesquisa['periodo13'] == 13)
                                || (isset($pesquisa['periodo14']) && $pesquisa['periodo14'] == 14)
                                || (isset($pesquisa['periodo15']) && $pesquisa['periodo15'] == 15)
                                || (isset($pesquisa['periodo16']) && $pesquisa['periodo16'] == 16)
                                || (isset($pesquisa['periodo17']) && $pesquisa['periodo17'] == 17)
                                || (isset($pesquisa['periodo18']) && $pesquisa['periodo18'] == 18)
                                || (isset($pesquisa['periodo19']) && $pesquisa['periodo19'] == 19)
                                || (isset($pesquisa['periodo20']) && $pesquisa['periodo20'] == 20)
                                || (isset($pesquisa['periodo21']) && $pesquisa['periodo21'] == 21)  
                                || (isset($pesquisa['periodo22']) && $pesquisa['periodo22'] == 22)
                                || (isset($pesquisa['periodo23']) && $pesquisa['periodo23'] == 23)
                                || (isset($pesquisa['periodo24']) && $pesquisa['periodo24'] == 24)                             
                                )

                                <?php
                                // Inicializando variáveis de controle
                                $tem_periodo = false;
                                $periodo_bimestre = false;
                                $periodo_semestre = false;
                                $periodo_trimestral = false;
                                $volume = false;
                                

                                // Iterando sobre os dados
                                foreach($dados as $d) {                                    
                                    if (!empty($d['periodo'])) { // Verifique se a estrutura é um array associativo
                                        $tem_periodo = true;
                                        if (in_array($d['periodo'], [1, 2, 3, 4])) {
                                            $periodo_bimestre = true;
                                        }
                                        if (in_array($d['periodo'], [5, 6])) {
                                            $periodo_semestre = true;
                                        }
                                        if (in_array($d['periodo'], [7, 8, 9])){
                                            $periodo_trimestral = true;
                                        }
                                        if (in_array($d['periodo'], [10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24])){
                                            $volume = true;
                                        }                                                                              
                                    }
                                }
                                ?>                                
                                @if($tem_periodo)
                                    <div class="input-group ml-1">
                                        <select name="periodo" id="pesquisaPeriodo" class="form-control" onchange="alterarPesquisa(0)">
                                            @if($periodo_bimestre)
                                                <option value="">Selecione o Bimestre</option>
                                                @if(isset($pesquisa['periodo1']) && $pesquisa['periodo1']==1)<option @if(Request::input('periodo') == 1) selected @endif value="1">1º Bimestre</option>@endif
                                                @if(isset($pesquisa['periodo2']) && $pesquisa['periodo2']==2)<option @if(Request::input('periodo') == 2) selected @endif value="2">2º Bimestre</option>@endif
                                                @if(isset($pesquisa['periodo3']) && $pesquisa['periodo3']==3)<option @if(Request::input('periodo') == 3) selected @endif value="3">3º Bimestre</option>@endif
                                                @if(isset($pesquisa['periodo4']) && $pesquisa['periodo4']==4)<option @if(Request::input('periodo') == 4) selected @endif value="4">4º Bimestre</option>@endif
                                            @elseif($periodo_semestre)
                                                <option value="">Selecione o Semestre</option>
                                                @if(isset($pesquisa['periodo5']) && $pesquisa['periodo5']==5)<option @if(Request::input('periodo') == 5) selected @endif value="5">1º Semestre</option>@endif
                                                @if(isset($pesquisa['periodo6']) && $pesquisa['periodo6']==6)<option @if(Request::input('periodo') == 6) selected @endif value="6">2º Semestre</option>@endif
                                            @elseif($periodo_trimestral)
                                                <option value="">Selecione o Trimestre</option>
                                                @if(isset($pesquisa['periodo7']) && $pesquisa['periodo7']==7)<option @if(Request::input('periodo') == 7) selected @endif value="7">1º Trimestre</option>@endif
                                                @if(isset($pesquisa['periodo8']) && $pesquisa['periodo8']==8)<option @if(Request::input('periodo') == 8) selected @endif value="8">2º Trimestre</option>@endif
                                                @if(isset($pesquisa['periodo9']) && $pesquisa['periodo9']==9)<option @if(Request::input('periodo') == 9) selected @endif value="9">3º Trimestre</option>@endif                                            
                                            @elseif($volume)
                                                @if(auth()->user()->permissao == 'A')
                                                    <option value="">Selecione o Volume</option>
                                                    @if(isset($pesquisa['periodo10']) && $pesquisa['periodo10']==10)<option @if(Request::input('periodo') == 10) selected @endif value="10">Volume 1</option>@endif
                                                    @if(isset($pesquisa['periodo11']) && $pesquisa['periodo11']==11)<option @if(Request::input('periodo') == 11) selected @endif value="11">Volume 2</option>@endif
                                                    @if(isset($pesquisa['periodo12']) && $pesquisa['periodo12']==12)<option @if(Request::input('periodo') == 12) selected @endif value="12">Volume 3</option>@endif
                                                    @if(isset($pesquisa['periodo13']) && $pesquisa['periodo13']==13)<option @if(Request::input('periodo') == 13) selected @endif value="13">Volume 4</option>@endif
                                                    @if(isset($pesquisa['periodo14']) && $pesquisa['periodo14']==14)<option @if(Request::input('periodo') == 14) selected @endif value="14">Volume 5</option>@endif
                                                    @if(isset($pesquisa['periodo15']) && $pesquisa['periodo15']==15)<option @if(Request::input('periodo') == 15) selected @endif value="15">Volume 6</option>@endif
                                                    @if(isset($pesquisa['periodo16']) && $pesquisa['periodo16']==16)<option @if(Request::input('periodo') == 16) selected @endif value="16">Volume 7</option>@endif
                                                    @if(isset($pesquisa['periodo17']) && $pesquisa['periodo17']==17)<option @if(Request::input('periodo') == 17) selected @endif value="17">Volume 8</option>@endif
                                                    @if(isset($pesquisa['periodo18']) && $pesquisa['periodo18']==18)<option @if(Request::input('periodo') == 18) selected @endif value="18">Volume 9</option>@endif
                                                    @if(isset($pesquisa['periodo19']) && $pesquisa['periodo19']==19)<option @if(Request::input('periodo') == 19) selected @endif value="19">Volume 10</option>@endif
                                                    @if(isset($pesquisa['periodo20']) && $pesquisa['periodo20']==20)<option @if(Request::input('periodo') == 20) selected @endif value="20">Volume 11</option>@endif
                                                    @if(isset($pesquisa['periodo21']) && $pesquisa['periodo21']==21)<option @if(Request::input('periodo') == 21) selected @endif value="21">Volume 12</option>@endif 
                                                @elseif(auth()->user()->permissao == 'P')
                                                    <option value="">Selecione o Unidade/Volume</option>
                                                    @if(isset($pesquisa['periodo10']) && $pesquisa['periodo10']==10)<option @if(Request::input('periodo') == 10) selected @endif value="10">Unidade/Volume 1</option>@endif
                                                    @if(isset($pesquisa['periodo11']) && $pesquisa['periodo11']==11)<option @if(Request::input('periodo') == 11) selected @endif value="11">Unidade/Volume 2</option>@endif
                                                    @if(isset($pesquisa['periodo12']) && $pesquisa['periodo12']==12)<option @if(Request::input('periodo') == 12) selected @endif value="12">Unidade/Volume 3</option>@endif
                                                    @if(isset($pesquisa['periodo13']) && $pesquisa['periodo13']==13)<option @if(Request::input('periodo') == 13) selected @endif value="13">Unidade/Volume 4</option>@endif
                                                    @if(isset($pesquisa['periodo14']) && $pesquisa['periodo14']==14)<option @if(Request::input('periodo') == 14) selected @endif value="14">Unidade/Volume 5</option>@endif
                                                    @if(isset($pesquisa['periodo15']) && $pesquisa['periodo15']==15)<option @if(Request::input('periodo') == 15) selected @endif value="15">Unidade/Volume 6</option>@endif
                                                    @if(isset($pesquisa['periodo16']) && $pesquisa['periodo16']==16)<option @if(Request::input('periodo') == 16) selected @endif value="16">Unidade/Volume 7</option>@endif
                                                    @if(isset($pesquisa['periodo17']) && $pesquisa['periodo17']==17)<option @if(Request::input('periodo') == 17) selected @endif value="17">Unidade/Volume 8</option>@endif
                                                    @if(isset($pesquisa['periodo18']) && $pesquisa['periodo18']==18)<option @if(Request::input('periodo') == 18) selected @endif value="18">Unidade/Volume 9</option>@endif
                                                    @if(isset($pesquisa['periodo19']) && $pesquisa['periodo19']==19)<option @if(Request::input('periodo') == 19) selected @endif value="19">Unidade/Volume 10</option>@endif
                                                    @if(isset($pesquisa['periodo20']) && $pesquisa['periodo20']==20)<option @if(Request::input('periodo') == 20) selected @endif value="20">Unidade/Volume 11</option>@endif
                                                    @if(isset($pesquisa['periodo21']) && $pesquisa['periodo21']==21)<option @if(Request::input('periodo') == 21) selected @endif value="21">Unidade/Volume 12</option>@endif 
                                                    @if(isset($pesquisa['periodo22']) && $pesquisa['periodo22']==22)<option @if(Request::input('periodo') == 22) selected @endif value="22">Unidade 1-2-3-4</option>@endif
                                                    @if(isset($pesquisa['periodo23']) && $pesquisa['periodo23']==23)<option @if(Request::input('periodo') == 23) selected @endif value="23">Unidade 5-6-7-8</option>@endif
                                                    @if(isset($pesquisa['periodo24']) && $pesquisa['periodo24']==24)<option @if(Request::input('periodo') == 24) selected @endif value="24">Unidade 9-10-11-12</option>@endif
                                                @else
                                                    <option value="">Selecione o Unidade/Volume</option>
                                                    @if(isset($pesquisa['periodo10']) && $pesquisa['periodo10']==10)<option @if(Request::input('periodo') == 10) selected @endif value="10">Unidade 1</option>@endif
                                                    @if(isset($pesquisa['periodo11']) && $pesquisa['periodo11']==11)<option @if(Request::input('periodo') == 11) selected @endif value="11">Unidade 2</option>@endif
                                                    @if(isset($pesquisa['periodo12']) && $pesquisa['periodo12']==12)<option @if(Request::input('periodo') == 12) selected @endif value="12">Unidade 3</option>@endif
                                                    @if(isset($pesquisa['periodo13']) && $pesquisa['periodo13']==13)<option @if(Request::input('periodo') == 13) selected @endif value="13">Unidade 4</option>@endif
                                                    @if(isset($pesquisa['periodo14']) && $pesquisa['periodo14']==14)<option @if(Request::input('periodo') == 14) selected @endif value="14">Unidade 5</option>@endif
                                                    @if(isset($pesquisa['periodo15']) && $pesquisa['periodo15']==15)<option @if(Request::input('periodo') == 15) selected @endif value="15">Unidade 6</option>@endif
                                                    @if(isset($pesquisa['periodo16']) && $pesquisa['periodo16']==16)<option @if(Request::input('periodo') == 16) selected @endif value="16">Unidade 7</option>@endif
                                                    @if(isset($pesquisa['periodo17']) && $pesquisa['periodo17']==17)<option @if(Request::input('periodo') == 17) selected @endif value="17">Unidade 8</option>@endif
                                                    @if(isset($pesquisa['periodo18']) && $pesquisa['periodo18']==18)<option @if(Request::input('periodo') == 18) selected @endif value="18">Unidade 9</option>@endif
                                                    @if(isset($pesquisa['periodo19']) && $pesquisa['periodo19']==19)<option @if(Request::input('periodo') == 19) selected @endif value="19">Unidade 10</option>@endif
                                                    @if(isset($pesquisa['periodo20']) && $pesquisa['periodo20']==20)<option @if(Request::input('periodo') == 20) selected @endif value="20">Unidade 11</option>@endif
                                                    @if(isset($pesquisa['periodo21']) && $pesquisa['periodo21']==21)<option @if(Request::input('periodo') == 21) selected @endif value="21">Unidade 12</option>@endif 
                                                    @if(isset($pesquisa['periodo22']) && $pesquisa['periodo22']==22)<option @if(Request::input('periodo') == 22) selected @endif value="22">Unidade 1-2-3-4</option>@endif
                                                    @if(isset($pesquisa['periodo23']) && $pesquisa['periodo23']==23)<option @if(Request::input('periodo') == 23) selected @endif value="23">Unidade 5-6-7-8</option>@endif
                                                    @if(isset($pesquisa['periodo24']) && $pesquisa['periodo24']==24)<option @if(Request::input('periodo') == 24) selected @endif value="24">Unidade 9-10-11-12</option>@endif
                                                @endif 
                                            @endif
                                        </select>
                                    </div>
                                @endif
                            @endif 

                            <div class="input-group ml-1">
                                <select name="ano" id="pesquisaAno" class="form-control" onchange="alterarPesquisa(2)">
                                    <option value="">Selecione o Ano</option>
                                    @if($ano_livro === null)
                                        <option value="">Não há anos disponíveis para esta coleção</option>
                                    @else
                                        @foreach($ano_livro as $ano)
                                            <option @if(Request::input('ano') == $ano['id']) selected @endif value="{{$ano['id']}}">{{$ano['titulo']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="input-group ml-1">
                                <select name="componente" id="pesquisaDisciplina" class="form-control" onchange="alterarPesquisa(3)">
                                    <option value="">Componente Curricular</option>
                                    @if($pesquisa['disciplinas']==null)
                                        <option value="">Selecione o Ano</option>
                                    @else
                                        @foreach($pesquisa['disciplinas'] as $d)
                                            <option @if(Request::input('componente')==$d->id) selected @endif value="{{$d->id}}">{{$d->titulo}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>                            
                            <div class="input-group ml-1">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </div>
                                <input type="text" placeholder="Procurar Conteúdo" value="{{Request::input('texto')}}" name="texto" onclick="pesquisaTexto(event)" class="form-control" />
                            </div>
                            <div class="input-group ml-1">
                                <button type="button" class="btn btn-secondary" onclick="window.location.href='{{Request::url()}}'">Limpar Filtros</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <h2 class="title-page"><a @if(Request::input('id')!='') href="{{url()->previous()}}" @else href="{{url('/colecao_livro')}}" @endif title="Voltar" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a> Livros Didáticos Digitais</h2>
            @if($pesquisa['disciplinas']!=null)
                <div class="row">
                    @foreach($pesquisa['disciplinas'] as $d)
                        @if($d->id != 1)
                        <div class="col-md-1 text-center">
                            <a href="javascript:void(0); " onclick="$('#pesquisaDisciplina option[value={{$d->id}}]').attr('selected','selected').change();">
                                <img width="70px" height="70px" class="img-fluid" src="{{config('app.cdn')}}/storage/icones_disciplinas/{{@strtolower($d->sigla)}}.webp">
                                    <p><small>{{$d->titulo}}</small></p>
                            </a>
                        </div>
                        @endif
                    @endforeach
                </div>
            @endif
            <div class="subtitle-page" style="margin-top: 10px">{{$colecao->nome}}</div>
            <div class="list-grid-menu">
                <form class="form-inline">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary p-2 no-list-grid-btn active">
                            <input type="radio" name="options" id="option1" autocomplete="off" checked>
                            <i class="fas fa-th-large"></i>
                        </label>
                        <label class="btn btn-secondary list-grid-btn">
                            <input type="radio" name="options" id="option2" autocomplete="off">
                            <i class="fas fa-list"></i>
                        </label>
                    </div>
                </form>
            </div>

            <div class="row section-grid">
                @foreach($dados as $d)
                    <div class="col-md-3 grid-item" id="divConteudo{{$d->id}}">
                        <a href="{{ url('/colecao_livro/livro/'.$d->id)}}" class="wrap">
                            <div class="card text-center">
                                <div class="card-header" style="background-color:transparent;height:200px;border-bottom:transparent;display:inline-block;text-align:center;width:260px;max-width:100%;">
                                    <img src="{{ config('app.cdn') }}/storage/livrodigital/{{$d->id}}/thumb_1.webp" style="max-height: 100%;object-fit: fill;width: auto;height:auto;max-width:100%;" />
                                </div>
                                <div class="card-body" style="height:150px;display:inline-flex;">
                                    <div class="text mb-2"><h6 class="title font-weight-bold" style="margin-top: 5px">{{$d->titulo}} </h6> <!--Lorem Ipsum Dolor lorem ipsum dolor--></div>
                                </div>
                                <div class="card-footer">

                                    <a href="{{ url('/colecao_livro/livro/'.$d->id)}}" alt="Ver conteúdo" title="Ver conteúdo" class="btn btn-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ url('/colecao_livro/livro/pdfview/'.$d->id)}}" alt="Exibir em tela inteira" title="Exibir em tela inteira" class="btn btn-secondary">
                                        <i class="fas fa-arrows-alt"></i>
                                    </a>

                                    @if($d->permissao_download==1)
                                        <a href="{{ url('/colecao_livro/download/livro/'.$d->id)}}" alt="Download" title="Download" class="btn btn-secondary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif

                                    @if(auth()->user()->permissao == 'Z')
                                        <a href="javascript:void(0)" class="btn btn-secondary" onclick="modalIframe('{{$d->titulo}}', {{$d->id}})" title="Listar páginas" >
                                            <i class="fas fa-book"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <nav class="mt-5" aria-label="Page navigation example">
                {{ $dados->appends(Request::all())->links() }}
            </nav>
        </div>
    </section>

    <div class="modal fade" id="formIframe" tabindex="-1" role="dialog" aria-labelledby="formIframe" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                </button>
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloForm">Escolha de Páginas</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 id="tituloIframe"></h5>
                        </div>
                    </div>
                    <div class="row" id="conteudoIframe">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">

        var idLivro = 0;

        $('.list-grid-btn').click(function() {
            $('.grid-item').addClass('list-grid');
        });

        $('.no-list-grid-btn').click(function() {
            $('.grid-item').removeClass('list-grid');
        });

        $('#formIframe').on('hidden.bs.modal', function () {
            idLivro = 0;
        });

        function modalIframe(titulo,id){
            $('#tituloIframe').html(titulo);
            $('#formIframe').modal('show');
            idLivro = id;
            buscaLista();
        }

        function buscaLista(page = ''){
            $.ajax({
                url: '{{url('colecao_livro/listaPaginas')}}',
                type: 'post',
                dataType: 'json',
                data: {
                    id : idLivro,
                    page : page,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    $('#conteudoIframe').html(data);
                },
                error: function(data) {
                    swal("", "Não foi possível carregar as páginas dos livros ", "error");
                }
            });
        }


        $(document).on('click', '.page-link', function(event){
            if(idLivro != 0) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                buscaLista(page)
            }
            else {
                return true;
            }
        });

        function alterarPesquisa(id)
        {
            if(id ==1){
                // $('#pesquisaDisciplina').val('');
                $('#pesquisaAno').val('');
            }
            /* if(id==2){
                 $('#pesquisaDisciplina').val('');
             }
             */
            $('#formPesquisa').submit()
        }

        function pesquisaTexto(e) {
            if (e.keyCode == 13) {
                $('#formPesquisa').submit()
            }
        }
        function  copiarLink(el,link){
            navigator.clipboard.writeText(link);
            var elemento = $(el);
            setTimeout(function(){
                elemento.tooltip("hide");
            }, 2000)
        }

    </script>
@stop

